<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model{
    protected $table = 'services';

    protected $fillable = ['user_id', 'client_name', 'sender', 'sender_name', 'sender_phone', 'sender_neighborhood', 'sender_address', 'sender_address_opc', 'sender_latitude', 'sender_longitude', 'receiver', 'receiver_name', 'receiver_phone', 
        'receiver_neighborhood',  'receiver_address', 'receiver_address_opc', 'receiver_latitude', 
        'receiver_longitude', 'date', 'time', 'article', 'equipment_type', 'payment_type', 'payment_method', 
        'payment_status', 'refund_amount', 'rate', 'additional_cost', 'total', 'observations', 'brever_id', 'status', 'rate_status'];

    public function user(){
    	return $this->belongsTo('App\Models\User');
    }

    public function brever(){
    	return $this->belongsTo('App\Models\User', 'brever_id', 'id');
    }

    public function adminNotification(){
        return $this->hasOne('App\Models\AdminNotification');
    }

    public function notifications(){
        return $this->hasMany('App\Models\Notification');
    }

    public function scopeClient($query, $client){
        if ($client != ""){
            $query->where('user_id', '=', $client);
        }
    }

    public function scopeDate($query, $date){
        if ($date != ""){
            $query->where('date', '=', $date);
        }
    }

    public function scopeTime($query, $time){
        if ($time != ""){
            $query->where('time', '=', $time);
        }
    }

    public function scopeRate($query, $rate){
        if ($rate != ""){
            $query->where('rate', '=', $rate);
        }
    }

    public function scopeBrever($query, $brever){
        if ($brever != ""){
            $query->where('brever_id', '=', $brever);
        }
    }

    public function scopeStatus($query, $status){
        if ($status != ""){
            $query->where('status', '=', $status);
        }
    }
}
