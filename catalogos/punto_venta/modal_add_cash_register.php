<div class="modal fade" id="add_cash_register" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <img src="../../img/punto_venta/agregar_caja_blanco.svg" width="50">
        <h4 style="padding-left: 2rem;" class="modal-title w-100" id="myModalLabel">Agregar Caja</h4>
        
      </div>
      <div class="modal-body">
        <form id="form-data-cash-register" autocomplete="off">
          <div class="form-group row">
            <label for="cmbSucursal">Sucursal:*</label>
            <select name="cmbSucursal" id="cmbSucursal"></select>
            <div class="invalid-feedback" id="invalid-branchOfficeAccount" required autocomplete="off">La caja debe tener una sucursal.</div>
          </div>
          <div class="form-group row">
            <label for="txtNameCashRegister">Nombre de la caja:*</label>
            <input class="form-control" type="text" name="txtNameCashRegister" id="txtNameCashRegister" required autocomplete="off">
            <div class="invalid-feedback" id="invalid-nameAccount">La caja debe tener un nombre.</div>
            <div class="invalid-feedback" id="invalid-nameAccountExist">El nombre de la caja ya existe.</div>
          </div>
          
          <div class="form-group row">
            <label for="cmbMoneda">Moneda:*</label>
            <select name="cmbMoneda" id="cmbMoneda"></select>
            <div class="invalid-feedback" id="invalid-moneyAccount" required autocomplete="off">La caja debe de tener una moneda.</div>
          </div>
          <div class="form-group row">
            <label for="txtSaldoInicial">Saldo inicial:*</label>
            <input type="text" name="txtSaldoInicial" id="txtSaldoInicial" class="form-control" required autocomplete="off">
            <div class="invalid-feedback" id="invalid-initialBalanceAccount">La caja debe tener un saldo inicial.</div>
          </div>
          <!-- <div class="form-group row">
            <label for="txtPrinterName">Nombre de la impresora:</label>
              <input type="text" class="form-control alphaNumericDotAlter-only" id="txtPrinterName" required>
              <div class="invalid-feedback" id="invalid-printerName">La impresora debe de tener un nombre.</div>
          </div> -->
          <div class="form-group row">
            <label for="txtPrinterName">PIN de administrador:*</label>
              <input type="password" class="form-control numeric-only" id="txtSavePassAdmin" required autocomplete="new-password" maxlength="4" minlength="4">
              <small>PIN de 4 dígitos</small>
              <div class="invalid-feedback" id="invalid-savePassAdmin">La caja debe de tener una contraseña de administrador.</div>
          </div>
          <div class="row">
            <label for="txtDatosObligatoriosCrearCaja">* Datos obligatorios.</label>
          </div>
        </form>
      </div>
      <div class="modal-footer justify-content-center">
        <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal"
          id="btnCancelarRegistroCaja2"><span class="ajusteProyecto">Cancelar</span></button>
        <button type="button" class="btn-custom btn-custom--blue espAgregar float-right" name="btnAgregar" id="btnSaveDataCashRegister"><span
          class="ajusteProyecto">Registar</span></button>
      </div>
    </div>
  </div>
</div>