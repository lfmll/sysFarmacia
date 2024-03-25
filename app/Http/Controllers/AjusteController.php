<?php

namespace App\Http\Controllers;

use App\Models\Ajuste;
use App\Models\TipoDocumento;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use ZipArchive;
use PDO;

use App\Models\Medida;

class AjusteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ajustes = Ajuste::first();
        $docsector = TipoDocumento::all();
        return view('ajuste.index',['ajuste'=>$ajustes, 
                                    'docsector'=>$docsector]);
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
        //
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
            
            // header("Content-type: application/zip"); 
            // header("Content-Disposition: attachment; filename=$zipFileName");
            // header("Content-length: " . filesize($zipFileName));
            // header("Pragma: no-cache"); 
            // header("Expires: 0"); 
            // readfile("$zipFileName");
            return redirect('/ajuste')->with('toast_success','Respaldo guardado en: '.$path);
        } else {
            return redirect('/ajuste')->with('toast_error','Error al abrir Zip');                           
        }
    }
}
