<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service; use App\Models\User; use App\Models\AdminNotification; use App\Models\Balance;
use App\Models\Notification; use App\Models\RememberData; use App\Models\Address; use App\Models\Log;
use Illuminate\Support\Facades\Hash;
use DB; use Auth; use Carbon\Carbon;

class ServiceController extends Controller
{
    /**** Listado de Servicios ****/
    /**** Cliente / Mis Servicios ****/
    /**** Brever / Servicios Disponibles ****/
    /**** Admin / Servicios / Listado de Servicios Pendientes ****/
    public function index(Request $request){
        if (Auth::user()->role_id == 1){
            $statusClient = [0, 1, 2, 3, 6];

            $servicios = Service::where('user_id', '=', Auth::user()->id)
                            ->whereIn('status', $statusClient)
                            ->orderBy('id', 'DESC')
                            ->get();

            return view('client.services')->with(compact('servicios'));
        }else if (Auth::user()->role_id == 2){
            $date = Carbon::now();
            $date->subHour(5);

            $fecha = $date->format('Y-m-d');
            $horaInicial = $date->format('H:i');
            $horaFinal = $date->addHour(4)->format('H:i');

            $servicios = Service::where('status', '=', 0)
                            ->where('date', '=', $fecha)
                            ->where('time', '>', $horaInicial)
                            ->where('time', '<=', $horaFinal)
                            ->orderBy('time', 'ASC')
                            ->get();
            
            $serviciosInmediatos = Service::where('type', '=', 'Inmediato')
                                        ->where('date', '=', NULL)
                                        ->where('status', '=', 0)
                                        ->orderBy('id', 'ASC')
                                        ->get();

            return view('brever.services')->with(compact('servicios', 'serviciosInmediatos'));
        }else{
            $servicios = Service::client($request->get('client'))
                            ->date($request->get('date'))
                            ->time($request->get('time'))
                            ->rate($request->get('rate'))
                            ->where('status', '=', 0)
                            ->orderBy('date', 'ASC')
                            ->orderBy('time', 'ASC')
                            ->get();
            
            $breves = DB::table('users')
                        ->select('id', 'name')
                        ->where('role_id', '=', 2)
                        ->where('status', '=', 1)
                        ->orderBy('name', 'ASC')
                        ->get();

            $clientes = DB::table('users')
                            ->select('id', 'name')
                            ->where('role_id', '=', 1)
                            ->where('status', '=', 1)
                            ->orderBy('name', 'ASC')
                            ->get();

            return view('admin.services')->with(compact('servicios', 'breves', 'clientes'));
        }
    }

    /**** Cliente - Admin / Crear Nuevo Servicio ****/
    public function create(){
        if (Auth::user()->role_id == 1){
            $domicilios = DB::table('addresses')
                            ->select('id', 'name')
                            ->where('user_id', '=', Auth::user()->id)
                            ->get();

            $datosEnvio = DB::table('remember_data')
                            ->where('user_id', '=', Auth::user()->id)
                            ->where('type', '=', 'sender')
                            ->where('admin', '=', 0)
                            ->orderBy('alias', 'ASC')
                            ->get();
            $cantDatosEnvio = $datosEnvio->count();

            $datosRecogida = DB::table('remember_data')
                                ->where('user_id', '=', Auth::user()->id)
                                ->where('type', '=', 'receiver')
                                ->where('admin', '=', 0)
                                ->orderBy('alias', 'ASC')
                                ->get();
            $cantDatosRecogida = $datosRecogida->count();

            $cantDomicilios = $domicilios->count();
            
            return view('client.createService')->with(compact('domicilios', 'cantDomicilios', 'datosEnvio', 'cantDatosEnvio', 'datosRecogida', 'cantDatosRecogida'));
        }else{
            $clientes = DB::table('users')
                            ->select('id', 'name', 'tradename')
                            ->where('role_id', '=', 1)
                            ->where('status', '=', 1)
                            ->orderBy('name', 'ASC')
                            ->get();

            return view('admin.createService')->with(compact('clientes'));
        }
    }

