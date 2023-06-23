<?php
  require_once('../../../../../include/db-conn.php');
  $id = $_POST['idProveedorD'];
  if(isset($_POST['idProveedorD'])){
    try{
      $stmt = $conn->prepare("DELETE FROM proveedores WHERE PKProveedor=?");
      if($stmt->execute(array($id)) == true){
        $exito = 1;
      }
      $stmt = $conn->prepare("DELETE FROM datos_contacto_proveedores WHERE FKProveedor=?");
      if($stmt->execute(array($id)) == true){
        $exito2 = 1;
      }
      if ($exito && $exito2 == 1){
        echo "exito";
      }else{
        echo "fallo";
      }
      
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }
?>
