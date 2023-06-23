<?php
session_start();

$jwt_ruta = "../../";
require_once '../jwt.php';

if (isset($_SESSION["Usuario"])) {
    require_once '../../include/db-conn.php';
    $empresa_id = -1;
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $stmt = $conn->prepare('SELECT ops.id_orden_pedido_empresa, so.sucursal as sucursal_origen, sd.sucursal as sucursal_destino, c.NombreComercial as cliente, DATE_FORMAT(ops.fecha_captura, "%d/%m/%Y %H:%i:%s") as fecha_ingreso, DATE_FORMAT(ops.fecha_entrega, "%d/%m/%Y") as fecha_entrega, u.usuario, ops.observaciones, ops.numero_cotizacion, ops.numero_venta_directa, ops.empresa_id, ops.manual, CONCAT(emp.Nombres," ",emp.PrimerApellido) as nombre_vendedor FROM orden_pedido_por_sucursales as ops INNER JOIN sucursales as so ON so.id = ops.sucursal_origen_id LEFT JOIN sucursales as sd ON sd.id = ops.sucursal_destino_id LEFT JOIN clientes as c ON c.PKCliente = ops.cliente_id LEFT JOIN usuarios as u ON u.id = ops.usuario_creo_id LEFT JOIN empleados as emp ON emp.PKEmpleado = ops.vendedor_id WHERE ops.empresa_id = '.$_SESSION['IDEmpresa'] . ' AND ops.id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        $nopedido = $row['id_orden_pedido_empresa'];
        $vendedor = $row['nombre_vendedor'];
        $FechaIngreso = $row['fecha_ingreso'];
        $FechaEntrega = $row['fecha_entrega'];
        $cliente = $row['cliente'];
        $observaciones = $row['observaciones'];
        $sucursal_origen = $row['sucursal_origen'];
        $sucursal_destino = $row['sucursal_destino'];
        $numero_cotizacion = $row['numero_cotizacion'];
        $numero_venta_directa = $row['numero_venta_directa'];
        $empresa_id = $row['empresa_id'];
        $email_envio = $row['usuario'];
        $manual = $row['manual'];

    }
} else {
    header("location:../dashboard.php");
}

if($empresa_id != $_SESSION['IDEmpresa']){
  header("location:./");
}

