<?php
session_start();

$jwt_ruta = "../../";
require_once '../jwt.php';

if (isset($_SESSION["Usuario"])) {
  require_once '../../include/db-conn.php';
  $empresa_id = -1;
  if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare('SELECT ops.id_orden_pedido_empresa, 
                                   so.sucursal as sucursal_origen, 
                                   sd.sucursal as sucursal_destino,
                                   DATE_FORMAT(ops.fecha_captura, "%d/%m/%Y %H:%i:%s") as fecha_ingreso, 
                                   u.usuario, 
                                   ops.observaciones,
                                   ops.empresa_id,
                                   isps.folio_salida,
                                   ieps.folio_entrada
                            FROM orden_pedido_por_sucursales as ops 
                                LEFT JOIN sucursales as so ON so.id = ops.sucursal_origen_id 
                                LEFT JOIN sucursales as sd ON sd.id = ops.sucursal_destino_id
                                LEFT JOIN usuarios as u ON u.id = ops.usuario_creo_id
                                LEFT JOIN inventario_salida_por_sucursales isps ON isps.orden_pedido_id = ops.id
                                LEFT JOIN inventario_entrada_por_sucursales ieps ON ieps.orden_pedido_id = ops.id
                            WHERE ops.empresa_id = ' . $_SESSION['IDEmpresa'] . ' AND ops.id = :id');
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch();
    $nopedido = $row['id_orden_pedido_empresa'];
    $FechaIngreso = $row['fecha_ingreso'];
    $observaciones = $row['observaciones'];
    $sucursal_origen = $row['sucursal_origen'];
    $sucursal_destino = $row['sucursal_destino'];
    $empresa_id = $row['empresa_id'];
    $folio_salida = $row['folio_salida'];
    $folio_entrada = $row['folio_entrada'];
  }
} else {
  header("location:../dashboard.php");
}

if ($empresa_id != $_SESSION['IDEmpresa']) {
  header("location:./");
}

