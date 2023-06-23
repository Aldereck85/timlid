<!-- Modal alert -->
<div class="modal fade bd-example-modal-lg" id="modalAbonos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg"  style="max-width:900px" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Parcialidades</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">x</span>
            </button>
        </div>
        <div class="modal-body">
            <div id="divModCabeceraAbonos">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">
                            <label for="usr">Monto de parcialidad:</label>
                            <div class="input-group pegar">
                                <div class="signoPesos" for="usr">$</div>
                                <input type="number" id="txtModMontoAbono" class="form-control" value="0">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">
                            <label for="usr">Fecha de parcialidad:</label>
                            <input type="date" class="form-control" id="txtFechaParcialidad" max="<?php echo (date('Y-m-d'));?>">
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">                      
                            <div class="form-group">
                                <label for="usr">Saldo insoluto:</label>
                                <div class="input-group pegar">
                                    <div class="signoPesos" for="usr">$</div>
                                    <input type="txt" id="txtModSaldoInsoluto" class="form-control disabled" value="0">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">                      
                            <div class="form-group">
                                <label for="usr">Cuenta bancaria:</label>
                                <select name="cmbCuenta" id="chosenCuenta">
                                    <option disabled selected value="f">Seleccione una cuenta</option>
                                </select>
                                <div class="invalid-feedback" id="invalid-cuenta">gg</div>
                            </div>
                        </div>
                    </div>
                    <br><br>
                </div>
                <button type="button" class="btnesp espAgregar float-right" name="btnModAgregarAbonos" id="btnModAgregarAbono"><span class="ajusteProyecto">Agregar parcialidad</span></button>
                <br><br><br>
            </div>
            <div class="table-responsive">
                <table style="width:100%" class="table" id="tblModAbonos" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Monto de parcialidad</th>
                            <th>Nombre de usuario</th>
                            <th>Eliminar</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="modal-footer justify-content-rigth">
                <button type="button" class="btnesp espAgregar float-right" name="btnModCerrar" id="btnModCerrar"><span class="ajusteProyecto">Cerrar</span></button>
            </div>
        </div>
    </div>
  </div>
</div>