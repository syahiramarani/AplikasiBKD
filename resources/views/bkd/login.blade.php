<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" href="{{ asset('template/dist') }}/assets/images/favicon.svg" type="image/x-icon">

    <!-- Fonts & CSS -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="{{ asset('template/dist') }}/assets/fonts/tabler-icons.min.css">
    <link rel="stylesheet" href="{{ asset('template/dist') }}/assets/fonts/feather.css">
    <link rel="stylesheet" href="{{ asset('template/dist') }}/assets/fonts/fontawesome.css">
    <link rel="stylesheet" href="{{ asset('template/dist') }}/assets/fonts/material.css">
    <link rel="stylesheet" href="{{ asset('template/dist') }}/assets/css/style.css">
    <link rel="stylesheet" href="{{ asset('template/dist') }}/assets/css/style-preset.css">
</head>

<body>

    <div class="auth-main">
        <div class="auth-wrapper v3">
            <div class="auth-form">

                <div class="auth-header">
                    <a href="#"><img src="{{ asset('template/dist') }}/assets/images/logo-dark.svg"
                            alt="img"></a>
                </div>

                <div class="card my-5">
                    <div class="card-body">

                        @if (session('failed'))
                            <div class="alert alert-danger">{{ session('failed') }}</div>
                        @endif


                        <div class="d-flex justify-content-between align-items-end mb-4">
                            <h3><b>Login</b></h3>
                            <a href="/register" class="link-primary">Belum punya Akun?</a>
                        </div>

                        <!-- ✅ FORM -->
                        <form action="/login" method="POST" novalidate>
                            @csrf
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror

                            <!-- EMAIL -->
                            <div class="form-group mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" placeholder="Email Address"
                                    required>
                                <div class="invalid-feedback">
                                    Email wajib diisi!
                                </div>
                            </div>

                            @error('password ')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                            <!-- PASSWORD -->
                            <div class="form-group mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Password"
                                    required>
                                <div class="invalid-feedback">
                                    Password wajib diisi!
                                </div>
                            </div>

                            <!-- REMEMBER -->
                            <div class="d-flex mt-1 justify-content-between">
                                <div class="form-check">
                                    <input class="form-check-input input-primary" type="checkbox" name="remember"
                                        id="remember">
                                    <label for="remember">Remember</label>
                                </div>
                                <h5 class="text-secondary f-w-400">Forgot Password?</h5>
                            </div>

                            <!-- BUTTON -->
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div>

                        </form>
                        <!-- ✅ END FORM -->



                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- ✅ VALIDASI BOOTSTRAP -->
    <script>
        (() => {
            'use strict'

            const forms = document.querySelectorAll('form')

            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>

</body>

</html>
