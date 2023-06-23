<?php
    $years = array_combine(range(date("Y"), 2022), range(date("Y"), 2022));
?>

<div class="modal fade" id="modal_fiscal_data_general" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h4 class="modal-title w-100" id="myModalLabel">Datos fiscales facturación</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" tabindex="-2">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form-fiscal-data-general">
          <input type="hidden" name="txtInitialDate_hide" id="txtInitialDate_hide">
          <input type="hidden" name="txtFinalDate_hide" id="txtFinalDate_hide">
          <div class="form-group">
            <label for="cmbCFDIUse">Uso CFDI:</label>
            <select name="cmbCFDIUse" id="cmbCFDIUseGeneral" required></select>
            <div class="invalid-feedback" id="invalid-globalInvoiceCFDIUse">La factura require de un uso CFDI.</div>
          </div>
          <div class="form-group">
            <label for="cmbPaidType">Forma de pago:</label>
            <select name="" id="cmbPaidTypeGeneral" required></select>
            <div class="invalid-feedback" id="invalid-globalInvoicePaidType">La factura require de una forma de pago.</div>
          </div>
          <div class="form-group">
            <label for="cmbPaidMethod">Método de pago:</label>
            <select name="cmbPaidMethod" id="cmbPaidMethodGeneral" required></select>
            <div class="invalid-feedback" id="invalid-globalInvoicePaidMethod">La factura require de un método de pago.</div>
          </div>
          <div class="form-group">
            <label for="cmbCurrency">Moneda:</label>
            <select name="cmbCurrency" id="cmbCurrencyGeneral" required></select>
            <div class="invalid-feedback" id="invalid-globalInvoiceCurrency">La factura require de una moneda.</div>
          </div>
          <div class="form-group">
            <label for="cmbPeriodicity">Periodicidad:</label>
            <select name="cmbPeriodicity" id="cmbPeriodicity" required>
              <option value="">Seleccione un periodo...</option>
              <option value="day">Diario</option>
              <option value="week">Semanal</option>
              <option value="fortnight">Quincenal</option>
              <option value="month">Mensual</option>
              <option value="two_months">Bimestral</option>
            </select>
            <div class="invalid-feedback" id="invalid-globalInvoicePeriodicity">La factura require de una Periodicidad.</div>
          </div>
          <div class="form-group">
            <div class="row">
                <div class="col">
                    <label for="cmbMonth">Meses:</label>
                    <select name="cmbMonth" id="cmbMonth" required>
                        <option value="">Seleccione un mes o bimestre...</option>
                        <option value="01">Enero</option>
                        <option value="02">Febrero</option>
                        <option value="03">Marzo</option>
                        <option value="04">Abril</option>
                        <option value="05">Mayo</option>
                        <option value="06">Junio</option>
                        <option value="07">Julio</option>
                        <option value="08">Agosto</option>
                        <option value="09">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12">Diciembre</option>
                        <option value="13">Enero-Febrero</option>
                        <option value="14">Marzo-Abril</option>
                        <option value="15">Mayo-Junio</option>
                        <option value="16">Julio-Agosto</option>
                        <option value="17">Septiembre-Octubre</option>
                        <option value="18">Noviembre-Diciembre</option>              
                    </select>
                    <div class="invalid-feedback" id="invalid-globalInvoiceMonth">La factura require de un mes o bimestre.</div>
                </div>
                <div class="col">
                    <label for="cmbYears">Año</label>
                    <select name="cmbYears" id="cmbYears" required>
                        <?php foreach($years as $r => $val){ $selected = $val == date("Y") ? "selected" : "";?>
                        <option value="<?=$val?>" <?=$selected;?>><?=$val?></option>
                        <?php } ?>
                    </select>
                    <div class="invalid-feedback" id="invalid-globalInvoiceYear">La factura require de un año.</div>
                </div>
            </div>
            
          </div>
        </form>
      </div>

      <div class="modal-footer justify-content-center">
        <button type="button" class="btnesp first espCancelar btnCancelarActualizacion mx-2" data-dismiss="modal"
          id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
        <button type="button" class="btn-custom btn-custom--blue espAgregar mx-2" name="btnAgregar" id="btnSaveDataFiscalGeneral"><span
          class="ajusteProyecto">Aceptar</span></button>
      </div>
    </div>
  </div>
</div>