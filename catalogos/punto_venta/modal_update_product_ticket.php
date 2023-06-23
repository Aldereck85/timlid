<!-- Begin modal update quantity -->
<div class="modal fade" id="modal_update_product_ticket" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <input type="hidden" name="txtIdRow" id="txtIdRow">
        <h4 class="modal-title w-100" id="myModalLabel">Editar producto</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="txtRowIdUpdate" id="txtRowIdUpdate">
        <input type="hidden" name="txtProductUpdateTicketId" id="txtProductUpdateTicketId">
        <div class="form-group row">
          <h5 id="txtProductNameUpdateModal"></h5>
        </div>
        <div class="form-group row">
          <label for="txtQuantityModalUpdateProductTicket">Cantidad:</label>
          <input class="form-control numericDecimal-only" type="text" name="txtQuantityModalUpdateProductTicket" id="txtQuantityModalUpdateProductTicket" step="0.01">
        </div>
        <div class="form-group row">
          <label for="txtDiscountModalUpdateProductTicket">Descuento:</label>
          <input class="form-control numericDecimal-only" type="number" name="txtDiscountModalUpdateProductTicket" id="txtDiscountModalUpdateProductTicket" step="0.01" min="0" max="100">
        </div>
        <div class="form-group row">
          <label for="txtPriceModalUpdateProductTicket">Precio unitario:</label>
          <!-- <input type="text" name="txtPriceModalUpdateProductTicket" id="txtPriceModalUpdateProductTicket" class="form-control"> -->
          <select name="cmbPriceModalUpdateProductTicket" id="cmbPriceModalUpdateProductTicket"></select>
        </div>


      </div>
        <div class="modal-footer justify-content-center">
          <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal"
            id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
          <button type="button" class="btn-custom btn-custom--blue espAgregar float-right" name="btnAgregar" id="btnUpdateProductTicket"><span
            class="ajusteProyecto">Guardar</span></button>
        </div>
    </div>
  </div>
</div>