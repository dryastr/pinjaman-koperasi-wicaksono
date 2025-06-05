<!DOCTYPE html>
<html lang="en" class="light-style layout-wide customizer-hide" dir="ltr" data-theme="theme-default"
    data-assets-path="../assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Register</title>

    <meta name="description" content="" />
    <link rel="icon" type="image/x-icon" href="{{ asset('assets-auth/img/favicon/favicon.ico') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets-auth/vendor/fonts/boxicons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets-auth/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets-auth/vendor/css/theme-default.css') }}"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets-auth/css/demo.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets-auth/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets-auth/vendor/css/pages/page-auth.css') }}" />
    <script src="{{ asset('assets-auth/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets-auth/js/config.js') }}"></script>
</head>

<body>
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <div class="card">
                    <div class="card-body">

                        <h4 class="mb-2">Daftar Akun Baru!</h4>
                        <p class="mb-4">Silahkan isi form di bawah untuk mendaftar</p>

                        <form id="registerForm" class="mb-3" method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" placeholder="Masukkan nama lengkap"
                                    value="{{ old('name') }}" autofocus required />
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" placeholder="Masukkan email"
                                    value="{{ old('email') }}" required />
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3 form-password-toggle">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" placeholder="Masukkan password" required />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password-confirm" class="form-label">Konfirmasi Password</label>
                                <input type="password" class="form-control" id="password-confirm"
                                    name="password_confirmation" placeholder="Ulangi password" required />
                            </div>

                            <div class="mb-3">
                                <button class="btn btn-primary d-grid w-100" type="submit">Daftar</button>
                            </div>

                            <p class="text-center">
                                Sudah punya akun? <a href="{{ route('login') }}">Masuk</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="{{ asset('assets-auth/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets-auth/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets-auth/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets-auth/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets-auth/vendor/js/menu.js') }}"></script>
    <script src="{{ asset('assets-auth/js/main.js') }}"></script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.location.href = '/login';
        });
    </script> --}}
</body>

</html>
