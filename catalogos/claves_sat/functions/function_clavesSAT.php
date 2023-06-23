<?php
  require_once('../../../include/db-conn.php');

  $table = "";
  $no = 1;
  $stmt = $conn->prepare('SELECT * FROM claves_sat');
  $stmt->execute();
  $row = $stmt->fetch();
  switch($row['Estatus']){
    case 0:
      $estatus = "Deshabilitada";
    break;
    case 1:
      $estatus = "Habilitada";
    break;
  }

  while($row = $stmt->fetch()){
    if($no <11){
    switch($row['Estatus']){
      case 0:
        $estatus = "Deshabilitada";
      break;
      case 1:
        $estatus = "Habilitada";
      break;
    }
    $table .= '{"No":"'.$no.'","Clave":"'.$row['Clave'].'","Descripcion":"'.$row['Descripcion'].'","Estatus":"'.$estatus.'","Acciones":""},';
  }
    $no++;
  }

  //$table = '{"No":"'.$no.'","Clave":"'.$row['Clave'].'","Descripcion":"'.$row['Descripcion'].'","Estatus":"'.$estatus.'","Acciones":""},';
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
?>
