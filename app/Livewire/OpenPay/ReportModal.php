<?php

namespace App\Livewire\OpenPay;

use Livewire\Component;
use App\Livewire\OpenPay\service\ExportSales;
use Livewire\Attributes\On;
use App\Exports\SuccessPaymentSheet;

class ReportModal extends Component
{
    public $start;
    public $end;
    public $show = false;
    public $loading = false;

    private $exportSalesService;

    public function render()
    {

        return view('livewire.open-pay.report-modal');
    }

    public function boot(ExportSales $exportSalesService)
    {
        $this->exportSalesService = $exportSalesService;
    }

    #[On('new-date')]
    public function dateRangeSelected($start, $end)
    {

        $this->exportSalesService->findbyDate(['start' => $start, 'end' => $end]);
        return $this->exportSalesService->export();
        \Log::info("Fechas seleccionadas: {$start}");
    }
}
