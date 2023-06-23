<?php
require_once('../../../include/db-conn.php');
if(isset($_GET['id'])){
 $id =  $_GET['id'];
}
$stmt = $conn->prepare('SELECT * FROM servicios WHERE FKVehiculo = :id');
$stmt->bindValue(':id',$id);
$stmt->execute();
$table="";
while (($row = $stmt->fetch()) !== false) {
        if($row['Tipo_de_Servicio'] == 0){
            $tipo = "Correctivo";
        }else{
            $tipo = "Preventivo";
        }
        $edit = '<a class=\"btn btn-primary\" href=\"functions/editar_Servicio.php?id='.$row['PKServicio'].'&vehiculo='.$id.'\"><i class=\"fas fa-edit\"></i> Editar</a>&nbsp;&nbsp;';
		$delete ='<a class=\"btn btn-danger\" href=\"functions/eliminar_Servicio.php?id='.$row['PKServicio'].'&vehiculo='.$id.'\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a>';    
        $table.='{"Servicio":"'.$row['Servicio'].'","Tipo de Servicio":"'.$tipo.'","Acciones":"'.$edit.$delete.'"},';
  }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
 ?>
