<div class="row" style="margin-bottom:20px;border-bottom:1px solid rgba(0, 40, 100, 0.12);padding-bottom:10px;">
	<div class="col-3">
		<h4 class="card-title">User info</h4>
	</div>
	<div class="col-9">
		<form method="POST" action="{{ url('settings/details') }}">
			@csrf

			<div class="row">

				@if (Session::has('user_details.success'))
					<div class="alert alert-icon alert-success full-width" role="alert">
						<i class="fe fe-check mr-2" aria-hidden="true"></i> Details changed succesfully.
					</div>
				@endif

				<div class="col-md-6">
					<div class="form-group">
						<label class="form-label">Name</label>
						<input type="text" class="form-control {{{ $errors->has('name') ? 'is-invalid' : '' }}}" name="name" placeholder="Acme Inc. / John Appleseed" value="{{ old('name') ?? $details->name ?? '' }}" required>
						<div class="invalid-feedback">{{ $errors->first('name') }}</div>
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group my-0">
						<label class="form-label">VAT Number</label>
						<div class="row gutters-sm">
                            <div class="col">
                            	<input type="text" class="form-control {{{ $errors->has('vat_id') ? 'is-invalid' : '' }}}" name="vat_id" placeholder="NL390193263B01" value="{{ old('vat_id') ?? $details->vat_id ?? '' }}">
                            	<div class="invalid-feedback">{{ $errors->first('vat_id') }}</div>
                            </div>
                        	<span class="col-auto align-self-center">
                          		<span class="form-help" data-toggle="popover" data-placement="top"
									data-content="In order to comply with EU tax laws a VAT number is required for all businesses located in the European Union.">?</span>
                     		</span>
                    	</div>
						<small id="emailHelp" class="form-text text-muted">VAT Number is only required for EU businesses.</small>
					</div>
				</div>

				<div class="col-12">
					<div class="form-group">
						<label class="form-label">Address</label>
						<input type="text" class="form-control {{{ $errors->has('address') ? 'is-invalid' : '' }}}" name="address" placeholder="One Infinite Loop" value="{{ old('address') ?? $details->address ?? '' }}" required>
						<div class="invalid-feedback">{{ $errors->first('address') }}</div>
					</div>
				</div>

				<div class="col-md-4">
					<div class="form-group">
						<label class="form-label">Postal code</label>
						<input type="text" class="form-control {{{ $errors->has('postal') ? 'is-invalid' : '' }}}" name="postal" placeholder="95014" value="{{ old('postal') ?? $details->postal ?? '' }}" required>
						<div class="invalid-feedback">{{ $errors->first('postal') }}</div>
					</div>
				</div>

				<div class="col-md-4">
					<div class="form-group">
						<label class="form-label">City</label>
						<input type="text" class="form-control {{{ $errors->has('city') ? 'is-invalid' : '' }}}" name="city" placeholder="Cupertino, CA" value="{{ old('city') ?? $details->city ?? '' }}" required>
						<div class="invalid-feedback">{{ $errors->first('city') }}</div>
					</div>
				</div>

				<div class="col-md-4">
					<div class="form-group">
						<label class="form-label">Country</label>
                        <select name="country" id="select-countries" class="form-control custom-select {{{ $errors->has('country') ? 'is-invalid' : '' }}}" required>
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

				<div class="col-12 text-right">
					<button class="btn btn-primary" type="submit">Change info</button>
				</div>
			</div>
		</form>
	</div>
</div>
