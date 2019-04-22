<div class="row" style="margin-bottom:20px;border-bottom:1px solid rgba(0, 40, 100, 0.12);padding-bottom:30px;">
	<div class="col-3">
		<h4 class="card-title">Subscription</h4>
	</div>
	<div class="col-9">
		<strong>Plan: </strong> {{ auth()->user()->subscription()->name }}<br/>
		<strong>Renewal date: </strong> {{ auth()->user()->subscription()->renewDate }}<br/>

		<div class="row">
			<div class="col-6">
				<a href="#" style="position: absolute;bottom: 0;">Cancel subscription</a>
			</div>
			<div class="col-6 text-right">
				<a href="{{ route('upgrade') }}" class="btn btn-primary">Change plan</a>
			</div>
		</div>
	</div>
</div>
