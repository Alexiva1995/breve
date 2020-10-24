@extends('layouts.admin')

@push('scripts')
    <script>
        $('#myTable').DataTable( {
            dom: 'frtip',
            "order": [
                [0, 'desc']
            ],
            "pageLength": 25,
            /*columnDefs: [ {
              targets: 0,
              render: $.fn.dataTable.moment('YYYY/MM/DD', 'DD-MM-YYYY')
            } ]*/
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
                        <h4 class="card-title">Estado de Cuenta Brever <b>{{ $brever->name }}</b></h4>
                        @if (is_null($mes))
                            <span>Desde el <b>{{ date('d/m/Y', strtotime($fechaInicial)) }}</b> hasta el <b>{{ date('d/m/Y', strtotime($fechaFinal)) }}</b></span>
                        @else
                            <span>Mes de <b>{{ $mes }}</b> del <b>{{ $ano }}</b></span>
                        @endif
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <div class="table-responsive">
                                <table class="table" id="myTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Fecha</th>
                                            <th>Hora</th>
                                            <th>Cliente</th>
                                            <th>Servicio</th>
                                            <th>Valor Total</th>
                                            <th>Descripción del Servicio</th>
                                            <th>Recibido Por</th>
                                            <th>Comisión</th>
                                            <th>Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($movimientos as $movimiento)
                                            <tr>
                                                <td>{{ $movimiento->id }}</td>
                                                <td>{{ date('Y-m-d', strtotime($movimiento->created_at)) }}</td>
                                                <td>
                                                    @if ($movimiento->service_id != 0)
                                                        {{ date('H:i', strtotime($movimiento->service->time)) }}
                                                    @else
                                                        {{ date('H:i', strtotime($movimiento->created_at)) }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($movimiento->service_id != 0)
                                                        @if ($movimiento->service->user_id == 0)
                                                            {{ $movimiento->service->client_name }} (No Registrado)
                                                        @else
                                                            @if (!is_null($movimiento->service->user->tradename))
                                                                {{ $movimiento->service->user->tradename }}
                                                            @else
                                                                {{ $movimiento->service->user->name }}
                                                            @endif
                                                        @endif
                                                    @else
                                                        @if ( (!is_null($movimiento->transfer_id)) || ($movimiento->type == 'Recarga') )
                                                            -
                                                        @else
                                                            BREVE
                                                        @endif
                                                    @endif
                                                </td>
                                                <td>{{ $movimiento->type }}</td>
                                                <td>
                                                    @if ($movimiento->service_id != 0)
                                                        ${{ number_format(($movimiento->service->rate + $movimiento->service->additional_cost), 0, '.', ',') }}
                                                    @else
                                                        $0
                                                    @endif
                                                </td>
                                                <td>
                                                   @if ($movimiento->service_id != 0)
                                                        {{ $movimiento->service->sender_neighborhood }} - {{ $movimiento->service->receiver_neighborhood }}
                                                    @else
                                                        {{ $movimiento->description }}
                                                    @endif
                                                </td>
                                                @if ($movimiento->service_id != 0)
                                                    @if ($movimiento->service->payment_method == 'transferencia')
                                                        <td style="background-color: #4caf50; color: black;"><center>BREVE</center></td>
                                                    @else
                                                        <td style="background-color: #70DC74; color: black;"><center>BREVER</center></td>
                                                    @endif
                                                @else
                                                    <td style="background-color: #4caf50; color: black;"><center>BREVE</center></td>
                                                @endif
                                                <td>
                                                    @if ($movimiento->service_id != 0)
                                                        @if ($movimiento->service->payment_method == 'transferencia')
                                                            <span style="color: green;">+${{ number_format($movimiento->brever_commission, 0, '.', ',') }}</span>
                                                        @else
                                                            <span style="color: red;">-${{ number_format($movimiento->breve_commission, 0, '.', ',') }}</span>
                                                        @endif
                                                    @else
                                                        @if ($movimiento->type == 'Recarga')
                                                            <span style="color: green;">+${{ number_format($movimiento->brever_commission, 0, '.', ',') }}</span>
                                                        @elseif ($movimiento->type == 'Descuento')
                                                            <span style="color: red;">-${{ number_format($movimiento->breve_commission, 0, '.', ',') }}</span>
                                                        @elseif ($movimiento->type == 'Transferencia de Saldo (Débito)')
                                                            <span style="color: red;">-${{ number_format($movimiento->brever_commission, 0, '.', ',') }}</span>
                                                        @elseif ($movimiento->type == 'Transferencia de Saldo (Crédito)')
                                                            <span style="color: green;">${{ number_format($movimiento->brever_commission, 0, '.', ',') }}</span>
                                                        @endif
                                                    @endif
                                                </td>
                                                <td @if ($movimiento->brever_balance < 0) style="color: red;" @else style="color: green;" @endif>${{ number_format($movimiento->brever_balance, 0, '.', ',') }}</td>
                                           </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>#</th>
                                            <th>Fecha</th>
                                            <th>Hora</th>
                                            <th>Cliente</th>
                                            <th>Servicio</th>
                                            <th>Valor Total</th>
                                            <th>Descripción del Servicio</th>
                                            <th>Recibido Por</th>
                                            <th>Comisión</th>
                                            <th>Balance</th>
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