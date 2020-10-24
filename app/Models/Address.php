<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model{
    protected $table = 'addresses';

    protected $fillable = ['user_id', 'name', 'sender_latitude', 'receiver_latitude', 'sender_address', 'sender_neighborhood', 'receiver_latitude', 'receiver_longitude', 'receiver_address', 'receiver_neighborhood'];

    public function user(){
    	return $this->belongsTo('App\Models\User');
    }
}
