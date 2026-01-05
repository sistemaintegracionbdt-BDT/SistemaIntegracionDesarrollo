<body>    
    <div class="modal fade" id="modalGeneralUsuarios" tabindex="-1" role="dialog" aria-labelledby="label" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="label"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formularioModalUsuarios" method="POST">
                        @csrf
                        <!-- Input escondido para enviar la clave de usuario seleccionada al modificar usuario -->
                        <input type="hidden" id="nombre_clave_usuario" name="nombre_clave_usuario">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="nombre">Nombre</label>
                            </div>
                            <div class="form-group col-md-8 campo-wrapper" data-campo="#nombre">
                                <input type="text" class="form-control" id="nombre" name="nombre">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="correo">Correo</label>
                            </div>
                            <div class="form-group col-md-8 campo-wrapper" data-campo="#correo">
                                <input type="text" class="form-control" id="correo" name="correo">
                            </div>
                        </div>
                        <div id="grupo_campos_telegram" class="form-row">
                            <div class="form-group col-md-4">
                                <label for="telegram">
                                    Identificador Telegram
                                    <a href="https://drive.google.com/file/d/1PBcIJ9_hXiOk_qrrkrcxLkcK6km9CjbC/view?usp=sharing" target="_blank" class="ml-2"> 
                                        <i class="bi bi-question-circle" style="font-size: 1.2rem; color:#007bff;"></i> 
                                    </a>
                                </label>
                            </div>
                            <div class="form-group col-md-8 campo-wrapper" data-campo="#telegram">
                                <input type="tel" class="form-control" id="telegram" name="telegram" maxlength="15">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="contrasena">Contraseña</label>
                            </div>
                            <div class="form-group col-md-8 campo-wrapper" data-campo="#contrasena">
                                <input type="text" class="form-control" id="contrasena" name="contrasena" autocomplete="off" minlength="8" maxlength="8" data-toggle="tooltip" data-placement="left" title="Registre una contraseña de mínimo 8 caracteres y con sólo caracteres alfanuméricos.">
                            </div>
                        </div>  
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="rol">Rol</label>
                            </div>
                            <div id="opcionesConCargos" class="form-group col-md-8">
                                <select class="form-control" id="rolConCargos" name="rolConCargos[]" multiple size="4" data-toggle="tooltip" data-placement="left" data-html="true" title="Deje presionado Ctrl para seleccionar varias opciones<br><br>Restricción: No puede seleccionar un cargo rojo y uno verde simultáneamente">
                                    <option value="" selected disabled>
                                    </option>
                                    @foreach($roles as $rol)
                                        @php
                                            $color = 'black';
                                            if ($rol->NOMBRE == 'coordinador') {
                                                $color = 'red';
                                            } elseif ($rol->NOMBRE == 'director') {
                                                $color = 'green';
                                            } elseif ($rol->NOMBRE == 'tutor') {
                                                $color = 'green';
                                            }
                                        @endphp
                                    <option value="{{ $rol->NOMBRE }}" style="color: {{ $color }};">
                                        {{ $rol->NOMBRE }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div id="opcionesSinCargos" class="form-group col-md-8">
                                <select class="form-control" id="rolSinCargos" name="rolSinCargos[]" multiple size="4" data-toggle="tooltip" data-placement="left" title="Deje presionado Ctrl para seleccionar varias opciones">
                                    <option value="" selected disabled>
                                    </option>
                                    @php
                                        $cargosExcluidos = ['coordinador', 'director'];
                                    @endphp
                                    @foreach($roles as $rol)
                                        @if(!in_array($rol->NOMBRE, $cargosExcluidos))
                                            <option value="{{ $rol->NOMBRE }}">{{ $rol->NOMBRE }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div id="grupo_campos_casa_director" class="form-row">
                            <div class="form-group col-md-4">
                                <label for="casa_director">Casa</label>
                            </div>
                            <div class="form-group col-md-8">
                                <select class="form-control" id="casa_director" name="casa_director">
                                    <option value="" selected disabled>
                                    </option>
                                    @foreach($casas as $casa)
                                        <option value="{{ $casa->NOMBRE }}">{{ $casa->NOMBRE }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-row mt-4">
                            <div class="col d-flex align-items-center">
                                <div class="d-flex align-items-center flex-grow-1">
                                    <div id="advertencia" name="advertencia" class="mr-3 btn btn-danger">
                                        ELIMINAR UN USUARIO, NO SE PUEDE REVERTIR
                                    </div>
                                    <div id="lineaAdvertencia" name="lineaAdvertencia" class="flex-grow-1 linea-advertencia-gruesa"></div>
                                </div>
                                <div class="ml-3 d-flex">
                                    <button id="botonRegistrar" name="botonRegistrar" type="submit" class="btn btn-outline-success mr-2">Registrar</button>
                                    <button id="botonModificar" name="botonModificar" type="submit" class="btn btn-outline-warning mr-2">Modificar</button>
                                    <button id="botonEliminar" name="botonEliminar" type="submit" class="btn btn-outline-danger mr-2">Eliminar</button>
                                    <button id="botonCancelar" type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                </div>
                            </div>
                        </div>      
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

