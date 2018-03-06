@extends('NGAFID-master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <b>Data Encryption</b>
                        <span class="pull-right">{{ date("D M d, Y G:i A T") }}</span>
                    </div>

                    <div class="panel-body">
                        @include('partials.errors')

                        <div class="well">
                            <p>
                                Enabling data encryption will encrypt the N Number
                                and Day of each flight. After you have successfully
                                enabled encryption, all previous flights
                                will be retroactively encrypted as well as any
                                flights uploaded in the future. This will include
                                all flights uploaded by any user registered under
                                your fleet. Only the fleet administrator is able to
                                enroll in data encryption.
                            </p>
                            <p class="text-warning">
                                After encryption has been enabled, it is not
                                possible to disable it.
                            </p>
                        </div>

                        {!! Form::open(['method' => 'POST', 'url' => 'generate', 'class' => 'form-horizontal']) !!}
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="form-group">
                            <label class="col-md-4 control-label">
                                Secret Key
                            </label>
                            <div class="col-md-5">
                                <input type="password" class="form-control" name="secretKey" value=""> * minimum 10 characters, maximum 32
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">
                                Confirm Secret Key
                            </label>
                            <div class="col-md-5">
                                <input type="password" class="form-control" name="secretKey_confirmation" value="">
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

                        <h5 class="text-center text-danger">
                            ** Please note that once data is encrypted by the
                            data owner, the password is only known by that data
                            owner. If the password is lost, there is no way to
                            recover it. **
                        </h5>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