$token = $_SESSION['token_ld10d'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="icon" type="image/png" href="../../img/header/bluTimlid.png">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Timlid | Ver orden de pedido</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../vendor/jquery/jquery.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../js/validaciones.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/scripts.js"></script>
  <script src="../../js/bootstrap-clockpicker.min.js"></script>

  <!-- Page level plugins -->
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../vendor/datatables/buttons.bootstrap4.min.js"></script>
  <script src="../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../vendor/jszip/jszip.min.js"></script>

  <script src="../../js/jquery.redirect.min.js"></script>
  <!-- Custom fonts for this template-->
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../css/bootstrap-clockpicker.min.css" rel="stylesheet" type="text/css">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">
  <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  <link href="../../vendor/datatables/responsive.dataTables.css" rel="stylesheet">
  <link rel="stylesheet" href="../../vendor/datatables/buttons.dataTables.css">
  <link href="../../vendor/datatables/buttons.bootstrap4.min.css" rel="stylesheet">
  <link href="../../css/stylesTable.css" rel="stylesheet">
  <link href="../../css/lobibox.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../css/notificaciones.css">

  <script src="../../js/lobibox.min.js"></script>
  <script src="../../js/sweet/sweetalert2.js"></script>

  <script src="../../js/notificaciones_timlid.js" charset="utf-8"></script>
  <script>
  $(document).ready(function() {
    var id = <?=$id;?>;
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
    $("#tblProductos").dataTable({
        "ajax": "function_VerCotizacion.php?id=" + id,
        "columns": [{
            "data": "No"
          },
          {
            "data": "Clave"
          },
          {
            "data": "Cantidad"
          },
          {
            "data": "Unidad medida"
          },
          {
            "data": "Precio unitario"
          },
          {
            "data": "Importe"
          }
        ],
        "language": idioma_espanol,
        columnDefs: [{
          orderable: false,
          targets: 2
        }],
        responsive: true
      }

    )
  });
  </script>
  <style type="text/css">
  .header-color {
    font-size: 18px;
    color: #fff;
    line-height: 1.4;
    background-color: var(--primario-oscuro);
  }

  .redondear {
    border-radius: 10px;
  }

  table .btn {
    padding: 0 0;
  }

  .table-input {
    height: 20px;
    width: 80%;
    border: none;
    border-bottom: 1px solid #f2f2f2;
    text-align: center;
    background-color: #f2f2f2;
  }

  .table-input:focus {
    text-align: center;
    background-color: #f2f2f2;
  }

  .total {
    color: white;
    background-color: var(--primario-oscuro);
    font-size: 24px;
  }

  .redondearAbajoIzq {
    border-radius: 0px 0px 0px 10px;
  }

  .redondearAbajoDer {
    border-radius: 0px 0px 10px 0px;
  }

  .modificarnumero {
    text-align: center;
    border: 0;
  }
  </style>
  <link href="../../css/timeline.css" rel="stylesheet">
</head>

<body id="page-top" class="sidebar-toggled">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
$icono = '../../img/icons/ICONO COTIZACIONES-01.svg';
$titulo = 'Orden de pedido';
$ruta = "../";
$ruteEdit = "$ruta.central_notificaciones/";
require_once $ruta . 'menu3.php';
?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <?php
$rutatb = "../";
require_once $rutatb . 'topbar.php';?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Orden de pedido</h1>
          </div>


          <div class="row">

            <div class="col-lg-12">

              <!-- Basic Card Example -->
              <div class="card shadow mb-4">
                <div class="card-header">
                  <div class="d-flex justify-content-between flex-wrap">
                    <div>
                      <button data-toggle="modal" data-target="#datos_envio" type="button"
                        class="btn-custom btn-custom--blue-lightest" name="btnAgregarProducto"><i
                          class="far fa-envelope"></i> Enviar</button>
                    </div>
                    <div>
                      <button type="button" class="btn-custom btn-custom--blue-light" name="btnAgregarProducto"
                        onclick="editarOrdenPedido(<?=$id?>);"><i class="fas fa-edit"></i> Editar</button>
                    </div>
                    <div>
                      <button type="button" class="btn-custom btn-custom--yellow" name="btnChat"
                        onclick="mostrarOrdenPedido()"><i class="fas fa-receipt"></i> Orden de pedido</button>
                    </div>
                    <div>
                      <button type="button" class="btn-custom btn-custom--green" name="btnChat"
                        onclick="mostrarBitacora()"><i class="fas fa-book"></i> Bitácora</button>
                    </div>
                    <div>
                      <button data-toggle="modal" type="button" class="btn-custom btn-custom--gray" name="btnDescargar"
                        onclick="descargarOrdenPedido(<?=$id?>);"><i class="fas fa-download"></i> Descargar</button>
                    </div>
                    <?php
                      if($manual == 1){
                        echo '
                              <div>
                                <button data-toggle="modal" type="button" class="btn-custom btn-custom--red" name="btnDescargar"
                                  onclick="cancelarOrdenPedidoF('.$id.');"><i class="fas fa-trash-alt"></i> Cancelar</button>
                              </div>';
                      }else{
                        echo '
                              <div>
                                <button data-toggle="modal" type="button" class="btn-custom btn-custom--red" name="btnDescargar"
                                  onclick="cancelarOrdenPedidoF('.$id.');"><i class="fas fa-trash-alt"></i> Cancelar</button>
                              </div>
                              <div>
                                <button data-toggle="modal" type="button" class="btn-custom btn-custom--orange" name="btnDescargar"
                                  onclick="cerrarOrdenPedidoF('.$id.');"><i class="fas fa-trash-alt"></i> Cerrar</button>
                              </div>';
                      }
                    ?>
                  </div>
                </div>
                <div class="card-body">
                  <div class="row my-3">
                    <div class="col-lg-3 ">
                      <b>No. Pedido:</b> <?php echo sprintf("%011d", $nopedido);?><br>
                      <b>Vendedor:</b> <?=$vendedor;?>
                      <?php
                        if(trim($numero_cotizacion) != ""){
                            echo "<br><b>Numero cotización: </b>".sprintf("%011d", $numero_cotizacion);
                        }
                        if(trim($numero_venta_directa) != ""){
                            echo "<br><b>Núm. venta directa: </b>".sprintf("%011d", $numero_venta_directa);
                        }
                      ?>
                    </div>
                    <div class="col-lg-3">
                      <b>Sucursal origen:</b> <?=$sucursal_origen;?> <br>
                      <b>Sucursal destino:</b> <?=$sucursal_destino;?> <br>
                      <b>Cliente:</b> <?=$cliente;?>
                    </div>
                    <div class="col-lg-3">
                    </div>
                    <div class="col-lg-3">
                      <b>Fecha de ingreso:</b> <?=$FechaIngreso;?> <br>
                      <b>Fecha de entrega:</b> <?=$FechaEntrega;?>
                    </div>
                    <hr class="my-3" style="width: 100%">
                  </div>
                </div>

                <div class="card-body" id="mostrarOrdenPedido">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="table-responsive redondear">
                        <table class="table table-sm" id="cotizacion">
                          <thead class="text-center header-color">
                            <tr>
                              <th>Clave/Producto</th>
                              <th>Unidad de medida</th>
                              <th>Cantidad</th>
                              <th></th>
                            </tr>
                          </thead>
                          <tbody id="lstProductos">
                            <?php
                                $stmt = $conn->prepare('SELECT p.PKProducto, p.Nombre, p.ClaveInterna, dop.cantidad_pedida, csu.Descripcion FROM detalle_orden_pedido_por_sucursales as dop INNER JOIN productos as p ON p.PKProducto = dop.producto_id LEFT JOIN info_fiscal_productos as ifp ON ifp.FKProducto = p.PKProducto LEFT JOIN claves_sat_unidades as csu ON csu.PKClaveSATUnidad = ifp.FKClaveSATUnidad WHERE dop.orden_pedido_id = :id');
                                $stmt->execute(array(':id' => $id));
                                $numero_productos = $stmt->rowCount();
                                $rowp = $stmt->fetchAll();

                                foreach ($rowp as $rp) {

                                    if($rp['Descripcion'] == ""){
                                      $ClaveUnidad = "Sin unidad";
                                    }
                                    else{
                                      $ClaveUnidad = $rp['Descripcion'];
                                    }
                                    
                            ?>

                            <tr id="idProducto_<?=$rp['PKProducto']?>" class="text-center">
                              <th id="nombreproducto_<?=$rp['PKProducto']?>">
                                <?=$rp['ClaveInterna'] . ' - ' . $rp['Nombre']?>
                              </th>
                              <th><?=$ClaveUnidad?></th>
                              <th id="piezas_<?=$rp['PKProducto']?>"><?=$rp['cantidad_pedida']?></th>
                              <input type="hidden" id="piezaAnt_<?=$rp['PKProducto']?>" value="<?=$rp['cantidad_pedida']?>" />
                              <input type="hidden" name='inp_productos[]' value="<?=$rp['PKProducto']?>" />
                              <th></th>
                            </tr>
                            <?php

}?>
                          </tbody>
                        </table>
                      </div>

                      <div class="row">
                        <div class="col-lg-12" style="color:#d9534f;display: none;text-align: center;"
                          id="mostrarMensaje">
                          <h2>Ingresa un producto al menos.</h2>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row my-3">
                    <div class="col-lg-12 text-center">
                      <b>Observaciones:</b> <br><?=$observaciones;?>
                    </div>
                  </div>
                </div>



                <div id="mostrarBitacora" style="display:none;">
                  <?php
                      $stmt = $conn->prepare('SELECT u.nombre,b.created_at,m.Mensaje
                        FROM bitacora_orden_pedido AS b
                        LEFT JOIN usuarios AS u ON b.usuario_id = u.id
                        LEFT JOIN mensajes_acciones AS m ON b.mensaje_id = m.PKMensajesAcciones
                        WHERE b.orden_pedido_id = :id');
                        $stmt->bindValue(':id', $id);
                        $stmt->execute();
while ($row = $stmt->fetch()) {
    $fecha = new DateTime($row['created_at']);
    $usuario = $row['nombre'];

    $alerta = $fecha->format('d/m/Y H:i:s') . ": " . $row['Mensaje'] . " por " . $usuario;
    ?>
                  <!-- bitacora de movimientos en compras-->
                  <div class="row">
                    <div class="alert alert-secondary col-lg-6 text-center text-primary"
                      style="font-weight: bold;margin-left:25%" role="alert">
                      <?=$alerta;?>
                    </div>
                  </div>
                  <?php }?>
                </div>


              </div>
            </div>
          </div>
          <!-- End of Main Content -->

          <!-- Footer -->
          <?php
$rutaf = "../";
require_once '../footer.php';
?>
          <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

      </div>
      <!-- End of Page Wrapper -->

      <!-- Scroll to Top Button-->
      <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
      </a>

      <!-- Modal Datos envio -->
      <div id="datos_envio" class="modal fade">
        <div class="modal-dialog">
          <div class="modal-content">
            <form action="#" method="POST">
              <input type="hidden" name="txtId" id="txtId" value="<?=$id;?>">
              <div class="modal-header">
                <h4 class="modal-title">Datos de envio</h4>
                <button type="button" class="close text-light" data-dismiss="modal" aria-hidden="true">x</button>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="form-group col-lg-12">
                    <label for="">De: </label>
                    <input class="form-control" type="email" name="txtOrigen" id="txtOrigen" value="<?=$email_envio;?>"
                      required>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-lg-12">
                    <label for="txtEmail">Para: </label>
                    <input class="form-control" type="email" name="txtDestino" id="txtDestino" value=""
                      required>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-lg-12">
                    <label for="">Asunto: </label>
                    <input class="form-control" type="text" name="txtAsunto" id="txtAsunto" value="Orden de pedido <?php echo sprintf("%011d", $nopedido);?>"
                      required>
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
                <img src="../../img/chat/loading.gif" id="loading" width="30px"
                  style="position: absolute; bottom: 70px;text-align: center;display: none;">
              </div>
              <div class="modal-footer">
                <input type="hidden" name="csr_token_8UY8N" id="csr_token_8UY8N" value="<?=$token?>">
                <button type="button" class="btn-custom btn-custom--border-blue" data-dismiss="modal" name="button"
                  id="cancelarOrdenPedido"><i class="fas fa-times"></i> Cancelar</button>
                <button class="btn-custom btn-custom--blue" type="button" name="button" id="enviarOrdenPedido"><i
                    class="fas fa-envelope"></i> Enviar</button>
              </div>
            </form>
          </div>
        </div>
      </div>


      <!-- Agregar mensaje-->
      <div id="agregar_Proyecto" class="modal fade" style="z-index: 100000000">
        <div class="modal-dialog">
          <div class="modal-content">
            <form action="" method="POST" id="frProyecto">
              <div class="modal-header">
                <h4 class="modal-title">Portal de clientes</h4>
                <button type="button" class="close text-light" data-dismiss="modal" aria-hidden="true">x</button>
              </div>
              <br>
              <div class="row">
                <div class="col-lg-8" style="text-align: center;position:relative;left: 16%;">
                  <label for="usr">Mensaje:</label>
                  <textarea id="txtMensaje" rows="4" cols="50" class="form-control" maxlength="150" required></textarea>
                </div>
              </div>
              <br>
              <div class="modal-footer">
                <input type="button" class="btn-custom btn-custom--border-blue" data-dismiss="modal" value="Cancelar"
                  id="cancelarMensaje">
                <input type="button" class="btn-custom btn-custom--blue" id="btnGuardar" value="Agregar">
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <script>
    function editarOrdenPedido(idOrdenPedido) {

       $.ajax({
        type: 'POST',
        url: 'functions/verificarEstadoOrdenPedido.php',
        data: {
          idOrdenPedido: idOrdenPedido
        },
        success: function(data) {
          if (data == 1 || data == 2) {
            $().redirect('editarOrdenPedido.php', {
              'idOrdenPedidoU': idOrdenPedido
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
              msg: "No se pueden modificar ordenes de pedido surtidas, facturadas, canceladas o cerradas."
            });
          }

        }
      });

    }

    function descargarOrdenPedido(idOrdenPedido) {
      
      let empresa_id = <?php echo $empresa_id; ?>; 
      let session_empresa = <?php echo $_SESSION['IDEmpresa']; ?>;
      let token = $("#csr_token_8UY8N").val();
      if(empresa_id == session_empresa){
        $().redirect('functions/descargar_OrdenPedido.php', {
          'idOrdenPedido': idOrdenPedido,
          'csr_token_8UY8N': token
        });
      }
      else{
        Lobibox.notify("error", {
          size: 'mini',
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: 'center top', //or 'center bottom'
          icon: false,
          img: '../../img/timdesk/warning_circle.svg',
          msg: "No tienes permiso para descargar la orden de pedido."
        });
      } 

    }


    function isEmail(email) {
      var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
      return regex.test(email);
    }

    let contadorEnviar = 0;
    $("#enviarOrdenPedido").click(function() {
      var id = $("#txtId").val();
      var emailOrigen = $("#txtOrigen").val();
      var emailDestino = $("#txtDestino").val();
      var asunto = $("#txtAsunto").val();
      var mensaje = $("#txaMensaje").val();
      let token = $("#csr_token_8UY8N").val();


      if (contadorEnviar == 0) {

        $("#txtOrigen")[0].reportValidity();
        $("#txtOrigen")[0].setCustomValidity('Ingresa el correo electrónico de origen.');

        $("#txtDestino")[0].reportValidity();
        $("#txtDestino")[0].setCustomValidity('Ingresa un correo electrónico válido.');

        $("#txtAsunto")[0].reportValidity();
        $("#txtAsunto")[0].setCustomValidity('Ingresa el asunto del correo.');

        $("#txaMensaje")[0].reportValidity();
        $("#txaMensaje")[0].setCustomValidity('Ingresa un mensaje del correo.');
        contadorEnviar = 1;
      }


      if (emailOrigen.trim() == "") {
        $("#txtOrigen")[0].reportValidity();
        $("#txtOrigen")[0].setCustomValidity('Ingresa el correo electrónico de origen.');
        return;
      }
      var validarEmailOrigen = isEmail(emailOrigen);
      if (validarEmailOrigen == false) {
        $("#txtOrigen")[0].reportValidity();
        $("#txtOrigen")[0].setCustomValidity('Ingresa un correo electrónico válido.');
        return;
      }

      if (emailDestino.trim() == "") {
        $("#txtDestino")[0].reportValidity();
        $("#txtDestino")[0].setCustomValidity('Ingresa el correo electrónico de destino.');
        return;
      }

      var validarEmailDestino = isEmail(emailDestino);
      if (validarEmailDestino == false) {
        $("#txtDestino")[0].reportValidity();
        $("#txtDestino")[0].setCustomValidity('Ingresa un correo electrónico válido.');
        return;
      }

      if (asunto.trim() == "") {
        $("#txtAsunto")[0].reportValidity();
        $("#txtAsunto")[0].setCustomValidity('Ingresa el asunto del correo.');
        return;
      }

      if (mensaje.trim() == "") {
        $("#txaMensaje")[0].reportValidity();
        $("#txaMensaje")[0].setCustomValidity('Ingresa un mensaje del correo.');
        return;
      }

      $("#enviarOrdenPedido").attr("disabled", true);
      $("#cancelarOrdenPedido").attr("disabled", true);
      $("#loading").css("display", "flex");

      $.ajax({
        type: 'POST',
        url: 'functions/enviar_OrdenPedido.php',
        data: {
          txtId: id,
          txtOrigen: emailOrigen,
          txtDestino: emailDestino,
          txtAsunto: asunto,
          txaMensaje: mensaje,
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
              msg: "Se envio la orden de pedido al correo."
            });

            $("#txaMensaje").val("");
            $("#datos_envio").modal('toggle');

          } else {
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "Ocurrio un error al enviar, vuelva intentarlo."
            });
          }

          $("#enviarOrdenPedido").attr("disabled", false);
          $("#cancelarOrdenPedido").attr("disabled", false);
          $("#loading").css("display", "none");
        }
      });

    });


    function mostrarBitacora() {
      $("#mostrarBitacora").show();
      $("#mostrarOrdenPedido").hide();
    }

    function mostrarOrdenPedido() {
      $("#mostrarOrdenPedido").show();
      $("#mostrarBitacora").hide();
    }

    function cancelarOrdenPedidoF(idOrdenPedido) {

      let FKUsuario = <?=$_SESSION['PKUsuario']?>;
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
          text: "Se cancelara la orden de pedido y ya no se podrá modificar.",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: '<span class="verticalCenter">Cancelar orden pedido</span>',
          cancelButtonText: '<span class="verticalCenter">Cerrar</span>',
          reverseButtons: true,
        })
        .then((result) => {
          if (result.isConfirmed) {

            $.ajax({
              type: 'POST',
              url: 'functions/cancelarOrdenPedido.php',
              data: {
                idOrdenPedido: idOrdenPedido,
                FKUsuario: FKUsuario,
                csr_token_8UY8N : token
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
                    msg: "Se ha cancelado la orden de pedido"
                  });

                  //$("#aceptarActualizar").html('<div style="background:#dc3545;padding:5px;color:white;-webkit-border-radius: 16px; -moz-border-radius: 16px; border-radius: 16px;"><center>Cotización cancelada</center></div>');

                } 
                else if (data == "fallo-cancelacion") {
                  Lobibox.notify("error", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/warning_circle.svg',
                    msg: "No puedes cancelar un orden de pedido surtida o facturada."
                  });

                }
                else {

                  Lobibox.notify("error", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/warning_circle.svg',
                    msg: "Ocurrio un error, vuelva intentarlo."
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

      let FKUsuario = <?=$_SESSION['PKUsuario']?>;
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
          text: "Se cerrara la orden de pedido y ya no se podrá modificar.",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: '<span class="verticalCenter">Cerrar orden de pedido</span>',
          cancelButtonText: '<span class="verticalCenter">Cerrar ventana</span>',
          reverseButtons: true,
        })
        .then((result) => {
          if (result.isConfirmed) {

            $.ajax({
              type: 'POST',
              url: 'functions/cerrarOrdenPedido.php',
              data: {
                idOrdenPedido: idOrdenPedido,
                FKUsuario: FKUsuario,
                csr_token_8UY8N : token
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
                    msg: "Se ha cerrado la orden de pedido"
                  });

                  //$("#aceptarActualizar").html('<div style="background:#dc3545;padding:5px;color:white;-webkit-border-radius: 16px; -moz-border-radius: 16px; border-radius: 16px;"><center>Cotización cancelada</center></div>');

                } 
                else if (data == "fallo-cancelacion") {
                  Lobibox.notify("error", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/warning_circle.svg',
                    msg: "No puedes cerrar un orden de pedido surtida o facturada."
                  });

                }
                else {

                  Lobibox.notify("error", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/warning_circle.svg',
                    msg: "Ocurrio un error, vuelva intentarlo."
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

</body>

</html>