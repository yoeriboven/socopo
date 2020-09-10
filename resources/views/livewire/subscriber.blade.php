<form wire:submit.prevent="subscribe" id="payment-form">
    @csrf

    @include('upgrade._plans')
    @include('upgrade._user_details')
    @include('upgrade._billing')

</form>

@once
	@push('scripts')
		@paddleJS
		@livewireScripts
		<script>
			Livewire.on('readyForPaddle', payLink => {
			    Paddle.Checkout.open({
					override: payLink
				});
			});
		</script>
	@endpush

	@push('styles')
		@livewireStyles
	@endpush
@endonce
