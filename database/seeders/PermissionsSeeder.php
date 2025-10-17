<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          //  Permisos para el módulo Usuarios
        Permission::firstOrCreate(['name' => 'usuarios.listar', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'usuarios.ver', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'usuarios.crear', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'usuarios.editar', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'usuarios.eliminar', 'guard_name' => 'web']);

        // Permisos para el módulo Roles
        Permission::firstOrCreate(['name' => 'roles.listar', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'roles.crear', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'roles.permisos.asignar', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'roles.editar', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'roles.eliminar', 'guard_name' => 'web']);

        // Permisos para el módulo almacen
        Permission::firstOrCreate(['name' => 'almacenes.listar', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'almacenes.crear', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'almacenes.editar', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'almacenes.eliminar', 'guard_name' => 'web']);

        // Permisos para el módulo Categorías
        Permission::firstOrCreate(['name' => 'categorias.listar', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'categorias.crear', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'categorias.editar', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'categorias.eliminar', 'guard_name' => 'web']);

        //Permisos para el módulo productos
        Permission::firstOrCreate(['name' => 'productos.listar', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'productos.crear', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'productos.editar', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'productos.eliminar', 'guard_name' => 'web']);

        //Permisos para el módulo compras
        Permission::firstOrCreate(['name' => 'compras.listar', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'compras.ver', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'compras.crear', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'compras.editar', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'compras.eliminar', 'guard_name' => 'web']);

        //Permisos para el módulo Proveedores
        Permission::firstOrCreate(['name' => 'proveedores.listar', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'proveedores.crear', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'proveedores.editar', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'proveedores.eliminar', 'guard_name' => 'web']);

         //Permisos para el módulo clientes
        Permission::firstOrCreate(['name' => 'clientes.listar', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'clientes.crear', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'clientes.editar', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'clientes.eliminar', 'guard_name' => 'web']);

        // Permisos para el módulo repartidor
        Permission::firstOrCreate(['name' => 'repartidores.listar', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'repartidores.crear', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'repartidores.editar', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'repartidores.eliminar', 'guard_name' => 'web']);

        // Permisos para el módulo menu
        Permission::firstOrCreate(['name' => 'menu.listar', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'menu.ver', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'menu.crear', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'menu.editar', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'menu.eliminar', 'guard_name' => 'web']);

    }
    
    
}
