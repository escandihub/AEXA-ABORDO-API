<?php

namespace App\Livewire\Nomina;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Builder;

use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

use Livewire\Component;
use App\Exports\CorridasPorConductorSheet;
use App\Exports\OperadoresExport;

class Operadores extends Component
{
    use WithPagination, WithoutUrlPagination;

    #[Url]
    public ?string $filter = 'all';
    #[Url]
    public ?string $search = '';

    public function render()
    {
        // $this->query();
        return view('livewire.nomina.operadores', [
            'corridas' =>  $this->filters()->paginate(10),
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
                ],
                [
                    'id' => 5,
                    'nombre' => 'SAMUEL DOMINGUEZ URBINA',
                    'telefono' => '123456117890',
                    'email' => ''
                ],
                [
                    'id' => 6,
                    'nombre' => 'FRANCISCO JAVIER OJEDA GONZALEZ',
                    'telefono' => '123456117890',
                    'email' => ''
                ]
            ],

        ]);
    }

    public function query()
    {
        $now = \Carbon\CarbonImmutable::now();
        $corrida = DB::table('diario_c')->whereBetween('fecha', ["2025-05-20", $now->format('Y-m-d')])->where('condicion_corrida', 'Disponible')
            ->select('fecha', 'hora', 'minutos', 'origen', 'destino', 'autobus', 'clase', 'operador1', 'operador2', 'id_diario_c')
            ->orderBy('fecha');

        // \Log::info($corrida);
        return $corrida;
        /**
         * SELECT fecha, hora, origen, destino, autobus, operador1, operador2, id_diario_c FROM diario_c
        WHERE fecha between '2025-04-16' and '2025-04-30' and condicion_corrida = "Disponible"
        ORDER BY fecha DESC, operador1 DESC
         */
    }

    private function filters()
    {
        $now = \Carbon\CarbonImmutable::now();
        \Log::info($now->subMinutes(30)->format('H') . ':' . $now->subMinutes(30)->format('i'));
        \Log::info($now->addMinutes(30)->format('H') . ':' . $now->addMinutes(30)->format('i'));

       return $this->query()->when($this->filter == 'now', function ($query) use ($now) {
            $query
            ->whereBetween('hora', [$now->subMinutes(30)->format('H'), $now->addMinutes(30)->format('H')]);
            // ->whereBetween('minutos', [$now->subMinutes(30)->format('i'), $now->addMinutes(30)->format('i')]);
        })->when($this->search, function ($query) {
            $query->where(function ($q) {
                $q->where('autobus', 'like', '%' . $this->search . '%')
                    ->orWhere('operador1', 'like', '%' . $this->search . '%');
            });
        });
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
    public function generateReport()
    {
        $datos = $this->query();
        $Formating = new CorridasPorConductorSheet($datos->get());
        // dd($datos);
        $array = $Formating->array();

        return \Excel::download(new OperadoresExport($array, [$Formating->header, $Formating->subheader]), 'operadores.xlsx');
    }
}
