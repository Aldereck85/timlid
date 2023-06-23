<?php
$mDate = new DateTime();
$hoy = $mDate->format("H:i:s");
?>

<style type="text/css">
    .color-picker-sm {
        cursor: pointer;
    }
</style>
<div class="modal" tabindex="-1" role="dialog" id="addNote">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title-nota">Nueva Nota</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">x</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row form-group">
                        <div class="col-lg-12 py-2">
                            <label for="nota">Nota:*</label>
                            <input id="nota_id" hidden="true">
                            <textarea type="text" class="form-control alphaNumeric-only p-1" rows="3" maxlength="250" name="nota" id="nota" required placeholder="Ej. Enviar correo electrónico"></textarea>
                            <label>Máximo de caracteres: <span id="contador"></span> restantes</label>

                        </div>
                        <div class="col-lg-12 py-2 d-flex justify-content-between">
                            <div class="col-lg-12 py-2 d-flex justify-content-between">
                                <label for="color">Color:*</label>
                                <label class="text-center">
                                    <div class="color-picker" style="background-color: #98DDCA;"></div>
                                    <input type="radio" name="color" value="#98DDCA" required>
                                </label>
                                <label class="text-center">
                                    <div class="color-picker" style="background-color: #D5ECC2;"></div>
                                    <input type="radio" name="color" value="#D5ECC2" required>
                                </label>
                                <label class="text-center">
                                    <div class="color-picker" style="background-color: #FFD3B4;"></div>
                                    <input type="radio" name="color" value="#FFD3B4" required>
                                </label>
                                <label class="text-center">
                                    <div class="color-picker" style="background-color: #FFAAA7;"></div>
                                    <input type="radio" name="color" value="#FFAAA7" required>
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btnesp first espAgregar" id="btnActualizarNotas">Actualizar</button>
                <button type="button" class="btnesp first espAgregar" id="btnGuardarNotas">Guardar</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal Editar Contacto -->
<div class="modal" tabindex="-1" role="dialog" id="editarContactoModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Contacto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">x</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formEditContact">
                    <div class="row form-group">
                        <div class="col-lg-4">
                            <label for="empresa">Empresa:*</label>
                            <input type="text" class="form-control alphaNumeric-only" maxlength="100" name="empresa" id="empresaModal" required placeholder="Ej. GH Medic">
                            <div class="invalid-feedback" id="invalid-empresa">El campo empresa es requerido.</div>
                        </div>
                        <div class="col-lg-4">
                            <label for="propietario">Vendedor:</label>
                            <div class="row">
                                <div class="col-lg-12 input-group">
                                    <select id="propietarioModalEditar">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <label for="usr">Medio de contacto:</label>
                            <div class="row">
                                <!-- CREAR PARA MEDIOS O CAMPAÑAS -->
                                <div class="col-lg-12 input-group">
                                    <select name="campania" id="campaniaModalEditar">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-lg-4">
                            <label for="nombre">Nombre:*</label>
                            <input type="text" class="form-control alphaNumeric-only" maxlength="50" name="nombre" id="nombreModal" placeholder="Ej. John" required>
                            <div class="invalid-feedback" id="invalid-nombre">El campo nombre es requerido.</div>
                        </div>
                        <div class="col-lg-4">
                            <label for="nombre">Apellido del contacto:</label>
                            <input type="text" class="form-control alphaNumeric-only" maxlength="50" name="apellido" id="apellidoModal" placeholder="Ej. López Pérez">
                        </div>
                        <div class="col-lg-4">
                            <label for="puesto">Puesto:</label>
                            <input type="text" class="form-control alphaNumeric-only" name="puesto" id="puestoModal" maxlength="50" placeholder="Ej. Gerente de ventas">
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-lg-4">
                            <label for="email">Email:</label>
                            <input type="text" class="form-control alphaNumeric-only" maxlength="100" name="email" id="emailModal" placeholder="Ej. ejemplo@dominio.com">
                        </div>
                        <div class="col-lg-4">
                            <label for="telefono">Teléfono:</label>
                            <input type="text" class="form-control alphaNumeric-only" name="telefono" id="telefonoModal" maxlength="10" placeholder="Ej. 33 3333 3333">
                        </div>
                        <div class="col-lg-4">
                            <label for="celular">Celular:*</label>
                            <input type="text" class="form-control alphaNumeric-only" name="celular" id="celularModal" maxlength="10" placeholder="Ej. 33 3333 3333">
                            <div class="invalid-feedback" id="invalid-celular">El campo celular es requerido.</div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-lg-4">
                            <label for="sitioWebModal">Sitio web:</label>
                            <input type="text" class="form-control alphaNumeric-only" name="sitioWebModal" id="sitioWebModal" placeholder="www.google.com">
                        </div>
                        <div class="col-lg-4">
                            <label for="direccionModal">Dirección:</label>
                            <input type="text" class="form-control alphaNumeric-only" name="direccionModal" id="direccionModal" placeholder="Calzada del ejercito 169">
                        </div>
                        <div class="col-lg-4">
                            <label for="aniversarioModal">Fecha aniversario:</label>
                            <input type="date" class="form-control" name="aniversarioModal" id="aniversarioModal">
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-lg-4">
                            <label for="usr">Pais:</label>
                            <select name="pais" id="paisModalEditar">
                                <option data-placeholder="true"></option>
                                <?php foreach ($paises as $pais) { ?>
                                    <option value="<?= $pais['PKPais'] ?>"><?= $pais['Pais'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label for="usr">Estado:</label>
                            <select name="estado" id="estadoModalEditar">
                                <option data-placeholder="true"></option>
                                <?php for ($i = 0; $i < count($estados); $i++) { ?>
                                    <option value="<?= $estados[$i]->PKEstado ?>"><?= $estados[$i]->Estado ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="row form-group mt-4">
                        <div class="col-lg-12">
                            <label for="">* Campos requeridos</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btnesp first espAgregar" id="btnEditarContacto">Guardar</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>