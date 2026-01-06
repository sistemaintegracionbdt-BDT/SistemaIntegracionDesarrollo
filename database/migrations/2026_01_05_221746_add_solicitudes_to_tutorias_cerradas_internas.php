<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSolicitudesToTutoriasCerradasInternas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('datos_captura_estado_tutorias_cerradas_internas', function (Blueprint $table) {
            $table->string('SOLICITUDES', 255);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('datos_captura_estado_tutorias_cerradas_internas', function (Blueprint $table) {
            $table->dropColumn('SOLICITUDES');
        });
    }
}
