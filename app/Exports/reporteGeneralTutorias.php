<?php

namespace App\Exports;

use DB;
use App\adts;
use App\lineas;
use App\infraestructuras;
use App\usos;
use App\equipamientos;
use App\mobiliarios;
use App\UsuariosEstadoTutoriasAbiertasInternas;
use App\DatosCapturaEstadoTutoriasAbiertas;
use App\DatosCapturaEstadoTutoriasAbiertasInternas;
use App\DatosCapturaEstadoTutoriasCerradasInternas;

use Carbon\Carbon;

use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Http\Request;

class reporteGeneralTutorias {
    
    public function __construct() {

    }

    public function generarReporteGeneralActual(){

        return Excel::create('Reporte General Actual de Tutorías', function($excel) {

            $excel->sheet('Resumen', function($sheet) {

                //Abiertas
                $adtsAbiertas = adts::with(['lineas'])
                ->whereIn('ESTATUS_ACTUAL', ['ABIERTA', 'ABIERTA INTERNA']);
                $adtsAbiertasExternas = (clone $adtsAbiertas)->whereIn('INICIATIVA', ['ADT', 'BDT MPAL']);
                $adtsAbiertasInternas = (clone $adtsAbiertas)->where('INICIATIVA', 'CASA TELMEX');
                $adtsAbiertasInternasObtenidas = $adtsAbiertasInternas->get();
                foreach ($adtsAbiertasInternasObtenidas as $adt) {
                    UsuariosEstadoTutoriasAbiertasInternas::firstOrCreate(
                        ['NOMBRE' => $adt->NOMBRE],
                        [
                            'META' => $adt->META ?? 0,
                            'REAL' => $adt->REAL ?? 0,
                        ]
                    );
                }
                $adtsCerradasInternas = adts::with(['lineas'])
                ->where('ESTATUS_ACTUAL', 'CERRADA INTERNAS');

                $adtsConLineas = (clone $adtsAbiertas)
                ->whereHas('lineas', function($colaDeConsulta) {
                    $colaDeConsulta->where('LINEA', '<>', 'BAJA');
                });
                $lineasDeAdts = lineas::with(['adts'])
                ->whereHas('adts', function ($colaDeConsulta) {
                    $colaDeConsulta->whereIn('ESTATUS_ACTUAL', ['ABIERTA', 'ABIERTA INTERNA']);
                });
                $consumoInternetLineasAbiertas = (clone $lineasDeAdts)
                ->avg('GB_RECIBIDO');
                $consumoInternetLineasMayorAbiertas = (clone $lineasDeAdts)
                ->where('GB_RECIBIDO', '>', 110);
                $adtsConLineasPagaEntidad = (clone $adtsConLineas)
                ->whereHas('lineas', function($colaDeConsulta) {
                    $colaDeConsulta->where('PAGA', 'INSTITUCIÓN / GOBIERNO');
                });
                $lineasDeAdtsPagaEntidad = (clone $lineasDeAdts)
                ->where('PAGA', 'INSTITUCIÓN / GOBIERNO');
                $lineasDeAdtsCobreQuePagaEntidad = (clone $lineasDeAdtsPagaEntidad)
                ->where('TECNOLOGIA', 'IPDSLAM');
                $adtsConLineasPagaTelmex = (clone $adtsConLineas)
                ->whereHas('lineas', function($colaDeConsulta) {
                    $colaDeConsulta->whereIn('PAGA', ['TELMEX CT', 'TELMEX BDT EXTERNAS', 'FUNDACION CARLOS SLIM']);
                });
                $lineasDeAdtsPagaTelmex = (clone $lineasDeAdts)
                ->whereIn('PAGA', ['TELMEX CT', 'TELMEX BDT EXTERNAS', 'FUNDACION CARLOS SLIM']);
                $lineasDeAdtsEnlaceQuePagaTelmex = (clone $lineasDeAdtsPagaTelmex)
                ->where('TECNOLOGIA', 'ENLACE');
                $costoLineasPagaEntidad = (clone $lineasDeAdtsPagaEntidad)
                ->sum('COSTO');
                $costoLineasPagaTelmex = (clone $lineasDeAdtsPagaTelmex)
                ->sum('COSTO');
                $lineasConsumoSinConsumo = (clone $lineasDeAdts)
                ->whereIn('SEMAFORO', ['-', 'NULO', 'NULL'])->orWhereNull('SEMAFORO');
                $lineasConsumoBajo = (clone $lineasDeAdts)
                ->where('SEMAFORO', 'BAJO');
                $lineasConsumoMedio = (clone $lineasDeAdts)
                ->where('SEMAFORO', 'MEDIO');
                $lineasConsumoAlto = (clone $lineasDeAdts)
                ->where('SEMAFORO', 'ALTO');
                $lineasConsumoHeavy = (clone $lineasDeAdts)
                ->where('SEMAFORO', 'HEAVY');
                $lineasConsumoAtipico = (clone $lineasDeAdts)
                ->where('SEMAFORO', 'ATIPICO');

                $equipamientoAdts = equipamientos::with('adts');
                $totalEquipamientoAdts = 
                equipamientos::where('TIPO', 'INICIAL')->sum('PC') +
                equipamientos::where('TIPO', 'INICIAL')->sum('LAPTOP') +
                equipamientos::where('TIPO', 'INICIAL')->sum('CLASSMATE') +
                equipamientos::where('TIPO', 'INICIAL')->sum('XO');
                $equipamientoInicialAdts = equipamientos::whereHas('adts', function ($colaDeConsulta) {
                    $colaDeConsulta->whereIn('ESTATUS_ACTUAL', ['ABIERTA', 'ABIERTA INTERNA']);
                })
                ->where('TIPO', 'INICIAL')
                ->get();
                $totalEquipamientoInicialAdts =
                $equipamientoInicialAdts->sum('PC') +
                $equipamientoInicialAdts->sum('LAPTOP') +
                $equipamientoInicialAdts->sum('CLASSMATE') +
                $equipamientoInicialAdts->sum('XO');
                $equipamientoFuncionalAdts = equipamientos::whereHas('adts', function($colaDeConsulta) {
                    $colaDeConsulta->whereIn('ESTATUS_ACTUAL', ['ABIERTA', 'ABIERTA INTERNA']);
                })
                ->where('TIPO', 'FUNCIONAL')
                ->get();
                $totalEquipamientoFuncionalAdts = 
                $equipamientoFuncionalAdts->sum('PC') +
                $equipamientoFuncionalAdts->sum('LAPTOP') +
                $equipamientoFuncionalAdts->sum('CLASSMATE') +
                $equipamientoFuncionalAdts->sum('XO');
                $relacionPorcentualEquipamientoFuncionalEntreInicial = 
                ($totalEquipamientoFuncionalAdts * 100) / ($totalEquipamientoInicialAdts);
                
                $mobiliarioAdts = mobiliarios::with('adts');
                $totalMobiliarioAdts =
                mobiliarios::where('TIPO', 'INICIAL')->sum('MESA_RECTANGULAR_GRANDE') +
                mobiliarios::where('TIPO', 'INICIAL')->sum('MESA_RECTANGULAR_MEDIANA') +
                mobiliarios::where('TIPO', 'INICIAL')->sum('MESA_CIRCULAR') +
                mobiliarios::where('TIPO', 'INICIAL')->sum('SILLAS') +
                mobiliarios::where('TIPO', 'INICIAL')->sum('MUEBLE_RESGUARDO');
                $mobiliarioInicialAdts = mobiliarios::whereHas('adts', function($colaDeConsulta) {
                    $colaDeConsulta->whereIn('ESTATUS_ACTUAL', ['ABIERTA', 'ABIERTA INTERNA']);
                })
                ->where('TIPO', 'INICIAL')
                ->get();
                $totalMobiliarioInicialAdts =
                $mobiliarioInicialAdts->sum('MESA_RECTANGULAR_GRANDE') +        
                $mobiliarioInicialAdts->sum('MESA_RECTANGULAR_MEDIANA') +        
                $mobiliarioInicialAdts->sum('MESA_CIRCULAR') +   
                $mobiliarioInicialAdts->sum('SILLAS') +    
                $mobiliarioInicialAdts->sum('MUEBLE_RESGUARDO');
                $mobiliarioFuncionalAdts = mobiliarios::whereHas('adts', function($colaDeConsulta) {
                    $colaDeConsulta->whereIn('ESTATUS_ACTUAL', ['ABIERTA', 'ABIERTA INTERNA']);
                })
                ->where('TIPO', 'FUNCIONAL')
                ->get();
                $totalMobiliarioFuncionalAdts =
                $mobiliarioFuncionalAdts->sum('MESA_RECTANGULAR_GRANDE') +        
                $mobiliarioFuncionalAdts->sum('MESA_RECTANGULAR_MEDIANA') +        
                $mobiliarioFuncionalAdts->sum('MESA_CIRCULAR') +    
                $mobiliarioFuncionalAdts->sum('SILLAS') +        
                $mobiliarioFuncionalAdts->sum('MUEBLE_RESGUARDO');
                $conveniosIndeterminadosAdts = 
                (clone $adtsAbiertas)->whereNull('FECHA_TERMINO_CONVENIO');
                $conveniosVencidosAdts = 
                (clone $adtsAbiertas)->whereNotNull('FECHA_TERMINO_CONVENIO')
                ->where('FECHA_TERMINO_CONVENIO', '<', Carbon::now()->toDateString());
                $conveniosVigentesAdts = 
                (clone $adtsAbiertas)->whereNotNull('FECHA_TERMINO_CONVENIO')
                ->where('FECHA_TERMINO_CONVENIO', '>=', Carbon::now()->toDateString());


                //Abiertas Internas
                $adtsInternasPropias = (clone $adtsAbiertasInternas)->where('ENTORNO', 'PROPIA');
                $numeroAdtsInternasPropias = $adtsInternasPropias->count();
                $adtsInternasExternas = (clone $adtsAbiertasInternas)->where('ENTORNO', 'EXTERNA');
                $numeroAdtsInternasExternas = $adtsInternasExternas->count();

                $adtsInternasExternasConLineas = (clone $adtsInternasExternas)
                ->whereHas('lineas', function($colaDeConsulta) {
                    $colaDeConsulta->where('LINEA', '<>', 'BAJA')
                    ->whereIn('PAGA', ['TELMEX CT', 'TELMEX BDT EXTERNAS', 'FUNDACION CARLOS SLIM']);
                });
                $adtsInternasPropiasConLineas = (clone $adtsInternasPropias)
                ->whereHas('lineas', function($colaDeConsulta) {
                    $colaDeConsulta->where('LINEA', '<>', 'BAJA')
                    ->whereIn('PAGA', ['TELMEX CT', 'TELMEX BDT EXTERNAS', 'FUNDACION CARLOS SLIM']);
                });
                $lineasDeAdtsInternas = lineas::with(['adts'])
                ->whereHas('adts', function ($colaDeConsulta) {
                    $colaDeConsulta->where('ESTATUS_ACTUAL', 'ABIERTA INTERNA')
                    ->where('INICIATIVA', 'CASA TELMEX');
                });
                $consumoInternetLineasAdtsInternas = (clone $lineasDeAdtsInternas)
                ->avg('GB_RECIBIDO');
                $consumoInternetLineasMayorAdtsInternas = (clone $lineasDeAdtsInternas)
                ->where('GB_RECIBIDO', '>', 110);
                $lineasDeAdtsInternasExternas = (clone $lineasDeAdtsInternas)
                ->whereHas('adts', function ($colaDeConsulta) {
                    $colaDeConsulta->where('ENTORNO', 'EXTERNA');
                });
                $lineasDeAdtsInternasPropias = (clone $lineasDeAdtsInternas)
                ->whereHas('adts', function ($colaDeConsulta) {
                    $colaDeConsulta->where('ENTORNO', 'PROPIA');
                });
                $costoLineasAdtsInternasExternasPagaTelmex = (clone $lineasDeAdtsInternasExternas)
                ->whereIn('PAGA', ['TELMEX CT', 'TELMEX BDT EXTERNAS', 'FUNDACION CARLOS SLIM'])
                ->sum('COSTO');
                $costoLineasAdtsInternasPropiasPagaTelmex = (clone $lineasDeAdtsInternasPropias)
                ->whereIn('PAGA', ['TELMEX CT', 'TELMEX BDT EXTERNAS', 'FUNDACION CARLOS SLIM'])
                ->sum('COSTO');
                $lineasAdtsInternasConsumoSinConsumo = (clone $lineasDeAdtsInternas)
                ->whereIn('SEMAFORO', ['-', 'NULO', 'NULL'])->orWhereNull('SEMAFORO');
                $lineasAdtsInternasConsumoBajo = (clone $lineasDeAdtsInternas)
                ->where('SEMAFORO', 'BAJO');
                $lineasAdtsInternasConsumoMedio = (clone $lineasDeAdtsInternas)
                ->where('SEMAFORO', 'MEDIO');
                $lineasAdtsInternasConsumoAlto = (clone $lineasDeAdtsInternas)
                ->where('SEMAFORO', 'ALTO');
                $lineasAdtsInternasConsumoHeavy = (clone $lineasDeAdtsInternas)
                ->where('SEMAFORO', 'HEAVY');
                $lineasAdtsInternasConsumoAtipico = (clone $lineasDeAdtsInternas)
                ->where('SEMAFORO', 'ATIPICO');

                $equipamientoAdtsInternas = equipamientos::with('adts')
                ->whereHas('adts', function($colaDeConsulta) {
                    $colaDeConsulta->where('ESTATUS_ACTUAL', 'ABIERTA INTERNA')
                    ->where('INICIATIVA', 'CASA TELMEX');
                });
                $totalEquipamientoAdtsInternas = 
                (clone $equipamientoAdtsInternas)->where('TIPO', 'INICIAL')->sum('PC') +
                (clone $equipamientoAdtsInternas)->where('TIPO', 'INICIAL')->sum('CLASSMATE') +
                (clone $equipamientoAdtsInternas)->where('TIPO', 'INICIAL')->sum('LAPTOP') +
                (clone $equipamientoAdtsInternas)->where('TIPO', 'INICIAL')->sum('XO');
                $equipamientoInicialAdtsInternas = (clone $equipamientoAdtsInternas)
                ->where('TIPO', 'INICIAL')
                ->get();
                $equipamientoFuncionalAdtsInternas = (clone $equipamientoAdtsInternas)
                ->where('TIPO', 'FUNCIONAL')
                ->get();
                $totalEquipamientoFuncionalAdtsInternas = 
                $equipamientoFuncionalAdtsInternas->sum('PC') +
                $equipamientoFuncionalAdtsInternas->sum('LAPTOP') +
                $equipamientoFuncionalAdtsInternas->sum('CLASSMATE') +
                $equipamientoFuncionalAdtsInternas->sum('XO');
                $totalEquipamientoInicialAdtsInternas =
                $equipamientoInicialAdtsInternas->sum('PC') +
                $equipamientoInicialAdtsInternas->sum('LAPTOP') +
                $equipamientoInicialAdtsInternas->sum('CLASSMATE') +
                $equipamientoInicialAdtsInternas->sum('XO');
                $totalEquipamientoBDOFAdtsInternas =
                ($equipamientoInicialAdtsInternas->sum('PC') 
                - $equipamientoFuncionalAdtsInternas->sum('PC')) +
                ($equipamientoInicialAdtsInternas->sum('LAPTOP') 
                - $equipamientoFuncionalAdtsInternas->sum('LAPTOP')) +
                ($equipamientoInicialAdtsInternas->sum('CLASSMATE') 
                - $equipamientoFuncionalAdtsInternas->sum('CLASSMATE')) +
                ($equipamientoInicialAdtsInternas->sum('XO')
                - $equipamientoFuncionalAdtsInternas->sum('XO'));
                $relacionPorcentualEquipamientoFuncionalEntreInicialAdtsInternas = 
                ($totalEquipamientoFuncionalAdtsInternas * 100) / ($totalEquipamientoInicialAdtsInternas);

                $numeroUsuariosMetaRealAdts = UsuariosEstadoTutoriasAbiertasInternas::all();

                $conveniosIndeterminadosAdtsInternas = 
                (clone $adtsAbiertasInternas)->whereNull('FECHA_TERMINO_CONVENIO');
                $conveniosVencidosAdtsInternas = 
                (clone $adtsAbiertasInternas)->whereNotNull('FECHA_TERMINO_CONVENIO')
                ->where('FECHA_TERMINO_CONVENIO', '<', Carbon::now()->toDateString());
                $conveniosVigentesAdtsInternas = 
                (clone $adtsAbiertasInternas)->whereNotNull('FECHA_TERMINO_CONVENIO')
                ->where('FECHA_TERMINO_CONVENIO', '>=', Carbon::now()->toDateString());

                //Cerradas Internas
                $adtsInternasPropiasCerradas = (clone $adtsCerradasInternas)->where('ENTORNO', 'PROPIA');
                $adtsInternasExternasCerradas = (clone $adtsCerradasInternas)->where('ENTORNO', 'EXTERNA');

                $adtsInternasExternasCerradasConLineas = (clone $adtsInternasExternasCerradas)
                ->whereHas('lineas', function($colaDeConsulta) {
                    $colaDeConsulta->where('LINEA', '<>', 'BAJA')
                    ->whereIn('PAGA', ['TELMEX CT', 'TELMEX BDT EXTERNAS', 'FUNDACION CARLOS SLIM']);
                });
                $adtsInternasPropiasCerradasConLineas = (clone $adtsInternasPropiasCerradas)
                ->whereHas('lineas', function($colaDeConsulta) {
                    $colaDeConsulta->where('LINEA', '<>', 'BAJA')
                    ->whereIn('PAGA', ['TELMEX CT', 'TELMEX BDT EXTERNAS', 'FUNDACION CARLOS SLIM']);
                });
                $lineasDeAdtsInternasCerradas = lineas::with(['adts'])
                ->whereHas('adts', function ($colaDeConsulta) {
                    $colaDeConsulta->where('ESTATUS_ACTUAL', 'CERRADA INTERNAS')
                    ->where('INICIATIVA', 'CASA TELMEX');
                });
                $consumoInternetLineasAdtsInternasCerradas = (clone $lineasDeAdtsInternasCerradas)
                ->avg('GB_RECIBIDO');
                $consumoInternetLineasMayorAdtsInternasCerradas = (clone $lineasDeAdtsInternasCerradas)
                ->where('GB_RECIBIDO', '>', 110);
                $lineasDeAdtsInternasExternasCerradas = (clone $lineasDeAdtsInternasCerradas)
                ->whereHas('adts', function ($colaDeConsulta) {
                    $colaDeConsulta->where('ENTORNO', 'EXTERNA');
                });
                $lineasDeAdtsInternasPropiasCerradas = (clone $lineasDeAdtsInternasCerradas)
                ->whereHas('adts', function ($colaDeConsulta) {
                    $colaDeConsulta->where('ENTORNO', 'PROPIA');
                });
                $costoLineasAdtsInternasExternasCerradasPagaTelmex = (clone $lineasDeAdtsInternasExternasCerradas)
                ->whereIn('PAGA', ['TELMEX CT', 'TELMEX BDT EXTERNAS', 'FUNDACION CARLOS SLIM'])
                ->sum('COSTO');
                $costoLineasAdtsInternasPropiasCerradasPagaTelmex = (clone $lineasDeAdtsInternasPropiasCerradas)
                ->whereIn('PAGA', ['TELMEX CT', 'TELMEX BDT EXTERNAS', 'FUNDACION CARLOS SLIM'])
                ->sum('COSTO');
                $lineasAdtsInternasCerradasConsumoSinConsumo = (clone $lineasDeAdtsInternasCerradas)
                ->whereIn('SEMAFORO', ['-', 'NULO', 'NULL'])->orWhereNull('SEMAFORO');
                $lineasAdtsInternasCerradasConsumoBajo = (clone $lineasDeAdtsInternasCerradas)
                ->where('SEMAFORO', 'BAJO');
                $lineasAdtsInternasCerradasConsumoMedio = (clone $lineasDeAdtsInternasCerradas)
                ->where('SEMAFORO', 'MEDIO');
                $lineasAdtsInternasCerradasConsumoAlto = (clone $lineasDeAdtsInternasCerradas)
                ->where('SEMAFORO', 'ALTO');
                $lineasAdtsInternasCerradasConsumoHeavy = (clone $lineasDeAdtsInternasCerradas)
                ->where('SEMAFORO', 'HEAVY');
                $lineasAdtsInternasCerradasConsumoAtipico = (clone $lineasDeAdtsInternasCerradas)
                ->where('SEMAFORO', 'ATIPICO');

                $equipamientoAdtsInternasCerradas = equipamientos::with('adts')
                ->whereHas('adts', function($colaDeConsulta) {
                    $colaDeConsulta->where('ESTATUS_ACTUAL', 'CERRADA INTERNAS')
                    ->where('INICIATIVA', 'CASA TELMEX');
                });
                $totalEquipamientoAdtsInternasCerradas = 
                (clone $equipamientoAdtsInternasCerradas)->where('TIPO', 'INICIAL')->sum('PC') +
                (clone $equipamientoAdtsInternasCerradas)->where('TIPO', 'INICIAL')->sum('CLASSMATE') +
                (clone $equipamientoAdtsInternasCerradas)->where('TIPO', 'INICIAL')->sum('LAPTOP') +
                (clone $equipamientoAdtsInternasCerradas)->where('TIPO', 'INICIAL')->sum('XO');
                $equipamientoInicialAdtsInternasCerradas = (clone $equipamientoAdtsInternasCerradas)
                ->where('TIPO', 'INICIAL')
                ->get();
                $equipamientoFuncionalAdtsInternasCerradas = (clone $equipamientoAdtsInternasCerradas)
                ->where('TIPO', 'FUNCIONAL')
                ->get();
                $totalEquipamientoFuncionalAdtsInternasCerradas = 
                $equipamientoFuncionalAdtsInternasCerradas->sum('PC') +
                $equipamientoFuncionalAdtsInternasCerradas->sum('LAPTOP') +
                $equipamientoFuncionalAdtsInternasCerradas->sum('CLASSMATE') +
                $equipamientoFuncionalAdtsInternasCerradas->sum('XO');
                $totalEquipamientoInicialAdtsInternasCerradas =
                $equipamientoInicialAdtsInternasCerradas->sum('PC') +
                $equipamientoInicialAdtsInternasCerradas->sum('LAPTOP') +
                $equipamientoInicialAdtsInternasCerradas->sum('CLASSMATE') +
                $equipamientoInicialAdtsInternasCerradas->sum('XO');
                $totalEquipamientoBDOFAdtsInternasCerradas =
                ($equipamientoInicialAdtsInternasCerradas->sum('PC') 
                - $equipamientoFuncionalAdtsInternasCerradas->sum('PC')) +
                ($equipamientoInicialAdtsInternasCerradas->sum('LAPTOP') 
                - $equipamientoFuncionalAdtsInternasCerradas->sum('LAPTOP')) +
                ($equipamientoInicialAdtsInternasCerradas->sum('CLASSMATE') 
                - $equipamientoFuncionalAdtsInternasCerradas->sum('CLASSMATE')) +
                ($equipamientoInicialAdtsInternasCerradas->sum('XO')
                - $equipamientoFuncionalAdtsInternasCerradas->sum('XO'));

                $conveniosIndeterminadosAdtsInternasCerradas = 
                (clone $adtsCerradasInternas)->whereNull('FECHA_TERMINO_CONVENIO');
                $conveniosVencidosAdtsInternasCerradas = 
                (clone $adtsCerradasInternas)->whereNotNull('FECHA_TERMINO_CONVENIO')
                ->where('FECHA_TERMINO_CONVENIO', '<', Carbon::now()->toDateString());
                $conveniosVigentesAdtsInternasCerradas = 
                (clone $adtsCerradasInternas)->whereNotNull('FECHA_TERMINO_CONVENIO')
                ->where('FECHA_TERMINO_CONVENIO', '>=', Carbon::now()->toDateString());

                $datosAdts = [
                    //Datos Abiertas Totales
                    'numeroAdtsAbiertas' => $adtsAbiertas->count(),
                    'numeroAdtsExternas' => $adtsAbiertasExternas->count(),
                    'numeroAdtsInternas' => $adtsAbiertasInternas->count(),
                    'consumoInternetLineasAbiertas' => $consumoInternetLineasAbiertas,
                    'consumoInternetLineasMayorAbiertas' => $consumoInternetLineasMayorAbiertas->count(),
                    'numeroAdtsCerradasInternas' => $adtsCerradasInternas->count(),
                    'numeroAdtsConLineasPagaEntidad' => $adtsConLineasPagaEntidad->count(),
                    'numeroLineasPagaEntidad' => $lineasDeAdtsPagaEntidad->count(),
                    'numeroLineasCobre' => $lineasDeAdtsCobreQuePagaEntidad->count(),
                    'numeroAdtsConLineasPagaTelmex' => $adtsConLineasPagaTelmex->count(),
                    'numeroLineasPagaTelmex' => $lineasDeAdtsPagaTelmex->count(),
                    'numeroLineasEnlaceQuePagaTelmex' => $lineasDeAdtsEnlaceQuePagaTelmex->count(),
                    'costoLineasPagaEntidad' => $costoLineasPagaEntidad,
                    'costoLineasPagaTelmex' => $costoLineasPagaTelmex,
                    'numeroLineasConsumoSinConsumo' => $lineasConsumoSinConsumo->count(),
                    'numeroLineasConsumoBajo' => $lineasConsumoBajo->count(),
                    'numeroLineasConsumoMedio' => $lineasConsumoMedio->count(),
                    'numeroLineasConsumoAlto' => $lineasConsumoAlto->count(),
                    'numeroLineasConsumoHeavy' => $lineasConsumoHeavy->count(),
                    'numeroLineasConsumoAtipico' => $lineasConsumoAtipico->count(),
                    'cantidadEquipamientoAdts' => $totalEquipamientoAdts,
                    'cantidadEquipamientoInicialAdts' => $totalEquipamientoInicialAdts,
                    'cantidadEquipamientoFuncionalAdts' => $totalEquipamientoFuncionalAdts,
                    'cantidadRelacionPorcentualEquipamientoFuncionalEntreInicial' => 
                    $relacionPorcentualEquipamientoFuncionalEntreInicial,
                    'cantidadMobiliarioAdts' => $totalMobiliarioAdts,
                    'cantidadMobiliarioInicialAdts' => $totalMobiliarioInicialAdts,
                    'cantidadMobiliarioFuncionaAdts' => $totalMobiliarioFuncionalAdts,
                    'numeroConveniosIndeterminadosAdts' => $conveniosIndeterminadosAdts->count(),
                    'numeroConveniosVencidosAdts' => $conveniosVencidosAdts->count(),
                    'conveniosVigentesAdts' => $conveniosVigentesAdts->get(),
                    'numeroConveniosVigentesAdts' => $conveniosVigentesAdts->count(),

                    //Datos Abiertas Internas
                    'adtsInternasPropias' => $adtsInternasPropias->get(),
                    'numeroAdtsInternasPropias' => $numeroAdtsInternasPropias,
                    'adtsInternasExternas' => $adtsInternasExternas->get(),
                    'consumoInternetLineasAdtsInternas' => $consumoInternetLineasAdtsInternas,
                    'consumoInternetLineasMayorAdtsInternas' => $consumoInternetLineasMayorAdtsInternas->count(),
                    'numeroAdtsInternasExternas' => $numeroAdtsInternasExternas,
                    'numeroLineasAdtsInternasExternas' => $adtsInternasExternasConLineas->count(),
                    'numeroLineasAdtsInternasPropias' => $adtsInternasPropiasConLineas->count(),
                    'costoLineasAdtsInternasExternasPagaTelmex' 
                    => $costoLineasAdtsInternasExternasPagaTelmex,
                    'costoLineasAdtsInternasPropiasPagaTelmex' 
                    => $costoLineasAdtsInternasPropiasPagaTelmex,
                    'numeroLineasAdtsInternasConsumoSinConsumo' => $lineasAdtsInternasConsumoSinConsumo->count(),
                    'numeroLineasAdtsInternasConsumoBajo' => $lineasAdtsInternasConsumoBajo->count(),
                    'numeroLineasAdtsInternasConsumoMedio' => $lineasAdtsInternasConsumoMedio->count(),
                    'numeroLineasAdtsInternasConsumoAlto' => $lineasAdtsInternasConsumoAlto->count(),
                    'numeroLineasAdtsInternasConsumoHeavy' => $lineasAdtsInternasConsumoHeavy->count(),
                    'numeroLineasAdtsInternasConsumoAtipico' => $lineasAdtsInternasConsumoAtipico->count(),
                    'cantidadEquipamientoAdtsInternas' => $totalEquipamientoAdtsInternas,
                    'cantidadEquipamientoFuncionalAdtsInternas' => $totalEquipamientoFuncionalAdtsInternas,
                    'cantidadEquipamientoBDOFAdtsInternas' => $totalEquipamientoBDOFAdtsInternas,
                    'cantidadRelacionPorcentualEquipamientoFuncionalEntreInicialAdtsInternas' 
                    => $relacionPorcentualEquipamientoFuncionalEntreInicialAdtsInternas,
                    'numeroConveniosIndeterminadosAdtsInternas' => $conveniosIndeterminadosAdtsInternas->count(),
                    'numeroConveniosVencidosAdtsInternas' => $conveniosVencidosAdtsInternas->count(),
                    'conveniosVigentesAdtsInternas' => $conveniosVigentesAdtsInternas->get(),
                    'numeroConveniosVigentesAdtsInternas' => $conveniosVigentesAdtsInternas->count(),
                    'adtsAbiertasInternas' => $adtsAbiertasInternas->get(),

                    //Datos Cerradas Internas
                    'adtsCerradasInternas' => $adtsCerradasInternas->get(),
                    'consumoInternetLineasAdtsInternasCerradas' => $consumoInternetLineasAdtsInternasCerradas,
                    'consumoInternetLineasMayorAdtsInternasCerradas' => $consumoInternetLineasMayorAdtsInternasCerradas->count(),
                    'numeroLineasAdtsInternasExternasCerradas' => 
                    $adtsInternasExternasCerradasConLineas->count(),
                    'numeroLineasAdtsInternasPropiasCerradas' => 
                    $adtsInternasPropiasCerradasConLineas->count(),
                    'costoLineasAdtsInternasExternasCerradasPagaTelmex' 
                    => $costoLineasAdtsInternasExternasCerradasPagaTelmex,
                    'costoLineasAdtsInternasPropiasCerradasPagaTelmex' 
                    => $costoLineasAdtsInternasPropiasCerradasPagaTelmex,
                    'numeroLineasAdtsInternasCerradasConsumoSinConsumo' => 
                    $lineasAdtsInternasCerradasConsumoSinConsumo->count(),
                    'numeroLineasAdtsInternasCerradasConsumoBajo' => 
                    $lineasAdtsInternasCerradasConsumoBajo->count(),
                    'numeroLineasAdtsInternasCerradasConsumoMedio' => 
                    $lineasAdtsInternasCerradasConsumoMedio->count(),
                    'numeroLineasAdtsInternasCerradasConsumoAlto' => 
                    $lineasAdtsInternasCerradasConsumoAlto->count(),
                    'numeroLineasAdtsInternasCerradasConsumoHeavy' => 
                    $lineasAdtsInternasCerradasConsumoHeavy->count(),
                    'numeroLineasAdtsInternasCerradasConsumoAtipico' => 
                    $lineasAdtsInternasCerradasConsumoAtipico->count(),
                    'cantidadEquipamientoAdtsInternasCerradas' => $totalEquipamientoAdtsInternasCerradas,
                    'cantidadEquipamientoFuncionalAdtsInternasCerradas' => 
                    $totalEquipamientoFuncionalAdtsInternasCerradas,
                    'cantidadEquipamientoBDOFAdtsInternasCerradas' => 
                    $totalEquipamientoBDOFAdtsInternasCerradas,
                    'conveniosVigentesAdtsInternasCerradas' =>
                    $conveniosVigentesAdtsInternasCerradas->get(),
                    'numeroConveniosVigentesAdtsInternasCerradas' => 
                    $conveniosVigentesAdtsInternasCerradas->count(),
                    'numeroConveniosVencidosAdtsInternasCerradas' => 
                    $conveniosVencidosAdtsInternasCerradas->count(),
                    'numeroConveniosIndeterminadosAdtsInternasCerradas' => 
                    $conveniosIndeterminadosAdtsInternasCerradas->count()
                ];

                $datosQueSeCapturan = [

                    //BDTS Abiertas
                    'usuariosBdtsAcumulados' => DatosCapturaEstadoTutoriasAbiertas::value('USUARIOS_BDTS_ACUMULADOS'),
                    'usuariosBdtsRegistraron' => DatosCapturaEstadoTutoriasAbiertas::value('USUARIOS_BDTS_REGISTRARON'),
                    'usuariosBdtsTotales' => DatosCapturaEstadoTutoriasAbiertas::value('USUARIOS_BDTS_TOTALES'),
                    'usuariosBdtsRegistrados' => DatosCapturaEstadoTutoriasAbiertas::value('USUARIOS_BDTS_REGISTRADOS'),
                    'usuariosBdtsInscritos' => DatosCapturaEstadoTutoriasAbiertas::value('USUARIOS_BDTS_INSCRITOS'),
                    'usuariosBdtsConstancias' => DatosCapturaEstadoTutoriasAbiertas::value('USUARIOS_BDTS_CONSTANCIAS'),
                    'ofertaEducativaNuevosTalleres' => DatosCapturaEstadoTutoriasAbiertas::value('OFERTA_EDUCATIVA_NUEVOS_TALLERES'),
                    'ofertaEducativaTalleres' => DatosCapturaEstadoTutoriasAbiertas::value('OFERTA_EDUCATIVA_TALLERES'),
                    'ofertaEducativaEnLinea' => DatosCapturaEstadoTutoriasAbiertas::value('OFERTA_EDUCATIVA_EN_LINEA'),
                    'ofertaEducativaTalleresEnDesarrollo' => DatosCapturaEstadoTutoriasAbiertas::value('OFERTA_EDUCATIVA_TALLERES_EN_DESARROLLO'),
                    'solicitudesRecibidas' => DatosCapturaEstadoTutoriasAbiertas::value('SOLICITUDES_RECIBIDAS'),
                    'solicitudBdt' => DatosCapturaEstadoTutoriasAbiertas::value('SOLICITUD_BDT'),
                    'solicitudReequipamiento' => DatosCapturaEstadoTutoriasAbiertas::value('SOLICITUD_REEQUIPAMIENTO'),
                    'solicitudRetiro' => DatosCapturaEstadoTutoriasAbiertas::value('SOLICITUD_RETIRO'),
                    'solicitudOtros' => DatosCapturaEstadoTutoriasAbiertas::value('SOLICITUD_OTROS'),

                    //BDTS Abiertas Internas
                    'internetInfinitumPersonalInterno'  => DatosCapturaEstadoTutoriasAbiertasInternas::value('INTERNET_INFINITUM_PERSONAL_INTERNO'),
                    'internetVozPersonalInterno' => DatosCapturaEstadoTutoriasAbiertasInternas::value('INTERNET_VOZ_PERSONAL_INTERNO'),
                    'internetEnlacePersonalExterno'=> DatosCapturaEstadoTutoriasAbiertasInternas::value('INTERNET_ENLACE_PERSONAL_EXTERNO'),
                    'internetVozPersonalExterno' => DatosCapturaEstadoTutoriasAbiertasInternas::value('INTERNET_VOZ_PERSONAL_EXTERNO'),
                    'mobiliarioMesas' => DatosCapturaEstadoTutoriasAbiertasInternas::value('MOBILIARIO_MESAS'),
                    'mobiliarioSillas' => DatosCapturaEstadoTutoriasAbiertasInternas::value('MOBILIARIO_SILLAS'),
                    'mobiliarioLibreros' => DatosCapturaEstadoTutoriasAbiertasInternas::value('MOBILIARIO_LIBREROS'),
                    'mobiliarioTv' => DatosCapturaEstadoTutoriasAbiertasInternas::value('MOBILIARIO_TV'),
                    'mobiliarioArchiveros' => DatosCapturaEstadoTutoriasAbiertasInternas::value('MOBILIARIO_ARCHIVEROS'),
                    'mobiliarioRacks' => DatosCapturaEstadoTutoriasAbiertasInternas::value('MOBILIARIO_RACKS'),
                    'mobiliarioCarritoCargador' => DatosCapturaEstadoTutoriasAbiertasInternas::value('MOBILIARIO_CARRITO_CARGADOR'),
                    'usuariosAcumulado' => DatosCapturaEstadoTutoriasAbiertasInternas::value('USUARIOS_ACUMULADO'),
                    'numeroDeUsuariosMetaRealAdts' => $numeroUsuariosMetaRealAdts->keyby('NOMBRE'),
                    'gastoMensualAcumulado' => DatosCapturaEstadoTutoriasAbiertasInternas::value('GASTO_MENSUAL_ACUMULADO'),
                    'gastoMensual' => DatosCapturaEstadoTutoriasAbiertasInternas::value('GASTO_MENSUAL'),
                    'gastoMensualRenta' => DatosCapturaEstadoTutoriasAbiertasInternas::value('GASTO_MENSUAL_RENTA'),
                    'gastoMensualAseo' => DatosCapturaEstadoTutoriasAbiertasInternas::value('GASTO_MENSUAL_ASEO'),
                    'gastoMensualLuz' => DatosCapturaEstadoTutoriasAbiertasInternas::value('GASTO_MENSUAL_LUZ'),
                    'gastoMensualVigilancia' => DatosCapturaEstadoTutoriasAbiertasInternas::value('GASTO_MENSUAL_VIGILANCIA'),
                    'gastoAguaPotable' => DatosCapturaEstadoTutoriasAbiertasInternas::value('GASTO_AGUA_POTABLE'),
                    'gastoNominaOperacion' => DatosCapturaEstadoTutoriasAbiertasInternas::value('GASTO_NOMINA_OPERACION'),
                    'gastoNominaGerencia' => DatosCapturaEstadoTutoriasAbiertasInternas::value('GASTO_NOMINA_GERENCIA'),
                    'gastoMantenimientosTotal' => DatosCapturaEstadoTutoriasAbiertasInternas::value('GASTO_MANTENIMIENTOS_TOTAL'),
                    'gastoMantenimientosEjercido' => DatosCapturaEstadoTutoriasAbiertasInternas::value('GASTO_MANTENIMIENTOS_EJERCIDO'),
                    'solicitudesAbiertasInternas' => DatosCapturaEstadoTutoriasAbiertasInternas::value('SOLICITUDES'),

                    //BDTS CERRADAS INTERNAS
                    'internetInfinitumPersonalInternoC' => DatosCapturaEstadoTutoriasCerradasInternas::value('INTERNET_INFINITUM_PERSONAL_INTERNO'),
                    'internetVozPersonalInternoC' => DatosCapturaEstadoTutoriasCerradasInternas::value('INTERNET_VOZ_PERSONAL_INTERNO'),
                    'internetEnlacePersonalExternoC' => DatosCapturaEstadoTutoriasCerradasInternas::value('INTERNET_ENLACE_PERSONAL_EXTERNO'),
                    'internetVozPersonalExternoC' => DatosCapturaEstadoTutoriasCerradasInternas::value('INTERNET_VOZ_PERSONAL_EXTERNO'),
                    'mobiliarioMesasC' => DatosCapturaEstadoTutoriasCerradasInternas::value('MOBILIARIO_MESAS'),
                    'mobiliarioSillasC' => DatosCapturaEstadoTutoriasCerradasInternas::value('MOBILIARIO_SILLAS'),
                    'mobiliarioLibrerosC' => DatosCapturaEstadoTutoriasCerradasInternas::value('MOBILIARIO_LIBREROS'),
                    'mobiliarioTvC' => DatosCapturaEstadoTutoriasCerradasInternas::value('MOBILIARIO_TV'),
                    'mobiliarioArchiverosC' => DatosCapturaEstadoTutoriasCerradasInternas::value('MOBILIARIO_ARCHIVEROS'),
                    'mobiliarioRacksC' => DatosCapturaEstadoTutoriasCerradasInternas::value('MOBILIARIO_RACKS'),
                    'mobiliarioCarritoCargadorC' => DatosCapturaEstadoTutoriasCerradasInternas::value('MOBILIARIO_CARRITO_CARGADOR'),
                    'usuariosAcumuladoC' => DatosCapturaEstadoTutoriasCerradasInternas::value('USUARIOS_ACUMULADO'),
                    'numeroUsuariosDelAnioBdts' => DatosCapturaEstadoTutoriasCerradasInternas::value('NUMERO_USUARIOS_DEL_ANIO_BDTS'),
                    'numeroUsuariosDelMesBdts' => DatosCapturaEstadoTutoriasCerradasInternas::value('NUMERO_USUARIOS_DEL_MES_BDTS'),
                    'numeroUsuariosDelAnioFiliales' => DatosCapturaEstadoTutoriasCerradasInternas::value('NUMERO_USUARIOS_DEL_ANIO_FILIALES'),
                    'numeroUsuariosDelMesFiliales' => DatosCapturaEstadoTutoriasCerradasInternas::value('NUMERO_USUARIOS_DEL_MES_FILIALES'),
                    'gastoMensualAcumuladoC' => DatosCapturaEstadoTutoriasCerradasInternas::value('GASTO_MENSUAL_ACUMULADO'),
                    'gastoMensualC' => DatosCapturaEstadoTutoriasCerradasInternas::value('GASTO_MENSUAL'),
                    'gastoMensualRentaC' => DatosCapturaEstadoTutoriasCerradasInternas::value('GASTO_MENSUAL_RENTA'),
                    'gastoMensualAseoC' => DatosCapturaEstadoTutoriasCerradasInternas::value('GASTO_MENSUAL_ASEO'),
                    'gastoMensualLuzC' => DatosCapturaEstadoTutoriasCerradasInternas::value('GASTO_MENSUAL_LUZ'),
                    'gastoMensualVigilanciaC' => DatosCapturaEstadoTutoriasCerradasInternas::value('GASTO_MENSUAL_VIGILANCIA'),
                    'gastoAguaPotableC' => DatosCapturaEstadoTutoriasCerradasInternas::value('GASTO_AGUA_POTABLE'),
                    'gastoNominaOperacionC' => DatosCapturaEstadoTutoriasCerradasInternas::value('GASTO_NOMINA_OPERACION'),
                    'gastoNominaGerenciaC' => DatosCapturaEstadoTutoriasCerradasInternas::value('GASTO_NOMINA_GERENCIA'),
                    'gastoMantenimientosTotalC' => DatosCapturaEstadoTutoriasCerradasInternas::value('GASTO_MANTENIMIENTOS_TOTAL'),
                    'gastoMantenimientosEjercidoC' => DatosCapturaEstadoTutoriasCerradasInternas::value('GASTO_MANTENIMIENTOS_EJERCIDO'),
                    'solicitudesCerradasInternas' => DatosCapturaEstadoTutoriasCerradasInternas::value('SOLICITUDES'),

                ];

                $sheet->loadView('exports.reporteGeneralTutorias', [
                    'datosAdts' => $datosAdts,
                    'datosQueSeCapturan' => $datosQueSeCapturan,
                ]);

                $sheet->setWidth([
                    'A' => 20, 
                    'B' => 20, 
                    'C' => 20, 
                    'D' => 20, 
                    'E' => 20, 
                    'F' => 20, 
                    'G' => 20, 
                    'H' => 20, 
                    'I' => 20, 
                    'J' => 2, 
                    'K' => 20, 
                    'L' => 20, 
                    'M' => 20, 
                    'N' => 20, 
                    'O' => 20, 
                    'P' => 20, 
                    'Q' => 20, 
                    'R' => 20, 
                    'S' => 20, 
                    'T' => 2, 
                    'U' => 20, 
                    'V' => 20, 
                    'W' => 20, 
                    'X' => 20, 
                    'Y' => 20, 
                    'Z' => 20, 
                    'AA' => 20,
                    'AB' => 20,
                    'AC' => 20,
                ]);

            });

        });

    }

    public function descargarReporteGeneralActual() {

        return $this->generarReporteGeneralActual()->download('xlsx');

    }

}