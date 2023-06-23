<?php
  require_once('../../../include/db-conn.php');

  $stmt = $conn->prepare('SELECT * FROM horas_extras');
  $stmt->execute();
  $table="";
  while (($row = $stmt->fetch()) !== false) {
    $table.='{"Id empleado":"'.$row['FKEmpleado'].'","Horas autorizadas":"'.$row['Horas_Autorizadas'].'","Fecha autorizada":"'.$row['FechaAutorizada'].'","Entrada":"'.$row['Entrada'].'","Salida":"'.$row['Salida'].'","Encargado":"'.$row['FKEncargado'].'"},';
  }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
?>
