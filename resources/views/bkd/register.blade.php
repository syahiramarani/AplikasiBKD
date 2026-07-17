<!DOCTYPE html>
<html lang="en">

<head>
    <title>Register</title>
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
                    <h3>Aplikasi BKD</h3>
                </div>

                <div class="card my-5">
                    <div class="card-body">

                        @if (session('failed'))
                            <div class="alert alert-danger">{{ session('failed') }}</div>
                        @endif


                        <div class="d-flex justify-content-between align-items-end mb-4">
                            <h3><b>Register</b></h3>
                        </div>

                        <!-- ✅ FORM -->
                        <form action="/register" method="POST" novalidate>
                            @csrf
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror

                            <!-- Name -->
                            <div class="form-group mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control" placeholder="Name"
                                    value="{{ old('name') }}">
                                <div class="invalid-feedback">
                                    Nama Wajib Diisi!
                                </div>
                            </div>

                            <!-- EMAIL -->
                            <div class="form-group mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" placeholder="Email Address"
                                    value="{{ old('email') }}">
                                <div class="invalid-feedback">
                                    Email wajib diisi!
                                </div>
                            </div>

                            @error('password')
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
                            @error('confirm_password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                            <!-- PASSWORD -->
                            <div class="form-group mb-3">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" name="confirm_password" class="form-control"
                                    placeholder="Password_Confirmation" id="confirm-password">
                                <div class="invalid-feedback">
                                    Password wajib diisi!
                                </div>
                            </div>

                            <!-- BUTTON -->
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary">Create Account</button>
                            </div>

                        </form>
                        <!-- ✅ END FORM -->

                        <p class="mt-3">
                            Already have an account?
                        </p>
                        <a href="/login" class="text-center">Login Here!</a>
                        </p>

                        <!-- ✅ VALIDASI BOOTSTRAP -->
                        <script>
                            $(document).ready(function() {

                                $('.show-password').on('click', function() {
                                    if ($('#password').attr('type') === 'password') {
                                        $('#password').attr('type', 'text');
                                        $('#password-lock').attr('class', 'fas fa-unlock');
                                    } else {
                                        $('#password').attr('type', 'password');
                                        $('#password-lock').attr('class', 'fas fa-lock');
                                    }
                                });

                                $('.show-confirm-password').on('click', function() {
                                    if ($('#confirm-password').attr('type') === 'password') {
                                        $('#confirm-password').attr('type', 'text');
                                        $('#confirm-password-lock').attr('class', 'fas fa-unlock');
                                    } else {
                                        $('#confirm-password').attr('type', 'password');
                                        $('#confirm-password-lock').attr('class', 'fas fa-lock');
                                    }
                                });

                            });
                        </script>

</body>

</html>
