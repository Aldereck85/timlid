<?php
session_start();
require_once '../../include/db-conn.php';
date_default_timezone_set('America/Mexico_City');

if (isset($_SESSION["Usuario"])) {
  require_once '../../include/db-conn.php';
  $user = $_SESSION["Usuario"];
  $today = date('Y-m-d');
  $lastMonth = date('Y-m-d', strtotime("-1 months", strtotime($today)));

  /* CUMPLEAÑOS DEL MES */
  $stmt = $conn->prepare('SELECT notificacion.id, detalle.descripcion, notificacion.id_elemento, notificacion.visto, notificacion.id_sub_elemento, notificacion.visto, notificacion.usuario_creo, empleado.nombres, usuario.imagen, DATE(notificacion.created_at) as fecha, TIME(notificacion.created_at) AS hora
  FROM notificaciones AS notificacion
  LEFT JOIN detalle_tipo_notificacion AS detalle ON notificacion.detalle_tipo_notificacion = detalle.id
  LEFT JOIN usuarios AS usuario ON notificacion.usuario_creo = usuario.id
  LEFT JOIN empleados AS empleado ON usuario.id = empleado.PKEmpleado
  WHERE notificacion.usuario_recibe = :idUsuario AND notificacion.created_at > :lastmonth ORDER BY notificacion.id DESC');
  $stmt->execute([':idUsuario' => $_SESSION['PKUsuario'], ':lastmonth' => $lastMonth]);
  //$numberNotification = $stmt->rowCount() > 0 ? "<span id='contadorTareas' class='badge badge-pill badge-counter badge-circle'>" . $stmt->rowCount() . "</span>" : '';
  $notofocations = $stmt->fetchAll(PDO::FETCH_ASSOC);

  function resolveNotificationsPage($item)
  {
    $mensaje = '';
    $enlace = '';
    $visto = $item['visto'] == '0' ? 'card-notification--no-read' : 'card-notification--read';
    $avatar = '';
    $usrCreo = '';
    if ($item['usuario_creo']) {
      $usrCreo = $item['nombres'];
      $avatar = $item['imagen'] ? $item['imagen'] : '../../img/timUser.png';
    }
    switch ($item['descripcion']) {
      case 'Nuevo proyecto':
        $enlace = $_ENV['APP_URL'] . 'catalogos/tareas/timDesk/index.php?id=' . $item['id_elemento'];
        $mensaje = 'Tienes un nuevo proyecto';
        break;
      case 'Nueva tarea':
        $enlace = $_ENV['APP_URL'] . 'catalogos/tareas/timDesk/index.php?id=' . $item['id_elemento'] . '&task=' . $item['id_sub_elemento'];
        $mensaje = $item['nombres'] . ' te asigno una tarea';
        break;
      case 'Verificar tareas':
        $enlace = $_ENV['APP_URL'] . 'catalogos/tareas/timDesk/index.php?id=' . $item['id_elemento'];
        $mensaje = $item['nombres'] . ' marcó completada una de tus tareas';
        break;
      case 'Tarea no verificada':
        $enlace = $_ENV['APP_URL'] . 'catalogos/tareas/timDesk/index.php?id=' . $item['id_elemento'];
        $mensaje = $item['nombres'] . ' marcó una de tus tareas como no completada';
        break;
      case 'Nuevo mensaje':
        $enlace = $_ENV['APP_URL'] . 'catalogos/tareas/timDesk/index.php?id=' . $item['id_elemento'] . '&task=' . $item['id_sub_elemento'] . '&chat=true';
        $mensaje = 'Tienes un mensaje en el chat';
        break;
      case 'Nueva sub tarea':
        $enlace = $_ENV['APP_URL'] . 'catalogos/tareas/timDesk/index.php?id=' . $item['id_elemento'] . '&task=' . $item['id_sub_elemento'] . '&subtask=true';
        $mensaje = $item['nombres'] . ' te asigno una sub tarea';
        break;
      case 'Verificar sub tarea':
        $enlace = $_ENV['APP_URL'] . 'catalogos/tareas/timDesk/index.php?id=' . $item['id_elemento'] . '&task=' . $item['id_sub_elemento'] . '&subtask=true';
        $mensaje = $item['nombres'] . ' verifico una de tus sub tareas';
        break;
      case 'Sub tarea no verificada':
        $enlace = $_ENV['APP_URL'] . 'catalogos/tareas/timDesk/index.php?id=' . $item['id_elemento'] . '&task=' . $item['id_sub_elemento'] . '&subtask=true';
        $mensaje = $item['nombres'] . ' marcó una de tus sub tareas como no completada';
        break;
      case 'Sub tareas verificadas':
        $enlace = $_ENV['APP_URL'] . 'catalogos/tareas/timDesk/index.php?id=' . $item['id_elemento'] . '&task=' . $item['id_sub_elemento'] . '&subtask=true';
        $mensaje = $item['nombres'] . ' marcó las sub tareas completadas';
        break;
      case 'Sub tareas no verificadas':
        $enlace = $_ENV['APP_URL'] . 'catalogos/tareas/timDesk/index.php?id=' . $item['id_elemento'] . '&task=' . $item['id_sub_elemento'] . '&subtask=true';
        $mensaje = $item['nombres'] . ' marcó tus sub tareas como no completadas';
        break;
      case 'Cotización aceptada':
        $enlace = $_ENV['APP_URL'] . 'catalogos/cotizaciones/detalleCotizacion.php?id=' . $item['id_elemento'];
        $mensaje = $item['nombres'] . ' acepto la cotización';
        break;
      case 'Cotización vencida':
        $enlace = $_ENV['APP_URL'] . 'catalogos/cotizaciones/detalleCotizacion.php?id=' . $item['id_elemento'];
        $mensaje = 'La cotización vencio';
        break;
      case 'Cotización actualizada':
        $enlace = $_ENV['APP_URL'] . 'catalogos/cotizaciones/detalleCotizacion.php?id=' . $item['id_elemento'];
        $mensaje = 'Se actualizo una cotización';
        break;
      case 'Mensaje cotización':
        $enlace = $_ENV['APP_URL'] . 'catalogos/cotizaciones/detalleCotizacion.php?id=' . $item['id_elemento'] . '&chat=true';
        $mensaje = 'Recibiste un mensaje en una cotización';
        break;
      case 'Nueva reunión':
        $fechastr = date('Y-m-d', strtotime($item['id_sub_elemento']));
        $fechaStamp = strtotime($fechastr);
        //$fechastr = date('Y-m-d', $fechastam);
        $enlace = $_ENV['APP_URL'] . 'catalogos/calendario_crm/index.php?meet=' . $fechaStamp;
        $mensaje = 'Tienes una nueva reunion';
        break;
      case 'Reunión cancelada':
        $enlace = $_ENV['APP_URL'] . 'catalogos/calendario_crm';
        $mensaje = 'Se cancelo una de tus reuniones';
        break;
      case 'Reunión actualizada':
        $fechastr = date('Y-m-d', strtotime($item['id_sub_elemento']));
        $fechaStamp = strtotime($fechastr);
        $enlace = $_ENV['APP_URL'] . 'catalogos/calendario_crm/index.php?meet=' . $fechaStamp;
        $mensaje = 'Se actualizo una de tus reuniones';
        break;
      case 'Nuevo pedido':
        $enlace = $_ENV['APP_URL'] . 'catalogos/pedidos/detallePedido.php?id=' . $item['id_elemento'];
        $mensaje = 'Se generó un nuevo pedido';
        break;
      case 'Pedido cancelado':
        $enlace = $_ENV['APP_URL'] . 'catalogos/pedidos/detallePedido.php?id=' . $item['id_elemento'];
        $mensaje = $item['nombres'] . ' cancelo un pedido';
        break;
      case 'Pedido cerrado':
        $enlace = $_ENV['APP_URL'] . 'catalogos/pedidos/detallePedido.php?id=' . $item['id_elemento'];
        $mensaje = $item['nombres'] . ' cerro un pedido';
        break;
      case 'Pedido sin surtir':
        $enlace = $_ENV['APP_URL'] . 'catalogos/pedidos/detallePedido.php?id=' . $item['id_elemento'];
        $mensaje = 'No se a surtido el pedido';
        break;
      case 'Nueva factura':
        $enlace = $_ENV['APP_URL'] . 'catalogos/facturacion/detalle_factura.php?idFactura=' . $item['id_elemento'];
        $mensaje = 'Nueva factura';
        break;
      default:
        break;
    }
    $fecha = implode('/', array_reverse(explode('-', $item['fecha'])));
    $horaArray = explode(':', $item['hora']);
    array_pop($horaArray);
    $hora = implode(':', $horaArray);
    return ['mensaje' => $mensaje, 'enlace' => $enlace, 'visto' => $visto, 'avatar' => $avatar, 'usrCreo' => $usrCreo, 'fecha' => $fecha, 'hora' => $hora];
  }
} else {
  header("location:../../dashboard.php");
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" type="image/png" href="../../img/header/bluTimlid.png">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Timlid | Notificaciones</title>

  <!-- ESTILOS -->
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../css/styles.css" rel="stylesheet">
  <link rel="stylesheet" href="../../css/notificaciones.css">
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- JS -->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</head>

<body id="page-top" class="sidebar-toggled">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
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
        $icono = '../../img/icons/ICONO_ACCION.svg';
        $titulo = 'Centro de notificaciones';
        require_once $rutatb . 'topbar.php';
        ?>
        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- DataTales Example -->
          <div class="card mb-4">
            <div class="card-body">
              <!-- <div class="container-fluid">
                <div class="row">
                  <div class="col-12 col-md-6 col-lg-3"> -->
              <div class="container-notifications">
                <?php foreach ($notofocations as $notification) {
                  $resultNoti = resolveNotificationsPage($notification);
                  $nameNoti = $resultNoti['usrCreo'] ? '<p class="card-notification__name">' . $resultNoti['usrCreo'] . '</p>' : '';
                  $avatarNoti = $resultNoti['avatar'] ? '<img src="' . $_ENV['RUTA_ARCHIVOS_READ'] . $_SESSION['IDEmpresa'] . '/img/' . $resultNoti['avatar'] . '" alt="" width="40px" class="card-notification__avatar">' : '';
                ?>
                  <span class="card-notification shadow-sm notification-item pointer <?= $resultNoti['visto'] ?>" data-href="<?= $resultNoti['enlace'] ?>" data-notification="<?= $notification['id'] ?>">
                    <?php if ($avatarNoti && $nameNoti) { ?>
                      <div class="card-notification__header">
                        <?= $avatarNoti ?>
                        <?= $nameNoti ?>
                      </div>
                    <?php } ?>
                    <div class="card-notification__info">
                      <p><?= $resultNoti['mensaje'] ?></p>
                      <p class="date-notification"><?= $resultNoti['fecha'] ?> - <?= $resultNoti['hora'] ?></p>
                    </div>
                  </span>
                <?php } ?>
              </div>
              <!-- </div>
                </div>
              </div> -->
            </div>
          </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <?php
      $rutaf = "../";
      require_once $rutaf . 'footer.php';
      ?>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/scripts.js"></script>
</body>

</html>