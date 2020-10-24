@php
    if (Auth::user()->role_id == 3){
        $notificaciones = \App\Models\AdminNotification::where('status', '=', 0)
                        ->orderBy('id', 'DESC')
                        ->get();
    }else{
        $notificaciones = \App\Models\Notification::where('status', '=', 0)
                                ->where('user_id', '=', Auth::user()->id)
                                ->orderBy('id', 'DESC')
                                ->get();
    }
     
    $cantNotificaciones = $notificaciones->count();

    if (Auth::user()->role_id == 2){
        $statusHeader = [1, 2, 3];

        $serviciosAsignadosHeader = DB::table('services')
                                        ->where('brever_id', '=', Auth::user()->id)
                                        ->whereIn('status', $statusHeader)
                                        ->orderBy('date', 'ASC')
                                        ->orderBy('time', 'ASC')
                                        ->get();
        
        $cantServiciosAsignadosHeader = $serviciosAsignadosHeader->count();

        $dateHeader = Carbon\Carbon::now();
        $dateHeader->subHour(5);
        $fechaHeader = $dateHeader->format('Y-m-d');
        $horaInicialHeader = $dateHeader->format('H:i');
        $horaFinalHeader = $dateHeader->addHour(4)->format('H:i');

        $serviciosNuevosHeader = DB::table('services')
                                    ->where('status', '=', 0)
                                    ->where('date', '=', $fechaHeader)
                                    ->where('time', '>', $horaInicialHeader)
                                    ->where('time', '<=', $horaFinalHeader)
                                    ->orderBy('time', 'ASC')
                                    ->take(10)
                                    ->get();

        $cantServiciosNuevosHeader = $serviciosNuevosHeader->count();
    }
@endphp

