<div class="row" style="margin-bottom:20px;border-bottom:1px solid rgba(0, 40, 100, 0.12);padding-bottom:10px;">
	<div class="col-3">
		<h4 class="card-title">Business info</h4>
	</div>
	<div class="col-9">
		<form method="POST" action="{{ url('settings/details') }}">
			@csrf

			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label class="form-label">Name</label>
						<input type="text" class="form-control" name="name" placeholder="Acme Inc. / John Appleseed" required>
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group my-0">
						<label class="form-label">VAT Number</label>
						<div class="row gutters-sm">
                            <div class="col">
                            	<input type="text" class="form-control" name="vat_id" placeholder="NL390193263B01">
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
						<input type="text" class="form-control" name="address" placeholder="One Infinite Loop" required>
					</div>
				</div>

				<div class="col-md-4">
					<div class="form-group">
						<label class="form-label">Postal code</label>
						<input type="text" class="form-control" name="postal" placeholder="95014" required>
					</div>
				</div>

				<div class="col-md-4">
					<div class="form-group">
						<label class="form-label">City</label>
						<input type="text" class="form-control" name="city" placeholder="Cupertino, CA" required>
					</div>
				</div>

				<div class="col-md-4">
					<div class="form-group">
						<label class="form-label">Country</label>
                        <select name="country" id="select-countries" class="form-control custom-select" required>
	                        <option value="" disabled selected>Country...</option>

	                        @foreach (config('countries') as $code => $country)
								<option value="{{ $code }}">{{ $country }}</option>
							@endforeach

                        </select>
					</div>
				</div>

				<div class="col-12 text-right">
					<button class="btn btn-primary" type="submit">Change info</button>
				</div>
			</div>
		</form>
	</div>
</div>
