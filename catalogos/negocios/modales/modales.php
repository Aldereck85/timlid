<!-- Modal -->
<div class="modal fade" id="AgregarEtapa" tabindex="-1" role="dialog" aria-labelledby="AgregarEtapaLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="AgregarEtapaLabel">Agregar Etapa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <i class="fas fa-times text-white"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="formAgregarEtapa">
                    <div class="row form-group">
                        <div class="col">
                            <label for="etapa">Nueva Etapa:</label>
                            <input type="text" class="form-control alphaNumeric-only" name="etapa" id="etapa" maxlength="250" placeholder="Ej. En revisión">
                            <div id="etapa-invalid" class="invalid-feedback">
                                El nombre de la etapa es requerido.
                            </div>
                        </div>
                        <!-- <div class="col-lg-6">
                            <label for="orden">Orden:</label>
                            <input type="number" class="form-control alphaNumeric-only" name="orden" id="orden" maxlength="20" placeholder="Ej. 1">
                        </div> -->
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-custom btn-custom--border-blue" data-dismiss="modal">
                    Cerrar
                </button>
                <button type="button" class="btn-custom btn-custom--blue" onclick="agregarEtapa(event)">Agregar
                </button>
            </div>
        </div>
    </div>
</div>