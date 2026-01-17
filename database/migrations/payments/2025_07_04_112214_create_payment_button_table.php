<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * php artisan migrate --database=openpay --path=\database\migrations\2025_07_04_112214_create_payments_table.php
     */
    public function up(): void
    {
        Schema::create('payments_buttons', function (Blueprint $table) {
            $table->id();
            $table->string('openpay_id')->unique(); // Columna para el 'id' del JSON
            $table->foreignId('customer_id')->constrained()->onDelete('cascade'); // Clave foránea a la tabla customers
            $table->string('order_id')->unique(); // ID unico para logica interna del negocio
            $table->foreingId('user_id')->constraided()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->text('description')->nullable();
            $table->string('currency', 3)->default('MXN'); // Moneda, por defecto 'MXN'
            $table->decimal('iva', 10, 2)->default(0.00);
            $table->string('status'); // completed, failed, cancelled,
            $table->string('checkout_link')->nullable();
            $table->timestamp('creation_date');
            $table->timestamp('expiration_date')->nullable();

            $table->json('raw_response')->nullable(); // Para guardar la respuesta completa
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();

            $table->index(['openpay_id']);
            $table->index(['status']);
            $table->index(['order_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
