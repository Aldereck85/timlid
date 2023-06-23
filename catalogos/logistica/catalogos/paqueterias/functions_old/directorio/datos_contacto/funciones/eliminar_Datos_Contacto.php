<?php
  require_once('../../../../../include/db-conn.php');

  if(isset($_POST['idContactoD'])){
    $id = $_POST['idContactoD'];
    $paqueteria = $_POST['txtIdP'];
    try{
      $stmt = $conn->prepare("DELETE FROM datos_contacto_paqueterias WHERE PKContacto_Paqueteria=?");
      $stmt->execute(array($id));
      header('Location:../index.php?id='.$paqueteria);
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }
?>
