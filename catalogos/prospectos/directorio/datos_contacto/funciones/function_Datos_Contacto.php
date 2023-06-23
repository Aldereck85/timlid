<?php
  require_once('../../../../../include/db-conn.php');
  if(isset($_GET['id'])){
    $id =  $_GET['id'];
    $stmt = $conn->prepare('SELECT * FROM datos_contacto WHERE FKCliente = :id');
    $stmt->execute(array(':id'=>$id));
    $table="";
    while (($row = $stmt->fetch()) !== false) {
      $edit = '<a class=\"btn btn-primary btn-circle\" href=\"funciones/editar_Datos_Contacto.php?id='.$row['PKContacto']."&cliente=".$row['FKCliente'].'\"><i class=\"fas fa-edit\"></i></a>&nbsp;&nbsp;';
  		$delete ='<a class=\"btn btn-danger btn-circle\" href=\"funciones/eliminar_Datos_Contacto.php?id='.$row['PKContacto']."&cliente=".$row['FKCliente'].'\"><i class=\"fas fa-trash-alt\"></i></a>';
      $table.='{"Nombre":"'.$row['Nombre'].'","Apellido":"'.$row['Apellido_Paterno'].'","Puesto":"'.$row['Puesto'].'","Telefono":"'.$row['Telefono'].'","Extencion":"'.$row['Extencion'].'","Celular":"'.$row['Celular'].'","Email":"'.$row['Email'].'","Acciones":"'.$edit.$delete.'"},';
    }
    $table = substr($table,0,strlen($table)-1);
  	echo '{"data":['.$table.']}';
  }
 ?>
