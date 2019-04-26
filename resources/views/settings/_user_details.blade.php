<div class="row settings_panel">
	<div class="col-3">
		<h4 class="card-title">User info</h4>
	</div>
	<div class="col-9">
		<form method="POST" action="{{ url('settings/details') }}">
			@csrf

			@if (Session::has('user_details.success'))
				<div class="alert alert-icon alert-success full-width" role="alert">
					<i class="fe fe-check mr-2" aria-hidden="true"></i> Details changed succesfully.
				</div>
			@endif

			@include('partials._user_details_fields')

			<div class="row">
				<div class="col-12 text-right">
					<button class="btn btn-primary" type="submit">Change info</button>
				</div>
			</div>

		</form>
	</div>
</div>
