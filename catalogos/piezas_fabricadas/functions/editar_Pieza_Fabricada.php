<?php
session_start();

  if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 4 || $_SESSION["FKRol"] == 1)){
    require_once('../../../include/db-conn.php');
      if(isset ($_POST['btnEditar'])){
        $id = (int) $_POST['txtId'];
        $nombrePieza = $_POST['txtNombrePieza'];
        $largo = floatval($_POST['txtLargo']);
        $ancho = floatval($_POST['txtAncho']);
        $rollo= $_POST['cmbRollos'];
        try{
        $stmt = $conn->prepare('UPDATE piezas_fabricadas set NombrePiezas= :nombrePieza, Largo= :largo, Ancho =:ancho, FKRollo =:rollo WHERE PKPiezaFabricada = :id');
        $stmt->bindValue(':nombrePieza',$nombrePieza);
        $stmt->bindValue(':largo',$largo);
        $stmt->bindValue(':ancho',$ancho);
        $stmt->bindValue(':rollo',$rollo);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
          header('Location:../index.php');
        }catch(PDOException $ex){
          echo $ex->getMessage();
        }
      }

      if(isset($_POST['idPiezaFabricadaU'])){
        $id =  $_POST['idPiezaFabricadaU'];
        $stmt = $conn->prepare('SELECT * FROM piezas_fabricadas WHERE PKPiezaFabricada= :id');
        $stmt->execute(array(':id'=>$id));
        $row = $stmt->fetch();
        $nombrePieza = $row['NombrePiezas'];
        $largo = $row['Largo'];
        $ancho = $row['Ancho'];
        $rollo = $row['FKRollo'];
      }
  }else {
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

  <title>Timlid | Editar rollo</title>

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
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../../../css/sb-admin-2.css" rel="stylesheet">

  <script>
    function calcularRolloOptimo(){
      var largo = $("#txtLargo").val();
      var ancho = $("#txtAncho").val();
      $("#btnCalcular").hide();
      $("#btnAgregar").show();
      $("#txtNombrePieza").prop('disabled', false);
      $("#cmbRollos").prop('disabled', false);
      $("#divCalculo").load('calcular_Rollo.php?largo='+largo+'&ancho='+ancho);
      //$("#divCalculo").load('calcular_Rollo.php');
    }
  </script>

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
      <?php
      $ruta = "../../";
      $ruteEdit = $ruta."central_notificaciones/";
      require_once('../../menu3.php');
      ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>



          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">
            <?php
              $rutes = "../";
              //require_once('../../alerta_Tareas_Nuevas.php');
            ?>
            <div id="alertaTareas"></div>
            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION["Usuario"] ?></span>
                <i class="fas fa-user-circle fa-3x"></i>
              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="../../perfil">
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  Perfil
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Salir
                </a>
              </div>
            </li>

          </ul>

        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">
          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Editar pieza fabricada</h1>
          </div>

          <div class="row">
            <div class="col-lg-8">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header">
                  Agregar piezas fabricadas
                </div>
                <!-- Card Body -->
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <form action="" method="post">
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-6">
                              <label for="usr">Nombre de la pieza:</label>
                              <input type="text" class="form-control alpha-only" maxlength="30" id="txtNombrePieza" name="txtNombrePieza" value="<?=$nombrePieza;?>">
                            </div>
                            <div class="col-lg-6">
                              <label for="usr">Rollo (gramos):</label>
                              <select class="form-control" name="cmbRollos" required>
                                <option value="">Seleccione un rollo...</option>
                                <?php
                                  $stmt = $conn->prepare('SELECT * FROM rollos');
                                  $stmt->execute();
                                  while($row = $stmt->fetch()){?>
                                      <option value="<?=$row['PKRollo'];?>"<?php if($row['PKRollo'] == $rollo) echo 'selected';?> ><?php echo "Gramaje: ".$row['Gramos']." grs. / Ancho: ".$row['Ancho']." mts." ?></option>
                                  <?php
                                  }
                                  ?>
                              </select>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-lg-6">
                              <label for="usr">Largo:</label>
                              <input type="text" class="form-control numericDecimal-only" maxlength="30" id="txtLargo" name="txtLargo" value="<?=$largo;?>">
                            </div>
                            <div class="col-lg-6">
                              <label for="usr">Ancho:</label>
                              <input type="text" class="form-control numericDecimal-only" maxlength="30" id="txtAncho" name="txtAncho" value="<?=$ancho;?>">
                            </div>
                          </div>
                        </div>
                        <input type="hidden" name="txtId" value="<?=$id;?>">
                        <button type="submit" class="btn btn-primary float-right" name="btnEditar">Editar</button>
                      </form>
                      <br><br>
                      <button type="submit" class="btn btn-primary float-right" id="btnCalcular" onclick="calcularRolloOptimo();">Calculos de rollos</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header">
                  Calculo de piezas por rollo
                </div>
                <!-- Card Body -->
                <div class="card-body">
                  <div id="divCalculo">
                    &nbsp;
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
        require_once('../../footer.php');
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

  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">¿En serio quieres salir?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">x</span>
          </button>
        </div>
        <div class="modal-body">Selecciona "Salir" Para cerrar tu sesión</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
          <a class="btn btn-primary" href="../../logout.php">Salir</a>
        </div>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function(){
      $("#alertaTareas").load('../../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
        setInterval(refrescar, 5000);
      });
    function refrescar(){
      $("#alertaTareas").load('../../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
    }
  </script>

</body>

</html>
