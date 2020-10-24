<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(){
        if (Auth::guest()){
            return redirect('login');
        }else{
            if (Auth::user()->role_id == 1){
                return redirect('dashboard');
            }else if (Auth::user()->role_id == 2){
                return redirect('brever');
            }else if (Auth::user()->role_id == 3){
                return redirect('admin');
            }
        }
    }

    public function guest_service(){
        return view('index');
    }
}
