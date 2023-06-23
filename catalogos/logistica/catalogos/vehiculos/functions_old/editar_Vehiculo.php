<?php
session_start();

  if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 4 || $_SESSION["FKRol"] == 1)){
    require_once('../../../include/db-conn.php');
      if(isset ($_POST['btnEditar'])){
        $id = (int) $_POST['txtId'];
        $linea = $_POST['txtLinea'];
        $marca = $_POST['txtMarca'];
        $serie = $_POST['txtSerie'];
        $placas = $_POST['txtPlacas'];
        $modelo = $_POST['txtModelo'];
        $puertas = $_POST['txtPuertas'];
        $cilindros = $_POST['txtCilindros'];
        $odometro = $_POST['txtOdometro'];
        $kilometro = $_POST['txtKilometros'];
        $motor = $_POST['txtMotor'];
        $color = $_POST['txtColor'];
        $combustible = $_POST['txtCombustible'];
        $transmision = $_POST['txtTransmision'];
        $usuario = $_POST['cmbUsuario'];
        try{
        $stmt = $conn->prepare('UPDATE vehiculos set Linea= :linea, Serie= :serie,Marca= :marca,Placas= :placas,Color= :color,Modelo= :modelo,Puertas= :puertas,Cilindros= :cilindros,Odometro= :odometro,Kilometraje_para_Servicio= :kilometro,Motor= :motor,Combustible= :combustible,Transmision= :transmision, FKUsuario = :usuario WHERE PKVehiculo = :id');
          $stmt->bindValue(':linea',$linea);
          $stmt->bindValue(':serie',$serie);
          $stmt->bindValue(':marca',$marca);
          $stmt->bindValue(':placas',$placas);
          $stmt->bindValue(':color',$color);
          $stmt->bindValue(':modelo',$modelo);
          $stmt->bindValue(':puertas',$puertas);
          $stmt->bindValue(':cilindros',$cilindros);
          $stmt->bindValue(':odometro',$odometro);
          $stmt->bindValue(':kilometro',$kilometro);
          $stmt->bindValue(':motor',$motor);
          $stmt->bindValue(':combustible',$combustible);
          $stmt->bindValue(':transmision',$transmision);
          $stmt->bindValue(':usuario',$usuario);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
          $stmt->execute();
          header('Location:../index.php');
        }catch(PDOException $ex){
          echo $ex->getMessage();
        }
      }

      if(isset($_POST['idVehiculoU'])){
        $id =  $_POST['idVehiculoU'];
        $stmt = $conn->prepare('SELECT * FROM vehiculos WHERE PKVehiculo= :id');
        $stmt->execute(array(':id'=>$id));
        $row = $stmt->fetch();
        $linea = $row['Linea'];
        $serie = $row['Serie'];
        $marca = $row['Marca'];
        $placa = $row['Placas'];
        $color = $row['Color'];
        $modelo = $row['Modelo'];
        $puertas = $row['Puertas'];
        $cilindros = $row['Cilindros'];
        $odometro = $row['Odometro'];
        $kilometro = $row['Kilometraje_para_Servicio'];
        $motor = $row['Motor'];
        $combustible = $row['Combustible'];
        $transmision = $row['Transmision'];
        $usuario = $row['FKUsuario'];
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

  <title>Timlid | Editar vehiculo</title>

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
  <link href="../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../css/styles.css" rel="stylesheet">

  <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha256-c4gVE6fn+JRKMRvqjoDp+tlG4laudNYrXI1GncbfAYY=" crossorigin="anonymous"></script>
  <link href="../../../css/chosen.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" integrity="sha256-EH/CzgoJbNED+gZgymswsIOrM9XhIbdSJ6Hwro09WE4=" crossorigin="anonymous" />

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
            <h1 class="h3 mb-0 text-gray-800">Editar vehiculo</h1>
          </div>


          <div class="row">

            <div class="col-lg-12">

              <!-- Basic Card Example -->
              <div class="card shadow mb-4">
                <div class="card-header">
                  Información del vehiculo
                </div>
                <div class="card-body">
                    <div class="row">
                      <div class="col-lg-12">
                        <form action="" method="post">
                          <div class="form-group">
                            <div class="row">
                              <div class="col-lg-6">
                                <label for="usr">Linea:</label>
                                <input type="text" class="form-control alphaNumeric-only" maxlength="30" name="txtLinea" value="<?=$linea;?>">
                              </div>
                              <div class="col-lg-6">
                                <label for="usr">Marca:</label>
                                <input type="text" class="form-control alpha-only" maxlength="30" name="txtMarca" value="<?=$marca;?>">
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-lg-4">
                                <label for="usr">Serie:</label>
                                <input type="text" class="form-control alphaNumeric-only" maxlength="30" name="txtSerie" value="<?=$serie;?>">
                              </div>
                              <div class="col-lg-4">
                                <label for="usr">Placas:</label>
                                <input type="text" class="form-control alphaNumeric-only" maxlength="30" name="txtPlacas" value="<?=$placa;?>">
                              </div>
                              <div class="col-lg-4">
                                <label for="usr">Modelo:</label>
                                <input type="text" class="form-control numeric-only" maxlength="30" name="txtModelo" value="<?=$modelo;?>" >
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-lg-3">
                                <label for="usr">Puertas:</label>
                                <input type="text" class="form-control numeric-only" maxlength="30" name="txtPuertas" value="<?=$puertas;?>">
                              </div>
                                <div class="col-lg-3">
                                <label for="usr">Cilindros:</label>
                                <input type="text" class="form-control numeric-only" maxlength="30" name="txtCilindros" value="<?=$cilindros;?>">
                              </div>
                              <div class="col-lg-3">
                                <label for="usr">Odometro:</label>
                                <input type="text" class="form-control numeric-only" maxlength="30" name="txtOdometro" value="<?=$odometro;?>">
                              </div>
                              <div class="col-lg-3">
                                <label for="usr">Kilometros para servicio:</label>
                                <input type="text" class="form-control numeric-only" maxlength="30" name="txtKilometros" value="<?=$kilometro;?>" >
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-lg-6">
                                <label for="usr">Motor:</label>
                                <input type="text" class="form-control alphaNumeric-only" maxlength="30" name="txtMotor" value="<?=$motor;?>">
                              </div>
                              <div class="col-lg-6">
                                <label for="usr">Color:</label>
                                <input type="text" class="form-control alpha-only" maxlength="30" name="txtColor" value="<?=$color;?>">
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-lg-6">
                                <label for="usr">Combustible:</label>
                                <input type="text" class="form-control alpha-only" maxlength="30" name="txtCombustible" value="<?=$combustible;?>">
                              </div>
                              <div class="col-lg-6">
                                <label for="usr">Transmision:</label>
                                <input type="text" class="form-control alpha-only" maxlength="30" name="txtTransmision" value="<?=$transmision;?>">
                              </div>
                            </div>
                          </div>
                          <div class="row">
                              <div class="col-lg-12">
                                <label for="usr">Responsable carga de combustible:</label>
                                <select class="form-control" name="cmbUsuario" id="cmbUsuario">
                                  <option value="">Seleccione una opcion...</option>
                                  <?php
                                    $stmt = $conn->prepare('SELECT * FROM usuarios as u INNER JOIN empleados as e ON e.PKEmpleado = u.FKEmpleado');
                                    $stmt->execute();
                                    while($row = $stmt->fetch()){
                                  ?>
                                      <option value="<?=$row['PKUsuario'];?>" <?php if($usuario == $row['PKUsuario']) echo 'selected';?>><?=$row['PKUsuario'].".- ".$row['Nombres']." ".$row['PrimerApellido']." ".$row['SegundoApellido'];?></option>
                                  <?php } ?>
                                </select>
                              </div>
                          </div>
                          <br>
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
    $(document).ready(function() {
      $("#cmbUsuario").chosen();
    });
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
