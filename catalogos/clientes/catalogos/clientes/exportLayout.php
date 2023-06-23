<?php
require_once('../../../../vendor/shuchkin/simplexlsxgen/src/SimpleXLSXGen.php');
//Arreglo de las cabeceras del excel
$book = [['<b>Nombre comercial</b>','<b>Medio de contacto</b>','<b>Nombre vendedor</b>','<b>Apellido paterno vendedor</b>','<b>Apellido materno vendedor</b>','<b>Género</b>','<b>Teléfono</b>','<b>Correo electrónico</b>','<b>Razón social</b>','<b>RFC</b>','<b>Régimen fiscal</b>','<b>Calle</b>','<b>No. Exterior</b>','<b>No. Interior</b>','<b>Colonia</b>','<b>Municipio</b>','<b>País</b>','<b>Estado</b>','<b>Código postal</b>']];

$xlsx = Shuchkin\SimpleXLSXGen::fromArray( $book, 'Clientes' );
$xlsx->downloadAs('clientes.xlsx'); // or downloadAs('books.xlsx') or $xlsx_content = (string) $xlsx 
?>