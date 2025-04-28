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
            Schema::create('device_printer', function (Blueprint $table) {
                $table->id();
                $table->foreignId('device_id')->constrained('devices')->onDelete('cascade');
                $table->foreignId('printer_id')->constrained('printers')->onDelete('cascade');
                $table->timestamp('date_vinculation')->useCurrent();
                $table->string('user_vinculation')->nullable(); // Opcional: para saber quién lo registró
                $table->boolean('is_default')->default(false);
                $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
