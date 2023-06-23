<?php

  require_once('../../../include/db-conn.php');

  $stmt = $conn->prepare('SELECT * FROM gh_checador');
  $stmt->execute();
  $table="";
  while (($row = $stmt->fetch()) !== false) {
    $editar = '<a class=\"btn btn-primary\" href=\"functions/editar_empleado.php?id='.$row['PKChecada'].'\"><i class=\"fas fa-user-edit\"></i> Editar</a>&nbsp;';
    $datos = '<a class=\"btn btn-secondary\" href=\"functions/detalles_empleo.php?id='.$row['PKChecada'].'\"> <i class=\"fas fa-chalkboard-teacher\"></i> Puesto</a>&nbsp;';
    $ficha = '<a class=\"btn btn-success\" href=\"functions/ficha_empleado.php?id='.$row['PKChecada'].'\"> <i class=\"far fa-id-card\"></i> Ficha</a>&nbsp;';
		$borrar ='<a class=\"btn btn-danger\" href=\"functions/eliminar_empleado.php?id='.$row['PKChecada'].'\"><i class=\"fas fa-user-times\"></i> Eliminar</a>';
    $documentos ='<a class=\"btn btn-dark\" href=\"functions/descargar_Documentos.php?id='.$row['PKChecada'].'\"><i class=\"fas fa-folder\"></i> Documentos</a>&nbsp;';

    $funciones= '<div class=\"dropdown\"><button class=\"btn btn-secondary btnMargin dropdown-toggle\" type=\"button\" id=\"dropdownMenuButton\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\"><i class=\"fas fa-folder-open\"></i> Directorio</button><div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuButton\"><a class=\"dropdown-item\" href=\"functions/ficha_empleado.php?id='.$row['PKChecada'].'\"><i class=\"far fa-id-card\"></i> Ficha</a><a class=\"dropdown-item\" href=\"functions/editar_empleado.php?id='.$row['PKChecada'].'\"><i class=\"fas fa-user-edit\"></i> Editar</a><a class=\"dropdown-item\" href=\"functions/detalles_empleo.php?id='.$row['PKChecada'].'\"><i class=\"fas fa-chalkboard-teacher\"></i> Puesto</a><a class=\"dropdown-item\" href=\"functions/nomina.php?id='.$row['PKChecada'].'\"><i class=\"fas fa-book-open\"></i> Nomina</a><a class=\"dropdown-item\" href=\"functions/eliminar_empleado.php?id='.$row['PKChecada'].'\"><i class=\"fas fa-user-times\"></i> Eliminar</a> </div></div>';


    $table.='{"Id empleado":"'.$row['FKUsuario'].'","Fecha":"'.$row['Fecha'].'","Entrada":"'.$row['Entrada'].'","Salida Comida":"'.$row['Salida_Comida'].'","Regreso Comida":"'.$row['Regreso_Comida'].'","Salida":"'.$row['Salida'].'","Estatus":"'.$row['Estatus'].'","Acciones":"'.$funciones.'"},';
  }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';

?>
