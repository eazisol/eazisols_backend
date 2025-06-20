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
    
    <!-- General JS Scripts -->
    <script src="{{ asset('assets/js/app.min.js') }}"></script>
    <!-- JS Libraries -->
    <script src="{{ asset('assets/bundles/apexcharts/apexcharts.min.js') }}"></script>
    <!-- Page Specific JS File -->
    <script src="{{ asset('assets/js/page/index.js') }}"></script>
    <!-- Template JS File -->
    <script src="{{ asset('assets/js/scripts.js') }}"></script>
    <!-- Custom JS File -->
    <script src="{{ asset('assets/js/custom.js') }}"></script>

    <script>
    $(document).ready(function() {
        // Fix sidebar initialization issues
        if (window.location.pathname.includes('/blogs') || window.location.pathname.includes('/careers')) {
            // Only trigger for blogs and careers pages which have issues
            setTimeout(function() {
                // This helps reinitialize the sidebar
                $('body').removeClass('sidebar-mini');
                $('body').removeClass('sidebar-gone');
                
                if ($(window).outerWidth() <= 1024) {
                    $('body').addClass('sidebar-gone');
                }
                
                // Reset sidebar dropdown behavior
                $('.main-sidebar .sidebar-menu li a.has-dropdown').off('click').on('click', function() {
                    var me = $(this);
                    me.parent().find('> .dropdown-menu').slideToggle(500);
                    return false;
                });
            }, 200);
        }
    });
    </script>
    
    @yield('scripts')

</body>
</html>
