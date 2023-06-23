<?php
  require_once('../../../include/db-conn.php');
  $stmt = $conn->prepare('SELECT * from empleados ORDER BY PKEmpleado DESC LIMIT 1');
  $stmt->execute();
  $row = $stmt->fetch();

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

  $stmt = $conn->prepare('SELECT COUNT(*) FROM datos_bancarios_empleado WHERE FKEmpleado = :id');
  $stmt->bindValue(':id',$ide);
  $stmt->execute();
  $row_count = $stmt->fetchColumn();
?>

<div class="card shadow mb-4">
  <div class="card-header">
    Agregar datos bancarios
  </div>
  <div class="card-body">
      <div class="row">
        <div class="col-lg-12">
          <form action="" method="post">
            <div class="row">
              <div class="col-lg-12">
                <input type="hidden" name="txtIdEmpleado" value="<?=$ide;?>">
              </div>
              <?php
                if($row_count > 0){
                  echo "<div class='col-lg-12'><div class='alert alert-danger' role='alert'><center>Ya registró los datos laborales de este empleado.</center></div></div>";
                }
              ?>
              <div class="col-lg-12" style="text-align: center;">
                <label  for=""> <h4><?='Empleado(a): '. $empleado;?></h4></label>
              </div>
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col-lg-6">
                  <label for="usr">Banco:*</label>
                  <select class="form-control" name="cmbBanco" required>
                    <option value="">Elegir banco...</option>
                    <?php
                      $stmt = $conn->prepare('SELECT * FROM bancos');
                      $stmt->execute();
                      while($row = $stmt->fetch()){
                        echo '
                          <option value="'.$row['PKBanco'].'">'.$row['Nombre'].'</option>
                        ';
                      }
                    ?>
                  </select>
                </div>
                <div class="col-lg-6">
                  <label for="usr">Cuenta bancaria:*</label>
                  <input class="form-control numeric-only" maxlength="11" type="text" name="txtCuentaBancaria" value="" required>
                </div>
                <div class="col-lg-6">
                  <label for="usr">CLABE:</label>
                  <input class="form-control numeric-only" maxlength="18" type="text" name="txtCLABE" value="" >
                </div>
                <div class="col-lg-6">
                  <label for="usr">Numero de tarjeta:*</label>
                  <input class="form-control numeric-only" maxlength="16" type="text" name="txtNumeroTarjeta" value="" required>
                </div>
              </div>
            </div>
            <button type="submit" class="btn btn-success float-right" name="btnAgregarBancarios" >Agregar</button>
            <label for="">* Campos requeridos</label>
          </form>
        </div>
      </div>

  </div>
</div>
<script src="../../../js/validaciones.js"></script>
