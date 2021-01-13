<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use App\Models\User; use App\Models\Service;
use App\Models\Address; use App\Models\RememberData;
use DB; use Mail; use Auth; use Carbon\Carbon;

class UserController extends Controller
{
    public function recover_password(Request $request){
        $usuario = DB::table('users')
                    ->select('id', 'name')
                    ->where('email', '=', $request->email)
                    ->first();

        if (!is_null($usuario)){
            $claveTemporal = strtolower(Str::random(9));

            DB::table('users')
                ->where('id', '=', $usuario->id)
                ->update(['password' => Hash::make($claveTemporal)]);

            $data['correo'] = $request->email;
            $data['cliente'] = $usuario->name;
            $data['clave'] = $claveTemporal;

            Mail::send('emails.recoverPassword',['data' => $data], function($msg) use ($data){
                $msg->to($data['correo']);
                $msg->subject('Recuperar Contraseña');
            });

            return redirect('login')->with('msj-exitoso', 'Se ha enviado una clave temporal a su correo registrado.');

        }else{
            return redirect('login')->with('msj-erroneo', 'El correo ingresado no se encuentra registrado.');
        }
    }

    public function verify_email($id){
        DB::table('users')
            ->where('id', '=', $id)
            ->update(['email_verified_at' => date('Y-m-d H:i:s')]);

        return redirect('login')->with('msj-confirmacion', 'Listo');
    }

