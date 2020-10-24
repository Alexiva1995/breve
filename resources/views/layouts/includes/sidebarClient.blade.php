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
                <a href="{{ route('dashboard') }}">
                    <i class="feather icon-home"></i>
                    <span class="menu-title" data-i18n="Dashboard">Inicio</span>
                </a>
            </li>
            <li class=" nav-item"><a href="#"><i class="fas fa-route"></i><span class="menu-title">Servicios</span></a>
                <ul class="menu-content">
                    <li>
                        <a href="{{ route('services.create') }}"><i class="feather icon-circle"></i><span class="menu-item">Nuevo</span></a>
                    </li>
                    <li>
                        <a href="{{ route('services.index') }}"><i class="feather icon-circle"></i><span class="menu-item">Mis Servicios</span></a>
                    </li>
                    <li>
                        <a href="{{ route('services.record') }}"><i class="feather icon-circle"></i><span class="menu-item">Realizados</span></a>
                    </li>
                </ul>
            </li>
            <li class=" nav-item"><a href="#"><i class="feather icon-user"></i><span class="menu-title">Perfil</span></a>
                <ul class="menu-content">
                    <li>
                        <a href="{{ route('profile') }}"><i class="feather icon-circle"></i><span class="menu-item">Datos de Perfil</span></a>
                    </li>
                    <li>
                        <a href="{{ route('my-addresses') }}"><i class="feather icon-circle"></i><span class="menu-item">Mis Domicilios</span></a>
                    </li>
                    <li>
                        <a href="{{ route('my-remember-data') }}"><i class="feather icon-circle"></i><span class="menu-item">Mis Datos Recordados</span></a>
                    </li>
                </ul>
            </li>
            <li class=" nav-item">
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="feather icon-power"></i>
                    <span class="menu-title" data-i18n="Dashboard">Cerrar Sesión</span>
                </a>
            </li>
        </ul>
    </div>
</div>