    /**** Almacenar nuevo servicio ****/
    public function store(Request $request){
        $servicio = new Service($request->all());
        if (isset($request->landing)){
            $servicio->landing = 1;
        }else{
            $servicio->landing = 0;
        }

        if ($servicio->type == 'Inmediato'){
            $servicio->date = NULL;
            $servicio->time = NULL;
        }
        if ($servicio->payment_method == 'reembolso'){
            $servicio->total = $servicio->rate + $servicio->refund_amount;
        }else{
            $servicio->total = $servicio->rate;
            $servicio->refund_amount = 0;
            if ($servicio->payment_method == 'efectivo-inicio'){
                $servicio->payment_method = 'efectivo';
                $servicio->payment_type = 'inicio';
            }else if ($servicio->payment_method == 'efectivo-final'){
                $servicio->payment_method = 'efectivo';
                $servicio->payment_type = 'final';
            }
        }
            
        $equipment_type = "";
        if (!is_null($request->equipment_type)){
            foreach ($request->equipment_type as $equipo) {
                if ($equipment_type == ""){
                    $equipment_type = $equipo;
                }else{
                    $equipment_type = $equipment_type."; ".$equipo;
                }    
            }
        }else{
            $equipment_type = "Maletin; MB; Canasta";
        }
        $servicio->equipment_type = $equipment_type;
    
        if (is_null($servicio->sender_address_opc)){
            $servicio->sender_address_opc = $servicio->sender_address;
        }
        if (is_null($servicio->receiver_address_opc)){
            $servicio->receiver_address_opc = $servicio->receiver_address;
        }
        if (is_null($servicio->sender_address)){
            $servicio->sender_address= $servicio->sender_address_opc;
        }
        if (is_null($servicio->receiver_address)){
            $servicio->receiver_address = $servicio->receiver_address_opc;
        }

        $servicio->save();
    
        if (!Auth::guest()){
            //Guardar datos de envío para futuros servicios
            if (!is_null($request->remember_sender_data)){
                $datosEnvio = new RememberData();
                if (Auth::user()->role_id == 1){
                    $datosEnvio->user_id = Auth::user()->id;
                    $datosEnvio->alias = $request->sender_data_alias;
                    $datosEnvio->admin = 0;
                }else{
                    $datosEnvio->user_id = $request->user_id;
                    $datosEnvio->alias_admin = $request->sender_data_alias;
                    $datosEnvio->admin = 1;
                }
                $datosEnvio->identification = $request->sender;
                $datosEnvio->neighborhood = $request->sender_neighborhood;
                $datosEnvio->address_opc = $request->sender_address_opc;
                $datosEnvio->type = 'sender';
                $datosEnvio->save();
            } 
            //Guardar datos de recogida para futuros servicios
            if (!is_null($request->remember_receiver_data)){
                $datosRecogida = new RememberData();
                if (Auth::user()->role_id == 1){
                    $datosRecogida->user_id = Auth::user()->id;
                    $datosRecogida->alias = $request->receiver_data_alias;
                    $datosRecogida->admin = 0;
                }else{
                    $datosRecogida->user_id = $request->user_id;
                    $datosRecogida->alias_admin = $request->receiver_data_alias;
                    $datosRecogida->admin = 1;
                }
                $datosRecogida->identification = $request->receiver;
                $datosRecogida->neighborhood = $request->receiver_neighborhood;
                $datosRecogida->address_opc = $request->receiver_address_opc;
                $datosRecogida->type = 'receiver';
                $datosRecogida->save();
            }
    
            if (!is_null($request->remember_markers)){
                $domicilio = new Address($request->all());
                $domicilio->user_id = Auth::user()->id;
                $domicilio->sender_address = $servicio->sender_address;
                $domicilio->receiver_address = $servicio->receiver_address;
                $domicilio->name = $request->markers_alias;
                $domicilio->save();
            }
        }
            
        if ( (Auth::guest()) || (Auth::user()->role_id != 3) ){
            $notificacion = new Notification();
            $notificacion->user_id = 0;
            $notificacion->service_id = $servicio->id;
            $notificacion->title = 'Nueva solicitud de servicio';
            $notificacion->icon = 'feather icon-plus-square';
            $notificacion->status = 0;
            $notificacion->save();
        }
    
        if (Auth::guest()){
            return redirect("https://api.whatsapp.com/send?phone=573508663301&text=He%20programado%20la%20solicitud%20de%20servicio%20n%C3%BAmero%20*".$servicio->id."*.%20Estar%C3%A9%20pendiente%20a%20la%20tarifa.");
        }else{
            if (Auth::user()->role_id == 1){
                return redirect('services/show/'.$servicio->id)->with('msj-exitoso', 'Su servicio ha sido añadido con éxito');
            }else if (Auth::user()->role_id == 2){
                return redirect("https://api.whatsapp.com/send?phone=573508663301&text=He%20programado%20la%20solicitud%20de%20servicio%20n%C3%BAmero%20*".$servicio->id."*.%20Estar%C3%A9%20pendiente%20a%20la%20tarifa.");
            }else{
                return redirect('admin/services/show/'.$servicio->id)->with('msj-exitoso', 'El servicio ha sido creado con éxito');
            }
        }
    }

    /**** Admin / Crear Nuevo Servicio / Cargar Datos Recordados de un Usuario ****/
    public function load_remember_data($user_id){
        $datosEnvio = DB::table('remember_data')
                        ->where('user_id', '=', $user_id)
                        ->where('type', '=', 'sender')
                        ->orderBy('alias', 'ASC')
                        ->get();
        $cantDatosEnvio = $datosEnvio->count();

        $datosRecogida = DB::table('remember_data')
                            ->where('user_id', '=', $user_id)
                            ->where('type', '=', 'receiver')
                            ->orderBy('alias', 'ASC')
                            ->get();

        $cantDatosRecogida = $datosRecogida->count();
            
        return response()->json(
            ["datosEnvio" => $datosEnvio,  "datosRecogida" => $datosRecogida, "cantDatosEnvio" => $cantDatosEnvio, "cantDatosRecogida" => $cantDatosRecogida]
        );
    }

    /**** Admin / Servicios / Listado de Servicios / Asignar Brever ****/
    public function add_brever(Request $request){
        $servicio = Service::find($request->service_id);
        $servicio->brever_id = $request->brever_id;
        if (isset($request->status)){
            $servicio->status = $request->status;
        }else{
            $servicio->status = 1;
        }
        if ($servicio->type == 'Inmediato'){
            $fecha = Carbon::now()->timezone("America/Bogota");
            $hora = Carbon::now()->timezone("America/Bogota");
            $servicio->date = $fecha->format('Y-m-d');
            $servicio->time = $hora->format('H:i:s');
        }
        $servicio->save();

        $notificacion = new Notification();
        $notificacion->user_id = $request->brever_id;
        $notificacion->service_id = $request->service_id;
        $notificacion->title = 'Tienes un nuevo servicio asignado';
        $notificacion->icon = 'feather icon-plus-square';
        $notificacion->status = 0;
        $notificacion->save();

        if ($servicio->user_id != 0){
            $notificacion2 = new Notification();
            $notificacion2->user_id = $servicio->user_id;
            $notificacion2->service_id = $servicio->id;
            $notificacion2->title = 'Su servicio ha sido asignado';
            $notificacion2->icon = 'feather icon-check-circle';
            $notificacion2->status = 0;
            $notificacion2->save();
        }
       

        if (isset($request->home)){
            return redirect('admin')->with('msj-exitoso', 'El brever ha sido asignado al servicio con éxito');
        }
        
        return redirect('admin/services')->with('msj-exitoso', 'El brever ha sido asignado al servicio con éxito');
    }

    /**** Detalles de un Servicio ****/
    /**** Admin / Servicios / Listado de Servicios / Ver Más ****/
    /**** Brever / Servicios / Listado de Servicios / Ver Más ****/
    public function show($id){
        if (Auth::user()->role_id == 1){
            $notificacionesPendientes = Notification::where('service_id','=', $id)
                                            ->where('user_id', '=', Auth::user()->id)
                                            ->get();
            
            foreach ($notificacionesPendientes as $not){
                if ($not->status == 0){
                    $not->status = 1;
                    $not->save();
                }
            }
                                            
            $servicio = Service::find($id);

            return view('client.showService')->with(compact('servicio'));
        }else if (Auth::user()->role_id == 2){
            $notificacionesPendientes = Notification::where('service_id','=', $id)
                                            ->where('user_id', '=', Auth::user()->id)
                                            ->get();
            
            foreach ($notificacionesPendientes as $not){
                if ($not->status == 0){
                    $not->status = 1;
                    $not->save();
                }
            }
            $servicio = Service::find($id);

            $servicio->sender_address_plus = str_replace(" ", "+", $servicio->sender_address);
            $servicio->receiver_address_plus = str_replace(" ", "+", $servicio->receiver_address);

            return view('brever.showService')->with(compact('servicio'));
        }else{
            $notificacionesPendientes = Notification::where('service_id','=', $id)
                                            ->where('user_id', '=', 0)
                                            ->get();
            
            foreach ($notificacionesPendientes as $not){
                if ($not->status == 0){
                    $not->status = 1;
                    $not->save();
                }
            }
            $servicio = Service::where('id', '=', $id)
                            ->withCount('logs')
                            ->first();
            
            return view('admin.showService')->with(compact('servicio'));
        }
    }

