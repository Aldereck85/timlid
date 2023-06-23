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


<!-- <head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" type="image/png" href="../../../../img/header/bluTimlid.png">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Timlid | Reportes ventas</title> -->

<!-- ESTILOS -->
<!--   <link href="../../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../../../css/sweetalert2.css" rel="stylesheet">
  <link href="../../../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../../../css/styles.css" rel="stylesheet">
  <link href="../../../../css/stylesNewTable.css" rel="stylesheet"> -->
<!-- JS-->
<!--   <script src="../../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../../../js/sb-admin-2.min.js"></script>
  <script src="../../../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../../../vendor/jszip/jszip.min.js"></script>
  <script src="../../../../js/slimselect.min.js"></script>
  <script src="../../../../js/sweet/sweetalert2.js"></script>
</head> -->
<script src="../../../js/jquery.redirect.min.js"></script>
<script src="../../../vendor/chart.js/Chart.min.js"></script>
<script src="js/reportes-ventas.js" charset="utf-8"></script>


<!-- Content Wrapper -->
<div class="tab-pane fade" id="ventas" role="tabpanel" aria-labelledby="nav-main-tab">
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
          <div class="filtros mb-4">
            <form>
              <div class="form-row align-items-center">
                <div class="col-md-2 mb-3">
                  <label for="vendedor_input">Vendedor</label>
                  <select name="" class="form-control" id="vendedor_input" onchange="validEmptyInput(this)">
                    <option disabled selected>Selecciona un vendedor</option>
                    <option value="todos">Todos</option>
                    <?php foreach ($vendedores as $vendedor) { ?>
                      <option value="<?= $vendedor["id"] ?>"><?= $vendedor["nombre"] ?></option>
                    <?php } ?>
                  </select>
                  <div class="invalid-feedback mb-n4" id="invalid-vendedor">
                    El vendedor no puede estar vacio.
                  </div>
                </div>
                <div class="col-md-2 mb-3">
                  <label for="cliente_input">Cliente</label>
                  <select name="" class="form-control" id="cliente_input" onchange="validEmptyInput(this)">
                    <option disabled selected>Selecciona un cliente</option>
                    <option value="todos">Todos</option>
                    <?php foreach ($clientes as $cliente) { ?>
                      <option value="<?= $cliente["id"] ?>"><?= $cliente["nombre"] ?></option>
                    <?php } ?>
                  </select>
                  <div class="invalid-feedback mb-n4" id="invalid-cliente">
                    El cliente no puede estar vacio.
                  </div>
                </div>
                <div class="col-md-2 mb-3">
                  <label for="estado_input">Estado</label>
                  <select name="" class="form-control" id="estado_input" onchange="validEmptyInput(this)">
                    <option disabled selected>Selecciona un estado</option>
                    <option value="todos">Todos</option>
                    <?php foreach ($estados as $estado) { ?>
                      <option value="<?= $estado["id"] ?>"><?= $estado["estado"] ?></option>
                    <?php } ?>
                  </select>
                  <div class="invalid-feedback mb-n4" id="invalid-estado">
                    El estado no puede estar vacio.
                  </div>
                </div>
                <div class="col-md-2 mb-3">
                  <label for="fechaInic_input">Fecha inicial</label>
                  <input type="date" name="fechaInic_input" id="fechaInic_input" class="form-control dateRange">
                 <!--  <div class="invalid-feedback" id="invalid-fechaInic">
                    La fecha inical no puede estar vacia.
                  </div> -->
                </div>
                <div class="col-md-2 mb-3">
                  <label for="fechaFin_input">Fecha final</label>
                  <input type="date" name="fechaFin_input" id="fechaFin_input" class="form-control dateRange" >
                  <!-- <div class="invalid-feedback" id="invalid-fechaFin">
                    La fecha final no puede estar vacia.
                  </div> -->
                </div>
                <div class="col-md-2 mb-3">
                  <label for="cmbMesV">Mes:</label>
                  <select name="" id="cmbMesV" class="form-select" multiple></select>
                  <div class="invalid-feedback" id="invalid-cmbMesV">

                  </div>
                </div>
                <div class="col-md-2 mb-3">
                  <button id="fitro_reportes" type="button" class="btn-custom btn-custom--blue mt-3">Filtrar</button>
                </div>
              </div>
              <div class="row">
                <div class="col-4">
                  <h2 class="text-center">
                    <b class="textBlue text-center" from="totalText">Total Facturado:</b><br>
                      <b class="total_facturado text-center" id="totalText">$0.00</b>
                  </h2>
                </div>
                <div class="col-4">
                  <h2 class="text-center">
                    <b class="textBlue text-center" from="totalText">Total No Facturado:</b><br>
                      <b class="total_no_facturado text-center" id="totalText">$0.00</b>
                  </h2>
                </div>
                <div class="col-4">
                  <h2 class="text-center">
                    <b class="textBlue text-center" from="totalText">Total Global:</b><br>
                      <b class="total_global text-center" id="totalText">$0.00</b>
                  </h2>
                </div>
              </div>
            </form>
          </div>
          <div class="table-responsive">
            <table class="table d-none" id="tblReporteVentas" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Vendedor</th>
                  <th>Cliente</th>
                  <th>Importe Facturado</th>
                  <th>Importe Sin Facturar</th>
                  <th>Importe Global</th>
                  <th>Estado</th>
                  <th>Mes</th>
                  <th></th>
                </tr>
              </thead>
            </table>
          </div>
          <!-- <div class="row" id="container-canvas">
          </div> -->

        </div>
      </div>

    </div>
    <!-- /.container-fluid -->

  </div>
  <!-- End of Main Content -->
</div>
<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
  <i class="fas fa-angle-up"></i>
</a>

<script src="../../../js/sb-admin-2.min.js"></script>
<script src="../../../js/scripts.js"></script>
<script src="../../../js/Sortable.js"></script>
<script src="../../../js/pagination/pagination.js"></script>