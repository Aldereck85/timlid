<!--ADD MODAL PERSONAL-->
<div class="modal fade right" id="agregar_Personal" tabindex="-1" role="dialog" aria-labelledby="modalAgregarPersonal"
      aria-hidden="true">
      <div class="modal-dialog modal-full-height modal-right modal-md" role="document">
        <div class="modal-content">
          <form id="agregarPersonal" method="POST">
            <div class="modal-header">
              <h4 class="modal-title w-100">Agregar personal</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">x</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label for="txtNombrePersonal">Nombre(s):*</label>
                <input type="text" class="form-control alpha-only" maxlength="50" name="txtNombrePersonal" id="txtNombrePersonal" required
                onkeyup="validEmptyInput(this)">
                <div class="invalid-feedback" id="invalid-nombre">El empleado debe tener un nombre.</div>
              </div>
              <div class="form-group">
                <label for="txtPrimerApellidoPersonal">Primer apellido:*</label>
                <input type="text" class="form-control alpha-only" name="txtPrimerApellidoPersonal id="txtPrimerApellidoPersonal" maxlength="50"
                  onkeyup="validEmptyInput(this)" required>
                <div class="invalid-feedback" id="invalid-primerApellido">El empleado debe tener un primer apellido.</div>
              </div>
              <div class="form-group">
                <label for="cmbGenero">Genero:</label>
                <select name="cmbGenero" id="cmbGenero">
                  <option data-placeholder="true"></option>
                  <option value="Masculino">Masculino</option>
                  <option value="Femenino">Femenino</option>
                </select>
                <div class="invalid-feedback" id="invalid-genero">El empleado debe tener un género.</div>
              </div>
              <div class="form-group">
                <label for="cmbEstadoPersonal">Estado:*</label>
                <select name="cmbEstadoPersonal" id="cmbEstadoPersonal" onchange="validEmptyInput(this)" required>
                  <option data-placeholder="true"></option>
                  <?php
                    $stmt = $conn->prepare("SELECT * FROM estados_federativos");
                    $stmt->execute();
                    $row = $stmt->fetchAll();
                    if (count($row) > 0) {
                        foreach ($row as $r) { //Mostrar estados
                            echo '<option value="' . $r['PKEstado'] . '" >' . $r['Estado'] . '</option>';
                        }
                    } else {
                        echo '<option value="" disabled>No hay registros para mostrar.</option>';
                    }
                  ?>
                </select>
                <div class="invalid-feedback" id="invalid-estado">El empleado debe tener un estado.</div>
              </div>
              <!-- <div class="form-group">
                <label for="cmbRoles">Roles:*</label>
                <select name="cmbRoles" id="cmbRoles" onchange="validEmptyInput(this)" multiple required>
                  <option data-placeholder="true"></option>
                  <?php
                    // $stmt = $conn->prepare("SELECT * FROM tipo_empleado");
                    // $stmt->execute();
                    // $row = $stmt->fetchAll();
                    // foreach ($row as $r) { //Mostrar roles
                    //     echo '<option value="' . $r['id'] . '" >' . $r['tipo'] . '</option>';
                    // }
                  ?>
                </select>
                <div class="invalid-feedback" id="invalid-roles">El empleado debe tener al menos un rol.</div>
              </div> -->
            </div>
            <div class="modal-footer justify-content-center">
              <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal"
                id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
              <button type="button" class="btnesp espAgregar float-right" name="btnAgregarPersonal" id="btnAgregarPersonal"><span
                  class="ajusteProyecto">Agregar</span></button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- END ADD MODAL PERSONAL -->