$token = $_SESSION['token_ld10d'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" type="image/png" href="../../img/header/bluTimlid.png">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Timlid | Ver traspaso</title>

  <!-- ESTILOS -->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../css/bootstrap-clockpicker.min.css" rel="stylesheet" type="text/css">
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="../../vendor/datatables/responsive.dataTables.css" rel="stylesheet">
  <link rel="stylesheet" href="../../vendor/datatables/buttons.dataTables.css">
  <link href="../../css/stylesNewTable.css" rel="stylesheet">
  <link href="../../css/lobibox.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../css/notificaciones.css">
  <link href="../../css/timeline.css" rel="stylesheet">
  <link href="../../css/slimselect.min.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">
  <link href="style/detalletraspaso.css" rel="stylesheet">
  <!-- JS -->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../js/validaciones.js"></script>
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/scripts.js"></script>
  <script src="../../js/bootstrap-clockpicker.min.js"></script>
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../vendor/jszip/jszip.min.js"></script>
  <script src="../../js/jquery.redirect.min.js"></script>
  <script src="../../js/lobibox.min.js"></script>
  <script src="../../js/sweet/sweetalert2.js"></script>
  <script src="../../js/notificaciones_timlid.js" charset="utf-8"></script>
  <script src="js/detallepedido.js" charset="utf-8"></script>
  <script src="../../js/slimselect.min.js"></script>
</head>

<body id="page-top" class="sidebar-toggled">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
    $icono = 'ICONO-PEDIDOS-AZUL.svg';
    $titulo = 'Traspaso';

    $ruta = "../";
    $ruteEdit = "$ruta.central_notificaciones/";
    require_once $ruta . 'menu3.php';
    ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <input type="hidden" id="txtUsuario" value="<?= $_SESSION['PKUsuario']; ?>">
      <input type="hidden" id="txtRuta" value="<?= $ruta; ?>">
      <input type="hidden" id="txtEdit" value="<?= $ruteEdit; ?>">
      <input type="hidden" id="txtPantalla" value="13">
      <input type="hidden" id="txtFolioEntrada" value="<?= $folio_entrada ?>">

      <!-- Main Content -->
      <div id="content">

        <?php
        $rutatb = "../";
        $backIcon = true;
        require_once $rutatb . 'topbar.php'; ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">
          <!-- Basic Card Example -->
          <div class="card shadow mb-4">
            <div class="card-body">
              <div class="d-flex">
                <div>
                  <span class="btn-table-custom btn-table-custom--turquoise" name="btnDescargar" onclick="descargarSalida(<?= $nopedido ?>);"><img style="width:1.5rem; vertical-align: top;" src="../../img/cotizaciones_nuevos_iconos/ICONO-DESCARGAR AZUL OSCURO NVO-01.svg"></img> Descargar traspaso</span>
                </div>
                <!-- <div>
                  <span class="btn-table-custom btn-table-custom--turquoise" name="btnDescargar" onclick="descargarEntrada();"><img style="width:1.5rem; vertical-align: top;" src="../../img/cotizaciones_nuevos_iconos/ICONO-DESCARGAR AZUL OSCURO NVO-01.svg"></img> Descargar entrada</span>
                </div>
                <span class="btn-table-custom btn-table-custom--red" onclick="cancelarOrdenPedidoF('<?= $id; ?>');"><img style="width:1.5rem; vertical-align: top;" src="../../img/cotizaciones_nuevos_iconos/ICONO-CANCELAR ROJO NVO-01.svg"></img> Cancelar</span> -->
              </div>
            </div>
            <div class="card-body">
              <div class="row my-3">
                <div class="col-lg-3 textData">
                  <p><b class="textBlue">No. Traspaso:</b> <?php echo sprintf("%011d", $nopedido); ?></p>
                </div>
                <div class="col-lg-3 textData">
                    <p><b class='textBlue'>Sucursal origen:</b> <?= $sucursal_origen ?></p>
                </div>
                <div class="col-lg-3 textData">
                    <p><b class='textBlue'>Sucursal destino:</b> <?= $sucursal_destino ?></p>
                </div>
                <div class="col-lg-3 textData">
                  <b class="textBlue">Fecha de ingreso:</b> <?= $FechaIngreso; ?>
                </div>
                <hr class="my-3" style="width: 100%">
              </div>
            </div>

            <div class="card-body" id="mostrarOrdenPedido">
              <div class="row">
                <div class="col-lg-12">
                  <div class="table-responsive redondear">
                    <table class="table table-sm" id="cotizacion">
                      <thead class="header-color">
                        <tr>
                          <th>Clave/Producto</th>
                          <th>Unidad de medida</th>
                          <th>Cantidad</th>
                          <th>Lote</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody id="lstProductos">
                        <?php
                        $stmt = $conn->prepare("SELECT p.PKProducto as id,
                                                    p.Nombre as nombre,
                                                    p.ClaveInterna  as clave,
                                                    csu.Descripcion as unidadMedida,
                                                    epp.numero_lote as lote,
                                                    isps.cantidad as cantidad
                                                from inventario_salida_por_sucursales isps
                                                  inner join existencia_por_productos epp on ifnull(isps.numero_lote,'') = ifnull(epp.numero_lote,'') and isps.clave = epp.clave_producto
                                                  inner join productos p on isps.clave = p.ClaveInterna and p.empresa_id = :empresa
                                                  left join info_fiscal_productos ifp on p.PKProducto = ifp.FKProducto
                                                  left join claves_sat_unidades csu on ifp.FKClaveSATUnidad = csu.PKClaveSATUnidad
                                                  inner join orden_pedido_por_sucursales opps on isps.orden_pedido_id = opps.id and epp.sucursal_id = opps.sucursal_origen_id
                                                  inner join sucursales so on opps.sucursal_origen_id = so.id
                                                where isps.folio_salida = :folio and so.empresa_id = :empresa2
                                                group by p.PKProducto, epp.numero_lote");
                        $stmt->execute(array(':folio' => $nopedido . '-1', ':empresa' => $_SESSION['IDEmpresa'], ':empresa2' => $_SESSION['IDEmpresa']));
                        $numero_productos = $stmt->rowCount();
                        $rowp = $stmt->fetchAll();

                        foreach ($rowp as $rp) {

                          if ($rp['unidadMedida'] == "") {
                            $ClaveUnidad = "Sin unidad";
                          } else {
                            $ClaveUnidad = $rp['unidadMedida'];
                          }

                        ?>

                          <tr id="idProducto_<?= $rp['id'] ?>">
                            <td style="text-align: left;" id="nombreproducto_<?= $rp['id'] ?>">
                              <?= $rp['clave'] . ' - ' . $rp['nombre'] ?>
                            </td>
                            <td class="text-center"><?= $ClaveUnidad ?></td>
                            <td class="text-center" id="piezas_<?= $rp['id'] ?>"><?= $rp['cantidad'] ?></td>
                            <td class="text-center" id="lotes_<?= $rp['id'] ?>"><?= $rp['lote'] ?></td>
                            <input type="hidden" id="piezaAnt_<?= $rp['id'] ?>" value="<?= $rp['cantidad'] ?>" />
                            <input type="hidden" name='inp_productos[]' value="<?= $rp['id'] ?>" />
                            <td></td>
                          </tr>
                        <?php

                        } ?>
                      </tbody>
                    </table>
                  </div>

                  <div class="row">
                    <div class="col-lg-12" style="color:#d9534f;display: none;text-align: center;" id="mostrarMensaje">
                      <h2>Ingresa un producto al menos.</h2>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row my-3">
                <div class="col-lg-12">
                  <b>Observaciones:</b> <br><?= $observaciones; ?>
                </div>
              </div>
            </div>


          </div>
          <!-- End of Main Content -->

          <!-- End of Content Wrapper -->

        </div>
        <!-- End of Page Wrapper -->

        <!-- Footer -->
        <?php
          $rutaf = "../";
          require_once '../footer.php';
          ?>
          <!-- End of Footer -->
      </div>
    </div>
  </div>
  <style>
    .textBlue{
      color: var(--azul-mas-oscuro);
    }

    .textData{
      font-size:large;
    }
    
    .custom-color-blue{
      color: #76a5f3;
    }

    .custom-color-green{
      color: #1b8482;
    }
  </style>

  <script>
    $(document).ready(function() {
      var id = <?= $id; ?>;
      var idioma_espanol = {
        "sProcessing": "Procesando...",
        "sLengthMenu": "Mostrar _MENU_ registros",
        "sZeroRecords": "No se encontraron resultados",
        "sEmptyTable": "Ningún dato disponible en esta tabla",
        "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
        "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix": "",
        "sSearch": "Buscar:",
        "sUrl": "",
        "sInfoThousands": ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
          "sFirst": "Primero",
          "sLast": "Último",
          "sNext": "Siguiente",
          "sPrevious": "Anterior"
        },
        "oAria": {
          "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
          "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        }
      }
    });
  </script>

  <script>

    function descargarSalida(folioSalida) {
      folioSalida = folioSalida + '-1';
      let empresa_id = <?php echo $empresa_id; ?>;
      let session_empresa = <?php echo $_SESSION['IDEmpresa']; ?>;
      if (empresa_id == session_empresa) {
        window.location.href = 'functions/descargarSalida.php?folio=' + folioSalida;
      } else {
        Lobibox.notify("error", {
          size: 'mini',
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: 'center top', //or 'center bottom'
          icon: false,
          img: '../../img/timdesk/warning_circle.svg',
          msg: "No tienes permiso para descargar el pedido."
        });
      }

    }

    function descargarEntrada() {
      var folioEntrada = $("#txtFolioEntrada").val();
      let empresa_id = <?php echo $empresa_id; ?>;
      let session_empresa = <?php echo $_SESSION['IDEmpresa']; ?>;
      if (empresa_id == session_empresa) {
        window.location.href = 'functions/descargarEntrada.php?folio=' + folioEntrada;
      } else {
        Lobibox.notify("error", {
          size: 'mini',
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: 'center top', //or 'center bottom'
          icon: false,
          img: '../../img/timdesk/warning_circle.svg',
          msg: "No tienes permiso para descargar el pedido."
        });
      }

    }

    function cancelarOrdenPedidoF(idOrdenPedido) {

      let FKUsuario = <?= $_SESSION['PKUsuario'] ?>;
      let token = $("#csr_token_8UY8N").val();

      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          actions: "d-flex justify-content-around",
          confirmButton: "btn-custom btn-custom--border-blue",
          cancelButton: "btn-custom btn-custom--blue",
        },
        buttonsStyling: false,
      });

      swalWithBootstrapButtons
        .fire({
          title: "¿Desea continuar?",
          text: "Se cancelará el pedido.",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: '<span class="verticalCenter">Cancelar pedido</span>',
          cancelButtonText: '<span class="verticalCenter">Cerrar</span>',
          reverseButtons: false,
        })
        .then((result) => {
          if (result.isConfirmed) {

            $.ajax({
              type: 'POST',
              url: 'functions/cancelarPedido.php',
              data: {
                idOrdenPedido: idOrdenPedido,
                FKUsuario: FKUsuario,
                csr_token_8UY8N: token
              },
              success: function(data) {

                if (data == "exito") {

                  Lobibox.notify("success", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/checkmark.svg',
                    msg: "Se ha cancelado el pedido"
                  });

                  setTimeout(function() {
                    $(location).attr('href', './');
                  }, 4000);

                } else if (data == "fallo-cancelacion") {
                  Lobibox.notify("error", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/warning_circle.svg',
                    msg: "No puedes cancelar un pedido surtido o facturado."
                  });

                } else {

                  Lobibox.notify("error", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/warning_circle.svg',
                    msg: "Ocurrió un error, vuelva intentarlo."
                  });

                }
              }
            });



          } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
          ) {}
        });


    }


    function cerrarOrdenPedidoF(idOrdenPedido) {

      let FKUsuario = <?= $_SESSION['PKUsuario'] ?>;
      let token = $("#csr_token_8UY8N").val();

      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          actions: "d-flex justify-content-around",
          confirmButton: "btn-custom btn-custom--border-blue",
          cancelButton: "btn-custom btn-custom--blue",
        },
        buttonsStyling: false,
      });

      swalWithBootstrapButtons
        .fire({
          title: "¿Desea continuar?",
          text: "Se cerrará el pedido y ya no se podrá modificar.",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: '<span class="verticalCenter">Cerrar pedido</span>',
          cancelButtonText: '<span class="verticalCenter">Cerrar ventana</span>',
          reverseButtons: false,
        })
        .then((result) => {
          if (result.isConfirmed) {

            $.ajax({
              type: 'POST',
              url: 'functions/cerrarPedido.php',
              data: {
                idOrdenPedido: idOrdenPedido,
                FKUsuario: FKUsuario,
                csr_token_8UY8N: token
              },
              success: function(data) {

                if (data == "exito") {

                  Lobibox.notify("success", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/checkmark.svg',
                    msg: "Se ha cerrado el pedido"
                  });

                  setTimeout(function() {
                    $(location).attr('href', './');
                  }, 4000);

                } else if (data == "fallo-cancelacion") {
                  Lobibox.notify("error", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/warning_circle.svg',
                    msg: "No puedes cerrar un pedido surtido o facturado."
                  });

                } else {

                  Lobibox.notify("error", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/warning_circle.svg',
                    msg: "Ocurrió un error, vuelva intentarlo."
                  });

                }
              }
            });



          } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
          ) {}
        });


    }
  </script>
  <script>
    loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
    setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
  </script>
  <script>
    var OrdenPedidoID = '<?php echo $id; ?>';
  </script>
</body>

</html>