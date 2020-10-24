<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model{
    protected $table = 'transfers';

    protected $fillable = ['user_id', 'brever_id', 'amount'];

    public function user(){
    	return $this->belongsTo('App\Models\User');
    }

    public function brever(){
    	return $this->belongsTo('App\Models\User', 'brever_id', 'id');
    }
}
