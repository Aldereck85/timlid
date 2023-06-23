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

$stmtc = $conn->prepare("SELECT if (tcp.ColumnaAfectada = null or tcp.ColumnaAfectada = '','PKProducto', tcp.ColumnaAfectada) as  ColumnaAfectada
                        FROM columnas_productos as cp 
                          inner JOIN tipo_columna_productos tcp ON cp.FKTipoColumnaProductos = tcp.PKTipoColumnaProductos 
                        WHERE PKColumnasProductos = :indiceOrder limit 1;");
$stmtc->execute(array(':indiceOrder'=>$indice));
$rowc = $stmtc->fetchAll();

foreach ($rowc as $rc) { 
  $_columnaAfectada = $rc['ColumnaAfectada'];
}

$stmtp = $conn->prepare("SELECT p.ClaveInterna as 'Clave Interna',
                                p.Nombre as 'Nombre',
                                p.Descripcion as 'Descripción',
                                p.CodigoBarras as 'Código de barras',
                                catp.CategoriaProductos as 'Categoría',
                                marp.MarcaProducto as 'Marca',
                                cs.Clave as 'Clave SAT',
                                csu.Clave AS 'Unidad SAT'
                        FROM productos p
                          left join categorias_productos catp on p.FKCategoriaProducto = catp.PKCategoriaProducto
                          left join marcas_productos marp on p.FKMarcaProducto  = marp.PKMarcaProducto
                          left join info_fiscal_productos ifp on p.PKProducto = ifp.FKProducto
                          inner join claves_sat cs on ifp.FKClaveSAT = cs.PKClaveSAT
                          inner join claves_sat_unidades csu on ifp.FKClaveSATUnidad = csu.PKClaveSATUnidad
                        where p.empresa_id = :pkEmpresa and 
                          CONVERT(concat(ifnull(p.PKProducto,''),
                            ifnull(p.Nombre,''),
                            ifnull(p.ClaveInterna,''),
                            ifnull(p.CodigoBarras,''),
                            ifnull(marp.MarcaProducto,''),
                            ifnull(catp.CategoriaProductos,''),
                            ifnull(p.Descripcion,''),
                            ifnull(cs.Clave,''),
                            ifnull(csu.Clave,'')
                          )USING utf8) regexp '".$search."'
                        order by p.".$_columnaAfectada." ".$sort."
                        ;");
$stmtp->execute(array(':pkEmpresa'=>$PKEmpresa));
$rowp = $stmtp->fetchall(PDO::FETCH_ASSOC);


$filename = "Productos.xls";
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=".$filename);
//Arreglo de las cabeceras del excel
$headers = array('Clave Interna','Nombre','Descripción','Código de barras','Categoría','Marca','Clave SAT','Unidad SAT');
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