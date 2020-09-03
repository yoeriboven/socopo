@extends('master')

@section('page_title', 'Settings')


@section('content')

@if (Session::has('success'))
	<div class="alert alert-icon alert-success full-width" role="alert">
		<i class="fe fe-check mr-2" aria-hidden="true"></i> {{ Session::get('success') }}
	</div>
@endif

<div class="card">
	<div class="card-header">
		<h3 class="card-title">Settings</h3>
	</div>
	<div class="card-body">
		@include('settings._slack')

		@if (auth()->user()->subscribed())
			@include('settings._subscription_active')
		@else
			@include('settings._subscription_inactive')
		@endif

		@include('settings._user_details')
		@include('settings._change_password')
	</div>
</div>

@endsection
