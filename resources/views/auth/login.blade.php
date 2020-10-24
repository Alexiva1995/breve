@extends('layouts.auth')

@section('content')
    @if (Session::has('msj-exitoso'))
        <script>
            $(document).ready(function () {
                Swal.fire({
                    title: "¡Listo!",
                    text: "Hemos enviado una nueva contraseña a tu correo",
                    type: "success",
                    confirmButtonClass: 'btn btn-primary',
                    buttonsStyling: false,
                });
            });
        </script>
    @endif

    @if (Session::has('msj-confirmacion'))
        <script>
            $(document).ready(function () {
                Swal.fire({
                    title: "¡Listo!",
                    text: "Su cuenta ha sido confirmada con éxito. Inicie sesión para disfrutar de nuestros servicios.",
                    type: "success",
                    confirmButtonClass: 'btn btn-primary',
                    buttonsStyling: false,
                });
            });
        </script>
    @endif
    
    @if (Session::has('msj-erroneo'))
        <script>
            $(document).ready(function () {
                Swal.fire({
                    title: "¡Ups!",
                    text: "El correo ingresado no se encuentra registado",
                    type: "error",
                    confirmButtonClass: 'btn btn-primary',
                    buttonsStyling: false,
                });
            }); 
        </script>
    @endif

    @if (Session::has('msj-erroneo2'))
        <script>
            $(document).ready(function () {
                Swal.fire({
                    title: "¡Ups!",
                    text: "Sus datos no coinciden con nuestros registros.",
                    type: "error",
                    confirmButtonClass: 'btn btn-primary',
                    buttonsStyling: false,
                });
            }); 
        </script>
    @endif

    @if (Session::has('msj-erroneo3'))
        <script>
            $(document).ready(function () {
                Swal.fire({
                    title: "¡Ups!",
                    text: "Su cuenta se encuentra inhabilitada.",
                    type: "error",
                    confirmButtonClass: 'btn btn-primary',
                    buttonsStyling: false,
                });
            }); 
        </script>
    @endif

    @if (Session::has('msj-erroneo4'))
        <script>
            $(document).ready(function () {
                Swal.fire({
                    title: "¡Ups!",
                    text: "Su cuenta se encuentra a la espera de la verificación del administrador.",
                    type: "error",
                    confirmButtonClass: 'btn btn-primary',
                    buttonsStyling: false,
                });
            }); 
        </script>
    @endif
   
	<div class="row m-0">
        <div class="col-lg-12 col-12 p-0">
            <div class="card rounded-0 mb-0 px-2">
                <div class="card-header pb-1">
                    <div class="card-title">
                        <h4 class="mb-0">Iniciar Sesión</h4>
                    </div>
                </div>
                <p class="px-2">Bienvenido de vuelta, por favor inicia sesión con tu cuenta.</p>
                <div class="card-content" style="padding-bottom: 20px;">
                    <div class="card-body pt-1" >
                        <form action="{{ route('login') }}" method="POST">
                        	@csrf
                            <fieldset class="form-label-group form-group position-relative has-icon-left">
                                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" placeholder="Correo Electrónico" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                <div class="form-control-position">
                                    <i class="feather icon-user"></i>
                                </div>
                                <label for="user-name">Correo Electrónico</label>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </fieldset>

                            <fieldset class="form-label-group position-relative has-icon-left">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" required autocomplete="current-password">
                                <div class="form-control-position">
                                    <i class="feather icon-lock"></i>
                                </div>
                                <label for="user-password">Contraseña</label>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </fieldset>
                            
                            <div class="form-group d-flex justify-content-between align-items-center">    
                                <div class="text-right"><a href="javascript:;" data-toggle="modal" data-target="#passwordModal" class="card-link">Recuperar Contraseña</a></div>
                            </div>
                            
                            <div class="row d-block d-sm-none">
                                <div class="col-sm-6 register"> 
                                    <button type="submit" class="btn btn-primary btn-inline">Iniciar Sesión</button>
                                </div>
                                <div class="col-sm-6 login">
                                    <a href="{{ route('register')}}" class="btn btn-outline-primary btn-inline">Registrarme</a>
                                </div>
                            </div>

                            <div class="d-none d-sm-block">
                                <a href="{{ route('register')}}" class="btn btn-outline-primary float-left btn-inline">Registrarme</a>
                                <button type="submit" class="btn btn-primary btn-inline float-right">Iniciar Sesión</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade text-left" id="passwordModal" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel1">Recuperar Contraseña</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ route('user.recover-password') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Ingresa el correo eléctronico registrado</label>
                            <input name="email" type="email" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Enviar Contraseña</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection