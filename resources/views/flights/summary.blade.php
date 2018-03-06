@extends('NGAFID-master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading"><b>Exceedance Monitoring</b> <span class="pull-right">{{date("D M d, Y G:i A T")}}</span></div>

                    <div class="panel-body">

                        <div class="row">
                            <?php
                                $excessiveRoll    = 0;
                                $excessivePitch   = 0;
                                $excessiveLat     = 0;
                                $excessiveVert    = 0;
                                $excessiveLon     = 0;
                                $excessiveVSI     = 0;
                            ?>

                            @foreach($data['stats'] as $stats)
                                @if($stats->aircraft_type == 1)
                                    <div class="col-md-12">
                                        <h4>Cessna 172 Exceedance</h4>
                                        <ul class="list-group">
                                            <li class="list-group-item">{{$data['c172']['excessiveSpeed']}}
                                                <a href="{{ route('flights/exceedance') }}?event=excessive-speed" title="View excessive speed events" class="glyphicon glyphicon-eye-open pull-right" style="padding-left: 4px;"></a>
                                                <span class="badge">{{$stats->ExcessiveSpeed}}</span>
                                            </li>
                                            <li class="list-group-item">{{$data['c172']['highCHT']}}
                                                <a href="{{ route('flights/exceedance') }}?event=high-cht" title="View high CHT events" class="glyphicon glyphicon-eye-open pull-right" style="padding-left: 4px;"></a>
                                                <span class="badge">{{$stats->HighCHT}}</span>
                                            </li>
                                            <li class="list-group-item">{{$data['c172']['highAltitude']}}
                                                <a href="{{ route('flights/exceedance') }}?event=high-altitude" title="View high altitude events" class="glyphicon glyphicon-eye-open pull-right" style="padding-left: 4px;"></a>
                                                <span class="badge">{{$stats->HighAltitude}}</span>
                                            </li>
                                            <li class="list-group-item">{{$data['c172']['lowFuel']}}
                                                <a href="{{ route('flights/exceedance') }}?event=low-fuel" title="View low fuel events" class="glyphicon glyphicon-eye-open pull-right" style="padding-left: 4px;"></a>
                                                <span class="badge">{{$stats->LowFuel}}</span>
                                            </li>
                                            <li class="list-group-item">{{$data['c172']['lowOilPress']}}
                                                <a href="{{ route('flights/exceedance') }}?event=low-oil-pressure" title="View low oil pressure events" class="glyphicon glyphicon-eye-open pull-right" style="padding-left: 4px;"></a>
                                                <span class="badge">{{$stats->LowOilPress}}</span>
                                            </li>
                                            <li class="list-group-item">{{$data['c172']['lowAirspeedApproach']}}
                                                <a href="{{ route('flights/exceedance') }}?event=low-airspeed-approach" title="View low airspeed on approach events" class="glyphicon glyphicon-eye-open pull-right" style="padding-left: 4px;"></a>
                                                <span class="badge">{{$stats->LowAirspeedApproach}}</span>
                                            </li>
                                            <li class="list-group-item">{{$data['c182']['lowAirspeedClimbout']}}
                                                <a href="{{ route('flights/exceedance') }}?event=low-airspeed-climbout" title="View low airspeed on climbout events" class="glyphicon glyphicon-eye-open pull-right" style="padding-left: 4px;"></a>
                                                <span class="badge">{{$stats->LowAirspeedClimbout}}</span>
                                            </li>
                                        </ul>
                                    </div>
                                @elseif($stats->aircraft_type == 2)
                                    <div class="col-md-12">
                                        <h4>Cessna 182 Exceedance</h4>
                                        <ul class="list-group">
                                            <li class="list-group-item">{{$data['c182']['excessiveSpeed']}}
                                                <a href="{{ route('flights/exceedance') }}?event=excessive-speed" title="View excessive speed events" class="glyphicon glyphicon-eye-open pull-right" style="padding-left: 4px;"></a>
                                                <span class="badge">{{$stats->ExcessiveSpeed}}</span>
                                            </li>
                                            <li class="list-group-item">{{$data['c182']['highCHT']}}
                                                <a href="{{ route('flights/exceedance') }}?event=high-cht" title="View high CHT events" class="glyphicon glyphicon-eye-open pull-right" style="padding-left: 4px;"></a>
                                                <span class="badge">{{$stats->HighCHT}}</span>
                                            </li>
                                            <li class="list-group-item">{{$data['c182']['highAltitude']}}
                                                <a href="{{ route('flights/exceedance') }}?event=high-altitude" title="View high altitude events" class="glyphicon glyphicon-eye-open pull-right" style="padding-left: 4px;"></a>
                                                <span class="badge">{{$stats->HighAltitude}}</span>
                                            </li>
                                            <li class="list-group-item">{{$data['c182']['lowFuel']}}
                                                <a href="{{ route('flights/exceedance') }}?event=low-fuel" title="View low fuel events" class="glyphicon glyphicon-eye-open pull-right" style="padding-left: 4px;"></a>
                                                <span class="badge">{{$stats->LowFuel}}</span>
                                            </li>
                                            <li class="list-group-item">{{$data['c182']['lowOilPress']}}
                                                <a href="{{ route('flights/exceedance') }}?event=low-oil-pressure" title="View low oil pressure events" class="glyphicon glyphicon-eye-open pull-right" style="padding-left: 4px;"></a>
                                                <span class="badge">{{$stats->LowOilPress}}</span>
                                            </li>
                                            <li class="list-group-item">{{$data['c182']['lowAirspeedApproach']}}
                                                <a href="{{ route('flights/exceedance') }}?event=low-airspeed-approach" title="View low airspeed on approach events" class="glyphicon glyphicon-eye-open pull-right" style="padding-left: 4px;"></a>
                                                <span class="badge">{{$stats->LowAirspeedApproach}}</span>
                                            </li>
                                            <li class="list-group-item">{{$data['c182']['lowAirspeedClimbout']}}
                                                <a href="{{ route('flights/exceedance') }}?event=low-airspeed-climbout" title="View low airspeed on climbout events" class="glyphicon glyphicon-eye-open pull-right" style="padding-left: 4px;"></a>
                                                <span class="badge">{{$stats->LowAirspeedClimbout}}</span>
                                            </li>
                                        </ul>
                                    </div>
                                @else
                                        <?php
                                            $excessiveRoll    += $stats->ExcessiveRoll;
                                            $excessivePitch   += $stats->ExcessivePitch;
                                            $excessiveLat     += $stats->ExcessiveLat;
                                            $excessiveVert    += $stats->ExcessiveVert;
                                            $excessiveLon     += $stats->ExcessiveLon;
                                            $excessiveVSI     += $stats->ExcessiveVSI;
                                        ?>
                                @endif
                            @endforeach

                            <div class="col-md-12">
                                <h4>General Aircraft Exceedance</h4>
                                <ul class="list-group">
                                    <li class="list-group-item">{{$data['generic']['excessiveRoll']}}
                                        <a href="{{ route('flights/exceedance') }}?event=excessive-roll" class="glyphicon glyphicon-eye-open pull-right" title="View excessive roll events" style="padding-left: 4px;"></a>
                                        <span class="badge">{{$excessiveRoll}}</span>
                                    </li>
                                    <li class="list-group-item">{{$data['generic']['excessivePitch']}}
                                        <a href="{{ route('flights/exceedance') }}?event=excessive-pitch" title="View excessive pitch events" class="glyphicon glyphicon-eye-open pull-right" style="padding-left: 4px;"></a>
                                        <span class="badge">{{$excessivePitch}}</span>
                                    </li>
                                    <li class="list-group-item">{{$data['generic']['excessiveLateral']}}
                                        <a href="{{ route('flights/exceedance') }}?event=excessive-lateral-acceleration" title="View excessive lateral acceleration events" class="glyphicon glyphicon-eye-open pull-right" style="padding-left: 4px;"></a>
                                        <span class="badge">{{$excessiveLat}}</span>
                                    </li>
                                    <li class="list-group-item">{{$data['generic']['excessiveVertical']}}
                                        <a href="{{ route('flights/exceedance') }}?event=excessive-vertical-acceleration" title="View excessive vertical acceleration events" class="glyphicon glyphicon-eye-open pull-right" style="padding-left: 4px;"></a>
                                        <span class="badge">{{$excessiveVert}}</span>
                                    </li>
                                    <li class="list-group-item">{{$data['generic']['excessiveLongitudinal']}}
                                        <a href="{{ route('flights/exceedance') }}?event=excessive-longitudinal-acceleration" title="View excessive longitudinal acceleration events" class="glyphicon glyphicon-eye-open pull-right" style="padding-left: 4px;"></a>
                                        <span class="badge">{{$excessiveLon}}</span>
                                    </li>
                                    <li class="list-group-item">{{$data['generic']['excessiveVSI']}}
                                        <a href="{{ route('flights/exceedance') }}?event=excessive-vsi-final" title="View excessive VSI on final events" class="glyphicon glyphicon-eye-open pull-right" style="padding-left: 4px;"></a>
                                        <span class="badge">{{$excessiveVSI}}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
