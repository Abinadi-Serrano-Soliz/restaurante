<?php

use App\Http\Controllers\AlmacenController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\RepartidorController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;

Route::get('/login', [LoginController::class, 'index'])->name('login');

Route::post('/login', [LoginController::class, 'login'])->name('auth.login');

//mostrar formulario para enviar correo
Route::get('forgot-password', [ForgotPasswordController::class, 'showRequestForm'])
    ->name('password.request');

//enviar una contraseña aleatoria al correo 
Route::post('forgot-password', [ForgotPasswordController::class, 'sendTemporaryPassword'])
    ->name('password.email');


Route::middleware(['auth'])->group(function () {

    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [LoginController::class, 'home'])->name('dashboard');

    Route::get('/users/empleados', [UserController::class, 'empleados'])->name('users.empleados');

    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('categorias', CategoriaController::class);
    Route::resource('almacenes', AlmacenController::class)->parameters(['almacenes' => 'almacen']);//para cambiar a singular  a  almacen
    Route::resource('productos', ProductoController::class);
    Route::resource('proveedores', ProveedorController::class);
    Route::resource('clientes', ClienteController::class);
    Route::resource('repartidores', RepartidorController::class);
    Route::resource('compras', CompraController::class);
    Route::resource('menus',MenuController::class);

    // Rutas extra para otorgar permisos
    Route::get('roles/{role}/permisos', [RoleController::class, 'permisos'])->name('roles.permisos');
    Route::post('roles/{role}/permisos', [RoleController::class, 'asignarPermisos'])->name('roles.asignarPermisos');

    //para ver miperfil y actualizar mi contraseña
    Route::get('/perfil', [PerfilController::class, 'edit'])->name('perfil.edit');
    Route::put('/perfil', [PerfilController::class, 'update'])->name('perfil.update');
});