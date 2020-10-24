@extends('layouts.client')

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
                        <h4 class="card-title">Servicios Realizados</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <div class="table-responsive">
                                <table class="table zero-configuration" id="myTable">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Hora</th>
                                            <th>Descripción</th>
                                            <th>Tarifa</th>
                                            <th>Costo Adicional</th>
                                            <th>Total</th>
                                            <th>Brever</th>
                                            <th>Estado</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($servicios as $servicio)
                                            <tr>
                                                <td>{{ date('Y-m-d', strtotime($servicio->date)) }}</td>
                                                <td>{{ date('H:i', strtotime($servicio->time)) }}</td>
                                                <td>{{ $servicio->sender_neighborhood }} - {{ $servicio->receiver_neighborhood }}</td>
                                                <td>
                                                    @if ($servicio->rate_status == 1)
                                                        ${{ number_format($servicio->rate, 0, '.', ',') }}
                                                    @else
                                                        Sin Calcular
                                                    @endif
                                                </td>
                                                <td>${{ number_format($servicio->additional_cost, 0, '.', ',') }}</td>
                                                <td>
                                                    @if ($servicio->rate_status == 1)
                                                        ${{ number_format( ($servicio->rate + $servicio->additional_cost), 0, '.', ',') }}
                                                    @else
                                                        Sin Calcular
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (!is_null($servicio->brever_id))
                                                        {{ $servicio->brever->name }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($servicio->status == 4)
                                                        <i class="fa fa-circle font-small-3 text-success mr-50"></i> Completado
                                                    @else
                                                        <i class="fa fa-circle font-small-3 text-danger mr-50"></i> Declinado
                                                    @endif
                                                </td>
                                                <td>
                                                <a type="button" class="btn btn-outline-info" href="{{ route('services.show', $servicio->id) }}"><i class="fa fa-search"></i> Ver Detalles</a>
                                                </td>
                                           </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Hora</th>
                                            <th>Descripción</th>
                                            <th>Tarifa</th>
                                            <th>Costo Adicional</th>
                                            <th>Total</th>
                                            <th>Brever</th>
                                            <th>Estado</th>
                                            <th>Acción</th>
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