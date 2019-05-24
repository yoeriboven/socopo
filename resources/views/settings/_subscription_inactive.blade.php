<div class="row settings_panel pb-30">
	<div class="col-sm-3 mb-3">
		<h4 class="card-title">Subscription</h4>
	</div>
	<div class="col-sm-9">
		You are not subscribed yet. Upgrade your account to get more perks.<br/><br/>

		<strong>Max Profiles: </strong>{{ $plan->maxProfiles }} profiles<br/>
		<strong>Interval: </strong>{{ $plan->interval }} minutes
		<span class="col-auto align-self-center">
      		<span class="form-help" data-toggle="popover" data-placement="top"
				data-content="How often we check your selected Instagram profiles.">?</span>
 		</span>

		<div class="row">
			<div class="col-6">
				<a href="{{ route('upgrade') }}" style="position: absolute;bottom: 0;">View plans</a>
			</div>
			<div class="col-6 text-right">
				<a href="{{ route('upgrade') }}" class="btn btn-primary">Upgrade</a>
			</div>
		</div>
	</div>
</div>
