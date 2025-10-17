<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {

        $rolAdmin = Role::firstOrCreate(['name' => 'Administrador']);

        $usuario=User::create([
            'ci' => '9637311',
            'name'   => 'Administrador',
            'lastname' => 'Administrador',
            'cargo' => 'Administrador',
            'fecha_contratacion' => '2025-05-05',
            'salario' => '3500.00',
            'telefono' => '63408393',
            'email'    => 'admin@demo.com',
            'password' => Hash::make('12345678'), // siempre encriptada
        ]);

        if (!$usuario->hasRole('Administrador')) {
            $usuario->assignRole($rolAdmin);
        }
         //3. Asignar todos los permisos al rol Administrador
        $rolAdmin->syncPermissions(Permission::all());
    }
}
