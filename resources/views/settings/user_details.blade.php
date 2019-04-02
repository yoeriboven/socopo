<div class="row" style="margin-bottom:20px;border-bottom:1px solid rgba(0, 40, 100, 0.12);">
	<div class="col-3">
		<h4 class="card-title">Business info</h4>
	</div>
	<div class="col-9">
		<form>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label class="form-label">Name</label>
						<input type="text" class="form-control" name="example-text-input" placeholder="Acme Inc. / John Appleseed" required>
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group my-0">
						<label class="form-label">VAT Number</label>
						<div class="row gutters-sm">
                            <div class="col">
                            	<input type="text" class="form-control" name="example-text-input" placeholder="NL390193263B01">
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
						<input type="text" class="form-control" name="example-text-input" placeholder="One Infinite Loop" required>
					</div>
				</div>

				<div class="col-md-4">
					<div class="form-group">
						<label class="form-label">Postal code</label>
						<input type="text" class="form-control" name="example-text-input" placeholder="95014" required>
					</div>
				</div>

				<div class="col-md-4">
					<div class="form-group">
						<label class="form-label">City</label>
						<input type="text" class="form-control" name="example-text-input" placeholder="Cupertino, CA" required>
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

				<button type="submit">Go</button>
			</div>
		</form>
	</div>
</div>
