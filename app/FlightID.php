<?php
namespace NGAFID;

use DB;
use Eloquent;

/**
 * NGAFID\FlightID
 *
 * @property int
 *                    $id
 * @property string|null
 *                    $n_number
 * @property mixed|null
 *                    $enc_n_number
 * @property string
 *                    $time
 * @property string
 *                    $date
 * @property mixed|null
 *                    $enc_day
 * @property string|null
 *                    $origin
 * @property string|null
 *                    $destination
 * @property int
 *                    $fleet_id
 * @property int
 *                    $aircraft_type
 * @property string|null
 *                    $duration
 * @property-read \NGAFID\Aircraft
 *                         $aircraft
 * @property-read \NGAFID\Fleet
 *                         $fleet
 * @property-read \Illuminate\Database\Eloquent\Collection|\NGAFID\Main[]
 *                         $mainTableData
 * @property-read \Illuminate\Database\Eloquent\Collection|\NGAFID\SelfDefinedApproach[]
 *                $selfDefinedApproaches
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\FlightID
 *         exceedanceStats($fleet)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\FlightID
 *         flightDetails($fleet, $startDate = null, $endDate = null, $archived
 *         = '', $sort = null, $column = null, $duration = '00:00', $flightID =
 *         '')
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\FlightID
 *         totalFlightHours($fleet)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\FlightID
 *         totalFlightsByAircraft($fleet)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\FlightID
 *         whereAircraftType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\FlightID
 *         whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\FlightID
 *         whereDestination($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\FlightID
 *         whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\FlightID
 *         whereEncDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\FlightID
 *         whereEncNNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\FlightID
 *         whereFleetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\FlightID
 *         whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\FlightID
 *         whereNNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\FlightID
 *         whereOrigin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\FlightID
 *         whereTime($value)
 * @mixin \Eloquent
 */
