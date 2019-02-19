@extends('auth/master')

@section('page_title', 'Register')

@section('content')
<form method="POST" action="{{ route('register') }}" class="card">
    @csrf

    <div class="card-body p-6">
        <div class="card-title">Create new account</div>

        <div class="form-group">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="Enter name" value="{{ old('name') }}">
            <div class="invalid-feedback">{{ $errors->first('name') }}</div>
        </div>

        <div class="form-group">
            <label class="form-label">Email address</label>
            <input type="email" name="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="Enter email" value="{{ old('email') }}">
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

        {{-- <div class="form-group">
            <label class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" />
                <span class="custom-control-label">Agree the <a href="terms.html">terms and policy</a></span>
            </label>
        </div> --}}
        <div class="form-footer">
            <button type="submit" class="btn btn-primary btn-block">Create new account</button>
        </div>
    </div>
</form>
<div class="text-center text-muted">
    Already have account? <a href="{{ route('login') }}">Sign in</a>
</div>
@endsection
