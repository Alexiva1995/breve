@extends('layouts.brever')

@push('scripts')
    <script>
        $('#myTable').DataTable( {
            dom: 'frtip',
            "order": [
                [0, 'desc']
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
                        <h4 class="card-title">Listado de Servicios Declinados</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <div class="table-responsive">
                                <table class="table zero-configuration" id="myTable">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Hora</th>
                                            <th>Cliente</th>
                                            <th>Tarifa</th>
                                            <th>Descripción</th>
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
                                                            {{ $servicio->user->tradename }}
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