    public function index(Request $request){
        if (Auth::user()->role_id == 3){
            //Inicio Admin
            $cantServiciosNuevos = DB::table('services')
                                    ->where('status', '=', 0)
                                    ->where('date', '=', date('Y-m-d'))
                                    ->count();

            $graficaServiciosNuevos = DB::table('services')
                                        ->where('status', '=', 0)
                                        ->where('date', '=', date('Y-m-d'))
                                        ->select(DB::raw('hour(time)'), DB::raw('COUNT(id) as total'))
                                        ->groupBy(DB::raw('hour(time)'))
                                        ->get();

            $datosGraficoServiciosNuevos = array();
            foreach ($graficaServiciosNuevos as $sn){
                array_push($datosGraficoServiciosNuevos, $sn->total);
            }

            $cantServiciosAsignados = DB::table('services')
                                        ->where('status', '=', 1)
                                        ->where('date', '=', date('Y-m-d'))
                                        ->count();

            $graficaServiciosAsignados = DB::table('services')
                                            ->where('status', '=', 1)
                                            ->where('date', '=', date('Y-m-d'))
                                            ->select(DB::raw('hour(time)'), DB::raw('COUNT(id) as total'))
                                            ->groupBy(DB::raw('hour(time)'))
                                            ->get();

            $datosGraficoServiciosAsignados = array();
            foreach ($graficaServiciosAsignados as $sa){
                array_push($datosGraficoServiciosAsignados, $sa->total);
            }

            $cantServiciosCompletados = DB::table('services')
                                        ->where('status', '=', 4)
                                        ->where('date', '=', date('Y-m-d'))
                                        ->count();

            $graficaServiciosCompletados = DB::table('services')
                                            ->where('status', '=', 4)
                                            ->where('date', '=', date('Y-m-d'))
                                            ->select(DB::raw('hour(time)'), DB::raw('COUNT(id) as total'))
                                            ->groupBy(DB::raw('hour(time)'))
                                            ->get();

            $datosGraficoServiciosCompletados = array();
            foreach ($graficaServiciosCompletados as $sc){
                array_push($datosGraficoServiciosCompletados, $sc->total);
            }

            $cantServiciosCancelados = DB::table('services')
                                        ->where('status', '=', 5)
                                        ->where('date', '=', date('Y-m-d'))
                                        ->count();

            $graficaServiciosCancelados = DB::table('services')
                                        ->where('status', '=', 5)
                                        ->where('date', '=', date('Y-m-d'))
                                        ->select(DB::raw('hour(time)'), DB::raw('COUNT(id) as total'))
                                        ->groupBy(DB::raw('hour(time)'))
                                        ->get();

            $datosGraficoServiciosCancelados = array();
            foreach ($graficaServiciosCancelados as $sca){
                array_push($datosGraficoServiciosCancelados, $sca->total);
            }

            $statusProximos = [0, 1, 2, 3, 6];
            $serviciosProximos = Service::whereIn('status', $statusProximos)
                                    ->orderBy('type', 'ASC')
                                    ->orderBy('date', 'ASC')
                                    ->orderBy('time', 'ASC')
                                    ->get();
            
            $ingresos = DB::table('balances')
                            ->where('type', '=', 'Domicilio Breve')
                            ->orderBy('created_at', 'DESC')
                            ->get();

            $datosIngreso = array();
            $datosGanancia = array();
            $datosHora = array();
            foreach ($ingresos as $ingreso){
                array_push($datosIngreso, ($ingreso->brever_commission + $ingreso->breve_commission));
                array_push($datosGanancia, $ingreso->breve_commission);
                array_push($datosHora, $ingreso->created_at);
            }

            $breves = DB::table('users')
                        ->select('id', 'name')
                        ->where('role_id', '=', 2)
                        ->orderBy('name', 'ASC')
                        ->get();

            $clientes = DB::table('users')
                            ->select('id', 'name', 'tradename')
                            ->where('role_id', '=', 1)
                            ->where('status', '=', 1)
                            ->orderBy('name', 'ASC')
                            ->get();

           return view('admin.index')->with(compact('cantServiciosNuevos', 'datosGraficoServiciosNuevos', 'cantServiciosAsignados', 'datosGraficoServiciosAsignados', 'cantServiciosCompletados', 'datosGraficoServiciosCompletados', 'cantServiciosCancelados', 'datosGraficoServiciosCancelados', 'serviciosProximos', 'datosIngreso', 'datosGanancia', 'datosHora', 'breves', 'clientes'));
       }else if (Auth::user()->role_id == 2){
            //Inicio Brever
            $status = [1, 2, 3, 6];

            $serviciosAsignados = Service::where('brever_id', '=', Auth::user()->id)
                                    ->whereIn('status', $status)
                                    ->get();
            
            $cantServiciosAsignados = $serviciosAsignados->count();
            
            $graficaServiciosAsignados = DB::table('services')
                                            ->where('brever_id', '=', Auth::user()->id)
                                            ->whereIn('status', $status)
                                            ->select(DB::raw('hour(time)'), DB::raw('COUNT(id) as total'))
                                            ->groupBy(DB::raw('hour(time)'))
                                            ->get();

            $datosGraficoServiciosAsignados = array();
            foreach ($graficaServiciosAsignados as $sa){
                array_push($datosGraficoServiciosAsignados, $sa->total);
            }

            $cantServiciosCompletados = DB::table('services')
                                            ->where('brever_id', '=', Auth::user()->id)
                                            ->where('status', '=', 4)
                                            ->count();

            $graficaServiciosCompletados = DB::table('services')
                                            ->where('brever_id', '=', Auth::user()->id)
                                            ->where('status', '=', 4)
                                            ->select(DB::raw('hour(time)'), DB::raw('COUNT(id) as total'))
                                            ->groupBy(DB::raw('hour(time)'))
                                            ->get();

            $datosGraficoServiciosCompletados = array();
            foreach ($graficaServiciosCompletados as $sc){
                array_push($datosGraficoServiciosCompletados, $sc->total);
            }

            $date = Carbon::now();
            $date->subHour(5);

            $fecha = $date->format('Y-m-d');
            $horaInicial = $date->format('H:i');
            $horaFinal = $date->addHour(4)->format('H:i');

           /* $serviciosNuevos = Service::where('status', '=', 0)
                                    ->where('date', '=', $fecha)
                                    ->where('time', '>', $horaInicial)
                                    ->where('time', '<=', $horaFinal)
                                    ->orderBy('time', 'ASC')
                                    ->take(10)
                                    ->get();

            $cantServiciosNuevos = $serviciosNuevos->count();*/

            $ganancias = DB::table('balances')
                            ->where('brever_id', '=', Auth::user()->id)
                            ->where('type', '=', 'Domicilio Breve')
                            ->select('date', DB::raw('SUM(brever_commission) as total'))
                            ->groupBy('date')
                            ->get();

            $datosGraficoGananciasFecha = array();
            $datosGraficoGananciasMonto = array();
            foreach ($ganancias as $ganancia){
                array_push($datosGraficoGananciasFecha, $ganancia->date);
                array_push($datosGraficoGananciasMonto, $ganancia->total);
            }


            return view('brever.index')->with(compact('serviciosAsignados', 'cantServiciosAsignados', 'datosGraficoServiciosAsignados', 'cantServiciosCompletados', 'datosGraficoServiciosCompletados', 'datosGraficoGananciasFecha', 'datosGraficoGananciasMonto'));
       }else{
            //Inicio Cliente
            $statusClient = [0, 1, 2, 3, 6];

            $cantServiciosPendientes = DB::table('services')
                                        ->where('user_id', '=', Auth::user()->id)
                                        ->whereIn('status', $statusClient)
                                        ->count();

            $graficaServiciosPendientes = DB::table('services')
                                            ->where('user_id', '=', Auth::user()->id)
                                            ->where('status', '<=', 3)
                                            ->select(DB::raw('hour(time)'), DB::raw('COUNT(id) as total'))
                                            ->groupBy(DB::raw('hour(time)'))
                                            ->get();

            $datosGraficoServiciosPendientes = array();
            foreach ($graficaServiciosPendientes as $sp){
                array_push($datosGraficoServiciosPendientes, $sp->total);
            }

            $serviciosPendientes = Service::where('user_id', '=', Auth::user()->id)
                                        ->whereIn('status', $statusClient)
                                        ->orderBy('date', 'ASC')
                                        ->orderBy('time', 'ASC')
                                        ->take(5)
                                        ->get();

            $cantServiciosFinalizados = DB::table('services')
                                        ->where('user_id', '=', Auth::user()->id)
                                        ->where('status', '>=', 4)
                                        ->count();

            $graficaServiciosFinalizados = DB::table('services')
                                            ->where('user_id', '=', Auth::user()->id)
                                            ->where('status', '>=', 4)
                                            ->select(DB::raw('hour(time)'), DB::raw('COUNT(id) as total'))
                                            ->groupBy(DB::raw('hour(time)'))
                                            ->get();

            $datosGraficoServiciosFinalizados = array();
            foreach ($graficaServiciosFinalizados as $sf){
                array_push($datosGraficoServiciosFinalizados, $sf->total);
            }

            $domicilios = DB::table('addresses')
                            ->select('id', 'name')
                            ->where('user_id', '=', Auth::user()->id)
                            ->get();

            $cantDomicilios = $domicilios->count();

            $datosEnvio = DB::table('remember_data')
                            ->where('user_id', '=', Auth::user()->id)
                            ->where('type', '=', 'sender')
                            ->orderBy('alias', 'ASC')
                            ->get();
            $cantDatosEnvio = $datosEnvio->count();

            $datosRecogida = DB::table('remember_data')
                                ->where('user_id', '=', Auth::user()->id)
                                ->where('type', '=', 'receiver')
                                ->orderBy('alias', 'ASC')
                                ->get();
            $cantDatosRecogida = $datosRecogida->count();

            return view('client.index')->with(compact('datosGraficoServiciosPendientes', 'datosGraficoServiciosFinalizados', 'cantServiciosPendientes', 'cantServiciosFinalizados', 'serviciosPendientes', 'domicilios', 'cantDomicilios', 'datosEnvio', 'cantDatosEnvio', 'datosRecogida', 'cantDatosRecogida'));
       }
    }

