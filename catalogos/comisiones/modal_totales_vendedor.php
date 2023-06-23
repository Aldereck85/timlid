<!-- Modal alert -->
<div class="modal fade bd-example-modal-lg" id="mdlTotalesVendedor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg"  style="max-width:1000px" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Ver totales</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">x</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="col-xl-5 col-lg-6 col-md col-sm col-xs">                      
                <div class="form-group">
                    <label for="usr">Vendedor:</label>
                    <input class="form-control disabled" type="txt" id="txtMdlVendedor">
                </div>
            </div>
            <br><br>
            <div class="row">
                <div class="col-sm-6">
                    <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">
                        <label for="usr">Total de comisiones pagadas:</label>
                        <div class="input-group pegar">
                            <div class="signoPesos" for="usr">$</div>
                            <input type="txt" id="txtModTotalComPagadas" class="form-control disabled">
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="col-xl-8 col-lg-6 col-md col-sm col-xs">
                        <label for="usr">Total de saldo insoluto:</label>
                        <div class="input-group pegar">
                            <div class="signoPesos" for="usr">$</div>
                            <input type="txt" id="txtModTotalSaldoInsoluto" class="form-control disabled">
                        </div>
                    </div>
                </div>
            </div>
            <br><br><br>
            <div class="table-responsive">
                <table style="width:100%" class="table" id="tblModCalVendedor" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Número de cálculo</th>
                            <th>Fecha</th>
                            <th>Monto calculado</th>
                            <th>Monto ingresado</th>
                            <th>Saldo insoluto</th>
                            <th>Estatus</th>
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