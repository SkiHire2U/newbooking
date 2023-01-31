@extends('layouts.app')

@section('title-bar')
	@include('partials._adminMenu')
@endsection

@section('styles')
<link rel="stylesheet" type="text/css" href="/css/admin.css">
<link rel="stylesheet" type="text/css" href="/css/jquery.dataTables.min.css">
<style>
@media print{
    @page {
        size: landscape;
    }
}
</style>
@endsection

@section('content')
<div class="container">
	<h2>Picking List</h2>
	<div class="admin-button-container">
		<a id="print-picking-list" href="#" class="btn btn-info btn-icon" title="Print Picking List"><i class="fa fa-print"></i>Print</a>
	</div>
</div>
<div class="container">
	<div id="picking-list" class="printable" media="print">
		<h4>Booking Information</h4>
		<div class="booking-data">
			<table class="webee-table">
				<tr>
					<td width="50%">
						<p><strong>Chalet: </strong> {{ $booking->chalet_name }}</p>
						@if ($booking->chalet_id == 1)
						<p><strong>Accommodation Address: </strong> {{ $booking->chalet_address }}</p>
						@endif
						<p><strong>Party Leader: </strong> {{ $booking->party_leader }}</p>
						<p><strong>Party Email: </strong> {{ $booking->party_email }}</p>
						<p><strong>Party Mobile: </strong> {{ $booking->party_mobile }}</p>
						<p><strong>Reference Number: </strong> {{ $booking->reference_number }}</p>
						<p><strong>Notes: </strong> {{ $booking->notes }}</p>
					</td>
					<td width="50%" class="booking-dates">
						<p><strong>Arrival Date: </strong> {{ $booking->arrival_datetime }}</p>
						<p><strong>First Day on Mountain: </strong> {{ $booking->mountain_datetime }}</p>
						<p><strong>Departure Date: </strong> {{ $booking->departure_datetime }}</p>
					</td>
				</tr>
			</table>
		</div>
		<hr>
		<table class="table webee-table table-bordered">
			<thead>
				<tr>
					<th></th>
					<th>Name</th>
					<th>Details</th>
					<th>Package/Notes</th>
					<th>Add on</th>
					<!-- <th>Ski</th> -->
					<th>Pole</th>
					<th>Boots</th>
					<!-- <th>Code</th> -->
					<th>DIN</th>
					<th class="serial-column" width="25%">Management Notes</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($booking->rentals as $rental)
				<tr>
					<td>{{ $loop->iteration }}</td>
					<td><strong>{{ $rental->name }}</strong></td>
					<td>
						<p>{{ $select['age'][$rental->age] }}</p>
						<p>{{ $rental->sex }}</p>
						<p>Ability: <strong>{{ $select['level'][$rental->ability] }}</strong></p>
						<p>Height: <strong>{{ $select['height'][$rental->height] }}</strong></p>
						<p>Weight: <strong>{{ $select['weight'][$rental->weight] }}</strong></p>
					</td>
					<td>
						<p>{{ $packageModel->getPackageName($rental->package_id) }}</p>
						<p><strong>{{ $packageModel->getPackageLevel($rental->package_id) }}</strong></p>
						@if($rental->notes)
						<p>Notes: <strong>{{ $rental->notes }}</strong></p>
						@endif
					</td>
					<td>
						@if($rental->addons->boots == 'on')
						<p>Boots</p>
						@endif
						@if($rental->addons->helmet == 'on')
						<p>Helmet</p>
						@endif
						@if($rental->addons->insurance == 'on')
						<p>Insurance</p>
						@endif
                    </td>
					<!-- <td><p><strong>{{ $rental->ski_length}}</strong></p></td> -->
					<td><p><strong>{{ $rental->pole_length}}</strong></p></td>
					<td>
						@if($rental->addons->boots == 'on')
						<p><strong>{{ $rental->boot_size}}</strong></p>
						@endif
					</td>
					<!-- <td><p><strong>{{ $rental->skier_code}}</strong></p></td> -->
					<td><strong>{{ $rental->din}}</strong></p></td>
					<td></td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
@endsection

@section('scripts')
<!-- <script type="text/javascript" src="/js/jquery.dataTables.min.js"></script> -->
<script type="text/javascript">
	jQuery(document).ready(function() {
		// jQuery('#dataTable').DataTable();

		jQuery('#print-picking-list').click(function(e) {
			e.preventDefault();
			var printContents = jQuery('#picking-list').html();
			var originalContents = jQuery('body').html();

			jQuery('body').html(printContents);

			window.print();

			location.reload();

			return false;

			//jQuery('body').html(originalContents);
		});
	});
</script>
@endsection