<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">

    <title>Login &mdash; {{ getSiteName() }}</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/@fortawesome/fontawesome-free/css/all.min.css') }}">

    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-social/bootstrap-social.css') }}">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('assets/themes/stisla/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/themes/stisla/css/components.css') }}">
</head>

<body>
    <div id="app">
        <section class="section">
            <div class="d-flex flex-wrap align-items-stretch">
                <div class="col-lg-4 col-md-6 col-12 order-lg-1 min-vh-100 order-2 bg-white">
                    <div class="p-4 m-3">
                        <img src="{{ asset('assets/themes/stisla/img/stisla-fill.svg') }}" alt="logo" width="80"
                            class="shadow-light rounded-circle mb-5 mt-2">
                        <h4 class="text-dark font-weight-normal">Selamat Datang <span
                                class="font-weight-bold">{{ getSiteName() }}</span></h4>
                        <p class="text-info-container">
                            Untuk melanjutkan, silahkan login menggunakan email dan password Anda.
                        </p>

                        <form method="POST" action="#" class="needs-validation" id="login-form" novalidate="">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input id="email" type="email" class="form-control" name="email" tabindex="1" required>
                                <div class="invalid-feedback email-feedback">
                                    Silahkan masukkan email Anda
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="d-block">
                                    <label for="password" class="control-label">Password</label>
                                </div>
                                <input id="password" type="password" class="form-control" name="password" tabindex="2"
                                    required>
                                <div class="invalid-feedback password-feedback">
                                    Silahkan masukkan password Anda
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="remember" class="custom-control-input" tabindex="3"
                                        id="remember-me">
                                    <label class="custom-control-label" for="remember-me">Remember Me</label>
                                </div>
                            </div>

                            <div class="form-group text-right">
                                <a href="{{ route('password.request') }}" class="float-left mt-3">
                                    Lupa Password?
                                </a>
                                <button type="submit" class="login-btn btn btn-primary btn-lg btn-icon icon-right"
                                    tabindex="4">
                                    Login
                                </button>
                            </div>

                            @csrf
                        </form>

                        <div class="text-center text-small mt-5">
                            Sistem Informasi {{ getSiteName() }} &bull; Informatika UNIB 2019
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-12 order-lg-2 order-1 min-vh-100 background-walk-y position-relative overlay-gradient-bottom"
                    data-background="{{ asset('assets/uploads/images/bg-home.jpg') }}">
                    <div class="absolute-bottom-left index-2">
                        <div class="text-light p-5 pb-2">
                            <div class="mb-5 pb-3">
                                <h1 class="mb-2 display-4 font-weight-bold">Selamat Datang</h1>
                                <h5 class="font-weight-normal text-muted-transparent">Bengkulu, Indonesia</h5>
                            </div>
                            Photo by <a class="text-light bb" target="_blank"
                                href="https://www.freepik.com/free-photo/lunch-table-with-healthy-organic-food-top-view_10896582.htm#page=2&query=restaurant&position=20">@pvproductions</a> on <a
                                class="text-light bb" target="_blank" href="https://www.freepik.com/">Freepik</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- General JS Scripts -->
    <script src="{{ asset('assets/plugins/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery.nicescroll/dist/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ asset('assets/themes/stisla/js/stisla.js') }}"></script>
    <script src="{{ asset('assets/themes/stisla/js/scripts.js') }}"></script>
    <script>
        let loginForm = document.querySelector('#login-form');
        let txtInfo = document.querySelector('.text-info-container');

        loginForm.addEventListener('submit', (e) => {
            e.preventDefault();

            let email = loginForm.querySelector('#email');
            let password = loginForm.querySelector('#password');
            let loginBtn = loginForm.querySelector('.login-btn');

            let loginData = new FormData(loginForm);

            if (email.value != '' && password.value != '') {
                loginBtn.innerHTML = '<i class="fa fa-spin fa-spinner"></i> Login...';
                loginBtn.setAttribute('disabled', 'disabled');

                fetch('{{ route('auth.login') }}', {
                            method: 'POST',
                            body: loginData
                        })
                    .then(res => res.json())
                    .then(res => {
                        if (res.error) {
                            loginBtn.innerHTML = 'Login';
                            loginBtn.removeAttribute('disabled');

                            if (res.validations) {
                                if (res.validations.email) {
                                    email.classList.add('is-invalid');
                                    loginForm.querySelector('.email-feedback')
                                        .innerHTML = res.validations.email[0];
                                }

                                if (res.validations.password) {
                                    password.classList.add('is-invalid');
                                    loginForm.querySelector('.password-feedback')
                                        .innerHTML = res.validations.password[0];
                                }
                            } else {
                                email.classList.add('is-invalid');
                                loginForm.querySelector('.email-feedback')
                                    .innerHTML = res.message;
                            }
                        } else if (res.success) {
                            localStorage.setItem('accessToken', res.token.accessToken);
                            localStorage.setItem('accessTokenType', 'Bearer');
                            localStorage.setItem('accessTokenExpire', res.token.expiresAt);

                            loginBtn.innerHTML = '<i class="fa fa-check"></i> Berhasil login';

                            email.classList.add('is-valid');
                            password.classList.add('is-valid');

                            txtInfo.innerHTML = 'Berhasil login.';
                            setTimeout(() => {
                                txtInfo.innerHTML = 'Berhasil login. Anda akan dialihkan segera...';
                            }, 1500);

                            setTimeout(() => {
                                txtInfo.innerHTML =
                                    '<i class="fa fa-spin fa-spinner"></i> Mengalihkan...';
                            }, 3000);

                            txtInfo.classList.add('alert');
                            txtInfo.classList.add('alert-success');

                            setTimeout(() => {
                                window.location = '{{ route('home') }}';
                            }, 5000);
                        }
                    })
                    .catch(errors => {
                        document.querySelector('.text-info')
                            .innerHTML = errors;
                    })
            }
        })

    </script>
</body>

</html>
