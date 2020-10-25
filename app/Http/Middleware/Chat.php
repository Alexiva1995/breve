<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Support\Facades\View;




class Chat
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $usuarios = User::where('role_id', 3)->select('id', 'name')->get();
        View::share('usuarios', $usuarios);

        return $next($request);
    }
}
