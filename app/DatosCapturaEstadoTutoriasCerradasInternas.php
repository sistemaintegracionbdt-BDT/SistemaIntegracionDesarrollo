<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DatosCapturaEstadoTutoriasCerradasInternas extends Model
{
    public $timestamps = false;
    protected $table = 'datos_captura_estado_tutorias_cerradas_internas';
    protected $primaryKey = 'id';
    protected $fillable = [
            'INTERNET_INFINITUM_PERSONAL_INTERNO',
            'INTERNET_VOZ_PERSONAL_INTERNO',
            'INTERNET_ENLACE_PERSONAL_EXTERNO',
            'INTERNET_VOZ_PERSONAL_EXTERNO',
            'MOBILIARIO_MESAS',
            'MOBILIARIO_SILLAS',
            'MOBILIARIO_LIBREROS',
            'MOBILIARIO_TV',
            'MOBILIARIO_ARCHIVEROS',
            'MOBILIARIO_RACKS',
            'MOBILIARIO_CARRITO_CARGADOR',
            'USUARIOS_ACUMULADO',
            'NUMERO_USUARIOS_DEL_ANIO_BDTS',
            'NUMERO_USUARIOS_DEL_MES_BDTS',
            'NUMERO_USUARIOS_DEL_ANIO_FILIALES',
            'NUMERO_USUARIOS_DEL_MES_FILIALES',
            'GASTO_MENSUAL_ACUMULADO',
            'GASTO_MENSUAL',
            'GASTO_MENSUAL_RENTA',
            'GASTO_MENSUAL_ASEO',
            'GASTO_MENSUAL_LUZ',
            'GASTO_MENSUAL_VIGILANCIA',
            'GASTO_AGUA_POTABLE',
            'GASTO_NOMINA_OPERACION',
            'GASTO_NOMINA_GERENCIA',
            'GASTO_MANTENIMIENTOS_TOTAL',
            'GASTO_MANTENIMIENTOS_EJERCIDO',
            'SOLICITUDES',
    ];
}
