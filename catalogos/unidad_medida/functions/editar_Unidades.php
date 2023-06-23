<?php
session_start();
  if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 4 || $_SESSION["FKRol"] == 1)){
    require_once('../../../include/db-conn.php');
      if(isset ($_POST['btnEditar'])){
        $id = (int) $_POST['txtId'];
        $unidad = $_POST['txtUnidadMedida'];
        $pieza = $_POST['txtPiezas'];
        $clave = $_POST['cmbClaveSAT'];

        try{
        $stmt = $conn->prepare('UPDATE unidad_medida set FKClaveSAT= :clave, Unidad_de_Medida= :unidad, Piezas_por_Caja= :pieza WHERE PKUnidadMedida = :id');
        $stmt->bindValue(':clave',$clave);
        $stmt->bindValue(':unidad',$unidad);
        $stmt->bindValue(':pieza',$pieza);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
          $stmt->execute();
          header('Location:../index.php');
        }catch(PDOException $ex){
          echo $ex->getMessage();
        }
      }

      if(isset($_POST['idUnidadesU'])){
        $id =  $_POST['idUnidadesU'];
        $stmt = $conn->prepare('SELECT * FROM unidad_medida WHERE PKUnidadMedida= :id');
        $stmt->execute(array(':id'=>$id));
        $row = $stmt->fetch();
        $clave = $row['FKClaveSAT'];
        $unidad = $row['Unidad_de_Medida'];
        $pieza = $row['Piezas_por_Caja'];

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

  <title>Timlid | Editar unidad de medida</title>

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
            <h1 class="h3 mb-0 text-gray-800">Editar unidad de medida</h1>
          </div>


          <div class="row">

            <div class="col-lg-12">

              <!-- Basic Card Example -->
              <div class="card shadow mb-4">
                <div class="card-header">
                  Tarjeta de unidad de medida
                </div>
                <div class="card-body">
                    <div class="row">
                      <div class="col-lg-12">
                        <form action="" method="post">
                          <div class="form-group">
                            <div class="row">
                              <div class="col-lg-4">
                                <label for="usr">Clave SAT:</label>
                                <select class="form-control" name="cmbClaveSAT" id="cmbClaveSAT">
                                  <option value="">Selecciona una clave SAT...</option>
                                  <?php
                                    $stmt = $conn->prepare('SELECT * FROM claves_sat_unidades WHERE Estatus= :estatus');
                                    $stmt->bindValue(':estatus',1);
                                    $stmt->execute();
                                    while($row = $stmt->fetch()){
                                  ?>
                                  <option value="<?=$row['PKClaveSATUnidad']; ?>"<?php if($clave == $row['PKClaveSATUnidad']) echo 'selected';?>><?=$row['Clave']." - ".$row['Descripcion']; ?></option>
                                <?php } ?>
                                </select>
                              </div>
                              <div class="col-lg-4">
                                <label for="usr">Unidad de medida:</label>
                                <input type="text" class="form-control alpha-only" maxlength="10"  name="txtUnidadMedida" value="<?=$unidad; ?>" required>
                              </div>
                              <div class="col-lg-4">
                                <label for="usr">Piezas por unidad:</label>
                                <input type="text" class="form-control numeric-only" maxlength="5"  name="txtPiezas" value="<?=$pieza; ?>" required>
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

  <script>
  $(document).ready(function(){
    $('#cmbClaveSAT').chosen();
  });

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
