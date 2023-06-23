<?php
  require_once('../../../include/db-conn.php');

  $stmt = $conn->prepare('SELECT * from empleados ORDER BY PKEmpleado DESC LIMIT 1');
  $stmt->execute();
  $row = $stmt->fetch();
  $empleado = "";
  if(isset($_GET['id'])){
    $ide = $_GET['id'];
    $stmt = $conn->prepare('SELECT * from empleados WHERE PKEmpleado = :id');
    $stmt->bindValue(':id',$ide);
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
    Agregar datos laborales
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
                <div class="col-lg-3">
                  <label for="usr">Fecha de Ingreso:*</label>
                  <input type="date" class="form-control" id="" name="txtfechaIngreso" step="1" required>
                </div>
                <div class="col-lg-3">
                  <label for="usr">Puesto:*</label>
                  <select class="form-control" name="cmbPuesto" required>
                    <option value="">Seleccione un puesto...</option>
                    <?php
                      $stmt = $conn->prepare('SELECT * FROM puestos');
                      $stmt->execute();
                      while($row = $stmt->fetch()){
                        echo '
                          <option value="'.$row['PKPuesto'].'">'.$row['Puesto'].'</option>
                        ';
                      }
                    ?>
                  </select>
                </div>
                <div class="col-lg-3">
                  <label for="usr">Turno:*</label>
                  <select class="form-control" name="cmbTurno" required>
                    <option value="">Seleccione un turno...</option>
                    <?php
                      $stmt = $conn->prepare('SELECT * FROM turnos');
                      $stmt->execute();
                      while($row = $stmt->fetch()){
                        echo '
                          <option value="'.$row['PKTurno'].'">'.$row['Turno'].'</option>
                        ';
                      }
                    ?>
                  </select>
                </div>
                <div class="col-lg-3">
                  <label for="usr">Área de trabajo:*</label>
                  <select class="form-control" name="cmbLocacion" required>
                    <option value="">Seleccione una local...</option>
                    <?php
                      $stmt = $conn->prepare('SELECT * FROM sucursales');
                      $stmt->execute();
                      while($row = $stmt->fetch()){
                        echo '
                          <option value="'.$row['PKSucursal'].'">'.$row['Sucursal'].'</option>
                        ';
                      }
                    ?>
                  </select>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <div class="col-lg-4">
                  <label for="usr">Infonavit:</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="basic-addon1">$</span>
                    </div>
                    <input type="text" maxlength="11" class="form-control numericDecimal-only" name="txtInfonavit">
                  </div>
                </div>
                <div class="col-lg-4">
                  <label for="usr">Deuda interna:</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="basic-addon1">$</span>
                    </div>
                    <input type="text" maxlength="13" class="form-control numericDecimal-only"  name="txtDeuda">
                  </div>
                </div>
                <div class="col-lg-4">
                  <label for="usr">Deuda restante:</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="basic-addon1">$</span>
                    </div>
                      <input type="text" maxlength="13" class="form-control numericDecimal-only"  name="txtDeudaRestante">
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <div class="col-lg-6">
                  <label for="usr">Empresa:*</label>
                  <select name="cmbEmpresa" class="form-control" id="cmbEmpresa" required>
                    <option value="">Seleccionar empresa</option>
                    <?php
                      $stmt = $conn->prepare('SELECT * FROM empresas');
                      $stmt->execute();
                      while($row = $stmt->fetch()){
                    ?>
                        <option value="<?=$row['PKEmpresa'];?>"><?=$row['Razon_Social']; ?></option>
                    <?php } ?>
                  </select>
                </div>
                <div class="col-lg-6">
                  <label for="usr">Estatus:*</label>
                  <select name="cmbEstatus" class="form-control" id="cmbEstatus" required>
                    <option value="">Seleccionar estatus</option>
                    <?php
                      $stmt = $conn->prepare('SELECT * FROM estatus_empleado');
                      $stmt->execute();
                      while($row = $stmt->fetch()){
                        if($row['PKEstatusEmpleado'] != 1){
                    ?>
                        <option value="<?=$row['PKEstatusEmpleado'];?>"><?=$row['Estatus_Empleado']; ?></option>
                    <?php }} ?>
                  </select>
                </div>
                <div class="col-lg-6">
                  <label for="usr">Sueldo:*</label>
                  <input type="number" class="form-control numeric-only" maxlength="30" id="txtSueldo" name="txtSueldo">
                </div>
              </div>
            </div>
            <button type="submit" class="btn btn-success float-right" name="btnAgregarLaborales">Agregar</button>
            <label for="">* Campos requeridos</label>
          </form>
        </div>
      </div>

  </div>
</div>
<script src="../../../js/validaciones.js"></script>
