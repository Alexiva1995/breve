<!DOCTYPE html>
    <html class="loading" lang="en" data-textdirection="ltr">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <meta name="description" content="BREVE Soluciones Urbanas. Servicios integrales a domicilio que optimizan tu tiempo libre.">
        <meta name="keywords" content="BREVE Soluciones Urbanas. Servicios integrales a domicilio que optimizan tu tiempo libre.">
        <meta name="author" content="BREVE">
        <title>Breve</title>
        {{--<link rel="apple-touch-icon" href="{{ asset('themeforest/app-assets/images/ico/apple-icon-120.png') }}">
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('themeforest/app-assets/images/ico/favicon.ico') }}">--}}
        <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600" rel="stylesheet">

        <!-- BEGIN: Vendor CSS-->
        <link rel="stylesheet" type="text/css" href="{{ asset('themeforest/app-assets/vendors/css/vendors.min.css') }}">
        <!-- END: Vendor CSS-->

        <!-- BEGIN: Theme CSS-->
        <link rel="stylesheet" type="text/css" href="{{ asset('themeforest/app-assets/css/bootstrap.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('themeforest/app-assets/css/bootstrap-extended.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('themeforest/app-assets/css/colors.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('themeforest/app-assets/css/components.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('themeforest/app-assets/css/themes/dark-layout.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('themeforest/app-assets/css/themes/semi-dark-layout.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('themeforest/app-assets/vendors/css/extensions/sweetalert2.min.css') }}">

        <!-- BEGIN: Page CSS-->
        <link rel="stylesheet" type="text/css" href="{{ asset('themeforest/app-assets/css/core/menu/menu-types/vertical-menu.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('themeforest/app-assets/css/core/colors/palette-gradient.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('themeforest/app-assets/css/pages/authentication.css') }}">
        <!-- END: Page CSS-->

        <!-- BEGIN: Custom CSS-->
        <link rel="stylesheet" type="text/css" href="{{ asset('themeforest/assets/css/style.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/responsive.css') }}">
        <!-- END: Custom CSS-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

        <style>
            .contenedor{
                width:90px;
                height:240px;
                position:absolute;
                right:0px;
                bottom:0px;
            }
            .botonF1{
                width:50px;
                height:50px;
                border-radius:100%;
                background:#4caf50;
                right:0;
                bottom:0;
                position:fixed;
                margin-right:16px;
                margin-bottom:16px;
                border:none;
                outline:none;
                color:#FFF;
                font-size:28px;
                box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
                transition:.3s;  
            }
            span{
                transition:.5s;  
            }
        </style>

    </head>
    <!-- END: Head-->
    
    <!-- BEGIN: Body-->
    <body class="vertical-layout vertical-menu-modern 1-column  navbar-floating footer-static bg-full-screen-image  blank-page blank-page" data-open="click" data-menu="vertical-menu-modern" data-col="1-column">
        <div>
            <img style="position: absolute;" src="https://www.breve.com.co/images/logo320.png">
        </div>
        <!-- BEGIN: Content-->
        <div class="app-content content">
            <div class="content-overlay"></div>
            <div class="header-navbar-shadow"></div>
            <div class="content-wrapper">
                <div class="content-header row"></div>
                <div class="content-body">
                    <section class="row flexbox-container">
                        <div class="col-xl-8 col-11 d-flex justify-content-center">
                            <div class="card bg-authentication rounded-0 mb-0">
                                @yield('content')
                            </div>
                        </div>
                    </section>

                </div>
            </div>

            <div class="contenedor">
                <button class="botonF1">
                    <a href="https://wa.me/573508663301" target="_blank" style="color: white;"><span><i class="fab fa-whatsapp"></i></span></a>
                </button>
            </div>
        </div>
        <!-- END: Content-->
        
        <script src="https://kit.fontawesome.com/d6f2727f64.js" crossorigin="anonymous"></script>
        <!-- BEGIN: Vendor JS-->
        <script src="{{ asset('themeforest/app-assets/vendors/js/vendors.min.js') }}"></script>
        <!-- BEGIN Vendor JS-->

        <!-- BEGIN: Page Vendor JS-->
        <!-- END: Page Vendor JS-->

        <!-- BEGIN: Theme JS-->
        <script src="{{ asset('themeforest/app-assets/js/core/app-menu.js') }}"></script>
        <script src="{{ asset('themeforest/app-assets/js/core/app.js') }}"></script>
        <script src="{{ asset('themeforest/app-assets/js/scripts/components.js') }}"></script>
        <script src="{{ asset('themeforest/app-assets/js/scripts/extensions/sweet-alerts.js') }}"></script>
        <script src="{{ asset('themeforest/app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
        <!-- END: Theme JS-->

        <!-- BEGIN: Page JS-->
        <!-- END: Page JS-->

    </body>
    <!-- END: Body-->
</html>