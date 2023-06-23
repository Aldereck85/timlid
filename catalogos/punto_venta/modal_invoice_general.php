<div class="modal fade" id="modal_invoice_general" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h4 class="modal-title w-100" id="myModalLabel">Facturación general público en general</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" tabindex="-2">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="form-invoice_general">
          <div class="form-group row">
            <div class="col-3">
              <label for="txtInitialDate">Fecha inicial</label>
              <input class="form-control" type="date" id="txtInitialDate" name="txtInitialDate">
            </div>
            <div class="col-3">
              <label for="txtFinalDate">Fecha final</label>
              <input class="form-control" type="date" id="txtFinalDate" name="txtFinalDate">
            </div>
            <div class="col-3 d-flex align-items-end">
              <a class="btn-custom btn-custom--blue espAgregar mx-2" href="#" name="btnFilterDataInvoice" id="btnFilterDataInvoice">Filtrar</a>
              <!-- <button  name="btnFilterDataInvoice" id="btnFilterDataInvoice">Filtrar</button> -->
            </div>
          </div>
        </form>

        <div class="" id="table_display_data">
          <div class="table-responsive">
            <table class="table" id="tblGeneralInvoice" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th class="disable-select">No</th>
                  <th class="disable-select">Folio</th>
                  <th class="disable-select">Fecha</th>
                  <th class="disable-select">Importe</th>
                </tr>
              </thead>
            </table>
          </div>
          
          <div class="row">
            <div class="col-2"></div>
            <div class="col-2"></div>
            <div class="col-8">
              <div class="card">
                <div class="card-body">
                  
                  <div class="row" style="border-bottom: 1px solid #e3e6f07d;">
                    <div class="col-4">
                      <p>Subtotal:</p>
                      <input type="hidden" name="generalInvoice-subtotal-ticket-hidden" id="generalInvoice-subtotal-ticket-hidden">
                    </div>
                    <div class="col-4"></div>
                    <div class="col-4">
                      <p id="generalInvoice-subtotal-ticket">$0.00</p>
                    </div>
                    
                  </div>

                  <div class="row">
                    <div class="col-4">
                      <p>Impuestos:</p>
                    </div>
                    
                  </div>
                  <div class="row" style="padding-bottom:15px;border-bottom: 1px solid #e3e6f07d;">
                    <div class="col-6"></div>
                    <div class="col-6" id="generalInvoice-taxes"></div>
                  </div>

                  <div class="row" style="font-size:1.5em;border-bottom: 1px solid #e3e6f07d;">
                    <div class="col-4">
                      <p>Total:</p>
                    </div>
                    <div class="col-4"></div>
                    <div class="col-4">
                      <p id="generalInvoice-total-price">$0.00</p>
                      <input type="hidden" name="generalInvoice-total-price-hidden" id="generalInvoice-total-price-hidden">
                    </div>
                  </div>
                    
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="modal-footer justify-content-center">
        <button type="button" class="btnesp first espCancelar btnCancelarActualizacion mx-2" data-dismiss="modal"
          id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
        <button type="button" class="btn-custom btn-custom--blue espAgregar mx-2" name="btnAgregar" id="btnSaveGeneralInvoice"><span
          class="ajusteProyecto">Aceptar</span></button>
      </div>

    </div>
  </div>
</div>