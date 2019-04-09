@extends('master')

@section('page_title', 'Settings')


@section('content')
<div class="card" style="min-height: 1000px;">
	<div class="card-header">
		<h3 class="card-title">Settings</h3>
	</div>
	<div class="card-body">
		@include('settings._slack')
		@include('settings._user_details')
		@include('settings._change_password')
	</div>
</div>

@endsection
