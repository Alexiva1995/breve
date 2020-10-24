<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model{
    protected $table = 'admin_notifications';

    protected $fillable = ['service_id', 'brever_id', 'status'];

    public function service(){
    	return $this->belongsTo('App\Models\Service');
    }

    public function brever(){
    	return $this->belongsTo('App\Models\User', 'brever_id', 'id');
    }
}
