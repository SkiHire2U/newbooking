@extends('layouts.app')

@section('styles')
    {!! Html::style('css/bootstrap-datetimepicker.min.css') !!}
    {!! Html::style('/css/bootstrap-select.min.css') !!}
@endsection

@section('title-bar')
	@include('partials._titlebar')
@endsection

@section('content')
    @if(Session::has('reference'))
    <div class="container">
        <div class="row">
            <div class="col-md-10">
                <div class="section-details">
                    <h2>Booking Information</h2>
                </div>
            </div>
            <div class="col-md-2">
            @if($button)
                @if($details['booking_id'])
                <a href="#" data-toggle="modal" data-target="#save-and-exit-modal" class="btn btn-danger btn-lg pull-right">Save and Exit</a>
                <div class="hidden">
                {!! Form::open(array('route' => array('reference.update', $details['booking_id']), 'method' => 'POST', 'id' => 'save-and-exit' )) !!}
                {!! Form::close() !!}
                </div>
                @endif
            @endif
            </div>
        </div>
        {!! Form::open(['route' => array('reference.updateDetails', $details['booking_id']), 'method' => 'POST', 'data-parsley-validate' => '']) !!}
        <div class="row">
            <div class="col-md-6">
                <div class="form-section">
                    <div class="form-group">
                        {{ Form::label('chalet_id', 'Chalet') }}
                        <select id="chalet_id" name="chalet_id" class="col-md-12 custom-select selectpicker" data-show-subtext="true" data-live-search="true">
                            @foreach ($operators as $operator)
                            @if ($operator->is_active == 1)
                            @foreach ($operator->accommodations as $acc)
                            @if ($acc->is_active == 1)
                            <option value="{{ $acc->id }}" data-subtext="{{ $acc->operator->name }}" {{ $details['chalet_id'] == $acc->id ? 'selected="selected"' : '' }}>{{ $acc->name }}</option>
                            @endif
                            @endforeach
                            @endif
                            @endforeach
                        </select>
                    </div>
                    <div id="independent-info" class="hidden">
                        <div class="form-group">
                            {{ Form::label('chalet_name', 'Name of Accommodation') }}
                            {{ Form::text('chalet_name', $details['chalet_name'], array('id' => 'chalet-name', 'class' => 'form-control', 'required' => '')) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('chalet_address', 'Address') }}
                            {{ Form::text('chalet_address', $details['chalet_address'], array('id' => 'chalet-address', 'class' => 'form-control', 'required' => '')) }}
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-section">
                    <div class="form-group">
                        {{ Form::label('arrival_dtp', 'Arrival Date/Time') }}
                        <div class='input-group date' id='arrival_dtp'>
                            <input name="arrival_dtp" type='text' class="form-control" required />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('departure_dtp', 'Departure Date/Time') }}
                        <div class='input-group date' id='departure_dtp'>
                            <input name="departure_dtp" type='text' class="form-control" required />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('mountain_dtp', 'First day on Mountain') }}
                        <div class='input-group date' id='mountain_dtp'>
                            <input name="mountain_dtp" type='text' class="form-control" required />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-section">
                    <div class="form-group">
                        {{ Form::label('party_leader', 'Party Leader Name') }}
                        {{ Form::text('party_leader', $details['party_leader'], array('class' => 'form-control', 'required' => '', 'data-parsley-required' => 'true')) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('party_email', 'Email Address') }}
                        {{ Form::email('party_email', $details['party_email'], array('class' => 'form-control', 'required' => '', 'data-parsley-required' => 'true')) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('party_mobile', 'Mobile Number') }}
                        {{ Form::text('party_mobile', $details['party_mobile'], array('class' => 'form-control', 'required' => '', 'data-parsley-required' => 'true')) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('party_notes', 'Notes') }}
                        {{ Form::textarea('party_notes', $details['party_notes'], array('size' => '10x5', 'class' => 'form-control')) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-10">
                <div class="alert alert-info" role="alert">
                    <p>If you wish to adjust the Rental Days or update any information regarding your booking, click on the UPDATE Details on the right before you Save and Exit or the changes will be lost.</p>
                </div>
            </div>
            <div class="col-md-2">
                {{ Form::submit('Update Details', array('class' => 'btn btn-success btn-lg pull-right')) }}
            </div>
        </div>
        
        {!! Form::close() !!}
    </div>
    <hr>
    @endif
    <div class="container">
        <div class="row">
        	<div class="col-md-10">
        		<div class="section-details">
                    <h2>Your rentals</h2>
                </div>
            </div>
            <div class="col-md-2">
            @if($button)
                @if($details['booking_id'])
                @else
                <a href="{{ route('details') }}" class="btn btn-success btn-lg pull-right">Continue</a>
                @endif
            @endif
            </div>
        </div>
        <div class="row">
        	<div class="col-md-12">
        		<div class="alert alert-info" role="alert">
        			<p><strong>SAVE EACH PACKAGE ONE AT A TIME.</strong></p>
					<p>There's no rush, start filling in details for any package you like but <strong>remember to press 'Save' for that package to avoid losing your inputs</strong>.</p>
                    <p>Helmets are free for all children under 16. </p>
				</div>
        	</div>
        </div>
        <hr>
        <div class="row">
        	<div class="col-md-12">
        		<div class="rental-information-table">
        			<div id="rental-table" class="">
                        <div class="row">
                            <div class="col-md-3 rental-table-header">Personal Details</div>
                            <div class="col-md-3 rental-table-header">Measurements</div>
                            <div class="col-md-4 rental-table-header equipments">Equipment</div>
                            <div class="col-md-2 rental-table-header price">Price</div>
                        </div>
                        @foreach ($packages as $key => $package)
                        @if($package['rent_status'] == 'new')
                        <div class="row">
        					<div id="equip-{{ $loop->iteration }}" class="equip-row warning clearfix" data-id="{{ $loop->iteration }}">
                                {!! Form::open(array('route' => array('saveRenter', $key), 'method' => 'POST', 'class' => 'form-renter-info', 'data-parsley-validate' => '')) !!}
        						<div class="col-md-3">
                                    <div class="personal-details-container">
                                        <div class="form-group">
                                            {{ Form::label('package_renter', 'Full Name:') }}
                                            {{ Form::text('package_renter', $package['package_renter'], array('class' => 'renter-name form-control', 'required' => '', 'data-parsley-required' => 'true')) }}
                                        </div>
                                        <div class="form-group">
                                            {{ Form::label('renter_age', 'Age:') }}
                                            {{ Form::select('renter_age', $select['age'], null, array('class' => 'renter-age form-control', 'required' => '', 'data-parsley-required' => 'true')) }}
                                        </div>
                                        <div class="form-group">
                                            {{ Form::label('renter_sex', 'Sex:') }}
                                            {{ Form::select('renter_sex', array('Male' => 'Male', 'Female' => 'Female'), null, array('class' => 'renter-sex form-control', 'required' => '', 'data-parsley-required' => 'true')) }}
                                        </div>
                                        <div class="form-group">
                                            {{ Form::label('renter_notes', 'Notes') }}<span> (optional)</span>
                                            {{ Form::text('renter_notes', '', array('class' => 'renter-notes form-control')) }}
                                        </div>
                                    </div>
                                </div>
        						<div class="col-md-3">
                                    <div class="measurements-container">
                                        <div class="form-group">
                                            {{ Form::label('renter_ability', 'Ability:') }}
                                            {{ Form::select('renter_ability', $select['level'], null, array('class' => 'renter-abiity form-control', 'required' => '', 'data-parsley-required' => 'true')) }}
                                        </div>
                                        <div class="form-group">
                                            {{ Form::label('renter_height', 'Height:') }}
                                            {{ Form::select('renter_height', $select['height'], null, array('class' => 'renter-height form-control', 'required' => '', 'data-parsley-required' => 'true')) }}
                                        </div>
                                        <div class="form-group">
                                            {{ Form::label('renter_weight', 'Weight:') }}
                                            {{ Form::select('renter_weight', $select['weight'], null, array('class' => 'renter-weight form-control', 'required' => '', 'data-parsley-required' => 'true')) }}
                                        </div>
                                        <div class="form-group">
                                            {{ Form::label('renter_foot', 'Foot Size:') }}
                                            {{ Form::select('renter_foot', $select['foot'], null, array('class' => 'renter-foot form-control', 'required' => '', 'data-parsley-required' => 'true')) }}
                                            <a href="#" data-toggle="modal" data-target="#foot-size-conversion-table"><small>Foot Size Conversion Table</small></a>
                                            @include('partials._footsize')
                                        </div>
                                    </div>
                                </div>
        						<div class="col-md-4">
                                    <div class="equipments-container">
                                        <div class="form-group">
                                            {{ Form::label('package_id', 'Package:') }}
                                            {{ Form::select('package_id', $select['packages'], $package['package_id'], array('class' => 'renter-package form-control', 'required' => '')) }}
                                        </div>
                                        <div class="form-group">
                                            <label for="rent_days">Duration:</label>
                                            <select name="rent_days" class="form-control">
                                            @for ($i = 1; $i <= $days; $i++)
                                                <option value="{{ $i }}" {{ $i == $package['rent_days'] ? 'selected="selected"' : '' }}>{{ $i }} {{ ($i == 1) ? 'day' : 'days' }}</option>
                                            @endfor
                                            </select>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Add on</label>
                                                    <div class="checkbox addon">
                                                        <label>
                                                            <input name="addon[boots]" type="hidden" value="off">
                                                            <input name="addon[boots]" class="boots-addon" type="checkbox" {{ ($package['addon']['boots'] == 'on') ? 'checked' : '' }} >
                                                            Boots
                                                        </label>
                                                    </div>
                                                    <div class="checkbox addon">
                                                        <label>
                                                            <input name="addon[helmet]" type="hidden" value="off">
                                                            <input name="addon[helmet]" class="helmet-addon" type="checkbox" {{ ($package['addon']['helmet'] == 'on') ? 'checked' : '' }} >
                                                            Helmet
                                                        </label>
                                                    </div>
                                                    <div class="checkbox addon">
                                                        <label>
                                                            <input name="addon[insurance]" type="hidden" value="off">
                                                            <input name="addon[insurance]" class="insurance-addon" type="checkbox" {{ ($package['addon']['insurance'] == 'on') ? 'checked' : '' }} >
                                                            Insurance
                                                        </label>
                                                        <a href="#" class="" data-toggle="modal" data-target="#insurance-info-modal"><i class="fa fa-question-circle"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="price-note"><strong>Note: </strong>Prices will be updated once saved</p>
                                                <div class="hidden">
                                                    {{ Form::text('rent_status', 'saved') }}
                                                </div>
                                                <button type="submit" class="btn btn-block btn-success">Save</button>
                                                <a href="#" class="btn btn-block btn-danger" data-toggle="modal" data-target="#remove-from-rack-modal" data-id="{{ $key }}" data-name="{{ $package['package_renter'] }}">Delete</a>
                                            </div>
                                        </div>
                                    </div> 
                                </div>
        						<div class="col-md-2">
                                    <div class="rentals-prices-container">
                                        @if ($chalet['discount'])
                                        <span class="price">&euro; {{ number_format($prices[$key]['discounted'], 2, '.', ',') }}</span>
                                        <span class="price strike"><small>&euro; {{ number_format($prices[$key]['originalAmount'], 2, '.', ',') }}</small></span>
                                        <span class="discount-amount">Less: ( - &euro; <small>{{ number_format($prices[$key]['discountAmount'], 2, '.', ',') }}</small> )</span>
                                        @else
                                        <span class="price">&euro; {{ number_format($prices[$key]['originalAmount'], 2, '.', ',') }}</span>
                                        @endif
                                    </div>
                                </div>
                                {!! Form::close() !!}
        					</div>
                        </div>
                        @elseif($package['rent_status'] == 'edit')
                        <div class="row">
                            <div id="equip-{{ $loop->iteration }}" class="equip-row danger clearfix" data-id="{{ $loop->iteration }}">
                                {!! Form::open(array('route' => array('saveRenter', $key), 'method' => 'POST', 'class' => 'form-renter-info', 'data-parsley-validate' => '')) !!}
                                <div class="col-md-3">
                                    <div class="form-input-container">
                                        <div class="form-group">
                                            {{ Form::label('package_renter', 'Full Name:') }}
                                            {{ Form::text('package_renter', $package['package_renter'], array('class' => 'renter-name form-control', 'required' => '', 'data-parsley-required' => 'true')) }}
                                        </div>
                                        <div class="form-group">
                                            {{ Form::label('renter_age', 'Age:') }}
                                            {{ Form::select('renter_age', $select['age'], $package['renter_age'], array('class' => 'renter-age form-control', 'required' => '', 'data-parsley-required' => 'true')) }}
                                        </div>
                                        <div class="form-group">
                                            {{ Form::label('renter_sex', 'Sex:') }}
                                            {{ Form::select('renter_sex', array('Male' => 'Male', 'Female' => 'Female'), $package['renter_sex'], array('class' => 'renter-sex form-control', 'required' => '', 'data-parsley-required' => 'true')) }}
                                        </div>
                                        <div class="form-group">
                                            {{ Form::label('renter_notes', 'Notes') }}<span> (optional)</span>
                                            {{ Form::text('renter_notes', ($package['renter_notes'] != '') ? $package['renter_notes'] : '', array('class' => 'renter-notes form-control')) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-input-container">
                                        <div class="form-input-container">
                                            <div class="form-group">
                                                {{ Form::label('renter_ability', 'Ability:') }}
                                                {{ Form::select('renter_ability', $select['level'], $package['renter_ability'], array('class' => 'renter-abiity form-control', 'required' => '', 'data-parsley-required' => 'true')) }}
                                            </div>
                                            <div class="form-group">
                                                {{ Form::label('renter_height', 'Height:') }}
                                                {{ Form::select('renter_height', $select['height'], $package['renter_height'], array('class' => 'renter-height form-control', 'required' => '', 'data-parsley-required' => 'true')) }}
                                            </div>
                                            <div class="form-group">
                                                {{ Form::label('renter_weight', 'Weight:') }}
                                                {{ Form::select('renter_weight', $select['weight'], $package['renter_weight'], array('class' => 'renter-weight form-control', 'required' => '', 'data-parsley-required' => 'true')) }}
                                            </div>
                                            <div class="form-group">
                                                {{ Form::label('renter_foot', 'Foot Size:') }}
                                                {{ Form::select('renter_foot', $select['foot'], $package['renter_foot'], array('class' => 'renter-foot form-control', 'required' => '', 'data-parsley-required' => 'true')) }}
                                                <a href="#" data-toggle="modal" data-target="#foot-size-conversion-table"><small>Foot Size Conversion Table</small></a>
                                                @include('partials._footsize')
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="equipments-container">
                                        <div class="form-group">
                                            {{ Form::label('package_id', 'Package:') }}
                                            {{ Form::select('package_id', $select['packages'], $package['package_id'], array('class' => 'renter-package form-control', 'required' => '', 'data-parsley-required' => 'true')) }}
                                        </div>
                                        <div class="form-group">
                                            <label for="rent_days">Duration:</label>
                                            <select name="rent_days" class="form-control">
                                            @for ($i = 1; $i <= $days; $i++)
                                                <option value="{{ $i }}" {{ $i == $package['rent_days'] ? 'selected="selected"' : '' }}>{{ $i }} {{ ($i == 1) ? 'day' : 'days' }}</option>
                                            @endfor
                                            </select>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Add on</label>
                                                    <div class="checkbox addon">
                                                        <label>
                                                            <input name="addon[boots]" type="hidden" value="off">
                                                            <input name="addon[boots]" class="boots-addon" type="checkbox" {{ ($package['addon']['boots'] == 'on') ? 'checked' : '' }} >
                                                            Boots
                                                        </label>
                                                    </div>
                                                    <div class="checkbox addon">
                                                        <label>
                                                            <input name="addon[helmet]" type="hidden" value="off">
                                                            <input name="addon[helmet]" class="helmet-addon" type="checkbox" {{ ($package['addon']['helmet'] == 'on') ? 'checked' : '' }} >
                                                            Helmet
                                                        </label>
                                                    </div>
                                                    <div class="checkbox addon">
                                                        <label>
                                                            <input name="addon[insurance]" type="hidden" value="off">
                                                            <input name="addon[insurance]" class="insurance-addon" type="checkbox" {{ ($package['addon']['insurance'] == 'on') ? 'checked' : '' }} >
                                                            Insurance
                                                        </label>
                                                        <a href="#" class="" data-toggle="modal" data-target="#insurance-info-modal"><i class="fa fa-question-circle"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="price-note"><strong>Note: </strong>Prices will be updated once saved</p>
                                                <div class="hidden">
                                                    {{ Form::text('rent_status', 'saved') }}
                                                </div>
                                                <button type="submit" class="btn btn-block btn-success">Save</button>
                                                <a href="#" class="btn btn-block btn-danger" data-toggle="modal" data-target="#remove-from-rack-modal" data-id="{{ $key }}" data-name="{{ $package['package_renter'] }}">Delete</a>
                                            </div>
                                        </div>
                                    </div> 
                                </div>
                                <div class="col-md-2">
                                    <div class="rentals-prices-container">
                                        @if ($chalet['discount'])
                                        <span class="price">&euro; {{ number_format($prices[$key]['discounted'], 2, '.', ',') }}</span>
                                        <span class="price strike"><small>&euro; {{ number_format($prices[$key]['originalAmount'], 2, '.', ',') }}</small></span>
                                        <span class="discount-amount">Less: ( &euro; - <small>{{ number_format($prices[$key]['discountAmount'], 2, '.', ',') }}</small> )</span>
                                        @else
                                        <span class="price">&euro; {{ number_format($prices[$key]['originalAmount'], 2, '.', ',') }}</span>
                                        @endif
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                        @else
                        <div class="row">
                            <div id="equip-{{ $loop->iteration }}" class="equip-row success checked clearfix" data-id="{{ $loop->iteration }}">
                                {!! Form::open(array('route' => array('editRenter', $key), 'method' => 'POST', 'class' => 'form-renter-info')) !!}
                                <div class="col-md-3">
                                    <h4>{{ $package['package_renter'] }}</h4>
                                    <p>Age: <strong>{{ $select['age'][$package['renter_age']] }}</strong></p>
                                    <p>Sex: <strong>{{ $package['renter_sex'] }}</strong></p>
                                    @if($package['renter_notes'] != '')
                                    <p>Notes: <stong>{{ $package['renter_notes'] }}</stong></p>
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    <p>Ability: <strong>{{ $select['level'][$package['renter_ability']] }}</strong></p>
                                    <p>Height: <strong>{{ $select['height'][$package['renter_height']] }}</strong></p>
                                    <p>Weight: <strong>{{ $select['weight'][$package['renter_weight']] }}</strong></p>
                                    <p>Foot Size: <strong>{{ $select['foot'][$package['renter_foot']] }}</strong></p>
                                </div>
                                <div class="col-md-4">
                                    <div class="equipments-container">
                                        <span><strong>{{ $packageModel->getPackageName($package['package_id']) }}</strong></span>
                                        @if($packageModel->getPackageLevel($package['package_id']))
                                        <span><strong>{{ $packageModel->getPackageLevel($package['package_id']) }}</strong></span>
                                        @endif
                                        <div class="row">
                                            <div class="col-md-6">
                                                <span>Duration: {{ $package['rent_days'] }} {{ ($package['rent_days'] == 1) ? 'day' : 'days' }}</span>
                                                <span>Boots: {{ ($package['addon']['boots'] == 'on') ? 'Yes' : 'No' }}</span>
                                                <span>
                                                    Helmet: {{ ($package['addon']['helmet'] == 'on') ? 'Yes' : 'No' }}
                                                    @if( (int) $package['renter_age'] <= 3 && $package['renter_age'] != null && $package['addon']['helmet'] == 'on' || $packageModel->getPackageType($package['package_id']) == 'Child')
                                                    <small class="free-helmet">FREE! *</small>
                                                    @endif
                                                </span>
                                                <span>Insurance: {{ ($package['addon']['insurance'] == 'on') ? 'Yes' : 'No' }}</span>
                                            </div>
                                            <div class="col-md-6">
                                                <button type="submit" class="btn btn-block btn-primary">Edit</button>
                                                <a href="#" class="btn btn-block btn-danger" data-toggle="modal" data-target="#remove-from-rack-modal" data-id="{{ $key }}" data-name="{{ $package['package_renter'] }}">Delete</a>
                                            </div>
                                        </div>
                                    </div> 
                                </div>
                                <div class="col-md-2">
                                    <div class="rentals-prices-container">
                                        @if ($chalet['discount'])
                                        <span class="price">&euro; {{ number_format($prices[$key]['discounted'], 2, '.', ',') }}</span>
                                        <span class="price strike"><small>&euro; {{ number_format($prices[$key]['originalAmount'], 2, '.', ',') }}</small></span>
                                        <span class="discount-amount">Less: ( &euro; - <small>{{ number_format($prices[$key]['discountAmount'], 2, '.', ',') }}</small> )</span>
                                        @else
                                        <span class="price">&euro; {{ number_format($prices[$key]['originalAmount'], 2, '.', ',') }}</span>
                                        @endif
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                        @endif
                        <div class="hidden">
                        {!! Form::open(array('route' => array('removeFromRental', $key), 'method' => 'POST', 'class' => 'remove-from-rack', 'data-id' => $key )) !!}
                        {!! Form::close() !!}
                        </div>
                        @endforeach
                        <div class="rental-table-footer">
            				<div class="row">
                                <div class="col-md-8">
                					<div class="add-new-package-cell">
                                        <a href="{{ route('equipments') }}" class="btn btn-lg btn-primary">Add new package</a>
                                    </div>
                                </div>
                                <div class="col-md-2">
        	        				<div class="total-cell">Total</div>
                                </div>
                                <div class="col-md-2">
                					<div>
                                        @if ($chalet['discount'])
                                        <span class="total-price">&euro; {{ number_format($prices['totalDiscounted'], 2, '.', ',') }}</span>
                                        <span class="total-price strike"><small>&euro; {{ number_format($prices['total'], 2, '.', ',') }}</small></span>
                                        @else
                                        <span class="total-price">&euro; {{ number_format($prices['total'], 2, '.', ',') }}</span>
                                        @endif
                                    </div>
                                </div>
            				</div>
                        </div>
        			</div>
        		</div>
        	</div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-10">
                <div class="alert alert-info" role="alert">
                    <p>Please complete and save all the packages above to proceed.</p>
                </div>
            </div>
            <div class="col-md-2">
                @if($button)
                    @if($details['booking_id'])
                        <a href="#" data-toggle="modal" data-target="#save-and-exit-modal" class="btn btn-danger btn-lg pull-right">Save and Exit</a>
                        <div class="hidden">
                        {!! Form::open(array('route' => array('reference.update', $details['booking_id']), 'method' => 'POST', 'id' => 'save-and-exit' )) !!}
                        {!! Form::close() !!}
                        </div>
                    @else
                        <a href="{{ route('details') }}" class="btn btn-success btn-lg pull-right">Continue</a>
                    @endif
                @endif
            </div>
        </div>
    </div>
    <div class="modal fade" id="remove-from-rack-modal" tabindex="-1" role="dialog" aria-labelledby="removeRenter">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="removal-modal-title">REMOVE PACKAGE FOR: %renter%</h4>
                </div>
                <div class="modal-body">
                    <form class="hidden">
                        <input type="text" class="form-control" id="remove-renter-name">
                        <input type="text" class="form-control" id="remove-renter-id">
                    </form>
                    <p id="removal-body-text">Are you sure you would like to remove the package for <strong>%renter%</strong> from the rentals?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" id="remove-from-rack-btn">Delete</button>
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
    <div class="modal fade" id="notification-modal" tabindex="-1" role="dialog" aria-labelledby="insuranceInfo">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="notification-modal-title">Save packages one at a time</h4>
                </div>
                <div class="modal-body">
                    <p>Please make sure you save each persons details before you proceed to the next person or your inputs will be lost.</p>
                    <p>There's no rush, start filling in details for any package you like but remember to press 'Save' for that package before filling in the next.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="save-and-exit-modal" tabindex="-1" role="dialog" aria-labelledby="insuranceInfo">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="save-and-exit-modal-title">Save and Exit</h4>
                </div>
                <div class="modal-body">
                    <p>Please make sure you have saved your edits, if any, on your Booking Information before you exit or your changes will be lost.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="save-and-exit-btn">Save and Exit</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    {!! Html::script('js/bootstrap-select.min.js') !!}
    {!! Html::script('js/moment-with-locales.js') !!}
    {!! Html::script('js/bootstrap-datetimepicker.min.js') !!}
    <script type="text/javascript">
        jQuery(document).ready(function() {
            @if (Session::has('notify'))
            jQuery('#notification-modal').modal('show');
            @endif

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

            jQuery('#remove-from-rack-btn').click(function (e) {
                e.preventDefault();
                var id = jQuery('#remove-renter-id').val();
                jQuery('.remove-from-rack[data-id="' + id + '"]').submit();
            });

            jQuery('#save-and-exit-btn').click(function (e) {
                e.preventDefault();
                jQuery('#save-and-exit').submit();
            });

/*
            jQuery('#rental-table').click(function() {
                var id = jQuery(this).data('id');
                if(jQuery(this).hasClass('checked')) {
                    //
                } else {
                    //alert('Please make sure you save each persons details before you proceed to the next person or your inputs will be lost.');
                    jQuery('#notification-modal').modal('show');
                    jQuery(this).addClass('checked');
                }
            });
*/
            checkChalet();

           	jQuery('#chalet_id').change(function() {
           		checkChalet();
           	});

            jQuery('#arrival_dtp').datetimepicker({
                format: 'YYYY-MM-DD HH:mm',
                stepping: 30,
                //minDate: moment().endOf('hour'),
                defaultDate: moment('{{ $details['arrival_dtp'] }}', 'YYYY-MM-DD HH:mm'),
                maxDate: moment('{{ $details['mountain_dtp'] }}', 'YYYY-MM-DD HH:mm'),
                useCurrent: false,
                showClear: true,
                sideBySide: true
            });
            jQuery('#departure_dtp').datetimepicker({
                format: 'YYYY-MM-DD HH:mm',
                stepping: 30,
                minDate: moment('{{ $details['mountain_dtp'] }}', 'YYYY-MM-DD HH:mm'),
                defaultDate: moment('{{ $details['departure_dtp'] }}', 'YYYY-MM-DD HH:mm'),
                useCurrent: false,
                sideBySide: true
            });
            jQuery('#mountain_dtp').datetimepicker({
                format: 'YYYY-MM-DD HH:mm',
                stepping: 30,
                minDate: moment('{{ $details['arrival_dtp'] }}', 'YYYY-MM-DD HH:mm'),
                maxDate: moment('{{ $details['departure_dtp'] }}', 'YYYY-MM-DD HH:mm'),
                defaultDate: moment('{{ $details['mountain_dtp'] }}', 'YYYY-MM-DD HH:mm'),
                useCurrent: false,
                sideBySide: true
            });
            jQuery("#arrival_dtp").on("dp.change", function (e) {
                var mtn = jQuery('#mountain_dtp').data("DateTimePicker").date();
                if(mtn) {
                    jQuery('#departure_dtp').data("DateTimePicker").minDate(mtn);
                } else {
                    jQuery('#departure_dtp').data("DateTimePicker").minDate(e.date);
                }
                jQuery('#mountain_dtp').data("DateTimePicker").minDate(e.date);
            });
            jQuery("#departure_dtp").on("dp.change", function (e) {
                var mtn = jQuery('#mountain_dtp').data("DateTimePicker").date();
                if(mtn) {
                    jQuery('#arrival_dtp').data("DateTimePicker").maxDate(mtn);
                } else {
                    jQuery('#arrival_dtp').data("DateTimePicker").maxDate(e.date);
                }
                jQuery('#mountain_dtp').data("DateTimePicker").maxDate(e.date);
            });
            jQuery("#mountain_dtp").on("dp.change", function (e) {
                jQuery('#arrival_dtp').data("DateTimePicker").maxDate(e.date);
                jQuery('#departure_dtp').data("DateTimePicker").minDate(e.date);
            });
        });

        function renterReplace(originalString, newString) {
            return originalString.replace(/%renter%/, newString);
        }

        function checkChalet() {
	        var val = jQuery('#chalet_id').val();
	        if( val == '1') {
	            jQuery('#independent-info').removeClass('hidden');
                var name = jQuery('#chalet-name').val();
                var address = jQuery('#chalet-address').val();
                if(name == 'null' || address == 'null') {
                    jQuery('#chalet-name').val('');
                    jQuery('#chalet-address').val('');
                }
	        } else {
	            jQuery('#independent-info').addClass('hidden');
	            jQuery('#chalet-name').val('null');
	            jQuery('#chalet-address').val('null');
	        }
		}
    </script>

@endsection