    /**** Editar un Servicio ****/
    /**** Admin / Servicios / Listado de Servicios / Ver / Editar ****/
    /**** Cliente / Servicios / Listado de Servicios / Ver / Editar ****/
    public function edit($id){
        $servicio = Service::find($id);
        $equipment_type = explode("; ", $servicio->equipment_type);
        if (Auth::user()->role_id == 1){
            return view('client.editService')->with(compact('servicio', 'equipment_type'));
        }else{    
            $breves = DB::table('users')
                        ->select('id', 'name')
                        ->where('role_id', '=', 2)
                        ->where('status', '=', 1)
                        ->orderBy('name', 'ASC')
                        ->get();

            return view('admin.editService')->with(compact('servicio', 'equipment_type', 'breves'));
        }
    }

    /**** Admin / Servicios / Listado de Servicios / Ver - Editar ****/
    public function update(Request $request){
       
        $servicio = Service::find($request->service_id);
 
        $checkCompleted = 0;
        if ( ($servicio->status != 4) && ($request->status == 4) ){
            $checkCompleted = 1;
        }
        $checkStatus = 0;
        if ($servicio->status != $request->status){
            $checkStatus = 1;
        }
        $checkRate = 0;
        if ( ($servicio->rate_status == 0) && ($request->rate_status == "1") ){
           $checkRate = 1;
        }

        $servicio->fill($request->all());
        if ($request->change_rate == 0){
            $servicio->rate = $request->initial_rate;
            $servicio->sender_latitude = $request->initial_sender_latitude;
            $servicio->sender_longitude = $request->initial_sender_longitude;
            $servicio->receiver_latitude = $request->initial_receiver_latitude;
            $servicio->receiver_longitude = $request->initial_receiver_longitude;
            $servicio->sender_address = $request->initial_sender_address;
            $servicio->receiver_address = $request->initial_receiver_address;
        }

        if ($request->status == "0"){
            $servicio->brever_id = null;
        }
        
        if (!is_null($request->equipment_type)){
            $equipment_type = "";
            foreach ($request->equipment_type as $equipo) {
                if ($equipment_type == ""){
                    $equipment_type = $equipo;
                }else{
                    $equipment_type = $equipment_type."; ".$equipo;
                }
                 
            }
            $servicio->equipment_type = $equipment_type;
        }
        
        if ($servicio->payment_method == 'reembolso'){
            $servicio->total = $servicio->rate + $servicio->additional_cost + $servicio->refund_amount;
        }else{
            $servicio->total = $servicio->rate + $servicio->additional_cost;
            $servicio->refund_amount = 0;
        }
        
        $servicio->save();

        if ($checkCompleted == 1){
            $servicioMarcado = DB::table('balances')
                                ->where('service_id', '=', $servicio->id)
                                ->first();
            
            if (is_null($servicioMarcado)){
                    $datosBrever = User::find($servicio->brever_id);

                    $ultBalance = Balance::orderBy('id', 'DESC')->first();

                    $saldoBrever = new Balance();
                    $saldoBrever->brever_id = $servicio->brever_id;
                    $saldoBrever->type = 'Domicilio Breve';
                    $saldoBrever->brever_commission = (($servicio->rate + $servicio->additional_cost) * 0.75);
                    $saldoBrever->breve_commission = (($servicio->rate + $servicio->additional_cost) - $saldoBrever->brever_commission);
                    if ($servicio->payment_method == 'transferencia'){
                        $saldoBrever->brever_balance = $datosBrever->balance + $saldoBrever->brever_commission;
                    }else{
                        $servicio->payment_status = 1;
                        $servicio->save();
                        $saldoBrever->brever_balance = $datosBrever->balance - $saldoBrever->breve_commission;
                    }
                    if (!is_null($ultBalance)){
                        $saldoBrever->breve_balance = $ultBalance->breve_balance + $saldoBrever->breve_commission;
                    }else{
                        $saldoBrever->breve_balance = $saldoBrever->breve_commission;
                    }
                    $saldoBrever->service_id = $servicio->id;
                    $saldoBrever->date = date('Y-m-d');
                    $saldoBrever->save();

                    $datosBrever->balance = $saldoBrever->brever_balance;
                    $datosBrever->save();
                
            }
        }

        if ($checkStatus == 1){
            if ($request->status == 1){
                $log = new Log();
                $log->service_id = $servicio->id;
                $log->user_id = Auth::user()->id;
                $log->action = 'Servicio Asignado a Brever';
                $log->save();
                
                $notificacion = new Notification();
                $notificacion->user_id = $servicio->brever_id;
                $notificacion->service_id = $servicio->id;
                $notificacion->title = 'Tienes un nuevo servicio asignado';
                $notificacion->icon = 'feather icon-plus-square';
                $notificacion->status = 0;
                $notificacion->save();

                if ($servicio->user_id != 0){
                    $notificacion2 = new Notification();
                    $notificacion2->user_id = $servicio->user_id;
                    $notificacion2->service_id = $servicio->id;
                    $notificacion2->title = 'Su servicio ha sido asignado';
                    $notificacion2->icon = 'feather icon-check-circle';
                    $notificacion2->status = 0;
                    $notificacion2->save();
                }
            }else if ($request->status == 2){
                $log = new Log();
                $log->service_id = $servicio->id;
                $log->user_id = Auth::user()->id;
                $log->action = 'Servicio Iniciado';
                $log->save();

                if ($servicio->user_id != 0){
                    $notificacion2 = new Notification();
                    $notificacion2->user_id = $servicio->user_id;
                    $notificacion2->service_id = $servicio->id;
                    $notificacion2->title = 'Su servicio ha sido iniciado';
                    $notificacion2->icon = 'feather icon-check-circle';
                    $notificacion2->status = 0;
                    $notificacion2->save();
                }
            }else if ($request->status == 3){
                $log = new Log();
                $log->service_id = $servicio->id;
                $log->user_id = Auth::user()->id;
                $log->action = 'Servicio Confirmado';
                $log->save();

                if ($servicio->user_id != 0){
                    $notificacion2 = new Notification();
                    $notificacion2->user_id = $servicio->user_id;
                    $notificacion2->service_id = $servicio->id;
                    $notificacion2->title = 'Su servicio ha sido confirmado';
                    $notificacion2->icon = 'feather icon-check-circle';
                    $notificacion2->status = 0;
                    $notificacion2->save();
                }
            }else if ($request->status == 4 ){
                $log = new Log();
                $log->service_id = $servicio->id;
                $log->user_id = Auth::user()->id;
                $log->action = 'Servicio Completado';
                $log->save();

                $notificacion = new Notification();
                $notificacion->user_id = $servicio->brever_id;
                $notificacion->service_id = $servicio->id;
                $notificacion->title = 'Su servicio ha sido completado';
                $notificacion->icon = 'feather icon-check-circle';
                $notificacion->status = 0;
                $notificacion->save();

                if ($servicio->user_id != 0){
                    $notificacion2 = new Notification();
                    $notificacion2->user_id = $servicio->user_id;
                    $notificacion2->service_id = $servicio->id;
                    $notificacion2->title = 'Su servicio ha sido completado';
                    $notificacion2->icon = 'feather icon-check-circle';
                    $notificacion2->status = 0;
                    $notificacion2->save();
                }
            }else if ($request->status == 5){
                $log = new Log();
                $log->service_id = $servicio->id;
                $log->user_id = Auth::user()->id;
                $log->action = 'Servicio Cancelado';
                $log->save();

                if (!is_null($servicio->brever_id)){
                    $notificacion = new Notification();
                    $notificacion->user_id = $servicio->brever_id;
                    $notificacion->service_id = $servicio->id;
                    $notificacion->title = 'Su servicio ha sido declinado';
                    $notificacion->icon = 'fa fa-times';
                    $notificacion->status = 0;
                    $notificacion->save();
                }

                if ($servicio->user_id != 0){
                    $notificacion2 = new Notification();
                    $notificacion2->user_id = $servicio->user_id;
                    $notificacion2->service_id = $servicio->id;
                    $notificacion2->title = 'Su servicio ha sido declinado';
                    $notificacion2->icon = 'fa fa-times';
                    $notificacion2->status = 0;
                    $notificacion2->save();
                }
            }

            if ( ($servicio->type == 'Inmediato') && (is_null($servicio->date)) ){
                $fecha = Carbon::now()->timezone("America/Bogota");
                $hora = Carbon::now()->timezone("America/Bogota");
                $servicio->date = $fecha->format('Y-m-d');
                $servicio->time = $hora->format('H:i:s');
            }
        }

        if ($checkRate == 1){
            $notificacion3 = new Notification();
            $notificacion3->user_id = $servicio->user_id;
            $notificacion3->service_id = $servicio->id;
            $notificacion3->title = 'Se ha establecido la tarifa de su servicio';
            $notificacion3->icon = 'feather icon-check-circle';
            $notificacion3->status = 0;
            $notificacion3->save();
        }

        if (Auth::user()->role_id == 1){
            return redirect('services/show/'.$servicio->id)->with('msj-exitoso', 'Los datos del servicio han sido actualizados con éxito');
        }else{
            return redirect('admin/services/show/'.$servicio->id)->with('msj-exitoso', 'Los datos del servicio han sido actualizados con éxito');
        }
    }

