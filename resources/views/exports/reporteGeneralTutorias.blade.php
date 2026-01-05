<table class="table">
    <thead class="bg-info">
        <tr><!--1-->
            @php
                $meses = [
                    'January'   => 'Enero',
                    'February'  => 'Febrero',
                    'March'     => 'Marzo',
                    'April'     => 'Abril',
                    'May'       => 'Mayo',
                    'June'      => 'Junio',
                    'July'      => 'Julio',
                    'August'    => 'Agosto',
                    'September' => 'Septiembre',
                    'October'   => 'Octubre',
                    'November'  => 'Noviembre',
                    'December'  => 'Diciembre',
                ];

                $fecha = \Carbon\Carbon::now();
                $mes   = $meses[$fecha->format('F')]; // Traducción manual
                $anio  = $fecha->format('Y');
            @endphp
            <th colspan="29" style="background-color: #1b3fa2; color: #ffffff; text-align:center;">
                ESTATUS Bibliotecas Digitales Telmex {{ $mes }} {{ $anio }}
            </th>
        </tr>
    </thead>
    <thead class="bg-info">
        <tr><!--2-->
            <th colspan="19" style="background-color: #23981d; text-align:center;">
                {{ $datosAdts['numeroAdtsAbiertas'] ?? '-' }} ABIERTAS
            </th>
	        <th colspan="10" style="background-color: #747673ff; text-align:center;">
                {{ $datosAdts['numeroAdtsCerradasInternas'] ?? '-' }} CERRADAS (INTERNAS)
            </th> 
        </tr>
    </thead>
    <tr class="table-info"><!--3-->
        <th colspan="5" style="background-color: #6be465; text-align:center;">
            {{ $datosAdts['numeroAdtsAbiertas'] ?? '-' }} Totales
        </th>
        <th colspan="2" style="background-color: #6be465; text-align:center;">
            Cerradas del mes:
        </th>
        <th colspan="2" style="background-color: #6be465; text-align:center;">
            Abiertas del mes:
        </th>
        <td>
        </td>
        <th colspan="9" style="background-color: #6be465; text-align:center;">
            {{ $datosAdts['numeroAdtsInternas'] ?? '-' }} Internas
        </th>
        <td>
        </td>
        <th colspan="9" style="background-color: #6be465; text-align:center;">
            {{ $datosAdts['numeroAdtsCerradasInternas'] ?? '-' }} Internas
        </th>
    </tr>
    <tr><!--4-->
        <td colspan="5" style="background-color: #bffbbc; text-align:center;">
            {{ $datosAdts['numeroAdtsExternas'] ?? '-' }} Externas
        </td>
        <td  style="color: red; background-color: #bffbbc; text-align:center;" colspan="2">
            -
        </td>
        <td  style="color: red; background-color: #bffbbc; text-align:center;" colspan="2">
            -
        </td>
        <td>
        </td>
        <td colspan="9" style="background-color: #bffbbc; text-align:center;">
            {{ $datosAdts['numeroAdtsInternasPropias'] ?? '-' }} con personal interno
            @foreach($datosAdts['adtsInternasPropias'] as $adtInternaPropia)
                {{ " (" . $adtInternaPropia->NOMBRE }})
            @endforeach
        </td>
        <td>
        </td>
	    <td colspan="9">
            @foreach($datosAdts['adtsCerradasInternas'] as $adtCerradaInterna)
                {{ $adtCerradaInterna->NOMBRE }}@if(!$loop->last), @endif
            @endforeach
        </td>
    </tr>
    <tr><!--5-->
        <td colspan="5" style="background-color: #bffbbc; text-align:center;">
            {{ $datosAdts['numeroAdtsInternas'] ?? '-' }} Internas
        </td>
        <td  style="color: red; background-color: #bffbbc; text-align:center;" colspan="2">
            -
        </td>
        <td  style="color: red; background-color: #bffbbc; text-align:center;" colspan="2">
            -
        </td>
        <td>
        </td>
        <td colspan="9" style="background-color: #bffbbc; text-align:center;">
            {{ $datosAdts['numeroAdtsInternasExternas'] ?? '-' }} con personal externo
            @foreach($datosAdts['adtsInternasExternas'] as $adtInternaExterna)
                {{ " (" . $adtInternaExterna->NOMBRE }})
            @endforeach
        </td>
        <td>
        </td>
        <td colspan="9" style="background-color: #bffbbc; text-align:center;">
        </td>
    </tr>
    <tr class="table-info"><!--6-->
        <th colspan="9" style="background-color: #6be465;">
            1. Internet (uso promedio del mes: {{ number_format($datosAdts['consumoInternetLineasAbiertas'], 2) ?? '-' }} GB, {{ $datosAdts['consumoInternetLineasMayorAbiertas'] ?? '-' }} &gt; 110 GB)
        </th>
        <td>
        </td>
        <th colspan="9" style="background-color: #6be465;">
            1. Internet (uso promedio del mes: {{ number_format($datosAdts['consumoInternetLineasAdtsInternas'], 2) ?? '-' }} GB, {{ $datosAdts['consumoInternetLineasMayorAdtsInternas'] ?? '-' }} &gt; 110 GB)
        </th>
        <td>
        </td>
        <th colspan="9">
            1. Internet (uso promedio del mes: {{ number_format($datosAdts['consumoInternetLineasAdtsInternasCerradas'], 2) ?? '-' }} GB, {{ $datosAdts['consumoInternetLineasMayorAdtsInternasCerradas'] ?? '-' }} &gt; 110 GB)
        </th>
    </tr>
    <tr><!--7-->
        <td colspan="5" style="background-color: #bffbbc; text-align:center;">
            {{ $datosAdts['numeroAdtsConLineasPagaEntidad'] ?? '-' }} BDT con {{ $datosAdts['numeroLineasPagaEntidad'] ?? '-' }} líneas paga la entidad ({{ $datosAdts['numeroLineasCobre'] ?? '-'}} en cobre)
        </td>
        <td colspan="4" style="background-color: #bffbbc; text-align:center;">
            {{ $datosAdts['numeroAdtsConLineasPagaTelmex'] ?? '-'}} BDT con {{ $datosAdts['numeroLineasPagaTelmex'] ?? '-'}} líneas y {{ $datosAdts['numeroLineasEnlaceQuePagaTelmex'] ?? '-' }} enlaces que paga Telmex
        </td>
        <td>
        </td>
        <td colspan="5" style="background-color: #bffbbc; text-align:center;">
            {{ $datosAdts['numeroLineasAdtsInternasExternas'] ?? '-' }} CT con {{ number_format($datosQueSeCapturan['internetInfinitumPersonalInterno'], 0, ".", ",") ?? '-' }} infinitum y {{ number_format($datosQueSeCapturan['internetVozPersonalInterno'], 0, ".", ",") ?? '-' }} de voz (paga Telmex)
        </td>
        <td colspan="4" style="background-color: #bffbbc; text-align:center;">
            {{ $datosAdts['numeroLineasAdtsInternasPropias'] ?? '-' }} CT con {{ number_format($datosQueSeCapturan['internetEnlacePersonalExterno'], 0, ".", ",") ?? '-' }} enlaces y {{ number_format($datosQueSeCapturan['internetVozPersonalExterno'], 0, ".", ",") ?? '-' }} de voz (paga Telmex)
        </td>
        <td>
        </td>
        <td colspan="5"><!--En producción seguirá siendo datos que se capturan pero las variables dentro del array al final tendrán C-->
            {{ $datosAdts['numeroLineasAdtsInternasExternasCerradas'] ?? '-' }} CT con {{ number_format($datosQueSeCapturan['internetInfinitumPersonalInternoC'], 0, ".", ",") ?? '-' }} infinitum y {{ number_format($datosQueSeCapturan['internetVozPersonalInternoC'], 0, ".", ",") ?? '-' }} de voz (paga Telmex)
        </td>
        <td colspan="4">
            {{ $datosAdts['numeroLineasAdtsInternasPropiasCerradas'] ?? '-' }} CT con {{ number_format($datosQueSeCapturan['internetEnlacePersonalExternoC'], 0, ".", ",") ?? '-' }} enlaces y {{ number_format($datosQueSeCapturan['internetVozPersonalExternoC'], 0, ".", ",") ?? '-' }} de voz (paga Telmex)
        </td>
    </tr>
    <tr><!--8-->
        <td colspan="5" style="background-color: #bffbbc; text-align:center;">
            {{ isset($datosAdts['costoLineasPagaEntidad']) ? '$' . number_format($datosAdts['costoLineasPagaEntidad'], 2) : '-' }}
        </td>
        <td colspan="4" style="background-color: #bffbbc; text-align:center;">
            {{ isset($datosAdts['costoLineasPagaTelmex']) ? '$' . number_format($datosAdts['costoLineasPagaTelmex'], 2) : '-' }}
        </td>
        <td>
        </td>
        <td colspan="5" style="background-color: #bffbbc; text-align:center;">
            {{ isset($datosAdts['costoLineasAdtsInternasExternasPagaTelmex']) ? '$' . number_format($datosAdts['costoLineasAdtsInternasExternasPagaTelmex'], 2) : '-' }}
        </td>
        <td colspan="4" style="background-color: #bffbbc; text-align:center;">
            {{ isset($datosAdts['costoLineasAdtsInternasPropiasPagaTelmex']) ? '$' . number_format($datosAdts['costoLineasAdtsInternasPropiasPagaTelmex'], 2) : '-' }}
        </td>
        <td>
        </td>
        <td colspan="5">
            {{ $datosAdts['costoLineasAdtsInternasExternasCerradasPagaTelmex'] ?? '-' }}
        </td>
        <td colspan="4">
            {{ isset($datosAdts['costoLineasAdtsInternasPropiasCerradasPagaTelmex']) ? '$' . number_format($datosAdts['costoLineasAdtsInternasPropiasCerradasPagaTelmex'], 2) : '-' }}
        </td>
    </tr>
    <tr><!--9-->
        <td style="background-color: #bffbbc; text-align:center;">
            Sin consumo
        </td>
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            Bajo
        </td>
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            Medio
        </td>
        <td  style="background-color: #bffbbc; text-align:center;">
            Alto
        </td>
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            Heavy
        </td>
        <td style="background-color: #bffbbc; text-align:center;">
            Atípico
        </td>
        <td>
        </td>
        <td style="background-color: #bffbbc; text-align:center;">
            Sin consumo
        </td>
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            Bajo
        </td>
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            Medio
        </td>
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            Alto
        </td>
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            Heavy
        </td>
        <td>
        </td>
        <td style="background-color: #bffbbc; text-align:center;">
            Sin consumo
        </td>
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            Bajo
        </td>
        <td colspan="3" style="background-color: #bffbbc; text-align:center;">
            Medio
        </td>
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            Alto
        </td>
        <td style="background-color: #bffbbc; text-align:center;">
            Heavy
        </td>
    </tr>
    <tr><!--10-->
        <td style="background-color: #bffbbc; text-align:center;">
            {{ $datosAdts['numeroLineasConsumoSinConsumo'] ?? '-' }}
        </td>
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            {{ $datosAdts['numeroLineasConsumoBajo'] ?? '-' }}
        </td>
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            {{ $datosAdts['numeroLineasConsumoMedio'] ?? '-' }}
        </td>
        <td style="background-color: #bffbbc; text-align:center;">
            {{ $datosAdts['numeroLineasConsumoAlto'] ?? '-' }}
        </td>
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            {{ $datosAdts['numeroLineasConsumoHeavy'] ?? '-' }}
        </td>
        <td style="background-color: #bffbbc; text-align:center;">
            {{ $datosAdts['numeroLineasConsumoAtipico'] ?? '-' }}
        </td>
        <td>
        </td>
        <td style="background-color: #bffbbc; text-align:center;">
            {{ $datosAdts['numeroLineasAdtsInternasConsumoSinConsumo'] ?? '-' }}
        </td>
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            {{ $datosAdts['numeroLineasAdtsInternasConsumoBajo'] ?? '-' }}
        </td>
        <td colspan="3" style="background-color: #bffbbc; text-align:center;">
            {{ $datosAdts['numeroLineasAdtsInternasConsumoMedio'] ?? '-' }}
        </td>
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            {{ $datosAdts['numeroLineasAdtsInternasConsumoAlto'] ?? '-' }}
        </td>
        <td style="background-color: #bffbbc; text-align:center;">
            {{ $datosAdts['numeroLineasAdtsInternasConsumoHeavy'] ?? '-' }}
        </td>
        <td>
        </td>
        <td>
            {{ $datosAdts['numeroLineasAdtsInternasCerradasConsumoSinConsumo'] ?? '-' }}
        </td>
        <td colspan="2">
            {{ $datosAdts['numeroLineasAdtsInternasCerradasConsumoBajo'] ?? '-' }}
        </td>
        <td colspan="2">
            {{ $datosAdts['numeroLineasAdtsInternasCerradasConsumoMedio'] ?? '-' }}
        </td>
        <td colspan="2">
            {{ $datosAdts['numeroLineasAdtsInternasCerradasConsumoAlto'] ?? '-' }}
        </td>
        <td colspan="2">
            {{ $datosAdts['numeroLineasAdtsInternasCerradasConsumoHeavy'] ?? '-' }}
        </td>
    </tr>
    <tr><!--11-->
        <th class="text-center table-info"colspan="9" style="background-color: #6be465;">
            2. Equipamiento
        </th>
        <td>
        </td>
        <th class="text-center table-secondary"colspan="9" style="background-color: #6be465;">
            2. Equipamiento
        </th>
        <td>
        </td>
        <th class="text-center table-info"colspan="9">
            2. Equipamiento
        </th>
    </tr>
    <tr><!--12-->
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            Total del proyecto
        </td>
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            Abiertas Inicial
        </td>
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            Abiertas Funcional
        </td>
        <td colspan="3" style="background-color: #bffbbc; text-align:center;">
            % funcional contra inicial
        </td>
        <td>
        </td>
        <td style="background-color: #bffbbc; text-align:center;">
            Total del proyecto
        </td>
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            Funcional
        </td>
        <td colspan="4" style="background-color: #bffbbc; text-align:center;">
            Baja, Dañado, Obsoleto o Faltante
        </td>
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            % funcional contra inicial
        </td>
        <td>
        </td>
        <td colspan="2">
            Total del proyecto
        </td>
        <td colspan="4">
            Abiertas Funcional
        </td>
        <td colspan="3">
            Baja, Dañado, Obsoleto o Faltante
        </td>
    </tr>
    <tr><!--13-->
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            {{ number_format($datosAdts['cantidadEquipamientoAdts'], 0, ".", ",") ?? '-' }}
        </td>
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            {{ number_format($datosAdts['cantidadEquipamientoInicialAdts'], 0, ".", ",") ?? '-' }}
        </td>
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            {{ number_format($datosAdts['cantidadEquipamientoFuncionalAdts'], 0, ".", ",") ?? '-'}}
        </td>
        <td colspan="3" style="background-color: #bffbbc; text-align:center;">
            {{ isset($datosAdts['cantidadRelacionPorcentualEquipamientoFuncionalEntreInicial']) ? number_format($datosAdts['cantidadRelacionPorcentualEquipamientoFuncionalEntreInicial'], 2) . "%" : '-' }}
        </td>
        <td>
        </td>
        <td style="background-color: #bffbbc; text-align:center;">
            {{ number_format($datosAdts['cantidadEquipamientoAdtsInternas'], 0, ".", ",") ?? '-' }}
        </td>
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            {{ number_format($datosAdts['cantidadEquipamientoFuncionalAdtsInternas'], 0, ".", ",") ?? '-' }}
        </td>
        <td colspan="4" style="background-color: #bffbbc; text-align:center;">
            {{ number_format($datosAdts['cantidadEquipamientoBDOFAdtsInternas'],0 , ".", ",") ?? '-' }}
        </td>
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            {{ isset($datosAdts['cantidadRelacionPorcentualEquipamientoFuncionalEntreInicialAdtsInternas']) ? number_format($datosAdts['cantidadRelacionPorcentualEquipamientoFuncionalEntreInicialAdtsInternas'], 2) . '%' : '-' }}
        </td>
        <td>
        </td>
        <td colspan="2">
            {{ number_format($datosAdts['cantidadEquipamientoAdtsInternasCerradas'], 0, ".", ",") ?? '-' }}
        </td>
        <td colspan="4">
            {{ number_format($datosAdts['cantidadEquipamientoFuncionalAdtsInternasCerradas'], 0, ".", ",") ?? '-' }}
        </td>
        <td colspan="3">
            {{ number_format($datosAdts['cantidadEquipamientoBDOFAdtsInternasCerradas'], 0, ".", ",") ?? '-' }}
        </td>
    </tr>
    <tr><!--14-->
        <th class="text-center table-info"colspan="9" style="background-color: #6be465;">
            3. Mobiliario BDT Externas
        </th>
        <td>
        </td>
        <th class="text-center table-info"colspan="9" style="background-color: #6be465;">
            3. Mobiliario y gadgets funcionales
        </th>
        <td>
        </td>
        <th class="text-center table-secondary"colspan="9">
            3. Mobiliario y gadgets
        </th>
    </tr>
    <tr><!--15-->
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            Total del proyecto
        </td>
        <td colspan="7" style="background-color: #bffbbc; text-align:center;">
            BDT Abiertas
        </td>
        <td>
        </td>
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            Mesas
        </td>
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            Sillas, bancos y puff
        </td>
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            Libreros
        </td>
        <td colspan="3" style="background-color: #bffbbc; text-align:center;">
            Tv
        </td>
        <td>
        </td>
        <td colspan="2">
            Mesas
        </td>
        <td colspan="2">
            Sillas, bancos y puff
        </td>
        <td colspan="2">
            Libreros
        </td>
        <td colspan="3">
            Tv
        </td>
    </tr>
    <tr><!--16-->
        <td colspan="2"style="background-color: #bffbbc; text-align:center;">
        </td>
        <td colspan="4"style="background-color: #bffbbc; text-align:center;">
            Inicial
        </td>
        <td colspan="3"style="background-color: #bffbbc; text-align:center;">
            Funcional
        </td>
        <td>
        </td>
        <td colspan="2"style="background-color: #bffbbc; text-align:center;">
            {{ number_format($datosQueSeCapturan['mobiliarioMesas'], 0, ".", ",") ?? '-' }}
        </td>
        <td colspan="2"style="background-color: #bffbbc; text-align:center;">
            {{ number_format($datosQueSeCapturan['mobiliarioSillas'], 0, ".", ",") ?? '-' }}
        </td>
        <td colspan="2"style="background-color: #bffbbc; text-align:center;">
            {{ number_format($datosQueSeCapturan['mobiliarioLibreros'], 0, ".", ",") ?? '-' }}
        </td>
        <td colspan="3" style="background-color: #bffbbc; text-align:center;">
            {{ number_format($datosQueSeCapturan['mobiliarioTv'], 0, ".", ",") ?? '-' }}
        </td>
        <td>
        </td>
        <td colspan="2">
            {{ number_format($datosQueSeCapturan['mobiliarioMesasC'], 0, ".", ",") ?? '-' }}
        </td>
        <td colspan="2">
            {{ number_format($datosQueSeCapturan['mobiliarioSillasC'], 0, ".", ",") ?? '-' }}
        </td>
        <td colspan="2">
            {{ number_format($datosQueSeCapturan['mobiliarioLibrerosC'], 0, ".", ",") ?? '-' }}
        </td>
        <td colspan="3">
            {{ number_format($datosQueSeCapturan['mobiliarioTvC'], 0, ".", ",") ?? '-' }}
        </td>
    </tr>
    <tr><!--17-->
        <td  colspan="2" style="background-color: #bffbbc; text-align:center;">
            {{ number_format($datosAdts['cantidadMobiliarioAdts'], 0, ".", ",") ?? '-' }}
        </td>
        <td  colspan="4" style="background-color: #bffbbc; text-align:center;">
            {{ number_format($datosAdts['cantidadMobiliarioInicialAdts'], 0, ".", ",") ?? '-' }}
        </td>
        <td  colspan="3" style="background-color: #bffbbc; text-align:center;">
            {{ number_format($datosAdts['cantidadMobiliarioFuncionaAdts'], 0, ".", ",") ?? '-' }}
        </td>
        <td>
        </td>
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            Archivos y lockers
        </td>
        <td colspan="4" style="background-color: #bffbbc; text-align:center;">
            Racks
        </td>
        <td colspan="3" style="background-color: #bffbbc; text-align:center;">
            Carrito cargador
        </td>
        <td>
        </td>
        <td colspan="3">
            Archiveros y Lockers
        </td>
        <td colspan="3">
            Racks
        </td>
        <td colspan="3">
            Carrito Cargador
        </td>
    </tr>
    <tr><!--18-->
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
        </td>
        <td colspan="4" style="background-color: #bffbbc; text-align:center;">
        </td>
        <td colspan="3" style="background-color: #bffbbc; text-align:center;">
        </td>
        <td>
        </td>
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            {{ number_format($datosQueSeCapturan['mobiliarioArchiveros'], 0, ".", ",") ?? '-' }}
        </td>
        <td colspan="4" style="background-color: #bffbbc; text-align:center;">
            {{ number_format($datosQueSeCapturan['mobiliarioRacks'], 0, ".", ",") ?? '-' }}
        </td>
        <td colspan="3" style="background-color: #bffbbc; text-align:center;">
            {{ number_format($datosQueSeCapturan['mobiliarioCarritoCargador'], 0 , ".", ",") ?? '-' }}
        </td>
        <td>
        </td>
        <td colspan="3">
            {{ number_format($datosQueSeCapturan['mobiliarioArchiverosC'], 0, ".", ",") ?? '-' }}
        </td>
        <td colspan="3">
            {{ number_format($datosQueSeCapturan['mobiliarioRacksC'], 0, ".", ",") ?? '-' }}
        </td>
        <td colspan="3">
            {{ number_format($datosQueSeCapturan['mobiliarioCarritoCargadorC'], 0, ".", ",") ?? '-' }}
        </td>
    </tr>
    <tr><!--19-->
        <th class="text-center table-info"colspan="9" style="background-color: #6be465;">
            4. Estatus convenio
        </th>
        <td>
        </td>
	    <th class="text-center table-info"colspan="9" style="background-color: #6be465;">
            4. Estatus convenio
        </th>
        <td>
        </td>
        <th class="text-center table-secondary"colspan="9">
            4. Estatus convenio
        </th>
    </tr>
    <tr><!--20-->
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            Indeterminado
        </td>
        <td colspan="3" style="background-color: #bffbbc; text-align:center;">
            Vencido
        </td>
        <td colspan="4" style="background-color: #bffbbc; text-align:center;">
            Vigente
        </td>
        <td>
        </td>
	    <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            Indeterminado
        </td>
        <td colspan="3" style="background-color: #bffbbc; text-align:center;">
            Vencido
        </td>
        <td colspan="4" style="background-color: #bffbbc; text-align:center;">
            Vigente
        </td>
        <td>
        </td>
        <td colspan="2">
            Vigente
        </td>
        <td colspan="3">
            Vencido
        </td>
        <td colspan="4">
            Sin convenio
        </td>
    </tr>
    <tr><!--21-->
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            {{ $datosAdts['numeroConveniosIndeterminadosAdts'] ?? '-' }}
        </td>
        <td colspan="3" style="background-color: #bffbbc; text-align:center;">
            {{ $datosAdts['numeroConveniosVencidosAdts'] ?? '-' }}
        </td>
        <td colspan="4" style="background-color: #bffbbc; text-align:center;">
            {{ $datosAdts['numeroConveniosVigentesAdts'] ?? '-' }}
            @foreach($datosAdts['conveniosVigentesAdts'] as $convenioVigenteAdt)
                {{ " (" . $convenioVigenteAdt->NOMBRE }} / 
                {{ $convenioVigenteAdt->FECHA_TERMINO_CONVENIO }}) 
            @endforeach
        </td>
        <td>
        </td>
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            {{ $datosAdts['numeroConveniosIndeterminadosAdtsInternas'] ?? '-' }}
        </td>
        <td colspan="3" style="background-color: #bffbbc; text-align:center;">
            {{ $datosAdts['numeroConveniosVencidosAdtsInternas'] ?? '-' }}
        </td>
        <td colspan="4" style="background-color: #bffbbc; text-align:center;">
            {{ $datosAdts['numeroConveniosVigentesAdtsInternas'] ?? '-' }}
            @foreach($datosAdts['conveniosVigentesAdtsInternas'] as $convenioVigenteAdtInterna)
                {{ " (" . $convenioVigenteAdtInterna->NOMBRE }} / 
                {{ $convenioVigenteAdtInterna->FECHA_TERMINO_CONVENIO }}) 
            @endforeach
        </td>
        <td>
        </td>
        <td colspan="2">
            {{ $datosAdts['numeroConveniosVigentesAdtsInternasCerradas'] ?? '-' }}
            @foreach($datosAdts['conveniosVigentesAdtsInternasCerradas'] as $convenioVigenteAdtInternaCerrada)
                {{ " (" . $convenioVigenteAdtInternaCerrada->NOMBRE }} / 
                {{ $convenioVigenteAdtInternaCerrada->FECHA_TERMINO_CONVENIO }}) 
            @endforeach
        </td>
        <td colspan="3">
            {{ $datosAdts['numeroConveniosVencidosAdtsInternasCerradas'] ?? '-' }}
        </td>
        <td colspan="4">
            {{ $datosAdts['numeroConveniosIndeterminadosAdtsInternasCerradas'] ?? '-' }}
        </td>
    </tr>
    <tr><!--22-->
        <th class="text-center table-info"colspan="9" style="background-color: #6be465;">
            5. Usuarios BDT (acumulado {{ \Carbon\Carbon::now()->format('Y') }}: {{ number_format($datosQueSeCapturan['usuariosBdtsAcumulados'], 0, ".", ",") ?? '-' }}) y Plataforma
        </th>
        <td>
        </td>
	    <th class="text-center table-info"colspan="9" style="background-color: #6be465;">
            5. Usuarios (acumulado {{ \Carbon\Carbon::now()->format('Y') }}: {{ number_format($datosQueSeCapturan['usuariosAcumulado'], 0 , ".", ",") ?? '-' }})
        </th>
        <td>
        </td>
        <th class="text-center table-secondary"colspan="9">
            5. Usuarios (acumulado {{ \Carbon\Carbon::now()->format('Y') }}: {{ number_format($datosQueSeCapturan['usuariosAcumuladoC'], 0, ".", ",") ?? '-' }})
        </th>
    </tr>
    <tr><!--23-->
        <td colspan="3" style="background-color: #bffbbc; text-align:center;">
            BDT's ({{ number_format($datosQueSeCapturan['usuariosBdtsRegistraron'], 0, ".", ",") ?? '-' }})
        </td>
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            Registros
        </td>
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            Inscritos
        </td>
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            Constancias
        </td>
        <td>
        </td>
        <td colspan="4" style="background-color: #bffbbc; text-align:center;">
            CT Abiertas
        </td>
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            Meta
        </td>
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            Real
        </td>
        <td style="background-color: #bffbbc; text-align:center;" >
            %
        </td>
        <td>
        </td>
        <td colspan="2">
            BDT ({{ $datosAdts['numeroAdtsCerradasInternas'] ?? '-' }} Cerradas)
        </td>
        <td colspan="3">
            @php
                $meses = ["ene", "feb", "mar", "abr", "may", "jun", 
                        "jul", "ago", "sep", "oct", "nov", "dic"];
                $mes = $meses[date('n') - 1]; // date('n') devuelve 1-12
            @endphp
            ene-{{ $mes }} 2025
        </td>
        <td >
            {{ number_format($datosQueSeCapturan['numeroUsuariosDelAnioBdts'], 0, ".", ",") ?? '-' }}
        </td>
        <td colspan="2">
            Del mes
        </td>
        <td >
            {{ number_format($datosQueSeCapturan['numeroUsuariosDelMesBdts'], 0, ".", ",") ?? '-' }}
        </td>
    <tr><!--24-->
        {{-- Fila inicial con totales --}}
        <td colspan="3" style="background-color: #bffbbc; text-align:center;">
            {{ number_format($datosQueSeCapturan['usuariosBdtsTotales'], 0, ".", ",") ?? '-' }}
        </td>
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            {{ number_format($datosQueSeCapturan['usuariosBdtsRegistrados'], 0, ".", ",") ?? '-' }}
        </td>
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            {{ number_format($datosQueSeCapturan['usuariosBdtsInscritos'], 0, ".", ",") ?? '-' }}
        </td>
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            {{ number_format($datosQueSeCapturan['usuariosBdtsConstancias'], 0, ".", ",") ?? '-' }}
        </td>
        <td>
        </td>
        {{-- Primera iteración del foreach en la misma fila --}}
        @php $i = 0; @endphp
        @foreach($datosAdts['adtsAbiertasInternas'] as $adtAbiertaInterna)
            @php
                $registro = $datosQueSeCapturan['numeroDeUsuariosMetaRealAdts'][$adtAbiertaInterna->NOMBRE] ?? null;
                $idSafe = preg_replace('/[^A-Za-z0-9_-]/', '_', $adtAbiertaInterna->NOMBRE);
            @endphp

            @if($i == 0)
                <td colspan="4" style="background-color: #bffbbc; text-align:center;">{{ $adtAbiertaInterna->NOMBRE }}</td>
                <td colspan="2" style="background-color: #bffbbc; text-align:center;">
                    {{ $registro ? number_format((float) $registro->META, 0, '.', ',') : '' }}
                </td>
                <td colspan="2" style="background-color: #bffbbc; text-align:center;">
                    {{ $registro ? number_format((float) $registro->REAL, 0, '.', ',') : '' }}
                </td>
                <td  style="background-color: #bffbbc; text-align:center;">
                    -
                </td>
                <td>
                </td>
                <td colspan="2">
                    FILIALES
                </td>
                <td colspan="3">
                    ene-{{ $mes }} 2025
                </td>
                <td >
                    {{ number_format($datosQueSeCapturan['numeroUsuariosDelAnioFiliales'], 0, ".", ",") ?? '-' }}
                </td>
                <td colspan="2">
                    Del mes
                </td>
                <td >
                    {{ number_format($datosQueSeCapturan['numeroUsuariosDelMesFiliales'], 0, ".", ",") ?? '-' }}
                </td>
            </tr>
            @else
            <tr><!--22a-->
                {{-- Aquí se repite la estructura de columnas iniciales pero vacías para mantener alineación --}}
                <td colspan="3" style="background-color: #bffbbc; text-align:center;"></td>
                <td colspan="2" style="background-color: #bffbbc; text-align:center;"></td>
                <td colspan="2" style="background-color: #bffbbc; text-align:center;"></td>
                <td colspan="2" style="background-color: #bffbbc; text-align:center;"></td>
                <td>
                </td>
                {{-- Se inserrtan las columnas dinámicas --}}
                <td colspan="4" style="background-color: #bffbbc; text-align:center;">{{ $adtAbiertaInterna->NOMBRE }}</td>
                <td colspan="2" style="background-color: #bffbbc; text-align:center;">
                    {{ $registro ? number_format((float) $registro->META, 0, '.', ',') : '' }}
                </td>
                <td colspan="2" style="background-color: #bffbbc; text-align:center;">
                    {{ $registro ? number_format((float) $registro->REAL, 0, '.', ',') : '' }}
                </td>
                <td style="background-color: #bffbbc; text-align:center;" >
                    -
                </td>
            </tr>
            @endif

            @php $i++; @endphp
        @endforeach
    <tr>
        <td colspan="9" style="background-color: #bffbbc; text-align:center;">
        </td>
        <td>
        </td>
        <td colspan="9" style="background-color: #bffbbc; text-align:center;">
        </td>
        <td>
        </td>
        <td colspan="9" style="background-color: #bffbbc; text-align:center;">
            SANBORNS, SEARS, Global Hitss, Sección Amarilla, TELCEL, Bienestar Social, SCITUM, RED UNO, Guarderías Telmex, INBURSA, TELESITES
        </td>
    </tr>
    <tr>
        <th class="text-center table-info"colspan="9" style="background-color: #6be465;">
            6. Oferta educativa
        </th>
        <td>
        </td>
	    <th class="text-center table-info"colspan="9" style="background-color: #6be465;">
            6. Gasto mensual <input type="text" class="d-inline-block w-auto" id="gastoMensual" name="gastoMensual" data-capturar value="${{ number_format($datosQueSeCapturan['gastoMensual'], 2, '.', ',') ?? '-' }}"> / acumulado 2025 <input type="text" class="d-inline-block w-auto" id="gastoMensualAcumulado" name="gastoMensualAcumulado" data-capturar value="${{ number_format($datosQueSeCapturan['gastoMensualAcumulado'], 2, '.', ',') ?? '-' }}">
        </th>
        <td>
        </td>
	    <th class="text-center table-secondary"colspan="9">
            6. Gasto mensual <input type="text" class="d-inline-block w-auto" id="gastoMensualC" name="gastoMensualC" data-capturar value="${{ number_format($datosQueSeCapturan['gastoMensualC'], 2, '.', ',') ?? '-' }}"> / acumulado 2025 <input type="text" class="d-inline-block w-auto" id="gastoMensualAcumuladoC" name="gastoMensualAcumuladoC" data-capturar value="${{ number_format($datosQueSeCapturan['gastoMensualAcumuladoC'], 2, '.', ',') ?? '-' }}">
        </th>
    </tr>
    <tr><!--25-->
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            Nuevos
        </td>
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            Talleres
        </td>
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            En línea
        </td>
        <td colspan="3" style="background-color: #bffbbc; text-align:center;">
            En desarrollo
        </td>
        <td>
        </td>
	    <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            Renta
        </td>
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            Aseo
        </td>
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            Luz
        </td>
        <td colspan="3" style="background-color: #bffbbc; text-align:center;">
            Vigilancia
        </td>
        <td>
        </td>
	    <td colspan="2">
            Renta
        </td>
        <td colspan="2">
            Aseo
        </td>
        <td colspan="2">
            Luz
        </td>
        <td colspan="3">
            Vigilancia
        </td>
    </tr>
    <tr><!--26-->
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            {{ number_format($datosQueSeCapturan['ofertaEducativaNuevosTalleres'], 0, ".", ",") ?? '-' }}
        </td>
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            {{ number_format($datosQueSeCapturan['ofertaEducativaTalleres'], 0, ".", ",") ?? '-' }}
        </td>
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            {{ number_format($datosQueSeCapturan['ofertaEducativaEnLinea'], 0, ".", ",") ?? '-' }}
        </td>
        <td colspan="3" style="background-color: #bffbbc; text-align:center;">
            {{ number_format($datosQueSeCapturan['ofertaEducativaTalleresEnDesarrollo'], 0, ".", ",") ?? '-' }}
        </td>
        <td>
        </td>
	    <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            ${{ number_format($datosQueSeCapturan['gastoMensualRenta'], 2, '.', ',') ?? '-' }}
        </td>
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            ${{ number_format($datosQueSeCapturan['gastoMensualAseo'], 2, '.', ',') ?? '-' }}
        </td>
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            ${{ number_format($datosQueSeCapturan['gastoMensualLuz'], 2, '.', ',') ?? '-' }}
        </td>
        <td colspan="3" style="background-color: #bffbbc; text-align:center;">
            ${{ number_format($datosQueSeCapturan['gastoMensualVigilancia'], 2, '.', ',') ?? '-' }}
        </td>
        <td>
        </td>
	    <td colspan="2">
            ${{ number_format($datosQueSeCapturan['gastoMensualRentaC'], 2, '.', ',') ?? '-' }}
        </td>
        <td colspan="2">
            ${{ number_format($datosQueSeCapturan['gastoMensualAseoC'], 2, '.', ',') ?? '-' }}
        </td>
        <td colspan="2">
            ${{ number_format($datosQueSeCapturan['gastoMensualLuzC'], 2, '.', ',') ?? '-' }}
        </td>
        <td colspan="3">
            ${{ number_format($datosQueSeCapturan['gastoMensualVigilanciaC'], 2, '.', ',') ?? '-' }}
        </td>
    </tr>
    <tr><!--27-->
        <th class="text-center table-info" colspan="9" style="background-color: #6be465;">
            7. Nuevas Solicitudes 2025 ({{ number_format($datosQueSeCapturan['solicitudesRecibidas'], 0, ".", ",") ?? '-' }} recibidas)
        </th>
        <td>
        </td>
	    <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            Agua Potable
        </td>
        <td colspan="4" style="background-color: #bffbbc; text-align:center;">
            Nòmina Operaciòn
        </td>
        <td colspan="3" style="background-color: #bffbbc; text-align:center;">
            Nòmina Gerencia
        </td>
        <td>
        </td>
	    <td colspan="3">
            Agua Potable
        </td>
        <td colspan="3">
            Nómina operación
        </td>
        <td colspan="3">
            Nómina total
        </td>
    </tr>
    <tr><!--28-->
        <td colspan="7" style="background-color: #bffbbc;"> 
            Solicitud BDT o donación de equipos
        </td>
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            {{ number_format($datosQueSeCapturan['solicitudBdt'],0 , ".", ",") ?? '-' }}
        </td>
        <td>
        </td>
	    <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            ${{ number_format($datosQueSeCapturan['gastoAguaPotable'], 2, '.', ',') ?? '-' }}
        </td>
        <td colspan="4" style="background-color: #bffbbc; text-align:center;">
            ${{ number_format($datosQueSeCapturan['gastoNominaOperacion'], 2, '.', ',') ?? '-' }}
        </td>
        <td colspan="3" style="background-color: #bffbbc; text-align:center;">
            ${{ number_format($datosQueSeCapturan['gastoNominaGerencia'], 2, '.', ',') ?? '-' }}
        </td>
        <td>
        </td>
	    <td colspan="3">
            ${{ number_format($datosQueSeCapturan['gastoAguaPotableC'], 2, '.', ',') ?? '-' }}
        </td>
        <td colspan="3">
            ${{ number_format($datosQueSeCapturan['gastoNominaOperacionC'], 2, '.', ',') ?? '-' }}
        </td>
        <td colspan="3">
            ${{ number_format($datosQueSeCapturan['gastoNominaGerenciaC'], 2, '.', ',') ?? '-' }}
        </td>
    </tr>
    <tr><!--29-->
        <td colspan="7" style="background-color: #bffbbc;"> 
            Solicitud BDT de reequipamiento
        </td>
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            {{ number_format($datosQueSeCapturan['solicitudReequipamiento'], 0, ".", ",") ?? '-' }}
        </td>
        <td>
        </td>
	    <th class="text-center table-info" style="background-color: #bffbbc; text-align:center;">
            Mantenimientos:
        </th>
        <th class="text-center table-info"colspan="2" style="background-color: #bffbbc; text-align:center;">
            Total:
        </th>
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            ${{ number_format($datosQueSeCapturan['gastoMantenimientosTotal'], 2, '.', ',') ?? '-' }}
        </td>
        <th class="text-center table-info"colspan="2" style="background-color: #bffbbc; text-align:center;">
            Ejercido:
        </th>
        <th colspan="2" style="background-color: #bffbbc; text-align:center;"><!-- eliminé table-info (color celda)-->
            ${{ number_format($datosQueSeCapturan['gastoMantenimientosEjercido'], 2, '.', ',') ?? '-' }}
        </th>
        <td>
        </td>
	    <th class="text-center table-secondary">
            Mantenimientos:
        </th>
        <th class="text-center table-secondary"colspan="2">
            Total:
        </th>
        <td colspan="2">
            ${{ number_format($datosQueSeCapturan['gastoMantenimientosTotalC'], 2, '.', ',') ?? '-' }}
        </td>
        <th class="text-center table-secondary"colspan="2">
            Ejercido:
        </th>
        <td colspan="2">
            ${{ number_format($datosQueSeCapturan['gastoMantenimientosEjercidoC'], 2, '.', ',') ?? '-' }}
        </td>
    </tr>
    <tr><!--30-->
        <td colspan="7" style="background-color: #bffbbc;"> 
            Retiro de equipos
        </td>
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            {{ number_format($datosQueSeCapturan['solicitudRetiro'], 0, ".", ",") ?? '-' }}
        </td>
        <td>
        </td>
	    <th class="bg-secondary text-white text-center"colspan="9">
            Solicitudes relevantes del mes:
        </th>
        <td>
        </td>
	    <th class="bg-secondary text-white text-center"colspan="9">
            Solicitudes relevantes del mes:
        </th>
    </tr>
    <tr><!--31-->
        <td colspan="7" style="background-color: #bffbbc;"> 
            Otros (visitas a museo, acuario, etc.)
        </td>
        <td colspan="2" style="background-color: #bffbbc; text-align:center;">
            {{ number_format($datosQueSeCapturan['solicitudOtros'], 0, ".", ",") ?? '-' }}
        </td>
        <td>
        </td>
	    <th colspan="9">
            -
        </th>
        <td>
        </td>
	    <th colspan="9">
            -
        </th>
    </tr>
</table>