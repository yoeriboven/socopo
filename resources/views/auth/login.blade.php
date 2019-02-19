@extends('auth/master')

@section('page_title', 'Login')

@section('content')
<form method="POST" action="{{ route('login') }}" class="card">
    @csrf

    <div class="card-body p-6">
        <div class="card-title">Login to your account</div>

        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <div class="form-group">
            <label class="form-label">Email address</label>
            <input type="email" name="email" id="emailField" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="Enter email" value="{{ old('email') }}">
            <div class="invalid-feedback">{{ $errors->first('email') }}</div>
        </div>

        <div class="form-group">
            <label class="form-label">
                Password
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="float-right small">I forgot password</a>
                @endif

            </label>
            <input type="password" name="password" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="Password">
            <div class="invalid-feedback">{{ $errors->first('password') }}</div>
        </div>

        {{-- <div class="form-group">
            <label class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" />
                <span class="custom-control-label">Remember me</span>
            </label>
        </div> --}}

        <div class="form-footer">
            <button type="submit" class="btn btn-primary btn-block">Sign in</button>
        </div>
    </div>
</form>

<div class="text-center text-muted">
    Don't have account yet? <a href="{{ route('register') }}">Sign up</a>
</div>
@endsection
