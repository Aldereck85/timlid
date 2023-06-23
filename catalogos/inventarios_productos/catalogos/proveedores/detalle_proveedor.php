<?php
session_start();
require_once '../../../../include/db-conn.php';
$user = $_SESSION["Usuario"];
$PKEmpresa = $_SESSION["IDEmpresa"];

$proveedor_id = $_GET["proveedor_id"];

$stmt = $conn->prepare("SELECT NombreComercial, RFC, empresa_id  FROM proveedores p left join domicilio_fiscal_proveedor dfp on p.PKProveedor = dfp.FKProveedor WHERE p.PKProveedor = :id");
$stmt->bindValue(':id', $proveedor_id, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch();

$GLOBALS["PKProveedor"] = $row['empresa_id'];
$GLOBALS["NombreComercial"] = $row['NombreComercial'];
$GLOBALS["RFC"] = $row['RFC'];

if (isset($_SESSION["Usuario"])) {
  require_once '../../../../include/db-conn.php';
  $user = $_SESSION["Usuario"];

 /*  if ($GLOBALS["PKProveedor"] != $PKEmpresa) {
    header("location:../proveedores/");
  } */
} else {
  header("location:../../../dashboard");
}
  $jwt_ruta = "../../../../";
  require_once '../../../jwt.php';
  $token = $_SESSION['token_ld10d'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" type="image/png" href="../../../../img/header/bluTimlid.png">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Timlid | Detalles proveedor</title>

  <!-- ESTILOS -->
  <link href="../../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../../../vendor/datatables/responsive.dataTables.css" rel="stylesheet">
  <link href="../../../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../../../css/styles.css" rel="stylesheet">
  <link href="../../../../css/stylesNewTable.css" rel="stylesheet">
  <link href="../../../../css/notificaciones.css" rel="stylesheet">
  <!-- JS -->
  <script src="../../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../../../vendor/datatables/dataTables.responsive.js"></script>
  <script src="../../../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../../../vendor/jszip/jszip.min.js"></script>
</head>

<body id="page-top">
  <!-- Page Wrapper -->

  <div id="wrapper">

    <!-- Sidebar -->
    <?php
    $ruta = "../../../";
    $ruteEdit = $ruta . "central_notificaciones/";
    require_once $ruta . 'menu3.php';
    ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <?php
        $rutatb = "../../../";
        $icono = 'ICONO-CLIENTES-AZUL.svg';
        $titulo = 'Detalles proveedor';
        $backIcon = true;
        require_once $rutatb . 'topbar.php';
        ?>
        <!-- End of Topbar -->
        <input type="hidden" id="txtUsuario" value="<?= $_SESSION['PKUsuario']; ?>">
        <input type="hidden" id="txtRuta" value="<?= $ruta; ?>">
        <input type="hidden" id="txtEdit" value="<?= $ruteEdit; ?>">
        <input type="hidden" id="hddPKProveedor" value="<?= $proveedor_id; ?>">
        .

        <!-- Begin Page Content -->
        <div class="container-fluid" id="upPg">
          
          <div class="row">
            <div class="col-lg-12">
              <ul class="nav nav-tabs">
                <li class="nav-item">
                  <a id="cargarDatosPagos" class="nav-link" onclick="cargarDatosPagos($('#hddPKProveedor').val())">
                    Cuentas por pagar
                  </a>
                </li>
                <li class="nav-item">
                  <a id="cargarDatosOrdenes" class="nav-link" onclick="cargarDatosOrdenes($('#hddPKProveedor').val())">
                    Órdenes de compra
                  </a>
                </li>
                
              </ul>
              <input id="PKUsuario" value="<?php echo $_SESSION["PKUsuario"]; ?>" type="hidden">

              <div class="card shadow mb-4">
                <div class="card-body">
                  <div class="row">
                    <div class="col-6">
                      <h2 Style="color:var(--color-primario)"><?php echo $GLOBALS["NombreComercial"]; ?></h2>
                    </div>
                    <div class="col-3">
                    <label for="lblTotalCA">Credito Asignado:</label>
                    <h4 id="lblTotalCA"></h4>
                    </div>
                    <div class="col-3">
                      <label for="lblTotal">Total Cuentas por Cobrar:</label>
                      <h4 id="lblTotal"></h4>
                    </div>
                    <div class="col-6">
                      <h4 Style="color:var(--color-primario)">RFC: <?php echo $GLOBALS["RFC"]; ?></h4>
                      <a href="#" onclick="obtenerIdProveedorEditar(<?=$proveedor_id;?>)"><img src="../../../../img/Proveedores/BOTON EDITAR PROVEEDOR AZUL NVO-01.svg" alt="" width="240"></a>
                    </div>
                    <div class="col-3">
                      <label for="lblTotalCD">Credito Disponible:</label>
                      <h4 id="lblTotalCD"></h4>
                    </div>
                    <div class="col-3">
                      <label for="lblTotalCuV">Total Cuentas Vencidas:</label>
                      <h4 id="lblTotalCuV"></h4>
                    </div>
                  </div>
                  <!-- Basic Card Example -->
                  <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="datos" role="tabpanel" aria-labelledby="nav-main-tab">
                    
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- End Begin Page Content -->

        </div>
        <!-- End Main Content -->

        <!-- Footer -->
        <?php
        $rutaf = "../../../";
        require_once $rutaf . 'footer.php';
        ?>
      </div>
      <!-- End Content Wrapper -->
    </div>
    <!-- End Page Wrapper -->
    <script src="../../../../js/sb-admin-2.min.js"></script>
    <script src="../../../../js/scripts.js"></script>
    <script src="../../../../js/notificaciones_timlid.js" charset="utf-8"></script>
    <script src="../../js/detalle_proveedor.js" charset="utf-8"></script>
    <script type="text/javascript">
      cargarDatosPagos(parseInt($('#hddPKProveedor').val()));
    </script>
</body>

</html>