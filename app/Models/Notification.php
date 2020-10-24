<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model{
    protected $table = 'notifications';

    protected $fillable = ['user_id', 'service_id', 'title', 'icon', 'status'];

    public function user(){
    	return $this->belongsTo('App\Models\User');
    }

    public function service(){
    	return $this->belongsTo('App\Models\Service');
    }
}
