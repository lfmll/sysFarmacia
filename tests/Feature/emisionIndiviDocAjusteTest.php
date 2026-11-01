<?php

namespace Tests\Feature;

use App\Models\Agencia;
use App\Models\Ajuste;
use App\Models\Empresa;
use App\Models\Cufd;
use App\Models\Cuis;
use App\Models\DetalleFactura;
use App\Models\Factura;
use App\Models\PuntoVenta;
use Carbon\Carbon;
use App\Models\Leyenda;
use App\Models\Parametro;
use App\Models\NotaAjuste;
use App\Models\DetalleNotaAjuste;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class emisionIndiviDocAjusteTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testEmisionIndividual3()
    {
       // codigoAmbiente = 2
        // codigoEmision = 1
        // archivo = su cadena de archivo
        // codigoSistema = su código de sistema
        // hashArchivo = el hash correspondiente a su cadena archivo
        // codigoSucursal = 0
        // codigoModalidad = su modalidad de Facturación
        // cuis = su CUIS
        // codigoPuntoVenta = 1
        // fechaEnvio = la fecha actual en formato UTC extendido sin zona horaria
        // tipoFacturaDocumento = 3
        // nit = su NIT
        // codigoDocumentoSector = 24
        // cufd = su CUFD válido 

        $empresa = Empresa::first();
        $agencia = Agencia::where('codigo',0)->first();
        $puntoVenta = PuntoVenta::where('codigo',1)->first();
        $cuis = Cuis::where('codigo_cuis',"5E20BB5C")->first();
        $cufd = Cufd::where('estado','A')
                    ->where('cuis_id',$cuis->id)
                    ->first();
        $ajuste = Ajuste::first();
        $token = $ajuste->token;
        $wdslNotaAjuste = $ajuste->wsdl."/ServicioFacturacionDocumentoAjuste?wsdl";
        $clienteNotaAjuste = Ajuste::consumoSIAT($token,$wdslNotaAjuste);
        $fecha_envio = Carbon::now('America/La_Paz')->format('Y-m-d\TH:i:s');
        $fecha_envio = $fecha_envio.'.'.str_pad(now('America/La_Paz')->milli, 3, '0', STR_PAD_LEFT); 
        $fechaEmision = Factura::deFechaNumero($fecha_envio);
        $facturas = DB::table('facturas')
                        ->leftJoin('nota_ajustes', 'facturas.id', '=', 'nota_ajustes.factura_id')
                        ->whereNull('nota_ajustes.id')
                        ->where('facturas.estado', 'VALIDADA')
                        ->whereNotNull('facturas.codigoRecepcion')
                        ->select('facturas.*', 'facturas.id as factura_id')
                        ->limit(100)
                        ->get();
        $errores = [];
        if ($clienteNotaAjuste->verificarComunicacion()->return->transaccion == "true") {
            foreach ($facturas as $factura) {
                $usuario = User::where('name','Luis Fernando')->first();
                //Montos Nota de Ajuste
                $montoTotalDevuelto = $factura->montoTotal * 0.10; //10% del total factura
                $montoDescuentoCreditoDebito = $montoTotalDevuelto * 0.50; //50% del total devuelto
                $montoEfectivoCreditoDebito = number_format($montoTotalDevuelto*0.13, 2, '.', '');

                $leyenda = Leyenda::where('cuis_id',$cuis->id)
                            ->inRandomOrder()
                            ->first();
                $DocSector = Parametro::join('tipo_parametros','tipo_parametros.id','=','parametros.tipo_parametro_id')
                            ->where('tipo_parametros.nombre','TIPO DOCUMENTO SECTOR')
                            ->where('parametros.descripcion','NOTA DE CRÉDITO-DÉBITO')
                            ->where('cuis_id',$cuis->id)
                            ->first();
                $tipoFactura = Parametro::join('tipo_parametros','tipo_parametros.id','=','parametros.tipo_parametro_id')
                            ->where('tipo_parametros.nombre','TIPO DOCUMENTO FACTURA')
                            ->where('parametros.descripcion','DOCUMENTO DE AJUSTE')
                            ->where('cuis_id',$cuis->id)
                            ->first();
                            $cantNotaAjustes = NotaAjuste::count();
                //Crear Nota de Ajuste
                $notaAjuste = new NotaAjuste();
                $notaAjuste->numeroNotaCreditoDebito = $cantNotaAjustes + 1;
                $notaAjuste->tipo = 1;
                $notaAjuste->estado = 'ENVIADO';
                $notaAjuste->motivo = 'Devolución';
                $notaAjuste->tipoFactura = $tipoFactura->codigo_clasificador;
                $notaAjuste->codigoDocumentoSector = $DocSector->codigo_clasificador;
                $notaAjuste->fechaEmision = $fecha_envio;
                $notaAjuste->montoTotal = $montoTotalDevuelto;
                $cuf = Factura::generarCUF(
                    $empresa->nit,
                    $agencia->codigo,
                    $fechaEmision,
                    $empresa->modalidad,
                    1,  //$tipo_emision [1=Online; 2=Offline; 3=Masiva]
                    $tipoFactura->codigo_clasificador, //$tipo_factura [1=Doc Cred Fiscal; 2=Doc Sin Cred Fiscal; 3=Doc Ajuste]
                    $DocSector->codigo_clasificador, //$tipo_documento_sector
                    $cantNotaAjustes + 1, //$numero_documento
                    $puntoVenta->codigo
                );                
                $notaAjuste->cuf = $cuf.$cufd->codigo_control;
                $notaAjuste->montoTotalDevuelto = $montoTotalDevuelto;
                $notaAjuste->montoDescuentoCreditoDebito = $montoDescuentoCreditoDebito;
                $notaAjuste->montoEfectivoCreditoDebito = $montoEfectivoCreditoDebito;
                $notaAjuste->codigoExcepcion = null;
                $notaAjuste->leyenda = $leyenda->descripcion_leyenda;
                $notaAjuste->factura_id = $factura->factura_id;
                $notaAjuste->cufd_id = $cufd->id;
                $notaAjuste->punto_venta_id = $puntoVenta->id;
                $notaAjuste->user_id = $usuario->id;
                $notaAjuste->save();
                $detalleFactura = DetalleFactura::where('factura_id',$factura->factura_id)->get();
                foreach ($detalleFactura as $index => $detalle) {
                    $detalleNotaAjuste1 = new DetalleNotaAjuste();
                    $detalleNotaAjuste1->actividadEconomica = $detalle->actividadEconomica;
                    $detalleNotaAjuste1->codigoProductoSin = $detalle->codigoProductoSin;
                    $detalleNotaAjuste1->codigoProducto = $detalle->codigoProducto;
                    $detalleNotaAjuste1->descripcion = $detalle->descripcion;
                    $detalleNotaAjuste1->unidadMedida = $detalle->unidadMedida;
                    $detalleNotaAjuste1->cantidad = $detalle->cantidad;
                    $detalleNotaAjuste1->precioUnitario = $detalle->precioUnitario;
                    $detalleNotaAjuste1->montoDescuento = $detalle->montoDescuento;
                    $detalleNotaAjuste1->subTotal = $detalle->subTotal; 
                    $detalleNotaAjuste1->codigoDetalleTransaccion = 1; //1: Transaccion Original    
                    $detalleNotaAjuste1->nota_ajuste_id = $notaAjuste->id;  
                    $detalleNotaAjuste1->save();

                    $detalleNotaAjuste2 = new DetalleNotaAjuste();
                    $detalleNotaAjuste2->actividadEconomica = $detalle->actividadEconomica;
                    $detalleNotaAjuste2->codigoProductoSin = $detalle->codigoProductoSin;
                    $detalleNotaAjuste2->codigoProducto = $detalle->codigoProducto;
                    $detalleNotaAjuste2->descripcion = $detalle->descripcion;
                    $detalleNotaAjuste2->unidadMedida = $detalle->unidadMedida;                        
                    $detalleNotaAjuste2->cantidad = $detalle->cantidad;                       
                    $detalleNotaAjuste2->precioUnitario = $detalle->precioUnitario;
                    $detalleNotaAjuste2->montoDescuento = $detalle->montoDescuento;
                    $detalleNotaAjuste2->subTotal = $montoTotalDevuelto;
                    $detalleNotaAjuste2->codigoDetalleTransaccion = 2; //2: Devuelto
                    $detalleNotaAjuste2->nota_ajuste_id = $notaAjuste->id;
                    $detalleNotaAjuste2->save();

                }
                $xml = NotaAjuste::generarXML($factura->factura_id, $notaAjuste->id);
                $msjError = NotaAjuste::validarXML($xml, 'notaComputarizadaCreditoDebito.xsd');
                if (empty($msjError)) 
                {
                    $gzipFileName = $notaAjuste->numeroNotaCreditoDebito.'.gz';
                    $path = public_path('/siat/notaAjuste/');
                    if (!file_exists($path)) {
                        mkdir($path, 0777, true);
                    }
                    $gzipFilePath = $path . $gzipFileName;
                    $gzdata = gzencode($xml, 9);
                    // $contenido64 = base64_encode($gzdata);
                    // dd($contenido64);
                    file_put_contents($gzipFilePath, $gzdata);
                    
                    $hashArchivo = hash_file('sha256', $gzipFilePath);

                    $ajuste = Ajuste::first();
                    $token = $ajuste->token;
                    $wsdlSincronizacion = $ajuste->wsdl."/ServicioFacturacionDocumentoAjuste?wsdl";
                    $clienteNotaAjuste = Ajuste::consumoSIAT($token,$wsdlSincronizacion);             
                    $parametrosNotaAjuste = array(
                            'SolicitudServicioRecepcionDocumentoAjuste' => array(
                                'codigoAmbiente' => $empresa->ambiente,
                                'codigoDocumentoSector' => $notaAjuste->codigoDocumentoSector,  //Tipo Documento Sector, Nota de Credito-Debito = 24
                                'codigoEmision' => 1,
                                'codigoModalidad' => $empresa->modalidad,
                                'codigoPuntoVenta' => $factura->codigoPuntoVenta,
                                'codigoSistema' => $empresa->codigo_sistema,
                                'codigoSucursal' => $factura->codigoSucursal,
                                'cufd' => $cufd->codigo_cufd,
                                'cuis' => $cuis->codigo_cuis,
                                'nit' => $empresa->nit,
                                'tipoFacturaDocumento' => $notaAjuste->tipoFactura, //Tipo Documento Factura, Docuemento Ajuste = 3
                                'archivo' => $gzdata,
                                'fechaEnvio' => $fecha_envio,
                                'hashArchivo' => $hashArchivo
                            )
                        );
                    $responseRecepcionNotaAjuste = NotaAjuste::soapRecepcionNotaAjuste($clienteNotaAjuste, $parametrosNotaAjuste, $notaAjuste->id);
                    if ($responseRecepcionNotaAjuste == "VALIDADA") {
                        $this->assertTrue(true);
                    } else {
                        $errores[] = $responseRecepcionNotaAjuste;
                        $this->fail("Nota Ajuste no validada: {$responseRecepcionNotaAjuste}");                         
                    }                    
                } else {
                    $errores[] = $msjError;
                    $this->fail("Error de validación XML: {$msjError}");
                }
            }                                        
        } else {
            $this->assertTrue(false);
            $errores[] = "Error de comunicación con el SIAT";
        }

    }
}
