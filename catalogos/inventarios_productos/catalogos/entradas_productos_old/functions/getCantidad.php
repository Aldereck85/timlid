<?php
  require_once('../../../include/db-conn.php');
  if(isset($_POST['id']) && is_numeric($_POST['id']) && isset($_POST['orden']) && is_numeric($_POST['orden'])){
    $id = $_POST['id'];
    $compra = $_POST['orden'];

    $stmt = $conn->prepare('SELECT * FROM productos_oc WHERE FKProducto = :id AND FKOrdenCompra = :orden');
    $stmt->bindValue(':id',$id);
    $stmt->bindValue(':orden',$compra);
    $stmt->execute();
    $row = $stmt->fetch();
    $canTotal = $row['Cantidad'];

    $stmt = $conn->prepare('SELECT SUM(Cantidad_Recibida) FROM productos_cc AS pcc
                    INNER JOIN compras_productos AS cp ON pcc.FKCompra = cp.PKCompra
                    INNER JOIN orden_compra AS oc ON cp.FKOrdenCompra = oc.PKOrdenCompra
                    WHERE cp.FKOrdenCompra= :compra AND pcc.FKProducto = :id');
    $stmt->bindValue(':compra',$compra);
    $stmt->bindValue(':id',$id);
    $stmt->execute();
    $row = $stmt->fetch();
    $sumaCan = (int)$row['SUM(Cantidad_Recibida)'];


    if($canTotal > $sumaCan){
      echo $canTotal - $sumaCan;
    }else{
      echo "Cantidad máxima alcanzada.";
    }

  //}else{
    //echo "0";
  }
 ?>
