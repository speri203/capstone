<!-- resources/views/partials/_flights-table.blade.php -->

<div id="no-more-tables" class="col-md-12">
    <table class="table table-hover table-condensed" style="border-collapse:collapse;margin:0px;">
        <thead>
        <tr>
            <th class="col-xs-1"></th>
            <th class="col-xs-2 text-left">Aircraft</th>
            <th class="col-xs-2 text-left">Route</th>
            <th class="col-xs-2 text-left">Date</th>
            <th class="col-xs-1 text-center">Time</th>
            <th class="col-xs-1 text-right">Duration</th>
            <th class="col-xs-3 text-center">&nbsp;</th>
        </tr>
        </thead>
    </table>
    <div class="div-table-content">
        <table class="table">
            <tbody>
            @if(count($data) == 0)
                <tr class="col-md-12 text-center">
                    <td class="text-center"><b></br>No data to display.</b></td>
                </tr>
            @endif
            @foreach ($data as $flight)
                <tr data-toggle="collapse" data-target="#expand{{$flight['id']}}" class="clickable">
                    <td class="col-xs-1" data-title="Details"><button class="btn btn-default btn-xs"><span class="glyphicon glyphicon-eye-open"></span></button></td>
                    <td class="col-xs-2 text-left text-nowrap" data-title="Aircraft">{{$flight->aircraft['aircraft name']}}</td>
                    <td class="col-xs-2 text-left text-nowrap" data-title="Origin">{{$flight['origin']}} &rArr; {{$flight['destination']}}</td>
                    <td class="col-xs-2 text-left text-nowrap" data-title="Date">{{$flight['date']}}</td>
                    <td class="col-xs-1 text-center text-nowrap" data-title="Time">{{$flight['time']}}</td>
                    <td class="col-xs-1 text-right text-nowrap" data-title="Duration">{{$flight['duration']}}&nbsp;</td>
                    <td class="col-xs-3 text-center text-nowrap" data-title="More Options">
                        <a href="{{URL::route('flights.edit', $flight['id']) }}" class="glyphicon glyphicon-pencil" title="Edit flight"></a> &nbsp;&nbsp;
                        <a href="{{URL::route('flights/chart', $flight['id']) }}" class="glyphicon glyphicon-stats" title="Flight stats"></a> &nbsp;&nbsp;
                        <div class="btn-group">
                            <a href="#" class="glyphicon glyphicon-download-alt" data-toggle="dropdown" title="Download flight"></a> &nbsp;&nbsp;
                            <ul class="dropdown-menu"  role="menu">
                                @if($flight['num_events'] > 0 && \Route::is('flights/event'))
                                    <li><a href="#download" class="open-DownloadModal" data-toggle="modal" data-id="{{URL::route('flights/download', $flight['id'] . '/csv' . '/'. $selected['event'] ) }}">CSV</a></li>
                                    <li><a href="#download" class="open-DownloadModal" data-toggle="modal" data-id="{{URL::route('flights/download', $flight['id'] . '/kml' . '/'. $selected['event'] ) }}">KML</a></li>
                                    <li><a href="#download" class="open-DownloadModal" data-toggle="modal" data-id="{{URL::route('flights/download', $flight['id'] . '/fdr' . '/'. $selected['event'] ) }}">X-Plane</a></li>
                                @else
                                    <li><a href="#" id="dwldFlight{{$flight['id']}}" data-link="{{URL::route('flights/download', $flight['id'] . '/csv') }}">CSV</a></li>
                                    <li><a href="#" id="dwldFlight{{$flight['id']}}" data-link="{{URL::route('flights/download', $flight['id'] . '/kml') }}">KML</a></li>
                                    <li><a href="#" id="dwldFlight{{$flight['id']}}" data-link="{{URL::route('flights/download', $flight['id'] . '/fdr') }}">X-Plane</a></li>
                                @endif
                            </ul>
                        </div>
                        <a href="#" id="replayFlight{{$flight['id']}}" data-link="{{URL::route('flights/load', $flight['id']) }}" class="glyphicon glyphicon-plane" title="Replay flight"></a>
                    </td>
                </tr>
                <tr>
                    <td colspan="12" class="hiddenRow">
                        <div class="collapse" id="expand{{$flight['id']}}">
                            <div class="col-md-3 col-md-offset-1">
                                <b>N Number: </b> {{$flight['n_number']}} <br>
                                <b>Aircraft:</b> {{$flight->aircraft['aircraft name'] . ' - ' . $flight->aircraft['year'] . ' ' . $flight->aircraft['make'] . ' ' . $flight->aircraft['model']}}<br>
                                <b>Edit Flight:</b> <a href="{{URL::route('flights.edit', $flight['id']) }}" class="glyphicon glyphicon-pencil" title="Edit flight"></a><br>
                            </div>
                            <div class="col-md-3 col-md-offset-1">
                                <b>View Charts:</b> <a href="{{URL::route('flights/chart', $flight['id']) }}" class="glyphicon glyphicon-stats"></a><br>
                                <b>Replay Flight:</b> <a href="#" id="replayFlight{{$flight['id']}}" data-link="{{URL::route('flights/load', $flight['id']) }}" class="glyphicon glyphicon-plane" title="Replay flight"></a>
                            </div>
                            <div class="col-md-3 col-md-offset-1">
                                <b>No. Exceedance:</b> {{$flight['num_events']}}<br>
                                @if($flight['num_events'] > 0 && \Route::is('flights/event'))
                                    <b>Exceedance Details:</b><a href="#summary" class="displaySummary" data-toggle="modal" data-link="{{URL::route('flights/download', $flight['id'] . '/data/'. $selected['event'] . '/30') }}"><span class="glyphicon glyphicon-list-alt"></span></a><br>
                                @endif
                                @if($flight['archived'] == 'N')
                                    <b>Archive Flight:</b> <a href="{{URL::route('flights/archive', $flight['id']) }}" class="glyphicon glyphicon-trash" title="Archive exceedance"></a><br>
                                @endif
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
