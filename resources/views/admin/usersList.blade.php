@extends('layouts.admin')

@push('scripts')
    <script>
        $('#myTable').DataTable( {
            dom: 'frtip',
            "order": [
                [4, 'asc']
            ]
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
                        <h4 class="card-title">Listado de {{ $tipo_usuario }}</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <div class="table-responsive">
                                <table class="table" id="myTable">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre</th>
                                            @if ($tipo_usuario == 'Clientes')
                                                <th>Nombre Comercial</th>
                                            @endif
                                            <th>Correo Electrónico</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($usuarios as $usuario)
                                            <tr>
                                                <td>{{ $usuario->id }}</td>
                                                <td>{{ $usuario->name }}</td>
                                                @if ($tipo_usuario == 'Clientes')
                                                    <td>{{ $usuario->tradename }}</td>
                                                @endif
                                                <td>{{ $usuario->email }}</td>
                                                <td>@if ($usuario->status == 0) Inhabilitado @elseif ($usuario->status == 1) Habilitado @else Esperando Aprobación @endif</td>
                                                <td class="text-center">
                                                    <div class="btn-group" role="group">
                                                        <a type="button" class="btn btn-outline-info" href="{{ route('admin.users.edit', $usuario->id) }}"><i class="fa fa-edit"> Editar</i></a>
                                                        @if ($tipo_usuario == 'Clientes')
                                                            <a type="button" class="btn btn-outline-info" href="{{ route('admin.users.show-remember-data', $usuario->id) }}"><i class="fa fa-list"></i> Datos Recordados</a>
                                                        @endif
                                                        @if ($tipo_usuario == 'Brevers')
                                                            @if ($usuario->vip == 0)
                                                                <a type="button" class="btn btn-outline-success" href="{{ route('admin.users.make-vip', [$usuario->id, 1]) }}"><i class="fa fa-star"> Asignar VIP</i></a>
                                                            @else
                                                                <a type="button" class="btn btn-outline-warning" href="{{ route('admin.users.make-vip', [$usuario->id, 0]) }}"><i class="fa fa-star"> Quitar VIP</i></a>
                                                            @endif
                                                        @endif
                                                        @if ($usuario->status == 0)
                                                            <a type="button" class="btn btn-outline-primary" href="{{ route('admin.users.change-status', [$usuario->id, 1]) }}"><i class="fa fa-check"> Habilitar</i></a>
                                                        @else
                                                            <a type="button" class="btn btn-outline-warning" href="{{ route('admin.users.change-status', [$usuario->id, 0]) }}"><i class="fa fa-times"> Inhabilitar</i></a>
                                                        @endif
                                                        @if ($usuario->status == 2)
                                                            <a type="button" class="btn btn-outline-primary" href="{{ route('admin.users.change-status', [$usuario->id, 1]) }}"><i class="fa fa-check"> Aprobar</i></a>
                                                        @endif
                                                        @if ($tipo_usuario == 'Administradores')
                                                            <a type="button" class="btn btn-outline-danger" href="{{ route('admin.users.delete', $usuario->id) }}"><i class="fa fa-times"> Eliminar</i></a>
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
@endsection