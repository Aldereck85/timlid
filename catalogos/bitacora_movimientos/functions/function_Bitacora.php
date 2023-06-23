
<?php
require_once('../../../include/db-conn.php');

$stmt = $conn->prepare('SELECT e.Primer_Nombre,e.Apellido_Paterno,b.Fecha_Movimiento,m.Mensaje FROM bitacora_movimientos AS b
        LEFT JOIN usuarios AS u ON b.FKUsuario = u.PKUsuario
        LEFT JOIN empleados AS e ON u.FKEmpleado = e.PKEmpleado
        LEFT JOIN mensajes_acciones AS m ON b.FKMensaje = m.PKMensajesAcciones  ');
$stmt->execute();
$table="";
$no = 1;
while (($row = $stmt->fetch()) !== false) {
    $usuario = $row['Primer_Nombre']." ".$row['Apellido_Paterno'];
    $fecha = date('d/m/Y',strtotime($row['Fecha_Movimiento']));
    //$edit = '<a class=\"btn btn-primary\" href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Marca\" class=\"btn btn-primary\" onclick=\"obtenerIdMarcaEditar('.$row['PKMarca'].');\"><i class=\"fas fa-edit\"></i> Editar</a>&nbsp;&nbsp;';
		//$delete ='<a class=\"btn btn-danger\" href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_Marca\" class=\"btn btn-danger\" onclick=\"obtenerIdMarcaEliminar('.$row['PKMarca'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a>';

    $table.='{"No":"'.$no.'","Usuario":"'.$usuario.'","Fecha de movimiento":"'.$fecha.'","Mensaje":"'.$row['Mensaje'].'"},';
    $no++;
  }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
 ?>
