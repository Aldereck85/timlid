<?php
  require_once('../../../include/db-conn.php');
  $stmt = $conn->prepare('SELECT * from empleados ORDER BY PKEmpleado DESC LIMIT 1');
  $stmt->execute();
  $row = $stmt->fetch();

  $empleado="";
  if(isset($_GET['id'])){
    $ide = $_GET['id'];
    $stmt = $conn->prepare('SELECT * from empleados WHERE PKEmpleado = :id');
    $stmt->bindValue('id',$ide);
    $stmt->execute();
    $row1 = $stmt->fetch();
    $empleado = $row1['Primer_Nombre'].' '.$row1['Segundo_Nombre'].' '.$row1['Apellido_Paterno'].' '.$row1['Apellido_Materno'];
  }else{
    $ide = $row['PKEmpleado'];
    $empleado = $row['Primer_Nombre'].' '.$row['Segundo_Nombre'].' '.$row['Apellido_Paterno'].' '.$row['Apellido_Materno'];
  }


?>
<div class="card shadow mb-4">
  <div class="card-header">
    Agregar datos médicos
  </div>
  <div class="card-body">
      <div class="row">
        <div class="col-lg-12">
          <form action="" method="post">
            <div class="row">
              <div class="col-lg-12">
                <input type="hidden" name="txtIdEmpleado" value="<?=$ide;?>">
              </div>
              <div class="col-lg-12" style="text-align: center;">
                <label  for=""> <h4><?='Empleado(a): '. $empleado;?></h4></label>
              </div>
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col-lg-6">
                  <label for="usr">NSS:*</label>
                  <input type="text" maxlength="11" class="form-control numeric-only upperCaseletter" name="txtNSS" required>
                </div>
                <div class="col-lg-6">
                  <label for="usr">Tipo de sangre:*</label>
                  <select class="form-control" name="cmbTipoSangre" required>
                    <option value="" selected>Seleccione tipo de sangre</option>
                    <option value="A+">A+</option>
                    <option value="A-">A-</option>
                    <option value="B+">B+</option>
                    <option value="B-">B-</option>
                    <option value="AB+">AB+</option>
                    <option value="AB-">AB-</option>
                    <option value="O+">O+</option>
                    <option value="O-">O-</option>
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-6">
                  <label for="usr">Contacto de emergencia:</label>
                  <input type="text" maxlength="25" class="form-control alpha-only" name="txtContactoEmergencia">
                </div>
                <div class="col-lg-6">
                  <label for="usr">Numero de emergencia:</label>
                  <input type="text" maxlength="11" class="form-control numeric-only upperCaseletter" name="txtNumeroEmergencia">
                </div>
              </div>
              <div class="row">
                <div class="col-lg-12">
                  <label for="usr">Notas:</label>
                  <textarea class="form-control" maxlength="70" name="txaNotas" rows="4" cols="2"></textarea>
                </div>
              </div>
            </div>
            <button type="submit" class="btn btn-success float-right" name="btnAgregarMedicos">Agregar</button>
            <label for="">* Campos requeridos</label>
          </form>
        </div>
      </div>

  </div>
</div>
<script src="../../../js/validaciones.js"></script>
