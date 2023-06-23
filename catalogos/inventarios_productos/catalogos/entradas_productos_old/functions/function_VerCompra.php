<?php
  require_once('../../../include/db-conn.php');
  $id = $_GET['id'];
  $stmt = $conn->prepare('SELECT pr.Descripcion,pr.Clave,pc.Precio_Unitario,pc.Importe,pc.Cantidad_Recibida FROM productos_cc as pc
          LEFT JOIN compras_productos as op ON pc.FKCompra = op.PKCompra
          LEFT JOIN productos as pr ON pc.FKProducto = pr.PKProducto
          WHERE pc.FKCompra = :id');
  $stmt->bindValue(':id', $id, PDO::PARAM_INT);
  $stmt->execute();
  $table="";
  $no = 1;

  while(($row = $stmt->fetch()) !== false){
    $importe = "$".number_format($row['Importe'],2);
    $precio = "$".number_format($row['Precio_Unitario'],2);

    $table .= '{"No":"'.$no.'","Clave":"'.$row['Clave'].'","Producto":"'.$row['Descripcion'].'","Precio unitario":"'.$precio.'","Cantidad":"'.$row['Cantidad_Recibida'].'","Importe":"'.$importe.'"},';
    $no++;
  }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';



?>