    /**** Admin / Usuarios / Crear Usuario ****/
    public function create(){
        return view('admin.createUser');
    }

    /**** Admin / Usuarios / Crear Usuario / Guardar ****/
    public function store(Request $request){
        $messages = [
            'email.unique' => 'El correo ingresado ya se encuentra registrado.',
            'password.min' => 'La contraseña debe contener al menos 8 caracteres.',
            'password.max' => 'La contraseña debe contener máximo 25 caracteres.',
            'password.confirmed' => 'Las contraseñas ingresadas no coinciden.',
        ];

        $validator = Validator::make($request->all(), [
            'email' => 'unique:users',
            'password' => 'min:8|max:25|confirmed',
        ], $messages);

        if ($validator->fails()) {
            return redirect('admin/users/create')
                ->withErrors($validator)
                ->withInput();
        }

        $usuario = new User($request->all());
        $usuario->password = Hash::make($request->password);
        if ($usuario->role_id == 3){
            if (!is_null($request->services)){
                $usuario->services = 1;
            }
            if (!is_null($request->users)){
                $usuario->users = 1;
            }
             if (!is_null($request->financial)){
                $usuario->financial = 1;
            }
        }
        $usuario->save();

        return redirect('admin/users/create')->with('msj-exitoso', 'El usuario ha sido creado con éxito.');
    }

