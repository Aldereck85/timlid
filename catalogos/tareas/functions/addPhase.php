<?php
  require_once('../../../include/db-conn.php');
  if(isset($_POST['etapa'])){
    $etapa = $_POST['etapa'];
    try{
      $stmt = $conn->prepare('INSERT INTO etapas (Etapa) VALUES (:etapa)');
      $stmt->execute(array(':etapa'=>$etapa));
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }

  }


?>
