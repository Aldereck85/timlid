<!-- Begin modal product checker -->
<div class="modal fade" id="opening_cash_register" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-modal="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">  
        <img  src="../../img/punto_venta/apertura_caja.svg" alt="" width="40">
        <h4 style="padding-left: 2rem;" class="modal-title w-100" id="myModalLabel">Apertura de caja</h4>
        
      </div>
      <div class="modal-body">
        <div class="form-group row">
          <div class="col-lg-12 text-center">
            <label for=""><h3>Selección de caja:</h3></label>
            
          </div>
          
        </div>
        <form id="form-select-cash-register" autocomplete="off">

            <div class="justify-content-center" >
                <div class="form-group">
                    <label for="">Sucursal:</label>
                    <select name="cmb_branchOffice" id="cmb_branchOffice" required autocomplete="off"></select>
                </div>
            </div>

            <div class="justify-content-center no-visible" id="div_cmbCashRegister">
                <div class="form-group"> 
                    <label for="">Caja:</label>
                    <select name="cmb_cash_register" id="cmb_cash_register" required autocomplete="off"></select>
                    <div class="invalid-feedback" id="invalid-selectCashRegister">Se debe selecciona una caja.</div> 
                </div>
            </div>

            <div class="justify-content-center no-visible" id="div_cmbUserType">
                <div class="form-group">
                    <label for="cmbTipoUsuario">Tipo de usuario:</label>
                    <select name="cmbTipoUsuario" id="cmbTipoUsuario" required autocomplete="off">
                        <option value="" disabled selected>Seleccione un tipo de usuario...</option>
                        <option value="0">Cajero</option>
                        <option value="1">Administrador</option>
                    </select>
                    <div class="invalid-feedback" id="invalid-user_type">Se debe selecciona un tipo de usuario.</div> 
                </div>
            </div>
            <div class="justify-content-center no-visible" id="div_cmbEmployer">
                <div class="form-group">
                    <label for="cmbEmpleado">Cajeros:</label>
                    <select name="cmbEmpleado" id="cmbEmpleado" required></select>
                    <div class="invalid-feedback" id="invalid-employer_cash">Se debe selecciona un cajero.</div> 
                </div>
            </div>
            <div class="justify-content-center no-visible" id="div_passAdmin">
                <div class="form-group">
                    <label for="txtPassAdmin">Contraseña</label>
                    <input class="form-control numeric-only" type="password" name="txtPassAdmin" id="txtPassAdmin" autocomplete="new-password"  maxlength="4" minlength="4">
                    <div class="invalid-feedback" id="invalid-passAdmin">Debe ingresar la contraseña.</div> 
                </div>
            </div>
          
        </form>

      </div>
      <div class="modal-footer justify-content-center">
        <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal"
          id="btnCancelarActualizacionOpeningCashRegister"><span class="ajusteProyecto">Cancelar</span></button>
        <button type="button" class="btn-custom btn-custom--blue espAgregar float-right" name="btnAgregar" id="btnAddOpeningCashRegister" onclick="addOpeningCashRegister()"><span
          class="ajusteProyecto">Aceptar</span></button>
      </div>
    </div>
  </div>
</div>