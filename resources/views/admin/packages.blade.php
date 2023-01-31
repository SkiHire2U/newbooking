@extends('layouts.app')

@section('title-bar')
	@include('partials._adminMenu')
@endsection

@section('styles')
<link rel="stylesheet" type="text/css" href="/css/admin.css">
<link rel="stylesheet" type="text/css" href="/css/jquery.dataTables.min.css">
@endsection

@section('content')
<div class="container">
	<h2>Packages</h2>
	<div class="admin-button-container">
		<a href="#" class="btn btn-default btn-icon" data-toggle="modal" data-target="#add-package-modal" title="Add"><i class="fa fa-plus"></i>Add Package</a>
		<div class="modal fade" id="add-package-modal" tabindex="-1" role="dialog" aria-labelledby="addPackage">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h3 class="modal-title">Add Package</h3>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="form-wrapper col-md-8 col-md-offset-2">
							{!! Form::open(array('route' => 'package.store', 'method' => 'PUT', 'class' => 'add-package' )) !!}
								<div class="form-group clearfix">
					            	{{ Form::label('package_name', 'Package Name:') }}
					            	{{ Form::text('package_name', null, array('class' => 'package-name form-control', 'required' => '')) }}
				          		</div>
				          		<div class="form-group clearfix">
					            	{{ Form::label('level', 'Level:') }}
					            	{{ Form::text('level', null, array('class' => 'level form-control')) }}
				          		</div>
				          		<div class="form-group clearfix">
					            	{{ Form::label('type', 'Type') }}
	                                {{ Form::select('type', array('Adult' => 'Adult', 'Child' => 'Child'), null, array('class' => 'col-md-12 form-control', 'required' => '')) }}
				          		</div>
				          		<div class="form-group clearfix">
					            	{{ Form::label('category', 'Category') }}
	                                {{ Form::select('category', array('Adult Skis' => 'Adult Skis', 'Adult Snowboards'=>'Adult Snowboards', 'Child Skis'=>'Child Skis', 'Child Snowboards'=>'Child Snowboards', 'Extras'=>'Extras'), null, array('class' => 'col-md-12 form-control', 'required' => '')) }}
				          		</div>
				          		<div class="form-group clearfix">
				          			{{ Form::label('prices', 'Prices:') }}
				          			@include('partials._priceAdd')
				          		</div>
				          		<div class="form-group">
					            	{{ Form::label('image_url', 'Image URL:') }}
					            	{{ Form::text('image_url', null, array('class' => 'image-url form-control')) }}
				          		</div>
				          		<div class="form-group">
					            	{{ Form::label('notes', 'Notes:') }}
					            	{{ Form::text('notes', null, array('class' => 'notes form-control')) }}
				          		</div>
				          		<div class="checkbox clearfix">
								    <label>
								    	<input name="is_active" type="hidden" value="off">
								      	<input name="is_active" class="is-active" type="checkbox" checked>
								      	Is active?
								    </label>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Add</button>
					</div>
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
	<div class="table-container">
		<table class="table webee-table" id="dataTable">
			<thead>
				<!-- <th>Image</th> -->
				<th>Package Name/Level</th>
				<th>Category</th>
				<th>Image/Prices</th>
				<th></th>
			</thead>
			<tbody>
			@foreach ($packages as $package)
				<tr class="{{ $package->is_active ? 'success' : 'danger' }}">
					<!-- <td><div class="ski-image" style="background-image: url({{ $package->image_url }});"></div></td> -->
					<th>{{ $package->name }}{{ $package->level ? ' / ' . $package->level : '' }}</th>
					<td>{{ $package->category }}</td>
					<td>
						<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#package-price-modal-{{ $package->id }}" data-id="{{ $package->id }}"><i class="fa fa-eye"></i></a>
						<div class="modal fade" id="package-price-modal-{{ $package->id }}" tabindex="-1" role="dialog" aria-labelledby="packagePrice">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h3 class="modal-title">{{ $package->name }}</h3>
									</div>
									<div class="modal-body">
										<h3>Image</h3>
										<div class="equipment-image">
											<img src="{{ $package->image_url }}" alt="{{ $package->name }}" />
										</div>
										<hr>
										<h3>Prices</h3>
										<table class="table table-bordered package-price-table">
											<thead>
												<tr>
													<th>Days</th>
													<td>Without Boots</td>
													<td>With Boots</td>
												</tr>
											</thead>
											<tbody>
												@foreach ($package->prices as $key => $value)
												<tr>
													<td>{{ $key }}</td>
													<td>{{ $value->flat }}</td>
													<td>{{ $value->boots }}</td>
												</tr>
												@endforeach
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</td>
					<td>
						<a href="#" class="btn btn-success btn-icon" data-toggle="modal" data-target="#update-package-{{ $package->id }}-modal" title="Edit"><i class="fa fa-pencil"></i></a>
						<div class="modal fade" id="update-package-{{ $package->id }}-modal" tabindex="-1" role="dialog" aria-labelledby="updatePackage">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h3 class="modal-title">Update Package</h3>
									</div>
									<div class="modal-body">
										<div class="row">
											<div class="form-wrapper col-md-8 col-md-offset-2">
												{!! Form::open(array('route' => array('package.update', $package->id), 'method' => 'PUT', 'class' => 'update-package' )) !!}
												<div class="form-group clearfix">
									            	{{ Form::label('package_name', 'Package Name:') }}
									            	{{ Form::text('package_name', $package->name, array('class' => 'package-name form-control', 'required' => '')) }}
								          		</div>
								          		<div class="form-group clearfix">
									            	{{ Form::label('level', 'Level:') }}
									            	{{ Form::text('level', $package->level, array('class' => 'level form-control')) }}
								          		</div>
								          		<div class="form-group clearfix">
									            	{{ Form::label('type', 'Type') }}
									            	{{ Form::select('type', array('Adult' => 'Adult', 'Child' => 'Child'), $package->type, array('class' => 'col-md-12 form-control', 'required' => '')) }}
								          		</div>
								          		<div class="form-group clearfix">
									            	{{ Form::label('category', 'Category') }}
									            	{{ Form::select('category', array('Adult Skis' => 'Adult Skis', 'Adult Snowboards'=>'Adult Snowboards', 'Child Skis'=>'Child Skis', 'Child Snowboards'=>'Child Snowboards', 'Extras'=>'Extras'), $package->category, array('class' => 'col-md-12 form-control', 'required' => '')) }}
								          		</div>
								          		<div class="form-group clearfix">
								          			{{ Form::label('prices', 'Prices:') }}
								          			@include('partials._priceUpdate')
								          		</div>
								          		<div class="form-group">
									            	{{ Form::label('image_url', 'Image URL:') }}
									            	{{ Form::text('image_url', $package->image_url, array('class' => 'image-url form-control')) }}
								          		</div>
								          		<div class="form-group">
									            	{{ Form::label('notes', 'Notes:') }}
									            	{{ Form::text('notes', $package->notes, array('class' => 'notes form-control')) }}
								          		</div>
								          		<div class="checkbox clearfix">
												    <label>
												    	<input name="is_active" type="hidden" value="off">
												      	<input name="is_active" class="is-active" type="checkbox" checked>
												      	Is active?
												    </label>
												</div>
											</div>
										</div>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
										<button type="submit" class="btn btn-primary">Update</button>
									</div>
									{!! Form::close() !!}
								</div>
							</div>
						</div>
						<a href="#" onclick="event.preventDefault(); confirmDelete({{ $package->id }});" class="btn btn-danger btn-icon" title="Delete"><i class="fa fa-trash"></i></a>
						<form id="delete-form-{{ $package->id }}" action="{{ route('package.delete', $package->id) }}" method="POST" style="display: none;">
	                        {{ csrf_field() }}
	                    </form>
					</td>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
</div>

@endsection

@section('scripts')
<script type="text/javascript" src="/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('#dataTable').DataTable();
	});

	function confirmDelete(id) {
		var con = confirm('Are you sure you want to remove this entry?');

		if(con) {
			jQuery('#delete-form-' + id).submit();
		}
	}
</script>
@endsection