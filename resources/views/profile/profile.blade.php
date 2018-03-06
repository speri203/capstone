@extends('NGAFID-master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <b>Edit Profile</b>
                        <span class="pull-right">{{ date("D M d, Y G:i A T") }}</span>
                    </div>

                    <div class="panel-body">
                        @include('partials.errors')

                        {!! Form::open(['method' => 'PATCH', 'route' => ['profile.update', $data['fleetInfo']['id']], 'class' => 'form-horizontal']) !!}

                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="col-md-4 control-label">
                                    User Name
                                </label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="email" readonly value="{{ $data['username'] }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">
                                    Account Type
                                </label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="account_type" readonly value="{{ $data['type'] }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">
                                    First Name
                                </label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="firstname" value="{{ Input::old('firstname') ? Input::old('firstname') : $data['firstname'] }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">
                                    Last Name
                                </label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="lastname" value="{{ Input::old('lastname') ? Input::old('lastname') : $data['lastname'] }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">
                                    Password
                                </label>
                                <div class="col-md-6">
                                    <input type="password" class="form-control" name="password" readonly value="*******">
                                    &nbsp;
                                    <a href="{{ url('/profile/password') }}">
                                        Change Password
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-5 col-md-offset-1">
                            <div class="form-group">
                                <label class="col-md-4 control-label">Address</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="address" value="{{ Input::old('address') ? Input::old('address') : $data['fleetInfo']['address'] }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">City</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="city" value="{{ Input::old('city') ? Input::old('city') : $data['fleetInfo']['city'] }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">Country</label>
                                <div class="col-md-6">
                                    @include('partials.countries-macro')
                                    {!! Form::countrySelect('country', Input::old('country') ? Input::old('country') : $data['fleetInfo']['country'], ["class" => "form-control", "id" => "country"]) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">State</label>
                                <div class="col-md-6">
                                    @include('partials.usa-states-macro')
                                    {!! Form::stateSelect('states', Input::old('states') ? Input::old('states') : $data['fleetInfo']['state'], ["class" => "form-control", "id" => "states"]) !!}
                                    <input type="hidden" name="state" value="" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">
                                    Zip Code
                                </label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="zip_code" value="{{ Input::old('zip_code') ? Input::old('zip_code') : $data['fleetInfo']['zip_code'] }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">Phone</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="phone" value="{{ Input::old('phone') ? Input::old('phone') : $data['fleetInfo']['phone'] }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">Fax</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="fax" value="{{ Input::old('fax') ? Input::old('fax') : $data['fleetInfo']['fax'] }}">
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


@section('jsScripts')
    <script type="text/javascript">
        $(function () {
            $('#country').change(function () {
                var selected = $(this).val();
                checkStates(selected);
            }).change();

            $('#states').change(function () {
                var selected = $(this).val();
                $('input[name="state"]').val(selected);
            });
        });

        function checkStates(selected) {
            if (selected !== 'US' && selected !== '') {
                $('input[name="zip_code"]')
                    .val('')
                    .attr('readonly', true)
                    .css('background-color', '#EBEBE4');

                $('input[name="state"]').val('');

                $("#states")
                    .val('')
                    .attr('disabled', 'disabled');
            } else {
                $('input[name="zip_code"]')
                    .attr('readonly', false)
                    .css('background-color', '#FFFFFF');

                $('#states').removeAttr('disabled');
            }
        }
    </script>
@endsection
