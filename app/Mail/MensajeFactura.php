<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MensajeFactura extends Mailable
{
    use Queueable, SerializesModels;
    public $msg;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($msj)
    {
        $this->msg = $msj;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {              
        return $this->view('email.notificacion_factura')
                    ->attach($this->msg['dirPDF'],['as'=>'factura.pdf'])
                    ->attach($this->msg['dirXML'],['as'=>'factura.xml']);
    }
}
