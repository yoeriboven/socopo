@extends('master')

@section('page_title', 'Upgrade')

@section('content')

<form method="POST" action="{{ route('subscription.store') }}" id="payment-form">
    @csrf

    @include('upgrade._plans')
    @include('upgrade._user_details')
    @include('upgrade._billing')
</form>

@endsection

@once
	@push('scripts')
		@paddleJS
	@endpush
@endonce
