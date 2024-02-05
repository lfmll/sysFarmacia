<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\MensajeFactura;
use Illuminate\Support\Facades\Mail;
use App\Models\Factura;
use App\Models\Cliente;

class MensajeController extends Controller
{
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
            return redirect('factura')->with('toast_success','Correo enviado exitosamente');
        } catch (\Throwable $th) {
            return redirect('factura')->with('toast_error','Error de Envio');
        }
        
    }
}
