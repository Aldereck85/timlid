<?php

  //echo $_REQUEST['idFactura'];
  $screen = 14;
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
  <title>Timlid | Detalle Factura</title>

  <!-- ESTILOS -->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">
  <link href="../../css/stylesNewTable.css" rel="stylesheet">
  <link href="../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../css/sweetalert2.css" rel="stylesheet">
  <link href="../../css/lobibox.min.css" rel="stylesheet">
  <!--<link href="../../css/notificaciones.css" rel="stylesheet">-->
  <link href="css/detalle_factura.css" rel="stylesheet">
  <!-- JS -->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../vendor/moment/moment.min.js"></script>
  <script src="../../vendor/jszip/jszip.min.js"></script>
  <script src="../../js/jquery.redirect.min.js"></script>
  <script src="../../js/notificaciones_timlid.js" charset="utf-8"></script>
  <script src="../../js/mdtimepicker.min.js"></script>
  <script src="../../js/permisos_usuario.js"></script>
  <script src="../../js/lobibox.min.js"></script>

</head>

<body id="page-top" data-screen="16">
  <div id=loader></div>
  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
        $icono = 'ICONO-FACTURACION-AZUL.svg';
        $titulo = 'Detalle Factura';
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
              <input type="hidden" name="txtIdFactura" id="txtIdFactura" value="<?=$_REQUEST['idFactura'];?>">
              <input type="hidden" name="txtClient_id" id="txtClient_id">
              <div class="btn-downloads">  
                <a href="php/download_pdf.php?value=<?=$_REQUEST['idFactura'];?>" class="btn-table-custom btn-table-custom--turquoise" target="_blank"><img style="width:1.5rem; vertical-align: top;" src="../../img/cotizaciones_nuevos_iconos/ICONO-DESCARGAR AZUL OSCURO NVO-01.svg"> Descargar</a>
                <a href="php/download_xml.php?value=<?=$_REQUEST['idFactura'];?>" class="btn-table-custom btn-table-custom--turquoise" target="_blank"><img style="width:1.5rem; vertical-align: top;" src="../../img/cotizaciones_nuevos_iconos/ICONO-DESCARGAR AZUL OSCURO NVO-01.svg"> Descargar xml</a>
                <a href="php/download_zip.php?value=<?=$_REQUEST['idFactura'];?>" class="btn-table-custom btn-table-custom--turquoise" target="_blank"><img style="width:1.5rem; vertical-align: top;" src="../../img/cotizaciones_nuevos_iconos/ICONO-DESCARGAR AZUL OSCURO NVO-01.svg"> Descargar zip</a>
                <a href="#" id="send_email" data-toggle="modal" data-target="#enviarFactura" class="btn-table-custom btn-table-custom--turquoise"><img style="width:1.5rem; vertical-align: top;" src="../../img/cotizaciones_nuevos_iconos/ICONO-ENVIAR AZUL CLARO NVO-01.svg"> Enviar por email</a>
                <a href="#" class="btn-table-custom btn-table-custom--red" id="btnModalCancelacion"><img style="width:1.5rem; vertical-align: top;" src="../../img/cotizaciones_nuevos_iconos/ICONO-CANCELAR ROJO NVO-01.svg"> Cancelar</a> 
                <b><span class="btn-table-custom--turquoise" id="estatus"></span></b>
              </div>
          
              <br><br>
              <div class="cabecera-cliente">
                <div class="row">
                  <div class="col-lg-3 textData">
                    <p><b class="textBlue">Serie/Folio: </b><span id="serie_folio"></span></p>
                    <div id="referencia"></div>
                  </div>
                  <div class="col-lg-3 textData">
                    <div id="razon_social"></div>
                    <p><b class="textBlue">RFC: </b><span id="rfc"></span></p>
                    <b class="textBlue">Vendedor: </b><span id="vendedor"></span>
                    <div id="enable_edit_seller"></div>
                  </div>
                  <div class="col-lg-3 textData">
                    <b class="textBlue" for="ft">Fecha timbrado: </b><div id="ft"><span id="fecha_timbrado"></span></div><p></p>
                    <b class="textBlue" for="fecha_vencimiento">Fecha de vencimiento: </b><div id="fecha_vencimiento"></div>
                    <div id="enable_edit_expiration_date"></div><p></p>
                    <div id="fech_canc" style="display: none;">
                      <b class="textBlue" for="fc">Fecha cancelación: </b><div id="fc"><span id="fecha_cancelacion"></span></div><p></p>
                    </div>
                  </div>
                  <div class="col-lg-3 textData">
                    <p><b class="textBlue">Estado: </b><span id="estado"></span></p>
                    <h2><b class="textBlue" for="totalFactura">Importe Total: </b><div id="ft"><b><span id="totalFactura"></span></b></div></h2>
                  </div>
                </div>
              </div>
              <br>
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
                      <th class="border_top"></th>
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
                      <th class="not_border_top"></th>
                      <th class="border_top text-center" style="color: var(--color-primario)">Impuestos:</th>
                      <td class="border_top text-center" style="color: var(--color-primario)" id="impuestos"></td>
                    </tr>
                    <tr>
                      <th class="not_border_top"></th>
                      <th class="not_border_top"></th>
                      <th class="not_border_top"></th>
                      <th class="not_border_top"></th>
                      <th class="border_top text-center" style="color: var(--color-primario)">Total neto:</th>
                      <td class="border_top text-center" style="color: var(--color-primario)" id="total"></td>
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
                <label for="cmbMotivoCancelacion">Motivo cancelación:</label>
                <select name="cmbMotivoCancelacion" id="cmbMotivoCancelacion">
                  <option data-placeholder='true'></option>
                  <option value="01">Comprobante emitido con errores con relación</option>
                  <option value="02">Comprobante emitido con errores sin relación</option>
                  <option value="03">No se llevó a cabo la operación</option>
                  <option value="04">Operación nominativa relacionada en la factura global</option>
                </select>
                <!--<textarea class="form-control" name="txtMotivoCancelacion" id="txtMotivoCancelacion" cols="30" rows="2" required=""></textarea>-->
                <div class="invalid-feedback" id="invalid-motivoCancelacion">Debe ingresar un motivo de cancelación.</div>
              </div>
              <div id="relation-uuid">
                <div class="form-group">
                  <label for="cmbRelationInvoice">Factura a relacionar:</label>
                  <select name="cmbRelationInvoice" id="cmbRelationInvoice"></select>
                </div>
              </div>
                
            </form>
          </div>
          <div class="modal-footer justify-content-center">
            <button type="button" class="btnesp first espCancelar btnCancelarActualizacion" data-dismiss="modal"
              id="btnCancelarActualizacion"><span class="ajusteProyecto">Cerrar</span></button>
            <button type="button" class="btnesp espAgregar float-right" name="btnAgregar" id="cancelar_factura"><span
                class="ajusteProyecto">Proceder a cancelar</span></button>
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


  <script src="js/detalle_factura.js"></script>
  <script>
  
  </script>
  <script> var ruta = "../";</script>
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/scripts.js"></script>
  <script src="../../js/slimselect.min.js"></script>

</body>

</html>