<nav class="header-navbar navbar-expand-lg navbar navbar-with-menu floating-nav navbar-light navbar-shadow">
    <div class="navbar-wrapper">
        <div class="navbar-container content">
            <div class="navbar-collapse" id="navbar-mobile">
                <div class="mr-auto float-left bookmark-wrapper d-flex align-items-center">
                    <ul class="nav navbar-nav">
                        <li class="nav-item mobile-menu d-xl-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ficon feather icon-menu"></i></a></li>
                    </ul>
                    <ul class="nav navbar-nav bookmark-icons">
                        <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-expand"><i class="ficon feather icon-maximize"></i></a></li>
                    </ul>
                </div>
                <ul class="nav navbar-nav float-right">
                    @if (Auth::user()->role_id == 2)
                        <li class="dropdown dropdown-notification nav-item d-block d-sm-none" style="padding-right: 10px; border: solid red 2px; border-right: solid red 1px;">
                            <a class="nav-link nav-link-label" href="#" data-toggle="dropdown">
                                Asignados<br>
                                <center><span class="badge badge-pill badge-danger" style="margin-top: 5px;">{{ $cantServiciosAsignadosHeader }}</span></center>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                                <li class="dropdown-menu-header">
                                    <div class="dropdown-header m-0 p-2">
                                        <h3 class="white">{{ $cantServiciosAsignadosHeader }}</h3>
                                        <span class="notification-title">Servicios Asignados</span>
                                    </div>
                                </li>
                                <li class="scrollable-container media-list">
                                    @foreach ($serviciosAsignadosHeader as $servicioAsignadoHeader)
                                        <div class="media d-flex align-items-start">
                                            <div class="media-body">
                                                <div>
                                                    Fecha: <b>{{ date('d-m-Y', strtotime($servicioAsignadoHeader->date)) }}</b> Hora: <b>{{ date('H:i A', strtotime($servicioAsignadoHeader->time)) }}</b>
                                                </div>
                                                <div style="padding-top: 10px;">
                                                    {{ $servicioAsignadoHeader->sender_neighborhood }} - {{ $servicioAsignadoHeader->receiver_neighborhood }} ({{ $servicioAsignadoHeader->equipment_type }})
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </li>
                            </ul>
                        </li>
                        @if (Auth::user()->vip == 1)
                            <li class="dropdown dropdown-notification nav-item d-block d-sm-none" style="padding-right: 10px; border: solid red 2px; border-left: solid red 1px;">
                                <a class="nav-link nav-link-label" href="#" data-toggle="dropdown">
                                    Disponibles<br>
                                    <center><span class="badge badge-pill badge-danger" style="margin-top: 5px;">{{ $cantServiciosNuevosHeader }}</span></center>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                                    <li class="dropdown-menu-header">
                                        <div class="dropdown-header m-0 p-2">
                                            <h3 class="white">{{ $cantServiciosNuevosHeader }}</h3>
                                            <span class="notification-title">Servicios Disponibles</span>
                                        </div>
                                    </li>
                                    <li class="scrollable-container media-list">
                                        @foreach ($serviciosNuevosHeader as $servicioNuevoHeader)
                                            <div class="media d-flex align-items-start">
                                                <div class="media-body">
                                                    <div>
                                                        Fecha: <b>{{ date('d-m-Y', strtotime($servicioNuevoHeader->date)) }}</b> Hora: <b>{{ date('H:i A', strtotime($servicioNuevoHeader->time)) }}</b>
                                                    </div>
                                                    <div style="padding-top: 10px;">
                                                        {{ $servicioNuevoHeader->sender_neighborhood }} - {{ $servicioNuevoHeader->receiver_neighborhood }} ({{ $servicioNuevoHeader->equipment_type }})
                                                    </div>
                                                </div>
                                                <div class="media-right">
                                                    <a class="btn btn-success btn-sm" href="javascript:;" onclick="loadModal({{$servicioNuevoHeader->id}});">TOMAR <br>SERVICIO</a> 
                                                </div>
                                            </div>
                                        @endforeach
                                    </li>
                                </ul>
                            </li>
                        @endif
                    @endif
                    
                    {{-- Notificaciones --}}
                    @if (Auth::user()->role_id == 3)
                        <li class="dropdown dropdown-notification nav-item"><a class="nav-link nav-link-label" href="#" data-toggle="dropdown"><i class="ficon feather icon-bell"></i><span class="badge badge-pill badge-primary badge-up">{{ $cantNotificaciones }}</span></a>
                            <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                                <li class="dropdown-menu-header">
                                    <div class="dropdown-header m-0 p-2">
                                        <h3 class="white">{{ $cantNotificaciones }} Nuevas</h3><span class="notification-title">Notificaciones</span>
                                    </div>
                                </li>
                                <li class="scrollable-container media-list">
                                    @foreach ($notificaciones as $notificacion)
                                        <a href="{{ route('admin.services.show', $notificacion->service_id) }}" class="d-flex justify-content-between" href="javascript:void(0)">
                                            <div class="media d-flex align-items-start">
                                                @if ($notificacion->brever_id == 0)
                                                    <div class="media-left"><i class="feather icon-plus-square font-medium-5 primary"></i></div>
                                                    <div class="media-body">
                                                        <h6 class="primary media-heading">Nueva Solicitud de Servicio</h6><small class="notification-text">{{ $notificacion->service->sender_neighborhood }} - {{ $notificacion->service->receiver_neighborhood }} ( {{ date('d-m-Y', strtotime($notificacion->service->date)) }} {{ date('H:i A', strtotime($notificacion->service->time)) }})</small>
                                                    </div>
                                                @else
                                                    <div class="media-left"><i class="feather icon-check font-medium-5 primary"></i></div>
                                                    <div class="media-body">
                                                        <h6 class="primary media-heading">Un Brever ha tomado un servicio</h6><small class="notification-text"> <b>({{ $notificacion->brever->name }})</b> {{ $notificacion->service->sender_neighborhood }} - {{ $notificacion->service->receiver_neighborhood }} ( {{ date('d-m-Y', strtotime($notificacion->service->date)) }} {{ date('H:i A', strtotime($notificacion->service->time)) }})</small>
                                                    </div>
                                                @endif
                                                <small><time class="media-meta">{{ date('d-m-Y', strtotime($notificacion->created_at)) }}</time></small>
                                            </div>
                                        </a>
                                    @endforeach
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="dropdown dropdown-notification nav-item"><a class="nav-link nav-link-label" href="#" data-toggle="dropdown"><i class="ficon feather icon-bell"></i><span class="badge badge-pill badge-primary badge-up">{{ $cantNotificaciones }}</span></a>
                            <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                                <li class="dropdown-menu-header">
                                    <div class="dropdown-header m-0 p-2">
                                        <h3 class="white">{{ $cantNotificaciones }} Nuevas</h3><span class="notification-title">Notificaciones</span>
                                    </div>
                                </li>
                                <li class="scrollable-container media-list">
                                    @foreach ($notificaciones as $notificacion)
                                        @if (Auth::user()->role_id == 1)
                                            <a href="{{ route('services.show', $notificacion->service_id) }}" class="d-flex justify-content-between" href="javascript:void(0)">
                                        @else
                                            <a href="{{ route('brever.services.show', $notificacion->service_id) }}" class="d-flex justify-content-between" href="javascript:void(0)">
                                        @endif
                                            <div class="media d-flex align-items-start">
                                                <div class="media-left"><i class="{{ $notificacion->icon }} font-medium-5 primary"></i></div>
                                                <div class="media-body">
                                                    <h6 class="primary media-heading">{{ $notificacion->title }}</h6><small class="notification-text">{{ $notificacion->service->sender_neighborhood }} - {{ $notificacion->service->receiver_neighborhood }} ( {{ date('d-m-Y', strtotime($notificacion->service->date)) }} {{ date('H:i A', strtotime($notificacion->service->time)) }})</small>
                                                </div>
                                                <small><time class="media-meta">{{ date('d-m-Y', strtotime($notificacion->created_at)) }}</time></small>
                                            </div>
                                        </a>
                                    @endforeach
                                </li>
                            </ul>
                        </li>
                    @endif
                    {{-- Menú de Usuario --}}
                    <li class="dropdown dropdown-user nav-item">
                        <a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                            <div class="user-nav d-sm-flex d-none">
                                <span class="user-name text-bold-600">{{ Auth::user()->name }}</span>
                            </div>
                            <span>
                                @if (is_null(Auth::user()->avatar))
                                    <img class="round" src="{{ asset('themeforest/app-assets/images/portrait/small/avatar.png') }}" alt="avatar" height="40" width="40">
                                @else
                                    <img class="round" src="{{ asset('images/users/'.Auth::user()->avatar) }}" alt="avatar" height="40" width="40">
                                @endif
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            @if (Auth::user()->role_id != 3)
                                @if (Auth::user()->role_id == 1)
                                    <a class="dropdown-item" href="{{ route('profile') }}"><i class="feather icon-user"></i> Mi Perfil</a>
                                @else
                                    <a class="dropdown-item" href="{{ route('brever.profile') }}"><i class="feather icon-user"></i> Mi Perfil</a>
                                @endif
                                <div class="dropdown-divider"></div>
                            @endif
                            
                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="feather icon-power"></i> Cerrar Sesión</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>