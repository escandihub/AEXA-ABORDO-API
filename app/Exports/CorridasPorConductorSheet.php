<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CorridasPorConductorSheet // WithHeadings
{
    protected $datos;

    protected Collection $data;
    public array $header;
    public array $subheader;


    public function __construct(Collection $data)
    {
        $this->data = $data;
        // $this->headings = $headings;
    }

    public function collection()
    {
        // Excluye las primeras 2 filas (nombres operadores y subencabezados) porque ya las tiene en `headings()`
        return $this->data->slice(2)->values();
    }




    public function array()
    {
        $operadores = $this->data
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
                                'autobus' => $item->autobus, 
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
                return $items->pluck('data')->groupBy('fecha');;
            });

        // Paso 2: Obtener rango de fechas
        $fechas = $this->data->pluck('fecha')->filter()->unique()->sort();
        $minFecha = $fechas->min();
        $maxFecha = $fechas->max();
        // dd($minFecha, $maxFecha);

        if (!$minFecha || !$maxFecha) {
            // No hay fechas, retornar estructura vacía
            return collect([]);
        }
        $rangoFechas = collect();

        for ($date = \Carbon\Carbon::parse($minFecha); $date->lte($maxFecha); $date->addDay()) {
            $rangoFechas->push($date->toDateString());
        }
         
        // Paso 3: Mapear datos para que cada operador tenga todas las fechas del rango
        $operadores = $operadores->map(function ($viajes) use ($rangoFechas) {
            //  (puede haber más de uno por fecha)
            // Para cada fecha del rango, agrega todos los viajes de esa fecha (o un registro vacío si no hay)
            return $rangoFechas->flatMap(function ($fecha) use ( $viajes) {
            if ($viajes->has($fecha)) {
                // Puede haber varios viajes en la misma fecha
                return $viajes[$fecha]->all();
            }
            // Si no hay viajes para esa fecha, agrega un registro vacío
            return [[
                'fecha' => $fecha,
                'autobus' => '',
                'ruta' => '',
                'precio' => 0,
            ]];
            })->values();
        });

        // Ordenar por nombre
        $operadores = collect($operadores->sortKeys()->all());
        $resultado = collect();


        // 1. Encabezado con nombre del operador (cada 3 columnas)
        $filaNombres = [];
        foreach ($operadores as $nombre => $data) {
            $filaNombres[] = $nombre;
            $filaNombres[] = '';
            $filaNombres[] = '';
            $filaNombres[] = '';
            $filaNombres[] = ''; // Espacio para el nombre del operador
        }
       // $resultado->push($filaNombres);

        // 2. Subencabezado (Fecha, Ruta, Monto)
        $subencabezado = [];
        foreach ($operadores as $data) {
            $subencabezado[] = 'Fecha';
            $subencabezado[] = '# Autobús';
            $subencabezado[] = 'Ruta';
            $subencabezado[] = 'Monto';
            $subencabezado[] = ''; // Espacio para separar columnas
        }
        $this->header = $filaNombres;
        $this->subheader = $subencabezado;

        //$resultado->push($subencabezado);


        // 3. Cuerpo de datos
        $maxFilas = $operadores->map(fn($items) => count($items))->max();

        for ($i = 0; $i < $maxFilas; $i++) {
            $fila = [];

            foreach ($operadores as $data) {
                $viaje = $data[$i] ?? null;

                if ($viaje) {
                    // dd($viaje);
                    $fila[] = $viaje['fecha'];
                     $fila[] = $viaje['autobus'];
                    $fila[] = $viaje['ruta'];
                    $fila[] = '$' . number_format($viaje['precio'], 2);
                    $fila[] = ''; // Espacio para separar columnas
                } else {
                    $fila[] = '';
                     $fila[] = '';
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
            $filaTotales[] = '';
             $filaTotales[] = '';
            $filaTotales[] = 'Total';
            $filaTotales[] = '$' . number_format($total, 2);
            $filaTotales[] = '';
        }

        $resultado->push($filaTotales);
        return $resultado;
    }


    private function priceRoute($origen, $destino): float
    {
        // Aquí puedes implementar la lógica para calcular el precio de la ruta
        // Por ahora, retornamos un valor fijo
        if($origen === 'TGZ' && $destino === 'TAP' ||
           $origen === 'TAP' && $destino === 'TGZ') {
            return 445.00;
        } elseif ($origen === 'TGZ' && $destino === 'PAL' ||
                  $origen === 'PAL' && $destino === 'TGZ') {
            return 465.00;
        } elseif ($origen === 'TGZ' && $destino === 'TON' ||
                  $origen === 'TON' && $destino === 'TGZ') {
            return 445.00;
        } elseif ($origen === 'TAP' && $destino === 'ARR' ||
                  $origen === 'ARR' && $destino === 'TAP') {
            return 445.00;
        }elseif ($origen === 'C' && $destino === 'D') {
            return 100.00;
        }
        return 100.00;
    }
}
