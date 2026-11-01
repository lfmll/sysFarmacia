<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SoapClient;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

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
            return $e->getMessage();
        }
    }

        // ---- Credenciales cifradas en reposo (token SIAT y contraseña SMTP) ----
    // Si el valor guardado aún está en texto plano, se devuelve tal cual.
    public function getTokenAttribute($valor)
    {
        return self::descifrar($valor);
    }

    public function setTokenAttribute($valor)
    {
        $this->attributes['token'] = self::cifrar($valor);
    }

    public function getPasswordAttribute($valor)
    {
        return self::descifrar($valor);
    }

    public function setPasswordAttribute($valor)
    {
        $this->attributes['password'] = self::cifrar($valor);
    }

    private static function cifrar($valor)
    {
        return ($valor === null || $valor === '') ? $valor : Crypt::encryptString($valor);
    }

    private static function descifrar($valor)
    {
        if ($valor === null || $valor === '') {
            return $valor;
        }
        try {
            return Crypt::decryptString($valor);
        } catch (DecryptException $e) {
            return $valor; // dato legado en texto plano
        }
    }
}
