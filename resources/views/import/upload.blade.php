@extends('NGAFID-master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading"><b>Import Flight Data</b> <span class="pull-right">{{date("D M d, Y G:i A T")}}</span></div>

                    <div class="panel-body">
                        @include('partials.errors')

                        {!! Form::open(['method' => 'POST', 'route' => 'import.store', 'files' => true, 'novalidate' => 'novalidate', 'class' => 'form-horizontal']) !!}
                        <p class="col-md-offset-1">Enter flight details below:</p>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="col-md-4 control-label">Aircraft</label>
                                <div class="col-md-6">
                                    {!! Form::select('aircraft', $data['aircraft'], Input::old('aircraft'), ["class" => "form-control"]) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">N Number</label>
                                <div class="col-md-6">
                                    {!! Form::text('n_number', Input::old('n_number') , array('class' => 'form-control')) !!}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="col-md-4 control-label">File Upload</label>
                                <div class="col-md-6">
                                    {!! Form::file('flight_data', null) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    Upload
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
