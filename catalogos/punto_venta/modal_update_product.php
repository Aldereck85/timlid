<!-- Begin modal update product -->
<div class="modal fade" id="modal_update_product" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <img src="../../img/punto_venta/productos_servicios_blanco.svg" width="40">
        <h4 style="padding-left: 2rem;" class="modal-title w-100" id="myModalLabel">Editar producto</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <div class="modal-body">
        <input type="hidden" name="txtProductUpdateId" id="txtProductUpdateId">
        <input type="hidden" name="txtProductAllUpdateId" id="txtProductAllUpdateId">
        <nav id="btn-controls-product-modal">
          <div class="nav nav-tabs nav-fill" role="tablist"id="navbarProductData">
            <a class="nav-item nav-link active" id="general-data-update-product-tab" data-toggle="tab" href="#nav-general-data-update-product" role="tab" aria-controls="nav-general-data-update-product" aria-selected="true">Datos generales</a>
            <a class="nav-item nav-link" id="additional-data-update-product-tab" data-toggle="tab" href="#nav-additional-data-update-product" role="tab" aria-controls="nav-additional-data-update-product" aria-selected="true">Datos adicionales</a>
            <a class="nav-item nav-link" id="image-update-product-tab" data-toggle="tab" href="#nav-upload-image-update-product" role="tab" aria-controls="nav-upload-image-update-product" aria-selected="true">Imagen y descripción</a>
          </div>

        </nav>
        <br>
        <div class="tab-content" id="nav-tabContent">
          <div class="tab-pane fade show active" id="nav-general-data-update-product" role="tabpanel" aria-labelledby="nav-general-data-update-product">
            
            <form class="form-data-update-product" id="form-general-data-update-product">
              <div class="form-group row">
                <div class="col-lg-6">
                  <div class="custom-control custom-radio custom-control-inline">
                    <input class="custom-control-input" type="radio" name="producto_update_type" id="chkUpdateProduct" value="4" required>
                    <label class="custom-control-label" for="chkUpdateProduct">Producto</label>
                  </div>
                  <div class="invalid-feedback" id="invalid-productUpdateType">El producto debe de tener un tipo.</div>
                </div>
                <div class="col-lg-6">
                  <div class="custom-control custom-radio custom-control-inline">
                    <input class="custom-control-input" type="radio" name="producto_update_type" id="chkUpdateService" value="5" required>
                    <label class="custom-control-label" for="chkUpdateService">Servicio</label>
                  </div>
                  
                </div>
              </div>
              <div class="form-group row">
                <div class="col-lg-6">
                  <label for="txtUpdateClave">Clave:*</label>
                  <input class="form-control alphaNumericDotAlter-only" type="text" name="txtUpdateClave" id="txtUpdateClave" required>
                  <div class="invalid-feedback" id="invalid-productUpdateKey">La factura debe de tener una dirección de facturación.</div>
                </div>
                <div class="col-lg-6">
                  <label for="txtUpdateCodigoBarras">Código de barras:</label>
                  <input class="form-control numeric-only" type="text" name="txtUpdateCodigoBarras" id="txtUpdateCodigoBarras">
                </div>
              </div>
              
              <div class="form-group row">
                <div class="col-lg-12">
                  <label for="txtUpdateNombre">Nombre:*</label>
                  <input class="form-control alphaNumericDotAlter-only" type="text" name="txtUpdateNombre" id="txtUpdateNombre" required>
                  <div class="invalid-feedback" id="invalid-productUpdateName">El producto debe tener un nombre.</div>

                </div>
              </div>
              
              <div class="form-group row">
                <div class="col-lg-6">
                  <label for="cmbUpdateCategoria">Categoria:*</label>
                  <select name="cmbUpdateCategoria" id="cmbUpdateCategoria" required></select>
                  <div class="invalid-feedback" id="invalid-productUpdateCategory">El producto debe tener una categoría.</div>
                </div>
                <div class="col-lg-6">
                  <label for="cmbUpdateMarca">Marca:*</label>
                  <select name="cmbUpdateMarca" id="cmbUpdateMarca" required></select>
                  <div class="invalid-feedback" id="invalid-productUpdateBrand">El producto debe tener un producto.</div>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-lg-12 text-center" style="border-bottom: 1px solid var(--color-primario) !important">
                  <label>Datos de Venta</label>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-lg-4">
                  <div class="table-responsive">
                    <table class="table" id="tblUpdateTax" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th></th>
                          <th>Impuesto</th>
                          <th>Tasa o Cuota</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody></tbody>
                    </table>
                  </div>
                </div>
                <div class="col-lg-4">
                  <label for="txtPrecioCompra">Precio de compra:*</label>
                  <input class="form-control decimal" type="text" name="txtUpdatePrecioCompra" id="txtUpdatePrecioCompra" required>
                  <div class="invalid-feedback" id="invalid-productUpdatePurchasePrice">El producto debe tener un precio de compra.</div>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="chkUpdatePrecioCompraNeto">
                    <label class="form-check-label" for="chkUpdatePrecioCompraNeto">
                      Neto
                    </label>
                  </div>
                </div>
                
                <div class="col-lg-4">
                  <label for="txtUpdatePrecioCompraSinImpuestos">Precio de compra s/impuestos:</label>
                  <input class="form-control decimal" type="text" name="txtUpdatePrecioCompraSinImpuestos" id="txtUpdatePrecioCompraSinImpuestos" readonly>
                  <input type="hidden" name="txtUpdatePrecioCompraSinImpuestosValue" id="txtUpdatePrecioCompraSinImpuestosValue">
                </div>
              </div>

              <div class="form-group row">
                <div class="col-lg-12">
                  <div id="alerts_tax"></div>
                </div>
              </div>


                <table class="table" id="tblStocksProduct">
                  <thead>
                    <tr>
                      <td></td>
                      <td>Precio 1</td>
                      <td>Precio 2</td>
                      <td>Precio 3</td>
                      <td>Precio 4</td>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>% Utilidad</td>
                      <td>
                        <input class="form-control decimal numericDecimal-only" type="number" name="txtUpdateUtilidad" id="txtUpdateUtilidad1" step="0.01">
                        <div class="invalid-feedback" id="invalid-productUdtateUtility">El producto debe de tener una utilidad.</div>
                      </td>
                      <td>
                        <input class="form-control decimal numericDecimal-only" type="text" name="txtUpdateUtilidad" id="txtUpdateUtilidad2">
                      </td>
                      <td>
                        <input class="form-control decimal numericDecimal-only" type="text" name="txtUpdateUtilidad" id="txtUpdateUtilidad3">
                      </td>
                      <td>
                        <input class="form-control decimal numericDecimal-only" type="text" name="txtUpdateUtilidad" id="txtUpdateUtilidad4">
                      </td>
                    </tr>
                    <tr>
                      <td>Precio venta</td>
                      <td>
                        <input class="form-control" type="text" name="txtUpdatePrecioVenta" id="txtUpdatePrecioVenta1" readonly>
                      </td>
                      <td>
                        <input class="form-control" type="text" name="txtUpdatePrecioVenta" id="txtUpdatePrecioVenta2" readonly>
                      </td>
                      <td>
                        <input class="form-control" type="text" name="txtUpdatePrecioVenta" id="txtUpdatePrecioVenta3" readonly>
                      </td>
                      <td>
                        <input class="form-control" type="text" name="txtUpdatePrecioVenta" id="txtUpdatePrecioVenta4" readonly>
                      </td>
                    </tr>
                    <tr>
                      <td>Precio de venta neto</td>
                      <td>
                        <input class="form-control" type="number" name="txtUpdatePrecioVentaNeto" id="txtUpdatePrecioVentaNeto1" step="0.01">
                      </td>
                      <td>
                        <input class="form-control" type="text" name="txtUpdatePrecioVentaNeto" id="txtUpdatePrecioVentaNeto2">
                      </td>
                      <td>
                        <input class="form-control" type="text" name="txtUpdatePrecioVentaNeto" id="txtUpdatePrecioVentaNeto3">
                      </td>
                      <td>
                        <input class="form-control" type="text" name="txtUpdatePrecioVentaNeto" id="txtUpdatePrecioVentaNeto4">
                      </td>
                    </tr>
                    
                  </tbody>
                </table>
              
            </form>
            
          </div>

          <div class="tab-pane fade" id="nav-additional-data-update-product" role="tabpanel" aria-labelledby="nav-additional-data-update-product">
            <form class="form-data-product" id="form-additional-data-update-product">
              <div class="form-group row">
                <div class="col-lg-6">
                  <label for="cmbClvProdUctServ">Clave producto/servicio</label>
                  <input type="hidden" name="txtUpdateClaveSatId" id="txtUpdateClaveSatId" required>
                  <input type="text" class="form-control" name="txtUpdateClaveSat" id="txtUpdateClaveSat" readonly>
                  <div class="invalid-feedback" id="invalid-productUpdateServiceKey">El producto debe tener una clave de producto/servicio.</div>
                </div>
                <div class="col-lg-6">
                  <label for="cmbClvUnit">Clave unidad de medida</label>
                  <input type="hidden" name="txtUpdateUnidadMedidaId" id="txtUpdateUnidadMedidaId" required>
                  <input type="text" class="form-control" name="txtUpdateUnidadMedida" id="txtUpdateUnidadMedida" readonly>
                  <div class="invalid-feedback" id="invalid-productUpdateUnitKey">El producto debe tener una clave de unidad de medida.</div>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-lg-3">
                  <label for="txtUpdateStockMinimo">Stock mínimo:</label>
                  <input class="form-control numeric-only" type="text" name="txtUpdateStockMinimo" id="txtUpdateStockMinimo" value="0">
                </div>
                <div class="col-lg-3">
                  <label for="txtUpdateStockMaximo">Stock máximo:</label>
                  <input class="form-control numeric-only" type="text" name="txtUpdateStockMaximo" id="txtUpdateStockMaximo" value="0">
                </div>
                <div class="col-lg-3">
                  <label for="txtUpdatePuntoReorden">Punto de reorden:</label>
                  <input class="form-control numeric-only" type="text" name="txtUpdatePuntoReorden" id="txtUpdatePuntoReorden" value="0">
                </div>
                <div class="col-lg-3">
                  <div class="custom-control custom-switch">  
                    <input class="custom-control-input" type="checkbox" id="chkUpdateReceta">
                    <label class="custom-control-label" for="chkUpdateReceta">Receta</label>
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-lg-4"> 
                  <div class="custom-control custom-radio custom-control-inline">
                    <input class="custom-control-input" type="radio" name="loteoserie" id="chkUpdateLote">
                    <label class="custom-control-label" for="chkUpdateLote">Lote</label>
                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="custom-control custom-radio custom-control-inline">
                    <input class="custom-control-input" type="radio" name="loteoserie" id="chkUpdateSerie">
                    <label class="custom-control-label" for="chkUpdateSerie">Serie</label>
                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="custom-control custom-switch">
                    <input class="custom-control-input" type="checkbox" id="chkUpdateCaducidad">
                    <label class="custom-control-label" for="chkUpdateCaducidad">Fecha de caducidad</label>
                  </div>
                </div>
                
              </div>
            </form>
          </div>

          <div class="tab-pane fade" id="nav-upload-image-update-product" role="tabpanel" aria-labelledby="nav-upload-image-update-product">
            <div class="container-fluid">
              <form id="form-upload-data-update-product">
                <div class="row">
                  <div class="col">
                    <div class="card">
                      <div class="card-body text-center">
                        <label class="mx-auto" for="flUpdateUploadImage"><img id="lbl-file-upload-image-update-product" src="" alt="no images" width="100%" style="max-height:250px" data-toggle="tooltip" data-placement="top" title="Clic para subir imagen"></label>
                        <div id="file-upload-image-product">
                          <input type="file" name="flUpdateUploadImage" id="flUpdateUploadImage" onchange="document.getElementById('lbl-file-upload-image-update-product').src = window.URL.createObjectURL(this.files[0])">
                        </div>
                        
                      </div>
                      
                    </div>
                  </div>
                  <div class="col">
                    <div class="card">
                      <div class="card-body">
                        <label for="txaDescriptionProduct">Descripción:</label>
                        <textarea class="form-control alphaNumericDot-only" name="txaUpdateDescriptionProduct" id="txaUpdateDescriptionProduct" cols="30" rows="8"></textarea>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
          
        </div>
      
      </div>
      <div class="modal-footer justify-content-center">
        <button type="button" class="btnesp first espCancelar btnCancelarActualizacion mx-2" data-dismiss="modal"
          id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
        <button type="button" class="btn-custom btn-custom--blue espAgregar mx-2" name="btnAgregar" id="btnUpdateProductModal"><span
          class="ajusteProyecto">Guardar</span></button>
      </div>
      
    </div>
  </div>
</div>