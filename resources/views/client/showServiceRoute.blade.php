@extends('layouts.client')

@push('styles')
	<style>
    	#mapa {
		    width: 100%;
		    height: 450px;
		}
    </style>
@endpush

@push('scripts')
	<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDX1cuvw-Xs_HC-1WjVtxv-18bLvKRu3Rw&callback=initMap&libraries=geometry,places" type="text/javascript"></script>
	<script>
		var map;
		var coords = {};    //coordenadas obtenidas con la geolocalizaci贸n

		//Funcion principal Mapa Inicial
        initMap = function () {
            //usamos la API para geolocalizar el usuario
            navigator.geolocation.getCurrentPosition(
                function (position){
                    setMapa(); 
                },function(error){
                    console.log(error);
                }
            );
        }
 
		function setMapa (){  
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
                    document.getElementById("distance").innerHTML = response.routes[0].legs[0].distance.text;
                }else {
                    document.getElementById("sender_address").value = "";
                    document.getElementById("receiver_address").value = "";
                    document.getElementById("distance").innerHTML = "0.0 Kms";
                    Swal.fire({
                       	title: "¡Ups!",
                        text: "Una de las direcciones ingresadas no se ha encontrado en el mapa.",
                        type: "error",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    });    //delete content
                }
            });
        }
	</script>
@endpush

@section('content')
    <div class="row">
        <div class="col-sm-12">
           	<div class="card overflow-hidden">
                <div class="card-header">
                    <h4 class="card-title">Mapa de la Ruta del Servicio #{{ $servicio->id }}</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                    	<div class="row">
                    		<div class="col-md-6">
                                <div class="form-group">
                                    <label for="sender_address">Dirección de Origen</label>
                                    <div class="position-relative has-icon-left">
                                        <input type="text" class="form-control required" id="sender_address" value="{{ $servicio->sender_address }}" disabled>
                                        <div class="form-control-position">
                                            <i class="feather icon-map-pin"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                        
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="receiver_address">Dirección de Destino</label>
                                    <div class="position-relative has-icon-left">
                                        <input type="text" class="form-control required" id="receiver_address" value="{{ $servicio->receiver_address }}" disabled>
                                        <div class="form-control-position">
                                            <i class="feather icon-map-pin"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                            	<div class="form-group">
                            		<div id="mapa"></div>
                            	</div>
                            </div>
                            
                            <div class="col-md-12 text-center">
                                <span id="distance" style="font-weight: bold;"></span>
                            </div>
                    	</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection