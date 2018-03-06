<?php
namespace NGAFID;

use Eloquent;

/**
 * NGAFID\StabilizedApproach
 *
 * @property int         $id
 * @property int|null    $airport_id
 * @property string|null $runway
 * @property string|null $nNumber
 * @property int|null    $flight
 * @property int|null    $fleet_id
 * @property float|null  $oat
 * @property string|null $fltDate
 * @property string|null $timeOfFinal
 * @property int|null    $timeOnFinal
 * @property float|null  $avgHAT
 * @property float|null  $maxHAT
 * @property float|null  $minHAT
 * @property float|null  $avgSpeed
 * @property float|null  $maxSpeed
 * @property float|null  $minSpeed
 * @property float|null  $avgCrsTrk
 * @property float|null  $maxCrsTrk
 * @property float|null  $minCrsTrk
 * @property float|null  $avgWind
 * @property float|null  $maxWind
 * @property float|null  $minWind
 * @property string|null $rollDirection
 * @property float|null  $timeInTurn
 * @property float|null  $startMSL
 * @property float|null  $endMSL
 * @property float|null  $deltaMSL
 * @property float|null  $meanMSL
 * @property float|null  $maxMSL
 * @property float|null  $minMSL
 * @property float|null  $startIAS
 * @property float|null  $endIAS
 * @property float|null  $deltaIAS
 * @property float|null  $meanIAS
 * @property float|null  $maxIAS
 * @property float|null  $minIAS
 * @property float|null  $startVSI
 * @property float|null  $endVSI
 * @property float|null  $deltaVSI
 * @property float|null  $maxVSI
 * @property float|null  $minVSI
 * @property float|null  $startPitch
 * @property float|null  $endPitch
 * @property float|null  $deltaPitch
 * @property float|null  $maxPitch
 * @property float|null  $minPitch
 * @property float|null  $startRoll
 * @property float|null  $endRoll
 * @property float|null  $deltaRoll
 * @property float|null  $maxRoll
 * @property float|null  $minRoll
 * @property float|null  $startRPM
 * @property float|null  $endRPM
 * @property float|null  $deltaRPM
 * @property float|null  $meanRPM
 * @property float|null  $maxRPM
 * @property float|null  $minRPM
 * @property float|null  $maxVertG
 * @property float|null  $minVertG
 * @property float|null  $maxLatG
 * @property float|null  $minLatG
 * @property float|null  $maxLonG
 * @property float|null  $minLonG
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         approachData($fleet, $runway, $params, $date)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         graphApproach($flight, $flightTime, $start, $end)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereAirportId($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereAvgCrsTrk($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereAvgHAT($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereAvgSpeed($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereAvgWind($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereDeltaIAS($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereDeltaMSL($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereDeltaPitch($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereDeltaRPM($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereDeltaRoll($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereDeltaVSI($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereEndIAS($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereEndMSL($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereEndPitch($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereEndRPM($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereEndRoll($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereEndVSI($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereFleetId($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereFlight($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereFltDate($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereId($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereMaxCrsTrk($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereMaxHAT($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereMaxIAS($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereMaxLatG($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereMaxLonG($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereMaxMSL($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereMaxPitch($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereMaxRPM($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereMaxRoll($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereMaxSpeed($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereMaxVSI($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereMaxVertG($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereMaxWind($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereMeanIAS($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereMeanMSL($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereMeanRPM($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereMinCrsTrk($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereMinHAT($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereMinIAS($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereMinLatG($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereMinLonG($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereMinMSL($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereMinPitch($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereMinRPM($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereMinRoll($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereMinSpeed($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereMinVSI($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereMinVertG($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereMinWind($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereNNumber($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereOat($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereRollDirection($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereRunway($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereStartIAS($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereStartMSL($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereStartPitch($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereStartRPM($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereStartRoll($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereStartVSI($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereTimeInTurn($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereTimeOfFinal($value)
 * @method static
 *         \Illuminate\Database\Eloquent\Builder|\NGAFID\StabilizedApproach
 *         whereTimeOnFinal($value)
 * @mixin \Eloquent
 */
class StabilizedApproach extends Eloquent
{
    protected $table = 'stabilized_approach';

    protected $maxVal = 30;

    protected $minVal = 20;

    protected $slowSpd = -5;

    protected $fastSpd = 10;

    protected $lowHAT = -5;

    protected $highHAT = 10;

