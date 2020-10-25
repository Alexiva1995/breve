@extends('layouts.client')

@section('content')
	@if (Session::has('msj-exitoso'))
        <div class="col-md-12">
            <div class="alert alert-success">
                <strong>{{ Session::get('msj-exitoso') }}</strong>
            </div>
        </div>
    @endif

    <section id="nav-justified">
        <div class="row">
            <div class="col-sm-12">
                <div class="card overflow-hidden">
                    <div class="card-header">
                        <h4 class="card-title">Datos del Servicio #{{ $servicio->id }}</h4>
                        @if ($servicio->status < 4)
                        	<a href="{{ route('services.edit', $servicio->id) }}" type="button" class="btn btn-icon btn-outline-primary mr-1 mb-1 waves-effect waves-light"><i class="feather icon-edit"></i> Editar</a>
                        @endif
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
                                            <span>Nombre y teléfono: <b>{{ $servicio->sender }}</b></span>
                                        </li>
                                        <li class="list-group-item d-flex">
                                            <p class="float-left mb-0">
                                                <i class="feather icon-map-pin mr-1"></i>
                                            </p>
                                            <span>Dirección cómo la conoces: <b>{{ $servicio->sender_address_opc }}</b></span>
                                        </li>
                                        <li class="list-group-item d-flex">
                                            <p class="float-left mb-0">
                                                <i class="feather icon-map-pin mr-1"></i>
                                            </p>
                                            <span>Barrio: <b>{{ $servicio->sender_neighborhood }}</b></span>
                                        </li>
                                    </ul>
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
	                                        <span>Estado: <b>@if ($servicio->status == 0) Pendiente @elseif ($servicio->status == 1) Asignado @elseif ($servicio->status == 2) Iniciado @elseif ($servicio->status == 3) Confirmado @elseif ($servicio->status == 4) Completado @else Declinado @endif</b></span>
	                                    </li>
	                                    @if (!is_null($servicio->brever_id))
                                        	<li class="list-group-item d-flex">
	                                            <p class="float-left mb-0">
	                                                <i class="feather icon-user mr-1"></i>
	                                            </p>
	                                            <span>Brever Asignado: <b>{{ $servicio->brever->name }}</b></span>
	                                        </li>
                                        @endif
                                    </ul>
                            	</div>
                            	<div class="col-md-6">
                            		<ul class="list-group">
                            			<a href="#" class="list-group-item active"><strong>Datos de Pago</strong></a>
                            			@if ($servicio->payment_method == 'efectivo')
                                            <li class="list-group-item d-flex">
                                                <p class="float-left mb-0">
                                                    <i class="feather icon-watch mr-1"></i>
                                                </p>
                                                <span>El servicio se paga al: <b>{{ $servicio->payment_type }}</b></span>
                                            </li>
                                        @endif
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
                                            <span>Tarifa: <b> @if ($servicio->rate_status == 1) ${{ $servicio->rate }} @else Sin Calcular @endif</b></span>
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
                                    
                                    @if ( ($servicio->status == 4) && (!is_null($servicio->delivery_photo)) )
                                        <div class="text-center">
                                            <br>
                                            <a href="#photoModal" data-toggle="modal" type="button" class="btn btn-icon btn-primary mr-1 mb-1 waves-effect waves-light"><i class="feather icon-check"></i> Ver Foto de Entrega</a>
                                        </div>
                                    @endif
                            	</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade text-left show" id="photoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel110" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document"><div class="modal-content">
            <div class="modal-header bg-success white">
                <h5 class="modal-title" id="myModalLabel110">Foto de Entrega</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img src="{{ asset('images/services/'.$servicio->delivery_photo) }}" alt="" style="width: 250px; height: 250px;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect waves-light" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
@endsection