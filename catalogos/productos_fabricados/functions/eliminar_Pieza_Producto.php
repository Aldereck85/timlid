<?php
  require_once('../../../include/db-conn.php');
  $id = $_POST['idPiezaElimina'];
  if(isset($_POST['idPiezaElimina'])){
    try{
      $stmt = $conn->prepare("DELETE FROM piezas_por_producto WHERE PKPiezaProducto=?");
      $stmt->execute(array($id));
      //header('Location:index.php');
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }
?>
