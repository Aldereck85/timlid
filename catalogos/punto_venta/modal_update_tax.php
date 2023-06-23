<div class="modal fade modal-right" id="update_tax_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title w-100" id="myModalLabel">Agregar impuestos</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form_update_tax">
          <div class="form-group row">
            <div class="col-lg-12">
              <label for="cmbUpdateTax">Impuesto:</label>
              <select name='cmbUpdateTax' id='cmbUpdateTax'><select>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-lg-12">
              <label for="cmbUpdateRateOrFee">Tasa o Cuota</label>
              <select name="cmbUpdateRateOrFee" id="cmbUpdateRateOrFee"></select>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer justify-content-center">
        <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal"
          id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
        <button type="button" class="btn-custom btn-custom--blue espAgregar float-right" name="btnAgregar" id="btnUpdateTax"><span
          class="ajusteProyecto">Guardar</span></button>
      </div>
    </div>
  </div>
</div>