<!-- Diseño en php no funcional, solo es la base de lo que se aplico en el JS de pestanas_productos.js -->
<div class="card shadow mb-4">
                <div class="card-header">
                  Tarjeta de productos
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <form id="formDatosProducto">
                        <div class="form-group">
                          <div class="row">
                            
                            <div class="col-lg-8">
                              <div class="form-group">
                                <div class="row">
                                  <div class="col-xl-9 col-lg-9 col-md-6 col-sm-3 col-xs-0">
                                    
                                  </div>
                                  <div class="col-xl-1 col-lg-1 col-md-2 col-sm-4 col-xs-6">
                                    <label for="usr">Estatus:*</label>
                                  </div>
                                  <div class="col-xl-2 col-lg-2 col-md-4 col-sm-5 col-xs-6">
                                    <select class="form-control" name="cmbEstatusProducto" id="cmbEstatusProducto" required="" onchange="cambiarColor()">
                                    </select>
                                  </div>
                                </div>
                              </div>
                              
                              <div class="form-group">
                                <div class="row">
                                  <div class="col-lg-6">
                                    <label for="usr">Nombre:*</label>
                                    <div class="row">
                                      <div class="col-lg-12 input-group">
                                        <input class="form-control alphaNumeric-only" type="text" name="txtNombre" id="txtNombre" autofocus="" required="" maxlength="100" placeholder="Ej. Bata quirúgica desechable" onkeyup="escribirNombre()">
                                        <img  id="notaNombre" name="notaNombre" style="display: none;"
                                        src="../../../../img/timdesk/alerta.svg" width=30px
                                        title="La clave interna ya existe, favor de anexar otra" readonly>
                                        <img  id="notaFNombre" name="notaFNombre" style="display: none;"
                                        src="../../../../img/timdesk/alerta.svg" width=30px
                                        title="El nombre ya existe, favor de anexar otro" readonly>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-lg-6">
                                    <label for="usr">Clave interna:</label>
                                    <div class="row">
                                      <div class="col-lg-12 input-group">
                                        <input type="text" maxlength="50" class="form-control alphaNumeric-only" name="txtClaveInterna" id="txtClaveInterna" required="" maxlength="50" placeholder="Ej. AA - 0001" onkeyup="escribirClave()">
                                        <img  id="notaClaveInterna" name="notaClaveInterna" style="display: none;"
                                        src="../../../../img/timdesk/alerta.svg" width=30px
                                        title="La clave interna ya existe, favor de anexar otra" readonly>
                                        <img  id="notaFClaveInterna" name="notaFClaveInterna" style="display: none;"
                                        src="../../../../img/timdesk/alerta.svg" width=30px
                                        title="Campo requerido" readonly>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              
                              <div class="form-group">
                                <div class="row">
                                  <div class="col-lg-6">
                                    <label for="usr">Código de barras:</label>
                                    <div class="row">
                                      <div class="col-lg-12 input-group">
                                        <input type="text" maxlength="50" class="form-control alphaNumeric-only" name="txtCodigoBarras" id="txtCodigoBarras" required="" maxlength="50" placeholder="Ej. 7 88492 808274" onkeyup="escribirCodigo()">
                                        <img  id="notaCodigoBarras" name="notaCodigoBarras" style="display: none;"
                                        src="../../../../img/timdesk/alerta.svg" width=30px
                                        title="El código de barras ya existe, favor de anexar otro" readonly>
                                        <img  id="notaFCodigoBarras" name="notaFCodigoBarras" style="display: none;"
                                        src="../../../../img/timdesk/alerta.svg" width=30px
                                        title="Campo requerido" readonly>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-lg-6">
                                    <label for="usr">Categoría:</label>
                                    <div class="row">
                                      <div class="col-lg-12 input-group">
                                        <span class="input-group-addon" style="width:100%">
                                          <select name="cmbCategoriaProducto" id="cmbCategoriaProducto" required="">
                                            
                                          </select>
                                        </span>
                                        <img  id="notaFCategoriaProducto" name="notaFCategoriaProducto" style="display: none;"
                                        src="../../../../img/timdesk/alerta.svg" width=30px
                                        title="Campo requerido" readonly>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>

                              <div class="form-group">
                                <div class="row">
                                  <div class="col-lg-6">
                                    <label for="usr">Marca:</label>
                                    <div class="row">
                                      <div class="col-lg-12 input-group">
                                        <span class="input-group-addon" style="width:100%">
                                          <select name="cmbMarcaProducto" id="cmbMarcaProducto" required="">
                                            
                                          </select>
                                        </span>
                                        <img  id="notaFMarcaProducto" name="notaFMarcaProducto" style="display: none;"
                                        src="../../../../img/timdesk/alerta.svg" width=30px
                                        title="Campo requerido" readonly>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-lg-6">
                                    <label for="usr">Tipo:*</label>
                                    <div class="row">
                                      <div class="col-lg-12 input-group">
                                        <span class="input-group-addon" style="width:100%">
                                          <select name="cmbTipoProducto" id="cmbTipoProducto" required="" onchange="cambiarTipoProd()">
                                          
                                          </select>
                                        </span>
                                        <img  id="notaFTipoProducto" name="notaFTipoProducto" style="display: none;"
                                        src="../../../../img/timdesk/alerta.svg" width=30px
                                        title="Campo requerido" readonly>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>

                              

                              <div class="form-group">
                                <div class="row">
                                  <div class="col-lg-12">
                                    <label for="usr">Descripción:</label>
                                    <div class="row">
                                      <div class="col-lg-12 input-group">
                                        <textarea type="text" class="form-control alphaNumeric-only" maxlength="100" id="txtDescripcionLarga"
                                        name="txtDescripcionLarga" cols="30" rows="3" placeholder="Escriba aquí la descripción"
                                        style="resize: none!important;" required></textarea>
                                        <img  id="notaFDescripcionLarga" name="notaFDescripcionLarga" style="display: none;"
                                        src="../../../../img/timdesk/alerta.svg" width=30px
                                        title="Campo requerido" readonly>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>

                            </div>
                            <div class="col-lg-4">
                              <div class="file-field">
                                <span id="espacioImagen">
                                <div class="mb-4 img-thumbnail" style="position: relative; width:350px; height:350px; display:block; margin:auto; no-repeat rgb(249,249,249);
                                opacity: .6;">
                                  <img class="z-depth-1-half" src="../../../../img/timdesk/ICONO AGREGAR2_Mesa de trabajo 1.svg"
                                    alt="example placeholder" id="imgProd" name="imgProd" width="100px" height="100px" style=" position: absolute; top: 35%; right: 0; left: 0; margin: 0 auto;">
                                
                                </div>
                                </span>
                                <div class="d-flex justify-content-center">
                                
                                  <span id="espacioFile">
                                  <div class="btnesp espAgregar">
                                    <span>Seleccionar imagen</span>
                                    <input type="file" id="imgFile" name="imgFile" accept="image/jpg" required="">
                                  </div>
                                  </span>
                                </div>
                                
                              </div>
                            </div>
                          </div>
                        </div> 
                        <br>

                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-12">
                              <span id="areaCompuesto">
                              </span>
                            </div>
                          </div>
                        </div>
                        <input  name="contadorCompuesto" id="contadorCompuesto" type="hidden" readonly value="0">

                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-3">
                              <label for="usr">Operaciones del producto:</label>
                              <!--<select class="cmbSlim" name="cmbAccionesProductoTemp" id="cmbAccionesProductoTemp" required="" onchange="verificarAccionProductoTemp(`+$("#PKUsuario").val()+`)">
                              </select>
                              <img  id="notaTipoProductoTemp" name="notaTipoProductoTemp" style="display: none;"
                                        src="../../../../img/timdesk/alerta.svg" width=30px
                                        title="La operación ya ha sido agregada." readonly> -->
                            </div>
                            
                            <div class="col-lg-9">
                            </div>

                            <div class="col-lg-1">
                              <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="cbxCompra" name="cbxCompra">
                                <label class="form-check-label" for="flexSwitchCheckDefault">Compra</label>
                              </div>
                            </div>
                            <div class="col-lg-1">
                              <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="cbxVenta" name="cbxVenta">
                                <label class="form-check-label" for="flexSwitchCheckDefault">Venta</label>
                              </div>
                            </div>
                            <div class="col-lg-1">
                              <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="cbxFabricacion" name="cbxFabricacion">
                                <label class="form-check-label" for="flexSwitchCheckDefault">Fabricación</label>
                              </div>
                            </div>

                            <div class="col-lg-3" style="text-align:center!important;">
                              <!--<a href="#" class="swal2-cancel" id="btnAnadirAccion" onclick="validarAccionProductoTemp(`+$("#PKUsuario").val()+`)">Añadir operación</a>  -->
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-lg-12">
                              <!-- DataTales Example -->
                              <!--<div class="card-body">
                                <div class="table-responsive">
                                  <table class="table" id="tblListadoAccionesProductoTemp" width="100%" cellspacing="0">
                                    <thead>
                                      <tr>
                                        <th>Id</th>
                                        <th>Operación de producto</th>
                                      </tr>
                                    </thead>
                                  </table>
                                </div>
                              </div>-->
                            </div>
                          </div>
                        </div>
                        <br>



                        <label for="">* Campos requeridos</label>
                      </form>

                      <a href="#" class="swal2-cancel float-right" id="btnAgregarProducto">Guardar</a>
                    </div>
                  </div>
                </div>
              </div>