<?php
  require_once('../../../include/db-conn.php');
  if(isset($_POST['serie'])){
    $serie = $_POST['serie'];
    $stmt = $conn->prepare('SELECT COUNT(Serie) FROM facturacion WHERE Serie = :serie');
    $stmt->bindValue(':serie',$serie);
    $stmt->execute();
    $rowCount = $stmt->fetchColumn();

    if($rowCount > 0){
      $count = $rowCount +1;
      $folio = str_pad($count, 6, "0", STR_PAD_LEFT);
    }else{
      $folio = "000001";
    }
  }
  echo $folio;

?>
