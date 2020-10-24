@extends('layouts.admin')

@push('scripts')
    <script>
        $('#myTable').DataTable( {
            dom: 'frtip',
            "order": [
                [1, 'asc']
            ]
        });

        function loadModal($brever){
            document.getElementById("brever_id").value = $brever;
            document.getElementById("brever_id2").value = $brever;
            $("#datesModal").modal("show");
        }

        function loadRechargeModal($brever){
            document.getElementById("brever").value = $brever;
            $("#rechargeModal").modal("show");
        }

        function loadDiscountModal($brever){
            document.getElementById("brever2").value = $brever;
            $("#discountModal").modal("show");
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

     @if (Session::has('msj-erroneo'))
        <div class="col-md-12">
            <div class="alert alert-danger">
                <strong>{{ Session::get('msj-erroneo') }}</strong>
            </div>
        </div>
    @endif

	<section id="basic-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Listado de Brevers</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <div class="table-responsive">
                                <table class="table" id="myTable">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre</th>
                                            <th>Correo Electrónico</th>
                                            <th>Saldo</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($brevers as $brever)
                                            <tr>
                                                <td>{{ $brever->id }}</td>
                                                <td>{{ $brever->name }}</td>
                                                <td>{{ $brever->email }}</td>
                                                <td @if ($brever->balance < 0) style="color: red;" @elseif ($brever->balance > 0) style="color: green;" @else style="color: orange;" @endif>${{ number_format($brever->balance, 0, '.', ',') }}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        @if ($opcion == 'Estado de Cuenta')
                                                            <a type="button" class="btn btn-outline-success" href="javascript:;" onclick="loadModal({{$brever->id}});"><i class="feather icon-file-text"></i> Ver Estado de Cuenta</a>
                                                        @else
                                                            <a type="button" class="btn btn-outline-success" href="javascript:;" onclick="loadRechargeModal({{$brever->id}});"><i class="feather icon-rotate-cw"></i> Recargar</a>
                                                            <a type="button" class="btn btn-outline-danger" href="javascript:;" onclick="loadDiscountModal({{$brever->id}});"><i class="feather icon-rotate-ccw"></i> Descontar</a>
                                                        @endif
                                                    </div>
                                                </td>
                                           </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre</th>
                                            <th>Correo Electrónico</th>
                                            <th>Saldo</th>
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

    <div class="modal fade text-left show" id="datesModal" tabindex="-1" role="dialog" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary white">
                    <h5 class="modal-title" id="myModalLabel160">Fechas del Estado de Cuenta</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.financial.show-account-status') }}" method="POST">
                        @csrf
                        <input type="hidden" name="brever_id" id="brever_id">
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="initial_date">Mes</label>
                                    <div class="position-relative has-icon-left">
                                        <select class="form-control" name="month">
                                            <option value="01">Enero</option>
                                            <option value="02">Febrero</option>
                                            <option value="03">Marzo</option>
                                            <option value="04">Abril</option>
                                            <option value="05">Mayo</option>
                                            <option value="06">Junio</option>
                                            <option value="07">Julio</option>
                                            <option value="08">Agosto</option>
                                            <option value="09">Septiembre</option>
                                            <option value="10">Octubre</option>
                                            <option value="11">Noviembre</option>
                                            <option value="12">Diciembre</option>
                                        </select>
                                        <div class="form-control-position">
                                            <i class="feather icon-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="final_date">Año</label>
                                    <div class="position-relative has-icon-left">
                                       <select class="form-control" name="year">
                                            <option value="2020">2020</option>
                                            <option value="2021">2021</option>
                                            <option value="2022">2022</option>
                                            <option value="2023">2023</option>
                                            <option value="2024">2024</option>
                                            <option value="2025">2025</option>
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

                    <br>

                    <form action="{{ route('admin.financial.show-account-status') }}" method="POST"> 
                        @csrf
                        <input type="hidden" name="brever_id2" id="brever_id2">
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="initial_date">Fecha Inicial</label>
                                    <div class="position-relative has-icon-left">
                                        <input type="date" id="initial_date" class="form-control" name="initial_date">
                                        <div class="form-control-position">
                                            <i class="feather icon-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="final_date">Fecha Final</label>
                                    <div class="position-relative has-icon-left">
                                        <input type="date" id="final_date" class="form-control" name="final_date">
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
        </div>
    </div>

    <div class="modal fade text-left show" id="rechargeModal" tabindex="-1" role="dialog" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.financial.recharger') }}" method="POST">
                    @csrf
                    <input type="hidden" name="brever_id" id="brever">
                    <div class="modal-header bg-primary white">
                        <h5 class="modal-title" id="myModalLabel160">Recargar Saldo</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 col-12">
                                <div class="form-group">
                                    <label for="recharge_amount">Saldo a Recargar</label>
                                    <div class="position-relative has-icon-left">
                                        <input type="number" id="recharge_amount" class="form-control" name="recharge_amount">
                                        <div class="form-control-position">
                                            <i class="feather icon-credit-card"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 col-12">
                                <div class="form-group">
                                    <label for="recharge_amount">Descripción</label>
                                    <div class="position-relative has-icon-left">
                                        <textarea id="description" class="form-control" name="description" required></textarea>
                                        <div class="form-control-position">
                                            <i class="feather icon-file-text"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Recargar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade text-left show" id="discountModal" tabindex="-1" role="dialog" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.financial.discount') }}" method="POST">
                    @csrf
                    <input type="hidden" name="brever_id" id="brever2">
                    <div class="modal-header bg-primary white">
                        <h5 class="modal-title" id="myModalLabel160">Descontar Saldo</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 col-12">
                                <div class="form-group">
                                    <label for="recharge_amount">Saldo a Descontar</label>
                                    <div class="position-relative has-icon-left">
                                        <input type="number" id="discount_amount" class="form-control" name="discount_amount">
                                        <div class="form-control-position">
                                            <i class="feather icon-credit-card"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 col-12">
                                <div class="form-group">
                                    <label for="recharge_amount">Descripción</label>
                                    <div class="position-relative has-icon-left">
                                        <textarea id="description" class="form-control" name="description" required></textarea>
                                        <div class="form-control-position">
                                            <i class="feather icon-file-text"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Descontar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection