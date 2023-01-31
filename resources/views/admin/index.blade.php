@extends('layouts.app')

@section('title-bar')
	@include('partials._adminMenu')
@endsection

@section('styles')
<link rel="stylesheet" type="text/css" href="/css/admin.css">
@endsection

@section('content')
<div class="container">
    <div class="row">
    	Admin Panel
    </div>
</div>

@endsection

@section('scripts')

@endsection