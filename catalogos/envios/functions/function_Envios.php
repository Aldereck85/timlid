<?php
require_once('../../../include/db-conn.php');

$stmt = $conn->prepare('SELECT e.PKEnvio,e.Numero_rastreo,e.Estatus,e.FKFactura,p.PKPaqueteria,p.Nombre_Comercial, DATE_FORMAT(e.FechaEnvio, "%d/%m/%Y") as FechaEnvio,DATE_FORMAT(e.FechaEntrega, "%d/%m/%Y") as FechaEntrega FROM envios as e INNER JOIN paqueterias as p ON e.FKPaqueteria = p.PKPaqueteria ORDER BY e.FKFactura, e.PKEnvio');
$stmt->execute();
$table="";
while (($row = $stmt->fetch()) !== false) {
  //href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Envio\" class=\"btn btn-primary\" onclick=\"obtenerIdEnvioEditar('.$row['PKEnvio'].');\"><i class=\"fas fa-edit\"></i> Editar
  //href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_Envio\" class=\"btn btn-danger\" onclick=\"obtenerIdEnvioEliminar('.$row['PKEnvio'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar
    $edit = '<a class=\"btn btn-primary\" href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Envio\" class=\"btn btn-primary\" onclick=\"obtenerIdEnvioEditar('.$row['PKEnvio'].');\"><i class=\"fas fa-edit\"></i> Editar</a>&nbsp;&nbsp;';
    $agregarProductos = '<a class=\"btn btn-primary\" href=\"functions/Agregar_Productos_Envios.php?id='.$row['PKEnvio'].'&factura='.$row['FKFactura'].'\"><i class=\"fas fa-box\"></i> Productos</a>&nbsp;&nbsp;';
		$delete ='<a class=\"btn btn-danger\" href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_Envio\" class=\"btn btn-danger\" onclick=\"obtenerIdEnvioEliminar('.$row['PKEnvio'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a>';
    $table.='{"Id de envio":"'.$row['PKEnvio'].'","Numero de rastreo":"'.$row['Numero_rastreo'].'","Estatus":"'.$row['Estatus'].'","Factura":"'.$row['FKFactura'].'","Paqueteria":"'.$row['Nombre_Comercial'].'","Fecha Envio":"'.$row['FechaEnvio'].'","Fecha Entrega":"'.$row['FechaEntrega'].'","Acciones":"'.$edit.$agregarProductos.$delete.'"},';
  }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
 ?>
