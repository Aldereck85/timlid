<!-- Begin modal update quantity -->
<div class="modal fade" id="update_quantity" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <input type="hidden" name="txtIdRow" id="txtIdRow">
          <h4 class="modal-title w-100" id="myModalLabel">Cantidad</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-lg-10 container text-center">
              
              <h1 class="align-middle" id="quantity" style="margin-top: 15px; margin-left:20px;" contenteditable="true"></h1>
              
            </div>
            <div class="col-lg-2">
              <a href="#" id="plus-quantity"><h2><i class="fas fa-angle-up"></i></h2></a>
              <a href="#" id="minus-quantity"><h2><i class="fas fa-angle-down"></i></h2></a>
            </div>
          </div>

        </div>
        <div class="modal-footer justify-content-center">
          <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal"
            id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
          <button type="button" class="btn-custom btn-custom--blue espAgregar float-right" name="btnAgregar" id="btnUpdateQuantity"><span
            class="ajusteProyecto">Guardar</span></button>
        </div>
      </div>
    </div>
  </div>
  <!-- End modal update quantity -->