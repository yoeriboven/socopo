<div class="row settings_panel">
	<div class="col-sm-3 mb-3">
		<h4 class="card-title">Change Password</h4>
	</div>
	<div class="col-sm-9">
		<form method="POST" action="{{ route('change_password.store') }}">
			@csrf

			<div class="row">

				@if (Session::has('change_password.success'))
					<div class="alert alert-icon alert-success full-width" role="alert">
						<i class="fe fe-check mr-2" aria-hidden="true"></i> Changing password was successful.
					</div>
				@endif

				<div class="col-md-6">
					<div class="form-group">
						<label class="form-label">Current password</label>
						<input type="password" class="form-control {{ $errors->has('old_password') ? 'is-invalid' : '' }}" name="old_password" placeholder="&#8729;&#8729;&#8729;&#8729;&#8729;&#8729;&#8729;&#8729;" required>
						<div class="invalid-feedback">{{ $errors->first('old_password') }}</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label class="form-label">New password</label>
						<input type="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" name="password" placeholder="&#8729;&#8729;&#8729;&#8729;&#8729;&#8729;&#8729;&#8729;" required>
						<div class="invalid-feedback">{{ $errors->first('password') }}</div>
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group">
						<label class="form-label">Confirm new password</label>
						<input type="password" class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}" name="password_confirmation" placeholder="&#8729;&#8729;&#8729;&#8729;&#8729;&#8729;&#8729;&#8729;" required>
						<div class="invalid-feedback">{{ $errors->first('password_confirmation') }}</div>
					</div>
				</div>



				<div class="col-12 text-right">
					<button class="btn btn-primary" type="submit">Change password</button>
				</div>
			</div>
		</form>
	</div>
</div>
