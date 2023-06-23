<?php
  require_once('../../../../../include/db-conn.php');
  if(isset($_GET['id'])){
    $id =  $_GET['id'];
    $stmt = $conn->prepare('SELECT PKCuenta,FKCliente,Nombre, No_de_cuenta,CLABE FROM cuentas_bancarias INNER JOIN bancos on PKBanco = FKBanco WHERE FKCliente = :id');
    $stmt->execute(array(':id'=>$id));
    $table="";
    while (($row = $stmt->fetch()) !== false) {
      $edit = '<a class=\"btn btn-primary btnMargin\" href=\"funciones/editar_Cuenta.php?id='.$row['PKCuenta']."&cliente=".$row['FKCliente'].'\"><i class=\"fas fa-edit\"></i> Editar</a>&nbsp;&nbsp;';
  		$delete ='<a class=\"btn btn-danger btnMargin\" href=\"funciones/eliminar_Cuenta.php?id='.$row['PKCuenta']."&cliente=".$row['FKCliente'].'\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a>';
      $table.='{"Banco":"'.$row['Nombre'].'","Cuenta":"'.$row['No_de_cuenta'].'","CLABE":"'.$row['CLABE'].'","Acciones":"'.$edit.$delete.'"},';
    }
    $table = substr($table,0,strlen($table)-1);
  	echo '{"data":['.$table.']}';
  }



 ?>
