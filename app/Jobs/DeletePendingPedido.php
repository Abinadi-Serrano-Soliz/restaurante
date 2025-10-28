<?php

namespace App\Jobs;

use App\Models\Pedido;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeletePendingPedido implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $pedidoId;
    /**
     * Create a new job instance.
     */
    public function __construct($pedidoId)
    {
         $this->pedidoId = $pedidoId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
         $pedido = Pedido::with(['pagos', 'detallePedidos'])->find($this->pedidoId);

        if ($pedido && $pedido->pagos->estado_pago !== 'COMPLETADO') {
            // Eliminar detalles del pedido
            if ($pedido->detallePedidos) {
                foreach ($pedido->detallePedidos as $detalle) {
                    $detalle->delete();
                }
            }

            // Eliminar pago asociado
            if ($pedido->pagos) {
                $pedido->pagos->delete();
            }

            // Eliminar pedido
            $pedido->delete();
        }
    }
}
