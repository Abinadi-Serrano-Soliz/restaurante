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
        Schema::create('ajustes', function (Blueprint $table) {
            $table->id();
            $table->dateTime('fecha_hora');
            $table->string('glosa')->nullable(); // descripción o motivo del ajuste
            $table->decimal('reembolso', 10, 2)->nullable(); //para registrar si se devuelve algo de dinerò
            $table->enum('tipo', ['INGRESO', 'EGRESO']); // tipo de movimiento
            $table->string('imagen')->nullable(); // imagen del comprobante de reembolso
            $table->integer('cantidad')->nullable(); // imagen del comprobante de reembolso
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_detallePedido');

            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_detallePedido')->references('id')->on('detalle_pedidos')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ajustes');
    }
};
