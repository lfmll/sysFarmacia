<?php
namespace App\Helpers;

use App\Models\Bitacora;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Carbon\Carbon;

class BitacoraHelper
{
    public static function registrar($accion, $descripcion = null, $modulo = null)
    {
        Bitacora::create([
            'accion'     => $accion,
            'descripcion'=> $descripcion,
            'modulo'     => $modulo,
            'fecha_hora' => Carbon::now('America/La_Paz'),
            'ip'         => Request::ip(),
            'user_id'    => Auth::id()
        ]);
    }
}
