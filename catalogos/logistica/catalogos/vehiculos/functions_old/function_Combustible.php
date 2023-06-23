<?php
require_once('../../../include/db-conn.php');
if(isset($_GET['id'])){
     $id =  $_GET['id'];

}
$stmt = $conn->prepare('SELECT PKCombustible, Fecha_Carga, Cantidad, Costo_Unitario, Odometro, Diferencia_Odometro, Tanque_Lleno, FKVehiculo, FKUsuario, Usuario FROM combustible INNER JOIN usuarios ON FKUsuario = PKUsuario WHERE FKVehiculo = :id');
$stmt->execute(array(':id'=>$id));
$table="";
while (($row = $stmt->fetch()) !== false) {

        if($row['Tanque_Lleno'] == 0){
            $lleno = "No";
        }else{
            $lleno = "Si";
        }
        $edit = '<a class=\"btn btn-primary\" href=\"functions/editar_Carga.php?id='.$row['PKCombustible'].'&vehiculo='.$id.'\"><i class=\"fas fa-edit\"></i> Editar</a>&nbsp;&nbsp;';
		$delete ='<a class=\"btn btn-danger\" href=\"functions/eliminar_Carga.php?id='.$row['PKCombustible'].'&vehiculo='.$id.'\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a>';

    $funciones = "";
    $table.='{"Fecha":"'.$row['Fecha_Carga'].'","Cantidad":"'.$row['Cantidad'].'","Costo total":"'.$row['Costo_Unitario'].'","Odometro":"'.$row['Odometro'].'","Tanque lleno":"'.$lleno.'","Usuario":"'.$row['Usuario'].'","Acciones":"'.$delete.'"},';
  }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
 ?>
