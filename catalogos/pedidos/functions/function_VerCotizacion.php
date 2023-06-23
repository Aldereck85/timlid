<?php
  require_once('../../../include/db-conn.php');
  $id = $_GET['id'];
  $stmt = $conn->prepare('SELECT * FROM detallecotizacion as dc LEFT JOIN productos as p ON dc.FKProducto = p.PKProducto WHERE dc.FKCotizacion = :id');
  $stmt->bindValue(':id', $id, PDO::PARAM_INT);
  $stmt->execute();
  $table="";
  $no = 1;

  while(($row = $stmt->fetch()) !== false){
    $precio = "$".number_format($row['Precio'],2);
    $importe = "$".number_format($row['Precio'] * $row['Cantidad'],2);
    $unidadmedida = "Pieza";
    $producto = $row['Clave']." ".$row['Descripcion'];
    /*$edit = '<a class=\"btn btn-primary\" href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Producto\" onclick=\"obtenerIdProductoEditar('.$row['PKCompras'].');\"><i class=\"fas fa-edit\"></i> Editar</a>&nbsp;&nbsp;';
		$delete ='<a class=\"btn btn-danger\" href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_Producto\" onclick=\"obtenerIdProductoEliminar('.$row['PKCompras'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a>';*/

    $table .= '{"No":"'.$no.'","Clave":"'.$producto.'","Cantidad":"'.$row['Cantidad'].'","Unidad medida":"'.$unidadmedida.'","Precio unitario":"'.$precio.'","Importe":"'.$importe.'"},';
    $no++;
  }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';



?>
