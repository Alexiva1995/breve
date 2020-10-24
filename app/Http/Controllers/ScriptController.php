<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; use App\Models\Balance; use App\Models\Service;
use App\Models\RememberData;
use DB;

class ScriptController extends Controller
{
    public function verificarServiciosCompletados(){
        $serviciosCompletados = Service::where('status', '=', 4)
                                    ->get();
        
        //$serviciosFaltantes = [];
        foreach ($serviciosCompletados as $servicio){
            $balance = DB::table('balances')
                        ->where('service_id', '=', $servicio->id)
                        ->first();
            
            if (is_null($balance)){
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
                //array_push($serviciosFaltantes, $servicio->id);
            }
        }
    }

    public function unir_campos_nombre_telefono(){
        $servicios = Service::where('sender', '=', NULL)
                        ->get();

        foreach ($servicios as $servicio) {
            $servicio->sender = $servicio->sender_name." ".$servicio->sender_phone;
            $servicio->receiver = $servicio->receiver_name." ".$servicio->receiver_phone;
            $servicio->save();
        }

        $datos = RememberData::get();

        foreach ($datos as $dato) {
            $dato->identification = $dato->name." ".$dato->phone;
            $dato->save();
        }

        echo "Script ejecutado correctamente";
    }


    public function corregir_servicios_transferencia(){
        $servicios = DB::table('services')
                        ->where('id', '>', 2049)
                        ->where('payment_method', '=', 'transferencia')
                        ->where('status', '=', 4)
                        ->get();
        
        foreach ($servicios as $servicio){
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
                $saldoBrever->brever_balance = $datosBrever->balance + $saldoBrever->brever_commission;
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
    }
}
