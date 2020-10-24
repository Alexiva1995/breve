@extends('layouts.brever')

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

    <div class="row">
        <div class="col-12">
			<div class="card">
				<div class="card-header">
                    <h4 class="card-title">Editar Mi Perfil</h4>
                </div>
		        <div class="card-content">
		            <div class="card-body">
			 			<form class="form" action="{{ route('brever.update-profile') }}" method="POST" enctype="multipart/form-data">
                        	@csrf
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="name">Nombres y Apellidos (^)</label>
                                            <div class="position-relative has-icon-left">
                                                <input type="text" id="name" class="form-control" name="name" value="{{ Auth::user()->name }}" required>
                                                <div class="form-control-position">
                                                    <i class="feather icon-user"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                    	<div class="form-group">
                                            <label for="email">Correo Electrónico (^)</label>
                                            <div class="position-relative has-icon-left">
                                                <input type="email" id="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ Auth::user()->email }}" required>
                                                <div class="form-control-position">
                                                    <i class="feather icon-mail"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                    	<div class="form-group">
                                            <label for="phone">Teléfono</label>
                                            <div class="position-relative has-icon-left">
                                                <input type="phone" id="phone" class="form-control" name="phone" value="{{ Auth::user()->phone }}">
                                                <div class="form-control-position">
                                                    <i class="feather icon-phone"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="tradename">Placa del Vehículo</label>
                                            <div class="position-relative has-icon-left">
                                                <input type="text" id="license_plate" class="form-control" name="license_plate" value="{{ Auth::user()->license_plate }}">
                                                <div class="form-control-position">
                                                    <i class="feather icon-car"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                    	<div class="form-group">
                                            <label for="password">Contraseña</label>
                                            <div class="position-relative has-icon-left">
                                                <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" name="password">
                                                <div class="form-control-position">
                                                    <i class="feather icon-lock"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                    	<div class="form-group">
                                            <label for="password2">Confirmación de Contraseña</label>
                                            <div class="position-relative has-icon-left">
                                                <input type="password" name="password2" id="password2" class="form-control" >
                                                <div class="form-control-position">
                                                    <i class="feather icon-lock"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="tradename">Avatar</label>
                                            <input type="file" id="avatar" name="avatar" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-12 text-right">
                                        <button type="submit" class="btn btn-primary mr-1 mb-1 waves-effect waves-light">Guardar Cambios</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection