@extends('NGAFID-master')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Register</div>
				<div class="panel-body">
                    @include('partials.errors')

					<form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/register') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="col-md-4 control-label">Fleet / Operator Name</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="fleet" value="{{ (Input::old('fleet')) }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">E-Mail Address</label>
                                <div class="col-md-6">
                                    <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">First Name</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="firstname" value="{{ old('firstname') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">Last Name</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="lastname" value="{{ old('lastname') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">Password</label>
                                <div class="col-md-6">
                                    <input type="password" class="form-control" name="password">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">Confirm Password</label>
                                <div class="col-md-6">
                                    <input type="password" class="form-control" name="password_confirmation">
                                </div>
                            </div>
                            {!! Honeypot::generate('hp_filter', 'hp_time') !!}
                        </div>

                        <div class="col-md-5 col-md-offset-1">
                            <div class="form-group">
                                <label class="col-md-4 control-label">Address</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="address" value="{{ (Input::old('address')) }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">City</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="city" value="{{ (Input::old('city')) }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">Country</label>
                                <div class="col-md-6">
                                    @include('partials.countries-macro')
                                    {!! Form::countrySelect('country', Input::old('country'), ["class" => "form-control", "id" => "country"]) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">State</label>
                                <div class="col-md-6">
                                    @include('partials.usa-states-macro')
                                    {!! Form::stateSelect('states', Input::old('states'), ["class" => "form-control", "id" => "states"]) !!}
                                    <input type="hidden" name="state" value=""/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">Zip Code</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="zip_code" value="{{ (Input::old('zip_code')) }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">Phone</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="phone" value="{{ (Input::old('phone')) }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">Fax</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="fax" value="{{ (Input::old('fax')) }}">
                                </div>
                            </div>
                        </div>


						<div class="form-group">
							<div class="col-md-8 col-md-offset-5">
								<button type="submit" class="btn btn-primary">
									Register
								</button>
                                <br><br>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="consent">
                                    I agree to the NGAFID <a href="#viewTerms" class="consentForm" data-toggle="modal" id="consentForm">Terms and Conditions</a>
                                </label>

                                <div class="modal fade" id="viewTerms" tableindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5><b>NGAFID Consent Form</b></h5>
                                            </div>
                                            <div class="modal-body">
                                                <iframe width="100%" height="100%" frameborder="0" scrolling="yes" allowtransparency="true" src="{{ asset("files/NGAFIDconsent-form.pdf") }}"></iframe>
                                            </div>
                                            <div class="modal-footer">
                                                <a href="#" class="btn btn-default" data-dismiss="modal">Close</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
							</div>
						</div>

                    </form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('jsScripts')
<script type="text/javascript">
    $( document ).ready(function() {
        var selected = $('#country').val();
        checkStates(selected);
    });

    $(document).on('change','#country',function(){
        var selected = $(this).val();
        checkStates(selected);
    });

    $(document).on('change','#states',function(){
        var selected = $(this).val();
        $('input[name="state"]').val(selected);
    });

    function checkStates(selected) {

        if(selected !== 'US' && selected !== ''){
            $('input[name="zip_code"]').val('');
            $('input[name="state"]').val('');
            $("#states").val('');

            $('input[name="zip_code"]').attr('readonly', true);
            $('input[name="zip_code"]').css('background-color' , '#EBEBE4');
            $('#states').attr('disabled', 'disabled');
        }
        else{
            $('input[name="zip_code"]').attr('readonly', false);
            $('input[name="zip_code"]').css('background-color' , '#FFFFFF');
            $('#states').removeAttr('disabled');
        }
        
    }
</script>
@endsection