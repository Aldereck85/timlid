<!--add producto and service key -->
<div class="modal fade right hide" id="add_producto_service_key" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"  aria-hidden="true">
  <div class="modal-dialog modal-full-height modal-right modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title w-100" id="myModalLabel">Agregar clave SAT</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="header_search_sat">
          <label>
            <img src="../../img/timdesk/buscar.svg" width="20px">
            <input class="form-control form-control-sm" type="search" placeholder="Buscar..." name="buscar_clave_sat" id="buscar_clave_sat">
          </label>
        </div>
          <div class="table-responsive">
            <table class="table stripe" id="tblClaveSatModal" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Clave</th>
                  <th>Descripción</th>
                </tr>
              </thead>
              <tbody id="tabla_body_sat"></tbody>
            </table>
          </div>
      </div>
    </div>
  </div>
</div>
