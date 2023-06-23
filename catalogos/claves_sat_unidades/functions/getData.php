<?php
  require_once('../../../include/db-conn.php');
  $clave = $_POST['clave'];
  $stmt = $conn->prepare('SELECT * FROM claves_sat_unidades WHERE Clave = :clave');
  $stmt->bindValue(':clave',$clave,PDO::PARAM_STR);
  $stmt->execute();
  $row = $stmt->fetch();
  $cadena = '{"Id":"'.$row['PKClaveSATUnidad'].'","Descripcion":"'.$row['Descripcion'].'","Estatus":"'.$row['Estatus'].'"}';
  echo json_encode($cadena);


?>
