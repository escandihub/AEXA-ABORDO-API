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
use App\Models\Diario;

class Operadores extends Component
{
    use WithPagination;

    #[Url]
    public ?string $filter = 'all';
    #[Url]
    public ?string $search = '';
    #[Url]
    public ?string $date = '';
     #[Url]
    public ?string $filter_dateOne = null;
     #[Url]
    public ?string $filter_dateTwo = null;

    public function render()
    {
        // $this->query();
        return view('livewire.nomina.operadores', [
            'corridas' =>  $this->query()->paginate(10),
            'operadores' => [],

        ]);
    }

    public function query()
    {
        $now = \Carbon\CarbonImmutable::now(); // $now->format('Y-m-d')
        $query = Diario::disponibles()
        ->select('fecha', 'hora', 'minutos', 'origen', 'destino', 'autobus', 'clase', 'operador1', 'operador2', 'id_diario_c')
        ->orderBy('fecha')
        ->orderBy('hora');
        \Log::info("updating querty:_ {$this->filter_dateOne}");
        if(!$this->filter_dateOne){
            $query->where('fecha', '>=', $now->format('Y-m-d'));
        }
         // Aplicar filtros
        if ($this->filter === 'now') {
            // dd('ahora?');
            $query->horaActual();
        }
        
        if ($this->search) {
            $query->buscar($this->search);
        }
        
        if ($this->filter_dateOne && $this->filter_dateTwo) {
            $query->entreFechas($this->filter_dateOne, $this->filter_dateTwo);
        }else{
            // Si no hay filtro de fecha, mostrar solo las corridas a partir de hoy
            $query->HoraActual(); //where('fecha', '>=', $now->format('Y-m-d'))->whereBetween('hora', [$now->subHour(1)->format('H'), $now->format('H')])->orderBy('hora', 'asc');
        }
        
        return $query; //->paginate(10);

        // \Log::info($corrida);
        /**
         * SELECT fecha, hora, origen, destino, autobus, operador1, operador2, id_diario_c FROM diario_c
        WHERE fecha between '2025-04-16' and '2025-04-30' and condicion_corrida = "Disponible"
        ORDER BY fecha DESC, operador1 DESC
         */
    }
    public function updatingFilter()
    {
        $this->resetPage();
    }

    private function filters()
    {
        $now = \Carbon\CarbonImmutable::now();

       return $this->query()->when($this->filter == 'now', function ($query) use ($now) {
            $query
            ->whereBetween('hora', [$now->subMinutes(30)->format('H'), $now->addMinutes(30)->format('H')]);
            // ->whereBetween('minutos', [$now->subMinutes(30)->format('i'), $now->addMinutes(30)->format('i')]);
        })->when($this->search, function ($query) {
            $query->where(function ($q) {
                $q->where('autobus', 'like', '%' . $this->search . '%')
                    ->orWhere('operador1', 'like', '%' . $this->search . '%');
            });
        })
        ->when($this->filter_dateOne != null, function ($query) {
            $query->whereBetween('fecha', [$this->filter_dateOne, $this->filter_dateTwo]);
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
            ->where('id_diario_c', $diario)
            ->update([
                'operador1' => $name,
                // 'operador2' => $full_name_operador2
            ]); */

            // \Log::info("Operador actualizado: $name, $diario");

            $this->dispatch('task-updated', message: 'Operador actualizado correctamente.');
            \Log::info("Actualizando la corrida ID: $diario, operador1: $name, operador2: $full_name_operador2");
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Manejo de errores de validación
            \Log::error("Error de validación: " . $e->getMessage());
            return false;
        }
        
        // Actualización ficticia
        return true;
    }
    /**
     * escucha el evento new-date que envia el componente date-piker 
     * para realizar un filtrador de fecha de las corridas 
     */
    
    #[On('new-date')] 
    public function newDate($start, $end)
    {
        $this->filter_dateOne = $start;
        $this->filter_dateTwo = $end;

        // $this->dispatch('post-created', data: $result); 
        \Log::info($start);
        \Log::info($end);
    }
    // Método para limpiar filtros
    public function resetFilters()
    {
        $this->reset(['search', 'filter', 'filter_dateOne', 'filter_dateTwo']);
        $this->resetPage();
    }
    
    // Método que se ejecuta cuando cambian los filtros
    public function updatedSearch()
    {
        $this->resetPage();
    }
    
    public function updatedFilter()
    {
        $this->resetPage();
    }
    
    public function updatedFilterDateOne()
    {
        $this->resetPage();
    }
    
    public function updatedFilterDateTwo()
    {
        $this->resetPage();
    }
}
/**
 * trigger en la base de datos 
 * para ver cuantas corridas si fueron actualizadas 
 */
