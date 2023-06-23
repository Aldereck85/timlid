<?php
  require_once('../../../include/db-conn.php');
  $id = $_GET['id'];
  $idEnvio = $_GET['idEnvio'];

  if(isset($_GET['id'])){
    try{
      $stmt = $conn->prepare("DELETE FROM productos_en_envio WHERE PKProductoEnvio=?");
      $stmt->execute(array($id));
      header('Location:agregar_Productos_Envios.php?id='.$idEnvio);
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }
?>
