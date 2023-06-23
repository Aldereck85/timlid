<?php
  require_once('../../../../../include/db-conn.php');
  if(isset($_POST['idEmailD']) && isset($_POST['txtIdP'])){
    $id = $_POST['idEmailD'];
    $paqueteria = $_POST['txtIdP'];
    try{
      $stmt = $conn->prepare("DELETE FROM correos_paqueterias WHERE PKEmail_Paqueteria=?");
      $stmt->execute(array($id));
      header('Location:../index.php?id='.$paqueteria);
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }
?>
