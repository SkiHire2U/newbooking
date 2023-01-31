<li class="{{ $key }} price">
	@if($chalet['discount'])
	<span class="final">&euro; <small>{{ number_format(($value->boots - ($value->boots * ((int) $chalet['discount'] / 100) )), 2, '.', ',') }}</small></span>
	<span class="price-small strike">&euro; <small>{{ number_format($value->boots, 2, '.', ',') }}</small></span>
	<span class="discount-amount">Less: ( - &euro; <small>{{ number_format($value->boots * ((int) $chalet['discount'] / 100), 2, '.', ',') }}</small> )</span>
	@else
	<span class="final">&euro; <small>{{ number_format($value->boots, 2, '.', ',') }}</small></span>
	@endif
</li>