class FlightID extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'flight_id';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'n_number',
        'enc_n_number',
        'time',
        'date',
        'enc_day',
        'fleet_id',
        'aircraft_type',
        'origin',
        'destination',
    ];

    /**************************************************************************
     * Model Scopes
     */

    public function scopeFlightDetails(
        $query,
        $fleet,
        $startDate = null,
        $endDate = null,
        $archived = '',
        $sort = null,
        $column = null,
        $duration = '00:00',
        $flightID = ''
    ) {
        $query->select(
            'flight_id.id',
            DB::raw(
                "COALESCE(UNCOMPRESS(enc_n_number), n_number) AS 'n_number'"
            ),
            'date',
            DB::raw("COALESCE(UNCOMPRESS(enc_day), '**') AS 'enc_day'"),
            'time',
            'origin',
            'destination',
            'duration',
            'aircraft name',
            'year',
            'make',
            'model',
            DB::raw("COALESCE(num_exceedances, 0) AS num_events"),
            DB::raw("main_exceedances.archived AS 'archived'")
        )
            ->distinct()
            ->leftJoin(
                'main_exceedances',
                'main_exceedances.flight',
                '=',
                'flight_id.id'
            )
            ->leftJoin(
                'aircraft_list',
                'aircraft_list.id',
                '=',
                'flight_id.aircraft_type'
            )
            ->where('flight_id.fleet_id', '=', $fleet);

        if ($archived !== '') {
            $query->where(
                DB::raw('main_exceedances.`archived`'),
                '=',
                DB::raw("'$archived'")
            );
        }

        if ($startDate != '') {
            $query->where(
                DB::raw('flight_id.`date`'),
                '>=',
                DB::raw("'startDate'")
            );
        }

        if ($endDate != '') {
            $query->where(
                DB::raw('flight_id.`date`'),
                '<=',
                DB::raw("'$endDate'")
            );
        }

        if ($column != '') {
            $query->where(
                DB::raw('main_exceedances.' . $column),
                '=',
                DB::raw("'Y'")
            );
        }

        if ($duration !== '00:00' && $sort === 5) {
            $query->where(
                DB::raw("TIME_FORMAT(flight_id.`duration`, '%H:%i')"),
                '>=',
                DB::raw("'$duration'")
            );
        }

        if ($flightID !== '' && $flightID > 0) {
            $query->where(DB::raw('flight_id.`id`'), '=', $flightID);
        }

        switch ($sort) {
            case 1:
                $query->orderBy('num_exceedances', 'DESC');
                break;

            case 2:
                $query->orderBy('date', 'ASC')
                    ->orderBy('time', 'ASC');
                break;

            case 3:
                $query->orderBy('destination', 'ASC');
                break;

            case 4:
                $query->orderBy('origin', 'ASC');
                break;

            case 5:
                $query->orderBy('duration', 'ASC');
                break;

            default:
                $query->orderBy('date', 'DESC')
                    ->orderBy('time', 'DESC');
        }

        return $query;
    }

    public function scopeExceedanceStats(
        $query,
        $fleet,
        $date = null,
        $aircraft = null
    ) {
        $query->select(
            DB::raw(
                "flight_id.aircraft_type,
                    CONCAT(aircraft_list.`aircraft name`, ' - ', COALESCE(aircraft_list.`year`, ''), ' ', COALESCE(aircraft_list.make, ''), ' ', COALESCE(aircraft_list.model, '')) AS 'aircraft',
                    SUM(CASE WHEN excessive_roll = 'Y' THEN 1 ELSE NULL END) AS 'ExcessiveRoll',
                    SUM(CASE WHEN excessive_pitch = 'Y' THEN 1 ELSE NULL END) AS 'ExcessivePitch',
                    SUM(CASE WHEN excessive_speed = 'Y' THEN 1 ELSE NULL END) AS 'ExcessiveSpeed',
                    SUM(CASE WHEN high_cht = 'Y' THEN 1 ELSE NULL END) AS 'HighCHT',
                    SUM(CASE WHEN high_altitude = 'Y' THEN 1 ELSE NULL END) AS 'HighAltitude',
                    SUM(CASE WHEN low_fuel = 'Y' THEN 1 ELSE NULL END) AS 'LowFuel',
                    SUM(CASE WHEN low_oil_pressure = 'Y' THEN 1 ELSE NULL END) AS 'LowOilPress',
                    SUM(CASE WHEN low_airspeed_on_approach = 'Y' THEN 1 ELSE NULL END) AS 'LowAirspeedApproach',
                    SUM(CASE WHEN excessive_lateral_acceleration = 'Y' THEN 1 ELSE NULL END) AS 'ExcessiveLat',
                    SUM(CASE WHEN excessive_vertical_acceleration = 'Y' THEN 1 ELSE NULL END) AS 'ExcessiveVert',
                    SUM(CASE WHEN excessive_longitudinal_acceleration = 'Y' THEN 1 ELSE NULL END) AS 'ExcessiveLon',
                    SUM(CASE WHEN low_airspeed_on_climbout = 'Y' THEN 1 ELSE NULL END) AS 'LowAirspeedClimbout',
                    SUM(CASE WHEN excessive_vsi_on_final = 'Y' THEN 1 ELSE NULL END) AS 'ExcessiveVSI'"
            )
        )
            ->join(
                'main_exceedances',
                'flight_id.id',
                '=',
                'main_exceedances.flight'
            )
            ->leftJoin(
                'aircraft_list',
                'aircraft_list.id',
                '=',
                'flight_id.aircraft_type'
            )
            ->where('flight_id.fleet_id', '=', $fleet)
            ->where('main_exceedances.archived', '=', DB::raw("'N'"));

        if ($aircraft) {
            $query->where('flight_id.aircraft_type', '=', $aircraft);
        }

        if ($date) {
            $query->where(
                DB::raw('YEAR(flight_id.`date`)'),
                '=',
                DB::raw("YEAR('{$date}')")
            )
                ->where(
                    DB::raw('MONTH(flight_id.`date`)'),
                    '=',
                    DB::raw("MONTH('{$date}')")
                );
        }

        $query->groupBy('aircraft_type');

        return $query;
    }

    public function scopeTotalFlightsByAircraft($query, $fleet)
    {
        return $query->select(
            DB::raw(
                "aircraft_type AS 'aircraft_id', COUNT(*) AS 'total', aircraft_list.`aircraft name` AS 'name', COUNT(flight_id.id) AS 'y'"
            )
        )
            ->leftJoin(
                'aircraft_list',
                'aircraft_list.id',
                '=',
                'flight_id.aircraft_type'
            )
            ->where('flight_id.fleet_id', '=', $fleet)
            ->groupBy('aircraft_type');
    }

    public function scopeTotalFlightHours($query, $fleet)
    {
        return $query->select(
            DB::raw("SUM(TIME_TO_SEC(duration)) AS 'duration'")
        )
            ->where('fleet_id', '=', $fleet);
    }

    public function scopeMonthlyFlightHours($query, $fleet)
    {
        return $query->select(
            DB::raw(
                "UNIX_TIMESTAMP(LAST_DAY(DATE(`date`))) * 1000 AS 'unixTime', SUM(TIME_TO_SEC(duration)) * 1000 AS 'duration'"
            )
        )
            ->where('fleet_id', '=', $fleet)
            ->groupBy(DB::raw('YEAR(`date`)'))
            ->groupBy(DB::raw('MONTH(`date`)'))
            ->havingRaw('unixTime > 0');
    }

    public function scopeTotalExceedances($query, $fleet)
    {
        return $query->select(DB::raw("SUM(num_exceedances) AS 'total'"))
            ->join(
                'main_exceedances',
                'flight_id.id',
                '=',
                'main_exceedances.flight'
            )
            ->where('fleet_id', '=', $fleet);
    }

    /**************************************************************************
     * Model Eloquent Relationships
     */

    public function fleet()
    {
        return $this->belongsTo('NGAFID\Fleet', 'fleet_id');
    }

    public function aircraft()
    {
        return $this->hasOne('NGAFID\Aircraft', 'id', 'aircraft_type');
    }

    public function mainTableData()
    {
        return $this->hasMany('NGAFID\Main', 'flight');
    }

    public function selfDefinedApproaches()
    {
        return $this->hasMany('NGAFID\SelfDefinedApproach', 'flight', 'id');
    }

    /*************************************************************************
     * Public Methods
     */

    public function hasDataInMainTable()
    {
        return $this->mainTableData()
            ->exists();
    }
}
