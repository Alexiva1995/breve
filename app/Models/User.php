<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'tradename', 'email', 'phone', 'license_plate', 'password', 'avatar', 'role_id', 'vip', 'services', 'users', 'financial', 'balance', 'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function addresses(){
        return $this->hasMany('App\Models\Address');
    }

    public function remember_datas(){
        return $this->hasMany('App\Models\RememberData');
    }

    public function services(){
        return $this->hasMany('App\Models\Service');
    }

    public function services_brevers(){
        return $this->hasMany('App\Models\Service', 'brever_id', 'id');
    }

    public function balances(){
        return $this->hasMany('App\Models\Balance', 'brever_id', 'id');
    }

    public function notifications(){
        return $this->hasMany('App\Models\Notification');
    }

    public function adminNotifications(){
        return $this->hasMany('App\Models\AdminNotification');
    }

    public function transfers(){
        return $this->hasMany('App\Models\Transfer');
    }

    public function brever_transfers(){
        return $this->hasMany('App\Models\Transfer', 'brever_id', 'id');
    }

    public function logs(){
        return $this->hasMany('App\Models\Log');
    }
}
