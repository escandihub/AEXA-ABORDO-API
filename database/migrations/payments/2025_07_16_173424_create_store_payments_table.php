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
        Schema::create('store_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade');
            $table->string('type')->default('store');
            $table->string('reference');
            $table->string('barcode_url');
            $table->string('url_store');
            $table->string('store_name')->nullable(); // OXXO, 7-Eleven, etc.
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            
            $table->index('transaction_id');
            $table->index('reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_payments');
    }
};
