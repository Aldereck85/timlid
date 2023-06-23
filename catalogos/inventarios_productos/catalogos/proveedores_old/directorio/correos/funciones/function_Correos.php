<?php
  require_once('../../../../../include/db-conn.php');
  if(isset($_GET['id'])){
    $id =  $_GET['id'];
    $stmt = $conn->prepare('SELECT PKEmail_Proveedor,Email,FKProveedor FROM correos_proveedores WHERE FKProveedor = :id');
    $stmt->execute(array(':id'=>$id));
    $table="";
    while (($row = $stmt->fetch()) !== false) {
      $edit = '<a class=\"btn btn-primary btnMargin\" href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Correo\" onclick=\"obtenerIdCorreoEditar('.$row['PKEmail_Proveedor'].')\"><i class=\"fas fa-edit\"></i> Editar</a>&nbsp;&nbsp;';
  		$delete ='<a class=\"btn btn-danger btnMargin\" href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_Correo\" onclick=\"obtenerIdCorreoEliminar('.$row['PKEmail_Proveedor'].",".$row['FKProveedor'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a>';
      $table.='{"Email":"'.$row['Email'].'","Acciones":"'.$edit.$delete.'"},';
    }
    $table = substr($table,0,strlen($table)-1);
  	echo '{"data":['.$table.']}';
  }



 ?>
