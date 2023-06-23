<?php
require_once('../../../../include/db-conn.php');
try{
  $json = new \stdClass();
  $id =  $_REQUEST['id'];
  
  $stmt = $conn->prepare('SELECT * FROM puestos  WHERE id= :id');
  $stmt->execute(array(':id'=>$id));

  $row = $stmt->fetch();
  
  $html = $row['puesto'];
  
  $json->html = $html;
 
  $json = json_encode($json);
  echo $json;

}catch(PDOException $ex){
  echo $ex->getMessage();
}

$con = null;
$db = null;
$stmt = null;