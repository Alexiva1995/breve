@extends('layouts.auth')


@section('content')
    @if (Session::has('msj-exitoso'))
        <script>
            $(document).ready(function () {
                Swal.fire({
                    title: "¡Listo!",
                    text: "Su cuenta ha sido creada con éxito. Por favor, ingrese a su correo electrónico para confirmar su cuenta",
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
     
	<div class="row m-0">
        <div class="col-lg-12 col-12 p-0">
            <div class="card rounded-0 mb-0 p-2">
                <div class="card-header pt-50 pb-1">
                    <div class="card-title">
                        <h4 class="mb-0">Crear una cuenta</h4>
                    </div>
                </div>
                <p class="px-2">Rellene el siguiente formulario para crear una nueva cuenta.</p>
                <div class="card-content">
                    <div class="card-body pt-0">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="form-label-group">
                                <input type="text" name="name" id="name" class="form-control" placeholder="Nombre y Apellido" required>
                            </div>
                            <div class="form-label-group">
                                <input type="text" name="tradename" id="tradename" class="form-control" placeholder="Nombre Comercial (Opcional)">
                            </div>
                            <div class="form-label-group">
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="Correo electrónico">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-label-group">
                                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Contraseña" required>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-label-group">
                                <input type="password" id="password-confirm" name="password_confirmation" class="form-control" placeholder="Confirmar contraseña" required>
                            </div>
                            <div class="form-group row">
                                <div class="col-12">
                                    <fieldset class="checkbox">
                                        <div class="vs-checkbox-con vs-checkbox-primary">
                                            <input type="checkbox" checked="">
                                            <span class="vs-checkbox">
                                                <span class="vs-checkbox--check">
                                                    <i class="vs-icon feather icon-check"></i>
                                                </span>
                                            </span>
                                            <span class="">Acepto los términos y condiciones.</span>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                            
                            {{-- Solo visible en móvil --}}
                            <div class="row d-block d-sm-none">
                                <div class="col-sm-6 register"> 
                                    <button type="submit" class="btn btn-primary btn-inline mb-50 waves-effect waves-light">Registrarse</button>
                                </div>
                                <div class="col-sm-6 login">
                                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-inline mb-50 waves-effect waves-light">Iniciar sesión</a>
                                </div>
                            </div>
                            
                            {{-- Solo oculto en móvil --}}
                            <div class="d-none d-sm-block">
                                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-inline float-left mb-50 waves-effect waves-light">Iniciar sesión</a>
                                <button type="submit" class="btn btn-primary btn-inline float-right mb-50 waves-effect waves-light">Registrarse</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection