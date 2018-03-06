<?php namespace NGAFID\Http\Requests;

use NGAFID\FlightID;


class FlightIdRequest extends Request {

    public function authorize()
    {
        //verify if the user is the owner of the flight
        if ( ! \Auth::check() )
        {
            return false;
        }

        $routeParams = \Route::current()->parameters();

        $flightIdTable = new FlightID();
        $selectedFlight = $flightIdTable->find($routeParams['flights']);

        if ($selectedFlight->fleet_id != \Auth::user()->org_id)
        {
            return false;
        }
        return true;
    }

    public function rules()
    {
        $routeParams = \Route::current()->parameters();
        $flightID = $routeParams['flights'];
        $params = Request::all();

        $nNumber = $params['n_number'];
        $aircraft = $params['aircraft'];
        $date = explode(' ', $params['date']);
        $time = $date[1];
        $date = $date[0];
        $fleetID = \Auth::user()->org_id;

        return [
            'n_number'      => 'required|max:25|unique:flight_id,n_number,'.$flightID.',id,n_number,'.$nNumber.',date,'.$date.',time,'.$time.',aircraft_type,'.$aircraft, //NULL,NULL,time,'.$time.',date,'.$date.',fleet_id,'.$fleetID.',aircraft_type,'.$aircraft,
            'aircraft'      => 'required',
            'origin'        => 'max:7',
            'destination'   => 'max:7',
        ];
    }

    public function messages()
    {
        return[
            'n_number.unique'  => 'There is an existing flight with this information.',
        ];
    }

}