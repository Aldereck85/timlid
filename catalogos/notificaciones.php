<?php
include "../$rutatb" . "include/db-conn.php";
date_default_timezone_set('America/Mexico_City');

function resolveNotifications($item)
{
  $mensaje = '';
  $enlace = '';
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
      $mensaje = $item['nombres'] . ' envío un mensaje en el chat';
      break;
    case 'Nueva respuesta':
      $enlace = $_ENV['APP_URL'] . 'catalogos/tareas/timDesk/index.php?id=' . $item['id_elemento'] . '&task=' . $item['id_sub_elemento'] . '&chat=true';
      $mensaje = $item['nombres'] . ' respondio un mensaje en el chat';
      break;
    case 'Me gusta':
      $enlace = $_ENV['APP_URL'] . 'catalogos/tareas/timDesk/index.php?id=' . $item['id_elemento'] . '&task=' . $item['id_sub_elemento'] . '&chat=true';
      $mensaje = $item['nombres'] . ' le gusta tu mensaje';
      break;
    case 'No me gusta':
      $enlace = $_ENV['APP_URL'] . 'catalogos/tareas/timDesk/index.php?id=' . $item['id_elemento'] . '&task=' . $item['id_sub_elemento'] . '&chat=true';
      $mensaje = $item['nombres'] . ' no le gusta tu mensaje';
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
  return ['mensaje' => $mensaje, 'enlace' => $enlace, 'fecha' => $fecha, 'hora' => $hora];
}

$today = date('Y-m-d');
$lastMonth = date('Y-m-d', strtotime("-1 months", strtotime($today)));

$query = sprintf('SELECT notificacion.id, detalle.descripcion, notificacion.id_elemento, notificacion.visto, notificacion.id_sub_elemento, empleado.nombres, DATE(notificacion.created_at) as fecha, TIME(notificacion.created_at) AS hora
FROM notificaciones AS notificacion
LEFT JOIN detalle_tipo_notificacion AS detalle ON notificacion.detalle_tipo_notificacion = detalle.id
LEFT JOIN usuarios AS usuario ON notificacion.usuario_creo = usuario.id
LEFT JOIN empleados AS empleado ON usuario.id = empleado.PKEmpleado
WHERE notificacion.usuario_recibe = :idUsuario AND notificacion.visto = 0 AND notificacion.created_at > :lastmonth ORDER BY notificacion.id DESC');
$stmt = $conn->prepare($query);
$stmt->execute([':idUsuario' => $_SESSION['PKUsuario'], ':lastmonth' => $lastMonth]);
$numberNotification = $stmt->rowCount() > 0 ? "<span id='contadorTareas' class='badge badge-pill badge-counter badge-circle'>" . $stmt->rowCount() . "</span>" : '';
$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
$topUrl = $_ENV['APP_URL'] ?? 'https://app.timlid.com/';
$maxNumberNot = count($res) < 4 ? count($res) : 4;
?>

<li id="notificationContainer" class="nav-item dropdown no-arrow mx-1">
  <input id="url-top" type="hidden" value="<?= $topUrl ?>">
  <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <img src="../<?= $rutatb ?>img/notificaciones/ICONO ALERTAS_Mesa de trabajo 1.svg" width="25px" />
    <?= $numberNotification ?>
  </a>

  <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
    <h3 class="dropdown-header">
      Notificaciones
    </h3>
    <?php for ($i = 0; $i < $maxNumberNot; $i++) {
      $result = resolveNotifications($res[$i]);
    ?>
      <span class="dropdown-item d-flex align-items-center pointer notification-item" data-href="<?= $result['enlace'] ?>" data-notification="<?= $res[$i]['id'] ?>">
        <div class="mr-3">
          <div class="icon-circle">
            <img src="../<?= $rutatb ?>img/notificaciones/ICONO TAREAS_Mesa de trabajo 1.svg" width="25px" />
          </div>
        </div>
        <div id="notification-latest">
          <div id="fechaTarea" class="title-notification"><?= $res[$i]['descripcion'] ?></div>
          <span id="tarea" class="font-weight-bold"><?= $result['mensaje'] ?></span>
          <span class="date-notification"> <?= $result['fecha'] ?> - <?= $result['hora'] ?></span>
        </div>
      </span>
    <?php } ?>
    <div class="show-center-noti">
      <a href="<?= $topUrl ?>catalogos/notificaciones">Ir al centro de notificaciones</a>
    </div>
  </div>
</li>