<?php
  require_once('../../../include/db-conn.php');
  $clave = $_POST['clave'];
  $stmt = $conn->prepare('SELECT * FROM claves_sat WHERE Clave = :clave');
  $stmt->bindValue(':clave',$clave,PDO::PARAM_STR);
  $stmt->execute();
  $row = $stmt->fetch();
  $cadena = '{"Id":"'.$row['PKClaveSAT'].'","Descripcion":"'.$row['Descripcion'].'","Estatus":"'.$row['Estatus'].'"}';
  echo json_encode($cadena);


?>
