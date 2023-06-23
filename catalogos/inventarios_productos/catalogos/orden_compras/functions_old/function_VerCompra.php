<?php
  require_once('../../../include/db-conn.php');
  $id = $_GET['id'];
  $stmt = $conn->prepare('SELECT p.Descripcion,p.Clave,pr.Precio_Unitario,pr.Importe,pr.Cantidad FROM productos_oc as pr
          LEFT JOIN orden_compra as o ON pr.FKOrdenCompra = o.PKOrdenCompra
          LEFT JOIN productos as p ON pr.FKProducto = p.PKProducto
          WHERE pr.FKOrdenCompra = :id');
  $stmt->bindValue(':id', $id, PDO::PARAM_INT);
  $stmt->execute();
  $table="";
  $no = 1;

  while(($row = $stmt->fetch()) !== false){
    $importe = "$".number_format($row['Importe'],2);

    $precio = "$".number_format($row['Precio_Unitario'],2);

    $table .= '{"No":"'.$no.'","Clave":"'.$row['Clave'].'","Producto":"'.$row['Descripcion'].'","Precio unitario":"'.$precio.'","Cantidad":"'.$row['Cantidad'].'","Importe":"'.$importe.'"},';
    $no++;
  }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';



?>
