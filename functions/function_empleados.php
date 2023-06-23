<?php
  define("cServidor","localhost");
  define("cUsuario","root");
  define("cPass","");
  define("cBd","recursos_humanos");

  $conn = mysqli_connect(cServidor,cUsuario,cPass,cBd);
  $query = "SELECT * FROM empleados";
  $register = mysqli_query($conn,$query);
  $i=0;
  $table="";

  while($row = mysqli_fetch_array($register)){
    $edit = '<a class=\"btn btn-primary\" href=\"functions/editarMiembro.php?id='.$row['PKEmpleado'].'\">Editar</a>&nbsp;<br>';
		$delete ='<a class=\"btn btn-danger\" href=\"functions/eliminarMiembro.php?id='.$row['PKEmpleado'].'\">Eliminar</a>';
    $table.='{"Id empleado":"'.$row['PKEmpleado'].'","Primer nombre":"'.$row['Primer_Nombre'].'","Segundo nombre":"'.$row['Segundo_Nombre'].'","Apellido paterno":"'.$row['Apellido_Paterno'].'","Apellido materno":"'.$row['Apellido_Materno'].'","NSS":"'.$row['NSS'].'","CURP":"'.$row['CURP'].'","RFC":"'.$row['RFC'].'","Acciones":"'.$edit.$delete.'"},';
    $i++;
  }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
 ?>
