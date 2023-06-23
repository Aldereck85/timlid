<?php
  require_once('../../../../../include/db-conn.php');
  if(isset($_GET['id'])){
    $id =  $_GET['id'];
    $stmt = $conn->prepare('SELECT * FROM domicilio_de_envio WHERE FKCliente = :id');
    $stmt->execute(array(':id'=>$id));
    $table="";
    while (($row = $stmt->fetch()) !== false) {
      $edit = '<a class=\"btn btn-primary btnMargin\" href=\"funciones/editar_Domicilio_Envio.php?id='.$row['PKDomicilio']."&cliente=".$row['FKCliente'].'\"><i class=\"fas fa-edit\"></i> Editar</a>&nbsp;&nbsp;';
  		$delete ='<a class=\"btn btn-danger btnMargin\" href=\"funciones/eliminar_Domicilio_Envio.php?id='.$row['PKDomicilio']."&cliente=".$row['FKCliente'].'\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a>';
      $table.='{"Calle":"'.$row['Calle'].'","Numero exterior":"'.$row['Numero_exterior'].'","Numero interior":"'.$row['Numero_Interior'].'","Colonia":"'.$row['Colonia'].'","Municipio":"'.$row['Municipio'].'","Estado":"'.$row['Estado'].'","Codigo Postal":"'.$row['CP'].'","Locacion":"'.$row['Locacion'].'","Acciones":"'.$edit.$delete.'"},';
    }
    $table = substr($table,0,strlen($table)-1);
  	echo '{"data":['.$table.']}';
  }
 ?>
