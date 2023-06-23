<?php
  require_once('../../../include/db-rm.php');
  $table = "";

  $stmt = $conn_rm->prepare('SELECT * FROM tipos_entradas_inventarios');
  $stmt->execute();


  while ($row = $stmt->fetch()) {
    $table .= '{"Id":"'.$row['PKTipoEntrada'].'","Tipo entrada":"'.$row['TipoEntrada'].'"},';
  }

  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
?>
