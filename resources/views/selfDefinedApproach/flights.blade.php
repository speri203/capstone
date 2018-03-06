<!-- resources/views/selfDefinedApproach/flights.blade.php -->

@extends('NGAFID-master')

@section('cssScripts')
    <style>
        .btn-group {
            float: none;
            display: inline-block;
        }

        .btn-group .dropdown-menu {
            min-width: 0px !important;
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
                        <b>My Flights - Self Defined Glide Path</b>
                        <span class="pull-right">{{ date("D M d, Y G:i A T") }}</span>
                    </div>
                    <div class="panel-body">

                        <div class="col-md-4">
                            <h4>Runway: {{ $runway }}</h4>
                        </div>
                        <div class="col-md-4">
                            <h4>Year-Month: {{ $date }}</h4>
                        </div>
                        <div class="col-md-4">
                            <h4>Glide Path Angle:
                                {{ $gpa_low }}&deg; - {{ $gpa_high }}&deg;
                            </h4>
                        </div>

                        <div class="col-md-12">
                            <hr />
                        </div>

                        @include('partials._flights-table', ['data' => $data])

                    </div>
                    {!! str_replace('/?', '?', $data->appends(Request::only(['runway', 'date', 'gpa_low', 'gpa_high', 'flight_id']))->render()) !!}

                </div>
            </div>
        </div>
    </div>
@endsection
