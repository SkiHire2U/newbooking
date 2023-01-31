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
	@if($monthFilter != 0 || $yearFilter != 0)
		<p>Showing records from <strong>{{ $records }}</strong></p>
	@else
		<p>Showing records from <strong>{{ $records }}</strong> up to present</p>
	@endif
	<div class="row">
		<div class="col-md-3">
			<p><strong>Search Booking by Reference Number</strong></p>
			{!! Form::open(array('route' => 'booking.reference', 'method' => 'POST', 'class' => 'booking-date-filter' )) !!}
			<div class="row">
				<div class="form-group col-md-12">
					{{ Form::label('booking_reference', 'Reference Number') }}
					{{ Form::text('booking_reference', null, array('id' => 'booking_reference', 'class' => 'form-control', 'required' => '')) }}
				</div>
				<div class="form-group col-md-12">
					{{ Form::submit('Search', array('class' => 'btn btn-primary')) }}
				</div>
				{!! Form::close() !!}
			</div>
		</div>
		<div class="col-md-1">
		</div>
		<div class="col-md-8">
			<p><strong>Filter results by month and year</strong></p>
			{!! Form::open(array('route' => 'bookings.filter', 'method' => 'POST', 'class' => 'booking-date-filter' )) !!}
			<div class="row">
				<div class="form-group col-md-6">
					{{ Form::label('booking_month', 'Month') }}
					<select id="booking_month" name="booking_month" class="col-md-12 form-control">
						<option value="0" {{ $monthFilter == '0' ? 'selected' : '' }}></option>
						<option value="01" {{ $monthFilter == '01' ? 'selected' : '' }}>January</option>
						<option value="02" {{ $monthFilter == '02' ? 'selected' : '' }}>Febraury</option>
						<option value="03" {{ $monthFilter == '03' ? 'selected' : '' }}>March</option>
						<option value="04" {{ $monthFilter == '04' ? 'selected' : '' }}>April</option>
						<option value="05" {{ $monthFilter == '05' ? 'selected' : '' }}>May</option>
						<option value="06" {{ $monthFilter == '06' ? 'selected' : '' }}>June</option>
						<option value="07" {{ $monthFilter == '07' ? 'selected' : '' }}>July</option>
						<option value="08" {{ $monthFilter == '08' ? 'selected' : '' }}>August</option>
						<option value="09" {{ $monthFilter == '09' ? 'selected' : '' }}>September</option>
						<option value="10" {{ $monthFilter == '10' ? 'selected' : '' }}>October</option>
						<option value="11" {{ $monthFilter == '11' ? 'selected' : '' }}>November</option>
						<option value="12" {{ $monthFilter == '12' ? 'selected' : '' }}>December</option>
					</select>
				</div>
				<div class="form-group col-md-6">
					{{ Form::label('booking_year', 'Year') }}
					<select id="booking_year" name="booking_year" class="col-md-12 form-control">
						<option value="0" {{ $yearFilter == '0' ? 'selected' : '' }}></option>
						<option value="2022" {{ $yearFilter == '2022' ? 'selected' : '' }}>2022</option>
						<option value="2021" {{ $yearFilter == '2021' ? 'selected' : '' }}>2021</option>
						<option value="2020" {{ $yearFilter == '2020' ? 'selected' : '' }}>2020</option>
						<option value="2019" {{ $yearFilter == '2019' ? 'selected' : '' }}>2019</option>
						<option value="2018" {{ $yearFilter == '2018' ? 'selected' : '' }}>2018</option>
						<option value="2017" {{ $yearFilter == '2017' ? 'selected' : '' }}>2017</option>
						<option value="2016" {{ $yearFilter == '2016' ? 'selected' : '' }}>2016</option>
						<option value="2015" {{ $yearFilter == '2015' ? 'selected' : '' }}>2015</option>
						<option value="2014" {{ $yearFilter == '2014' ? 'selected' : '' }}>2014</option>
					</select>
				</div>
				<div class="form-group col-md-12">
					{{ Form::submit('Filter', array('name' => 'action', 'class' => 'btn btn-primary')) }}
					{{ Form::submit('Reset', array('name' => 'action', 'class' => 'btn btn-danger')) }}
				</div>
			{!! Form::close() !!}
			</div>
		</div>
	</div>
	<hr>
	<div class="table-container">
		<table class="table webee-table" id="dataTable">
			<thead>
				<th>Date Created</th>
				<!-- <th>Reference Number</th> -->
				<th>Party Leader Information</th>
				<!-- <th>Members</th> -->
				<th>First Day on Mountain</th>
				<th>Arrival/Departure Date</th>
				<th></th>
			</thead>
			<tbody>
			@foreach ($bookings as $booking)
				<tr>
					<td>{{ $booking->created_at }}</td>
					<!-- <td>{{ $booking->reference_number }}</td> -->
					<td><strong>{{ $booking->party_leader }}</strong> <br>
						{{ $booking->chalet_name }} <br>
						No. of People: {{ $booking->rentals->count() }}<br>
						REF #: {{ $booking->reference_number }}
					</td>
					<!-- <td>{{ $booking->rentals->count() }}</td> -->
					<td>{{ $booking->mountain_datetime }}</td>
					<td>
						{{ $booking->arrival_datetime }}<br>
						{{ $booking->departure_datetime }}
					</td>
					<td>
						<a href="{{ route('booking', $booking->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></a>
						<a href="#" class="btn btn-danger btn-icon" data-toggle="modal" data-target="#remove-booking-modal" data-id="{{ $booking->id }}"><i class="glyphicon glyphicon-trash" aria-hidden="true"></i></a>
						<div class="hidden">
							{!! Form::open(array('route' => array('booking.delete', $booking->id), 'method' => 'POST', 'class' => 'remove-booking', 'data-id' => $booking->id )) !!}
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
<div class="modal fade" id="remove-booking-modal" tabindex="-1" role="dialog" aria-labelledby="removeRenter">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="removal-modal-title">Delete Booking?</h4>
			</div>
			<div class="modal-body">
				<input type="text" class="form-control hidden" id="remove-booking">
		        <p id="removal-body-text">Are you sure you want to delete this booking?</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" id="remove-booking-btn">Continue</button>
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
			"order": [[ 0, "desc" ]],
			"pageLength": 50
		});

		jQuery('#remove-booking-modal').on('show.bs.modal', function (event) {
            var button = jQuery(event.relatedTarget);
            var booking = button.data('id');
            var modal = jQuery(this);

            modal.find('#remove-booking').val(booking);
        });

		jQuery('#remove-booking-btn').click(function (e) {
            e.preventDefault();
            var id = jQuery('#remove-booking').val();
            jQuery('.remove-booking[data-id="' + id + '"]').submit();
        });
	});
</script>
@endsection