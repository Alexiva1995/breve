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
            #mapa {
                width: 100%;
                height: 400px;
            }
            .checkeable input {
              display: none;
            }
            .checkeable img {
              width: 160px;
              border: 1px solid gray;
            }
            .checkeable input {
              display: none;
            }
            .checkeable input:checked  + img {
                border: 3px solid green;
            }
            .label-small{
                font-size: 11px;
                color: gray;
            }
        </style>

    </head>
    <!-- END: Head-->
    
    <!-- BEGIN: Body-->
    <body class="vertical-layout vertical-menu-modern 1-column  navbar-floating footer-static bg-full-screen-image  blank-page blank-page" data-open="click"  data-menu="vertical-menu-modern" data-col="1-column">
        <div>
            <img style="position: absolute;z-index: 1;" src="https://www.breve.com.co/images/logo320.png">
        </div>
        <!-- BEGIN: Content-->
        <div class="app-content content">
            <div class="content-overlay"></div>
            <div class="header-navbar-shadow"></div>
            <div class="content-wrapper">
                <div class="content-header row"></div>
                <div class="content-body" style="background: url(https://www.breve.com.co/images/fondo_completo.jpg) no-repeat center center;background-size: cover;">
                   
                    <section class="row container" style="margin: 0 auto; text-align: center;">
                        <div class="col-xl-12 col-12 justify-content-center" style="margin-top: 10%; margin-bottom: 5%; z-index: 1;">
                            <div class="card bg-authentication rounded-0 mb-0">
                                <div class="row m-0">
                                    <div class="col-lg-12 col-12 p-0">
                                        <div class="card rounded-0 mb-0 p-2">
                                            @if (Session::has('msj-exitoso'))
                                                <div class="col-md-12">
                                                    <div class="alert alert-success">
                                                        <strong>{{ Session::get('msj-exitoso') }}</strong>
                                                    </div>
                                                </div>
                                            @endif
                                            <center>
                                                <h4 class="mb-1">Solicitud de Domicilio</h4>
                                            </center>
                                            <div class="card-content">
                                                <div class="card-body pt-0">
                                                    <section id="validation">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="card">
                                                                    <div class="card-content">
                                                                        <div class="card-body">
                                                                            <form action="{{ route('guest.new-service') }}" method="POST" id="main-form">
                                                                                @csrf                      
                                                                                <fieldset>
                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <div class="form-group">
                                                                                                <label for="client_name"> Tu Nombre (o el de tu Negocio/Emprendimiento) (*)</label>
                                                                                                <input type="text" class="form-control" id="client_name" name="client_name" required>
                                                                                                <label class="label-small">Con este nombre registraremos el servicio</label>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            <div class="form-group">
                                                                                                <label for="date">Fecha</label>
                                                                                                <input type="date" class="form-control" id="date" name="date" min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" required>
                                                                                                <label class="label-small">¿Que día debemos estar en el punto inicial?</label>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            <div class="form-group">
                                                                                                <label for="time">Hora <i class="fas fa-info-circle" data-toggle="tooltip" title="2 Horas de Anticipación"></i></label>
                                                                                                <input type="time" class="form-control" id="time" name="time" min="07:00" max="19:00" value="{{ date('H:i') }}" required>
                                                                                                <label class="label-small">¿A qué hora debemos estar en el punto inicial?</label>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-12">
                                                                                            <div class="form-group">
                                                                                                <label for="sender_address">Dirección inicial: (*)</label>
                                                                                                <input type="text" class="form-control" id="sender_address" name="sender_address" required>
                                                                                                <label class="label-small">Dirección Completa, nombre del barrio (no olvides unidad, bloque, apto u oficina, si aplica)</label>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-12">
                                                                                            <div class="form-group">
                                                                                                <label for="sender"> Nombre & número de 📲 de quien entrega: (*)</label>
                                                                                                <input type="text" class="form-control" id="sender" name="sender" required>
                                                                                                <label class="label-small">Nombre, apellido & número de contacto de quien entrega</label>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-12">
                                                                                            <div class="form-group">
                                                                                                <label for="article">Articulo a transportar: (*) <i class="fas fa-info-circle" data-toggle="tooltip" title="CANASTA: 40cm de ancho. 60cm de largo. 20cm de profundidad. (Máximo 18kg de peso). MALETA BREVE: 42cm de ancho. 38cm de largo. 50cm de profundidad. (Máximo 15kg de peso). MALETÍN CONVENCIONAL: 27cm de ancho. 14cm de largo. 40cm de profundidad. (Máximo 15kg de peso)."></i></label>
                                                                                                <input type="text" class="form-control" id="article" name="article" required>
                                                                                                <label class="label-small">¿Qué vas a enviar?</label>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-12">
                                                                                            <label for="equipment_type">Equipo Breve</label><br>
                                                                                            <label class="label-small">Para transportar tu artículo (Puedes elegir dos o más💚)</label>
                                                                                            <ul class="list-unstyled mb-0">
                                                                                                <li class="d-inline-block mr-2">
                                                                                                    <fieldset>
                                                                                                        <div class="vs-checkbox-con vs-checkbox-primary">
                                                                                                            <label class="checkeable">
                                                                                                                <input type="checkbox" name="equipment_type[]" value="Maletin"/>
                                                                                                                <img src="{{ asset('images/maletin.png') }}"/>
                                                                                                            </label>
                                                                                                        </div>
                                                                                                    </fieldset>
                                                                                                </li>
                                                                                                <li class="d-inline-block mr-2">
                                                                                                    <fieldset>
                                                                                                        <div class="vs-checkbox-con vs-checkbox-primary">
                                                                                                            <label class="checkeable">
                                                                                                                <input type="checkbox" name="equipment_type[]" value="MB"/>
                                                                                                                <img src="{{ asset('images/maleta.png') }}"/>
                                                                                                            </label>
                                                                                                        </div>
                                                                                                    </fieldset>
                                                                                                </li>
                                                                                                 <li class="d-inline-block mr-2">
                                                                                                    <fieldset>
                                                                                                        <div class="vs-checkbox-con vs-checkbox-primary">
                                                                                                            <label class="checkeable">
                                                                                                                <input type="checkbox" name="equipment_type[]" value="Canasta"/>
                                                                                                                <img src="{{ asset('images/canasta.png') }}"/>
                                                                                                            </label>
                                                                                                        </div>
                                                                                                    </fieldset>
                                                                                                </li>
                                                                                            </ul>
                                                                                        </div>
                                                                                         <div class="col-md-12">
                                                                                            <div class="form-group">
                                                                                                <label for="receiver_address">Dirección final: (*)</label>
                                                                                                <input type="text" class="form-control" id="receiver_address" name="receiver_address" required>
                                                                                                <label class="label-small">Dirección Completa, nombre del barrio (no olvides unidad, bloque, apto u oficina, si aplica)</label>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-12">
                                                                                            <div class="form-group">
                                                                                                <label for="receiver"> Nombre & número de 📲 de quien recibe: (*)</label>
                                                                                                <input type="text" class="form-control" id="receiver" name="receiver" required>
                                                                                                <label class="label-small">Nombre, apellido & número de contacto de quien recibe:</label>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            <div class="form-group">
                                                                                                <label for="payment_method">La tarifa se paga en</label>
                                                                                                <select class="custom-select form-control" id="payment_method" name="payment_method" onchange="checkPaymentMethod();" required>
                                                                                                    <option value="transferencia">Transferencia</option>
                                                                                                    <option value="efectivo-inicio">Efectivo al Inicio</option>
                                                                                                    <option value="efectivo-final">Efectivo al Final</option>
                                                                                                    <option value="reembolso">Contra Entrega</option>
                                                                                                </select>
                                                                                                <label class="label-small">Elige donde y cómo cancelarías la tarifa del servicio</label> 
                                                                                            </div>
                                                                                        </div>
                                                                                         <div class="col-md-3" id="refund_div" style="display: none;">
                                                                                            <div class="form-group">
                                                                                                <label for="refund_amount">Monto</label>
                                                                                                <input type="number" class="form-control" name="refund_amount" id="refund_amount" onkeyup="updateAmount();">
                                                                                            </div>
                                                                                        </div>
                                                                                         <div class="col-md-3" id="refund_div2" style="display: none;">
                                                                                            <div class="form-group">
                                                                                                <label for="refund_amount">Cobrar al Final</label>
                                                                                                <input type="number" class="form-control" id="refund_amount2" disabled>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-12">
                                                                                            <div class="form-group">
                                                                                                <label for="observations">Nota</label>
                                                                                                <textarea name="observations" id="observations" rows="4" class="form-control"></textarea>
                                                                                                <label class="label-small">Nota especial para el Brever que realizará el servicio</label>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-12 text-center">
                                                                                            <button type="submit" class="btn btn-primary" id="btn-submit">Crear Servicio</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </fieldset>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </section>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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

        <script src="{{ asset('themeforest/app-assets/vendors/js/extensions/jquery.steps.min.js') }}"></script>
        <script src="{{ asset('themeforest/app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>        <!-- END: Theme JS-->

        <!-- BEGIN: Page JS-->
        <!-- END: Page JS-->
       <script>
            var checkSsubmit = 0;
            
            function checkPaymentMethod() {
                if (document.getElementById("payment_method").value == 'reembolso') {
                    document.getElementById('refund_div').style.display = 'block';
                    document.getElementById('refund_div2').style.display = 'block';
                }else if (document.getElementById("payment_method").value == 'transferencia'){
                    document.getElementById('refund_div').style.display = 'none';
                    document.getElementById('refund_div2').style.display = 'none';
                }else if (document.getElementById("payment_method").value == 'efectivo-inicio'){
                    document.getElementById('refund_div').style.display = 'none';
                    document.getElementById('refund_div2').style.display = 'none';
                }else if (document.getElementById("payment_method").value == 'efectivo-final'){
                    document.getElementById('refund_div').style.display = 'none';
                    document.getElementById('refund_div2').style.display = 'none';
                }
            }

            function updateAmount(){
                document.getElementById("refund_amount2").value = parseInt(document.getElementById("refund_amount").value) + parseInt(document.getElementById("rate").value);
            }
            
             $("#main-form").submit(function(){
                $("#btn-submit").prop('disabled', true);
                var contador = 0;
                $("input[type=checkbox]").each(function(){
                    if($(this).is(":checked")){
                        contador++;
                    }
                });
                
                if (contador > 0){
                    return true;
                }else{
                    $("#btn-submit").prop('disabled', false);
                    alert("Debe seleccionar un tipo de equipamiento");
                    return false;
                }
               
            });

        </script>
    </body>
    <!-- END: Body-->
</html>