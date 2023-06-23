<?php
  require_once('../../../include/db-conn.php');
  $id = $_POST['idProveedor'];

  $stmt = $conn->prepare('SELECT PKOrdenCompra,Referencia,FKEstatusOrden FROM ordenes_compra WHERE FKProveedor = :id AND FKEstatusOrden = :no');
  $stmt->bindValue(':id',$id);
  $stmt->bindValue(':no',2);
  $stmt->execute();
  $x = 0;
  $html = '<option value="">Seleccione una opcion...</option>';

  while($row = $stmt->fetch()){
    $html .= '<option value="'.$row['PKOrdenCompra'].'">'.$row['Referencia'].'</option>';
    $x++;
  }

  if($x == 0){
    $html .= '<option style="background: red;color:white">No hay ordenes de compras para este proveedor</option>';
  }
  echo $html;

?>
