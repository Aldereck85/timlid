<?php
require_once('../../../../vendor/shuchkin/simplexlsxgen/src/SimpleXLSXGen.php');
//Arreglo de las cabeceras del excel
$book = [['<b>Estado</b>','<b>Dias de crédito</b>','<b>Tipo de persona</b>','<b>RFC</b>','<b>Razón social</b>','<b>Nombre comercial</b>','<b>Giro</b>','<b>Nombre</b>','<b>Correo 1</b>','<b>Correo 2</b>','<b>Teléfono</b>','<b>Móvil</b>','<b>Calle</b>','<b>No. Exterior</b>','<b>No. Interior</b>','<b>Colonia</b>','<b>Localidad</b>','<b>Estado</b>','<b>Municipio</b>','<b>Código postal</b>','<b>Referencia</b>','<b>País</b>']];

$xlsx = Shuchkin\SimpleXLSXGen::fromArray( $book, 'Proveedores' );
$xlsx->downloadAs('proveedores.xlsx'); // or downloadAs('books.xlsx') or $xlsx_content = (string) $xlsx 
?>