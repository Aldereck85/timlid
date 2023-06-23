<?php
session_start();
require_once '../../../../include/db-conn.php';
$user = $_SESSION["Usuario"];
$PKEmpresa = $_SESSION["IDEmpresa"];

$PKVenta = $_GET["vd"];
$token = $_SESSION['token_ld10d'];

$stmt = $conn->prepare("SELECT empresa_id, FKEstatusVenta FROM ventas_directas WHERE PKVentaDirecta = :id");
$stmt->bindValue(':id', $PKVenta, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch();

$GLOBALS["PKVentaDirecta"] = $row['empresa_id'];
$GLOBALS["PKEstatusVenta"] = $row['FKEstatusVenta'];

$stmt2 = $conn->prepare("SELECT  c.Email, u.usuario as email, dcc.Email as contacto  FROM ventas_directas vd 
inner join clientes c on vd.FKCliente=c.PKCliente 
inner join usuarios u on vd.FKUsuarioCreacion=u.id 
LEFT JOIN dato_contacto_cliente dcc on vd.FKCliente = dcc.FKCliente
WHERE PKVentaDirecta = :id");
$stmt2->bindValue(':id', $PKVenta, PDO::PARAM_INT);
$stmt2->execute();
$row2 = $stmt2->fetchAll();
$Email = [];
$EmailEnvio;
$EmailIn = false;
foreach ($row2 as $r) {
  if (isset($r['Email']) && !$EmailIn) {
    //$Email = $row2['Email'];
    array_push($Email, $r['Email']);
    $EmailIn = true;
  }
  if (isset($r['email'])) {
    $EmailEnvio = $r['email'];
  }
  if (isset($r['contacto'])) {
    //$Email = $row2['contacto'];
    array_push($Email, $r['contacto']);
  }
}

$query = sprintf("select t.id,concat('TPV-',t.folio) folio,t.estatus from relacion_tickets_ventas rtv
                    inner join ticket_punto_venta t on rtv.ticket_id = t.id
                    where rtv.venta_id = :id");
$stmt = $conn->prepare($query);
$stmt->bindValue(":id", $PKVenta);
$stmt->execute();

$data_ticket = $stmt->fetchAll();

$ticket_id = "";
$ticket_folio = "";
$ticket_estatus = "";
if (count($data_ticket)) {
  $ticket_id =  $data_ticket[0]['id'];
  $ticket_folio = $data_ticket[0]['folio'];
  $ticket_estatus = $data_ticket[0]['estatus'];
}
if (isset($_SESSION["Usuario"])) {
  require_once '../../../../include/db-conn.php';
  $user = $_SESSION["Usuario"];

  if ($GLOBALS["PKVentaDirecta"] != $PKEmpresa) {
    header("location:../../../ventas_directas/catalogos/ventas/");
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
  <meta http-equiv="Last-Modified" content="0">
  <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <title>Timlid | Ver Venta</title>

  <!-- ESTILOS -->
  <link href="../../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../style/agregar_entrada.css" rel="stylesheet">
  <link href="../../../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../../../vendor/datatables/responsive.dataTables.css" rel="stylesheet">
  <link href="../../../../vendor/datatables/buttons.dataTables.css" rel="stylesheet">
  <link href="../../../../css/stylesNewTable.css" rel="stylesheet">
  <link href="../../../../css/styles.css" rel="stylesheet">
  <link href="../../../../css/croppie.css" rel="stylesheet">
  <link href="../../../../css/sweetalert2.css" rel="stylesheet">
  <link href="../../../../css/notificaciones.css" rel="stylesheet">
  <link href="../../style/ventas.css" rel="stylesheet">
  <link href="../../../../css/lobibox.min.css" rel="stylesheet">
  <link href="../../css/slimselect.min.css" rel="stylesheet">
  <!-- JS -->
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
  <script src="../../../../js/sweet/sweetalert2.js"></script>
  <script src="../../../../js/notificaciones_timlid.js" charset="utf-8"></script>
  <script src="../../js/ventas.js" charset="utf-8"></script>
  <script src="../../js/ver_ventass.js" charset="utf-8"></script>
  <script src="../../../../js/numeral.min.js"></script>
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
        $icono = 'ICONO-NOTA-DE-VENTA-AZUL.svg';
        $titulo = 'Ver venta';
        $backIcon = true;
        require_once $rutatb . 'topbar.php';
        ?>

        <input type="hidden" id="txtUsuario" value="<?= $_SESSION['PKUsuario']; ?>">
        <input type="hidden" id="txtRuta" value="<?= $ruta; ?>">
        <input type="hidden" id="txtEdit" value="<?= $ruteEdit; ?>">
        <input type="hidden" id="txtPantalla" value="13">
        <input type="hidden" id="txtPKVenta" value="<?= $PKVenta ?>">
        <input type="hidden" id="txtPKVentaEncrip" value="">
        <input type="hidden" name="csr_token_7ALF1" id="csr_token_7ALF1" value="<?= $token ?>">
        <input type="hidden" name="txtIdTicketHide" id="txtIdTicketHide" value="<?= $ticket_id; ?>">
        <input type="hidden" name="txtFolioTicketHide" id="txtFolioTicketHide" value="<?= $ticket_folio; ?>">
        <input type="hidden" name="txtEstatusTicketHide" id="txtEstatusTicketHide" value="<?= $ticket_estatus; ?>">

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <div class="row">
            <div class="col-lg-12">

              <!-- Basic Card Example -->
              <div class="card shadow mb-4 nav-link" id="bodyUp">
                <div class="card-body">
                  <span>
                    <div class="d-flex">
                      <span id="isPermissionsFacturar">
                      </span>
                      <div>
                        <span data-toggle="modal" data-target="#datos_envio" class="btn-table-custom btn-table-custom--blue-lightest"><img style="width:1.5rem; vertical-align: top;" src="../../../../img/cotizaciones_nuevos_iconos/ICONO-ENVIAR AZUL CLARO NVO-01.svg"></img>Enviar</span>
                      </div>
                      <div id="btnEditar"></div>
                      <div>
                        <span data-toggle="modal" class="btn-table-custom btn-table-custom--turquoise" name="btnDescargarOC" onclick="descargarVentaDirecta();"><img style="width:1.5rem; vertical-align: top;" src="../../../../img/cotizaciones_nuevos_iconos/ICONO-DESCARGAR AZUL OSCURO NVO-01.svg"></img>Descargar</span>
                      </div>
                      <div id="btnCancelar">
                      </div>
                      <div id="btnEliminar">
                      </div>
                      <div id="btnCopiar">
                      </div>
                      <div id="btnAceptar"></div>
                      <div id="link-recepecion-pagos" style="margin-left: auto;">
                      </div>
                    </div>
                  </span>
                  <span id="alertas"> </span>
                  <div class="row">
                    <div class="col-lg-12">
                      <form action="" method="post" id="frmVentaDirectaEdit">
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-12">
                            </div>
                          </div>
                        </div>
                        <br>
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-3 textData">
                              <p><b class="textBlue">Referencia: </b><span id="txtReferencia"></span></p>
                              <p><b class="textBlue">Sucursal: </b><span id="txtSucursal"></span></p>
                              <p><b class="textBlue">Condición de pago: </b><span id="txtCondicionPago"></span></p>
                              <p><b class="textBlue">Moneda: </b><span id="txtmoneda"></span></p>
                            </div>
                            <div class="col-lg-3 textData">
                              <p><b class="textBlue">Cliente: </b><span id="txtCliente"></span></p>
                              <p><b class="textBlue">Domicilio de entrega: </b><span id="txtDomi"></span></p>
                              <p><b class="textBlue">Vendedor: </b><span id="txtVendedor"></span></p>
                            </div>
                            <div class="col-lg-3 textData">
                              <b class="textBlue" for="fe">Fecha emisión: </b>
                              <div id="fe"><span id="txtFechaEmision"></span></div>
                              <p></p>
                              <b class="textBlue" for="fv">Fecha vencimiento: </b>
                              <div id="fv"><span id="txtFechaEstimada"></span></div>
                              <p></p>
                              <span id="orderPedidoID"></span>
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
                                  <table class="table" id="tblListadoVentasDirectasEdit" width="100%" cellspacing="0">
                                    <thead>
                                      <tr>
                                        <th>ID</th>
                                        <th>Clave/Producto</th>
                                        <th>Cantidad</th>
                                        <th>Unidad de medida</th>
                                        <th>Precio unitario</th>
                                        <th>Impuestos</th>
                                        <th>Importe</th>
                                        <th>Existencias</th>
                                        <th></th>
                                      </tr>
                                    </thead>

                                  </table>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-12" style="float:right">
                            <table class="table table-hover" ALIGN="right" style="width: 25%;">
                              <tfoot>
                                <tr>
                                  <th style="color: var(--color-primario);"><b>Subtotal:</b></th>
                                  <td style="color: var(--color-primario);">$ <span id="Subtotal">0.00</span>
                                  </td>
                                  <th style="width:60px;"></th>
                                </tr>
                                <tr>
                                  <th style="color: var(--color-primario);"><b>Impuestos:</b></th>
                                  <td id="impuestos"></td>
                                  <th></th>
                                </tr>
                                <tr class="total redondearAbajoIzq">
                                  <th style="color: var(--color-primario);" class="redondearAbajoIzq"><b>Total:</b></th>
                                  <td style="color: var(--color-primario);"><b>$ <span id="Total">0.00</span></b></td>
                                  <th></th>
                                </tr>
                              </tfoot>
                            </table>
                          </div>
                          <div class="row my-3">
                            <div class="col-lg-6 text-center">
                              <b>Nota Interna:</b> <br><span id="NotasInternas"></span>
                            </div>
                            <div class="col-lg-6 text-center">
                              <b>Nota Cliente:</b> <br><span id="NotasCliente"></span>
                            </div>
                          </div>
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
  <script src="../../../../js/lobibox.min.js"></script>
  <script>
    loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
    setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit')
      .val());
  </script>
</body>

</html>

<!--DELETE MODAL SLIDE VENTAS DIRECTAS-->
<div class="modal fade" id="eliminar_VentaDirecta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">¿Desea eliminar el registro de este?</h5>
          <button class="close text-light" type="button" data-dismiss="modal" aria-label="Close">
            x
          </button>
        </div>
        <div>
          <input type="hidden" name="txtVentaDirectaIDD" id="txtVentaDirectaIDD">
          <br>
          <label for="usr" style="margin-left: 80px!important;">Se eliminará la venta con los siguientes
            datos:</label>
        </div>

        <div class="form-group col-md-6">
          <label for="usr">Referencia:</label>
        </div>
        <div class="form-group col-md-12">
          <input type="text" style="border:none!important;" class="form-control" maxlength="50" id="txtReferenciaD" name="txtReferenciaD" required readonly>
        </div>

        <div class="modal-body" style="font-size: 10 px!important; color: red;">Esta acción no podrá deshacerse</div>
        <div class="modal-footer d-flex justify-content-end">
          <button class="btn-custom btn-custom--border-blue btnCancelarActualizacion" type="button" data-dismiss="modal"><span class="ajusteProyecto">Cancelar</span></button>
          <button type="submit" onclick="obtenerEliminar($('#txtVentaDirectaIDD').val());" class="btn-custom btn-custom--blue"><span class="ajusteProyecto" data-dismiss="modal">Eliminar</span></button>
        </div>
      </form>
    </div>
  </div>
</div>
<!--END DELETE MODAL SLIDE VENTAS DIRECTAS-->

<!-- Modal para copiar venta -->
<div class="modal fade" id="copyVenta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">¿Desea copiar los datos de esta venta para crear una nueva?</h5>
          <button class="close text-light" type="button" data-dismiss="modal" aria-label="Close">
            x
          </button>
        </div>
        <div class="modal-footer d-flex justify-content-end">
          <button class="btn-custom btn-custom--border-blue btnCancelarActualizacion" type="button" data-dismiss="modal"><span class="ajusteProyecto">Cancelar</span></button>
          <button type="submit" onclick="duplicarVenta(<?= $PKVenta ?>);" class="btn-custom btn-custom--blue"><span class="ajusteProyecto" data-dismiss="modal">Copiar</span></button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Datos envio -->
<div id="datos_envio" class="modal fade">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form action="#" method="POST">
        <input type="hidden" name="txtId" id="txtId" value="<?= $PKVenta; ?>">
        <div class="modal-header">
          <h4 class="modal-title">Datos de envio</h4>
          <button type="button" class="close text-light" data-dismiss="modal" aria-hidden="true">x</button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="form-group col-lg-12">
              <label for="">De: </label>
              <input class="form-control" type="email" name="txtOrigen" id="txtOrigen" value="<?= $EmailEnvio; ?>" required>
            </div>
          </div>

          <div class="row">
            <div class="form-group col-lg-12">
              <label for="txtEmail">Para: </label>
              <select name="txtDestino" id="txtDestino" required multiple>
                <option data-placeholder="true"></option>
                <?php
                foreach ($Email as $e) {
                  //echo ('<input type="text" id="notifi" value="' . $e . '">');
                  echo ('<option value=' . $e . '>' . $e . '</option>');
                }
                ?>
                <!--  -->
              </select>
              <div class="invalid-feedback" id="invalid-emailDestino">Ingresa el correo electrónico de destino.</div>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-lg-12">
              <label for="">Asunto: </label>
              <input class="form-control" type="text" name="txtAsunto" id="txtAsunto" value="Nueva Venta" required>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-lg-12">
              <label for="">Mensaje: </label>
              <textarea class="form-control" name="txaMensaje" id="txaMensaje" rows="5" cols="80"></textarea>
            </div>
          </div>

        </div>
        <div align="center">
          <img src="../../../../img/chat/loading.gif" id="loading" width="30px" style="position: absolute; bottom: 73px;left: 50%;text-align: center;display: none;">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn-custom btn-custom--border-blue" data-dismiss="modal" name="button" id="cancelarVenta"><i class="fas fa-times"></i> Cancelar</button>
          <button class="btn-custom btn-custom--blue" type="button" name="button" id="enviarVenta"><i class="fas fa-envelope"></i> Enviar</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- Fin de Modal Datos envio -->