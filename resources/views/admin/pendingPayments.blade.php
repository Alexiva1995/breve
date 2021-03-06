@extends('layouts.admin')

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
                        <h4 class="card-title">Listado de Pagos Pendientes</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <div class="table-responsive">
                                <form action="{{ route('admin.financial.pending-payments') }}" method="GET">
                                    <div class="row">
                                        <div class="col-md-2 col-sm-12">
                                            Cliente
                                            <select class="form-control" name="client">
                                                <option value="" @if (app('request')->input('client') == "") selected @endif>Mostrar Todos</option>
                                                <option value="0" @if (app('request')->input('client') == "0") selected @endif>No Registrados</option>
                                                @foreach ($clientes as $cliente)
                                                    <option value="{{ $cliente->id }}" @if (app('request')->input('client') == $cliente->id) selected @endif>{{ $cliente->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2 col-sm-12">
                                            Brever
                                            <select class="form-control" name="brever">
                                                <option value="" @if (app('request')->input('brever') == "") selected @endif>Mostrar Todos</option>
                                                @foreach ($brevers as $brever)
                                                    <option value="{{ $brever->id }}" @if (app('request')->input('brever') == $brever->id) selected @endif>{{ $brever->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2 col-sm-12">
                                            Tarifa
                                            <input type="number" class="form-control" name="rate" value="{{ app('request')->input('rate') }}">
                                        </div>
                                        <div class="col-md-2 col-sm-12">
                                            Fecha
                                            <input type="date" class="form-control" name="date" value="{{ app('request')->input('date')}}">     
                                        </div>
                                        <div class="col-md-2 col-sm-12">
                                            Hora
                                            <input type="time" class="form-control" name="time" value="{{ app('request')->input('time')}}">
                                        </div>
                                        <div class="col-md-2 col-sm-12">
                                            Estado
                                            <select class="form-control" name="status">
                                                <option value="">Mostrar Todos</option>
                                                <option value="0">Pendiente</option>
                                                <option value="1">Asignado</option>
                                                <option value="2">Iniciado</option>
                                                <option value="3">Confirmado</option>
                                                <option value="4">Completado</option>
                                            </select>
                                            <br>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-6 text-right">
                                            <button class="btn btn-primary waves-effect waves-light" type="submit"><i class="fa fa-search"></i> Buscar</button>
                                        </div>   
                                        <div class="col-md-6 col-sm-6 col-6 text-left">
                                            <a class="btn btn-warning waves-effect waves-light" style="color: white;" href="{{ route('admin.financial.pending-payments') }}"><i class="fas fa-redo-alt"></i> Limpiar Filtros</a>
                                        </div> 
                                    </div>
                                </form>

                                <table class="table" id="myTable">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Hora</th>
                                            <th>Cliente</th>
                                            <th>Tarifa</th>
                                            <th>Descripción</th>
                                            <th>Brever Delegado</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($servicios as $servicio)
                                            <tr>
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
                                                <td>
                                                    @if ($servicio->rate_status == 1)
                                                        ${{ $servicio->rate +$servicio->additional_cost }}
                                                    @else
                                                        Sin Calcular
                                                    @endif
                                                </td>
                                                <td>{{ $servicio->sender_neighborhood }} - {{ $servicio->receiver_neighborhood }}</td>
                                                <td>
                                                    @if (!is_null($servicio->brever_id))
                                                        {{ $servicio->brever->name }}
                                                    @else
                                                        Sin Asignar
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($servicio->status == 0)
                                                        <i class="fa fa-circle font-small-3 text-warning mr-50"></i> Pendiente
                                                    @elseif ($servicio->status == 1)
                                                        <i class="fa fa-circle font-small-3 text-info mr-50"></i> Asignado
                                                    @elseif ($servicio->status == 2)
                                                        <i class="fa fa-circle font-small-3 text-primary mr-50"></i> Iniciado
                                                    @elseif ($servicio->status == 3)
                                                        <i class="fa fa-circle font-small-3 text-primary mr-50"></i> Confirmado
                                                    @elseif ($servicio->status == 4)
                                                        <i class="fa fa-circle font-small-3 text-success mr-50"></i> Completado
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group" role="group" aria-label="Basic example">
                                                        <a type="button" class="btn btn-outline-info" href="{{ route('admin.services.show', $servicio->id) }}"><i class="fa fa-search"></i> Ver Detalles</a>
                                                        <a type="button" class="btn btn-outline-primary" href="{{ route('admin.services.confirm-payment', $servicio->id) }}"><i class="fa fa-check"></i> Confirmar</a>
                                                    </div>
                                                </td>
                                           </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Hora</th>
                                            <th>Cliente</th>
                                            <th>Tarifa</th>
                                            <th>Descripción</th>
                                            <th>Brever Delegado</th>
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
@endsection