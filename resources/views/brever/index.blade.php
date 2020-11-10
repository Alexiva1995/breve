@extends('layouts.brever')

@push('scripts')
    <script>
        $(window).on("load", function(){

            var datosServiciosAsignados = <?php echo json_encode($datosGraficoServiciosAsignados);?>;
            var datosServiciosCompletados = <?php echo json_encode($datosGraficoServiciosCompletados);?>;

            /*** Card Servicios Asignados ***/
            var gainedChartoptions2 = {
                chart: {
                    height: 100,
                    type: 'area',
                    toolbar: {
                        show: false,
                    },
                    sparkline: {
                        enabled: true
                    },
                    grid: {
                        show: false,
                        padding: {
                            left: 0,
                            right: 0
                        }
                    },
                },
                colors: ['#1a5d1c'],
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 2.5
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 0.9,
                        opacityFrom: 0.7,
                        opacityTo: 0.5,
                        stops: [0, 80, 100]
                    }
                },
                series: [{
                    name: 'Servicios',
                    data: datosServiciosAsignados
                }],

                xaxis: {
                    labels: {
                        show: false,
                    },
                    axisBorder: {
                        show: false,
                    }
                },
                yaxis: [{
                    y: 0,
                    offsetX: 0,
                    offsetY: 0,
                    padding: { left: 0, right: 0 },
                }],
                tooltip: {
                    x: { show: false }
                },
            }

            var gainedChart2 = new ApexCharts(
                document.querySelector("#servicios-asignados"),
                gainedChartoptions2
            );
            gainedChart2.render();
            var gainedChart22 = new ApexCharts(
                document.querySelector("#servicios-asignados2"),
                gainedChartoptions2
            );
            gainedChart22.render();

            /*** Card Servicios Completados ***/
            var gainedChartoptions3 = {
                chart: {
                    height: 100,
                    type: 'area',
                    toolbar: {
                        show: false,
                    },
                    sparkline: {
                        enabled: true
                    },
                    grid: {
                        show: false,
                        padding: {
                            left: 0,
                            right: 0
                        }
                    },
                },
                colors: ['#4caf50'],
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 2.5
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 0.9,
                        opacityFrom: 0.7,
                        opacityTo: 0.5,
                        stops: [0, 80, 100]
                    }
                },
                series: [{
                    name: 'Servicios',
                    data: datosServiciosCompletados
                }],

                xaxis: {
                    labels: {
                        show: false,
                    },
                    axisBorder: {
                        show: false,
                    }
                },
                yaxis: [{
                    y: 0,
                    offsetX: 0,
                    offsetY: 0,
                    padding: { left: 0, right: 0 },
                }],
                tooltip: {
                    x: { show: false }
                },
            }

            var gainedChart3 = new ApexCharts(
                document.querySelector("#servicios-completados"),
                gainedChartoptions3
            );
            gainedChart3.render();
            var gainedChart33 = new ApexCharts(
                document.querySelector("#servicios-completados2"),
                gainedChartoptions3
            );
            gainedChart33.render();

            //Gráfico de Ganancias
            var fechas = <?php echo json_encode($datosGraficoGananciasFecha);?>;
            var montos = <?php echo json_encode($datosGraficoGananciasMonto);?>;

            var grid_line_color = '#dae1e7';
            var $primary = '#7367F0';
            var $success = '#28C76F';
            var $danger = '#EA5455';
            var $warning = '#FF9F43';
            var themeColors = [$primary, $success, $danger, $warning, $primary, $success, $danger, $warning, $primary, $success, $danger, $warning];

            var horizontalChartctx = $("#ganancias");

            var horizontalchartOptions = {
                // Elements options apply to all of the options unless overridden in a dataset
                // In this case, we are setting the border of each horizontal bar to be 2px wide
                elements: {
                    rectangle: {
                        borderWidth: 2,
                        borderSkipped: 'right',
                        borderSkipped: 'top',
                    }
                },
                responsive: true,
                maintainAspectRatio: false,
                responsiveAnimationDuration: 500,
                legend: {
                    display: false,
                },
                scales: {
                    xAxes: [{
                        display: true,
                        gridLines: {
                          color: grid_line_color,
                        },
                        scaleLabel: {
                          display: true,
                        }
                    }],
                    yAxes: [{
                        display: true,
                        gridLines: {
                            color: grid_line_color,
                        },
                        scaleLabel: {
                            display: true,
                        }
                    }]
                },
                title: {
                    display: true,
                    text: 'Mis Ganancias 2020'
                }
            };

            // Chart Data
            var horizontalchartData = {
                labels: fechas,
                datasets: [{
                    label: "Total",
                    data: montos,
                    backgroundColor: themeColors,
                    borderColor: "transparent"
                }]
            };

            var horizontalChartconfig = {
                type: 'horizontalBar',

                // Chart Options
                options: horizontalchartOptions,

                data: horizontalchartData
            };

            // Create the chart
            var horizontalChart = new Chart(horizontalChartctx, horizontalChartconfig);
        });

        function loadConfirmModal($servicio, $accion){
            document.getElementById("service_id").value = $servicio;
            if ($accion == 1){
                document.getElementById("confirm-form").setAttribute('action', ' {{ route('brever.services.start') }}');
                document.getElementById("confirm-text").innerHTML = "¿Está seguro de marcar la llegada al punto inicial?";
                document.getElementById("photo_div").style.display = 'none';
                $("#photo").prop('required', false);
            }else if($accion == 2){
                document.getElementById("confirm-form").setAttribute('action', '{{ route('brever.services.confirm') }}');
                document.getElementById("confirm-text").innerHTML = "¿Está seguro de iniciar este servicio?";
                document.getElementById("photo_div").style.display = 'none';
                $("#photo").prop('required', false);
            }else{
                document.getElementById("confirm-form").setAttribute('action', '{{ route('brever.services.complete') }}');
                document.getElementById("confirm-text").innerHTML = "¿Está seguro de completar este servicio?";
                document.getElementById("photo_div").style.display = 'block';
                $("#photo").prop('required', true);
            }
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

    <section id="dashboard-analytics">
        <div class="d-none d-md-block">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <a href="{{ route('brever.financial.account-status') }}">
                        <div class="card text-white" @if (Auth::user()->balance >= 0) style="background-color: #4caf50;" @else style="background-color: #EA5455;" @endif>
                            <div class="card-content">
                                <div class="card-body text-center">
                                    <div class="avatar avatar-xl shadow mt-0" @if (Auth::user()->balance >= 0) style="background-color: #70DC74;" @else style="background-color: red;" @endif>
                                        <div class="avatar-content">
                                            <i class="fas fa-wallet white font-large-1"></i>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <h1 class="mb-2 text-white">${{ number_format(Auth::user()->balance, 0, '.', ',') }}</h1>
                                        <p class="m-auto w-75">Saldo en Billetera</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-sm-6 col-12 d-none d-sm-block">
                    <a href="{{ route('brever.services.assigned') }}">
                        <div class="card">
                            <div class="card-header d-flex flex-column align-items-start pb-0">
                                <div class="avatar p-50 m-0" style="background-color: #70DC74;">
                                    <div class="avatar-content">
                                        <i class="feather icon-user-plus font-medium-5"></i>
                                    </div>
                                </div>
                                <h2 class="text-bold-700 mt-1 mb-25">{{ $cantServiciosAsignados }}</h2>
                                <p class="mb-0">Servicios Asignados</p>
                            </div>
                            <div class="card-content">
                                <div id="servicios-asignados"></div>
                            </div>
                        </div>
                    </a>
                </div> 
                <div class="col-lg-3 col-sm-6 col-12 d-none d-sm-block">
                    <a href="{{ route('brever.services.completed') }}">
                        <div class="card">
                            <div class="card-header d-flex flex-column align-items-start pb-0">
                                <div class="avatar p-50 m-0" style="background-color: #4caf50;">
                                    <div class="avatar-content">
                                        <i class="feather icon-user-check font-medium-5"></i>
                                    </div>
                                </div>
                                <h2 class="text-bold-700 mt-1 mb-25">{{ $cantServiciosCompletados }}</h2>
                                <p class="mb-0">Servicios Completados</p>
                            </div>
                            <div class="card-content">
                                <div id="servicios-completados"></div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <div class="row d-block d-md-none">
            <div class="col-sm-12 col-sm-12">
                <a href="{{ route('brever.financial.account-status') }}">
                    <div class="card text-white" @if (Auth::user()->balance >= 0) style="background-color: #4caf50;" @else style="background-color: #EA5455;" @endif>
                        <div class="card-content">
                            <div class="card-body text-center">
                                <div class="avatar avatar-xl shadow mt-0" @if (Auth::user()->balance >= 0) style="background-color: #70DC74;" @else style="background-color: red;" @endif>
                                    <div class="avatar-content">
                                        <i class="fas fa-wallet white font-large-1"></i>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <h1 class="mb-2 text-white">${{ number_format(Auth::user()->balance, 0, '.', ',') }}</h1>
                                    <p class="m-auto w-75">Saldo en Billetera</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Mis Servicios Asignados</h4>
                    </div>
                    <div class="card-content">
                        <!-- VERSIÓN PC -->
                        <div class="table-responsive mt-1 d-none d-sm-block">
                            <table class="table table-hover-animation mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-center">Fecha</th>
                                        <th class="text-center">Hora</th>
                                        <th class="text-center">Punto Inicial</th>
                                        <th class="text-center">Punto Final</th>
                                        <th class="text-center">Equipamiento</th>
                                        <th class="text-center">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($cantServiciosAsignados > 0)
                                        @foreach ($serviciosAsignados as $servicioAsignado)
                                            <tr>
                                                <td class="text-center">{{ date('Y-m-d', strtotime($servicioAsignado->date)) }}</td>
                                                <td class="text-center">{{ date('H:i', strtotime($servicioAsignado->time)) }}</td>
                                                <td class="text-center">{{ $servicioAsignado->sender_neighborhood }}</td>
                                                <td class="text-center">{{ $servicioAsignado->receiver_neighborhood }}</td>
                                                <td class="text-center">{{ $servicioAsignado->equipment_type }}</td>
                                                <td class="text-center">
                                                    <a data-toggle="collapse" href="#collapse-{{$servicioAsignado->id}}"><i class="far fa-caret-square-down" style="font-size: 22px;"></i></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="6">
                                                    <div id="collapse-{{$servicioAsignado->id}}" class="collapse">
                                                        <div class="row">
                                                            <div class="col-12 col-md-4">
                                                                <ul class="list-group">
                                                                    <a href="#" class="list-group-item"><strong>Datos de Envío y Recogida</strong></a>
                                                                    <li class="list-group-item d-flex">
                                                                        <p class="float-left mb-0">
                                                                            <i class="fas fa-id-card mr-1"></i>
                                                                        </p>
                                                                        <span>Nombre y teléfono de quién envía: <b>{{ $servicioAsignado->sender }}</b></span>
                                                                    </li>
                                                                    <li class="list-group-item d-flex">
                                                                        <p class="float-left mb-0">
                                                                            <i class="feather icon-map-pin mr-1"></i>
                                                                        </p>
                                                                        <span>Dirección de quién envía: <b>{{ $servicioAsignado->sender_address_opc }}</b></span>
                                                                    </li>
                                                                    <li class="list-group-item d-flex">
                                                                        <p class="float-left mb-0">
                                                                            <i class="feather icon-map-pin mr-1"></i>
                                                                        </p>
                                                                        <span>Barrio de quién envía: <b>{{ $servicioAsignado->sender_neighborhood }}</b></span>
                                                                    </li>
                                                                    <li class="list-group-item d-flex">
                                                                        <p class="float-left mb-0">
                                                                            <i class="fas fa-id-card mr-1"></i>
                                                                        </p>
                                                                        <span>Nombre y teléfono de quién recibe: <b>{{ $servicioAsignado->receiver }}</b></span>
                                                                    </li>
                                                                    <li class="list-group-item d-flex">
                                                                        <p class="float-left mb-0">
                                                                            <i class="feather icon-map-pin mr-1"></i>
                                                                        </p>
                                                                        <span>Dirección de quién recibe: <b>{{ $servicioAsignado->receiver_address_opc }}</b></span>
                                                                    </li>
                                                                    <li class="list-group-item d-flex">
                                                                        <p class="float-left mb-0">
                                                                            <i class="feather icon-map-pin mr-1"></i>
                                                                        </p>
                                                                        <span>Barrio de quién recibe: <b>{{ $servicioAsignado->receiver_neighborhood }}</b></span>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <div class="col-12 col-md-4">
                                                                <ul class="list-group">
                                                                    <a href="#" class="list-group-item"><strong>Datos del Servicio</strong></a>
                                                                    <li class="list-group-item d-flex">
                                                                        <p class="float-left mb-0">
                                                                            <i class="feather icon-calendar mr-1"></i>
                                                                        </p>
                                                                        <span>Cliente: @if ($servicioAsignado->user_id == 0) <b>{{ $servicioAsignado->client_name }}</b> (No Registrado) @else @if (!is_null($servicioAsignado->user->tradename)) <b>{{ $servicioAsignado->user->tradename}}</b> @else <b>{{ $servicioAsignado->user->name }}</b> @endif @endif</span>
                                                                    </li>
                                                                    <li class="list-group-item d-flex">
                                                                        <p class="float-left mb-0">
                                                                            <i class="feather icon-calendar mr-1"></i>
                                                                        </p>
                                                                        <span>Fecha: <b>{{ date('d-m-Y', strtotime($servicioAsignado->date)) }}</b></span>
                                                                    </li>
                                                                    <li class="list-group-item d-flex">
                                                                        <p class="float-left mb-0">
                                                                            <i class="feather icon-clock mr-1"></i>
                                                                        </p>
                                                                        <span>Hora: <b>{{ date('H:i', strtotime($servicioAsignado->time)) }}</b></span>
                                                                    </li>
                                                                    <li class="list-group-item d-flex">
                                                                        <p class="float-left mb-0">
                                                                            <i class="feather icon-package mr-1"></i>
                                                                        </p>
                                                                        <span>Artículo: <b>{{ $servicioAsignado->article }}</b></span>
                                                                    </li>
                                                                    <li class="list-group-item d-flex">
                                                                        <p class="float-left mb-0">
                                                                            <i class="feather icon-file-text mr-1"></i>
                                                                        </p>
                                                                        <span>Observaciones: <b>{{ $servicioAsignado->observations }}</b></span>
                                                                    </li>
                                                                    <li class="list-group-item d-flex">
                                                                        <p class="float-left mb-0">
                                                                            <i class="feather icon-briefcase mr-1"></i>
                                                                        </p>
                                                                        <span>Tipo de Equipo: <b>{{ $servicioAsignado->equipment_type }}</b></span>
                                                                    </li>
                                                                    <li class="list-group-item d-flex">
                                                                        <p class="float-left mb-0">
                                                                            <i class="feather icon-disc mr-1"></i>
                                                                        </p>
                                                                        <span>Estado: <b>@if ($servicioAsignado->status == 0) Pendiente @elseif ($servicioAsignado->status == 1) Asignado @elseif ($servicioAsignado->status == 2) Iniciado @elseif ($servicioAsignado->status == 3) Confirmado @elseif ($servicioAsignado->status == 4) Completado @else Declinado @endif</b></span>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <div class="col-12 col-md-4">
                                                                <ul class="list-group">
                                                                    <a href="#" class="list-group-item"><strong>Datos de Pago</strong></a>
                                                                    <li class="list-group-item d-flex">
                                                                        <p class="float-left mb-0">
                                                                            <i class="feather icon-watch mr-1"></i>
                                                                        </p>
                                                                        <span>Forma de Pago: <b>{{ $servicioAsignado->payment_type }}</b></span>
                                                                    </li>
                                                                    <li class="list-group-item d-flex">
                                                                        <p class="float-left mb-0">
                                                                            <i class="feather icon-credit-card mr-1"></i>
                                                                        </p>
                                                                        <span>Método de Pago: <b>@if ($servicioAsignado->payment_method == 'reembolso') Contra Entrega @else {{ $servicioAsignado->payment_method }} @endif</b></span>
                                                                    </li>
                                                                    @if ($servicioAsignado->payment_method == 'reembolso')
                                                                        <li class="list-group-item d-flex">
                                                                            <p class="float-left mb-0">
                                                                                <i class="feather icon-credit-card mr-1"></i>
                                                                            </p>
                                                                            <span>Monto de Entrega: <b>${{ $servicioAsignado->refund_amount }}</b></span>
                                                                        </li>
                                                                    @endif
                                                                    <li class="list-group-item d-flex">
                                                                        <p class="float-left mb-0">
                                                                            <i class="feather icon-tag mr-1"></i>
                                                                        </p>
                                                                        <span>Tarifa: <b>@if ($servicioAsignado->rate_status == 1) ${{ $servicioAsignado->rate }} @else Sin Calcular @endif</b></span>
                                                                    </li>
                                                                    <li class="list-group-item d-flex">
                                                                        <p class="float-left mb-0">
                                                                            <i class="feather icon-plus-circle mr-1"></i>
                                                                        </p>
                                                                        <span>Costo Adicional: <b>${{ $servicioAsignado->additional_cost }}</b></span>
                                                                    </li>
                                                                    <li class="list-group-item d-flex">
                                                                        <p class="float-left mb-0">
                                                                            <i class="feather icon-tag mr-1"></i>
                                                                        </p>
                                                                        <span>Total: <b> @if ($servicioAsignado->rate_status == 1) ${{ $servicioAsignado->total }} @else Sin Calcular @endif</b></span>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <div class="col-12 col-md-12 text-right">
                                                                <br>
                                                                @if ($servicioAsignado->status == 1)
                                                                    <a href="javascript:;" type="button" class="btn btn-icon btn-primary mr-1 mb-1 waves-effect waves-light" onclick="loadConfirmModal({{$servicioAsignado->id}},1);"><i class="feather icon-check"></i> Llegada a Punto Inicial</a>
                                                                @elseif ($servicioAsignado->status == 2)  
                                                                    <a href="javascript:;" type="button" class="btn btn-icon btn-primary mr-1 mb-1 waves-effect waves-light" onclick="loadConfirmModal({{$servicioAsignado->id}},2);"><i class="feather icon-check"></i> Iniciar Servicio</a>  
                                                                @elseif ($servicioAsignado->status == 3)
                                                                    <a href="javascript:;" type="button" class="btn btn-icon btn-primary mr-1 mb-1 waves-effect waves-light" onclick="loadConfirmModal({{$servicioAsignado->id}},3);"><i class="feather icon-check"></i> Entrega en Punto Final</a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6" class="text-center">No posee ningún servicio programado...</td>
                                        </tr>
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th class="text-center">Fecha</th>
                                        <th class="text-center">Hora</th>
                                        <th class="text-center">Punto Inicial</th>
                                        <th class="text-center">Punto Final</th>
                                        <th class="text-center">Equipamiento</th>
                                        <th class="text-center">Acción</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!-- VERSIÓN MÓVIL -->
                        <div class="table-responsive mt-1 d-block d-sm-none">
                            <table class="table table-hover-animation mb-0">
                                <tbody>
                                    @if ($cantServiciosAsignados > 0)
                                        @foreach ($serviciosAsignados as $servicioAsignadoMovil)
                                            <tr>
                                                <td>
                                                    {{ date('Y-m-d', strtotime($servicioAsignadoMovil->date)) }} - {{ date('H:i', strtotime($servicioAsignadoMovil->time)) }} <br>
                                                    {{ $servicioAsignadoMovil->sender_neighborhood }} - {{ $servicioAsignadoMovil->receiver_neighborhood }} <br>
                                                    ({{ $servicioAsignadoMovil->equipment_type }})
                                                </td>
                                                <td class="text-center">
                                                    <a data-toggle="collapse" href="#collapse-movil-{{$servicioAsignadoMovil->id}}"><i class="far fa-caret-square-down" style="font-size: 22px;"></i></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <div id="collapse-movil-{{$servicioAsignadoMovil->id}}" class="collapse">
                                                        <div class="row">
                                                            <div class="col-12 col-md-4">
                                                                <ul class="list-group">
                                                                    <a href="#" class="list-group-item"><strong>Datos de Envío y Recogida</strong></a>
                                                                    <li class="list-group-item d-flex">
                                                                        <p class="float-left mb-0">
                                                                            <i class="fas fa-id-card mr-1"></i>
                                                                        </p>
                                                                        <span>Nombre y teléfono de quién envía: <br><b>{{ $servicioAsignadoMovil->sender }}</b></span>
                                                                    </li>
                                                                    <li class="list-group-item d-flex">
                                                                        <p class="float-left mb-0">
                                                                            <i class="feather icon-map-pin mr-1"></i>
                                                                        </p>
                                                                        <span>Dirección de quién envía: <br><b>{{ $servicioAsignadoMovil->sender_address_opc }}</b></span>
                                                                    </li>
                                                                    <li class="list-group-item d-flex">
                                                                        <p class="float-left mb-0">
                                                                            <i class="feather icon-map-pin mr-1"></i>
                                                                        </p>
                                                                        <span>Barrio de quién envía: <br><b>{{ $servicioAsignadoMovil->sender_neighborhood }}</b></span>
                                                                    </li>
                                                                    <li class="list-group-item d-flex">
                                                                        <p class="float-left mb-0">
                                                                            <i class="fas fa-id-card mr-1"></i>
                                                                        </p>
                                                                        <span>Nombre y teléfono de quién recibe: <br><b>{{ $servicioAsignadoMovil->receiver }}</b></span>
                                                                    </li>
                                                                    <li class="list-group-item d-flex">
                                                                        <p class="float-left mb-0">
                                                                            <i class="feather icon-map-pin mr-1"></i>
                                                                        </p>
                                                                        <span>Dirección de quién recibe: <br><b>{{ $servicioAsignadoMovil->receiver_address_opc }}</b></span>
                                                                    </li>
                                                                    <li class="list-group-item d-flex">
                                                                        <p class="float-left mb-0">
                                                                            <i class="feather icon-map-pin mr-1"></i>
                                                                        </p>
                                                                        <span>Barrio de quién recibe: <br><b>{{ $servicioAsignadoMovil->receiver_neighborhood }}</b></span>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <div class="col-12 col-md-4">
                                                                <ul class="list-group">
                                                                    <a href="#" class="list-group-item"><strong>Datos del Servicio</strong></a>
                                                                    <li class="list-group-item d-flex">
                                                                        <p class="float-left mb-0">
                                                                            <i class="feather icon-calendar mr-1"></i>
                                                                        </p>
                                                                        <span>Cliente: @if ($servicioAsignadoMovil->user_id == 0) <br><b>{{ $servicioAsignadoMovil->client_name }}</b> (No Registrado) @else @if (!is_null($servicioAsignadoMovil->user->tradename)) <b>{{ $servicioAsignadoMovil->user->tradename}}</b> @else <b>{{ $servicioAsignadoMovil->user->name }}</b> @endif @endif</span>
                                                                    </li>
                                                                    <li class="list-group-item d-flex">
                                                                        <p class="float-left mb-0">
                                                                            <i class="feather icon-calendar mr-1"></i>
                                                                        </p>
                                                                        <span>Fecha: <br><b>{{ date('d-m-Y', strtotime($servicioAsignadoMovil->date)) }}</b></span>
                                                                    </li>
                                                                    <li class="list-group-item d-flex">
                                                                        <p class="float-left mb-0">
                                                                            <i class="feather icon-clock mr-1"></i>
                                                                        </p>
                                                                        <span>Hora: <br><b>{{ date('H:i', strtotime($servicioAsignadoMovil->time)) }}</b></span>
                                                                    </li>
                                                                    <li class="list-group-item d-flex">
                                                                        <p class="float-left mb-0">
                                                                            <i class="feather icon-package mr-1"></i>
                                                                        </p>
                                                                        <span>Artículo: <br><b>{{ $servicioAsignadoMovil->article }}</b></span>
                                                                    </li>
                                                                    <li class="list-group-item d-flex">
                                                                        <p class="float-left mb-0">
                                                                            <i class="feather icon-file-text mr-1"></i>
                                                                        </p>
                                                                        <span>Observaciones: <br><b>{{ $servicioAsignadoMovil->observations }}</b></span>
                                                                    </li>
                                                                    <li class="list-group-item d-flex">
                                                                        <p class="float-left mb-0">
                                                                            <i class="feather icon-briefcase mr-1"></i>
                                                                        </p>
                                                                        <span>Tipo de Equipo: <br><b>{{ $servicioAsignadoMovil->equipment_type }}</b></span>
                                                                    </li>
                                                                    <li class="list-group-item d-flex">
                                                                        <p class="float-left mb-0">
                                                                            <i class="feather icon-disc mr-1"></i>
                                                                        </p>
                                                                        <span>Estado: <br><b>@if ($servicioAsignadoMovil->status == 0) Pendiente @elseif ($servicioAsignadoMovil->status == 1) Asignado @elseif ($servicioAsignadoMovil->status == 2) Iniciado @elseif ($servicioAsignadoMovil->status == 3) Confirmado @elseif ($servicioAsignadoMovil->status == 4) Completado @else Declinado @endif</b></span>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <div class="col-12 col-md-4">
                                                                <ul class="list-group">
                                                                    <a href="#" class="list-group-item"><strong>Datos de Pago</strong></a>
                                                                    <li class="list-group-item d-flex">
                                                                        <p class="float-left mb-0">
                                                                            <i class="feather icon-watch mr-1"></i>
                                                                        </p>
                                                                        <span>Forma de Pago: <br><b>{{ $servicioAsignadoMovil->payment_type }}</b></span>
                                                                    </li>
                                                                    <li class="list-group-item d-flex">
                                                                        <p class="float-left mb-0">
                                                                            <i class="feather icon-credit-card mr-1"></i>
                                                                        </p>
                                                                        <span>Método de Pago: <br><b>@if ($servicioAsignadoMovil->payment_method == 'reembolso') Contra Entrega @else {{ $servicioAsignadoMovil->payment_method }} @endif</b></span>
                                                                    </li>
                                                                    @if ($servicioAsignadoMovil->payment_method == 'reembolso')
                                                                        <li class="list-group-item d-flex">
                                                                            <p class="float-left mb-0">
                                                                                <i class="feather icon-credit-card mr-1"></i>
                                                                            </p>
                                                                            <span>Monto de Entrega: <br><b>${{ $servicioAsignadoMovil->refund_amount }}</b></span>
                                                                        </li>
                                                                    @endif
                                                                    <li class="list-group-item d-flex">
                                                                        <p class="float-left mb-0">
                                                                            <i class="feather icon-tag mr-1"></i>
                                                                        </p>
                                                                        <span>Tarifa: <br><b>@if ($servicioAsignadoMovil->rate_status == 1) ${{ $servicioAsignadoMovil->rate }} @else Sin Calcular @endif</b></span>
                                                                    </li>
                                                                    <li class="list-group-item d-flex">
                                                                        <p class="float-left mb-0">
                                                                            <i class="feather icon-plus-circle mr-1"></i>
                                                                        </p>
                                                                        <span>Costo Adicional: <br><b>${{ $servicioAsignadoMovil->additional_cost }}</b></span>
                                                                    </li>
                                                                    <li class="list-group-item d-flex">
                                                                        <p class="float-left mb-0">
                                                                            <i class="feather icon-tag mr-1"></i>
                                                                        </p>
                                                                        <span>Total: <br><b> @if ($servicioAsignadoMovil->rate_status == 1) ${{ $servicioAsignadoMovil->total }} @else Sin Calcular @endif</b></span>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <div class="col-12 col-md-12 text-right">
                                                                <br>
                                                                @if ($servicioAsignadoMovil->status == 1)
                                                                    <a href="javascript:;" type="button" class="btn btn-icon btn-primary mr-1 mb-1 waves-effect waves-light" onclick="loadConfirmModal({{$servicioAsignadoMovil->id}},1);"><i class="feather icon-check"></i> Llegada a Punto Inicial</a>
                                                                @elseif ($servicioAsignadoMovil->status == 2)  
                                                                    <a href="javascript:;" type="button" class="btn btn-icon btn-primary mr-1 mb-1 waves-effect waves-light" onclick="loadConfirmModal({{$servicioAsignadoMovil->id}},2);"><i class="feather icon-check"></i> Iniciar Servicio</a>  
                                                                @elseif ($servicioAsignadoMovil->status == 3)
                                                                    <a href="javascript:;" type="button" class="btn btn-icon btn-primary mr-1 mb-1 waves-effect waves-light" onclick="loadConfirmModal({{$servicioAsignadoMovil->id}},3);"><i class="feather icon-check"></i> Entrega en Punto Final</a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="2" class="text-center">No posee ningún servicio programado...</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-sm-12 col-12 d-block d-md-none">
                <a href="{{ route('brever.services.assigned') }}">
                    <div class="card">
                        <div class="card-header d-flex flex-column align-items-start pb-0">
                            <div class="avatar p-50 m-0" style="background-color: #70DC74;">
                                <div class="avatar-content">
                                    <i class="feather icon-user-plus font-medium-5"></i>
                                </div>
                            </div>
                            <h2 class="text-bold-700 mt-1 mb-25">{{ $cantServiciosAsignados }}</h2>
                            <p class="mb-0">Servicios Asignados</p>
                        </div>
                        <div class="card-content">
                            <div id="servicios-asignados2"></div>
                        </div>
                    </div>
                </a>
            </div> 

            <div class="col-sm-12 col-12 d-block d-md-none">
                <a href="{{ route('brever.services.completed') }}">
                    <div class="card">
                        <div class="card-header d-flex flex-column align-items-start pb-0">
                            <div class="avatar p-50 m-0" style="background-color: #4caf50;">
                                <div class="avatar-content">
                                    <i class="feather icon-user-check font-medium-5"></i>
                                </div>
                            </div>
                            <h2 class="text-bold-700 mt-1 mb-25">{{ $cantServiciosCompletados }}</h2>
                            <p class="mb-0">Servicios Completados</p>
                        </div>
                        <div class="card-content">
                            <div id="servicios-completados2"></div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-12 col-sm-12 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Mis Ganancias</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body pl-0">
                            <div class="height-300">
                                <canvas id="ganancias"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade text-left show" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel110" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <form method="POST" id="confirm-form" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="service_id" id="service_id">
                <div class="modal-content">
                    <div class="modal-header bg-success white">
                        <h5 class="modal-title" id="myModalLabel110">Actualizar Progreso de Servicio</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group" id="photo_div" style="display:none;">
                            <label for="photo">Foto de Entrega</label>
                            <input type="file" class="form-control" name="photo" id="photo" capture="camera" accept="image/*">
                        </div>
                        <div class="text-center" id="confirm-text"></div>
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