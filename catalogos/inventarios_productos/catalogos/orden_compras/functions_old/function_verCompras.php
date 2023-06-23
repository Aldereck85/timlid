<?php
  require_once('../../../include/db-conn.php');
  $id = $_GET['id'];
  $table = "";
  $no = 1;
  $stmt = $conn->prepare('SELECT * FROM compras_productos WHERE FKOrdenCompra = :id');
  $stmt->bindValue(':id',$id);
  $stmt->execute();

  while($row = $stmt->fetch()){
    $fecha = date('d/m/Y',strtotime($row['Fecha_de_Emision']));
    $importe = "$".number_format($row['Importe'],2);

    $table .= '{"No":"'.$no.'","Compra":"'.$row['Referencia'].'","Fecha de compra":"'.$fecha.'","Importe":"'.$importe.'"},';
    //$table .= '{"No":"'.$no.'","Clave":"'.$row['Clave'].'","Producto":"'.$row['Descripcion'].'","Precio unitario":"'.$precio.'","Cantidad":"'.$row['Cantidad'].'","Importe":"'.$importe.'"},';
    $no++;
  }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
?>
