<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\MensajeFactura;
use Illuminate\Support\Facades\Mail;

class MensajeController extends Controller
{
    public function enviarCorreo()
    {
        $msj = [
            'title' => 'Hey you'
        ];

        Mail::to('luisfernandomedinallorenti@gmail.com')->send(new MensajeFactura($msj));
        // return new MensajeFactura($msj);
    }
}
