<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\Rule;
use function Laravel\Prompts\password;

class UserController extends Controller implements HasMiddleware
{
     public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('permission:usuarios.ver', only: ['index']),
            new Middleware('permission:usuarios.crear', only: ['create', 'store']),
            new Middleware('permission:usuarios.actualizar', only: ['edit', 'update']),
            new Middleware('permission:usuarios.eliminar', only: ['destroy']),
        ];
    }

    public function index()
    {
        $users = User::with('roles')->whereHas('roles')->whereNotNull('email')->get(); 
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ci' => 'required|unique:users,ci',
            'name' => 'required|regex:/^[a-zA-Z\s]+$/u',
            'lastname'=>'required|regex:/^[a-zA-Z\s]+$/u',
            'cargo'=>'required|regex:/^[a-zA-Z\s]+$/u',
            'fecha_contratacion'=>'required',
            'salario'=>'required',
            'telefono'=>'nullable|digits:8',
            'email' => 'nullable|email|unique:users,email',
            'password' => 'nullable|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?!.*\s)(?!.*\*).*$/',
            'role' => 'nullable'
        ],
        [
            'name.required'=>'el campo nombre es obligatorio',
            'name.regex'=>' el nombre no puede llevar números',
            'lastname.required'=>'el campo Apellidos es obligatorio',
            'lastname.regex'=>'el Apellido no puede llevar números',
            'cargo.regex'=>'el cargo no puede llevar números',
            'fecha_contratacion.required'=>'La fecha de contratacion es obligatorio',
            'password.regex'=>'la contraseña debe tener mayúsculas-minúsculas-números-sin espacios'
        ]);
         
        $user = User::create([
            'ci' => $request->ci,
            'name' => $request->name,
            'lastname' => $request->lastname,
            'cargo' => $request->cargo,
            'fecha_contratacion' => $request->fecha_contratacion,
            'salario' => $request->salario,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        if (!empty($request->role)) {           //si el rol viene vacio no se registra el rol /puede ser nullo no pasa nada
        $user->assignRole($request->role);
        }
        
        return redirect()->route('users.index')->with('success', 'Usuario creado correctamente');
        
    }

    public function edit(User $user, Request $request)
    {
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
             'ci' => [
                'required',
                Rule::unique('users', 'ci')->ignore($user->id),
            ],
            'name' => 'required|regex:/^[a-zA-Z\s]+$/u',
            'lastname'=>'required|regex:/^[a-zA-Z\s]+$/u',
            'cargo'=>'required|regex:/^[a-zA-Z\s]+$/u',
            'fecha_contratacion'=>'required',
            'salario'=>'required',
            'telefono'=>'nullable|digits:8',
            'email' => [
                'nullable',
                'email',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => 'nullable|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?!.*\s)(?!.*\*).*$/',
            'role' => 'nullable'
        ],
        [
            'ci.unique' => 'Este CI ya está registrado',
            'name.required'=>'el campo nombre es obligatorio',
            'name.regex'=>' el nombre no puede llevar números',
            'lastname.required'=>'el campo Apellidos es obligatorio',
            'lastname.regex'=>'el Apellido no puede llevar números',
            'cargo.regex'=>'el cargo no puede llevar números',
            'email.unique' => 'Este email ya está registrado',
            'fecha_contratacion.required'=>'La fecha de contratacion es obligatorio',
            'password.regex'=>'la contraseña debe tener mayúsculas-minúsculas-números-sin espacios'
        ]);

        $user->update([
            'ci' => $request->ci,
            'name' => $request->name,
            'lastname' => $request->lastname,
            'cargo' => $request->cargo,
            'fecha_contratacion' => $request->fecha_contratacion,
            'salario' => $request->salario,
            'telefono' => $request->telefono,
            'email' => $request->email,
        
        ]);

        if ($request->password) {
            $user->update(['password' => bcrypt($request->password)]);
        }

        if (!empty($request->role)) {           //si el rol viene vacio no se registra el rol /puede ser nullo no pasa nada
        $user->assignRole($request->role);
        }


        return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuario eliminado');
    }

    public function empleados()
    {
        $empleados = User::with('roles')->get(); 
        return view('users.empleados', compact('empleados'));
    }


}
