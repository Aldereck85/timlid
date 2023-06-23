<?php
require_once('../../../include/db-conn.php');
try{
  $json = new \stdClass();
  $id =  $_REQUEST['id'];
  $stmt = $conn->prepare('SELECT * FROM puestos INNER JOIN tipo_pago_nomina ON puestos.FKTipoPagoNomina = tipo_pago_nomina.PKPagoNomina WHERE PKPuesto= :id');
  $stmt->execute(array(':id'=>$id));
  $row = $stmt->fetch();
  //$row = $stmt->fetchAll(PDO::FETCH_OBJ);
  //$row = $stmt->rowCount();
  $html = $row['Puesto'];
  $html1 = $row['Sueldo'];
  $html2 = $row['FKTipoPagoNomina'];
  $json->html = $html;
  $json->html11 = $html1;
  $json->html21 = $html2;
  $json = json_encode($json);
  echo $json;
  //header('Location:editar_puesto.php');
}catch(PDOException $ex){
  echo $ex->getMessage();
}
