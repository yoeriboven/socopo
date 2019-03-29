@extends('master')

@section('page_title', 'Settings')


@section('content')
<div class="card" style="min-height: 1000px;">
	<div class="card-header">
		<h3 class="card-title">Settings</h3>
	</div>
	<div class="card-body">
		<div class="row" style="margin-bottom:20px;border-bottom:1px solid rgba(0, 40, 100, 0.12);">
			<div class="col-3">
				<h4 class="card-title">Business info</h4>
			</div>
			<div class="col-9">
				<div class="form-group">
					<label class="form-label">Name</label>
					<input type="text" class="form-control" name="example-text-input" placeholder="Name">
				</div>
				<div class="form-group">
					<label class="form-label">Address</label>
					<input type="text" class="form-control" name="example-text-input" placeholder="Address">
				</div>
			</div>
		</div>
	</div>
</div>

@endsection
