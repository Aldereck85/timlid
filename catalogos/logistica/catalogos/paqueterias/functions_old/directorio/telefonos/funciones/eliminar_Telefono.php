<?php
  require_once('../../../../../include/db-conn.php');

  if(isset($_POST['idTelefonoD'])){
    $id = $_POST['idTelefonoD'];
    $paqueteria = $_POST['txtIdP'];
    try{
      $stmt = $conn->prepare("DELETE FROM telefonos_paqueterias WHERE PKTelefono_Paqueteria=?");
      $stmt->execute(array($id));
      header('Location:../index.php?id='.$paqueteria);
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }
?>
