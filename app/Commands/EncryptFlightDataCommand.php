<?php
namespace NGAFID\Commands;

ini_set("memory_limit", "10240M");

use DB;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use NGAFID\CryptoSystem;
use NGAFID\FileUpload;
use NGAFID\FlightID;

class EncryptFlightDataCommand extends Command
    implements SelfHandling, ShouldBeQueued
{
    use InteractsWithQueue, SerializesModels;

    protected $fleetID;

    public function __construct($fleetID)
    {
        $this->fleetID = $fleetID;
    }

    public function handle()
    {
        $salt = getenv('STATIC_SALT');
        $encNnumber = '';

        // Get the public key
        $ngafidKey = CryptoSystem::where('fleet_id', '=', $this->fleetID)
            ->pluck(DB::raw("DECODE(ngafid_key, '$salt')"));

        // Loop through the n number and flight date
        $metaData = FlightID::where('fleet_id', '=', $this->fleetID)
            ->get(
                ['id', 'n_number', 'date']
            );

        foreach ($metaData as $meta) {
            $flightInfo = FlightID::find($meta['id']);
            $uploadsTable = FileUpload::where('flight_id', $meta['id'])
                ->first();

            // Encrypt the n_number in flight_id table
            $trimmedNnumber = trim($meta['n_number']);
            if ($trimmedNnumber) {
                openssl_public_encrypt(
                    $trimmedNnumber,
                    $encNnumber,
                    $ngafidKey
                );

                $flightInfo->n_number = DB::raw("NULL");
                $flightInfo->enc_n_number = DB::raw(
                    "COMPRESS('" . base64_encode($encNnumber) . "')"
                );
            }

            // Encrypt the day in flight_id table
            $trimmedDate = trim($meta['date']);
            if ($trimmedDate && !ends_with($trimmedDate, '00')) {
                $day = substr($trimmedDate, -2);
                $tmpDate = rtrim($trimmedDate, $day);
                openssl_public_encrypt($day, $encDate, $ngafidKey);

                $flightInfo->date = $tmpDate . '00';
                $flightInfo->enc_day = DB::raw(
                    "COMPRESS('" . base64_encode($encDate) . "')"
                );
            }

            $flightInfo->save();

            // Encrypt the filename and n_number in the log table
            if ($uploadsTable && $encNnumber !== '') {
                openssl_public_encrypt(
                    $uploadsTable->file_name,
                    $encFileName,
                    $ngafidKey
                );

                $uploadsTable->file_name = DB::raw(
                    "COMPRESS('" . base64_encode($encFileName) . "')"
                );

                $uploadsTable->n_number = DB::raw(
                    "COMPRESS('" . base64_encode($encNnumber) . "')"
                );

                $uploadsTable->save();
            }

            // If flight is a GAARD flight, then update the data in those tables
            if ($flightInfo->recorder_type === 'G') {
                // Encrypt the epoch and flight time in the GAARD tables
                $gaard = DB::selectOne(
                    "SELECT id, recording_start, recording_end, COALESCE(UNCOMPRESS(recording_start), 'N') AS 'enc_start',
                    COALESCE(UNCOMPRESS(recording_end), 'N') AS 'enc_end'
                    FROM mitre_flight_meta_data
                    WHERE flight_id = {$meta['id']}"
                );

                if ($gaard) {
                    // GAARD 1
                    $values = [];

                    if ($gaard->enc_start === 'N') {
                        $values[] = "`recording_start` = COMPRESS('"
                                    . base64_encode($gaard->recording_start)
                                    . "')";
                    }

                    if ($gaard->enc_end === 'N') {
                        $values[] = "`recording_end` = COMPRESS('"
                                    . base64_encode(
                                        $gaard->recording_end
                                    ) . "')";
                    }

                    if ($values) {
                        $sql = "UPDATE mitre_flight_meta_data SET " . implode(
                                ',',
                                $values
                            ) . " WHERE flight_id = {$meta['id']}";

                        DB::statement($sql);
                    }

                    // Update GPS data
                    $gpsData = DB::select(
                        "SELECT id, epoch, msg_timestamp, gps_time, COALESCE(UNCOMPRESS(epoch), 'N') AS 'enc_epoch',
                          COALESCE(UNCOMPRESS(msg_timestamp), 'N') AS 'enc_msg_timestamp', COALESCE(UNCOMPRESS(gps_time), 'N') AS 'enc_gps_time'
                        FROM mitre_gps_data WHERE meta_data_id = {$gaard->id}"
                    );

                    foreach ($gpsData as $gpsRow) {
                        $values = [];

                        if ($gpsRow->enc_epoch === 'N') {
                            $values[] = "`epoch` = COMPRESS('" . base64_encode(
                                    $gpsRow->epoch
                                ) . "')";
                        }

                        if ($gpsRow->enc_msg_timestamp === 'N') {
                            $values[] = "`msg_timestamp` = COMPRESS('"
                                        . base64_encode($gpsRow->msg_timestamp)
                                        . "')";
                        }

                        if ($gpsRow->enc_gps_time === 'N') {
                            $values[] = "`gps_time` = COMPRESS('"
                                        . base64_encode(
                                            $gpsRow->gps_time
                                        ) . "')";
                        }

                        if ($values) {
                            $sql = "UPDATE mitre_gps_data SET " . implode(
                                    ',',
                                    $values
                                ) . " WHERE id = {$gpsRow->id}";

                            DB::statement($sql);
                        }
                    }
                } else {
                    // GAARD 2

                    // Update GPS data
                    $gpsData = DB::select(
                        "SELECT id, epoch_time, COALESCE(UNCOMPRESS(epoch_time), 'N') AS 'enc_epoch_time'
                        FROM mitre_gaard2_loc WHERE flight = {$meta['id']}"
                    );

                    foreach ($gpsData as $gpsRow) {
                        if ($gpsRow->enc_epoch_time === 'N') {
                            $sql = "UPDATE mitre_gaard2_loc SET epoch_time = COMPRESS('"
                                   . base64_encode($gpsRow->epoch_time)
                                   . "') WHERE id = {$gpsRow->id}";

                            DB::statement($sql);
                        }
                    }

                    // Update AHRS data
                    $ahrsData = DB::select(
                        "SELECT id, epoch_time, COALESCE(UNCOMPRESS(epoch_time), 'N') AS 'enc_epoch_time'
                        FROM mitre_gaard2_ahrs WHERE flight = {$meta['id']}"
                    );

                    foreach ($ahrsData as $ahrsRow) {
                        if ($ahrsRow->enc_epoch_time === 'N') {
                            $sql = "UPDATE mitre_gaard2_ahrs SET epoch_time = COMPRESS('"
                                   . base64_encode($ahrsRow->epoch_time)
                                   . "') WHERE id = {$ahrsRow->id}";

                            DB::statement($sql);
                        }
                    }
                }
            }
        }
    }
}
