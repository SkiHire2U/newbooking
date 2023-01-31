@extends('layouts.app')

@section('title-bar')
	@include('partials._titlebar')
@endsection

@section('content')
	<div class="container">
        <div class="row">
        	<div class="col-md-10">
        		<div class="section-details">
                    <h2>Booking Information</h2>
                    <p><strong>Almost done!</strong> Please complete the last bits of information required below.</p>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-8">
                <div class="">
                	{!! Form::open(['route' => 'booking.store', 'method' => 'POST', 'data-parsley-validate' => '']) !!}
                	<div class="form-section">
                        <h3>Party Details</h3>
                        <div class="form-group">
                            {{ Form::label('party_leader', 'Party Leader Name') }}
                            {{ Form::text('party_leader', old('party_leader'), array('class' => 'form-control', 'required' => '', 'data-parsley-required' => 'true')) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('party_email', 'Email Address') }}
                            {{ Form::email('party_email', old('party_email'), array('class' => 'form-control', 'required' => '', 'data-parsley-required' => 'true')) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('party_mobile', 'Mobile Number') }}
                            {{ Form::text('party_mobile', old('party_mobile'), array('class' => 'form-control', 'required' => '', 'data-parsley-required' => 'true')) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('party_notes', 'Notes') }}
                            {{ Form::textarea('party_notes', old('party_notes'), array('size' => '10x5', 'class' => 'form-control')) }}
                        </div>
                    </div>
                    <hr>
                    <div class="form-section">
                        <h3>Terms and Conditions</h3>
                        <div class="form-group">
                            Please check the box below to show that you agree with our <a href="http://skihire2u.com/terms-and-conditions/" target="_blank">terms and conditions</a>.
                            <div class="checkbox">
								<label>
	                                <input name="terms_and_conditions" type="checkbox" required>
	                                I agree with the SkiHire2U terms and conditions.
                                </label>
                            </div>
                        </div>
                    </div>
                    <hr>
                    {{ Form::submit('Continue', array('class' => 'btn btn-success btn-lg')) }}
                    {!! Form::close() !!}
                </div>
            </div>
            <div class="col-md-4">
        		@include('partials._rack')
        	</div>
        </div>
    </div>	
@endsection

@section('scripts')
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery('#remove-from-rack-modal').on('show.bs.modal', function (event) {
            var button = jQuery(event.relatedTarget);
            var renter = button.data('id');
            var modal = jQuery(this);

            modal.find('#removal-modal-title').text(renterReplace(modal.find('#removal-modal-title').text(), renter));
            modal.find('#removal-body-text').text(renterReplace(modal.find('#removal-body-text').text(), renter));
            modal.find('#remove-renter').val(renter);
        });

        jQuery('#remove-from-rack-btn').click(function (e) {
            e.preventDefault();
            var id = jQuery('#remove-renter').val();
            jQuery('.remove-from-rack[data-id="' + id + '"]').submit();
        });
    });

    function renterReplace(originalString, newString) {
        return originalString.replace(/%renter%/, newString);
    }
</script>
@endsection