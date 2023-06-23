<?php
require_once('../../../../../include/db-conn.php');
try{
  $json = new \stdClass();
  $id =  $_REQUEST['id'];
  $stmt = $conn->prepare('call spc_SalidaUnica(:id);');
  $stmt->execute(array(':id'=>$id));
  $row = $stmt->fetch();
  $pkHistorialInsumo = $row['PKHistorialInsumos'];
  $fkInsumoStock = $row['FKInsumosStock'];
  $cantidadMovimiento = $row['CantidadMovimiento'];
  $descripcion = $row['Descripcion'];
  $cantidadExistencia = $row['CantidadExistencia'];
  $cantidadMinima = $row['CantidadMinima'];

  $json->pkHistorialInsumo = $pkHistorialInsumo;
  $json->fkInsumoStock = $fkInsumoStock;
  $json->cantidadMovimiento = $cantidadMovimiento;
  $json->descripcion = $descripcion;
  $json->cantidadExistencia = $cantidadExistencia;
  $json->cantidadMinima = $cantidadMinima;
  $json = json_encode($json);
  echo $json;
  //header('Location:editar_puesto.php');
}catch(PDOException $ex){
  echo $ex->getMessage();
}
