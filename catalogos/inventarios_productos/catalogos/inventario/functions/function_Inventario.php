
<?php
require_once('../../../include/db-rm.php');

$stmt = $conn->prepare('SELECT * FROM inventarios as i
                        LEFT JOIN productos as p ON i.FKProducto = p.PKProducto');
$stmt->execute();
$table="";
$no = 1;
$presentacion = "";
while (($row = $stmt->fetch()) !== false) {

    //$edit = '<a class=\"btn btn-primary\" href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Marca\" class=\"btn btn-primary\" onclick=\"obtenerIdMarcaEditar('.$row['PKMarca'].');\"><i class=\"fas fa-edit\"></i> Editar</a>&nbsp;&nbsp;';
		//$delete ='<a class=\"btn btn-danger\" href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_Marca\" class=\"btn btn-danger\" onclick=\"obtenerIdMarcaEliminar('.$row['PKMarca'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a>';
    $watch = '<a class=\"btn btn-success\" href=\"functions/ver_DetalleProducto.php?id='.$row['FKProducto'].'\"><i class=\"fas fa-eye\"></i> Ver detalles</a>';
    $table.='{"No":"'.$no.'","Código":"'.$row['Codigo'].'","Descripción":"'.$row['Descripcion'].'","Cantidad":"'.$row['Cantidad'].'","Stock mínimo":"'.$row['StockMinimo'].'","Stock máximo":"'.$row['StockMaximo'].'","Acciones":"'.$watch.'"},';
    $no++;
  }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
 ?>
