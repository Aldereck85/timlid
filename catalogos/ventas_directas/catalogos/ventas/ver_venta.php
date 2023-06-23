<?php
session_start();
require_once '../../../../include/db-conn.php';
$user = $_SESSION["Usuario"];
$PKEmpresa = $_SESSION["IDEmpresa"];

$pkVentaDirecta = $_GET["vd"];
$idVentaDirecta = $pkVentaDirecta;
$FKUsuario = $_SESSION["PKUsuario"];

$stmt = $conn->prepare("SELECT empresa_id, FKEstatusVenta FROM ventas_directas WHERE PKVentaDirecta = :id");
$stmt->bindValue(':id', $pkVentaDirecta, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch();

$GLOBALS["PKVentaDirecta"] = $row['empresa_id'];
$GLOBALS["PKEstatusVenta"] = $row['FKEstatusVenta'];

if (isset($_SESSION["Usuario"])) {
    require_once '../../../../include/db-conn.php';
    $user = $_SESSION["Usuario"];

    if($GLOBALS["PKVentaDirecta"] != $PKEmpresa){
      header("location:../../../ventas_directas/catalogos/ventas/");
    }
} else {
    header("location:../../../dashboard.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="icon" type="image/png" href="../../../../img/header/bluTimlid.png">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <meta http-equiv="Last-Modified" content="0">
  <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <title>Timlid | Ver venta</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Page level plugins -->
  <script src="../../../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="../../../../vendor/datatables/dataTables.responsive.js"></script>
  <script src="../../../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../../../vendor/datatables/buttons.bootstrap4.min.js"></script>
  <script src="../../../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../../../vendor/jszip/jszip.min.js"></script>

  <!-- Page level custom scripts
    <script src="js/demo/datatables-demo.js"></script>
    -->

  <!-- Custom fonts for this template -->
  <link href="../../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Custom styles for this template -->
  <link href="../../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../../css/slimselect.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../style/agregar_entrada.css">
  <link rel="stylesheet" href="../../style/ver_ventas.css">

  <!-- Custom scripts for all pages-->

  <script src="../../../../js/sb-admin-2.min.js"></script>
  <script src="../../../../js/scripts.js"></script>

  <!-- Custom styles for this page -->
  <link href="../../../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  <link href="../../../../vendor/datatables/responsive.dataTables.css" rel="stylesheet">
  <link rel="stylesheet" href="../../../../vendor/datatables/buttons.dataTables.css">
  <link href="../../../../vendor/datatables/buttons.bootstrap4.min.css" rel="stylesheet">
  <link href="../../../../css/stylesTable.css" rel="stylesheet">
  <link href="../../../../css/styles.css" rel="stylesheet">
  <link rel="stylesheet" href="../../../../css/croppie.css" />

  <script src="../../../../js/numeral.min.js" charset="utf-8"></script>
  <script src="../../../../js/croppie.js"></script>

  <script src="../../../../js/sweet/sweetalert2.js"></script>
  <link rel="stylesheet" href="../../../../css/sweetalert2.css">
  <link rel="stylesheet" href="../../../../css/notificaciones.css">
  <link rel="stylesheet" href="../../style/ventas.css">

  <script src="../../../../js/notificaciones_timlid.js" charset="utf-8"></script>
  <script src="../../js/ventas.js" charset="utf-8"></script>
  <script src="../../../../js/numeral.min.js"></script>
  <link href="../../../../css/lobibox.min.css" rel="stylesheet">
  <script src="../../js/ver_venta.js" charset="utf-8"></script>
</head>

<body id="page-top">
  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
$icono = '../../../../img/Ventas/ICONO VENTAS_Mesa de trabajo 1.svg';
$titulo = 'Ver venta';
$ruta = "../../../";
?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">
        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 sticky-top shadow">
          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>
          <!-- Topbar Search -->
          <h3 class="d-none d-sm-inline-block mr-auto ml-md-3 my-2 my-md-0 mw-100">
            <?php if (!isset($icono)) {
              $icono = $rutatb . "../img/menu/dashboardTopbar.svg";
            }
            ?>
            <img src="<?=$icono;?>" alt="" width="40px">
            <?=$titulo;?>
            <a href="<?= $PKEstatusVenta == 1 ? "./editar_venta.php?vd=".$idVentaDirecta : "./" ?>" data-toggle="tooltip" data-placement="bottom" title="Regresar" class="ml-3">
              <img src="../../../../img/icons/REGRESAR_2.svg" alt="Regresar" width="40px">
            </a>
          </h3>
        </nav>

        <input type="hidden" id="txtUsuario" value="<?=$_SESSION['PKUsuario'];?>">
        <input type="hidden" id="txtRuta" value="<?=$ruta;?>">
        <input type="hidden" id="txtEdit" value="<?=$ruteEdit;?>">
        <input type="hidden" id="txtPantalla" value="13">
        <input type="hidden" id="cmbProveedor" value="0">

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <div class="row">
            <div class="col-lg-12">

              <!-- Basic Card Example -->
              <div class="card shadow mb-4 nav-link" id="bodyUp">
                <div>
                  <span id="alertas"> </span>
                  <div class="row">
                    <div class="col-lg-12">
                      <form action="" method="post" id="frmVerVentaDirecta">
                        <input type="hidden" value="<?=$pkVentaDirecta?>" id="txtPKVenta">
                        <div class="form-group" style="width:70%; margin:auto; ">
                          <div class="row">
                            <div class="col-lg-6" >
                            <span id="botones">
                                
                             </span> 
                            </div>
                          </div>
                        </div>
                        <br><br>
                        <div class="form-group" style="width:70%; margin:auto; border:1px solid">
                          <div class="row">
                            <table class="table2">
                              <tr>
                                <td class="td2" width="30%">Venta</td>
                                <th class="td2" width="40%" style="background-color: transparent;">
                                <td class="td2" width="100%"><img src="../../../../img/Logo-transparente.png"
                                    width="300"></td>
                              </tr>
                            </table>
                            <br>
                            <table class="table1">
                              <tr>
                                <td class="td1" width="30%">
                                  <span id="referencia">
                                
                                  </span>
                                </td>
                              </tr>
                            </table>
                            <table class="table1">
                              <tr>
                                <th width="15%">Fecha de Solicitud</th>
                                <th width="55%" style="background-color: transparent;"></th>
                                <th width="30%">Cliente</th>
                              </tr>
                              <tr>
                                <td class="td1" width="15%">
                                  <span id="fechaIngreso">
                                  
                                  </span>
                                </td>
                                <td class="td1" width="55%" style="background-color: transparent;"></td>
                                <td class="td1" width="100%">
                                  <span id="nombreComercial">
                                  
                                  </span>
                                </td>
                              </tr>
                            </table>

                            <table class="table1">
                              <tr>
                                <th width="15%">Contacto:</th>
                                <td class="td1" width="40%">
                                  <span id="vendedor">
                                  
                                  </span>
                                </td>
                                <th width="5%" style="background-color: transparent;"></th>
                                <th width="15%">Fecha de solicitud de entrega:</th>
                                <td class="td1" width="100%" height="60px">
                                  <span id="fechaEstimada">
                                  
                                  </span>
                                </td>
                              </tr>
                            </table>

                            <table class="table1">
                              <tr>
                                <th width="10%">Referencia:</th>
                                <td class="td1" width="15%">
                                  <span id="referencia2">
                                  
                                  </span>
                                </td>
                                <th width="5%" style="background-color: transparent;"></th>
                                <th width="15%">Proveedor:</th>
                                <td class="td1" width="25%">
                                  <span id="nombreComercial2">
                                  
                                  </span>
                                </td>
                                <th width="5%" style="background-color: transparent;"></th>
                                <th width="10%">Sucursal:</th>
                                <td class="td1" width="100%" height="40px">
                                  <span id="sucursal">
                                  
                                  </span>
                                </td>
                              </tr>
                            </table>

                            <table class="table1">
                              <tr>
                                <th width="15%">Domicilio de entrega:</th>
                                <td class="td1" width="100%" height="50px">
                                  <span id="direccion">
                                  
                                  </span>
                                </td>

                              </tr>
                            </table>

                            <table class="table1" id="tablaProductos">
                              <thead>
                                <tr>
                                  <th width="9%">Clave</th>
                                  <th width="36%">Producto</th>
                                  <th width="14%">Cantidad</th>
                                  <th width="14%">Precio</th>
                                  <th width="15%">U. medida</th>
                                  <th width="12%">Impuestos</th>
                                  <th width="12%">Importe</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                </tr>
                              </tbody>
                            </table>
                            
                            <table class="table1" id="tablaImpuestos">
                              <tr>
                                <td class="td1" width="65%" style="background-color: transparent;border-bottom: 1px solid #fff; border-top: 1px solid #fff;"></td>
                                <td class="td1" width="21%">Subtotal:</td>
                                <td class="td1" width="100%" style="text-align: right;">
                                  <span id="subtotal">

                                  </span>
                                </td>
                              </tr>
                              <tr>
                                <td class="td1" width="65%" style="background-color: transparent;border-bottom: 1px solid #fff; border-top: 1px solid #fff;"></td>
                                <td class="td1" width="21%">Impuestos:</td>
                                <td class="td1" width="100%" height="30px"></td>
                              </tr>
                              <tr>
                              </tr>
                            </table>

                            <table class="table1">
                              <tr>
                                <th width="100%" style="text-align: left;">Notas</th>
                              </tr>
                              <tr>
                                <td class="td1" width="100%">
                                  <span id="notas">
                                  
                                  </span>
                                </td>
                              </tr>
                            </table>
                            <br><br><br>
                            <table class="table1">
                              <tr>
                                <th class="td2" width="50%" style="font-size: 16px; background-color: transparent; color:black;" >
                                  <b>Contacto:</b>
                                  <span id="vendedor2">
                                  
                                  </span>
                                </th>
                                <th class="td2" width="50%" style="font-size: 16px; background-color: transparent; color:black;">
                                  <b>Teléfono:</b>
                                  <span id="telefono">
                                  
                                  </span>
                                </th>
                              </tr>
                              <tr>
                                <th class="td2" width="50%" style="font-size: 16px; background-color: transparent; color:black;"><b>Email:</b> <?=$user?></th>
                              </tr>
                            </table>
                          </div>

                          </br></br>

                          <span id="modal_envio"></span>

                      </form>
                    </div>
                  </div>
                </div>
              </div>
              <!-- End Basic Card Example -->

            </div>
          </div>

        </div>
        <!-- End Page Content -->
      </div>

      <!--<embed src="../../../../ordenComp/OrdendeCompra_15.pdf" type="application/pdf" width="100%" height="600px" />-->
      <!-- End Main Content -->

      <!-- Footer -->
      <?php
$rutaf = "../../../";
require_once $rutaf . 'footer.php';
?>
      <!-- End of Footer -->

    </div>
    <!-- End Content Wrapper -->



  </div>
  <!-- End Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!--<script src="../../../../js/sb-admin-2.min.js"></script>
  <script src="../../../../js/scripts.js"></script>-->
  <script src="../../../../js/slimselect.min.js"></script>
  <!--<script src="../../../../js/lobibox.min.js"></script>-->
  <script>
  loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
  setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit')
    .val());
  </script>
</body>

</html>