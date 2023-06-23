<?php
session_start();

if (isset($_SESSION["Usuario"])) {
    require_once '../../../include/db-conn.php';
    if (isset($_POST['btnEditar'])) {
        $id = (int) $_POST['txtId'];
        $razon = $_POST['txtRazon'];
        $nombre = $_POST['txtNombre'];
        $rfc = $_POST['txtRFC'];
        $calle = $_POST['txtCalle'];
        $numEx = $_POST['txtNumeroEx'];
        $numInt = $_POST['txtNumeroInt'];
        $colonia = $_POST['txtColonia'];
        $municipio = $_POST['txtMunicipio'];
        $estado = $_POST['cmbEstados'];
        $cp = $_POST['txtCP'];

        try {
            $stmt = $conn->prepare('UPDATE paqueterias set Razon_Social= :razon, Nombre_comercial= :nombre,RFC= :rfc,Calle =:calle,Numero_exterior =:numEx,Numero_Interior =:numInt,Colonia =:colonia,Municipio =:municipio,Estado =:estado,CP =:cp WHERE PKPaqueteria = :id');
            $stmt->bindValue(':razon', $razon);
            $stmt->bindValue(':nombre', $nombre);
            $stmt->bindValue(':rfc', $rfc);
            $stmt->bindValue(':calle', $calle);
            $stmt->bindValue(':numEx', $numEx);
            $stmt->bindValue(':numInt', $numInt);
            $stmt->bindValue(':colonia', $colonia);
            $stmt->bindValue(':municipio', $municipio);
            $stmt->bindValue(':estado', $estado);
            $stmt->bindValue(':cp', $cp);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            header('Location:../index.php');
        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    }

    if (isset($_POST['idPaqueteriaU'])) {
        $id = $_POST['idPaqueteriaU'];
        $stmt = $conn->prepare('SELECT * FROM paqueterias WHERE PKPaqueteria= :id');
        $stmt->execute(array(':id' => $id));
        $row = $stmt->fetch();
        $razon = $row['Razon_Social'];
        $nombre = $row['Nombre_Comercial'];
        $rfc = $row['RFC'];
        $calle = $row['Calle'];
        $numEx = $row['Numero_Exterior'];
        $numInt = $row['Numero_Interior'];
        $colonia = $row['Colonia'];
        $municipio = $row['Municipio'];
        $estado = $row['Estado'];
        $cp = $row['CP'];
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

  <title>Timlid | Editar paqueteria</title>

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
$titulo = "Editar Paqueteria";
require_once '../../topbar.php';
?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Editar paqueteria</h1>
          </div>


          <div class="row">

            <div class="col-lg-12">

              <!-- Basic Card Example -->
              <div class="card shadow mb-4">
                <div class="card-header">
                  Tarjeta de paqueteria
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <form action="" method="post">
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-4">
                              <label for="usr">Razón social:*</label>
                              <input type="text" class="form-control alphaNumeric-only" maxlength="30" name="txtRazon"
                                value="<?=$razon;?>" required>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">Nombre comercial:*</label>
                              <div class="input-group mb-3">
                                <input type="text" class="form-control alphaNumeric-only" maxlength="30"
                                  name="txtNombre" value="<?=$nombre;?>" required>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">RFC:*</label>
                              <input type="text" class="form-control alphaNumeric-only" maxlength="30" name="txtRFC"
                                value="<?=$rfc;?>" required>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-lg-3">
                              <label for="usr">Calle:*</label>
                              <input type="text" class="form-control alpha-only" maxlength="30" name="txtCalle"
                                value="<?=$calle;?>" required>
                            </div>
                            <div class="col-lg-3">
                              <label for="usr">Numero exterior:*</label>
                              <div class="input-group mb-3">
                                <input type="text" class="form-control numeric-only" maxlength="30" name="txtNumeroEx"
                                  value="<?=$numEx;?>" required>
                              </div>
                            </div>
                            <div class="col-lg-3">
                              <label for="usr">Numero interior:</label>
                              <input type="text" class="form-control alphaNumeric-only" maxlength="30"
                                name="txtNumeroInt" value="<?=$numInt;?>">
                            </div>
                            <div class="col-lg-3">
                              <label for="usr">Colonia:*</label>
                              <input type="text" class="form-control alpha-only" maxlength="30" name="txtColonia"
                                value="<?=$colonia;?>" required>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-lg-4">
                              <label for="usr">Municipio:*</label>
                              <div class="input-group mb-3">
                                <input type="text" class="form-control alpha-only" maxlength="30" name="txtMunicipio"
                                  value="<?=$municipio;?>" required>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">Estado:*</label>
                              <select name="cmbEstados" class="form-control" id="cmbEstados" required>
                                <option value="">Seleccionar estado</option>
                                <option value="Aguascalientes" <?php if ($estado == "Aguascalientes") {
    echo 'selected';
}
?>>Aguascalientes</option>
                                <option value="Baja California" <?php if ($estado == "Baja California") {
    echo 'selected';
}
?>>Baja California</option>
                                <option value="Baja California Sur" <?php if ($estado == "Baja California Sur") {
    echo 'selected';
}
?>>Baja California Sur</option>
                                <option value="Campeche" <?php if ($estado == "Campeche") {
    echo 'selected';
}
?>>Campeche</option>
                                <option value="Coahuila de Zaragoza" <?php if ($estado == "Coahuila de Zaragoza") {
    echo 'selected';
}
?>>Coahuila de Zaragoza</option>
                                <option value="Colima" <?php if ($estado == "Colima") {
    echo 'selected';
}
?>>Colima</option>
                                <option value="Chiapas" <?php if ($estado == "Chiapas") {
    echo 'selected';
}
?>>Chiapas</option>
                                <option value="Chihuahua" <?php if ($estado == "Chihuahua") {
    echo 'selected';
}
?>>Chihuahua</option>
                                <option value="Distrito Federal" <?php if ($estado == "Distrito Federal") {
    echo 'selected';
}
?>>Distrito Federal</option>
                                <option value="Durango" <?php if ($estado == "Durango") {
    echo 'selected';
}
?>>Durango</option>
                                <option value="Guanajuato" <?php if ($estado == "Guanajuato") {
    echo 'selected';
}
?>>Guanajuato</option>
                                <option value="Guerrero" <?php if ($estado == "Guerrero") {
    echo 'selected';
}
?>>Guerrero</option>
                                <option value="Hidalgo" <?php if ($estado == "Hidalgo") {
    echo 'selected';
}
?>>Hidalgo</option>
                                <option value="Jalisco" <?php if ($estado == "Jalisco") {
    echo 'selected';
}
?>>Jalisco</option>
                                <option value="México" <?php if ($estado == "México") {
    echo 'selected';
}
?>>México</option>
                                <option value="Michoacán" <?php if ($estado == "Michoacán") {
    echo 'selected';
}
?>>Michoacán de Ocampo</option>
                                <option value="Morelos" <?php if ($estado == "Morelos") {
    echo 'selected';
}
?>>Morelos</option>
                                <option value="Nayarit" <?php if ($estado == "Nayarit") {
    echo 'selected';
}
?>>Nayarit</option>
                                <option value="Nuevo León" <?php if ($estado == "Nuevo León") {
    echo 'selected';
}
?>>Nuevo León</option>
                                <option value="Oaxaca" <?php if ($estado == "Oaxaca") {
    echo 'selected';
}
?>>Oaxaca</option>
                                <option value="Puebla" <?php if ($estado == "Puebla") {
    echo 'selected';
}
?>>Puebla</option>
                                <option value="Querétaro" <?php if ($estado == "Querétaro") {
    echo 'selected';
}
?>>Querétaro</option>
                                <option value="Quintana Roo" <?php if ($estado == "Quintana Roo") {
    echo 'selected';
}
?>>Quintana Roo</option>
                                <option value="San Luis Potosí" <?php if ($estado == "San Luis Potosí") {
    echo 'selected';
}
?>>San Luis Potosí</option>
                                <option value="Sinaloa" <?php if ($estado == "Sinaloa") {
    echo 'selected';
}
?>>Sinaloa</option>
                                <option value="Sonora" <?php if ($estado == "Sonora") {
    echo 'selected';
}
?>>Sonora</option>
                                <option value="Tabasco" <?php if ($estado == "Tabasco") {
    echo 'selected';
}
?>>Tabasco</option>
                                <option value="Tamaulipas" <?php if ($estado == "Tamaulipas") {
    echo 'selected';
}
?>>Tamaulipas</option>
                                <option value="Tlaxcala" <?php if ($estado == "Tlaxcala") {
    echo 'selected';
}
?>>Tlaxcala</option>
                                <option value="Veracruz" <?php if ($estado == "Veracruz") {
    echo 'selected';
}
?>>Veracruz de Ignacio de la Llave</option>
                                <option value="Yucatán" <?php if ($estado == "Yucatán") {
    echo 'selected';
}
?>>Yucatán</option>
                                <option value="Zacatecas" <?php if ($estado == "Zacatecas") {
    echo 'selected';
}
?>>Zacatecas</option>
                              </select>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">Código Postal:*</label>
                              <input type="text" class="form-control numeric-only" maxlength="30" name="txtCP"
                                value="<?=$cp;?>" required>
                            </div>

                          </div>
                        </div>
                        <label for="">* Campos requeridos</label>
                        <input type="hidden" name="txtId" value="<?=$id;?>">
                        <button type="submit" class="btn-custom btn-custom--blue float-right"
                          name="btnEditar">Editar</button>
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