@extends('layouts.app')

@section('title-bar')
	@include('partials._titlebar')
@endsection

@section('content')
	<div class="container">
		<div class="section-details">
            <h3>Success!</h3>
            <h2>You have successfully submitted your booking. We sent you an email containing the Reference Number of your booking to {{ $email }} so you can revisit it in the future and change some things if needed. Thank you!</h2>
        </div>
    </div>	
@endsection

@section('scripts')

@endsection