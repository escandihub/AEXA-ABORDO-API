<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Url;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On; 

class Monitoreo extends Component
{
    /// fecha de inicio y fin del reporte
    #[Url] 
    public $start = '';
    #[Url] 
    public $end = '';
    public $abordo = [];

    public function render()
    {
        // $abordo = $this->queryAbordo();
        return view('livewire.monitoreo', [
            'abordo' =>  []
        ]);
    }

    public function mount() {
        $this->abordo = [];
        \Log::info($this->abordo);
    }

   public function queryAbordo($start, $end){
    $inicio = $start;
    $fin = $end;
    
    $abordo = DB::table('pasajeros')
    ->selectRaw("pasajeros.fecha_salida, pasajeros.id_diario_c, pasajeros.origen, count(pasajeros.origen) cantidad, 'ABORODO' as Abordo, pasajeros.clase, pasajeros.hora, pasajeros.minutos")
    ->join('diario_c', 'diario_c.id_diario_c', 'pasajeros.id_diario_c')
    ->where('pasajeros.abordo', 1)
    ->where('pasajeros.status', "V")->where('diario_c.condicion_corrida', "Disponible")
    ->whereRaw('pasajeros.fecha_salida between ? AND ?', [$inicio, $fin])
    ->groupByRaw("pasajeros.fecha_salida, pasajeros.origen, pasajeros.id_diario_c, pasajeros.clase, pasajeros.hora, pasajeros.minutos");

   $q  =  DB::table('pasajeros')
    ->selectRaw("pasajeros.fecha_salida, pasajeros.id_diario_c, pasajeros.origen, count(pasajeros.origen) cantidad, 'NO ABORODO' as Abordo, pasajeros.clase, pasajeros.hora, pasajeros.minutos")
    ->join('diario_c', 'diario_c.id_diario_c', 'pasajeros.id_diario_c')
    ->where('pasajeros.abordo', 0)
    ->where('pasajeros.status', "V")->where('diario_c.condicion_corrida', "Disponible")
    ->whereRaw('pasajeros.fecha_salida between ? AND ?', [$inicio, $fin])
    ->groupByRaw("pasajeros.fecha_salida, pasajeros.origen, pasajeros.id_diario_c, pasajeros.clase, pasajeros.hora, pasajeros.minutos")
    ->unionAll($abordo)
    ->get();
    return $q;
   }

   #[On('new-date')] 
    public function newDate($start, $end)
    {
        $result = $this->queryAbordo($start, $end);
        $this->dispatch('post-created', data: $result); 
        \Log::info($start);
    }
}
