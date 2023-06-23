<?php
session_start();

if (isset($_SESSION["Usuario"])) {
    require_once '../../../include/db-conn.php';
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $stmt = $conn->prepare('SELECT c.PKCotizacion, c.ImporteTotal, c.Subtotal, DATE_FORMAT(c.FechaIngreso, "%d/%m/%Y %H:%s:%i") as FechaIngreso, DATE_FORMAT(c.FechaVencimiento, "%d/%m/%Y") as FechaVencimiento, c.NotaCliente,c.NotaInterna, cl.NombreComercial, cl.Email, u.nombre, c.estatus_factura_id as Estatus, u.usuario as EmailEnvio , c.FKUsuarioCreacion FROM cotizacion as c
        LEFT JOIN clientes as cl ON cl.PKCliente = c.FKCliente
        LEFT JOIN usuarios AS u ON c.FKUsuarioCreacion = u.id
        WHERE c.PKCotizacion = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        $referencia = $row['PKCotizacion'];
        $Subtotal = $row['Subtotal'];
        $ImporteTotal = "$ " . number_format($row['ImporteTotal'], 2);
        $FechaIngreso = $row['FechaIngreso'];
        $FechaVencimiento = $row['FechaVencimiento'];
        $NotaCliente = $row['NotaCliente'];
        $NotaInterna = $row['NotaInterna'];
        $NombreComercial = $row['NombreComercial'];
        $Email = $row['Email'];
        $EmailEnvio = $row['EmailEnvio'];
        $idVendedor = $row['FKUsuarioCreacion'];

        $Vendedor = $row['nombre'];

    }
} else {
    header("location:../../dashboard.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="icon" type="image/png" href="../../../img/header/bluTimlid.png">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Timlid | Ver cotización</title>

  <!-- Bootstrap core JavaScript-->
  <script src="../../../vendor/jquery/jquery.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../../js/validaciones.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../../../js/sb-admin-2.min.js"></script>
  <script src="../../../js/scripts.js"></script>
  <script src="../../../js/bootstrap-clockpicker.min.js"></script>

  <!-- Page level plugins -->
  <script src="../../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="../../../vendor/datatables/dataTables.buttons.js"></script>
  <script src="../../../vendor/datatables/buttons.bootstrap4.min.js"></script>
  <script src="../../../vendor/datatables/buttons.html5.min.js"></script>
  <script src="../../../vendor/jszip/jszip.min.js"></script>

  <script src="../../../js/jquery.redirect.min.js"></script>
  <script src="../../../js/sweet/sweetalert2.js"></script>

  <!-- Custom fonts for this template-->
  <link href="../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../../../css/bootstrap-clockpicker.min.css" rel="stylesheet" type="text/css">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../../css/styles.css" rel="stylesheet">
  <link href="../../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  <link href="../../../vendor/datatables/responsive.dataTables.css" rel="stylesheet">
  <link rel="stylesheet" href="../../../vendor/datatables/buttons.dataTables.css">
  <link href="../../../vendor/datatables/buttons.bootstrap4.min.css" rel="stylesheet">
  <link href="../../../css/stylesTable.css" rel="stylesheet">
  <link href="../../../css/lobibox.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../../css/notificaciones.css">

  <script src="../../../js/lobibox.min.js"></script>
  <script src="../../../js/sweet/sweetalert2.js"></script>

  <script src="../../../js/notificaciones_timlid.js" charset="utf-8"></script>
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
  <link href="../../../css/timeline.css" rel="stylesheet">
</head>

<body id="page-top" class="sidebar-toggled">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
$icono = '../../../img/icons/ICONO COTIZACIONES-01.svg';
$titulo = 'Cotización';
$ruta = "../../";
$ruteEdit = "$ruta.central_notificaciones/";
require_once $ruta . 'menu3.php';
?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <?php
$rutatb = "../../";
require_once $rutatb . 'topbar.php';?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Cotización</h1>
          </div>


          <div class="row">

            <div class="col-lg-12">

              <!-- Basic Card Example -->
              <div class="card shadow mb-4">
                <div class="card-header">
                  <div class="d-flex justify-content-between flex-wrap">
                    <div>
                      <?php
if ($row['Estatus'] == 5) {?>

                      <span id="aceptarActualizar"><button type="button" class="btn-custom btn-custom--green"
                          name="btnAgregarProducto" onclick="aceptarCotizacion('<?=$id?>');" id="aceptar"><i
                            class="far fa-check-square"></i> Aceptar</button></span>
                      <?php
}
if ($row['Estatus'] == 4) {?>

                      <div
                        style="background:#f6c23e;padding:5px;color:white;-webkit-border-radius: 16px; -moz-border-radius: 16px; border-radius: 16px;">
                        <center>Cotización vencida</center>
                      </div>
                      <?php
}
if ($row['Estatus'] == 3) {?>

                      <div
                        style="background:#dc3545;padding:5px;color:white;-webkit-border-radius: 16px; -moz-border-radius: 16px; border-radius: 16px;">
                        <center>Cotización cancelada</center>
                      </div>
                      <?php
}
if ($row['Estatus'] == 1) {?>

                      <button type="button" class="btn-custom btn-custom--blue-lightest" name="btnAgregarProducto"
                        onclick="facturarCotizacion(' . $id . ');"><i class="fas fa-file-invoice"></i> Facturar</button>
                      <?php
}?>
                    </div>
                    <div>
                      <button data-toggle="modal" data-target="#datos_envio" type="button"
                        class="btn-custom btn-custom--blue-lightest" name="btnAgregarProducto"><i
                          class="far fa-envelope"></i> Enviar</button>
                    </div>
                    <div>
                      <button type="button" class="btn-custom btn-custom--blue-light" name="btnAgregarProducto"
                        onclick="editarCotizacion(<?=$id?>);"><i class="fas fa-edit"></i> Editar</button>
                    </div>
                    <div>
                      <button type="button" class="btn-custom btn-custom--yellow" name="btnChat"
                        onclick="mostrarCotizacion()"><i class="fas fa-receipt"></i> Cotización</button>
                    </div>
                    <div>
                      <button type="button" class="btn-custom btn-custom--dark" name="btnChat"
                        onclick="mostrarChat()"><i class="far fa-comments"></i> Chat</button>
                    </div>
                    <div>
                      <button type="button" class="btn-custom btn-custom--green" name="btnChat"
                        onclick="mostrarBitacora()"><i class="fas fa-book"></i> Bitacora</button>
                    </div>
                    <div>
                      <button data-toggle="modal" type="button" class="btn-custom btn-custom--gray" name="btnDescargar"
                        onclick="descargarCotizacion(<?=$id?>);"><i class="fas fa-download"></i> Descargar</button>
                    </div>
                  </div>
                </div>
                <div class="card-body">
                  <div class="row my-3">
                    <div class="col-lg-3 ">
                      <b>Vendedor:</b> <?=$Vendedor;?> <br>
                      <b>Referencia:</b> <?=$referencia;?>
                    </div>
                    <div class="col-lg-3">
                      <b>Cliente:</b> <?=$NombreComercial;?> <br>
                      <b>Fecha de ingreso:</b> <?=$FechaIngreso;?> <br>
                      <b>Fecha de vencimiento:</b> <?=$FechaVencimiento;?>
                    </div>
                    <div class="col-lg-3">
                    </div>
                    <div class="col-lg-3">
                      <h4><b>Importe Total:</b> <?=$ImporteTotal;?></h4>
                    </div>
                    <hr class="my-3" style="width: 100%">
                  </div>
                </div>

                <div class="card-body" id="mostrarCotizacion">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="table-responsive redondear">
                        <table class="table table-sm" id="cotizacion">
                          <thead class="text-center header-color">
                            <tr>
                              <th>Clave/Producto</th>
                              <th>Cantidad</th>
                              <th>Unidad de medida</th>
                              <th>Precio unitario</th>
                              <th>Impuestos</th>
                              <th>Importe</th>
                              <th></th>
                            </tr>
                          </thead>
                          <tbody id="lstProductos">
                            <?php
$stmt = $conn->prepare('SELECT dc.FKProducto, dc.Cantidad, dc.Precio, p.ClaveInterna, p.Nombre FROM detalle_cotizacion as dc INNER JOIN productos as p ON p.PKProducto = dc.FKProducto WHERE dc.FKCotizacion = :id');
$stmt->execute(array(':id' => $id));
$numero_productos = $stmt->rowCount();
$rowp = $stmt->fetchAll();
$impuestos = array();
$x = 0;

foreach ($rowp as $rp) {

    $stmt = $conn->prepare('SELECT i.PKImpuesto, i.Nombre, i.FKTipoImpuesto as TipoImpuesto, i.FKTipoImporte as TipoImporte, i.Operacion, di.FKProducto, di.Tasa FROM detalleimpuesto as di INNER JOIN impuesto as i ON i.PKImpuesto = di.FKImpuesto WHERE di.FKCotizacion= :id AND FKProducto = :idProducto');
    $stmt->execute(array(':id' => $id, ':idProducto' => $rp['FKProducto']));
    $rowi = $stmt->fetchAll();
    $totalProducto = $rp['Cantidad'] * $rp['Precio'];?>

                            <tr id="idProducto_<?=$rp['FKProducto']?>" class="text-center">
                              <th id="nombreproducto_<?=$rp['FKProducto']?>">
                                <?=$rp['ClaveInterna'] . ' - ' . $rp['Nombre']?>
                              </th>
                              <th id="piezas_<?=$rp['FKProducto']?>"><?=$rp['Cantidad']?></th>
                              <input type="hidden" id="piezaAnt_<?=$rp['FKProducto']?>" value="<?=$rp['Cantidad']?>" />
                              <input type="hidden" name='inp_productos[]' value="<?=$rp['FKProducto']?>" />
                              <th>Pieza</th>
                              <th id="precio_<?=$rp['FKProducto']?>"><?=$rp['Precio']?></th>
                              <input type="hidden" name="inp_precio[]" value="<?=$rp['Precio']?>" />
                              <th><span id="impuestos_<?=$rp['FKProducto']?>">
                                  <?php

    $contImpuestos = 1;
    $numImpuestos = count($rowi);
    foreach ($rowi as $ri) {
        $IniImpuesto = explode(" ", $ri['Nombre']);
        $Identificador = $IniImpuesto[0] . "_" . $ri['TipoImpuesto'] . "_" . $ri['TipoImporte'] . "_" . $ri['PKImpuesto'] . "_" . $ri['FKProducto'];

        if ($ri['TipoImporte'] == 1) {
            $tas = "%";
        }
        if ($ri['TipoImporte'] == 2 || $ri['TipoImporte'] == 3) {
            $tas = "";
        }

        //print_r($impuestos);

        $found_key = array_search($ri['PKImpuesto'], array_column($impuestos, 0));
        
        if ($found_key > -1) {
            $impuestos[$found_key][0] = $ri['PKImpuesto'];
            if ($ri['TipoImporte'] == 1) {
                $impuestos[$found_key][1] = $impuestos[$found_key][1] + (($rp['Cantidad'] * $rp['Precio']) * ($ri['Tasa'] / 100));
            } else {
                $impuestos[$found_key][1] = $impuestos[$found_key][1] + $ri['Tasa'];
            }
            $impuestos[$found_key][2] = $ri['Nombre'];
            $impuestos[$found_key][3] = $ri['TipoImpuesto'];
            $impuestos[$found_key][4] = $ri['Operacion'];
        } else {
            $impuestos[$x][0] = $ri['PKImpuesto'];
            if ($ri['TipoImporte'] == 1) {
                $impuestos[$x][1] = ($rp['Cantidad'] * $rp['Precio']) * ($ri['Tasa'] / 100);
            } else {
                $impuestos[$x][1] = $ri['Tasa'];
            }
            $impuestos[$x][2] = $ri['Nombre'];
            $impuestos[$x][3] = $ri['TipoImpuesto'];
            $impuestos[$x][4] = $ri['Operacion'];
            $x++;
        }?>
                                  <span id="<?=$Identificador?>">
                                    <?=$ri['Nombre']?> <?=$ri['Tasa'] . $tas?>
                                    <input name="valImp_<?=$ri['Tasa']?>" type="hidden"
                                      id="impAgregado_<?=$ri['FKProducto']?>_<?=$ri['PKImpuesto']?>"
                                      value="<?=$ri['Tasa']?>" />
                                    <input type="hidden"
                                      id="OperacionUnica_<?=$ri['FKProducto']?>_<?=$ri['PKImpuesto']?>"
                                      value="<?=$ri['Operacion']?>" />
                                  </span>
                                  <?php

        if ($contImpuestos != $numImpuestos) {?>
                                  <br>
                                  <?php

        }
        $contImpuestos++;
    }?>
                                </span>
                              </th>
                              <th id="totalproducto_<?=$rp[' FKProducto']?>"><?=number_format($totalProducto, 2)?>
                              </th>
                              <input type="hidden" name="inp_total_producto[]"
                                value="<?=number_format($totalProducto, 2)?>" />
                              <th>

                              </th>
                            </tr>
                            <?php

}?>
                          </tbody>
                          <tr>
                            <th colspan=" 3">
                            </th>
                            <th style="text-align: right;">Subtotal:</th>
                            <th colspan="2" style="text-align: right;">$ <span
                                id="Subtotal"><?=number_format($Subtotal, 2)?></span></th>
                            <th></th>
                          </tr>
                          <tr>
                            <th colspan="3"></th>
                            <th style="text-align: right;">Impuestos:</th>
                            <th colspan="2"></th>
                            <th></th>
                          </tr>
                          <tbody id="lstimpuestos">
                            <?php
foreach ($impuestos as $imp) {
    $IniImpuesto = explode(" ", $imp[2]);?>

                            <tr id="<?=$IniImpuesto[0]?>_<?=$imp[0]?>">
                              <th colspan='3'></th>
                              <th style="text-align: right;"><?=$imp[2]?></th>
                              <th colspan="2" style="text-align: right;">$ <span id="Impuesto_<?=$imp[0]?>"
                                  name="<?=$imp[3]?>_<?=$imp[4]?>"
                                  class="ImpuestoTot"><?=number_format($imp[1], 2)?></span>
                              </th>
                              <th></th>
                            </tr>
                            <?php

}?>
                          </tbody>
                          <tr class="total">
                            <th colspan="3" class="redondearAbajoIzq"></th>
                            <th>Total:</th>
                            <th colspan="2" style="text-align: right;"><span id="Total"><?=$ImporteTotal?></span></th>
                            <th class="redondearAbajoDer"></th>
                          </tr>
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
                    <div class="col-lg-6 text-center">
                      <b>Nota Interna:</b> <br><?=$NotaInterna;?>
                    </div>
                    <div class="col-lg-6 text-center">
                      <b>Nota Cliente:</b> <br><?=$NotaCliente;?>
                    </div>
                  </div>
                </div>

                <!---SEPARACION BODY--->
                <div class="card shadow mb-4" id="mostrarChat" style="display:none;">
                  <div class="card-header">
                    <a href="#" class="btn btn-success btn-round"
                      style="position: relative; right: 2%;float: right;margin: 5px 0;" data-toggle="modal"
                      data-target="#agregar_Proyecto"><i class="far fa-comment-dots"></i> Agregar mensaje </a>
                  </div>
                  <div class="card-body">


                    <?php
$stmt = $conn->prepare('SELECT mc.TipoUsuario, mc.Mensaje, DATE_FORMAT(mc.FechaAgregado, "%d/%m/%Y %H:%i:%s") as Fecha ,
                              cl.NombreComercial, u.nombre as Nombre_Empleado 
                            FROM mensajes_cotizacion as mc 
                              INNER JOIN cotizacion as c ON c.PKCotizacion = mc.FKCotizacion
                              INNER JOIN usuarios as u ON u.id = c.FKUsuarioCreacion
                              INNER JOIN clientes as cl ON cl.PKCliente = c.FKCliente
                          WHERE mc.FKCotizacion = :cotizacion ORDER BY  mc.FechaAgregado DESC');
$stmt->bindValue(':cotizacion', $id);
$stmt->execute();
$row = $stmt->fetchAll();
if (count($row) > 0) {?>

                    <ul class="timeline" id="add-timeline">
                      <?php

    foreach ($row as $r) {
        if ($r['TipoUsuario'] == 1) {
            $nombreMensaje = $r['NombreComercial'];
            $clase = 'class="timeline-inverted"';
            $color = 'warning';
        } else {
            $nombreMensaje = $r['Nombre_Empleado'];
            $clase = '';
            $color = 'info';
        }?>
                      <li <?=$clase?>>
                        <div class="timeline-badge <?=$color?>"><i class="glyphicon glyphicon-credit-card"></i></div>
                        <div class="timeline-panel">
                          <div class="timeline-heading">
                            <h4 class="timeline-title"><?=$nombreMensaje?></h4>
                          </div>
                          <div class="timeline-body">
                            <p><?=$r['Mensaje']?></p>
                          </div>
                          <hr>
                          <div class="row">
                            <div class="col-md-12" align="right">
                              <small><?=$r['Fecha']?></small>
                            </div>
                          </div>
                        </div>
                      </li>
                      <?php

    }?>
                    </ul>
                    <?php

} else {?>
                    <ul id="add-timeline">
                      <h3 id="nuevo_mensaje">
                        <center>AUN NO HAY MENSAJES EN ESTA COTIZACIÓN</center>
                      </h3>
                    </ul>
                    <?php

}?>
                  </div>
                </div>
                <div id="mostrarBitacora" style="display:none;">
                  <?php
$stmt = $conn->prepare('SELECT u.nombre,b.Fecha_Movimiento,m.Mensaje
                        FROM bitacora_cotizaciones AS b
                        LEFT JOIN usuarios AS u ON b.FKUsuario = u.id
                        LEFT JOIN mensajes_acciones AS m ON b.FKMensaje = m.PKMensajesAcciones
                        WHERE b.FKCotizacion = :id');
$stmt->bindValue(':id', $id);
$stmt->execute();
while ($row = $stmt->fetch()) {
    $fecha = new DateTime($row['Fecha_Movimiento']);
    $usuario = $row['Nombres'] . " " . $row['PrimerApellido'];
    $alerta = $fecha->format('d/m/Y') . ": " . $row['Mensaje'] . " por " . $usuario;
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
$rutaf = "../../";
require_once '../../footer.php';
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
                    <input class="form-control" type="email" name="txtOrigen" id="txtOrigen" value="<?=$EmailEnvio;?>"
                      required>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-lg-12">
                    <label for="txtEmail">Para: </label>
                    <input class="form-control" type="email" name="txtDestino" id="txtDestino" value="<?=$Email;?>"
                      required>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-lg-12">
                    <label for="">Asunto: </label>
                    <input class="form-control" type="text" name="txtAsunto" id="txtAsunto" value="Nueva Cotización"
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
                <img src="../../../img/chat/loading.gif" id="loading" width="30px"
                  style="position: absolute; bottom: 70px;text-align: center;display: none;">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn-custom btn-custom--border-blue" data-dismiss="modal" name="button"
                  id="cancelarCotizacion"><i class="fas fa-times"></i> Cancelar</button>
                <button class="btn-custom btn-custom--blue" type="button" name="button" id="enviarCotizacion"><i
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
    function isEmail(email) {
      var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
      return regex.test(email);
    }

    var contadorEnviar = 0;

    function aceptarCotizacion(idCotizacion) {


      var FKUsuario = <?=$_SESSION['PKUsuario']?>;

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
          text: "Se aceptará la cotización y ahora se podrá facturar.",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: '<span class="verticalCenter">Aceptar cotización</span>',
          cancelButtonText: '<span class="verticalCenter">Cancelar</span>',
          reverseButtons: true,
        })
        .then((result) => {
          if (result.isConfirmed) {

            $.ajax({
              type: 'POST',
              url: 'aceptarCotizacion.php',
              data: {
                idCotizacion: idCotizacion,
                FKUsuario: FKUsuario
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
                    img: '../../../img/timdesk/checkmark.svg',
                    msg: "¡Se ha aceptado la cotización, ya la puedes facturar!"
                  });

                  $("#aceptarActualizar").html(
                    '<button type="button" class="btn btn-info" name="btnAgregarProducto" onclick="facturarCotizacion(' +
                    idCotizacion + ');"><i class="fas fa-file-invoice"></i> Facturar</button>');

                } else {

                  Lobibox.notify("error", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../../img/timdesk/warning_circle.svg',
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


    $("#enviarCotizacion").click(function() {
      var id = $("#txtId").val();
      var emailOrigen = $("#txtOrigen").val();
      var emailDestino = $("#txtDestino").val();
      var asunto = $("#txtAsunto").val();
      var mensaje = $("#txaMensaje").val();


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

      $("#enviarCotizacion").attr("disabled", true);
      $("#cancelarCotizacion").attr("disabled", true);
      $("#loading").css("display", "flex");

      $.ajax({
        type: 'POST',
        url: 'enviar_Cotizacion.php',
        data: {
          txtId: id,
          txtOrigen: emailOrigen,
          txtDestino: emailDestino,
          txtAsunto: asunto,
          txaMensaje: mensaje
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
              img: '../../../img/timdesk/checkmark.svg',
              msg: "Se envio la cotización al correo."
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
              img: '../../../img/timdesk/warning_circle.svg',
              msg: "Ocurrio un error al enviar, vuelva intentarlo."
            });
          }

          $("#enviarCotizacion").attr("disabled", false);
          $("#cancelarCotizacion").attr("disabled", false);
          $("#loading").css("display", "none");
        }
      });


    });

    function editarCotizacion(idCotizacion) {

      $().redirect('editar_Cotizacion.php', {
        'idCotizacionU': idCotizacion
      });
    }

    function descargarCotizacion(idCotizacion) {

      $().redirect('descargar_Cotizacion.php', {
        'idCotizacion': idCotizacion
      });
    }

    function facturarCotizacion(idCotizacion) {

      $().redirect('../../facturacion/functions/agregar_CFDI.php', {
        'idCotizacionF': idCotizacion
      });
    }

    function mostrarChat() {
      $("#mostrarChat").show();
      $("#mostrarBitacora").hide();
      $("#mostrarCotizacion").hide();
    }

    function mostrarCotizacion() {
      $("#mostrarCotizacion").show();
      $("#mostrarChat").hide();
      $("#mostrarBitacora").hide();
    }

    function mostrarBitacora() {
      $("#mostrarBitacora").show();
      $("#mostrarChat").hide();
      $("#mostrarCotizacion").hide();
    }

    $("#btnGuardar").click(function() {

      var mensaje = $('#txtMensaje').val().trim();
      var cotizacion = <?=$id?>;

      if (mensaje === '') {
        $("#txtMensaje")[0].reportValidity();
        $("#txtMensaje")[0].setCustomValidity('Completa este campo.');
        return;
      }

      $("#btnGuardar").attr("disabled", true);
      $("#cancelarMensaje").attr("disabled", true);

      <?php date_default_timezone_set('America/Mexico_City');?>
      var fecha = "<?php echo date('d/m/Y H:i:s', time()); ?>";
      var nombreVendedor = '<?php echo $Vendedor; ?>';
      var idVendedor = <?php echo $idVendedor; ?>;

      var myData = {
        "Mensaje": mensaje,
        "Cotizacion": cotizacion,
        "Fecha": fecha
      };

      $.ajax({
        url: "agregarMensaje.php",
        type: "POST",
        data: myData,
        success: function(data, status, xhr) {
          if (data == 'exito') {

            var agregarLista = '<li>' +
              '<div class="timeline-badge info"><i class="glyphicon glyphicon-credit-card"></i></div>' +
              '<div class="timeline-panel">' +
              '<div class="timeline-heading">' +
              '<h4 class="timeline-title">' + nombreVendedor + '</h4>' +
              '</div>' +
              '<div class="timeline-"body">' +
              '<p>' + mensaje + '</p>' +
              '</div>' +
              '<hr>' +
              '<div class="row">' +
              '<div class="col-md-12" align="right">' +
              '<small>' + fecha + '</small>' +
              '</div>' +
              '</div>' +
              '</div>' +
              '</li>';

            $("#add-timeline").addClass("timeline");
            $("#nuevo_mensaje").remove();
            $("#add-timeline").prepend(agregarLista);

            Lobibox.notify("success", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../../img/timdesk/checkmark.svg',
              msg: "¡Mensaje enviado!"
            });

            $('#agregar_Proyecto').modal('toggle');
            $('#txtMensaje').val("");

          } else {

            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../../img/timdesk/warning_circle.svg',
              msg: "Ocurrio un error, no se envio el mensaje. Lo puede volver a intentar.."
            });

          }

          $("#btnGuardar").attr("disabled", false);
          $("#cancelarMensaje").attr("disabled", false);
        }
      });
    });    
    </script>

</body>

</html>