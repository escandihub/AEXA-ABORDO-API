<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CorridasPorConductorSheet implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $datos;

    protected Collection $data;
    protected array $headings;


   public function __construct(Collection $data)
    {
        $this->data = $data;
        // $this->headings = $headings;
    }

    public function headings(): array
    {
        return $this->headings;
    }

      public function collection()
    {
        // Excluye las primeras 2 filas (nombres operadores y subencabezados) porque ya las tiene en `headings()`
        return $this->data->slice(2)->values();
    }




    public function array(): array
    {
        $operadores = collect($this->datos)
            ->flatMap(function ($item) {
                return collect(['operador1', 'operador2'])->map(function ($key) use ($item) {
                    // Log::info($item['fecha']);
                    // Log::info($key);
                    $nombre = trim($item->$key ?? '');

                    if ($nombre && $nombre !== 'No Asignado') {
                        return [
                            'nombre' => $nombre,
                            'data' => [
                                'fecha' => $item->fecha, //$item['fecha'],
                                'ruta' =>   "{$item->origen} - {$item->destino}", //"{$item['origen']} - {$item['destino']}",
                                'precio' => $this->priceRoute($item->origen, $item->destino),
                            ],
                        ];
                    }

                    return null;
                })->filter(); // elimina nulos
            })
            ->groupBy('nombre')
            ->map(function ($items) {
                return $items->pluck('data');
            });
       // dd($operadores->all());
        // Ordenar por nombre
        // Ordenar por nombre de operador
        $operadores = collect($operadores->sortKeys()->all());
        // Agrupar operadores en bloques de 2
        $ops = $operadores->chunk(2);

        $resultado = collect();

        // 1. Encabezado con nombre del operador (cada 3 columnas)
        $filaNombres = [];
        foreach ($operadores as $nombre => $data) {
            $filaNombres[] = $nombre;
            $filaNombres[] = '';
            $filaNombres[] = '';
        }
        $resultado->push($filaNombres);

        // 2. Subencabezado (Fecha, Ruta, Monto)
        $subencabezado = [];
        foreach ($operadores as $data) {
            $subencabezado[] = 'Fecha';
            $subencabezado[] = 'Ruta';
            $subencabezado[] = 'Monto';
        }
        $this->headings = $subencabezado;
        $resultado->push($subencabezado);


        // 3. Cuerpo de datos
        $maxFilas = $operadores->map(fn($items) => count($items))->max();

        for ($i = 0; $i < $maxFilas; $i++) {
            $fila = [];

            foreach ($operadores as $data) {
                $viaje = $data[$i] ?? null;

                if ($viaje) {
                    $fila[] = $viaje['fecha'];
                    $fila[] = $viaje['ruta'];
                    $fila[] = '$' . number_format($viaje['precio'], 2);
                } else {
                    $fila[] = '';
                    $fila[] = '';
                    $fila[] = '';
                }
            }

            $resultado->push($fila);
        }

        // 4. Fila de totales
        $filaTotales = [];

        foreach ($operadores as $data) {
            $total = collect($data)->sum('precio');
            $filaTotales[] = 'Total';
            $filaTotales[] = '';
            $filaTotales[] = '$' . number_format($total, 2);
        }

        $resultado->push($filaTotales);

        return $resultado->toArray();
        /*
        return  $ops->each(function ($bloque) use (&$resultado) {
            $nombres = $bloque->keys()->all();
            $datos = $bloque->values()->all();

            $maxFilas = collect($datos)->map->count()->max();

            for ($i = 0; $i < $maxFilas; $i++) {
                $fila = [];

                foreach ($datos as $opIndex => $opData) {
                    if ($i === 0) {
                        // Título con nombre del operador
                        $fila[] = $nombres[$opIndex];
                        $fila[] = '';
                        $fila[] = '';
                    }

                    $corrida = $opData[$i] ?? null;
                    if ($corrida) {
                        $fila[] = $corrida['fecha'];
                        $fila[] = $corrida['ruta'];
                        $fila[] = '$' . number_format($corrida['precio'], 2);
                    } else {
                        $fila[] = '';
                        $fila[] = '';
                        $fila[] = '';
                    }
                }

                $resultado->push($fila);
            }

            // espacio entre bloques
            $resultado->push([]);
        })->toArray(); */
    }


    private function priceRoute($origen, $destino): float
    {
        // Aquí puedes implementar la lógica para calcular el precio de la ruta
        // Por ahora, retornamos un valor fijo
        return 100.00;
    }

     public function styles(Worksheet $sheet)
    {
        $columnCount = count($this->headings[0]);

        $columnLetters = range('A', chr(64 + $columnCount));
        $lastCol = end($columnLetters);
        $rowCount = $this->data->count() + 2; // +2 porque usamos `headings()`

        return [
            // Cabecera principal (operadores)
            '1' => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '4F81BD']],
                'alignment' => ['horizontal' => 'center'],
            ],
            // Subencabezados (fecha, ruta, monto)
            '2' => [
                'font' => ['bold' => true],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'D9E1F2']],
                'alignment' => ['horizontal' => 'center'],
            ],
            // Totales (última fila)
            $rowCount => [
                'font' => ['bold' => true],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'FCE4D6']],
            ],
        ];
    }
}
