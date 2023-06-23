<?php
session_start();
require_once '../../../../include/db-conn.php';
$user = $_SESSION["Usuario"];
$PKEmpresa = $_SESSION["IDEmpresa"];

$PKOrden = $_GET["oc"];

$stmt = $conn->prepare("SELECT empresa_id, FKEstatusOrden FROM ordenes_compra WHERE PKORdenCompra = :id");
$stmt->bindValue(':id', $PKOrden, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch();

$GLOBALS["PKOrdenCompra"] = $row['empresa_id'];
$GLOBALS["PKEstatusOrden"] = $row['FKEstatusOrden'];

if (isset($_SESSION["Usuario"])) {
  require_once '../../../../include/db-conn.php';
  $user = $_SESSION["Usuario"];

  if ($GLOBALS["PKOrdenCompra"] != $PKEmpresa) {
    header("location:../../../inventarios_productos/catalogos/orden_compras/");
  }
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
  <title>Timlid | Ver Orden de compra</title>

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
  <link href="../../style/ordenesCompra.css" rel="stylesheet">
  <link href="../../../../css/lobibox.min.css" rel="stylesheet">
  <script src="../../../../vendor/jquery/jquery.min.js"></script>
  <script src="../../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../../../vendor/datatables/dataTables.responsive.js"></script>
  <script src="../../../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../../../vendor/jszip/jszip.min.js"></script>
  <script src="../../../../js/sb-admin-2.min.js"></script>
  <script src="../../../../js/scripts.js"></script>
  <script src="../../../../js/numeral.min.js" charset="utf-8"></script>
  <script src="../../../../js/croppie.js"></script>
  <script src="../../../../js/lobibox.min.js"></script>
  <script src="../../../../js/sweet/sweetalert2.js"></script>
  <script src="../../../../js/notificaciones_timlid.js" charset="utf-8"></script>
  <script src="../../js/ordenesCompra.js" charset="utf-8"></script>
  <script src="../../js/ver_ordenCompra.js" charset="utf-8"></script>
  <script src="../../../../js/numeral.min.js"></script>
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
        $icono = 'ICONO-ORDENES-DE-COMPRA-AZUL.svg';
        $titulo = 'Ver orden de compra';
        $backIcon = true;
        require_once $rutatb . 'topbar.php';
        ?>

        <input type="hidden" id="txtUsuario" value="<?= $_SESSION['PKUsuario']; ?>">
        <input type="hidden" id="txtRuta" value="<?= $ruta; ?>">
        <input type="hidden" id="txtEdit" value="<?= $ruteEdit; ?>">
        <input type="hidden" id="txtPantalla" value="13">
        <input type="hidden" id="txtPKOrden" value="<?= $PKOrden ?>">
        <input type="hidden" id="txtPKOrdenEncrip" value="">

        <!-- Begin Page Content -->
        <div class="container-fluid">
          <div class="row">
            <div class="col-lg-12">
              <!-- Basic Card Example -->
              <div class="card shadow mb-4 nav-link" id="bodyUp">
                <div class="card-body">
                  <div class="d-flex flex-wrap mb-4">
                    <div id="btnEditar">

                    </div>
                    <div>
                      <span data-toggle="modal" class="btn-table-custom btn-table-custom--turquoise" name="btnDescargarOC" onclick="descargarOrdenCompra();"><i class="fas fa-download"></i> Descargar</span>
                    </div>
                    <div id="btnCancelar" style="margin-right: 25px">

                    </div>
                    <div id="btnAceptar">

                    </div>
                  </div>
                  <span id="alertas"> </span>
                  <div class="row">
                    <div class="col-lg-12">
                      <form action="" method="post" id="frmOrdenCompra">

                        <div class="row">
                          <div class="col-lg-3 textData">
                            <p><b class="textBlue">Referencia: </b><span id="txtReferencia"></span></p>
                            <p><b class="textBlue">Condición de pago: </b><span id="txtCondicionPago"></span></p>
                            <p><b class="textBlue">Moneda: </b><span id="txtmoneda"></span></p>
                            <p><b class="textBlue">Categoria: </b><span id="txtcategoria"></span></p>
                          </div>
                          <div class="col-lg-3 textData">
                            <p><b class="textBlue">Proveedor: </b><span id="txtProveedor"></span></p>
                            <p><b class="textBlue">Domicilio de entrega: </b><span id="txtDomi"></span></p>
                            <p><b class="textBlue">Comprador: </b><span id="txtComprador"></span></p>
                            <p><b class="textBlue">Subcategoria: </b><span id="txtsubcategoria"></span></p>
                          </div>
                          <div class="col-lg-3 textData">
                            <b class="textBlue" for="fe">Fecha emisión: </b>
                            <div id="fe"><span id="txtFechaEmision"></span></div>
                            <p></p>
                            <b class="textBlue" for="fv">Fecha estimada de entrega: </b>
                            <div id="fv"><span id="txtFechaEstimada"></span></div>
                            <p></p>
                          </div>
                          <div class="col-lg-3 textData">
                            <h2><b class="textBlue" for="importe">Importe Total: </b>
                              <div id="importe"><b><span id="txtImporte"></span></b></div>
                            </h2>

                          </div>
                        </div>
                        <br>
                        <div class="form-group">
                          <!-- DataTales Example -->
                          <div class="mb-4">
                            <div class="">
                              <div class="table-responsive">
                                <table class="table" id="tblListadoOrdenesCompra" width="100%" cellspacing="0">
                                  <thead>
                                    <tr>
                                      <th>ID</th>
                                      <th>Clave/Producto</th>
                                      <th>Cantidad</th>
                                      <th>Unidad de medida</th>
                                      <th>Precio unitario</th>
                                      <th>Impuestos</th>
                                      <th>Importe</th>
                                    </tr>
                                  </thead>
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>
                        <table class="table">
                          <tr>
                            <th style="text-align: right; color: var(--color-primario);">Subtotal:</th>
                            <td style="text-align: right; color: var(--color-primario);">$ <span id="Subtotal">0.00</span>
                            </td>
                          </tr>
                          <tr>
                            <th style="text-align: right; color: var(--color-primario);">Impuestos:</th>
                            <td style="text-align: right; color: var(--color-primario); width: 270px;" id="impuestos"></td>
                          </tr>
                          <tr class="redondearAbajoIzq">
                            <th style="text-align: right; color: var(--color-primario);" class="redondearAbajoIzq">Total:</th>
                            <td style="text-align: right; color: var(--color-primario);"><b>$ <span id="Total">0.00</span></b></td>
                          </tr>
                        </table>

                        <div class="row">
                          <div class="col-lg-6 text-center">
                            <b>Nota proveedor:</b> <br> <span id="NotasProveedor"></span>
                          </div>
                          <div class="col-lg-6 text-center">
                            <b>Nota Interna:</b> <br> <span id="NotasInternas"></span>
                          </div>
                        </div>
                        <br>

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


  <script src="../../../../js/slimselect.min.js"></script>
  <script>
    loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
    setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
  </script>
</body>

</html>

<!--CANCEL MODAL SLIDE Orden Compra-->
<div class="modal fade" id="cancelar_OrdenCompra" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">¿Desea cancelar la orden de compra?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" class="text-light">x</span>
          </button>
        </div>
        <input type="hidden" vlaue="" id="estatusIDCancelar" name="estatusIDCancelar">
        <div class="modal-body" style="font-size: 10 px!important; color: red;">Al cancelar la Orden de compra se le notificará al proveedor de esta acción vía chat.</div>
        <div class="modal-footer">
          <button class="btn-custom btn-custom--border-blue btnCancelarActualizacion" type="button" data-dismiss="modal"><span class="ajusteProyecto">Cancelar</span></button>
          <button type="submit" onclick="updateEstatusOC($('#estatusIDCancelar').val())" class="btn-custom btn-custom--blue"><span class="ajusteProyecto" data-dismiss="modal">Confirmar</span></button>
        </div>
      </form>
    </div>
  </div>
</div>

<!--ACEPT MODAL SLIDE Orden Compra-->
<div class="modal fade" id="aceptar_OrdenCompra" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">¿Desea aceptar la orden de compra?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" class="text-light">x</span>
          </button>
        </div>
        <input type="hidden" vlaue="" id="estatusIDAceptar" name="estatusIDAceptar">
        <div class="modal-body" style="font-size: 10 px!important; color: red;">Al aceptar usted la Orden de compra se le notificará al proveedor de esta acción vía chat.</div>
        <div class="modal-footer">
          <button class="btn-custom btn-custom--border-blue btnCancelarActualizacion" type="button" data-dismiss="modal"><span class="ajusteProyecto">Cancelar</span></button>
          <button type="submit" onclick="updateEstatusOC($('#estatusIDAceptar').val())" class="btn-custom btn-custom--blue"><span class="ajusteProyecto" data-dismiss="modal">Confirmar</span></button>
        </div>
      </form>
    </div>
  </div>
</div>

<!--ACTIVATE MODAL SLIDE Orden Compra-->
<div class="modal fade" id="activar_OrdenCompra" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">¿Desea reactivar la orden de compra?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" class="text-light">x</span>
          </button>
        </div>
        <input type="hidden" vlaue="" id="estatusIDReactivar" name="estatusIDReactivar">
        <div class="modal-body" style="font-size: 10 px!important; color: red;">Al reactivar la Orden de compra se le notificará al proveedor de esta acción vía chat.</div>
        <div class="modal-footer">
          <button class="btn-custom btn-custom--border-blue btnCancelarActualizacion" type="button" data-dismiss="modal"><span class="ajusteProyecto">Cancelar</span></button>
          <button type="submit" onclick="updateEstatusOC($('#estatusIDReactivar').val())" class="btn-custom btn-custom--blue"><span class="ajusteProyecto" data-dismiss="modal">Reactivar</span></button>
        </div>
      </form>
    </div>
  </div>
</div>

<!--CLOSE MODAL SLIDE Orden Compra-->
<div class="modal fade" id="cerrar_OrdenCompra" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">¿Desea cerrar la orden de compra?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" class="text-light">x</span>
          </button>
        </div>
        <input type="hidden" vlaue="" id="estatusIDCerrar" name="estatusIDCerrar">
        <div class="modal-body" style="font-size: 10 px!important; color: red;">Esta acción no podrá deshacerse, y ya no podrá darle entrada a ningún producto de la orden.</div>
        <div class="modal-footer">
          <button class="btn-custom btn-custom--border-blue btnCancelarActualizacion" type="button" data-dismiss="modal"><span class="ajusteProyecto">Cancelar</span></button>
          <button type="submit" onclick="updateEstatusOC($('#estatusIDCerrar').val())" class="btn-custom btn-custom--blue"><span class="ajusteProyecto" data-dismiss="modal">Cerrar</span></button>
        </div>
      </form>
    </div>
  </div>
</div>