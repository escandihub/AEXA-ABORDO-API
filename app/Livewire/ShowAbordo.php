<?php

namespace App\Livewire;

use Livewire\Component;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Builder;
use Illuminate\Support\Facades\Log;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use Livewire\Attributes\Url;

class ShowAbordo extends Component
{
    use WithPagination, WithoutUrlPagination;

    #[Url]
    public ?string $search = '';
    #[Url]
    public ?string $dateSelect = '';

    // Nueva variable para la fecha seleccionada desde la vista

    public function updatedSelectedDate($value)
    {
        \Log::info('Selected date updated: ' . $value);
        $this->dateSelect = $value;
        $this->resetPage();
    }

    public function render()
    {
        // $now = \Carbon\CarbonImmutable::now();
        // // Si selectedDate tiene valor, úsalo como fecha actual
        // $fechaActual = $this->selectedDate ?: $now->format('Y-m-d');
        // $this->date = $fechaActual;

        return view('livewire.show-abordo', [
            'pasajeros' => $this->filters(),
            // 'fecha' => $fechaActual,
            // 'hora' => $now->format('H:i:s'),
        ]);
    }

    public function query()
    {
        $now = \Carbon\CarbonImmutable::now();
        return  DB::table('pasajeros')
            ->select('id_pasajero', 'nombre', 'fecha_salida', 'abordo', 'numero_terminal', 'terminal', 'origen', 'destino', 'tipo_descuento', 'clase', 'id_diario_c')
            // ->where('fecha_salida', '>=', $now->format('Y-m-d'))
            ->orderBy('fecha_salida');
    }

    public function filters() {

        \Log::info('Date: ' . $this->dateSelect);
        return $this->query()
            ->when($this->search && !is_numeric($this->search), function ($query) {
                \Log::info('Searching by name: ' . $this->search);
                $query->where('nombre', 'like', '%' . $this->search . '%');
            })
            ->when($this->search, function ($query) {
                 \Log::info('Searching by code: ' . $this->search);
                $query->where('consecutivo_terminal', $this->search);
            })
            ->when($this->dateSelect != '', function ($query) {
                $query->whereDate('fecha_salida', $this->dateSelect);
            })
            ->paginate(10);

    }
}
