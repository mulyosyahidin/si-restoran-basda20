<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>@yield('title')</title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/dist/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/plugins/@fortawesome/fontawesome-free/css/all.min.css') }}">

  <!-- Template CSS -->
  <link rel="stylesheet" href="{{ asset('assets/themes/stisla/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/themes/stisla/css/components.css') }}">

  @yield('custom_head')
</head>

<body>
  <div id="app">
    <div class="main-wrapper">
      <x-nav-bar />
      <x-side-bar />

      <!-- Main Content -->
      @yield('content')

      <footer class="main-footer">
        <div class="footer-left">
          &copy; 2020 {{ getSiteName() }}
        </div>
        <div class="footer-right">
          1.0.0
        </div>
      </footer>
    </div>
  </div>

  @yield('custom_html')

  <!-- General JS Scripts -->
  <script src="{{ asset('assets/plugins/jquery/dist/jquery.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/popper.js/dist/umd/popper.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/bootstrap/dist/js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/jquery.nicescroll/dist/jquery.nicescroll.min.js') }}"></script>
  <script src="{{ asset('assets/themes/stisla/js/stisla.js') }}"></script>
  <script src="{{ asset('assets/themes/stisla/js/scripts.js') }}"></script>

  @stack('custom_js')
</body>
</html>