     /**** Admin / Usuarios / Editar Usuario ****/
    public function edit($id){
        $usuario = User::find($id);

        return view('admin.editUser')->with(compact('usuario'));
    }

    /**** Admin / Usuarios / Editar Usuario / Guardar Cambios ****/
    public function update(Request $request){
        $usuario = User::find($request->user_id);
        if ($usuario->email != $request->email){
            $checkEmail = DB::table('users')
                            ->where('email', '=', $request->email)
                            ->first();

            if (!is_null($checkEmail)){
                return redirect('admin/users/edit/'.$usuario->id)->with('msj-erroneo', 'El correo ingresado ya se encuentra registrado');
            }

            $usuario->email = $request->email;
        }

        if ($usuario->name != $request->name){
            $usuario->name = $request->name;
        }

        if (!is_null($request->password)){
            if ($request->password != $request->password2){
                return redirect('admin/users/edit/'.$usuario->id)->with('msj-erroneo', 'Las contraseñas ingresadas no coinciden.');
            }

            $usuario->password = Hash::make($request->password);
        }
        $usuario->role_id = $request->role_id;
        if ($usuario->role_id == 3){
            if (!is_null($request->services)){
                $usuario->services = 1;
            }else{
                $usuario->services = 0;
            }
            if (!is_null($request->users)){
                $usuario->users = 1;
            }else{
                $usuario->users = 0;
            }
            if (!is_null($request->financial)){
                $usuario->financial = 1;
            }else{
                $usuario->financial = 0;
            }
        }
        $usuario->save();

         return redirect('admin/users/edit/'.$usuario->id)->with('msj-exitoso', 'Los datos del usuario han sido actualizados con éxito.');
    }

    /**** Admin / Usuarios / Clientes ****/
    public function clients(){
        $usuarios = User::where('role_id', '=', 1)
                        ->orderBy('name', 'ASC')
                        ->get();

        $tipo_usuario = 'Clientes';

        return view('admin.usersList')->with(compact('usuarios', 'tipo_usuario'));
    }

    /**** Admin / Usuarios / Clientes / Datos Recordados ****/
    public function show_remember_data($client_id){
        $cliente = DB::table('users')
                        ->select('name', 'tradename')
                        ->where('id', '=', $client_id)
                        ->first();

        $datos = RememberData::where('user_id', '=', $client_id)
                        ->orderBy('alias', 'ASC')
                        ->get();

        return view('admin.clientRememberData')->with(compact('cliente', 'datos'));
    }

    /**** Admin / Usuarios / Brevers ****/
    public function brevers(){
        $usuarios = User::where('role_id', '=', 2)
                        ->orderBy('name', 'ASC')
                        ->get();

        $tipo_usuario = 'Brevers';

        return view('admin.usersList')->with(compact('usuarios', 'tipo_usuario'));
    }

