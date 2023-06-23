<?php
  require_once('../../../../../include/db-conn.php');
  if(isset($_GET['id'])){
    $id =  $_GET['id'];
    $stmt = $conn->prepare('SELECT PKCuentaPaqueteria,FKPaqueteria,Nombre, No_de_cuenta,CLABE FROM cuentas_bancarias_paqueterias INNER JOIN bancos on PKBanco = FKBanco WHERE FKPaqueteria = :id');
    $stmt->execute(array(':id'=>$id));
    $table="";
    while (($row = $stmt->fetch()) !== false) {
      $edit = '<a class=\"btn btn-primary btnMargin\" href=\"#\" data-toggle=\"modal\" data-target=\"#editar_CuentaPaqueteria\" onclick=\"obtenerIdCuentaPaqueteriaEditar('.$row['PKCuentaPaqueteria'].')\"><i class=\"fas fa-edit\"></i> Editar</a>&nbsp;&nbsp;';
  		$delete ='<a class=\"btn btn-danger btnMargin\" href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_CuentaPaqueteria\" onclick=\"obtenerIdCuentaPaqueteriaEliminar('.$row['PKCuentaPaqueteria'].",".$row['FKPaqueteria'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a>';
      $table.='{"Banco":"'.$row['Nombre'].'","Cuenta":"'.$row['No_de_cuenta'].'","CLABE":"'.$row['CLABE'].'","Acciones":"'.$edit.$delete.'"},';
    }
    $table = substr($table,0,strlen($table)-1);
  	echo '{"data":['.$table.']}';
  }



 ?>
