<?php
require_once('../../../include/db-conn.php');
session_start();
$empresa = $_SESSION["IDEmpresa"];
//función para dar formato a las cantidades
require_once('function_formatoCantidad.php');

$id = ($_GET['idFactura']);
$stmt = $conn->prepare("select p.Nombre, df.descripcion, df.clave, df.cantidad, df.precio, 
df.numero_lote, df.numero_serie, df.caducidad from productos p inner join detalle_facturacion df on df.factura_id=:id, facturacion f 
where p.PKProducto=df.producto_id and f.empresa_id=:empresa and f.id=df.factura_id");
$stmt->bindValue(":id",$id);
$stmt->bindValue(":empresa",$empresa);
$stmt->execute();

$row['Nombre'] = str_replace('"', '\"', $row['Nombre']);
$row['descripcion'] = str_replace('"', '\"', $row['descripcion']);
$row['numero_lote'] = str_replace('"', '\"', $row['numero_lote']);
$row['numero_serie'] = str_replace('"', '\"', $row['numero_serie']);

$table="";
while (($row = $stmt->fetch()) !== false) {
        /* Guardamos en un JSON los datos de la consulta  */
    $row['precio']="$".formatoCantidad($row['precio']);
    $table.='{"Clave":"'.$row['clave'].'",
        "Producto":"'.$row['Nombre'].'",
        "Descripcion":"'.$row['descripcion'].'",
        "Cantidad":"'.$row['cantidad'].'",
        "Precio":"'.$row['precio'].'",
        "Lote":"'.$row['numero_lote'].'",
        "Serie":"'.$row['numero_serie'].'",
        "Caducidad":"'.$row['caducidad'].'"},'; 
  }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
 ?>