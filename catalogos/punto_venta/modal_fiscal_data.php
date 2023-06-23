<div class="modal fade" id="modal_fiscal_data" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h4 class="modal-title w-100" id="myModalLabel">Datos fiscales facturación</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" tabindex="-2">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form-fiscal-data">
          <div class="form-group">
            <label for="cmbCFDIUse">Uso CFDI:</label>
            <select name="cmbCFDIUse" id="cmbCFDIUse"></select>
          </div>
          <div class="form-group">
            <label for="cmbPaidType">Forma de pago:</label>
            <select name="" id="cmbPaidType"></select>
          </div>
          <div class="form-group">
            <label for="cmbPaidMethod">Método de pago:</label>
            <select name="cmbPaidMethod" id="cmbPaidMethod"></select>
          </div>
          <div class="form-group">
            <label for="cmbCurrency">Moneda:</label>
            <select name="cmbCurrency" id="cmbCurrency"></select>
          </div>
        </form>
      </div>

      <div class="modal-footer justify-content-center">
        <button type="button" class="btnesp first espCancelar btnCancelarActualizacion mx-2" data-dismiss="modal"
          id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
        <button type="button" class="btn-custom btn-custom--blue espAgregar mx-2" name="btnAgregar" id="btnSaveDataFiscal"><span
          class="ajusteProyecto">Aceptar</span></button>
      </div>
    </div>
  </div>
</div>