<!DOCTYPE html>
    <html class="loading" lang="en" data-textdirection="ltr">
    <!-- BEGIN: Head-->
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
        <link rel="stylesheet" type="text/css" href="{{ asset('themeforest/app-assets/vendors/css/charts/apexcharts.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('themeforest/app-assets/vendors/css/extensions/tether-theme-arrows.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('themeforest/app-assets/vendors/css/extensions/tether.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('themeforest/app-assets/vendors/css/extensions/shepherd-theme-default.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('themeforest/app-assets/vendors/css/tables/datatable/datatables.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('themeforest/app-assets/vendors/css/forms/select/select2.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('themeforest/app-assets/vendors/css/extensions/sweetalert2.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('themeforest/app-assets/vendors/css/charts/apexcharts.css') }}">
        <!-- END: Vendor CSS-->

        <!-- BEGIN: Theme CSS-->
        <link rel="stylesheet" type="text/css" href="{{ asset('themeforest/app-assets/css/bootstrap.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('themeforest/app-assets/css/bootstrap-extended.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('themeforest/app-assets/css/colors.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('themeforest/app-assets/css/components.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('themeforest/app-assets/css/themes/dark-layout.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('themeforest/app-assets/css/themes/semi-dark-layout.css') }}">

        <!-- BEGIN: Page CSS-->
        <link rel="stylesheet" type="text/css" href="{{ asset('themeforest/app-assets/css/core/menu/menu-types/vertical-menu.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('themeforest/app-assets/css/core/colors/palette-gradient.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('themeforest/app-assets/css/pages/dashboard-analytics.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('themeforest/app-assets/css/pages/card-analytics.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('themeforest/app-assets/css/plugins/tour/tour.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('themeforest/app-assets/css/plugins/forms/wizard.css') }}">
        <!-- END: Page CSS-->

        <!-- BEGIN: Custom CSS-->
        <link rel="stylesheet" type="text/css" href="{{ asset('themeforest/assets/css/style.css') }}">
        <!-- END: Custom CSS-->
        
        
        @stack('styles')
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    </head>
    <!-- END: Head-->

    <!-- BEGIN: Body-->

    <body class="vertical-layout vertical-menu-modern 2-columns navbar-floating footer-static" data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">
        <!-- BEGIN: Header-->
        @include('layouts.includes.header')
        <!-- END: Header-->

        <!-- BEGIN: Main Menu-->
        @include('layouts.includes.sidebarAdmin')
        <!-- END: Main Menu-->

        <!-- BEGIN: Content-->
        <div class="app-content content">
            <div class="content-overlay"></div>
            <div class="header-navbar-shadow"></div>
            <div class="content-wrapper">
                <div class="content-header row"></div>
                <div class="content-body">
                    @yield('content')  
                </div>
            </div>
        </div>
        <!-- END: Content-->

        <div class="sidenav-overlay"></div>
        <div class="drag-target"></div>

        <div class="contenedor">
            @include('layouts.includes.users_support')
            {{-- <button class="botonF1">
                <a href="https://wa.me/573508663301" target="_blank" style="color: white;"><span><i class="fab fa-whatsapp"></i></span></a>
            </button> --}}
        </div>


         <!-- BEGIN: Footer-->
         <footer class="footer footer-static footer-light">
            <p class="clearfix blue-grey lighten-2 mb-0"><span class="float-md-left d-block d-md-inline-block mt-25">COPYRIGHT &copy; 2020<a class="text-bold-800 grey darken-2" href="https://www.breve.com.co" target="_blank">BREVE,</a>All rights Reserved</span>
            </p>
        </footer>
        <!-- END: Footer-->

        <script src="https://kit.fontawesome.com/d6f2727f64.js" crossorigin="anonymous"></script>
        <!-- BEGIN: Vendor JS-->
        <script src="{{ asset('themeforest/app-assets/vendors/js/vendors.min.js') }}"></script>
        <!-- BEGIN Vendor JS-->

        <!-- BEGIN: Page Vendor JS-->
        <script src="{{ asset('themeforest/app-assets/js/scripts/moment.min.js') }}"></script>
        <script src="{{ asset('themeforest/app-assets/vendors/js/charts/apexcharts.min.js') }}"></script>
        <script src="{{ asset('themeforest/app-assets/vendors/js/extensions/tether.min.js') }}"></script>
        <script src="{{ asset('themeforest/app-assets/vendors/js/extensions/shepherd.min.js') }}"></script>
        <script src="{{ asset('themeforest/app-assets/js/scripts/datatables/datatable.js') }}"></script>
        <script src="{{ asset('themeforest/app-assets/vendors/js/tables/datatable/datatables.min.js') }}"></script>
        <script src="{{ asset('themeforest/app-assets/vendors/js/tables/datatable/datatables.buttons.min.js') }}"></script>
        <script src="{{ asset('themeforest/app-assets/vendors/js/tables/datatable/jszip.min.js') }}"></script>
        <script src="{{ asset('themeforest/app-assets/vendors/js/tables/datatable/pdfmake.min.js') }}"></script>
        <script src="{{ asset('themeforest/app-assets/vendors/js/tables/datatable/vfs_fonts.js') }}"></script>
        <script src="{{ asset('themeforest/app-assets/vendors/js/tables/datatable/buttons.html5.min.js') }}"></script>
        <script src="{{ asset('themeforest/app-assets/vendors/js/tables/datatable/buttons.print.min.js') }}"></script>
        <script src="{{ asset('themeforest/app-assets/vendors/js/tables/datatable/buttons.bootstrap.min.js') }}"></script>
        <script src="{{ asset('themeforest/app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('themeforest/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
        <script src="{{ asset('themeforest/app-assets/js/scripts/forms/select/form-select2.js') }}"></script>
        <script src="{{ asset('themeforest/app-assets/vendors/js/extensions/jquery.steps.min.js') }}"></script>
        <script src="{{ asset('themeforest/app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
        <script src="{{ asset('themeforest/app-assets/js/scripts/datetime-moment.js') }}"></script>
        <script src="{{ asset('themeforest/app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
        <script src="{{ asset('themeforest/app-assets/vendors/js/charts/apexcharts.min.js') }}"></script>
        <!-- END: Page Vendor JS-->

        <!-- BEGIN: Theme JS-->
        <script src="{{ asset('themeforest/app-assets/js/core/app-menu.js') }}"></script>
        <script src="{{ asset('themeforest/app-assets/js/core/app.js') }}"></script>
        <script src="{{ asset('themeforest/app-assets/js/scripts/components.js') }}"></script>
        <script src="{{ asset('themeforest/app-assets/js/scripts/forms/wizard-steps.js') }}"></script>
        <!-- END: Theme JS-->

        <!-- BEGIN: Page JS-->
        <script src="{{ asset('themeforest/app-assets/js/scripts/pages/dashboard-analytics.js') }}"></script>
        <script src="{{ asset('themeforest/app-assets/js/scripts/cards/card-analytics.js') }}"></script>
        <!-- END: Page JS-->

        @stack('scripts')
    </body>
    <!-- END: Body-->
</html>