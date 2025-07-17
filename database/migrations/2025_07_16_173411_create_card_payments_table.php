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
        Schema::create('card_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade');
            $table->string('type')->default('redirect'); // redirect, card
            $table->string('url'); // URL para captura de tarjeta
            $table->string('brand')->nullable(); // visa, mastercard, etc.
            $table->string('card_number')->nullable(); // últimos 4 dígitos
            $table->string('holder_name')->nullable();
            $table->string('expiration_month')->nullable();
            $table->string('expiration_year')->nullable();
            $table->string('authorization')->nullable();
            $table->timestamps();
            
            $table->index('transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_payments');
    }
};
