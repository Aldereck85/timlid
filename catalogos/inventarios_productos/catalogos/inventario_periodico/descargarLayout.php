<?php
session_start();
require_once('../../../../include/db-conn.php');
require_once('../../../../vendor/shuchkin/simplexlsxgen/src/SimpleXLSXGen.php');

$sucursal = $_GET["sucursal"];

$stmtp = $conn->prepare("SELECT
                          dips.clave,
                          p.Nombre,
                          dips.cantidad_sistema,
                          (CASE WHEN ips.conteo = 2 THEN dips.`cantidad_toma 1` WHEN ips.conteo = 3 THEN dips.`cantidad_toma 2` WHEN ips.conteo = 4 THEN dips.`cantidad_toma 3` ELSE dips.cantidad_toma END) AS cantidad,
                          dips.numero_lote,
                          dips.caducidad
                        FROM detalle_inventario_por_sucursales dips
                          INNER JOIN productos p ON dips.producto_id = p.PKProducto
                          INNER JOIN inventario_por_sucursales ips on dips.inventario_id = ips.id
                        WHERE ips.sucursal_id = :sucursal AND ips.tipo=1 
                        ;");
$stmtp->execute(array(':sucursal'=>$sucursal));
$rowp = $stmtp->fetchall(PDO::FETCH_ASSOC);


//Arreglo de las cabeceras del excel
$book = [['<b>Clave Interna</b>','<b>Nombre</b>','<b>Existencia</b>','<b>Cantidad</b>','<b>Lote</b>','<b>Caducidad</b>']];//,'Serie' between cantidad y lote
foreach($rowp as $row){
  $book[] =  $row;
}
//Mostrar las cabeceras siempre
$xlsx = Shuchkin\SimpleXLSXGen::fromArray( $book, 'Inventario' );
$xlsx->downloadAs('inventario.xlsx'); // or downloadAs('books.xlsx') or $xlsx_content = (string) $xlsx 

?>