<?php
require_once '../include/db-conn.php';
$idDes = $_GET['id'];
$codigoDes = $_GET['codigo'];

include_once("../functions/functions.php");
$FKCotizacion = encryptor("decrypt", $idDes);
$codigo = encryptor("decrypt", $codigoDes);

$stmt = $conn->prepare('SELECT codigoCotizacion FROM cotizacion WHERE PKCotizacion = :cotizacion ');
$stmt->bindValue(':cotizacion', $FKCotizacion);
$stmt->execute();
$rowcomprobacion = $stmt->fetch();

if ($codigo != $rowcomprobacion['codigoCotizacion']) {
  header("Location: error.php");
}

$stmt = $conn->prepare('SELECT c.FKCliente, c.id_cotizacion_empresa, cl.NombreComercial,c.codigoCotizacion,c.Subtotal, c.ImporteTotal, DATE_FORMAT(c.FechaIngreso, "%d/%m/%Y") as FechaIngreso, DATE_FORMAT(c.FechaVencimiento, "%d/%m/%Y") as FechaVencimiento, u.nombre as Nombre_Empleado, e.Telefono, u.Usuario, c.estatus_cotizacion_id as Estatus, c.FKUsuarioCreacion
FROM cotizacion as c INNER JOIN clientes as cl ON cl.PKCliente = c.FKCliente LEFT JOIN usuarios AS u ON c.FKUsuarioCreacion = u.id LEFT JOIN empleados_usuarios as eu ON eu.FKUsuario = u.id LEFT JOIN empleados as e ON e.PKEmpleado = eu.FKEmpleado WHERE c.PKCotizacion = :cotizacion ');
$stmt->bindValue(':cotizacion', $FKCotizacion);
$stmt->execute();
$row = $stmt->fetch();

$idCotizacionEmpresa = $row['id_cotizacion_empresa'];
$idCliente = $row['FKCliente'];
$nombreCliente = $row['NombreComercial'];
$Subtotal = $row['Subtotal'];
$ImporteTotal = $row['ImporteTotal'];
$FechaGeneracion = $row['FechaIngreso'];
$FechaVencimiento = $row['FechaVencimiento'];

$nombreVendedor = $row['Nombre_Empleado'];
$Email = $row['Usuario']; //email del vendedor
$Telefono = $row['Telefono'];
$Estatus = $row['Estatus'];
$idUsuacrioCreacion = $row['FKUsuarioCreacion'];

?>
<!doctype html>
<html class="no-js" lang="zxx">

<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Timlid - Portal del cliente</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- <link rel="manifest" href="site.webmanifest"> -->
  <link rel="shortcut icon" type="image/x-icon" href="../img/header/bluTimlid.png">
  <!-- Place favicon.ico in the root directory -->

  <!-- CSS here -->
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/magnific-popup.css">
  <link rel="stylesheet" href="../vendor/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="css/themify-icons.css">
  <link rel="stylesheet" href="css/nice-select.css">
  <link rel="stylesheet" href="css/flaticon.css">
  <link rel="stylesheet" href="css/gijgo.css">
  <link rel="stylesheet" href="css/animate.css">
  <link rel="stylesheet" href="css/slicknav.css">
  <link rel="stylesheet" href="css/style.css">
  <link href="../css/lobibox.min.css" rel="stylesheet">
  <!-- <link rel="stylesheet" href="css/responsive.css"> -->
</head>

<body>
  <!--[if lte IE 9]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
        <![endif]-->

  <!-- header-start -->
  <header class="container-fluid mt-3">
    <div class="row">
      <div class="col-12 d-flex justify-content-center align-items-center">
        <a href="#" style="color:rgb(21, 88, 155);">Cotización</a>
        <span class="divider"></span>
        <a href="chat.php?id=<?= $idDes ?>&codigo=<?= $codigoDes ?>">Chat</a>
      </div>
    </div>
  </header>
  <!-- header-end -->


  <?php
  $stmt = $conn->prepare('SELECT mc.TipoUsuario, mc.Mensaje, DATE_FORMAT(mc.FechaAgregado, "%d/%m/%Y %H:%i:%s") as Fecha ,cl.NombreComercial, u.nombre as Nombre_Empleado FROM mensajes_cotizacion as mc INNER JOIN cotizacion as c ON c.PKCotizacion = mc.FKCotizacion INNER JOIN clientes as cl ON cl.PKCliente = c.FKCliente INNER JOIN usuarios as u ON u.id = c.FKUsuarioCreacion LEFT JOIN empleados_usuarios as eu ON eu.FKUsuario = u.id LEFT JOIN empleados as e ON e.PKEmpleado = eu.FKEmpleado WHERE mc.FKCotizacion = :cotizacion ORDER BY  mc.FechaAgregado DESC');
  $stmt->bindValue(':cotizacion', $FKCotizacion);
  $stmt->execute();
  $row = $stmt->fetchAll();

  ?>


  <div class="container">
    <div class="row mt-4">
      <div class="col-12 col-md-6 d-flex justify-content-center justify-content-md-end mb-2 mb-md-0 order-md-2 p-0">
        <img src="../img/header/TIMLID_LOGO AZUL.png" alt="" width="200px" />
      </div>
      <div class="col-12 col-md-6 d-flex justify-content-center justify-content-md-start align-items-center order-md-1 p-0">
        <h1 class="m-0">Cotización No. <?php echo sprintf("%011d", $idCotizacionEmpresa); ?></h1>
      </div>
    </div>
    <br>
    <div class="row justify-content-between mt-4">
      <div class="col-12 col-md-4">
        <div class="row">
          <div class="col-6 text-center th">Fecha de expedición</div>
          <div class="col-6 text-center th">Fecha de vencimiendo</div>
        </div>
        <div class="row">
          <div class="col-6 text-center td"><?= $FechaGeneracion ?></div>
          <div class="col-6 text-center td"><?= $FechaVencimiento ?></div>
        </div>
      </div>
      <div class="col-12 col-md-4">
        <p><span class="font-weight-bold">Contacto: </span><span><?= $nombreVendedor ?></span></p>
        <p><span class="font-weight-bold">Teléfono: </span><span><?= $Telefono ?></span></p>
        <p><span class="font-weight-bold">Email: </span><span><?= $Email ?></span></p>
      </div>
    </div>

    <div class="row">
      <!-- <div class="col-md-12">
          <h4>
            <address>
              <?php
              echo "<strong>Contacto: </strong>" . $nombreVendedor . "<br>
                            <strong>Teléfono: </strong>" . $Telefono . "<br>
                            <strong>Email: </strong>" . $Email . "<br>";
              ?>
            </address>
          </h4>
        </div> -->
      <div class="col-12 my-4 p-0 table-container">
        <table>
          <tr>
            <th>Clave/Producto</th>
            <th>Cantidad</th>
            <th>Unidad de medida</th>
            <th>Precio unitario</th>
            <th>Impuestos</th>
            <th>Importe</th>
          </tr>
          <?php
          $stmt = $conn->prepare('SELECT dc.FKProducto, dc.Cantidad, dc.Precio, p.ClaveInterna, p.Nombre FROM detalle_cotizacion  as dc INNER JOIN productos as p ON p.PKProducto = dc.FKProducto WHERE dc.FKCotizacion = :id');
          $stmt->execute(array(':id' => $FKCotizacion));
          $numero_productos = $stmt->rowCount();
          $rowp = $stmt->fetchAll();
          $impuestos = array();
          $x = 0;

          foreach ($rowp as $rp) {

            $stmt = $conn->prepare('SELECT i.PKImpuesto, i.Nombre, i.FKTipoImpuesto as TipoImpuesto, i.FKTipoImporte as TipoImporte, i.Operacion, di.FKProducto, di.Tasa FROM detalleimpuesto  as di INNER JOIN impuesto as i ON i.PKImpuesto = di.FKImpuesto WHERE di.FKCotizacion= :id AND FKProducto = :idProducto');
            $stmt->execute(array(':id' => $FKCotizacion, ':idProducto' => $rp['FKProducto']));
            $rowi = $stmt->fetchAll();
            //print_r($rowi);

            $totalProducto = $rp['Cantidad'] * $rp['Precio'];
            echo "<tr id='idProducto_" . $rp['FKProducto'] . "'>" .
              "<td id='nombreproducto_" . $rp['FKProducto'] . "'>" . $rp['ClaveInterna'] . " - " . $rp['Nombre'] . "</td>" .
              "<td id='piezas_" . $rp['FKProducto'] . "'>" . $rp['Cantidad'] . "</td>" .
              "<td>Pieza</td>" .
              "<td id='precio_" . $rp['FKProducto'] . "'>" . $rp['Precio'] . "</td>" .
              "<input type='hidden' name='inp_precio[]' value='" . $rp['Precio'] . "' />" .
              "<td>" .
              "<span id='impuestos_" . $rp['FKProducto'] . "'>";

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

              //echo "id impuesto :".$ri['PKImpuesto']."//";
              $found_key = array_search($ri['PKImpuesto'], array_column($impuestos, 0));
              /*print_r($found_key);
        echo "fk ".$found_key[0];*/
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
              }

              echo "<span id='" . $Identificador . "' >" . $ri['Nombre'] . " " . $ri['Tasa'] . $tas . " <input name='valImp_" . $ri['Tasa'] . "' type='hidden' id='impAgregado_" . $ri['FKProducto'] . "_" . $ri['PKImpuesto'] . "' value='" . $ri['Tasa'] . "' /><input type='hidden' id='OperacionUnica_" . $ri['FKProducto'] . "_" . $ri['PKImpuesto'] . "' value='" . $ri['Operacion'] . "' /></span>";
              if ($contImpuestos != $numImpuestos) {
                echo "<br>";
              }

              $contImpuestos++;
            }

            echo "</span>" .
              "</td>" .
              "<td id='totalproducto_" . $rp['FKProducto'] . "'>" . number_format($totalProducto, 2) . "</td>" .
              "<input type='hidden' name='inp_total_producto[]' value='" . number_format($totalProducto, 2) . "' />" .
              "</tr>";
          }

          ?>
          <tr>
            <td colspan="3"></td>
            <td class="font-weight-bold">Subtotal:</td>
            <td colspan="2" style="text-align: right;">$ <span id="Subtotal"><?= number_format($Subtotal, 2) ?></span>
            </td>
          </tr>
          <tr>
            <td colspan="3"></td>
            <td class="font-weight-bold">Impuestos:</td>
            <td colspan="2"></td>
          </tr>
          <?php
          foreach ($impuestos as $imp) {
            $IniImpuesto = explode(" ", $imp[2]);
            echo "<tr id='" . $IniImpuesto[0] . "_" . $imp[0] . "'>" .
              "<td colspan='3'></td>" .
              "<td class='font-weight-bold'>" . $imp[2] . "</td>" .
              "<td colspan='2' style='text-align: right;'>$ <span id='Impuesto_" . $imp[0] . "' name='" . $imp[3] . "_" . $imp[4] . "' class='ImpuestoTot'>" . number_format($imp[1], 2) . "</span></td>" .
              "</tr>";
          }
          ?>
          <tr class="total">
            <td colspan="3" class="redondearAbajoIzq"></td>
            <td class="font-weight-bold">Total:</td>
            <td colspan="2" style="text-align: right;">$ <span id="Total"><?= number_format($ImporteTotal, 2) ?></span>
            </td>
          </tr>
        </table>


        <br>
        <div id="aceptarActualizar" style="text-align: center !important;">

          <?php

          if ($Estatus == 1) {
            echo '<button class="btn-custom btn-custom--blue">Cotización aceptada</button>';
          }
          if ($Estatus == 3) {
            echo '<button class="btn-custom btn-custom--red">Cotización cancelada</button>';
          }
          if ($Estatus == 4) {
            echo '<button class="btn-custom btn-custom--yellow">Cotización vencida</button>';
          }
          if ($Estatus == 5) {
            echo '<button type="button" class="btn-custom btn-custom--green" name="btnAgregarProducto" onclick="aceptarCotizacion(' . "'" . $idDes . "'" . ');" id="aceptar"><i class="far fa-check-square"></i> Aceptar cotizacion </button>';
          }
          ?>
        </div>
      </div>
    </div>
  </div>



  <!-- footer start -->
  <footer class="footer">
    <div class="copy-right_text">
      <div class="container">
        <div class="footer_border"></div>
        <div class="row">
          <div class="col-xl-12">
            <p class="copy_right text-center">
              Copyright &copy; Timlid <script>
                document.write(new Date().getFullYear());
              </script>
            </p>
          </div>
        </div>
      </div>
    </div>
  </footer>
  <!-- footer end  -->

  </div>

  <!-- JS here -->
  <script src="js/vendor/modernizr-3.5.0.min.js"></script>
  <script src="js/vendor/jquery-1.12.4.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/isotope.pkgd.min.js"></script>
  <script src="js/ajax-form.js"></script>
  <script src="js/waypoints.min.js"></script>
  <script src="js/jquery.counterup.min.js"></script>
  <script src="js/imagesloaded.pkgd.min.js"></script>
  <script src="js/scrollIt.js"></script>
  <script src="js/jquery.scrollUp.min.js"></script>
  <script src="js/wow.min.js"></script>
  <script src="js/nice-select.min.js"></script>
  <script src="js/jquery.slicknav.min.js"></script>
  <script src="js/jquery.magnific-popup.min.js"></script>
  <script src="js/plugins.js"></script>
  <script src="js/gijgo.min.js"></script>
  <!--contact js-->
  <script src="js/contact.js"></script>
  <script src="js/jquery.ajaxchimp.min.js"></script>
  <script src="js/jquery.form.js"></script>
  <script src="js/jquery.validate.min.js"></script>
  <script src="js/mail-script.js"></script>
  <script src="js/main.js"></script>
  <script src="../js/lobibox.min.js"></script>
  <script src="../js/sweet/sweetalert2.js"></script>
  <script src="../js/sweet/sweetalert2.js"></script>

  <script>
    function aceptarCotizacion(idCotizacion) {
      var FKUsuario = <?= $idUsuacrioCreacion ?>;
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
          text: "Se aceptará la cotización.",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: '<span class="verticalCenter">Aceptar cotización</span>',
          cancelButtonText: '<span class="verticalCenter">Cancelar</span>',
          reverseButtons: false,
        })
        .then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              type: 'POST',
              url: 'functions/aceptarCotizacion.php',
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
                    img: '../img/timdesk/checkmark.svg',
                    msg: "¡Se ha aceptado la cotización!"
                  });
                  $("#aceptarActualizar").html('<button class="btn-custom btn-custom--blue">Cotización aceptada</button>');
                } else {
                  Lobibox.notify("error", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../img/timdesk/warning_circle.svg',
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

    let estadoCotizacion = <?= $Estatus ?>;
    let idCotizacionG = <?= $FKCotizacion ?>;
    let nuevoEstatus;

    setInterval(function() {
      $.ajax({
        type: 'POST',
        url: 'functions/verificarEstadoCotizacion.php',
        data: {
          idCotizacion: idCotizacionG
        },
        success: function(data) {
          if (data != estadoCotizacion) {
            if (data == 1) {
              nuevoEstatus = '<button class="btn-custom btn-custom--blue" >Cotización aceptada</button>';
            }
            if (data == 3) {
              nuevoEstatus = '<button class="btn-custom btn-custom--red">Cotización cancelada</button>';
            }
            if (data == 4) {
              nuevoEstatus = '<button class="btn-custom btn-custom--yellow">Cotización vencida</button>';
            }
            if (data == 5) {
              nuevoEstatus = '<button type="button" class="btn-custom btn-custom--green" name="btnAgregarProducto" onclick="aceptarCotizacion(' + idCotizacionG + ');" id="aceptar"><i class="far fa-check-square"></i> Aceptar cotizacion </button>';
            }
            $("#aceptarActualizar").html(nuevoEstatus);
            estadoCotizacion = data;
          }
        }
      });
    }, 2000);
  </script>
</body>

</html>