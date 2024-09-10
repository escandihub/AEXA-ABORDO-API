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
        Schema::create('traking_device', function (Blueprint $table) {
            $table->id();
            $table->unsignedBiginteger('traking_app_id');
            $table->unsignedBiginteger('device_traking_id');

            $table->foreign('traking_app_id')->references('id')->on('traking_apps')->onDelete('cascade');
            $table->foreign('device_traking_id')->references('id')->on('devices_trakings')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('traking_device');
    }
};
