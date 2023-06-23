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

  //redirecciona a la pantalla del detalle de nc venta
  $stmt = $conn->prepare("SELECT tipo_nc from notas_cuentas_por_cobrar where id = :ncv");
                $stmt->bindValue(":ncv",$_GET['idNota']);
                $stmt->execute();
                $row = $stmt->fetch();
                if(isset($row['tipo_nc'])){
                  if($row['tipo_nc'] == 2){
                    header("location:detalle_notaVenta.php?idNota=".$_GET['idNota']);
                  }
                }

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

    .not_border_top{
      border-top: none !important;
    }

    .border_top{
      border-top: 1px solid #edeff5 !important;
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
            
            <!--<div class="permission-view-add"></div>-->

            
            
            
            <div class="card-body">
              <input type="hidden" name="idNota" id="idNota" value="<?=$_GET['idNota'];?>">
              <div class="btn-downloads">  
                <a href="functions/download_pdf.php?value=<?=$_GET['idNota'];?>" class="btn-table-custom btn-table-custom--turquoise" target="_blank"><img style="width:1.5rem; vertical-align: top;" src="../../img/cotizaciones_nuevos_iconos/ICONO-DESCARGAR AZUL OSCURO NVO-01.svg"> Descargar pdf</a>
                <a href="functions/download_xml.php?value=<?=$_GET['idNota'];?>" class="btn-table-custom btn-table-custom--turquoise" target="_blank"><img style="width:1.5rem; vertical-align: top;" src="../../img/cotizaciones_nuevos_iconos/ICONO-DESCARGAR AZUL OSCURO NVO-01.svg"> Descargar xml</a>
                <a href="functions/download_zip.php?value=<?=$_GET['idNota'];?>" class="btn-table-custom btn-table-custom--turquoise" target="_blank"><img style="width:1.5rem; vertical-align: top;" src="../../img/cotizaciones_nuevos_iconos/ICONO-DESCARGAR AZUL OSCURO NVO-01.svg"> Descargar zip</a>
                <a href="#" id="send_email" data-toggle="modal" data-target="#enviarFactura" class="btn-table-custom btn-table-custom--turquoise"><img style="width:1.5rem; vertical-align: top;" src="../../img/cotizaciones_nuevos_iconos/ICONO-ENVIAR AZUL CLARO NVO-01.svg"></img> Enviar por email</a>
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
                
                <!-- <div class="row">
                  <div class="col-lg-6">
                  <h4 id="referencia"></h4>
                  </div>
                </div> -->
      
              </div>
              <br>
              <div class="table-responsive">
                <table class="table stripe" id="tblDetalleNota" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Clave</th>
                      <th>Descripción</th>
                      <th>Unidad de medida</th>
                      <th>Cantidad</th>
                      <th></th>
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
                      <th class="border_top" style="color: var(--color-primario)">Subtotal:</th>
                      <td class="border_top" style="color: var(--color-primario)" id="subtotal"></td>
                    </tr>
                    <tr>
                      <th class="not_border_top"></th>
                      <th class="not_border_top"></th>
                      <th class="not_border_top"></th>
                      <th class="not_border_top"></th>
                      <th class="border_top" style="color: var(--color-primario)">Impuestos:</th>
                      <td class="border_top" style="color: var(--color-primario)" id="impuestos"></td>
                    </tr>
                    <tr>
                      <th class="not_border_top"></th>
                      <th class="not_border_top"></th>
                      <th class="not_border_top"></th>
                      <th class="not_border_top"></th>
                      <th class="border_top" style="color: var(--color-primario)">Total neto:</th>
                      <td class="border_top" style="color: var(--color-primario)" id="total"></td>
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
      require_once 'modal_alert_confirm.php';
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

                <?php
                  require_once 'modal_alert_confirm.php';
                ?>

<!--     <div class="modal fade" id="modalCancelacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                <div class="invalid-feedback" id="invalid-motivoCancelacion">Debe ingresar un motivo de cancelación.</div>
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
 -->
  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>


  <script src="js/ver_detalle_v1.js"></script>
  <script>
  
  </script>
  <script> var ruta = "../";</script>
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/scripts.js"></script>
  <script src="../../js/slimselect.min.js"></script>

</body>

</html>