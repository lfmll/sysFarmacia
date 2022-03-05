<?php

namespace App\Imports;

use App\Models\Medicamento;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class MedicamentoImport implements ToModel, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        ++$this->numRows;
        return new Medicamento([
            'nombre_comercial'  =>  $row[0],

        ]);
    }

    public function getRowCount(): int
    {
        return $this->numRows;
    }
}
