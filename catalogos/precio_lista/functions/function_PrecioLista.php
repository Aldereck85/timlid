
<?php
require_once('../../../include/db-conn.php');

$stmt = $conn->prepare('SELECT * FROM precio_lista LEFT JOIN productos ON FKProducto = PKProducto LEFT JOIN clientes ON FKCliente = PKCliente');
$stmt->execute();
$table="";
$no = 1;
while (($row = $stmt->fetch()) !== false) {
    $descripcion = $row['Descripcion'];
    $edit = '<a class=\"btn btn-primary\" href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Precio\" class=\"btn btn-primary\" onclick=\"obtenerIdPrecioEditar('.$row['PKPrecioLista'].');\"><i class=\"fas fa-edit\"></i> Editar</a>&nbsp;&nbsp;';
		$delete ='<a class=\"btn btn-danger\" href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_Precio\" class=\"btn btn-danger\" onclick=\"obtenerIdPrecioEliminar('.$row['PKPrecioLista'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a>';

    $table.='{"No":"'.$no.'","Producto":"'.$descripcion.'","Cliente":"'.$row['Nombre_comercial'].'","Precio unitario":"'.$row['Precio_Unitario'].'","Acciones":"'.$edit.$delete.'"},';
    $no++;
  }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
 ?>
