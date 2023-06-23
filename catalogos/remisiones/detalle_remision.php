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
  
  <link rel="icon" type="image/png" href="../../img/header/bluTimlid.png">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Timlid | Detalle Factura</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../../js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../vendor/datatables/buttons.bootstrap4.min.js"></script>
  <script src="../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../vendor/jszip/jszip.min.js"></script>
  <script src="../../js/jquery.redirect.min.js"></script>

  <!-- Custom fonts for this template -->
  <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Custom styles for this template -->
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../vendor/datatables/buttons.bootstrap4.min.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">
  <link href="../../css/stylesNewTable.css" rel="stylesheet">
  <link href="../../css/slimselect.min.css" rel="stylesheet">
  
  <link rel="stylesheet" href="../../css/sweetalert2.css">
  <link href="../../css/lobibox.min.css" rel="stylesheet">

  <link rel="stylesheet" href="../../css/notificaciones.css">
  <script src="../../js/notificaciones_timlid.js" charset="utf-8"></script>
  
  <script src="../../js/mdtimepicker.min.js"></script>
  
  <script src="../../js/permisos_usuario.js"></script>

  <link rel="stylesheet" href="css/detalle_remision.css">

  <script src="../../js/lobibox.min.js"></script>

</head>

<body id="page-top" data-screen="16">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
        $icono = '../../img/icons/ICONO FACTURACION-01.svg';
        $titulo = 'Detalle Remision';
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

          <!-- Page Heading -->

          <div id="alerta"></div>
          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            
            <!--<div class="permission-view-add"></div>-->
            
            <div class="card-body">
              <input type="hidden" name="txtIdFactura" id="txtIdFactura" value="<?=$_REQUEST['idRemision'];?>">
              <!--
              <div class="btn-downloads">  
                
                <a style="float:left;margin-right: 5px;" href="php/download_pdf.php?value=<?//=$_REQUEST['idRemision'];?>" class="btn btn-primary" target="_blank">Descargar pdf</a>
                <a style="float:left;margin-right: 5px;" href="php/download_xml.php?value=<?//=$_REQUEST['idRemision'];?>" class="btn btn-primary" target="_blank">Descargar xml</a>
                <a style="float:left;margin-right: 5px;" href="php/download_zip.php?value=<?//=$_REQUEST['idRemision'];?>" class="btn btn-primary" target="_blank">Descargar zip</a>
                <a style="float:left;margin-right: 5px;" href="#" class="btn btn-primary" id="send_email" data-toggle="modal" data-target="#enviarFactura">Enviar por email</a>
                <a style="float:right" href="#" class="btn btn-danger" data-toggle="modal" data-target="#modalCancelacion" id="btnModalCancelacion">Cancelar</a>
              </div>
              -->
              <br>
              <div class="cabecera-cliente">

                <div class="row cabecera-datos">
                  <div class="col-lg-6">
                    <h2 id="razon_social">Razón social:</h2>
                  </div>
                  <div class="col-lg-6">
                    <h2 id="rfc">RFC:</h2>
                  </div>
                  
                </div>
                <br>
                <div class="row cabecera-facturar" >
                  <div class="col-lg-6">
                    <h2 id="serie_folio">Serie/Folio:</h2>
                  </div>
                  <div class="col-lg-6">
                    <h2 id="fecha_timbrado">Fecha timbrado:</h2>
                  </div>
                  
                </div>
                <br>
                <div class="row">
                  <div class="col-lg-6">
                    <h2 id="estatus">Estatus:</h2>
                  </div>
                </div>
              </div>
              <div class="table-responsive">
                <table class="table stripe" id="tblDetalleFactura" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Clave</th>
                      <th>Descripción</th>
                      <th>Unidad de medida</th>
                      <th>Cantidad</th>
                      <th>Precio unitario</th>
                      <th>Importe</th>
                    </tr>
                  </thead>
                 
                  <tbody>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th>Subtotal:</th>
                      <th id="subtotal"></th>
                    </tr>
                    <tr>
                      <th class="not_border_top"></th>
                      <th class="not_border_top"></th>
                      <th class="not_border_top"></th>
                      <th class="not_border_top"></th>
                      <th>Impuestos:</th>
                      <th id="impuestos"></th>
                    </tr>
                    <tr>
                      <th class="not_border_top"></th>
                      <th class="not_border_top"></th>
                      <th class="not_border_top"></th>
                      <th class="not_border_top"></th>
                      <th>Total neto:</th>
                      <th id="total"></th>
                    </tr>
                  </tfoot>

                </table>
                <div>

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
        $rutaf = "../";
        require_once('../footer.php');
      ?>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

    <!-- Modal s  end email client -->
    <div class="modal fade" id="enviarFactura" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Enviar factura por email</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" id="enviarDestinatarios">
            <form id="formEnviarEmail">
              <div class="form-group">
                <label for="txtDestino">Destinatario:</label>
                <a href="#" style="float: right;margin-left: 1%;" data-toggle="tooltip" data-placement="top" title="Agregar más destinatarios" id="agregar_destinatarios"><i class="far fa-plus-square"></i></a>
                <a href="#" style="float: right" data-toggle="tooltip" data-placement="top" title="Eliminar más destinatarios" id="eliminar_destinatarios"><i class="far fa-minus-square"></i></i></a>
                
                <input type="text" class="form-control" name="txtDestino" id="txtDestino" required="">
                <div class="invalid-feedback" id="invalid-destino">Debe ingresar al menos un email destinatario.</div>
              </div>
            </form>
          </div>
          <div class="modal-footer justify-content-center">
            <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal"
              id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="button" class="btnesp espAgregar float-right" name="btnAgregar" id="btnEnviarFactura"><span
                class="ajusteProyecto">enviar</span></button>
          </div>
        </div>
      </div>
    </div>
    <!-- End modal send email client -->

    <div class="modal fade" id="modalCancelacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel">Cancelación</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="dataCancelacion">
              <div class="form-group">
                <label for="txtDestino">Destinatario:</label>
                <input type="email" class="form-control" name="txtDestinoCancel" id="txtDestinoCancel" required="">
                <div class="invalid-feedback" id="invalid-destinoCancel">Debe ingresar un email destinatario.</div>
              </div>
              <div class="form-group">
                <label for="txtDestino">Motivo cancelación:</label>
                <textarea class="form-control" name="txtMotivoCancelacion" id="txtMotivoCancelacion" cols="30" rows="2" required=""></textarea>
                <div class="invalid-feedback" id="invalid-motivoCancelacion">Debe ingresar un motivo de cancelación.</div>
              </div>
            </form>
          </div>
          <div class="modal-footer justify-content-center">
            <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal"
              id="btnCancelarActualizacion"><span class="ajusteProyecto">Cancelar</span></button>
            <button type="button" class="btnesp espAgregar float-right" name="btnAgregar" id="cancelar_factura"><span
                class="ajusteProyecto">Cancelar</span></button>
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


  <script src="js/detalle_remision.js"></script>
  <script>
  
  </script>
  <script> var ruta = "../";</script>
  <script src="../../js/sb-admin-2.min.js"></script>


</body>

</html>