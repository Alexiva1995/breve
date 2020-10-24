<?php
namespace App\Console\Commands;
 
use Illuminate\Console\Command;
use DB; use Carbon\Carbon; 
 
class servicesExpiration extends Command{

  	protected $signature = 'servicesExpiration';
 
  	protected $description = 'Verifica si se ha pasado la hora del servicio y lo coloca como cancelado en caso de no estar asignado a ningún Brever.';

  	public function __construct(){
    	parent::__construct();
  	}
 
  	public function handle(){
  		\Log::info('Cron Activado servicesExpiration');

      $date = Carbon::now();
      $date->subHour(5);

      $fecha = $date->format('Y-m-d');
      $hora = $date->format('H:i');

  		$servicios = DB::table('services')
                     ->select('id', 'date', 'time')
                     ->where('status', '=', 0)
                     ->where('date', '<=', $fecha)
                     ->get();

      foreach ($servicios as $servicio){
         if ($servicio->date < $fecha){
            DB::table('services')
               ->where('id', '=', $servicio->id)
               ->update(['status' => 5,
                         'updated_at' => date('Y-m-d H:i:s')]);
         }else{
            if ($servicio->time <= $hora){
               DB::table('services')
                  ->where('id', '=', $servicio->id)
                  ->update(['status' => 5,
                            'updated_at' => date('Y-m-d H:i:s')]);
            }
         }
      }
   }
}