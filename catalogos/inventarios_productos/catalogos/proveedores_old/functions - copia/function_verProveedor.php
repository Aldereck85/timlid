<?php
  require_once('../../../include/db-conn.php');
  $id = $_GET['id'];
  $table ="";
  $no = 1;
  $stmt = $conn->prepare('SELECT DISTINCT pr.FKProducto,pd.Clave,pd.Descripcion FROM orden_compra AS oc
                          LEFT JOIN productos_oc AS pr ON oc.PKOrdenCompra = pr.FKOrdenCompra
                          LEFT JOIN productos AS pd ON pr.FKProducto = pd.PKProducto
                          LEFT JOIN proveedores AS pv ON oc.FKProveedor = pv.PKProveedor
                          WHERE oc.FKProveedor = :id AND Estatus = :estatus');
  $stmt->bindValue(':id',$id);
  $stmt->bindValue(':estatus',1);
  $stmt->execute();
  $rowCount = $stmt->rowCount();


  while($row = $stmt->fetch()){
    $table .= '{"No":"'.$no.'","Clave":"'.$row['Clave'].'","Producto":"'.$row['Descripcion'].'"},';
    $no++;
  }

  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
?>
