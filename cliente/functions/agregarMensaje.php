<?php
date_default_timezone_set('America/Mexico_City');
session_start();
require_once('../../include/db-conn.php');
$json = new \stdClass();
$timestamp = date('Y-m-d H:i:s');
$mensaje = $_POST['Mensaje'];
$cotizacion = $_POST['Cotizacion'];
$fecha = $_POST['Fecha'];

$fechaarray = explode(" ", $fecha);
$fechasolo = explode("/", $fechaarray[0]);
$fechacorrecta = $fechasolo[2] . "-" . $fechasolo[1] . "-" . $fechasolo[0] . " " . $fechaarray[1];

try {
  /* INSERTAMOS EL MENSAJE EN LA BD */
  $stmt = $conn->prepare('INSERT INTO mensajes_cotizacion (FKCotizacion,TipoUsuario, Mensaje, FechaAgregado) VALUES (:cotizacion,1,:mensaje, :fecha)');
  $insertedMsj = $stmt->execute([':cotizacion' => $cotizacion, ':mensaje' => $mensaje, ':fecha' => $fechacorrecta]);
  $idMensaje = $conn->lastInsertId();

  /* SELECCIONAMOS LOS USUARIOS DE ESA COTIZACION */
  $stmt = $conn->prepare('SELECT FKUsuarioCreacion, FKUsuarioEdicion FROM cotizacion WHERE PKCotizacion = :cotizacion');
  $stmt->execute([':cotizacion' => $cotizacion]);
  $res = $stmt->fetch(PDO::FETCH_ASSOC);

  /* INSERTAMOS LA NOTIFICACION EN LA BD */
  $queryNot = 'INSERT INTO notificaciones (tipo_notificacion, detalle_tipo_notificacion, id_elemento, created_at, usuario_recibe) VALUES (:tipoNot, :detaleNot, :idElem, :fecha, :usrRecibe)';
  $insertedNot = false;
  if ($res['FKUsuarioCreacion'] === $res['FKUsuarioEdicion']) {
    $stmt = $conn->prepare($queryNot);
    $insertedNot = $stmt->execute([':tipoNot' => 4, ':detaleNot' => 9, ':idElem' => $cotizacion, ':fecha' => $timestamp, ':usrRecibe' => $res['FKUsuarioCreacion']]);
  } else {
    $stmt = $conn->prepare($queryNot);
    $insertedNot = $stmt->execute([':tipoNot' => 4, ':detaleNot' => 9, ':idElem' => $cotizacion, ':fecha' => $timestamp, ':usrRecibe' => $res['FKUsuarioCreacion']]);

    $stmt = $conn->prepare($queryNot);
    $insertedNot = $stmt->execute([':tipoNot' => 4, ':detaleNot' => 9, ':idElem' => $cotizacion, ':fecha' => $timestamp, ':usrRecibe' => $res['FKUsuarioEdicion']]);
  }
  if ($insertedMsj && $insertedNot) {
    $json->idMensaje = $idMensaje;
    $json->estatus = "exito";
    $json = json_encode($json);
    echo $json;
  } else {
    $json->estatus = "fallo";
    $json = json_encode($json);
    echo $json;
  }
} catch (PDOException $ex) {
  echo $ex->getMessage();
}
