<!-- Begin modal create product -->
<div class="modal fade" id="create_product" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <img src="../../img/punto_venta/productos_servicios_blanco.svg" width="40">
        <h4 style="padding-left: 2rem;" class="modal-title w-100" id="myModalLabel">Agregar producto</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <div class="modal-body">
        
        <nav id="btn-controls-product-modal">
          <div class="nav nav-tabs" role="tablist"id="navbarProductData">
            <a class="nav-item nav-link active" id="general-data-product-tab" data-toggle="tab" href="#nav-general-data-product" role="tab" aria-controls="nav-general-data-product" aria-selected="true">Datos generales</a>
            <a class="nav-item nav-link" id="additional-data-product-tab" data-toggle="tab" href="#nav-additional-data-product" role="tab" aria-controls="nav-additional-data-product" aria-selected="true">Datos adicionales</a>
            <a class="nav-item nav-link" id="image-product-tab" data-toggle="tab" href="#nav-upload-image-product" role="tab" aria-controls="nav-upload-image-product" aria-selected="true">Imagen y descripción</a>
          </div>

        </nav>
        <br>
        <form id="form-data-product">
          <div class="tab-content" id="nav-tabContent">

            <div class="tab-pane fade show active" id="nav-general-data-product" role="tabpanel" aria-labelledby="nav-general-data-product"> 
            
              <div class="form-group row">
                <div class="col-lg-6">
                  <div class="custom-control custom-radio custom-control-inline">
                    <input class="custom-control-input" type="radio" name="producto_type" id="chkProduct" value="4" required>
                    <label class="custom-control-label" for="chkProduct">Producto</label>
                  </div>
                  <div class="invalid-feedback" id="invalid-productType">El producto debe de tener un tipo.</div>
                </div>
                <div class="col-lg-6">
                  <div class="custom-control custom-radio custom-control-inline">
                    <input class="custom-control-input" type="radio" name="producto_type" id="chkService" value="5" required>
                    <label class="custom-control-label" for="chkService">Servicio</label>
                  </div>
                  
                </div>
              </div>
              <div class="form-group row">
                <div class="col-lg-5">
                  <label for="txtClave">Clave:*</label>
                  <input class="form-control alphaNumericDotAlter-only" type="text" name="txtClave" id="txtClave" required>
                  <div class="invalid-feedback" id="invalid-productKey">El producto debe de tener una clave.</div>
                  <div class="invalid-feedback" id="invalid-productKeyRepeat">La clave ya está asignada a otro producto.</div>
                </div>
                <div class="col-lg-2 d-flex align-items-end">
                  <button type="button" class="btnesp espAgregar" name="btnGenerarClave" id="btnGenerarClave"><span
                    class="ajusteProyecto">Generar clave</span>
                  </button>
                </div>
                <div class="col-lg-5">
                  <label for="txtCodigoBarras">Código de barras:</label>
                  <input class="form-control numeric-only" type="text" name="txtCodigoBarras" id="txtCodigoBarras">
                </div>
              </div>
              
              <div class="form-group row">
                <div class="col-lg-12">
                  <label for="txtNombre">Nombre:*</label>
                  <input class="form-control alphaNumericDotAlter-only" type="text" name="txtNombre" id="txtNombre" required>
                  <div class="invalid-feedback" id="invalid-productName">El producto debe tener un nombre.</div>

                </div>
              </div>
              
              <div class="form-group row">
                <div class="col-lg-6">
                  <label for="cmbCategoria">Categoria:*</label>
                  <select name="cmbCategoria" id="cmbCategoria" required></select>
                  <div class="invalid-feedback" id="invalid-productCategory">El producto debe tener una categoría.</div>
                </div>
                <div class="col-lg-6">
                  <label for="cmbDepartamento">Marca:*</label>
                  <select name="cmbMarca" id="cmbMarca" required></select>
                  <div class="invalid-feedback" id="invalid-productBrand">El producto debe tener un producto.</div>
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
                    <table class="table" id="tblTax" width="100%" cellspacing="0">
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
                  <label for="txtPrecioCompra">Precio de compra:</label>
                  <input class="form-control decimal numericDecimal-only" type="text" name="txtPrecioCompra" id="txtPrecioCompra" required>

                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="chkPrecioCompraNeto">
                    <label class="form-check-label" for="chkPrecioCompraNeto">
                      Neto
                    </label>
                  </div>
                  <div class="invalid-feedback" id="invalid-productPurchasePrice">El producto debe tener un precio de compra.</div>
                </div>
                
                <div class="col-lg-4">
                  <label for="txtPrecioCompraSinImpuestos">Precio de compra s/impuestos:</label>
                  <input class="form-control" type="text" name="txtPrecioCompraSinImpuestos" id="txtPrecioCompraSinImpuestos" readonly>
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
                        <input class="form-control decimal numericDecimal-only" type="text" name="txtUtilidad" id="txtUtilidad1">
                        <div class="invalid-feedback" id="invalid-productUtility">El producto debe de tener una utilidad.</div>
                      </td>
                      <td>
                        <input class="form-control decimal numericDecimal-only" type="text" name="txtUtilidad" id="txtUtilidad2">
                      </td>
                      <td>
                        <input class="form-control decimal numericDecimal-only" type="text" name="txtUtilidad" id="txtUtilidad3">
                      </td>
                      <td>
                        <input class="form-control decimal numericDecimal-only" type="text" name="txtUtilidad" id="txtUtilidad4">
                      </td>
                    </tr>
                    <tr>
                      <td>Precio venta</td>
                      <td>
                        <input class="form-control" type="text" name="txtPrecioVenta" id="txtPrecioVenta1" readonly>
                      </td>
                      <td>
                        <input class="form-control" type="text" name="txtPrecioVenta" id="txtPrecioVenta2" readonly>
                      </td>
                      <td>
                        <input class="form-control" type="text" name="txtPrecioVenta" id="txtPrecioVenta3" readonly>
                      </td>
                      <td>
                        <input class="form-control" type="text" name="txtPrecioVenta" id="txtPrecioVenta4" readonly>
                      </td>
                    </tr>
                    <tr>
                      <td>Precio de venta neto</td>
                      <td>
                        <input class="form-control" type="text" name="txtPrecioVentaNeto" id="txtPrecioVentaNeto1">
                      </td>
                      <td>
                        <input class="form-control" type="text" name="txtPrecioVentaNeto" id="txtPrecioVentaNeto2">
                      </td>
                      <td>
                        <input class="form-control" type="text" name="txtPrecioVentaNeto" id="txtPrecioVentaNeto3">
                      </td>
                      <td>
                        <input class="form-control" type="text" name="txtPrecioVentaNeto" id="txtPrecioVentaNeto4">
                      </td>
                    </tr>
                    
                  </tbody>
                </table>
              
            
            
            </div>

            <div class="tab-pane fade" id="nav-additional-data-product" role="tabpanel" aria-labelledby="nav-additional-data-product">
              
                <div class="no-visible">
                  <input type="text" name="txtClaveSatId" id="txtClaveSatId" required>
                </div>
                <div class="no-visible">
                  <input type="text" name="txtUnidadMedidaId" id="txtUnidadMedidaId" required>
                </div>
                <div class="form-group row">
                  <div class="col-lg-6">
                    <label for="cmbClvProdUctServ">Clave producto/servicio</label>
                    <input type="text" class="form-control" name="txtClaveSat" id="txtClaveSat" readonly>
                    <div class="invalid-feedback" id="invalid-productServiceKey">El producto debe tener una clave de producto/servicio.</div>
                  </div>
                  <div class="col-lg-6">
                    <label for="cmbClvUnit">Clave unidad de medida</label>

                    <input type="text" class="form-control" name="txtUnidadMedida" id="txtUnidadMedida" readonly>
                    <div class="invalid-feedback" id="invalid-productUnitKey">El producto debe tener una clave de unidad de medida.</div>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-lg-3">
                    <label for="txtStockMinimo">Stock mínimo:</label>
                    <input class="form-control numeric-only" type="text" name="txtStockMinimo" id="txtStockMinimo" value="0">
                  </div>
                  <div class="col-lg-3">
                    <label for="txtStockMaximo">Stock máximo:</label>
                    <input class="form-control numeric-only" type="text" name="txtStockMaximo" id="txtStockMaximo" value="0">
                  </div>
                  <div class="col-lg-3">
                    <label for="txtPuntoReorden">Punto de reorden:</label>
                    <input class="form-control numeric-only" type="text" name="txtPuntoReorden" id="txtPuntoReorden" value="0">
                  </div>
                  <div class="col-lg-3">
                    <div class="custom-control custom-switch">  
                      <input class="custom-control-input" type="checkbox" id="chkReceta">
                      <label class="custom-control-label" for="chkReceta">Receta</label>
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-lg-3"> 
                    <div class="custom-control custom-switch">
                      <input class="custom-control-input" type="checkbox" id="chkLote">
                      <label class="custom-control-label" for="chkLote">Lote</label>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="custom-control custom-switch">
                      <input class="custom-control-input" type="checkbox" id="chkSerie">
                      <label class="custom-control-label" for="chkSerie">Serie</label>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="custom-control custom-switch">
                      <input class="custom-control-input" type="checkbox" id="chkCaducidad" disabled>
                      <label class="custom-control-label" for="chkCaducidad">Fecha de caducidad</label>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="custom-control custom-switch">
                      <input class="custom-control-input" type="checkbox" id="chkExistencia">
                      <label class="custom-control-label" for="chkExistencia">Agregar existencia</label>
                    </div>
                  </div>
                </div>
                <div class="form-group row">

                  <div class="col-lg-4">
                    <div class="no-visible" id="input-text-exist">
                      <label for="txtExistencia">Existencia</label>
                      <input class="form-control numeric-only" type="number" name="txtExistencia" id="txtExistencia" step="0.00">
                    </div>
                  </div>

                  <div class="col-lg-4 no-visible" id="button-add-exist">
                    <div class="d-flex justify-content-start mt-4">
                      <button type="button" class="btnesp espAgregar" id="addExistsProduct"><span
                      class="ajusteProyecto" id="textAddExistsProduct">Agregar Lotes y Series</span></button>
                    </div>
                  </div>
                </div>

                <div class="table-responsive">
                  <table class="table no-visible" id="tblExistProduct" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th>Existencia</th>
                        <th>Lote/Serie</th>
                        <th>Caducidad</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody></tbody>
                  </table>
                </div>
              
            </div>

            <div class="tab-pane fade" id="nav-upload-image-product" role="tabpanel" aria-labelledby="nav-upload-image-product">
              <div class="container-fluid">
                  <div class="row">
                    <div class="col">
                      <div class="card">
                        <div class="card-body text-center">
                          <label class="mx-auto" for="flUploadImage"><img id="lbl-file-upload-image-product" src="../../img/Productos/agregar.svg" alt="no images" width="100%" style="max-height:250px" data-toggle="tooltip" data-placement="top" title="Clic para subir imagen"></label>
                          <div id="file-upload-image-product">
                            <input type="file" name="flUploadImage" id="flUploadImage" onchange="document.getElementById('lbl-file-upload-image-product').src = window.URL.createObjectURL(this.files[0])">
                          </div>
                          
                        </div>
                        
                      </div>
                    </div>
                    <div class="col">
                      <div class="card">
                        <div class="card-body">
                          <label for="txaDescriptionProduct">Descripción:</label>
                          <textarea class="form-control alphaNumericDot-only" name="txaDescriptionProduct" id="txaDescriptionProduct" cols="30" rows="8"></textarea>
                        </div>
                      </div>
                    </div>
                  </div>
              </div>
            </div>
         
          </div>
        </form>
      </div>
      <div class="modal-footer justify-content-center">
        <!-- <button type="button" class="btnesp espAgregar mx-2" name="btnGenerarClave" id="btnGenerarClave"><span
          class="ajusteProyecto">Generar clave</span></button> -->
        <button type="button" class="btnesp first espCancelar btnCancelarActualizacion mx-2" data-dismiss="modal"
          id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
        <button type="button" class="btn-custom btn-custom--blue espAgregar mx-2" name="btnAddProduct" id="btnAddProduct"><span
          class="ajusteProyecto">Guardar</span></button>
      </div>
      
    </div>
  </div>
</div>