     /**** Admin / Servicios / Listado de Servicios / Ver Ruta ****/
    public function show_route($id){
        $servicio = Service::find($id);

        if (Auth::user()->role_id == 1){
             return view('client.showServiceRoute')->with(compact('servicio'));
        }else if (Auth::user()->role_id == 2){
            return view('brever.showServiceRoute')->with(compact('servicio'));
        }else if (Auth::user()->role_id == 3){
            return view('admin.showServiceRoute')->with(compact('servicio'));
        }
    }

     /**** Cliente / Servicios / Cancelar un Servicio ****/
    public function cancel($id){
        $servicio = Service::find($id);
        $servicio->status = 5;
        $servicio->save();

        $log = new Log();
        $log->service_id = $servicio->id;
        $log->user_id = Auth::user()->id;
        $log->action = 'Servicio declinado por el cliente';
        $log->save();

        if (!is_null($servicio->brever_id)){
            $notificacion = new Notification();
            $notificacion->user_id = $servicio->brever_id;
            $notificacion->service_id = $servicio->id;
            $notificacion->title = 'Su servicio ha sido declinado';
            $notificacion->icon = 'fa fa-times';
            $notificacion->status = 0;
            $notificacion->save();
        }

        $notificacion2 = new Notification();
        $notificacion2->user_id = 0;
        $notificacion2->service_id = $servicio->id;
        $notificacion2->title = 'El cliente ha declinado el servicio';
        $notificacion2->icon = 'fa fa-times';
        $notificacion2->status = 0;
        $notificacion2->save();

        return redirect('services')->with('msj-exitoso', 'El servicio ha sido cancelado con éxito.');
    }

    /**** Listado de Servicios Finalizados ****/
    /**** Cliente / Servicios Finalizados ****/
    /**** Admin / Servicios / Servicios Completados ****/
    /**** Brever / Servicios / Servicios Completados ****/
    public function completed(Request $request){
        if (Auth::user()->role_id == 1){
            $servicios = Service::where('user_id', '=', Auth::user()->id)
                            ->where('status', '>=', 4)
                            ->orderBy('id', 'DESC')
                            ->take(100)
                            ->get();

            return view('client.servicesRecord')->with(compact('servicios'));
        }else if (Auth::user()->role_id == 2){
            $servicios = Service::where('brever_id', '=', Auth::user()->id)
                            ->where('status', '=', 4)
                            ->orderBy('date', 'DESC')
                            ->orderBy('time', 'DESC')
                            ->take(100)
                            ->get();

            return view('brever.servicesCompleted')->with(compact('servicios'));
        }else{
            $fechaActual = Carbon::now();
            $fechaLimite = $fechaActual->subDays(45);
            $servicios = Service::client($request->get('client'))
                            ->brever($request->get('brever'))
                            ->rate($request->get('rate'))
                            ->date($request->get('date'))
                            ->time($request->get('time'))
                            ->where('status', '=', 4)
                            ->where('date', '>=', $fechaLimite)
                            ->orderBy('date', 'DESC')
                            ->orderBy('time', 'DESC')
                            ->get();

            $brevers = DB::table('users')
                        ->select('id', 'name')
                        ->where('role_id', '=', 2)
                        ->orderBy('name', 'ASC')
                        ->get();

            $clientes = DB::table('users')
                            ->select('id', 'name')
                            ->where('role_id', '=', 1)
                            ->orderBy('name', 'ASC')
                            ->get();

            return view('admin.servicesCompleted')->with(compact('servicios', 'clientes', 'brevers'));
        }
    }

