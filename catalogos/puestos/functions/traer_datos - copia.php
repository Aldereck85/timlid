<?php
require_once('../../../include/db-conn.php');
try{
  $id =  $_REQUEST['id'];
  $stmt = $conn->prepare('SELECT * FROM puestos WHERE PKPuesto= :id');
  $stmt->execute(array(':id'=>$id));
  $row = $stmt->fetchAll(PDO::FETCH_OBJ);
  //$row = $stmt->rowCount();
  echo json_encode($row);
  //header('Location:editar_puesto.php');
}catch(PDOException $ex){
  echo $ex->getMessage();
}
