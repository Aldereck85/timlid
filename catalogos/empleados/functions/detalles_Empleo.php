<?php
require_once('../../../include/db-conn.php');

  if(isset($_GET['id'])){
    $id =  $_GET['id'];
    $stmt = $conn->prepare('SELECT count(*) FROM datos_empleo WHERE FKEmpleado= :id');
    $stmt->execute(array(':id'=>$id));
    $number_of_rows = $stmt->fetchColumn();
    if($number_of_rows > 0)
    {
      header("location:detalles_empleoEditar.php?id=$id");
    }else{
      header("location:detalles_empleoAgregar.php?id=$id");
    }
  }
 ?>
