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
    <h2>Accommodations</h2>
    <div class="admin-button-container">
		<a href="#" class="btn btn-default btn-icon" data-toggle="modal" data-target="#add-operator-modal" title="Add"><i class="fa fa-plus"></i>Add Operator</a>
		<div class="modal fade" id="add-operator-modal" tabindex="-1" role="dialog" aria-labelledby="addOperator">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h3 class="modal-title">Add Operator</h3>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="form-wrapper col-md-8 col-md-offset-2">
							{!! Form::open(array('route' => 'operator.store', 'method' => 'PUT', 'class' => 'add-operator' )) !!}
								<div class="form-group">
					            	{{ Form::label('operator_name', 'Operator Name:') }}
					            	{{ Form::text('operator_name', null, array('class' => 'operator-name form-control', 'required' => '')) }}
				          		</div>
				          		<div class="form-group">
					            	{{ Form::label('web_address', 'Web Address:') }}
					            	{{ Form::text('web_address', null, array('class' => 'web-address form-control')) }}
				          		</div>
				          		<div class="form-group">
					            	{{ Form::label('postal_address', 'Postal Address:') }}
					            	{{ Form::text('postal_address', null, array('class' => 'postal-address form-control')) }}
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
				<!-- <th></th> -->
				<th>Operator</th>
				<th>Chalet</th>
				<th>Address</th>
				<th>Notes</th>
				<th>Actions</th>
			</thead>
			<tbody>
				@foreach ($operators as $operator)
				<tr class="{{ $operator->is_active ? 'success' : 'danger' }}">
					<!-- <td>{{ $loop->iteration }}</td> -->
					<td>{{ $operator->name }}</td>
					<td>
						@foreach ($operator->accommodations as $acc)
						@if($acc->is_active == 1)
							<p>{{ $acc->name }}</p>
						@else
							<p class="strike">{{ $acc->name }}</p>
						@endif
						@endforeach
					</td>
					<td>
						@if ($operator->web_address)
						<p><i class="fa fa-globe"></i><a href="{{ $operator->web_address }}" target="_blank">{{ $operator->web_address }}</a></p>
						@endif
						@if ($operator->postal_address)
						<p><i class="fa fa-map-marker"></i>{{ $operator->postal_address }}</p>
						@endif
					</td>
					<td></td>
					<td>
						@if ($operator->id != '1')
						<a href="{{ route('accommodation', $operator->id) }}" class="btn btn-primary btn-icon" title="View"><i class="fa fa-eye"></i></a>
						<a href="#" class="btn btn-success btn-icon" data-toggle="modal" data-target="#operator-modal-{{ $operator->id }}" title="Edit"><i class="fa fa-pencil"></i></a>
						<div class="modal fade" id="operator-modal-{{ $operator->id }}" tabindex="-1" role="dialog" aria-labelledby="editOperator">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h3 class="modal-title">Edit Chalet</h3>
									</div>
									<div class="modal-body">
										<div class="row">
											<div class="form-wrapper col-md-8 col-md-offset-2">
											{!! Form::open(array('route' => array('operator.update', $operator->id), 'method' => 'PUT', 'class' => 'edit-operator' )) !!}
												<div class="form-group">
									            	{{ Form::label('operator_name', 'Operator Name:') }}
									            	{{ Form::text('operator_name', $operator->name, array('class' => 'operator-name form-control', 'required' => '')) }}
								          		</div>
								          		<div class="form-group">
									            	{{ Form::label('web_address', 'Web Address:') }}
									            	{{ Form::text('web_address', $operator->web_address, array('class' => 'web-address form-control')) }}
								          		</div>
								          		<div class="form-group">
									            	{{ Form::label('postal_address', 'Postal Address:') }}
									            	{{ Form::text('postal_address', $operator->postal_address, array('class' => 'postal-address form-control')) }}
								          		</div>
								          		<div class="form-group">
									            	{{ Form::label('notes', 'Notes:') }}
									            	{{ Form::text('notes', $operator->notes, array('class' => 'notes form-control')) }}
								          		</div>
								          		<div class="checkbox">
												    <label>
												    	<input name="is_active" type="hidden" value="off">
												      	<input name="is_active" class="is-active" type="checkbox" {{ $operator->is_active ? 'checked' : '' }}>
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
						<a href="#" onclick="event.preventDefault(); confirmDelete({{ $operator->id }});" class="btn btn-danger btn-icon" title="Delete"><i class="fa fa-trash"></i></a>
						<form id="delete-form-{{ $operator->id }}" action="{{ route('operator.delete', $operator->id) }}" method="POST" style="display: none;">
	                        {{ csrf_field() }}
	                    </form>
						@endif
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