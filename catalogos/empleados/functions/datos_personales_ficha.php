<?php
  $id = $_GET['id'];
  $primerNombre = $_GET['primer_nombre'];
  $segundoNombre = $_GET['segundo_nombre'];
  $apellidoPaterno = $_GET['apellido_paterno'];
  $apellidoMaterno = $_GET['apellido_materno'];
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
    Datos personales
  </div>
  <div class="card-body">
      <div class="row">
        <div class="col-lg-12">
          <form action="" method="post">
            <input type="hidden" name="txtId" value="<?=$id;?>">
            <div class="form-group">
              <div class="row">
                <div class="col-lg-3">
                  <label for="usr">Primer nombre:</label>
                  <input type="text"  maxlength="20" class="form-control alpha-only" id="" name="txtPrimerNombre" value="<?=$primerNombre;?>" disabled>
                </div>
                <div class="col-lg-3">
                  <label for="usr">Segundo nombre:</label>
                  <input type="text"  maxlength="20" class="form-control alpha-only"  name="txtSegundoNombre" value="<?=$segundoNombre; ?>"disabled>
                </div>
                <div class="col-lg-3">
                  <label for="usr">Apellido paterno:</label>
                  <input type="text"  maxlength="20" class="form-control alpha-only"  name="txtApellidoPaterno" value="<?=$apellidoPaterno;?>"disabled>
                </div>
                <div class="col-lg-3">
                  <label for="usr">Apellido materno:</label>
                  <input type="text"  maxlength="20" class="form-control alpha-only"  name="txtApellidoMaterno" value="<?=$apellidoMaterno;?>"disabled>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-2">
                  <label for="usr">Telefono:</label>
                  <input type="text" maxlength="10" class="form-control alphaNumeric-only"  name="txtTelefono" value="<?=$telefono;?>" disabled>
                </div>
                <div class="col-lg-2">
                  <label for="usr">CURP:</label>
                  <input type="text" maxlength="18" class="form-control alphaNumeric-only upperCaseletter"  name="txtCURP" value="<?=$curp;?>" disabled>
                </div>
                <div class="col-lg-2">
                  <label for="usr">RFC:</label>
                  <input type="text" maxlength="13" class="form-control alphaNumeric-only upperCaseletter"  name="txtRFC" value="<?=$rfc;?>" disabled>
                </div>
                <div class="col-lg-3">
                  <label for="usr">Fecha de nacimiento:</label>
                  <input type="date" name="txtFecha" class="form-control" step="1" value="<?=$fechaNacimiento ?>" disabled>
                </div>
                <div class="radio col-lg-3">
                  <label for="txtEstadoCivil">Estado Civil:</label><br>
                  <input class="form-control" type="text" name="txtEstadoCivil" value="<?=$estadoCivil; ?>" disabled>
                </div>
              </div>
              <div class="row">
                <div class="radio col-lg-2">
                  <label for="txtSexo">Sexo:</label><br>
                    <input class="form-control" type="text" nametxtSexo value="<?=$sexo; ?>" disabled>
                </div>
                <div class="col-lg-3">
                  <label for="usr">Calle:</label>
                  <input type="text" maxlength="100" class="form-control alphaNumeric-only"  name="txtCalle" value="<?=$calle;?>" disabled>
                </div>
                <div class="col-lg-1">
                  <label for="usr">No. Exterior:</label>
                  <input type="text" maxlength="5" class="form-control numeric-only"  name="txtNumeroExterior" value="<?=$no_exterior;?>" disabled>
                </div>
                <div class="col-lg-1">
                  <label for="usr">No. Interior:</label>
                  <input type="text" maxlength="5" class="form-control numeric-only"  name="txtNumeroInterior" value="<?=$no_interior;?>" disabled>
                </div>
                <div class="col-lg-2">
                  <label for="usr">Colonia:</label>
                  <input type="text" maxlength="20" class="form-control alpha-only"  name="txtColonia" value="<?=$colonia;?>" disabled>
                </div>
                <div class="col-lg-3">
                  <label for="usr">Código Postal:</label>
                  <input type="text" maxlength="5" class="form-control numeric-only"  name="txtCodigoPostal" value="<?=$cp ?>" disabled>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-6">
                  <label for="usr">Ciudad:</label>
                  <input type="text" maxlength="20" class="form-control alpha-only"  name="txtCiudad" value="<?=$ciudad;?>" disabled>
                </div>
                <div class="col-lg-6">
                  <label for="usr">Estado:</label>
                  <input class="form-control" type="text" name="txtEstados" value="<?=$estado;?>" disabled>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>

  </div>
</div>
