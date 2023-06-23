<?php
session_start();
require_once('../../../../include/db-conn.php');
require_once('../../../../vendor/shuchkin/simplexlsxgen/src/SimpleXLSXGen.php');

$PKEmpresa = $_SESSION["IDEmpresa"];
$sucursal = $_GET["sucursal"];

//dips.numero_serie, 20
$stmtp = $conn->prepare("SELECT
                            p.ClaveInterna,
                            p.Nombre,
                            p.Descripcion,
                            detalle_inventario.cantidad_toma,
                            /*IF(p.serie = 1, detalle_inventario.numero_serie, 'No aplica') as serie,*/ 
                            IF(p.lote = 1, detalle_inventario.numero_lote, 'No aplica') as lote,
                            IF(p.fecha_caducidad = 1, detalle_inventario.caducidad, 'No aplica') as caducidad
                        FROM productos p
                            INNER JOIN usuarios u ON p.empresa_id = u.empresa_id AND u.id = :usuario
                            LEFT JOIN (SELECT dips.id, dips.clave,  dips.numero_lote, dips.caducidad, dips.cantidad_toma, ips.sucursal_id FROM detalle_inventario_por_sucursales dips INNER JOIN inventario_por_sucursales ips ON dips.inventario_id = ips.id WHERE ips.sucursal_id = :sucursal) AS detalle_inventario
                            ON detalle_inventario.clave = p.ClaveInterna
                        ;");
$stmtp->execute(array(':usuario'=>$_SESSION["PKUsuario"], ':sucursal'=>$sucursal));
$rowp = $stmtp->fetchall(PDO::FETCH_ASSOC);


//Arreglo de las cabeceras del excel
$book = [['Clave Interna','Nombre','Descripción','Cantidad','Lote','Caducidad']];//,'Serie' between cantidad y lote
foreach($rowp as $row){
  $book[] =  $row;
}
//Mostrar las cabeceras siempre
$xlsx = Shuchkin\SimpleXLSXGen::fromArray( $book, 'Inventario inicial' );
$xlsx->downloadAs('inventario_inicial.xlsx'); // or downloadAs('books.xlsx') or $xlsx_content = (string) $xlsx 

?>