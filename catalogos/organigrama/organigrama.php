<?php
session_start();

if (isset($_SESSION["Usuario"])) {
    require_once '../../include/db-conn.php';
    $user = $_SESSION["Usuario"];
} else {
    header("location:../dashboard.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="icon" type="image/png" href="../../img/header/bluTimlid.png">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Timlid | Organigrama</title>
  <!-- Page level plugins -->

  <!-- Custom fonts for this template -->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">

  <!-- Custom styles for this page -->
  <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

  <link rel="stylesheet" href="../../css/jquery.jOrgChart.css" />
  <link href="../../css/styles.css" rel="stylesheet">
  <link rel="stylesheet" href="../../css/custom.css" />
  <script type="text/javascript" src="../../js/jquery.organigrama.js"></script>
  <script src="../../js/jquery.jOrgChart.js"></script>
  <script type="text/javascript" src="../../js/jquery-ui.min.js"></script>
  <script>
  var $jq1111 = jQuery.noConflict(true);
  </script>

  <script src="../../vendor/jquery/jquery.min.js"></script>
  <!-- Core plugin JavaScript-->
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../js/scripts.js"></script>
</head>

<body id="page-top" class="sidebar-toggled">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
$icono = '../../img/icons/ICONO ORGANIGRAMA-01.svg';
$titulo = "Organigrama";
$ruta = "../";
$ruteEdit = $ruta . "central_notificaciones/";
require_once '../menu3.php';
?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <?php
$rutatb = "../";
require_once '../topbar.php';
?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800">Organigrama</h1>
          <p class="mb-4">Información general del organigrama de la empresa</p>

          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <?php
echo '<div class="card-header py-3">

                        <a href="index.php" class="btn btn-info float-right" style="position: relative; right: 20px;"><i class="fas fa-project-diagram"></i> Editar organigrama</a>
                  </div>';
?>
            <div class="card-body" align="center">
              <?php

$data = array();
$index = array();
$stmt = $conn->prepare("SELECT o.PKOrganigrama, o.ParentNode, o.Imagen_Perfil,e.Nombres, e.PrimerApellido, e.SegundoApellido, p.puesto FROM organigrama as o INNER JOIN empleados as e ON e.PKEmpleado = o.FKEmpleado LEFT JOIN datos_laborales_empleado as de ON de.FKEmpleado = e.PKEmpleado LEFT JOIN puestos as p ON p.id = de.FKPuesto WHERE e.empresa_id = :idempresa ORDER BY o.PKOrganigrama");
$stmt->bindValue(':idempresa', $_SESSION['IDEmpresa']);
$stmt->execute();
$numElementos = $stmt->rowCount();

while ($row = $stmt->fetch()) {
    $id = $row["PKOrganigrama"];
    $parent_id = $row["ParentNode"] === null ? "NULL" : $row["ParentNode"];
    $data[$id] = $row;
    $index[$parent_id][] = $id;
}

function display_child_nodes($parent_id, $level)
{
    $PKEmpresa = $_SESSION["IDEmpresa"];
    global $data, $index;
    $parent_id = $parent_id === null ? "NULL" : $parent_id;
    if (isset($index[$parent_id])) {
        echo '<ul>';
        foreach ($index[$parent_id] as $id) {
            //echo str_repeat("-", $level) . " / ".$level." / ".$data[$id]["Imagen_Perfil"];
            $nombreEmpleado = $data[$id]['Nombres'] . " " . $data[$id]['PrimerApellido'];

            if ($data[$id]["Imagen_Perfil"] == "") {
                $imagenNodo = "lideroganizacion48487646.png";
            } else {
                $imagenNodo = $data[$id]["Imagen_Perfil"];
            }
            if ($level == 0) {

                echo '<ul id="org" style="display:none">
                                  <li>' .
                    '<img src="functions/perfil/' . $imagenNodo . '"  class="img-organigrama" />' .
                    '<span class="puesto">' . $data[$id]["puesto"] . '</span><br><br><br>
                                        <div style="clear: both;"></div>
                                         <span class="nombreempleado">' . $nombreEmpleado . '</span>
                                         <input type="hidden" id="input" value="' . $data[$id]["PKOrganigrama"] . '" />';

            } else {

                echo '
                   <li>
                   <img src="../empresas/archivos/'.$PKEmpresa.'/img'.'/' . $imagenNodo . '"  class="img-organigrama" />
                <span class="puesto">' . $data[$id]["puesto"] . '</span><br><br><br>
                <div style="clear: both;"></div>
                <span class="nombreempleado">' . $nombreEmpleado . '</span>
                <input type="hidden" id="input" value="' . $data[$id]["PKOrganigrama"] . '" /> ';
            }

            display_child_nodes($id, $level + 1);
            //echo "level prime ".$level."<br>";

        }
        if ($level == 0) {
            echo '</ul>';
        }

        echo '</ul>';

    }
}

if ($numElementos == 0) {
    echo '<br><br><div class="col-md-12"><h2><center>NO HAY NINGUN TRABAJADOR AGREGADO EN EL ORGANIGRAMA</center></h2></div>';
} else {
    display_child_nodes(null, 0);
}

?>


              <div id="chart" class="orgChart"></div>


            </div>
          </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <?php
$rutaf = "../";
require_once '../footer.php';
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
  var ruta = "../";
  </script>
  <script src="../../js/sb-admin-2.min.js"></script>
  <script>
  $jq1111("#org").jOrgChart({
    chartElement: '#chart',
    dragAndDrop: true
  });
  </script>
</body>

</html>