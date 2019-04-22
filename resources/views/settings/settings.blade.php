@extends('master')

@section('page_title', 'Settings')


@section('content')
<div class="card">
	<div class="card-header">
		<h3 class="card-title">Settings</h3>
	</div>
	<div class="card-body">
		@include('settings._slack')

		@if (auth()->user()->isSubscribed())
			@include('settings._subscribed')
		@else
			@include('settings._not_subscribed')
		@endif

		@include('settings._user_details')
		@include('settings._change_password')
	</div>
</div>

@endsection
