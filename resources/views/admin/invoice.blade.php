@extends('layouts.app')

@section('title-bar')
	@include('partials._adminMenu')
@endsection

@section('styles')
<link rel="stylesheet" type="text/css" href="/css/admin.css">
<style>
.table img.logo {
	width: 350px;
	height: auto;
}
hr.line-spacer {
	margin: 0;
    border-color: #FFF;
}
@media print{
    @page {
        size: portrait;
    }
    .table img.logo {
		width: 350px;
		height: 80px;
	}
}
</style>
@endsection

@section('content')
<div class="container">
	<h2>Invoice</h2>
	<div class="admin-button-container">
		<a href="{{ route('invoice.edit', $booking->id) }}" class="btn btn-success btn-icon" title="Edit Invoice"><i class="fa fa-pencil"></i>Edit</a>
		<a id="print-invoice" href="#" class="btn btn-info btn-icon" title="Print Invoice"><i class="fa fa-print"></i>Print</a>
	</div>
</div>
<div class="container">
	<div id="invoice" class="printable" media="print">
		<hr class="line-spacer"/>
		<table class="table webee-table">
			<thead>
				<tr>
					<th><img class="logo" src="/images/logo.png" width="350" height="80"></th>
					<th></th>
					<th width="50%"><h2>Booking Invoice</h2></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<table>
							<tr>
								<td><strong>SkiHire2U S.A.R.L.</strong></td>
							</tr>
							<tr>
								<td>1351 Route de Vonnes</td>
							</tr>
							<tr>
								<td>Chatel</td>
							</tr>
							<tr>
								<td>74390</td>
							</tr>
							<tr>
								<td>France</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
							<tr>
								<td>TVA FR41817602857</td>
							</tr>
						</table>
					</td>
					<td></td>
					<td>
						<table>
							<tr>
								<td>
									<p><strong>Chalet: </strong> {{ $booking->chalet_name }}</p>
									@if ($booking->chalet_id == 1)
									<p><strong>Accommodation Address: </strong> {{ $booking->chalet_address }}</p>
									@endif
								</td>
							</tr>
							<tr>
								<td>
									<p><strong>Party Leader: </strong> {{ $booking->party_leader }}</p>
								</td>
							</tr>
							<tr>
								<td>
									<p><strong>Party Email: </strong> {{ $booking->party_email }}</p>
								</td>
							</tr>
							<tr>
								<td>
									<p><strong>Party Mobile: </strong> {{ $booking->party_mobile }}</p>
								</td>
							</tr>
							<tr>
								<td>
									<p><strong>REF #: </strong> {{ $booking->reference_number }}</p>
								</td>
							</tr>
							<tr>
								<td>
									<p><strong>Notes: </strong> {{ $booking->notes }}</p>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
		<table class="table webee-table table-bordered">
			<thead>
				<tr>
					<th></th>
					<th>Name</th>
					<th>Package</th>
					<th>Duration/Add on</th>
					<th>Price</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($booking->rentals as $rental)
				<tr>
					<td>{{ $loop->iteration }}</td>
					<td><strong>{{ $rental->name }}</strong></td>
					<td>
						<p>{{ $packageModel->getPackageName($rental->package_id) }}</p>
						<p><strong>{{ $packageModel->getPackageLevel($rental->package_id) }}</strong></p>
						<p>Duration: <strong>{{ $rental->duration }} {{ ($rental->duration == 1) ? 'day' : 'days' }}</strong></p>
					</td>
					<td>
						@if($rental->addons->boots == 'on')
						<p>Boots</p>
						@endif
						@if($rental->addons->helmet == 'on')
						<p>
							Helmet
                        	@if( (int) $rental->age <= 3 || $packageModel->getPackageType($rental->package_id) == 'Child')
					    	<small class="free-helmet">FREE!</small>
					    	@endif
						</p>
						@endif
						@if($rental->addons->insurance == 'on')
						<p>Insurance</p>
						@endif
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
				</tr>
				@endforeach
			</tbody>
			<tfoot>
				<tr>
					<td colspan="4" class="total-cell">Total</td>
					<td class="total-prices">
                        @if ($invoice->discount != 0)
                        <p class="total-price">&euro; {{ $invoice->total }}</p>
                        <p class="total-price strike"><small>&euro; {{ $invoice->subtotal }}</small></p>
                        <p class="discount-amount">Less: ( &euro; - <small>{{ $invoice->discount }}</small> )</p>
                        @else
                        <p class="total-price">&euro; {{ $invoice->total }}</p>
                        @endif
                    </td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
	jQuery(document).ready(function() {

		jQuery('#print-invoice').click(function(e) {
			e.preventDefault();
			var printContents = jQuery('#invoice').html();
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