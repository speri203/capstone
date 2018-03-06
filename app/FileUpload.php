<?php
namespace NGAFID;

use DB;
use Eloquent;

/**
 * NGAFID\FileUpload
 *
 * @property int         $id
 * @property mixed|null  $file_name
 * @property string|null $path
 * @property int|null    $user_id
 * @property string      $upload_time
 * @property mixed|null  $n_number
 * @property int|null    $fleet_id
 * @property int|null    $aircraft_type
 * @property int|null    $total_num_of_data
 * @property int|null    $imported_num_of_data
 * @property string|null $import_time
 * @property string|null $import_notes
 * @property int         $error
 * @property bool|null   $is_submitted
 * @property int|null    $flight_id
 * @property string|null $dest_db
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\FileUpload
 *         importStatus($userID, $fleetID)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\FileUpload
 *         whereAircraftType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\FileUpload
 *         whereDestDb($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\FileUpload
 *         whereError($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\FileUpload
 *         whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\FileUpload
 *         whereFleetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\FileUpload
 *         whereFlightId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\FileUpload
 *         whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\FileUpload
 *         whereImportNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\FileUpload
 *         whereImportTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\FileUpload
 *         whereImportedNumOfData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\FileUpload
 *         whereIsSubmitted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\FileUpload
 *         whereNNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\FileUpload
 *         wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\FileUpload
 *         whereTotalNumOfData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\FileUpload
 *         whereUploadTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\FileUpload
 *         whereUserId($value)
 * @mixin \Eloquent
 */
class FileUpload extends Eloquent
{
    protected $table = 'fdmdm.uploaded_file';

    public $timestamps = false;

    protected $fillable = [
        'file_name',
        'path',
        'user_id',
        'upload_time',
        'n_number',
        'fleet_id',
        'aircraft_type',
        'is_submitted',
    ];

    public function scopeImportStatus($query, $userID, $fleetID)
    {
        return $query->select(
            DB::raw(
                "COALESCE(UNCOMPRESS(uploaded_file.n_number), uploaded_file.n_number, 'N/A') AS 'n_number'"
            ),
            DB::raw(
                "COALESCE(a.`aircraft name`, CONCAT(a.make, ' ', a.model)) AS 'aircraft'"
            ),
            DB::raw(
                "CASE IFNULL(uploaded_file.upload_time, '0000-00-00 00:00:00')
                            WHEN '0000-00-00 00:00:00' THEN 'N/A'
                            ELSE uploaded_file.upload_time
                        END AS 'uploaded'"
            ),
            DB::raw(
                "CASE
                            WHEN is_submitted = 1 THEN 'Yes'
                            ELSE 'No'
                        END AS 'submitted'"
            ),
            DB::raw(
                "CASE
                            WHEN (uploaded_file.total_num_of_data/uploaded_file.imported_num_of_data = 1 AND error = 0 AND is_submitted = 1) THEN 'Imported'
                            WHEN (error = 0 AND is_submitted = 1) THEN 'Pending'
                            ELSE 'Failed'
                        END AS 'status'"
            ),
            DB::raw("uploaded_file.import_notes AS 'notes'")
        )
            ->leftJoin(
                DB::raw('fdm_test.organization o'),
                'o.id',
                '=',
                'uploaded_file.fleet_id'
            )
            ->leftJoin(
                DB::raw('fdm_test.flight_id f'),
                'f.id',
                '=',
                'uploaded_file.flight_id'
            )
            ->leftJoin(
                DB::raw('fdm_test.aircraft_list a'),
                'a.id',
                '=',
                'uploaded_file.aircraft_type'
            )
            ->where(DB::raw('uploaded_file.user_id'), '=', $userID)
            ->where(
                DB::raw('uploaded_file.fleet_id'),
                '=',
                $fleetID
            )
            ->orderBy(DB::raw('uploaded_file.upload_time'), 'desc');
    }
}
