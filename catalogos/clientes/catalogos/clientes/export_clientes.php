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

$stmtc = $conn->prepare("SELECT if (tcc.ColumnaAfectada = null or tcc.ColumnaAfectada = '','PKCliente', tcc.ColumnaAfectada) as  ColumnaAfectada
                        FROM columnas_clientes as cc 
                          inner JOIN tipo_columna_clientes as tcc ON cc.FKTipoColumnaClientes = tcc.PKTipoColumnaClientes 
                        WHERE PKColumnasClientes = :indiceOrder limit 1;");
$stmtc->execute(array(':indiceOrder'=>$indice));
$rowc = $stmtc->fetchAll();

foreach ($rowc as $rc) { 
  $_columnaAfectada = $rc['ColumnaAfectada'];
}

$stmtp = $conn->prepare("SELECT
                                c.NombreComercial as 'Nombre comercial',
                                mcc.MedioContactoCliente as 'Medio de contacto',
                                e.Nombres as 'Nombre del vendedor',
                                e.PrimerApellido as 'Apellido paterno del vendedor',
                                e.SegundoApellido as 'Apellido materno del vendedor',
                                e.Genero as 'Género del vendedor',
                                c.Telefono as 'Teléfono',
                                c.Email as 'Correo electrónico',
                                c.razon_social as 'Razón Social',
                                c.rfc as 'RFC',
                                c.Calle as 'Calle',
                                c.Numero_exterior as 'Número exterior',
                                c.Numero_Interior as 'Número interior',
                                c.Colonia as 'Colonia',
                                c.Municipio as 'Municipio',
                                p.Pais as 'País',
                                ef.Estado as 'Estado',
                                c.codigo_postal as 'Código postal'
                        FROM clientes c 
                          left join empleados e on c.empleado_id = e.PKEmpleado
							            left join medios_contacto_clientes mcc on c.medio_contacto_id = mcc.PKMedioContactoCliente
                          left join paises p on c.pais_id = p.PKPais
                          left join estados_federativos ef on c.estado_id = ef.PKEstado
                        where c.empresa_id = :pkEmpresa and
                          CONVERT(concat(
                            ifnull(c.PKCliente,''),
                            ifnull(c.NombreComercial,''),
                            ifnull(mcc.MedioContactoCliente,''),
                            ifnull(e.Nombres,''),
                            ifnull(e.PrimerApellido,''),
                            ifnull(e.SegundoApellido,''),
                            ifnull(e.Genero,''),
                            ifnull(c.Telefono,''),
                            ifnull(c.Email,''),
                            ifnull(c.razon_social,''),
                            ifnull(c.rfc,''),
                            ifnull(c.Calle,''),
                            ifnull(c.Numero_exterior,''),
                            ifnull(c.Numero_Interior,''),
                            ifnull(c.Colonia,''),
                            ifnull(c.Municipio,''),
                            ifnull(p.Pais,''),
                            ifnull(ef.Estado,''),
                            ifnull(c.codigo_postal,'')
                          )USING utf8) regexp '".$search."'
                        order by c.".$_columnaAfectada." ".$sort."
                        ;");
$stmtp->execute(array(':pkEmpresa'=>$PKEmpresa));
$rowp = $stmtp->fetchall(PDO::FETCH_ASSOC);


$filename = "Clientes.xls";
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=".$filename);
//Arreglo de las cabeceras del excel
$headers = array('Nombre comercial','Medio de contacto','Nombre del vendedor','Apellido paterno del vendedor','Apellido materno del vendedor', 'Género del vendedor', 'Teléfono', 'Correo electrónico', 'Razón Social', 'RFC', 'Calle', 'Número exterior', 'Número interior', 'Colonia', 'Municipio', 'Pais', 'Estado', 'Código Postal');
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