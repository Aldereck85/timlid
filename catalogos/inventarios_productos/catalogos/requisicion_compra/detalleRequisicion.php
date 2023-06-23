<?php
session_start();
$jwt_ruta = "../../../../";
require_once '../../../jwt.php';

date_default_timezone_set('America/Mexico_City');

$token = $_SESSION['token_ld10d'];

if (isset($_SESSION["Usuario"])) {
  require_once '../../../../include/db-conn.php';
  $user = $_SESSION["Usuario"];
} else {
  header("location:../../../dashboard.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" type="image/png" href="../../../../img/header/bluTimlid.png">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Timlid | Detalle requisición de compra</title>

  <!-- ESTILOS -->
  <link href="../../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../style/agregar_entrada.css" rel="stylesheet">
  <link href="../../style/pestanas_producto.css" rel="stylesheet">
  <link href="../../../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../../../vendor/datatables/responsive.dataTables.css" rel="stylesheet">
  <link href="../../../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../../../css/stylesNewTable.css" rel="stylesheet">
  <link href="../../../../css/styles.css" rel="stylesheet">
  <link href="../../../../css/croppie.css" rel="stylesheet">
  <link href="../../../../css/sweetalert2.css" rel="stylesheet">
  <link href="../../../../css/notificaciones.css" rel="stylesheet">
  <link href="../../../../css/lobibox.min.css" rel="stylesheet">
  <link href="css/disabled.css" rel="stylesheet">
  <style>
    table.dataTable tbody tr td:last-of-type, table.dataTable tbody tr td:last-of-type i, table.dataTable tbody tr td:last-of-type a, table.dataTable tbody tr td:last-of-type img {
      color: #858796;
      text-align: left !important;
    }
  </style>  
  <script src="../../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../../../vendor/datatables/dataTables.responsive.js"></script>
  <script src="../../../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../../../vendor/jszip/jszip.min.js"></script>
  <script src="../../../../js/numeral.min.js" charset="utf-8"></script>
  <script src="../../../../js/croppie.js"></script>
  <script src="../../../../js/sweet/sweetalert2.js"></script>
  <script src="../../../../js/jquery.redirect.min.js"></script>
</head>

<body id="page-top">
  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
    $ruta = "../../../";
    $ruteEdit = $ruta . "central_notificaciones/";
    require_once $ruta . 'menu3.php';
    ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">
        <?php
        $rutatb = "../../../";
        $icono = '../../../../img/icons/ICONO ORDENES DE COMPRA-01.svg';
        $titulo = 'Detalle requisición de compra';
        $backIcon = true;
        require_once $rutatb . 'topbar.php';
        ?>

        <input type="hidden" id="txtUsuario" value="<?= $_SESSION['PKUsuario']; ?>">
        <input type="hidden" id="txtRuta" value="<?= $ruta; ?>">
        <input type="hidden" id="txtEdit" value="<?= $ruteEdit; ?>">
        <input type="hidden" id="RequisicionId" value="<?= $_GET['requisicion']; ?>">

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <div class="row">
            <div class="col-lg-12">

              <!-- Basic Card Example -->
              <div class="card shadow mb-4 nav-link" id="bodyUp">
                <div class="card-body">
                  <span id="alertas"> </span>
                    <div class="row" style="display: flex;">
                      <div id="divEstatus">

                      </div>
                      &nbsp;
                      <div id="divEstatusSpan">

                      </div>
                      &nbsp;&nbsp;
                      <div id="divBtnSeguimiento">
                      &nbsp;&nbsp;
                      </div>
                      <div id="divBtnDescargar">

                      </div>
                      <div id="divBtnEditar">

                      </div>
                      <div id="divBtnCerrar">

                      </div>
                      <div id="divBtnCancelar">

                      </div>
                      <div id="divComboOrdenes"  style=" position: absolute; right: 2%;">

                      </div>
                    </div>
                    <br>
                        <div class="row">
                          <div class="col-lg-3">
                            <label for="usr">Folio:</label>
                            <h5 id="txtFolio"></h5>
                          </div>
                          <div class="col-lg-3">
                            <label for="usr">Fecha de emision:</label>
                            <h5 id="txtFechaEmision"></h5>
                          </div>
                          <div class="col-lg-3">
                            <label for="usr">Fecha estimada de entrega:</label>
                            <h5 id="txtFechaEntrega"></h5>
                          </div>
                          <div class="col-lg-3">
                            <div class="form-group">
                              <label for="cmbDireccionEnvio">Sucursal de entrega:</label>
                              <h5 id="txtSucursal"></h5>
                            </div>
                          </div>
                        </div>
                        <br>
                        <div class="row">
                          <div class="col-lg-3">
                            <label for="cmbArea">Area/Departamento:</label>
                            <h5 id="txtArea"></h5>
                          </div>
                          <div class="col-lg-3">
                            <label for="cmbEmpleado">Empleado:</label>
                            <h5 id="txtEmpleado"></h5>
                          </div>
                          <div class="col-lg-3">
                            <div class="form-group" id="divProvee">
                              <label for="usr">Proveedor:</label>
                              <h5 id="txtProveedor">N/A</h5>
                            </div>
                          </div>
                          <div class="col-lg-3">
                            <div class="form-group">
                              <label for="cmbComprador">Comprador:</label>
                              <h5 id="txtComprador"></h5>
                            </div>
                          </div>
                        </div>
                        <br>
                        <div class="form-group">
                          <!-- DataTales Example -->
                          <div class="mb-4">
                            <div class="">
                              <div class="table-responsive">
                                <table class="table" id="tblListadoProductosRequisiciones" width="100%" cellspacing="0">
                                  <thead>
                                    <tr>
                                      <th>ID</th>
                                      <th>Clave</th>
                                      <th>Producto</th>
                                      <th>Cantidad requerida</th>
                                      <th>Cantidad colocada</th>
                                      <th>Unidad de medida</th>
                                    </tr>
                                  </thead>
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-lg-6">
                            <label for="usr">Notas visibles al Comprador</label>
                            <textarea disabled class="form-control alphaNumeric-only" cols="10" rows="3" name="NotasComprador" id="NotasComprador" placeholder="Aquí debería estar la descripción de tu requisición de compra o datos importantes dirigidos hacia el comprador" maxlength="255"></textarea>
                          </div>
                          <div class="col-lg-6">
                            <label for="usr">Notas internas</label>
                            <textarea disabled class="form-control alphaNumeric-only" cols="10" rows="3" name="NotasInternas" id="NotasInternas" placeholder="Aquí debería estar la descripción de tu requisición o datos importantes solo para uso interno" maxlength="255"></textarea>
                          </div>
                        </div>
                        <br>
                        
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

  <?php
  require_once 'modal_alert.php';
  require_once 'modal_notFound.php';
  ?>

  <!-- modal para la cancelacion de la requisicion -->
  <div class="modal fade" id="modal_cancelar_Requisicion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">¿Desea cancelar la requisición de compra?</h5>
        </div>
        <div class="modal-body" style="color: red;"><center><h4>Esta acción no podrá deshacerse.</h4></center></div>
        <div class="modal-footer">
          <button class="btn-custom btn-custom--border-blue" type="button" data-dismiss="modal"><span class="ajusteProyecto">Cancelar</span></button>
          <button type="submit" onclick="cancelaRequisicion()" class="btn-custom btn-custom--blue"><span class="ajusteProyecto" data-dismiss="modal">Confirmar</span></button>
        </div>
      </div>
    </div>
  </div>

  <!-- modal para cerrar la requisición -->
  <div class="modal fade" id="modal_cerrar_Requisicion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">¿Desea cerrar la requisición de compra?</h5>
        </div>
        <div class="modal-body" style="color: red;"><center><h4>Esta acción no podrá deshacerse.</h4></center></div>
        <div class="modal-footer">
          <button class="btn-custom btn-custom--border-blue" type="button" data-dismiss="modal"><span class="ajusteProyecto">Cancelar</span></button>
          <button type="submit" onclick="cerrarRequisicion()" class="btn-custom btn-custom--blue" data-dismiss="modal"><span class="ajusteProyecto">Confirmar</span></button>
        </div>
      </div>
    </div>
  </div>
</div>
  <script src="../../../../js/sb-admin-2.min.js"></script>
  <script src="../../../../js/scripts.js"></script>
  <script src="../../../../js/notificaciones_timlid.js" charset="utf-8"></script>
  <script src="js/requisicionCompra.js" charset="utf-8"></script>
  <script src="js/detalleRequisicion.js" charset="utf-8"></script>
  <script src="../../../../js/numeral.min.js"></script>
  <script src="../../../../js/slimselect.min.js"></script>
  <script src="../../../../js/lobibox.min.js"></script>
  <script src="js/scriptNotificaciones.js"></script>
</body>

</html>