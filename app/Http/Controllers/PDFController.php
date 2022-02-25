<?php
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use App\Models\Laboratorio;
use PDF;
use Carbon\Carbon;
  
class PDFController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function generatePDF()
    {
        $data = [
            'title' => 'Welcome to ItSolutionStuff.com',
            'date' => date('m/d/Y')
        ];
          
        $pdf = PDF::loadView('reporte.reporte', $data);
    
        return $pdf->download('itsolutionstuff.pdf');
    }

    public function listaLaboratorios()
    {
        $fecha=Carbon::now('America/La_Paz')->format('d/m/y');        
        $laboratorios=Laboratorio::orderBy('nombre')->get();
        $pdf = PDF::loadView('laboratorio.reporte',['laboratorios' => $laboratorios,'fecha'=>$fecha]);
        return $pdf->download('Laboratorios.pdf');
    }
}