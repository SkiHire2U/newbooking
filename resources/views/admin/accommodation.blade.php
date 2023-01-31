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
    <h2>Operator Information</h2>
	<div class="operator-data">
		<p><strong>Operator: </strong> {{ $operator->name }}</p>
		<p><strong>Web Address: </strong> {{ $operator->web_address }}</p>
		<p><strong>Postal Mobile: </strong> {{ $operator->postal_address }}</p>
		<p><strong>Notes</strong> {{ $operator->notes }}</p>
	</div>
</div>
<div class="container">
	<h2>Chalet</h2>
	<div class="admin-button-container">
		<a href="#" class="btn btn-default btn-icon" data-toggle="modal" data-target="#add-chalet-modal" title="Add Chalet"><i class="fa fa-plus"></i>Add Chalet</a>
		<div class="modal fade" id="add-chalet-modal" tabindex="-1" role="dialog" aria-labelledby="addChalet">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h3 class="modal-title">Add Chalet</h3>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="form-wrapper col-md-8 col-md-offset-2">
							{!! Form::open(array('route' => 'accommodation.store', 'method' => 'PUT', 'class' => 'add-chalet' )) !!}
								<div class="hidden">
									{{ Form::text('operator_id', $operator->id, array('readonly' => '')) }}
								</div>
								<div class="form-group">
					            	{{ Form::label('chalet_name', 'Chalet Name:') }}
					            	{{ Form::text('chalet_name', null, array('class' => 'chalet-name form-control', 'required' => '')) }}
				          		</div>
				          		<div class="form-group">
					            	{{ Form::label('postal_address', 'Postal Address:') }}
					            	{{ Form::text('postal_address', null, array('class' => 'postal-address form-control')) }}
				          		</div>
				          		<div class="form-group">
					            	{{ Form::label('discount', 'Discount:') }}
					            	{{ Form::number('discount', null, array('class' => 'discount form-control')) }}
				          		</div>
				          		<div class="form-group">
					            	{{ Form::label('notes', 'Notes:') }}
					            	{{ Form::text('notes', null, array('class' => 'notes form-control')) }}
				          		</div>
				          		<div class="checkbox">
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
				<th></th>
				<th>Chalet Name</th>
				<th>Postal Address</th>
				<th>Discount</th>
				<th>Notes</th>
				<th>Actions</th>
			</thead>
			<tbody>
				@foreach ($operator->accommodations as $acc)
				<tr class="{{ $acc->is_active ? 'success' : 'danger' }}">
					<td>{{ $loop->iteration }}</td>
					<td>{{ $acc->name }}</td>
					<td>
						@if ($acc->postal_address)
						<p><i class="fa fa-map-marker"></i>{{ $acc->postal_address }}</p>
						@endif
					</td>
					<td>{{ $acc->discount }}{{ $acc->discount ? '%' : '' }}</td>
					<td>{{ $acc->notes }}</td>
					<td>
						<a href="#" class="btn btn-success btn-icon" data-toggle="modal" data-target="#chalet-modal-{{ $acc->id }}" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-pencil"></i></a>
						<div class="modal fade" id="chalet-modal-{{ $acc->id }}" tabindex="-1" role="dialog" aria-labelledby="editChalet">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h3 class="modal-title">Edit Chalet</h3>
									</div>
									<div class="modal-body">
										<div class="row">
											<div class="form-wrapper col-md-8 col-md-offset-2">
											{!! Form::open(array('route' => array('accommodation.update', $acc->id), 'method' => 'PUT', 'class' => 'edit-chalet' )) !!}
												<div class="hidden">
													{{ Form::text('operator_id', $operator->id, array('readonly' => '')) }}
												</div>
												<div class="form-group">
									            	{{ Form::label('chalet_name', 'Chalet Name:') }}
									            	{{ Form::text('chalet_name', $acc->name, array('class' => 'chalet-name form-control', 'required' => '')) }}
								          		</div>
								          		<div class="form-group">
									            	{{ Form::label('postal_address', 'Postal Address:') }}
									            	{{ Form::text('postal_address', $acc->postal_address, array('class' => 'postal-address form-control')) }}
								          		</div>
								          		<div class="form-group">
									            	{{ Form::label('discount', 'Discount:') }}
									            	{{ Form::number('discount', $acc->discount, array('class' => 'discount form-control')) }}
								          		</div>
								          		<div class="form-group">
									            	{{ Form::label('notes', 'Notes:') }}
									            	{{ Form::text('notes', $acc->notes, array('class' => 'notes form-control')) }}
								          		</div>
								          		<div class="checkbox">
												    <label>
												    	<input name="is_active" type="hidden" value="off">
												      	<input name="is_active" class="is-active" type="checkbox" {{ $acc->is_active ? 'checked' : '' }}>
												      	Is active?
												    </label>
												</div>
											</div>
										</div>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
										<button type="submit" class="btn btn-primary">Save</button>
									</div>
									{!! Form::close() !!}
								</div>
							</div>
						</div>
						<a href="#" onclick="event.preventDefault(); confirmDelete({{ $acc->id }});" class="btn btn-danger btn-icon" title="Delete"><i class="fa fa-trash"></i></a>
						<form id="delete-form-{{  $acc->id }}" action="{{ route('accommodation.delete',  $acc->id) }}" method="POST" style="display: none;">
	                        {{ csrf_field() }}
							{{ Form::text('operator_id', $operator->id, array('class' => 'hidden', 'readonly' => '')) }}
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