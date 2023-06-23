<?php
  require_once('../../../include/db-conn.php');
  if(isset($_POST['id'])){
    $id = $_POST['id'];
    $stageName = $_POST['stage_name'];

    $stmt = $conn->prepare('UPDATE etapas SET etapa = :etapa WHERE PKEtapa = :id');
    $stmt->execute(array(':etapa'=>$stageName,':id'=>$id));

  }

?>
