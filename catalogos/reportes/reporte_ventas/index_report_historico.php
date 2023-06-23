<?php
date_default_timezone_set('America/Mexico_City');

if (isset($_SESSION["Usuario"])) {

  $user = $_SESSION["Usuario"];

  $pkusuario = $_SESSION["PKUsuario"];
  $ruta = "../../";
  $screen = 8;
  require_once $ruta . '../include/db-conn.php';
  /* VENDEDOR */
  $stmt = $conn->prepare("SELECT e.PKEmpleado AS id, CONCAT(e.Nombres, ' ', e.PrimerApellido) AS nombre FROM relacion_tipo_empleado AS rte INNER JOIN empleados AS e ON rte.empleado_id = e.PKEmpleado WHERE rte.tipo_empleado_id = 1 AND e.estatus = 1 AND e.empresa_id = :empresa");
  $stmt->execute([':empresa' => $_SESSION['IDEmpresa']]);
  $vendedores = $stmt->fetchAll(PDO::FETCH_ASSOC);
  /* CLIENTES */
  $stmt = $conn->prepare("SELECT c.PKCliente AS id, c.NombreComercial AS nombre FROM clientes AS c WHERE c.estatus = 1 AND c.empresa_id = :empresa");
  $stmt->execute([':empresa' => $_SESSION['IDEmpresa']]);
  $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
  /* ESTADOS */
  $stmt = $conn->prepare("SELECT e.PKEstado AS id, e.Estado AS estado FROM estados_federativos AS e WHERE e.FKPais = 146");
  $stmt->execute();
  $estados = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
  header("location:../../../dashboard.php");
}

?>

<script src="../../../js/jquery.redirect.min.js"></script>
<script src="../../../vendor/chart.js/Chart.min.js"></script>
<script src="js/reportes-historico.js" charset="utf-8"></script>


<!-- Content Wrapper -->
<div class="tab-pane fade show active" id="historico" role="tabpanel" aria-labelledby="nav-main-tab">
  <input type="hidden" id="txtUsuario" value="<?= $_SESSION['PKUsuario']; ?>">
  <input type="hidden" id="txtRuta" value="<?= $ruta; ?>">
  <input type="hidden" id="txtEdit" value="<?= $ruteEdit; ?>">
  <input type="hidden" id="txtPantalla" value="13">

  <!-- Main Content -->
  <div class="card" id="card">

    <!-- Begin Page Content -->
    <div class="container-fluid">

      <!-- Page Heading -->

      <!-- DataTales Example -->
      <div class="card">
        <!-- <div class="card-header py-3">
              <div class="float-right">
                <div class="float-right" id="btnAddPermissions" name="btnAddPermissions">
              
                </div>
              </div>
            </div> -->
        <div class="card-body">
          <div class="filtros mb-4" id="filtros_historico" style="display: none;">
            <form>
              <div class="form-row align-items-center">
                <div class="col-md-2 mb-3">
                  <label for="vendedor_input_H">Vendedor</label>
                  <select name="" class="form-control" id="vendedor_input_H" onchange="validEmptyInput(this)">
                    <option value="todos" selected>Todos</option>
                    <?php foreach ($vendedores as $vendedor) { ?>
                      <option value="<?= $vendedor["id"] ?>"><?= $vendedor["nombre"] ?></option>
                    <?php } ?>
                  </select>
                  <div class="invalid-feedback" id="invalid-vendedor_H">
                    El vendedor no puede estar vacio.
                  </div>
                </div>
                <div class="col-md-2 mb-3">
                  <label for="cliente_input_H">Cliente</label>
                  <select name="" class="form-control" id="cliente_input_H" onchange="validEmptyInput(this)">
                    <option value="todos" selected>Todos</option>
                    <?php foreach ($clientes as $cliente) { ?>
                      <option value="<?= $cliente["id"] ?>"><?= $cliente["nombre"] ?></option>
                    <?php } ?>
                  </select>
                  <div class="invalid-feedback" id="invalid-cliente_H">
                    El cliente no puede estar vacio.
                  </div>
                </div>
                <div class="col-md-2 mb-3">
                  <label for="estado_input_H">Estado</label>
                  <select name="" class="form-control" id="estado_input_H" onchange="validEmptyInput(this)">
                    <option value="todos" selected>Todos</option>
                    <?php foreach ($estados as $estado) { ?>
                      <option value="<?= $estado["id"] ?>"><?= $estado["estado"] ?></option>
                    <?php } ?>
                  </select>
                  <div class="invalid-feedback" id="invalid-estado_H">
                    El estado no puede estar vacio.
                  </div>
                </div>
                <div id="divAño" class="col-md-2 mb-3">
                    <label for="cmbAño">Año:</label>
                    <select name="cmbAño" id="cmbAño" class="form-select" required>
                    <option value="todos" selected>Todos</option>
                    <?php
                      $cont = date('Y');
                       while ($cont >= 1999) { 
                    ?>
                      <option value="<?php echo($cont); ?>"><?php echo($cont); ?></option>
                    <?php $cont = ($cont-1); } ?>
                    </select>
                    <div class="invalid-feedback" id="invalid-cmbAño">
                      El estado no puede estar vacio.
                    </div>
                </div>
                <div class="col-md-2 mb-3">
                  <button id="download_report" type="button" class="btn-custom btn-custom--blue" onclick="Descarga_excel()">Descargar</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- /.container-fluid -->
  </div>
  <!-- End of Main Content -->
</div>

<script src="../../../js/sb-admin-2.min.js"></script>
<script src="../../../js/scripts.js"></script>
<script src="../../../js/Sortable.js"></script>
<script src="../../../js/pagination/pagination.js"></script>