<!-- Modal product sell-->
<div class="modal fade" id="modal_product_sales" tabindex="-1" aria-labelledby="exampleModalLabel" aria-modal="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Venta de productos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form action="">
                    <input type="hidden" name="txtPaymentType" id="txtPaymentType" value="1">
                    <div class="btn-toolbar mb-4" role="toolbar" aria-label="Group buttons product sales ">

                        <div class="btn-group mx-auto " role="group" aria-label="First group" >
                            <a href="#" id="cash-payment">
                                <img src="../../img/punto_venta/ICONO PAGO EN EFECTIVO-01-01.svg" width="80" alt="" data-toggle="tooltip" data-placement="bottom" title="Pago en efectivo"> 
                            </a>
                        </div>

                        <div class="btn-group mx-auto" role="group" aria-label="Second group">
                            <a href="#" id="credit-payment">
                                <img src="../../img/punto_venta/ICONO PAGO CON TARJETA-01-01.svg" width="80" alt="" data-toggle="tooltip" data-placement="bottom" title="Pago con tarjeta Débito/Crédito"> 
                            </a>
                        </div>
                        
                        <div class="btn-group mx-auto" role="group" aria-label="Third group">
                            <a href="#" id="bank-transfer">
                                <img src="../../img/punto_venta/ICONO PAGO TRANSFERENCIA-01-01.svg" width="80" alt="" data-toggle="tooltip" data-placement="bottom" title="Pago con transferencia">
                            </a>
                        </div>
                        
                    </div>
                    <hr>

                    <div class="text-center">
                        <input type="hidden" name="txtImporteTotalVenta" id="txtImporteTotalVenta">
                        <h4>Importe: $<span id="txtImporteTotalVentaH4"></span> </h4>
                    </div>
                
                    <div id="cash-payment-data">
                        <hr>
                        <p class="text-between"><span>Datos para pagos en efectivo</span></p>
                        <div class="form-group">
                            <label for="txtMontoRecibido">Monto recibido:</label>
                            <input class="form-control decimal numericDecimal-only" type="text" name="txtMontoRecibido" id="txtMontoRecibido" autofocus>
                        </div>
                        <div class="form-group">
                            <label for="txtMontoRecibido">Cambio:</label>
                            <input class="form-control" type="text" name="txtMontoCambio" id="txtMontoCambio" disabled> 
                        </div>
                    </div>

                    <div id="credit-payment-data">
                        <hr>
                        <p class="text-between"><span>Datos para pagos con tarjeta de crédito</span></p>
                        <div class="form-group">
                            <label for="txtApprovedCredit">No. Aprobación</label>
                            <input type="text" name="txtApprovedCredit" id="txtApprovedCredit" class="form-control alphaNumeric-only">
                            <div class="invalid-feedback" id="invalid-approved_credit">El pago en efectivo debe tener un monto recibido.</div>
                        </div>
                    </div>

                    <div id="bank-transfer-details">
                        <hr>
                        <p class="text-between"><span>Datos para pagos con transferencia</span></p>
                        <div class="form-group">
                            <label for="txtApprovedTransfer">No. Aprobación</label>
                            <input type="text" name="txtApprovedTransfer" id="txtApprovedTransfer" class="form-control alphaNumeric-only">
                            <div class="invalid-feedback" id="invalid-approved_transfer">El pago en efectivo debe tener un monto recibido.</div>
                        </div>
                    </div>

                </form>

            </div>
            
            <div class="modal-footer justify-content-center">
                <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal" id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
                <button type="button" class="btn-custom btn-custom--blue espAgregar" name="btnAgregar" id="btnSaveTicket"><span class="ajusteProyecto">Guardar</span></button>
            </div>
        </div>
        </div>
    </div>
</div>