<?php

  //echo $_REQUEST['idFactura'];
  $screen = 16;
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
?>

<!DOCTYPE html>
<html lang="es">

<head>
  
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" type="image/png" href="../../img/header/bluTimlid.png">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Timlid | Nota de crédito</title>

  <!-- ESTILOS -->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">
  <link href="../../css/stylesNewTable.css" rel="stylesheet">
  <link href="../../css/sweetalert2.css" rel="stylesheet">
  <link href="../../css/lobibox.min.css" rel="stylesheet">
  <link href="../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../css/notificaciones.css" rel="stylesheet">
  <link href="../../css/slimselect.min.css" rel="stylesheet">
  <style>
    .textBlue{
      color: var(--azul-mas-oscuro);
    }

    .textData{
      font-size:large;
    }
  </style>

   <link href="../../css/styles.css" rel="stylesheet">
   
  <!-- <link href="css/detalle_factura.css" rel="stylesheet"> -->
  <!-- JS -->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../vendor/jszip/jszip.min.js"></script>
  <script src="../../js/jquery.redirect.min.js"></script>
  <script src="../../js/notificaciones_timlid.js" charset="utf-8"></script>
  <script src="../../js/mdtimepicker.min.js"></script>
  <script src="../../js/permisos_usuario.js"></script>
  <script src="../../js/lobibox.min.js"></script>

  <script src="js/descargas.js"></script>


</head>

<body id="page-top" data-screen="16">
  <div id=loader></div>
  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
        $icono = '../../img/icons/ICONO FACTURACION-01.svg';
        $titulo = 'Detalle Nota de Crédito';
        $backIcon = true;
        $backRoute = (isset($_POST["toDo"]) && $_POST["toDo"] == 1) ? "../notas_credito/cancelacion" :  "../notas_credito";
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

          <!-- Page Heading -->

          <div id="alerta"></div>
          <!-- DataTales Example -->
          <div class="card shadow mb-4">            
            <div class="card-body">
              <input type="hidden" name="idNota" id="idNota" value="<?=$_GET['idNota'];?>">
              <div class="btn-downloads">  
                <b style="margin-right: 25px"><span class="btn-table-custom--turquoise" id="estatus"></span></b>           
                <span id ="btncancelar">
                 <!--  -->
                </span>
              </div>
              <br><br>
              <div class="cabecera-cliente">
                <div class="row">
                  <div class="col-lg-3 textData">
                    <p><b class="textBlue">Serie/Folio: </b><span id="serie_folio"></span></p>
                    <!-- <div id="referencia"></div> -->
                  </div>
                  <div class="col-lg-3 textData">
                    <p><b class="textBlue">Razón social: </b><span id="razon_social"></span></p>
                    <p><b class="textBlue">RFC: </b><span id="rfc"></span></p>
                  </div>
                  <div class="col-lg-3 textData">
                    <b class="textBlue" for="ft">Fecha timbrado: </b><div id="ft"><span id="fecha_timbrado"></span></div><p></p>
                    <div id="fech_canc" style="display: none;">
                      <b class="textBlue" for="fc">Fecha cancelación: </b><div id="fc"><span id="fecha_cancelacion"></span></div><p></p>
                    </div>
                  </div>
                  <div class="col-lg-3 textData">
                    <h2><b class="textBlue" for="it">Importe Total: </b><div id="it"><b>$ <span id="totalFactura"></span></b></div></h2>
                  </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-lg-9 textData">
                    <p><b class="textBlue">Descripción: </b></span></p>
                    <span id="descripcion_NCV">
                  </div>
                </div>      
              </div>
              <br>
            </div>
          </div>
        </div>
        <!-- /.container-fluid -->
      </div>
      <!-- End of Main Content -->
      <!-- Footer -->
      <?php
      require_once 'modal_alert_confirm.php';
        $rutaf = "../";
        require_once('../footer.php');
      ?>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

    <!-- alertConfirm -->
    <div class="modal fade" id="mdlAlert" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 id="titlemdl" class="modal-title" id="exampleModalLabel">¿Cancelar Nota de Crédito?</h5>
        </div>
        <div id="msj" class="modal-body"><center><h4>¿Deseas cancelar la nota de crédito? </h4></center></div>
        <div class="modal-footer">
          <button type="button" class="btn-custom btn-custom--border-blue btnCancelarActualizacion" data-dismiss="modal">Cancelar</button>
          <button type="button" class="btn-custom btn-custom--blue" id="btnAcepCambioss">Aceptar</button>
        </div>
      </div>
    </div>
  </div>
  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>


  <script src="js/ver_detalleVenta_v1.js"></script>
  <script> var ruta = "../";</script>
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/scripts.js"></script>
  <script src="../../js/slimselect.min.js"></script>
</body>

</html>