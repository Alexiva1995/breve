@extends('layouts.admin')

@push('scripts')
	<script>
		function checkUser(){
			if (document.getElementById("role_id").value == 3){
				document.getElementById("profile_div").style.display = 'block';
			}else{
				document.getElementById("profile_div").style.display = 'none';
			}
		}		
	</script>
@endpush

@section('content')
	<div class="row">
		<div class="col-md-12">
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
	   		
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Editar Usuario</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <form class="form" action="{{ route('admin.users.update') }}" method="POST">
                        	@csrf
                            <input type="hidden" name="user_id" value="{{ $usuario->id }}">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-6">
                                    	<div class="form-group">
                                            <label for="name">Nombre y Apellido</label>
                                            <div class="position-relative has-icon-left">
                                                <input type="text" id="name" class="form-control" name="name" value="{{ $usuario->name }}" required>
                                                <div class="form-control-position">
                                                    <i class="feather icon-user"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                        	<label for="email">Correo Electrónico</label>
                                        	<div class="position-relative has-icon-left">
                                            	<input type="email" id="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $usuario->email }}" required>
	                                            <div class="form-control-position">
	                                                <i class="feather icon-mail"></i>
	                                            </div>
	                                        </div>
	                                        @error('email')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
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
                                    <div class="col-md-6">
                                        <div class="form-group">
                                        	<label for="password">Confirmación de Contraseña</label>
                                        	<div class="position-relative has-icon-left">
                                            	<input type="password" id="password-confirm" name="password2" class="form-control" placeholder="Confirmación de Contraseña">
	                                            <div class="form-control-position">
	                                                <i class="feather icon-lock"></i>
	                                            </div>
	                                        </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                        	<label for="role_id">Tipo de Usuario</label>
                                        	<div class="position-relative has-icon-left">
	                                            <select class="form-control" name="role_id" id="role_id" required onchange="checkUser();">
	                                            	<option value="1" @if ($usuario->role_id == 1) selected @endif>Cliente</option>
	                                            	<option value="2" @if ($usuario->role_id == 2) selected @endif>Brever</option>
	                                            	@if (Auth::user()->id == 0)
                                                        <option value="3" @if ($usuario->role_id == 3) selected @endif>Administrador</option>
                                                    @endif
	                                            </select>
	                                            <div class="form-control-position">
	                                                <i class="feather icon-lock"></i>
	                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6" id="profile_div" @if (Auth::user()->id != 0) style="display: none;" @endif>
                                    	<label for="role_id">¿A qué áreas tendrá acceso este usuario?</label>
                                        <ul class="list-unstyled mb-0">
                                            <li class="d-inline-block mr-2">
                                                <fieldset>
                                                    <div class="vs-checkbox-con vs-checkbox-primary">
                                                        <input type="checkbox" name="services" value="1" @if ($usuario->services == 1) checked @endif>
                                                        <span class="vs-checkbox">
                                                            <span class="vs-checkbox--check">
                                                                <i class="vs-icon feather icon-check"></i>
                                                            </span>
                                                        </span>
                                                        <span class="">Servicios</span>
                                                    </div>
                                                </fieldset>
                                            </li>
                                            <li class="d-inline-block mr-2">
                                                <fieldset>
                                                    <div class="vs-checkbox-con vs-checkbox-primary">
                                                        <input type="checkbox" name="users" value="1" @if ($usuario->users == 1) checked @endif>
                                                        <span class="vs-checkbox">
                                                            <span class="vs-checkbox--check">
                                                                <i class="vs-icon feather icon-check"></i>
                                                            </span>
                                                        </span>
                                                        <span class="">Usuarios</span>
                                                    </div>
                                                </fieldset>
                                            </li>
                                            <li class="d-inline-block mr-2">
                                                <fieldset>
                                                    <div class="vs-checkbox-con vs-checkbox-primary">
                                                        <input type="checkbox" name="financial" value="1" @if ($usuario->financial == 1) checked @endif>
                                                        <span class="vs-checkbox">
                                                            <span class="vs-checkbox--check">
                                                                <i class="vs-icon feather icon-check"></i>
                                                            </span>
                                                        </span>
                                                        <span class="">Financiero</span>
                                                    </div>
                                                </fieldset>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-12 text-right">
                                    	<button type="reset" class="btn btn-outline-warning mr-1 mb-1 waves-effect waves-light">Cancelar</button>
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