<?php
  require_once('../../../include/db-conn.php');
  $id = $_POST['id'];
  $text = $_POST['text'];

  $stmt = $conn->prepare('UPDATE responsables_subtarea SET FKUsuario= :usuario WHERE FKSubTarea= :id');
  echo $stmt->execute(array(':usuario'=>$text,':id'=>$id));


?>
