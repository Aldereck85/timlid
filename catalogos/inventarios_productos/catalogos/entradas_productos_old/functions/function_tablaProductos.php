<?php
  require_once('../../../include/db-conn.php');
  $id = $_GET['id'];
  $stmt = $conn->prepare('SELECT * FROM compras_tmp as c LEFT JOIN productos as p ON c.FKProducto = p.PKProducto WHERE FKOrdenCompra = :id');
  $stmt->bindValue(':id', $id, PDO::PARAM_INT);
  $stmt->execute();
  $table="";
  $no = 1;

  while(($row = $stmt->fetch()) !== false){
    $importe = $row['Precio_Unitario'] * $row['Cantidad'];
    $precio = "$".number_format($row['Precio_Unitario'],2);
    $importe = "$".number_format($importe,2);
    $edit = '<a class=\"btn btn-primary\" href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Producto\" onclick=\"obtenerIdProductoEditar('.$row['PKComprastmp'].');\"><i class=\"fas fa-edit\"></i> Editar</a>&nbsp;&nbsp;';
		$delete ='<a class=\"btn btn-danger\" href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_Producto\" onclick=\"obtenerIdProductoEliminar('.$row['PKComprastmp'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a>';
    //$edit = "";
    //$delete = "";
    $table .= '{"No":"'.$no.'","Clave":"'.$row['Clave'].'","Producto":"'.$row['Descripcion'].'","Precio unitario":"'.$precio.'","Cantidad":"'.$row['Cantidad'].'","Importe":"'.$importe.'","Acciones":"'.$edit.$delete.'"},';
    $no++;
  }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';

?>
