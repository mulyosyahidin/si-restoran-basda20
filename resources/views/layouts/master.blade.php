<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>@yield('title') | {{ getSiteName() }}</title>

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

      @yield('content')

      <footer class="main-footer">
        @section('footer')
        <div class="footer-left">
          &copy; 2020 {{ getSiteName() }}
        </div>
        <div class="footer-right">
          1.0.0
        </div>
        @show
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

  <script>
    let logOutBtn = document.querySelector('.logout-btn');
    let passportAccessToken = localStorage.getItem('accessToken');
    
    logOutBtn.addEventListener('click', (e) => {
      e.preventDefault();

      fetch('{{ route('auth.logout') }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer '+ passportAccessToken
        },
        body: JSON.stringify({
          _token: '{{ csrf_token() }}'
        })
      })
        .then(res => res.json())
        .then(res => {
          if (res.success) {
            localStorage.removeItem('accessToken');
            localStorage.removeItem('accessTokenType');
            localStorage.removeItem('accessTokenExpire');

            window.location = '{{ route('auth.login') }}';
          }
        })
        .catch(errors => {
          console.log(errors)
        })
      
    })
  </script>

  @stack('custom_js')
</body>
</html>
