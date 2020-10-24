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

        $('#myTable2').DataTable( {
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

    @if (Session::has('msj-erroneo'))
        <div class="col-md-12">
            <div class="alert alert-danger">
                <strong>{{ Session::get('msj-erroneo') }}</strong>
            </div>
        </div>
    @endif

	<section id="basic-datatable">
        <div class="row">
            <div class="col-md-12 text-center">
                <a href="javascript:;" data-toggle="modal" data-target="#transferModal" type="button" class="btn btn-icon btn-primary mr-1 mb-1 waves-effect waves-light"><i class="fas fa-people-arrows"></i> Nueva Transferencia</a>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Transferencias de Saldo Realizadas</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <div class="table-responsive">
                                <table class="table" id="myTable">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Hora</th>
                                            <th>Brever</th>
                                            <th>Monto</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($transferenciasRealizadas as $transferencia)
                                            <tr>
                                                <td>{{ date('Y-m-d', strtotime($transferencia->created_at)) }}</td>
                                                <td>{{ date('H:i', strtotime($transferencia->created_at)) }}</td>
                                                <td>{{ $transferencia->brever->name }}</td>
                                                <td>${{ $transferencia->amount }}</td>
                                           </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                             <th>Fecha</th>
                                            <th>Hora</th>
                                            <th>Brever</th>
                                            <th>Monto</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Transferencias de Saldo Recibidas</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <div class="table-responsive">
                                <table class="table" id="myTable2">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Hora</th>
                                            <th>Brever</th>
                                            <th>Monto</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($transferenciasRecibidas as $transferencia2)
                                            <tr>
                                                <td>{{ date('Y-m-d', strtotime($transferencia2->created_at)) }}</td>
                                                <td>{{ date('H:i', strtotime($transferencia2->created_at)) }}</td>
                                                <td>{{ $transferencia2->user->name }}</td>
                                                <td>${{ $transferencia->amount }}</td>
                                           </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                             <th>Fecha</th>
                                            <th>Hora</th>
                                            <th>Brever</th>
                                            <th>Monto</th>
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

    <div class="modal fade text-left show" id="transferModal" tabindex="-1" role="dialog" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <form action="{{ route('brever.financial.transfer') }}" method="POST">
                    @csrf
                    <input type="hidden" name="brever_id" id="brever">
                    <div class="modal-header bg-primary white">
                        <h5 class="modal-title" id="myModalLabel160">Transferir Saldo</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 col-12">
                                <div class="form-group">
                                    <label for="recharge_amount">Correo Electrónico del Brever</label>
                                    <div class="position-relative has-icon-left">
                                        <input type="email" class="form-control" name="brever_email" required>
                                        <div class="form-control-position">
                                            <i class="feather icon-user"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 col-12">
                                <div class="form-group">
                                    <label for="recharge_amount">Monto</label>
                                    <div class="position-relative has-icon-left">
                                        <input type="number" class="form-control" name="amount" required>
                                        <div class="form-control-position">
                                            <i class="feather icon-credit-card"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Transferir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection