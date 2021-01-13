@extends('layouts.brever')

@push('scripts')
    <script>
        $('#myTable').DataTable( {
            dom: 'frtip',
            "order": [
                [0, 'asc']
            ]
        });

        function loadModal($servicio){
            document.getElementById("service_id").value = $servicio;
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
                        <h4 class="card-title">Listado de Servicios Disponibles</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <div class="table-responsive">
                                <table class="table" id="myTable">
                                    <thead>
                                        <tr>
                                            <th>Hora</th>
                                            <th>Descripción</th>
                                            <th>Equipamiento</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($serviciosInmediatos as $servicioInmediato)
                                            <tr>
                                                <td>De Inmediato</td>
                                                <td>{{ $servicioInmediato->sender_neighborhood }} - {{ $servicioInmediato->receiver_neighborhood }}</td>
                                                <td>{{ $servicioInmediato->equipment_type }}</td>
                                                <td>
                                                    <div class="btn-group" role="group" aria-label="Basic example">
                                                        <a type="button" class="btn btn-outline-success" onclick="loadModal({{$servicioInmediato->id}});" style="color: green;"><i class="far fa-arrow-alt-circle-right"></i> Tomar Sevicio</a>
                                                    </div>
                                                </td>
                                           </tr>
                                        @endforeach
                                        @foreach ($servicios as $servicio)
                                            <tr>
                                                <td>{{ date('H:i', strtotime($servicio->time)) }}</td>
                                                <td>{{ $servicio->sender_neighborhood }} - {{ $servicio->receiver_neighborhood }}</td>
                                                <td>{{ $servicio->equipment_type }}</td>
                                                <td>
                                                    <div class="btn-group" role="group" aria-label="Basic example">
                                                        <a type="button" class="btn btn-outline-success" onclick="loadModal({{$servicio->id}});" style="color: green;"><i class="far fa-arrow-alt-circle-right"></i> Tomar Sevicio</a>
                                                    </div>
                                                </td>
                                           </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Hora</th>
                                            <th>Descripción</th>
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

    <div class="modal fade text-left show" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel110" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <form action="{{ route('brever.services.take') }}" method="POST">
                @csrf
                <input type="hidden" name="service_id" id="service_id">
                <div class="modal-content">
                    <div class="modal-header bg-success white">
                        <h5 class="modal-title" id="myModalLabel110">Tomar Servicio</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        ¿Está seguro de tomar este servicio?
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