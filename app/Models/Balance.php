<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Balance extends Model{
    protected $table = 'balances';

    protected $fillable = ['brever_id', 'type', 'brever_commission', 'brever_balance', 'breve_commission', 'breve_balance', 'service_id', 'transfer_id', 'date', 'description'];

    public function brever(){
    	return $this->belongsTo('App\Models\User', 'brever_id', 'id');
    }

    public function service(){
    	return $this->belongsTo('App\Models\Service');
    }
}
