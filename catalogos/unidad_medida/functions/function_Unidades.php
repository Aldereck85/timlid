
<?php
require_once('../../../include/db-conn.php');

$stmt = $conn->prepare('SELECT * FROM unidad_medida AS um
                        LEFT JOIN claves_sat_unidades AS csu ON FKClaveSAT = PKClaveSATUnidad');
$stmt->execute();
$table="";
$no = 1;
while (($row = $stmt->fetch()) !== false) {
    $edit = '<a class=\"btn btn-primary\" href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Unidades\" class=\"btn btn-primary\" onclick=\"obtenerIdUnidadesEditar('.$row['PKUnidadMedida'].');\"><i class=\"fas fa-edit\"></i> Editar</a>&nbsp;&nbsp;';
		$delete ='<a class=\"btn btn-danger\" href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_Unidades\" class=\"btn btn-danger\" onclick=\"obtenerIdUnidadesEliminar('.$row['PKUnidadMedida'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a>';

    $table.='{"No":"'.$no.'","Clave SAT":"'.$row['Clave'].'","Unidad de medida":"'.$row['Unidad_de_Medida'].'","Piezas por caja":"'.$row['Piezas_por_Caja'].'","Acciones":"'.$edit.$delete.'"},';
    $no++;
  }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
 ?>
