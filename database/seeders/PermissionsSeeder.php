<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = ['dashboard','usuarios','roles']; // modulos para acceder 
        $actions = ['ver','crear','actualizar','eliminar']; //acciones que vaan a hacer en los modulos

        foreach($modules as $m){
            foreach($actions as $a){
                Permission::firstOrCreate(
                    ['name' => "{$m}.{$a}", 'guard_name' => 'web']
                );
            }
        }
    }
    
}
