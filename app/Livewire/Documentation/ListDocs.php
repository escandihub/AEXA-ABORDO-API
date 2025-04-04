<?php

namespace App\Livewire\Documentation;

use Livewire\Component;

use Livewire\Attributes\Url;
use App\Models\Documentation AS mDocument;
use Illuminate\Support\Facades\DB;

use Livewire\WithPagination;

class ListDocs extends Component
{
    use WithPagination;
    #[Url] 
    public ?string $origen = "";
    #[Url] 
    public ?string $fecha = "";

    public function render()
    {
        $values = $this->getDocuments();
        \Log::info($values);
        return view('livewire.documentation.list-docs', [
            "documents" => $values,
            "terminales" => $this->Terminales(),
        ]);
    }

    private function query(){
       // return Documentation::orderBy('created_at', 'DESC')->groupBy("documentations.pasajero_id")->paginate(10);
        $now = \Carbon\CarbonImmutable::parse('2025-02-12'); //now();
        
        return DB::table('documentations')
        ->join('pasajeros', 'pasajeros.id_pasajero', 'documentations.pasajero_id')
        ->select("pasajeros.nombre",  "pasajeros.origen", "pasajeros.destino")
        ->whereBetween('created_at', [$now->subDay()->format('y-m-d'), $now->format('y-m-d')])
        ->groupBy("documentations.pasajero_id", "pasajeros.nombre",  "pasajeros.origen", "pasajeros.destino")->get(); //paginate(10);
    }

    private function getDocuments() {
        $now = \Carbon\CarbonImmutable::today();
        \Log::info($now);

        // return DB::table('documentations')->select(DB::raw('*'))
        // ->whereDate('created_at', \Carbon\Carbon::today() )
        // ->groupBy('pasajero_id')
        // ->get();
        \Log::info($this->origen);  
        return mDocument::whereDate('created_at', \Carbon\Carbon::today())
        ->join('pasajeros', 'pasajeros.id_pasajero', 'documentations.pasajero_id')
        ->select(DB::raw('documentations.id, documentations.pasajero_id, documentations.documenter_by,documentations.status, documentations.created_at, pasajeros.origen,
        pasajeros.destino, pasajeros.nombre, count(pasajero_id) AS nMaletas,  CONCAT(pasajeros.hora,":", pasajeros.minutos) AS horario'))
    //    ->where('pasajeros.origen', 'TGZ')
        ->when($this->origen != '', function($query){
            return $query->where('pasajeros.origen', $this->origen);
        })
        ->when($this->fecha != '', function($query){
            return $query->where('pasajeros.created_at', $this->fecha);
        })
        ->groupBy("pasajero_id")->paginate(10);
        //  "id","documenter_by","delivery_by", "uuid","type_id","status","number_document","comment","created_at", "updated_at","delivery_at"
    }
    /**
     * destino - origen => pasajero 
     * quien documento agregar y su terminal 
     * fecha de documentacion - ¿ya entrego?
     */
    private function Terminales(){
        return DB::table('terminales')->select('id_terminal','abreviacion')->groupBy('abreviacion')->get();
    }
}
