<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto">
                <a class="navbar-brand" href="#">
                    <div class="brand-logo"></div>
                    <h2 class="brand-text mb-0">Breve</h2>
                </a>
            </li>
            <li class="nav-item nav-toggle">
                <a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse">
                    <i class="feather icon-x d-block d-xl-none font-medium-4 primary toggle-icon"></i>
                    <i class="toggle-icon feather icon-disc font-medium-4 d-none d-xl-block collapse-toggle-icon primary" data-ticon="icon-disc"></i>
                </a>
            </li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class=" nav-item">
                <a href="{{ route('admin.index') }}">
                    <i class="feather icon-home"></i>
                    <span class="menu-title" data-i18n="Dashboard">Inicio</span>
                </a>
            </li>
            @if (Auth::user()->services == 1)
                <li class=" nav-item"><a href="#"><i class="fas fa-route"></i><span class="menu-title">Servicios</span></a>
                    <ul class="menu-content">
                        <li>
                            <a href="{{ route('admin.services.create') }}"><i class="feather icon-circle"></i><span class="menu-item">Nuevo Servicio</span></a>
                        </li>
                        <li>
                            <a href="{{ route('admin.services') }}"><i class="feather icon-circle"></i><span class="menu-item">Pendientes</span></a>
                        </li>
                        <li>
                            <a href="{{ route('admin.services.assigned') }}"><i class="feather icon-circle"></i><span class="menu-item">Asignados</span></a>
                        </li>
                        <li>
                            <a href="{{ route('admin.services.confirmed') }}"><i class="feather icon-circle"></i><span class="menu-item">Confirmados</span></a>
                        </li>
                        <li>
                            <a href="{{ route('admin.services.started') }}"><i class="feather icon-circle"></i><span class="menu-item">En Curso</span></a>
                        </li>
                        <li>
                            <a href="{{ route('admin.services.completed') }}"><i class="feather icon-circle"></i><span class="menu-item">Realizados</span></a>
                        </li>
                        <li>
                            <a href="{{ route('admin.services.canceled') }}"><i class="feather icon-circle"></i><span class="menu-item">Declinados</span></a>
                        </li>
                    </ul>
                </li>
            @endif
            @if (Auth::user()->users == 1)
                <li class=" nav-item"><a href="#"><i class="fa fa-users"></i><span class="menu-title">Usuarios</span></a>
                    <ul class="menu-content">
                        <li>
                            <a href="{{ route('admin.users.create') }}"><i class="feather icon-circle"></i><span class="menu-item">Nuevo Usuario</span></a>
                        </li>
                        <li>
                            <a href="{{ route('admin.users.clients') }}"><i class="feather icon-circle"></i><span class="menu-item">Clientes</span></a>
                        </li>
                        <li>
                            <a href="{{ route('admin.users.brevers') }}"><i class="feather icon-circle"></i><span class="menu-item">Brevers</span></a>
                        </li>
                        @if (Auth::user()->id == 0)
                            <li>
                                <a href="{{ route('admin.users.admins') }}"><i class="feather icon-circle"></i><span class="menu-item">Administradores</span></a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            @if (Auth::user()->financial == 1)
                <li class=" nav-item"><a href="#"><i class="fas fa-wallet"></i><span class="menu-title">Financiero</span></a>
                    <ul class="menu-content">
                        <li>
                            <a href="{{ route('admin.financial.services-record') }}"><i class="feather icon-circle"></i><span class="menu-item">Historial de Servicios</span></a>
                        </li>
                        <li>
                            <a href="{{ route('admin.financial.brevers-record') }}"><i class="feather icon-circle"></i><span class="menu-item">Estado de Cuenta Brever</span></a>
                        </li>
                        <li>
                            <a href="{{ route('admin.financial.brever-recharge') }}"><i class="feather icon-circle"></i><span class="menu-item">Saldo Breve</span></a>
                        </li>
                        <li>
                            <a href="{{ route('admin.financial.pending-payments') }}"><i class="feather icon-circle"></i><span class="menu-item">Pagos Pendientes</span></a>
                        </li>
                        <li>
                            <a href="{{ route('admin.financial.earnings') }}"><i class="feather icon-circle"></i><span class="menu-item">Ganancias</span></a>
                        </li>
                    </ul>
                </li>
            @endif
            <li class=" nav-item">
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="feather icon-power"></i>
                    <span class="menu-title" data-i18n="Dashboard">Cerrar Sesión</span>
                </a>
            </li>
        </ul>
    </div>
</div>