<?php require_once('../../../include/db-conn.php'); ?>
<div class="card shadow mb-4" >
  <div class="card-header">
    Agregar datos personales
  </div>
  <div class="card-body">
      <div class="row">
        <div class="col-lg-12">
          <form action="" method="post">
            <div class="form-group">
              <div class="row">
                <div class="col-lg-3">
                  <label for="usr">Nombre(s):*</label>
                  <input type="text"  maxlength="20" class="form-control alpha-only" id="" name="txtPrimerNombre" required>
                </div>
                <div class="col-lg-3">
                  <label for="usr">Primer apellido:*</label>
                  <input type="text"  maxlength="20" class="form-control alpha-only"  name="txtApellidoPaterno" required>
                </div>
                <div class="col-lg-3">
                  <label for="usr">Segundo apellido:</label>
                  <input type="text"  maxlength="20" class="form-control alpha-only"  name="txtApellidoMaterno">
                </div>
                <div class="col-lg-3">
                  <label for="txtEstadoCivil">Estado Civil:*</label><br>
                  <select class="form-control" name="cmbEstadoCivil" required>
                    <option value="">Seleccione un estado civil...</option>
                    <?php
                      $stmt = $conn->prepare('SELECT * FROM estado_civil');
                      $stmt->execute();
                      $row = $stmt->fetchAll();
                      foreach ($row as $r) { ?>
                        <option value="<?=$r['PKEstadoCivil']; ?>"><?=$r['EstadoCivil']; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col-lg-2">
                  <label for="usr">Telefono:*</label>
                  <input type="text" maxlength="10" class="form-control numeric-only"  name="txtTelefono" required>
                </div>
                <div class="col-lg-2">
                  <label for="usr">CURP:*</label>
                  <input type="text" maxlength="18" class="form-control alphaNumericNDot-only upperCaseletter"  name="txtCURP" required>
                </div>
                <div class="col-lg-2">
                  <label for="usr">RFC:</label>
                  <input type="text" maxlength="13" class="form-control alphaNumericNDot-only upperCaseletter"  name="txtRFC">
                </div>
                <div class="col-lg-3">
                  <label for="usr">Fecha de nacimiento:*</label>
                  <input type="date" name="txtFecha" class="form-control" step="1" required>
                </div>
                <div class="radio col-lg-3">
                  <label for="txtSexo">Genero:*</label><br>
                  <select class="form-control" name="cmbSexo" required>
                    <option value="">Seleccion un género...</option>
                    <option value="Masculino">Masculino</option>
                    <option value="Femenino">Femenino</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col-lg-4">
                  <label for="usr">Calle:*</label>
                  <input type="text" maxlength="100" class="form-control alphaNumericNDot-only"  name="txtCalle" required>
                </div>
                <div class="col-lg-1">
                  <label for="usr">No. Exterior:*</label>
                  <input type="text" maxlength="5" class="form-control numeric-only"  name="txtNumeroExterior" required>
                </div>
                <div class="col-lg-1">
                  <label for="usr">Interior:</label>
                  <input type="text" maxlength="5" class="form-control alphaNumericNDot-only"  name="txtNumeroInterior">
                </div>
                <div class="col-lg-4">
                  <label for="usr">Colonia:*</label>
                  <input type="text" maxlength="20" class="form-control alpha-only"  name="txtColonia" required>
                </div>
                <div class="col-lg-2">
                  <label for="usr">Código Postal:*</label>
                  <input type="text" maxlength="5" class="form-control numeric-only"  name="txtCodigoPostal" required>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col-lg-4">
                  <label for="usr">Ciudad:*</label>
                  <input type="text" maxlength="20" class="form-control alphaNumericNDot-only"  name="txtCiudad" required>
                </div>
                <div class="col-lg-4">
                  <label for="usr">Estado:*</label>
                  <select name="cmbEstados" class="form-control" id="cmbEstados" required>
                    <option value="">Seleccionar estado</option>
                    <option value="Aguascalientes">Aguascalientes</option>
                    <option value="Baja California">Baja California</option>
                    <option value="Baja California Sur">Baja California Sur</option>
                    <option value="Campeche">Campeche</option>
                    <option value="Coahuila de Zaragoza">Coahuila de Zaragoza</option>
                    <option value="Colima">Colima</option>
                    <option value="Chiapas">Chiapas</option>
                    <option value="Chihuahua">Chihuahua</option>
                    <option value="Distrito Federal">Distrito Federal</option>
                    <option value="Durango">Durango</option>
                    <option value="Guanajuato">Guanajuato</option>
                    <option value="Guerrero">Guerrero</option>
                    <option value="Hidalgo">Hidalgo</option>
                    <option value="Jalisco">Jalisco</option>
                    <option value="México">México</option>
                    <option value="Michoacán de Ocampo">Michoacán de Ocampo</option>
                    <option value="Morelos">Morelos</option>
                    <option value="Nayarit">Nayarit</option>
                    <option value="Nuevo León">Nuevo León</option>
                    <option value="Oaxaca">Oaxaca</option>
                    <option value="Puebla">Puebla</option>
                    <option value="Querétaro">Querétaro</option>
                    <option value="Quintana Roo">Quintana Roo</option>
                    <option value="San Luis Potosí">San Luis Potosí</option>
                    <option value="Sinaloa">Sinaloa</option>
                    <option value="Sonora">Sonora</option>
                    <option value="Tabasco">Tabasco</option>
                    <option value="Tamaulipas">Tamaulipas</option>
                    <option value="Tlaxcala">Tlaxcala</option>
                    <option value="Veracruz de Ignacio de la Llave">Veracruz de Ignacio de la Llave</option>
                    <option value="Yucatán">Yucatán</option>
                    <option value="Zacatecas">Zacatecas</option>
                  </select>
                </div>
                <div class="col-lg-4">
                  <label for="cmbEstatus">Estatus:*</label>
                  <span class="input-group-addon" style="width:100%">
                    <select name="cmbTipoProducto" id="cmbTipoProducto" required="" onchange="cambiarTipoProd()">
                    
                    </select>
                  </span>
                                        
                  <select class="form-control" name="cmbEstatus" required>
                    <option value="">Seleccion un estatus</option>
                    <?php
                      $stmt = $conn->prepare('SELECT * FROM estatus_empleado');
                      $stmt->execute();
                      $row = $stmt->fetchAll();
                      foreach ($row as $r) { ?>
                        <option value="<?=$r['PKEstatusEmpleado'] ?>"><?=$r['Estatus_Empleado'] ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>
            <button type="submit" class="btn btn-success float-right" name="btnAgregarPersonales">Agregar</button>
            <label for="">* Campos requeridos fff </label>
          </form>
        </div>
      </div>

  </div>
</div>
<script src="../js/validaciones.js"></script>
