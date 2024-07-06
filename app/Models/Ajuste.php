<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SoapClient;

class Ajuste extends Model
{    
    protected $table = 'ajustes';
    protected $fillable=[
        'driver', 
        'host', 
        'port', 
        'encryption', 
        'username', 
        'password', 
        'from',
    ];

    public function punto_venta(){
        return $this->belongsTo(PuntoVenta::class);
    }
    
    public static function consumoSIAT($token, $wsdl){
        try {
            $opts = array(
                'http'=> array(
                    'header' => "apikey: TokenApi $token",
                )
            );
            
            $context = stream_context_create($opts);
                        
            $response = new SoapClient($wsdl, [ 
                'stream_context' => $context,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE,                  
            ]);
            
            return $response;
        } catch (\Exception $e) {
            return null;
        }
    }
}
