@extends('layouts.client')

@push('styles')
    <style>
        #mapa {
            width: 100%;
            height: 300px;
        }
    </style>
@endpush


@push('scripts')
    <script>
        $('#myTable').DataTable( {
            dom: 'frtip',
            "order": [
                [0, 'asc']
            ],
            columnDefs: [ {
              targets: 0,
              render: $.fn.dataTable.moment('YYYY/MM/DD', 'DD-MM-YYYY')
            } ]
        });
    </script>

    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDX1cuvw-Xs_HC-1WjVtxv-18bLvKRu3Rw&callback=initMap&libraries=geometry,places" type="text/javascript"></script>

    <script>
        var map;
        var coords = {};
        var marker;   
        var marker2; 
        var geocoder;
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

            var p1 = new google.maps.LatLng(document.getElementById("sender_latitude").value, document.getElementById("sender_longitude").value);
            var p2 = new google.maps.LatLng(document.getElementById("receiver_latitude").value, document.getElementById("receiver_longitude").value);
            
            var distance = getDistance(p1, p2);
            var rate = getRate(distance);
            document.getElementById("distance").innerHTML = distance+" Kilómetros.";
            document.getElementById("rate").value = rate;
            document.getElementById("rate_span").innerHTML = "Tarifa: $"+rate;

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
                var p1 = new google.maps.LatLng(this.getPosition().lat(),this.getPosition().lng());
                var p2 = new google.maps.LatLng(document.getElementById("receiver_latitude").value, document.getElementById("receiver_longitude").value);
            
                var distance = getDistance(p1, p2);
                var rate = getRate(distance);
                document.getElementById("distance").innerHTML = distance+" Kilómetros.";
                document.getElementById("rate").value = rate;
                document.getElementById("rate_span").innerHTML = "Tarifa: $"+rate;
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
                var p1 = new google.maps.LatLng(document.getElementById("sender_latitude").value, document.getElementById("sender_longitude").value);
                var p2 = new google.maps.LatLng(this.getPosition().lat(),this.getPosition().lng());

                var distance = getDistance(p1, p2);
                var rate = getRate(distance);
                document.getElementById("distance").innerHTML = distance+" Kilómetros.";
                document.getElementById("rate").value = rate;
                document.getElementById("rate_span").innerHTML = "Tarifa: $"+rate;
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

        function getDistance(p1, p2) {
          return (google.maps.geometry.spherical.computeDistanceBetween(p1, p2) / 1000).toFixed(2);
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

        function enviarForm(){
            if (document.getElementById("check_sender").value == "0"){
                Swal.fire({
                    title: "¡Ups!",
                    text: "La dirección de origen no se encuentra en los límites del servicio. (Solo disponible en Cali)",
                    type: "error",
                    confirmButtonClass: 'btn btn-primary',
                    buttonsStyling: false,
                }); 
            }else if(document.getElementById("check_receiver").value == "0"){
                Swal.fire({
                    title: "¡Ups!",
                    text: "La dirección de destino no se encuentra en los límites del servicio. (Solo disponible en Cali)",
                    type: "error",
                    confirmButtonClass: 'btn btn-primary',
                    buttonsStyling: false,
                });   
            }else{
                var name = document.getElementById("name").value;
                var sender_address = document.getElementById("sender_address_aux").value;
                var receiver_address = document.getElementById("receiver_address_aux").value;
                var sender_neighborhood = document.getElementById("sender_neighborhood").value;
                var receiver_neighborhood = document.getElementById("receiver_neighborhood").value;
                if( name == null || name.length == 0 || /^\s+$/.test(name) ) {
                    Swal.fire({
                        title: "¡Ups!",
                        text: "Debe colocar un nombre a su domicilio.",
                        type: "error",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    });   
                }else if( sender_address == null || sender_address.length == 0 || /^\s+$/.test(sender_address) ) {
                    Swal.fire({
                        title: "¡Ups!",
                        text: "Debe colocar una dirección de origen a su domicilio.",
                        type: "error",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    });   
                }else if( receiver_address == null || receiver_address.length == 0 || /^\s+$/.test(receiver_address) ) {
                    Swal.fire({
                        title: "¡Ups!",
                        text: "Debe colocar una dirección de destino a su domicilio.",
                        type: "error",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    });   
                }else if( sender_neighborhood == null || sender_neighborhood.length == 0 || /^\s+$/.test(sender_neighborhood) ) {
                    Swal.fire({
                        title: "¡Ups!",
                        text: "Debe colocar un barrio de origen a su domicilio.",
                        type: "error",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    });   
                }else if( receiver_neighborhood == null || receiver_neighborhood.length == 0 || /^\s+$/.test(receiver_neighborhood) ) {
                    Swal.fire({
                        title: "¡Ups!",
                        text: "Debe colocar un barrio de destino a su domicilio.",
                        type: "error",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    });   
                }else{
                    document.addressForm.submit();
                }
                
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

    <section id="basic-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Mis Domicilios</h4>
                        <button class="btn btn-primary" href="javascript:;" data-target="#newAddressModal" data-toggle="modal" title="Nuevo Domicilio"><i class="feather icon-plus-circle"></i></button>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <div class="table-responsive">
                                 <table class="table" id="myTable">
                                    <thead>
                                        <tr>
                                            <th>Identificador</th>
                                            <th>Descripción</th>
                                            <th>Dirección Inicial</th>
                                            <th>Dirección Final</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($domicilios as $domicilio)
                                            <tr>
                                                <td>{{ $domicilio->name }}</td>
                                                <td>{{ $domicilio->sender_neighborhood }} - {{ $domicilio->receiver_neighborhood }}</td>
                                                <td>{{ $domicilio->sender_address }}</td>
                                                <td>{{ $domicilio->receiver_address }}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a type="button" class="btn btn-outline-danger" href="{{ route('delete-address', $domicilio->id) }}"><i class="fa fa-times"></i> Eliminar</a>
                                                    </div>
                                                </td>
                                           </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Identificador</th>
                                            <th>Descripción</th>
                                            <th>Dirección Inicial</th>
                                            <th>Dirección Final</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade text-left show" id="newAddressModal" tabindex="-1" role="dialog" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('store-address') }}" method="POST" name="addressForm">
                    @csrf
                    <input type="hidden" id="check_sender" value="1">
                    <input type="hidden" id="check_receiver" value="1">
                    <input type="hidden" name="user_id" id="user_id" value="{{ Auth::user()->id }}">
                    <input type="hidden" name="sender_latitude" id="sender_latitude">
                    <input type="hidden" name="sender_longitude" id="sender_longitude">
                    <input type="hidden" name="receiver_latitude" id="receiver_latitude">
                    <input type="hidden" name="receiver_longitude" id="receiver_longitude">
                    <div class="modal-header bg-primary white">
                        <h5 class="modal-title" id="myModalLabel160">Crear Nuevo Domicilio</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 col-12">
                                <div class="form-group">
                                    <div id="mapa"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <span id="distance" style="font-weight: bold;">0.0 Kilómetros</span>
                            </div>
                            <div class="col-md-6 text-right">
                                <input type="hidden" class="form-control" name="rate" id="rate">
                                <span id="rate_span" style="font-weight: bold;">Tarifa: $0.0</span>
                            </div><br>
                            <div class="col-md-12 col-12">
                                <div class="form-group">
                                    <label for="name">Nombre Identificador (*)</label>
                                    <div class="position-relative has-icon-left">
                                        <input type="text" id="name" class="form-control" name="name" required>
                                        <div class="form-control-position">
                                            <i class="feather icon-bookmark"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-12">
                                <div class="form-group">
                                    <label for="sender_address">Dirección de Origen (*)</label>
                                    <div class="position-relative has-icon-left">
                                        <input type="hidden" id="sender_address" name="sender_address">
                                        <input type="text" id="sender_address_aux" class="form-control" required>
                                        <div class="form-control-position">
                                            <i class="feather icon-map-pin"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-12">
                                <div class="form-group">
                                    <label for="receiver_address">Dirección de Destino (*)</label>
                                    <div class="position-relative has-icon-left">
                                        <input type="hidden" id="receiver_address" name="receiver_address">
                                        <input type="text" id="receiver_address_aux" class="form-control" required>
                                        <div class="form-control-position">
                                            <i class="feather icon-map-pin"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="sender_neighborhood">Barrio de Origen (*)</label>
                                    <div class="position-relative has-icon-left">
                                        <input type="text" id="sender_neighborhood" class="form-control" name="sender_neighborhood">
                                        <div class="form-control-position">
                                            <i class="feather icon-map-pin"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="receiver_neighborhood">Barrio de Destino (*)</label>
                                    <div class="position-relative has-icon-left">
                                        <input type="text" id="receiver_neighborhood" class="form-control" name="receiver_neighborhood">
                                        <div class="form-control-position">
                                            <i class="feather icon-map-pin"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="javascript:;" class="btn btn-primary waves-effect waves-light" onclick="enviarForm();">Guardar Domicilio</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection