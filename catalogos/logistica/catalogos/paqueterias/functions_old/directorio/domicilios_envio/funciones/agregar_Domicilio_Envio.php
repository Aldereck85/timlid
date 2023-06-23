<?php
session_start();

  if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 4 || $_SESSION["FKRol"] == 1)){
    require_once('../../../../../include/db-conn.php');
      if(isset ($_POST['btnAgregar'])){

        if(isset($_GET['id'])){
          $id =  $_GET['id'];
          $calle = $_POST['txtCalle'];
          $numEx = $_POST['txtNumeroEx'];
          $numInt = $_POST['txtNumeroInt'];
          $colonia = $_POST['txtColonia'];
          $municipio = $_POST['txtMunicipio'];
          $estados = $_POST['cmbEstados'];
          $cp = $_POST['txtCP'];
          $locacion = $_POST['txtLocacion'];
          try{
            $stmt = $conn->prepare('INSERT INTO domicilio_de_envio_paqueterias (Calle,Numero_exterior,Numero_Interior,Colonia,Municipio,Estado,CP,Locacion,FKPaqueteria)VALUES(:calle,:numEx,:numInt,:colonia,:municipio,:estado,:cp,:locacion,:fkPaqueteria)');
            $stmt->bindValue(':calle',$calle);
            $stmt->bindValue(':numEx',$numEx);
            $stmt->bindValue(':numInt',$numInt);
            $stmt->bindValue(':colonia',$colonia);
            $stmt->bindValue(':municipio',$municipio);
            $stmt->bindValue(':estado',$estados);
            $stmt->bindValue(':cp',$cp);
            $stmt->bindValue(':locacion',$locacion);
            $stmt->bindValue(':fkPaqueteria',$id);
            $stmt->execute();
            header('Location:../index.php?id='.$id);
          }catch(PDOException $ex){
            echo $ex->getMessage();
          }
        }
      }
  }else {
    header("location:../../../../dashboard.php");
  }


 ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="icon" type="image/png" href="../../../../../img/header/bluTimlid.png">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Timlid | Agregar domicilio de envio</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../../../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../../../../js/validaciones.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../../../../../js/sb-admin-2.min.js"></script>

  <script src="../../../../../js/bootstrap-clockpicker.min.js"></script>

  <!-- Custom fonts for this template-->
  <link href="../../../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../../../../css/bootstrap-clockpicker.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../../../../../css/sb-admin-2.css" rel="stylesheet">

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
      $ruta = "../../../../";
      require_once('../../../../menu3.php');
    ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

          <?php
            $rutatb = "../../../../";
            require_once('../../../../topbar.php');
          ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Agregar domicilio de envio</h1>
          </div>


          <div class="row">

            <div class="col-lg-12">

              <!-- Basic Card Example -->
              <div class="card shadow mb-4">
                <div class="card-header">
                  Agregar domicilio de envio
                </div>
                <div class="card-body">
                    <div class="row">
                      <div class="col-lg-12">
                        <form action="" method="post">
                          <div class="form-group">
                            <div class="row">
                              <div class="col-lg-8">
                                <label for="usr">Calle:*</label>
                                <input type="text" class="form-control alphaNumeric-only" maxlength="50" name="txtCalle" required>
                              </div>
                              <div class="col-lg-2">
                                <label for="usr">Número exterior:*</label>
                                <div class="input-group mb-3">
                                  <input type="text" class="form-control numeric-only" maxlength="5" name="txtNumeroEx" required>
                                </div>
                              </div>
                              <div class="col-lg-2">
                                <label for="usr">Número interior:</label>
                                <input type="text" class="form-control alphaNumeric-only" maxlength="5" name="txtNumeroInt">
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-lg-4">
                                <label for="usr">Colonia:*</label>
                                <input type="text" class="form-control alpha-only" maxlength="30" name="txtColonia" required>
                              </div>
                              <div class="col-lg-4">
                                <label for="usr">Municipio:*</label>
                                <div class="input-group mb-3">
                                  <input type="text" class="form-control alpha-only" maxlength="25" name="txtMunicipio" required>
                                </div>
                              </div>
                              <div class="col-lg-4">
                                <label for="usr">Estado:*</label>
                                <select name="cmbEstados" class="form-control" id="cmbEstados" required>
                                  <option value="">Seleccionar estado</option>
                                  <option value="Aguascalientes">Aguascalientes</option>
                                  <option value="Baja California">Baja California</option>
                                  <option value="Baja California Sur">Baja California Sur</option>
                                  <option value="Campeche">Campeche</option>
                                  <option value="Coahuila de Zaragoza">Coahuila de Zaragoza</option>
                                  <option value="Colima">Colima</option>
                                  <option value="Chiapas">Chiapas</option>
                                  <option value="Chihuahua">Chihuahua</option>
                                  <option value="Distrito Federal">Distrito Federal</option>
                                  <option value="Durango">Durango</option>
                                  <option value="Guanajuato">Guanajuato</option>
                                  <option value="Guerrero">Guerrero</option>
                                  <option value="Hidalgo">Hidalgo</option>
                                  <option value="Jalisco">Jalisco</option>
                                  <option value="México">México</option>
                                  <option value="Michoacán de Ocampo">Michoacán de Ocampo</option>
                                  <option value="Morelos">Morelos</option>
                                  <option value="Nayarit">Nayarit</option>
                                  <option value="Nuevo León">Nuevo León</option>
                                  <option value="Oaxaca">Oaxaca</option>
                                  <option value="Puebla">Puebla</option>
                                  <option value="Querétaro">Querétaro</option>
                                  <option value="Quintana Roo">Quintana Roo</option>
                                  <option value="San Luis Potosí">San Luis Potosí</option>
                                  <option value="Sinaloa">Sinaloa</option>
                                  <option value="Sonora">Sonora</option>
                                  <option value="Tabasco">Tabasco</option>
                                  <option value="Tamaulipas">Tamaulipas</option>
                                  <option value="Tlaxcala">Tlaxcala</option>
                                  <option value="Veracruz de Ignacio de la Llave">Veracruz de Ignacio de la Llave</option>
                                  <option value="Yucatán">Yucatán</option>
                                  <option value="Zacatecas">Zacatecas</option>
                                </select>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-lg-6">
                                <label for="usr">Código Postal:*</label>
                                <input type="text" class="form-control numeric-only" maxlength="5" name="txtCP" required>
                              </div>
                              <div class="col-lg-6">
                                <label for="usr">Locación:*</label>
                                <input type="text" class="form-control alpha-only" maxlength="20" name="txtLocacion" required>
                              </div>
                            </div>
                          </div>
                          <label for="">* Campos requeridos</label>
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
        $rutaf = "../../../../";
        require_once('../../../../footer.php');
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
      $("#alertaTareas").load('../../../../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
        setInterval(refrescar, 5000);
      });
    function refrescar(){
      $("#alertaTareas").load('../../../../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
    }
  </script>
  <script> var ruta = "../../../../";</script>
  <script src="../../../../../js/sb-admin-2.min.js"></script>

</body>

</html>
