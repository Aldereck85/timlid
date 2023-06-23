<?php
session_start();
date_default_timezone_set('America/Mexico_City');

$jwt_ruta = "../../../";
require_once '../../jwt.php';

$token = $_POST["csr_token_8UY8N"];

if (empty($_SESSION['token_ld10d'])) {
  echo "fallo";
  return;
}

if (!hash_equals($_SESSION['token_ld10d'], $token)) {
  echo "fallo";
  return;
}

require_once('../../../include/db-conn.php');
date_default_timezone_set('America/Mexico_City');

if (isset($_POST['idOrdenPedido'])) {
  $id =  $_POST['idOrdenPedido'];
  $FKUsuario = $_POST['FKUsuario'];
  $FechaIngreso = date("Y-m-d H:i:s");

  try {

    $stmt = $conn->prepare('SELECT estatus_orden_pedido_id FROM orden_pedido_por_sucursales WHERE id = :id AND empresa_id = ' . $_SESSION['IDEmpresa']);
    $stmt->execute(array(':id' => $id));
    $row = $stmt->fetch();

    if ($row['estatus_orden_pedido_id'] == 1 || $row['estatus_orden_pedido_id'] == 2) {

      $stmt = $conn->prepare('UPDATE orden_pedido_por_sucursales SET estatus_orden_pedido_id = 8 WHERE id = :id AND empresa_id = ' . $_SESSION['IDEmpresa']);

      if ($stmt->execute(array(':id' => $id))) {

        $stmt = $conn->prepare("INSERT INTO bitacora_orden_pedido (usuario_id, created_at, updated_at, mensaje_id, orden_pedido_id) VALUES (:fkusuario, :fechamovimiento, :fechamovimientoupdate,:fkmensaje, :fkordenpedido)");
        $stmt->bindValue(':fkusuario', $FKUsuario);
        $stmt->bindValue(':fechamovimiento', $FechaIngreso);
        $stmt->bindValue(':fechamovimientoupdate', $FechaIngreso);
        $stmt->bindValue(':fkmensaje', 22);
        $stmt->bindValue(':fkordenpedido', $id);
        $stmt->execute();
        setnotification($conn, $id);
        echo "exito";
      } else {
        echo "fallo";
      }
    } else {
      echo "fallo-cancelacion";
    }
  } catch (Exception $ex) {
    echo $ex->getMessage();
  }
}

function setnotification($conn, $idelemento)
{
  /* NOTIFICACIONES */
  $timestamp = date('Y-m-d H:i:s');
  /* SELECCIONAMOS LOS USUARIOS DE TIPO ALMACEN */
  $stmt = $conn->prepare('SELECT id FROM usuarios WHERE empresa_id = :empresaId AND role_id = :roleId');
  $stmt->execute([':empresaId' => $_SESSION['IDEmpresa'], ':roleId' => 6]);
  $empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);

  foreach ($empleados as $empleado) {
    /* INSERTAMOS LA NOTIFICACION EN LA BD */
    $stmt = $conn->prepare('INSERT INTO notificaciones (tipo_notificacion, detalle_tipo_notificacion, id_elemento, created_at, usuario_creo, usuario_recibe) VALUES (:tipoNot, :detaleNot, :idElem, :fecha, :usrCreo, :ursRecibe)');
    $stmt->execute([':tipoNot' => 6, ':detaleNot' => 19, ':idElem' => $idelemento, ':fecha' => $timestamp, ':usrCreo' => $_SESSION['PKUsuario'], ':ursRecibe' => $empleado['id']]);
  }
}
