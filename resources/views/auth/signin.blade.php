<!DOCTYPE html>
<html lang="en">

<head>
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Multipurpose, super flexible, powerful, clean modern responsive bootstrap 5 admin template"
        name="description">
    <meta
        content="admin template, alina admin template, dashboard template, flat admin template, responsive admin template, web app"
        name="keywords">
    <meta content="la-themes" name="author">
    <link href="{{ asset('assets/images/logo/favicon.png') }}" rel="icon" type="image/x-icon">
    <link href="{{ asset('assets/images/logo/favicon.png') }}" rel="shortcut icon" type="image/x-icon">

    <title>Login | Smart Gas Station by Nexgen Pakistan</title>

    <!--font-awesome-css-->
    <link href="{{ asset('assets/vendor/fontawesome/css/all.css') }}" rel="stylesheet">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
    <link href="{{ asset('assets/css2?family=Lexend+Deca:wght@100..900&display=swap') }}" rel="stylesheet">

    <!-- tabler icons-->
    <link href="{{ asset('assets/vendor/tabler-icons/tabler-icons.css') }}" rel="stylesheet" type="text/css">

    <!-- Bootstrap css-->
    <link href="{{ asset('assets/vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet" type="text/css">

    <!-- App css-->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css">

    <!-- Responsive css-->
    <link href="{{ asset('assets/css/responsive.css') }}" rel="stylesheet" type="text/css">

</head>

<body>
    <div class="auth-container">

        <div class="card form-container">

            <div class="card-body">

                <div class="text-center pt-3">
                    <span class="h-100 w-80 overflow-hidden d-flex-center b-r-10 mx-auto p-2 auth-logo">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="User" class="img-fluid">
                    </span>
                    <h3 class="f-w-600 mb-0 pt-3">
                        Sign <span class="text-primary">In</span>
                    </h3>
                    <p class="text-dark-800 f-w-500">
                        Sign in to continue to your account
                    </p>
                </div>
                <form class="app-form" action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="my-4">
                        @if ($errors->any())
                            <div class="alert alert-danger mb-3">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label" for="loginEmail">User Name</label>
                            <input class="form-control" type="text" id="loginEmail"
                                placeholder="Enter your user name" required="" name="username">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="loginPassword">Password</label>
                            <input class="form-control" type="password" name="password" id="loginPassword"
                                placeholder="Enter your password" required="">
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn bg-gradient-primary w-100 btn-lg b-r-20">
                                Sign In
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- latest jquery-->
    <script src="{{ asset('assets/js/jquery-3.6.3.min.js') }}"></script>

    <!-- Bootstrap js-->
    <script src="{{ asset('assets/vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>

</body>

</html>
