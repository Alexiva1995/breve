<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Balance; use App\Models\User; use App\Models\Service; use App\Models\Transfer;
use Auth; use DB;

class FinancialController extends Controller{

	//**** Admin / Financiero / Estado de Cuenta Brever / Ver Estado de Cuenta ****/
    public function show_account_status(Request $request){
        if (isset($request->brever_id)){
            $brever = User::find($request->brever_id);

            $fechaInicial = $request->year."-".$request->month."-01";
            $fechaFinal = $request->year."-".$request->month."-31";
            $movimientos = Balance::where('brever_id', '=', $request->brever_id)
                                ->where('date', '>=', $fechaInicial)
                                ->where('date', '<=', $fechaFinal)
                                ->orderBy('id', 'DESC')
                                ->get();

            switch ($request->month) {
                case '01': $mes = 'Enero'; break;
                case '02': $mes = 'Febrero'; break;
                case '03': $mes = 'Marzo'; break;
                case '04': $mes = 'Abril'; break;
                case '05': $mes = 'Mayo'; break;
                case '06': $mes = 'Junio'; break;
                case '07': $mes = 'Julio'; break;
                case '08': $mes = 'Agosto'; break;
                case '09': $mes = 'Septiembre'; break;
                case '10': $mes = 'Octubre'; break;
                case '11': $mes = 'Noviembre'; break;
                case '12': $mes = 'Diciembre'; break;
            }

            $ano = $request->year;
        }else{
            $brever = User::find($request->brever_id2);

            $movimientos = Balance::where('brever_id', '=', $request->brever_id2)
                                ->where('date', '>=', $request->initial_date)
                                ->where('date', '<=', $request->final_date)
                                ->orderBy('id', 'DESC')
                                ->get();

            $fechaInicial = $request->initial_date;
            $fechaFinal = $request->final_date;
            $mes = null;
            $ano = null;
        }
    	
    	return view('admin.showBreverAccountStatus')->with(compact('brever', 'movimientos', 'fechaInicial', 'fechaFinal', 'mes', 'ano'));
    }

    //**** Admin / Financiero / Saldo Breve / Recargar ****/
    public function recharger(Request $request){
        $brever = User::find($request->brever_id);

        $ultBalance = Balance::orderBy('id', 'DESC')->first();

        $saldoBrever = new Balance();
        $saldoBrever->brever_id = $request->brever_id;
        $saldoBrever->type = 'Recarga';
        $saldoBrever->brever_commission = $request->recharge_amount;
        $saldoBrever->breve_commission = 0;
        $saldoBrever->brever_balance = $brever->balance + $request->recharge_amount;
        if (!is_null($ultBalance)){
            $saldoBrever->breve_balance = $ultBalance->breve_balance;
        }else{
            $saldoBrever->breve_balance = 0;
        }
        $saldoBrever->date = date('Y-m-d');
        $saldoBrever->description = $request->description;
        $saldoBrever->save();

        $brever->balance = $saldoBrever->brever_balance;
        $brever->save();

        return redirect('admin/financial/brever-recharge')->with('msj-exitoso', 'La recarga ha sido procesada con éxito.');
    }

    //**** Admin / Financiero / Saldo Breve / Descontar ****/
    public function discount(Request $request){
        $brever = User::find($request->brever_id);
  
        $ultBalance = Balance::orderBy('id', 'DESC')->first();

        $saldoBrever = new Balance();
        $saldoBrever->brever_id = $request->brever_id;
        $saldoBrever->type = 'Descuento';
        $saldoBrever->brever_commission = 0;
        $saldoBrever->breve_commission = $request->discount_amount;
        $saldoBrever->brever_balance = $brever->balance - $request->discount_amount;
        if (!is_null($ultBalance)){
            $saldoBrever->breve_balance = $ultBalance->breve_balance + $request->discount_amount;
        }else{
            $saldoBrever->breve_balance = $request->discount_amount;
        }
        $saldoBrever->date = date('Y-m-d');
        $saldoBrever->description = $request->description;
        $saldoBrever->save();

        $brever->balance = $saldoBrever->brever_balance;
        $brever->save();

        return redirect('admin/financial/brever-recharge')->with('msj-exitoso', 'El descuento ha sido procesado con éxito.');

    }

    //**** Admin / Financiero / Pagos Pendientes ****/
    public function pending_payments(Request $request){
        $servicios = Service::client($request->get('client'))
                        ->brever($request->get('brever'))
                        ->rate($request->get('rate'))
                        ->date($request->get('date'))
                        ->time($request->get('time'))
                        ->status($request->get('status'))
                        ->where('payment_method', '=', 'transferencia')
                        ->where('payment_status', '=', 0)
                        ->where('status', '<>', 5)
                        ->orderBy('date', 'ASC')
                        ->orderBy('time', 'ASC')
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

        return view('admin.pendingPayments')->with(compact('servicios', 'clientes', 'brevers'));
    }

