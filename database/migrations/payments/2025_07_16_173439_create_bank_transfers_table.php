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
        Schema::create('bank_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade');
            $table->string('type')->default('bank_transfer');
            $table->string('bank');
            $table->string('clabe');
            $table->string('agreement');
            $table->string('name');
            $table->string('url_spei');
            $table->string('reference')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index('transaction_id');
            $table->index('clabe');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_transfers');
    }
};
