<li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
        Flights<span class="caret"></span>
    </a>
    <ul class="dropdown-menu" role="menu">
        <li>
            <a href="{{ URL::route('flights') }}">All Flights</a>
        </li>
        <li role="separator" class="divider"></li>
        <li>
            <a href="{{ URL::route('flights/event', 'excessive-roll') }}">
                Excessive Roll
            </a>
        </li>
        <li>
            <a href="{{ URL::route('flights/event', 'excessive-pitch') }}">
                Excessive Pitch
            </a>
        </li>
        <li>
            <a href="{{ URL::route('flights/event', 'excessive-lateral-acceleration') }}">
                Excessive Lateral Acceleration
            </a>
        </li>
        <li>
            <a href="{{ URL::route('flights/event', 'excessive-vertical-acceleration') }}">
                Excessive Vertical Acceleration
            </a>
        </li>
        <li>
            <a href="{{ URL::route('flights/event', 'excessive-longitudinal-acceleration') }}">
                Excessive Longitudinal Acceleration
            </a>
        </li>
        <li>
            <a href="{{ URL::route('flights/event', 'excessive-vsi-final') }}">
                Excessive VSI on Final
            </a>
        </li>

        <?php
        $fleet = Auth::user()->fleet;
        $aircraftTypes = NGAFID\Aircraft::uniqueAircraft($fleet->id)
            ->get()
            ->lists('id');
        ?>

        @if (in_array('1', $aircraftTypes) || in_array('2', $aircraftTypes))
            <li>
                <a href="{{ URL::route('flights/event', 'excessive-speed') }}">
                    Excessive Speed
                </a>
            </li>
            <li>
                <a href="{{ URL::route('flights/event', 'high-cht') }}">
                    High CHT
                </a>
            </li>
            <li>
                <a href="{{ URL::route('flights/event', 'high-altitude') }}">
                    High Altitude
                </a>
            </li>
            <li>
                <a href="{{ URL::route('flights/event', 'low-fuel') }}">
                    Low Fuel
                </a>
            </li>
            <li>
                <a href="{{ URL::route('flights/event', 'low-oil-pressure') }}">
                    Low Oil Pressure
                </a>
            </li>
            <li>
                <a href="{{ URL::route('flights/event', 'low-airspeed-approach') }}">
                    Low Airspeed on Approach
                </a>
            </li>
            <li>
                <a href="{{ URL::route('flights/event', 'low-airspeed-climbout') }}">
                    Low Airspeed on Climb-out
                </a>
            </li>
        @endif
    </ul>
</li>

<li>
    <a href="{{ url('/flights/trend') }}">Trends</a>
</li>

<li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
        Data Import<span class="caret"></span>
    </a>
    <ul class="dropdown-menu" role="menu">
        <li>
            <a href="{{ url('import/upload') }}">Import Flight Data</a>
        </li>
        <li>
            <a href="{{ url('import/status') }}">Import Status/History</a>
        </li>
    </ul>
</li>

@if ($fleet->isUND())
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
            Approach Analysis <span class="caret"></span>
        </a>
        <ul class="dropdown-menu" role="menu">
            <li>
                <a href="{{ url('approach/analysis') }}">Stabilized Approach</a>
            </li>
            {{--<li>--}}
                {{--<a href="{{ url('approach/selfdefined') }}">Self Defined</a>--}}
            {{--</li>--}}
        </ul>
    </li>
@endif
