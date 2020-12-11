@extends('layouts.brever')

@push('scripts')
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDX1cuvw-Xs_HC-1WjVtxv-18bLvKRu3Rw&callback=initMap&libraries=geometry,places" type="text/javascript"></script>
    <script>
        var coords = {};    //coordenadas obtenidas con la geolocalizaci贸n
        var miUbicacion = null;
        //Funcion principal Mapa Inicial
        initMap = function () {
            //usamos la API para geolocalizar el usuario
            navigator.geolocation.getCurrentPosition(
                function (position){
                    coords =  {
                        lng: position.coords.longitude,
                        lat: position.coords.latitude
                    };
                    document.getElementById("miLatitud").value = coords.lat;
                    document.getElementById("miLongitud").value = coords.lng;
                    geocoder = new google.maps.Geocoder();
                    geocoder.geocode({'latLng': coords}, function(results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            var address=results[0]['formatted_address'];
                            document.getElementById("miUbicacion").value = address.replace(/ /g, "+");
                        }
                    }); 
                },function(error){
                    console.log(error);
                }
            );
        }

        function showStartPoint(){
            //window.open('https://www.google.com/maps/dir/?api=1&origin='+document.getElementById("miUbicacion").value+'&destination='+document.getElementById("sender_address_plus").value+'&travelmode=driving');
            window.open('https://www.google.com/maps/dir/?api=1&origin='+document.getElementById("miLatitud").value+'%2C'+document.getElementById("miLongitud").value+'&destination='+document.getElementById("latitudOrigen").value+'%2C'+document.getElementById("longitudOrigen").value+'&travelmode=driving');
        }

        function showEndPoint(){
            //window.open('https://www.google.com/maps/dir/?api=1&origin='+document.getElementById("miUbicacion").value+'&destination='+document.getElementById("receiver_address_plus").value+'&travelmode=driving');
            window.open('https://www.google.com/maps/dir/?api=1&origin='+document.getElementById("miLatitud").value+'%2C'+document.getElementById("miLongitud").value+'&destination='+document.getElementById("latitudDestino").value+'%2C'+document.getElementById("longitudDestino").value+'&travelmode=driving');
        }

        function loadConfirmModal($servicio, $accion){
            document.getElementById("service_id").value = $servicio;
            if ($accion == 1){
                document.getElementById("confirm-form").setAttribute('action', ' {{ route('brever.services.arrive') }}');
                document.getElementById("confirm-text").innerHTML = "¿Está seguro de marcar la llegada al punto inicial?";
                document.getElementById("photo_div").style.display = 'none';
                $("#photo").prop('required', false);
            }else if($accion == 2){
                document.getElementById("confirm-form").setAttribute('action', '{{ route('brever.services.start') }}');
                document.getElementById("confirm-text").innerHTML = "¿Está seguro de iniciar este servicio?";
                document.getElementById("photo_div").style.display = 'none';
                $("#photo").prop('required', false);
            }else{
                document.getElementById("confirm-form").setAttribute('action', '{{ route('brever.services.complete') }}');
                document.getElementById("confirm-text").innerHTML = "¿Está seguro de completar este servicio?";
                document.getElementById("photo_div").style.display = 'block';
                $("#photo").prop('required', true);
            }
            $("#confirmModal").modal("show");
        }
    </script>
@endpush

