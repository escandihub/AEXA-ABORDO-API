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
        DB::unprepared('DROP PROCEDURE IF EXISTS getColumn;'); 

        DB::unprepared("
                CREATE procedure getColumn (in corrida_id int,in terminal_user varchar(20), terminal varchar(50))
            BEGIN
             DECLARE v_terminal1  VARCHAR(20);
             DECLARE v_terminal2  VARCHAR(20);
             DECLARE v_terminal3  VARCHAR(20);
             DECLARE v_terminal4  VARCHAR(20);
             DECLARE v_terminal5  VARCHAR(20);
             DECLARE v_terminal6  VARCHAR(20);
             DECLARE v_terminal7  VARCHAR(20);
             DECLARE v_terminal8  VARCHAR(20);
             DECLARE v_terminal9  VARCHAR(20);
             DECLARE v_terminal10 VARCHAR(20);
             DECLARE v_terminal11 VARCHAR(20);
             DECLARE v_terminal12 VARCHAR(20);
             DECLARE v_terminal13 VARCHAR(20);
             DECLARE v_terminal14 VARCHAR(20);
             DECLARE v_terminal15 VARCHAR(20);
             DECLARE v_terminal16 VARCHAR(20);
             DECLARE v_terminal17 VARCHAR(20);
             DECLARE v_terminal18 VARCHAR(20);
             DECLARE v_terminal19 VARCHAR(20);
             DECLARE v_terminal20 VARCHAR(20);

            DECLARE done INT DEFAULT FALSE;
             DECLARE current_terminal varchar(20) DEFAULT terminal_user;

             DECLARE corrida CURSOR for SELECT terminal1,terminal2,terminal3,terminal4,terminal5,terminal6,terminal7,terminal8,terminal9,terminal10,terminal11,terminal12,terminal13,terminal14,terminal15,terminal16,terminal17,terminal18,terminal19,terminal20
             FROM diario_c_terminales where  id_diario_c = corrida_id;
             DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

             OPEN corrida;

             -- read_loop: LOOP
             FETCH corrida into v_terminal1,v_terminal2,v_terminal3,v_terminal4,v_terminal5,v_terminal6,v_terminal7,v_terminal8,v_terminal9,v_terminal10,v_terminal11,v_terminal12,v_terminal13,v_terminal14,v_terminal15,v_terminal16,v_terminal17,v_terminal18,v_terminal19,v_terminal20;
             -- IF done THEN 
                -- LEAVE read_loop;
            -- END IF;

            CASE 
            WHEN v_terminal1 = current_terminal THEN
                SET terminal = '1';
            WHEN v_terminal2 = current_terminal THEN
                SET terminal = '2';
            WHEN v_terminal3 = current_terminal THEN
                SET terminal = '3';
            WHEN v_terminal4 = current_terminal THEN
                SET terminal = '4';
            WHEN v_terminal5 = current_terminal THEN
                SET terminal = '5';
            WHEN v_terminal6 = current_terminal THEN
                SET terminal = '6';
            WHEN v_terminal7 = current_terminal THEN
                SET terminal = '7';
            WHEN v_terminal8 = current_terminal THEN
                SET terminal = '8';
            WHEN v_terminal9 = current_terminal THEN
                SET terminal = '9';
            WHEN v_terminal10 = current_terminal THEN
                SET terminal = '10';
            WHEN v_terminal11 = current_terminal THEN
                SET terminal = '11';
            WHEN v_terminal12 = current_terminal THEN
                SET terminal = '12';
            WHEN v_terminal13 = current_terminal THEN
                SET terminal = '13';
            WHEN v_terminal14 = current_terminal THEN
                SET terminal = '14';
            WHEN v_terminal15 = current_terminal THEN
                SET terminal = '15';
            WHEN v_terminal16 = current_terminal THEN
                SET terminal = '16';
            WHEN v_terminal17 = current_terminal THEN
                SET terminal = '17';
            WHEN v_terminal18 = current_terminal THEN
                SET terminal = '18';
            WHEN v_terminal19 = current_terminal THEN
                SET terminal = '19';
            WHEN v_terminal20 = current_terminal THEN
                SET terminal = '20';
            ELSE 
            	SET terminal = '0';
            END CASE;
             -- END LOOP;
             CLOSE corrida;
             if terminal = 0 then
            	select '0';
            elseif terminal != 0 THEN
            	SET @ter = CONCAT('terminal', terminal); -- terminal
            	SET @statusn = CONCAT('status_t', terminal);
            	SET @hora = CONCAT('hr', terminal);
            	SET @minuto = CONCAT('min', terminal);

            	SET @sql_query = CONCAT('SELECT ', @ter,' AS terminal, ',@statusn,' AS status, ', @hora,' AS hora, ',@minuto, ' AS minutos, fecha, id_diario_c  FROM diario_c_terminales where id_diario_c = ', corrida_id );
            	PREPARE dynamic_statement FROM @sql_query;
            	EXECUTE dynamic_statement;
            	DEALLOCATE PREPARE dynamic_statement;
            end if;
            END;"); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
