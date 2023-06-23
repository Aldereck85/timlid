<?php
session_start();

require_once('../../../include/db-conn.php');
date_default_timezone_set('America/Mexico_City');

if (isset($_REQUEST['idVenta'])) {
  $id =  $_REQUEST['idVenta'];
  $FKUsuario = $_SESSION["PKUsuario"];
  $FechaIngreso = date("Y-m-d H:i:s");

  try {
    $conn->beginTransaction();

    $stmt = $conn->prepare('UPDATE cotizacion c INNER JOIN ventas_directas vd ON c.id_cotizacion_empresa = vd.referencia_cotizacion SET c.estatus_factura_id = 2 WHERE vd.PKVentaDirecta = :id AND c.empresa_id = ' . $_SESSION['IDEmpresa']);
    $stmt->bindValue(':id', $id);
    $stmt->execute();

  } catch (Exception $ex) {
    echo $ex->getMessage();
    $conn->rollBack();
    echo "fallo";
    exit;
  }
}
