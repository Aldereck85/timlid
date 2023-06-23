<?php
  require_once('../../../../../include/db-conn.php');

  if(isset($_POST['idDomiciliosEnvioD'])){
    $id = $_POST['idDomiciliosEnvioD'];
    $paqueteria = $_POST['txtIdP'];

    try{
      $stmt = $conn->prepare("DELETE FROM domicilio_de_envio_paqueterias WHERE PKDomicilio=?");
      $stmt->execute(array($id));
      header('Location:../index.php?id='.$paqueteria);
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }
?>
