<?php
require_once '../include/db-conn.php';
$idDes = $_GET['id'];
$codigoDes = $_GET['codigo'];

include_once("../functions/functions.php");
$FKCotizacion = encryptor("decrypt", $idDes);
$codigo = encryptor("decrypt", $codigoDes);

$stmt = $conn->prepare('SELECT c.FKCliente, cl.NombreComercial,c.codigoCotizacion FROM cotizacion as c INNER JOIN clientes as cl ON cl.PKCliente = c.FKCliente WHERE c.PKCotizacion = :cotizacion ');
$stmt->bindValue(':cotizacion', $FKCotizacion);
$stmt->execute();
$row = $stmt->fetch();

if ($codigo != $row['codigoCotizacion']) {
  header("Location: error.php");
}

$idCliente = $row['FKCliente'];
$nombreCliente = $row['NombreComercial'];
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
        <a href="cotizacion.php?id=<?= $idDes ?>&codigo=<?= $codigoDes ?>">Cotización</a>
        <span class="divider"></span>
        <a href="#" style="color:rgb(21, 88, 155);">Chat</a>
      </div>
    </div>
  </header>
  <!-- header-end -->


  <?php
  $stmt = $conn->prepare('SELECT mc.PKMensajes_Cotizacion ,mc.TipoUsuario, mc.Mensaje, DATE_FORMAT(mc.FechaAgregado, "%d/%m/%Y %H:%i:%s") as Fecha ,cl.NombreComercial, CONCAT(e.Nombres," ", e.PrimerApellido) as Nombre_Empleado FROM mensajes_cotizacion as mc INNER JOIN cotizacion as c ON c.PKCotizacion = mc.FKCotizacion INNER JOIN clientes as cl ON cl.PKCliente = c.FKCliente INNER JOIN usuarios as u ON u.id = c.FKUsuarioCreacion INNER JOIN empleados as e ON e.PKEmpleado = u.id WHERE mc.FKCotizacion = :cotizacion ORDER BY  mc.PKMensajes_Cotizacion DESC');
  $stmt->bindValue(':cotizacion', $FKCotizacion);
  $stmt->execute();
  $row = $stmt->fetchAll();

  ?>
  <!-- welcome_docmed_area_start -->
  <div class="welcome_docmed_area" style="min-height: 600px;">
    <div class="container">
      <div class="row">
        <div class="col-xl-12 col-lg-12">
          <div class="doctors_title mb-55">
            <h3>Mensajes</h3>
            <a href="#" class="btn-custom btn-custom--blue" style="position: relative; right: 2%;float: right;margin: 5px 0;" data-toggle="modal" data-target="#agregar_Proyecto" onclick="cambiarTamanio();"><i class="far fa-comment-dots"></i> Agregar
              mensaje </a>
          </div>
          <br>
          <?php
          $idMensajeFinal = 0;
          $contador = 0;

          if (count($row) > 0) {

            echo '<ul class="timeline" id="add-timeline">';

            foreach ($row as $r) {

              if ($contador == 0) {
                $idMensajeFinal = $r['PKMensajes_Cotizacion'];
                $contador = 1;
              }

              if ($r['TipoUsuario'] == 1) {
                $nombreMensaje = $r['NombreComercial'];
                $clase = 'class="timeline-inverted"';
                $color = 'warning';
              } else {
                $nombreMensaje = $r['Nombre_Empleado'];
                $clase = '';
                $color = 'info';
              }
              echo '<li ' . $clase . '>
                                    <div class="timeline-badge ' . $color . '"><i class="glyphicon glyphicon-credit-card"></i></div>
                                    <div class="timeline-panel">
                                      <div class="timeline-heading">
                                        <h4 class="timeline-title">' . $nombreMensaje . '</h4>
                                      </div>
                                      <div class="timeline-body">
                                        <p>' . $r['Mensaje'] . '</p>
                                      </div>
                                      <hr>
                                      <div class="row">
                                        <div class="col-md-12" align="right">
                                          <small>' . $r['Fecha'] . '</small>
                                        </div>
                                      </div>
                                    </div>
                                  </li>';
            }

            echo "</ul>";
          } else {
            echo '<ul id="add-timeline">';
            echo "<h3 id='nuevo_mensaje'><center>AUN NO HAY MENSAJES EN ESTA COTIZACIÓN</center></h3>";
            echo '</ul>';
          }
          ?>
        </div>
      </div>
    </div>
  </div>
  <!-- welcome_docmed_area_end -->

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
  <!-- Agregar mensaje-->
  <div id="agregar_Proyecto" class="modal fade" style="z-index: 100000000">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="" method="POST" id="frProyecto">
          <div class="modal-header">
            <h4 class="modal-title">Portal de clientes</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
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
            <input type="button" class="btn-custom btn-custom--border-blue" data-dismiss="modal" value="Cancelar" id="cancelarMensaje">
            <input type="button" class="btn-custom btn-custom--blue" id="btnGuardar" value="Agregar">
          </div>
        </form>
      </div>
    </div>
  </div>
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
  <script>
    var cotizacion = <?= $FKCotizacion ?>;
    $("#btnGuardar").click(function() {
      var mensaje = $('#txtMensaje').val().trim();
      if (mensaje === '') {
        $("#txtMensaje")[0].reportValidity();
        $("#txtMensaje")[0].setCustomValidity('Completa este campo.');
        return;
      }
      $("#btnGuardar").attr("disabled", true);
      $("#cancelarMensaje").attr("disabled", true);
      <?php date_default_timezone_set('America/Mexico_City'); ?>
      var fecha = "<?php echo date('d/m/Y H:i:s', time()); ?>";
      var nombreCliente = '<?php echo $nombreCliente; ?>';
      var idCliente = <?php echo $idCliente; ?>;
      var myData = {
        "Mensaje": mensaje,
        "Cotizacion": cotizacion,
        "Fecha": fecha
      };
      $.ajax({
        url: "functions/agregarMensaje.php",
        type: "POST",
        data: myData,
        success: function(data, status, xhr) {
          console.log(data);
          var datos = JSON.parse(data);
          if (datos.estatus == 'exito') {
            var agregarLista = '<li class="timeline-inverted">' +
              '<div class="timeline-badge warning"><i class="glyphicon glyphicon-credit-card"></i></div>' +
              '<div class="timeline-panel">' +
              '<div class="timeline-heading">' +
              '<h4 class="timeline-title">' + nombreCliente + '</h4>' +
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
            $("#mostrarProyecto").css("display", "block");
            Lobibox.notify("success", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../img/timdesk/checkmark.svg',
              msg: "¡Mensaje enviado!"
            });
            $('#agregar_Proyecto').modal('toggle');
            $('#txtMensaje').val("");
            idMensajeFinal = datos.idMensaje;
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

    let idMensajeFinal = <?= $idMensajeFinal ?>;
    let nombreResponsable, ladoMostrar, colorMostrar;

    setInterval(function() {
      $.ajax({
        type: 'POST',
        url: 'functions/verificarMensajesCotizacion.php',
        data: {
          idCotizacion: cotizacion,
          idMensajeFinal: idMensajeFinal
        },
        success: function(data) {
          var obj = jQuery.parseJSON(data);
          $.each(obj, function(key, value) {
            if (value.TipoUsuario == 1) {
              nombreResponsable = value.NombreComercial;
              ladoMostrar = 'class="timeline-inverted"';
              colorMostrar = 'warning';
            } else {
              nombreResponsable = value.Nombre_Empleado;
              ladoMostrar = '';
              colorMostrar = 'info';
            }
            var agregarLista = '<li ' + ladoMostrar + '>' +
              '<div class="timeline-badge ' + colorMostrar + '"><i class="glyphicon glyphicon-credit-card"></i></div>' +
              '<div class="timeline-panel">' +
              '<div class="timeline-heading">' +
              '<h4 class="timeline-title">' + nombreResponsable + '</h4>' +
              '</div>' +
              '<div class="timeline-"body">' +
              '<p>' + value.Mensaje + '</p>' +
              '</div>' +
              '<hr>' +
              '<div class="row">' +
              '<div class="col-md-12" align="right">' +
              '<small>' + value.Fecha + '</small>' +
              '</div>' +
              '</div>' +
              '</div>' +
              '</li>';
            $("#add-timeline").addClass("timeline");
            $("#nuevo_mensaje").remove();
            $("#add-timeline").prepend(agregarLista);
            idMensajeFinal = value.PKMensajes_Cotizacion;
          });
        }
      });
    }, 2000);
  </script>
</body>

</html>