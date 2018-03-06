<?php
namespace NGAFID;

use Eloquent;

/**
 * NGAFID\Airports
 *
 * @property int         $id
 * @property string|null $AirportName
 * @property string|null $AirportCode
 * @property string|null $Runway
 * @property float|null  $pointALat
 * @property float|null  $pointALong
 * @property float|null  $pointBLat
 * @property float|null  $pointBLong
 * @property float|null  $pointCLat
 * @property float|null  $pointCLong
 * @property float|null  $pointDLat
 * @property float|null  $PointDLong
 * @property float|null  $touchdownLat
 * @property float|null  $touchdownLong
 * @property float|null  $extendedcenterlineLat
 * @property float|null  $extendedcenterlineLong
 * @property float|null  $runwayCourse
 * @property float|null  $glidepathAngle
 * @property int|null    $tdze
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Airports
 *         whereAirportCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Airports
 *         whereAirportName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Airports
 *         whereExtendedcenterlineLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Airports
 *         whereExtendedcenterlineLong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Airports
 *         whereGlidepathAngle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Airports
 *         whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Airports
 *         wherePointALat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Airports
 *         wherePointALong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Airports
 *         wherePointBLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Airports
 *         wherePointBLong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Airports
 *         wherePointCLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Airports
 *         wherePointCLong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Airports
 *         wherePointDLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Airports
 *         wherePointDLong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Airports
 *         whereRunway($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Airports
 *         whereRunwayCourse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Airports
 *         whereTdze($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Airports
 *         whereTouchdownLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NGAFID\Airports
 *         whereTouchdownLong($value)
 * @mixin \Eloquent
 */
class Airports extends Eloquent
{
    protected $table = 'airports';
}
