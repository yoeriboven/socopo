@extends('auth/master')

@section('page_title', 'Reset password')

@section('content')
<form method="POST" action="{{ route('password.update') }}" class="card">
    @csrf

    <input type="hidden" name="token" value="{{ $token }}">
    <input type="hidden" name="email" value="{{ $email }}" />

    <div class="card-body p-6">
        <div class="card-title">Reset Password</div>

        <p class="text-muted">Enter a new password to reset it.</p>

        <div class="form-group">
            <label class="form-label">E-mail</label>
            <input type="email" name="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="E-mail">
            <div class="invalid-feedback">{{ $errors->first('email') }}</div>
        </div>

        <div class="form-group">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="Password">
            <div class="invalid-feedback">{{ $errors->first('password') }}</div>
        </div>

        <div class="form-group">
            <label class="form-label">Password Confirm</label>
            <input type="password" name="password_confirmation" class="form-control" placeholder="Password Confirm">
        </div>

        <div class="form-footer">
            <button type="submit" class="btn btn-primary btn-block">Reset password</button>
        </div>
    </div>
</form>
@endsection
