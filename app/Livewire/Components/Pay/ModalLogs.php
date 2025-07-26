<?php

namespace App\Livewire\Components\Pay;
use Livewire\Attributes\On; 
use Carbon\Carbon;

use Livewire\Component;

class ModalLogs extends Component
{
     public bool $show = false;

    // Propiedad pública para almacenar los logs de pago
    public array $transactions = [];

    /**
     * Define los listeners para eventos Livewire.
     * Cuando se emita 'openPaymentLogsModal', se llamará al método 'open'.
     */
    protected $listeners = ['openPaymentLogsModal' => 'open'];
    public bool $loading = false;

    /**
     * Abre el modal y carga los datos de los logs.
     * En una aplicación real, aquí cargarías los datos desde tu base de datos.
     */
    public function open(): void
    {
        $this->loading = true; // Establece el estado de carga a true al inicio
        $this->show = true;    // Muestra el contenedor principal del modal/overlay inmediatamente

        // Simula un retardo para la carga de datos (¡ELIMINA ESTO EN PRODUCCIÓN!)
        // En un entorno real, aquí harías una consulta a la base de datos
        // o llamarías a un servicio para obtener las transacciones.
        sleep(1); // Simula 1 segundo de carga para ver el spinner

        // Datos de ejemplo para demostración, siguiendo tu estructura proporcionada.
        $this->transactions = [
            [
                "id" => 38,
                "transaction_id" => "tr8yxmc3fgr8wx9jyz8e",
                "customer_id" => "aladxunwgxrv1ctpdtvm",
                "order_id" => "ORD-687feb8b3adda-1753213835",
                "method" => "card",
                "status" => "charge_pending",
                "amount" => "900.00",
                "currency" => "MXN",
                "description" => "MXA-COM",
                "metadata" => null,
                "created_at_openpay" => "2025-07-22T23:23:56.000000Z",
                "created_at" => "2025-07-22T23:24:02.000000Z",
                "updated_at" => "2025-07-22T23:24:02.000000Z",
                "logs" => [
                    [
                        "id" => 7,
                        "transaction_id" => 38,
                        "status" => "failed",
                        "error_message" => "Fraud risk detected by anti-fraud system",
                        "error_details" => null,
                        "gateway_response_code" => "3005",
                        "attempted_amount" => "900.00",
                        "created_at" => "2025-07-22T23:26:34.000000Z",
                        "updated_at" => "2025-07-22T23:26:34.000000Z"
                    ],
                    [
                        "id" => 8,
                        "transaction_id" => 38,
                        "status" => "attempted",
                        "error_message" => null,
                        "error_details" => null,
                        "gateway_response_code" => "1000",
                        "attempted_amount" => "900.00",
                        "created_at" => "2025-07-22T23:25:00.000000Z",
                        "updated_at" => "2025-07-22T23:25:00.000000Z"
                    ]
                ]
            ],
            // Agregamos otra transacción de ejemplo para ver el listado
            [
                "id" => 39,
                "transaction_id" => "tr9abc123def456ghi789",
                "customer_id" => "johndoe123xyz",
                "order_id" => "ORD-79cdef9c4effa-1753215000",
                "method" => "bank_transfer",
                "status" => "completed",
                "amount" => "1500.50",
                "currency" => "USD",
                "description" => "Online Course Fee",
                "metadata" => ["course_name" => "Advanced Laravel"],
                "created_at_openpay" => "2025-07-23T10:00:00.000000Z",
                "created_at" => "2025-07-23T10:01:00.000000Z",
                "updated_at" => "2025-07-23T10:01:00.000000Z",
                "logs" => [
                    [
                        "id" => 9,
                        "transaction_id" => 39,
                        "status" => "received",
                        "error_message" => null,
                        "error_details" => null,
                        "gateway_response_code" => "2000",
                        "attempted_amount" => "1500.50",
                        "created_at" => "2025-07-23T10:00:30.000000Z",
                        "updated_at" => "2025-07-23T10:00:30.000000Z"
                    ],
                    [
                        "id" => 10,
                        "transaction_id" => 39,
                        "status" => "completed",
                        "error_message" => null,
                        "error_details" => null,
                        "gateway_response_code" => "1000",
                        "attempted_amount" => "1500.50",
                        "created_at" => "2025-07-23T10:01:00.000000Z",
                        "updated_at" => "2025-07-23T10:01:00.000000Z"
                    ]
                ]
            ],
            // Otra transacción de ejemplo con pocos logs
            [
                "id" => 40,
                "transaction_id" => "trxyz789uvw0123opq456",
                "customer_id" => "marysue456",
                "order_id" => "ORD-8ab9cde01f22-1753216000",
                "method" => "spei",
                "status" => "pending",
                "amount" => "300.00",
                "currency" => "MXN",
                "description" => "Utility Bill",
                "metadata" => null,
                "created_at_openpay" => "2025-07-24T08:00:00.000000Z",
                "created_at" => "2025-07-24T08:01:00.000000Z",
                "updated_at" => "2025-07-24T08:01:00.000000Z",
                "logs" => [
                    [
                        "id" => 11,
                        "transaction_id" => 40,
                        "status" => "initiated",
                        "error_message" => null,
                        "error_details" => null,
                        "gateway_response_code" => "2000",
                        "attempted_amount" => "300.00",
                        "created_at" => "2025-07-24T08:00:30.000000Z",
                        "updated_at" => "2025-07-24T08:00:30.000000Z"
                    ]
                ]
            ],
        ];

        $this->loading = false; 
    }
     #[On('open-logs-modal')] 
    public function handleLogs(array $logs): void
    {
        // Aquí puedes procesar los logs recibidos, por ejemplo, almacenarlos en la base de datos
        // o simplemente asignarlos a la propiedad pública para mostrarlos en el modal.
        $this->transactions = $logs;
        // dd($this->transactions);
        $this->show = true;
        $this->dispatch('loading-show');
        // Opcional: cerrar el modal después de procesar los logs
        // $this->close();
        $this->dispatch('loading-none');
    }

    /**
     * Cierra el modal.
     */
    public function close(): void
    {
        $this->show = false;
        // Opcional: limpiar los logs si no quieres que se mantengan en el estado
        // $this->logs = [];
    }
    public function render()
    {
        return view('livewire.components.pay.modal-logs');
    }
}
