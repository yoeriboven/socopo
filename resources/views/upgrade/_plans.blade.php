<div class="card">
    <div class="card-body">
		<div class="row">
			<div class="col-lg-3 mb-3">
				<h4 class="card-title">Step 1: Pick a plan</h4>
				<p>On the free plan Instagram is checked every 10 minutes and you can add 10 profiles.</p>
			</div>
			<div class="col-lg-9">
				<div class="form-group">
					<div class="row">
						<div class="col-12 col-md-4">
							<label class="pricing-label">
								<input type="radio" class="custom-control-input" name="plan" value="plan_1" {{ old('plan') == 'plan_1' ? 'checked' : '' }}>

								<div class="card">
									<div class="card-body text-center">
										<div class="card-category">Pro</div>
										<div class="display-3 my-4">$19</div>
										<ul class="list-unstyled leading-loose">
											<li><strong>25</strong> Profiles</li>
											<li>Checks every 5 minutes</li>
										</ul>
									</div>
								</div>
							</label>
						</div>

						<div class="col-12 col-md-4">
							<label class="pricing-label">
								<input type="radio" class="custom-control-input" name="plan" value="plan_2" {{ old('plan') == 'plan_2' ? 'checked' : '' }}>

								<div class="card">
									<div class="card-body text-center">
										<div class="card-category">Brand</div>
										<div class="display-3 my-4">$49</div>
										<ul class="list-unstyled leading-loose">
											<li><strong>100</strong> Profiles</li>
											<li>Checks every 3 minutes</li>
										</ul>
									</div>
								</div>
							</label>
						</div>

						<div class="col-12 col-md-4">
							<label class="pricing-label">
								<input type="radio" class="custom-control-input" name="plan" value="plan_3" {{ old('plan') == 'plan_3' ? 'checked' : '' }}>

								<div class="card">
									<div class="card-body text-center">
										<div class="card-category">Agency</div>
										<div class="display-3 my-4">$69</div>
										<ul class="list-unstyled leading-loose">
											<li><strong>Unlimited</strong> Profiles</li>
											<li>Checks every minute</li>
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
