<!DOCTYPE html>
<html>
<head>
    
    <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>@yield('title')</title>

  <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
  
  <!-- General CSS Files -->
  <link rel="stylesheet" href="{{ asset('assets/css/app.min.css') }}">
  <!-- Template CSS -->
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
  <!-- Custom style CSS -->
  <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
  <link rel='shortcut icon' type='image/x-icon' href="{{ asset('assets/img/logo-removebg-preview.png') }}" />
</head>
<body>
    <div class="loader"></div>
    <div id="app">
            <div class="main-wrapper main-wrapper-1">
    
                @include('partials.header')
               

                
                  @include('partials.sidebar')
               

                
                <div class="main-content">
                    
                        @yield('content')
                    
                </div>

                
                @include('partials.footer')

                
                
            </div>
    </div>
    
    {{-- Header and Navbar --}}

    

    {{-- js --}}
    <script src="{{ asset('assets/js/app.min.js') }}"></script>
  <!-- JS Libraies -->
  <script src="{{ asset('assets/bundles/apexcharts/apexcharts.min.js') }}"></script>
  <!-- Page Specific JS File -->
  <script src="{{ asset('assets/js/page/index.js') }}"></script>
  <!-- Template JS File -->
  <script src="{{ asset('assets/js/scripts.js') }}"></script>
  <!-- Custom JS File -->
  <script src="{{ asset('assets/js/custom.js') }}"></script>
  @yield('scripts')

</body>
</html>
