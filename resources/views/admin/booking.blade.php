@extends('layouts.app')

@section('title-bar')
	@include('partials._adminMenu')
@endsection

@section('styles')
<link rel="stylesheet" type="text/css" href="/css/admin.css">
<link rel="stylesheet" type="text/css" href="/css/jquery.dataTables.min.css">
@endsection

@section('content')
<div class="container">
	<h2>Booking Information</h2>
	<div class="booking-data">
		<div class="row">
			<div class="col-md-6">
				<p><strong>Chalet: </strong> {{ $booking->chalet_name }}</p>
				@if ($booking->chalet_id == 1)
				<p><strong>Accommodation Address: </strong> {{ $booking->chalet_address }}</p>
				@endif
				<p><strong>Party Leader: </strong> {{ $booking->party_leader }}</p>
				<p><strong>Party Email: </strong> {{ $booking->party_email }}</p>
				<p><strong>Party Mobile: </strong> {{ $booking->party_mobile }}</p>
				<p><strong>Reference Number: </strong> {{ $booking->reference_number }}</p>
				<p><strong>Notes: </strong> {{ $booking->notes }}</p>
			</div>
			<div class="col-md-6">
				<div class="booking-dates">
					<p><strong>Arrival Date: </strong> {{ $booking->arrival_datetime }}</p>
					<p><strong>First Day on Mountain: </strong> {{ $booking->mountain_datetime }}</p>
					<p><strong>Departure Date: </strong> {{ $booking->departure_datetime }}</p>
				</div>
			</div>
		</div>
	</div>
	<div class="admin-button-container">
		<div class="row">
			<div class="col-md-6">
				<!-- <a href="#" class="btn btn-success btn-icon" data-toggle="modal" data-target="#booking-modal" title="Edit"><i class="fa fa-pencil"></i>Edit Booking</a> -->
				<a href="#" id="notifyCustomer" class="btn btn-primary btn-icon" title="Notify Customer"><i class="fa fa-envelope"></i>Notify Customer</a>
				<div class="hidden">
					<form id="notification-form" action="{{ route('booking.email', $booking->id) }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
				</div>
				<div class="modal fade" id="booking-modal" tabindex="-1" role="dialog" aria-labelledby="editBooking">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h3 class="modal-title">Edit Booking</h3>
							</div>
							<div class="modal-body">
								<div class="row">
									<div class="form-wrapper col-md-8 col-md-offset-2">
									{!! Form::open(array('route' => array('bookings.update', $booking->id), 'method' => 'PUT', 'class' => 'edit-booking' )) !!}
										<div class="form-group">
							            	{{ Form::label('party_leader', 'Party Leader:') }}
							            	{{ Form::text('party_leader', $booking->party_leader, array('class' => 'party-leader form-control', 'required' => '')) }}
						          		</div>
						          		<div class="form-group">
							            	{{ Form::label('party_email', 'Party Leader:') }}
							            	{{ Form::text('party_email', $booking->party_email, array('class' => 'party-email form-control', 'required' => '')) }}
						          		</div>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="submit" class="btn btn-primary">Save</button>
							</div>
							{!! Form::close() !!}
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="right">
					<a href="{{ route('picking', $booking->id) }}" class="btn btn-warning btn-icon" title="Print Picking List"><i class="fa fa-print"></i>Picking List</a>
					<a href="{{ route('invoice', $booking->id) }}" class="btn btn-info btn-icon" title="Print Picking List"><i class="fa fa-print"></i>Invoice</a>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="container">
	<h3>Members</h3>
	<div class="table-container">
		<table class="table webee-table" id="dataTable">
			<thead>
				<th></th>
				<th>Personal Details</th>
				<th>Measurements</th>
				<th>Package</th>
				<th>Price</th>
				<th>Actions</th>
			</thead>
			<tbody>
			@foreach ($booking->rentals as $rental)
				<tr>
					<td>{{ $loop->iteration }}</td>
					<td>
						<strong>{{ $rental->name }}</strong>
						<p>{{ $select['age'][$rental->age] }}</p>
						<p>{{ $rental->sex }}</p>
						@if($rental->notes)
						<p>Notes: <strong>{{ $rental->notes }}</strong></p>
						@endif
					</td>
					<td>
						<p>Ability: <strong>{{ $select['level'][$rental->ability] }}</strong></p>
                        <p>Height: <strong>{{ $select['height'][$rental->height] }}</strong></p>
                        <p>Weight: <strong>{{ $select['weight'][$rental->weight] }}</strong></p>
                        @if($rental->addons->boots == 'on')
                        <p>Foot Size (in EU): <strong>{{ $select['foot'][$rental->foot] }}</strong></p>
                        @endif
                        <br>
                        <p>Ski Length: <strong>{{ $rental->ski_length}}</strong></p>
                        <p>Pole Length: <strong>{{ $rental->pole_length}}</strong></p>
                        <p>Skier Code: <strong>{{ $rental->skier_code}}</strong></p>
                        @if($rental->addons->boots == 'on')
                        <p>Boot Size: <strong>{{ $rental->boot_size}}</strong></p>
                        @endif
                        <p>DIN: <strong>{{ $rental->din}}</strong></p>
					</td>
					<td>
						<div class="equipments-container">
                            <p><strong>{{ $packageModel->getPackageName($rental->package_id) }}</strong></p>
                            <p><strong>{{ $packageModel->getPackageLevel($rental->package_id) }}</strong></p>
                            <p>Duration: <strong>{{ $rental->duration }} {{ ($rental->duration == 1) ? 'day' : 'days' }}</strong></p>
                            <p>Boots: <strong>{{ ($rental->addons->boots == 'on') ? 'Yes' : 'No' }}</strong></p>
                            <p>
                            	Helmet: <strong>{{ ($rental->addons->helmet == 'on') ? 'Yes' : 'No' }}</strong>
                            	@if( (int) $rental->age <= 3 && $rental->addons->helmet == 'on' || $packageModel->getPackageType($rental->package_id) == 'Child')
						    	<small class="free-helmet">FREE!</small>
						    	@endif
                            </p>
                            <p>Insurance: <strong>{{ ($rental->addons->insurance == 'on') ? 'Yes' : 'No' }}</strong></p>
                        </div>
					</td>
					<td>
                        @if ($invoice->rental_prices[$rental->id]['discount'] != 0)
                        <p class="price">&euro; {{ $invoice->rental_prices[$rental->id]['total'] }}</p>
                        <p class="price strike"><small>&euro; {{ $invoice->rental_prices[$rental->id]['price'] }}</small></p>
                        <p class="discount-amount">Less: ( &euro; - <small>{{ $invoice->rental_prices[$rental->id]['discount'] }}</small> )</p>
                        @else
                        <p class="price">&euro; {{ $invoice->rental_prices[$rental->id]['total'] }}</p>
                        @endif
                    </td>
					<td>
						<a href="#" class="btn btn-success btn-icon" data-toggle="modal" data-target="#rental-modal-{{ $rental->id }}" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-pencil"></i></a>
						<div class="modal fade" id="rental-modal-{{ $rental->id }}" tabindex="-1" role="dialog" aria-labelledby="editRental">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h3 class="modal-title">Edit Rental</h3>
									</div>
									<div class="modal-body">
										<div class="row">
											<div class="form-wrapper col-md-8 col-md-offset-2">
											{!! Form::open(array('route' => array('rental.update', $rental->id), 'method' => 'PUT', 'class' => 'edit-rental' )) !!}
												<div class="hidden">
													{{ Form::text('booking_id', $booking->id, array('readonly' => '')) }}
												</div>
												<h3>Personal Details</h3>
												<div class="form-group">
									            	{{ Form::label('package_renter', 'Name:') }}
									            	{{ Form::text('package_renter', $rental->name, array('class' => 'chalet-name form-control', 'required' => '')) }}
								          		</div>
								          		<div class="form-group">
		                                            {{ Form::label('renter_age', 'Age:') }}
		                                            {{ Form::select('renter_age', $select['age'], $rental->age, array('class' => 'renter-age form-control', 'required' => '')) }}
		                                        </div>
		                                        <div class="form-group">
		                                            {{ Form::label('renter_sex', 'Sex:') }}
		                                            {{ Form::select('renter_sex', array('Male' => 'Male', 'Female' => 'Female'), $rental->sex, array('class' => 'renter-sex form-control', 'required' => '')) }}
		                                        </div>
		                                        <div class="form-group">
		                                            {{ Form::label('notes', 'Notes') }}<span> (optional)</span>
		                                            {{ Form::text('notes', $rental->notes, array('class' => 'renter-notes form-control')) }}
		                                        </div>
		                                        <hr>
		                                        <h3>Measurements</h3>
		                                        <div class="form-group">
	                                                {{ Form::label('renter_ability', 'Ability:') }}
	                                                {{ Form::select('renter_ability', $select['level'], $rental->ability, array('class' => 'renter-abiity form-control', 'required' => '')) }}
	                                            </div>
	                                            <div class="form-group">
	                                                {{ Form::label('renter_height', 'Height:') }}
	                                                {{ Form::select('renter_height', $select['height'], $rental->height, array('class' => 'renter-height form-control', 'required' => '')) }}
	                                            </div>
	                                            <div class="form-group">
	                                                {{ Form::label('renter_weight', 'Weight:') }}
	                                                {{ Form::select('renter_weight', $select['weight'], $rental->weight, array('class' => 'renter-weight form-control', 'required' => '')) }}
	                                            </div>
	                                            <div class="form-group">
	                                                {{ Form::label('renter_foot', 'Foot Size (in EU):') }}
	                                                {{ Form::select('renter_foot', $select['foot'], $rental->foot, array('class' => 'renter-foot form-control', 'required' => '')) }}
	                                            </div>
	                                            <hr>
	                                            <h3>Package</h3>
	                                            <div class="form-group">
		                                            {{ Form::label('package_id', 'Package:') }}
		                                            {{ Form::select('package_id', $select['packages'], $rental->package_id, array('class' => 'renter-package form-control', 'required' => '')) }}
		                                        </div>
		                                        <div class="form-group">
		                                            <label for="rent_days">Duration:</label>
		                                            <select name="rent_days" class="form-control">
		                                            @for ($i = 1; $i <= $days; $i++)
		                                                <option value="{{ $i }}" {{ $i == $rental->duration ? 'selected="selected"' : '' }}>{{ $i }} {{ ($i == 1) ? 'day' : 'days' }}</option>
		                                            @endfor
		                                            </select>
		                                        </div>
		                                        <div class="form-group">
		                                            <label>Add on</label>
		                                            <div class="checkbox addon">
		                                                <label>
		                                                    <input name="addon[boots]" type="hidden" value="off">
		                                                    <input name="addon[boots]" class="boots-addon" type="checkbox" {{ ($rental->addons->boots == 'on') ? 'checked' : '' }} >
		                                                    Boots
		                                                </label>
		                                            </div>
		                                            <div class="checkbox addon">
		                                                <label>
		                                                    <input name="addon[helmet]" type="hidden" value="off">
		                                                    <input name="addon[helmet]" class="helmet-addon" type="checkbox" {{ ($rental->addons->helmet == 'on') ? 'checked' : '' }} >
		                                                    Helmet
		                                                </label>
		                                            </div>
		                                            <div class="checkbox addon">
		                                                <label>
		                                                    <input name="addon[insurance]" type="hidden" value="off">
		                                                    <input name="addon[insurance]" class="insurance-addon" type="checkbox" {{ ($rental->addons->insurance == 'on') ? 'checked' : '' }} >
		                                                    Insurance
		                                                </label>
		                                            </div>
		                                        </div>
											</div>
										</div>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
										<button type="submit" class="btn btn-primary">Save</button>
									</div>
									{!! Form::close() !!}
								</div>
							</div>
						</div>
						<a href="#" class="btn btn-danger btn-icon" data-toggle="modal" data-target="#remove-from-list-modal" data-id="{{ $rental->name }}"><i class="glyphicon glyphicon-trash" aria-hidden="true"></i></a>
						<div class="hidden">
							{!! Form::open(array('route' => array('rental.delete', $rental->id), 'method' => 'POST', 'class' => 'remove-from-list', 'data-id' => $rental->name )) !!}
							<div class="hidden">
								{{ Form::text('booking_id', $booking->id, array('readonly' => '')) }}
							</div>
							{!! Form::close() !!}
						</div>
					</td>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
