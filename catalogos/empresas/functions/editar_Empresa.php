<?php

  session_start();

  if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 1 || $_SESSION["FKRol"] == 2 || $_SESSION["FKRol"] == 4)){
    require_once('../../../include/db-conn.php');

    if(isset($_POST['btnEditar'])){
      $id = $_POST['txtId'];
      $razonSocial = $_POST['txtRazonSocial'];
      $rfc = $_POST['txtRFC'];
      $calle = $_POST['txtCalle'];
      $numeroExterno = $_POST['txtNumeroExterno'];
      $interior = $_POST['txtInterior'];
      $colonia = $_POST['txtColonia'];
      $cp = $_POST['txtCodigoPostal'];
      $municipio = $_POST['txtMunicipio'];
      $estado = $_POST['cmbEstados'];
      $registroPatronal = $_POST['txtRegistroPatronal'];

      try{
        $stmt = $conn->prepare('UPDATE empresas SET Razon_Social = :razonSocial, RFC = :rfc,Calle = :calle,Colonia = :colonia,Numero_Externo = :numeroExterno,Interior = :interior,Codigo_Postal = :cp,Municipio = :municipio,Estado = :estado, Registro_Patronal = :patronal WHERE PKEmpresa = :id');
        $stmt->bindValue(':razonSocial',$razonSocial);
        $stmt->bindValue(':rfc',$rfc);
        $stmt->bindValue(':calle',$calle);
        $stmt->bindValue(':numeroExterno',$numeroExterno);
        $stmt->bindValue(':interior',$interior);
        $stmt->bindValue(':colonia',$colonia);
        $stmt->bindValue(':cp',$cp);
        $stmt->bindValue(':municipio',$municipio);
        $stmt->bindValue(':estado',$estado);
        $stmt->bindValue(':patronal',$registroPatronal);
        $stmt->bindValue(':id',$id);
        $stmt->execute();

        header("location:../index.php");

      }catch(PDOException $ex){
        echo $ex->getMessage();
      }
    }
    if(isset($_POST['txtIdU'])){
      $id = $_POST['txtIdU'];

      $stmt = $conn->prepare('SELECT * FROM empresas WHERE PKEmpresa = :id');
      $stmt->bindValue(':id',$id);
      $stmt->execute();
      $row = $stmt->fetch();

      $razonSocial = $row['Razon_Social'];
      $rfc = $row['RFC'];
      $calle = $row['Calle'];
      $numeroExterno = $row['Numero_Externo'];
      $interior = $row['Interior'];
      $colonia = $row['Colonia'];
      $cp = $row['Codigo_Postal'];
      $municipio = $row['Municipio'];
      $estado = $row['Estado'];
      $registroPatronal = $row['Registro_Patronal'];
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
                            <input type="hidden" name="txtId" value="<?=$id; ?>">
                            <div class="form-group">
                              <div class="row">
                                <div class="col-lg-4">
                                  <label for="usr">Razon social:*</label>
                                  <input class="form-control alphaNumeric-only" type="text" name="txtRazonSocial" value="<?=$razonSocial ?>" required>
                                </div>
                                <div class="col-lg-4">
                                  <label for="usr">RFC:*</label>
                                  <input type="text" class="form-control alphaNumeric-only" name="txtRFC" maxlength="14" value="<?=$rfc ?>" required>
                                </div>
                                <div class="col-lg-4">
                                  <label for="usr">Registro Patronal:*</label>
                                  <input type="text" class="form-control alphaNumeric-only" name="txtRegistroPatronal" maxlength="14" value="<?=$registroPatronal ?>" required>
                                </div>
                              </div>
                            </div>
                            <div class="form-group">
                              <div class="row">
                                <div class="col-lg-4">
                                  <label for="usr">Calle:*</label>
                                  <input type="text" class="form-control alphaNumeric-only" name="txtCalle" maxlength="50" value="<?=$calle ?>" required>
                                </div>
                                <div class="col-lg-1">
                                  <label for="usr">Numero ext.:*</label>
                                  <input type="text" class="form-control numeric-only" name="txtNumeroExterno" maxlength="5" value="<?=$numeroExterno ?>" required>
                                </div>
                                <div class="col-lg-1">
                                  <label for="usr">Interior:</label>
                                  <input type="text" class="form-control alphaNumeric-only" name="txtInterior" maxlength="15" value="<?=$interior ?>">
                                </div>
                                <div class="col-lg-3">
                                  <label for="usr">Colonia:*</label>
                                  <input type="text" class="form-control alphaNumeric-only" name="txtColonia" maxlength="25" value="<?=$colonia ?>" required>
                                </div>
                                <div class="col-lg-3">
                                  <label for="usr">Codigo postal:*</label>
                                  <input type="text" class="form-control alphaNumeric-only" name="txtCodigoPostal" maxlength="5" value="<?=$cp ?>" required>
                                </div>
                              </div>
                            </div>
                            <div class="form-group">
                              <div class="row">

                                <div class="col-lg-6">
                                  <label for="usr">Municipio:*</label>
                                  <input type="text" class="form-control alphaNumeric-only" name="txtMunicipio" maxlength="50" value="<?=$municipio ?>" required>
                                </div>
                                <div class="col-lg-6">
                                  <label for="usr">Estado:*</label>
                                  <select name="cmbEstados" class="form-control" id="cmbEstados" required>
                                    <option value="">Seleccionar estado</option>
                                    <option value="Aguascalientes" <?php if($estado == 'Aguascalientes') echo 'selected';?>>Aguascalientes</option>
                                    <option value="Baja California" <?php if($estado == 'Baja California') echo 'selected';?>>Baja California</option>
                                    <option value="Baja California Sur" <?php if($estado == 'Baja California Sur') echo 'selected';?>>Baja California Sur</option>
                                    <option value="Campeche" <?php if($estado == 'Campeche') echo 'selected';?>>Campeche</option>
                                    <option value="Coahuila de Zaragoza" <?php if($estado == 'Coahuila de Zaragoza') echo 'selected';?>>Coahuila de Zaragoza</option>
                                    <option value="Colima" <?php if($estado == 'Colima') echo 'selected';?>>Colima</option>
                                    <option value="Chiapas" <?php if($estado == 'Chiapas') echo 'selected';?>>Chiapas</option>
                                    <option value="Chihuahua" <?php if($estado == 'Chihuahua') echo 'selected';?>>Chihuahua</option>
                                    <option value="Distrito Federal" <?php if($estado == 'Distrito Federal') echo 'selected';?>>Distrito Federal</option>
                                    <option value="Durango" <?php if($estado == 'Durango') echo 'selected';?>>Durango</option>
                                    <option value="Guanajuato" <?php if($estado == 'Guanajuato') echo 'selected';?>>Guanajuato</option>
                                    <option value="Guerrero" <?php if($estado == 'Guerrero') echo 'selected';?>>Guerrero</option>
                                    <option value="Hidalgo" <?php if($estado == 'Hidalgo') echo 'selected';?>>Hidalgo</option>
                                    <option value="Jalisco" <?php if($estado == 'Jalisco') echo 'selected';?>>Jalisco</option>
                                    <option value="México" <?php if($estado == 'México') echo 'selected';?>>México</option>
                                    <option value="Michoacán de Ocampo" <?php if($estado == 'Michoacán de Ocampo') echo 'selected';?>>Michoacán de Ocampo</option>
                                    <option value="Morelos" <?php if($estado == 'Morelos') echo 'selected';?>>Morelos</option>
                                    <option value="Nayarit" <?php if($estado == 'Nayarit') echo 'selected';?>>Nayarit</option>
                                    <option value="Nuevo León" <?php if($estado == 'Nuevo León') echo 'selected';?>>Nuevo León</option>
                                    <option value="Oaxaca" <?php if($estado == 'Oaxaca') echo 'selected';?>>Oaxaca</option>
                                    <option value="Puebla" <?php if($estado == 'Puebla') echo 'selected';?>>Puebla</option>
                                    <option value="Querétaro" <?php if($estado == 'Querétaro') echo 'selected';?>>Querétaro</option>
                                    <option value="Quintana Roo" <?php if($estado == 'Quintana Roo') echo 'selected';?>>Quintana Roo</option>
                                    <option value="San Luis Potosí" <?php if($estado == 'San Luis Potosí') echo 'selected';?>>San Luis Potosí</option>
                                    <option value="Sinaloa" <?php if($estado == 'Sinaloa') echo 'selected';?>>Sinaloa</option>
                                    <option value="Sonora" <?php if($estado == 'Sonora') echo 'selected';?>>Sonora</option>
                                    <option value="Tabasco" <?php if($estado == 'Tabasco') echo 'selected';?>>Tabasco</option>
                                    <option value="Tamaulipas" <?php if($estado == 'Tamaulipas') echo 'selected';?>>Tamaulipas</option>
                                    <option value="Tlaxcala" <?php if($estado == 'Tlaxcala') echo 'selected';?>>Tlaxcala</option>
                                    <option value="Veracruz de Ignacio de la Llave" <?php if($estado == 'Veracruz de Ignacio de la Llave') echo 'selected';?>>Veracruz de Ignacio de la Llave</option>
                                    <option value="Yucatán" <?php if($estado == 'Yucatán') echo 'selected';?>>Yucatán</option>
                                    <option value="Zacatecas" <?php if($estado == 'Zacatecas') echo 'selected';?>>Zacatecas</option>
                                  </select>
                                </div>
                              </div>
                            </div>
                            <br>
                            <label for="">* Campos requeridos</label>
                            <button type="submit" class="btn btn-primary float-right" name="btnEditar" id="btnEditar">Editar</button>
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
