<?php
  require_once('../../../include/db-conn.php');
  if(isset($_POST['id'])){
    $id = $_POST['id'];
    $etapa = "Nueva etapa";
    $array = [];
    try{
      $stmt = $conn->prepare('SELECT Orden FROM etapas WHERE FKProyecto=:id ORDER BY Orden DESC');
      $stmt->execute(array(':id'=>$id));
      $noTareas = $stmt->rowCount();
      $row = $stmt->fetch();
      if($noTareas > 0){
        $order = $row['Orden']+1;
      }else{
        $order = 1;
      }

      $stmt = $conn->prepare('INSERT INTO etapas (Etapa,Orden,FKProyecto) VALUES (:etapa,:orden,:id)');
      $stmt->execute(array(':etapa'=>$etapa,':orden'=>$order,':id'=>$id));
      $lastId = $conn->lastInsertId();

      $stmt = $conn->prepare('SELECT PKEtapa,Etapa FROM etapas WHERE PKEtapa = :id');
      $stmt->execute(array(':id'=>$lastId));
      $row = $stmt->fetchAll();

      $array =$row;

      echo json_encode($array);

    }catch(PDOException $ex){
      echo $ex->getMessage();
    }

  }


?>
