<?php
  require_once('../../../include/db-conn.php');
  $id = $_POST['idProspectoD'];
  if(isset($_POST['idProspectoD'])){
    try{
      $stmt = $conn->prepare("DELETE FROM clientes WHERE PKCliente=?");
      $stmt->execute(array($id));
      header('Location:../editar_Prospecto.php?id='.$id);
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }
?>
