<?php
require_once('../../../../vendor/shuchkin/simplexlsxgen/src/SimpleXLSXGen.php');
//Arreglo de las cabeceras del excel
$book = [['<b>Clave</b>','<b>Nombre</b>','<b>Descripción</b>','<b>Cantidad</b>','<b>Lote</b>','<b>Caducidad</b>']]; //'<b>Serie</b>', between cantidad y lote

$xlsx = Shuchkin\SimpleXLSXGen::fromArray( $book, 'Inventario inicial' );
$xlsx->downloadAs('inventario_inicial.xlsx'); // or downloadAs('books.xlsx') or $xlsx_content = (string) $xlsx 
?>