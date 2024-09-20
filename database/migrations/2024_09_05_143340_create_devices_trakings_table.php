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
        Schema::create('devices_trakings', function (Blueprint $table) {
            $table->id();
            $table->string('versionCode');
            $table->string('versionName');
            $table->boolean('updated')->nullable();
            $table->foreignId('device_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices_trakings');
    }
};
