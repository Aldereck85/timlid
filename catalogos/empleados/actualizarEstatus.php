<?php
  require_once('../../include/db-conn.php');
  if(isset($_GET['id'])){
    $id =  $_GET['id'];
    $estatus = 9;
    $stmt = $conn->prepare('UPDATE gh_checador set Estatus= :estatus WHERE PKChecada = :id');
    $stmt->bindValue(':estatus',$estatus);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
  }

 ?>