    /**** Listado de Servicios Asignados****/
    /**** Admin / Servicios / Servicios Asignados ****/
    /**** Brever / Servicios / Servicios Asignados ****/
    public function assigned(Request $request){
        if (Auth::user()->role_id == 2){
            $status = [1, 2, 3, 6];
            $servicios = Service::where('brever_id', '=', Auth::user()->id)
                            ->whereIn('status', $status)
                            ->orderBy('date', 'ASC')
                            ->orderBy('time', 'ASC')
                            ->get();

            return view('brever.servicesAssigned')->with(compact('servicios'));
        }else{
            $servicios = Service::client($request->get('client'))
                            ->brever($request->get('brever'))
                            ->rate($request->get('rate'))
                            ->date($request->get('date'))
                            ->time($request->get('time'))
                            ->where('status', '=', 1)
                            ->orderBy('date', 'ASC')
                            ->orderBy('time', 'ASC')
                            ->get();

            $clientes = DB::table('users')
                            ->select('id', 'name')
                            ->where('role_id', '=', 1)
                            ->orderBy('name', 'ASC')
                            ->get();

            $brevers = DB::table('users')
                            ->select('id', 'name')
                            ->where('role_id', '=', 2)
                            ->orderBy('name', 'ASC')
                            ->get();

            return view('admin.servicesAssigned')->with(compact('servicios', 'clientes', 'brevers'));
        }   
       
    }

    /**** Brever / Detalles de Servicio / Llegada a punto inicial ****/
    public function arrive(Request $request){
        $servicio = Service::find($request->service_id);

        $servicio->status = 6;
        $servicio->save();
        
        $checkLog = Log::where('service_id', '=', $servicio->id)
                        ->where('action', '=', 'Llegada al punto inicial')
                        ->first();
        
        if (is_null($checkLog)){
            $log = new Log();
            $log->service_id = $servicio->id;
            $log->user_id = Auth::user()->id;
            $log->action = 'Llegada al punto inicial';
            $log->save();
        }

        return redirect(redirect()->getUrlGenerator()->previous())->with('msj-exitoso', 'La llegada al punto inicial ha sido marcada con éxito.'); 
    }

    /**** Admin / Servicios / Servicios Asignados / Iniciar ****/
    /**** Brever / Detalles de Servicio / Iniciar Servicio (Llegada a punto inicial) ****/
    public function start($id = 0, Request $request){
        if ($id == 0){
            $servicio = Service::find($request->service_id);
        }else{
           $servicio = Service::find($id); 
        }
        
        $servicio->status = 2;
        $servicio->save();
        
        $checkLog = Log::where('service_id', '=', $servicio->id)
                        ->where('action', '=', 'Servicio Iniciado')
                        ->first();
        
        if (is_null($checkLog)){
            $log = new Log();
            $log->service_id = $servicio->id;
            $log->user_id = Auth::user()->id;
            $log->action = 'Servicio Iniciado';
            $log->save();
        }

        if (Auth::user()->role_id == 2){
            return redirect(redirect()->getUrlGenerator()->previous())->with('msj-exitoso', 'El servicio ha sido iniciado con éxito.');
        }else{
            return redirect('admin/services/confirmed')->with('msj-exitoso', 'El servicio ha sido iniciado con éxito.');
        }
        
    }

    /**** Listado de Servicios Iniciados ****/
    /**** Admin / Servicios / Servicios Iniciados ****/
    public function started(Request $request){
        if (Auth::user()->role_id == 2){
            $servicios = Service::where('brever_id', '=', Auth::user()->id)
                            ->where('status', '=', 2)
                            ->orderBy('date', 'ASC')
                            ->orderBy('time', 'ASC')
                            ->get();

            return view('brever.servicesStarted')->with(compact('servicios'));
        }else{
            $status = [2, 6];
            $servicios = Service::client($request->get('client'))
                            ->brever($request->get('brever'))
                            ->rate($request->get('rate'))
                            ->date($request->get('date'))
                            ->time($request->get('time'))
                            ->whereIn('status', $status)
                            ->orderBy('date', 'ASC')
                            ->orderBy('time', 'ASC')
                            ->get();

            $clientes = DB::table('users')
                            ->select('id', 'name')
                            ->where('role_id', '=', 1)
                            ->orderBy('name', 'ASC')
                            ->get();

            $brevers = DB::table('users')
                            ->select('id', 'name')
                            ->where('role_id', '=', 2)
                            ->orderBy('name', 'ASC')
                            ->get();

            return view('admin.servicesStarted')->with(compact('servicios', 'clientes', 'brevers'));
        }
    }

    /**** Admin / Servicios / Servicios Asignados / Confirmar ****/
    public function confirm($id = 0){
        $servicio = Service::find($id); 
        $servicio->status = 3;
        $servicio->save();
        
        $checkLog = Log::where('service_id', '=', $servicio->id)
                        ->where('action', '=', 'Servicio Confirmado')
                        ->first();
        
        if (is_null($checkLog)){
            $log = new Log();
            $log->service_id = $servicio->id;
            $log->user_id = Auth::user()->id;
            $log->action = 'Servicio Confirmado';
            $log->save();
        }

        return redirect('admin/services/assigned')->with('msj-exitoso', 'El servicio ha sido confirmado con éxito.');  
    }

