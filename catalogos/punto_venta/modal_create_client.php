<div class="modal fade right" id="modal_create_client" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
  <div class="modal-dialog modal-full-height modal-right modal-md" role="document">
    <div class="modal-content">
      <form action="" id="agregarCliente" method="POST">
        <div class="modal-header">
          <h4 class="modal-title w-100" id="myModalLabel">Agregar cliente</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">x</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="usr">Razón social:*</label>
            <input class="form-control" type="text" name="txtRazonSocial" id="txtRazonSocial" autofocus="" required="" maxlength="100" placeholder="Ej. GH Medic" onchange="escribirRazonSocial()" style="text-transform: uppercase">
            <div class="invalid-feedback" id="invalid-razon">El cliente debe tener razón social.</div>
            <div class="invalid-feedback" id="invalid-razonTipoSociedad">La razón social no debe tener el tipo de sociedad.</div>
          </div>
          <div class="form-group">
            <label for="usr">Teléfono:</label>
            <input type="text" id="txtTelefono_Cl" maxlength="10" class="form-control numeric-only" name="txtTelefono_Cl"
            onkeyup="validaNumTelefono(event,'txtTelefono_Cl', 'invalid-telCl', 'result2')">
            <div class="invalid-feedback" id="invalid-telCl">El número de teléfono debe ser válido.</div>
            <input type="hidden" id="result2" readonly>
          </div>
          <div class="form-group DataClient_invoice w-100" style="display: none;">
            <label for="usr">Nombre comercial:</label>
            <input class="form-control" type="text" name="txtNombreComercial" id="txtNombreComercial" autofocus="" maxlength="255" placeholder="Ej. GH Medic" onkeyup="escribirNombre()" style="text-transform: uppercase">
            <div class="invalid-feedback" id="invalid-nombreCom">El cliente debe tener un nombre comercial.</div>
          </div>
          <div class="form-group DataClient_invoice w-100"  style="display: none;">
            <label for="usr">RFC:**</label>
            <input class="form-control alphaNumeric-only" type="text" name="txtRFC" id="txtRFC" maxlength="13" placeholder="Ej. GHMM100101AA1" onchange="validInput('txtRFC', 'invalid-rfc', 'El cliente debe tener RFC.')" oninput="let p=this.selectionStart;this.value=this.value.toUpperCase();this.setSelectionRange(p, p);">
            <div class="invalid-feedback" id="invalid-rfc">El cliente debe tener RFC.</div>
          </div>
          <div class="form-group DataClient_invoice w-100" style="display: none;">
            <label for="usr">Régimen fiscal:**</label>
            <select name="cmbRegimen" id="cmbRegimen" onchange="validInput('cmbRegimen', 'invalid-regimen', 'El cliente debe tener régimen fiscal.')">
            </select>
            <div class="invalid-feedback" id="invalid-regimen">El cliente debe tener régimen fiscal.</div>
          </div>
          <div class="form-group DataClient_invoice w-100" style="display: none;">
            <label for="usr">Medio de contacto:</label>
            <select name="cmbMedioContactoCliente" id="cmbMedioContactoCliente">
            <!-- <select name="cmbMedioContactoCliente" id="cmbMedioContactoCliente" onchange="validInput('cmbMedioContactoCliente', 'invalid-medioCont', 'El cliente debe tener un medio de contacto.')"> -->
            </select>
            <!-- <div class="invalid-feedback" id="invalid-medioCont">El cliente debe tener un medio de contacto.</div> -->
          </div>
          <div class="form-group DataClient_invoice w-100" style="display: none;">
            <label for="usr">Vendedor:</label>
            <select name="cmbVendedorNC" id="cmbVendedorNC">
            <!-- <select name="cmbVendedorNC" id="cmbVendedorNC" onchange="validInput('cmbVendedorNC', 'invalid-vendedor', 'El cliente debe tener un vendedor.')"> -->
            </select>
            <!-- <div class="invalid-feedback" id="invalid-vendedor">El cliente debe tener un vendedor.</div> -->
          </div>
          <div class="form-group DataClient_invoice w-100" style="display: none;">
            <label for="usr">E-mail:</label>
            <input class="form-control" type="email" name="txtEmail" id="txtEmail" autofocus="" maxlength="100" placeholder="Ej. ejemplo@dominio.com" onchange="validarCorreo(this.value, 'txtEmail', 'invalid-email')">
            <div class="invalid-feedback" id="invalid-email">E-mail inválido.</div>
          </div>
          <div class="form-group DataClient_invoice w-100" style="display: none;">
            <label for="usr">Código postal:**</label>
            <input class="form-control numeric-only" type="text" name="txtCP" id="txtCP" autofocus="" maxlength="5" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ej. 52632" onkeyup="validarCP('txtCP', 'invalid-cp');"">
            <div class="invalid-feedback" id="invalid-cp">El cliente debe tener un codigo postal.</div>
          </div>
          <div class="form-group DataClient_invoice w-100" style="display: none;">
            <label for="usr">País:</label>
            <select name="cmbPais" class="" id="cmbPais">
            <!-- <select name="cmbPais" class="" id="cmbPais" onchange="validInput('cmbPais', 'invalid-paisFisc', 'El cliente debe tener un país.')"> -->
            <option data-placeholder="true"></option>
            <?php
              $stmt = $conn->prepare("SELECT * FROM paises");
              $stmt->execute();
              $row = $stmt->fetchAll();

              if (count($row) > 0) {
                foreach ($row as $r) { //Mostrar usuarios
                  if ($r['Disponible'] == 1) {
                    echo '<option value="' . $r['PKPais'] . '">' . $r['Pais'] . '</option>';
                    $pais = $r['PKPais'];
                  } else {
                    //echo '<option value="'.$r['PKPais'].'">'.$r['Pais'].'</option>';
                  }
                }
              } else {
                echo '<option value="" disabled>No hay registros para mostrar.</option>';
              }
              ?>
            </select>
            <div class="invalid-feedback" id="invalid-paisFisc">El cliente debe tener un país.</div>
            <!--<input type="text" id="txtarea6" class="form-control alpha-only" size="40" name="txtEstado" required>-->
          </div>
          <div class="form-group DataClient_invoice w-100" style="display: none;">
            <label for="usr">Estado:</label>
            <select name="cmbEstado" class="" id="cmbEstado">
            <!-- <select name="cmbEstado" class="" id="cmbEstado" onchange="validInput('cmbEstado', 'invalid-paisEstadoFisc', 'El cliente debe tener un estado.')"> -->
              <option data-placeholder="true"></option>
            </select>
            <!-- <div class="invalid-feedback" id="invalid-paisEstadoFisc">El cliente debe tener un estado.</div> -->
          </div>
          <input type="checkbox" name="check_clienteFacturar" id="check_clienteFacturar" value="1" onclick="valida_check(this);"/>
            <label for="check_clienteFacturar">Cliente para facturación</label>
          <br><br>
          <div>
            <label for="usr">Campos requeridos *</label>
          </div>
          <div class="DataClient_invoice" style="display: none;">
            <label for="usr">Campos requeridos para facturación **</label>
          </div>
        </div>
        <div class="modal-footer justify-content-center">
          <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal" id="btnCancelar_newCliente" onclick="resetForm('agregarCliente')"><span class="ajusteProyecto">Cancelar</span></button>
          <button type="button" class="btn-custom btn-custom--blue espAgregar float-right" name="btnAgregarNC" id="btnAgregarNC"><span class="ajusteProyecto">Agregar</span></button>
        </div>
      </form>
    </div>
  </div>
</div>