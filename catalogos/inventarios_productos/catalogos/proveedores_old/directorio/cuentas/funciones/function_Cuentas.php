<?php
  require_once('../../../../../include/db-conn.php');
  if(isset($_GET['id'])){
    $id =  $_GET['id'];
    $stmt = $conn->prepare('SELECT PKCuentaProveedor,FKProveedor,Nombre, No_de_cuenta,CLABE FROM cuentas_bancarias_proveedores INNER JOIN bancos on PKBanco = FKBanco WHERE FKProveedor = :id');
    $stmt->execute(array(':id'=>$id));
    $table="";
    while (($row = $stmt->fetch()) !== false) {
      $edit = '<a class=\"btn btn-primary btnMargin\" href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Cuenta\" onclick=\"obtenerIdCuentaEditar('.$row['PKCuentaProveedor'].')\"><i class=\"fas fa-edit\"></i> Editar</a>&nbsp;&nbsp;';
  		$delete ='<a class=\"btn btn-danger btnMargin\" href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_Cuenta\" onclick=\"obtenerIdCuentaEliminar('.$row['PKCuentaProveedor'].",".$row['FKProveedor'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a>';
      $table.='{"Banco":"'.$row['Nombre'].'","Cuenta":"'.$row['No_de_cuenta'].'","CLABE":"'.$row['CLABE'].'","Acciones":"'.$edit.$delete.'"},';
    }
    $table = substr($table,0,strlen($table)-1);
  	echo '{"data":['.$table.']}';
  }



 ?>
