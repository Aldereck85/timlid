<?php
  require_once('../../../include/db-conn.php');
  $id = $_GET['id'];
  $smtm = $conn->prepare('SELECT PKLaboralesEmpleado, Primer_Nombre,Segundo_Nombre,Apellido_Paterno,Apellido_Materno,FKEmpresa FROM empleados LEFT JOIN datos_laborales_empleado ON PKEmpleado = FKEmpleado WHERE PKEmpleado = :id');
  $smtm->bindValue(':id',$id);
  $smtm->execute();
  $row = $smtm->fetch();
  $fechaIngreso = $_GET['fecha_ingreso'];
  $puesto = $_GET['puesto'];
  $turno = $_GET['turno'];
  $locacion = $_GET['locacion'];
  $infonavit = $_GET['infonavit'];
  $deuda = $_GET['deuda'];
  //$deudaRestante = $_GET['deuda_restante'];
  $estatus = $_GET['estatus'];
  $stmt = $conn->prepare('SELECT COUNT(*) FROM datos_laborales_empleado WHERE FKEmpleado = :id');
  $stmt->bindValue(':id',$id);
  $stmt->execute();
  $row_count = $smtm->fetchColumn();
  if($row_count == 0){
    $fechaIngreso = "No registrado";
    $turno = "No registrado";
    $locacion = "No registrado";
    $infonavit = "No registrado";
    $deuda = "No registrado";
    $deudaRestante = "No registrado";
    $estatus = "No registrado";
  }
  $empleado = $row['Primer_Nombre'].' '.$row['Segundo_Nombre'].' '.$row['Apellido_Paterno'].' '.$row['Apellido_Materno'];
  $empresa = $row['FKEmpresa'];
?>
<div class="card shadow mb-4">
  <div class="card-header">
    Datos laborales
  </div>
  <div class="card-body">
      <div class="row">
        <div class="col-lg-12">
          <form action="" method="post">
            <input type="hidden" name="txtId" value="<?=$row['PKLaboralesEmpleado'];?>">
            <div class="form-group">
              <div class="row">
                <div class="col-lg-4">
                  <label for="usr">Fecha de Ingreso:</label>
                  <input type="text" class="form-control" id="" name="txtfechaIngreso" step="1" value="<?=$fechaIngreso ?>" disabled>
                </div>
                <div class="col-lg-4">
                  <label for="usr">Puesto:</label>
                  <?php
                    $stmt = $conn->prepare('SELECT * FROM puestos WHERE PKPuesto = :puesto');
                    $stmt->bindValue(':puesto',$puesto);
                    $stmt->execute();
                    $row = $stmt->fetch();
                    $puesto1 = "";
                    if(isset($row['Puesto'])){
                      $puesto1 = $row['Puesto'];
                    }else{
                      $puesto1 = "No registrado";
                    }
                  ?>
                  <input class="form-control" type="text" name="txtPuesto" value="<?=$puesto1; ?>" disabled>
                </div>
                <div class="col-lg-4">
                  <label for="usr">Turno:</label>
                  <?php
                    $stmt = $conn->prepare('SELECT * FROM turnos WHERE PKTurno = :turno');
                    $stmt->bindValue(':turno',$turno);
                    $stmt->execute();
                    $row = $stmt->fetch();
                    $turno1 = "";
                    if(isset($row['Turno'])){
                      $turno1 = $row['Turno'];
                    }else{
                      $turno1 = "No registrado";
                    }
                  ?>
                  <input class="form-control" type="text" name="txtTurno" value="<?=$turno1; ?>" disabled>
                </div>

              </div>
              <br>
              <div class="row">
                <div class="col-lg-4">
                  <label for="usr">Locacion:</label>
                  <?php
                    $stmt = $conn->prepare('SELECT * FROM locacion WHERE PKLocacion = :locacion');
                    $stmt->bindValue(':locacion',$locacion);
                    $stmt->execute();
                    $row = $stmt->fetch();
                    $locacion1 = "";
                    if(isset($row['Locacion'])){
                      $locacion1 = $row['Locacion'];
                    }else{
                      $locacion1 = "No registrado";
                    }
                  ?>
                  <input class="form-control" type="text" name="" value="<?=$locacion1; ?>" disabled>
                </div>
                <div class="col-lg-4">
                  <label for="usr">Infonavit:</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="basic-addon1">$</span>
                    </div>
                    <input type="text" maxlength="11" class="form-control numericDecimal-only" name="txtInfonavit" value="<?=$infonavit;?>" disabled>
                  </div>
                </div>
                <div class="col-lg-4">
                  <label for="usr">Deuda interna:</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="basic-addon1">$</span>
                    </div>
                    <input type="text" maxlength="13" class="form-control numericDecimal-only"  name="txtDeuda" value="<?=$deuda?>" disabled>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-4">
                  <label for="usr">Deuda restante:</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="basic-addon1">$</span>
                    </div>
                      <input type="text" maxlength="13" class="form-control numericDecimal-only"  name="txtDeudaRestante" value="<?=$deuda; ?>" disabled>
                  </div>
                </div>
                <div class="col-lg-4">
                  <label for="usr">Estatus:</label>
                  <?php
                    $stmt = $conn->prepare('SELECT * FROM estatus_empleado WHERE PKEstatusEmpleado = :estatus');
                    $stmt->bindValue(':estatus',$estatus);
                    $stmt->execute();
                    $row = $stmt->fetch();
                    $estatus1 = "";
                    if(isset($row['Estatus_Empleado'])){
                      $estatus1 = $row['Estatus_Empleado'];
                    }else{
                      $estatus1 = "No registrado";
                    }
                  ?>
                  <input class="form-control" type="text" name="txtestatus" value="<?=$estatus1; ?>" disabled>
                </div>
                <div class="col-lg-4">
                  <label for="usr">Empresa:</label>
                  <?php
                    $stmt = $conn->prepare('SELECT Razon_Social FROM empresas WHERE PKEmpresa = :empresa');
                    $stmt->bindValue(':empresa',$empresa);
                    $stmt->execute();
                    $row = $stmt->fetch();
                    $empresa1 = "";
                    if(isset($row['Razon_Social'])){
                      $empresa1 = $row['Razon_Social'];
                    }else{
                      $empresa1 = "No registrado";
                    }
                  ?>
                  <input class="form-control" type="text" name="txtEmpresa" value="<?=$empresa1; ?>" readonly>
                </div>
              </div>

            </div>

          </form>
        </div>
      </div>

  </div>
</div>
