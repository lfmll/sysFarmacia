<?php

namespace App\Http\Middleware;

use App\Models\Empresa;
use App\Models\Agencia;
use App\Models\PuntoVenta;
use App\Models\Cuis;
use App\Models\Sincronizacion;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facade\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

use App\Services\SiatService;

class SincronizarFechaHoraMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $empresa = Empresa::where('estado','A')->first();
        $sucursal = Agencia::where('id',session('agencia_id'))
                            ->where('estado', 'A')
                            ->first();
        $puntoVenta = PuntoVenta::where('id',session('punto_venta_id'))
                            ->where('estado', 'A')
                            ->first();
        $cuis = Cuis::obtenerCuis();                            
        if (!is_null($cuis)) {
            $sincronizada = Sincronizacion::where('nit',$empresa->nit)
                                    ->where('agencia_id', $sucursal->id)
                                    ->where('punto_venta_id',$puntoVenta->id)
                                    ->where('cuis_id',$cuis->id)
                                    ->latest('fecha_sincronizada')
                                    ->first();
        } else {
            return redirect('ajuste')->withInput()->with('toast_error', 'Debe sincronizar CUIS');
        }
        if (!$sincronizada) {
            return redirect('ajuste')->withInput()->with('toast_error', 'Debe sincronizar fecha y hora antes de continuar.');
        }         

        $desfaseMinutos = Carbon::now('America/La_Paz')->diffInMinutes($sincronizada->fecha_sincronizada);
        if ($desfaseMinutos > 5) {
            return redirect('ajuste')->withInput()->with('toast_error','La sincronizacion está desfasda más de 5 min. Actualice antes de continuar.');
        }
        return $next($request);
    }
}
