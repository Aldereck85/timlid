<?php
  require_once('../../../include/db-conn.php');
  if(isset($_POST['id'])){
    $id = $_POST['id'];
    $texto = "";
    $stmt = $conn->prepare('SELECT Repetible FROM tareas_repetibles WHERE FKTarea = :id');
    $stmt->execute(array(':id'=>$id));
    $row = $stmt->fetch();
    $rowCount = $stmt->rowCount();
    if($rowCount > 0){
      $texto = $row['Repetible'];
    }
    echo $texto;

  }

?>
