<?php
require_once('../../../../vendor/shuchkin/simplexlsxgen/src/SimpleXLSXGen.php');
//Arreglo de las cabeceras del excel
$book = [['<b>Clave Interna</b>','<b>Nombre</b>','<b>Descripción</b>','<b>Código de barras</b>','<b>Categoría</b>','<b>Marca</b>','<b>Costo de fabricación</b>','<b>Moneda</b>','<b>Clave SAT</b>','<b>Unidad SAT</b>']];

$xlsx = Shuchkin\SimpleXLSXGen::fromArray( $book, 'Productos' );
$xlsx->downloadAs('productos_materia_prima.xlsx'); // or downloadAs('books.xlsx') or $xlsx_content = (string) $xlsx 
?>