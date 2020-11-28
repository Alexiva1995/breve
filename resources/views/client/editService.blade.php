@extends('layouts.client')

@push('styles')
	<style>
    	#mapa {
		    width: 100%;
		    height: 500px;
		}
    </style>
@endpush

@push('scripts')
	<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDX1cuvw-Xs_HC-1WjVtxv-18bLvKRu3Rw&callback=initMap&libraries=geometry,places" type="text/javascript"></script>

	<script>
		var map;
        var coords = {};
        var marker;
        var marker2;
        var geocoder;
        var directionsDisplay;
        var directionsService;
        var geocoder;
        var autocomplete;
        var autocomplete2;

        //Funcion principal Mapa Inicial
        initMap = function () {
            //usamos la API para geolocalizar el usuario
            navigator.geolocation.getCurrentPosition(
                function (position){
                    mapaInicial();
                },function(error){
                    console.log(error);
                }
            );
        }

        function mapaInicial(){
            geocoder = new google.maps.Geocoder();
            if ( (document.getElementById("sender_address").value != "") && (document.getElementById("receiver_address").value != "") ){
                var request = {
                    origin: document.getElementById("sender_address").value,
                    destination: document.getElementById("receiver_address").value,
                    travelMode: google.maps.DirectionsTravelMode['DRIVING'],
                    unitSystem: google.maps.DirectionsUnitSystem['METRIC'],
                    provideRouteAlternatives: false
                };

                map = new google.maps.Map($('#mapa').get(0));
                directionsDisplay = new google.maps.DirectionsRenderer();
                directionsService = new google.maps.DirectionsService();

                directionsService.route(request, function (response, status) {
                    if (status == google.maps.DirectionsStatus.OK) {
                        directionsDisplay.setMap(map);
                        directionsDisplay.setDirections(response);
                        var rate = getRate(response.routes[0].legs[0].distance.value/1000);
                        document.getElementById("distance").innerHTML = response.routes[0].legs[0].distance.text;
                        document.getElementById("rate").value = rate;
                        document.getElementById("rate_span").innerHTML = "Tarifa: $"+rate;
                        document.getElementById("submit-button").disabled = false;
                    }else {
                        document.getElementById("sender_address").value = "";
                        document.getElementById("receiver_address").value = "";
                        document.getElementById("distance").innerHTML = "0.0 Kms";
                        document.getElementById("rate").value = 0;
                        document.getElementById("rate_span").innerHTML = "Tarifa: $0";
                        Swal.fire({
                            title: "¡Ups!",
                            text: "Una de las direcciones ingresadas no se ha encontrado en el mapa.",
                            type: "error",
                            confirmButtonClass: 'btn btn-primary',
                            buttonsStyling: false,
                        });
                       document.getElementById("submit-button").disabled = true;
                    }
                });
            }else{
                document.getElementById("distance").innerHTML = "0.0 Kms";
                document.getElementById("rate").value = 0;
                document.getElementById("rate_span").innerHTML = "Tarifa: $0";
                Swal.fire({
                    title: "¡Ups!",
                    text: "Por favor, rellene los campos de direcciones.",
                    type: "error",
                    confirmButtonClass: 'btn btn-primary',
                    buttonsStyling: false,
                });
                document.getElementById("submit-button").disabled = true;
            }
        }

        function setMap(){
            document.getElementById("sender_search").disabled = false;
            document.getElementById("receiver_search").disabled = false;
            document.getElementById("sender_address_aux").disabled = false;
            document.getElementById("receiver_address_aux").disabled = false;
            document.getElementById("restore_map").style.display = 'none';
            document.getElementById("ready").style.display = 'block';
            document.getElementById("distance").innerHTML = "0.0 Kilómetros";
            document.getElementById("rate_span").innerHTML = "Tarifa: $0.0";
            document.getElementById("submit-button").disabled = true;

            map = new google.maps.Map(document.getElementById('mapa'),
            {
                zoom: 13,
                center:new google.maps.LatLng(document.getElementById("sender_latitude").value,document.getElementById("sender_longitude").value),
            });

            marker = new google.maps.Marker({
                map: map,
                draggable: true,
                animation: google.maps.Animation.DROP,
                position: new google.maps.LatLng(document.getElementById("sender_latitude").value,document.getElementById("sender_longitude").value),
                icon: 'https://www.breve.com.co/images/green-dot.png',
            });
            marker.addListener('click', toggleBounce);
            marker.addListener( 'dragend', function (event){
                document.getElementById("sender_latitude").value = this.getPosition().lat();
                document.getElementById("sender_longitude").value = this.getPosition().lng();

                document.getElementById("check_sender").value = "0";
                geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        var address=results[0]['formatted_address'];
                        for (var i = 0; i < results[0].address_components.length; i++ ){
                            if (results[0].address_components[i].long_name == 'Cali'){
                                document.getElementById("check_sender").value = "1";
                            }
                        }
                        document.getElementById("sender_address").value = address;
                        document.getElementById("sender_address_aux").value = address;
                    }
                });
            });

            marker2 = new google.maps.Marker({
                map: map,
                draggable: true,
                animation: google.maps.Animation.DROP,
                position: new google.maps.LatLng(document.getElementById("receiver_latitude").value,document.getElementById("receiver_longitude").value),
                icon: 'https://www.breve.com.co/images/blue-dot.png',
            });
            marker2.addListener('click', toggleBounce2);
            marker2.addListener( 'dragend', function (event){
                document.getElementById("receiver_latitude").value = this.getPosition().lat();
                document.getElementById("receiver_longitude").value = this.getPosition().lng();

                document.getElementById("check_receiver").value = "0";
                geocoder.geocode({'latLng': marker2.getPosition()}, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        var address=results[0]['formatted_address'];
                        for (var i = 0; i < results[0].address_components.length; i++ ){
                            if (results[0].address_components[i].long_name == 'Cali'){
                                document.getElementById("check_receiver").value = "1";
                            }
                        }
                        document.getElementById("receiver_address").value = address;
                        document.getElementById("receiver_address_aux").value = address;
                    }
                });
            });
        }

        function toggleBounce() {
            if (marker.getAnimation() !== null) {
                marker.setAnimation(null);
            } else {
                marker.setAnimation(google.maps.Animation.BOUNCE);
            }
        }

        function toggleBounce2() {
            if (marker2.getAnimation() !== null) {
                marker2.setAnimation(null);
            } else {
                marker2.setAnimation(google.maps.Animation.BOUNCE);
            }
        }

        function sender(){
            address = document.getElementById('sender_address_aux').value;
            geocoder.geocode({ 'address': address}, function(results, status){
                if (status == 'OK'){
                    document.getElementById('sender_address').value = address;
                    document.getElementById("sender_latitude").value = results[0].geometry.location.lat();
                    document.getElementById("sender_longitude").value = results[0].geometry.location.lng();
                    marker.setPosition(results[0].geometry.location);
                    map.setCenter(marker.getPosition());

                    document.getElementById("check_sender").value = "0";
                    geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            var address=results[0]['formatted_address'];
                            for (var i = 0; i < results[0].address_components.length; i++ ){
                                if (results[0].address_components[i].long_name == 'Cali'){
                                    document.getElementById("check_sender").value = "1";
                                }
                            }
                        }
                    });
                }
            });
        }

        function receiver(){
            address = document.getElementById('receiver_address_aux').value;
            geocoder.geocode({ 'address': address}, function(results, status){
                if (status == 'OK'){
                    document.getElementById('receiver_address').value = address;
                    document.getElementById("receiver_latitude").value = results[0].geometry.location.lat();
                    document.getElementById("receiver_longitude").value = results[0].geometry.location.lng();
                    marker2.setPosition(results[0].geometry.location);
                    map.setCenter(marker2.getPosition());

                    document.getElementById("check_receiver").value = "0";
                    geocoder.geocode({'latLng': marker2.getPosition()}, function(results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            var address=results[0]['formatted_address'];
                            for (var i = 0; i < results[0].address_components.length; i++ ){
                                if (results[0].address_components[i].long_name == 'Cali'){
                                    document.getElementById("check_receiver").value = "1";
                                }
                            }
                        }
                    });
                }
            });
        }

        function route(){
            if (document.getElementById("check_sender").value == "0"){
                Swal.fire({
                    title: "¡Ups!",
                    text: "La dirección de origen no se encuentra en los límites del servicio. (Solo disponible en Cali). Si deseas programar un servicio por fuera del perímetro urbano, por favor comunícate con nosotros por WhatsApp.",
                    type: "error",
                    confirmButtonClass: 'btn btn-primary',
                    buttonsStyling: false,
                });    //delete content
                document.getElementById("submit-button").disabled = true;
            }else if(document.getElementById("check_receiver").value == "0"){
                Swal.fire({
                    title: "¡Ups!",
                    text: "La dirección de destino no se encuentra en los límites del servicio. (Solo disponible en Cali). Si deseas programar un servicio por fuera del perímetro urbano, por favor comunícate con nosotros por WhatsApp.",
                    type: "error",
                    confirmButtonClass: 'btn btn-primary',
                    buttonsStyling: false,
                });
                document.getElementById("submit-button").disabled = true;
            }else{
                var request = {
                    origin: marker.getPosition(),
                    destination: marker2.getPosition(),
                    travelMode: google.maps.DirectionsTravelMode['DRIVING'],
                    unitSystem: google.maps.DirectionsUnitSystem['METRIC'],
                    provideRouteAlternatives: false
                };

                directionsDisplay = new google.maps.DirectionsRenderer();
                directionsService = new google.maps.DirectionsService();

                directionsService.route(request, function (response, status) {
                    if (status == google.maps.DirectionsStatus.OK) {
                        directionsDisplay.setMap(map);
                        directionsDisplay.setDirections(response);
                        var rate = getRate(response.routes[0].legs[0].distance.value/1000);
                        document.getElementById("distance").innerHTML = response.routes[0].legs[0].distance.text;
                        document.getElementById("rate").value = rate;
                        document.getElementById("rate_span").innerHTML = "Tarifa: $"+rate;
                    }
                });

                marker.setVisible(false);
                marker2.setVisible(false);
                document.getElementById("sender_search").disabled = true;
                document.getElementById("receiver_search").disabled = true;
                document.getElementById("sender_address_aux").disabled = true;
                document.getElementById("receiver_address_aux").disabled = true;
                document.getElementById("ready").style.display = 'none';
                document.getElementById("restore_map").style.display = 'block';
                document.getElementById("submit-button").disabled = false;
            }
        }

        function getRate($distance){
            if ( ($distance >= 0) && ($distance <= 3.9) ){
                return 5000;
            }else if ( ($distance > 3.9) && ($distance <= 6.9) ){
                return 6000;
            }else if ( ($distance > 6.9) && ($distance <= 9.9) ){
                return 7000;
            }else if ( ($distance > 9.9) && ($distance <= 12.9) ){
                return 8000;
            }else if ( ($distance > 12.9) && ($distance <= 15.9) ){
                return 9000;
            }else if ( ($distance > 15.9) && ($distance <= 18.9) ){
                return 10000;
            }else if ( ($distance > 18.9) && ($distance <= 21.9) ){
                return 11000;
            }else if ($distance > 21.9){
                return 12000;
            }
        }

        $(document).ready(function () {
            autocomplete = new google.maps.places.Autocomplete((document.getElementById("sender_address_aux")), {
                types: ['geocode'],
                 componentRestrictions: {
                    country: "CO"
                }
            });

            autocomplete2 = new google.maps.places.Autocomplete((document.getElementById("receiver_address_aux")), {
                types: ['geocode'],
                 componentRestrictions: {
                    country: "CO"
                }
            });

            google.maps.event.addListener(autocomplete, 'place_changed', function () {
                var near_place = autocomplete.getPlace();
                document.getElementById('sender_latitude').value = near_place.geometry.location.lat();
                document.getElementById('sender_longitude').value = near_place.geometry.location.lng();
                marker.setPosition( near_place.geometry.location);
                map.setCenter(marker.getPosition());

                document.getElementById("check_sender").value = "0";
                geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        var address=results[0]['formatted_address'];
                        for (var i = 0; i < results[0].address_components.length; i++ ){
                            if (results[0].address_components[i].long_name == 'Cali'){
                                document.getElementById("check_sender").value = "1";
                            }
                        }
                        document.getElementById("sender_address").value = address;
                    }
                });
            });

            google.maps.event.addListener(autocomplete2, 'place_changed', function () {
                var near_place = autocomplete2.getPlace();
                document.getElementById('receiver_latitude').value = near_place.geometry.location.lat();
                document.getElementById('receiver_longitude').value = near_place.geometry.location.lng();
                marker2.setPosition(near_place.geometry.location);
                map.setCenter(marker2.getPosition());

                document.getElementById("check_receiver").value = "0";
                geocoder.geocode({'latLng': marker2.getPosition()}, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        var address=results[0]['formatted_address'];
                        for (var i = 0; i < results[0].address_components.length; i++ ){
                            if (results[0].address_components[i].long_name == 'Cali'){
                                document.getElementById("check_receiver").value = "1";
                            }
                        }
                        document.getElementById("receiver_address").value = address;
                    }
                });
            });
        });

        function checkPaymentMethod() {
            if (document.getElementById("payment_method").value == 'reembolso') {
                document.getElementById('refund_div').style.display = 'block';
                document.getElementById('payment_type_div').style.display = 'none';
                document.getElementById("total").value = parseInt(document.getElementById("rate").value) + parseInt(document.getElementById("additional_cost2").value) + parseInt(document.getElementById("refund_amount").value);
            }else if (document.getElementById("payment_method").value == 'transferencia'){
                document.getElementById('refund_div').style.display = 'none';
                document.getElementById('payment_type_div').style.display = 'none';
                document.getElementById("total").value = parseInt(document.getElementById("rate").value) + parseInt(document.getElementById("additional_cost2").value);
            }else if (document.getElementById("payment_method").value == 'efectivo'){
                document.getElementById('refund_div').style.display = 'none';
                document.getElementById('payment_type_div').style.display = 'block';
                document.getElementById("total").value = parseInt(document.getElementById("rate").value) + parseInt(document.getElementById("additional_cost2").value);
            }
        }

        function updateTotal(){
            document.getElementById("total").value = parseInt(document.getElementById("total2").value) + parseInt(document.getElementById("refund_amount").value);
        }

        function immediatelyCheck(){
            if (document.getElementById('immediately_check').checked){
                $("#time").attr("disabled", true);
                $("#time").prop('required', false);
                $("#time").removeClass("required");
                $("#time").val("");
                $("#immediately").val(1);
            } else {
                $("#time").attr("disabled", false);
                $("#time").prop('required', true);
                $("#time").addClass("required");
                $("#immediately").val(0);
            }
        }
	</script>
