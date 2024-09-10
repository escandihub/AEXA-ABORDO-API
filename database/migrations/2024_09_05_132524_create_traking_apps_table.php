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
        Schema::create('traking_apps', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('versionCode');
            $table->string('versionName');
            $table->string('comentarios');
            $table->boolean('active');
            $table->boolean('in_process'); #por actulizar dispositivos
            $table->string('path_app');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('traking_apps');
    }
};
