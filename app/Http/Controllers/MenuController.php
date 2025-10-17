<?php
namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\ProductoAlmacene;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class MenuController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('permission:menu.listar', only: ['index']),
            new Middleware('permission:menu.crear', only: ['create', 'store']),
            new Middleware('permission:menu.ver', only: ['show']),
            new Middleware('permission:menu.editar', only: ['edit', 'update']),
            new Middleware('permission:menu.eliminar', only: ['destroy']),
        ];
    }

    public function index()
    {
        $menus = Menu::with('producto_almacenes.producto')->get();
        return view('menus.index', compact('menus'));
    }

    public function create()
    {
        $productos_almacen = ProductoAlmacene::with('producto', 'almacen')->get();
        return view('menus.create', compact('productos_almacen'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'stock_menu' => 'required|integer|min:0',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp',

             // Validar productos del menú
            'productos' => 'required|array|min:1',
            'productos.*.id_ProductoAlmacen' => 'required|integer|exists:producto_almacenes,id',
            'productos.*.cantidad' => 'required|numeric|min:0.01',
        ]);

        DB::beginTransaction();
        try {
            // 👉 Subir imagen si existe
            $imagenPath = null;
            if ($request->hasFile('imagen')) {
                $imagenPath = $request->file('imagen')->store('menus', 'public');
            }

            $menu = Menu::create([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'precio' => $request->precio,
                'stock_menu' => $request->stock_menu,
                'imagen' => $imagenPath,
                'estado' => $request->estado ?? true,
            ]);

            // Relacionar productos del menú
            if ($request->has('productos')) {
                foreach ($request->productos as $p) {
                    $menu->producto_almacenes()->attach($p['id_ProductoAlmacen'], [
                        'cantidad' => $p['cantidad']
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('menus.index')->with('success', 'Menú creado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al crear menú: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $menu = Menu::with('producto_almacenes')->findOrFail($id);
        $menu->producto_almacenes = $menu->producto_almacenes ?? collect();
        $productos_almacen= ProductoAlmacene::with('producto', 'almacen')->get();
        return view('menus.edit', compact('menu', 'productos_almacen'));
    }

    public function update(Request $request, $id)
    {
     $request->validate([
        'nombre' => 'required|string|max:255',
        'descripcion' => 'nullable|string',
        'precio' => 'required|numeric|min:0',
        'stock_menu' => 'required|integer|min:0',
        'imagen' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

        // Validación de productos
        'productos' => 'required|array|min:1',
        'productos.*.id_ProductoAlmacen' => 'required|integer|exists:producto_almacenes,id',
        'productos.*.cantidad' => 'required|numeric|min:0.01',
     ],
        [
           'productos.*.cantidad' => 'solo se permiten numeros',
    ]);

    DB::beginTransaction();
    try {
        $menu = Menu::findOrFail($id);

        // Reemplazar imagen si se sube una nueva
        if ($request->hasFile('imagen')) {
            if ($menu->imagen && Storage::disk('public')->exists($menu->imagen)) {
                Storage::disk('public')->delete($menu->imagen);
            }
            $menu->imagen = $request->file('imagen')->store('menus', 'public');
        }

        $menu->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'stock_menu' => $request->stock_menu,
            'estado' => $request->estado ?? true,
        ]);

        // Actualizar productos del menú
        // Primero eliminamos todos los existentes
        $menu->producto_almacenes()->detach();

        // Luego agregamos los enviados en el formulario
        foreach ($request->productos as $p) {
            $menu->producto_almacenes()->attach($p['id_ProductoAlmacen'], [
                'cantidad' => $p['cantidad']
            ]);
        }

        DB::commit();
        return redirect()->route('menus.index')->with('success', 'Menú actualizado correctamente.');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Error al actualizar menú: ' . $e->getMessage());
    }

    }

    public function show($id)
    {
        // Traer el menú con los productos relacionados y su información de almacen y producto
        $menu = Menu::with('producto_almacenes.producto', 'producto_almacenes.almacen')->findOrFail($id);

        // Retornar la vista show.blade.php pasando el menú
        return view('menus.show', compact('menu'));
    }

    public function destroy($id)
    {
        try {
            $menu = Menu::findOrFail($id);
            if ($menu->imagen && Storage::disk('public')->exists($menu->imagen)) {
                Storage::disk('public')->delete($menu->imagen);
            }
            $menu->delete();

            return redirect()->route('menus.index')->with('success', 'Menú eliminado correctamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar menú: ' . $e->getMessage());
        }
    }
}