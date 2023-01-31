<div class="price-input-container">
	<div class="row">
		<div class="col-xs-2">
			<p>Day</p>
		</div>
		<div class="col-xs-5">
			<p>Without Boots</p>
		</div>
		<div class="col-xs-5">
			<p>With Boots</p>
		</div>
	</div>
	@foreach($package->prices as $key => $price)
	<div class="row form-group">
		<div class="col-xs-2">
			<span class="day">{{ $key }}</span>
		</div>
		<div class="col-xs-5">
			<div class="input-group">
				<div class="input-group-addon">&euro;</div>
      			<input name="prices[{{ $key }}][flat]" type="number" step="0.01" class="form-control" required value="{{ $price->flat }}">
			</div>
		</div>
		<div class="col-xs-5">
			<div class="input-group">
				<div class="input-group-addon">&euro;</div>
      			<input name="prices[{{ $key }}][boots]" type="number" step="0.01" class="form-control" required value="{{ $price->boots }}">
			</div>
		</div>
	</div>
	@endforeach
</div>