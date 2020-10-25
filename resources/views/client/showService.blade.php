@extends('layouts.client')

@push('styles')
    <style>
        .container-list-data-brever {
            text-align: center;
            padding: 0;
        }
        div.list-data-brever.first{
            border-top-color: transparent;
            border-top-style: none;
            border-top-width: 0;
        }
        div.list-data-brever:last-child{
            border-bottom-left-radius: 0;
            border-bottom-color: transparent;
            border-bottom-style: none;
            border-bottom-width: 0;
            border-bottom-right-radius: 0.5rem;
        }
        .avatar-brever {
            float: left;
            padding: 0.75rem 2.25rem;
        }
        .list-data-brever {
            border-right-color: transparent;
            border-right-style: none;
            border-right-width: 0;
        }
        .round_{
            border-radius: 5.5rem;
        }
    </style>
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
                                </div>
                                
                                @if (!is_null($servicio->brever_id) && $servicio->status < 4)
                                    <div class="col-md-12 mt-2">
                                        <ul class="list-group">
                                            <a href="#" class="list-group-item active"><strong>Datos del brever asignado</strong></a>
                                            <li class="list-group-item container-list-data-brever">
                                                <span class="avatar-brever">
                                                    @if (is_null($servicio->brever->avatar))
                                                        <img class="round" src="{{ asset('themeforest/app-assets/images/portrait/small/avatar.png') }}" alt="avatar" width="107" height="107">
                                                    @else
                                                        <img class="round_" src="{{ asset('images/users/'.$servicio->brever->avatar) }}" alt="avatar" width="107" height="107">
                                                    @endif
                                                </span>  
                                                <div class="list-group-item d-flex list-data-brever first">
                                                    <p class="float-left mb-0">
                                                        <i class="feather icon-credit-card mr-1"></i>
                                                    </p>
                                                    <span>Nombre y Apellido: <b>{{ $servicio->brever->name }}</b></span>
                                                </div>
                                                <div class="list-group-item d-flex list-data-brever">
                                                    <p class="float-left mb-0">
                                                        <i class="feather icon-tag mr-1"></i>
                                                    </p>
                                                    <span>Teléfono: <b> @if ($servicio->brever->phone) {{ $servicio->brever->phone }} @else Sin número telefónico @endif </b></span>
                                                </div>
                                                <div class="list-group-item d-flex list-data-brever">
                                                    <p class="float-left mb-0">
                                                        <i class="feather icon-credit-card mr-1"></i>
                                                    </p>
                                                    <span>Licencia: <b> @if ($servicio->brever->license_plate) {{ $servicio->brever->license_plate }} @else Sin licencia @endif </b></span>
                                                </div>
                                            </li>  
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection