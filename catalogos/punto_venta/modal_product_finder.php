<!-- Begin modal product finder -->
<div class="modal fade" id="product_finder" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <img src="../../img/punto_venta/productos_servicios_blanco.svg" width="40">
        <h4 style="padding-left: 2rem;" class="modal-title w-100" id="myModalLabel">Buscador de productos</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <div class="form-group row no-visible" id="loadAllProducts">
          <div class="custom-control custom-switch">
            <input type="checkbox" name="chkLoadAllProducts" id="chkLoadAllProducts" class="custom-control-input">
            <label for="chkLoadAllProducts" class="custom-control-label">Cargar todos los productos de la empresa</label>
          </div>
        </div>

        <div class="table-responsive" id="tblProductsFinderforBranchOffice">
          
          <table class="table" id="tblProductsFinder" style="width:100%">
            <thead>
              <tr>
                <th>Id</th>
                <th>Clave</th>
                <th>Descripción</th>
                <th></th>
                <th>Exis.</th>
                <th>Precio</th>
                <th>Precio Neto</th>
                <th></th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>

        
        <div class="table-responsive no-visible" id="tblProductsFinderforEnterprise">
          <table class="table" id="tblProductsFinderAll" style="width:100%">
          <thead>
              <tr>
                <th>Id</th>
                <th>Clave</th>
                <th>Descripción</th>
                <th></th>
                <th>Exis.</th>
                <th>Precio U.</th>
                <th>Sucursal</th>
                <th></th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>


      </div>
    </div>
  </div>
</div>
  <!-- End modal product finder -->