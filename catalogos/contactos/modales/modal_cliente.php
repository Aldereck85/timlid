<!-- MODAL PROSPECTO DECLINADO -->
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
                <button type="button" class="btn-custom btn-custom--blue" id="btnEliminarContacto">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ASCENDER A CLIENTE -->
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
                <div class="card">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link active" id="nav-tab-activos" data-toggle="tab" href="#nav-cliente-existente" role="tab" aria-controls="nav-cliente-existente" aria-selected="false">Cliente existente</a>
                            <a class="nav-item nav-link" id="nav-tab-inactivos" data-toggle="tab" href="#nav-cliente-nuevo" role="tab" aria-controls="nav-cliente-nuevo" aria-selected="false">Cliente nuevo</a>
                        </div>
                    </nav>
                    <div class="card-body">
                        <div class="tab-content" id="nav-tabCliente">
                            <div class="tab-pane fade show active" id="nav-cliente-existente" role="tabpanel" aria-labelledby="nav-cliente-existente-tab">
                                <form id="formClienteExistente" class="row">
                                    <div class="col-lg-3 mb-2">
                                        <label for="clientesModalExistente">Cliente*:</label>
                                        <select name="clientesModalExistente" id="clientesModalExistente">
                                            <option value="0">Seleccionar cliente</option>
                                        </select>
                                        <div class="invalid-feedback" id="invalid-cliente-existente">El campo cliente es requerido.</div>
                                    </div>
                                    <div class="col-lg-3 mb-2">
                                        <label for="nombreModalExistente">Nombre*:</label>
                                        <input type="text" class="form-control alphaNumeric-only" name="nombreModalExistente" id="nombreModalExistente" placeholder="Ej. Juan Lopez" required>
                                        <div class="invalid-feedback" id="invalid-nombre-existente">El campo nombre es requerido.</div>
                                    </div>
                                    <div class="col-lg-3 mb-2">
                                        <label for="emailModalExistente">Email*:</label>
                                        <input type="email" class="form-control alphaNumeric-only" name="emailModalExistente" id="emailModalExistente" placeholder="Ej. ejemplo@dominio.com" required>
                                        <div class="invalid-feedback" id="invalid-email-existente">El campo email es requerido.</div>
                                    </div>
                                    <div class="col-lg-3 mb-2">
                                        <label for="celularModalExistente">Celular*:</label>
                                        <input type="text" class="form-control alphaNumeric-only" name="celularModalExistente" id="celularModalExistente" placeholder="Ej. 33 3333 3333" required>
                                        <div class="invalid-feedback" id="invalid-celular-existente">El campo celular es requerido.</div>
                                    </div>
                                    <div class="col-12 d-flex justify-content-end mt-4"><button type="button" class="btnesp first espAgregar" id="agregarClienteExistente">Guardar</button></div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="nav-cliente-nuevo" role="tabpanel" aria-labelledby="nav-cliente-nuevo-tab">
                                <form id="formCrearCliente">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <h5>Datos del Contacto</h5>
                                        </div>
                                        <input type="text" name="" id="contacto_id" hidden="true">
                                        <!-- <input type="text" name="" id="nombreModal" hidden="true">
                                        <input type="text" name="" id="apellidoModal" hidden="true">
                                        <input type="text" name="" id="puestoModal" hidden="true">
                                        <input type="text" name="" id="celularModal" hidden="true"> -->
                                        <div class="col-lg-4 mb-2">
                                            <label for="nombreContacto">Nombre del contacto*:</label>
                                            <input type="text" class="form-control alphaNumeric-only" name="nombreContacto" id="nombreContacto" placeholder="Ej. Juan Luis" required>
                                            <div class="invalid-feedback" id="invalid-nombre-contacto">El campo nombre del contacto es requerido.</div>
                                        </div>
                                        <div class="col-lg-4 mb-2">
                                            <label for="apellidoContacto">Apellido del contacto:</label>
                                            <input type="text" class="form-control alphaNumeric-only" name="apellidoContacto" id="apellidoContacto" placeholder="Ej. Sanchez">
                                        </div>
                                        <div class="col-lg-4 mb-2">
                                            <label for="puestoContacto">Puesto del contacto:</label>
                                            <input type="text" class="form-control alphaNumeric-only" name="puestoContacto" id="puestoContacto" placeholder="Ej. Gerente de ventas">
                                        </div>
                                        <div class="col-lg-4 mb-2">
                                            <label for="telefonoModal">Teléfono:</label>
                                            <input type="text" class="form-control alphaNumeric-only" name="telefonoModal" id="telefonoModal" placeholder="Ej. 33 33 33 33 33">
                                        </div>
                                        <div class="col-lg-4 mb-2">
                                            <label for="emailModal">Email*:</label>
                                            <input type="email" class="form-control alphaNumeric-only" name="emailModal" id="emailModal" placeholder="Ej. ejemplo@dominio.com">
                                            <div class="invalid-feedback" id="invalid-email">El campo empresa es requerido.</div>
                                        </div>
                                        <div class="col-lg-4 mb-2">
                                            <label for="celularModal">Celular*:</label>
                                            <input type="text" class="form-control alphaNumeric-only" name="celularModalNuevo" id="celularModalNuevo" placeholder="Ej. 33 33 33 33 33">
                                            <div class="invalid-feedback" id="invalid-celular">El campo celular es requerido.</div>
                                        </div>
                                        <div class="col-lg-4 mb-2">
                                            <label for="medioModal">Medio de contacto*:</label>
                                            <select name="medioModal" id="medioModal">
                                                <option disabled>Medio de contacto</option>
                                            </select>
                                            <div class="invalid-feedback" id="invalid-medio">El campo medio de contacto es requerido.</div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-lg-12">
                                            <h5>Datos del Cliente</h5>
                                        </div>
                                        <div class="col-lg-4 mb-2">
                                            <label for="razonSocialModal">Razón Social*:</label>
                                            <input type="text" class="form-control alphaNumeric-only" name="razonSocialModal" id="razonSocialModal" placeholder="Ej. Timlid S.A de C.V">
                                            <div class="invalid-feedback" id="invalid-razon-social">El razón social es requerido.</div>
                                        </div>
                                        <div class="col-12 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="clienteFacturacion">
                                                <label class="form-check-label" for="clienteFacturacion">
                                                    Cliente para facturación
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="datos-cliente-facturacion" class="row mt-3 d-none">
                                        <div class="col-lg-4 mb-2">
                                            <label for="empresaModal">Nombre comercial*:</label>
                                            <input type="text" class="form-control alphaNumeric-only" name="empresaModal" id="empresaModal" placeholder="Ej. Timlid" required>
                                            <div class="invalid-feedback" id="invalid-nombre-comercial">El campo empresa es requerido.
                                            </div>
                                        </div>
                                        <div class="col-lg-4 mb-2">
                                            <label for="propietarioModalVendedor">Vendedor*:</label>
                                            <select name="propietarioModalVendedor" id="propietarioModalVendedor" required>
                                                <option value="0">Seleccionar vendedor</option>
                                            </select>
                                            <div class="invalid-feedback" id="invalid-vendedor">El cliente debe tener un vendedor.</div>
                                        </div>
                                        <div class="col-lg-4 mb-2">
                                            <label for="rfcModal">RFC*:</label>
                                            <input type="text" class="form-control alphaNumeric-only" maxlength="13" name="rfcModal" id="rfcModal" placeholder="Ej. XAXX010101000" onchange="validarInput(this)">
                                            <div class="invalid-feedback" id="invalid-rfc">El campo rfc es requerido.</div>
                                        </div>
                                        <div class="col-lg-4 mb-2">
                                            <label for="regimenModal">Régimen Fiscal*:</label>
                                            <select name="regimenModal" id="regimenModal" required>
                                                <option value="0">Seleccionar régimen</option>
                                            </select>
                                            <div class="invalid-feedback" id="invalid-regimen">El campo régimen fiscal es requerido.</div>
                                        </div>
                                        <div class="col-lg-4 mb-2">
                                            <label for="sitioWebModal">Sitio web:</label>
                                            <input type="text" class="form-control alphaNumeric-only" name="sitioWebModal" id="sitioWebModal" placeholder="Ej. Zapopan">
                                        </div>
                                        <div class="col-lg-4 mb-2">
                                            <label for="municipioModal">Municipio:</label>
                                            <input type="text" class="form-control alphaNumeric-only" name="municipioModal" id="municipioModal" placeholder="Ej. Zapopan">
                                        </div>
                                        <div class="col-lg-4 mb-2">
                                            <label for="coloniaModal">Colonia:</label>
                                            <input type="text" class="form-control alphaNumeric-only" name="coloniaModal" id="coloniaModal" placeholder="Ej. Los Agaves">
                                        </div>
                                        <div class="col-lg-4 mb-2">
                                            <label for="email">Calle:</label>
                                            <input type="text" class="form-control alphaNumeric-only" name="" id="calleModal" placeholder="Ej. Av. México">
                                        </div>

                                        <div class="col-lg-4 mb-2">
                                            <label for="numero_exteriorModal">Número exterior:</label>
                                            <input type="text" class="form-control numeric-only" maxlength="5" name="numero_exteriorModal" id="numero_exteriorModal" placeholder="Ej. 1525">
                                        </div>
                                        <div class="col-lg-4 mb-2">
                                            <label for="numero_interiorModal">Número interior:</label>
                                            <input type="text" class="form-control alphaNumeric-only" maxlength="5" name="numero_interiorModal" id="numero_interiorModal" placeholder="Ej. 1526">
                                        </div>
                                        <div class="col-lg-4 mb-2">
                                            <label for="codigo_postalModal">Código postal*:</label>
                                            <input type="text" class="form-control numeric-only" maxlength="5" name="codigo_postalModal" id="codigo_postalModal" placeholder="Ej. 45000">
                                            <div class="invalid-feedback" id="invalid-codigo-postal">El campo código postal es requerido.</div>
                                        </div>
                                        <div class="col-lg-4">
                                            <label for="modalPais">Pais*:</label>
                                            <select name="modalPais" id="modalPais"></select>
                                            <div class="invalid-feedback" id="invalid-pais">El campo pais es requerido.</div>
                                        </div>
                                        <div class="col-lg-4">
                                            <label for="estadoModal">Estado*:</label>
                                            <select name="estadoModal" id="estadoModal">
                                            </select>
                                            <div class="invalid-feedback" id="invalid-estado">El campo estado es requerido.</div>
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-12 d-flex justify-content-end">
                                            <button type="button" class="btnesp first espAgregar" id="agregarCliente">Guardar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ModalContact" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title center">El contacto se ligara al cliente:
                    <br><span id="ClientContact"></span>
                </h4>
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
                <button type="button" class="float-right btn-custom btn-custom--gray" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btnesp first espAgregar" id="btnCreateContactCliente">Guardar Contacto
                </button>
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
                            <input type="text" class="form-control alphaNumeric-only" name="empresa" id="empresaModal" required placeholder="Ej. GH Medic">
                        </div>
                        <div class="col-lg-6">
                            <label for="propietario">Vendedor:*</label>
                            <div class="row">
                                <div class="col-lg-12 input-group">
                                    <select name="propietario" id="propietarioModal" required>
                                        <option value="" disabled selected hidden>Seleccionar vendedor</option>

                                    </select>
                                    <div class="invalid-feedback" id="invalid-vendedor">El cliente debe tener un
                                        vendedor.
                                    </div>
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
                                    <div class="invalid-feedback" id="invalid-medioCont">El cliente debe tener un medio
                                        de contacto.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <label for="email">Email:</label>
                            <input type="text" class="form-control alphaNumeric-only" name="email" id="emailModal" placeholder="Ej. ejemplo@dominio.com">
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