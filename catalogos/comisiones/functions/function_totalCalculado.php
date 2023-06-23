<?php
session_start();
require_once('../../../include/db-conn.php');
/* Calcula el monto total de las facturas ya con el porcentaje dado por el usuario*/ 

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
$porcentaje = ($porcentaje/100);

if($tipo == 1){
    $stmt = $conn->prepare('SELECT sum(totalCalculado) as totalCalculado from (
        SELECT ifnull(sum(df.subtotal),0)*:porcentaje as totalCalculado 
                FROM clientes cli 
                    INNER JOIN facturacion f ON cli.PKCliente=f.cliente_id 
                    inner join detalle_facturacion df on df.factura_id=f.id
                    INNER JOIN (select * from movimientos_cuentas_bancarias_empresa where estatus = 1 and tipo_CuentaCobrar = 2 group by id_factura order by Fecha) mcbe on f.id=mcbe.id_factura 
                    inner join pagos p on mcbe.id_pago=p.idpagos
                WHERE f.flag_comisionada = 0 AND f.estatus=3 and f.empleado_id=:vendedor and f.empresa_id=:empresa and p.estatus = 1 and p.fecha_pago between :fecha_desde and :fecha_hasta
                
                union 
                
                SELECT ifnull(vd.Subtotal,0)*:porcentaje2 as totalCalculado 
                FROM clientes cli 
                    INNER JOIN ventas_directas vd ON cli.PKCliente=vd.FKCliente 
                    INNER JOIN (select * from movimientos_cuentas_bancarias_empresa where estatus = 1 and tipo_CuentaCobrar = 1 group by id_factura order by Fecha) mcbe on vd.PKVentaDirecta=mcbe.id_factura 
                    inner join pagos p on mcbe.id_pago=p.idpagos
                WHERE vd.flag_comisionada = 0 AND vd.estatus_cuentaCobrar=3 and vd.empleado_id=:vendedor2 and vd.empresa_id=:empresa2 and p.estatus = 1 and p.fecha_pago between :fecha_desde2 and :fecha_hasta2
        ) as tabla;');
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
}else if($tipo == 2){
    $stmt = $conn->prepare('SELECT ifnull(sum(df.subtotal),0)*:porcentaje as totalCalculado 
        FROM clientes cli 
            INNER JOIN facturacion f ON cli.PKCliente=f.cliente_id 
            inner join detalle_facturacion df on df.factura_id=f.id
            INNER JOIN (select * from movimientos_cuentas_bancarias_empresa where estatus = 1 and tipo_CuentaCobrar = 2 group by id_factura order by Fecha) mcbe on f.id=mcbe.id_factura 
            inner join pagos p on mcbe.id_pago=p.idpagos
        WHERE f.flag_comisionada = 0 AND f.estatus=3 and f.empleado_id=:vendedor and f.empresa_id=:empresa and p.estatus = 1 and p.fecha_pago between :fecha_desde and :fecha_hasta');
    $stmt->bindValue(":empresa",$empresa);
    $stmt->bindValue(":fecha_desde",$fecha_desde);
    $stmt->bindValue(":fecha_hasta",$fecha_hasta);
    $stmt->bindValue(":vendedor",$vendedor);
    $stmt->bindValue(":porcentaje",$porcentaje);
}else if($tipo == 3){
    $stmt = $conn->prepare('SELECT ifnull(sum(vd.Subtotal),0)*:porcentaje2 as totalCalculado 
        FROM clientes cli 
            INNER JOIN ventas_directas vd ON cli.PKCliente=vd.FKCliente 
            INNER JOIN (select * from movimientos_cuentas_bancarias_empresa where estatus = 1 and tipo_CuentaCobrar = 1 group by id_factura order by Fecha) mcbe on vd.PKVentaDirecta=mcbe.id_factura 
            inner join pagos p on mcbe.id_pago=p.idpagos
        WHERE vd.flag_comisionada = 0 AND vd.estatus_cuentaCobrar=3 and vd.empleado_id=:vendedor2 and vd.empresa_id=:empresa2 and p.estatus = 1 and p.fecha_pago between :fecha_desde2 and :fecha_hasta2;');
    $stmt->bindValue(":empresa2",$empresa);
    $stmt->bindValue(":fecha_desde2",$fecha_desde);
    $stmt->bindValue(":fecha_hasta2",$fecha_hasta);
    $stmt->bindValue(":vendedor2",$vendedor);
    $stmt->bindValue(":porcentaje2",$porcentaje);
}

$stmt->execute(); 

$tc=$stmt-> fetch(PDO::FETCH_ASSOC);

echo json_encode($tc);
?>