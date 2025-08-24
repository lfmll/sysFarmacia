<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Parametro;
use App\Models\PuntoVenta;
use App\Models\Agencia;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\TipoParametro;
use App\Models\Cuis;
use App\Models\Cufd;
use App\Models\Ajuste;
use App\Models\Factura;
use Illuminate\Support\Facades\DB;
use ZipArchive;
use App\Helpers\BitacoraHelper;
use App\Models\Bitacora;
use Illuminate\Support\Facades\Auth;

class EventoController extends Controller
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
        $eventos = Evento::all();
        return view('evento.index', ['eventos' => $eventos]);        
    }

    public function create()
    {
        //Obtener evento Abierto
        $eventoAbierto = Evento::where('estado', 'Abierto')->first();
        if (is_null($eventoAbierto)) {
            // Si no hay evento abierto, se puede crear uno nuevo
            $fecha = Carbon::now('America/La_Paz');
            $hora = Carbon::now('America/La_Paz')->format('H:i');
            $eventos = Parametro::where('tipo_parametro_id', TipoParametro::where('nombre', 'EVENTOS SIGNIFICATIVOS')->first()->id)
                ->orderBy('descripcion', 'ASC')
                ->pluck('descripcion', 'codigo_clasificador');
            
            $evento = new Evento();
            return view('evento.create', ['evento' => $evento])
                ->with('fecha', $fecha)
                ->with('hora', $hora)
                ->with('eventos', $eventos)
                ->with('toast_success', 'Nuevo evento significativo creado.');
        } else {
            // Si ya hay un evento abierto, redirigir a la vista del evento abierto
            return redirect('evento')->with('toast_error', 'Ya existe un evento significativo abierto.');
        }
        
    }

    public function store(Request $request)
    {
        $fecha = Carbon::now('America/La_Paz')->format('Y-m-d\TH:i:s');
        $fecha = $fecha.'.'.str_pad(now('America/La_Paz')->milli, 3, '0', STR_PAD_LEFT);
        $puntoVenta = PuntoVenta::where('user_id', auth()->id())->first();    
        $sucursal = Agencia::where('id','=',$puntoVenta->agencia_id)->first();
        $empresa = Empresa::first();
        $ultimocuis = Cuis::where('punto_venta_id', $puntoVenta->id)->orderBy('id','DESC')->first();
        $ultimocufd = Cufd::where('cuis_id', $ultimocuis->id)->orderBy('id', 'DESC')->first();        
        $parametro = DB::table('parametros')
            ->join('tipo_parametros','parametros.tipo_parametro_id','=','tipo_parametros.id')
            ->where('tipo_parametros.nombre','=','EVENTOS SIGNIFICATIVOS')
            ->where('parametros.codigo_clasificador', $request->tipos)
            ->get();
        $docSector = DB::table('parametros')
            ->join('tipo_parametros','parametros.tipo_parametro_id','=','tipo_parametros.id')
            ->where('tipo_parametros.nombre','=','TIPO DOCUMENTO SECTOR')
            ->where('parametros.descripcion','=','FACTURA COMPRA-VENTA')
            ->get();
        
        $evento = new Evento();
        
        $evento->codigoAmbiente = 2; // Ambiente de Prueba
        $evento->codigoSistema = $empresa->codigo_sistema;
        $evento->nit = $empresa->nit;
        $evento->cuis = $ultimocuis->codigo_cuis;
        $evento->cufd = $ultimocufd->codigo_cufd;                
        $evento->codigoSucursal = $sucursal->codigo;
        $evento->codigoPuntoVenta = $puntoVenta->codigo;
        $evento->codigoDocumentoSector = $docSector[0]->codigo_clasificador;
        $evento->codigoEvento = $parametro[0]->codigo_clasificador;
        $evento->descripcion = $parametro[0]->descripcion;
        $evento->estado = 'Abierto';
        $evento->fechaInicioEvento = $fecha;
        $evento->fechaFinEvento = null;
        $evento->cufdEvento = $ultimocufd->codigo_cufd; // Asignar el CUFD del último CUFD registrado
        $evento->cafc = null; // Asignar CAFc si es necesario
        $evento->codigoRecepcion = null; // Asignar código de recepción al cerrar el evento
        $evento->cantidadFacturas = 0; // Inicializar cantidad de facturas a 0
        $evento->save();

        if ($evento->save()) {            
            BitacoraHelper::registrar('Registro Evento Significativo', 'Evento significativo iniciado por el usuario: ' . Auth::user()->name, 'Evento');            
            return redirect('/evento')->with('success', 'Evento significativo creado exitosamente.');
        } else {
            return view('evento.create')->with('error', 'Error al crear el evento significativo.');
        }                
    }

    public function show($id)
    {
        $evento = Evento::findOrFail($id);
        return view('evento.show', ['evento' => $evento]);
    }

    public function update($id)
    {    
        $empresa = Empresa::first();
        $fechaEnvio = Carbon::now('America/La_Paz')->format('Y-m-d\TH:i:s');
        $fechaEnvio = $fechaEnvio.'.'.str_pad(now('America/La_Paz')->milli, 3, '0', STR_PAD_LEFT);
        $cuis = Cuis::obtenerCuis();
        $cufd = Cufd::obtenerCufd();
        $fecha = Carbon::now('America/La_Paz')->format('Y-m-d\TH:i:s');
        $fecha = $fecha.'.'.str_pad(now('America/La_Paz')->milli, 3, '0', STR_PAD_LEFT);
        $fechaEmision = Factura::deFechaNumero($fecha);
        $empresa = Empresa::first();
        //Obtener Facturas del Evento
        $facturas = Factura::where('evento_id', $id)->get();
        if (!is_null($cufd)) {
            //Actualizar Facturas
            foreach ($facturas as $factura) {
                $cuf = Factura::generarCUF(
                    $factura->nitEmisor,
                    $factura->codigoSucursal,
                    $fechaEmision,
                    $empresa->modalidad,
                    1, // Tipo de Emisión: 1 Online
                    1, // Tipo de Factura: 1 Documento Credito Fiscal
                    $factura->codigoDocumentoSector,
                    $factura->numeroFactura,
                    0 //POS                
                );            
                $factura->cuf = $cuf.$cufd->codigo_control;     
                $factura->fechaEmision = $fecha;  
                $factura->cufd = $cufd->codigo_cufd;
                $factura->save();
            }
            
            //Registrar el evento significativo
            $ajuste = Ajuste::first();
            $token = $ajuste->token;            
            $wsdlOperaciones = $ajuste->wsdl."/FacturacionOperaciones?wsdl";
            $wsdlRecepcion = $ajuste->wsdl."/ServicioFacturacionCompraVenta?wsdl";
            $clienteOperaciones = Ajuste::consumoSIAT($token, $wsdlOperaciones);
            $clienteCompraVenta = Ajuste::consumoSIAT($token, $wsdlRecepcion);
                               
            if ($clienteOperaciones->verificarComunicacion()->return->mensajesList->codigo == "926") {    
                //Registrar Evento Significativo   
                $msjError = Evento::soapRegistrarEvento($clienteOperaciones, $id);            
                if ($msjError == "") {
                    $evento = Evento::findOrFail($id);
                    
                    $cantidadFacturas = $evento->cantidadFacturas;
                    if ($cantidadFacturas > 0) {
                        $dir = public_path('/siat/facturas/evento'.$evento->id);
                        if (!file_exists($dir)) {
                            mkdir($dir, 0777, true);
                        }
                        $xmlFiles = [];
                        foreach ($facturas as $factura) {
                            $xmlFileName = $factura->id.'.xml';
                            $xmlFilePath = $dir.'/'.$xmlFileName;
                            file_put_contents($xmlFilePath, Factura::generarXML($factura->id));
                            $xmlFiles[] = $xmlFilePath; // Guardar la ruta del archivo XML
                        }
                        // Crear un archivo TAR
                        $tarFileName = 'paquete_evento_'.$evento->id.'.tar';
                        $tarFilePath = $dir.'/'.$tarFileName;
                        $tar = new \PharData($tarFilePath);
                        foreach ($xmlFiles as $xmlFile) {
                            $tar->addFile($xmlFile, basename($xmlFile)); // Añadir el XML al archivo TAR
                        }
                        // Comprimir el TAR a GZIP
                        $tar->compress(\Phar::GZ); // Comprimir el TAR a GZIP
                        
                        $gzPath = $tarFilePath.'.gz';
                        $byteArray = file_get_contents($gzPath);
                        $hashArchivo = hash('sha256', $byteArray);
                        //Enviar Paquete de Facturas
                        if ($clienteCompraVenta->verificarComunicacion()->return->transaccion == "true") {
                            $parametrosPaquetes = array(
                                'SolicitudServicioRecepcionPaquete' => array(
                                    'codigoAmbiente' => $evento->codigoAmbiente,
                                    'codigoDocumentoSector' => $evento->codigoDocumentoSector,
                                    'codigoEmision' => 2, // Tipo de Emisión: 1 Online 2 Offline  
                                    'codigoModalidad' => 2,
                                    'codigoPuntoVenta' => $evento->codigoPuntoVenta,
                                    'codigoSistema' => $empresa->codigo_sistema,
                                    'codigoSucursal' => $evento->codigoSucursal,
                                    'cufd' => $cufd->codigo_cufd,
                                    'cuis' => $cuis->codigo_cuis,
                                    'nit' => $empresa->nit,
                                    'tipoFacturaDocumento' => 1, // Tipo de Documento: 1 Factura
                                    'archivo' => $byteArray,
                                    'fechaEnvio' => $fechaEnvio,
                                    'hashArchivo' => $hashArchivo,
                                    'cafc' => $evento->cafc,
                                    'cantidadFacturas' => $evento->cantidadFacturas,
                                    'codigoEvento' => $evento->codigoRecepcion
                                )
                            );
                            $msjError = Evento::soapRececpcionPaqueteFactura($clienteCompraVenta, $parametrosPaquetes); 
                            if ($msjError != "") {
                                return redirect('/evento')->with('toast_error', 'Error al enviar el paquete de facturas: ' . $msjError);
                            }
                        } else{
                            return redirect('/evento')->with('toast_error', 'Error de comunicación para enviar el paquete de facturas.');
                        }                        
                    }
                    BitacoraHelper::registrar('Cierre Evento Significativo', 'Evento significativo cerrado por el usuario: ' . Auth::user()->name, 'Evento');
                    return redirect('/evento')->with('toast_success', 'Evento significativo cerrado exitosamente.');
                } else {
                    return redirect('/evento')->with('toast_error', 'Error al cerrar el evento significativo: ' . $msjError);
                }                                        
            } else {
                return redirect('/evento')->with('toast_error', 'Error de comunicación para registrar el evento significativo.');
            }                                                      
        } else {
            return redirect('/evento')->with('toast_error', 'No se encontró un CUFD activo.');
        }        
    }

    public function destroy($id)
    {

    }

}
