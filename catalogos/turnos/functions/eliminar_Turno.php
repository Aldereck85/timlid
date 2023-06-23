<?php
  require_once('../../../include/db-conn.php');
  
  $id = $_POST['idTurnoD'];
  if(isset($_POST['idTurnoD'])){
  
    try{
      $stmt = $conn->prepare("DELETE FROM Turnos WHERE PKTurno=?");
      if($stmt->execute(array($id))){
        echo "exito";
      }else{
        echo "fallo";
      }
      
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }
?>
