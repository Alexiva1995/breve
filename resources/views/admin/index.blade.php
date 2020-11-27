@extends('layouts.admin')

@push('styles')
    <style>
        #mapa {
            width: 100%;
            height: 400px;
        }

        .select2-container, .select2-dropdown, .select2-search, .select2-results {
            -webkit-transition: none !important;
            -moz-transition: none !important;
            -ms-transition: none !important;
            -o-transition: none !important;
            transition: none !important;
        }
    </style>
@endpush

@push('scripts')
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDX1cuvw-Xs_HC-1WjVtxv-18bLvKRu3Rw&callback=initMap&libraries=geometry,places" type="text/javascript"></script>

    <script>
        $('#myTable').DataTable( {
            dom: 'frtip',
            "order": [
                [1, 'asc']
            ],
            drawCallback: function() {
                $(".page-item .page-link").on('click', function(){
                    $('html, body').animate({
                        scrollTop: $("#card-table").offset().top
                    }, 1000); 
                });
            }
        });

        function changeStatus($service_id){
            $status = $("#service-"+$service_id).val();
            if ( ($service_id == 0) || ($service_id == 5) ){
                $("#status2").val($status);
                $("#service_id2").val($service_id);
                $("#status_form").submit();
            }else{
                if ($("#service-"+$service_id).attr('data-brever') == ""){
                    $('#service_id').val($service_id);
                    $("#breverModal").modal('show');
                }else{
                    $("#status2").val($status);
                    $("#service_id2").val($service_id);
                    $("#status_form").submit();
                }
            }
        }

        $(function() {
            $("#user_id").select2({
                dropdownAutoWidth: true,
                width: '100%',
                scrollAfterSelect: true
            });
            $('#user_id').on('change',function() {
                $('#user_id').focus().select();
                var id = $(this).val();
                $("#sender").val("");
                $("#sender_address_opc").val("");
                $("#sender_neighborhood").val("");
                $("#receiver").val("");
                $("#receiver_address_opc").val("");
                $("#receiver_neighborhood").val("");

                if (id == 0){
                    document.getElementById("client_name_div").style.display = 'block';
                    $('#client_name').prop("required", true);
                    $('#remember_sender_check').prop('disabled', true);
                    $('#remember_receiver_check').prop('disabled', true);
                    $("#sender_data_div").css('display', 'none');
                    $("#receiver_data_div").css('display', 'none');
                }else{
                    //var path = "http://localhost:8000/admin/services/load-remember-data/"+id;
                    var path = "https://www.breve.com.co/admin/services/load-remember-data/"+id;

                    $.ajax({
                        type:"GET",
                        url:path,
                        success:function(ans){
                            $("#sender_data").empty();
                            $("#receiver_data").empty();
                            if (ans["cantDatosEnvio"] > 0){
                                $("#sender_data").append('<option value="" selected disabled>Seleccione una opción...</option>');
                                $.each(ans['datosEnvio'], function (ind, data) { 
                                    if (data.alias_admin == null){
                                        $("#sender_data").append('<option value="'+data.id+'">'+data.alias+'</option>');
                                    }else{
                                        $("#sender_data").append('<option value="'+data.id+'">'+data.alias_admin+'</option>');
                                    }
                                }); 
                                $("#sender_data_div").css('display', 'block');
                            }else{
                                $("#sender_data_div").css('display', 'none');
                            }
                            
                            if (ans["cantDatosEnvio"] > 0){
                                $("#receiver_data").append('<option value="" selected disabled>Seleccione una opción...</option>');
                                $.each(ans['datosRecogida'], function (ind, data) { 
                                    if (data.alias_admin == null){
                                        $("#receiver_data").append('<option value="'+data.id+'">'+data.alias+'</option>');
                                    }else{
                                        $("#receiver_data").append('<option value="'+data.id+'">'+data.alias_admin+'</option>');
                                    }
                                    $("#receiver_data_div").css('display', 'block');
                                });
                            }else{
                                $("#receiver_data_div").css('display', 'none');
                            }
                        } 
                    }); 
                    document.getElementById("client_name_div").style.display = 'none';
                    $('#client_name').removeAttr("required");
                    $('#remember_sender_check').removeAttr('disabled');
                    $('#remember_receiver_check').removeAttr('disabled');
                }
            });
        });

        function asignarBrever($servicio){
            $('#service_id').val($servicio);
            $("#breverModal").modal('show');
        }

        function reasignarBrever($servicio, $brever){
            $('#service_id').val($servicio);
            $("#brever_id option[value="+$brever+"]").attr("selected",true);
            $("#breverModal").modal('show');
        }
    </script>

    <script>
        var map;
        var coords = {};
        var marker;   
        var marker2; 
        var geocoder;
        var geocoder2;
        var directionsDisplay;
        var directionsService;
        var autocomplete;
        var autocomplete2;

        initMap = function () {
            navigator.geolocation.getCurrentPosition(
                function (position){
                    coords =  {
                        lng: -76.5319854,
                        lat: 3.4516467
                    };
                    setMapa(coords);
                },function(error){
                    console.log(error);
                }
            );
        }
 
        function setMapa (coords){   
            map = new google.maps.Map(document.getElementById('mapa'),
            {
                zoom: 13,
                center:new google.maps.LatLng(coords.lat,coords.lng),
            });

            document.getElementById("sender_latitude").value = coords.lat;
            document.getElementById("sender_longitude").value = coords.lng;
            document.getElementById("receiver_latitude").value = coords.lat+0.002;
            document.getElementById("receiver_longitude").value = coords.lng+0.002;

            // creamos el objeto geodecoder
            geocoder = new google.maps.Geocoder();
            geocoder.geocode({'latLng': coords}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    var address=results[0]['formatted_address'];
                    document.getElementById("sender_address").value = address;
                    document.getElementById("sender_address_aux").value = address;
                }
            });
            geocoder.geocode({'latLng': {lng: coords.lng+0.002, lat: coords.lat+0.002}}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    var address=results[0]['formatted_address'];
                    document.getElementById("receiver_address").value = address;
                    document.getElementById("receiver_address_aux").value = address;
                }
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

        function senderSearch(){
            address = document.getElementById('sender_address_aux').value;
            document.getElementById("sender_address").value = address;
            geocoder.geocode({ 'address': address}, function(results, status){
                if (status == 'OK'){
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

        function receiverSearch(){
            address = document.getElementById('receiver_address_aux').value;
            document.getElementById("receiver_address").value = address;
            geocoder.geocode({ 'address': address}, function(results, status){
                if (status == 'OK'){
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
                $(".actions").hide();
            }else if(document.getElementById("check_receiver").value == "0"){
                Swal.fire({
                    title: "¡Ups!",
                    text: "La dirección de destino no se encuentra en los límites del servicio. (Solo disponible en Cali). Si deseas programar un servicio por fuera del perímetro urbano, por favor comunícate con nosotros por WhatsApp.",
                    type: "error",
                    confirmButtonClass: 'btn btn-primary',
                    buttonsStyling: false,
                });   
                $(".actions").hide();
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
                document.getElementById("sender_address_aux").disabled = true;
                document.getElementById("receiver_address_aux").disabled = true;
                document.getElementById("sender_search").disabled = true;
                document.getElementById("receiver_search").disabled = true;
                document.getElementById("ready").style.display = 'none';
                document.getElementById("restore_map").style.display = 'block';
                $(".actions").show();
            }     
        }

        function restoreMap(){
            directionsDisplay.setMap(null);
            marker.setVisible(true);
            marker2.setVisible(true);
            document.getElementById("sender_address_aux").disabled = false;
            document.getElementById("receiver_address_aux").disabled = false;
            document.getElementById("sender_search").disabled =false;
            document.getElementById("receiver_search").disabled =false;
            document.getElementById("restore_map").style.display = 'none';
            document.getElementById("ready").style.display = 'block';
            document.getElementById("distance").innerHTML = "0.0 Kilómetros";
            document.getElementById("rate_span").innerHTML = "Tarifa: $0.0";
            $(".actions").hide();
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
            $(".actions").hide();
            
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
                marker.setPosition(near_place.geometry.location);
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
                document.getElementById('refund_div2').style.display = 'block';
                document.getElementById('payment_type_div').style.display = 'none';
            }else if (document.getElementById("payment_method").value == 'transferencia'){
                document.getElementById('refund_div').style.display = 'none';
                document.getElementById('refund_div2').style.display = 'none';
                document.getElementById('payment_type_div').style.display = 'none';
            }else if (document.getElementById("payment_method").value == 'efectivo'){
                document.getElementById('refund_div').style.display = 'none';
                 document.getElementById('refund_div2').style.display = 'none';
                document.getElementById('payment_type_div').style.display = 'block';
            }
        }

        function updateAmount(){
            document.getElementById("refund_amount2").value = parseInt(document.getElementById("refund_amount").value) + parseInt(document.getElementById("rate").value);
        }

        function rememberCheck($opcion){
            if ($opcion == 1){
                if (document.getElementById('remember_sender_check').checked){
                    $("#sender_data_alias_div").css('visibility', 'visible');
                    $("#sender_data_alias").prop('required', true);
                }else{
                    $("#sender_data_alias_div").css('visibility', 'hidden');
                    $("#sender_data_alias").prop('required', false);
                }
            }else{
                if (document.getElementById('remember_receiver_check').checked){
                    $("#receiver_data_alias_div").css('visibility', 'visible');
                    $("#receiver_data_alias").prop('required', true);
                }else{
                    $("#receiver_data_alias_div").css('visibility', 'hidden');
                    $("#receiver_data_alias").prop('required', false);
                }
            }
        }

        function loadSenderData(){
            //var path = "http://localhost:8000/admin/services/load-data/"+$("#sender_data").val();
            var path = "https://www.breve.com.co/admin/services/load-data/"+$("#sender_data").val();

            $.ajax({
                type:"GET",
                url:path,
                success:function(ans){
                    $("#sender").val(ans.identification);
                    $("#sender_address_opc").val(ans.address_opc);
                    $("#sender_neighborhood").val(ans.neighborhood);
                } 
            }); 
        }

        function loadReceiverData(){
            //var path = "http://localhost:8000/admin/services/load-data/"+$("#receiver_data").val();
            var path = "https://www.breve.com.co/admin/services/load-data/"+$("#receiver_data").val();

            $.ajax({
                type:"GET",
                url:path,
                success:function(ans){
                    $("#receiver").val(ans.identification);
                    $("#receiver_address_opc").val(ans.address_opc);
                    $("#receiver_neighborhood").val(ans.neighborhood);
                } 
            }); 
        }

    </script>

    <script>
        $(".page-item .page-link").on('click', function(){
            $('html, body').animate({
                scrollTop: $("#card-table").offset().top
            }, 1000); 
        });
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

    <div class="row">
        <div class="col-lg-3 col-sm-6 col-12">
            <a href="{{ route('admin.services') }}">
                <div class="card">
                    <div class="card-header d-flex flex-column align-items-start pb-0">
                        <div class="avatar p-50 m-0" style="background-color: #11ec1a;">
                            <div class="avatar-content">
                                <i class="feather icon-user-plus font-medium-5"></i>
                            </div>
                        </div>
                        <h2 class="text-bold-700 mt-1 mb-25">{{ $cantServiciosNuevos }}</h2>
                        <p class="mb-0">Servicios Nuevos</p>
                    </div>
                    <div class="card-content">
                        <div id="servicios-nuevos"></div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-sm-6 col-12">
            <a href="{{ route('admin.services.assigned') }}">
                <div class="card">
                    <div class="card-header d-flex flex-column align-items-start pb-0">
                        <div class="avatar p-50 m-0" style="background-color: #1a5d1c;">
                            <div class="avatar-content">
                                <i class="feather icon-user-minus font-medium-5"></i>
                            </div>
                        </div>
                        <h2 class="text-bold-700 mt-1 mb-25">{{ $cantServiciosAsignados }}</h2>
                        <p class="mb-0">Servicios Asignados</p>
                    </div>
                    <div class="card-content">
                        <div id="servicios-asignados"></div>
                    </div>
                </div>
            </a>
        </div> 
        <div class="col-lg-3 col-sm-6 col-12">
            <a href="{{ route('admin.services.completed') }}">
                <div class="card">
                    <div class="card-header d-flex flex-column align-items-start pb-0">
                        <div class="avatar p-50 m-0" style="background-color: #4caf50;">
                            <div class="avatar-content">
                                <i class="feather icon-user-check font-medium-5"></i>
                            </div>
                        </div>
                        <h2 class="text-bold-700 mt-1 mb-25">{{ $cantServiciosCompletados }}</h2>
                        <p class="mb-0">Servicios Completados</p>
                    </div>
                    <div class="card-content">
                        <div id="servicios-completados"></div>
                    </div>
                </div>
            </a>
        </div>
         <div class="col-lg-3 col-sm-6 col-12">
            <a href="{{ route('admin.services.canceled') }}">
                <div class="card">
                    <div class="card-header d-flex flex-column align-items-start pb-0">
                        <div class="avatar p-50 m-0" style="background-color: #EA5455;">
                            <div class="avatar-content">
                                <i class="feather icon-user-x font-medium-5"></i>
                            </div>
                        </div>
                        <h2 class="text-bold-700 mt-1 mb-25">{{ $cantServiciosCancelados }}</h2>
                        <p class="mb-0">Servicios Declinados</p>
                    </div>
                    <div class="card-content">
                        <div id="servicios-cancelados"></div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="row" id="card-table">
        <div class="col-lg-12 col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Servicios Próximos</h4>
                </div>
                <div class="card-content">
                    <div class="table-responsive mt-1">
                        <table class="table" id="myTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Fecha</th>
                                    <th>Hora</th>
                                    <th>Cliente</th>
                                    <th>Descripción</th>
                                    <th>Equipamiento</th>
                                    <th>Tarifa</th>
                                    <th>Estado</th>
                                    <th>Brever</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($serviciosProximos as $servicio)
                                    <tr>
                                        <td>{{ $servicio->id }}</td>
                                        <td>{{ date('Y-m-d', strtotime($servicio->date)) }}</td>
                                        <td>{{ date('H:i', strtotime($servicio->time)) }}</td>
                                        <td>
                                            @if ($servicio->user_id == 0)
                                                {{ $servicio->client_name }} (No Registrado)
                                            @else
                                                @if (!is_null($servicio->user->tradename))
                                                    {{ $servicio->user->tradename}}
                                                @else
                                                    {{ $servicio->user->name }}
                                                @endif
                                            @endif
                                        </td>
                                        <td>{{ $servicio->sender_neighborhood }} - {{ $servicio->receiver_neighborhood }}</td>
                                        <td>{{ $servicio->equipment_type }}</td>
                                        <td>
                                            @if ($servicio->rate_status == 1)
                                                ${{ $servicio->rate + $servicio->additional_cost}}
                                            @else
                                                Sin Calcular
                                            @endif
                                        </td>
                                        <td >
                                            <select style="width: 120px;" class="form-control status" name="status" id="service-{{$servicio->id}}" data-brever="{{$servicio->brever_id}}" onchange="changeStatus({{$servicio->id}});">
                                                <option value="0" @if ($servicio->status == 0) selected @endif>Pendiente</option>
                                                <option value="1"@if ($servicio->status == 1) selected @endif>Asignado</option>
                                                <option value="3"@if ($servicio->status == 3) selected @endif>Confirmado</option>
                                                <option value="2"@if ($servicio->status == 2) selected @endif>En Curso</option>
                                                <option value="4"@if ($servicio->status == 4) selected @endif>Completado</option>
                                                <option value="5"@if ($servicio->status == 5) selected @endif>Declinado</option>
                                            </select>
                                        </td>
                                        <td>
                                            @if (is_null($servicio->brever_id))
                                                -
                                            @else
                                                {{ $servicio->brever->name }}
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                @if (is_null($servicio->brever_id))
                                                    <a type="button" class="btn btn-outline-success" href="javascript:;" onclick="asignarBrever({{$servicio->id}});" title="Asignar Breve"><i class="fa fa-user-plus"></i></a>
                                                @else
                                                    <a type="button" class="btn btn-outline-success" href="javascript:;" onclick="reasignarBrever({{$servicio->id}},{{$servicio->brever_id}});" title="Reasignar Breve"><i class="fa fa-user-plus"></i></a>
                                                @endif
                                                <a type="button" class="btn btn-outline-info" href="{{ route('admin.services.show', $servicio->id) }}" title="Ver Detalles"><i class="fa fa-search"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <form action="{{ route('admin.services.change-status') }}" method="POST" id="status_form">
                            @csrf
                            <input type="hidden" name="service_id" id="service_id2">
                            <input type="hidden" name="status" id="status2">
                        </form> 
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-12">
            <section id="validation">
                <input type="hidden" id="check_sender" value="1">
                <input type="hidden" id="check_receiver" value="1">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Nuevo Servicio</h4>
                                <span>Si tu servicio es para lo más pronto posible por favor comunícate con nosotros haciendo clic en el botón flotante de WhatsApp.</span>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <form action="{{ route('admin.services.store') }}" method="POST" class="steps-validation wizard-circle" id="form-validation">
                                        @csrf
                                        <input type="hidden" name="sender_latitude" id="sender_latitude">
                                        <input type="hidden" name="sender_longitude" id="sender_longitude">
                                        <input type="hidden" name="receiver_latitude" id="receiver_latitude">
                                        <input type="hidden" name="receiver_longitude" id="receiver_longitude">
                                        <h6><i class="step-icon feather icon-map-pin"></i>Paso 1</h6>
                                        <fieldset>
                                            <div class="row">
                                                <div class="col-md-6 col-sm-12">
                                                    <fieldset>
                                                        <div class="input-group">
                                                            <input type="hidden" class="form-control" name="sender_address" id="sender_address">
                                                            <input type="text" class="form-control" id="sender_address_aux">
                                                            <div class="input-group-append" id="button-addon2">
                                                                <button class="btn waves-effect waves-light" type="button" id="sender_search" style="background-color: #3BCD3F; color:white;" onclick="senderSearch();"><i class="fa fa-search"></i></button>
                                                            </div>
                                                        </div>
                                                    </fieldset>

                                                    <br class="d-block d-sm-none">
                                                </div>
                                                
                                                <div class="col-md-6 col-sm-12">
                                                    <fieldset>
                                                        <div class="input-group">
                                                            <input type="hidden" class="form-control" name="receiver_address" id="receiver_address">
                                                            <input type="text" class="form-control" id="receiver_address_aux">
                                                            <div class="input-group-append" id="button-addon2">
                                                                <button class="btn waves-effect waves-light" type="button" id="receiver_search" style="background-color: #768ADE; color:white;" onclick="receiverSearch();"><i class="fa fa-search"></i></button>
                                                            </div>
                                                        </div>
                                                    </fieldset><bR>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group text-center" id="ready">
                                                        <a class="btn btn-primary" onclick="route();" style="color: white;">¡Listo!<br> Consultar Tarifa</a>
                                                    </div>
                                                    <div class="form-group text-center" id="restore_map" style="display: none;">
                                                        <a class="btn btn-warning" onclick="restoreMap();" style="color: white;">Editar Direcciones</a>
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
                                            <br>
                                        </fieldset>
                                        
                                        <h6><i class="step-icon feather icon-user"></i>Paso 2</h6>
                                        <fieldset>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="user_id"> Cliente</label>
                                                        <select class="form-control" name="user_id" id="user_id">
                                                            <option value="0" selected>No Registrado</option>
                                                            @foreach ($clientes as $cliente)
                                                                <option value="{{ $cliente->id }}">@if (!is_null($cliente->tradename)) {{ $cliente->tradename}} @else {{ $cliente->name }} @endif</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6" id="client_name_div">
                                                <div class="form-group">
                                                        <label for="client_name">Nombre del Cliente</label>
                                                        <input type="text" class="form-control" id="client_name" name="client_name" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h4 class="card-title">Datos de quien envía</h4>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12" id="sender_data_div" style="display: none;">
                                                    <div class="form-group">
                                                        <label for="address">Datos de Envío Guardados</label>
                                                        <div class="position-relative has-icon-left">
                                                            <select class="form-control" id="sender_data" onchange="loadSenderData();">
                                                            </select>
                                                            <div class="form-control-position">
                                                                <i class="feather icon-map-pin"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="sender"> Nombre y Teléfono</label>
                                                        <input type="text" class="form-control" id="sender" name="sender">
                                                    </div>
                                                </div>

                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label for="sender_address_opc">Dirección como la conoces</label>
                                                        <input type="text" class="form-control" id="sender_address_opc" name="sender_address_opc">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="sender_neighborhood">Barrio</label>
                                                        <input type="text" class="form-control required" id="sender_neighborhood" name="sender_neighborhood">
                                                    </div>
                                                </div>

                                                <div class="col-md-4" id="sender_data_alias_div" style="visibility: hidden;">
                                                    <div class="form-group">
                                                        <label for="sender_data_alias">Alias Datos de Envío</label>
                                                        <input type="text" class="form-control" name="sender_data_alias" id="sender_data_alias">
                                                    </div>
                                                </div>
                            
                                                <div class="col-md-8 text-right">
                                                    <ul class="list-unstyled mb-0">
                                                        <li class="d-inline-block mr-2">
                                                            <fieldset>
                                                                <div class="vs-checkbox-con vs-checkbox-primary">
                                                                    <input type="checkbox" name="remember_sender_data" id="remember_sender_check" onclick="rememberCheck(1);" disabled>
                                                                    <span class="vs-checkbox">
                                                                        <span class="vs-checkbox--check">
                                                                            <i class="vs-icon feather icon-check"></i>
                                                                        </span>
                                                                    </span>
                                                                    <span class="">Recordar datos de envío</span>
                                                                </div>
                                                            </fieldset>
                                                        </li>
                                                    </ul><br>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h4 class="card-title">Datos de quien recibe</h4>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12" id="receiver_data_div" style="display: none;">
                                                    <div class="form-group">
                                                        <label for="address">Datos de Entrega Guardados</label>
                                                        <div class="position-relative has-icon-left">
                                                            <select class="form-control" id="receiver_data" onchange="loadReceiverData();">
                                                            </select>
                                                            <div class="form-control-position">
                                                                <i class="feather icon-map-pin"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="receiver_name"> Nombre y Teléfono</label>
                                                        <input type="text" class="form-control" id="receiver" name="receiver">
                                                    </div>
                                                </div>

                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label for="sender_address_opc">Dirección como la conoces</label>
                                                        <input type="text" class="form-control" id="receiver_address_opc" name="receiver_address_opc">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="receiver_neighborhood">Barrio</label>
                                                        <input type="text" class="form-control required" id="receiver_neighborhood" name="receiver_neighborhood">
                                                    </div>
                                                </div>

                                                <div class="col-md-4" id="receiver_data_alias_div" style="visibility: hidden;">
                                                    <div class="form-group">
                                                        <label for="receiver_data_alias">Alias Datos de Entrega</label>
                                                        <input type="text" class="form-control" name="receiver_data_alias" id="receiver_data_alias">
                                                    </div>
                                                </div>
                            
                                                <div class="col-md-8 text-right">
                                                    <ul class="list-unstyled mb-0">
                                                        <li class="d-inline-block mr-2">
                                                            <fieldset>
                                                                <div class="vs-checkbox-con vs-checkbox-primary">
                                                                    <input type="checkbox" name="remember_receiver_data" id="remember_receiver_check" onclick="rememberCheck(2);" disabled>
                                                                    <span class="vs-checkbox">
                                                                        <span class="vs-checkbox--check">
                                                                            <i class="vs-icon feather icon-check"></i>
                                                                        </span>
                                                                    </span>
                                                                    <span class="">Recordar datos de entrega</span>
                                                                </div>
                                                            </fieldset>
                                                        </li>
                                                    </ul><br>
                                                </div>
                                            </div>
                                        </fieldset>

                                        <h6><i class="step-icon feather icon-briefcase"></i> Paso 3</h6>
                                        <fieldset>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="date">Fecha</label>
                                                        <input type="date" class="form-control required" id="date" name="date" min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="time">Hora <i class="fas fa-info-circle" data-toggle="tooltip" title="2 Horas de Anticipación"></i></label>
                                                        <input type="time" class="form-control required" id="time" name="time" value="{{ date('H:i') }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="article">Artículo <i class="fas fa-info-circle" data-toggle="tooltip" title="CANASTA: 40cm de ancho. 60cm de largo. 20cm de profundidad. (Máximo 18kg de peso). MALETA BREVE: 42cm de ancho. 38cm de largo. 50cm de profundidad. (Máximo 15kg de peso). MALETÍN CONVENCIONAL: 27cm de ancho. 14cm de largo. 40cm de profundidad. (Máximo 15kg de peso)."></i></label>
                                                        <input type="text" class="form-control" id="article" name="article">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="date">Equipo Breve</label>
                                                    <ul class="list-unstyled mb-0">
                                                        <li class="d-inline-block mr-2">
                                                            <fieldset>
                                                                <div class="vs-checkbox-con vs-checkbox-primary">
                                                                    <input type="checkbox" name="equipment_type[]" checked="" value="Maletin" required>
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
                                                                    <input type="checkbox" name="equipment_type[]" value="MB" required>
                                                                    <span class="vs-checkbox">
                                                                        <span class="vs-checkbox--check">
                                                                            <i class="vs-icon feather icon-check"></i>
                                                                        </span>
                                                                    </span>
                                                                    <span class="">Maleta (Especial para Domicilios)</span>
                                                                </div>
                                                            </fieldset>
                                                        </li>
                                                        <li class="d-inline-block mr-2">
                                                            <fieldset>
                                                                <div class="vs-checkbox-con vs-checkbox-primary">
                                                                    <input type="checkbox" name="equipment_type[]" value="Canasta" required>
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
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="payment_method">Método de Pago</label>
                                                        <select class="custom-select form-control" id="payment_method" name="payment_method" onchange="checkPaymentMethod();">
                                                            <option value="transferencia" checked>Transferencia</option>
                                                            <option value="efectivo">Efectivo</option>
                                                            <option value="reembolso">Contra Entrega</option>
                                                        </select>
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
                                                <div class="col-md-6" id="payment_type_div" style="display: none;">
                                                    <div class="form-group">
                                                        <label for="payment_type">El servicio se paga al:</label>
                                                        <select class="custom-select form-control" id="payment_type" name="payment_type">
                                                            <option value="inicio" checked>Inicio</option>
                                                            <option value="final">Final</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="observations">Observaciones</label>
                                                        <textarea name="observations" id="observations" rows="4" class="form-control"></textarea>
                                                    </div>
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
    
    @if (Auth::user()->financial == 1)
        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Ingresos y Ganancias</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div id="line-area-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

     {{-- Modal para Asignar un Brever a un Servicio --}}
    <div class="modal fade text-left" id="breverModal" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel1">Asignar Breve</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ route('admin.services.add-brever') }}" method="POST">
                    @csrf
                    <input type="hidden" name="home" value="1">
                    <input type="hidden" name="service_id" id="service_id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Seleccione el brever a asignar</label>
                            <div class="form-group">
                                <select class="select2 form-control" name="brever_id" id="brever_id">
                                    @foreach ($breves as $breve)
                                        <option value="{{ $breve->id }}">{{ $breve->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Asignar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection