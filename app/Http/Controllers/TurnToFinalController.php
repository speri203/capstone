<?php
/*
*Austin Holtz
*/ 

namespace NGAFID\Http\Controllers;

use NGAFID\Http\Requests;
use NGAFID\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;

use NGAFID\StabilizedApproach as SA;
use NGAFID\Main as Main;
use NGAFID\FlightID as FID;

class TurnToFinalController extends Controller {

	public function start()
	{
		return view('turnToFinal');
	}


	/**
	*Takes info from turnToFinal view, finds the flight the user was looking for and finds the lats and longs from the final approach.
	*@param request=getRequest from turnToFinal.blade.php
	*@return a view that will process the latitudes and longitudes from the flight into a cesium display
	*/
	public function runQuery(Request $request)
	{
		//initialize the output
		$output = "";
		
		//get the string of IDs from the request and convert it into an array
		$startDate = $request->start;
		$endDate = $request->end;
		$flights = FID::where('date','>=',$startDate)
						->where('date','<=',$endDate)
						->get();


		//for each flight ID in the array, add the spacial data from the final approach to the output
		foreach ($flights as $flight)
		{


			

			$flightID=$flight->id;
			//find the start time of the flight
			$startTime = FID::where('id',$flightID)->get()->toArray();
			//retrieve the time of the final approach
			$startTime = array_pop($startTime)['time'];
			

			//convert time to seconds from 00:00
			$startTimeInSeconds = $this->timeToSeconds($startTime);

			//find the time of the turn-to-final, convert it to seconds
			$finalInfo = SA::where('flight',$flightID)->get()->toArray();
			$finalInfo = array_pop($finalInfo);
			$timeOfFinal = $finalInfo['timeOfFinal'];

			//some tables are missing this data for a given flight. This skips those flights.
			if (!isset($timeOfFinal)) continue;
			if ($finalInfo['airport_id']!=$request->airport) continue;
			
			$tofInSeconds = $this->timeToSeconds($timeOfFinal);

			//find the time (in milliseconds) that we need to start pulling data from the database
			//ttf begin time = (total flight time - flight start time) * 1000
			$finalBeginTime = ($tofInSeconds-$startTimeInSeconds)*1000;
			
			//pull the time on final from the database. This tells us how many rows to pull
			$timeOnFinal = $finalInfo['timeOnFinal'];

			//pull lat and long data from main table. Limit to the value in timeOnFinal
			$data = Main::where('flight',$flightID)
								->where('time','>=',$finalBeginTime)
								->limit($timeOnFinal)->get();

			//create a string for the flight, add the longitudes and latitudes
			$flightStr = "";
			foreach ($data as $datum) {
				$flightStr .= "$datum->longitude,";
				$flightStr .= "$datum->latitude,";
				// $flightStr .= "$datum->msl_altitude,";
			}

			//add the array to the output. key is the flight id and the value is an array of points.
			// $output["f$flightID"]=$flightStr;
			$output .= "$flightStr ";
		}		

		// echo "<pre>";
		$json = json_encode($output);

		// echo "\n";
		// print_r(json_decode($json));
		// echo "</pre>";
		
		return view('turnToFinalDisplay')->withData($json);
	}

	private function timeToSeconds($inTime)
	{
		//inTime is provided as hh:mm:ss, so we split by ':'
		$timeArray = explode(":",$inTime);

		//convert the time to seconds. hh*60^2 + mm 8 60 + ss
		$seconds = $timeArray[0]*60**2+$timeArray[1]*60+$timeArray[2];
		return $seconds;
	}

	public function viewFlights(Request $request)
	{

		$startDate = $request->input('startDate');
		$endDate = $request->input('endDate');

		$flightList =  FID::where('date','>=',$startDate)->where('date','<=',$endDate)->get();

		return view('turnToFinal')->withFlights($flightList);

	}

	

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