    /**** Listado de Servicios Confirmados ****/
    /**** Admin / Servicios / Servicios Confirmados ****/
    public function confirmed(Request $request){
        $servicios = Service::client($request->get('client'))
                        ->brever($request->get('brever'))
                        ->rate($request->get('rate'))
                        ->date($request->get('date'))
                        ->time($request->get('time'))
                        ->where('status', '=', 3)
                        ->orderBy('date', 'ASC')
                        ->orderBy('time', 'ASC')
                        ->get();

        $clientes = DB::table('users')
                        ->select('id', 'name')
                        ->where('role_id', '=', 1)
                        ->orderBy('name', 'ASC')
                        ->get();

        $brevers = DB::table('users')
                        ->select('id', 'name')
                        ->where('role_id', '=', 2)
                        ->orderBy('name', 'ASC')
                        ->get();

        return view('admin.servicesConfirmed')->with(compact('servicios', 'clientes', 'brevers'));
    }

    /**** Admin / Servicios / Servicios Confirmados / Completar ****/
    /**** Brever / Servicios  / Completar ****/
    public function complete($id = 0, Request $request){
        if ($id == 0){
            $servicio = Service::find($request->service_id);
        }else{
           $servicio = Service::find($id); 
        }

        $servicio->status = 4;
        if (isset($request->brever_observations)){
            $servicio->brever_observations = $request->brever_observations;
        }
        if ($request->hasFile('photo')){
            $file = $request->file('photo');
            $name = time().$file->getClientOriginalName();
            $file->move(public_path().'/images/services', $name);
            $servicio->delivery_photo = $name;
        }
        $servicio->save();

        $servicioMarcado = DB::table('balances')
                            ->where('service_id', '=', $servicio->id)
                            ->first();

        if (is_null($servicioMarcado)){
                $datosBrever = User::find($servicio->brever_id);

                $ultBalance = Balance::orderBy('id', 'DESC')->first();

                $saldoBrever = new Balance();
                $saldoBrever->brever_id = $servicio->brever_id;
                
                $saldoBrever->type = 'Domicilio Breve';
                $saldoBrever->brever_commission = (($servicio->rate + $servicio->additional_cost) * 0.75);
                $saldoBrever->breve_commission = (($servicio->rate + $servicio->additional_cost) - $saldoBrever->brever_commission);
                if ($servicio->payment_method == 'transferencia'){
                    $saldoBrever->brever_balance = $datosBrever->balance + $saldoBrever->brever_commission;
                }else{
                    $servicio->payment_status = 1;
                    $servicio->save();
                    $saldoBrever->brever_balance = $datosBrever->balance - $saldoBrever->breve_commission;
                }
                if (!is_null($ultBalance)){
                    $saldoBrever->breve_balance = $ultBalance->breve_balance + $saldoBrever->breve_commission;
                }else{
                    $saldoBrever->breve_balance = $saldoBrever->breve_commission;
                }
                $saldoBrever->service_id = $servicio->id;
                $saldoBrever->date = date('Y-m-d');
                $saldoBrever->save();

                $datosBrever->balance = $saldoBrever->brever_balance;
                $datosBrever->save();
        }
        
        $checkLog = Log::where('service_id', '=', $servicio->id)
                        ->where('action', '=', 'Servicio Completado')
                        ->first();
        
        if (is_null($checkLog)){
            $log = new Log();
            $log->service_id = $servicio->id;
            $log->user_id = Auth::user()->id;
            $log->action = 'Servicio Completado';
            $log->save();
        }

        if (Auth::user()->role_id == 2){
            if ($servicio->user_id != 0){
                $notificacion = new Notification();
                $notificacion->user_id = $servicio->user_id;
                $notificacion->service_id = $servicio->id;
                $notificacion->title = 'Su servicio ha sido completado';
                $notificacion->icon = 'feather icon-check-circle';
                $notificacion->status = 0;
                $notificacion->save();
            }
            
            $checkNot = DB::table('notifications')
                            ->where('user_id', '=', 0)
                            ->where('service_id', '=', $servicio->id)
                            ->where('title', '=', 'El Brever ha completado el servicio')
                            ->first();
            
            if (is_null($checkNot)){             
                $notificacion2 = new Notification();
                $notificacion2->user_id = 0;
                $notificacion2->service_id = $servicio->id;
                $notificacion2->title = 'El Brever ha completado el servicio';
                $notificacion2->icon = 'feather icon-check-circle';
                $notificacion2->status = 0;
                $notificacion2->save();
            }

            return redirect('brever')->with('msj-exitoso', 'El servicio ha sido completado con éxito.');
        }else{
            $notificacion = new Notification();
            $notificacion->user_id = $servicio->brever_id;
            $notificacion->service_id = $servicio->id;
            $notificacion->title = 'Su servicio ha sido completado';
            $notificacion->icon = 'feather icon-check-circle';
            $notificacion->status = 0;
            $notificacion->save();
            
            if ($servicio->user_id != 0){
                $notificacion2 = new Notification();
                $notificacion2->user_id = $servicio->user_id;
                $notificacion2->service_id = $servicio->id;
                $notificacion2->title = 'Su servicio ha sido completado';
                $notificacion2->icon = 'feather icon-check-circle';
                $notificacion2->status = 0;
                $notificacion2->save();
            }

            return redirect('admin/services/started')->with('msj-exitoso', 'El servicio ha sido completado con éxito.');
        }
    }

    /**** Listado de Servicios Cancelados ****/
    /**** Admin / Servicios / Servicios Cancelados ****/
    /**** Brever / Servicios / Servicios Cancelados ****/
    public function canceled(Request $request){
        if (Auth::user()->role_id == 2){
            $servicios = Service::where('brever_id', '=', Auth::user()->id)
                            ->where('status', '=', 5)
                            ->orderBy('date', 'DESC')
                            ->orderBy('time', 'DESC')
                            ->get();

            return view('brever.servicesCanceled')->with(compact('servicios'));
        }else{
            $servicios = Service::client($request->get('client'))
                            ->brever($request->get('brever'))
                            ->rate($request->get('rate'))
                            ->date($request->get('date'))
                            ->time($request->get('time'))
                            ->where('status', '=', 5)
                            ->orderBy('date', 'DESC')
                            ->orderBy('time', 'DESC')
                            ->get();

            $brevers = DB::table('users')
                        ->select('id', 'name')
                        ->where('role_id', '=', 2)
                        ->orderBy('name', 'ASC')
                        ->get();

            $clientes = DB::table('users')
                            ->select('id', 'name')
                            ->where('role_id', '=', 1)
                            ->orderBy('name', 'ASC')
                            ->get();

            return view('admin.servicesCanceled')->with(compact('servicios', 'clientes', 'brevers'));
        }
        
    }