    /**** Admin / Usuarios / Brevers / Hacer o Quitar VIP ****/
    public function make_vip($usuario, $accion){
        DB::table('users')
            ->where('id', '=', $usuario)
            ->update(['vip' => $accion]);

        if ($accion == 0){
            return redirect('admin/users/brevers')->with('msj-exitoso', 'El brever ha sido quitado de VIP con éxito.');
        }else{
            return redirect('admin/users/brevers')->with('msj-exitoso', 'El brever ha sido asigando a VIP con éxito.');
        }
    }

    /**** Admin / Usuarios / Brevers / Habilitar - Inhabilitar ****/
    public function change_status($usuario, $accion){
        $usuario = User::find($usuario);
        $usuario->status = $accion;
        $usuario->save();

        if ($accion == 0){
            if ($usuario->role_id == 1){
                return redirect('admin/users/clients')->with('msj-exitoso', 'El cliente ha sido inhabilitado con éxito.');
            }else if($usuario->role_id == 2){
                return redirect('admin/users/brevers')->with('msj-exitoso', 'El brever ha sido inhabilitado con éxito.');
            }else{
                return redirect('admin/users/admins')->with('msj-exitoso', 'El administrador ha sido inhabilitado con éxito.');
            }
        }else{
            if ($usuario->role_id == 1){
                return redirect('admin/users/clients')->with('msj-exitoso', 'El cliente ha sido habilitado con éxito.');
            }else if($usuario->role_id == 2){
                return redirect('admin/users/brevers')->with('msj-exitoso', 'El brever ha sido habilitado con éxito.');
            }else{
                return redirect('admin/users/admins')->with('msj-exitoso', 'El administrador ha sido habilitado con éxito.');
            }
        }
    }

    /**** Admin / Usuarios / Administradores ****/
    public function admins(){
        $usuarios = User::where('role_id', '=', 3)
                        ->where('id', '<>', 0)
                        ->orderBy('name', 'ASC')
                        ->get();

        $tipo_usuario = 'Administradores';

        return view('admin.usersList')->with(compact('usuarios', 'tipo_usuario'));
    }

    /**** Admin / Financiero / Estado de Cuenta Brever ****/
    public function brevers_record(){
        $ruta = Route::getFacadeRoot()->current()->uri();

        $brevers = User::where('role_id', '=', 2)
                        ->orderBy('name', 'ASC')
                        ->get();

        if ($ruta == "admin/financial/brevers-record"){
            $opcion = 'Estado de Cuenta';
        }else{
            $opcion = 'Recarga';
        }

        return view('admin.breversRecord')->with(compact('brevers', 'opcion'));
    }

    /**** Brever / Perfil ****/
    public function profile(){
        if (Auth::user()->role_id == 1){
            return view('client.profile');
        }else{
            return view('brever.profile');
        }
    }

    /**** Cliente - Brever / Perfil / Actualizar Datos ****/
    public function update_profile(Request $request){
        $usuario = User::find(Auth::user()->id);
        if ($usuario->email != $request->email){
            $checkEmail = DB::table('users')
                            ->where('email', '=', $request->email)
                            ->first();

            if (!is_null($checkEmail)){
                if (Auth::user()->role_id == 1){
                    return redirect('profile')->with('msj-erroneo', 'El correo ingresado ya se encuentra registrado');
                }else{
                    return redirect('brever/profile')->with('msj-erroneo', 'El correo ingresado ya se encuentra registrado');
                }
            }

            $usuario->email = $request->email;
        }

        if ($usuario->name != $request->name){
            $usuario->name = $request->name;
        }

        if ($usuario->tradename != $request->tradename){
            $usuario->tradename = $request->tradename;
        }

        if ($usuario->phone != $request->phone){
            $usuario->phone = $request->phone;
        }

        if ($usuario->license_plate != $request->license_plate){
            $usuario->license_plate = $request->license_plate;
        }

        if (!is_null($request->password)){
            if ($request->password != $request->password2){
                if (Auth::user()->role_id == 1){
                    return redirect('profile')->with('msj-erroneo', 'Las contraseñas ingresadas no coinciden.');
                }else{
                    return redirect('brever/profile')->with('msj-erroneo', 'Las contraseñas ingresadas no coinciden.');
                }
            }

            $usuario->password = Hash::make($request->password);
        }

        if ($request->hasFile('avatar')){
            $file = $request->file('avatar');
            $name = time().$file->getClientOriginalName();
            $file->move(public_path().'/images/users', $name);
            $usuario->avatar = $name;
        }

        $usuario->save();

        if (Auth::user()->role_id == 1){
            return redirect('profile')->with('msj-exitoso', 'Los datos del usuario han sido actualizados con éxito.');
        }else{
            return redirect('brever/profile')->with('msj-exitoso', 'Los datos del usuario han sido actualizados con éxito.');
        }
    }

