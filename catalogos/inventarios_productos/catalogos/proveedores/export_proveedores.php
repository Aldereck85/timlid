<?php
session_start();
require_once('../../../../include/db-conn.php');

$PKEmpresa = $_SESSION["IDEmpresa"];
$sort = $_GET["sort"];
$search = $_GET["search"];
$indice = $_GET["indice"];

if ($indice == '' || $indice == null){
  $indice = 1;
}

$_columnaAfectada = '';

$stmtc = $conn->prepare("SELECT if (tcp.ColumnaAfectada = null or tcp.ColumnaAfectada = '','PKProveedor', tcp.ColumnaAfectada) as  ColumnaAfectada
                        FROM columna_proveedores as cp 
                          inner JOIN tipo_columna_proveedores tcp ON cp.FKTipoColumnaProveedores = tcp.PKTipoColumnaProveedores 
                        WHERE PKColumnasProveedores = :indiceOrder limit 1;");
$stmtc->execute(array(':indiceOrder'=>$indice));
$rowc = $stmtc->fetchAll();

foreach ($rowc as $rc) { 
  $_columnaAfectada = $rc['ColumnaAfectada'];
}

$stmtp = $conn->prepare("SELECT
                              p.tipo_persona as 'Tipo de persona',
                              dfp.RFC as 'RFC',
                              dfp.Razon_Social as 'Razón Social / Nombre',
                              p.NombreComercial as 'Nombre comercial',
                              p.Vendedor as 'Vendedor',
                              p.Email as 'Correo electrónico',
                              p.Telefono as 'Teléfono',
                              dfp.Calle as 'Calle',
                              dfp.Numero_exterior as 'Número exterior',
                              dfp.Numero_Interior  as 'Número interior',
                              dfp.Colonia as 'Colonia',
                              dfp.Localidad as 'Localidad',
                              e.Estado as 'Estado',
                              dfp.Municipio as 'Municipio',
                              dfp.CP as 'Código Postal',
                              dfp.Referencia as 'Referencia',
                              pa.Pais as 'Pais'
                        FROM proveedores p 
                        LEFT JOIN domicilio_fiscal_proveedor dfp
                        ON p.PKProveedor = dfp.FKProveedor
                        LEFT JOIN estados_federativos e
                        ON dfp.Estado = e.PKEstado
                        LEFT JOIN paises pa
                        ON dfp.Pais = pa.PKPais
                        where p.tipo = '1' and p.empresa_id = :pkEmpresa and
                          CONVERT(concat(
                            ifnull(p.tipo_persona,''),
                            ifnull(p.NombreComercial,''),
                            ifnull(p.Telefono,''),
                            ifnull(p.Email,''),
                            ifnull(p.Vendedor,''),
                            ifnull(dfp.RFC,''),
                            ifnull(dfp.Razon_Social, ''),
                            ifnull(dfp.Calle, ''),
                            ifnull(dfp.Numero_exterior, ''),
                            ifnull(dfp.Numero_Interior, ''),
                            ifnull(dfp.Colonia, ''),
                            ifnull(dfp.Localidad, ''),
                            ifnull(dfp.Municipio, ''),
                            ifnull(dfp.CP, ''),
                            ifnull(e.Estado, ''),
                            ifnull(dfp.Razon_Social, ''),
                            ifnull(pa.Pais, '')
                          )USING utf8) regexp '".$search."'
                        order by p.".$_columnaAfectada." ".$sort."
                        ;");
$stmtp->execute(array(':pkEmpresa'=>$PKEmpresa));
$rowp = $stmtp->fetchall(PDO::FETCH_ASSOC);


$filename = "Proveedores.xls";
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=".$filename);
//Arreglo de las cabeceras del excel
$headers = array('Tipo de persona','RFC','Razón Social / Nombre','Nombre comercial','Vendedor','Correo electrónico','Teléfono','Calle','Número exterior','Número interior','Colonia','Localidad','Estado','Municipio','Código Postal','Referencia','Pais');
//Mostrar las cabeceras siempre
  foreach($headers as $columna){
    echo utf8_decode($columna) . "\t";
  }
echo "\r\n";
  foreach($rowp as $record) {
    echo utf8_decode(implode("\t", array_values($record))) . "\r\n";
  }

exit;

?>