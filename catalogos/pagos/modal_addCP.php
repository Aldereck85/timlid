<!-- Modal Add -->
<div class="modal fade bd-example-modal-lg" id="mod_agregarFacturas" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg"  style="max-width:1400px" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Agregar Facturas</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">x</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="col-xl-3 col-lg-6 col-md col-sm col-xs">                      
                <div class="form-group">
                    <label for="usr">Proveedor:</label>
                    <input type="text" id="txtProveeModal" class="form-control disabled">
                </div>
            </div>
            <div class="container-fluid">
               <!-- DataTales Example -->
               <div class="card mb-4 h-100">
                  <div class="card-body">
                    <div class="table-responsive">
                        <table style="width:100%" class="table" id="tblFactura" cellspacing="0">
                            <thead>
                                    <tr>
                                    <th>Proveedor</th>
                                    <th>Folio de Factura</th>
                                    <th>Serie de Factura</th>
                                    <th>Fecha de Vencimiento</th>
                                    <th>Importe</th>
                                    <th>Saldo insoluto</th>
                                    <th>Estatus</th>
                                    <th>Id</th>
                                    <th>Agregar</th>
                                    </tr>
                                </thead>
                            </table>
                    </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-rigth">
              <button type="button" class="btnesp first espCancelar btnCancelar" data-dismiss="modal" id="btnCancelarSeleccion"><span class="ajusteProyecto">Cancelar</span></button>
              <button type="button" class="btnesp espAgregar float-right" name="btnAgregar" id="btnAgregarFacturas"><span class="ajusteProyecto">Agregar</span></button>
            </div>
        </div>
    </div>
  </div>
</div>