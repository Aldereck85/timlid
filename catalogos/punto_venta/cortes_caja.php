<?php

  $screen = 69;
  $ruta = "../";
  require_once $ruta . 'validarPermisoPantalla.php';
  if(isset($_SESSION["Usuario"]) && $permiso === 1){
    require_once '../../include/db-conn.php';
  } else {
    header("location:../dashboard.php");
  }
  $jwt_ruta = "../../";
  require_once '../jwt.php';

  date_default_timezone_set('America/Mexico_City');

  $token = $_SESSION['token_ld10d'];

  if(isset($_POST['caja_id'])){
    $caja_id = $_POST['caja_id'];
    $caja_name = $_POST['caja_name'];
    $sucursal = $_POST['sucursal'];
  } 
  
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" type="image/png" href="../../img/header/bluTimlid.png">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Timlid | Punto de venta</title>

  <!-- ESTILOS -->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../vendor/datatables/responsive.dataTables.css" rel="stylesheet">
  <link href="../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../vendor/KeyTable/css/keyTable.dataTables.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">
  <link href="../../css/stylesNewTable.css" rel="stylesheet">
  <link href="../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../css/sweetalert2.css" rel="stylesheet">
  <link href="../../css/lobibox.min.css" rel="stylesheet">
  <link href="css/corte_caja.css" rel="stylesheet">
 
  <!-- JS -->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../vendor/moment/moment.min.js"></script>
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/KeyTable/js/dataTables.keyTable.min.js"></script>
  <script src="../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../vendor/datatables/datetime-moment.js"></script>
  <script src="../../vendor/datatables/datetime.js"></script>
  <script src="../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../vendor/jszip/jszip.min.js"></script>
  <script src="../../js/jquery.redirect.min.js"></script>
  <script src="../../js/notificaciones_timlid.js" charset="utf-8"></script>
  <script src="../../js/mdtimepicker.min.js"></script>
  <script src="../../js/permisos_usuario.js"></script>
  <script src="../../js/jquery.redirect.js"></script>
  <script src="../../js/numeral.min.js"></script>
  <script src="../../js/slimselect.min.js"></script>
  <script src="../../js/lobibox.min.js"></script>
  <script src="../../js/sweet/sweetalert2.js"></script>
  
</head>

