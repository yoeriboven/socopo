<div class="row" style="margin-bottom:20px;border-bottom:1px solid rgba(0, 40, 100, 0.12);padding-bottom:30px;">
	<div class="col-3">
		<h4 class="card-title">Slack connection</h4>
	</div>
	<div class="col-9">
		<div class="row">
			@if ($slack)
				@include('settings._slack_authorized')
			@else
				@include('settings._slack_unauthorized')
			@endif
		</div>
	</div>
</div>
