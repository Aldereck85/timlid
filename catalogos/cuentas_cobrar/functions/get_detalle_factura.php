<?php
require_once('../../../include/db-conn.php');
session_start();

//función para dar formato a las cantidades
require_once('function_formatoCantidad.php');

$empresa = $_SESSION["IDEmpresa"];

$is_invoice = isset($_GET['idFactura']) ? true : false;

$id = $is_invoice ? $_GET['idFactura'] : $_GET['idVenta'];

if($is_invoice){
  $query = ("SELECT p.ClaveInterna, p.Nombre, df.cantidad, df.precio 
  from productos p inner join detalle_facturacion df on df.factura_id=:id, facturacion f 
  where p.PKProducto=df.producto_id and f.empresa_id=:empresa and f.id=df.factura_id and f.prefactura = 0");
}else{
  $query = ("SELECT p.ClaveInterna, p.Nombre, dv.cantidad, dv.precio 
            from productos p 
              inner join detalle_venta_directa dv on dv.FKVentaDirecta=:id, ventas_directas vd 
            where p.PKProducto = dv.FKProducto and vd.empresa_id=:empresa and vd.empresa_id !=6 and vd.PKVentaDirecta=dv.FKVentaDirecta");  
}

$stmt = $conn->prepare($query);
$stmt->bindValue(":empresa",$empresa);
$stmt->bindValue(":id",$id);  
$stmt->execute();

$table="";
while (($row = $stmt->fetch()) !== false) {
    //calculamos el importe
    $importe=$row['cantidad']*$row['precio'];
    $importe="$".formatoCantidad($importe);

    /* Guardamos en un JSON los datos de la consulta  */
    $row['precio']="$".formatoCantidad($row['precio']);
    $table.='{"Clave":"'.$row['ClaveInterna'].'",
        "Descripcion":"'.$row['Nombre'].'",
        "Cantidad":"'.$row['cantidad'].'",
        "Precio":"'.$row['precio'].'", 
        "Importe":"'.$importe.'"},'; 
  }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
 ?>