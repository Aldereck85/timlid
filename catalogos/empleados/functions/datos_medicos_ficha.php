<?php
  require_once('../../../include/db-conn.php');
  $id = $_GET['id'];
  $smtm = $conn->prepare('SELECT FKEmpleado,Primer_Nombre,Segundo_Nombre,Apellido_Paterno,Apellido_Materno FROM empleados LEFT JOIN datos_medicos_empleado ON PKEmpleado = FKEmpleado WHERE PKEmpleado = :id');
  $smtm->bindValue(':id',$id,PDO::PARAM_INT);
  $smtm->execute();
  $row = $smtm->fetch();
  $nss = $_GET['nss'];
  $tipo_sangre = $_GET['tipo_sangre'];
  $contacto_emeregencia = $_GET['contacto_emergencia'];
  $numero_emergencia = $_GET['numero_emergencia'];
  $notas = $_GET['notas'];
  $empleado = $row['Primer_Nombre'].' '.$row['Segundo_Nombre'].' '.$row['Apellido_Paterno'].' '.$row['Apellido_Materno'];
  $stmt = $conn->prepare('SELECT COUNT(*) FROM datos_medicos_empleado WHERE FKEmpleado = :id');
  $stmt->bindValue(':id',$id);
  $stmt->execute();
  $row_count = $stmt->fetchColumn();
  if($row_count == 0){
    $nss = 'No registrado';
    $tipo_sangre = 'No registrado';
    $contacto_emeregencia = 'No registrado';
    $numero_emergencia = 'No registrado';
    $notas = 'No registrado';
  }
?>

<div class="card shadow mb-4">
  <div class="card-header">
    Datos médicos
  </div>
  <div class="card-body">
      <div class="row">
        <div class="col-lg-12">
          <form action="" method="post">
            <input type="hidden" name="txtId" value="<?=$row['PKMedicosEmpleado'];?>">
            <div class="form-group">
              <div class="row">
                <div class="col-lg-6">
                  <label for="usr">NSS:</label>
                  <input type="text" maxlength="11" class="form-control numeric-only upperCaseletter" name="txtNSS" value="<?=$nss;?>" disabled>
                </div>
                <div class="col-lg-6">
                  <label for="usr">Tipo de sangre:</label>
                  <input class="form-control" type="text" name="txtTipoSangre" value="<?=$tipo_sangre;?>" disabled>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-6">
                  <label for="usr">Contacto de emergencia:</label>
                  <input type="text" maxlength="11" class="form-control numeric-only upperCaseletter" name="txtContactoEmergencia" value="<?=$contacto_emeregencia;?>" disabled>
                </div>
                <div class="col-lg-6">
                  <label for="usr">Numero de emergencia:</label>
                  <input type="text" maxlength="11" class="form-control numeric-only upperCaseletter" name="txtNumeroEmergencia" value="<?=$numero_emergencia;?>" disabled>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-12">
                  <label for="usr">Notas:</label>
                  <textarea class="form-control" maxlength="50" name="txaNotas" rows="4" cols="2" disabled><?=$notas;?></textarea>
                </div>
              </div>
            </div>

          </form>
        </div>
      </div>

  </div>
</div>
