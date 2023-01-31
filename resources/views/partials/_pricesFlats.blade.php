<li class="{{ $key }} price">
	@if($chalet['discount'])
	<span class="final">&euro; <small>{{ number_format(($value->flat - ($value->flat * ((int) $chalet['discount'] / 100) )), 2, '.', ',') }}</small></span>
	<span class="price-small strike">&euro; <small>{{ number_format($value->flat, 2, '.', ',') }}</small></span>
	<span class="discount-amount">Less: ( - &euro; <small>{{ number_format($value->flat * ((int) $chalet['discount'] / 100), 2, '.', ',') }}</small> )</span>
	@else
	<span class="final">&euro; <small>{{ number_format($value->flat, 2, '.', ',') }}</small></span>
	@endif
</li>