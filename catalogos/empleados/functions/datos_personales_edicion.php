<?php
  $id = $_GET['id'];
  $primerNombre = $_GET['primer_nombre'];
  $segundoNombre = $_GET['segundo_nombre'];
  $apellidoPaterno = $_GET['apellido_paterno'];
  $apellidoMaterno = $_GET['apellido_materno'];
  $empleado = $primerNombre ." ". $segundoNombre ." ". $apellidoPaterno ." ". $apellidoMaterno;
  $telefono = $_GET['telefono'];
  $curp = $_GET['curp'];
  $rfc = $_GET['rfc'];
  $fechaNacimiento = $_GET['$fecha_nacimiento'];
  $estadoCivil = $_GET['estado_civil'];
  $sexo = $_GET['sexo'];
  $calle = $_GET['calle'];
  $no_exterior = $_GET['n_exterior'];
  $no_interior = $_GET['n_interior'];
  $colonia = $_GET['colonia'];
  $cp = $_GET['cp'];
  $ciudad = $_GET['ciudad'];
  $estado = $_GET['estado'];
?>
<div class="card shadow mb-4">
  <div class="card-header">
    Editar datos personales
  </div>
  <div class="card-body">
      <div class="row">
        <div class="col-lg-12">
          <form action="" method="post">
            <input type="hidden" name="txtId" value="<?=$id;?>">
            <div class="row">
              <div class="col-lg-12" style="text-align: center;">
                <label  for=""> <h4><?='Empleado(a): '. $empleado;?></h4></label>
              </div>
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col-lg-3">
                  <label for="usr">Primer nombre:*</label>
                  <input type="text"  maxlength="20" class="form-control alpha-only" id="" name="txtPrimerNombre" value="<?=$primerNombre;?>" required>
                </div>
                <div class="col-lg-3">
                  <label for="usr">Segundo nombre:</label>
                  <input type="text"  maxlength="20" class="form-control alpha-only"  name="txtSegundoNombre" value="<?=$segundoNombre; ?>">
                </div>
                <div class="col-lg-3">
                  <label for="usr">Apellido paterno:*</label>
                  <input type="text"  maxlength="20" class="form-control alpha-only"  name="txtApellidoPaterno" value="<?=$apellidoPaterno;?>" required>
                </div>
                <div class="col-lg-3">
                  <label for="usr">Apellido materno:</label>
                  <input type="text"  maxlength="20" class="form-control alpha-only"  name="txtApellidoMaterno" value="<?=$apellidoMaterno;?>">
                </div>
              </div>
              <div class="row">
                <div class="col-lg-2">
                  <label for="usr">Telefono:*</label>
                  <input type="text" maxlength="10" class="form-control numeric-only"  name="txtTelefono" value="<?=$telefono;?>" required>
                </div>
                <div class="col-lg-2">
                  <label for="usr">CURP:*</label>
                  <input type="text" maxlength="18" class="form-control alphaNumericNDot-only upperCaseletter"  name="txtCURP" value="<?=$curp;?>" required>
                </div>
                <div class="col-lg-2">
                  <label for="usr">RFC:</label>
                  <input type="text" maxlength="13" class="form-control alphaNumericNDot-only upperCaseletter"  name="txtRFC" value="<?=$rfc;?>">
                </div>
                <div class="col-lg-3">
                  <label for="usr">Fecha de nacimiento:*</label>
                  <input type="date" name="txtFecha" class="form-control" step="1" value="<?=$fechaNacimiento ?>" required>
                </div>
                <div class="radio col-lg-3">
                  <label for="txtEstadoCivil">Estado Civil:*</label><br>
                    <label><input type="radio" name="txtEstadoCivil" value="Soltero" <?php if ($estadoCivil == "Soltero" ) echo 'checked'; ?> required>Soltero</label>&nbsp;&nbsp&nbsp;&nbsp&nbsp;&nbsp&nbsp;&nbsp
                    <label><input type="radio" name="txtEstadoCivil" value="Casado" <?php if ($estadoCivil == "Casado" ) echo 'checked'; ?> required>Casado</label>
                </div>
              </div>
              <div class="row">
                <div class="radio col-lg-2">
                  <label for="txtSexo">Sexo:*</label><br>
                    <label><input type="radio" name="txtSexo" value="Hombre" <?php if ($sexo == "Hombre" ) echo 'checked'; ?> required>Hombre</label>&nbsp;&nbsp&nbsp;&nbsp&nbsp;&nbsp&nbsp;&nbsp
                    <label><input type="radio" name="txtSexo" value="Mujer" <?php if ($sexo == "Mujer" ) echo 'checked'; ?> required>Mujer</label>
                </div>
                <div class="col-lg-3">
                  <label for="usr">Calle:*</label>
                  <input type="text" maxlength="100" class="form-control alphaNumericNDot-only"  name="txtCalle" value="<?=$calle;?>" required>
                </div>
                <div class="col-lg-1">
                  <label for="usr">No. Exterior:*</label>
                  <input type="text" maxlength="5" class="form-control numeric-only"  name="txtNumeroExterior" value="<?=$no_exterior;?>" required>
                </div>
                <div class="col-lg-1">
                  <label for="usr">Interior:</label>
                  <input type="text" maxlength="5" class="form-control alphaNumericNDot-only"  name="txtNumeroInterior" value="<?=$no_interior;?>">
                </div>
                <div class="col-lg-2">
                  <label for="usr">Colonia:*</label>
                  <input type="text" maxlength="35" class="form-control alpha-only"  name="txtColonia" value="<?=$colonia;?>" required>
                </div>
                <div class="col-lg-3">
                  <label for="usr">Código Postal:*</label>
                  <input type="text" maxlength="5" class="form-control numeric-only"  name="txtCodigoPostal" value="<?=$cp ?>" required>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-6">
                  <label for="usr">Ciudad:*</label>
                  <input type="text" maxlength="20" class="form-control alphaNumericNDot-only"  name="txtCiudad" value="<?=$ciudad;?>" required>
                </div>
                <div class="col-lg-6">
                  <label for="usr">Estado:*</label>
                  <select name="cmbEstados" class="form-control" id="cmbEstados" required>
                    <option value="">Seleccionar estado</option>
                    <option value="Aguascalientes" <?php if ($estado == "Aguascalientes" ) echo 'selected'; ?>>Aguascalientes</option>
                    <option value="Baja California" <?php if ($estado == "Baja California" ) echo 'selected'; ?>>Baja California</option>
                    <option value="Baja California Sur" <?php if ($estado == "Baja California Sur" ) echo 'selected'; ?>>Baja California Sur</option>
                    <option value="Campeche" <?php if ($estado == "Campeche" ) echo 'selected'; ?>>Campeche</option>
                    <option value="Coahuila de Zaragoza" <?php if ($estado == "Coahuila de Zaragoza" ) echo 'selected'; ?>>Coahuila de Zaragoza</option>
                    <option value="Colima" <?php if ($estado == "Colima" ) echo 'selected'; ?>>Colima</option>
                    <option value="Chiapas" <?php if ($estado == "Chiapas" ) echo 'selected'; ?>>Chiapas</option>
                    <option value="Chihuahua" <?php if ($estado == "Chihuahua" ) echo 'selected'; ?>>Chihuahua</option>
                    <option value="Distrito Federal" <?php if ($estado == "Distrito Federal" ) echo 'selected'; ?>>Distrito Federal</option>
                    <option value="Durango" <?php if ($estado == "Durango" ) echo 'selected'; ?>>Durango</option>
                    <option value="Guanajuato" <?php if ($estado == "Guanajuato" ) echo 'selected'; ?>>Guanajuato</option>
                    <option value="Guerrero" <?php if ($estado == "Guerrero" ) echo 'selected'; ?>>Guerrero</option>
                    <option value="Hidalgo" <?php if ($estado == "Hidalgo" ) echo 'selected'; ?>>Hidalgo</option>
                    <option value="Jalisco" <?php if ($estado == "Jalisco" ) echo 'selected'; ?>>Jalisco</option>
                    <option value="México" <?php if ($estado == "México" ) echo 'selected'; ?>>México</option>
                    <option value="Michoacán" <?php if ($estado == "Michoacán" ) echo 'selected'; ?>>Michoacán de Ocampo</option>
                    <option value="Morelos" <?php if ($estado == "Morelos" ) echo 'selected'; ?>>Morelos</option>
                    <option value="Nayarit" <?php if ($estado == "Nayarit" ) echo 'selected'; ?>>Nayarit</option>
                    <option value="Nuevo León" <?php if ($estado == "Nuevo León" ) echo 'selected'; ?>>Nuevo León</option>
                    <option value="Oaxaca" <?php if ($estado == "Oaxaca" ) echo 'selected'; ?>>Oaxaca</option>
                    <option value="Puebla" <?php if ($estado == "Puebla" ) echo 'selected'; ?>>Puebla</option>
                    <option value="Querétaro" <?php if ($estado == "Querétaro" ) echo 'selected'; ?>>Querétaro</option>
                    <option value="Quintana Roo" <?php if ($estado == "Quintana Roo" ) echo 'selected'; ?>>Quintana Roo</option>
                    <option value="San Luis Potosí" <?php if ($estado == "San Luis Potosí" ) echo 'selected'; ?>>San Luis Potosí</option>
                    <option value="Sinaloa" <?php if ($estado == "Sinaloa" ) echo 'selected'; ?>>Sinaloa</option>
                    <option value="Sonora" <?php if ($estado == "Sonora" ) echo 'selected'; ?>>Sonora</option>
                    <option value="Tabasco" <?php if ($estado == "Tabasco" ) echo 'selected'; ?>>Tabasco</option>
                    <option value="Tamaulipas" <?php if ($estado == "Tamaulipas" ) echo 'selected'; ?>>Tamaulipas</option>
                    <option value="Tlaxcala" <?php if ($estado == "Tlaxcala" ) echo 'selected'; ?>>Tlaxcala</option>
                    <option value="Veracruz" <?php if ($estado == "Veracruz" ) echo 'selected'; ?>>Veracruz de Ignacio de la Llave</option>
                    <option value="Yucatán" <?php if ($estado == "Yucatán" ) echo 'selected'; ?>>Yucatán</option>
                    <option value="Zacatecas" <?php if ($estado == "Zacatecas" ) echo 'selected'; ?>>Zacatecas</option>
                  </select>
                </div>
              </div>
            </div>
            <button type="submit" class="btn btn-primary float-right" name="btnEditarPersonales">Editar</button>
            <label for="">* Campos requeridos</label>
          </form>
        </div>
      </div>

  </div>
</div>
