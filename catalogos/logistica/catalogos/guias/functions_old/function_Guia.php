
<?php
require_once('../../../include/db-conn.php');

$stmt = $conn->prepare('SELECT ng.PKGuiaEnvio,ng.Descripcion, ng.Tipo_de_Pago, p.Nombre_Comercial FROM guias_envio as ng
	LEFT JOIN paqueterias as p ON p.PKPAqueteria = ng.FKPaqueteria');
$stmt->execute();
$table="";
$no = 1;
while (($row = $stmt->fetch()) !== false) {

if($row['Tipo_de_Pago'] == 0)
	$tipopago = "Prepagadas";
else
	$tipopago = "Por consumo";

    $edit = '<a class=\"btn btn-primary\" href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Guia\" class=\"btn btn-primary\" onclick=\"obtenerIdGuiaEditar('.$row['PKGuiaEnvio'].');\"><i class=\"fas fa-edit\"></i> Editar</a>&nbsp;&nbsp;';
		$delete ='<a class=\"btn btn-danger\" href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_Guia\" class=\"btn btn-danger\" onclick=\"obtenerIdGuiaEliminar('.$row['PKGuiaEnvio'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a>';

    $table.='{"No":"'.$no.'","Descripcion":"'.$row['Descripcion'].'","Tipo de pago":"'.$tipopago.'","Paqueteria":"'.$row['Nombre_Comercial'].'","Acciones":"'.$edit.$delete.'"},';
    $no++;
  }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
 ?>
