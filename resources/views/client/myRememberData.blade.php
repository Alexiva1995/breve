@extends('layouts.client')

@push('scripts')
    <script>
        $('#myTable').DataTable( {
            dom: 'frtip',
            "order": [
                [0, 'asc']
            ]
        });
        $('#myTable2').DataTable( {
            dom: 'frtip',
            "order": [
                [0, 'asc']
            ]
        });

        function loadModal($tipo){
            document.getElementById("type").value = $tipo;
            if ($tipo == 'sender'){
                document.getElementById("modal-title").innerHTML = "Crear Datos de Envío";
            }else{
                document.getElementById("modal-title").innerHTML = "Crear Datos de Recogida";
            }
            
            $("#newDataModal").modal("show");
        }

        function editData($id){
            //var path = "http://localhost:8000/edit-data/"+$id;
            var path = "https://www.breve.com.co/edit-data/"+$id;

            $.ajax({
                type:"GET",
                url:path,
                success:function(ans){
                    document.getElementById("data_id").value = ans.id;
                    document.getElementById("alias_update").value = ans.alias;
                    document.getElementById("identification_update").value = ans.identification;
                    document.getElementById("address_opc_update").value = ans.address_opc;
                    document.getElementById("neighborhood_update").value = ans.neighborhood;
                    if (ans.type == 'sender'){
                        document.getElementById("modal-title-update").innerHTML = "Actualizar Datos de Envío";
                    }else{
                        document.getElementById("modal-title-update").innerHTML = "Actualizar Datos de Recogida";
                    }
                    $("#updateDataModal").modal("show");
                } 
            }); 
        }

        function deleteData($id){
            document.getElementById("data_id_delete").value = $id;
            $("#deleteModal").modal("show");
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
                        <h4 class="card-title">Mis Datos de Envíos</h4>
                        <button class="btn btn-primary" href="javascript:;" onclick="loadModal('sender');" title="Nuevo Domicilio"><i class="feather icon-plus-circle"></i></button>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <div class="table-responsive">
                                 <table class="table" id="myTable">
                                    <thead>
                                        <tr>
                                            <th>Alias</th>
                                            <th>Nombre y Teléfono</th>
                                            <th>Barrio</th>
                                            <th>Dirección</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($datosEnvio as $datoEnvio)
                                            <tr>
                                                <td>{{ $datoEnvio->alias }}</td>
                                                <td>{{ $datoEnvio->identification }}</td>
                                                <td>{{ $datoEnvio->neighborhood }}</td>
                                                <td>{{ $datoEnvio->address_opc }}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a type="button" class="btn btn-outline-info" href="javascript:;" onclick="editData({{$datoEnvio->id}});"><i class="fa fa-edit"></i> Editar</a>
                                                        <a type="button" class="btn btn-outline-danger" href="javascript:;" onclick="deleteData({{$datoEnvio->id}});"><i class="fa fa-times"></i> Eliminar</a>
                                                    </div>
                                                </td>
                                           </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Alias</th>
                                            <th>Nombre y Teléfono</th>
                                            <th>Dirección</th>
                                            <th>Barrio</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Mis Datos de Recogida</h4>
                        <button class="btn btn-primary" href="javascript:;" onclick="loadModal('receiver');" title="Nuevos Datos de Recogida"><i class="feather icon-plus-circle"></i></button>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <div class="table-responsive">
                                 <table class="table" id="myTable2">
                                    <thead>
                                        <tr>
                                            <th>Alias</th>
                                            <th>Nombre y Teléfono</th>
                                            <th>Barrio</th>
                                            <th>Dirección</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($datosRecogida as $datoRecogida)
                                            <tr>
                                                <td>{{ $datoRecogida->alias }}</td>
                                                <td>{{ $datoRecogida->identification }}</td>
                                                <td>{{ $datoRecogida->neighborhood }}</td>
                                                <td>{{ $datoRecogida->address_opc }}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a type="button" class="btn btn-outline-info" href="javascript:;" onclick="editData({{$datoRecogida->id}});"><i class="fa fa-edit"></i> Editar</a>
                                                        <a type="button" class="btn btn-outline-danger" href="javascript:;" onclick="deleteData({{$datoRecogida->id}});"><i class="fa fa-times"></i> Eliminar</a>
                                                    </div>
                                                </td>
                                           </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Alias</th>
                                            <th>Nombre y Teléfono</th>
                                            <th>Dirección</th>
                                            <th>Barrio</th>
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

    <div class="modal fade text-left show" id="newDataModal" tabindex="-1" role="dialog" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('store-data') }}" method="POST">
                    @csrf
                    <input type="hidden" name="type" id="type">
                    <div class="modal-header bg-primary white">
                        <h5 class="modal-title" id="modal-title"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">                            
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="alias">Alias (*)</label>
                                    <div class="position-relative has-icon-left">
                                        <input type="text" class="form-control" name="alias" id="alias" required>
                                        <div class="form-control-position">
                                            <i class="feather icon-bookmark"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="identification">Nombre y Teléfono(*)</label>
                                    <div class="position-relative has-icon-left">
                                        <input type="text" class="form-control" name="identification" id="identification" required>
                                        <div class="form-control-position">
                                            <i class="fas fa-id-card"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                             <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="neighborhood">Barrio (*)</label>
                                    <div class="position-relative has-icon-left">
                                        <input type="text" class="form-control" id="neighborhood" name="neighborhood">
                                        <div class="form-control-position">
                                            <i class="feather icon-map-pin"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-12">
                                <div class="form-group">
                                    <label for="address_opc">Dirección </label>
                                    <div class="position-relative has-icon-left">
                                        <input type="text" class="form-control" name="address_opc" id="address_opc">
                                        <div class="form-control-position">
                                            <i class="feather icon-map-pin"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Guardar Datos</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade text-left show" id="updateDataModal" tabindex="-1" role="dialog" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('update-data') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="data_id">
                    <div class="modal-header bg-primary white">
                        <h5 class="modal-title" id="modal-title-update"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">                            
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="alias">Alias (*)</label>
                                    <div class="position-relative has-icon-left">
                                        <input type="text" class="form-control" name="alias" id="alias_update" required>
                                        <div class="form-control-position">
                                            <i class="feather icon-bookmark"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="identification_update">Nombre y Teléfono (*)</label>
                                    <div class="position-relative has-icon-left">
                                        <input type="text" class="form-control" name="identification" id="identification_update" required>
                                        <div class="form-control-position">
                                            <i class="fas fa-id-card"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                             <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="neighborhood">Barrio (*)</label>
                                    <div class="position-relative has-icon-left">
                                        <input type="text" class="form-control" id="neighborhood_update" name="neighborhood">
                                        <div class="form-control-position">
                                            <i class="feather icon-map-pin"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-12">
                                <div class="form-group">
                                    <label for="address_opc">Dirección </label>
                                    <div class="position-relative has-icon-left">
                                        <input type="text" class="form-control" name="address_opc" id="address_opc_update">
                                        <div class="form-control-position">
                                            <i class="feather icon-map-pin"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Guardar Datos</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

     <div class="modal fade text-left show" id="deleteModal" tabindex="-1" role="dialog" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <form method="POST" action="{{ route('delete-data') }}">
                @csrf
                <input type="hidden" name="id" id="data_id_delete">
                <div class="modal-content">
                    <div class="modal-header bg-success white">
                        <h5 class="modal-title" id="myModalLabel110">Elminar Datos</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        ¿Está seguro que desea eliminar estos datos?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger waves-effect waves-light" data-dismiss="modal">NO</button>
                        <button type="submit" class="btn btn-success waves-effect waves-light">SI</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection