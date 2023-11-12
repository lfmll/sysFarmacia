<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DocumentoController extends Controller
{
    public function reporte()
    {
        $fecha = Carbon::now('America/La_Paz')->toDateString();        
        $horai=date('00:00:00');
        $horaf=date('23:59:59');

        $fechai=Carbon::now('America/La_Paz')->toDateString();
        $fechaf=Carbon::now('America/La_Paz')->addMonths(5)->toDateString();

        $cantVentas=DB::table('ventas')
                ->whereBetween('fecha_venta',[$fecha.' '.$horai, $fecha.' '.$horaf])
                ->count();
        
        $cantCompras=DB::table('compras')
                ->whereBetween('fecha_compra',[$fecha.' '.$horai, $fecha.' '.$horaf])
                ->count();

        $cantLotes=DB::table('lotes')
                ->whereBetween('fecha_vencimiento',[$fechai,$fechaf])
                ->count();    
        
        $cantCierres=DB::table('cajas')
                ->where('hora_fin','<>',null)
                ->count();                           

        return view('reporte.reporte',['cantVentas'=>$cantVentas,'cantCompras'=>$cantCompras,'cantLotes'=>$cantLotes,'cantCierres'=>$cantCierres]);
    }

    public function importe()
    {
        return view('importe.importe');
    }
}
