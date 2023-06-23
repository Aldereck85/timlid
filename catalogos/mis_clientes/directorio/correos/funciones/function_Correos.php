<?php
  require_once('../../../../../include/db-conn.php');
  if(isset($_GET['id'])){
    $id =  $_GET['id'];
    $stmt = $conn->prepare('SELECT PKEmail,Email,FKCliente FROM correos_clientes WHERE FKCliente = :id');
    $stmt->execute(array(':id'=>$id));
    $table="";
    while (($row = $stmt->fetch()) !== false) {
      $edit = '<a class=\"btn btn-primary btnMargin\" href=\"funciones/editar_Correo.php?id='.$row['PKEmail']."&cliente=".$row['FKCliente'].'\"><i class=\"fas fa-edit\"></i> Editar</a>&nbsp;&nbsp;';
  		$delete ='<a class=\"btn btn-danger btnMargin\" href=\"funciones/eliminar_Correo.php?id='.$row['PKEmail']."&cliente=".$row['FKCliente'].'\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a>';
      $table.='{"Email":"'.$row['Email'].'","Acciones":"'.$edit.$delete.'"},';
    }
    $table = substr($table,0,strlen($table)-1);
  	echo '{"data":['.$table.']}';
  }



 ?>
