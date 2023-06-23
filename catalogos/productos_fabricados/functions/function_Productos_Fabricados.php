<?php
require_once('../../../include/db-conn.php');

$stmt = $conn->prepare('SELECT * FROM productos_fabricados');
$stmt->execute();
$table="";
//href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Rollo\" class=\"btn btn-primary\" onclick=\"obtenerIdProductoEditar('.$row['PKProductoFabricado'].');\"><i class=\"fas fa-edit\"></i> Editar
//href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_Rollo\" class=\"btn btn-danger\" onclick=\"obtenerIdProductoEliminar('.$row['PKProductoFabricado'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar
while (($row = $stmt->fetch()) !== false) {
    $directorio = '<div class=\"dropdown\"><button class=\"btn btn-secondary btnMargin dropdown-toggle\" type=\"button\" id=\"dropdownMenuButton\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\"><i class=\"fas fa-tools\"></i> Operaciones</button><div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuButton\"><a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Producto\" onclick=\"obtenerIdProductoEditar('.$row['PKProductoFabricado'].');\"><i class=\"fas fa-edit\"></i> Editar</a><a class=\"dropdown-item\" href=\"functions/agregar_Piezas_Productos.php?id='.$row['PKProductoFabricado'].'\"><i class=\"fas fa-th\"></i> Piezas</a><a class=\"dropdown-item\" href=\"functions/calcular_piezas.php?id='.$row['PKProductoFabricado'].'\"><i class=\"fas fa-calculator\"></i> Calcular frabricación por rollo</a><a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_Producto\" onclick=\"obtenerIdProductoEliminar('.$row['PKProductoFabricado'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a></div></div>';
    /*$editar = '<a class=\"btn btn-primary\" href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Producto\" class=\"btn btn-primary\" onclick=\"obtenerIdProductoEditar('.$row['PKProductoFabricado'].');\"><i class=\"fas fa-edit\"></i> Editar</a>&nbsp;&nbsp;';
    $piezas = '<a class=\"btn btn-primary\" href=\"functions/agregar_Piezas_Productos.php?id='.$row['PKProductoFabricado'].'\" class=\"btn btn-primary\"><i class=\"fas fa-th\"></i> Piezas</a>&nbsp;&nbsp;';
    $eliminar ='<a class=\"btn btn-danger\" href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_Producto\" class=\"btn btn-danger\" onclick=\"obtenerIdProductoEliminar('.$row['PKProductoFabricado'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a>';*/

    $table.='{"Producto fabricado":"'.$row['ProductoFabricado'].'","Acciones":"'.$directorio.'"},';
  }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
 ?>