    /**** Histórico de Servicios ****/
    /**** Admin / Financiero / Histórico de Servicios****/
    public function record(Request $request){
        $fechaActual = Carbon::now();
        $fechaLimite = $fechaActual->subDays(45);
        $servicios = Service::client($request->get('client'))
                        ->brever($request->get('brever'))
                        ->rate($request->get('rate'))
                        ->date($request->get('date'))
                        ->time($request->get('time'))
                        ->status($request->get('status'))
                        ->where('date', '>=', $fechaLimite)
                        ->orderBy('date', 'DESC')
                        ->orderBy('time', 'DESC')
                        ->get();

        $brevers = DB::table('users')
                        ->select('id', 'name')
                        ->where('role_id', '=', 2)
                        ->orderBy('name', 'ASC')
                        ->get();

        $clientes = DB::table('users')
                        ->select('id', 'name')
                        ->where('role_id', '=', 1)
                        ->orderBy('name', 'ASC')
                        ->get();

        return view('admin.servicesRecord')->with(compact('servicios', 'clientes', 'brevers'));
    }

     /**** Confirmar un Pago Por Transferencia ****/
    /**** Admin / Financiero / Pagos Pendientes / Confirmar Pago ****/
    public function confirm_payment($id){
        $servicio = Service::find($id);
        $servicio->payment_status = 1;
        $servicio->save();
        
        return redirect('admin/financial/pending-payments')->with('msj-exitoso', 'El pago ha sido confirmado con éxito.');
    }

    /**** Brever / Inicio / Listado de Servicios Disponibles / Tomar Servicio ****/
    public function take(Request $request){
        $servicio = Service::find($request->service_id);
        $servicio->brever_id = Auth::user()->id;
        if ($servicio->type == 'Inmediato'){
            $fecha = Carbon::now()->timezone("America/Bogota");
            $hora = Carbon::now()->timezone("America/Bogota");
            $servicio->date = $fecha->format('Y-m-d');
            $servicio->time = $hora->format('H:i:s');
            if ( (Auth::user()->vip == 1) && ($servicio->user_id != 0) ){
                $servicio->status = 3;
            }else{
                $servicio->status = 1;
            }
        }else{
            $servicio->status = 1;
        }
        $servicio->save();
        
        if ($servicio->type == 'Inmediato'){
            $checkLog = Log::where('service_id', '=', $servicio->id)
                        ->where('action', '=', 'Servicio Iniciado')
                        ->first();
        
            if (is_null($checkLog)){
                $log = new Log();
                $log->service_id = $servicio->id;
                $log->user_id = Auth::user()->id;
                $log->action = 'Servicio Iniciado';
                $log->save();
            }

            if ($servicio->user_id != 0){
                $notificacion = new Notification();
                $notificacion->user_id = $servicio->user_id;
                $notificacion->service_id = $servicio->id;
                $notificacion->title = 'Su servicio ha sido iniciado';
                $notificacion->icon = 'feather icon-check-circle';
                $notificacion->status = 0;
                $notificacion->save();
            }

            $notificacion2 = new Notification();
            $notificacion2->user_id = 0;
            $notificacion2->service_id = $servicio->id;
            $notificacion2->title = 'Un brever ha iniciado un servicio inmediato';
            $notificacion2->icon = 'feather icon-check-circle';
            $notificacion2->status = 0;
            $notificacion2->save();

            return redirect('brever')->with('msj-exitoso', 'El servicio le ha sido asignado con éxito.');
        }else{
            $checkLog = Log::where('service_id', '=', $servicio->id)
                        ->where('action', '=', 'Servicio tomado por Brever')
                        ->first();
        
            if (is_null($checkLog)){
                $log = new Log();
                $log->service_id = $servicio->id;
                $log->user_id = Auth::user()->id;
                $log->action = 'Servicio tomado por Brever';
                $log->save();
            }

            if ($servicio->user_id != 0){
                $notificacion = new Notification();
                $notificacion->user_id = $servicio->user_id;
                $notificacion->service_id = $servicio->id;
                $notificacion->title = 'Su servicio ha sido asignado';
                $notificacion->icon = 'feather icon-check-circle';
                $notificacion->status = 0;
                $notificacion->save();
            }

            $notificacion2 = new Notification();
            $notificacion2->user_id = 0;
            $notificacion2->service_id = $servicio->id;
            $notificacion2->title = 'Un brever ha tomado el servicio';
            $notificacion2->icon = 'feather icon-check-circle';
            $notificacion2->status = 0;
            $notificacion2->save();

            return redirect('brever')->with('msj-exitoso', 'El servicio le ha sido asignado con éxito');
        }
        
    }

    public function load_address($id){
        $domicilio = DB::table('addresses')
                        ->where('id', '=', $id)
                        ->first();

        return response()->json(
            $domicilio
        );
    }

    public function load_data($id){
        $datos = DB::table('remember_data')
                    ->where('id', '=', $id)
                    ->first();

        return response()->json(
            $datos
        );
    }

