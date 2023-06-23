<?php
require_once('../../../include/db-conn.php');

  $Proyecto = $_POST['Proyecto'];
  $idUsuario = $_POST['Usuario'];
  
  try{
    $stmt = $conn->prepare('INSERT INTO proyectos (Proyecto,FKResponsable) VALUES (:proyecto,:fkusuario)');
    $stmt->bindValue(':proyecto',$Proyecto);
    $stmt->bindValue(':fkresponsable',$idUsuario);
    if($stmt->execute())
      echo "exito";
      else
        echo "fallo";
  }catch(PDOException $ex){
    echo $ex->getMessage();
  }
 ?>
