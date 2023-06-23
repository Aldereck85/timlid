<?php
include "../$rutatb" . "include/db-conn.php";

date_default_timezone_set('America/Mexico_City');
$timestamp = date('Y-m-d H:i:s');

$stmt = $conn->prepare('SELECT opps.id, opps.fecha_captura FROM orden_pedido_por_sucursales AS opps WHERE opps.empresa_id = ' . $_SESSION['IDEmpresa'] . ' AND (opps.estatus_orden_pedido_id = 1 OR opps.estatus_orden_pedido_id = 2 OR opps.estatus_orden_pedido_id = 3 OR opps.estatus_orden_pedido_id = 4)');
$stmt->execute();
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* SELECCIONAMOS LOS USUARIOS DE TIPO ALMACEN Y ADMINISTRADORES */
$stmt = $conn->prepare('SELECT id FROM usuarios WHERE empresa_id = :empresaId AND (role_id = 6 OR role_id = 2)');
$stmt->execute([':empresaId' => $_SESSION['IDEmpresa']]);
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($row) {
  foreach ($row as $r) {
    $today = date('Y-m-d');
    $datePed = strtotime($r['fecha_captura']);
    $datePed =  date('Y-m-d', $datePed);
    $todayMinusOneMonth = date('Y-m-d', strtotime("-1 months", strtotime($today)));
    if ($datePed < $todayMinusOneMonth) {
      /* VERIFICAR QUE NO TENGA MAS DE UNA NOTIFICACION DE LA COTIZACION EN EL MISMO MES */
      $stmt = $conn->prepare('SELECT created_at FROM notificaciones WHERE tipo_notificacion = :tipoNot AND detalle_tipo_notificacion = :detalleNot AND id_elemento = :idElem ORDER BY id DESC LIMIT 1');
      $stmt->execute([':tipoNot' => 6, ':detalleNot' => 21, ':idElem' => $r['id']]);
      $res = $stmt->fetch(PDO::FETCH_ASSOC);
      if (!$res) {
        insertPediNot($conn, $r['id'], $usuarios, $timestamp);
      } else {
        $datePedNot = strtotime($res['created_at']);
        $datePedNot =  date('Y-m-d', $datePedNot);
        $PedPlusOneMonthNot = date('Y-m-d', strtotime("+1 months", strtotime($datePedNot)));
        if ($today > $PedPlusOneMonthNot) {
          insertPediNot($conn, $r['id'], $usuarios, $timestamp);
        }
      }
    }
  }
}

function insertPediNot($conn, $elemento, $usuarios, $timestamp)
{
  /* INSERTAMOS LA NOTIFICACION EN LA BD */
  foreach ($usuarios as $usuario) {
    $stmt = $conn->prepare('INSERT INTO notificaciones (tipo_notificacion, detalle_tipo_notificacion, id_elemento, created_at, usuario_recibe) VALUES (:tipoNot, :detaleNot, :idElem, :fecha, :usuarioRecibe)');
    $stmt->execute([':tipoNot' => 6, ':detaleNot' => 21, ':idElem' => $elemento, ':fecha' => $timestamp, ':usuarioRecibe' => $usuario['id']]);
  }
}
