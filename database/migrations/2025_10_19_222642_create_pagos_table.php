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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pedido')->unique(); // un pedido tiene un solo pago
            $table->string('transaccion_id', 100)->nullable(); // ID de transacción de Libélula
            $table->string('referencia', 100)->nullable(); // referencia interna o generada
            $table->decimal('monto_total', 10, 2);
            $table->string('metodo_pago', 50)->nullable(); // QR, TigoMoney, Transferencia, etc.
            $table->string('canal_pago', 50)->nullable(); // Canal usado (opcional)
            $table->enum('estado_pago', ['PENDIENTE', 'COMPLETADO', 'FALLIDO'])->default('PENDIENTE');
            $table->dateTime('fecha_hora_pago')->nullable();
            $table->json('respuesta_libelula')->nullable(); // Guardar respuesta completa del API
            $table->timestamps();

            $table->foreign('id_pedido')
                  ->references('id')
                  ->on('pedidos')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
