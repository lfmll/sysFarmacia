<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MedicamentoExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $headings;
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function __construct(array $headings)
    {
        $this->headings = $headings;
    }

    public function array(): array
    {
        return [];
    }
    /**
     * @return array
     */  
    public function headings(): array
    {
        return $this->headings;
    } 

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:I1')->getFont()->setBold(true);
        $sheet->getStyle('A1:I1')->getAlignment()->setHorizontal('center');
    }
    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }
}
