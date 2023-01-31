<div id="rack-data" class="rack-data-container">
	<!-- <a id="close-side" href="#"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a> -->
	<div class="rack-data-wrapper">
		<div class="party-details">
			<h3>Accommodation Details</h3>
			<span>Chalet Name: <strong>{{ $chalet['name'] }}</strong></span>
			<span>Arrival Date/Time: <strong>{{ $details['arrival_dtp'] }}</strong></span>
			<span>Departure Date/Time: <strong>{{ $details['departure_dtp'] }}</strong></span>
			<span>First day on Mountain: <strong>{{ $details['mountain_dtp'] }}</strong></span>
		</div>
		<hr>
		<div class="rack-details">
			<h3>Rack Items</h3>
			@if (count($packages) > 0)
			<ul class="rack-list">
				<?php $total = 0; $origTotal = 0; ?>
				@foreach ($packages as $key => $package)
				<li class="rack-item">
					<div class="hidden">
					{!! Form::open(array('route' => array('removeFromRack', $key), 'method' => 'POST', 'class' => 'remove-from-rack', 'data-id' => $key )) !!}
					{!! Form::close() !!}
					</div>
					@if(!Request::is('details'))
					<a href="#" class="remove-from-rack-btn" data-toggle="modal" data-target="#remove-from-rack-modal" data-id="{{ $key }}" data-name="{{ $package['package_renter'] }}"><i class="glyphicon glyphicon-trash" aria-hidden="true"></i></a>
					@endif
					<h4>{{ $package['package_renter'] }}</h4>
					<span><strong>{{ $packageModel->getPackageName($package['package_id']) }}</strong></span>
					@if($packageModel->getPackageLevel($package['package_id']))
					<span><strong>{{ $packageModel->getPackageLevel($package['package_id']) }}</strong></span>
					@endif
					<span>Duration: {{ $package['rent_days'] }} {{ ($package['rent_days'] == 1) ? 'day' : 'days' }}</span>
					<div class="row">
						<div class="col-md-6">
							<span>Boots: {{ ($package['addon']['boots'] == 'on') ? 'Yes' : 'No' }}</span>
							<span>
						    	Helmet: {{ ($package['addon']['helmet'] == 'on') ? 'Yes' : 'No' }}
						    	@if( (int) $package['renter_age'] <= 3 && $package['renter_age'] != null && $package['addon']['helmet'] == 'on' || $packageModel->getPackageType($package['package_id']) == 'Child')
						    	<small class="free-helmet">FREE! *</small>
						    	@endif
						    </span>
							<span>Insurance: {{ ($package['addon']['insurance'] == 'on') ? 'Yes' : 'No' }}</span>
						</div>
						<div class="col-md-6">
							@if ($chalet['discount'])
							<span class="price">&euro; {{ number_format($prices[$key]['discounted'], 2, '.', ',') }}</span>
							<span class="price strike">&euro; {{ number_format($prices[$key]['originalAmount'], 2, '.', ',') }}</span>
							<span class="discount-amount">Less: ( - &euro; <small>{{ number_format($prices[$key]['discountAmount'], 2, '.', ',') }}</small> )</span>
							@else
							<span class="price">&euro; {{ number_format($prices[$key]['originalAmount'], 2, '.', ',') }}</span>
							@endif
						</div>
					</div>
				</li>
				@endforeach
			</ul>
			<div class="total-price-container">
				<div class="col-md-4">
					<h3>TOTAL:</h3>
				</div>
				<div class="col-md-8">
					<div class="total-price-wrapper">
						@if ($chalet['discount'])
						<span class="total-price">&euro; {{ number_format($prices['totalDiscounted'], 2, '.', ',') }}</span>
						<span class="total-price strike">&euro; {{ number_format($prices['total'], 2, '.', ',') }}</span>
						@else
						<span class="total-price">&euro; {{ number_format($prices['total'], 2, '.', ',') }}</span>
						@endif
					</div>
				</div>
			</div>
			@else
			<div class="alert alert-info" role="alert">
				Rack is empty.
			</div>
			@endif
		</div>
	</div>
</div>
<div class="modal fade" id="remove-from-rack-modal" tabindex="-1" role="dialog" aria-labelledby="removeRenter">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="removal-modal-title">REMOVE PACKAGE FOR: %renter%</h4>
			</div>
			<div class="modal-body">
				<form class="hidden">
	            	<input type="text" class="form-control" id="remove-renter-name">
                    <input type="text" class="form-control" id="remove-renter-id">
		        </form>
		        <p id="removal-body-text">Are you sure you would like to remove the package for %renter% from the rack?</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-danger" id="remove-from-rack-btn">Delete</button>
			</div>
		</div>
	</div>
</div>