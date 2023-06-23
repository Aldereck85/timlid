<?php
session_start();

  if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 4 || $_SESSION["FKRol"] == 1)){
    require_once('../../../include/db-conn.php');
    $error = "0";
      if(isset($_GET['id'])){
        $id =  $_GET['id'];
      }
      if(isset ($_POST['btnAgregar'])){
        $poliza = $_POST['txtPoliza'];
        $aseguradora = $_POST['txtAseguradora'];
        $fechaInicio = $_POST['txtFechaInicio'];
        $fechaTermino = $_POST['txtFechaTermino'];
        $inciso = $_POST['txtInciso'];
        $importe = $_POST['txtImporte'];
        $agente = $_POST['txtAgente'];
        $telefonoAgente = $_POST['txtTelefonoAgente'];
        $telefonoSiniestros = $_POST['textTelefonoSiniestros'];

        if($_FILES["files"]["type"] == "application/pdf" || trim($_FILES['files']['name']) == ""){

          $nombreArchivo = "";

          if(trim($_FILES['files']['name']) != ""){
            $unico = time();
            $nombreArchivo = $unico.'_'.$_FILES['files']['name'];
            move_uploaded_file($_FILES['files']['tmp_name'], 'pdf/'.$nombreArchivo);
          }

            try{
              $stmt = $conn->prepare('INSERT INTO poliza_autos (NoPoliza,Aseguradora,Inciso,Fecha_Inicio,Fecha_Termino,Importe,AgenteSeguros,TelefonoAgente,TelefonoSiniestros, Archivo, FKVehiculo) VALUES(:poliza,:aseguradora,:inciso,:fechaInicio,:fechaTermino,:importe,:agente,:telefonoAgente,:telefonoSiniestros, :archivo, :fkVehiculo)');
              $stmt->bindValue(':poliza',$poliza);
              $stmt->bindValue(':aseguradora',$aseguradora);
              $stmt->bindValue(':inciso',$inciso);
              $stmt->bindValue(':fechaInicio',$fechaInicio);
              $stmt->bindValue(':fechaTermino',$fechaTermino);
              $stmt->bindValue(':importe',$importe);
              $stmt->bindValue(':agente',$agente);
              $stmt->bindValue(':telefonoAgente',$telefonoAgente);
              $stmt->bindValue(':telefonoSiniestros',$telefonoSiniestros);
              $stmt->bindValue(':archivo',$nombreArchivo);
              $stmt->bindValue(':fkVehiculo',$id);
              $stmt->execute();
              header('Location:../index.php');
            }catch(PDOException $ex){
              echo $ex->getMessage();
            }
        }
        else{
          $error = "1";
        }
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

  <title>Timlid | Agregar Poliza</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../../js/validaciones.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../../../js/sb-admin-2.min.js"></script>

  <script src="../../../js/bootstrap-clockpicker.min.js"></script>

  <!-- Custom fonts for this template-->
  <link href="../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../../css/bootstrap-clockpicker.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

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

        <!-- Topbar -->
        <?php
          $rutatb = "../../";
          $titulo = '<div class="header-screen">
                                  <div class="header-title-screen">
                                  <h1 class="h3 mb-2">Timdesk  <img src="' . $rutatb . '../img/timdesk/timdesk_icon.svg" alt="" style="position:relative;top:-5px;left:-7px;"></h1>
                                  </div>
                                 </div>';
          require_once $rutatb . 'topbar.php';
        ?>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Agregar poliza</h1>
          </div>


          <div class="row">

            <div class="col-lg-12">

              <!-- Basic Card Example -->
              <div class="card shadow mb-4">
                <div class="card-header">
                  Detalles de la poliza
                </div>
                <div class="card-body">

                  <?php
                    if($error == 1){
                      echo '<div class="row">
                            <div class="col-lg-12 text-center">
                              <div class="alert alert-danger" role="alert">
                                No se pueden subir archivos que no sean pdf.
                              </div>
                            </div>
                          </div>';
                    }
                  ?>
                    <div class="row">
                      <div class="col-lg-12">
                        <form action="" method="post" enctype="multipart/form-data">
                          <div class="form-group">
                            <div class="row">
                              <div class="col-lg-6">
                                <label for="usr">No de poliza:</label>
                                <input type="text" class="form-control numeric-only" maxlength="20" name="txtPoliza" value="<?php if(isset ($_POST['btnAgregar'])) echo $poliza;?>">
                              </div>
                              <div class="col-lg-6">
                                <label for="usr">Aseguradora:</label>
                                <input type="text" class="form-control alpha-only" maxlength="20" name="txtAseguradora" value="<?php if(isset ($_POST['btnAgregar'])) echo $aseguradora;?>">
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-lg-6">
                                <label for="usr">Fecha de inicio:</label>
                                <input type="date" class="form-control" name="txtFechaInicio" value="<?php if(isset ($_POST['btnAgregar'])) echo $fechaInicio;?>">
                              </div>
                              <div class="col-lg-6">
                                <label for="usr">Fecha de termino:</label>
                                <input type="date" class="form-control" name="txtFechaTermino" value="<?php if(isset ($_POST['btnAgregar'])) echo $fechaTermino;?>">
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-lg-6">
                                <label for="usr">Inciso:</label>
                                <input type="text" class="form-control numeric-only" maxlength="2" name="txtInciso" value="<?php if(isset ($_POST['btnAgregar'])) echo $fechaTermino;?>">
                              </div>
                              <div class="col-lg-6">
                                <label for="usr">Importe:</label>
                                <input type="text" class="form-control numericDecimal-only" maxlength="8" name="txtImporte" value="<?php if(isset ($_POST['btnAgregar'])) echo $importe;?>">
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-lg-4">
                                <label for="usr">Agente de seguros:</label>
                                <input type="text" class="form-control alpha-only" maxlength="40" name="txtAgente" value="<?php if(isset ($_POST['btnAgregar'])) echo $agente;?>">
                              </div>
                              <div class="col-lg-4">
                                <label for="usr">Telefono del agente:</label>
                                <input type="text" class="form-control numeric-only" maxlength="12" name="txtTelefonoAgente" value="<?php if(isset ($_POST['btnAgregar'])) echo $telefonoAgente;?>">
                              </div>
                              <div class="col-lg-4">
                                <label for="usr">Telefono de siniestros:</label>
                                <input type="text" class="form-control numeric-only" maxlength="12" name="textTelefonoSiniestros" value="<?php if(isset ($_POST['btnAgregar'])) echo $telefonoSiniestros;?>">
                              </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-lg-12">
                                  <label for="usr">Carpeta:</label>
                                  <input type="file" name="files" id="files" accept="application/pdf" required>
                                </div>
                            </div>
                          </div>
                          <input type="hidden" name="txtId" value="<?=$id;?>">
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
  $(document).ready(function() {
    $("#alertaTareas").load(
      '../../alerta_Tareas_Nuevas.php?user=<?=$_SESSION['PKUsuario'];?>&ruta=<?=$ruta;?>&ruteEdit=<?=$ruteEdit;?>');
    setInterval(refrescar, 5000);
  });

  function refrescar() {
    $("#alertaTareas").load(
      '../../alerta_Tareas_Nuevas.php?user=<?=$_SESSION['PKUsuario'];?>&ruta=<?=$ruta;?>&ruteEdit=<?=$ruteEdit;?>');
  }
  </script>

</body>

</html>
