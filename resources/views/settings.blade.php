@extends('master')

@section('page_title', 'Settings')


@section('content')
<div class="card" style="min-height: 1000px;">
	<div class="card-header">
		<h3 class="card-title">Settings</h3>
	</div>
	<div class="card-body">
		<div class="row" style="margin-bottom:20px;border-bottom:1px solid rgba(0, 40, 100, 0.12);">
			<div class="col-3">
				<h4 class="card-title">Business info</h4>
			</div>
			<div class="col-9">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="form-label">Name</label>
							<input type="text" class="form-control" name="example-text-input" placeholder="Acme Inc. / John Appleseed">
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group">
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
							{{-- <small id="emailHelp" class="form-text text-muted">VAT Number is only required for EU businesses.</small> --}}
						</div>
					</div>

					<div class="col-12">
						<div class="form-group">
							<label class="form-label">Address</label>
							<input type="text" class="form-control" name="example-text-input" placeholder="One Infinite Loop">
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label class="form-label">Postal code</label>
							<input type="text" class="form-control" name="example-text-input" placeholder="95014">
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label class="form-label">City</label>
							<input type="text" class="form-control" name="example-text-input" placeholder="Cupertino, CA">
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label class="form-label">Country</label>
							<input type="text" class="form-control" name="example-text-input" placeholder="NL390193263B01">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection
