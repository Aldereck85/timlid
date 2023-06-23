<?php
  require_once('../../../include/db-conn.php');
  $id = $_GET['id'];
  $smtm = $conn->prepare('SELECT PKBancariosEmpleado, Primer_Nombre,Segundo_Nombre,Apellido_Paterno,Apellido_Materno FROM empleados LEFT JOIN datos_bancarios_empleado ON PKEmpleado = FKEmpleado WHERE PKEmpleado = :id');
  $smtm->bindValue(':id',$id);
  $smtm->execute();
  $row = $smtm->fetch();
  $empleado = $row['Primer_Nombre'].' '.$row['Segundo_Nombre'].' '.$row['Apellido_Paterno'].' '.$row['Apellido_Materno'];
  $stmt = $conn->prepare('SELECT COUNT(*) FROM datos_bancarios_empleado WHERE FKEmpleado = :id');
  $stmt->bindValue(':id',$id);
  $stmt->execute();
  $row_count = $stmt->fetchColumn();
  $banco = $_GET['banco'];
  $cuentaBancaria = $_GET['cuenta'];
  $clabe = $_GET['clabe'];
  $tarjeta = $_GET['tarjeta'];
  if($row_count == 0){
    $banco = 'No registrado';
    $cuentaBancaria = 'No registrado';
    $clabe = 'No registrado';
    $tarjeta = 'No registrado';
  }
?>
<div class="card shadow mb-4">
  <div class="card-header">
    Datos bancarios
  </div>
  <div class="card-body">
      <div class="row">
        <div class="col-lg-12">
          <form action="" method="post">
            <input type="hidden" name="txtId" value="<?=$row['PKBancariosEmpleado'];?>">
            <div class="form-group">
              <div class="row">
                <div class="col-lg-6">
                  <label for="usr">Banco:</label>
                  <?php
                    $stmt = $conn->prepare('SELECT * FROM bancos WHERE PKBanco = :banco');
                    $stmt->bindValue(':banco',$banco);
                    $stmt->execute();
                    $row = $stmt->fetch();
                    if(isset($row['Nombre'])){
                      $banco1 = $row['Nombre'];
                    }else{
                      $banco1 = 'No registrado';
                    }
                  ?>
                  <input class="form-control" type="text" name="txtBanco" value="<?=$banco1;?>" disabled>
                </div>
                <div class="col-lg-6">
                  <label for="usr">Cuenta bancaria:</label>
                  <input class="form-control numeric-only" maxlength="11" type="text" name="txtCuentaBancaria" value="<?=$cuentaBancaria; ?>" disabled>
                </div>
                <div class="col-lg-6">
                  <label for="usr">CLABE:</label>
                  <input class="form-control numeric-only" maxlength="18" type="text" name="txtCLABE" value="<?=$clabe;?>" disabled>
                </div>
                <div class="col-lg-6">
                  <label for="usr">Numero de tarjeta:</label>
                  <input class="form-control numeric-only" maxlength="16" type="text" name="txtNumeroTarjeta" value="<?=$tarjeta;?>" disabled>
                </div>
              </div>
            </div>

          </form>
        </div>
      </div>

  </div>
</div>
