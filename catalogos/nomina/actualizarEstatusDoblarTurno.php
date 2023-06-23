<?php
  require_once('../../include/db-conn.php');

  if(isset($_GET['id'])){
    $id =  $_GET['id'];
    $estatusEnviado =  $_GET['estatus'];
    $idEmpleado =  $_GET['idEmpleado'];
    $deuda = "00:00:00";

    if($estatusEnviado == 0){
      $estatus = 9;
      $stmt = $conn->prepare('UPDATE doblar_turno set Estatus= :estatus WHERE PKChecadaDoble = :id');
      $stmt->bindValue(':estatus',$estatus);
      $stmt->bindValue(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
    }else if($estatusEnviado == 1){
      $estatus = 10;
      $stmt = $conn->prepare('UPDATE doblar_turno set Deuda_Horas= :deuda,Estatus= :estatus WHERE PKChecadaDoble = :id');
      $stmt->bindValue(':estatus',$estatus);
      $stmt->bindValue(':deuda',$deuda);
      $stmt->bindValue(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
    }

  }

 ?>
