@extends('NGAFID-master')

@section('cssScripts')
    <style>
        th {
            white-space: pre-line;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading col-xs-12">
                        <b>Frequently Asked Questions</b>
                        <span class="pull-right">{{ date("D M d, Y G:i A T") }}</span>
                    </div>

                    <div class="panel-body">
                        <p class="col-md-12">
                            The NGAFID is a joint industry-FAA initiative
                            designed to bring voluntary Flight Data Monitoring
                            (FDM) to General Aviation. Users participate in two
                            ways. (1) Data is uploaded from either their
                            on-board avionics (for example, a G1000 or data
                            recorder) or (2) using a newly developed mobile app
                            — on their smart phone or tablet.

                            <br><br>

                            This is a short list of our most frequently asked
                            questions.
                        </p>
                        <div class="container">
                            <ul class="media-list col-md-12">
                                <li class="media">
                                    <div class="media-left">
                                        <a href="#">
                                            <span class="glyphicon glyphicon-th"></span>
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <p class="media-heading text-info">
                                            <b>How Many Users Voluntarily
                                               Participate?</b>
                                        </p>
                                        <p>
                                            @foreach ($userInfo as $info)
                                                @if ($info->type === 'N')
                                                    No. Web
                                                    Accounts: {{ $info->total }}
                                                @elseif ($info->type === 'G')
                                                    <br>No. Mobile Accounts
                                                    (GAARD): {{$info->total}}
                                                @endif
                                            @endforeach
                                        </p>
                                    </div>
                                </li>

                                <li class="media">
                                    <div class="media-left">
                                        <a href="#">
                                            <span class="glyphicon glyphicon-th"></span>
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <p class="media-heading text-info">
                                            <b>Do Fleets Participate?</b>
                                        </p>
                                        Yes, {{ $fleetInfo[0]->total }} of our
                                        existing users are fleets/flight
                                        training institutions.
                                    </div>
                                </li>

                                <li class="media">
                                    <div class="media-left">
                                        <a href="#">
                                            <span class="glyphicon glyphicon-th"></span>
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <p class="media-heading text-info">
                                            <b>How Many Flights & Flight Hours
                                               have been Contributed?</b>
                                        </p>
                                        <div class="row">
                                            <div id="no-more-tables" class="col-md-12">
                                                <table class="table table-condensed" style="border-collapse:collapse;margin:0px;">
                                                    <thead>
                                                        <tr class="text-info">
                                                            <th class="col-xs-1 text-left">
                                                                NGAFID
                                                            </th>
                                                            <th class="col-xs-1 text-center">
                                                                Flights
                                                            </th>
                                                            <th class="col-xs-1 text-center">
                                                                New
                                                                <br>Flights<sup>&dagger;</sup>
                                                            </th>
                                                            <th class="col-xs-2 text-center">
                                                                Flight <br>Hours
                                                            </th>
                                                            <th class="col-xs-2 text-center">
                                                                New <br>Flight
                                                                <br>Hours<sup>&dagger;</sup>
                                                            </th>
                                                            <th class="col-xs-1 text-center">
                                                                Accounts
                                                            </th>
                                                            <th class="col-xs-1 text-center">
                                                                New <br>Accounts<sup>&dagger;</sup>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                                @foreach ($statistics as $stats)
                                                    <div class="div-table-content">
                                                        <table class="table table-condensed">
                                                            <tbody>
                                                                @if ($stats->accountType === 'F')
                                                                    <tr>
                                                                        <td class="col-xs-1 text-left text-nowrap" data-title="Account Type">
                                                                            Fleet
                                                                        </td>
                                                                        <td class="col-xs-1 text-center text-nowrap" data-title="Flights">{{ $stats->flights }}</td>
                                                                        <td class="col-xs-1 text-center text-nowrap" data-title="New Flights">{{ $stats->newFlights }}</td>
                                                                        <td class="col-xs-2 text-center text-nowrap" data-title="Flight Hours">{{ $stats->flightHours }}</td>
                                                                        <td class="col-xs-2 text-center text-nowrap" data-title="New Flight Hours">{{ $stats->newFlightHours }}</td>
                                                                        <td class="col-xs-1 text-center text-nowrap" data-title="Accounts">{{ $stats->accounts }}</td>
                                                                        <td class="col-xs-1 text-right text-nowrap" data-title="New Accounts">{{ $stats->newAccounts }}</td>
                                                                    </tr>
                                                                @elseif ($stats->accountType === 'N')
                                                                    <tr>
                                                                        <td class="col-xs-1 text-left text-nowrap" data-title="Account Type">
                                                                            Non-Fleet
                                                                        </td>
                                                                        <td class="col-xs-1 text-center text-nowrap" data-title="Flights">{{ $stats->flights }}</td>
                                                                        <td class="col-xs-1 text-center text-nowrap" data-title="New Flights">{{ $stats->newFlights }}</td>
                                                                        <td class="col-xs-2 text-center text-nowrap" data-title="Flight Hours">{{ $stats->flightHours }}</td>
                                                                        <td class="col-xs-2 text-center text-nowrap" data-title="New Flight Hours">{{ $stats->newFlightHours }}</td>
                                                                        <td class="col-xs-1 text-center text-nowrap" data-title="Accounts">{{ $stats->accounts }}</td>
                                                                        <td class="col-xs-1 text-right text-nowrap" data-title="New Accounts">{{ $stats->newAccounts }}</td>
                                                                    </tr>
                                                                @elseif ($stats->accountType === 'G')
                                                                    <tr>
                                                                        <td class="col-xs-1 text-left text-nowrap" data-title="Account Type">
                                                                            GAARD
                                                                        </td>
                                                                        <td class="col-xs-1 text-center text-nowrap" data-title="Flights">{{ $stats->flights }}</td>
                                                                        <td class="col-xs-1 text-center text-nowrap" data-title="New Flights">{{  $stats->newFlights }}</td>
                                                                        <td class="col-xs-2 text-center text-nowrap" data-title="Flight Hours">{{ $stats->flightHours }}</td>
                                                                        <td class="col-xs-2 text-center text-nowrap" data-title="New Flight Hours">{{ $stats->newFlightHours }}</td>
                                                                        <td class="col-xs-1 text-center text-nowrap" data-title="Accounts">{{ $stats->accounts }}</td>
                                                                        <td class="col-xs-1 text-right text-nowrap" data-title="New Accounts">{{ $stats->newAccounts }}</td>
                                                                    </tr>
                                                                @endif
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <p class="pull-left text-danger">
                                                <b>
                                                    <small>
                                                        <sup>&dagger;</sup>
                                                        New information as
                                                        of {{ date("Y-n-j", strtotime("last day of previous month")) }}
                                                    </small>
                                                </b>
                                            </p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <!-- How-To Upload Flights -->
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            <div class="panel panel-info">
                                <div class="panel-heading" role="tab" id="heading_howto_upload">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_howto_upload" aria-expanded="true" aria-controls="collapseOne">
                                            How to Upload Flight Data
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse_howto_upload" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading_howto_upload">
                                    <div class="panel-body">
                                        <p>
                                            The following outlines the
                                            procedures for collecting and
                                            uploading flight data into the
                                            NGAFID.
                                        </p>
                                        <p>
                                            Most general aviation aircraft
                                            equipped with Garmin G1000/G3000
                                            glass panel are capable of recording
                                            flight data. To start recording data
                                            on your aircraft, place a blank 4 GB
                                            SD card into the upper right slot on
                                            the MFD.
                                        </p>
                                        <ul class="list-group">
                                            <li class="list-group-item">
                                                To retrieve the data, remove the
                                                data card from the upper slot in
                                                the MFD and place the card into
                                                your computer's card reader. You
                                                should see one file,
                                                <b>airframe_info.xml</b>, and
                                                one
                                                folder, <b>data_log</b>.
                                            </li>
                                            <li class="list-group-item">
                                                Open the <b>data_log</b> folder.
                                                You
                                                should see multiple <b>*.csv</b>
                                                files.
                                                Each file represents a flight.
                                                The flight file naming structure
                                                should be similar to the
                                                following:
                                                <samp>log_yymmdd_time_airportcode.csv</samp>.
                                                Each of these files can be
                                                opened in Microsoft Excel if you
                                                wish to review the raw flight
                                                data.

                                                <ul>
                                                    <li>
                                                        Example: <samp>log_170915_174350_GFK.csv</samp>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>

                                        <p>
                                            To upload the data into the NGAFID
                                            website:
                                        </p>
                                        <ol class="list-group">
                                            <li class="list-group-item">Click on
                                                <a href="{{ url('import/upload') }}">
                                                    Data Import
                                                    <span class="glyphicon glyphicon-chevron-right"></span>
                                                    Import Flight Data
                                                </a>
                                            </li>
                                            <li class="list-group-item">
                                                Select the appropriate aircraft
                                                from the drop-down menu
                                            </li>
                                            <li class="list-group-item">
                                                Enter the aircraft registration
                                                number in <b>N Number</b>
                                                textbox

                                                <ul>
                                                    <li>
                                                        Example:
                                                        <samp>N742JW</samp>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li class="list-group-item">
                                                Click on <b>Choose File</b> from
                                                directory where flight data is
                                                located on SD card. Then click
                                                open.

                                                <ul>
                                                    <li>
                                                        Example: <samp>G:\data_log</samp>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li class="list-group-item">
                                                Click <b>Upload</b>. In the
                                                upper-left hand corner, you
                                                should briefly see an alert that
                                                states
                                                <b>File has been submitted</b>.
                                            </li>
                                            <li class="list-group-item">
                                                Repeat steps 2-6 for multiple
                                                files.
                                            </li>
                                        </ol>

                                        <div class="well-sm">
                                            <span class="glyphicon glyphicon-info-sign text-info"></span>
                                            Large flight schools and operators
                                            can request batch uploads through
                                            our Dropbox synchronization tool.
                                            Please
                                            <a href="{{ env('MAILTO_STRING') }}">
                                                Contact Us
                                            </a>
                                            to receive additional information on
                                            this feature.
                                        </div>
                                    </div> <!-- /.panel-body -->
                                </div> <!-- /.panel-collapse.collapse.in -->
                            </div> <!-- /.panel.panel-info -->
                        </div> <!-- /.panel-group -->

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
