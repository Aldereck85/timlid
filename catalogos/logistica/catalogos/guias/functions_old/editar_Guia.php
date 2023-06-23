<?php
session_start();
if (isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 1 || $_SESSION["FKRol"] == 3 || $_SESSION["FKRol"] == 4 || $_SESSION["FKRol"] == 6)) {
    require_once '../../../include/db-conn.php';
    if (isset($_POST['btnEditar'])) {
        $id = (int) $_POST['txtId'];
        $descripcion = $_POST['txtDescripcion'];
        $tipopago = $_POST['cmbTipoPago'];
        try {
            $stmt = $conn->prepare('UPDATE guias_envio set Descripcion = :descripcion, Tipo_de_Pago = :tipopago WHERE PKGuiaEnvio = :id');
            $stmt->bindValue(':descripcion', $descripcion);
            $stmt->bindValue(':tipopago', $tipopago);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            header('Location:../index.php');
        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    }

    if (isset($_POST['idGuiaU'])) {
        $id = $_POST['idGuiaU'];
        $stmt = $conn->prepare('SELECT * FROM guias_envio WHERE PKGuiaEnvio= :id');
        $stmt->execute(array(':id' => $id));
        $row = $stmt->fetch();
        $descripcion = $row['Descripcion'];
        $paqueteria = $row['FKPaqueteria'];
        $tipoPago = $row['Tipo_de_Pago'];
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

  <title>Timlid | Editar guía</title>

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
  <link href="../../../css/sb-admin-2.css" rel="stylesheet">

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
$titulo = "Editar Guia de Envio";
require_once '../../topbar.php';
?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Editar guias de envio</h1>
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
                              <select class="form-control" name="cmbPaqueteria" disabled>
                                <option value="">Seleccione una opcion...</option>
                                <?php
$stmt = $conn->prepare('SELECT * FROM paqueterias');
$stmt->execute();
while ($row = $stmt->fetch()) {
    ?>
                                <option value="<?=$row['PKPaqueteria'];?>" <?php if ($row['PKPaqueteria'] == $paqueteria) {
        echo "selected";
    }
    ?>><?=$row['Nombre_Comercial'];?></option>
                                <?php
}
?>
                              </select>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">Tipo de pago:*</label>
                              <select class="form-control" name="cmbTipoPago">
                                <option value="">Seleccion una opcion...</option>
                                <option value="0" <?php if ($tipoPago == 0) {
    echo "selected";
}
?>>Prepagadas</option>
                                <option value="1" <?php if ($tipoPago == 1) {
    echo "selected";
}
?>>Por consumo</option>
                              </select>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">Descripción:*</label>
                              <input type="text" class="form-control alphaNumeric-only" maxlength="40"
                                name="txtDescripcion" value="<?=$descripcion?>" required>
                            </div>
                          </div>
                        </div>
                        <input type="hidden" name="txtId" value="<?=$id;?>">
                        <button type="submit" class="btn btn-primary float-right" name="btnEditar">Editar</button>
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

</body>

</html>