</div>
<div class="modal fade" id="remove-from-list-modal" tabindex="-1" role="dialog" aria-labelledby="removeRenter">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="removal-modal-title">REMOVE PACKAGE FOR: %renter%</h4>
			</div>
			<div class="modal-body">
				<form>
	            	<input type="text" class="form-control hidden" id="remove-renter">
		        </form>
		        <p id="removal-body-text">Are you sure you would like to remove the package for <strong>%renter%</strong> from the list?</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" id="remove-from-list-btn">Continue</button>
			</div>
		</div>
	</div>
</div>
@endsection

@section('scripts')
<script type="text/javascript" src="/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('#dataTable').DataTable({
			"pageLength": 50
		});

		jQuery('#notifyCustomer').click(function(e) {
			e.preventDefault();
			
			var con = confirm('Are you sure you want to send email?');

			if(con) {
				jQuery('#notification-form').submit();
			}
		});

		jQuery('#remove-from-list-modal').on('show.bs.modal', function (event) {
            var button = jQuery(event.relatedTarget);
            var renter = button.data('id');
            var modal = jQuery(this);

            modal.find('#removal-modal-title').text(renterReplace(modal.find('#removal-modal-title').text(), renter));
            modal.find('#removal-body-text').text(renterReplace(modal.find('#removal-body-text').text(), renter));
            modal.find('#remove-renter').val(renter);
        });

        jQuery('#remove-from-list-btn').click(function (e) {
            e.preventDefault();
            var id = jQuery('#remove-renter').val();
            jQuery('.remove-from-list[data-id="' + id + '"]').submit();
        });
	});

	function renterReplace(originalString, newString) {
        return originalString.replace(/%renter%/, newString);
    }
</script>
@endsection