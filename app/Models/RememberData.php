<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RememberData extends Model{
    protected $table = 'remember_data';

    protected $fillable = ['user_id', 'alias', 'alias_admin', 'identification', 'name', 'phone', 'neighborhood', 'address_opc', 'type', 'admin'];

    public function user(){
    	return $this->belongsTo('App\Models\User');
    }
}
