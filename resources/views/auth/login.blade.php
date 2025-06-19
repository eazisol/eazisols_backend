@extends('layouts.login')
@section('title', 'Login')
@section('content')
<div class="container mt-5">
  <div class="row">
    <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
      <div class="card card-primary">
        <div class="card-header justify-content-center">
          <h4>Login</h4>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate="">
            @csrf

            <div class="form-group">
              <label for="email">Email</label>
              <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                name="email" value="{{ old('email') }}" required autofocus>
              @error('email')
                <div class="invalid-feedback d-block">
                  {{ $message }}
                </div>
              @enderror
            </div>

            <div class="form-group">
              <label for="password" class="d-block">Password</label>
              <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                name="password" required>
              @error('password')
                <div class="invalid-feedback d-block">
                  {{ $message }}
                </div>
              @enderror
            </div>

            <div class="form-group">
              <div class="custom-control custom-checkbox">
                <input type="checkbox" name="remember" class="custom-control-input" id="remember-me"
                  {{ old('remember') ? 'checked' : '' }}>
                <label class="custom-control-label" for="remember-me">Remember Me</label>
              </div>
            </div>

            <div class="form-group">
              <button type="submit" class="btn btn-primary btn-lg btn-block">
                Login
              </button>
            </div>

            @if (Route::has('password.request'))
              <div class="text-center">
                <a href="{{ route('password.request') }}">Forgot Password?</a>
              </div>
            @endif
          </form>
        </div>
      </div>
      <div class="mt-5 text-muted text-center">
        Don't have an account? <a href="{{ route('register') }}">Create One</a>
      </div>
    </div>
  </div>
</div>
@endsection
