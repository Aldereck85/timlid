<?php
  require_once('../../../include/db-conn.php');
  $id = $_POST['idUnidadesD'];
  if(isset($_POST['idUnidadesD'])){
    try{
      $stmt = $conn->prepare("DELETE FROM unidad_medida WHERE PKUnidadMedida=?");
      $stmt->execute(array($id));
      header('Location:../index.php');
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }
?>
