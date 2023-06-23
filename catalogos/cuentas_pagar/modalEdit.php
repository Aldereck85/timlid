<!-- Modal edit -->
<div class="modal fade bd-example-modal-lg" id="modaldcp" role="dialog">
    <div class="modal-dialog modal-full-height modal-right modal-md">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">x</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Formulario de Detalle</h4>
            </div>
            
            <!-- Modal Body -->
            <div class="modal-body">
                <p class="statusMsg"></p>
                <form role="form">
                    <div class="form-group">
                        <label  for="mdnombre">Proveedor</label>
                        <input disabled type="text" class="form-control" id="mdnombre" placeholder="Seleccione el Proveedor"/></input>
                        <input type="hidden" id="hidden_cuenta_id"/></input>
                    </div>
                    <div class="form-group">
                        <label for="inputClave">Clave</label>
                        <input disabled type="email" class="form-control" id="inputClave" placeholder="Enter your email" value=""/></input>
                    </div>
                    <div class="form-group">
                        <label for="inputCantidad">Cantidad</label>
                        <input class="form-control" id="inputCantidad" placeholder="Enter your message"></input>
                    </div>
                    <div class="form-group">
                        <label for="inputPrecio">Precio</label>
                        <input class="form-control" id="inputPrecio" placeholder="Enter your message"></input>
                    </div>
                    <div class="form-group">
                        <label for="inputDescuento">Descuento</label>
                        <input class="form-control" id="inputDescuento" placeholder="Enter your message"></input>
                    </div>
                    <div class="form-group">
                        <label for="inputIva">IVA</label>
                        <input class="form-control" id="inputIva" placeholder="Enter your message"></input>
                    </div>
                    <div class="form-group">
                        <label for="inputIeps">IEPS</label>
                        <input class="form-control" id="inputIeps" placeholder="Enter your message"></input>
                    </div>
                </form>
            </div>
            
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn-custom btn-custom--border-blue btnCancelarActualizacion" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn-custom btn-custom--blue" id="enviar">Guardar</button>
            </div>
        </div>
    </div>
</div>

