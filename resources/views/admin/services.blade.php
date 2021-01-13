@extends('layouts.admin')

@push('scripts')
    <script>
        $('#myTable').DataTable( {
            dom: 'frtip',
            "order": [
                [1, 'asc']
            ],
            columnDefs: [ {
              targets: 0,
              render: $.fn.dataTable.moment('YYYY/MM/DD', 'DD-MM-YYYY')
            } ]
        });

        function asignarBrever($servicio){
            $('#service_id').val($servicio);
            $("#breverModal").modal('show');
        }

        function copiar($servicio){   
            let copyText = document.getElementById('div_copy_'+$servicio).innerText
            const textArea = document.createElement('textarea');
            textArea.textContent = copyText;
            document.body.append(textArea);      
            textArea.select();      
            document.execCommand("copy");    
            textArea.remove();

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
                        <h4 class="card-title">Listado de Servicios Pendientes</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <form action="{{ route('admin.services') }}" method="GET">
                                <div class="row">
                                    <div class="col-md-3 col-sm-12">
                                       Cliente
                                        <select class="form-control" name="client">
                                            <option value="" @if (app('request')->input('client') == "") selected @endif>Mostrar Todos</option>
                                            <option value="0" @if (app('request')->input('client') == "0") selected @endif>No Registrados</option>
                                            @foreach ($clientes as $cliente)
                                                <option value="{{ $cliente->id }}" @if (app('request')->input('client') == $cliente->id) selected @endif>{{ $cliente->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3 col-sm-12">
                                        Fecha
                                        <input type="date" class="form-control" name="date" value="{{ app('request')->input('date')}}">
                                    </div>

                                    <div class="col-md-3 col-sm-12">
                                        Hora
                                        <input type="time" class="form-control" name="time" value="{{ app('request')->input('time')}}">
                                    </div>

                                    <div class="col-md-3 col-sm-12">
                                        Tarifa
                                        <input type="number" class="form-control" name="rate" value="{{ app('request')->input('rate')}}">
                                        <br>
                                    </div>

                                    <div class="col-md-6 col-sm-6 col-6 text-right">
                                        <button class="btn btn-primary waves-effect waves-light" type="submit"><i class="fa fa-search"></i> Buscar</button>
                                    </div>   
                                    <div class="col-md-6 col-sm-6 col-6 text-left">
                                        <a class="btn btn-warning waves-effect waves-light" style="color: white;" href="{{ route('admin.services') }}"><i class="fas fa-redo-alt"></i> Limpiar Filtros</a>
                                    </div>   
                                </div><br>
                            </form>
                            
                            <div class="table-responsive">
                                <table class="table" id="myTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Fecha</th>
                                            <th>Hora</th>
                                            <th>Cliente</th>
                                            <th>Tarifa</th>
                                            <th>Descripción</th>
                                            <th>Brever Delegado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($servicios as $servicio)
                                            <tr>
                                                <td>{{ $servicio->id }}</td>
                                                <td>@if (!is_null($servicio->date)) {{ date('Y-m-d', strtotime($servicio->date)) }} @else 0000-00-00 (Inmediato) @endif</td>
                                                <td>@if (!is_null($servicio->time)) {{ date('H:i', strtotime($servicio->time)) }} @else 00:00 (Inmediato) @endif</td>
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
                                                        ${{ $servicio->rate + $servicio->additional_cost }}
                                                    @else  
                                                        Sin Calcular
                                                    @endif
                                                </td>
                                                <td>{{ $servicio->sender_neighborhood }} - {{ $servicio->receiver_neighborhood }}</td>
                                                <td>
                                                    <a type="button" class="btn btn-outline-success" href="javascript:;" onclick="asignarBrever({{$servicio->id}});"><i class="fa fa-user-plus"></i> Asignar Brever</a>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group" role="group" aria-label="Basic example">
                                                        <a type="button" class="btn btn-outline-info" href="{{ route('admin.services.show', $servicio->id) }}"><i class="fa fa-search"></i> Ver Detalles</a>
                                                        <a type="button" class="btn btn-outline-primary" href="{{ route('services.show-route', $servicio->id) }}"><i class="fas fa-route"></i> Ver Ruta</a>
                                                        <a type="button" class="btn btn-outline-primary" href="javascript:;" onclick="copiar({{$servicio->id}});"><i class="far fa-copy"></i> Copiar</a>
                                                        <div id="div_copy_{{$servicio->id}}" style="display: none;">
                                                            Dirección Inicio: {{ $servicio->sender_neighborhood}}<br>
                                                            Nombre de quién envía: {{ $servicio->sender_name}}<br>
                                                            {{ $servicio->observations }}<br>
                                                            Artículo a transportar: {{ $servicio->article }}<br>
                                                            Hora de recogida: {{ date('H:i', strtotime($servicio->time)) }}<br><br>

                                                            Dirección Final: {{ $servicio->receiver_neighborhood}}<br>
                                                            Nombre y número de destinatario: {{ $servicio->receiver_name }} Celular: {{ $servicio->receiver_phone }}<br>
                                                            El servicio lo pagan en: @if ($servicio->payment_type == 'inicio') {{ $servicio->sender_neighborhood }} / {{ $servicio->sender_name }} @else {{ $servicio->receiver_neighborhood }} / {{ $servicio->receiver_name }} @endif <br>
                                                            Tarifa *${{ $servicio->total }}*<br>
                                                            Desplazamiento: https://www.breve.com.co/services/show-route/{{$servicio->id}}
                                                        </div>
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
                    <input type="hidden" name="service_id" id="service_id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Seleccione el brever a asignar</label>
                            <div class="form-group">
                                <select class="select2 form-control" name="brever_id">
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