<?php
  require_once('../../../../../include/db-conn.php');
  if(isset($_GET['id'])){
    $id =  $_GET['id'];
    $stmt = $conn->prepare('SELECT * FROM domicilio_fiscal WHERE FKCliente = :id');
    $stmt->execute(array(':id'=>$id));
    $table="";
    while (($row = $stmt->fetch()) !== false) {
      $edit = '<a class=\"btn btn-outline-primary btnMargin\" href=\"funciones/editar_Domicilio_Fiscal.php?id='.$row['PKDomicilioFiscal']."&cliente=".$row['FKCliente'].'\"><i class=\"fas fa-edit\"></i> Editar</a>&nbsp;&nbsp;';
  		$delete ='<a class=\"btn btn-outline-danger btnMargin\" href=\"funciones/eliminar_Domicilio_Fiscal.php?id='.$row['PKDomicilioFiscal']."&cliente=".$row['FKCliente'].'\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a>';
      $table.='{"Razon Social":"'.$row['Razon_Social'].'","RFC":"'.$row['RFC'].'","Calle":"'.$row['Calle'].'","Numero exterior":"'.$row['Numero_exterior'].'","Numero interior":"'.$row['Numero_Interior'].'","Colonia":"'.$row['Colonia'].'","Municipio":"'.$row['Municipio'].'","Estado":"'.$row['Estado'].'","Codigo Postal":"'.$row['CP'].'","Locacion":"'.$row['Locacion'].'","Acciones":"'.$edit.$delete.'"},';
    }
    $table = substr($table,0,strlen($table)-1);
  	echo '{"data":['.$table.']}';
  }
 ?>
