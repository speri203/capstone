<!-- resources/views/dropbox_auth/index.blade.php -->

@extends('NGAFID-master')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2 class="panel-title">NGAFID_Sync Dropbox Authorization</h2>
                    </div>
                    <div class="panel-body">

                        <div class="col-md-10 col-md-offset-1">
                            <div class="well well-md">
                                <p>
                                    Below are steps to follow which will give the NGAFID access to a specific folder within your Dropbox account.
                                    When you follow the link and click "Allow", it will automatically create a folder in Apps <span class="glyphicon glyphicon-chevron-right"></span> NGAFID_Sync.
                                    The NGAFID_Sync tool will only have permissions to access that folder only.
                                </p>

                                1. Go to:
                                    <a target="_blank" href="{{ $dbx_url }}">
                                        {{ $dbx_url }} <span class="glyphicon glyphicon-new-window"></span>
                                    </a><br />
                                2. Click "Allow" (you might have to log in first).<br />
                                3. Copy &amp; paste the authorization code into the box below.<br />
                            </div>
                        </div>

                        {!! Form::open(['method' => 'POST', 'url' => 'dbx', 'class' => 'form-horizontal']) !!}
                        {!! Form::token() !!}

                            <div class="form-group{{ $errors->has('auth_code') ? ' has-error' : '' }}">
                                {!! Form::label('auth_code', 'Authorization Code:', ['class' => 'col-md-4 control-label']) !!}

                                <div class="col-md-6">
                                    {!! Form::text('auth_code', null, ['class' => 'form-control']) !!}

                                    @if ($errors->has('auth_code'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('auth_code') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    {!! Form::submit('Submit', ['class' => 'btn btn-success']) !!}
                                </div>
                            </div>

                        {!! Form::close() !!}
                    </div> <!-- /.panel-body -->
                </div> <!-- /.panel panel-default -->
            </div> <!-- /.col-md-10 col-md-offset-1 -->
        </div> <!-- /.row -->
    </div> <!-- /.container-fluid -->

@endsection
