<?php
session_start();

if(isset($_SESSION["Usuario"])){
  require_once('../../../include/db-conn.php');
  if(isset($_POST['idProyectoD'])){
    $id = $_POST['idProyectoD'];
    
    try{
      $stmt = $conn->prepare("DELETE FROM proyectos WHERE PKProyecto=? AND empresa_id=?");
      if ($stmt->execute(array($id,$_SESSION['IDEmpresa']))){
        echo "exito";
      }else{
        echo "fallo";
      }
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }
}
?>
