<?php
require_once('../../../include/db-conn.php');

$stmt = $conn->prepare('SELECT * FROM ingredientes');
$stmt->execute();
$table="";
while (($row = $stmt->fetch()) !== false) {
  //href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Ingrediente\" class=\"btn btn-primary\" onclick=\"obtenerIdIngredienteEditar('.$row['PKIngrediente'].');\"><i class=\"fas fa-edit\"></i> Editar
  //href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_Ingrediente\" class=\"btn btn-danger\" onclick=\"obtenerIdIngredienteEliminar('.$row['PKIngrediente'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar
    $precio = number_format($row['Precio'], 2, '.', '');
    $edit = '<a class=\"btn btn-primary\" href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Ingrediente\" class=\"btn btn-primary\" onclick=\"obtenerIdIngredienteEditar('.$row['PKIngrediente'].');\"><i class=\"fas fa-edit\"></i> Editar</a>&nbsp;&nbsp;';
		$delete ='<a class=\"btn btn-danger\" href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_Ingrediente\" class=\"btn btn-danger\" onclick=\"obtenerIdIngredienteEliminar('.$row['PKIngrediente'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a>';
    $table.='{"Ingrediente":"'.$row['Ingrediente'].'","Precio":"'.$precio.'","Acciones":"'.$edit.$delete.'"},';
  }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
 ?>
