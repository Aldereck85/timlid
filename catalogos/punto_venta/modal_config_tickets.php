<div class="modal fade modal-right" id="modal_config_tickets" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title w-100" id="myModalLabel">Configuración</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form-config-ticket">
          <div class="form-group">
            <label for="">Impresora</label><br>
            <div class="col">
              <label for="txtPrinterNameUpdate">Nombre de la impresora:</label>
              <input type="text" class="form-control alphaNumericDotAlter-only" id="txtPrinterNameUpdate" required>
              <small class="text-danger">La impresora debe tener el mismo nombre como fue registrada en el sistema operativo.</small>
              <div class="invalid-feedback" id="invalid-printerNameUpdate">La impresora debe de tener un nombre.</div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer justify-content-center">
        <button type="button" class="btnesp first espCancelar btnCancelarActualizacion mx-2" data-dismiss="modal"
          id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
        <button type="button" class="btn-custom btn-custom--blue espAgregar mx-2" name="btnConfigTicket" id="btnConfigTicket"><span
          class="ajusteProyecto">Guardar</span></button>
      </div>
    </div>
    
  </div>
</div>