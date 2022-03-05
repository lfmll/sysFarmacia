<?php

namespace App\Http\Controllers;

use App\Imports\MedicamentoImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;

class ImportController extends Controller
{
    public function importMedicamento()
    {
        return view('medicamento.importMedicamento');
    }
    public function importM(Request $request)
    {
        try {
            set_time_limit(0);
            
            $file=$request->file('Medicamentos');
            if ($file) {
                $filename=$file->getClientOriginalName();
                dd($filename);
            }
            dd("nada");
            DB::beginTransaction();
            Excel::import(new UsersImport(), $file);
            
            return back()
                ->with('notification', ['type' => 'success', 'title' => 'Medicamentos importados']);
        } catch (\Exception $exception) {
            DB::rollBack();
            return back()
                ->with('notification', ['type' => 'danger', 'title' => 'Error importando Medicamentos']);
        }
    }
}
