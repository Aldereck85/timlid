<?php

  session_start();

  if(isset($_SESSION["Usuario"]) && ($_SESSION["FKRol"] == 1 || $_SESSION["FKRol"] == 2 || $_SESSION["FKRol"] == 4)){
    require_once('../../../include/db-conn.php');
    if(isset($_GET['idCuentaU'])){
      $id = $_GET['id'];
      //$tipoCuenta = "HOLAAAAAAAAAA";
      //$cMoneda = "";
      $stmt = $conn->prepare('SELECT Tipo,Estado FROM cuentas_bancarias_empresa WHERE PKCuenta = :id');
      $stmt->bindValue(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
      $row = $stmt->fetch();
      $cuentaTipo = $row['Tipo'];
      $estatus = $row['Estado'];
      if($cuentaTipo == 1){
        $tipoCuenta = "Cuentas de Cheques(Bancaria)";
      }else if($cuentaTipo == 2){
        $tipoCuenta = "Credito";
      }else{
        $tipoCuenta = "Otro (No bancarias o control interno)";
      }
      if($cuentaTipo == 1){
        $stmt = $conn->prepare('SELECT * FROM cuentas_bancarias_empresa AS cbe LEFT JOIN cuentas_cheques AS cch ON cbe.PKCuenta = cch.FKCuenta LEFT JOIN movimientos_cuentas_bancarias_empresa AS mce ON cbe.PKCuenta = mce.FKCuenta WHERE PKCuenta = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row1 = $stmt->fetch();
        $moneda = $row1['FKMoneda'];

        $stmt = $conn->prepare('SELECT * FROM monedas WHERE PKMoneda = :id');
        $stmt->bindValue(':id',$moneda);
        $stmt->execute();
        $cMoneda = $stmt->fetch()['Clave'];

        $tipo = $row1['Tipo'];
        $nombre = $row1['Nombre'];
        $banco = $row1['FKBanco'];
        $noCuenta = $row1['Numero_Cuenta'];
        $clabe = $row1['CLABE'];
        $saldo = $row1['Saldo'];

        $stmt = $conn->prepare('SELECT SUM(Saldo) AS tSaldo FROM movimientos_cuentas_bancarias_empresa WHERE FKCuenta = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $saldo = $stmt->fetch()['tSaldo'];
      }else if($cuentaTipo == 2){
        $stmt = $conn->prepare('SELECT * FROM cuentas_bancarias_empresa AS cbe LEFT JOIN cuentas_credito AS cch ON cbe.PKCuenta = cch.FKCuenta LEFT JOIN movimientos_cuentas_bancarias_empresa AS mce ON cbe.PKCuenta = mce.FKCuenta WHERE PKCuenta = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row1 = $stmt->fetch();

        $tipo = $row1['Tipo'];
        $nombre = $row1['Nombre'];
        $banco = $row1['FKBanco'];
        $noCuenta = $row1['Numero_Credito'];
        $referencia = $row1['Referencia'];
        $limiteCredito = $row1['Limite_Credito'];
        $moneda = $row1['FKMoneda'];

        $stmt = $conn->prepare('SELECT * FROM monedas WHERE PKMoneda = :id');
        $stmt->bindValue(':id',$moneda);
        $stmt->execute();
        $cMoneda = $stmt->fetch()['Clave'];

        $stmt = $conn->prepare('SELECT SUM(Saldo) AS tSaldo FROM movimientos_cuentas_bancarias_empresa WHERE FKCuenta = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $saldo = $stmt->fetch()['tSaldo'];
      }else{
        $stmt = $conn->prepare('SELECT * FROM cuentas_bancarias_empresa AS cbe LEFT JOIN cuentas_otras AS cch ON cbe.PKCuenta = cch.FKCuenta LEFT JOIN movimientos_cuentas_bancarias_empresa AS mce ON cbe.PKCuenta = mce.FKCuenta WHERE PKCuenta = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row1 = $stmt->fetch();

        $tipo = $row1['Tipo'];
        $nombre = $row1['Nombre'];
        $noCuenta = $row1['Cuenta'];
        $descripcion = $row1['Descripcion'];
        $moneda = $row1['FKMoneda'];

        $stmt = $conn->prepare('SELECT * FROM monedas WHERE PKMoneda = :id');
        $stmt->bindValue(':id',$moneda);
        $stmt->execute();
        $cMoneda = $stmt->fetch()['Clave'];

        $stmt = $conn->prepare('SELECT SUM(Saldo) AS tSaldo FROM movimientos_cuentas_bancarias_empresa WHERE FKCuenta = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $saldo = $stmt->fetch()['tSaldo'];
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

    <title>Timlid | Detalles cuenta </title>

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

    <!-- Page level plugins -->
    <script src="../../../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="../../../vendor/datatables/dataTables.buttons.js"></script>
    <script src="../../../vendor/datatables/buttons.bootstrap4.min.js"></script>
    <script src="../../../vendor/datatables/buttons.html5.min.js"></script>
    <script src="../../../vendor/jszip/jszip.min.js"></script>

    <!-- Custom fonts for this template-->
    <link href="../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../../../css/bootstrap-clockpicker.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../../../css/sb-admin-2.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" integrity="sha256-EH/CzgoJbNED+gZgymswsIOrM9XhIbdSJ6Hwro09WE4=" crossorigin="anonymous" />
    <link href="../../../css/chosen.css" rel="stylesheet" type="text/css">

    <link href="../../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../vendor/datatables/buttons.dataTables.css">
    <link href="../../../vendor/datatables/buttons.bootstrap4.min.css" rel="stylesheet">

    <script>
      $(document).ready(function(){
        var id = <?=$id; ?>;
        var idioma_espanol = {
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix":    "",
            "sSearch":         "Buscar:",
            "sUrl":            "",
            "sInfoThousands":  ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst":    "Primero",
                "sLast":     "Último",
                "sNext":     "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        }
        $("#tblMovimientosCuentas").dataTable(
        {
          "ajax":"function_MovimientosCuentasBancarias.php?id="+id,
            "columns":[
              {"data":"Fecha"},
              {"data":"Tipo"},
              {"data":"Descripcion"},
              {"data":"Retiro"},
              {"data":"Deposito"},
              {"data":"Saldo"},
              {"data":"Referencia"}
            ],
            "language": idioma_espanol,
              columnDefs: [
                { orderable: false, targets: 2 }
              ],
              responsive: true,
              "dom":"lBfrtip",
              "buttons": [
                {
                  extend: 'excelHtml5',
                  text: '<img class="readEditPermissions" type="submit" width="50px" src="../../../img/excel-azul.svg" />',
        className: "excelDataTableButton",
                  titleAttr: 'Excel',
                  exportOptions: {
                    columns: [0,1,2,3,4,5,6]
                  }
                }
              ],
        }

        )
      });
    </script>

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
              <h1 class="h3 mb-0 text-gray-800">Detalles de la cuenta</h1>
            </div>


            <div class="row">

              <div class="col-lg-12">

                <!-- Basic Card Example -->
                <div class="card shadow mb-4">
                  <div class="card-header">
                    Detalles de la cuenta
                    <!--
                    <a href="#" data-toggle="modal" data-target="#transferir_CuentaBancaria" class="btn btn-success float-right" style="position: relative;right: 2%;" name="button"><i class="fas fa-exchange-alt"></i> Transferir</a>
                    -->
                    <?php if($estatus == 1){ ?>
                    <a href="cambiar_Estatus.php?id=<?=$id; ?>&tipo=0" class="btn btn-danger float-right" style="position: relative;right: 3%;" name="button"><i class="fas fa-times"></i> Desactivar</a>
                    <?php }else{ ?>
                    <a href="cambiar_Estatus.php?id=<?=$id; ?>&tipo=1" class="btn btn-primary float-right" style="position: relative;right: 4%;" name="button"><i class="fas fa-check"></i> Activar</a>
                    <?php } ?>
                  </div>

                  <div class="card-body">
                    <?php if($cuentaTipo == 1){ ?>
                    <div class="row my-3">
                      <div class="col-lg-3 ">
                        <h5>Cuenta: <?=$nombre." ".$noCuenta; ?></h4>
                      </div>
                      <div class="col-lg-3">
                        <h5>Tipo de Cuenta: <?=$tipoCuenta; ?></h4>
                      </div>
                      <div class="col-lg-3">
                        <h5>Nombre de la Cuenta: <?=$nombre; ?></h4>
                      </div>
                      <div class="col-lg-3">
                        <?php
                          $stmt = $conn->prepare('SELECT * FROM bancos WHERE PKBanco = :id');
                          $stmt->bindValue(':id',$banco);
                          $stmt->execute();
                          $nombreBanco = $stmt->fetch()['Nombre'];
                        ?>
                        <h5>Banco o institucion: <?=$nombreBanco; ?></h4>
                      </div>
                      <br>
                      <br>
                    </div>
                    <div class="row my-3">
                      <div class="col-lg-4">
                        <h5>Numero de cuenta: <?=$noCuenta; ?></h4>
                      </div>
                      <div class="col-lg-4">
                        <h5>CLABE: <?=$clabe; ?></h4>
                      </div>
                      <div class="col-lg-4 bg bg-info text-white" style="border-radius: 10px 10px 10px 10px;">
                        <h4 style="font-weight:bold">Saldo: <?="$".number_format($saldo,2,'.',',')." ".$cMoneda; ?></h4>
                      </div>

                      <hr class="my-3"style="width: 100%">
                      <br>
                    </div>
                  <?php }else if($cuentaTipo == 2){ ?>
                    <div class="row my-3">
                      <div class="col-lg-3 ">
                        <h5>Cuenta: <?=$nombre." ".$noCuenta; ?></h4>
                      </div>
                      <div class="col-lg-3">
                        <h5>Tipo de Cuenta: <?=$tipoCuenta; ?></h4>
                      </div>
                      <div class="col-lg-3">
                        <h5>Nombre de la Cuenta: <?=$nombre; ?></h4>
                      </div>
                      <div class="col-lg-3">
                        <?php
                          $stmt = $conn->prepare('SELECT * FROM bancos WHERE PKBanco = :id');
                          $stmt->bindValue(':id',$banco);
                          $stmt->execute();
                          $nombreBanco = $stmt->fetch()['Nombre'];
                        ?>
                        <h5>Banco o institucion: <?=$nombreBanco; ?></h4>
                      </div>
                      <br>
                      <br>
                    </div>
                    <div class="row my-3">
                      <div class="col-lg-3">
                        <h5>Numero de credito: <?=$noCuenta; ?></h4>
                      </div>
                      <div class="col-lg-3">
                        <h5>Referencia: <?=$referencia; ?></h4>
                      </div>
                      <div class="col-lg-3">
                        <h5>Limite de credito: <?="$".number_format($limiteCredito,2); ?></h4>
                      </div>
                      <div class="col-lg-3 bg bg-info text-white" style="border-radius: 10px 10px 10px 10px;">
                        <h4 style="font-weight:bold">Saldo: <?="$".number_format($saldo,2,'.',',')." ".$cMoneda; ?></h4>
                      </div>

                      <hr class="my-3"style="width: 100%">
                      <br>
                    </div>
                  <?php }else{ ?>
                    <div class="row my-3">
                      <div class="col-lg-3 ">
                        <h5>Cuenta: <?=$nombre." ".$noCuenta; ?></h4>
                      </div>
                      <div class="col-lg-3">
                        <h5>Tipo de Cuenta: <?=$tipoCuenta; ?></h4>
                      </div>
                      <div class="col-lg-3">
                        <h5>Nombre de la Cuenta: <?=$nombre; ?></h4>
                      </div>
                      <div class="col-lg-3">
                        <h5>Descripcion: <?=$descripcion; ?></h4>
                      </div>
                      <br>
                      <br>
                    </div>
                    <div class="row my-3 ">
                      <div class="col-lg-3  bg bg-info text-white" style="border-radius: 10px 10px 10px 10px;">
                        <h4 style="font-weight:bold">Saldo: <?="$".number_format($saldo,2,'.',',')." ".$cMoneda; ?></h4>
                      </div>

                      <hr class="my-3"style="width: 100%">
                      <br>
                    </div>
                  <?php } ?>
                    <div class="table-responsive">
                      <table class="table table-striped" id="tblMovimientosCuentas" width="100%" cellspacing="0">
                        <thead>
                          <tr>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Descripcion</th>
                            <th>Retiro/Cargo</th>
                            <th>Deposito/Abono</th>
                            <th>Saldo</th>
                            <th>Referencia</th>
                          </tr>
                        </thead>
                        <tfoot>
                          <tr>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Descripcion</th>
                            <th>Retiro/Cargo</th>
                            <th>Deposito/Abono</th>
                            <th>Saldo</th>
                            <th>Referencia</th>
                          </tr>
                        </tfoot>
                        <tbody>
                        </tbody>
                      </table>
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

    <!-- Transferir -->
    <div id="transferir_CuentaBancaria" class="modal fade">
      <div class="modal-dialog">
        <div class="modal-content">
          <form action="functions/editar_CuentaBancaria.php" method="POST">
            <input type="hidden" name="txtIdU" id="txtIdU">
            <div class="modal-header">
              <h4 class="modal-title">Editar cuenta bancaria</h4>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                  <label for="cmbCuenta">Cuenta:</label>
                  <select class="form-control" name="cmbCuenta">
                    <option value="">Seleccione una cuenta...</option>
                    <?php
                      $stmt = $conn->prepare('SELECT Nombre FROM cuentas_bancarias_empresa');
                      $stmt->execute();
                      while($row = $stmt->fetch()){
                    ?>
                    <option value="<?=$row['PKCuenta']; ?>"><?=$row['Nombre']; ?></option>
                    <?php } ?>
                  </select>
              </div>
              <div class="form-group">
                <label for="txtSaldo">Saldo:</label>
                <input class="form-control numericDecimal-only" type="text" name="txtSaldo" value="">
              </div>
            </div>
            <div class="modal-footer">
              <input type="button" class="btn btn-secondary" data-dismiss="modal" value="Cancelar">
              <input type="submit" class="btn btn-primary" value="Editar">
            </div>
          </form>
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