@endpush

@section('content')
	@if (Session::has('msj-exitoso'))
        <div class="col-md-12">
            <div class="alert alert-success">
                <strong>{{ Session::get('msj-exitoso') }}</strong>
            </div>
        </div>
    @endif

    <section id="nav-justified">
        <input type="hidden" id="check_sender" value="1">
        <input type="hidden" id="check_receiver" value="1">
        <div class="row">
            <div class="col-sm-12">
                <div class="card overflow-hidden">
                    <div class="card-header">
                        <h4 class="card-title">Servicio #{{ $servicio->id }}</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <ul class="nav nav-tabs nav-justified" id="myTab2" role="tablist">
                            	<li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#ruta" role="tab" aria-controls="ruta" aria-selected="false">Ruta</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#datos" role="tab" aria-controls="datos" aria-selected="true">Datos del Servicio</a>
                                </li>
                            </ul>
                            <div class="tab-content pt-1">
                                <div class="tab-pane active" id="ruta" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <fieldset>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="sender_address_aux" value="{{ $servicio->sender_address }}" disabled>
                                                    <div class="input-group-append" id="button-addon2">
                                                        <button class="btn waves-effect waves-light" type="button" id="sender_search" style="background-color: #3BCD3F; color:white;" onclick="sender();" disabled><i class="fa fa-search"></i></button>
                                                    </div>
                                                </div>
                                            </fieldset>

                                            <br class="d-block d-sm-none">
                                        </div>

                                        <div class="col-md-6">
                                            <fieldset>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="receiver_address_aux" value="{{ $servicio->receiver_address }}" disabled>
                                                    <div class="input-group-append" id="button-addon2">
                                                        <button class="btn waves-effect waves-light" type="button" id="receiver_search" style="background-color: #768ADE; color:white;" onclick="receiver();" disabled><i class="fa fa-search"></i></button>
                                                    </div>
                                                </div>
                                            </fieldset><bR>
                                        </div>

                                        <div class="col-md-12">
                                             <div class="form-group text-center" id="ready" style="display: none;">
                                                <a class="btn btn-primary" onclick="route();" style="color: white;">¡Listo!<br> Consultar Tarifa</a>
                                            </div>
                                            <div class="form-group text-center" id="restore_map">
                                                <a class="btn btn-warning" onclick="setMap();" style="color: white;">Editar Direcciones</a>
                                            </div>
                                            <div class="form-group">
                                                <div id="mapa"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <span id="distance" style="font-weight: bold;">0.0 Kilómetros</span>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            <input type="hidden" class="form-control" name="rate" id="rate">
                                            <span id="rate_span" style="font-weight: bold; font-size: 22px;">Tarifa: $0.0</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane" id="datos" role="tabpanel">
                                	<form action="{{ route('services.update') }}" method="POST">
                                		@csrf
                                		<input type="hidden" name="service_id" value="{{ $servicio->id }}">
                                        <input type="hidden" name="sender_latitude" id="sender_latitude" value="{{ $servicio->sender_latitude }}">
                                        <input type="hidden" name="sender_longitude" id="sender_longitude" value="{{ $servicio->sender_longitude }}">
                                        <input type="hidden" name="receiver_latitude" id="receiver_latitude" value="{{ $servicio->receiver_latitude }}">
                                        <input type="hidden" name="receiver_longitude" id="receiver_longitude" value="{{ $servicio->receiver_longitude }}">
                                        <input type="hidden" name="sender_address" id="sender_address" value="{{ $servicio->sender_address }}">
                                        <input type="hidden" name="receiver_address" id="receiver_address" value="{{ $servicio->receiver_address }}">
                                        <input type="hidden" class="form-control" name="rate" id="rate" value="{{ $servicio->rate }}">
                                        <input type="hidden" name="immediately" id="immediately" value="{{ $servicio->immediately }}">
                                		<div class="row">
	                                	 	<div class="col-sm-12 col-12">
	                                            <div class="text-bold-600 font-medium-2 mb-1">
	                                                Nombre y teléfono del cliente que envía
	                                            </div>
	                                            <fieldset class="form-group position-relative has-icon-left">
	                                                <input type="text" class="form-control" name="sender" value="{{ $servicio->sender }}">
	                                                <div class="form-control-position">
	                                                    <i class="fas fa-id-card"></i>
	                                                </div>
	                                            </fieldset>
	                                        </div>
                                            <div class="col-sm-8 col-12">
                                                <div class="text-bold-600 font-medium-2 mb-1">
                                                    Dirección como la conoces de quién envía
                                                </div>
                                                <fieldset class="form-group position-relative has-icon-left">
                                                    <input type="text" class="form-control" name="sender_address_opc" value="{{ $servicio->sender_address_opc }}">
                                                    <div class="form-control-position">
                                                        <i class="fa fa-city"></i>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-sm-4 col-12">
                                                <div class="text-bold-600 font-medium-2 mb-1">
                                                    Barrio de quién envía
                                                </div>
                                                <fieldset class="form-group position-relative has-icon-left">
                                                    <input type="text" class="form-control" name="sender_neighborhood" id="sender_neighborhood" value="{{ $servicio->sender_neighborhood }}">
                                                    <div class="form-control-position">
                                                        <i class="fa fa-city"></i>
                                                    </div>
                                                </fieldset>
                                            </div>
	                                        <div class="col-sm-12 col-12">
	                                            <div class="text-bold-600 font-medium-2 mb-1">
	                                                Nombre y teléfono del cliente que recibe
	                                            </div>
	                                            <fieldset class="form-group position-relative has-icon-left">
	                                                <input type="text" class="form-control" name="receiver" value="{{ $servicio->receiver }}">
	                                                <div class="form-control-position">
	                                                    <i class="fas fa-id-card"></i>
	                                                </div>
	                                            </fieldset>
	                                        </div>
                                            <div class="col-sm-8 col-12">
                                                <div class="text-bold-600 font-medium-2 mb-1">
                                                    Dirección como la conoces de quién recibe
                                                </div>
                                                <fieldset class="form-group position-relative has-icon-left">
                                                    <input type="text" class="form-control" name="receiver_address_opc" value="{{ $servicio->receiver_address_opc }}">
                                                    <div class="form-control-position">
                                                        <i class="fa fa-city"></i>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-sm-4 col-12">
                                                <div class="text-bold-600 font-medium-2 mb-1">
                                                    Barrio de quién recibe
                                                </div>
                                                <fieldset class="form-group position-relative has-icon-left">
                                                    <input type="text" class="form-control" name="receiver_neighborhood" id="receiver_neighborhood" value="{{ $servicio->receiver_neighborhood }}">
                                                    <div class="form-control-position">
                                                        <i class="fa fa-city"></i>
                                                    </div>
                                                </fieldset>
                                            </div>
											<div class="col-sm-3 col-12">
	                                            <div class="text-bold-600 font-medium-2 mb-1">
	                                                Fecha del Servicio
	                                            </div>
	                                            <fieldset class="form-group position-relative has-icon-left">
	                                                <input type="date" class="form-control" name="date" value="{{ $servicio->date }}" min="{{ date('Y-m-d') }}">
	                                                <div class="form-control-position">
	                                                    <i class="fa fa-calendar"></i>
	                                                </div>
	                                            </fieldset>
	                                        </div>
	                                        <div class="col-sm-3 col-12">
	                                            <div class="text-bold-600 font-medium-2 mb-1">
	                                                Hora del Servicio
	                                            </div>
	                                            <fieldset class="form-group position-relative has-icon-left">
	                                                <input type="time" class="form-control" name="time" id="time" value="{{ $servicio->time }}" min="07:00" max="19:00" @if ($servicio->immediately == 1 && is_null($servicio->time)) disabled="" @endif>
	                                                <div class="form-control-position">
	                                                    <i class="fa fa-clock"></i>
	                                                </div>
	                                            </fieldset>
                                            </div>
                                            <div class="col-md-3">
                                                <ul class="list-unstyled my-2">
                                                    <li class="d-inline-block mr-2">
                                                        <fieldset>
                                                            <div class="vs-checkbox-con vs-checkbox-primary">
                                                                <input type="checkbox" name="immediately_check_data" id="immediately_check" onclick="immediatelyCheck();" @if ($servicio->immediately == 1) checked="" @endif >
                                                                <span class="vs-checkbox">
                                                                    <span class="vs-checkbox--check">
                                                                        <i class="vs-icon feather icon-check"></i>
                                                                    </span>
                                                                </span>
                                                                <span>Inmediatamente</span>
                                                            </div>
                                                        </fieldset>
                                                    </li>
                                                </ul>
                                            </div>
	                                        <div class="col-sm-6 col-12">
	                                            <div class="text-bold-600 font-medium-2 mb-1">
	                                                Artículo a Transportar
	                                            </div>
	                                            <fieldset class="form-group position-relative has-icon-left">
	                                                <input type="text" class="form-control" name="article" value="{{ $servicio->article }}">
	                                                <div class="form-control-position">
	                                                   <i class="fas fa-box-open"></i>
	                                                </div>
	                                            </fieldset>
	                                        </div>
	                                        <div class="col-sm-6 col-12">
	                                            <div class="text-bold-600 font-medium-2 mb-1">
	                                                Equipo Breve
	                                            </div>
	                                            <ul class="list-unstyled mb-0">
                                                    <li class="d-inline-block mr-2">
                                                        <fieldset>
                                                            <div class="vs-checkbox-con vs-checkbox-primary">
                                                                <input type="checkbox" name="equipment_type[]"  value="Maletin" @if (in_array("Maletin", $equipment_type)) checked="true" @endif>
                                                                <span class="vs-checkbox">
                                                                    <span class="vs-checkbox--check">
                                                                        <i class="vs-icon feather icon-check"></i>
                                                                    </span>
                                                                </span>
                                                                <span class="">Maletín (Morral Convencional)</span>
                                                            </div>
                                                        </fieldset>
                                                    </li>
                                                    <li class="d-inline-block mr-2">
                                                        <fieldset>
                                                            <div class="vs-checkbox-con vs-checkbox-primary">
                                                                <input type="checkbox" name="equipment_type[]" value="MB" @if (in_array("MB", $equipment_type)) checked="" @endif>
                                                                <span class="vs-checkbox">
                                                                    <span class="vs-checkbox--check">
                                                                        <i class="vs-icon feather icon-check"></i>
                                                                    </span>
                                                                </span>
                                                                <span class="">Maleta Breve (Especial para Domicilios)</span>
                                                            </div>
                                                        </fieldset>
                                                    </li>
                                                     <li class="d-inline-block mr-2">
                                                        <fieldset>
                                                            <div class="vs-checkbox-con vs-checkbox-primary">
                                                                <input type="checkbox" name="equipment_type[]" value="Canasta" @if (in_array("Canasta", $equipment_type)) checked="" @endif>
                                                                <span class="vs-checkbox">
                                                                    <span class="vs-checkbox--check">
                                                                        <i class="vs-icon feather icon-check"></i>
                                                                    </span>
                                                                </span>
                                                                <span class="">Canasta</span>
                                                            </div>
                                                        </fieldset>
                                                    </li>
                                                </ul>
	                                        </div>
	                                        <div class="col-sm-3 col-12">
                                                <div class="text-bold-600 font-medium-2 mb-1">
                                                    Forma de Pago
                                                </div>
                                                <fieldset class="form-group position-relative has-icon-left">
                                                    <select class="form-control" name="payment_method" id="payment_method" onchange="checkPaymentMethod();">
                                                        <option value="efectivo" @if ($servicio->payment_method == 'efectivo') selected @endif>Efectivo</option>
                                                        <option value="transferencia" @if ($servicio->payment_method == 'transferencia') selected @endif>Transferencia</option>
                                                        <option value="reembolso" @if ($servicio->payment_method == 'reembolso') selected @endif>Contra Entrega</option>
                                                    </select>
                                                    <div class="form-control-position">
                                                        <i class="fa fa-money"></i>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-sm-3 col-12" id="refund_div" @if ($servicio->payment_method != 'reembolso') style="display: none;" @endif>
                                                <div class="text-bold-600 font-medium-2 mb-1">
                                                    Monto de Entrega
                                                </div>
                                                <fieldset class="form-group position-relative has-icon-left">
                                                    <input type="text" class="form-control" name="refund_amount" id="refund_amount" value="{{ $servicio->refund_amount }}" onkeyup="updateTotal();">
                                                    <div class="form-control-position">
                                                        <i class="fas fa-hand-holding-usd"></i>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-sm-3 col-12" id="payment_type_div" @if ($servicio->payment_method != 'efectivo') style="display: none;" @endif>
                                                <div class="text-bold-600 font-medium-2 mb-1">
                                                    El servicio se paga al:
                                                </div>
                                                <fieldset class="form-group position-relative has-icon-left">
                                                    <select class="form-control" name="payment_type">
                                                        <option value="inicio" @if ($servicio->payment_type == 'inicio') selected @endif>Inicio</option>
                                                        <option value="final" @if ($servicio->payment_type == 'final') selected @endif>Final</option>
                                                    </select>
                                                    <div class="form-control-position">
                                                        <i class="fas fa-money-check-alt"></i>
                                                    </div>
                                                </fieldset>
                                            </div>
	                                        <div class="col-sm-3 col-12">
	                                            <div class="text-bold-600 font-medium-2 mb-1">
	                                                Tarifa
	                                            </div>
	                                            <fieldset class="form-group position-relative has-icon-left">
	                                                <input type="text" class="form-control" value="{{ $servicio->rate }}" id="rate2" disabled>
	                                                <div class="form-control-position">
	                                                    <i class="fa fa-dollar"></i>
	                                                </div>
	                                            </fieldset>
	                                        </div>
	                                        <div class="col-sm-3 col-12">
	                                            <div class="text-bold-600 font-medium-2 mb-1">
	                                                Costo Adicional
	                                            </div>
	                                            <fieldset class="form-group position-relative has-icon-left">
                                                    <input type="hidden" id="additional_cost2" value="{{ $servicio->additional_cost }}">
	                                                <input type="number" class="form-control" name="additional_cost" id="additional_cost" value="{{ $servicio->additional_cost }}" disabled>
	                                                <div class="form-control-position">
	                                                    <i class="fa fa-dollar"></i>
	                                                </div>
	                                            </fieldset>
	                                        </div>
	                                        <div class="col-sm-3 col-12">
	                                            <div class="text-bold-600 font-medium-2 mb-1">
	                                                Precio Total
	                                            </div>
	                                            <fieldset class="form-group position-relative has-icon-left">
                                                    <input type="hidden" id="total2" value="{{ $servicio->total }}">
	                                                <input type="text" class="form-control" id="total" value="{{ $servicio->total }}" disabled>
	                                                <div class="form-control-position">
	                                                    <i class="fa fa-dollar"></i>
	                                                </div>
	                                            </fieldset>
	                                        </div>
	                                        <div class="col-sm-12 col-12">
	                                        	<div class="text-bold-600 font-medium-2 mb-1">
	                                                Observaciones
	                                            </div>
	                                            <fieldset class="form-group position-relative has-icon-left">
	                                            	<textarea class="form-control" rows="4" name="observations">{{ $servicio->observations }}</textarea>
	                                            	<div class="form-control-position">
	                                                    <i class="fas fa-comment-dots"></i>
	                                                </div>
	                                            </fieldset>

	                                        </div>
	                                        <div class="col-sm-12 col-12" style="text-align: right;">
	                                        	<button type="submit" class="btn btn-primary mr-1 mb-1 waves-effect waves-light" id="submit-button"><i class="fa fa-check"></i> Guardar Cambios</button>
	                                        </div>
                                    	</div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
