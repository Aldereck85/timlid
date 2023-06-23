<?php
  require_once('../../../include/db-conn.php');
  $id = $_POST['idProducto'];

  $json = new \stdClass();

  if(isset($_POST['idProducto'])){
    try{

      $stmt = $conn->prepare('SELECT csu.Descripcion FROM info_fiscal_productos as ifp INNER JOIN claves_sat_unidades as csu ON csu.PKClaveSATUnidad = ifp.FKClaveSATUnidad WHERE ifp.FKProducto= :id');
      $stmt->bindValue(":id" , $id);
      $stmt->execute();
      $rowUnidad = $stmt->fetch();

      if($stmt->rowCount() > 0){
        $json->ClaveUnidad = $rowUnidad['Descripcion'];
      }
      else{
        $json->ClaveUnidad = 'Sin unidad';
      }
      
      $json = json_encode($json);
      echo $json;

    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }
?>
