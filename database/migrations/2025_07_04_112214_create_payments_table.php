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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('openpay_id')->unique(); // Columna para el 'id' del JSON
            $table->foreignId('customer_id')->constrained()->onDelete('cascade'); // Clave foránea a la tabla customers
            $table->decimal('amount', 10, 2);
            $table->text('description')->nullable();
            $table->string('order_id')->unique();
            $table->string('currency', 3);
            $table->decimal('iva', 10, 2)->default(0.00);
            $table->string('status');
            $table->string('checkout_link')->nullable();
            $table->timestamp('creation_date');
            $table->timestamp('expiration_date')->nullable();
            $table->timestamps();
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