@section('content')
    <input type="hidden" id="miLatitud">
    <input type="hidden" id="miLongitud">
    <input type="hidden" id="latitudOrigen" value="{{ $servicio->sender_latitude }}">
    <input type="hidden" id="longitudOrigen" value="{{ $servicio->sender_longitude }}">
    <input type="hidden" id="latitudDestino" value="{{ $servicio->receiver_latitude }}">
    <input type="hidden" id="longitudDestino" value="{{ $servicio->receiver_longitude }}">
    <!--<input type="hidden" id="miUbicacion">
    <input type="hidden" id="sender_address_plus" value="{{ $servicio->sender_address_plus }}">
    <input type="hidden" id="receiver_address_plus" value="{{ $servicio->receiver_address_plus }}">-->
	@if (Session::has('msj-exitoso'))
        <div class="col-md-12">
            <div class="alert alert-success">
                <strong>{{ Session::get('msj-exitoso') }}</strong>
            </div>
        </div>
    @endif

    <section id="nav-justified">
        <div id="prueba"></div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card overflow-hidden">
                    <div class="card-header">
                        <h4 class="card-title">Datos del Servicio #{{ $servicio->id }}</h4>
                        <div class="d-none d-sm-block">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <a type="button" class="btn btn-outline-warning" href="javascript:;" onclick="showStartPoint();"><i class="fas fa-route"></i> Ver Ruta de Recogida</a>
                                <a type="button" class="btn btn-outline-danger" href="javascript:;" onclick="showEndPoint();"><i class="fas fa-route"></i> Ver Ruta de Entrega</a>
                                <a type="button" class="btn btn-outline-success" href="{{ route('services.show-route', $servicio->id) }}"><i class="fas fa-route"></i> Ver Ruta Completa</a>
                            </div>
                        </div>
                        <div class="row d-block d-sm-none">
                            <div class="col-sm-12 col-12" style="padding-top: 10px; padding-bottom: 10px;">
                                <a type="button" class="btn btn-outline-warning" href="javascript:;" onclick="showStartPoint();"><i class="fas fa-route"></i> Ver Ruta de Recogida</a>
                            </div>
                            <div class="col-sm-12 col-12" style="padding-bottom: 10px;">
                                <a type="button" class="btn btn-outline-danger" href="javascript:;" onclick="showEndPoint();"><i class="fas fa-route"></i> Ver Ruta de Entrega</a>
                            </div>
                            <div class="col-sm-12 col-12" style="padding-bottom: 10px;">
                                <a type="button" class="btn btn-outline-success" href="{{ route('services.show-route', $servicio->id) }}"><i class="fas fa-route"></i> Ver Ruta Completa</a>
                            </div>
                        </div>
                        
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="row">
                            	<div class="col-md-6">
                            		<ul class="list-group">
                            			<a href="#" class="list-group-item active"><strong>Datos de Envío</strong></a>
                                        <li class="list-group-item d-flex">
                                            <p class="float-left mb-0">
                                                <i class="fas fa-id-card mr-1"></i>
                                            </p>
                                            <span>Nombre y teléfono de quién envía: <b>{{ $servicio->sender }}</b></span>
                                        </li>
                                        <li class="list-group-item d-flex">
                                            <p class="float-left mb-0">
                                                <i class="feather icon-map-pin mr-1"></i>
                                            </p>
                                            <span>Dirección: <b>{{ $servicio->sender_address_opc }}</b></span>
                                        </li>
                                        <li class="list-group-item d-flex">
                                            <p class="float-left mb-0">
                                                <i class="feather icon-map-pin mr-1"></i>
                                            </p>
                                            <span>Barrio: <b>{{ $servicio->sender_neighborhood }}</b></span>
                                        </li>
                                    </ul><br>
                            	</div>
                            	<div class="col-md-6">
                            		<ul class="list-group">
                            			<a href="#" class="list-group-item active"><strong>Datos de Recepción</strong></a>
                                        <li class="list-group-item d-flex">
                                            <p class="float-left mb-0">
                                                <i class="fas fa-id-card mr-1"></i>
                                            </p>
                                            <span>Nombre y teléfono: <b>{{ $servicio->receiver }}</b></span>
                                        </li>
                                        <li class="list-group-item d-flex">
                                            <p class="float-left mb-0">
                                                <i class="feather icon-map-pin mr-1"></i>
                                            </p>
                                            <span>Dirección cómo la conoces: <b>{{ $servicio->receiver_address_opc }}</b></span>
                                        </li>
                                        <li class="list-group-item d-flex">
                                            <p class="float-left mb-0">
                                                <i class="feather icon-map-pin mr-1"></i>
                                            </p>
                                            <span>Barrio: <b>{{ $servicio->receiver_neighborhood }}</b></span>
                                        </li>
                                    </ul><br>
                            	</div>
                            	
                            	<div class="col-md-6">
                            		<ul class="list-group">
                                        <a href="#" class="list-group-item active"><strong>Datos del Servicio</strong></a>
                                        <li class="list-group-item d-flex">
                                            <p class="float-left mb-0">
                                                <i class="feather icon-calendar mr-1"></i>
                                            </p>
                                            <span>Cliente: @if ($servicio->user_id == 0) <b>{{ $servicio->client_name }}</b> (No Registrado) @else @if (!is_null($servicio->user->tradename)) <b>{{ $servicio->user->tradename}}</b> @else <b>{{ $servicio->user->name }}</b> @endif @endif</span>
                                        </li>
                                        <li class="list-group-item d-flex">
                                            <p class="float-left mb-0">
                                                <i class="feather icon-calendar mr-1"></i>
                                            </p>
                                            <span>Fecha: <b>{{ date('d-m-Y', strtotime($servicio->date)) }}</b></span>
                                        </li>
                                        <li class="list-group-item d-flex">
                                            <p class="float-left mb-0">
                                                <i class="feather icon-clock mr-1"></i>
                                            </p>
                                            <span>Hora: <b>{{ date('H:i', strtotime($servicio->time)) }}</b></span>
                                        </li>
                                        <li class="list-group-item d-flex">
                                            <p class="float-left mb-0">
                                                <i class="feather icon-package mr-1"></i>
                                            </p>
                                            <span>Artículo: <b>{{ $servicio->article }}</b></span>
                                        </li>
                                        <li class="list-group-item d-flex">
                                            <p class="float-left mb-0">
                                                <i class="feather icon-file-text mr-1"></i>
                                            </p>
                                            <span>Observaciones: <b>{{ $servicio->observations }}</b></span>
                                        </li>
                                        <li class="list-group-item d-flex">
                                            <p class="float-left mb-0">
                                                <i class="feather icon-briefcase mr-1"></i>
                                            </p>
                                            <span>Tipo de Equipo: <b>{{ $servicio->equipment_type }}</b></span>
                                        </li>
                                        <li class="list-group-item d-flex">
	                                        <p class="float-left mb-0">
	                                            <i class="feather icon-disc mr-1"></i>
	                                        </p>
	                                        <span>Estado: <b>@if ($servicio->status == 0) Pendiente @elseif ($servicio->status == 1) Asignado @elseif ($servicio->status == 2) Iniciado @elseif ($servicio->status == 3) Confirmado @elseif ($servicio->status == 4) Completado @elseif ($servicio->status == 5) Declinado @else En Punto Inicial @endif</b></span>
	                                    </li>
                                    </ul><br>
                            	</div>
                            	<div class="col-md-6">
                            		<ul class="list-group">
                            			<a href="#" class="list-group-item active"><strong>Datos de Pago</strong></a>
                            			<li class="list-group-item d-flex">
                                            <p class="float-left mb-0">
                                                <i class="feather icon-watch mr-1"></i>
                                            </p>
                                            <span>Forma de Pago: <b>{{ $servicio->payment_type }}</b></span>
                                        </li>
                                        <li class="list-group-item d-flex">
                                            <p class="float-left mb-0">
                                                <i class="feather icon-credit-card mr-1"></i>
                                            </p>
                                            <span>Método de Pago: <b>@if ($servicio->payment_method == 'reembolso') Contra Entrega @else {{ $servicio->payment_method }} @endif</b></span>
                                        </li>
                                        @if ($servicio->payment_method == 'reembolso')
                                        	<li class="list-group-item d-flex">
	                                            <p class="float-left mb-0">
	                                                <i class="feather icon-credit-card mr-1"></i>
	                                            </p>
	                                            <span>Monto de Entrega: <b>${{ $servicio->refund_amount }}</b></span>
	                                        </li>
	                                    @endif
	                                    <li class="list-group-item d-flex">
                                            <p class="float-left mb-0">
                                                <i class="feather icon-tag mr-1"></i>
                                            </p>
                                            <span>Tarifa: <b>@if ($servicio->rate_status == 1) ${{ $servicio->rate }} @else Sin Calcular @endif</b></span>
                                        </li>
                                       	<li class="list-group-item d-flex">
                                            <p class="float-left mb-0">
                                                <i class="feather icon-plus-circle mr-1"></i>
                                            </p>
                                            <span>Costo Adicional: <b>${{ $servicio->additional_cost }}</b></span>
                                        </li>
                                        <li class="list-group-item d-flex">
                                            <p class="float-left mb-0">
                                                <i class="feather icon-tag mr-1"></i>
                                            </p>
                                            <span>Total: <b> @if ($servicio->rate_status == 1) ${{ $servicio->total }} @else Sin Calcular @endif</b></span>
                                        </li>
                            		</ul>
                            	</div>
                            </div>
                            <div class="col-md-12 text-right">
                                <br>
                                @if ($servicio->status  == 3)
                                    <a href="javascript:;" type="button" class="btn btn-icon btn-primary mr-1 mb-1 waves-effect waves-light" onclick="loadConfirmModal({{$servicio->id}},1);"><i class="feather icon-check"></i> Llegada a Punto Inicial</a>
                                @elseif ($servicio->status == 6)  
                                    <a href="javascript:;" type="button" class="btn btn-icon btn-primary mr-1 mb-1 waves-effect waves-light" onclick="loadConfirmModal({{$servicio->id}},2);"><i class="feather icon-check"></i> Iniciar Servicio</a>  
                                @elseif ($servicio->status == 2)
                                    <a href="javascript:;" type="button" class="btn btn-icon btn-primary mr-1 mb-1 waves-effect waves-light" onclick="loadConfirmModal({{$servicio->id}},3);"><i class="feather icon-check"></i> Entrega en Punto Final</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade text-left show" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel110" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <form method="POST" id="confirm-form" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="service_id" id="service_id">
                <div class="modal-content">
                    <div class="modal-header bg-success white">
                        <h5 class="modal-title" id="myModalLabel110">Actualizar Progreso de Servicio</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group" id="photo_div" style="display:none;">
                            <label for="photo">Foto de Entrega</label>
                            <input type="file" class="form-control" name="photo" id="photo" capture="camera" accept="image/*">
                        </div>
                        <div class="text-center" id="confirm-text"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger waves-effect waves-light" data-dismiss="modal">NO</button>
                        <button type="submit" class="btn btn-success waves-effect waves-light">SI</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection