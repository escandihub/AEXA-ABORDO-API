<?php
namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OperadoresExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    protected Collection $data;
    protected array $headings;

    public function __construct(Collection $data, array $headings)
    {
        $this->data = $data;
        $this->headings = $headings;
    }

    public function headings(): array
    {
        return $this->headings;
    }

    public function array(): array
    {
        return $this->data->toArray(); // ->slice(2)->values(); // omitir las 2 filas de encabezado
    }

    public function styles(Worksheet $sheet)
    {
        $columnCount = count($this->headings[0]);
        $rowCount = $this->data->count() + 2; // +2 por las filas de encabezado

        return [
            // Cabecera: nombres de operadores
            '1' => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '4F81BD']],
                'alignment' => ['horizontal' => 'center'],
            ],
            // Subencabezado: Fecha, Ruta, Monto
            '2' => [
                'font' => ['bold' => true],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'D9E1F2']],
                'alignment' => ['horizontal' => 'center'],
            ],
            // Totales al final
            $rowCount => [
                'font' => ['bold' => true],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'FCE4D6']],
            ],
        ];
    }
}
