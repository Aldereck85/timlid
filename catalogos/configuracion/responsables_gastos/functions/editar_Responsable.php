<?php
  session_start();
  if(isset($_SESSION["Usuario"])){
    require_once('../../../../include/db-conn.php');
        $id =  $_POST['idResponsableU'];
        $fkresponsable = $_POST['txtResponsableU'];
        try{
          $stmt = $conn->prepare('UPDATE responsable_gastos set FKEmpleado= :fkresponsable WHERE PKResponsable = :id');
          $stmt->bindValue(':fkresponsable',$fkresponsable);
          $stmt->bindValue(':id', $id);
          
          if($stmt->execute()){
            echo "1";
          }else{
            echo "0";
          }
        }catch(PDOException $ex){
          echo $ex->getMessage();
        }
        $con = null;
        $db = null;
        $stmt = null;
  }
?>

