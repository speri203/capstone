<?php namespace NGAFID\Http\Controllers\Dashboard;

use NGAFID\Http\Controllers\Controller;
use NGAFID\FlightID;
use NGAFID\Aircraft;
use NGAFID\Fleet;


class DashboardController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Dashboard Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated.
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
        $fleet = new Fleet();
        $aircraft = new Aircraft();
        $flightIdTable = new FlightID();

        if (\Auth::check()) {
            $fleetID = \Auth::user()->org_id;
            $userType = \Auth::user()->user_type == 'G' ? 'GAARD' : 'NGAFID';

            $totalFlights = $fleet->find($fleetID)->flights()->count();
            $totalHours = $flightIdTable->totalFlightHours($fleetID)->get('duration')->first()->duration;
            $totalHours = sprintf('%02d:%02d:%02d', $totalHours / 3600, $totalHours / 60 % 60, $totalHours % 60);

            $totalAircraft = $aircraft->uniqueAircraft($fleetID)->count();

            $genericExceedances = [
                'excessiveRoll'         =>  'Excessive Roll is defined as bank angle in excess of 60 degrees.',
                'excessivePitch'        =>  'Excessive Pitch is defined as pitch in excess of 30 degrees.',
                'excessiveLateral'      =>  'Excessive Lateral Acceleration is defined as lateral G-Loads exceed +4.4 G\'s',
                'excessiveVertical'     =>  'Excessive Vertical Acceleration is defined as vertical G-Loads exceed +4.4 G\'s',
                'excessiveLongitudinal' =>  'Excessive Longitudinal Acceleration is defined as longitudinal G-Loads exceed +4.4 G\'s',
                'excessiveVSI'          =>  'Aircraft\'s vertical speed increases beyond -1000 FPM while on short final',
            ];

            $c172Exceedances = [
                'excessiveSpeed'        =>  'Excessive Speed is defined as indicated airspeed in excess of 163 knots.',
                'highCHT'               =>  'High Cylinder Head Temperature is defined as CHT in any cylinder greater than 500 degrees',
                'highAltitude'          =>  'High Altitude is defined as altitude above 12,800 feet MSL.',
                'lowFuel'               =>  'Low fuel is defined as combined fuel less than 8 gallons while aircraft is in flight.',
                'lowOilPress'           =>  'Low Oil Pressure is defined as engine oil pressure less than 20 PSI while aircraft is in flight.',
                'lowAirspeedApproach'   =>  'Low Airspeed on Approach is defined as the aircraft\'s Indicated Airspeed (IAS) decreases below 57 knots while on short final.',
                'lowAirspeedClimbout'   =>  'Low Airspeed on Climb-out is defined as the aircraft\'s Indicated Airspeed (IAS) decreases below 52 knots on climb-out',
            ];

            $c182Exceedances = [
                'excessiveSpeed'        =>  'Excessive Speed is defined as indicated airspeed in excess of 175 knots.',
                'highCHT'               =>  'High Cylinder Head Temperature is defined as CHT in any cylinder greater than 500 degrees',
                'highAltitude'          =>  'High Altitude is defined as altitude above 15,000 feet MSL.',
                'lowFuel'               =>  'Low fuel is defined as combined fuel less than 8 gallons while aircraft is in flight.',
                'lowOilPress'           =>  'Low Oil Pressure is defined as engine oil pressure less than 20 PSI while aircraft is in flight.',
                'lowAirspeedApproach'   =>  'Low Airspeed on Approach is defined as the aircraft\'s Indicated Airspeed (IAS) decreases below 57 knots while on short final.',
                'lowAirspeedClimbout'   =>  'Low Airspeed on Climb-out is defined as the aircraft\'s Indicated Airspeed (IAS) decreases below 55 knots on climb-out',
            ];

            $exceedanceStats = $flightIdTable->exceedanceStats($fleetID)->get();

            $excessiveRoll    = 0;
            $excessivePitch   = 0;
            $excessiveLat     = 0;
            $excessiveVert    = 0;
            $excessiveLon     = 0;
            $excessiveVSI     = 0;
            $totalFlightsByAircraft = $flightIdTable->totalFlightsByAircraft($fleetID)->get();

            $totalC172  = 0;
            $totalC182  = 0;
            $totalGeneric = $totalFlights;

            foreach ($totalFlightsByAircraft as $aircraft) {
                if ($aircraft->aircraft_id == 1) {
                    $totalC172 = $aircraft->total;
                } elseif ($aircraft->aircraft_id == 2) {
                    $totalC182 = $aircraft->total;
                }
            }

            $summary = [];
            $exeedances = ['ExcessiveSpeed', 'HighCHT', 'HighAltitude', 'LowFuel', 'LowOilPress', 'LowAirspeedApproach', 'LowAirspeedClimbout'];

            foreach ($exceedanceStats as $stats) {
                if (in_array($stats->aircraft_type, [1, 2])) {
                    $tmp = [];
                    $total = 0;

                    if ($stats->aircraft_type == 1) {
                        $tmp['aircraft_type'] = 'c172';
                        $total = $totalC172;
                    } elseif ($stats->aircraft_type == 2) {
                        $tmp['aircraft_type'] = 'c182';
                        $total = $totalC182;
                    }

                    foreach ($exeedances as $exceedance) {
                        $tmp[$exceedance] = $total > 0 ? $stats[$exceedance] / $total * 100 : 0;
                        if ($tmp[$exceedance] > 80) {
                            $tmp[$exceedance . 'Label'] = 'progress-bar-danger';
                        } elseif ($tmp[$exceedance] > 50) {
                            $tmp[$exceedance . 'Label'] = 'progress-bar-warning';
                        } else {
                            $tmp[$exceedance . 'Label'] = 'progress-bar-success';
                        }
                    }

                    $summary[] = $tmp;
                }

                $excessiveRoll    += $stats->ExcessiveRoll;
                $excessivePitch   += $stats->ExcessivePitch;
                $excessiveLat     += $stats->ExcessiveLat;
                $excessiveVert    += $stats->ExcessiveVert;
                $excessiveLon     += $stats->ExcessiveLon;
                $excessiveVSI     += $stats->ExcessiveVSI;
            }

            $other['aircraft_type'] = 'other';

            $other['ExcessiveRoll'] = $totalGeneric > 0 ? ($excessiveRoll / $totalGeneric) * 100 : 0;
            if ($other['ExcessiveRoll'] > 80) {
                $other['ExcessiveRollLabel'] = 'progress-bar-danger';
            } elseif ($other['ExcessiveRoll'] > 50) {
                $other['ExcessiveRollLabel'] = 'progress-bar-warning';
            } else {
                $other['ExcessiveRollLabel'] = 'progress-bar-success';
            }

            $other['ExcessivePitch'] = $totalGeneric > 0 ? ($excessivePitch / $totalGeneric) * 100 : 0;
            if ($other['ExcessivePitch'] > 80) {
                $other['ExcessivePitchLabel'] = 'progress-bar-danger';
            } elseif ($other['ExcessivePitch'] > 50) {
                $other['ExcessivePitchLabel'] = 'progress-bar-warning';
            } else {
                $other['ExcessivePitchLabel'] = 'progress-bar-success';
            }

            $other['ExcessiveLat'] = $totalGeneric > 0 ? ($excessiveLat / $totalGeneric) * 100 : 0;
            if ($other['ExcessiveLat'] > 80) {
                $other['ExcessiveLatLabel'] = 'progress-bar-danger';
            } elseif ($other['ExcessiveLat'] > 50) {
                $other['ExcessiveLatLabel'] = 'progress-bar-warning';
            } else {
                $other['ExcessiveLatLabel'] = 'progress-bar-success';
            }

            $other['ExcessiveVert'] = $totalGeneric > 0 ? ($excessiveVert / $totalGeneric) * 100 : 0;
            if ($other['ExcessiveVert'] > 80) {
                $other['ExcessiveVertLabel'] = 'progress-bar-danger';
            } elseif ($other['ExcessiveVert'] > 50) {
                $other['ExcessiveVertLabel'] = 'progress-bar-warning';
            } else {
                $other['ExcessiveVertLabel'] = 'progress-bar-success';
            }

            $other['ExcessiveLon'] = ($totalGeneric > 0) ? (($excessiveLon / $totalGeneric) * 100) : 0;
            if ($other['ExcessiveLon'] > 80) {
                $other['ExcessiveLonLabel'] = 'progress-bar-danger';
            } elseif ($other['ExcessiveLon'] > 50) {
                $other['ExcessiveLonLabel'] = 'progress-bar-warning';
            } else {
                $other['ExcessiveLonLabel'] = 'progress-bar-success';
            }

            $other['ExcessiveVSI'] = $totalGeneric > 0 ? ($excessiveVSI / $totalGeneric) * 100 : 0;
            if ($other['ExcessiveVSI'] > 80) {
                $other['ExcessiveVSILabel'] = 'progress-bar-danger';
            } elseif ($other['ExcessiveVSI'] > 50) {
                $other['ExcessiveVSILabel'] = 'progress-bar-warning';
            } else {
                $other['ExcessiveVSILabel'] = 'progress-bar-success';
            }

            $other['TotalFlights'] = $totalGeneric;
            $summary[] = $other;

            unset($exceedanceStats);

            $dashData = [
                'name'          => \Auth::user()->firstname . ' ' . \Auth::user()->lastname,
                'type'          => $userType,
                'username'      => \Auth::user()->email,
                'flightHours'   => $totalHours,
                'numFlights'    => $totalFlights,
                'numAircraft'   => $totalAircraft,
                'generic'       => $genericExceedances,
                'c172'          => $c172Exceedances,
                'c182'          => $c182Exceedances,
                'stats'         => $summary,
            ];

            return view('dashboard.dashboard')->with('data', $dashData);
        }

	}

    public function faq()
    {
        $userInfo   = \DB::select( \DB::raw("SELECT COUNT(`id`) AS 'total', user_type AS 'type' FROM user GROUP BY user_type") );

        $fleetInfo  = \DB::select( \DB::raw("SELECT COUNT(`id`) AS 'total' FROM organization WHERE org_type = 'F'") );

        /*$flightInfo = \DB::select( \DB::raw("SELECT o.org_type AS 'type', count(f.id) as 'uploads',
                    COALESCE(concat(floor(SUM( TIME_TO_SEC( f.`duration` ))/3600),\":\",LPAD(floor(SUM( TIME_TO_SEC( f.`duration` ))/60)%60, 2, 0),\":\",LPAD(SUM( TIME_TO_SEC( f.`duration` ))%60, 2, 0)), 'N/A') as 'hours'
                    FROM flight_id f LEFT OUTER JOIN organization o ON o.id = f.fleet_id
                    WHERE o.`org_type` IN ('F', 'O') GROUP BY o.`org_type`") );*/

        $stats = \DB::select('CALL `fdm_test`.`sp_GetStatistics`()');


        return view('dashboard.faq')->with(['userInfo' => $userInfo, 'fleetInfo' => $fleetInfo, 'statistics' => $stats]);
    }

}
