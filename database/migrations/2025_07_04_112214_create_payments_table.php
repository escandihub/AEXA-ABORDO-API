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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('openpay_id')->unique(); // Columna para el 'id' del JSON
            $table->foreignId('customer_id')->constrained()->onDelete('cascade'); // Clave foránea a la tabla customers
            $table->decimal('amount', 10, 2);
            $table->string('authorization')->nullable();
            $table->string('method');
            $table->string('operation_type');
            $table->text('description')->nullable();
            $table->string('order_id')->unique();
            $table->text('error_message')->nullable();
            $table->string('currency', 3);
            $table->decimal('iva', 10, 2)->default(0.00);
            $table->string('status'); // completed, failed, cancelled,
            $table->string('checkout_link')->nullable();
            $table->timestamp('creation_date');
            $table->timestamp('expiration_date')->nullable();
            $table->timestamp('processed_at')->nullable(); // fecha que se proceso
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
