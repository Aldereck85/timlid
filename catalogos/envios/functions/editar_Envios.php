<?php
session_start();

  if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 1 || $_SESSION["FKRol"] == 3 || $_SESSION["FKRol"] == 4 || $_SESSION["FKRol"] == 6)){
    require_once('../../../include/db-conn.php');
      if(isset ($_POST['btnEditar'])){
        $numeroRastreo = $_POST['txtRastreo'];
        $estatus = $_POST['cmbEstatusHidden'];

        if($estatus == "En proceso")
          $paqueteria = $_POST['cmbPaqueteria'];

        $id =  (int) $_POST['txtId'];

        try{
          if($estatus == "En proceso"){
            $stmt = $conn->prepare('UPDATE envios set Numero_rastreo= :numero_rastreo, FKPaqueteria = :fkPaqueteria WHERE PKEnvio = :id');
            $stmt->bindValue(':fkPaqueteria',$paqueteria);
          }
          else{
            $stmt = $conn->prepare('UPDATE envios set Numero_rastreo= :numero_rastreo WHERE PKEnvio = :id');
          }
          $stmt->bindValue(':numero_rastreo',$numeroRastreo);
          $stmt->bindValue(':id', $id, PDO::PARAM_INT);
          $stmt->execute();
          header('Location:../index.php');
        }catch(PDOException $ex){
          echo $ex->getMessage();
        }

      }

      if(isset($_POST['idEnvioU'])){

        $id =  $_POST['idEnvioU'];
        $stmt = $conn->prepare('SELECT *, envios.Estatus as EstatusEnvio FROM envios INNER JOIN paqueterias ON FKPaqueteria = PKPaqueteria INNER JOIN facturacion ON FKFactura = PKFacturacion WHERE PKEnvio = :id');
        $stmt->execute(array(':id'=>$id));
        $row = $stmt->fetch();
        $no_rastreo = $row['Numero_rastreo'];
        $estatus = $row['EstatusEnvio'];
        $factura = $row['Folio'];
        $paqueteria = $row['Nombre_Comercial'];

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

  <title>Timlid | Editar envio</title>

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

          <?php
            $rutatb = "../../";
            require_once('../../topbar.php');
          ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Editar envio</h1>
          </div>


          <div class="row">

            <div class="col-lg-12">

              <?php if($estatus != "En proceso"){ ?>
              <div class="alert alert-warning" role="alert" align="center">
                La paquetería sólo se puede modificar cuando el envío está en proceso.
              </div>
              <?php } ?>

              <!-- Basic Card Example -->
              <div class="card shadow mb-4">
                <div class="card-header">
                  Tarjeta de envios
                </div>
                <div class="card-body">
                    <div class="row">
                      <div class="col-lg-12">
                        <form action="" method="post">
                          <div class="form-group">
                            <div class="row">
                              <div class="col-lg-3">
                                <label for="usr">Numero de rastreo:</label>
                                <input type="text" maxlength="20" class="form-control alphaNumeric-only"  name="txtRastreo" value="<?=$no_rastreo;?>">
                              </div>
                              <div class="col-lg-3">
                                <label for="usr">Estatus:</label>
                                <select class="form-control" name="cmbEstatus" value="<?=$estatus?>" required readonly>
                                  <option value="">Elegir opción</option>
                                  <option value="Cancelado" <?php if ($estatus == "Cancelado" ) echo 'selected'; ?>>Cancelado</option>
                                  <option value="En proceso" <?php if ($estatus == "En proceso" ) echo 'selected'; ?>>En proceso</option>
                                  <option value="Enviado" <?php if ($estatus == "Enviado" ) echo 'selected'; ?>>Enviado</option>
                                  <option value="Entregado" <?php if ($estatus == "Entregado" ) echo 'selected'; ?>>Entregado</option>
                                </select>
                                <input type="hidden" name="cmbEstatusHidden" value="<?=$estatus?>" />
                              </div>
                              <div class="col-lg-3">
                                <label for="usr">Factura:</label>
                                <input type="text" maxlength="20" class="form-control alpha-only"  name="txtFactura" value="<?=$factura;?>" disabled>
                              </div>
                              <div class="col-lg-3">
                                <label for="usr">Paqueteria:</label>
                                <select class="form-control" name="cmbPaqueteria" required <?php if($estatus != "En proceso") echo "disabled"; ?>>
                                    <option value="">Elegir opción</option>
                                        <?php
                                            $stmt = $conn->prepare('SELECT * FROM paqueterias');
                                            $stmt->execute();
                                        ?>
                                        <?php foreach($stmt as $option) : ?>
                                             <option value="<?php echo $option['PKPaqueteria']; ?>" <?php if($option['Nombre_Comercial'] == $paqueteria) echo "selected"; ?> ><?php echo $option['Nombre_Comercial']  ; ?></option>
                                        <?php endforeach; ?>
                                </select>
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
  <script> var ruta = "../../";</script>
  <script src="../../../js/sb-admin-2.min.js"></script>

</body>

</html>
