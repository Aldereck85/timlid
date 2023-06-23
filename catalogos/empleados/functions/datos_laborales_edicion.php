<?php
  require_once('../../../include/db-conn.php');
  $id = $_GET['id'];
  $smtm = $conn->prepare('SELECT PKLaboralesEmpleado, Primer_Nombre,Segundo_Nombre,Apellido_Paterno,Apellido_Materno FROM empleados LEFT JOIN datos_laborales_empleado ON PKEmpleado = FKEmpleado WHERE PKEmpleado = :id');
  $smtm->bindValue(':id',$id);
  $smtm->execute();
  $row = $smtm->fetch();
  $fechaIngreso = $_GET['fecha_ingreso'];
  $puesto = $_GET['puesto'];
  $turno = $_GET['turno'];
  $locacion = $_GET['locacion'];
  $infonavit = $_GET['infonavit'];
  $deuda = $_GET['deuda'];
  $deudaRestante = $_GET['deuda_restante'];
  $estatus = $_GET['estatus'];
  $empresa = $_GET['empresa'];
  $empleado = $row['Primer_Nombre'].' '.$row['Segundo_Nombre'].' '.$row['Apellido_Paterno'].' '.$row['Apellido_Materno'];
  $stmt = $conn->prepare('SELECT COUNT(*) FROM datos_laborales_empleado WHERE FKEmpleado = :id');
  $stmt->bindValue(':id',$id);
  $stmt->execute();
  $row_count = $stmt->fetchColumn();
  if($row_count > 0){
?>
<div class="card shadow mb-4">
  <div class="card-header">
    Editar datos laborales
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
                  <label for="usr">Fecha de Ingreso:*</label>
                  <input type="date" class="form-control" id="" name="txtfechaIngreso" step="1" value="<?=$fechaIngreso ?>" required>
                </div>
                <div class="col-lg-3">
                  <label for="usr">Puesto:*</label>
                  <select class="form-control" name="cmbPuesto" required>
                    <option value="">Seleccione un puesto...</option>
                    <?php
                      $stmt = $conn->prepare('SELECT * FROM puestos');
                      $stmt->execute();
                      while($row = $stmt->fetch()){?>
                        <option value="<?=$row['PKPuesto'];?>"<?php if($row['PKPuesto'] == $puesto) echo 'selected';?> ><?=$row['Puesto'];?></option>
                    <?php
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
                      while($row = $stmt->fetch()){?>
                          <option value="<?=$row['PKTurno'];?>"<?php if($row['PKTurno'] == $turno) echo 'selected';?> ><?=$row['Turno'];?></option>
                      <?php
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
                      while($row = $stmt->fetch()){?>
                        <option value="<?=$row['PKSucursal'];?>"<?php if($row['PKSucursal'] == $locacion) echo 'selected';?> ><?=$row['Sucursal'];?></option>
                    <?php
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
                    <input type="text" maxlength="11" class="form-control numericDecimal-only" name="txtInfonavit" value="<?=$infonavit;?>" aria-label="Amount (to the nearest dollar)">
                  </div>
                </div>
                <div class="col-lg-4">
                  <label for="usr">Deuda interna:</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="basic-addon1">$</span>
                    </div>
                    <input type="text" maxlength="13" class="form-control numericDecimal-only"  name="txtDeuda" value="<?=$deuda?>" >
                  </div>
                </div>
                <div class="col-lg-4">
                  <label for="usr">Deuda restante:</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="basic-addon1">$</span>
                    </div>
                      <input type="text" maxlength="13" class="form-control numericDecimal-only"  name="txtDeudaRestante" value="<?=$deudaRestante; ?>">
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
                        <option value="<?=$row['PKEmpresa'];?>"<?php if($row['PKEmpresa'] == $empresa) echo 'selected';?>><?=$row['Razon_Social']; ?></option>
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
                        <option value="<?=$row['PKEstatusEmpleado'];?>"<?php if($row['PKEstatusEmpleado'] == $estatus) echo 'selected';?>><?=$row['Estatus_Empleado'];  ?></option>
                    <?php }} ?>
                  </select>
                </div>
              </div>
            </div>

              <button type="submit" class="btn btn-primary float-right" name="btnEditarLaborales">Editar</button>
              <label for="">* Campos requeridos</label>
            
          </form>

        </div>
      </div>

  </div>
</div>
<?php }else{ ?>
  <script>
    document.getElementById('CargarDatosPersonales').style.background = '#5bc0de';
    document.getElementById('CargarDatosLaborales').style.background = 'linear-gradient(#5cb85c,#2e7d32,white)';
    document.getElementById('CargarDatosMedicos').style.background = '#757575';
    document.getElementById('CargarDatosBancarios').style.background = '#59698d';
    $('#datos').load('datos_laborales.php?id=<?=$id;?>');
  </script>

<?php } ?>
<script src="../../../js/validaciones.js"></script>
