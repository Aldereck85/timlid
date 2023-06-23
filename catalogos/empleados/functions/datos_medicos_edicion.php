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
  if($row_count > 0){
?>

<div class="card shadow mb-4">
  <div class="card-header">
    Editar datos médicos
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
            <br>
            <div class="form-group">
              <div class="row">
                <div class="col-lg-6">
                  <label for="usr">NSS:*</label>
                  <input type="text" maxlength="11" class="form-control numeric-only" name="txtNSS" value="<?=$nss;?>" required>
                </div>
                <div class="col-lg-6">
                  <label for="usr">Tipo de sangre:*</label>
                  <select class="form-control" name="cmbTipoSangre" required>
                    <option value="" selected>Seleccione tipo de sangre</option>
                    <option value="A+" <?php if($tipo_sangre == 'A+') echo 'selected';?>>A+</option>
                    <option value="A-" <?php if($tipo_sangre == 'A-') echo 'selected';?>>A-</option>
                    <option value="B+" <?php if($tipo_sangre == 'B+') echo 'selected';?>>B+</option>
                    <option value="B-" <?php if($tipo_sangre == 'B-') echo 'selected';?>>B-</option>
                    <option value="AB+" <?php if($tipo_sangre == 'AB+') echo 'selected';?>>AB+</option>
                    <option value="AB-" <?php if($tipo_sangre == 'AB-') echo 'selected';?>>AB-</option>
                    <option value="O+" <?php if($tipo_sangre == 'O+') echo 'selected';?>>O+</option>
                    <option value="O-" <?php if($tipo_sangre == 'O-') echo 'selected';?>>O-</option>
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-6">
                  <label for="usr">Contacto de emergencia:</label>
                  <input type="text" maxlength="25" class="form-control alpha-only" name="txtContactoEmergencia" value="<?=$contacto_emeregencia;?>">
                </div>
                <div class="col-lg-6">
                  <label for="usr">Numero de emergencia:</label>
                  <input type="text" maxlength="11" class="form-control numeric-only" name="txtNumeroEmergencia" value="<?=$numero_emergencia;?>">
                </div>
              </div>
              <div class="row">
                <div class="col-lg-12">
                  <label for="usr">Notas:</label>
                  <textarea class="form-control" maxlength="50" name="txaNotas" rows="4" cols="2"><?=$notas;?></textarea>
                </div>
              </div>
            </div>
            <button type="submit" class="btn btn-primary float-right" name="btnEditarMedicos">Editar</button>
            <label for="">* Campos requeridos</label>
          </form>
        </div>
      </div>

  </div>
</div>
<?php }else{ ?>
  <script>
  document.getElementById('CargarDatosPersonales').style.background = '#5bc0de';
  document.getElementById('CargarDatosLaborales').style.background = '#5cb85c';
  document.getElementById('CargarDatosMedicos').style.background = 'linear-gradient(#757575,#424242,white)';
  document.getElementById('CargarDatosBancarios').style.background = '#59698d';
    $('#datos').load('datos_medicos.php?id=<?=$id;?>');
  </script>

<?php } ?>


<script src="../../../js/validaciones.js"></script>
