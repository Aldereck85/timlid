<?php
session_start();

if (isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 1 || $_SESSION["FKRol"] == 3 || $_SESSION["FKRol"] == 4 || $_SESSION["FKRol"] == 6)) {
    require_once '../../../include/db-conn.php';
    if (isset($_POST['btnAgregar'])) {

        $numeroRastreo = $_POST['txtNumeroRastreo'];
        $estatus = 'En proceso';
        $factura = $_POST['cmbIdFactura'];
        $paqueteria = $_POST['cmbPaqueteria'];

        try {
            $stmt = $conn->prepare('INSERT INTO envios (Numero_rastreo,Estatus,FKFactura,FKPaqueteria)VALUES(:numero_rastreo,:estatus,:fkFactura,:fkPaqueteria)');
            $stmt->bindValue(':numero_rastreo', $numeroRastreo);
            $stmt->bindValue(':estatus', $estatus);
            $stmt->bindValue(':fkFactura', $factura);
            $stmt->bindValue(':fkPaqueteria', $paqueteria);
            $stmt->execute();

            header('Location:../index.php');
        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }

    }
} else {
    header("location:../../dashboard.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="icon" type="image/png" href="../../../img/header/bluTimlid.png">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Timlid | Agregar envios </title>

  <!-- Custom fonts for this template-->
  <link href="../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../../css/bootstrap-clockpicker.min.css" rel="stylesheet" type="text/css">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css"
    integrity="sha256-EH/CzgoJbNED+gZgymswsIOrM9XhIbdSJ6Hwro09WE4=" crossorigin="anonymous" />
  <link href="../../../css/chosen.css" rel="stylesheet" type="text/css">

  <!-- Custom styles for this template-->
  <link href="../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../css/styles.css" rel="stylesheet">

  <!-- Bootstrap core JavaScript-->
  <script src="../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../../js/validaciones.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"
    integrity="sha256-c4gVE6fn+JRKMRvqjoDp+tlG4laudNYrXI1GncbfAYY=" crossorigin="anonymous"></script>

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->

    <?php
$titulo = "Cambiar";
$ruta = "../../";
$ruteEdit = "../central_notificaciones/";
require_once '../../menu3.php';
?>

    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <?php
$rutatb = "../../";
require_once '../../topbar.php';
?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-truck-loading"></i> Agregar envios</h1>
          </div>


          <div class="row">

            <div class="col-lg-12">

              <!-- Basic Card Example -->
              <div class="card shadow mb-4">
                <div class="card-header">
                  Tarjeta de paqueterias
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <form action="" method="post">
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-4">
                              <label for="usr">Numero de rastreo:</label>
                              <input type="text" maxlength="20" class="form-control alphaNumeric-only"
                                name="txtNumeroRastreo">
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">Factura:</label>
                              <select name="cmbIdFactura" id="cmbIdFactura" class="form-control" required>
                                <option value="">Elegir opción</option>
                                <?php

$stmt = $conn->prepare('SELECT f.PKFacturacion,f.Folio,df.Razon_Social FROM facturacion as f LEFT JOIN domicilio_fiscal as df ON df.PKDomicilioFiscal = f.FKDomicilioFiscal WHERE f.UUID <> "" AND f.Version <> "" AND f.Estatus = "Pagado" AND f.Enviado = 0');

$stmt->execute();
$row = $stmt->fetchAll();

if ($stmt->rowCount() > 0) {
    foreach ($row as $r) {
        echo '<option value="' . $r['PKFacturacion'] . '">' . $r['Folio'] . ' - ' . $r['Razon_Social'] . '</option>';
    }

} else {
    echo '<option value="" disabled>No hay pedidos para enviar.</option>';
}
?>
                              </select>
                              <!--<label for="usr">Factura:</label>
                                <input type="text" maxlength="20" class="form-control numeric-only"  name="txtFactura">-->
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">Paqueteria:</label>
                              <select class="form-control" name="cmbPaqueteria" required>
                                <option value="">Elegir opción</option>
                                <?php
$stmt = $conn->prepare('SELECT * FROM paqueterias');
$stmt->execute();
?>
                                <?php foreach ($stmt as $option): ?>
                                <option value="<?php echo $option['PKPaqueteria']; ?>">
                                  <?php echo $option['Nombre_Comercial']; ?></option>
                                <?php endforeach;?>
                              </select>
                            </div>
                          </div>
                        </div>

                        <button type="submit" class="btn btn-success float-right" name="btnAgregar">Agregar</button>
                      </form>
                    </div>
                  </div>

                </div>
              </div>

            </div>
          </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <?php
$rutaf = "../../";
require_once '../../footer.php';
?>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Core plugin JavaScript-->
  <script src="../../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../../../js/sb-admin-2.min.js"></script>
  <script src="../../../js/scripts.min.js"></script>

  <script src="../../../js/bootstrap-clockpicker.min.js"></script>

  <script>
  $(document).ready(function() {
    $("#cmbIdFactura").chosen();
  });

  $(document).ready(function() {
    $("#alertaTareas").load('../../alerta_Tareas_Nuevas.php?user=' + <?=$_SESSION['PKUsuario'];?> + '&ruta=' +
      '<?=$ruta;?>');
    setInterval(refrescar, 5000);
  });

  function refrescar() {
    $("#alertaTareas").load('../../alerta_Tareas_Nuevas.php?user=' + <?=$_SESSION['PKUsuario'];?> + '&ruta=' +
      '<?=$ruta;?>');
  }
  </script>
  <script>
  var ruta = "../../";
  </script>

</body>

</html>