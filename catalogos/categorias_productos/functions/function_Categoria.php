
<?php
require_once('../../../include/db-conn.php');

$stmt = $conn->prepare('SELECT * FROM categorias_producto');
$stmt->execute();
$table="";
$no = 1;
while (($row = $stmt->fetch()) !== false) {
    $edit = '<a class=\"btn btn-primary\" href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Categoria\" class=\"btn btn-primary\" onclick=\"obtenerIdCategoriaEditar('.$row['PKCategoriaProducto'].');\"><i class=\"fas fa-edit\"></i> Editar</a>&nbsp;&nbsp;';
		$delete ='<a class=\"btn btn-danger\" href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_Categoria\" class=\"btn btn-danger\" onclick=\"obtenerIdCategoriaEliminar('.$row['PKCategoriaProducto'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a>';

    $table.='{"No":"'.$no.'","Categorias":"'.$row['Categoria_Producto'].'","Acciones":"'.$edit.$delete.'"},';
    $no++;
  }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
 ?>
