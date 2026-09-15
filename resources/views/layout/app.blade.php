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
    <title>Smart Gas Station by Nexgen Pakistan</title>

    <!--animation-css-->
    <link href="{{ asset('assets/vendor/animation/animate.min.css') }}" rel="stylesheet">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
    <link href="{{ asset('assets/css2?family=Lexend+Deca:wght@100..900&display=swap') }}" rel="stylesheet">

    <!--flag Icon css-->
    <link href="{{ asset('assets/vendor/flag-icons-master/flag-icon.css') }}" rel="stylesheet" type="text/css">

    <!-- tabler icons-->
    <link href="{{ asset('assets/vendor/tabler-icons/tabler-icons.css') }}" rel="stylesheet" type="text/css">

    <!-- Bootstrap css-->
    <link href="{{ asset('assets/vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet" type="text/css">

    <!-- Simplebar js-->
    <link href="{{ asset('assets/vendor/simplebar/simplebar.css') }}" rel="stylesheet" type="text/css">

    <!-- apexcharts css-->
    <link href="{{ asset('assets/vendor/apexcharts/apexcharts.css') }}" rel="stylesheet" type="text/css">

    <!-- glight css -->
    <link href="{{ asset('assets/vendor/glightbox/glightbox.min.css') }}" rel="stylesheet">

    <!-- filepond css -->
    <link href="{{ asset('assets/vendor/filepond/filepond.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/filepond/image-preview.min.css') }}" rel="stylesheet">

    <!-- slick css -->
    <link href="{{ asset('assets/vendor/slick/slick.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/slick/slick-theme.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/toastify/toastify.css') }}" rel="stylesheet" type="text/css">


    <!-- App css-->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css">

    <!-- Responsive css-->
    <link href="{{ asset('assets/css/responsive.css') }}" rel="stylesheet" type="text/css">
    @yield('page-css')

</head>

<body>
    <div class="app-wrapper">

        <div class="loader-wrapper">
            <div class="loader-box">
                <div class="loader-6">
                    <div></div>
                    <div></div>
                </div>
            </div>
        </div>

        <!-- Menu Navigation starts -->
        @include('layout.sidebare')
        <!-- Menu Navigation ends -->

        <div class="app-content">
            <!-- Header Section starts -->
            <header class="header-main">
                <div class="container-fluid">
                    <div class="row align-items-center">
                        <div class="col-6 head-left">
                            <div class="d-flex align-items-center gap-3">
                                <span class="cursor-pointer main-side-toggle">
                                    <i class="ti ti-align-justified f-s-22 text-secondary"></i>
                                </span>
                                <h4 class="txt-ellipsis-2">Smart Gas Station</h4>
                            </div>
                        </div>
                        <div class="col-6 head-right">
                            <ul class="d-flex gap-2 align-items-center justify-content-end">
                                <li class="head-maximize-screen">
                                    <span class="h-40 w-40 d-flex-center b-r-50 head-icon">
                                        <i class="ti ti-arrows-maximize"></i>
                                    </span>
                                </li>
                                <li class="header-dark">
                                    <div class="sun-logo h-40 w-40 d-flex-center b-r-50 head-icon">
                                        <i id="theme-icon" class="ti ti-moon-stars"></i>
                                    </div>
                                </li>
                                <li class="header-dark">
                                    <a href="{{ route('logout') }}"
                                        class="sun-logo h-40 w-40 d-flex-center b-r-50 head-icon">
                                        <i id="theme-icon" class="ti ti-logout"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </header>
            <!-- Header Section ends -->
            <!-- Body main section starts -->
            <main class="pt-0">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </main>
        </div>
        <!-- Body main section ends -->

        <!-- tap on top -->
        <div class="go-top">
            <span class="progress-value">
                <i class="ti ti-chevron-up"></i>
            </span>
        </div>

        <!-- Footer Section starts-->
        <footer class="footer-container">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-9 col-12">
                        <p class="footer-text f-w-600 mb-0">Copyright © {{ date('Y') }} Nexgen Pakistan. All rights
                            reserved 💖</p>
                    </div>
                    <div class="col-md-3">
                        <div class="footer-text text-end">
                            <a class="f-w-500 text-primary" href="mailto:teqlathemes@gmail.com"> Need Help <i
                                    class="ti ti-help"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- Footer Section ends-->
        <div id="theme-customizer-box"></div>
    </div>


    <!-- latest jquery-->
    <script src="{{ asset('assets/js/jquery-3.6.3.min.js') }}"></script>

    <!-- Bootstrap js-->
    <script src="{{ asset('assets/vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>

    <!-- Simplebar js-->
    <script src="{{ asset('assets/vendor/simplebar/simplebar.js') }}"></script>

    <!-- Theme customizer  js-->
    <script src="{{ asset('assets/js/theme_customizer.js') }}"></script>

    <!-- apexcharts-->
    <script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>

    <!-- filepond -->
    <script src="{{ asset('assets/vendor/filepond/file-encode.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/filepond/validate-size.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/filepond/validate-type.js') }}"></script>
    <script src="{{ asset('assets/vendor/filepond/exif-orientation.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/filepond/image-preview.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/filepond/filepond.min.js') }}"></script>

    <!-- Glight js -->
    <script src="{{ asset('assets/vendor/glightbox/glightbox.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/notifications/toastify-js.js') }}"></script>
    <script src="{{ asset('assets/vendor/toastify/toastify.js') }}"></script>

    <!-- slick-file -->
    <script src="{{ asset('assets/vendor/slick/slick.min.js') }}"></script>

    <!-- js-->
    <script src="{{ asset('assets/js/ecommerce_dashboard.js') }}"></script>

    <!-- App js-->
    <script src="{{ asset('assets/js/script.js') }}"></script>
    <script>
        @if (session('success'))
            Toastify({
                text: "{{ session('success') }}",
                className: "info",
                style: {
                    background: "linear-gradient(to right, #00b09b, #2cc76f)",
                }
            }).showToast();
        @endif

        @if (session('error'))
            Toastify({
                text: "{{ session('error') }}",
                className: "info",
                style: {
                    background: "linear-gradient(to right, #00b09b, #2cc76f)",
                }
            }).showToast();
        @endif
    </script>
    @yield('page-js')

</body>

</html>
