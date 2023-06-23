<?php
  require_once('../../../include/db-conn.php');
  if(isset($_POST['id'])){
    $id = $_POST['id'];
    $texto = "";
    $stmt = $conn->prepare('SELECT Texto FROM texto_tarea WHERE FKTarea = :id');
    $stmt->execute(array(':id'=>$id));
    $row = $stmt->fetch();
    $rowCount = $stmt->rowCount();
    if($rowCount > 0){
      $texto = $row['Texto'];
    }
    echo $texto;
  }

?>
