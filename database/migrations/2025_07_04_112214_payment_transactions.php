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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->unique();
            $table->string('customer_id');
            $table->foreignId('order_id')->constrained('payments_buttons')->onDelete('cascade');
            $table->string('method'); // card, bank_transfer, store
            $table->string('status');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('MXN');
            $table->text('description')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('created_at_openpay');
            $table->timestamps();
            
            $table->index(['order_id', 'status']);
            $table->index(['method', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};