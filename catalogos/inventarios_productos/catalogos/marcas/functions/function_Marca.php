
<?php
require_once('../../../include/db-conn.php');

$stmt = $conn->prepare('SELECT * FROM marcas');
$stmt->execute();
$table="";
$no = 1;
while (($row = $stmt->fetch()) !== false) {
    $edit = '<a class=\"btn btn-primary\" href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Marca\" class=\"btn btn-primary\" onclick=\"obtenerIdMarcaEditar('.$row['PKMarca'].');\"><i class=\"fas fa-edit\"></i> Editar</a>&nbsp;&nbsp;';
		$delete ='<a class=\"btn btn-danger\" href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_Marca\" class=\"btn btn-danger\" onclick=\"obtenerIdMarcaEliminar('.$row['PKMarca'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a>';

    $table.='{"No":"'.$no.'","Marcas":"'.$row['Marca'].'","Acciones":"'.$edit.$delete.'"},';
    $no++;
  }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
 ?>
