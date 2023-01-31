@extends('layouts.app')

@section('title-bar')
	@include('partials._titlebar')
@endsection

@section('content')

    <div class="container">
        <div class="row">
        	<div class="col-md-10">
        		<div class="section-details">
                    <h2>Our Equipments</h2>
                    <p>Choose your equipment</p>
                </div>
            </div>
            <div class="col-md-2">
            	<div class="rack-toggle-container" >
            		<!-- <a id="rack-data-toggle" class="btn btn-default btn-lg" href="#"><span class="rack-count">{{ count($packages) }}</span>In Rack</a> -->
            		@if (count($packages) > 0)
            		<a class="btn btn-success btn-lg" href="{{ route('rentals') }}">Continue</a>
            		@endif
            	</div>
            </div> 
        </div>
        <hr>
        <div class="row">
            <div class="col-md-8">
                <div class="equipment-container">
                	<div>
						<ul id="equipCats" class="nav nav-tabs" role="tablist">
							<li role="presentation" class="active"><a href="#adult-skis" aria-controls="adult-skis" role="tab" data-toggle="tab">Adult Skis</a></li>
							<li role="presentation"><a href="#adult-snows" aria-controls="adult-snows" role="tab" data-toggle="tab">Adult Snowboards</a></li>
							<li role="presentation"><a href="#child-skis" aria-controls="child-skis" role="tab" data-toggle="tab">Child Skis</a></li>
							<li role="presentation"><a href="#child-snows" aria-controls="child-snows" role="tab" data-toggle="tab">Child Snowboards</a></li>
							<li role="presentation"><a href="#Extras" aria-controls="Extras" role="tab" data-toggle="tab">Extras</a></li>
							<div class="days-selector-container">
								<select id="days-selector" class="form-control">
								@for ($i = 1; $i <= $days; $i++)
									@if($days > 6)
									<option value="{{ $i }}" {{ $i == 6 ? 'selected="selected"' : '' }}>{{ $i }} {{ ($i == 1) ? 'day' : 'days' }}</option>
									@else
									<option value="{{ $i }}" {{ $i == $days ? 'selected="selected"' : '' }}>{{ $i }} {{ ($i == 1) ? 'day' : 'days' }}</option>
									@endif
								@endfor
								</select>
							</div>
						</ul>
						<div class="tab-content">
							<div role="tabpanel" class="tab-pane equipments active" id="adult-skis">
								<ul>
								@foreach ($equips as $equip)
									@if ($equip->category == 'Adult Skis')
										@include('partials._equipments')
									@endif
								@endforeach
								</ul>
							</div>
							<div role="tabpanel" class="tab-pane equipments" id="adult-snows">
								<ul>
								@foreach ($equips as $equip)
									@if ($equip->category == 'Adult Snowboards')
										@include('partials._equipments')
									@endif
								@endforeach
								</ul>
							</div>
							<div role="tabpanel" class="tab-pane equipments" id="child-skis">
								<div class="alert alert-warning" role="alert">
									<p>To select a ski length range, take your childs height and reduce by 20cm – actual ski length supplied on fitting will be charged. All under 13's will be given childrens equipment. Depending on their height / weight / foot size and ability, 13 - 15 yr olds may be upgraded to adult skis, to provide the most suitable and safest equipment.</p>
								</div>
								<ul>
								@foreach ($equips as $equip)
									@if ($equip->category == 'Child Skis')
										@include('partials._equipments')
									@endif
								@endforeach
								</ul>
							</div>
							<div role="tabpanel" class="tab-pane equipments" id="child-snows">
								<ul>
								@foreach ($equips as $equip)
									@if ($equip->category == 'Child Snowboards')
										@include('partials._equipments')
									@endif
								@endforeach
								</ul>
							</div>
							<div role="tabpanel" class="tab-pane equipments" id="Extras">
								<ul>
								@foreach ($equips as $equip)
									@if ($equip->category == 'Extras')
										@include('partials._equipments')
									@endif
								@endforeach
								</ul>
							</div>
						</div>
					</div>
                </div>
        	</div>
        	<div class="col-md-4">
        		@include('partials._rack')
        	</div>
        </div>
        <div class="row">
        	<div class="col-md-10">

            </div>
            <div class="col-md-2">
            	<div class="rack-toggle-container" >
            		<!-- <a id="rack-data-toggle" class="btn btn-default btn-lg" href="#"><span class="rack-count">{{ count($packages) }}</span>In Rack</a> -->
            		@if (count($packages) > 0)
            		<a class="btn btn-success btn-lg" href="{{ route('rentals') }}">Continue</a>
            		@endif
            	</div>
            </div> 
        </div>
    </div>
    <div class="modal fade" id="insurance-info-modal" tabindex="-1" role="dialog" aria-labelledby="insuranceInfo">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="insurance-info-modal-title">SkiHire2U Equipment Insurance</h4>
                </div>
                <div class="modal-body">
                    <p>If you opt for this, please make sure you read our terms and conditions to understand exactly what is covered. In summary:</p>
                    <p>Damage - all accidental damage to skis is covered providing they have not been mishandled or mistreated.</p>
                    <p>Theft - is covered only if a copy of the official police report is provided.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
	<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery('#equipCats a').click(function (e) {
				e.preventDefault();
				jQuery(this).tab('show');
			});

			jQuery('#rack-data-toggle').click(function (e) {
				e.preventDefault();
				jQuery('#rack-data').toggleClass('active');
			});

			jQuery('#close-side').click(function (e) {
				e.preventDefault();
				jQuery('#rack-data').toggleClass('active');
			});

			jQuery('#package-renter-modal').on('show.bs.modal', function (event) {
				var button = jQuery(event.relatedTarget);
				var id = button.data('id');
				jQuery(this).find('#package-id').val(id);
			});

			jQuery('#remove-from-rack-modal').on('show.bs.modal', function (event) {
                var button = jQuery(event.relatedTarget);
                var name = button.data('name');
                var id = button.data('id');
                var modal = jQuery(this);

                modal.find('#removal-modal-title').text(renterReplace(modal.find('#removal-modal-title').text(), name));
                modal.find('#removal-body-text').text(renterReplace(modal.find('#removal-body-text').text(), name));
                modal.find('#remove-renter-name').val(name);
                modal.find('#remove-renter-id').val(id);
            });

			jQuery('#remove-from-rack-modal').on('hidden.bs.modal', function (event) {
			  	var button = jQuery(event.relatedTarget);
			  	var modal = jQuery(this);

				modal.find('#removal-modal-title').text('REMOVE PACKAGE FOR: %renter%');
				modal.find('#removal-body-text').text('Are you sure you would like to remove the package for %renter% from the rack?');
			});

			jQuery('#remove-from-rack-btn').click(function (e) {
                e.preventDefault();
                var id = jQuery('#remove-renter-id').val();
                jQuery('.remove-from-rack[data-id="' + id + '"]').submit();
            });

			var days = jQuery('#days-selector').val();
			jQuery('.plain-prices.prices').show();
			jQuery('.plain-prices ul li.' + days).show();
			jQuery('.boots-prices ul li.' + days).show();

			jQuery('#days-selector').change(function() {
				var days = jQuery(this).val();
				jQuery('.prices-container ul li.price').hide();
				jQuery('.plain-prices ul li.' + days).show();
				jQuery('.boots-prices ul li.' + days).show();
				jQuery('.rack-details .rent-days').val(days);
			});

			jQuery('.boots-addon:checkbox').change(function() {
				var id = jQuery(this).data('id');
				if (jQuery(this).prop('checked')) {
					jQuery('.modal .plain-prices[data-id="' + id + '"]').hide();
					jQuery('.modal .boots-prices[data-id="' + id + '"]').show();
				} else {
					jQuery('.modal .plain-prices[data-id="' + id + '"]').show();
					jQuery('.modal .boots-prices[data-id="' + id + '"]').hide();
				}
			});

			@if($chalet['discount'])

			jQuery('.helmet-addon:checkbox').change(function() {
				var id = jQuery(this).data('id');
				var addonPrice = parseFloat(jQuery(this).data('price'));
				var addonIncrements = parseFloat(jQuery(this).data('increments'));
				var discounted1, discounted2, discountAmount1, discountAmount2;
				var days = jQuery('.rack-details[data-id="' + id + '"] .rent-days').val();
				var discount = parseFloat(jQuery('.final-price[data-id="' + id + '"] .discount small').text())/100;
				var val1 = parseFloat(jQuery('.modal .plain-prices[data-id="' + id + '"] .price.' + days + ' .price-small small').text());
				var val2 = parseFloat(jQuery('.modal .boots-prices[data-id="' + id + '"] .price.' + days + ' .price-small small').text());

				var addon = addonPrice + (addonIncrements * (parseFloat(days) - 1));

				if (jQuery(this).prop('checked')) {
					val1 = val1 + addon;
					val2 = val2 + addon;
				} else {
					val1 = val1 - addon;
					val2 = val2 - addon;
				}
				discountAmount1 = val1 * discount;
				discountAmount2 = val2 * discount;
				discounted1 = val1 - discountAmount1;
				discounted2 = val2 - discountAmount2;
				jQuery('.modal .plain-prices[data-id="' + id + '"] .price.' + days + ' .price-small small').text(val1.toFixed(2));
				jQuery('.modal .boots-prices[data-id="' + id + '"] .price.' + days + ' .price-small small').text(val2.toFixed(2));
				jQuery('.modal .plain-prices[data-id="' + id + '"] .price.' + days + ' .discount-amount small').text(discountAmount1.toFixed(2));
				jQuery('.modal .boots-prices[data-id="' + id + '"] .price.' + days + ' .discount-amount small').text(discountAmount2.toFixed(2));
				jQuery('.modal .plain-prices[data-id="' + id + '"] .price.' + days + ' .final small').text(discounted1.toFixed(2));
				jQuery('.modal .boots-prices[data-id="' + id + '"] .price.' + days + ' .final small').text(discounted2.toFixed(2));
			});

			jQuery('.insurance-addon:checkbox').change(function() {
				var discounted1, discounted2, discountAmount1, discountAmount2;
				var id = jQuery(this).data('id');
				var days = jQuery('.rack-details .rent-days').val();
				var addonIncrements = parseFloat(jQuery(this).data('increments'));
				var less1 = parseFloat(jQuery('.modal .plain-prices[data-id="' + id + '"] .price.' + days + ' .discount-amount small').text());
				var less2 = parseFloat(jQuery('.modal .boots-prices[data-id="' + id + '"] .price.' + days + ' .discount-amount small').text());
				var val1 = parseFloat(jQuery('.modal .plain-prices[data-id="' + id + '"] .price.' + days + ' .price-small small').text());
				var val2 = parseFloat(jQuery('.modal .boots-prices[data-id="' + id + '"] .price.' + days + ' .price-small small').text());

				var addon = addonIncrements * parseFloat(days);

				if (jQuery(this).prop('checked')) {
					val1 = val1 + addon;
					val2 = val2 + addon;
				} else {
					val1 = val1 - addon;
					val2 = val2 - addon;
				}
				discounted1 = val1 - less1;
				discounted2 = val2 - less2;
				jQuery('.modal .plain-prices[data-id="' + id + '"] .price.' + days + ' .price-small small').text(val1.toFixed(2));
				jQuery('.modal .boots-prices[data-id="' + id + '"] .price.' + days + ' .price-small small').text(val2.toFixed(2));
				jQuery('.modal .plain-prices[data-id="' + id + '"] .price.' + days + ' .final small').text(discounted1.toFixed(2));
				jQuery('.modal .boots-prices[data-id="' + id + '"] .price.' + days + ' .final small').text(discounted2.toFixed(2));
			});

			@else

			jQuery('.helmet-addon:checkbox').change(function() {
				var id = jQuery(this).data('id');
				var days = jQuery('.rack-details[data-id="' + id + '"] .rent-days').val();
				var addonPrice = parseFloat(jQuery(this).data('price'));
				var addonIncrements = parseFloat(jQuery(this).data('increments'));
				var val1 = parseFloat(jQuery('.modal .plain-prices[data-id="' + id + '"] .price.' + days + ' .final small').text());
				var val2 = parseFloat(jQuery('.modal .boots-prices[data-id="' + id + '"] .price.' + days + ' .final small').text());

				var addon = addonPrice + (addonIncrements * (parseFloat(days) - 1));

				if (jQuery(this).prop('checked')) {
					val1 = val1 + addon;
					val2 = val2 + addon;
				} else {
					val1 = val1 - addon;
					val2 = val2 - addon;
				}
				jQuery('.modal .plain-prices[data-id="' + id + '"] .price.' + days + ' .final small').text(val1.toFixed(2));
				jQuery('.modal .boots-prices[data-id="' + id + '"] .price.' + days + ' .final small').text(val2.toFixed(2));
			});

			jQuery('.insurance-addon:checkbox').change(function() {
				var id = jQuery(this).data('id');
				var days = jQuery('.rack-details .rent-days').val();
				var addonIncrements = jQuery(this).data('increments');
				var val1 = parseFloat(jQuery('.modal .plain-prices[data-id="' + id + '"] .price.' + days + ' .final small').text());
				var val2 = parseFloat(jQuery('.modal .boots-prices[data-id="' + id + '"] .price.' + days + ' .final small').text());

				var addon = addonIncrements * days;

				if (jQuery(this).prop('checked')) {
					val1 = val1 + addon;
					val2 = val2 + addon;
				} else {
					val1 = val1 - addon;
					val2 = val2 - addon;
				}
				jQuery('.modal .plain-prices[data-id="' + id + '"] .price.' + days + ' .final small').text(val1.toFixed(2));
				jQuery('.modal .boots-prices[data-id="' + id + '"] .price.' + days + ' .final small').text(val2.toFixed(2));
			});

			@endif
		});

		function renterReplace(originalString, newString) {
			return originalString.replace(/%renter%/, newString);
		}

		function addAddon(price, addon) {

		}

		function removeAddon(price, addon) {
			return price + addon;
		}
		

	</script>
@endsection