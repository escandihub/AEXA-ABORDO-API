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
use PhpParser\Node\Stmt\TryCatch;
use Livewire\Attributes\On; 
use App\Http\Controllers\OperadoresService\GetRelation;

class Operadores extends Component
{
    use WithPagination, WithoutUrlPagination;

    #[Url]
    public ?string $filter = 'all';
    #[Url]
    public ?string $search = '';
    #[Url]
    public ?string $date = '';

    public function render()
    {
        // $this->query();
        return view('livewire.nomina.operadores', [
            'corridas' =>  $this->filters()->paginate(10),
            'operadores' => $this->getOperadores(),

        ]);
    }

    public function query()
    {
        $now = \Carbon\CarbonImmutable::now(); // $now->format('Y-m-d')
        $corrida = DB::table('diario_c')->whereBetween('fecha', ["2025-05-01", "2025-05-15"])->where('condicion_corrida', 'Disponible')
            ->where('clase', '!=', 3)
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

    private function getOperadores(){
        $operadores = resolve(GetRelation::class);

        $v = $operadores->operadores();
        // dd($v);
        return $v;
    }

     #[On('name-selected')] 
    public function updateOperador($diario, $name, $full_name_operador2 = ""){
        // Aquí puedes implementar la lógica para actualizar el operador
        // Por ejemplo, podrías hacer una llamada a un servicio o actualizar la base de datos directamente
       // \Log::info("Actualizando operador con ID: $diario_c_id, operador1: $full_name_operador1, operador2: $full_name_operador2");
        try {
/*
            $update = DB::table('diario_c')
            ->where('id_diario_c', $diario_c_id)
            ->update([
                'operador1' => $full_name_operador1,
                'operador2' => $full_name_operador2
            ]);
*/
            \Log::info("Actualizando operador con ID: $diario, operador1: $name, operador2: $full_name_operador2");
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Manejo de errores de validación
            \Log::error("Error de validación: " . $e->getMessage());
            return false;
        }
        
        // Actualización ficticia
        return true;
    }
}
/**
 * trigger en la base de datos 
 * para ver cuantas corridas si fueron actualizadas 
 */
