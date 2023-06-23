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
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" type="image/png" href="../../img/header/bluTimlid.png">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Timlid | Cuentas Por Cobrar</title>

  <!-- ESTILOS -->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../css/stylesModal-lg.css" rel="stylesheet">
  <link href="../../css/lobibox.min.css" rel="stylesheet">
  <link href="../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">
  <link href="../../css/stylesNewTable.css" rel="stylesheet">
  <link href="css/modal.css" rel="stylesheet">
  <link rel="stylesheet" href="../../css/notificaciones.css">
  <link href="css/signoPesos.css" rel="stylesheet">
  <link href="css/disabled.css" rel="stylesheet">
  <link rel="stylesheet" href="css/detalle_factura.css">
  <!-- JS -->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="js/data_detalle_factura.js"></script>
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
  <script src="../../js/jquery.redirect.min.js"></script>
  <style>
    .textData{
      font-size: large;
    }
    .border_top {
      border-top: 1px solid #edeff5 !important;
    }
    .not_border_top {
      border-top: none !important;
    }
  </style>
</head>

<!--verificacion de permisos para la pagina-->
<?php
require_once 'functions/function_Permisos.php';
?>

<body id="page-top" class="sidebar-toggled">


  <div id="wrapper">

    <!-- Sidebar -->
    <?php
    $titulo = "Cuentas por Cobrar";
    $ruta = "../";
    $ruteEdit = $ruta . "central_notificaciones/";
    require_once '../menu3.php';

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
        $backIcon=true;
        $icono = 'ICONO-CUENTAS-POR-COBRAR-AZUL.svg';
        $backIcon = true;
        require_once "../topbar.php"
        ?>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">
          <div class="card mb-4">
            <div class="card-body">
            <input type="hidden" id="idFactura" value="<?php echo (int)(isset($_GET['idFactura']) ? $_GET['idFactura'] : $_GET['idVenta']); ?>" />
            <input type="hidden" id="is_invoice" value="<?php echo (int)(isset($_GET['idFactura']) ? 1 : 0); ?>" />
              <!-- Example single danger button -->
              <div class="row">
                <div class="col-6">
                  <a style="cursor:pointer; padding-right:1.5rem" id="btnPagos" class="btn-table-custom btn-table-custom--blue"><img style="width:1.5rem; vertical-align: top;" src="../../img/facturacion/aplicar_pago.svg"> Registrar pago</a>
                  <a style="cursor:pointer; display:none; padding-right:1.5rem" id="btnNotas" class="btn-table-custom btn-table-custom--blue"><img style="width:1.5rem; vertical-align: top;" src="../../img/cuentas_cobrar/aplicar_nota_credito.svg"> Crear nota de crédito</a>
                  <a style="cursor:pointer; padding-right:1.5rem" onclick="Descarga_pdf()" id="btn_downpdf" class="btn-table-custom--turquoise btn_dataTable"><img style="width:1.5rem; vertical-align: top;" src="../../img/cotizaciones_nuevos_iconos/ICONO-DESCARGAR AZUL OSCURO NVO-01.svg"> Descargar PDF</a>
                  <a style="cursor:pointer;  display:none;" onclick="Descarga_xml()" id="btn_downxml" class="btn-table-custom--turquoise btn_dataTable"><img style="width:1.5rem; vertical-align: top;" src="../../img/cotizaciones_nuevos_iconos/ICONO-DESCARGAR AZUL OSCURO NVO-01.svg"> Descargar XML</a>
                </div>
                <div class="col-6">
                  <div class="btn-group float-right" id="btn_docs_Relacionados" style="display:none;">
                    <button type="button" class="btn dropdown-toggle btn-custom--white-dark btn-custom" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="../../img/icons/ICONO-LISTA DE MATERIALES AZUL NVO-01.svg" width="20" class="mr-1">
                      Documentos Relacionados
                    </button>
                    <div class="dropdown-menu">
                      <a class="dropdown-item" id="btnNC" style="cursor:pointer">Notas de Credito</a>
                      <a class="dropdown-item" id="btnCP" style="cursor:pointer">Complementos de pagos</a>
                    </div>
                  </div>
                </div>
              </div> 
              <br>
              <div class="form-group">
                <label for="prov_id"></label>
                <div class="row">
                  <div class="col-lg-3">
                    <div class="col-xl-12 col-lg-6 col-md col-sm-12 col-xs textData">
                      <p>
                        <b class="textBlue">Folio: </b>
                        <span id="txtfolio"></span>
                      </p>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="col-xl-12 col-lg-6 col-md col-sm col-xs textData">
                      <p>
                        <b class="textBlue">Cliente: </b>
                        <span id="nombre"></span>
                      </p>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="col-xl-12 col-lg-6 col-md col-sm-12 col-xs textData">
                      <p>
                        <b class="textBlue">F. de expedición: </b>
                        <span id="txtfechaF"></span>
                      </p>
                      <p></p>
                      <p>
                        <b class="textBlue">F. de vencimiento: </b>
                        <span id="txtfechaV"></span>
                      </p>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="col-xl-12 col-lg-6 col-md col-sm-12 col-xs textData">
                      <h2>
                        <b class="textBlue">Importe: </b>
                        <div>
                          <b><span id="txtimporte"></span></b>
                        </div>
                      </h2>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table" id="tbldetalle" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Clave</th>
                      <th>Descripción</th>
                      <th>Cantidad</th>
                      <th>Precio unitario</th>
                      <th>Importe</th>
                    </tr>
                  </thead><!-- 
                  <tbody>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th class="border_top"></th>
                      <th class="border_top"></th>
                      <th class="border_top"></th>
                      <th class="border_top text-center" style="color:var(--color-primario);">Subtotal:</th>
                      <td class="border_top text-center" style="color:var(--color-primario);" id="subtotal"></td>
                    </tr>
                    <tr>
                      <th class="not_border_top"></th>
                      <th class="not_border_top"></th>
                      <th class="not_border_top"></th>
                      <th class="border_top text-center" style="color: var(--color-primario)">Impuestos:</th>
                      <td class="border_top text-center" style="color: var(--color-primario)" id="impuestos"></td>
                    </tr>
                    <tr>
                      <th class="not_border_top"></th>
                      <th class="not_border_top"></th>
                      <th class="not_border_top"></th>
                      <th class="border_top text-center" style="color: var(--color-primario)">Total neto:</th>
                      <td class="border_top text-center" style="color: var(--color-primario)" id="total"></td>
                    </tr>
                  </tfoot> -->
                </table>
              </div>
              <div class="col-12" style="float:right">
                <table class="table table-hover" ALIGN="right" style="width: 30%;">
                  <tfoot>
                    <tr>
                      <th style="color: var(--color-primario);"><b>Subtotal:</b></th>
                      <td style="color: var(--color-primario);" id="Subtotal">
                      </td>
                      <th style="width:60px;"></th>
                    </tr>
                    <tr>
                      <th style="color: var(--color-primario);"><b>Impuestos:</b></th>
                      <td style="color: var(--color-primario);" id="impuestos"></td>
                      <th></th>
                    </tr>
                    <tr class="total redondearAbajoIzq">
                      <th style="color: var(--color-primario);" class="redondearAbajoIzq"><b>Total:</b></th>
                      <td style="color: var(--color-primario);" id="Total"></td>
                      <th></th>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <?php
      require_once 'modal_alert.php';
      require_once 'modal_invoiceNotFound.php';
      ?>

      <!-- Footer -->
      <?php
      $rutaf = "../";
      require_once '../footer.php';
      ?>


    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/scripts.js"></script>
  <script src="js/scriptNotificaciones.js"></script>

</body>

</html>