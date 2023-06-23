<?php
  require_once('../../../../../include/db-conn.php');

  if(isset($_POST['idCuentaD'])){
    $id = $_POST['idCuentaD'];
    $paqueteria = $_POST['idPaqueteriaD'];
    try{
      $stmt = $conn->prepare("DELETE FROM cuentas_bancarias_paqueterias WHERE PKCuentaPaqueteria=?");
      $stmt->execute(array($id));
      header('Location:../index.php?id='.$paqueteria);
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }
?>
