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
                    <b>Verification</b>
                </div>

                <div class="card my-5">
                    <div class="card-body">

                        @if (session('failed'))
                            <div class="alert alert-danger">{{ session('failed') }}</div>
                        @endif

                        <p class="login-box-msg">Please verify your account</p>
                        <a href="/send-otp" class="btn btn-sm btn-primary">Send OTP to your Email</a>
                    </div>

                </div>
            </div>

        </div>
    </div>
    </div>


</body>

</html>
