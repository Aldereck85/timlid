<?php
  session_start();
  if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 1 || $_SESSION["FKRol"] == 2 || $_SESSION["FKRol"] == 4)){
    require_once('../../../include/db-conn.php');
    if(isset($_POST['btnAgregarCheques'])){
      $nombreCuenta = $_POST['txtNombreCuenta'];
      $tipoCuenta = $_POST['txtTipoCuenta'];
      $empresa = $_POST['cmbEmpresa'];
      $banco = $_POST['cmbBanco'];
      $noCuenta = $_POST['txtNoCuenta'];
      $clabe = $_POST['txtCLABE'];
      $saldo = $_POST['txtSaldo'];
      $moneda = $_POST['cmbMonedaCheques'];
      $id = $_POST['txtId'];
      try{
        $stmt = $conn->prepare('UPDATE cuentas_bancarias_empresa SET Nombre = :nombre,FKEmpresa = :empresa WHERE PKCuenta = :id');
        $stmt->bindValue(':nombre',$nombreCuenta);
        $stmt->bindValue(':empresa',$empresa);
        $stmt->bindValue(':id',$id);
        $stmt->execute();

        $stmt = $conn->prepare('UPDATE cuentas_cheques SET FKBanco = :banco,Numero_Cuenta = :cuenta,CLABE = :clabe,Saldo_Inicial = :saldo,FKMoneda = :moneda WHERE FKCuenta = :id');
        $stmt->bindValue(':banco',$banco);
        $stmt->bindValue(':cuenta',$noCuenta);
        $stmt->bindValue(':clabe',$clabe);
        $stmt->bindValue(':saldo',$saldo);
        $stmt->bindValue(':moneda',$moneda);
        $stmt->bindValue(':id',$id);
        $stmt->execute();

        header("location:../index.php");

      }catch(PDOException $ex){
        echo $ex->getMessage();
      }
    }

    if(isset($_POST['btnAgregarCredito'])){
      $nombreCuenta = $_POST['txtNombreCuenta'];
      $tipoCuenta = $_POST['txtTipoCuenta'];
      $empresa = $_POST['cmbEmpresa'];
      $banco = $_POST['cmbBancoCredito'];
      $credito = $_POST['txtNoCredito'];
      $referencia = $_POST['txtReferencia'];
      $limiteCredito = $_POST['txtLimiteCredito'];
      $moneda = $_POST['cmbMonedaCredito'];
      $creditoUtilizado = $_POST['txtCreditoUtilizado'];
      $id = $_POST['txtId'];

      try{
        $stmt = $conn->prepare('UPDATE cuentas_bancarias_empresa SET Nombre = :nombre,FKEmpresa = :empresa WHERE PKCuenta = :id');
        $stmt->bindValue(':nombre',$nombreCuenta);
        $stmt->bindValue(':empresa',$empresa);
        $stmt->bindValue(':id',$id);
        $stmt->execute();

        $stmt = $conn->prepare('UPDATE cuentas_credito SET FKBanco = :banco,Numero_Credito = :credito,Referencia = :referencia,Limite_Credito = :limite,FKMoneda = :moneda,Credito_Utilizado = :utilizado WHERE FKCuenta = :id');
        $stmt->bindValue(':banco',$banco);
        $stmt->bindValue(':credito',$credito);
        $stmt->bindValue(':referencia',$referencia);
        $stmt->bindValue(':limite',$limiteCredito);
        $stmt->bindValue(':moneda',$moneda);
        $stmt->bindValue(':utilizado',$creditoUtilizado);
        $stmt->bindValue(':id',$id);
        $stmt->execute();

        header("location:../index.php");

      }catch(PDOException $ex){
        echo $ex->getMessage();
      }
    }

    if(isset($_POST['btnAgregarOtros'])){
      $nombreCuenta = $_POST['txtNombreCuenta'];
      $tipoCuenta = $_POST['cmbTipoCuenta'];
      $empresa = $_POST['cmbEmpresa'];
      $idCuenta = $_POST['txtIdCuenta'];
      $descripcion = $_POST['txtDescripcion'];
      $saldoInicial = $_POST['txtSaldoInicial'];
      $moneda = $_POST['cmbMonedaOtros'];
      $id = $_POST['txtId'];

      try{
        $stmt = $conn->prepare('UPDATE cuentas_bancarias_empresa SET Nombre = :nombre,FKEmpresa = :empresa WHERE PKCuenta = :id');
        $stmt->bindValue(':nombre',$nombreCuenta);
        $stmt->bindValue(':empresa',$empresa);
        $stmt->bindValue(':id',$id);
        $stmt->execute();

        $stmt = $conn->prepare('UPDATE cuentas_otras SET Cuenta =:cuenta,Descripcion =:descripcion,Saldo_Inicial =:saldo,FKMoneda =:moneda WHERE FKCuenta =:id');
        $stmt->bindValue(':cuenta',$idCuenta);
        $stmt->bindValue(':descripcion',$descripcion);
        $stmt->bindValue(':saldo',$saldoInicial);
        $stmt->bindValue(':moneda',$moneda);
        $stmt->bindValue(':id',$id);
        $stmt->execute();

        header("location:../index.php");

      }catch(PDOException $ex){
        echo $ex->getMessage();
      }
    }

    if(isset($_POST['txtIdU'])){
      $id = $_POST['txtIdU'];
      //$id=9;
      $tipoCuenta = "";
      $stmt = $conn->prepare('SELECT Tipo FROM cuentas_bancarias_empresa WHERE PKCuenta = :cuenta');

      $stmt->bindValue(':cuenta',$id);
      $stmt->execute();
      $row1 = $stmt->fetch();
      
      if($row1['Tipo'] == 1){
        $stmt = $conn->prepare('SELECT * FROM cuentas_bancarias_empresa INNER JOIN cuentas_cheques ON PKCuenta = FKCuenta WHERE PKCuenta = :cuenta');
        $stmt->bindValue(':cuenta',$id);
        $stmt->execute();
        $row = $stmt->fetch();

        $tipo = $row['Tipo'];
        $nombreCuenta = $row['Nombre'];
        if($tipo == 1){
          $tipoCuenta = "Cuentas de Cheques(Bancaria)";
        }else if($tipo == 2){
          $tipoCuenta = "Credito";
        }else{
          $tipoCuenta = "Otro (No bancarias o control interno)";
        }

        $empresa = $row['FKEmpresa'];
        $banco = $row['FKBanco'];
        $noCuenta = $row['Numero_Cuenta'];
        $clabe = $row['CLABE'];
        $saldo = $row['Saldo_Inicial'];
        $moneda = $row['FKMoneda'];
      }else if($row1['Tipo'] == 2){
        $stmt = $conn->prepare('SELECT * FROM cuentas_bancarias_empresa INNER JOIN cuentas_credito ON PKCuenta = FKCuenta WHERE PKCuenta = :cuenta');
        $stmt->bindValue(':cuenta',$id);
        $stmt->execute();
        $row = $stmt->fetch();

        $tipo = $row['Tipo'];
        if($tipo == 1){
          $tipoCuenta = "Cuentas de Cheques(Bancaria)";
        }else if($tipo == 2){
          $tipoCuenta = "Credito";
        }else{
          $tipoCuenta = "Otro (No bancarias o control interno)";
        }
        $nombreCuenta = $row['Nombre'];
        $empresa = $row['FKEmpresa'];
        $banco = $row['FKBanco'];
        $credito = $row['Numero_Credito'];
        $referencia = $row['Referencia'];
        $limiteCredito = $row['Limite_Credito'];
        $moneda = $row['FKMoneda'];
        $creditoUtilizado = $row['Credito_Utilizado'];
      }else{
        $stmt = $conn->prepare('SELECT * FROM cuentas_bancarias_empresa INNER JOIN cuentas_otras ON PKCuenta = FKCuenta WHERE PKCuenta = :cuenta');
        $stmt->bindValue(':cuenta',$id);
        $stmt->execute();
        $row = $stmt->fetch();

        $tipo = $row['Tipo'];
        if($tipo == 1){
          $tipoCuenta = "Cuentas de Cheques(Bancaria)";
        }else if($tipo == 2){
          $tipoCuenta = "Credito";
        }else{
          $tipoCuenta = "Otro (No bancarias o control interno)";
        }
        $nombreCuenta = $row['Nombre'];
        $empresa = $row['FKEmpresa'];
        $idCuenta = $row['Cuenta'];
        $descripcion = $row['Descripcion'];
        $saldoInicial = $row['Saldo_Inicial'];
        $moneda = $row['FKMoneda'];
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

    <title>Timlid | Editar cuenta </title>

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
          $ruteEdit = $ruta."central_notificaciones/";
          require_once('../../menu3.php');
        ?>
        </ul>
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
              <h1 class="h3 mb-0 text-gray-800">Editar cuenta</h1>
            </div>


            <div class="row">

              <div class="col-lg-12">

                <!-- Basic Card Example -->
                <div class="card shadow mb-4">
                  <div class="card-header">
                    Editar cuenta
                  </div>
                  <div class="card-body">
                      <div class="row">
                        <div class="col-lg-12">
                          <form action="" method="post">
                            <input type="hidden" name="txtId" value="<?=$id; ?>">
                            <div class="form-group">
                              <div class="row">
                                <div class="col-lg-4">
                                  <label for="usr">Nombre de la cuenta:*</label>
                                  <input class="form-control alphaNumeric-only" type="text" name="txtNombreCuenta" value="<?=$nombreCuenta; ?>" required>
                                </div>

                                <div class="col-lg-4">
                                  <label for="usr">Tipo de cuenta:*</label>

                                  <input class="form-control" type="text" name="txtTipoCuenta" id="txtTipoCuenta" value="<?=$tipoCuenta; ?>" readonly required>
                                </div>
                                <div class="col-lg-4">
                                  <label for="usr">Empresa:*</label>
                                  <select class="form-control" name="cmbEmpresa" id="cmbEmpresa" required>
                                    <option value="">Seleccione una opcion...</option>

                                    <?php
                                      $stmt = $conn->prepare('SELECT PKEmpresa,RazonSocial FROM empresas');
                                      $stmt->execute();
                                      while($row = $stmt->fetch()){
                                    ?>
                                    <option value="<?= $row['PKEmpresa']; ?>"<?php if($row['PKEmpresa'] == $empresa) echo 'selected';?>><?=$row['RazonSocial']; ?></option>
                                  <?php } ?>

                                  </select>
                                </div>
                              </div>
                            </div>
<!-- EDICION DE CHEQUES -->                            
                            <div id="cheques">
                              <div class="form-group">
                                <div class="row">
                                  <div class="col-lg-4">
                                    <label for="usr">Banco o institución:*</label>
                                    <select class="form-control" name="cmbBanco" id="cmbBanco">
                                      <option value="">Seleccione un banco</option>
                              <?php
                                $stmt = $conn->prepare('SELECT * FROM bancos');
                                $stmt->execute();
                                while($row = $stmt->fetch()){
                              ?>
                                        <option value="<?=$row['PKBanco']; ?>"<?php if($row['PKBanco'] == $banco) echo 'selected';?>><?=$row['Banco']; ?></option>
                                      <?php } ?>
                                    </select>
                                  </div>
                                  <div class="col-lg-4">
                                    <label for="usr">Numero de cuenta:*</label>
                                    <div class="input-group mb-3">
                                      <input type="text" maxlength="11" class="form-control alphaNumeric-only" name="txtNoCuenta" id="txtNoCuenta" value="<?=$noCuenta; ?>">
                                    </div>
                                  </div>
                                  <div class="col-lg-4">
                                    <label for="usr">CLABE:</label>
                                    <input type="text" class="form-control alphaNumeric-only" name="txtCLABE" id="txtCLABE" maxlength="15" value="<?=$clabe ?>">
                                  </div>
                                </div>
                              </div>
                              <div class="form-group">
                                <div class="row">
                                  <div class="col-lg-6">
                                    <label for="usr">Saldo:*</label>
                                    <div class="input-group mb-3">
                                      <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">$</span>
                                      </div>
                                      <input type="text" maxlength="11" class="form-control numericDecimal-only" name="txtSaldo" id="txtSaldo" value="<?=$saldo; ?>">
                                    </div>
                                  </div>
                                  <div class="col-lg-6">
                                    <label for="usr">Moneda:*</label>
                                    <select name="cmbMonedaCheques" class="form-control" id="cmbMonedaCheques">
                                      <option value="">Seleccione un tipo de moneda</option>
                                      <?php
                                        $stmt = $conn->prepare('SELECT * FROM monedas WHERE Estatus = 1');
                                        $stmt->execute();
                                        while($row = $stmt->fetch()){
                                      ?>
                                        <option value="<?= $row['PKMoneda']; ?>" <?php if($row['PKMoneda'] == $moneda) echo 'selected'; ?>><?=$row['Descripcion']; ?></option>
                                      <?php } ?>
                                    </select>
                                  </div>
                                </div>
                              </div>
                              <!-- 
                              <button type="submit" class="btn btn-primary float-right" name="btnAgregarCheques" id="btnAgregar">Editar</button>
                              -->
                              <button type="submit" class="btnesp espAgregar float-right" name="btnAgregarCheques" id="btnAgregar"><span
                              class="ajusteProyecto">Editar</span></button>

                            </div>
<!-- Edicion de cuenta CREDITO -->
                            <div id="credito">
                              <div class="form-group">
                                <div class="row">
                                  <div class="col-lg-4">
                                    <label for="usr">Banco o institucion:*</label>
                                    <select class="form-control" name="cmbBancoCredito" id="cmbBancoCredito">
                                      <option value="">Seleccione un banco</option>

                                      <?php
                                        $stmt = $conn->prepare('SELECT * FROM bancos ORDER BY Banco ASC');
                                        $stmt->execute();
                                        while($row = $stmt->fetch()){
                                      ?>
                                        <option value="<?=$row['PKBanco']; ?>" <?php if($row['PKBanco'] == $banco) echo 'selected'; ?>><?=$row['Banco']; ?></option>
                                      <?php } ?>
                                    </select>


                                  </div>
                                  <div class="col-lg-4">
                                    <label for="usr">Numero de credito:*</label>
                                    <div class="input-group mb-3">
                                      <input type="text" maxlength="11" class="form-control alphaNumeric-only" name="txtNoCredito" id="txtNoCredito" value="<?=$credito; ?>">
                                    </div>
                                  </div>
                                  <div class="col-lg-4">
                                    <label for="usr">Referencia:</label>
                                    <input type="text" class="form-control alphaNumeric-only" name="txtReferencia" maxlength="15" value="<?=$referencia; ?>">
                                  </div>
                                </div>
                              </div>
                              <div class="form-group">
                                <div class="row">
                                  <div class="col-lg-4">
                                    <label for="usr">Limite de credito:*</label>
                                    <div class="input-group mb-3">
                                      <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">$</span>
                                      </div>
                                      <input type="text" maxlength="11" class="form-control numericDecimal-only" name="txtLimiteCredito" id="txtLimiteCredito" value="<?=$limiteCredito; ?>">
                                    </div>
                                  </div>
                                  <div class="col-lg-4">
                                    <label for="usr">Moneda:*</label>
                                    <select name="cmbMonedaCredito" class="form-control" id="cmbMonedaCredito">
                                      <option value="">Seleccione un tipo de moneda</option>
                                      <?php
                                        $stmt = $conn->prepare('SELECT * FROM monedas WHERE Estatus = 1');
                                        $stmt->execute();
                                        while($row = $stmt->fetch()){
                                      ?>
                                        <option value="<?= $row['PKMoneda']; ?>" <?php if($row['PKMoneda'] == $moneda) echo 'selected'; ?>><?=$row['Descripcion']; ?></option>
                                      <?php } ?>
                                    </select>
                                  </div>
                                  <div class="col-lg-4">
                                    <label for="usr">Credito utilizado:*</label>
                                    <div class="input-group mb-3">
                                      <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">$</span>
                                      </div>
                                      <input type="text" maxlength="11" class="form-control numericDecimal-only" name="txtCreditoUtilizado" id="txtCreditoUtilizado" value="<?=$creditoUtilizado; ?>">
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <!--
                              <button type="submit" class="btn btn-primary float-right" name="btnAgregarCredito" id="btnAgregar">Editar</button>
                              -->
                              <button type="submit" class="btnesp espAgregar float-right" name="btnAgregarCredito" id="btnAgregar"><span
                              class="ajusteProyecto">Editar</span></button>
                            </div>

<!-- Edicion de cuenta Otros -->
                            <div id="otros">
                              <div class="form-group">
                                <div class="row">
                                  <div class="col-lg-6">
                                    <label for="">Identificador de la cuenta:* </label>
                                    <input class="form-control" type="text" name="txtIdCuenta" id="txtIdCuenta" value="<?=$idCuenta; ?>">
                                  </div>
                                  <div class="col-lg-6">
                                    <label for="">Descripcion</label>
                                    <input class="form-control" type="text" name="txtDescripcion" id="txtDescripcion" value="<?=$descripcion; ?>">
                                  </div>
                                </div>
                              </div>
                              <div class="form-group">
                                <div class="row">
                                  <div class="col-lg-6">
                                    <label for="usr">Saldo inicial:*</label>
                                    <div class="input-group mb-3">
                                      <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">$</span>
                                      </div>
                                      <input type="text" maxlength="11" class="form-control numericDecimal-only" name="txtSaldoInicial" id="txtSaldoInicial" value="<?=$saldoInicial ?>">
                                    </div>
                                  </div>
                                  <div class="col-lg-6">
                                    <label for="usr">Moneda:*</label>
                                    <select name="cmbMonedaOtros" class="form-control" id="cmbMonedaOtros">
                                      <option value="">Seleccione un tipo de moneda</option>
                                      <?php
                                        $stmt = $conn->prepare('SELECT * FROM monedas WHERE Estatus = 1 ORDER BY Descripcion ASC');
                                        $stmt->execute();
                                        while($row = $stmt->fetch()){
                                      ?>
                                        <option value="<?= $row['PKMoneda']; ?>" <?php if($row['PKMoneda'] == $moneda) echo 'selected'; ?>><?=$row['Descripcion']; ?></option>
                                      <?php } ?>
                                    </select>
                                  </div>
                                </div>
                              </div>

                              <input type="hidden" name="cmbTipoCuenta" value="<?php echo $tipo ; ?>">
                              <!--
                              <button type="submit" class="btn btn-primary float-right" name="btnAgregarOtros" id="btnAgregar">Editar</button>
                              -->
                              <button type="submit" class="btnesp espAgregar float-right" name="btnAgregarOtros" id="btnAgregar"><span
                              class="ajusteProyecto">Editar</span></button>
                            </div>

                            <label for="">* Campos requeridos</label>

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
      $('#cheques').hide();
      $('#credito').hide();
      $('#otros').hide();
      var tipo = <?=$tipo; ?>;
      if(tipo == 1){
        $('#cheques').show();
        $('#credito').hide();
        $('#otros').hide();
      }else if(tipo == 2){
        $('#cheques').hide();
        $('#credito').show();
        $('#otros').hide();
      }else {
        $('#cheques').hide();
        $('#credito').hide();
        $('#otros').show();
      }

      $("#alertaTareas").load('../../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
        setInterval(refrescar, 5000);
      });
      function refrescar(){
      $("#alertaTareas").load('../../alerta_Tareas_Nuevas.php?user='+<?=$_SESSION['PKUsuario'];?>+'&ruta='+'<?=$ruta;?>');
    }
    </script>

    <script>
      $(document).on('change', '#cmbTipoCuenta', function(event) {
        var tipo = <?=$tipoCuenta; ?>;
        if($("#cmbTipoCuenta option:selected").val() == 1){
          $('#cheques').show();
          $('#cmbBanco').prop('required', true);
          $('#txtNoCuenta').prop('required', true);
          $('#txtSaldo').prop('required', true);
          $('#cmbMonedaCheques').prop('required', true);
          $('#credito').hide();
          $('#otros').hide();
        }else if($("#cmbTipoCuenta option:selected").val() == 2){
          $('#credito').show();
          $('#cmbBancoCredito').prop('required', true);
          $('#txtNoCredito').prop('required', true);
          $('#txtLimiteCredito').prop('required', true);
          $('#cmbMonedaCredito').prop('required', true);
          $('#txtCreditoUtilizado').prop('required', true);
          $('#cheques').hide();
          $('#otros').hide();
        }else{
          $('#otros').show();
          $('#txtIdCuenta').prop('required', true);
          $('#txtSaldoInicial').prop('required', true);
          $('#cmbMonedaOtros').prop('required', true);
          $('#cheques').hide();
          $('#credito').hide();
        }

      });
    </script>

    <script> var ruta = "../../";</script>
    <script src="../../../js/sb-admin-2.min.js"></script>

  </body>
</html>
