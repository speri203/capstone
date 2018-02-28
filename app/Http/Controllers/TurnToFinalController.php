<?php namespace NGAFID\Http\Controllers;

use NGAFID\Http\Requests;
use NGAFID\Http\Controllers\Controller;
use DB;
use NGAFID\StabilizedApproach;
use Illuminate\Http\Request;

class TurnToFinalController extends Controller {

	public function start()
	{
		return view('turnToFinal');
	}

	public function runQuery(Request $request)
	{
		$flightNum = $request->input('flightNum');
		$flights = NGAFID\StabilizedApproach::where('nNumber',$flightNum)->get();
		echo "<pre>";
		foreach ($endTimes as $key => $value) {
			echo $value->timeOfFinal."\n";
		}
		echo "</pre>";
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
