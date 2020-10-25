<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model{
    protected $table = 'logs';

    protected $fillable = ['service_id', 'user_id', 'action'];

    public function service(){
    	return $this->belongsTo('App\Models\Service');
    }

    public function user(){
    	return $this->belongsTo('App\Models\User');
    }
}
