<?php
  require_once('../../../include/db-conn.php');

  $id = $_POST['txtIdD'];

  $stmt = $conn->prepare('DELETE FROM empresas WHERE PKEmpresa = :id');
  $stmt->bindValue(':id',$id);
  $stmt->execute();

  header("location:../index.php");

?>
