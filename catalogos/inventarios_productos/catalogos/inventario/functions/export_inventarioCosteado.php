<?php
session_start();
require_once('../../../../../include/db-conn.php');
require_once('../../../../../vendor/shuchkin/simplexlsxgen/src/SimpleXLSXGen.php');

$PKEmpresa = $_SESSION["IDEmpresa"];


$stmtp = $conn->prepare("SELECT 
                            p.ClaveInterna Clave, 
                            p.Nombre,
                            cp.CategoriaProductos,
                            IFNULL(cvp.CostoCompra, 0) Costo, 
                            SUM(CAST(IFNULL(epp.existencia , 0) AS UNSIGNED)) Existencia,
                            (IFNULL(cvp.CostoCompra, 0) * SUM(CAST(IFNULL(epp.existencia, 0) AS DECIMAL))) Total 
                        FROM productos p 
                            LEFT JOIN costo_venta_producto cvp on p.PKProducto = cvp.FKProducto 
                            LEFT JOIN existencia_por_productos epp on p.PKProducto =epp.producto_id
                            LEFT JOIN categorias_productos cp on p.FKCategoriaProducto = cp.PKCategoriaProducto
                        WHERE p.empresa_id=:empresa
                        GROUP BY p.ClaveInterna
                        ;");
$stmtp->execute(array(':empresa'=>$_SESSION["IDEmpresa"]));
$rowp = $stmtp->fetchall(PDO::FETCH_ASSOC);

$stmtp = $conn->prepare("SELECT SUM(Total) AS TotalEmpresa FROM
                        ( 
                        SELECT 
                        (IFNULL(cvp.CostoCompra, 0) * CAST(IFNULL(epp.existencia, 0) AS DECIMAL)) AS Total
                        FROM productos p 
                            LEFT JOIN costo_venta_producto cvp on p.PKProducto = cvp.FKProducto 
                            LEFT JOIN existencia_por_productos epp on p.PKProducto =epp.producto_id 
                        WHERE p.empresa_id=:empresa
                        ) AS Tabla
                        ;");
$stmtp->execute(array(':empresa'=>$_SESSION["IDEmpresa"]));
$total = $stmtp->fetch();


//Arreglo de las cabeceras del excel
$book = [['<b>Clave Interna</b>','<b>Nombre</b>','<b>Categoría</b>','<b>Costo unitario</b>','<b>Existencia</b>','<b>Total</b>','<b>Total inventario costeado</b>']];//,'Serie' between cantidad y lote
$counter = 0;
foreach($rowp as $row){
    if($counter == 0){
        $row[] = $total['TotalEmpresa'];
        $book[] =  $row;
    }else{
        $book[] =  $row;
    }
  ++$counter;
}
//Mostrar las cabeceras siempre
$xlsx = Shuchkin\SimpleXLSXGen::fromArray( $book, 'Inventario costeado' );
$xlsx->downloadAs('inventario_costeado.xlsx'); // or downloadAs('books.xlsx') or $xlsx_content = (string) $xlsx 

?>