@extends('NGAFID-master')

@section('cssScripts')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading"><b>Welcome {{$data['name']}}</b> <span class="pull-right">{{date("D M d, Y G:i A T")}}</span></div>

				<div class="panel-body">
                    <div class="col-md-12">
                        <div class="col-md-5">
                            <p><b>Account Type:</b> {{$data['type']}}</p>
                            <p><a href="{{ url('/profile') }}">View Profile</a></p>
                            <p><a href="{{ url('/profile/password') }}">Change Password</a></p>
                        </div>

                        <div class="col-md-5 col-md-offset-2">
                            <p><b>No. Flights:</b> {{$data['numFlights']}}</p>
                            <p><b>Flight Hours:</b> {{$data['flightHours']}}</p>
                        </div>
                    </div>

                    @foreach($data['stats'] as $stats)
                        @if($stats['aircraft_type'] == 'c172')
                            <div class="col-md-{{12/count($data['stats'])}}">
                                <br>
                                <p><b>Cessna 172 Exceedance</b></p>
                                <ul class="list-group">
                                    <li class="list-group-item col-md-12">
                                        <div class="col-md-10">
                                            <a href="#" data-toggle="popover" title="Excessive Speed" data-content="{{$data['c172']['excessiveSpeed']}}"><span class="glyphicon glyphicon-info-sign"></span></a>  Excessive Speed
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar {{$stats['ExcessiveSpeedLabel']}}" data-percentage="{{$stats['ExcessiveSpeed']}}" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </li>

                                    <li class="list-group-item col-md-12">
                                        <div class="col-md-10">
                                            <a href="#" data-toggle="popover" title="High CHT" data-content="{{$data['c172']['highCHT']}}"><span class="glyphicon glyphicon-info-sign"></span></a>  High CHT
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar {{$stats['HighCHTLabel']}}" data-percentage="{{$stats['HighCHT']}}" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </li>

                                    <li class="list-group-item col-md-12">
                                        <div class="col-md-10">
                                            <a href="#" data-toggle="popover" title="High Altitude" data-content="{{$data['c172']['highAltitude']}}"><span class="glyphicon glyphicon-info-sign"></span></a>  High Altitude
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar {{$stats['HighAltitudeLabel']}}" data-percentage="{{$stats['HighAltitude']}}" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </li>

                                    <li class="list-group-item col-md-12">
                                        <div class="col-md-10">
                                            <a href="#" data-toggle="popover" title="Low Fuel" data-content="{{$data['c172']['lowFuel']}}"><span class="glyphicon glyphicon-info-sign"></span></a>  Low Fuel
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar {{$stats['LowFuelLabel']}}" data-percentage="{{$stats['LowFuel']}}" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </li>

                                    <li class="list-group-item col-md-12">
                                        <div class="col-md-10">
                                            <a href="#" data-toggle="popover" title="Low Oil Pressure" data-content="{{$data['c172']['lowOilPress']}}"><span class="glyphicon glyphicon-info-sign"></span></a>  Low Oil Pressure
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar {{$stats['LowOilPressLabel']}}" data-percentage="{{$stats['LowOilPress']}}" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </li>

                                    <li class="list-group-item col-md-12">
                                        <div class="col-md-10">
                                            <a href="#" data-toggle="popover" title="Low Airspeed on Approach" data-content="{{$data['c172']['lowAirspeedApproach']}}"><span class="glyphicon glyphicon-info-sign"></span></a>  Low Airspeed on Approach
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar {{$stats['LowAirspeedApproachLabel']}}" data-percentage="{{$stats['LowAirspeedApproach']}}" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </li>

                                    <li class="list-group-item col-md-12">
                                        <div class="col-md-10">
                                            <a href="#" data-toggle="popover" title="Low Airspeed on Climb-out" data-content="{{$data['c172']['lowAirspeedClimbout']}}"><span class="glyphicon glyphicon-info-sign"></span></a>  Low Airspeed on Climb-out
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar {{$stats['LowAirspeedClimboutLabel']}}" data-percentage="{{$stats['LowAirspeedClimbout']}}" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        @elseif($stats['aircraft_type'] == 'c182')
                            <div class="col-md-{{12/count($data['stats'])}}">
                                <br>
                                <p><b>Cessna 182 Exceedance</b></p>
                                <ul class="list-group">

                                    <li class="list-group-item col-md-12">
                                        <div class="col-md-10">
                                            <a href="#" data-toggle="popover" title="Excessive Speed" data-content="{{$data['c182']['excessiveSpeed']}}"><span class="glyphicon glyphicon-info-sign"></span></a>  Excessive Speed
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar {{$stats['ExcessiveSpeedLabel']}}" data-percentage="{{$stats['ExcessiveSpeed']}}" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </li>

                                    <li class="list-group-item col-md-12">
                                        <div class="col-md-10">
                                            <a href="#" data-toggle="popover" title="High CHT" data-content="{{$data['c182']['highCHT']}}"><span class="glyphicon glyphicon-info-sign"></span></a>  High CHT
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar {{$stats['HighCHTLabel']}}" data-percentage="{{$stats['HighCHT']}}" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </li>

                                    <li class="list-group-item col-md-12">
                                        <div class="col-md-10">
                                            <a href="#" data-toggle="popover" title="High Altitude" data-content="{{$data['c182']['highAltitude']}}"><span class="glyphicon glyphicon-info-sign"></span></a>  High Altitude
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar {{$stats['HighAltitudeLabel']}}" data-percentage="{{$stats['HighAltitude']}}" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </li>

                                    <li class="list-group-item col-md-12">
                                        <div class="col-md-10">
                                            <a href="#" data-toggle="popover" title="Low Fuel" data-content="{{$data['c182']['lowFuel']}}"><span class="glyphicon glyphicon-info-sign"></span></a>  Low Fuel
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar {{$stats['LowFuelLabel']}}" data-percentage="{{$stats['LowFuel']}}" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </li>

                                    <li class="list-group-item col-md-12">
                                        <div class="col-md-10">
                                            <a href="#" data-toggle="popover" title="Low Oil Pressure" data-content="{{$data['c182']['lowOilPress']}}"><span class="glyphicon glyphicon-info-sign"></span></a>  Low Oil Pressure
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar {{$stats['LowOilPressLabel']}}" data-percentage="{{$stats['LowOilPress']}}" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </li>

                                    <li class="list-group-item col-md-12">
                                        <div class="col-md-10">
                                            <a href="#" data-toggle="popover" title="Low Airspeed on Approach" data-content="{{$data['c182']['lowAirspeedApproach']}}"><span class="glyphicon glyphicon-info-sign"></span></a>  Low Airspeed on Approach
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar {{$stats['LowAirspeedApproachLabel']}}" data-percentage="{{$stats['LowAirspeedApproach']}}" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </li>

                                    <li class="list-group-item col-md-12">
                                        <div class="col-md-10">
                                            <a href="#" data-toggle="popover" title="Low Airspeed on Climb-out" data-content="{{$data['c182']['lowAirspeedClimbout']}}"><span class="glyphicon glyphicon-info-sign"></span></a>  Low Airspeed on Climb-out
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar {{$stats['LowAirspeedClimboutLabel']}}" data-percentage="{{$stats['LowAirspeedClimbout']}}" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        @else
                            <div class="col-md-{{12/count($data['stats'])}}">
                                <br>
                                <p><b>General Aircraft Exceedance</b></p>
                                <ul class="list-group">
                                    <li class="list-group-item col-md-12">
                                        <div class="col-md-10">
                                            <a href="#" data-toggle="popover" title="Excessive Roll" data-content="{{$data['generic']['excessiveRoll']}}"><span class="glyphicon glyphicon-info-sign"></span></a>  Excessive Roll
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar {{$stats['ExcessiveRollLabel']}}" data-percentage="{{$stats['ExcessiveRoll']}}" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </li>

                                    <li class="list-group-item col-md-12">
                                        <div class="col-md-10">
                                            <a href="#" data-toggle="popover" title="Excessive Pitch" data-content="{{$data['generic']['excessivePitch']}}"><span class="glyphicon glyphicon-info-sign"></span></a>  Excessive Pitch
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar {{$stats['ExcessivePitchLabel']}}" data-percentage="{{$stats['ExcessivePitch']}}" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </li>

                                    <li class="list-group-item col-md-12">
                                        <div class="col-md-10">
                                            <a href="#" data-toggle="popover" title="Excessive Lateral Acceleration" data-content="{{$data['generic']['excessiveLateral']}}"><span class="glyphicon glyphicon-info-sign"></span></a>  Excessive Lateral Acceleration
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar {{$stats['ExcessiveLatLabel']}}" data-percentage="{{$stats['ExcessiveLat']}}" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </li>
                                    <li class="list-group-item col-md-12">
                                        <div class="col-md-10">
                                            <a href="#" data-toggle="popover" title="Excessive Vertical Acceleration" data-content="{{$data['generic']['excessiveVertical']}}"><span class="glyphicon glyphicon-info-sign"></span></a>  Excessive Vertical Acceleration
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar {{$stats['ExcessiveVertLabel']}}" data-percentage="{{$stats['ExcessiveVert']}}" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </li>
                                    <li class="list-group-item col-md-12">
                                        <div class="col-md-10">
                                            <a href="#" data-toggle="popover" title="Excessive Longitudinal Acceleration" data-content="{{$data['generic']['excessiveLongitudinal']}}"><span class="glyphicon glyphicon-info-sign"></span></a>  Excessive Longitudinal Acceleration
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar {{$stats['ExcessiveLonLabel']}}" data-percentage="{{$stats['ExcessiveLon']}}" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </li>
                                    <li class="list-group-item col-md-12">
                                        <div class="col-md-10">
                                            <a href="#" data-toggle="popover" title="Excessive VSI on Final" data-content="{{$data['generic']['excessiveVSI']}}"><span class="glyphicon glyphicon-info-sign"></span></a>  Excessive VSI on Final
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar {{$stats['ExcessiveVSILabel']}}" data-percentage="{{$stats['ExcessiveVSI']}}" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        @endif
                    @endforeach

                </div>
            </div>
		</div>
	</div>
</div>
@endsection

@section('jsScripts')
    <script type="text/javascript">
        setTimeout(function(){

            $('.progress-bar').each(function() {
                var me = $(this);
                var percentage = me.attr("data-percentage");

                var curPercentage = 0;

                var progress = setInterval(function() {
                    if (curPercentage >= percentage)
                    {
                        clearInterval(progress);
                    }
                    else
                    {
                        curPercentage += 0.1;
                        me.css('width', (curPercentage)+'%');
                    }

                    me.text((Number((curPercentage).toFixed(2)))+'%');

                }, 10);

            });

        },50);
        $(document).ready(function(){
            $('[data-toggle="popover"]').popover({
                placement : 'top'
            });
        });
    </script>
@endsection
