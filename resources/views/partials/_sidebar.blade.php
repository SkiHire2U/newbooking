<div class="section-details">
    <h2>Find a booking</h2>
    <p>Input the details below to edit your existing booking.</p>
</div>
<hr>
<div class="existing-boooking-wrapper clearfix">
    {!! Form::open(['route' => 'reference', 'method' => 'POST', 'data-parsley-validate' => '']) !!}
    	<div class="form-group">
            {{ Form::label('party_leader', 'Party Leader Email') }}
            {{ Form::email('party_leader', old('party_leader'), array('class' => 'form-control', 'required' => '', 'data-parsley-required' => 'true')) }}
        </div>
        <div class="form-group">
            {{ Form::label('reference_code', 'Reference Code') }}
            {{ Form::text('reference_code', old('reference_code'), array('class' => 'form-control', 'required' => '', 'data-parsley-required' => 'true')) }}
        </div>
    	{{ Form::submit('Find Booking', array('class' => 'btn btn-primary')) }}

    {!! Form::close() !!}
</div>