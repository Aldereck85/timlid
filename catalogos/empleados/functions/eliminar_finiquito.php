<?php
  require_once('../../../include/db-conn.php');
  $id = $_GET['id'];
  if(isset($_GET['id'])){
    try{

      $conn->beginTransaction();

      $stmt = $conn->prepare("SELECT FKEmpleado, Prestamos FROM finiquito WHERE PKFiniquito = :id ");
      $stmt->bindValue(':id',$id);
      $stmt->execute();
      $row = $stmt->fetch();
      $fKEmpleado = $row['FKEmpleado'];
      $deuda = $row['Prestamos'];

      $stmt = $conn->prepare("UPDATE datos_laborales_empleado SET Deuda_Restante = :deuda WHERE FKEmpleado=:id");
      $stmt->bindValue(':deuda',$deuda);
      $stmt->bindValue(':id',$fKEmpleado);
      $stmt->execute();

      $stmt = $conn->prepare("DELETE FROM finiquito WHERE PKFiniquito=?");
      $stmt->execute(array($id));

      $conn->commit(); 
      header('Location:../finiquito.php?id='.$fKEmpleado);
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }
?>