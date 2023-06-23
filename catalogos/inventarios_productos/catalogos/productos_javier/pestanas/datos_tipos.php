<!-- Diseño en php no funcional, solo es la base de lo que se aplico en el JS de pestanas_productos.js -->
<div class="card shadow mb-4">
                <div class="card-header">
                  Tarjeta de tipo de producto
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <form id="formDatosTipoProducto"> 

                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-6">
                              <label for="usr">Tipo de producto</label>
                              <select class="cmbSlim" name="cmbAccionesProducto" id="cmbAccionesProducto" required="">
                              </select>
                            </div>
                            <div class="col-lg-6" style="text-align:center!important; margin-top:25px;">
                              <a href="#" class="swal2-cancel" id="btnAnadirAccion">Añadir tipo de producto</a>  
                            </div>
                          </div>
                        </div>
                        <br>

                        <div class="form-group">
                          <!-- DataTales Example -->
                          <div class="card mb-4">
                            <div class="card-body">
                              <div class="table-responsive">
                                <table class="table" id="tblListadoAccionesProducto" width="100%" cellspacing="0">
                                  <thead>
                                    <tr>
                                      <th>Id</th>
                                      <th>Tipo de producto</th>
                                    </tr>
                                  </thead>
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>

                        <label for="">* Campos requeridos</label>
                      </form>

                      <a href="#" class="swal2-cancel float-right" id="btnAgregarTipoProducto">Guardar</a>
                    </div>
                  </div>
                </div>
              </div>