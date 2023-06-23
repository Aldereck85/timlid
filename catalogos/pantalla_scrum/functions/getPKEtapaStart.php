<?php
  require_once('../../../include/db-conn.php');
  if($_POST['id']){
    $id = $_POST['id'];
    $json = [];
    $stmt = $conn->prepare('SELECT PKEtapa FROM etapas WHERE FKProyecto =:id ORDER BY PKEtapa ASC LIMIT 1');
    $stmt->execute(array(':id'=>$id));
    $row = $stmt->fetch();
    $starStage = $row['PKEtapa'];
    $stmt = $conn->prepare('SELECT PKEtapa FROM etapas WHERE FKProyecto = :id AND PKEtapa = :stage');
    $stmt->execute(array(':id'=>$id,':stage'=>$starStage));
    $row = $stmt->fetchAll();
    $i = 0;
    foreach ($row as $r) {
      $json[$i]= $r;
      $i++;
    }
    //$json = substr($json,0,strlen($json)-1);


    echo json_encode($json);
    //echo $id;
  }


?>
