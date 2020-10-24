<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;
use Mail;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $messages = [
            'email.unique' => 'El correo ingresado ya se encuentra registrado.',
            'password.min' => 'La contraseña debe contener al menos 8 caracteres.',
            'password.max' => 'La contraseña debe contener máximo 25 caracteres.',
            'password.confirmed' => 'Las contraseñas ingresadas no coinciden.',
        ];

        return Validator::make($data, [
            'email' => ['unique:users'],
            'password' => ['min:8', 'max:25', 'confirmed'],
        ], $messages);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    public function create(Request $request){
        $messages = [
            'email.unique' => 'El correo ingresado ya se encuentra registrado.',
            'password.min' => 'La contraseña debe contener al menos 8 caracteres.',
            'password.max' => 'La contraseña debe contener máximo 25 caracteres.',
            'password.confirmed' => 'Las contraseñas ingresadas no coinciden.',
        ];

        $validator = Validator::make($request->all(), [
            'email' => ['unique:users'],
            'password' => ['min:8', 'max:25', 'confirmed'],
        ], $messages);

        if ($validator->fails()) {
            return redirect('register')
                    ->withErrors($validator)
                    ->withInput();
        }

        if (is_null($request->tradename)){
            $request->tradename = $request->name;
        }
        
        /*return User::create([
            'name' => $data['name'],
            'tradename' => $data['tradename'],
            'email' => $data['email'],
            'role_id' => 1,
            'password' => Hash::make($data['password']),
        ]);*/

        $usuario = new User($request->all());
        $usuario->role_id = 1;
        $usuario->password = Hash::make($request->password);
        $usuario->status = 2;
        $usuario->save();

        $datos['correo'] = $usuario->email;
        $datos['cliente'] = $usuario->name;
        $datos['id'] = $usuario->id;

        Mail::send('emails.verifyEmail',['data' => $datos], function($msg) use ($datos){
            $msg->to($datos['correo']);
            $msg->subject('Verificar Correo Electrónico');
        });

        return redirect('register')->with('msj-exitoso', 'Su cuenta ha sido creada con éxito. Por favor, ingrese a su correo electrónico para confirmar el mismo.');
    }
}
