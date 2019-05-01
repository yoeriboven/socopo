@extends('master')

@section('page_title', 'Upgrade')

@section('content')

<form method="POST" action="{{ url('upgrade') }}" id="payment-form">
    @csrf

    @include('upgrade._plans')
    @include('upgrade._user_details')
    @include('upgrade._billing')
</form>

@endsection

@section('footer')
	<script src="https://js.stripe.com/v3/"></script>
	<script type="text/javascript" src="{{ mix('js/stripe.js') }}"></script>
@endsection