     //**** Admin / Financiero / Ganancias ****/
    public function earnings(){
        $ganancias = Balance::where('type', '=', 'Domicilio Breve')
                        ->orderBy('created_at', 'DESC')
                        ->get();

        return view('admin.earnings')->with(compact('ganancias'));
    }

    //**** Brever / Financiero / Estado de Cuenta ****/
    public function account_status(Request $request){
        if ( (is_null($request->get('month'))) && (is_null($request->get('initial_date'))) ){
            $movimientos = Balance::where('brever_id', '=', Auth::user()->id)
                            ->orderBy('id', 'DESC')
                            ->get();
        }else{
            if (!is_null($request->get('month'))){
                $fechaInicial = $request->get('year')."-".$request->get('month')."-01";
                $fechaFinal = $request->get('year')."-".$request->get('month')."-31";

                $movimientos = Balance::where('brever_id', '=', Auth::user()->id)
                                ->where('date', '>=', $fechaInicial)
                                ->where('date', '<=', $fechaFinal)
                                ->orderBy('id', 'DESC')
                                ->get();
            }else{
                $movimientos = Balance::where('brever_id', '=', Auth::user()->id)
                                ->where('date', '>=', $request->get('initial_date'))
                                ->where('date', '<=', $request->get('final_date'))
                                ->orderBy('id', 'DESC')
                                ->get();
            }
        }
        
        return view('brever.showAccountStatus')->with(compact('movimientos'));
    }

    //**** Brever / Financiero / Transferir Saldo ****/
    public function balance_transfers(){
        $transferenciasRealizadas = Transfer::where('user_id', '=', Auth::user()->id)
                                        ->orderBy('created_at', 'DESC')
                                        ->get();

        $transferenciasRecibidas = Transfer::where('user_id', '=', Auth::user()->id)
                                        ->orderBy('created_at', 'DESC')
                                        ->get();

        return view('brever.balanceTransfers')->with(compact('transferenciasRealizadas', 'transferenciasRecibidas'));
    }

    //**** Brever / Financiero / Transferir Saldo ****/
    public function transfer(Request $request){
        $brever = User::where('email', '=', $request->brever_email)
                    ->where('role_id', '=', 2)
                    ->first();

        if (!is_null($brever)){
            if ($request->amount <= Auth::user()->balance){
                $ultBalance = Balance::orderBy('id', 'DESC')->first();

                $transferencia = new Transfer($request->all());
                $transferencia->brever_id = $brever->id;
                $transferencia->user_id = Auth::user()->id;
                $transferencia->save();

                $usuario = User::find(Auth::user()->id);

                $saldoBrever = new Balance();
                $saldoBrever->brever_id = $brever->id;
                $saldoBrever->type = 'Transferencia de Saldo (Crédito)';
                $saldoBrever->brever_commission = $request->amount;
                $saldoBrever->breve_commission = 0;
                $saldoBrever->brever_balance = $brever->balance + $request->amount;
                if (!is_null($ultBalance)){
                    $saldoBrever->breve_balance = $ultBalance->breve_balance;
                }else{
                    $saldoBrever->breve_balance = 0;
                }
                $saldoBrever->transfer_id = $transferencia->id;
                $saldoBrever->date = date('Y-m-d');
                $saldoBrever->description = 'Transferencia de Saldo de parte de '.$usuario->name;
                $saldoBrever->save();

                $saldoUser = new Balance();
                $saldoUser->brever_id = Auth::user()->id;
                $saldoUser->type = 'Transferencia de Saldo (Débito)';
                $saldoUser->brever_commission = $request->amount;
                $saldoUser->breve_commission = 0;
                $saldoUser->brever_balance = $usuario->balance - $request->amount;
                if (!is_null($ultBalance)){
                    $saldoUser->breve_balance = $ultBalance->breve_balance;
                }else{
                    $saldoUser->breve_balance = 0;
                }
                $saldoUser->transfer_id = $transferencia->id;
                $saldoUser->date = date('Y-m-d');
                $saldoUser->description = 'Transferencia de Saldo para '.$brever->name;
                $saldoUser->save();

                $usuario->balance = $usuario->balance - $request->amount;
                $usuario->save();

                $brever->balance = $brever->balance + $request->amount;
                $brever->save();

                return redirect('brever/financial/balance-transfers')->with('msj-exitoso', 'La transferencia de saldo ha sido realizada con éxito.');

            }else{
                return redirect('brever/financial/balance-transfers')->with('msj-erroneo', 'El monto indicado excede su saldo disponible.');
            }
        }else{
            return redirect('brever/financial/balance-transfers')->with('msj-erroneo', 'El correo electrónico ingresado no corresponde a ningún brever registrado.');
        }
    }
}
