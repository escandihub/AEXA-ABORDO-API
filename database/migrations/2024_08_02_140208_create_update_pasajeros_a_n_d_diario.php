<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Schema::create('update_pasajeros_a_n_d_diario', function (Blueprint $table) {
        //     $table->id();
        //     $table->timestamps();
        // });
        DB::unprepared('DROP PROCEDURE IF EXISTS updatePasajero;'); 

        DB::unprepared("
                CREATE procedure updatePasajero (in pasajero int, in corrida int)
                BEGIN 
                declare _abordaron int default 0;
                update pasajeros set abordo = 1 where id_pasajero = pasajero;
                select abordaron INTO _abordaron from  diario_c  where id_diario_c = corrida;
                update diario_c set abordaron = _abordaron + 1 where id_diario_c = corrida;
                END;"); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS updatePasajero;'); 
    }
};
