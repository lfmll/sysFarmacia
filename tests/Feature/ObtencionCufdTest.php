<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Ajuste;
use App\Models\Empresa;
use App\Models\Cufd;

class testObtencionCUFD extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
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
        $cuis = "5E20BB5C";

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
                    'cuis' => $cuis,
                    'nit' => $empresa->nit
                )
            );
            //LOOP            
            for ($i=0; $i < 50; $i++) {
                $responseCufd = $clienteCufd->cufd($parametrosCUFD);
                if ($responseCufd->RespuestaCufd->transaccion==true) {
                    Cufd::where('estado','A')
                    ->where('cuis_id',6)
                    ->where('agencia_id',1)
                    ->where('punto_venta_id',1)
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
                        'agencia_id' => 1,
                        'punto_venta_id' => 1,
                        'cuis_id' => 6
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
        $cuis = "718E252F";

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
                    'cuis' => $cuis,
                    'nit' => $empresa->nit
                )
            );
            //LOOP            
            for ($i=0; $i < 100; $i++) {
                $responseCufd = $clienteCufd->cufd($parametrosCUFD);
                if ($responseCufd->RespuestaCufd->transaccion==true) {
                    Cufd::where('estado','A')
                    ->where('cuis_id',5)
                    ->where('agencia_id',1)
                    ->where('punto_venta_id',1)
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
                        'agencia_id' => 1,
                        'punto_venta_id' => 1,
                        'cuis_id' => 5
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
