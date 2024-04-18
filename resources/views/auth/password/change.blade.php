<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Responsive Admin Dashboard Template">
    <meta name="keywords" content="admin,dashboard">
    <meta name="author" content="stacks">
    <!-- The above 6 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <!-- Title -->
    <title>Relico - Login</title>

    <!-- Styles -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp"
        rel="stylesheet">
    <link href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/perfectscroll/perfect-scrollbar.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/pace/pace.css') }}" rel="stylesheet">


    <!-- Theme Styles -->
    <link href="{{ asset('assets/css/main.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/avatars/avatarlogo.png') }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/avatars/avatarlogo.png') }}" />

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
    <div class="container">
        <div class="row justify-content-center align-items-center" style="height: 100vh;">
            {{-- <div class="card-header">{{ __('Change Password') }}</div> --}}
            <div class="app-auth-container">
                <div style="text-align: center" class="mt-5 pt-5">
                    <img src="assets/images/avatars/avatarlogo.png" class="" height="80" width="80"
                        alt="">
                    <h3 class="mt-3">Regional Lima Cuti Online</h3>
                </div>
                <form action="{{ route('password.update') }}" method="post">
                    @csrf
                    <div class="auth-credentials m-b-xxl">
                        <label for="current_password" class="form-label">{{ __('Password Lama') }}</label>
                        <input type="password" class="form-control m-b-md @error('current_password') is-invalid @enderror" id="current_password"
                         placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;" name="current_password" required autofocus>
                         @error('current_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror

                        <label for="password" class="form-label">{{ __('Password Baru') }}</label>
                        <input type="password" class="form-control m-b-md @error('password') is-invalid @enderror" id="password"
                            aria-describedby="changePassword"
                            placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;" name="password"
                            required>
                        @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                        @enderror

                        <label for="password_confirmation" class="form-label">{{ __('Konfirmasi Password') }}</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password_confirmation"
                            aria-describedby="changePassword"
                            placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;" name="password_confirmation"
                            required>
                    </div>

                    <div class="auth-submit">
                        <button type="submit" class="btn btn-primary">{{ __('Ubah Password') }}</button>
                    </div>
                </form>
                <div class="divider"></div>
                <p class="text-muted text-center mb-5">Versi 1.0</p>
                <p class="mt-5  text-center">@Copyright Bagian SDM & Sistem Manajemen 2024</p>
            </div>
        </div>
    </div>

    <!-- Javascripts -->
    <script src="{{ asset('assets/plugins/jquery/jquery-3.5.1.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/perfectscroll/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/pace/pace.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
</body>

</html>
