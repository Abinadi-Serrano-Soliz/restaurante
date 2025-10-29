<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class PerfilController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('auth.panel');
    }

    /**
     * Show the form for creating a new resource.
     */
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
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('auth.perfil', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
{
    // Obtener el usuario autenticado
    $user = Auth::user();

    // Validaciones
    $request->validate([
        'old_password' => 'nullable|required_with:password|string',
        'password' => 'nullable|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?!.*\s)(?!.*\*).*$/',
    ]);

    // Si el usuario no es un Eloquent model, obtenemos el modelo real
    if (!($user instanceof \App\Models\User)) {
        $user = \App\Models\User::find($user->id);
        if (!$user) {
            return back()->withErrors(['user' => 'Usuario no encontrado.']);
        }
    }

    // Actualiza nombre y correo
   

    // Si quiere cambiar la contraseña
    if ($request->filled('password')) {
        if (!Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['old_password' => 'La contraseña actual no es correcta.']);
        }
        $user->password = Hash::make($request->password);
    }

    // Guardar cambios
    $user->save();

    return redirect()->route('perfil.edit')->with('success', 'Perfil actualizado correctamente.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