    public function scopeApproachData($query, $fleet, $runway, $params, $date)
    {
        $date = explode('-', $date);
        $month = $date[1];
        $year = $date[0];

        switch ($params) {
            // cross track error
            case 'CTE':
                $fields = "avgCrsTrk,
                            CASE
                                WHEN ABS(avgCrsTrk) > {$this->maxVal} THEN 'rgba(223, 83, 83, .7)'
                                WHEN ABS(avgCrsTrk) > {$this->minVal} AND ABS(avgCrsTrk) <= {$this->maxVal} THEN 'rgba(255, 204, 0, .7)'
                                ELSE 'rgba(0, 153, 151, .7)'
                            END 'avgCrsTrkColor',
                            LEAST(ABS(minCrsTrk), ABS(maxCrsTrk)) AS 'minCrsTrk',
                            CASE
                                WHEN LEAST(ABS(minCrsTrk), ABS(maxCrsTrk)) > {$this->maxVal} THEN 'rgba(223, 83, 83, .7)'
                                WHEN LEAST(ABS(minCrsTrk), ABS(maxCrsTrk)) > {$this->minVal} AND LEAST(ABS(minCrsTrk), ABS(maxCrsTrk)) <= {$this->maxVal} THEN 'rgba(255, 204, 0, .7)'
                                ELSE 'rgba(0, 153, 151, .7)'
                            END 'minCrsTrkColor',
                            GREATEST(ABS(minCrsTrk), ABS(maxCrsTrk)) AS 'maxCrsTrk',
                            CASE
                                WHEN GREATEST(ABS(minCrsTrk), ABS(maxCrsTrk)) > {$this->maxVal} THEN 'rgba(223, 83, 83, .7)'
                                WHEN GREATEST(ABS(minCrsTrk), ABS(maxCrsTrk)) > {$this->minVal} AND GREATEST(ABS(minCrsTrk), ABS(maxCrsTrk)) <= {$this->maxVal} THEN 'rgba(255, 204, 0, .7)'
                                ELSE 'rgba(0, 153, 151, .7)'
                            END 'maxCrsTrkColor',
                            0 AS 'yValues'";
                break;

            //high/low - fast/slow
            case 'HLFS':
                $fields = "avgHAT,
                            CASE
                                WHEN avgWind < 2 THEN 'diamond'
                                WHEN avgWind < 10 THEN 'square'
                                ELSE 'triangle'
                            END 'tailWindSymbol',
                            CASE
                                WHEN (avgHAT NOT BETWEEN ({$this->lowHAT}*2) AND ({$this->highHAT}*2)) OR (avgSpeed NOT BETWEEN ({$this->slowSpd}*2) AND ({$this->fastSpd}*2)) THEN 'rgba(223, 83, 83, .7)'
                                WHEN (avgHAT NOT BETWEEN {$this->lowHAT} AND {$this->highHAT}) OR (avgSpeed NOT BETWEEN {$this->slowSpd} AND {$this->fastSpd}) THEN 'rgba(255, 204, 0, .7)'
                                ELSE 'rgba(0, 153, 151, .7)'
                            END 'symbolColor',
                            avgWind AS 'tailWind',
                            avgSpeed";
                break;
        }

        return $query->select(
            \DB::raw(
                "id AS 'rowID', nNumber, flight, fltDate, timeOfFinal,
                $fields,
                @tot:= (SELECT  COUNT(*)
                    FROM    stabilized_approach t2
                    WHERE   t2.flight = stabilized_approach.flight AND t2.airport_id = {$runway}
                ) AS 'total',
                @ctr:= IFNULL(@ctr, 0) + 1 AS 'apprNo',
	            IF (@ctr >= @tot, @ctr:= 0, @ctr:= @ctr)"
            )
        )
            ->where('fleet_id', '=', $fleet)
            ->where('airport_id', '=', $runway)
            ->whereRaw("YEAR(fltDate) = {$year}")
            ->whereRaw("MONTH(fltDate) = {$month}")
            ->orderBy('flight', 'ASC')
            ->orderBy('timeOfFinal', 'ASC');
    }

    public function scopeGraphApproach(
        $query,
        $flight,
        $flightTime,
        $start,
        $end
    ) {
        return \DB::select(
            \DB::raw(
                "SELECT AddTime('{$flightTime}', COALESCE(SEC_TO_TIME(FLOOR(time/1000)), 0)) AS time_sec, roll_attitude AS roll,
                    CASE
                        WHEN roll_attitude < 30 THEN 'rgba(0, 153, 151, .7)'
                        WHEN roll_attitude > 30 AND roll_attitude < 40 THEN 'rgba(255, 204, 0, .7)'
                        ELSE 'rgba(223, 83, 83, .7)'
                    END AS 'roll_color',
                    pitch_attitude AS pitch,
                    'black' AS 'pitch_color',
                    indicated_airspeed AS ias,
                    CASE
                        WHEN indicated_airspeed > 70 THEN 'red'
                        WHEN indicated_airspeed > 65 THEN 'yellow'
                        ELSE 'green'
                    END AS 'ias_color',
                    vertical_airspeed AS vsi,
                    CASE
                        WHEN vertical_airspeed < -1000 THEN 'red'
                        ELSE 'green'
                    END AS 'vsi_color'
                FROM main
                WHERE flight = {$flight}
                  AND `time` BETWEEN {$start} AND {$end}
                ORDER BY time_sec ASC;"
            )
        );
    }
}
