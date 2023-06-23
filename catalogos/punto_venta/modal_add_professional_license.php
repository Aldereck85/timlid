<div class="modal fade" id="modal_add_professional_license" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title w-100" id="myModalLabel">Agregar cédula profesional</h4>
      </div>
      <div class="modal-body">
        <form id="form-add-professional-license">
          <div class="form-group row">
            <label for="txtProfessionalLicense">Cédula profesional:*</label>
            <input class="form-control" type="text" name="txtProfessionalLicense" id="txtProfessionalLicense" required>
            <div class="invalid-feedback" id="invalid-professionalLicense">Debe ingresar la cédula profesional.</div>
          </div>
        </form>
      </div>
      <div class="modal-footer justify-content-center">
        <button type="button" class="btnesp first espCancelar btnCancelarActualizacion mx-2" data-dismiss="modal"
          id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
        <button type="button" class="btn-custom btn-custom--blue espAgregar mx-2" name="btnAddProduct" id="btnAddProfessionalLicense" onclick="saveProfesionalLicencse();"><span
          class="ajusteProyecto">Guardar</span></button>
      </div>
    </div>
  </div>
</div>