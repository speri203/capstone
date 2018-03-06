@extends('NGAFID-master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading"><b>Edit Flight</b> <span class="pull-right">{{date("D M d, Y G:i A T")}}</span></div>

                    <div class="panel-body">
                        @include('partials.errors')

                        {!! Form::open(['method' => 'PATCH', 'route' => ['flights.update', $flight], 'class' => 'form-horizontal'] ) !!}

                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="col-md-4 control-label">Flight Date</label>
                                <div class="col-md-6">
                                    {!! Form::text('date', $data['date'] , array('class' => 'form-control', 'readonly')) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">Duration</label>
                                <div class="col-md-6">
                                    {!! Form::text('duration', $data['duration'] , array('class' => 'form-control', 'readonly')) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">N Number</label>
                                <div class="col-md-6">
                                    {!! Form::text('n_number', $data['n_number'] , array('class' => 'form-control')) !!}
                                </div>
                            </div>

                        </div>

                        <div class="col-md-5 col-md-offset-1">
                            <div class="form-group">
                                <label class="col-md-4 control-label">Aircraft</label>
                                <div class="col-md-6">
                                    {!! Form::select('aircraft', $data['aircraft'], $data['aircraft_type'], ["class" => "form-control"]) !!}
                                </div>
                            </div>

                            @include('partials.usa-airport-codes-macro')
                            <div class="form-group">
                                <label class="col-md-4 control-label">Origin</label>
                                <div class="col-md-6">
                                    {!! Form::airportSelect('origin', $data['origin'], ["class" => "form-control"]) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">Destination</label>
                                <div class="col-md-6">
                                    {!! Form::airportSelect('destination', $data['destination'], ["class" => "form-control"]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-5">
                                <button type="submit" class="btn btn-primary">
                                    Save
                                </button>
                            </div>
                        </div>
                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection