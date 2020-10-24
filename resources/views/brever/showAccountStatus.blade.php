@extends('layouts.brever')

@push('scripts')
    <script>
        $('#myTable').DataTable( {
            dom: 'frtip',
            "order": [
                [0, 'desc']
            ],
            "pageLength": 25,
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
                        <h4 class="card-title">Estado de Cuenta Brever</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <div class="table-responsive">
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <form action="{{ route('brever.financial.account-status') }}" method="GET">
                                            <div class="row">
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="initial_date">Mes</label>
                                                        <div class="position-relative has-icon-left">
                                                            <select class="form-control" name="month" required>
                                                                <option value="">Seleccione un mes...</option>
                                                                <option value="01" @if (app('request')->input('month') == '01') selected @endif>Enero</option>
                                                                <option value="02" @if (app('request')->input('month') == '02') selected @endif>Febrero</option>
                                                                <option value="03" @if (app('request')->input('month') == '03') selected @endif>Marzo</option>
                                                                <option value="04" @if (app('request')->input('month') == '04') selected @endif>Abril</option>
                                                                <option value="05" @if (app('request')->input('month') == '05') selected @endif>Mayo</option>
                                                                <option value="06" @if (app('request')->input('month') == '06') selected @endif>Junio</option>
                                                                <option value="07" @if (app('request')->input('month') == '07') selected @endif>Julio</option>
                                                                <option value="08" @if (app('request')->input('month') == '08') selected @endif>Agosto</option>
                                                                <option value="09" @if (app('request')->input('month') == '09') selected @endif>Septiembre</option>
                                                                <option value="10" @if (app('request')->input('month') == '10') selected @endif>Octubre</option>
                                                                <option value="11" @if (app('request')->input('month') == '11') selected @endif>Noviembre</option>
                                                                <option value="12" @if (app('request')->input('month') == '12') selected @endif>Diciembre</option>
                                                            </select>
                                                            <div class="form-control-position">
                                                                <i class="feather icon-calendar"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="final_date">Año</label>
                                                        <div class="position-relative has-icon-left">
                                                           <select class="form-control" name="year" required>
                                                                <option value="">Seleccione un año...</option>
                                                                <option value="2020" @if (app('request')->input('year') == '2020') selected @endif>2020</option>
                                                                <option value="2021" @if (app('request')->input('year') == '2021') selected @endif>2021</option>
                                                                <option value="2022" @if (app('request')->input('year') == '2022') selected @endif>2022</option>
                                                                <option value="2023" @if (app('request')->input('year') == '2023') selected @endif>2023</option>
                                                                <option value="2024" @if (app('request')->input('year') == '2024') selected @endif>2024</option>
                                                                <option value="2025" @if (app('request')->input('year') == '2025') selected @endif>2025</option>
                                                            </select>
                                                            <div class="form-control-position">
                                                                <i class="feather icon-calendar"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-12 text-center">
                                                    <button type="submit" class="btn btn-primary waves-effect waves-light">Consultar</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <form action="{{ route('brever.financial.account-status') }}" method="GET">
                                            <div class="row">
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="initial_date">Fecha Inicial</label>
                                                        <div class="position-relative has-icon-left">
                                                            <input type="date" class="form-control" name="initial_date" value="{{ app('request')->input('initial_date')}}" required>
                                                            <div class="form-control-position">
                                                                <i class="feather icon-calendar"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="final_date">Año</label>
                                                        <div class="position-relative has-icon-left">
                                                            <input type="date" class="form-control" name="final_date" value="{{ app('request')->input('final_date')}}" required>
                                                            <div class="form-control-position">
                                                                <i class="feather icon-calendar"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-12 text-center">
                                                    <button type="submit" class="btn btn-primary waves-effect waves-light">Consultar</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

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
                                                <td>{{ date('Y-m-d', strtotime($movimiento->date)) }}</td>
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
                                                            <span style="color: green;">+${{ number_format($movimiento->brever_commission, 0, '.', ',') }}</span>
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