@extends('auth/master')

@section('page_title', 'Forgot password');

@section('content')
<form method="POST" action="{{ route('password.email') }}" class="card">
    @csrf

    <div class="card-body p-6">
        <div class="card-title">Forgot Password</div>

        @if (Session::has('status'))
            <div class="alert alert-icon alert-success" role="alert">
                <i class="fe fe-check mr-2" aria-hidden="true"></i> We have e-mailed your password reset link!
            </div>
        @endif

        <p class="text-muted">Enter your email address and your password will be reset and emailed to you.</p>

        <div class="form-group">
            <label class="form-label">Email address</label>
            <input type="email" name="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="Enter email" value="{{ old('email') }}">
            <div class="invalid-feedback">{{ $errors->first('email') }}</div>
        </div>

        <div class="form-footer">
            <button type="submit" class="btn btn-primary btn-block">Send me new password</button>
        </div>
    </div>
</form>
<div class="text-center text-muted">
    Forget it, <a href="{{ route('login') }}">send me back</a> to the sign in screen.
</div>
@endsection
