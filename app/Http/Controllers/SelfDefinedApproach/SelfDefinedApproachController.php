<?php namespace NGAFID\Http\Controllers\SelfDefinedApproach;

use NGAFID\Airports;
use NGAFID\SelfDefinedApproach;
use NGAFID\FlightID;
use NGAFID\Http\Controllers\Controller;
use Illuminate\Support\Str;

use Illuminate\Http\Request;

use Auth;
use Response;

class SelfDefinedApproachController extends Controller {


    public function __construct() {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        $selectedRunway = $request->get('runway');
        $selectedDate   = $request->get('mthYr', date('Y') . '-' . date('m'));

        $airports = ['' => 'Select Runway'];

        return view('selfDefinedApproach/index', [
            'airports' => $airports,
            'selectedRunway' => $selectedRunway,
            'date' => $selectedDate,
        ]);
    }

    public function flights(Request $request) {
        $runway = $request->get('runway');
        $date = $request->get('date');
        $gpa_low = $request->get('gpa_low');
        $gpa_high = $request->get('gpa_high');
        $flight_ids = $request->get('flight_id');

        $flights = FlightID::with('aircraft')
            ->whereIn('id', $flight_ids);

        return view('selfDefinedApproach/flights', [
            'runway' => $runway,
            'date' => $date,
            'gpa_low' => $gpa_low,
            'gpa_high' => $gpa_high,
            'data' => $flights->paginate(15),
        ]);
    }

    public function chart(Request $request) {
        if (!$request->json()) {
            // @TODO throw 404 error since the request is not a JSON request
        }

        $fleetID = Auth::user()->org_id;
        $runway = $request->get('runway');
        $date = explode('-', $request->get('date', date('Y') . '-' . date('m')));
        $year = $date[0];
        $month = $date[1];

        $approaches = SelfDefinedApproach::select('flight', 'actualGPA', 'rsquared')
            ->whereRaw("MONTH(fltDate) = {$month}")
            ->whereRaw("YEAR(fltDate) = {$year}")
            ->where('fleet_id', $fleetID)
            ->where('airport_id', $runway)
            ->get();

        $series = null;
        foreach ($approaches as $approach) {
            $series[] = [
                'x' => (float)$approach->actualGPA,
                'y' => 0,
                'id' => $approach->flight,
            ];
        }

        $series = $this->histogram($series, 0.5);

        return Response::json(['success' => true, 'data' => $series]);
    }

    /**
     * Get histogram data out of xy data
     * @param   {Array} data  Array of tuples [x, y]
     * @param   {Number} step Resolution for the histogram
     * @returns {Array}       Histogram data
     */
    private function histogram($data, $step) {
        $histo = [];
        $arr = [];

        foreach ($data as $datum) {
            $x = floor($datum['x'] / $step) * $step;
            if (!array_key_exists("$x", $histo)) {
                $histo["$x"] = ['count' => 0, 'flights' => []];
            }
            $histo["$x"]['count']++;
            $histo["$x"]['flights'][] = $datum['id'];
        }

        foreach ($histo as $k => $v) {
            $arr[] = ['x' => floatval($k), 'y' => $v['count'], 'ids' => $v['flights']];
        }

        usort($arr, function ($a, $b) {
            return $a['x'] - $b['x'];
        });

        return $arr;
    }
}
