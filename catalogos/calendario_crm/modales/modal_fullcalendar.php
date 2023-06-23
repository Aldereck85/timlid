<div class="modal" tabindex="-1" role="dialog" id="actividades_fullcalendar">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Eventos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">x</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="nueva-actividad">
                    <div class="row">
                        <div class="col-lg-12">
                            <label for="puesto">Añade un título:</label>
                            <input type="text" class="form-control alphaNumeric-only" name="titulo" id="titulo" maxlength="50" placeholder="Añade un título">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-12">
                            <label>Contactos / Clientes</label>
                            <select id="selectContactos" name="contactos">
                                <option value="0">Selecciona un contacto</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-12">
                            <label>Actividades</label>
                            <select id="selectActividades" name="actividades" onchange="selectActividad(this);">
                                <option value="0">Selecciona una actividad</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="custom-radios mb-4">
                                <div>
                                    <input type="radio" id="color-1" name="color-actividad" value="#d63d22">
                                    <label for="color-1" title="Tomate">
                                        <span>
                                            <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/242518/check-icn.svg" alt="Checked Icon" />
                                        </span>
                                    </label>
                                </div>
                                <div>
                                    <input type="radio" id="color-2" name="color-actividad" value="#ea899a">
                                    <label for="color-2" title="Rosa chicle">
                                        <span>
                                            <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/242518/check-icn.svg" alt="Checked Icon" />
                                        </span>
                                    </label>
                                </div>
                                <div>
                                    <input type="radio" id="color-3" name="color-actividad" value="#de6749">
                                    <label for="color-3" title="Mandarina">
                                        <span>
                                            <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/242518/check-icn.svg" alt="Checked Icon" />
                                        </span>
                                    </label>
                                </div>
                                <div>
                                    <input type="radio" id="color-4" name="color-actividad" value="#ffd562">
                                    <label for="color-4" title="Amarillo huevo">
                                        <span>
                                            <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/242518/check-icn.svg" alt="Checked Icon" />
                                        </span>
                                    </label>
                                </div>
                                <div>
                                    <input type="radio" id="color-5" name="color-actividad" value="#287233">
                                    <label for="color-5" title="Verder esmeralda">
                                        <span>
                                            <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/242518/check-icn.svg" alt="Checked Icon" />
                                        </span>
                                    </label>
                                </div>
                                <div>
                                    <input type="radio" id="color-6" name="color-actividad" value="#2f4538">
                                    <label for="color-6" title="Verde musgo">
                                        <span>
                                            <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/242518/check-icn.svg" alt="Checked Icon" />
                                        </span>
                                    </label>
                                </div>
                                <div>
                                    <input type="radio" id="color-7" name="color-actividad" value="#5dc1b9">
                                    <label for="color-7" title="Azul turquesa">
                                        <span>
                                            <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/242518/check-icn.svg" alt="Checked Icon" />
                                        </span>
                                    </label>
                                </div>
                                <div>
                                    <input type="radio" id="color-8" name="color-actividad" value="#6040a0">
                                    <label for="color-8" title="Azul arándano">
                                        <span>
                                            <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/242518/check-icn.svg" alt="Checked Icon" />
                                        </span>
                                    </label>
                                </div>
                                <div>
                                    <input type="radio" id="color-9" name="color-actividad" value="#b57edc">
                                    <label for="color-9" title="Lavanda">
                                        <span>
                                            <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/242518/check-icn.svg" alt="Checked Icon" />
                                        </span>
                                    </label>
                                </div>
                                <div>
                                    <input type="radio" id="color-10" name="color-actividad" value="#572364">
                                    <label for="color-10" title="Morado intenso">
                                        <span>
                                            <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/242518/check-icn.svg" alt="Checked Icon" />
                                        </span>
                                    </label>
                                </div>
                                <div>
                                    <input type="radio" id="color-11" name="color-actividad" value="#9d9d97">
                                    <label for="color-11" title="Grafito">
                                        <span>
                                            <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/242518/check-icn.svg" alt="Checked Icon" />
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="actividadCorreos">
                        <div class="col-lg-6">
                            <label class="d-block">Fecha</label>
                            <input type="date" style="position: absolute; visibility: hidden;" name="fecha" id="fecha_correo-2">
                            <label id="label-date-correo" for="fecha_correo-2" class="input-label-date">dd / mm / aaaa</label>
                        </div>
                        <div class="col-lg-6">
                            <label id="label_hora_tarea">Hora</label>
                            <input type="time" class="form-control alphaNumeric-only" name="hora_tarea" id="hora_correo">
                        </div>
                    </div>

                    <div class="row" id="actividadTareas">
                        <div class="col-lg-4">
                            <label class="d-block">Fecha</label>
                            <input type="date" class="form-control" name="fecha" id="fecha_tarea-2" style="position: absolute; visibility: hidden;">
                            <label id="label-date-tarea" for="fecha_tarea-2" class="input-label-date">dd / mm / aaaa</label>
                        </div>
                        <div class="col-lg-4">
                            <label id="label_hora_tarea">Hora</label>
                            <input type="time" class="form-control alphaNumeric-only" name="hora_tarea" id="hora_tarea">
                        </div>
                        <div class="col-lg-4">
                            <label>Prioridad</label>
                            <select id="selectPrioridadTareas" name="selectPrioridadTarea">
                                <option value="0">Seleccione una prioridad</option>
                            </select>
                        </div>
                        <div class="col-lg-12 mt-2">
                            <label><input type="checkbox" id="tarea_todo_dia" class="mr-1">Todo el día</label>
                        </div>
                    </div>

                    <div class="row" id="actividadLlamadas">
                        <div class="col-lg-4">
                            <label>Resultado de la llamada</label>
                            <select id="selectEstatusLlamada">
                                <option value="0">Selecciona un resultado</option>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label class="d-block">Fecha</label>
                            <input type="date" style="position: absolute; visibility: hidden;" name="fecha" id="fecha_llamada-2">
                            <label id="label-date-llamada" for="fecha_llamada-2" class="input-label-date">dd / mm / aaaa</label>
                        </div>
                        <div class="col-lg-4">
                            <label id="label_hora_tarea">Hora</label>
                            <input type="time" class="form-control alphaNumeric-only" name="hora_tarea" id="hora_llamada">
                        </div>
                    </div>

                    <div class="row" id="actividadReuniones">
                        <div class="col-lg-12">
                            <label>Integrantes</label>
                            <select name="empleado" id="empleadoModal" multiple>
                                <option value="0">Selecciona un empleado</option>
                            </select>
                        </div>
                        <div class="col-lg-12 mt-2">
                            <label for="">Invitados</label>
                            <input type="text" name="" id="invitados" class="form-control alphaNumeric-only">
                        </div>
                        <div class="col-lg-12 mt-2">
                            <label>Lugar</label>
                            <input type="text" class="form-control alphaNumeric-only" name="lugar" id="lugar_reunion">
                        </div>
                        <div class="col-lg-4 mt-2">
                            <label class="d-block">Fecha</label>
                            <input type="date" style="position: absolute; visibility: hidden;" name="fecha_inicio" id="fecha_reunion-2">
                            <label id="label-date-reunion" for="fecha_reunion-2" class="input-label-date">dd / mm / aaaa</label>
                        </div>
                        <div class="col-lg-4 mt-2">
                            <label>Hora de inicio</label>
                            <input type="time" class="form-control alphaNumeric-only" name="hora" id="hora_inicio_reunion">
                        </div>
                        <div class="col-lg-4 mt-2">
                            <label>Hora de finalización</label>
                            <input type="time" class="form-control alphaNumeric-only" name="hora" id="hora_final_reunion" value="<?php echo $hoy ?>">
                        </div>
                        <div class="col-lg-12 mt-2">
                            <label><input type="checkbox" id="cbox1" class="mr-1">Todo el día</label>
                        </div>
                    </div>

                    <div class="col-lg-12" style="margin-left: -10px;"><br>
                        <label>Descripción</label>
                        <textarea type="text" class="form-control alphaNumeric-only p-1" rows="2" maxlength="250" name="descripcion" id="descripcion" required placeholder="Añade una descripción"></textarea>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btnesp first espEliminar" onclick="deleteEvent(this.value);" id="actividad_id">Eliminar
                </button>
                <button type="button" class="btnesp first espAgregar" onclick="crearActividad();" id="guardar_actividad">Guardar <span></span></button>
                <button type="button" class="btnesp first espAgregar" onclick="modificarActividad(this.value);" id="editar_actividad_id">Editar <span></span></button>
                <input type="text" id="fecha" hidden>
            </div>
        </div>
    </div>