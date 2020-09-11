<div wire:poll class="card">
    <div class="card-body">
		<div class="row">
			We're confirming your subscription in the background. This might take a minute.
		</div>
	</div>
</div>

@once
	@push('scripts')
		@livewireScripts
	@endpush

	@push('styles')
		@livewireStyles
	@endpush
@endonce
