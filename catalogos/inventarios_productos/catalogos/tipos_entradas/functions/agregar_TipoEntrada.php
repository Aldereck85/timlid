<?php
  require_once('../../../include/db-rm.php');

  $tipoEntrada = $_POST['txtTipoEntrada'];

  $stmt = $conn_rm->prepare('INSERT INTO tipos_entradas_inventarios (TipoEntrada) VALUES (:tipo)');
  $stmt->execute(array(':tipo'=>$tipoEntrada));

  
?>
