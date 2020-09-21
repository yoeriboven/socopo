<div class="row">
	<div class="col-12">
		<div class="form-group">
			<label class="form-label">Name</label>
			<input type="text" wire:model.defer="userDetails.name" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Acme Inc. / John Appleseed" value="{{ old('name') ?? $details->name ?? '' }}" required>
			<div class="invalid-feedback">{{ $errors->first('name') }}</div>
		</div>
	</div>

	<div class="col-12">
		<div class="form-group">
			<label class="form-label">Address</label>
			<input type="text" wire:model.defer="userDetails.address" class="form-control @error('address') is-invalid @enderror" name="address" placeholder="One Infinite Loop" value="{{ old('address') ?? $details->address ?? '' }}" required>
			<div class="invalid-feedback">{{ $errors->first('address') }}</div>
		</div>
	</div>

	<div class="col-md-4">
		<div class="form-group">
			<label class="form-label">Postal code</label>
			<input wire:model.defer="userDetails.postal" type="text" class="form-control @error('postal') is-invalid @enderror" name="postal" placeholder="95014" value="{{ old('postal') ?? $details->postal ?? '' }}" required>
			<div class="invalid-feedback">{{ $errors->first('postal') }}</div>
		</div>
	</div>

	<div class="col-md-4">
		<div class="form-group">
			<label class="form-label">City</label>
			<input wire:model.defer="userDetails.city" type="text" class="form-control @error('city') is-invalid @enderror" name="city" placeholder="Cupertino, CA" value="{{ old('city') ?? $details->city ?? '' }}" required>
			<div class="invalid-feedback">{{ $errors->first('city') }}</div>
		</div>
	</div>

	<div class="col-md-4">
		<div class="form-group">
			<label class="form-label">Country</label>
	        <select wire:model.defer="userDetails.country" name="country" id="select-countries" class="form-control custom-select @error('country') is-invalid @enderror" required>
	            <option value="" disabled selected>Country...</option>

	            @foreach (config('countries') as $code => $country)
					<option value="{{ $code }}"
						@if (old('country') !== null)
							{{ ($code == old('country')) ? 'selected' : '' }}
						@elseif (isset($details->country))
	            			{{ ($code == $details->country) ? 'selected' : '' }}
	            		@endif
	            		>
	            		{{ $country }}
	            	</option>
				@endforeach

	        </select>
	        <div class="invalid-feedback">{{ $errors->first('country') }}</div>
		</div>
	</div>

</div>
