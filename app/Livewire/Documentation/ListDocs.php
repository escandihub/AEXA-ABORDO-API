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

    public ?bool $showDetails = false; 

    public $passangerDocs = NULL;

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
        return mDocument::whereDate('created_at', \Carbon\Carbon::yesterday())
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

    public function showMore(){
        $this->showDetails = !$this->showDetails;
    }
    public function getMaletas($pasajero_id){
        
        $this->passangerDocs = mDocument::where('pasajero_id', $pasajero_id)
        ->join('pasajeros', 'pasajeros.id_pasajero', 'documentations.pasajero_id')
        ->join('diario_c', 'pasajeros.id_diario_c', 'diario_c.id_diario_c')
        ->join('documentation_types', 'documentation_types.id', 'documentations.type_id')
        ->select("documentations.uuid", "documentations.status", "documentations.created_at","documentations.delivery_at",
        "pasajeros.nombre", "pasajeros.origen","pasajeros.numero_asiento","diario_c.autobus","diario_c.clase", "documentation_types.name")
        ->get();
        $this->showDetails = !$this->showDetails;
        \Log::info($this->passangerDocs);
        // return $maletas;
    }
}
