@extends('NGAFID-master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading"><b>Service Unavailable</b> <span class="pull-right">{{date("D M d, Y G:i A T")}}</span></div>

                    <div class="panel-body">
                        <p>The National General Aviation Flight Information Database is currently performing scheduled
                            maintenance. During this time, access to our website will be temporarily unavailable.
                            We appreciate your patience as we perform these upgrades to better serve you.</p>

                        <p>Users who participate via the GAARD app may continue to record their flights using their mobile device.
                            The services offered by GAARD will not be affected by this scheduled maintenance.</p>

                        <p>We apologize for any inconvenience caused, and thank you for your patience.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
