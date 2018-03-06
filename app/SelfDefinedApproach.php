<?php
namespace NGAFID;

use Eloquent;

/**
 * NGAFID\SelfDefinedApproach
 *
 * @property int         $id
 * @property int|null    $airport_id
 * @property string|null $runway
 * @property string|null $nNumber
 * @property int|null    $flight
 * @property int|null    $fleet_id
 * @property string|null $fltDate
 * @property string|null $timeOfFinal
 * @property float|null  $actualGPA
 * @property float|null  $rsquared
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\SelfDefinedApproach
 *         approachData($fleet, $runway, $date)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\SelfDefinedApproach
 *         whereActualGPA($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\SelfDefinedApproach
 *         whereAirportId($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\SelfDefinedApproach
 *         whereFleetId($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\SelfDefinedApproach
 *         whereFlight($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\SelfDefinedApproach
 *         whereFltDate($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\SelfDefinedApproach
 *         whereId($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\SelfDefinedApproach
 *         whereNNumber($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\SelfDefinedApproach
 *         whereRsquared($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\SelfDefinedApproach
 *         whereRunway($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\SelfDefinedApproach
 *         whereTimeOfFinal($value)
 * @mixin \Eloquent
 */
class SelfDefinedApproach extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'stabilized_approach_self_defined';

    public function flightID()
    {
        $this->belongsTo('NGAFID\FlightID', 'flight', 'id');
    }

    public function scopeApproachData($query, $fleet, $runway, $date)
    {
        return $query->with('flightID')
            ->where('fltDate', $date)
            ->where('fleet_id', $fleet)
            ->where('airport_id', $runway);
    }
}
