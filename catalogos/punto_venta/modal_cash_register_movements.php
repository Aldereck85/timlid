<!-- Begin modal add cash register movements product -->
<div class="modal fade" id="add_cash_register_movements" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <img src="../../img/punto_venta/movimiento_caja_blanco.svg" width="40">
        <h4 style="padding-left: 2rem;" class="modal-title w-100" id="myModalLabel">Movimentos en caja</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form-data-regsiter-movements">
          
          <input type="hidden" name="txtCurrentBalanceHide" id="txtCurrentBalanceHide">
          <h4 class="text-center">Saldo actual: $<span id="txtCurrentBalance"></span></h4>
          
          <div class="form-group row">
            <label for="cmbMovementType">Tipo de movimiento:*</label>
            <select name="cmbMovementType" id="cmbMovementType" required>
              <option data-placeholder='true' selected></option>
              <option value="1">Retiro</option>
              <option value="2">Depósito</option>
            </select>
            <div class="invalid-feedback" id="invalid-MovementType">El movimiento debe tener un tipo de movimiento.</div>
          </div>
          <div class="form-group row">
            <label for="txtMovementAmount">Monto:*</label>
            <input class="form-control" type="text" name="txtMovementAmount" id="txtMovementAmount" required>
            <div class="invalid-feedback" id="invalid-MovementAmount">El movimiento debe tener un monto.</div>
          </div>
          <div class="form-group row">
            <label for="txaComments">Comentarios:</label>
            <textarea class="form-control" name="txaComments" id="txaComments" cols="30" rows="3"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer justify-content-center">
        <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal"
          id="btnCancelarRegistroCaja2"><span class="ajusteProyecto">Cancelar</span></button>
        <button type="button" class="btn-custom btn-custom--blue espAgregar float-right" name="btnAgregar" id="btnSaveMovementCashRegister" on><span
          class="ajusteProyecto">Guardar</span></button>
      </div>
    </div>
  </div>
</div>