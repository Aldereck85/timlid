<?php
  require_once('../../../../../include/db-conn.php');
  if(isset($_GET['id'])){
    $id =  $_GET['id'];
    $stmt = $conn->prepare('SELECT * FROM telefonos_paqueterias WHERE FKPaqueteria = :id');
    $stmt->execute(array(':id'=>$id));
    $table="";
    while (($row = $stmt->fetch()) !== false) {
      $edit = '<a class=\"btn btn-primary btnMargin\" href=\"#\" data-toggle=\"modal\" data-target=\"#editar_TelefonoPaqueteria\" onclick=\"obtenerIdTelefonoPaqueteriaEditar('.$row['PKTelefono_Paqueteria'].')\"><i class=\"fas fa-edit\"></i> Editar</a>&nbsp;&nbsp;';
  		$delete ='<a class=\"btn btn-danger btnMargin\" href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_TelefonoPaqueteria\" onclick=\"obtenerIdTelefonoPaqueteriaEliminar('.$row['PKTelefono_Paqueteria'].",".$row['FKPaqueteria'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a>';
      $table.='{"Telefono":"'.$row['Telefono'].'","Acciones":"'.$edit.$delete.'"},';
    }
    $table = substr($table,0,strlen($table)-1);
  	echo '{"data":['.$table.']}';
  }



 ?>
