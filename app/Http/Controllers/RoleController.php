<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RoleController extends Controller implements HasMiddleware
{
     public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('permission:roles.ver', only: ['index']),
        ];
    }

    public function index()
    {
        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(['name' => "required|regex:/^[a-zA-ZÁÉÍÓÚáéíóúÑñ\s]+$/u|unique:roles,name"],
        ['name.unique' => 'Este nombre ya está registrado',
        ]);
        Role::create([
            'name' => $request->name,
            'guard_name' => 'web'
        ]);

         return redirect()->route('roles.index')->with('success','Rol creado');
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
    public function edit(Role $role)
    {
         return view('roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $request->validate(['name' => "required|regex:/^[a-zA-ZÁÉÍÓÚáéíóúÑñ\s]+$/u|unique:roles,name,{$role->id}"],
        ['name.unique' => 'Este nombre ya está registrado',
    ]);
        $role->update(['name' => $request->name]);
        return redirect()->route('roles.index')->with('success','Rol actualizado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index')->with('success','Rol eliminado');
    }

       //PARA LOS PERMISOS
    public function permisos(Role $role)
    {
        // Traer todos los permisos y agrupar por módulo (antes del punto)
        $permisos = Permission::all()->groupBy(function($p){
            // espera nombres tipo: "usuarios.ver" -> módulo = usuarios
            return explode('.', $p->name)[0];
        });

        // para saber qué permisos tiene el rol
        $rolePermisos = $role->permissions->pluck('name')->toArray();

        return view('roles.permisos', compact('role', 'permisos', 'rolePermisos'));
    }

    // --- Asignar permisos: procesar POST ---
    public function asignarPermisos(Request $request, Role $role)
    {
        // recibimos un array de nombres de permisos: permisos[]
        $selected = $request->input('permisos', []); // pueden ser names
        $role->syncPermissions($selected);

        return redirect()->route('roles.index')->with('success','Permisos asignados correctamente');
    }
}
