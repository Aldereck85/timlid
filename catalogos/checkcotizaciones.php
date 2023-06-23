<?php
include "../$rutatb" . "include/db-conn.php";

date_default_timezone_set('America/Mexico_City');
$timestamp = date('Y-m-d H:i:s');

$stmt = $conn->prepare('SELECT c.PKCotizacion, c.FKUsuarioCreacion, c.FKUsuarioEdicion, c.estatus_cotizacion_id FROM cotizacion AS c WHERE c.empresa_id = ' . $_SESSION['IDEmpresa'] . ' AND c.estatus_cotizacion_id = 5');
$stmt->execute();
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
if ($row) {
  foreach ($row as $r) {
    if ($r['estatus_cotizacion_id'] == "Pendiente") {
      if (strtotime(date("Y-m-d")) > strtotime($r['FechaVencimiento'])) {
        /* VERIFICAR QUE NO TENGA MAS DE UNA NOTIFICACION DE LA COTIZACION EN EL MISMO MES */
        $stmt = $conn->prepare('SELECT created_at FROM notificaciones WHERE tipo_notificacion = :tipoNot AND detalle_tipo_notificacion = :detalleNot AND id_elemento = :idElem ORDER BY id DESC LIMIT 1');
        $stmt->execute([':tipoNot' => 4, ':detalleNot' => 8, ':idElem' => $r['PKCotizacion']]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$res) {
          insertCotiNot($conn, $r['PKCotizacion'], $timestamp);
        } else {
          $today = date('Y-m-d');
          $dateCot = strtotime($res['created_at']);
          $dateCot =  date('Y-m-d', $dateCot);
          $cotPlusOneMonth = date('Y-m-d', strtotime("+1 months", strtotime($dateCot)));
          if ($today > $cotPlusOneMonth) {
            insertCotiNot($conn, $r['PKCotizacion'], $timestamp);
          }
        }
      }
    }
  }
}

function insertCotiNot($conn, $elemento, $timestamp)
{
  /* SELECCIONAMOS LOS USUARIOS DE ESA COTIZACION */
  $stmt = $conn->prepare('SELECT FKUsuarioCreacion, FKUsuarioEdicion FROM cotizacion WHERE PKCotizacion = :cotizacion');
  $stmt->execute([':cotizacion' => $elemento]);
  $res = $stmt->fetch(PDO::FETCH_ASSOC);

  /* INSERTAMOS LA NOTIFICACION EN LA BD */
  $query = 'INSERT INTO notificaciones (tipo_notificacion, detalle_tipo_notificacion, id_elemento, created_at, usuario_recibe) VALUES (:tipoNot, :detalleNot, :idElem, :fecha, :usuarioRecibe)';
  if ($res['FKUsuarioCreacion'] === $res['FKUsuarioEdicion']) {
    $stmt = $conn->prepare($query);
    $stmt->execute([':tipoNot' => 4, ':detalleNot' => 8, ':idElem' => $elemento, ':fecha' => $timestamp, ':usuarioRecibe' => $res['FKUsuarioCreacion']]);
  } else {
    $stmt = $conn->prepare($query);
    $stmt->execute([':tipoNot' => 4, ':detalleNot' => 8, ':idElem' => $elemento, ':fecha' => $timestamp, ':usuarioRecibe' => $res['FKUsuarioCreacion']]);

    $stmt = $conn->prepare($query);
    $stmt->execute([':tipoNot' => 4, ':detalleNot' => 8, ':idElem' => $elemento, ':fecha' => $timestamp, ':usuarioRecibe' => $res['FKUsuarioEdicion']]);
  }
}
