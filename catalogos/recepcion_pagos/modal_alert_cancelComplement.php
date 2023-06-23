  <!-- Modal alert -->
  <div class="modal fade" id="mdlCancelComplement" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 id="titlemdl" class="modal-title" id="exampleModalLabel">¿Guardar Cambios?</h5>
        </div>
        <div id="msj" class="modal-body"><center><h4>¿Deseas cancelar el complemento de pago? </h4></center></div>
        <div class= "col-lg">
          <label for="usr">Motivo de cancelación:*</label>
          <select name="cmbMotivoCancela" id="cmbMotivoCancela">
            <option disabled selected value="f">Selecciona un motivo</option>
            <option value="01">Comprobante emitido con errores con relación.</option>
            <option value="02">Comprobante emitido con errores sin relación.</option>
            <option value="03">No se llevó a cabo la operación</option>
            <option value="04">Operación nominativa relacionada en la factura global.</option>
          </select> 
        </div>     
          <br>
          <div id = "docRelacion"></div>  
        <div class="modal-footer">
          <button type="button" class="btn-custom btn-custom--border-blue" id="btnCancelarCancelarComplemento" data-dismiss="modal">Cerrar</button>
          <button type="button" class="btn-custom btn-custom--blue float-right" id="btnAcepCancelarComplemento" data-dismiss="modal">Aceptar</a>
        </div>
      </div>
    </div>
  </div>