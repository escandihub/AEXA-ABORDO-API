<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class OperadoresExport implements WithMultipleSheets
{

    protected $datos;

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
    }

    public function __construct(array $datos)
    {
        $this->datos = $datos;
    }

     public function sheets(): array
    {
        $sheets = [];

        foreach ($this->datos as $conductor => $corridas) {
            $sheets[] = new CorridasPorConductorSheet($conductor, $corridas);
        }

        return $sheets;
    }
}
