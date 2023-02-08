<li id="{{ $equip->id }}" class="equipment">
	<div class="row">
		<div class="col-md-9">
			<div class="package-image">
				@if($equip->level)
				<div class="level-overlay {{ strtolower($equip->level) }}">
					<span>{{ $equip->level }}</span>
				</div>
				@endif
				<img src="{{ $equip->image_url }}">
			</div>
			<h3>{{ $equip->name }}</h3>
			<p>{{ $equip->notes }}</p>
		</div>
		<div class="col-md-3">
			<div class="prices-container">
				@if($chalet['discount'])
				<span class="discount"> - <small>{{ $chalet['discount'] }}%</small></span>
				@endif
				<div class="plain-prices prices" data-id="{{ $equip->id }}">
					<ul>
					@foreach ($equip->prices as $key => $value)
						@include('partials._pricesFlats')
					@endforeach
					</ul>
				</div>
				<div class="add-button-container">
					<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#package-renter-modal-{{ $equip->id }}" data-id="{{ $equip->id }}">Add to Rack</a>
				</div>
			</div>
			<div class="modal fade" id="package-renter-modal-{{ $equip->id }}" tabindex="-1" role="dialog" aria-labelledby="packageRenter">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h3 class="modal-title">{{ $equip->name }}</h3>
						</div>
						{!! Form::open(array('route' => 'addToRack', 'method' => 'POST', 'class' => 'rack-details', 'data-id' => $equip->id, 'data-parsley-validate' => '' )) !!}
						<div class="modal-body">
							<div class="row">
								<div class="form-wrapper col-md-8 col-md-offset-2">
								
					            	<input type="text" class="form-control hidden" id="package-id">
						         	<div class="form-group">
						            	{{ Form::label('package_renter', 'Full Name:') }}
						            	{{ Form::text('package_renter', null, array('class' => 'renter-name form-control', 'required' => '')) }}
					          		</div>
					          		<div class="checkbox addon">
									    <label>
									    	<input name="addon[boots]" type="hidden" value="off">
									      	<input name="addon[boots]" class="boots-addon" data-id="{{ $equip->id }}" type="checkbox">
											<img class="img-boots" src="{{ url('/images/boots.jpg') }}">Add Boots
									    </label>
									</div>
									<div class="checkbox addon">
									    <label>
									    	<input name="addon[helmet]" type="hidden" value="off">
									      	<input name="addon[helmet]" class="helmet-addon" data-id="{{ $equip->id }}" data-price="{{ $metaModel->getAddonMeta('helmet_prices', $equip->type) }}" data-increments="{{ $metaModel->getAddonMeta('helmet_increments', $equip->type) }}" type="checkbox">
									      	<img class="img-helmet" src="{{ url('/images/helmet.jpg') }}">Add Helmet 
									    </label>
									</div>
									<div class="checkbox addon">
									    <label>
									    	<input name="addon[insurance]" type="hidden" value="off">
									      	<input name="addon[insurance]" class="insurance-addon" data-id="{{ $equip->id }}" data-increments="{{ $metaModel->getAddonMeta('insurance_increments', $equip->type) }}" type="checkbox">
									      	Insurance {{ ($equip->category == 'Child Skis' || $equip->category == 'Child Snowboards') ? '(€ 1 per day)' : '(€ 2 per day)' }}
									    </label>
									    <a href="#" class="" data-toggle="modal" data-target="#insurance-info-modal"><i class="fa fa-question-circle"></i></a>
									</div>
									<div class="hidden">
										{{ Form::text('package_id', $equip->id) }}
										{{ Form::text('rent_days', ($days > 6) ? 6 : $days, array('class' => 'rent-days')) }}
										{{ Form::text('rent_status', 'new') }}
									</div>
							        <div class="final-price" data-id="{{ $equip->id }}">
							        	<div class="prices-container">
							        		@if($chalet['discount'])
											<span class="discount"> - <small>{{ $chalet['discount'] }}%</small></span>
											@endif
											<div class="plain-prices prices" data-id="{{ $equip->id }}">
												<ul>
												@foreach ($equip->prices as $key => $value)
													@include('partials._pricesFlats')
												@endforeach
												</ul>
											</div>
											<div class="boots-prices prices" data-id="{{ $equip->id }}">
												<ul>
												@foreach ($equip->prices as $key => $value)
													@include('partials._pricesBoots')
												@endforeach
												</ul>
											</div>
										</div>
							        </div>
							    </div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-primary">Add to Rack</button>
						</div>
						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
	</div>
</li>