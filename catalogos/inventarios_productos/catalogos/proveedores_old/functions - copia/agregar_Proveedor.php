<?php
session_start();

  if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 4 || $_SESSION["FKRol"] == 1)){
    require_once('../../../include/db-conn.php');
      if(isset ($_POST['btnAgregar'])){
        $razon = $_POST['txtRazon'];
        $nombre = $_POST['txtNombre'];
        $rfc = $_POST['txtRFC'];
        $calle = $_POST['txtCalle'];
        $numEx = $_POST['txtNumeroEx'];
        $numInt = $_POST['txtNumeroInt'];
        $colonia = $_POST['txtColonia'];
        $municipio = $_POST['txtMunicipio'];
        $estados = $_POST['cmbEstados'];
        $cp = $_POST['txtCP'];
        $diasCredito = $_POST['txtDiasCredito'];
        $limiteCredito = $_POST['txtLimiteCredito'];
        $contacto = $_POST['txtContacto'];
        $apellido = $_POST['txtApellido'];
        $telefono = $_POST['txtTelefono'];
        $celular = $_POST['txtCelular'];
        $email = $_POST['txtEmail'];

        try{
          $stmt = $conn->prepare('INSERT INTO proveedores (Razon_Social,Nombre_comercial,RFC,Calle,Numero_exterior,Numero_Interior,Colonia,Municipio,Estado,CP,Dias_Credito,Limite_Credito)VALUES(:razon,:nombre,:rfc,:calle,:numEx,:numInt,:colonia,:municipio,:estado,:cp,:dias_credito,:limite_credito)');
          $stmt->bindValue(':razon',$razon);
          $stmt->bindValue(':nombre',$nombre);
          $stmt->bindValue(':rfc',$rfc);
          $stmt->bindValue(':calle',$calle);
          $stmt->bindValue(':numEx',$numEx);
          $stmt->bindValue(':numInt',$numInt);
          $stmt->bindValue(':colonia',$colonia);
          $stmt->bindValue(':municipio',$municipio);
          $stmt->bindValue(':estado',$estados);
          $stmt->bindValue(':cp',$cp);
          $stmt->bindValue(':dias_credito',$diasCredito);
          $stmt->bindValue(':limite_credito',$limiteCredito);
          $stmt->execute();
          $id = $conn->lastInsertId();
          $stmt = $conn->prepare('INSERT INTO datos_contacto_proveedores (Nombre,Apellido_Paterno,Puesto,Telefono,Celular,Email,FKProveedor) VALUES (:nombre,:apellido,:puesto,:telefono,:celular,:email,:id)');
          $stmt->bindValue(':nombre',$contacto);
          $stmt->bindValue(':apellido',$apellido);
          $stmt->bindValue(':puesto','Vendedor');
          $stmt->bindValue(':telefono',$telefono);
          $stmt->bindValue(':celular',$celular);
          $stmt->bindValue(':email',$email);
          $stmt->bindValue(':id', $id, PDO::PARAM_INT);
          $stmt->execute();
          header('Location:../index.php');
        }catch(PDOException $ex){
          echo $ex->getMessage();
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

  <title>Timlid | Agregar Cliente</title>

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
  <link href="../../../css/sb-admin-2.css" rel="stylesheet">


</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
      <?php
      $ruta = "../../";
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
            <h1 class="h3 mb-0 text-gray-800">Agregar proveedor</h1>
          </div>


          <div class="row">

            <div class="col-lg-12">

              <!-- Basic Card Example -->
              <div class="card shadow mb-4">
                <div class="card-header">
                  Tarjeta de proveedor
                </div>
                <div class="card-body">
                    <div class="row">
                      <div class="col-lg-12">
                        <form action="" method="post">
                          <div class="form-group">
                            <div class="row">
                              <div class="col-lg-4">
                                <label for="usr">Razón social:*</label>
                                <input type="text" class="form-control alphaNumeric-only" maxlength="30" name="txtRazon" required>
                              </div>
                              <div class="col-lg-4">
                                <label for="usr">Nombre comercial:*</label>
                                <div class="input-group mb-3">
                                  <input type="text" class="form-control alphaNumeric-only" maxlength="30" name="txtNombre" required>
                                </div>
                              </div>
                              <div class="col-lg-4">
                                <label for="usr">RFC:*</label>
                                <input type="text" class="form-control alphaNumeric-only" maxlength="30" name="txtRFC" required>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-lg-3">
                                <label for="usr">Calle:*</label>
                                <input type="text" class="form-control alphaNumeric-only" maxlength="30" name="txtCalle" required>
                              </div>
                              <div class="col-lg-3">
                                <label for="usr">Numero exterior:*</label>
                                <div class="input-group mb-3">
                                  <input type="text" class="form-control numeric-only" maxlength="30" name="txtNumeroEx" required>
                                </div>
                              </div>
                              <div class="col-lg-3">
                                <label for="usr">Numero interior:</label>
                                <input type="text" class="form-control alphaNumeric-only" maxlength="30" name="txtNumeroInt">
                              </div>
                              <div class="col-lg-3">
                                <label for="usr">Colonia:*</label>
                                <input type="text" class="form-control alpha-only" maxlength="30" name="txtColonia" required>
                              </div>
                            </div>

                            <div class="row">

                              <div class="col-lg-3">
                                <label for="usr">Municipio:*</label>
                                <div class="input-group mb-3">
                                  <input type="text" class="form-control alpha-only" maxlength="30" name="txtMunicipio" required>
                                </div>
                              </div>
                              <div class="col-lg-3">
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
                              <div class="col-lg-3">
                                <label for="usr">Código Postal:*</label>
                                <input type="text" class="form-control numeric-only" maxlength="30" name="txtCP" required>
                              </div>
                              <div class="col-lg-3">
                                <label for="usr">Credito:*</label><br>
                                <div class="form-check-inline">
                                  <label class="form-check-label"><input type="radio" class="form-check-input" value="Si" name="txtCredito" id="txtCredito" required> Si </label>
                                </div>
                                <div class="form-check-inline">
                                  <label class="form-check-label"><input type="radio" class="form-check-input" value="No" name="txtCredito" id="txtCredito" required checked> No </label>
                                </div>
                              </div>
                            </div>
                            <div id="datosCredito">
                              <div class="row">
                                <div class="col-lg-6">
                                  <label for="txtDiasCredito">Dias de credito</label>
                                  <input class="form-control numeric-only" type="text" name="txtDiasCredito">
                                </div>
                                <div class="col-lg-6">
                                  <label for="txtLimiteCredito">Limite de credito</label>
                                  <input class="form-control numericDecimal-only" type="text" name="txtLimiteCredito">
                                </div>
                              </div>
                            </div>
                            <br>
                            <hr>
                            <h4>Datos del vendedor oficial</h4>
                            <div class="row">
                              <div class="col-lg-12">
                                <div class="row">
                                  <div class="col-lg-6">
                                    <label for="usr">Nombre(s):*</label>
                                    <input class="form-control" type="text" name="txtContacto" value="" required>
                                  </div>
                                  <div class="col-lg-6">
                                    <label for="usr">Apellido:*</label>
                                    <input class="form-control" type="text" name="txtApellido" value="" required>
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="col-lg-4">
                                    <label for="usr">Telefono:*</label>
                                    <input class="form-control numeric-only" type="text" maxlength="10" name="txtTelefono" value="" required>
                                  </div>
                                  <div class="col-lg-4">
                                    <label for="usr">Celular:</label>
                                    <input class="form-control numeric-only" type="text" maxlength="10" name="txtCelular" value="">
                                  </div>
                                  <div class="col-lg-4">
                                    <label for="usr">Email:*</label>
                                    <input class="form-control" type="text" name="txtEmail" value="" required>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

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

  <script>
    $(document).ready(function(){
      $("#alertaTareas").load('../../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUser'];?>+'&ruta='+'<?=$ruta;?>');
        setInterval(refrescar, 5000);
      });
    function refrescar(){
      $("#alertaTareas").load('../../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUser'];?>+'&ruta='+'<?=$ruta;?>');
    }

    $(document).ready(function(){
      $('#datosCredito').hide();
      $("input[type=radio]").click(function(evento){
        var valor = $(event.target).val();

        if(valor == 'Si'){
          $('#datosCredito').show();
        }else if(valor == 'No'){
          $('#nada').show();
          $('#datosCredito').hide();
        }
      });
    });

  </script>
  <script> var ruta = "../../";</script>
  <script src="../../../js/sb-admin-2.min.js"></script>

</body>

</html>
