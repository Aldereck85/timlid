<!-- Modal alert -->
<div class="modal fade bd-example-modal-lg" id="mod_agregarFacturas" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg"  style="max-width:1400px" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Agregar Documentos</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">x</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="col-xl-3 col-lg-6 col-md col-sm col-xs">                      
                <div class="form-group">
                    <label for="usr">Cliente:</label>
                    <input type="text" id="txtCliente" class="form-control disabled">
                </div>
            </div>
            <div class="table-responsive">
                <table style="width:100%" class="table" id="tblFactura" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Folio</th>
                            <th>F. Expedición</th>
                            <th>F. Vencimiento</th>
                            <th>Método de pago</th>
                            <th>Forma de pago</th>
                            <th>Monto factura</th>
                            <th>Saldo anterior</th>
                            <th>Saldo insoluto</th>
                            <th>No. Parcialidad</th>
                            <th></th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="modal-footer justify-content-rigth">
              <button type="button" class="btnesp first espCancelar btnCancelar" data-dismiss="modal" id="btnCancelarSeleccion"><span class="ajusteProyecto">Cerrar</span></button>
              <button type="button" class="btnesp espAgregar float-right" name="btnAgregar" id="btnAgregarFacturas"><span class="ajusteProyecto">Aceptar</span></button>
            </div>
        </div>
    </div>
  </div>
</div>