    public function change_status(Request $request){
        $servicio = Service::find($request->service_id);
        $servicio->status = $request->status;
        if ($servicio->status == 0){
            $servicio->brever_id = NULL;
        }
        if ($servicio->status == 4){
            $servicioMarcado = DB::table('balances')
                                ->where('service_id', '=', $servicio->id)
                                ->first();

            if (is_null($servicioMarcado)){
                $datosBrever = User::find($servicio->brever_id);

                $ultBalance = Balance::orderBy('id', 'DESC')->first();

                $saldoBrever = new Balance();
                $saldoBrever->brever_id = $servicio->brever_id;
                
                $saldoBrever->type = 'Domicilio Breve';
                $saldoBrever->brever_commission = (($servicio->rate + $servicio->additional_cost) * 0.75);
                $saldoBrever->breve_commission = (($servicio->rate + $servicio->additional_cost) - $saldoBrever->brever_commission);
                if ($servicio->payment_method == 'transferencia'){
                    $saldoBrever->brever_balance = $datosBrever->balance + $saldoBrever->brever_commission;
                }else{
                    $servicio->payment_status = 1;
                    $saldoBrever->brever_balance = $datosBrever->balance - $saldoBrever->breve_commission;
                }
                if (!is_null($ultBalance)){
                    $saldoBrever->breve_balance = $ultBalance->breve_balance + $saldoBrever->breve_commission;
                }else{
                    $saldoBrever->breve_balance = $saldoBrever->breve_commission;
                }
                $saldoBrever->service_id = $servicio->id;
                $saldoBrever->date = date('Y-m-d');
                $saldoBrever->save();

                $datosBrever->balance = $saldoBrever->brever_balance;
                $datosBrever->save();
            }
        }

        $servicio->save();

        if ($request->status == 1){
            $checkLog = Log::where('service_id', '=', $servicio->id)
                        ->where('action', '=', 'Servicio Asignado a Brever')
                        ->first();
        
            if (is_null($checkLog)){
                $log = new Log();
                $log->service_id = $servicio->id;
                $log->user_id = Auth::user()->id;
                $log->action = 'Servicio Asignado a Brever';
                $log->save();
            }

            if ($servicio->user_id != 0){
                $notificacion = new Notification();
                $notificacion->user_id = $servicio->user_id;
                $notificacion->service_id = $servicio->id;
                $notificacion->title = 'Su servicio ha sido asignado';
                $notificacion->icon = 'feather icon-check-circle';
                $notificacion->status = 0;
                $notificacion->save();
            }

            $notificacion = new Notification();
            $notificacion->user_id = $servicio->brever_id;
            $notificacion->service_id = $servicio->id;
            $notificacion->title = 'Se le ha asignado un asignado';
            $notificacion->icon = 'feather icon-check-circle';
            $notificacion->status = 0;
            $notificacion->save();
        }else if ($request->status == 2){
            $checkLog = Log::where('service_id', '=', $servicio->id)
                        ->where('action', '=', 'Servicio Iniciado')
                        ->first();
        
            if (is_null($checkLog)){
                $log = new Log();
                $log->service_id = $servicio->id;
                $log->user_id = Auth::user()->id;
                $log->action = 'Servicio Iniciado';
                $log->save();
            }

            if ($servicio->user_id != 0){
                $notificacion = new Notification();
                $notificacion->user_id = $servicio->user_id;
                $notificacion->service_id = $servicio->id;
                $notificacion->title = 'Su servicio ha sido iniciado';
                $notificacion->icon = 'feather icon-check-circle';
                $notificacion->status = 0;
                $notificacion->save();
            }
        }else if ($request->status == 3){
            $checkLog = Log::where('service_id', '=', $servicio->id)
                        ->where('action', '=', 'Servicio Confirmado')
                        ->first();
        
            if (is_null($checkLog)){
                $log = new Log();
                $log->service_id = $servicio->id;
                $log->user_id = Auth::user()->id;
                $log->action = 'Servicio Confirmado';
                $log->save();
            }

            if ($servicio->user_id != 0){
                $notificacion = new Notification();
                $notificacion->user_id = $servicio->user_id;
                $notificacion->service_id = $servicio->id;
                $notificacion->title = 'Su servicio ha sido confirmado';
                $notificacion->icon = 'feather icon-check-circle';
                $notificacion->status = 0;
                $notificacion->save();
            }
        }else if ($request->status == 4 ){
             $checkLog = Log::where('service_id', '=', $servicio->id)
                        ->where('action', '=', 'Servicio Completado')
                        ->first();
        
            if (is_null($checkLog)){
                $log = new Log();
                $log->service_id = $servicio->id;
                $log->user_id = Auth::user()->id;
                $log->action = 'Servicio Completado';
                $log->save();
            }

            if ($servicio->user_id != 0){
                $notificacion = new Notification();
                $notificacion->user_id = $servicio->user_id;
                $notificacion->service_id = $servicio->id;
                $notificacion->title = 'Su servicio ha sido completado';
                $notificacion->icon = 'feather icon-check-circle';
                $notificacion->status = 0;
                $notificacion->save();
            }

            $notificacion2 = new Notification();
            $notificacion2->user_id = $servicio->brever_id;
            $notificacion2->service_id = $servicio->id;
            $notificacion2->title = 'Su servicio ha sido completado';
            $notificacion2->icon = 'feather icon-check-circle';
            $notificacion2->status = 0;
            $notificacion2->save();
        }else if ($request->status == 5){
            $checkLog = Log::where('service_id', '=', $servicio->id)
                        ->where('action', '=', 'Servicio Cancelado')
                        ->first();
        
            if (is_null($checkLog)){
                $log = new Log();
                $log->service_id = $servicio->id;
                $log->user_id = Auth::user()->id;
                $log->action = 'Servicio Cancelado';
                $log->save();
            }

            if ($servicio->user_id != 0){
                $notificacion = new Notification();
                $notificacion->user_id = $servicio->user_id;
                $notificacion->service_id = $servicio->id;
                $notificacion->title = 'Su servicio ha sido cancelado';
                $notificacion->icon = 'fa fa-times';
                $notificacion->status = 0;
                $notificacion->save();
            }

            if (!is_null($servicio->brever_id)){
                $notificacion2 = new Notification();
                $notificacion2->user_id = $servicio->brever_id;
                $notificacion2->service_id = $servicio->id;
                $notificacion2->title = 'Su servicio ha sido cancelado';
                $notificacion2->icon = 'feather icon-check-circle';
                $notificacion2->status = 0;
                $notificacion2->save();
            }
        }else if ($request->status == 6){
            $checkLog = Log::where('service_id', '=', $servicio->id)
                        ->where('action', '=', 'Llegada a Punto Inicial')
                        ->first();
        
            if (is_null($checkLog)){
                $log = new Log();
                $log->service_id = $servicio->id;
                $log->user_id = Auth::user()->id;
                $log->action = 'Llegada a Punto Inicial';
                $log->save();
            }

            if ($servicio->user_id != 0){
                $notificacion = new Notification();
                $notificacion->user_id = $servicio->user_id;
                $notificacion->service_id = $servicio->id;
                $notificacion->title = 'El brever ha marcado la llegada al Punto Inicial';
                $notificacion->icon = 'fa fa-times';
                $notificacion->status = 0;
                $notificacion->save();
            }
        }

        if ( ($servicio->type == 'Inmediato') && (is_null($servicio->date)) ){
            $fecha = Carbon::now()->timezone("America/Bogota");
            $hora = Carbon::now()->timezone("America/Bogota");
            $servicio->date = $fecha->format('Y-m-d');
            $servicio->time = $hora->format('H:i:s');
            $servicio->save();
        }

        return redirect('admin')->with('msj-exitoso', 'El estado del servicio ha sido cambiado con éxito');
    }
}
