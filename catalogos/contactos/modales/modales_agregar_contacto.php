<!-- Modal Editar Contacto -->
<div class="modal" tabindex="-1" role="dialog" id="agregarContactoModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <img src="../../img/crm/usuario_blanco.svg" width="40">
                <h5 style="padding-left: 2rem;" class="modal-title">Agregar Contacto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">x</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formEditContact">
                    <div class="row form-group">
                        <div class="col-lg-4">
                            <label for="nombreModalContacto">Nombre:*</label>
                            <input type="text" class="form-control alphaNumeric-only" maxlength="100" name="nombreModalContacto" id="nombreModalContacto" required placeholder="Ej. GH Medic" required>
                            <div class="invalid-feedback" id="invalid-nombre-contacto">El campo nombre es requerido.</div>
                        </div>
                        <div class="col-lg-4">
                            <label for="emailModalContacto">Email:*</label>
                            <input type="email" class="form-control alphaNumeric-only" maxlength="100" name="emailModalContacto" id="emailModalContacto" required placeholder="Ej. contacto@gmail.com" required>
                            <div class="invalid-feedback" id="invalid-email-contacto">El campo email es requerido.</div>
                        </div>
                        <div class="col-lg-4">
                            <label for="celularModalContacto">Celular:*</label>
                            <input type="text" class="form-control alphaNumeric-only" maxlength="100" name="celularModalContacto" id="celularModalContacto" required placeholder="Ej. 33 3333 3333" required>
                            <div class="invalid-feedback" id="invalid-celular-contacto">El campo celular es requerido.</div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btnesp first espAgregar" id="btnAgregarContacto">Guardar</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- DataTales Example -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table" id="tblContactosProspectos" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Email</th>
                                            <th>Celular</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->
            </div>
        </div>
    </div>
</div>