<?php
session_start();

  if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 4 || $_SESSION["FKRol"] == 1)){
    require_once('../../../../../include/db-conn.php');
      if(isset ($_POST['btnEditar'])){
        $id = $_POST['txtIdCuenta'];
        $proveedor = $_POST['txtIdProveedor'];
        $banco = (int) $_POST['cmbBanco'];
        $cuenta = $_POST['txtCuenta'];
        $clabe = $_POST['txtCLABE'];
        try{
        $stmt = $conn->prepare('UPDATE cuentas_bancarias_proveedores set FKBanco= :banco,No_de_cuenta= :cuenta, CLABE= :clabe WHERE PKCuentaProveedor = :id');
        $stmt->bindValue(':banco',$banco);
        $stmt->bindValue(':id',$id);
        $stmt->bindValue(':cuenta',$cuenta);
        $stmt->bindValue(':clabe',$clabe);
        $stmt->execute();
          header('Location:../index.php?id='.$proveedor);
        }catch(PDOException $ex){
          echo $ex->getMessage();
        }
      }

      if(isset($_POST['txtIdCuentaU'])){
        $id =  $_POST['txtIdCuentaU'];
        $stmt = $conn->prepare('SELECT * FROM cuentas_bancarias_proveedores WHERE PKCuentaProveedor= :id');
        $stmt->execute(array(':id'=>$id));
        $row = $stmt->fetch();

        $banco = $row['FKBanco'];
        $cuenta = $row['No_de_cuenta'];
        $clabe = $row['CLABE'];
        $proveedor = $row['FKProveedor'];
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

  <title>Timlid | Editar cuenta bancaria</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../../../../js/validaciones.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../../../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../../../../../js/sb-admin-2.min.js"></script>
  <script src="../../../../../js/bootstrap-clockpicker.min.js"></script>

  <!-- Custom fonts for this template-->
  <link href="../../../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../../../../css/bootstrap-clockpicker.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../../../../../css/sb-admin-2.css" rel="stylesheet">

  <script>
      $( document ).ready(function() {
        var monto = $("#txtMonto").val();
        var dias = $("#txtDias").val();
        if(monto!="" || dias!=""){
          $('#grupoCredito').prop('checked', true);
          //$("input.grupoCredito").prop("disabled", this.checked);
          $(function() {
              $("#grupoCredito").click(activarCredito);
            });

            function activarCredito() {
              $("input.grupoCredito").prop("disabled", !this.checked);
            }
        }else{
          $('#grupoCredito').prop('checked', false);
          $(function() {
              activarCredito();
              $("#grupoCredito").click(activarCredito);
            });

            function activarCredito() {
              $("input.grupoCredito").prop("disabled", !this.checked);
            }
        }

      });
  </script>


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
            <h1 class="h3 mb-0 text-gray-800">Editar cuenta</h1>
          </div>


          <div class="row">

            <div class="col-lg-12">

              <!-- Basic Card Example -->
              <div class="card shadow mb-4">
                <div class="card-header">
                  Cuenta bancaria
                </div>
                <div class="card-body">
                    <div class="row">
                      <div class="col-lg-12">
                        <form action="" method="post">
                          <input type="hidden" name="txtIdCuenta" value="<?=$id; ?>">
                          <input type="hidden" name="txtIdProveedor" value="<?=$proveedor; ?>">
                          <div class="form-group">
                            <div class="row">
                              <div class="col-lg-4">
                                <label for="usr">Banco:*</label>
                                <select name="cmbBanco" id="cmbBanco" class="form-control" required>
                                    <option value="">Elegir opción</option>
                                        <?php
                                            $stmt = $conn->prepare('SELECT * FROM bancos');
                                            $stmt->execute();
                                        ?>
                                        <?php foreach($stmt as $option) : ?>
                                             <option value="<?php echo $option['PKBanco']; ?>" <?php if ($banco == $option['PKBanco'] ) echo 'selected'; ?>><?php echo $option['Nombre']; ?></option>
                                        <?php endforeach; ?>
                                </select>
                              </div>
                              <div class="col-lg-4">
                                <label for="usr">No de cuenta:*</label>
                                <div class="input-group mb-3">
                                  <input type="text" class="form-control alphaNumeric-only" maxlength="24" name="txtCuenta" value="<?=$cuenta;?>" required>
                                </div>
                              </div>
                              <div class="col-lg-4">
                                <label for="usr">CLABE:*</label>
                                <input type="text" class="form-control numeric-only" maxlength="18" name="txtCLABE" value="<?=$clabe;?>" required>
                              </div>

                            </div>
                          </div>
                          <label for="">* Campos requeridos</label>
                          <button type="submit" class="btn btn-primary float-right"  name="btnEditar">Editar</button>
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
      $("#alertaTareas").load('../../../../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUser'];?>+'&ruta='+'<?=$ruta;?>');
        setInterval(refrescar, 5000);
      });
    function refrescar(){
      $("#alertaTareas").load('../../../../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUser'];?>+'&ruta='+'<?=$ruta;?>');
    }
  </script>
  <script> var ruta = "../../../../";</script>
  <script src="../../../../../js/sb-admin-2.min.js"></script>

</body>

</html>
