<?php
  require_once('../../../include/db-conn.php');
  $id = $_GET['id'];
  if(isset($_GET['id'])){
    $estatus = "Cancelado";
    try{
      $stmt = $conn->prepare('SELECT count(*) FROM envios WHERE FKFactura= :id');
      $stmt->execute(array(':id'=>$id));
      $number_of_rows = $stmt->fetchColumn();

      if($number_of_rows > 0){
        header('Location:sustituir_factura.php?id='.$id);

      }else{
        $stmt = $conn->prepare('UPDATE facturas set Estatus= :estatus WHERE PKFactura = :id');
        $stmt->bindValue(':estatus',$estatus);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        header('Location:../index.php');
      }
    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }
?>
