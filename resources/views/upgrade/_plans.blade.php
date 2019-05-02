<div class="card">
    <div class="card-body">
		<div class="row">
			<div class="col-3">
				<h4 class="card-title">Step 1: Pick a plan</h4>
			</div>
			<div class="col-9">
				<div class="form-group">
					<div class="row">
						<div class="col-sm-6 col-lg-4">
							<label class="pricing-label">
								<input type="radio" class="custom-control-input" name="plan" value="plan_1" {{ old('plan') == 'plan_1' ? 'checked' : '' }}>

								<div class="card">
									<div class="card-body text-center">
										<div class="card-category">Start-up</div>
										<div class="display-3 my-4">$20</div>
										<ul class="list-unstyled leading-loose">
											<li><strong>25</strong> Profiles</li>
											<li><i class="fe fe-check text-success mr-2" aria-hidden="true"></i> Sharing Tools</li>
											<li><i class="fe fe-x text-danger mr-2" aria-hidden="true"></i> Design Tools</li>
											<li><i class="fe fe-x text-danger mr-2" aria-hidden="true"></i> Private Messages</li>
											<li><i class="fe fe-x text-danger mr-2" aria-hidden="true"></i> Twitter API</li>
										</ul>
									</div>
								</div>
							</label>
						</div>

						<div class="col-sm-6 col-lg-4">
							<label class="pricing-label">
								<input type="radio" class="custom-control-input" name="plan" value="plan_2" {{ old('plan') == 'plan_2' ? 'checked' : '' }}>

								<div class="card">
									<div class="card-body text-center">
										<div class="card-category">Company</div>
										<div class="display-3 my-4">$50</div>
										<ul class="list-unstyled leading-loose">
											<li><strong>100</strong> Profiles</li>
											<li><i class="fe fe-check text-success mr-2" aria-hidden="true"></i> Sharing Tools</li>
											<li><i class="fe fe-x text-danger mr-2" aria-hidden="true"></i> Design Tools</li>
											<li><i class="fe fe-x text-danger mr-2" aria-hidden="true"></i> Private Messages</li>
											<li><i class="fe fe-x text-danger mr-2" aria-hidden="true"></i> Twitter API</li>
										</ul>
									</div>
								</div>
							</label>
						</div>

						<div class="col-sm-6 col-lg-4">
							<label class="pricing-label">
								<input type="radio" class="custom-control-input" name="plan" value="plan_3" {{ old('plan') == 'plan_3' ? 'checked' : '' }}>

								<div class="card">
									<div class="card-body text-center">
										<div class="card-category">Agency</div>
										<div class="display-3 my-4">$70</div>
										<ul class="list-unstyled leading-loose">
											<li><strong>Unlimited</strong> Profiles</li>
											<li><i class="fe fe-check text-success mr-2" aria-hidden="true"></i> Sharing Tools</li>
											<li><i class="fe fe-x text-danger mr-2" aria-hidden="true"></i> Design Tools</li>
											<li><i class="fe fe-x text-danger mr-2" aria-hidden="true"></i> Private Messages</li>
											<li><i class="fe fe-x text-danger mr-2" aria-hidden="true"></i> Twitter API</li>
										</ul>
									</div>
								</div>
							</label>
						</div>
					</div>

					<p style="color:#cd201f;">{{ $errors->first('plan') }}</p>
					<p class="text-muted">*All plans are monthly subscriptions</p>
				</div>


			</div>
		</div>
	</div>
</div>
