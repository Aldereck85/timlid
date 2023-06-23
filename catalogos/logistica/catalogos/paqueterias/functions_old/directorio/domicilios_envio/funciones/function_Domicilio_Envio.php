<?php
  require_once('../../../../../include/db-conn.php');
  if(isset($_GET['id'])){
    $id =  $_GET['id'];
    $stmt = $conn->prepare('SELECT * FROM domicilio_de_envio_paqueterias WHERE FKPaqueteria = :id');
    $stmt->execute(array(':id'=>$id));
    $table="";
    while (($row = $stmt->fetch()) !== false) {
      $numInt = "S/N";
      if(isset($row['Numero_Interior']) && !empty($row['Numero_Interior'])){
        $numInt = $row['Numero_Interior'];
      }
      $edit = '<a class=\"btn btn-primary btnMargin\" href=\"#\" data-toggle=\"modal\" data-target=\"#editar_DomiciliosEnvioPaqueteria\" onclick=\"obtenerIdDomiciliosEnvioPaqueteriaEditar('.$row['PKDomicilio'].')\"><i class=\"fas fa-edit\"></i> Editar</a>&nbsp;&nbsp;';
  		$delete ='<a class=\"btn btn-danger btnMargin\" href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_DomiciliosEnvioPaqueteria\" onclick=\"obtenerIdDomiciliosEnvioPaqueteriaEliminar('.$row['PKDomicilio'].",".$row['FKPaqueteria'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a>';
      $table.='{"Calle":"'.$row['Calle'].'","Numero exterior":"'.$row['Numero_exterior'].'","Numero interior":"'.$numInt.'","Colonia":"'.$row['Colonia'].'","Municipio":"'.$row['Municipio'].'","Estado":"'.$row['Estado'].'","Codigo Postal":"'.$row['CP'].'","Locacion":"'.$row['Locacion'].'","Acciones":"'.$edit.$delete.'"},';
    }
    $table = substr($table,0,strlen($table)-1);
  	echo '{"data":['.$table.']}';
  }
 ?>
