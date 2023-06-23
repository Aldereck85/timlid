 <!-- Modal Prospecto Declinado -->
 <div class="modal fade" id="InactivarLead" tabindex="-1" role="dialog" aria-labelledby="InactivarLeadLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="InactivarLeadLabel">Prospecto Declinado</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
          <input type="hidden" name="" id="id">
          <label for="motivo">Motivo:*</label> 
          <textarea type="text" class="form-control alphaNumeric-only p-1" rows="3" maxlength="250" name="motivo" id="motivo" required="required" placeholder="Ej. Solo compra X producto"></textarea>
          <label>Máximo de caracteres: <span id="contador"></span> restantes</label>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="btnEliminarContacto">Guardar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="ActivarLead" tabindex="-1" role="dialog" aria-labelledby="InactivarLeadLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="InactivarLeadLabel">Ascender a cliente</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formCrearCliente">
          <br>
          <div class="row">
            <div class="col-lg-12">
              <h5>Datos Contacto Cliente</h5>
            </div>
            <input type="text" name="" id="contacto_id" hidden="true">
            <input type="text" name="" id="nombreModal" hidden="true">
            <input type="text" name="" id="apellidoModal" hidden="true">
            <input type="text" name="" id="puestoModal" hidden="true">
            <input type="text" name="" id="celularModal" hidden="true">

            <div class="col-lg-4">
              <label for="email">Nombre comercial*:</label>
              <input type="text" class="form-control alphaNumeric-only" maxlength="100" name="empresa" id="empresaModal" placeholder="Ej. Timlid">
                <div class="invalid-feedback" id="invalid-nombre-comercial">El campo empresa es requerido.</div>
            </div>
            <div class="col-lg-4">
              <label for="usr">Medio de contacto:</label>
              <input type="text" class="form-control alphaNumeric-only"  name="medio_contacto" id="medio_contacto" readonly="true" value="Correo">
            </div>  
            <div class="col-lg-4">
              <label for="usr">Vendedor*:</label>
              <select name="propietario" id="propietarioModalVendedor" required >
                <option value="0">Seleccionar vendedor</option>
              </select>
              <div class="invalid-feedback" id="invalid-vendedor">El cliente debe tener un vendedor.</div>
            </div> 
            <div class="col-lg-6">
              <label for="">Teléfono:</label>
              <input type="text" class="form-control alphaNumeric-only" maxlength="100" name="" id="telefonoModal" placeholder="Ej. 33 33 33 33 33">
            </div> 
            <div class="col-lg-6">
              <label for="email">Email*:</label>
              <input type="email" class="form-control alphaNumeric-only" maxlength="100" name="" id="emailModal" placeholder="Ej. ejemplo@dominio.com">
                <div class="invalid-feedback" id="invalid-email">El campo empresa es requerido.</div>
            </div>
          </div>
          <br>
          <div class="row">
           <div class="col-lg-12">
            <h5>Agregar Crédito</h5>
          </div>
          <div class="col-lg-6">
            <label for="">Monto de crédito:</label>
            <input type="number" class="form-control numericDecimal-only" maxlength="6" name="" id="montoModal" placeholder="Ej. 1,000.00" step="0.01">
          </div> 
          <div class="col-lg-6">
            <label for="">Días de crédito:</label>
            <input type="number" class="form-control numeric-only" maxlength="100" name="" id="diasModal" placeholder="Ej. 30,60,90">
          </div> 
        </div>
        <br>
        <div class="row">
          <div class="col-lg-12">
            <h5>Datos Fiscales Cliente</h5>
          </div>
          <div class="col-lg-6">
            <label for="email">Razón Social*:</label>
            <input type="text" class="form-control alphaNumeric-only" maxlength="100" name="" id="razon_socialModal" placeholder="Ej. Timlid S.A de C.V">
              <div class="invalid-feedback" id="invalid-razon-social">El campo empresa es requerido.</div>
          </div>
          <div class="col-lg-6">
            <label for="email">RFC*:</label>
            <input type="text" class="form-control alphaNumeric-only" maxlength="13" name="" id="rfcModal" placeholder="Ej. XAXX010101000" onkeyup="validarInput(this)" >
              <span id="resultado" style="color: red"></span>
              <div class="invalid-feedback" id="invalid-rfc">El campo empresa es requerido.</div>
          </div>
          <div class="col-lg-4">
            <label for="email">Municipio:</label>
            <input type="text" class="form-control alphaNumeric-only" maxlength="100" name="" id="municipioModal" placeholder="Ej. Zapopan">
          </div>
          <div class="col-lg-4">
            <label for="email">Colonia:</label>
            <input type="text" class="form-control alphaNumeric-only" maxlength="100" name="" id="coloniaModal" placeholder="Ej. Los Agaves">
          </div>
          <div class="col-lg-4">
            <label for="email">Calle:</label>
            <input type="text" class="form-control alphaNumeric-only" maxlength="100" name="" id="calleModal" placeholder="Ej. Av. México">
          </div>
          
          <div class="col-lg-4">
            <label for="email">Número exterior:</label>
            <input type="text" class="form-control numeric-only" maxlength="5" name="" id="numero_exteriorModal" placeholder="Ej. 1525">
          </div>
          <div class="col-lg-4">
            <label for="email">Número interior:</label>
            <input type="text" class="form-control alphaNumeric-only" maxlength="5" name="" id="numero_interiorModal" placeholder="Ej. 1526">
          </div>
          <div class="col-lg-4">
            <label for="email">Código postal*:</label>
            <input type="text" class="form-control numeric-only" maxlength="5" name="" id="codigo_postalModal" placeholder="Ej. 45000">
              <div class="invalid-feedback" id="invalid-codigo-postal">El campo empresa es requerido.</div>
          </div>
          <div class="col-lg-6">
            <label for="email">Pais:</label>
            <input type="text" class="form-control alphaNumeric-only" disabled="" value="México" maxlength="100" name="" id="paisModal" >
          </div>
          <div class="col-lg-6">
            <label for="usr">Estado:</label>
            <select name="estado" id="estadoModal" onchange="loadState()">
              <option value="0" >Selecciona un estado federativo</option>
            </select>
          </div>
          
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button type="button" class="btnesp first espAgregar"  id="agregarCliente">Guardar</button>
    </div>
  </div>
