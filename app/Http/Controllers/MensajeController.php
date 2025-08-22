<?php

namespace App\Http\Controllers;

use App\Helpers\BitacoraHelper;
use Illuminate\Http\Request;
use App\Mail\MensajeFactura;
use App\Models\Bitacora;
use Illuminate\Support\Facades\Mail;
use App\Models\Factura;
use App\Models\Cliente;
use Illuminate\Support\Facades\Auth;

class MensajeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function enviarCorreo($idfactura)
    {
        try {
            $factura=Factura::find($idfactura);
            $codigoCliente=$factura->codigoCliente;
            $cliente=Cliente::find($codigoCliente);
            
            $msj = [
                'cliente' => $cliente->nombre,
                'dirPDF'   => public_path('adjuntos/'.$factura->id.'.pdf'),
                'dirXML'   => public_path('adjuntos/'.$factura->id.'.xml'),
            ];

            Mail::to($cliente->correo)->send(new MensajeFactura($msj));
            BitacoraHelper::registrar('Envio de Correo', 'Correo enviado por '.Auth::user()->usuario.' a '.$cliente->nombre, 'Ajuste');
            return redirect('factura')->with('toast_success','Correo enviado exitosamente');
        } catch (\Exception $e) {            
            return redirect('factura')->with('toast_error','Error de Envio. '.$e->getMessage());
        }
        
    }
}
