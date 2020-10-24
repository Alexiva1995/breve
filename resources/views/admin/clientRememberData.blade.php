@extends('layouts.admin')

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

        function editData($id){
            //var path = "http://localhost:8000/admin/users/edit-remember-data/"+$id;
            var path = "https://www.breve.com.co/breve2/admin/users/edit-remember-data/"+$id;

            $.ajax({
                type:"GET",
                url:path,
                success:function(ans){
                    document.getElementById("data_id").value = ans.id;
                    document.getElementById("alias").value = ans.alias;
                    document.getElementById("alias_admin").value = ans.alias_admin;
                    document.getElementById("identification").value = ans.identification
                    /*document.getElementById("name").value = ans.name;
                    document.getElementById("phone").value = ans.phone;*/
                    document.getElementById("address_opc").value = ans.address_opc;
                    document.getElementById("neighborhood").value = ans.neighborhood;
                    if (ans.type == 'sender'){
                        document.getElementById("modal-title-update").innerHTML = "Actualizar Datos de Envío";
                    }else{
                        document.getElementById("modal-title-update").innerHTML = "Actualizar Datos de Recogida";
                    }
                    $("#updateDataModal").modal("show");
                } 
            }); 
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
                        <h4 class="card-title">Datos Recordados de @if (!is_null($cliente->tradename)) {{ $cliente->tradename }} @else {{ $cliente->name }} @endif</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <div class="table-responsive">
                                 <table class="table" id="myTable">
                                    <thead>
                                        <tr>
                                            <th>Alias Cliente</th>
                                            <th>Alias Administrativo</th>
                                            <th>Nombre y Teléfono</th>
                                            <th>Barrio</th>
                                            <th>Dirección</th>
                                            <th>Tipo</th>
                                            <th>Administrativo</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($datos as $dato)
                                            <tr>
                                                <td>
                                                    @if (!is_null($dato->alias))
                                                        {{ $dato->alias }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (!is_null($dato->alias_admin))
                                                        {{ $dato->alias_admin }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>{{ $dato->identification }}</td>
                                                <td>{{ $dato->neighborhood }}</td>
                                                <td>{{ $dato->address_opc }}</td>
                                                <td>
                                                    @if ($dato->type == 'sender')
                                                        Envío
                                                    @else
                                                        Recogida
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($dato->admin == 0)
                                                        NO
                                                    @else
                                                        SI
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a type="button" class="btn btn-outline-info" href="javascript:;" onclick="editData({{$dato->id}});"><i class="fa fa-edit"></i> Editar</a>
                                                    </div>
                                                </td>
                                           </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Alias Cliente</th>
                                            <th>Alias Administrativo</th>
                                            <th>Nombre y Teléfono</th>
                                            <th>Barrio</th>
                                            <th>Dirección</th>
                                            <th>Tipo</th>
                                            <th>Administrativo</th>
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

    <div class="modal fade text-left show" id="updateDataModal" tabindex="-1" role="dialog" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.users.update-remember-data') }}" method="POST">
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
                                    <label for="alias">Alias Usuario</label>
                                    <div class="position-relative has-icon-left">
                                        <input type="text" class="form-control" name="alias" id="alias">
                                        <div class="form-control-position">
                                            <i class="feather icon-bookmark"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="alias">Alias Administrativo</label>
                                    <div class="position-relative has-icon-left">
                                        <input type="text" class="form-control" name="alias_admin" id="alias_admin">
                                        <div class="form-control-position">
                                            <i class="feather icon-bookmark"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="name">Nombre y Teléfono(*)</label>
                                    <div class="position-relative has-icon-left">
                                        <input type="text" class="form-control" name="identification" id="identification">
                                        <div class="form-control-position">
                                            <i class="feather icon-user"></i>
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
                                        <input type="text" class="form-control" name="address" id="address_opc">
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
@endsection