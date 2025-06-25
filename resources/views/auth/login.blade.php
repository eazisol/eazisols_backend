@extends('layouts.login')
@section('title', 'Login')
@section('content')
<style>
@keyframes bounce-in {
  0%   { transform: scale(0.95) translateY(0); }
  30%  { transform: scale(1.03) translateY(-10px);}
  50%  { transform: scale(0.98) translateY(0);}
  70%  { transform: scale(1.01) translateY(-4px);}
  100% { transform: scale(1) translateY(0);}
}
.bounce-on-load {
  animation: bounce-in 0.8s ease;
  opacity: 0; /* Hide initially */
}

/* Sparrow Animation Styles */
.animation-container {
  position: absolute;
  top: -10px;
  left: 0;
  width: 100%;
  height: 100%;
  pointer-events: none;
  z-index: 10;
  overflow: hidden;
}

#sparrow {
  position: absolute;
  top: 80px;
  left: -200px;
  width: 200px;
  transition: all 3s cubic-bezier(0.215, 0.61, 0.355, 1);
  z-index: 1001;
  transform-origin: center;
}

/* Logo bounce animation */
@keyframes gentle-bounce {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-5px); }
}

#carried-logo {
  position: absolute;
  width: 50px;
  height: 50px;
  top: 140px;
  left: -160px;
  z-index: 1000;
  transition: all 3s cubic-bezier(0.215, 0.61, 0.355, 1);
  opacity: 1;
  animation: none;
}

/* Apply bounce animation to the form logo after it appears */
.bounce-after-drop {
  animation: gentle-bounce 1.5s infinite ease-in-out;
}

@keyframes flutter {
  0%, 100% { transform: rotate(0deg) translateY(0px); }
  25% { transform: rotate(-5deg) translateY(-5px); }
  75% { transform: rotate(5deg) translateY(5px); }
}

.fluttering {
  animation: flutter 0.5s infinite;
}

@keyframes drop-logo {
  0% { transform: translateY(0); opacity: 1; }
  100% { transform: translateY(100px); opacity: 0; }
}

.dropping {
  animation: drop-logo 1s forwards !important;
}

/* Center the login form */
.login-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  width: 100%;
  padding: 20px;
  background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
}

.login-card {
  width: 100%;
  max-width: 420px;
}

/* Enhanced UI Styles */
.card.card-primary {
  border: none;
  border-radius: 15px;
  box-shadow: 0 15px 35px rgba(50, 50, 93, 0.1), 0 5px 15px rgba(0, 0, 0, 0.07);
  overflow: hidden;
}

.card-header {
  background: white !important;
  border-bottom: none !important;
  padding: 25px 0 15px !important;
}

.card-body {
  padding: 30px 40px 40px !important;
}

.form-group {
  margin-bottom: 25px;
  position: relative;
}

.form-control {
  height: 55px;
  padding: 10px 20px;
  border-radius: 10px;
  border: 1px solid #e1e5eb;
  background-color: #f8f9fc;
  transition: all 0.3s;
  font-size: 16px;
  box-shadow: 0 1px 3px rgba(50, 50, 93, 0.05);
}

.form-control:focus {
  border-color: #4e73df;
  background-color: #fff;
  box-shadow: 0 3px 10px rgba(78, 115, 223, 0.1);
}

.form-control::placeholder {
  color: #b1b7c1;
  opacity: 0.7;
}

label {
  font-weight: 600;
  color: #5a5c69;
  margin-bottom: 8px;
  font-size: 14px;
}

/* Button with shine effect */
.btn-primary {
  background: linear-gradient(135deg, 
    #6e8efb 0%, 
    #4e73df 40%, 
    #3d5fc4 60%, 
    #224abe 100%);
  border: none;
  height: 55px;
  font-size: 16px;
  font-weight: 600;
  border-radius: 10px;
  letter-spacing: 0.5px;
  box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
  transition: all 0.15s ease;
  position: relative;
  overflow: hidden;
}

.btn-primary:hover {
  transform: translateY(-1px);
  box-shadow: 0 7px 14px rgba(50, 50, 93, 0.1), 0 3px 6px rgba(0, 0, 0, 0.08);
  background: linear-gradient(135deg, 
    #7d9bff 0%, 
    #5a7ee0 40%, 
    #4b6fd4 60%, 
    #2e5bd8 100%);
}

/* Enhanced Shine effect with gradient */
.btn-primary::after {
  content: '';
  position: absolute;
  top: -50%;
  left: -100%;
  width: 70%;
  height: 200%;
  background: linear-gradient(
    to right, 
    rgba(255, 255, 255, 0.0) 0%,
    rgba(255, 255, 255, 0.2) 30%,
    rgba(255, 255, 255, 0.4) 50%,
    rgba(164, 192, 255, 0.3) 70%,
    rgba(108, 142, 245, 0.2) 85%,
    rgba(76, 139, 245, 0.0) 100%
  );
  transform: rotate(30deg);
  transition: all 0.8s;
}

.btn-primary:hover::after {
  left: 120%;
  transition: all 0.8s;
}

.custom-control-label {
  padding-top: 2px;
  font-size: 14px;
}

.custom-control-input:checked ~ .custom-control-label::before {
  background-color: #4e73df;
  border-color: #4e73df;
}

.text-center a {
  color: #4e73df;
  font-weight: 600;
  text-decoration: none;
  transition: all 0.2s;
}

.text-center a:hover {
  color: #224abe;
  text-decoration: underline;
}

/* Remove logo border and shadow */
.brand-image {
  border: none;
  box-shadow: none;
}
</style>

<div class="animation-container">
  <!-- Dove GIF instead of SVG -->
  <img id="sparrow" src="{{ asset('assets/img/hummingbird-12098.gif') }}" alt="Dove">
  
  <!-- Logo carried by dove -->
  <img id="carried-logo" src="{{ asset('assets/img/logo-removebg-preview.png') }}" alt="Flying Logo">
</div>

<div class="login-container">
  <div class="login-card">
    <div class="card card-primary">
      <div class="card-header justify-content-center">
        <img id="form-logo" src="{{ asset('assets/img/logo-removebg-preview.png') }}" alt="logo" class="brand-image img-circle elevation-3 bounce-on-load"
          style="width: 100px; height: 100px;">
      </div>
      <div class="card-body">
        <h4 class="text-center mb-4" style="color: #5a5c69; font-weight: 700;">Welcome Back!</h4>
        <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate="">
          @csrf

          <div class="form-group">
            <label for="email">Email Address</label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
              name="email" value="{{ old('email') }}" required autofocus placeholder="Enter your email">
            @error('email')
              <div class="invalid-feedback d-block">
                {{ $message }}
              </div>
            @enderror
          </div>

          <div class="form-group">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <label for="password" class="mb-0">Password</label>
              @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="small">Forgot Password?</a>
              @endif
            </div>
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
              name="password" required placeholder="Enter your password">
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

          <div class="form-group mb-0">
            <button type="submit" class="btn btn-primary btn-lg btn-block">
              Sign In
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const sparrow = document.getElementById('sparrow');
  const carriedLogo = document.getElementById('carried-logo');
  const formLogo = document.getElementById('form-logo');
  const cardHeader = document.querySelector('.card-header');
  
  // Get the center position of the form for better positioning
  const formRect = document.querySelector('.card-primary').getBoundingClientRect();
  const centerX = formRect.left + (formRect.width / 2) - 60;
  
  // Add fluttering animation to sparrow
  sparrow.classList.add('fluttering');
  
  // Fly in with logo
  setTimeout(function() {
    sparrow.style.left = (centerX - 20) + 'px';
    carriedLogo.style.left = (centerX + 30) + 'px';
  }, 500);
  
  // Hover above form
  setTimeout(function() {
    sparrow.classList.remove('fluttering');
    sparrow.style.transform = 'translateY(-10px)';
    carriedLogo.style.transform = 'translateY(-10px)';
  }, 3500);
  
  // Drop logo
  setTimeout(function() {
    carriedLogo.classList.add('dropping');
    
    // Show form logo with bounce animation
    setTimeout(function() {
      formLogo.style.opacity = '1';
      // Add bounce animation to the form logo after it appears
      formLogo.classList.add('bounce-after-drop');
    }, 500);
    
    // Fly away
    setTimeout(function() {
      sparrow.style.left = window.innerWidth + 200 + 'px';
      sparrow.style.top = '0px';
      sparrow.classList.add('fluttering');
    }, 1000);
  }, 4000);
});
</script>
@endsection
