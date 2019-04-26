<div class="row settings_panel pb-30">
	<div class="col-3">
		<h4 class="card-title">Subscription</h4>
	</div>
	<div class="col-9">
		<strong>Plan: </strong> {{ $subscription->name }}<br/>

		@if ($subscription->cancelled())
			<strong>Subscribed until: </strong> {{ $subscription->ends_at->format('F jS, Y') }}<br/>
		@else
			<strong>Renewal date: </strong> {{ $subscription->current_period_end->format('F jS, Y') }}<br/>
		@endif

		<div class="row">
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
