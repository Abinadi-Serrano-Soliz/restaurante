<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_cliente');
            $table->unsignedBigInteger('id_repartidor')->nullable();
            $table->unsignedBigInteger('id_user')->nullable(); // users

            $table->string('direccion_entrega', 255);
            $table->integer('estado')->default(0); // 0=pending, 1=enviado, 2=entregado 3=cancelado
            $table->dateTime('fecha_hora_pedido');
            $table->decimal('latitud', 10, 6)->nullable();
            $table->decimal('longitud', 10, 6)->nullable();
            $table->decimal('monto_total', 10, 2);
            $table->text('observaciones')->nullable();

            // Relaciones
            $table->foreign('id_cliente')->references('id')->on('clientes')->onDelete('cascade');
            $table->foreign('id_repartidor')->references('id')->on('repartidors')->onDelete('set null');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('set null');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
