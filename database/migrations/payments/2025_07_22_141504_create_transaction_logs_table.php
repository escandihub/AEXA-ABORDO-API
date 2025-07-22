<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaction_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade');
            $table->enum('status', ['failed', 'success', 'pending', 'cancelled'])->default('failed');
            $table->text('error_message')->nullable();
            $table->json('error_details')->nullable(); // Para detalles adicionales del error
            $table->string('gateway_response_code')->nullable(); // Código de respuesta del gateway openpay
            $table->decimal('attempted_amount', 10, 2)->nullable(); // Monto que se intentó procesar
            $table->timestamps();
            
            // Índices para optimizar consultas
            $table->index(['transaction_id', 'status']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_logs');
    }
};
