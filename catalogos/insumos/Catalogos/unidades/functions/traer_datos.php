<?php
require_once('../../../../../include/db-conn.php');
try{
  $json = new \stdClass();
  $id =  $_REQUEST['id'];
  $stmt = $conn->prepare('call spc_UnidadesMedidaUnico(:id);');
  $stmt->execute(array(':id'=>$id));
  $row = $stmt->fetch();
  $unidadMedida = $row['UnidadMedida'];
  $noEliminar = $row['noEliminar'];

  $json->unidadMedida = $unidadMedida;
  $json->noEliminar = $noEliminar;
  $json = json_encode($json);
  echo $json;
  //header('Location:editar_puesto.php');
}catch(PDOException $ex){
  echo $ex->getMessage();
}
