@extends('layouts.client')

@push('scripts')
    <script>
        $('#myTable').DataTable( {
            dom: 'frtip',
            "order": [
                [2, 'desc']
            ],
            columnDefs: [ {
              targets: 0,
              render: $.fn.dataTable.moment('YYYY/MM/DD', 'DD-MM-YYYY')
            } ]
        });

        function loadConfirmModal($servicio){
            document.getElementById("confirm-link").setAttribute('href', 'services/cancel/'+$servicio);
            $("#confirmModal").modal("show");
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
                        <h4 class="card-title">Mis Servicios</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <div class="table-responsive">
                                <table class="table" id="myTable">
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
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($servicios as $servicio)
                                            <tr>
                                                <td>{{ date('Y-m-d', strtotime($servicio->date)) }}</td>
                                                <td>
                                                    @if ($servicio->immediately == 1 && is_null($servicio->time))
                                                        <strong style="color: #EA5455">Inmediato</strong>
                                                    @else
                                                        {{ date('H:i', strtotime($servicio->time)) }}
                                                    @endif
                                                </td>
                                                <td>{{ $servicio->sender_neighborhood }} - {{ $servicio->receiver_neighborhood }}</td>
                                                <td>
                                                    @if ($servicio->rate_status == 1)
                                                        ${{ number_format($servicio->rate, 0, '.', ',') }}
                                                    @else
                                                        Sin Calcular
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (!is_null($servicio->additional_cost))
                                                        {{ number_format($servicio->additional_cost, 0, '.', ',') }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($servicio->rate_status == 1)
                                                        ${{ number_format( ($servicio->rate + $servicio->additional_cost), 0, '.', ',') }}
                                                    @else
                                                        Sin Calcular
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($servicio->status == 0)
                                                        -
                                                    @else
                                                        {{ $servicio->brever->name }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($servicio->status == 0)
                                                        <i class="fa fa-circle font-small-3 text-danger mr-50"></i> Pendiente
                                                    @elseif ($servicio->status == 1)
                                                        <i class="fa fa-circle font-small-3 text-info mr-50"></i> Asignado
                                                    @elseif ($servicio->status == 2)
                                                        <i class="fa fa-circle font-small-3 text-info mr-50"></i> Iniciado
                                                    @else
                                                        <i class="fa fa-circle font-small-3 text-success mr-50"></i> Confirmado
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a type="button" class="btn btn-outline-info" href="{{ route('services.show', $servicio->id) }}"><i class="fa fa-search"></i> Ver Detalles</a>
                                                        <a type="button" class="btn btn-outline-success" href="{{ route('services.show-route', $servicio->id) }}"><i class="fas fa-route"></i> Ver Ruta</a>
                                                        @if ($servicio->status != 2)
                                                            <a type="button" class="btn btn-outline-danger" href="javascript:;" onclick="loadConfirmModal({{$servicio->id}});"><i class="fa fa-times"></i> Cancelar</a>
                                                        @endif
                                                    </div>
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

    <div class="modal fade text-left show" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel110" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <form method="GET" id="confirm-form">
                <div class="modal-content">
                    <div class="modal-header bg-success white">
                        <h5 class="modal-title" id="myModalLabel110">Cancelar Servicio</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        ¿Está seguro que desea cancelar el servicio?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger waves-effect waves-light" data-dismiss="modal">NO</button>
                        <a type="button" class="btn btn-success waves-effect waves-light" id="confirm-link">SI</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
