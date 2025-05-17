<?php

namespace App\Livewire\Nomina;

use Illuminate\Support\Facades\DB;

use Livewire\Component;

class Operadores extends Component
{
    public function render()
    {
        // $this->query();
        return view('livewire.nomina.operadores', [
            'corridas' =>  $this->query(),
            'operadores' => [
                [
                    'id' => 1,
                    'nombre' => 'Juan Perez',
                    'telefono' => '1234567890',
                    'email' => ''
                ],
                [
                    'id' => 2,
                    'nombre' => 'Juan Perez 1',
                    'telefono' => '1234567890',
                    'email' => ''
                ],
                [
                    'id' => 3,
                    'nombre' => 'Juan Perez 2',
                    'telefono' => '1234567890',
                    'email' => ''
                ],
                [
                    'id' => 4,
                    'nombre' => 'MANUEL DE JESUS MENDEZ',
                    'telefono' => '123456117890',
                    'email' => ''
                ]
            ],

        ]);
    }

    public function query()
    {
        $now = \Carbon\CarbonImmutable::now();
        $corrida = DB::table('diario_c')->where('fecha', $now->format('Y-m-d'))->where('condicion_corrida', 'Disponible')
            ->select('fecha', 'hora', 'minutos', 'origen', 'destino', 'autobus', 'clase', 'operador1', 'operador2', 'id_diario_c')
            ->orderBy('fecha')
            ->get();

        \Log::info($corrida);
        return $corrida;
        /**
         * SELECT fecha, hora, origen, destino, autobus, operador1, operador2, id_diario_c FROM diario_c
        WHERE fecha between '2025-04-16' and '2025-04-30' and condicion_corrida = "Disponible"
        ORDER BY fecha DESC, operador1 DESC
         */
    }

    /**
     * SELECT fecha, hora, origen, destino, autobus, operador1, operador2, id_diario_c FROM diario_c
WHERE fecha between '2025-04-16' and '2025-04-30' and condicion_corrida = "Disponible"
ORDER BY fecha DESC, operador1 DESC

SELECT fecha, hora, origen, destino, autobus, operador1, operador2, id_diario_c, clase FROM diario_c
WHERE fecha = "2025-04-15"  and condicion_corrida = "Disponible"
ORDER BY hora ASC

select * from `sessions` where `id` = "M58i05QvTZDQsuTAhgtMrCunORQvsCJxfWakJCA2" limit 1
     */
}
