<?php namespace NGAFID\Http\Controllers;

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

		$output = array();

		// if ($request->flightIDs)
		// {	
		// 	echo "LOL";
		// }
			$idString = $request->flightIDs;
			$flightIDs = explode(',',$idString);

		foreach ($flightIDs as $flightID)
		{
			$startTime = FID::where('id',$flightID)->get()->toArray();
			$startTime = array_pop($startTime)['time'];
			$startTimeInSeconds = $this->timeToSeconds($startTime);

			$finalInfo = SA::where('flight',$flightID)->get()->toArray();
			$finalInfo = array_pop($finalInfo);
			$timeOfFinal = $finalInfo['timeOfFinal'];
			$tofInSeconds = $this->timeToSeconds($timeOfFinal);
			$timeOnFinal = $finalInfo['timeOnFinal'];
			$finalBeginTime = ($tofInSeconds-$startTimeInSeconds)*1000;

			$data = Main::where('flight',$flightID)
								->where('time','>=',$finalBeginTime)
								->limit($timeOnFinal)->get();

			$flightArray = array();
			foreach ($data as $datum) {
				$tempArr = array();
				array_push($tempArr, $datum->latitude);
				array_push($tempArr, $datum->longitude);
				array_push($flightArray, $tempArr);
			}

			$output[$flightID]=$flightArray;
		}		

		echo "<pre>";
		print_r($output);
		// echo "finalBeginTime: $finalBeginTime\n";
		// // print_r($arr=explode(':',$startTime));
		// // echo "\n";
		// // var_dump($seconds = $arr[0]*60**2+$arr[1]*60+$arr[2]);
		// // echo "\n";
		// echo "flightID: $flightID\n";
		// echo "startTime: $startTime\n";
		// echo "startTimeInSeconds: $startTimeInSeconds\n";
		// echo "$timeOfFinal\n";
		// echo "$tofInSeconds\n";
		// foreach ($flights as $key => $value) {
		// 	echo $value->time."\n";
		// }
		echo "</pre>";
	}

	private function timeToSeconds($inTime)
	{
		$timeArray = explode(":",$inTime);
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
