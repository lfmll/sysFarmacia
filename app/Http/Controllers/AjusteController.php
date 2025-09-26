<?php

namespace App\Http\Controllers;

use App\Models\Ajuste;
use App\Models\TipoDocumento;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use ZipArchive;
use PDO;
use App\Models\Empresa;
use App\Models\Agencia;
use App\Models\PuntoVenta;
use App\Models\Cuis;
use App\Models\Cufd;
use App\Models\Parametro;
use App\Models\TipoParametro;
use App\Models\Codigo;
use App\Models\Leyenda;
use App\Models\Catalogo;
use App\Models\ActividadDocumento;
use Illuminate\Support\Facades\Auth;
use App\Helpers\BitacoraHelper;
use App\Models\Sincronizacion;

class AjusteController extends Controller
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
        $ajustes = Ajuste::first();        
        $cuis = Cuis::obtenerCuis();
        $tipo_parametro = TipoParametro::all();     
        $fecha_local = Carbon::now('America/La_Paz')->format('Y-m-d H:i:s');       
        
         //Datos para sincronizacion                    
        if (!is_null($cuis)) {
            $cufd = Cufd::obtenerCufd();
            if (!is_null($cufd)) {
                $parametros = Parametro::where('cuis_id',$cuis->id)->orderBy('codigo_clasificador','ASC')->get();        
                $actividades = Codigo::where('cuis_id',$cuis->id)->get();          
                $leyendas = Leyenda::where('cuis_id',$cuis->id)->get();
                $catalogos = Catalogo::where('cuis_id',$cuis->id)->get();
                $actividad_documentos = ActividadDocumento::where('cuis_id',$cuis->id)->get();
                $sincronizacionFechaHora = Sincronizacion::obtenerUltimaSincronizacion(session('agencia_id'), session('punto_venta_id')); 
                            
                if ($sincronizacionFechaHora) {
                    $fechaSincronizada = $sincronizacionFechaHora->fecha_sincronizada;
                    $fecha_local = $sincronizacionFechaHora->fecha_local;
                    $diferencia_horaria = $sincronizacionFechaHora->diferencia_horaria;
                } else {
                    $fechaSincronizada = null;
                    $fecha_local = null;
                    $diferencia_horaria = null;
                }            
            }
        }
        // dd($cuis);
        return view('ajuste.index',['ajuste'=>$ajustes])
                ->with('cuis',$cuis ?? null)
                ->with('cufd',$cufd ?? null)
                ->with('tipo_parametro',$tipo_parametro)
                ->with('parametros',$parametros ?? null)                
                ->with('actividades',$actividades ?? null)
                ->with('leyendas',$leyendas ?? null)
                ->with('catalogos',$catalogos ?? null)
                ->with('actividad_documentos',$actividad_documentos ?? null)
                ->with('fechaSincronizada',$fechaSincronizada ?? null)
                ->with('fecha_local',$fecha_local)
                ->with('diferencia_horaria',$diferencia_horaria ?? null);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ajuste  $ajuste
     * @return \Illuminate\Http\Response
     */
    public function show(Ajuste $ajuste)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ajuste  $ajuste
     * @return \Illuminate\Http\Response
     */
    public function edit(Ajuste $ajuste)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ajuste  $ajuste
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ajuste $ajuste)
    {           
        $ajuste = Ajuste::first();     
        if ($request->token !=null) {
            $ajuste->token = $request->token;
        } else {
            if ($request->inlineRadioOptions == "option1") {    //gmail
                $ajuste->driver = "smtp";
                $ajuste->host = "smtp.gmail.com";
                $ajuste->port = "587";
                $ajuste->encryption = "tls";
                $ajuste->username = $request->fromgmail;
                $ajuste->password = $request->passgmail;
                $ajuste->from = $request->fromgmail;
            } else {
                $ajuste->driver = "smtp";
                $ajuste->host = $request->host;
                $ajuste->port = $request->port;
                $ajuste->encryption = "tls";
                $ajuste->username = $request->username;
                $ajuste->password = $request->password;
                $ajuste->from = $request->username;
            }
        }
        //Registrar Bitacora
        BitacoraHelper::registrar('Registro Correo', 'Ajuste modificado por el usuario: ' . Auth::user()->name, 'Ajuste');
        if ($ajuste->save()) {
            return redirect('/ajuste')->with('toast_success','Registro realizado exitosamente');
        } else {
            return view('ajuste.edit',['ajuste'=>$ajuste])->with('toast_error','Error al registrar');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ajuste  $ajuste
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ajuste $ajuste)
    {
        //
    }
    public function sincronizarCuis()
    {
        $ajuste = Ajuste::first();
        $token = $ajuste->token;
        $wsdlCodigos = $ajuste->wsdl."/FacturacionCodigos?wsdl";           
        
        $clienteCuis = Ajuste::consumoSIAT($token,$wsdlCodigos);
        
        //Sincronizar CUIS
        $msjError = Cuis::sincroCUIS($clienteCuis);
        if ($msjError == "") {
            // Registrar en Bitacora
            BitacoraHelper::registrar('Sincronizacion CUIS', 'CUIS sincronizado por el usuario: ' . Auth::user()->name, 'Cuis');
            return redirect('/ajuste')->with('toast_success',"CUIS Actualizado");
        } else {
            return redirect('/ajuste')->with('toast_error',$msjError);
        }
    }

    public function sincronizarCufd()
    {        
        $ajuste = Ajuste::first();
        $token = $ajuste->token;
        $wsdlCodigos = $ajuste->wsdl."/FacturacionCodigos?wsdl";
        
        $clienteCufd = Ajuste::consumoSIAT($token,$wsdlCodigos);
        
        //Sincronizar CUFD
        $msjError = Cufd::sincroCUFD($clienteCufd);
        if ($msjError == "") {
            // Registrar en Bitacora
            BitacoraHelper::registrar('Sincronizacion CUFD', 'CUFD sincronizado por el usuario: ' . Auth::user()->name, 'Cufd');            
            return redirect('/ajuste')->with('toast_success',"CUFD Actualizado");
        } else {
            return redirect('/ajuste')->with('toast_error', $msjError);    
        }
    }

    public function sincronizar()
    {        
        $ajuste = Ajuste::first();
        $token = $ajuste->token;
        //PASO 1: Consumir servicios SIAT Sincronizacion
        $wsdlSincronizacion = $ajuste->wsdl."/FacturacionSincronizacion?wsdl";      
        
        $empresa = Empresa::where('estado','A')->first();
        $sucursal = Agencia::where('id', session('agencia_id'))->where('estado','A')->first();
        $puntoVenta = PuntoVenta::where('id', session('punto_venta_id'))->where('estado','A')->first();
        $cuis = Cuis::obtenerCuis(); 
        if (!is_null($cuis)) {  
            // PASO 2: Consumir Servicios de Sincronizacion
            $clienteSincronizacion = Ajuste::consumoSIAT($token,$wsdlSincronizacion);
            
            //PASO 2.1: Verificar Comunicacion
            if ($clienteSincronizacion->verificarComunicacion()->return->mensajesList->codigo == "926") 
            {
                //Comunicacion Exitosa            
                //Iniciar Parametros
                $parametrosSincronizacion = array(
                    'SolicitudSincronizacion' => array(
                        'codigoAmbiente' => $empresa->ambiente, 
                        'codigoPuntoVenta' => $puntoVenta->codigo,
                        'codigoSistema' => $empresa->codigo_sistema,
                        'codigoSucursal' => $sucursal->codigo,
                        'cuis' => $cuis->codigo_cuis,
                        'nit' => $empresa->nit
                    )
                );
                
                $estaSincronizado = Sincronizacion::obtenerUltimaSincronizacion($sucursal->id, $puntoVenta->id);
                if (!$estaSincronizado) {
                    //Registrar Sincronizacion
                    $sincronizacion = new Sincronizacion();
                    $sincronizacion->nit = $empresa->nit;
                    $sincronizacion->agencia_id = $sucursal->id;
                    $sincronizacion->punto_venta_id = $puntoVenta->id;
                    $sincronizacion->cuis_id = $cuis->id;
                    $sincronizacion->save();
                } else {
                    $sincronizacion = $estaSincronizado;
                }
                
                //PARAMETROS
                $responseParametros = Parametro::sincronizarParametro($clienteSincronizacion, $parametrosSincronizacion, $cuis->id);            
                                        
                //SECTOR
                //Sincronizar Actividades
                $responseActividades = Codigo::soapActividad($clienteSincronizacion, $parametrosSincronizacion, $cuis->id);

                //Sincronizar Leyendas
                $responseLeyendas = Leyenda::soapLeyenda($clienteSincronizacion, $parametrosSincronizacion, $cuis->id);            
                
                //Sincronizar Catalogos (Productos/Servicios)
                $responseCatalogos = Catalogo::soapCatalogo($clienteSincronizacion, $parametrosSincronizacion, $cuis->id);
                
                //Sincronizar Actividad Documento Sector
                $responseActividadDocumento = ActividadDocumento::soapActividadDocumento($clienteSincronizacion, $parametrosSincronizacion, $cuis->id);
                
                //Sincronizar Fecha y Hora
                $responseFechaHora = Sincronizacion::sincronizacionFechaHora($clienteSincronizacion, $parametrosSincronizacion, $sincronizacion->id);
                                            
                // Registrar en Bitacora
                BitacoraHelper::registrar('Sincronizacion Parametros', 'Sincronizacion realizada por el usuario: ' . Auth::user()->name, 'Parametro');
                return redirect('/ajuste')->with('toast_success', 'Sincronizacion Completada');
                
            } else {
                return redirect('/ajuste')->with('toast_error','Error en el consumo de Servicios de Sincronizacion');
            }
        } else {
            return redirect('/ajuste')->with('toast_error','Error, el CUIS no se encuentra registrado o esta desactualizado');
        }
                    
                                      
    }

    public function crearRespaldo()
    {
        $fecha = Carbon::now('America/La_Paz')->format('dmY');        
        $zipFileName = 'backup-'.$fecha.'.zip';
        $zip = new ZipArchive;
        $path = public_path($zipFileName);                
        $abrirZip = $zip->open($path, ZipArchive::CREATE);
        
        if ($abrirZip === TRUE) {
            $dba = DB::connection()->getPdo();
            $query1 = $dba->query('SHOW TABLES');
            $tablas = array();
            while ($row1 = $query1->fetch()) {
                $tablas[] = $row1[0];
            }
            $r = array();
            $script = "";
            foreach ($tablas as $tabla) {
                $query2 =$dba->prepare('SELECT * FROM '.$tabla);            
                $query2->execute();
                
                $query3 = $dba->prepare('SELECT COUNT(*) FROM '.$tabla);
                $query3->execute();
                $query3 = $query3->fetch(\PDO::FETCH_NUM);
                $numFilas = $query3[0];
                
                $script .= 'DROP TABLE IF EXISTS '.$tabla.';';
                
                $query4 = $dba->prepare('SHOW CREATE TABLE '.$tabla);
                $query4->execute();
                $query4 = $query4->fetchAll(\PDO::FETCH_NUM);     
                
                $script .= "\n\n".$query4[0][1].";\n\n";

                $counter = 1;
                for ($i=0; $i < $numFilas; $i++) { 
                    while ($row2 = $query2->fetch(PDO::FETCH_NUM)) {
                        if ($counter == 1) {
                            $script .= 'INSERT INTO '.$tabla.' VALUES(';    
                        } else {
                            $script .= '(';
                        }
                        $numColumnas = count($row2);
                        for ($j=0; $j < $numColumnas; $j++) { 
                            $row2[$j] = addslashes($row2[$j]);
                            $row2[$j] = str_replace("\n","\\n",$row2[$j]);
                            if (isset($row2[$j])) { //Variable Definida no es null 
                                $script.= '"'.$row2[$j].'"' ; 
                            } else { 
                                $script.= '""'; 
                            }
                            if ($j<($numColumnas-1)) { 
                                $script.= ','; 
                            }                            
                        }
                        
                        if ($numFilas == $counter) {
                            $script .= ");\n";
                        } else {
                            $script .= "),\n";
                        }
                        ++$counter;
                    }                
                }
                $script .= "\n";            
            }          
            //Guardar archivo sql        
            $zip->addFromString('backup_archivo.sql', $script, ZipArchive::FL_OVERWRITE);
            $zip->close();
            //Registrar en Bitacora
            BitacoraHelper::registrar('Respaldo Base de Datos', 'Respaldo realizado por el usuario: ' . Auth::user()->name, 'Ajuste');
            return redirect('/ajuste')->with('toast_success','Respaldo guardado en: '.$path);
        } else {
            return redirect('/ajuste')->with('toast_error','Error al abrir Zip');                           
        }
    }
}
