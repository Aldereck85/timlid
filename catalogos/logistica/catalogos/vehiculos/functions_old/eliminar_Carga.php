<?php
  require_once('../../../include/db-conn.php');
  
  if(isset($_GET['id'])){
    $id = $_GET['id'];
    $vehiculo = $_GET['vehiculo'];
    $vehiculoId = $vehiculo;
    
    $stmt = $conn->prepare('SELECT combustible.Diferencia_Odometro,vehiculos.Odometro,vehiculos.Kilometraje_acumulado FROM combustible INNER JOIN vehiculos on FKVehiculo = PKVehiculo WHERE PKCombustible = :id AND FKVehiculo = :vehiculo');
    //$stmt = $conn->prepare('SELECT * FROM combustible WHERE PKCombustible= :id');
    //$stmt->execute(array(':id'=>$id));
    $stmt->bindValue(':vehiculo',$vehiculo);
    $stmt->bindValue(':id',$id);
    $stmt->execute();  
      
    $row = $stmt->fetch();
    $diferencia = $row['Diferencia_Odometro'];  
    $odometro = $row['Odometro'];  
    $kilometrajeAcumulado = $row['Kilometraje_acumulado']; 
    
    
      
    $odometroActual = $odometro - $diferencia;
    $acumuladoActual = $kilometrajeAcumulado - $diferencia;
      
    echo $odometroActual."<br>".$acumuladoActual;
      
    $stmt = $conn->prepare('UPDATE vehiculos set Odometro= :odometro,Kilometraje_acumulado= :kilometrajeAcumulado WHERE PKVehiculo = :id');
    $stmt->bindValue(':odometro',$odometroActual);
    $stmt->bindValue(':kilometrajeAcumulado',$acumuladoActual);
    $stmt->bindValue(':id',$vehiculoId);
    $stmt->execute();
      
    try{
      $stmt = $conn->prepare("DELETE FROM combustible WHERE PKCombustible=?");
      $stmt->execute(array($id));
      header('Location:../combustible.php?id='.$vehiculo);
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }
?>


