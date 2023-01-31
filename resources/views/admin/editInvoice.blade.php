@extends('layouts.app')

@section('title-bar')
	@include('partials._adminMenu')
@endsection

@section('styles')
<link rel="stylesheet" type="text/css" href="/css/admin.css">
<style>
@media print{
    @page {
        size: portrait;
    }
}
</style>
@endsection

@section('content')
<div class="container">
	<div id="invoice" class="printable" media="print">
		<h2>Edit Invoice</h2>
		{!! Form::open(array('route' => array('invoice.update', $booking->id), 'method' => 'PUT', 'class' => 'invoice-edit', 'id' => 'invoice-edit' )) !!}
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
						<div class="form-group">
							<label for="rental_price[{{ $rental->id }}]">Price</label>
							<input name="rental_price[{{ $rental->id }}]" type="number" step="any" class="rental-price form-control" value="{{ $invoice->rental_prices[$rental->id]['price'] }}" required data-id="{{$rental->id}}">
                        </div>
                        <div class="form-group">
							<label for="rental_discount[{{ $rental->id }}]">Discount</label>
							<input name="rental_discount[{{ $rental->id }}]" type="number" step="any" class="rental-discount form-control" value="{{ $invoice->rental_prices[$rental->id]['discount'] }}" required data-id="{{$rental->id}}">
                        </div>
                        <div class="form-group">
							<label for="rental_total[{{ $rental->id }}]">Discounted Price</label>
							<input name="rental_total[{{ $rental->id }}]" type="number" step="any" class="rental-total form-control" value="{{ $invoice->rental_prices[$rental->id]['total'] }}" readonly data-id="{{$rental->id}}">
                        </div>
					</td>
				</tr>
				@endforeach
			</tbody>
			<tfoot>
				<tr>
					<td colspan="4" class="total-cell">Total</td>
					<td>
						<div class="form-group">
							<label for="subtotal">Subtotal</label>
							<input name="subtotal" type="number" step="any" id="invoice-subtotal" class="form-control" value="{{ $invoice->subtotal }}" readonly data-id="subtotal">
                        </div>
                        <div class="form-group">
							<label for="discount">Discount</label>
							<input name="discount" type="number" step="any" id="invoice-discount" class="form-control" value="{{ $invoice->discount }}" required data-id="discount">
                        </div>
                        <div class="form-group">
							<label for="total">Total</label>
							<input name="total" type="number" step="any" id="invoice-total" class="form-control" value="{{ $invoice->total }}" readonly data-id="total">
                        </div>
                        <button type="submit" class="btn btn-success right">Save</button>
                    </td>
				</tr>
			</tfoot>
		</table>
		{!! Form::close() !!}
	</div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('.rental-price').change(function() {
			var id = jQuery(this).data('id');
			var total = returnTotal(id);

			updateSubtotal();
			updateTotal();

			jQuery('.rental-total[data-id="' + id + '"]').val(total.toFixed(2));
		});

		jQuery('.rental-discount').change(function() {
			var id = jQuery(this).data('id');
			var total = returnTotal(id);

			updateDiscount();
			updateTotal();

			jQuery('.rental-total[data-id="' + id + '"]').val(total.toFixed(2));
		});

		jQuery('#invoice-discount').change(function() {
			updateTotal();
		});

		jQuery('.rental-total').change(function() {
			alert(jQuery(this).val());
		});

		jQuery(window).keydown(function(event){
			if(event.keyCode == 13) {
				event.preventDefault();
				return false;
			}
		});
	});

	function returnTotal(id) {
		var price = parseFloat(jQuery('.rental-price[data-id="' + id + '"]').val());
		var discount = parseFloat(jQuery('.rental-discount[data-id="' + id + '"]').val());

		return price - discount;
	}

	function updateSubtotal() {
		//var subtotal = jQuery('#invoice-subtotal').val();
		var subtotal = 0;
		jQuery('tr .rental-price').each(function() {
			subtotal += parseFloat(jQuery(this).val());
		});

		jQuery('#invoice-subtotal').val(subtotal.toFixed(2));
	}

	function updateDiscount() {
		//var discount = jQuery('#invoice-discount').val();
		var discount = 0;
		jQuery('tr .rental-discount').each(function() {
			discount += parseFloat(jQuery(this).val());
		});

		jQuery('#invoice-discount').val(discount.toFixed(2));
	}

	function updateTotal() {
		var subtotal = parseFloat(jQuery('#invoice-subtotal').val());
		var discount = parseFloat(jQuery('#invoice-discount').val());
		var total = subtotal - discount;

		jQuery('#invoice-total').val(total.toFixed(2));
	}
</script>
@endsection