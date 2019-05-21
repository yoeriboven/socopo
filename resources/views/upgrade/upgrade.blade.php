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

@section('footer')
	<script type="text/javascript">
		var stripeKey = '{{ config('services.stripe.key') }}';
	</script>
	<script src="https://js.stripe.com/v3/"></script>
	<script type="text/javascript" src="{{ mix('js/stripe.js') }}"></script>
@endsection
