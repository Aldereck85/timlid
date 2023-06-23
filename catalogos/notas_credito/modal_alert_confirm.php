  <!-- Modal alert -->
  <div class="modal fade" id="mdlsavealert" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 id="titlemdl" class="modal-title" id="exampleModalLabel">¿Cancelar Nota de Crédito?</h5>
        </div>
        <div id="msj" class="modal-body"><center><h4>¿Deseas cancelar la nota de crédito? </h4></center></div>
          <div class= "col-lg">
                  <label for="usr">Motivo de Cancelación:*</label>
                  <select name="cmbMotivo" class="form-select" id="cmbMotivo" aria-label="Default select example">
                    <option selected disabled value="0">Selecciona</option>
                    <option value="01">Comprobante emitido con errores con relación.</option>
                    <option value="02">Comprobante emitido con errores sin relación.</option>
                    <option value="03">No se llevó a cabo la operación.</option>
                    <option value="04">Operación nominativa relacionada en la factura global.</option>
                  </select>
                  <div class="invalid-feedback" id="invalid-cmbFMPago">Seleccione un motivo.</div>
          </div>
          <span id="idcliente"></span>
          <div id="relacion" class= "col-lg">
          </div>
        <div class="modal-footer">
          <button type="button" class="btn-custom btn-custom--border-blue btnCancelarActualizacion" data-dismiss="modal">Cancelar</button>
          <a disabled class="btn-custom btn-custom--blue" id="btnAcepCambios">Aceptar</a>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="mdlnotifi" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 id="titlemdl" class="modal-title" id="exampleModalLabel">Registro actualizado</h5>
        </div>
        <div id="msj" class="modal-body"><center><h4>Se han actualizado los cambios </h4></center></div>
        <div class="modal-footer">
          <a disabled class="btn-custom btn-custom--blue" href="" id="btnAcepCambios">Aceptar</a>
        </div>
      </div>
    </div>
  </div>