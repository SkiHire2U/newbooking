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
	@for ($i = 1; $i < 16; $i++)
	<div class="row form-group">
		<div class="col-xs-2">
			<span class="day">{{ $i }}</span>
		</div>
		<div class="col-xs-5">
			<div class="input-group">
				<div class="input-group-addon">&euro;</div>
      			<input name="prices[{{ $i }}][flat]" type="number" step="0.01" class="form-control" required>
			</div>
		</div>
		<div class="col-xs-5">
			<div class="input-group">
				<div class="input-group-addon">&euro;</div>
      			<input name="prices[{{ $i }}][boots]" type="number" step="0.01" class="form-control" required>
			</div>
		</div>
	</div>
	@endfor
</div>