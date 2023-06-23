<!--agregar unidad medida modal -->
<div class="modal fade right hide" id="add_unit_product_key" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
      <div class="modal-content">
        
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Agregar unidad de medida</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div id="header_search_unidad_medida">
              <label>
                <img src="../../img/timdesk/buscar.svg" width="20px">
                <input class="form-control form-control-sm" type="search" placeholder="Buscar..." name="buscar_clave_unidad_medida" id="buscar_clave_unidad_medida">
              </label>
            </div>
            <div class="table-responsive">
              <table class="table stripe" id="tblUnidadMedidaModal" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>Clave</th>
                    <th>Descripción</th>
                  </tr>
                </thead>
                <tbody id="tabla_body_medida"></tbody>
              </table>
            </div>

          </div>
          
      </div>
    </div>
  </div>
  <!-- End Add Fluid Modal User -->