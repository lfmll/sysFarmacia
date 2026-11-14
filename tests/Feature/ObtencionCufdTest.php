<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Ajuste;
use App\Models\Empresa;
use App\Models\Cufd;
use App\Models\Cuis;
use App\Models\Agencia;
use App\Models\PuntoVenta;

class ObtencionCufdTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    private const SUCURSAL = 0;

    private function cuisVigente(int $codigoPuntoVenta): Cuis
    {
        $agencia = Agencia::where('codigo', self::SUCURSAL)->first();
        $puntoVenta = PuntoVenta::where('codigo', $codigoPuntoVenta)->first();

        session ([
            'agencia_id' => $agencia->id,
            'punto_venta_id' => $puntoVenta->id,
        ]);

        $cuis = Cuis::obtenerCuis();
        $this->assertNotNull($cuis, "No se encontró un CUIS vigente para el punto de venta $codigoPuntoVenta.");
        return $cuis;
    }

    public function testObtencionCUFD1()
    {
        // cuis = su CUIS
        // codigoAmbiente = 2
        // codigoPuntoVenta = 1
        // codigoSistema = su código de sistema
        // nit = su NIT
        // codigoSucursal = 0
        // codigoModalidad = Su Modalidad de Facturación
        $empresa = Empresa::first();
        $agencia = 0;
        $puntoVenta = 1;
        $cuis = $this->cuisVigente($puntoVenta);

        $ajuste = Ajuste::first();
        $token = $ajuste->token;
        $wsdlCodigos = $ajuste->wsdl."/FacturacionCodigos?wsdl";        
        $clienteCufd = Ajuste::consumoSIAT($token,$wsdlCodigos);
        $errores = [];
        if ($clienteCufd->verificarComunicacion()->RespuestaComunicacion->mensajesList->codigo == "926") 
        {
            $parametrosCUFD = array(
                'SolicitudCufd' => array(
                    'codigoAmbiente' => $empresa->ambiente, 
                    'codigoModalidad' => $empresa->modalidad,
                    'codigoPuntoVenta' => $puntoVenta,
                    'codigoSistema' => $empresa->codigo_sistema,
                    'codigoSucursal' => $agencia,
                    'cuis' => $cuis->codigo_cuis,
                    'nit' => $empresa->nit
                )
            );
            //LOOP            
            for ($i=0; $i < 100; $i++) {
                $responseCufd = $clienteCufd->cufd($parametrosCUFD);
                if ($responseCufd->RespuestaCufd->transaccion==true) {
                    Cufd::where('estado', 'A')
                    ->where('cuis_id', $cuis->id)
                    ->where('agencia_id', $cuis->agencia_id)
                    ->where('punto_venta_id', $cuis->punto_venta_id)
                    ->update(['estado'=>'N']);

                    $cufd = new Cufd;
                    $fechaUTC = strtotime($responseCufd->RespuestaCufd->fechaVigencia);
                    $fecha = date("Y-m-d H:i:s", $fechaUTC);
                    $cufd->fill([
                        'codigo_cufd' => $responseCufd->RespuestaCufd->codigo,
                        'codigo_control' => $responseCufd->RespuestaCufd->codigoControl,
                        'direccion' => $responseCufd->RespuestaCufd->direccion,
                        'fecha_vigencia' => $fecha,
                        'estado' => 'A',
                        'agencia_id' => $cuis->agencia_id,
                        'punto_venta_id' => $cuis->punto_venta_id,
                        'cuis_id' => $cuis->id
                    ]);
                    $cufd->save();
                } else {
                    $errores[] = "Iteracion $i: " .$responseCufd->RespuestaCufd->mensajesList->descripcion;
                }
            }
            $this->assertEmpty($errores, "Errores encontrados:\n" . implode("\n", $errores));
        } else {
            $this->fail("Error en la comunicación con el Servicio SIAT");
        }
    }

    public function testObtencionCUFD2()
    {
        // cuis = su CUIS
        // codigoAmbiente = 2
        // codigoPuntoVenta = 0
        // codigoSistema = su código de sistema
        // nit = su NIT
        // codigoSucursal = 0
        // codigoModalidad = Su Modalidad de Facturación
        $empresa = Empresa::first();
        $agencia = 0;
        $puntoVenta = 0;
        $cuis = $this->cuisVigente($puntoVenta);

        $ajuste = Ajuste::first();
        $token = $ajuste->token;
        $wsdlCodigos = $ajuste->wsdl."/FacturacionCodigos?wsdl";        
        $clienteCufd = Ajuste::consumoSIAT($token,$wsdlCodigos);
        $errores = [];
        if ($clienteCufd->verificarComunicacion()->RespuestaComunicacion->mensajesList->codigo == "926") 
        {
            $parametrosCUFD = array(
                'SolicitudCufd' => array(
                    'codigoAmbiente' => $empresa->ambiente, 
                    'codigoModalidad' => $empresa->modalidad,
                    'codigoPuntoVenta' => $puntoVenta,
                    'codigoSistema' => $empresa->codigo_sistema,
                    'codigoSucursal' => $agencia,
                    'cuis' => $cuis->codigo_cuis,
                    'nit' => $empresa->nit
                )
            );
            //LOOP            
            for ($i=0; $i < 100; $i++) {
                $responseCufd = $clienteCufd->cufd($parametrosCUFD);
                if ($responseCufd->RespuestaCufd->transaccion==true) {
                    Cufd::where('estado','A')
                    ->where('cuis_id', $cuis->id)
                    ->where('agencia_id', $cuis->agencia_id)
                    ->where('punto_venta_id', $cuis->punto_venta_id)
                    ->update(['estado'=>'N']);

                    $cufd = new Cufd;
                    $fechaUTC = strtotime($responseCufd->RespuestaCufd->fechaVigencia);
                    $fecha = date("Y-m-d H:i:s", $fechaUTC);
                    $cufd->fill([
                        'codigo_cufd' => $responseCufd->RespuestaCufd->codigo,
                        'codigo_control' => $responseCufd->RespuestaCufd->codigoControl,
                        'direccion' => $responseCufd->RespuestaCufd->direccion,
                        'fecha_vigencia' => $fecha,
                        'estado' => 'A',
                        'agencia_id' => $cuis->agencia_id,
                        'punto_venta_id' => $cuis->punto_venta_id,
                        'cuis_id' => $cuis->id
                    ]);
                    $cufd->save();
                } else {
                    $errores[] = "Iteracion $i: " .$responseCufd->RespuestaCufd->mensajesList->descripcion;
                }
            }
            $this->assertEmpty($errores, "Errores encontrados:\n" . implode("\n", $errores));
        } else {
            $this->fail("Error en la comunicación con el Servicio SIAT");
        }
    }
}
