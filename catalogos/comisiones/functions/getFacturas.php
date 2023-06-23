<?php
session_start();
require_once('../../../include/db-conn.php');
/*Recupera las facturas dependiendo los parámetros que haya dado el usuario*/ 

function GetEvn()
{
    include "../../../include/db-conn.php";
    $appUrl = $_ENV['APP_URL'] ?? 'https://app.timlid.com/';
    return ['server' => $appUrl];
}

$envVariables = GetEvn();
$appUrl = $envVariables['server'];
$empresa = $_SESSION["IDEmpresa"];
$id = $_SESSION["PKUsuario"];
$vendedor = $_REQUEST["vendedor"];
$tipo = $_REQUEST["tipo"];
$fecha_desde = $_REQUEST["fecha_desde"];
$fecha_hasta = $_REQUEST["fecha_hasta"];
$porcentaje = $_REQUEST["porcentaje"];

//facturas y ventas
if($tipo == 1){
    $stmt = $conn->prepare('SELECT f.id as id, 
        concat(f.serie, " ", f.folio) as SerieFolio, 
        cli.razon_social as razon_social, 
        MAX(p.fecha_pago) as fecha_factura,
        (select ifnull(sum(df.subtotal),0) FROM detalle_facturacion as df WHERE df.factura_id=f.id) as monto_fac_si, 
        (select ifnull(sum(df.subtotal),0)*:porcentaje FROM detalle_facturacion as df WHERE df.factura_id=f.id) as monto_comisionado,
        1 as tipoDoc
    FROM clientes cli 
        INNER JOIN facturacion f ON cli.PKCliente=f.cliente_id 
        INNER JOIN movimientos_cuentas_bancarias_empresa mcbe on f.id=mcbe.id_factura and mcbe.tipo_CuentaCobrar = 2 and mcbe.estatus = 1
        inner join pagos p on mcbe.id_pago=p.idpagos
    WHERE f.flag_comisionada = 0 AND f.estatus=3 and f.empleado_id=:vendedor and f.empresa_id=:empresa and p.estatus = 1 and p.fecha_pago between :fecha_desde and :fecha_hasta
    GROUP BY f.id

    union

    SELECT vd.PKVentaDirecta as id, 
        vd.Referencia as SerieFolio, 
        cli.razon_social as razon_social, 
        MAX(p.fecha_pago) as fecha_factura,
        vd.Subtotal as monto_fac_si, 
        vd.Subtotal*:porcentaje2 as monto_comisionado,
        2 as tipoDoc
    FROM clientes cli 
        INNER JOIN ventas_directas vd ON cli.PKCliente=vd.FKCliente 
        INNER JOIN movimientos_cuentas_bancarias_empresa mcbe on vd.PKVentaDirecta=mcbe.id_factura and mcbe.tipo_CuentaCobrar = 1 and mcbe.estatus = 1
        inner join pagos p on mcbe.id_pago=p.idpagos
    WHERE vd.flag_comisionada = 0 AND vd.estatus_cuentaCobrar=3 and vd.empleado_id=:vendedor2 and vd.empresa_id=:empresa2 and p.estatus = 1 and p.fecha_pago between :fecha_desde2 and :fecha_hasta2
    GROUP BY vd.PKVentaDirecta;');
    $stmt->bindValue(":empresa",$empresa);
    $stmt->bindValue(":empresa2",$empresa);
    $stmt->bindValue(":fecha_desde",$fecha_desde);
    $stmt->bindValue(":fecha_desde2",$fecha_desde);
    $stmt->bindValue(":fecha_hasta",$fecha_hasta);
    $stmt->bindValue(":fecha_hasta2",$fecha_hasta);
    $stmt->bindValue(":vendedor",$vendedor);
    $stmt->bindValue(":vendedor2",$vendedor);
    $stmt->bindValue(":porcentaje",$porcentaje);
    $stmt->bindValue(":porcentaje2",$porcentaje);
}elseif($tipo == 2){
    //facturas
    $stmt = $conn->prepare('SELECT f.id as id, 
        concat(f.serie, " ", f.folio) as SerieFolio, 
        cli.razon_social as razon_social, 
        MAX(p.fecha_pago) as fecha_factura,
        (select ifnull(sum(df.subtotal),0) FROM detalle_facturacion as df WHERE df.factura_id=f.id) as monto_fac_si, 
        (select ifnull(sum(df.subtotal),0)*:porcentaje FROM detalle_facturacion as df WHERE df.factura_id=f.id) as monto_comisionado,
        1 as tipoDoc
    FROM clientes cli 
        INNER JOIN facturacion f ON cli.PKCliente=f.cliente_id 
        INNER JOIN movimientos_cuentas_bancarias_empresa mcbe on f.id=mcbe.id_factura and mcbe.tipo_CuentaCobrar = 2 and mcbe.estatus = 1
        inner join pagos p on mcbe.id_pago=p.idpagos
    WHERE f.flag_comisionada = 0 AND f.estatus=3 and f.empleado_id=:vendedor and f.empresa_id=:empresa and p.estatus = 1 and p.fecha_pago between :fecha_desde and :fecha_hasta
    GROUP BY f.id');
    $stmt->bindValue(":empresa",$empresa);
    $stmt->bindValue(":fecha_desde",$fecha_desde);
    $stmt->bindValue(":fecha_hasta",$fecha_hasta);
    $stmt->bindValue(":vendedor",$vendedor);
    $stmt->bindValue(":porcentaje",$porcentaje);
}elseif($tipo == 3){
    //ventas
    $stmt = $conn->prepare('SELECT vd.PKVentaDirecta as id, 
        vd.Referencia as SerieFolio, 
        cli.razon_social as razon_social, 
        MAX(p.fecha_pago) as fecha_factura,
        vd.Subtotal as monto_fac_si, 
        vd.Subtotal*:porcentaje2 as monto_comisionado,
        2 as tipoDoc
    FROM clientes cli 
        INNER JOIN ventas_directas vd ON cli.PKCliente=vd.FKCliente 
        INNER JOIN movimientos_cuentas_bancarias_empresa mcbe on vd.PKVentaDirecta=mcbe.id_factura and mcbe.tipo_CuentaCobrar = 1 and mcbe.estatus = 1
        inner join pagos p on mcbe.id_pago=p.idpagos
    WHERE vd.flag_comisionada = 0 AND vd.estatus_cuentaCobrar=3 and vd.empleado_id=:vendedor2 and vd.empresa_id=:empresa2 and p.estatus = 1 and p.fecha_pago between :fecha_desde2 and :fecha_hasta2
    GROUP BY vd.PKVentaDirecta;');
    $stmt->bindValue(":empresa2",$empresa);
    $stmt->bindValue(":fecha_desde2",$fecha_desde);
    $stmt->bindValue(":fecha_hasta2",$fecha_hasta);
    $stmt->bindValue(":vendedor2",$vendedor);
    $stmt->bindValue(":porcentaje2",$porcentaje);
}


$stmt->execute();

$table="";
$html = "";

while (($row = $stmt->fetch()) !== false) {

    $html = '<input class=\"contarFila\" type=\"checkbox\" name=\"invoiceSelected\" id=\" \" onclick=\"sumar(this)\" value=\"'.$row['id'].'-'.$row['monto_comisionado'].'\" data-tipo=\"'.$row['tipoDoc'].'\" checked>';
    
    $row['monto_fac_si'] = number_format($row['monto_fac_si'], 2, '.', ' ');
    $row['monto_comisionado'] = number_format($row['monto_comisionado'], 2, '.', ' ');

    $row['razon_social'] = str_replace('"', '\"', $row['razon_social']);

    //llena la tabla en formato json
    $table.= '{"Folio":"'.$row['SerieFolio'].'",
        "Fecha factura":"'.date("d-m-Y", strtotime($row['fecha_factura'])).'",
        "Razon social": "'.$row['razon_social'].'",
        "Monto facturado":"$'.$row['monto_fac_si'].'",
        "Monto comisionado":"$'.$row['monto_comisionado'].'",
        "Seleccionar":"'.$html.'"},'; 
}

$table = substr($table,0,strlen($table)-1);
echo '{"data":['.$table.']}';  
?>