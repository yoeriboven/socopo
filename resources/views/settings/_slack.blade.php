<div class="row" style="margin-bottom:20px;border-bottom:1px solid rgba(0, 40, 100, 0.12);padding-bottom:30px;">
	<div class="col-3">
		<h4 class="card-title">Slack connection</h4>
	</div>
	<div class="col-9">
		<div class="row">
			@if (count($errors))
				<div class="alert alert-icon alert-danger" role="alert" style="width:100%;">
					<i class="fe fe-alert-triangle mr-2" aria-hidden="true"></i> Connecting to Slack has failed. Try again later.
				</div>
			@endif

			@if (Session::has('success'))
				<div class="alert alert-icon alert-success" role="alert" style="width:100%;">
					<i class="fe fe-check mr-2" aria-hidden="true"></i> Slack authorization succeeded.
				</div>
			@endif

			@if ($slack)
				@include('settings._slack_authorized')
			@else
				@include('settings._slack_unauthorized')
			@endif

		</div>
	</div>
</div>
