<div class="modal fade" id="add_cash_closing" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <img src="../../img/punto_venta/corte_caja.svg" width="40">
        <h4 style="padding-left: 2rem;" class="modal-title w-100" id="myModalLabel">Corte de caja</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" tabindex="-2">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <div class="row">
          <div class="col-lg">
            <div class="form-group">
              <label for=""><b>Caja: </b><span id="name_cash"></span></label>
            </div>
          </div>
        </div>
        <form id="form-data-cash-closing">
          <div class="table-responsive">
            <table class="table" id="tblCashClosing" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th class="fill-cell"></th>
                  <th>Contado</th>
                  <th id="th_calculado">Calculado</th>
                  <th id="th_diferencia">Diferencia</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="header_row">Efectivo </td>
                  <input type="hidden" name="txtCashcountedhide" id="txtCashCountedhide">
                  <td id="efectivo_contado"><input class="form-control numericDecimal-only" type="text" name="txtCashcounted" id="txtCashCounted" value="0.00" onclick="this.select()"></td>
                  <td id="th_efectivo_calculado">$ <span id="efectivo_calculado">0.00</span></td>
                  <input type="hidden" name="efectivo_calculado_hide" id="efectivo_calculado_hide">
                  <td id="th_efectivo_diferencia">$ <span id="efectivo_diferencia">0.00</span></td>
                  <input type="hidden" name="efectivo_diferencia_hide" id="efectivo_diferencia_hide">
                </tr>
                <tr>
                  <td class="header_row">Crédito</td>
                  <input type="hidden" name="txtCreditCountedHide" id="txtCreditCountedHide">
                  <td id="credito_contado"><input class="form-control numericDecimal-only" type="text" name="txtCreditCounted" id="txtCreditCounted" value="0.00" onclick="this.select()"></td>
                  <td id="th_credito_calculado">$ <span id="credito_calculado">0.00</span></td>
                  <input type="hidden" name="credito_calculado_hide" id="credito_calculado_hide">
                  <td id="th_credito_diferencia">$ <span id="credito_diferencia">0.00</span></td>
                  <input type="hidden" name="credito_diferencia_hide" id="credito_diferencia_hide">
                </tr>
                <tr>
                  <td class="header_row">Transferencia</td>
                  <input type="hidden" name="txtTransfercountedHide" id="txtTransferCountedHide">
                  <td id="transferencia_contado"><input class="form-control numericDecimal-only" type="text" name="txtTransfercounted" id="txtTransferCounted" value="0.00" onclick="this.select()"></td>
                  <td id="th_transferencia_calculado">$ <span id="transferencia_calculado">0.00</span></td>
                  <input type="hidden" name="transferencia_calculado_hide" id="transferencia_calculado_hide">
                  <td id="th_transferencia_diferencia">$ <span id="transferencia_diferencia">0.00</span></td>
                  <input type="hidden" name="transferencia_diferencia_hide" id="transferencia_diferencia_hide">
                </tr>
                <tr>
                  <td class="header_row">Total</td>
                  <td id="total_contado">$ 0.00</td>
                  <input type="hidden" name="total_contado_hide" id="total_contado_hide">
                  <td id="total_calculado">$ 0.00</td>
                  <input type="hidden" name="total_calculado_hide" id="total_calculado_hide">
                  <td id="total_diferencia">$ 0.00</td>
                  <input type="hidden" name="total_diferencia_hide" id="total_diferencia_hide">

                </tr>
                <br>
                
              </tbody>
            </table>
          </div>
          <div class="row form-group">
            <div class="col-lg">
              <label for=""><b>Retiro por corte: </b></label>
            </div>
          </div>
          <div class="row form-group">
            <div class="col-lg">
              <label for="txtCashWithdrawal">Efectivo:</label>
              <input type="text" name="txtCashWithdrawal" id="txtCashWithdrawal" class="form-control" value="0.00" onclick="this.select()">
            </div>
            <div class="col-lg">
              <label for="txtCreditWithdrawal">Crédito:</label>
              <input type="text" name="txtCreditWithdrawal" id="txtCreditWithdrawal" class="form-control" value="0.00" onclick="this.select()">
            </div>
            <div class="col-lg">
              <label for="txtTransferWithdrawal">Transferencia:</label>
              <input type="text" name="txtTransferWithdrawal" id="txtTransferWithdrawal" class="form-control" value="0.00" onclick="this.select()">
            </div>
            <div class="col-lg">
              <label for="txtTotalWithdrawal">Total:</label>
              <input type="text" name="txtTotalWithdrawal" id="txtTotalWithdrawal" class="form-control" value="0.00" readonly>
              <input type="hidden" name="txtTotalWithdrawal_hide" id="txtTotalWithdrawal_hide" class="form-control" value="0.00">
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer justify-content-center">
        <button type="button" class="btnesp first espCancelar btnCancelarActualizacion mx-2" data-dismiss="modal"
          id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
        <button type="button" class="btn-custom btn-custom--blue espAgregar mx-2" name="btnAgregar" id="btnSaveDataCashClosing"><span
          class="ajusteProyecto">Guardar</span></button>
      </div>
    </div>
  </div>
</div>