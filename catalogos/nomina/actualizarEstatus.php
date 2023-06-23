<?php
  require_once('../../include/db-conn.php');


print_r($_GET);
  if(isset($_GET['id'])){
    $id =  $_GET['id'];
    $estatusEnviado =  $_GET['estatus'];
    $idEmpleado =  $_GET['idEmpleado'];
    $deuda = "00:00:00";

    if($estatusEnviado == 0){
      $estatus = 9;
      $stmt = $conn->prepare('UPDATE gh_checador set Estatus= :estatus WHERE PKChecada = :id');
      $stmt->bindValue(':estatus',$estatus);
      $stmt->bindValue(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
    }else if($estatusEnviado == 1){
      $estatus = 10;
      $stmt = $conn->prepare('UPDATE gh_checador set Deuda_Horas= :deuda,Estatus= :estatus WHERE PKChecada = :id');
      $stmt->bindValue(':estatus',$estatus);
      $stmt->bindValue(':deuda',$deuda);
      $stmt->bindValue(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
    }else if($estatusEnviado == 2){
      $estatus = 12;

      $stmt = $conn->prepare('SELECT Dias_de_Vacaciones FROM datos_laborales_empleado INNER JOIN puestos ON FKPuesto = PKPuesto WHERE FKEmpleado = :idEmpleado');
      $stmt->execute(array(':idEmpleado'=>$idEmpleado));
      $row = $stmt->fetch();
      $dias = $row['Dias_de_Vacaciones'];

      $dias = $dias - 1;

      $stmt = $conn->prepare('UPDATE datos_laborales_empleado set Dias_de_Vacaciones= :dias WHERE FKEmpleado = :idEmpleado');
      $stmt->bindValue(':dias',$dias);
      $stmt->bindValue(':idEmpleado',$idEmpleado);
      $stmt->execute();

      $stmt = $conn->prepare('UPDATE gh_checador set Deuda_Horas= :deuda,Estatus= :estatus WHERE PKChecada = :id');
      $stmt->bindValue(':estatus',$estatus);
      $stmt->bindValue(':deuda',$deuda);
      $stmt->bindValue(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
    }

  }

 ?>
