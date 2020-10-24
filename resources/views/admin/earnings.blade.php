@extends('layouts.admin')

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
                        <h4 class="card-title">Listado de Ganancias</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <div class="table-responsive">
                                <table class="table" id="myTable">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Hora</th>
                                            <th>Cliente</th>
                                            <th>Valor Total</th>
                                            <th>Descripción del Servicio</th>
                                            <th>Ganancia</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ganancias as $ganancia)
                                            <tr>
                                                <td>{{ date('Y-m-d', strtotime($ganancia->service->date)) }}</td>
                                                <td>{{ date('H:i', strtotime($ganancia->service->time)) }}</td>
                                                <td>
                                                    @if ($ganancia->service->user_id == 0)
                                                        No Registrado
                                                    @else
                                                        @if (!is_null($ganancia->service->user->tradename)) 
                                                            {{ $ganancia->service->user->tradename }} 
                                                        @else 
                                                            {{ $ganancia->service->user->name }}
                                                        @endif
                                                    @endif
                                                </td>
                                                <td>${{ number_format(($ganancia->service->rate +$ganancia->service->additional_cost), 0, '.', ',') }}</td>
                                                <td>{{ $ganancia->service->sender_neighborhood }} - {{ $ganancia->service->receiver_neighborhood }}</td>
                                                <td style="color: green;">${{ number_format($ganancia->breve_commission, 0, '.', ',') }}</td>
                                           </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Hora</th>
                                            <th>Cliente</th>
                                            <th>Valor Total</th>
                                            <th>Descripción del Servicio</th>
                                            <th>Ganancia</th>
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