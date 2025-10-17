<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function showRequestForm()
    {
          return view('auth.passwords.forgot-password'); 
    }

   
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Este correo no está registrado en el sistema.']);
        }

        // Enviar enlace de restablecimiento usando Laravel Password
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', 'Se ha enviado un enlace de restablecimiento a tu correo.');
        } else {
            return back()->withErrors(['email' => 'No se pudo enviar el enlace de restablecimiento. Intenta más tarde.']);
        }
    }
    public function sendTemporaryPassword(Request $request)
   {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        // Generar contraseña segura aleatoria
        $temporaryPassword = Str::random(12);

        // Guardarla en la base de datos (hash)
        $user->password = Hash::make($temporaryPassword);
        $user->save();

        // Enviar correo con la contraseña temporal
        Mail::raw("Tu nueva contraseña temporal es: $temporaryPassword", function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Tu contraseña temporal');
        });

        return back()->with('success', 'Se ha enviado una contraseña temporal a tu correo.');
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
