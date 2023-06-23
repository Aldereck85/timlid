<?php
session_start();
if (isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 1 || $_SESSION["FKRol"] == 3 || $_SESSION["FKRol"] == 4 || $_SESSION["FKRol"] == 6)) {
    require_once '../../../include/db-conn.php';
    if (isset($_POST['btnAgregar'])) {
        $paqueteria = $_POST['cmbPaqueteria'];
        $tipoPago = $_POST['cmbTipoPago'];
        $descripcion = $_POST['txtDescripcion'];

        try {
            $stmt = $conn->prepare('INSERT INTO guias_envio (Descripcion, Tipo_de_Pago,FKPaqueteria) VALUES (:descripcion, :tipopago,:paqueteria)');
            $stmt->bindValue(':descripcion', $descripcion);
            $stmt->bindValue(':tipopago', $tipoPago);
            $stmt->bindValue(':paqueteria', $paqueteria);
            $stmt->execute();
            $idLast = $conn->lastInsertId();
            $clave = "GE" . str_pad($idLast, 5, "0", STR_PAD_LEFT);

            $stmt = $conn->prepare('SELECT PKMarca,Marca FROM paqueterias
                              LEFT JOIN marcas ON Nombre_Comercial = Marca
                              WHERE PKPaqueteria = :id');
            $stmt->bindValue(':id', $paqueteria);
            $stmt->execute();
            $idMarca = $stmt->fetch()['PKMarca'];
            $marca = $stmt->fetch()['Marca'];
            $guia = $marca . " " . $descripcion;

            $stmt = $conn->prepare('INSERT INTO productos (Clave,Descripcion,FKMarca,FKCategoria,FKUnidadMedida,TipoProducto) VALUES (:clave,:descripcion,:marca,:categoria,:medida,:tipo)');
            $stmt->bindValue(':clave', $clave);
            $stmt->bindValue(':descripcion', $guia);
            $stmt->bindValue(':marca', $idMarca);
            $stmt->bindValue(':categoria', 1);
            $stmt->bindValue(':medida', 1);
            $stmt->bindValue(':tipo', 4);
            $stmt->execute();
            $idUltimo = $conn->lastInsertId();

            $stmt = $conn->prepare('UPDATE guias_envio SET FKProducto= :producto WHERE PKGuiaEnvio = :id');
            $stmt->bindValue(':producto', $idUltimo);
            $stmt->bindValue(':id', $idLast);
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

  <title>Timlid | Agregar marca</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../../js/validaciones.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../../../js/sb-admin-2.min.js"></script>

  <script src="../../../js/bootstrap-clockpicker.min.js"></script>

  <!-- Custom fonts for this template-->
  <link href="../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../../css/bootstrap-clockpicker.min.css" rel="stylesheet" type="text/css">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../css/styles.css" rel="stylesheet">

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
$ruta = "../../";
$ruteEdit = $ruta . "central_notificaciones/";
require_once '../../menu3.php';
?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <?php
$rutatb = "../../";
$titulo = "Agregar Guia de Envio";
require_once '../../topbar.php';
?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Agregar guias de envio</h1>
          </div>


          <div class="row">

            <div class="col-lg-12">

              <!-- Basic Card Example -->
              <div class="card shadow mb-4">
                <div class="card-header">
                  Tarjeta de guias de envio
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <form action="" method="post">
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-4">
                              <label for="usr">Paqueteria:*</label>
                              <select class="form-control" name="cmbPaqueteria">
                                <option value="">Seleccione una opcion...</option>
                                <?php
$stmt = $conn->prepare('SELECT * FROM paqueterias');
$stmt->execute();
while ($row = $stmt->fetch()) {
    ?>
                                <option value="<?=$row['PKPaqueteria'];?>"><?=$row['Nombre_Comercial'];?></option>
                                <?php
}
?>
                              </select>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">Tipo de pago:*</label>
                              <select class="form-control" name="cmbTipoPago">
                                <option value="">Seleccion una opcion...</option>
                                <option value="0">Prepagadas</option>
                                <option value="1">Por consumo</option>
                              </select>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">Descripción:*</label>
                              <input type="text" class="form-control alphaNumeric-only" maxlength="40"
                                name="txtDescripcion" required>
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

  <script>
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
  <script src="../../../js/sb-admin-2.min.js"></script>
  <script src="../../../js/scripts.js"></script>

</body>

</html>