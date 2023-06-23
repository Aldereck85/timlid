<?php
  require_once('../../include/db-conn.php');

  if(isset($_GET['idHoraExtra'])){
    $id =  $_GET['idHoraExtra'];
    $estatus =  $_GET['estatus'];

    $stmt = $conn->prepare('SELECT Horas_Autorizadas FROM horas_extras WHERE PKHoraExtra = :id');
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch();
    $horasAutorizadas = $row['Horas_Autorizadas'];

    if($estatus == 1){
      $horasAutorizadas = $horasAutorizadas + 1;
      $stmt = $conn->prepare('UPDATE horas_extras set Horas_Autorizadas = :tiempoagregado WHERE PKHoraExtra = :id');
      $stmt->bindValue(':tiempoagregado',$horasAutorizadas);
      $stmt->bindValue(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
    }else if($estatus == 2){
      $horasAutorizadas = $horasAutorizadas - 1;
      $stmt = $conn->prepare('UPDATE horas_extras set Horas_Autorizadas = :tiempoagregado WHERE PKHoraExtra = :id');
      $stmt->bindValue(':tiempoagregado',$horasAutorizadas);
      $stmt->bindValue(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
    }

  }

 ?>
