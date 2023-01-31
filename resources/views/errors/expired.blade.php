@extends('layouts.app')

@section('content')
	<div class="container">
        <div class="row">
            <h2>Session Expired!</h2>
        	<p>We’re really sorry but in order to protect the integrity of the booking system, after a long period of inactivity the session expires. Please either click on ‘Book Now’ found on the top menu or click on the ‘Start Over’ button below.  Thank you for your understanding.</p>
            <p>If this issue still persist after trying the above steps, please don't hesitate to email us at <a href="mailto:info@skihire2u.com" target="_blank">info@skihire2u.com</a> or call us on <a href="tel:+33450721970" value="+33450721970" target="_blank">+33 4 50 72 19 70</a></p>

            <a href="/" class="btn btn-success">Start Over</a>
        </div>
    </div>
@endsection