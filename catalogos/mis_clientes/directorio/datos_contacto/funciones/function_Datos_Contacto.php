<?php
  require_once('../../../../../include/db-conn.php');
  if(isset($_GET['id'])){
    $id =  $_GET['id'];
    $stmt = $conn->prepare('SELECT * FROM datos_contacto WHERE FKCliente = :id');
    $stmt->execute(array(':id'=>$id));
    $table="";
    while (($row = $stmt->fetch()) !== false) {
      $edit = '<a class=\"btn btn-primary btnMargin\" href=\"funciones/editar_Datos_Contacto.php?id='.$row['PKContacto']."&cliente=".$row['FKCliente'].'\"><i class=\"fas fa-edit\"></i> Editar</a>&nbsp;&nbsp;';
  		$delete ='<a class=\"btn btn-danger btnMargin\" href=\"funciones/eliminar_Datos_Contacto.php?id='.$row['PKContacto']."&cliente=".$row['FKCliente'].'\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a>';
      $table.='{"Nombre":"'.$row['Nombre'].'","Apellido":"'.$row['Apellido_Paterno'].'","Puesto":"'.$row['Puesto'].'","Telefono":"'.$row['Telefono'].'","Celular":"'.$row['Celular'].'","Email":"'.$row['Email'].'","Acciones":"'.$edit.$delete.'"},';
    }
    $table = substr($table,0,strlen($table)-1);
  	echo '{"data":['.$table.']}';
  }



 ?>