    //**** Cliente / Perfil / Mis Domicilios ****//
    public function my_addresses(){
        $domicilios = Address::where('user_id', '=', Auth::user()->id)
                        ->orderBy('name', 'ASC')
                        ->get();

        return view('client.myAddresses')->with(compact('domicilios'));
    }

    //**** Cliente / Perfil / Nuevo Domicilio / Guardar Domicilio ****//
    public function store_address(Request $request){
        $domicilio = new Address($request->all());
        $domicilio->save();

        return redirect('my-addresses')->with('msj-exitoso', 'El domicilio ha sido creado con éxito.');
    }

    //**** Cliente / Perfil / Mis Domicilios / Eliminar ****//
    public function delete_address($id){
        Address::destroy($id);

        return redirect('my-addresses')->with('msj-exitoso', 'El domicilio ha sido eliminado con éxito.');
    }

    //**** Cliente / Perfil / Mis Datos Recordados ****//
    public function my_remember_data(){
        $datosEnvio = RememberData::where('user_id', '=', Auth::user()->id)
                        ->where('type', '=', 'sender')
                        ->orderBy('alias', 'ASC')
                        ->get();

        $datosRecogida = RememberData::where('user_id', '=', Auth::user()->id)
                            ->where('type', '=', 'receiver')
                            ->orderBy('alias', 'ASC')
                            ->get();

        return view('client.myRememberData')->with(compact('datosEnvio', 'datosRecogida'));
    }

    //**** Cliente / Perfil / Nuevos Datos / Guardar Datos ****//
    public function store_data(Request $request){
        $datos = new RememberData($request->all());
        $datos->user_id = Auth::user()->id;
        $datos->save();

        return redirect('my-remember-data')->with('msj-exitoso', 'Los datos han sido guardados con éxito.');
    }

    //**** Cliente / Perfil / Mis Datos Recordados / Actualizar Datos ****//
    public function edit_data($id){
        $datos = DB::table('remember_data')
                    ->where('id', '=', $id)
                    ->first();

        return response()->json(
            $datos
        );
    }

    //**** Cliente / Perfil / Mis Datos Recordados / Actualizar Datos / Guardar Datos ****//
    public function update_data(Request $request){
        $datos = RememberData::find($request->id);
        $datos->fill($request->all());
        $datos->save();

        if (Auth::user()->role_id == 1){
            return redirect('my-remember-data')->with('msj-exitoso', 'Los datos han sido actualizados con éxito.');
        }else{
            return redirect('admin/users/show-remember-data/'.$datos->user_id)->with('msj-exitoso', 'Los datos han sido actualizados con éxito.');
        }
    }

    //**** Cliente / Perfil / Mis Datos Recordados / Eliminar ****//
    public function delete_data(Request $request){
        RememberData::destroy($request->id);

        return redirect('my-remember-data')->with('msj-exitoso', 'Los datos han sido eliminados con éxito.');
    }

    //**** Admin / Usuarios / Administradores / Eliminar *****/
    /*public function delete($id){
        User::destroy($id);

        return redirect('admin/users/admins')->with('msj-exitoso', ' El administrador ha sido eliminado con éxito');
    }*/
}
