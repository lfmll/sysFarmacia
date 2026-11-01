<?php

namespace App\Http\Controllers;

use App\Models\Ajuste;
use App\Models\DetalleFactura;
use App\Models\NotaAjuste;
use App\Models\Factura;
use App\Models\Empresa;
use App\Models\Agencia;
use App\Models\PuntoVenta;
use App\Models\Cuis;
use App\Models\Cufd;
use App\Models\DetalleNotaAjuste;
use App\Models\Leyenda;
use App\Models\Parametro;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\BitacoraHelper;

class NotaAjusteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cuis = Cuis::where('codigo_cuis',"5E20BB5C")->first();
        $cufd = Cufd::where('estado','A')
                    ->where('cuis_id',$cuis->id)
                    ->first();
        dd($cufd->codigo_cufd);
        $notas = NotaAjuste::all();        
        return view('notaAjuste.index', compact('notas'));
            // $empresa = Empresa::first();
            // $cuis = Cuis::obtenerCuis();
            // $cufd = Cufd::obtenerCufd();
            // $notaAjuste = NotaAjuste::find(1);
            // $motivo = 2;

            //     $parametrosAnulacionNotaAjuste = array(
            //         'anulacionDocumentoAjuste' => array(
            //             'codigoAmbiente' => $empresa->ambiente,
            //             'codigoDocumentoSector' => $notaAjuste->codigoDocumentoSector,
            //             'codigoEmision' => 1,
            //             'codigoModalidad' => $empresa->modalidad,
            //             'codigoPuntoVenta' => $notaAjuste->puntoVenta->codigo,
            //             'codigoSistema' => $empresa->codigo_sistema,
            //             'codigoSucursal' => $notaAjuste->puntoVenta->agencia->codigo,
            //             'cufd' => $cufd->codigo_cufd,
            //             'cuis' => $cuis->codigo_cuis,
            //             'nit' => $empresa->nit,
            //             'tipoFacturaDocumento' => $notaAjuste->tipoFactura,
            //             'codigoMotivo' => $motivo,
            //             'cuf' => $notaAjuste->cuf
            //         )
            //     );
            //     dd($parametrosAnulacionNotaAjuste);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $factura = Factura::findOrFail($id);
        $fechaFormateada = date('d/m/Y', strtotime($factura->fechaEmision));
        $factura->fechaEmision = $fechaFormateada;        
        $detalleFactura = DetalleFactura::where('factura_id', $factura->id)->get();
        $totalSubTotal = $detalleFactura->sum('subTotal');
        return view('notaAjuste.create', compact('factura', 'detalleFactura', 'totalSubTotal'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {     
        $fecha_envio = Carbon::now('America/La_Paz')->format('Y-m-d\TH:i:s');
        $fecha_envio = $fecha_envio.'.'.str_pad(now('America/La_Paz')->milli, 3, '0', STR_PAD_LEFT); 
        $fechaEmision = Factura::deFechaNumero($fecha_envio);
        $empresa = Empresa::first(); 
        $usuario = Auth::user();
        $sucursal = Agencia::where('id',session('agencia_id'))->where('estado','A')->first(); 
        $puntoVenta = PuntoVenta::where('id',session('punto_venta_id'))->where('estado','A')->first(); 
        $factura = Factura::find($request->factura_id);
        $detalleFactura = DetalleFactura::where('factura_id',$factura->id)->get();
        $montoTotalDevuelto = 0;
        $montoDescuentoCreditoDebito = 0;
        foreach ($detalleFactura as $index => $detalle) {
            $id = $detalle->id;
            //Verifica si el item fue marcado como modificado
            if (!empty($request->dmodificado[$id])) {
                //Obtener valores enviados
                $nuevaCantidad = $request->dcantidad[$index];
                $nuevoPrecio = $request->dprecio[$index];
                $nuevoDescuento = $request->ddescuento[$index];

                //Comparar con los valores originales
                $modificado = $nuevaCantidad != $detalle->cantidad ||
                            $nuevoPrecio != $detalle->precioUnitario ||
                            $nuevoDescuento != ($detalle->descuento ?? 0);

                if ($modificado) {
                    $montoTotalDevuelto = $montoTotalDevuelto + ($nuevaCantidad * $nuevoPrecio - $nuevoDescuento);
                    $montoDescuentoCreditoDebito = $montoDescuentoCreditoDebito + ($nuevoDescuento - $detalle->descuento);
                }
            }
        }
        $montoEfectivoCreditoDebito = number_format($montoTotalDevuelto*0.13, 2, '.',''); //13% IVA
                
        $cuis = Cuis::obtenerCuis();
        if (!is_null($cuis)) {
            $cufd = Cufd::obtenerCufd();
            if (!is_null($cufd)) {
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
                $notaAjuste->tipo = $request->tipoNota;
                $notaAjuste->estado = 'Enviado';
                $notaAjuste->motivo = $request->motivo;
                $notaAjuste->tipoFactura = $tipoFactura->codigo_clasificador;
                $notaAjuste->codigoDocumentoSector = $DocSector->codigo_clasificador;
                $notaAjuste->fechaEmision = $fecha_envio;
                $notaAjuste->montoTotal = $request->eSubtotal;
                $cuf = Factura::generarCUF(
                    $empresa->nit,
                    $sucursal->codigo,
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
                $notaAjuste->factura_id = $factura->id;
                $notaAjuste->cufd_id = $cufd->id;
                $notaAjuste->punto_venta_id = $puntoVenta->id;
                $notaAjuste->user_id = $usuario->id;
                $notaAjuste->save();

                foreach($detalleFactura as $index => $detalle) {
                    $id = $detalle->id;
                    //Verifica si el item fue marcado como modificado
                    if (!empty($request->dmodificado[$id])) {
                        //Obtener valores enviados
                        $nuevaCantidad = $request->dcantidad[$index];
                        $nuevoPrecio = $request->dprecio[$index];
                        $nuevoDescuento = $request->ddescuento[$index];

                        //Comparar con los valores originales
                        $modificado = $nuevaCantidad != $detalle->cantidad ||
                                    $nuevoPrecio != $detalle->precioUnitario ||
                                    $nuevoDescuento != ($detalle->descuento ?? 0);
                                                
                        if ($modificado) {    
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
                            $detalleNotaAjuste2->cantidad = $nuevaCantidad;                            
                            $detalleNotaAjuste2->precioUnitario = $nuevoPrecio;
                            $detalleNotaAjuste2->montoDescuento = $nuevoDescuento;
                            $detalleNotaAjuste2->subTotal = ($nuevaCantidad * $nuevoPrecio) - $nuevoDescuento;
                            $detalleNotaAjuste2->codigoDetalleTransaccion = 2; //2: Devuelto
                            $detalleNotaAjuste2->nota_ajuste_id = $notaAjuste->id;
                            $detalleNotaAjuste2->save();
                        } else {
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
                        }                                                                        
                    }
                }
                $xml = NotaAjuste::generarXML($factura->id, $notaAjuste->id);
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
                    $clienteFacturacion = Ajuste::consumoSIAT($token,$wsdlSincronizacion);

                    if ($clienteFacturacion->verificarComunicacion()->return->transaccion == "true") {
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
                        $responseRecepcionNotaAjuste = NotaAjuste::soapRecepcionNotaAjuste($clienteFacturacion, $parametrosNotaAjuste, $notaAjuste->id);
                        if (($responseRecepcionNotaAjuste == "VALIDADA")) {
                            BitacoraHelper::registrar('Registro Nota de Ajuste', 'Nota de Ajuste realizada por el usuario: ' . Auth::user()->name, 'Venta');
                            return redirect('/notaAjuste')->with('toast_success','Nota Débito-Crédito Recepcionada');
                        } else {
                            return redirect('/notaAjuste')->with('toast_error', $responseRecepcionNotaAjuste);
                        } 
                    } else {
                        return redirect('/factura')->with('toast_error','Error de comunicación SIAT');
                    }
                } else {
                    return redirect('/factura')->with('toast_error','Error en Formato XSD: '.implode(" ", $msjError));
                }                
            } else {
                return redirect("/factura")->with('toast_error','CUFD desactualizado');
            }            
        } else {
            return redirect("/factura")->with('toast_error','CUIS desactualizado');
        }        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\NotaAjuste  $notaAjuste
     * @return \Illuminate\Http\Response
     */
    public function show(NotaAjuste $notaAjuste)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\NotaAjuste  $notaAjuste
     * @return \Illuminate\Http\Response
     */
    public function edit(NotaAjuste $notaAjuste)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\NotaAjuste  $notaAjuste
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, NotaAjuste $notaAjuste)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\NotaAjuste  $notaAjuste
     * @return \Illuminate\Http\Response
     */
    public function destroy($idNotaAjuste, Request $request)
    {
        if ($request->ajax()) {
            $motivo = $request->motivo;
            
            $ajuste = Ajuste::first();
            $token = $ajuste->token;
            $wsdlAnulacion = $ajuste->wsdl."/ServicioFacturacionDocumentoAjuste?wsdl";
            $clienteAnulacion = Ajuste::consumoSIAT($token,$wsdlAnulacion);
            $notaAjuste = NotaAjuste::findOrFail($idNotaAjuste);

            $empresa = Empresa::first();
            $cuis = Cuis::obtenerCuis();
            if (!is_null($cuis)) {
                $cufd = Cufd::obtenerCufd();
                if (!is_null($cufd)) {
                    if ($clienteAnulacion->verificarComunicacion()->return->transaccion == "true") {
                        $parametrosAnulacionNotaAjuste = array(
                            'anulacionDocumentoAjuste' => array(
                                'codigoAmbiente' => $empresa->ambiente,
                                'codigoDocumentoSector' => $notaAjuste->codigoDocumentoSector,
                                'codigoEmision' => 1,
                                'codigoModalidad' => $empresa->modalidad,
                                'codigoPuntoVenta' => $notaAjuste->puntoVenta->codigo,
                                'codigoSistema' => $empresa->codigo_sistema,
                                'codigoSucursal' => $notaAjuste->puntoVenta->agencia->codigo,
                                'cufd' => $cufd->codigo_cufd,
                                'cuis' => $cuis->codigo_cuis,
                                'nit' => $empresa->nit,
                                'tipoFacturaDocumento' => $notaAjuste->tipoFactura,
                                'codigoMotivo' => $motivo,
                                'cuf' => $notaAjuste->cuf
                            )
                        );
                        $msjAnularNotaAjuste = NotaAjuste::soapAnulacionNotaAjuste($clienteAnulacion, $parametrosAnulacionNotaAjuste, $idNotaAjuste);
                        if ($msjAnularNotaAjuste == "") {
                            BitacoraHelper::registrar('Anulación Nota de Ajuste', 'Anulación de Nota de Ajuste por el usuario: ' . Auth::user()->name, 'Venta');
                            return response()->json(['message' => 'Nota de Ajuste Anulada'], 200);
                        } else {
                            return response()->json(['message' => $msjAnularNotaAjuste], 500);
                        }                
                    } else {
                        return response()->json(['message' => 'Error de comunicación SIAT'], 500);
                    }
                } else {
                    return response()->json(['message' => 'CUFD desactualizado'], 500);
                }
            } else {
                return response()->json(['message' => 'CUIS desactualizado'], 500);
            }                      
        }
    }

    public function revertirAnulacionNotaAjuste($idNotaAjuste)
    {
        $ajuste = Ajuste::first();
        $token = $ajuste->token;
        $wsdlAnulacion = $ajuste->wsdl."/ServicioFacturacionDocumentoAjuste?wsdl";
        $clienteAnulacion = Ajuste::consumoSIAT($token,$wsdlAnulacion);
        $notaAjuste = NotaAjuste::findOrFail($idNotaAjuste);
        $empresa = Empresa::where('estado','A')->first();
        $cuis = Cuis::obtenerCuis();
        if (!is_null($cuis)) {
            $cufd = Cufd::obtenerCufd();
            if (!is_null($cufd)) {
                if ($clienteAnulacion->verificarComunicacion()->return->transaccion == "true") {
                    $parametrosRevertirAnulacionNotaAjuste = array(
                        'SolicitudServicioReversionAnulacionDocumentoAjuste' => array(
                            'codigoAmbiente' => $empresa->ambiente,
                            'codigoDocumentoSector' => $notaAjuste->codigoDocumentoSector,
                            'codigoEmision' => 1,
                            'codigoModalidad' => $empresa->modalidad,
                            'codigoPuntoVenta' => $notaAjuste->puntoVenta->codigo,
                            'codigoSistema' => $empresa->codigo_sistema,
                            'codigoSucursal' => $notaAjuste->puntoVenta->agencia->codigo,
                            'cufd' => $cufd->codigo_cufd,
                            'cuis' => $cuis->codigo_cuis,
                            'nit' => $empresa->nit,
                            'tipoFacturaDocumento' => $notaAjuste->tipoFactura,
                            'cuf' => $notaAjuste->cuf
                        )
                    );
                    $msjRevertirAnularNotaAjuste = NotaAjuste::soapRevertirAnulacionNotaAjuste($clienteAnulacion, $parametrosRevertirAnulacionNotaAjuste, $idNotaAjuste);
                    if ($msjRevertirAnularNotaAjuste == "") {
                        BitacoraHelper::registrar('Revertir Anulación Nota de Ajuste', 'Revertir Anulación de Nota de Ajuste por el usuario: ' . Auth::user()->name, 'Venta');
                        return redirect('/notaAjuste')->with('toast_success','Revertir Anulación de Nota de Ajuste Exitosa');
                    } else {
                        return redirect('/notaAjuste')->with('toast_error', $msjRevertirAnularNotaAjuste);
                    }                
                } else {
                    return redirect('/notaAjuste')->with('toast_error','Error de comunicación SIAT');
                }
            } else {
                return redirect("/notaAjuste")->with('toast_error','CUFD desactualizado');
            }
        } else {
            return redirect("/notaAjuste")->with('toast_error','CUIS desactualizado');  
        }        
    }
}
