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
  if($row_count > 0){

?>
<div class="card shadow mb-4">
  <div class="card-header">
    Editar datos bancarios
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
                  <label for="usr">Banco:*</label>
                  <select class="form-control" name="cmbBanco" required>
                    <option value="">Elegir banco...</option>
                    <?php
                      $stmt = $conn->prepare('SELECT * FROM bancos');
                      $stmt->execute();
                      while($row = $stmt->fetch()){
                      ?>
                          <option value="<?=$row['PKBanco'];?>"<?php if($row['PKBanco'] == $banco) echo 'selected';?>><?=$row['Nombre'];?></option>
                      <?php
                      }
                      ?>
                  </select>
                </div>
                <div class="col-lg-6">
                  <label for="usr">Cuenta bancaria:*</label>
                  <input class="form-control numeric-only" maxlength="11" type="text" name="txtCuentaBancaria" value="<?=$cuentaBancaria; ?>" required>
                </div>
                <div class="col-lg-6">
                  <label for="usr">CLABE:</label>
                  <input class="form-control numeric-only" maxlength="18" type="text" name="txtCLABE" value="<?=$clabe;?>">
                </div>
                <div class="col-lg-6">
                  <label for="usr">Numero de tarjeta:*</label>
                  <input class="form-control numeric-only" maxlength="16" type="text" name="txtNumeroTarjeta" value="<?=$tarjeta;?>" required>
                </div>
              </div>
            </div>
            <button type="submit" class="btn btn-primary float-right" name="btnEditarBancarios">Editar</button>
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
document.getElementById('CargarDatosMedicos').style.background = '#757575';
document.getElementById('CargarDatosBancarios').style.background = 'linear-gradient(#59698d,#2e3951,white)';
$('#datos').load('datos_bancarios.php?id=<?=$id; ?>');
</script>
<?php } ?>
<script src="../../../js/validaciones.js"></script>
