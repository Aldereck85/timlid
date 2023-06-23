<?php
require_once('../../../include/db-conn.php');
$id =$_POST['id'];

$stmt = $conn->prepare('DELETE FROM tareas WHERE PKTarea= :id');
echo $stmt->execute(array(':id'=>$id));


?>
