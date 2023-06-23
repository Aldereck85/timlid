<!-- Diseño en php no funcional, solo es la base de lo que se aplico en el JS de pestanas_productos.js -->
<div class="card shadow mb-4">
                <div class="card-header">
                  Tarjeta de impuestos
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <form id="formDatosImpuesto"> 
                        <span id="areaDiseno">
                          <div class="form-group">
                            <div class="row">
                              <div class="col-lg-3">
                                <label for="usr">Impuesto</label>
                                <select class="cmbSlim" name="cmbImpuestos" id="cmbImpuestos" required="" onchange="cambioImpuesto()">
                                </select>
                              </div>
                              <div class="col-lg-3">
                                <label for="usr">Tipo</label>
                                <input type='hidden' value='1' name="txtTipoImpuesto" id="txtTipoImpuesto">
                                <div style="background:#0275d8;padding:5px;color:white;-webkit-border-radius: 16px; -moz-border-radius: 16px; border-radius: 16px;" id="trasladado"><center>Trasladado</center></div>
                                <div style="background:#f0ad4e;padding:5px;color:white;-webkit-border-radius: 16px; -moz-border-radius: 16px; border-radius: 16px;display: none;" id="retenciones"><center>Retenciones</center></div>
                                <div style="background:#5cb85c;padding:5px;color:white;-webkit-border-radius: 16px; -moz-border-radius: 16px; border-radius: 16px;display: none;" id="local"><center>Local</center></div>
                              </div>
                              <div class="col-lg-3">
                                <label for="usr" id="etiquetaImpuesto">Tasa:</label>
                                <input type='hidden' value='1' name="txtTipoTasa" id="txtTipoTasa">
                                <span id="areaimpuestos">
                                  <select class="cmbSlim" name="cmbTasaImpuestos" id="cmbTasaImpuestos" required="">
                                  </select> 
                                </span>   
                              </div>
                              <div class="col-lg-3" style="text-align:center!important; margin-top:25px;">
                                <a href="#" class="swal2-cancel" id="btnAnadirImpuesto">Añadir impuesto</a>  
                              </div>
                            </div>
                          </div>
                          <br>

                          <div class="form-group">
                            <!-- DataTales Example -->
                            <div class="card mb-4">
                              <div class="card-body">
                                <div class="table-responsive">
                                  <table class="table" id="tblListadoImpuestosProducto" width="100%" cellspacing="0">
                                    <thead>
                                      <tr>
                                        <th>Id</th>
                                        <th>Impuesto</th>
                                        <th>Tipo</th>
                                        <th>Tasa</th>
                                      </tr>
                                    </thead>
                                  </table>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="form-group">
                            <div class="row">
                              <div class="col-lg-12">
                                <label for="usr">Productos que lo componen</label>
                              </div>
                            </div>
                          </div>

                          <div class="form-group">
                            <!-- DataTales Example -->
                            <div class="card mb-4">
                              <div class="card-body">
                                <div class="table-responsive">
                                  <table class="table" id="tablaprueba" width="100%" cellspacing="0">
                                    <thead>
                                      <tr>
                                        <th>Clave/Producto*</th>
                                        <th>Cantidad</th>
                                        <th>Costo unitario</th>
                                        <th>Impuestos</th>
                                        <th>Importe</th>
                                        <th></th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <tr>
                                        <td>
                                          <input  name="txtProductos1" id="txtProductos1" type="hidden" readonly>
                                          <input type="text" class="form-control" name="cmbProductos1" id="cmbProductos1" data-toggle="modal" data-target="#agregar_Producto" 
                                          placeholder="Seleccione un producto..." readonly required="">
                                        </td>
                                        <td>
                                          <div class="row">
                                            <div class="col-lg-6">
                                              <input class="form-control" type="numeric" name="txtCantidadCompuesta" id="txtCantidadCompuesta" autofocus="" required="" placeholder="Ej. 10">
                                            </div>
                                            <div class="col-lg-6">
                                              <label  for="usr" name="lblUnidadMedida" id="lblUnidadMedida">Kilogramo</label>
                                            </div>
                                          </div>
                                        </td>
                                        <td>
                                          <label  for="usr" name="lblCostoUnitario" id="lblCostoUnitario">10.00</label>
                                        </td>
                                        <td>
                                          <label  for="usr" name="lblImpuestos" id="lblImpuestos">10.00</label>
                                        </td>
                                        <td>
                                          <label  for="usr" name="lblImporte" id="lblImporte">10.00</label>
                                        </td>
                                        <td>
                                          *
                                        </td>
                                      </tr>
                                      <tr>
                                        <td>
                                          <input  name="txtProductos2" id="txtProductos2" type="hidden" readonly>
                                          <input type="text" class="form-control" name="cmbProductos2" id="cmbProductos2" data-toggle="modal" data-target="#agregar_Producto" 
                                          placeholder="Seleccione un producto..." readonly required="">
                                        </td>
                                        <td>
                                          <div class="row">
                                            <div class="col-lg-6">
                                              <input class="form-control" type="numeric" name="txtCantidadCompuesta" id="txtCantidadCompuesta" autofocus="" required="" placeholder="Ej. 10">
                                            </div>
                                            <div class="col-lg-6">
                                              <label  for="usr" name="lblUnidadMedida" id="lblUnidadMedida">Kilogramo</label>
                                            </div>
                                          </div>
                                        </td>
                                        <td>
                                          <label  for="usr" name="lblCostoUnitario" id="lblCostoUnitario">10.00</label>
                                        </td>
                                        <td>
                                          <label  for="usr" name="lblImpuestos" id="lblImpuestos">10.00</label>
                                        </td>
                                        <td>
                                          <label  for="usr" name="lblImporte" id="lblImporte">10.00</label>
                                        </td>
                                        <td>
                                          *
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="form-group">
                            <button type="button" class="btn btn-primary mr-2" onclick="agregarFila()">Agregar Fila</button>
                          </div>

                          <label for="">* Campos requeridos</label>
                        </span>  
                      </form>

                      <a href="#" class="swal2-cancel float-right" id="btnAgregarImpuesto">Guardar</a>
                    </div>
                  </div>
                </div>
              </div>