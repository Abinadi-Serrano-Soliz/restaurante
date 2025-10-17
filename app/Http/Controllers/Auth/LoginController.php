<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function index(){
        return view('auth.login');
    }

    public function login(Request $request){
        $credentials = $request->validate([
            'email' => ['required', 'email','regex:/^\S*$/','regex:/^[^*]+$/'],
            'password' => ['required','min:8','regex:/^\S*$/','regex:/^[^*]+$/']
        ], [
            'password.regex' => 'La contraseña no debe contener espacios ni el caracter *.',
            
        ]);
                // 1. Buscar el usuario por email
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return back()->withErrors([
                    'email' => 'El correo no está registrado.',
                ])->onlyInput('email');
            }

            // 2. Verificar la contraseña
            if (!\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
                return back()->withErrors([
                    'password' => 'La contraseña es incorrecta.',
                ])->onlyInput('email');
            }

            // 3. Si todo está correcto, loguear
            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->route('dashboard')->with('success','Bienvenido');
        }

    public function logout(){
        Auth::logout();
        return redirect('/login');
    }
    public function home()
    {
        return view('dashboard');
    }
}
