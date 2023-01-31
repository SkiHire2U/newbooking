@if (Session::has('success'))
<div class="container">
	<div class="alert alert-success" role="alert">
		<strong>Success!</strong> {{ Session::get('success') }}
	</div>
</div>
@endif

@if (Session::has('info'))
<div class="container">
	<div class="alert alert-info" role="alert">
		{{ Session::get('info') }}
	</div>
</div>
@endif

@if (Session::has('warning'))
<div class="container">
	<div class="alert alert-warning" role="alert">
		{{ Session::get('warning') }}
	</div>
</div>
@endif

@if (Session::has('danger'))
<div class="container">
	<div class="alert alert-danger" role="alert">
		{{ Session::get('danger') }}
	</div>
</div>
@endif

@if (count($errors) > 0)
<div class="container">
	<div class="alert alert-danger" role="alert">
		<strong>Error!</strong>
		<ul>
		@foreach($errors->all() as $error)
			<li>{{ $error }}</li>
		@endforeach
		</ul>
	</div>
</div>
@endif