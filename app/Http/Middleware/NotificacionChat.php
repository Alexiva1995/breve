<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use BaklySystems\LaravelMessenger\Facades\Messenger;
use Illuminate\Support\Facades\View;


class NotificacionChat
{
    protected $auth;
    public function __construct(Guard $guard){
        $this->auth=$guard;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $notificaciones = [];
        if (!empty($this->auth->user()->id) || $this->auth->user()->role_id == 3) {
            $threads  = Messenger::threads(auth()->id(), 5);
            foreach ($threads as $key => $thread) {
                if (!$thread->lastMessage->is_seen && $thread->lastMessage->sender_id != auth()->id()) {
                    $notificaciones [] = [
                        'iduser' => $thread->withUser->id, 
                        'name_user' => $thread->withUser->name
                    ];
                }
            }
        }
        View::share('notificaciones', $notificaciones);
        return $next($request);
    }
}
