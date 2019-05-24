<div class="row settings_panel pb-30">
	<div class="col-sm-3 mb-3">
		<h4 class="card-title">Subscription</h4>
	</div>
	<div class="col-sm-9">
		<strong>Plan: </strong> {{ $subscription->name }}<br/>

		@if ($subscription->cancelled())
			<strong>Cancel date: </strong> {{ $subscription->ends_at->format('F jS, Y') }}<br/><br/>
		@else
			<strong>Renewal date: </strong> {{ $subscription->current_period_end->format('F jS, Y') }}<br/><br/>
		@endif

		<strong>Max Profiles: </strong>{{ $plan->maxProfiles }} profiles<br/>
		<strong>Interval: </strong>{{ $plan->interval }} minutes
		<span class="col-auto align-self-center">
      		<span class="form-help" data-toggle="popover" data-placement="top"
				data-content="How often we check your selected Instagram profiles.">?</span>
 		</span>

		<div class="row mt-3">
			<div class="col-6">
				@if (! $subscription->cancelled())
					<a href="#" data-toggle="modal" data-target="#cancelSubscriptionModal" style="position: absolute;bottom: 0;">Cancel subscription</a>
				@endif
			</div>
			<div class="col-6 text-right">
				<a href="{{ route('upgrade') }}" class="btn btn-primary">Change plan</a>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="cancelSubscriptionModal" tabindex="-1" role="dialog" aria-labelledby="Cancel Subscription Modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Are you sure?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <!-- <span aria-hidden="true">&times;</span> -->
                </button>
            </div>
            <form action="{{ route('subscription.destroy', $subscription->id) }}" method="POST">
            	@csrf
				@method('DELETE')

	            <div class="modal-body">
					Are you sure you want to cancel your subscription?
	            </div>
	            <div class="modal-footer">
			        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			        <button type="submit" class="btn btn-danger">Cancel subscription</button>
			    </div>
			</form>
        </div>
    </div>
</div>
