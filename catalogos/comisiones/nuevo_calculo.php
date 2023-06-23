<?php
session_start();
$jwt_ruta = "../../";
require_once '../jwt.php';

date_default_timezone_set('America/Mexico_City');

$token = $_SESSION['token_ld10d'];

if (isset($_SESSION["Usuario"])) {
  require_once '../../include/db-conn.php';
  $user = $_SESSION["Usuario"];
} else {
  header("location:../dashboard.php");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" type="image/png" href="../../img/header/bluTimlid.png">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Timlid | Núevo cálculo</title>

   <!-- ESTILOS -->
  <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
  <link href="../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">
  <link href="../../css/stylesNewTable.css" rel="stylesheet">
  <link href="../../css/stylesModal-lg.css" rel="stylesheet">
  <link href="../../css/lobibox.min.css" rel="stylesheet">
  <link href="css/nuevo_calculo.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" rel="stylesheet">
  
  <!-- JS -->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
  <script src="https://cdn.datatables.net/datetime/1.1.0/js/dataTables.dateTime.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/plug-ins/1.10.21/sorting/datetime-moment.js"></script>
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../js/sweet/sweetalert2.js"></script>
  <script src="../../js/validaciones.js"></script>
  <script src="../../js/lobibox.min.js"></script>
  <script src="../../js/slimselect.min.js"></script>
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../vendor/jszip/jszip.min.js"></script>
  <script src="../../js/notificaciones_timlid.js" charset="utf-8"></script>
</head>

<body id="page-top" class="sidebar-toggled">
  <div id=loader></div>
  
  <!-- Page Wrapper -->
  <div id="wrapper">

     <!-- Sidebar -->
     <?php
        $icono = '../../img/icons/ICONO FACTURACION-01.svg';
        $titulo = 'Nuevo cálculo';
        $backIcon = true;
        $ruta = "../";
        $ruteEdit = $ruta . "central_notificaciones/";
        require_once $ruta . 'menu3.php';
    ?>
    <!-- End of Sidebar -->

    <input type="hidden" id="txtUsuario" value="<?= $_SESSION['PKUsuario']; ?>">
    <input type="hidden" id="txtRuta" value="<?= $ruta; ?>">
    <input type="hidden" id="txtEdit" value="<?= $ruteEdit; ?>">

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <?php
          $rutatb = "../";
          require_once '../topbar.php';
        ?>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">
          <div class="card mb-4">
            <div class="card-body">
              <div class="form-group">
                <div class="row">
                  <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6" >
                    <label for="cmbProveedor">Vendedor:</label>
                    <select name="chosenVendedores" id="chosenVendedores" onchange="ocultarTabla('')">
                      <option disabled selected value="f">Selecciona un vendedor</option>
                    </select>
                    <div class="invalid-feedback" id="invalid-cmbVendedores">.</div>
                  </div>
                  <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                    <label for="chosenTipo">Tipo de documento:</label>
                    <select name="chosenTipo" id="chosenTipo" onchange="ocultarTabla('')">
                      <option selected value="1">Todos</option>
                      <option  value="2">Facturas</option>
                      <option  value="3">Ventas</option>
                    </select>
                    <div class="invalid-feedback" id="invalid-cmbTipo">.</div>
                  </div>
                  <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-xs-6">
                    <label for="txtDateFrom">Desde:</label>
                    <input class="form-control" type="date" name="txtDateFrom" id="txtDateFrom" max="<?php echo (date('Y-m-d'));?>" onchange="ocultarTabla('')">
                    <div class="invalid-feedback" id="invalid-txtDateFrom"></div>
                  </div>
                  <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-xs-6">
                    <label for="txtDateTo">Hasta:</label>
                    <input class="form-control" type="date" name="txtDateTo" id="txtDateTo" max="<?php echo (date('Y-m-d'));?>" onchange="ocultarTabla('')">
                    <div class="invalid-feedback" id="invalid-txtDateTo">.</div>
                  </div>
                  <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-xs-6">
                    <label for="number">Porcentaje de comisión:</label>
                    <input class="form-control" type="number" name="txtPorcentaje" id="txtPorcentaje" max="100" min="0">
                    <div class="invalid-feedback" id="invalid-txtPorcentaje">.</div>
                  </div>
                  <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-xs-6">    
                    <a class="btn-custom btn-custom--blue" id="btnBuscarFacturas" style="margin-top: 10px!important">Buscar facturas</a>
                  </div>
                </div>
              </div>

              <br>

              <div class="table-responsive" id="Sfacturas">
                <table class="table" id="tblSfacturas" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th style="text-align: center">Folio</th>
                      <th>Fecha de factura</th>
                      <th style="text-align: center">Razón social del cliente</th>
                      <th>Monto facturado (sin impuestos)</th>
                      <th>Monto comisionado por factura</th>
                      <th style="text-align: center">Seleccionar</th>
                    </tr>
                  </thead>
                </table>
                <br>
                <div style="float:right; width: 30%!important">
                  <table class="table" id="tblTotalCalculado" cellspacing="0">
                    <tbody>
                      <tr>
                        <th class="head-label-subtotal">Total calculado:</th>
                        <td class="head-quantity-total">$<label id="totalCalculado"></label></td>
                      </tr>
                      <tr>
                        <th class="head-label-subtotal">Total ingresado:</th>
                        <td class="head-quantity-total" style="display: flex; align-items: center;">$<input class="form-control" type="number" name="txtMontoIngresado" id="txtMontoIngresado" min="0"></td>
                      </tr>
                    </tbody>
                  </table>
                  <br>
                  <button type="button" class="btnesp espAgregar float-right" name="btnAgregar" id="btnAgregarCalculo"><span class="ajusteProyecto">Guardar cálculo</span></button>
                </div> 
              </div>
            </div>
          </div>
        </div>         
      </div>
      <!-- End of Main Content -->

    </div>
    <!-- End of Content Wrapper -->

  </div>


  <!-- End Page Wrapper -->
  <script src="../../js/slimselect.min.js"></script>
  <script src="js/data_nuevo_calculo.js"></script>

  <script>
    var selectVendedor = new SlimSelect({
      select: '#chosenVendedores',
      deselectLabel: '<span class="">✖</span>'
    });
    var selectTipo = new SlimSelect({
      select: '#chosenTipo',
      deselectLabel: '<span class="">✖</span>'
    });
  </script>

</body>
</html>
