<?php
require_once('../../../include/db-conn.php');
session_start();
$empresa = $_SESSION["IDEmpresa"];
/* $consulta = $_GET["consulta"]; */

/* $consulta = $_GET["toDo"]; */

$toggle;

$stmt;
  $stmt = $conn->prepare("SELECT pg.idpagos, pg.fecha_registro, pg.total, mcbe.FKProveedor, cpp.folio_factura, pv.NombreComercial
	from movimientos_cuentas_bancarias_empresa as mcbe 
		inner join pagos as pg on pg.idpagos = mcbe.id_pago
		inner join cuentas_por_pagar as cpp on  mcbe.cuenta_pagar_id = cpp.id
		inner join proveedores as pv on pv.PKProveedor = cpp.proveedor_id
       where pv.empresa_id = $empresa;");


/* $stmt = $conn->prepare("SELECT PKProveedor,  NombreComercial FROM proveedores Where empresa_id = $empresa"); */
/* $stmt = $conn->prepare("SELECT * from (
    Select pr.NombreComercial, PKProveedor, cp.folio_factura, cp.fecha_vencimiento  FROM cuentas_por_pagar as cp inner join proveedores 
                        as pr  ON cp.proveedor_id = pr.PKProveedor order by cp.fecha_vencimiento) as tabale group by NombreComercial"); */
$stmt->execute();


/* $stmt = $conn->prepare("SELECT id, folio_factura, num_serie_factura, subtotal, importe, fecha_factura, 
fecha_vencimiento,estatus_factura, NombreComercial 
FROM proveedores where Dias_credito between 0 and 30 and NombreComercial = Esteritam");
$stmt->execute(); */


$table="";
while (($row = $stmt->fetch()) !== false) {

    $row['fecha_registro'] = date("Y-m-d", strtotime($row['fecha_registro']));
  
    /* Guardamos en un JSON los datos de la consulta  */
    $table.='{"Proveedor":"'.$row['NombreComercial'].
        '","idpagos":"'.$row['idpagos'].
        '","Fecha":"'.$row['fecha_registro'].
        '","Total":"'.$row['total'].
        '","Folio factura":"'.$row['folio_factura'].
        '"},'; 
    //,"Acciones":"'.'"
  }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
 ?>