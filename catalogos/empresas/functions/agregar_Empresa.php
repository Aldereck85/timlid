<?php

  session_start();

  if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 1 || $_SESSION["FKRol"] == 2 || $_SESSION["FKRol"] == 4)){
    require_once('../../../include/db-conn.php');

    if(isset($_POST['btnAgregar'])){
      $razonSocial = $_POST['txtRazonSocial'];
      $rfc = $_POST['txtRFC'];
      $calle = $_POST['txtCalle'];
      $numeroExterno = $_POST['txtNumeroExterno'];
      $interior = $_POST['txtInterior'];
      $colonia = $_POST['txtColonia'];
      $cp = $_POST['txtCodigoPostal'];
      $municipio = $_POST['txtMunicipio'];
      $estados = $_POST['cmbEstados'];
      $registroPatronal = $_POST['txtRegistroPatronal'];

      try{
        $stmt = $conn->prepare('INSERT INTO empresas (Razon_Social,RFC,Calle,Colonia,Numero_Externo,Interior,Codigo_Postal,Municipio,Estado,Registro_Patronal) VALUES (:razonSocial,:rfc,:calle,:numeroExterno,:interior,:colonia,:cp,:municipio,:estados,:patronal)');
        $stmt->bindValue(':razonSocial',$razonSocial);
        $stmt->bindValue(':rfc',$rfc);
        $stmt->bindValue(':calle',$calle);
        $stmt->bindValue(':numeroExterno',$numeroExterno);
        $stmt->bindValue(':interior',$interior);
        $stmt->bindValue(':colonia',$colonia);
        $stmt->bindValue(':cp',$cp);
        $stmt->bindValue(':municipio',$municipio);
        $stmt->bindValue(':estados',$estados);
        $stmt->bindvalue(':patronal',$registroPatronal);
        $stmt->execute();

        header("location:../index.php");

      }catch(PDOException $ex){
        echo $ex->getMessage();
      }
    }
  }else {
    header("location:../../index.php");
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

    <title>Timlid | Agregar Cotización</title>

    <!-- Bootstrap core JavaScript-->
    <script src="../../../vendor/jquery/jquery.min.js"></script>
    <script src="../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../../js/validaciones.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../../../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../../../js/sb-admin-2.min.js"></script>

    <script src="../../../js/bootstrap-clockpicker.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha256-c4gVE6fn+JRKMRvqjoDp+tlG4laudNYrXI1GncbfAYY=" crossorigin="anonymous"></script>
    <script src="../../../js/jquery.number.min.js"></script>
    <script src="../../../js/numeral.min.js"></script>

    <!-- Custom fonts for this template-->
    <link href="../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../../../css/bootstrap-clockpicker.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../../../css/sb-admin-2.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" integrity="sha256-EH/CzgoJbNED+gZgymswsIOrM9XhIbdSJ6Hwro09WE4=" crossorigin="anonymous" />
    <link href="../../../css/chosen.css" rel="stylesheet" type="text/css">

  </head>
  <body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

      <!-- Sidebar -->
        <?php
          $ruta = "../../";
          require_once('../../menu3.php');
        ?>
        </ul>
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
                  <a class="dropdown-item" href="#">
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
              <h1 class="h3 mb-0 text-gray-800">Cotización</h1>
            </div>


            <div class="row">

              <div class="col-lg-12">

                <!-- Basic Card Example -->
                <div class="card shadow mb-4">
                  <div class="card-header">
                    Nueva cotización
                  </div>
                  <div class="card-body">
                      <div class="row">
                        <div class="col-lg-12">
                          <form action="" method="post">
                            <div class="form-group">
                              <div class="row">
                                <div class="col-lg-4">
                                  <label for="usr">Razon social:*</label>
                                  <input class="form-control alphaNumeric-only" type="text" name="txtRazonSocial">
                                </div>
                                <div class="col-lg-4">
                                  <label for="usr">RFC:*</label>
                                  <input type="text" class="form-control alphaNumeric-only" name="txtRFC" maxlength="14" required>
                                </div>
                                <div class="col-lg-4">
                                  <label for="usr">Registro patronal:*</label>
                                  <input type="text" class="form-control alphaNumeric-only" name="txtRegistroPatronal" maxlength="14" required>
                                </div>
                              </div>
                            </div>
                            <div class="form-group">
                              <div class="row">
                                <div class="col-lg-4">
                                  <label for="usr">Calle:*</label>
                                  <input type="text" class="form-control alphaNumeric-only" name="txtCalle" maxlength="50" required>
                                </div>
                                <div class="col-lg-1">
                                  <label for="usr">Numero ext.:*</label>
                                  <input type="text" class="form-control numeric-only" name="txtNumeroExterno" maxlength="5" required>
                                </div>
                                <div class="col-lg-1">
                                  <label for="usr">Interior:</label>
                                  <input type="text" class="form-control alphaNumeric-only" name="txtInterior" maxlength="15">
                                </div>
                                <div class="col-lg-3">
                                  <label for="usr">Colonia:*</label>
                                  <input type="text" class="form-control alphaNumeric-only" name="txtColonia" maxlength="25" required>
                                </div>
                                <div class="col-lg-3">
                                  <label for="usr">Codigo postal:*</label>
                                  <input type="text" class="form-control alphaNumeric-only" name="txtCodigoPostal" maxlength="5" required>
                                </div>
                              </div>
                            </div>
                            <div class="form-group">
                              <div class="row">

                                <div class="col-lg-6">
                                  <label for="usr">Municipio:*</label>
                                  <input type="text" class="form-control alphaNumeric-only" name="txtMunicipio" maxlength="50" required>
                                </div>
                                <div class="col-lg-6">
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
                            </div>
                            <br>
                            <label for="">* Campos requeridos</label>
                            <button type="submit" class="btn btn-success float-right" name="btnAgregar" id="btnAgregar">Agregar</button>
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
        <footer class="sticky-footer bg-white">
          <div class="container my-auto">
            <div class="copyright text-center my-auto">
              <span>Copyright &copy;  Timlid 2020</span>
            </div>
          </div>
        </footer>
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
