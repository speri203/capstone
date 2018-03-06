@extends('NGAFID-master')

@section('cssScripts')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">

    <style>
        .btn-group {
            float: none;
            display: inline-block;
        }

        .btn-group .dropdown-menu {
            min-width: 0 !important;
        }

        table {
            table-layout: fixed;
        }

        .div-table-content {
            height: 300px;
            overflow-y: auto;
        }

        .hiddenRow {
            padding: 0 !important;
        }

        div#spinner {
            display: none;
            width: 100px;
            height: 100px;
            position: fixed;
            top: 50%;
            left: 50%;
            text-align: center;
            margin-left: -50px;
            margin-top: -100px;
            z-index: 2;
            overflow: auto;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <b>My Flights - {{ $pageName }}</b>
                        <span class="pull-right">{{ date("D M d, Y G:i A T") }}</span>
                    </div>

                    <div class="panel-body">
                        @include('partials.errors')

                        {!! Form::open(['method' => 'GET', 'url' => $action, 'class' => 'form-horizontal']) !!}

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="col-md-4 control-label">
                                    Start Date
                                </label>
                                <div class="col-md-6 input-group">
                                    <input class="form-control" id="startDatepicker" type="text" name="startDate" value="{{ $selected['startDate'] }}" />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">
                                    End Date
                                </label>
                                <div class="col-md-6 input-group">
                                    <input class="form-control" id="endDatepicker" type="text" name="endDate" value="{{ $selected['endDate'] }}" />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="col-md-4 control-label">Filter:</label>
                                <div class="col-md-8">
                                    {!! Form::select('filter', ['' => 'All flights', 'E' => 'Flights with events', 'A' => 'Archived Flights'], $selected['filter'], ["class" => "form-control"]) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">
                                    Sort by:
                                </label>
                                <div class="col-md-8">
                                    {!! Form::select('sort', ['' => 'Select option', 1 => 'Exceedance (high-low)', 2 => 'Date (low-high)', 3 => 'Destination', 4 => 'Origin', 5 => 'Duration'], $selected['sortBy'], ["class" => "form-control", "id" => "sort"]) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            @if (Auth::user()->fleet->isUND())
                                <div class="form-group">
                                    <label class="col-xs-6 control-label">
                                        Flight ID
                                    </label>
                                    <div class="col-xs-6">
                                        {!! Form::text('flightID', $selected['flightID'], ["class" => "form-control"]) !!}
                                    </div>
                                </div>
                            @endif

                            <div class="form-group">
                                <label class="col-xs-6 control-label">
                                    No. Flights
                                    <i>
                                        <small>(per page)</small>
                                    </i>
                                </label>
                                <div class="col-xs-6">
                                    {!! Form::select('perPage', ['' => 'Select option', 20 => '20', 50 => '50', 100 => '100'], $selected['perPage'], ["class" => "form-control"]) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary btn-sm pull-right">
                                        Display
                                    </button>
                                </div>
                            </div>
                        </div>
                        {!! Form::hidden('duration', $selected['duration'], ['id' => 'duration']) !!}
                        {!! Form::close() !!}

                        <div id="no-more-tables" class="col-md-12">
                            <table class="table table-hover table-condensed" style="border-collapse:collapse;margin:0px;">
                                <thead>
                                    <tr>
                                        <th class="col-xs-1"></th>
                                        <th class="col-xs-2 text-left">
                                            Aircraft
                                        </th>
                                        <th class="col-xs-2 text-left">
                                            Route
                                        </th>
                                        <th class="col-xs-2 text-left">
                                            Date
                                        </th>
                                        <th class="col-xs-1 text-center">
                                            Time
                                        </th>
                                        <th class="col-xs-1 text-right">
                                            Duration
                                        </th>
                                        <th class="col-xs-3 text-center">
                                            &nbsp;
                                        </th>
                                    </tr>
                                </thead>
                            </table>
                            <div class="div-table-content">
                                <table class="table">
                                    <tbody>
                                        <?php // @formatter:off
                                            $fleet = Auth::user()->fleet;
                                            $showDecryptedData = Session::get('toggleEnc') === 'F'
                                                                 && count(Session::get('encrSK')) > 0;
                                        ?>

                                        @if (count($data) === 0)
                                            <tr class="col-md-12 text-center">
                                                <td class="text-center">
                                                    <b><br />No data to display.</b>
                                                </td>
                                            </tr>
                                        @endif
                                        @foreach ($data as $flight)
                                            <tr data-toggle="collapse" data-target="#expand{{ $flight['id'] }}" class="clickable">
                                                <td class="col-xs-1" data-title="Details">
                                                    <button class="btn btn-default btn-xs">
                                                        <span class="glyphicon glyphicon-eye-open"></span>
                                                    </button>
                                                </td>
                                                <td class="col-xs-2 text-left text-nowrap" data-title="Aircraft">
                                                    {{ $flight['aircraft name'] }}
                                                </td>
                                                <td class="col-xs-2 text-left text-nowrap" data-title="Origin">
                                                    {{ $flight['origin'] }} &rArr; {{ $flight['destination'] }}
                                                </td>
                                                <td class="col-xs-2 text-left text-nowrap" data-title="Date">
                                                    @if ($fleet->wantsDataEncrypted())
                                                        <?php $fltDate = substr($flight['date'], 0, 8); ?>
                                                        @if ($showDecryptedData)
                                                            <?php openssl_private_decrypt(base64_decode($flight['enc_day']), $decrDay, base64_decode(gzuncompress(Session::get('encrSK')))); ?>
                                                            {{ $fltDate . $decrDay }}
                                                        @else
                                                            {{ $fltDate . '**' }}
                                                        @endif
                                                    @else
                                                        {{ $flight['date'] }}
                                                    @endif
                                                </td>
                                                <td class="col-xs-1 text-center text-nowrap" data-title="Time">
                                                    {{ $flight['time'] }}
                                                </td>
                                                <td class="col-xs-1 text-right text-nowrap" data-title="Duration">
                                                    {{ $flight['duration'] }}
                                                    &nbsp;
                                                </td>
                                                <td class="col-xs-3 text-center text-nowrap" data-title="More Options">
                                                    <a href="{{ URL::route('flights.edit', $flight['id']) }}" class="glyphicon glyphicon-pencil" title="Edit flight"></a>
                                                    &nbsp;&nbsp;
                                                    <a href="{{ URL::route('flights/chart', $flight['id']) }}" class="glyphicon glyphicon-stats" title="Flight stats"></a>
                                                    &nbsp;&nbsp;
                                                    <div class="btn-group">
                                                        <a href="#" class="glyphicon glyphicon-download-alt" data-toggle="dropdown" title="Download flight"></a>
                                                        &nbsp;&nbsp;
                                                        <ul class="dropdown-menu" role="menu">
                                                            @if ($flight['num_events'] > 0 && Route::is('flights/event'))
                                                                <li>
                                                                    <a href="#download" class="open-DownloadModal" data-toggle="modal"
                                                                       data-id="{{ URL::route('flights/download', $flight['id'] . '/csv/'. $selected['event']) }}">
                                                                        CSV
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="#download" class="open-DownloadModal" data-toggle="modal"
                                                                       data-id="{{ URL::route('flights/download', $flight['id'] . '/kml/'. $selected['event']) }}">
                                                                        KML
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="#download" class="open-DownloadModal" data-toggle="modal"
                                                                       data-id="{{ URL::route('flights/download', $flight['id'] . '/fdr/'. $selected['event']) }}">
                                                                        X-Plane
                                                                    </a>
                                                                </li>
                                                            @else
                                                                <li>
                                                                    <a href="#" id="dwldFlight{{ $flight['id'] }}"
                                                                       data-link="{{ URL::route('flights/download', $flight['id'] . '/csv') }}">
                                                                        CSV
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="#" id="dwldFlight{{ $flight['id'] }}"
                                                                       data-link="{{ URL::route('flights/download', $flight['id'] . '/kml') }}">
                                                                        KML
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="#" id="dwldFlight{{ $flight['id'] }}"
                                                                       data-link="{{ URL::route('flights/download', $flight['id'] . '/fdr') }}">
                                                                        X-Plane
                                                                    </a>
                                                                </li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                    <a href="#" id="replayFlight{{ $flight['id'] }}" data-link="{{ URL::route('flights/load', $flight['id']) }}" class="glyphicon glyphicon-plane" title="Replay flight"></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="12" class="hiddenRow">
                                                    <div class="collapse" id="expand{{ $flight['id'] }}">
                                                        <div class="col-md-3 col-md-offset-1">
                                                            @if ($fleet->wantsDataEncrypted())
                                                                @if ($showDecryptedData)
                                                                    <?php openssl_private_decrypt(base64_decode($flight['n_number']), $decrNnumber, base64_decode(gzuncompress(Session::get('encrSK')))); ?>
                                                                    <b>
                                                                        N Number:
                                                                    </b> {{ $decrNnumber }}
                                                                    <br />
                                                                @else
                                                                    <b>
                                                                        N Number:
                                                                    </b> *****
                                                                    <br />
                                                                @endif
                                                            @else
                                                                <b>
                                                                    N Number:
                                                                </b> {{ $flight['n_number'] }}
                                                                <br />
                                                            @endif

                                                            <b>Aircraft:</b>
                                                            {{ "{$flight['aircraft name']} - {$flight['year']} {$flight['make']} {$flight['model']}" }}
                                                            <br />

                                                            <b>Edit Flight:</b>
                                                            <a href="{{ URL::route('flights.edit', $flight['id']) }}" class="glyphicon glyphicon-pencil" title="Edit flight"></a>
                                                            <br />
                                                        </div>
                                                        <div class="col-md-3 col-md-offset-1">
                                                            <b>View Charts:</b>
                                                            <a href="{{ URL::route('flights/chart', $flight['id']) }}" class="glyphicon glyphicon-stats"></a>
                                                            <br>
                                                            <b>
                                                                Replay Flight:
                                                            </b>
                                                            <a href="#" id="replayFlight{{ $flight['id'] }}" data-link="{{ URL::route('flights/load', $flight['id']) }}" class="glyphicon glyphicon-plane" title="Replay flight"></a>
                                                        </div>
                                                        <div class="col-md-3 col-md-offset-1">
                                                            <b>
                                                                No. Exceedance:
                                                            </b> {{ $flight['num_events'] }}
                                                            <br />
                                                            @if ($flight['num_events'] > 0 && Route::is('flights/event'))
                                                                <b>
                                                                    Exceedance Details:
                                                                </b>
                                                                <a href="#summary" class="displaySummary" data-toggle="modal" data-link="{{ URL::route('flights/download', $flight['id'] . '/data/'. $selected['event'] . '/30') }}">
                                                                    <span class="glyphicon glyphicon-list-alt"></span>
                                                                </a>
                                                                <br />
                                                            @endif
                                                            @if ($flight['archived'] == 'N')
                                                                <b>
                                                                    Archive Flight:
                                                                </b>
                                                                <a href="{{ URL::route('flights/archive', $flight['id']) }}" class="glyphicon glyphicon-trash" title="Archive exceedance"></a>
                                                                <br />
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
                    </div>
                    {!! str_replace('/?', '?', $data->appends(Request::only(['event', 'startDate', 'endDate', 'filter', 'sort', 'perPage', 'duration']))->render()) !!}

                    <div id="spinner">
                        <img src="{{ asset('images/loading.gif') }}" />
                    </div>

                    <div class="modal fade" id="summary" tableindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4>Exceedance Summary</h4>
                                </div>
                                <div class="modal-body">

                                </div>
                                <div class="modal-footer">
                                    <a href="#" class="btn btn-default" data-dismiss="modal">
                                        Close
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="download" tableindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4>Download Flight Data</h4>
                                </div>
                                <div class="modal-body">
                                    <p>
                                        This flight data has one or more
                                        exceedance(s). Download the replay for 30
                                        seconds
                                        (or 60 seconds) before and after the
                                        event. You may also download the entire
                                        flight.
                                    </p>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="option" value="30">+/- 30 Seconds
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="option" value="60">+/- 60 Seconds
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="option" value="0" checked="">Entire Flight
                                        </label>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <a href="#" class="btn btn-primary" id="dwldFlight" data-link="">
                                        Download
                                    </a>
                                    <a href="#" class="btn btn-default" data-dismiss="modal">
                                        Close
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="downloadStatus" tableindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-body"></div>
                                <div class="modal-footer">
                                    <a href="#" class="btn btn-default" data-dismiss="modal">
                                        Close
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="confirm-duration" title="Enter duration" style="display: none;">
                        <p>
                            <input id="hours" name="value" value="0" size='2' />
                            :
                            <input id="minutes" name="value" value="0" size="2" />
                            <i>(hh:mm)</i>
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('jsScripts')
    <script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>

    <script type="text/javascript">
        var datePickerOptions = {
            changeYear: true,
            dateFormat: 'yy-mm-dd'
        };

        (function ($) {
            $("#startDatepicker").datepicker(datePickerOptions);
            $("#endDatepicker").datepicker(datePickerOptions);

            // Generate dynamic url based on the flight ID and file type
            $('.open-DownloadModal').click(function () {
                var url = $(this).attr('data-id');
                $("#download .modal-footer #dwldFlight").attr('data-link', url);
            });

            $('.displaySummary').click(function () {
                var url = $(this).attr("data-link");
                var CSRF_TOKEN = $('input[name="_token"]').val();

                $.ajax({
                    url: url,
                    type: "GET",
                    data: {_token: CSRF_TOKEN },
                    success: function (data) {
                        // Show summary in modal
                        if (data.hasOwnProperty('data')) {
                            if (data.data.found === false) {
                                $('#summary .modal-body').html('<p>There was a problem retrieving information for this exceedance.</p>');
                            } else {
                                $('#summary .modal-body').html('<p>' + data.data.file + '</p>');
                            }
                        }
                    }
                });
            });

            $("a[id^=dwldFlight]").click(function () {
                var duration = $('input[name="option"]:checked').val();
                var url = $(this).attr("data-link");
                var CSRF_TOKEN = $('input[name="_token"]').val();

                if (!duration) {
                    duration = 0;
                }

                showProgress();
                jQuery.noConflict();
                $('#download').modal('hide');
                $('#downloadStatus').modal('hide');

                $.ajax({
                    url: url + '/' + duration,
                    type: "GET",
                    data: {_token: CSRF_TOKEN },
                    success: function (data) {
                        hideProgress();

                        // Show message in modal
                        if (data.hasOwnProperty('data')) {
                            if (data.data.found == false) {
                                $('#downloadStatus .modal-body').html('<p>There was a problem retrieving information for this flight due to invalid data.</p>');
                            } else {
                                $('#downloadStatus .modal-body').html('<a id="dwldLink" href="' + data.data.file + '">Download File</a>');
                            }
                        }
                        $('#downloadStatus').modal('show');
                    }
                });
            });

            $('#sort').change(function () {
                if ($(this).val() == "5") {

                    $("#confirm-duration").dialog({
                        resizable: false,
                        height: 160,
                        modal: true,
                        buttons: {
                            "OK": function () {
                                $(this).dialog("close");

                                var mins = parseInt($('#minutes').val());
                                var hrs = parseInt($('#hours').val());

                                if (isNaN(mins) || mins < 0) {
                                    mins = "00";
                                }

                                if (isNaN(hrs) || hrs < 0) {
                                    hrs = "00";
                                }

                                $("#duration").val(pad(hrs, 2) + ":" + pad(mins, 2));
                            },
                            Cancel: function () {
                                $(this).dialog("close");
                                $("#sort").val("");
                            }
                        }
                    });
                } else {
                    $("#duration").val("00:00");
                }
            });

            $('#minutes').spinner({
                numberFormat: "d2",
                spin: function (event, ui) {
                    if (ui.value >= 60) {
                        $(this).spinner('value', ui.value - 60);
                        $('#hours').spinner('stepUp');
                        return false;
                    } else if (ui.value < 0) {
                        $(this).spinner('value', ui.value + 60);
                        $('#hours').spinner('stepDown');
                        return false;
                    }
                }
            });

            $('#hours').spinner({
                numberFormat: "d2",
                min: 0
            });

            $("a[id^=replayFlight]").click(function () {
                showProgress();
                jQuery.noConflict();
                var url = $(this).attr("data-link");
                var CSRF_TOKEN = $('input[name="_token"]').val();

                $.ajax({
                    url: url,
                    type: "GET",
                    data: {_token: CSRF_TOKEN },
                    success: function (data) {
                        hideProgress();

                        // Show message in modal
                        if (data.hasOwnProperty('data')) {
                            if (data.data.found == false) {
                                $('#downloadStatus .modal-body').html('<p>There was a problem retrieving the replay for this flight due to invalid data.</p>');
                                $('#downloadStatus').modal('show');
                            } else {
                                var replayLink = url.replace("load", "replay");
                                window.open(replayLink, "popupWindow");
                            }
                        }

                    },
                    error: function () {
                        hideProgress();
                        $('#downloadStatus .modal-body').html('<p>There was a problem retrieving the replay for this flight due to invalid data.</p>');
                        $('#downloadStatus').modal('show');
                    }
                });
            });


            var spinnerVisible = false;

            function showProgress() {
                if (!spinnerVisible) {
                    $("div#spinner").fadeIn("fast");
                    spinnerVisible = true;
                }
            }

            function hideProgress() {
                if (spinnerVisible) {
                    $("div#spinner").fadeOut("fast");
                    spinnerVisible = false;
                }
            }
        })(jQuery);

        $(window).on('hashchange', function (e) {
            history.replaceState("", document.title, e.originalEvent.oldURL);
        });

//        $(document).on("click", ".open-DownloadModal", function () {
//            var url = $(this).attr('data-id');
//            $("#download .modal-footer #dwldFlight").attr('data-link', url);
//        });

        function pad(str, max) {
            str = str.toString();
            return str.length < max ? pad("0" + str, max) : str;
        }
    </script>
@endsection