</div>
</div>


<div class="modal fade" id="ModalContact" role="dialog">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title center">El contacto se ligara al cliente:
          <br><span id="ClientContact"></span> </h4>
      </div>
      <div class="modal-body">
        <p>Correos automaticos de:</p>
        <div class="form-check">
          <input type="checkbox" class="form-check-input" id="Check1" value="1">
          <label class="form-check-label" for="exampleCheck1">Facturación</label>
        </div>
        <div class="form-check">
          <input type="checkbox" class="form-check-input" id="Check2" value="1">
          <label class="form-check-label" for="exampleCheck1">Complemento de pago</label>
        </div>
        <div class="form-check">
          <input type="checkbox" class="form-check-input" id="Check3" value="1">
          <label class="form-check-label" for="exampleCheck1">Avisos de énvio</label>
        </div>
        <div class="form-check">
          <input type="checkbox" class="form-check-input" id="Check4" value="1">
          <label class="form-check-label" for="exampleCheck1">Pagos</label>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btnesp first espAgregar"  id="btnCreateContactCliente">Guardar Contacto</button>
      </div>
    </div>
  </div>
</div>


 <!-- Modal Editar Contacto -->
 <div class="modal" tabindex="-1" role="dialog" id="editarContactoModal">
     <div class="modal-dialog modal-lg" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title">Editar Contacto</h5>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">x</span>
                 </button>
             </div>
             <div class="modal-body">
                 <form>

                     <div class="row form-group">
                         <div class="col-lg-6">
                             <label for="empresa">Empresa:*</label>
                             <input type="text" class="form-control alphaNumeric-only" maxlength="100" name="empresa" id="empresaModal" required placeholder="Ej. GH Medic">
                         </div>
                         <div class="col-lg-6">
                             <label for="propietario">Vendedor:*</label>
                             <div class="row">
                                 <div class="col-lg-12 input-group">
                                     <select name="propietario" id="propietarioModal" required >
                                         <option value="" disabled selected hidden>Seleccionar vendedor</option>

                                     </select>
                                     <div class="invalid-feedback" id="invalid-vendedor">El cliente debe tener un vendedor.</div>
                                 </div>
                             </div>
                         </div>
                     </div>

                     <div class="row form-group">
                         <div class="col-lg-6">
                             <label for="nombre">Nombre:</label>
                             <input type="text" class="form-control alphaNumeric-only" maxlength="50" name="nombre" id="nombreModal" placeholder="Ej. John">
                         </div>
                         <div class="col-lg-6">
                             <label for="nombre">Apellido del contacto:</label>
                             <input type="text" class="form-control alphaNumeric-only" maxlength="50" name="apellido" id="apellidoModal" placeholder="Ej. López Pérez">
                         </div>
                     </div>

                     <div class="row form-group">
                         <div class="col-lg-6">
                             <label for="usr">Medio de contacto / Campaña:</label>
                             <div class="row">
                                 <div class="col-lg-12 input-group">
                                     <select name="campania" id="campaniaModal" required>
                                         <option value="1">Instagram</option>
                                         <option value="2">Facebook</option>
                                         <option value="3">Radio</option>
                                     </select>
                                     <div class="invalid-feedback" id="invalid-medioCont">El cliente debe tener un medio de contacto.</div>
                                 </div>
                             </div>
                         </div>
                         <div class="col-lg-6">
                             <label for="email">Email:</label>
                             <input type="text" class="form-control alphaNumeric-only" maxlength="100" name="email" id="emailModal" placeholder="Ej. ejemplo@dominio.com">
                         </div>
                     </div>

                     <div class="row form-group">
                         <div class="col-lg-6">
                             <label for="telefono">Teléfono:</label>
                             <input type="text" class="form-control alphaNumeric-only" name="teléfono" id="telefonoModal" maxlength="10" placeholder="Ej. 33 3333 3333">
                         </div>
                         <div class="col-lg-6">
                             <label for="celular">Celular:</label>
                             <input type="text" class="form-control alphaNumeric-only" name="celular" id="celularModal" maxlength="10" placeholder="Ej. 33 3333 3333">
                         </div>
                     </div>

                     <div class="row form-group">
                         <div class="col-lg-6">
                             <label for="puesto">Puesto:</label>
                             <input type="text" class="form-control alphaNumeric-only" name="puesto" id="puestoModal" maxlength="50" placeholder="Ej. Gerente de ventas">
                         </div>
                         <div class="col-lg-6">
                             <label for="usr">Estado:</label>
                             <select name="estado" id="estadoModal2" onchange="loadState()">
                             </select>
                         </div>
                     </div>

                     <div class="row form-group mt-4">
                         <div class="col-lg-12">
                             <label for="">* Campos requeridos</label>
                         </div>
                     </div>

                 </form>
             </div>
             <div class="modal-footer">
                 <button type="button" class="btnesp first espAgregar" id="btnEditarContacto">Guardar</button>
             </div>
         </div>
     </div>
 </div>

