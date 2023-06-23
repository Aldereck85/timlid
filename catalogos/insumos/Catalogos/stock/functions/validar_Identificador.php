<?php
require_once('../../../../../include/db-conn.php');
try{
  $json = new \stdClass();
  $identificador =  $_REQUEST['identificador'];
  $stmt = $conn->prepare('call spc_ValidarUnicoIdentificador(:identificador);');
  $stmt->execute(array(':identificador'=>$identificador));
  $row = $stmt->fetch();
  $identi = $row['Identificador'];
  $existe = $row['existe'];
  

  $json->identi = $identi;
  $json->existe = $existe;
  $json = json_encode($json);
  echo $json;
  //header('Location:editar_puesto.php');
}catch(PDOException $ex){
  echo $ex->getMessage();
}