<body>
  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
        $icono = '../../img/punto_venta/ICONO PUNTO DE VENTA-01-01.svg';
        $titulo = 'Punto de venta (Informes)';
        $backIcon = true;
        $ruteEdit = $ruta . "central_notificaciones/";
        require_once $ruta . 'menu3.php';
    ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <!-- Main Content -->
      <div id="content">

        <?php
          $rutatb = "../";
          require_once '../topbar.php';
        ?>

        <!-- Begin Page Content -->

        <div class="container-fluid">
          <input type="hidden" name="txtCashRegisterId" id="txtCashRegisterId" value="<?=$caja_id;?>">

          <nav id="btn-controls-cash-register-cut">
            <div class="nav nav-tabs nav-fill" role="tablist"id="navbarDataCashRegisterCuts">
              <a class="nav-item nav-link active" id="general-data-cash-register-cut-tab" data-toggle="tab" href="#nav-general-data-cash-register-cut" role="tab" aria-controls="nav-general-data-cash-register-cut" aria-selected="true">General</a>
              <a class="nav-item nav-link" id="additional-data-cash-register-cut-tab" data-toggle="tab" href="#nav-additional-data-cash-register-cut" role="tab" aria-controls="nav-additional-data-cash-register-cut" aria-selected="true">Por corte</a>
            </div>
          </nav>
           <!-- DataTales Example -->
           <div class="card shadow mb-4">
            <div class="card-body">

              <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-general-data-cash-register-cut" role="tabpanel" aria-labelledby="nav-general-data-cash-register-cut"> 
                  
                  <div class="row">
                    <div class="col-lg-3">
                      <div class="row">
                        <div class="col-12">
                          <h6>Caja: <?=$caja_name;?></h6>
                        </div>
                      </div> 
                      <div class="row">
                        <div class="col-12">
                          <h6>Sucursal: <?=$sucursal;?></h6>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg text-center">
                      <div class="row">
                        <div class="col-12">
                          <b>Efectivo retirado:</b>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-12">
                          <h6> 
                            <span id="efectivo_retirado_general"></span>
                          </h6>
                        </div>
                      </div>
                    </div>

                    <div class="col-lg text-center">
                      <div class="row">
                        <div class="col-12">
                          <b>Crédito retirado:</b> <br>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-12">
                          <h6> 
                            <span id="credito_retirado_general"></span>
                          </h6>
                        </div>
                      </div>
                    </div>

                    <div class="col-lg text-center"">
                      <div class="row">
                        <div class="col-12">
                          <b>Transferencia retirada:</b> 
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-12">
                          <h6> 
                            <span id="transferencia_retirado_general"></span>
                          </h6>
                        </div>
                      </div>
                    </div>

                    <div class="col-lg text-center">
                      <div class="row">
                        <div class="col-12">
                          <b>Saldo: </b> 
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-12">
                          <h6>
                            <span id="general_balance"></span>
                          </h6>
                        </div>
                      </div>
                      
                    </div>

                  </div>
                  <br>
                  <div class="table-responsive">
                    <table class="table" id="tblGeneralDataCashRegisterCut" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th></th>
                          <th>Contado</th>
                          <th>Calculado</th>
                          <th>Diferencia</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td class="header_row"> Efectivo </td>
                          <td id="efectivo_contado_general">0.00</td>
                          <td id="efectivo_calculado_general">$0.00</td>
                          <td id="efectivo_direfencia_general">$0.00</td>
                        </tr>
                        <tr>
                          <td class="header_row">Crédito</td>
                          <td id="credito_contado_general">0.00</td>
                          <td id="credito_calculado_general">$0.00</td>
                          <td id="credito_direfencia_general">$0.00</td>
                        </tr>
                        <tr>
                          <td class="header_row">Transferencia</td>
                          <td id="transferencia_contado_general">0.00</td>
                          <td id="transferencia_calculado_general">$0.00</td>
                          <td id="transferenciacredito_direfencia_general">$0.00</td>
                        </tr>
                        <tr>
                          <td class="header_row">Total</td>
                          <td id="total_contado_general">0.00</td>
                          <td id="total_calculado_general">$0.00</td>
                          <td id="total_direfencia_general">$0.00</td>
                        </tr>
                        
                      </tbody>
                    </table>
                    
                  </div>
                  
              
                </div>
                <div class="tab-pane fade" id="nav-additional-data-cash-register-cut" role="tabpanel" aria-labelledby="nav-additional-data-cash-register-cut">
                  <div class="row">
                    <div class="col-lg-3">
                      <label for="cmbBalancePerPeriod">Corte de caja:</label>
                      <select name="cmbBalancePerPeriod" id="cmbBalancePerPeriod"></select>
                    </div>
                    <div class="col-lg text-center">
                      <h6> 
                        <b>Efectivo retirado:</b> <br> 
                        <span id="efectivo_retirado"></span>
                      </h6>
                    </div>
                    <div class="col-lg text-center">
                      <h6> 
                        <b>Crédito retirado:</b> <br> 
                        <span id="credito_retirado"></span>
                      </h6>
                    </div>
                    <div class="col-lg text-center">
                      <h6> 
                        <b>Transferencia retirada:</b> </br> 
                        <span id="transferencia_retirado"></span>
                      </h6>
                    </div>
                    <div class="col-lg text-center">
                      <h6> 
                        <b>Saldo: </b> <br>
                        <span id="balancePerPeriod"></span>
                      </h6>
                    </div>
                  </div>
                  <br>
                  <div class="table-responsive">
                    <table class="table" id="tblCashRegisterCut" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th></th>
                          <th>Contado</th>
                          <th>Calculado</th>
                          <th>Diferencia</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td class="header_row"> Efectivo </td>
                          <td id="efectivo_contado">0.00</td>
                          <td id="efectivo_calculado">$0.00</td>
                          <td id="efectivo_direfencia">$0.00</td>
                        </tr>
                        <tr>
                          <td class="header_row">Crédito</td>
                          <td id="credito_contado">0.00</td>
                          <td id="credito_calculado">$0.00</td>
                          <td id="credito_direfencia">$0.00</td>
                        </tr>
                        <tr>
                          <td class="header_row">Transferencia</td>
                          <td id="transferencia_contado">0.00</td>
                          <td id="transferencia_calculado">$0.00</td>
                          <td id="transferencia_direfencia">$0.00</td>
                        </tr>
                        <tr>
                          <td class="header_row">Total</td>
                          <td id="total_contado">0.00</td>
                          <td id="total_calculado">$0.00</td>
                          <td id="total_direfencia">$0.00</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
              
                </div>
              </div>

              

            </div>
           </div>
        </div>
        <!-- End Page Content -->
      </div>
      <!-- End Main Content -->
    </div>
    <!-- End Content Wrapper -->
  </div>
  <!-- End Page Wrapper -->

  <script src="js/cortes_caja.js"></script>
</body>
</html>