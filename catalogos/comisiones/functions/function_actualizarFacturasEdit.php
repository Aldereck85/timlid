<?php
session_start(); 
require_once('../../../include/db-conn.php');
/* Recupera las facturas cuando de clic al botón "actualizar facturas" en el detalle del cálculo */

$empresa = $_SESSION["IDEmpresa"];
$idU = $_SESSION["PKUsuario"];
$fecha_desde = $_REQUEST["fecha_desde"];
$fecha_hasta = $_REQUEST["fecha_hasta"];
$porcentaje = $_REQUEST["porcentaje"];
$comision = $_REQUEST["comision"];
$vendedor = $_REQUEST["vendedor"];
$array = array();
  
$stmt = $conn->prepare('SELECT f.id as id, 
                                concat(f.serie, " ", f.folio) as SerieFolio,
                                MAX(p.fecha_pago) as fecha_factura, 
                                cli.razon_social as razon_social,
                                (select ifnull(sum(df.subtotal),0) FROM detalle_facturacion as df WHERE df.factura_id=f.id) as monto_fac_si, 
                                (select ifnull(sum(df.subtotal),0)*:porcentaje FROM detalle_facturacion as df WHERE df.factura_id=f.id) as monto_comisionado,
                                1 as tipoDoc
                        FROM clientes cli 
                                INNER JOIN facturacion f ON cli.PKCliente=f.cliente_id 
                                INNER JOIN movimientos_cuentas_bancarias_empresa mcbe on f.id=mcbe.id_factura and mcbe.estatus = 1 and mcbe.tipo_CuentaCobrar = 2
                                inner join pagos p on mcbe.id_pago=p.idpagos
                        WHERE f.flag_comisionada = 0 AND f.estatus=3 and f.empleado_id=:vendedor and p.estatus = 1 and f.empresa_id=:empresa and p.fecha_pago between :fecha_desde and :fecha_hasta
                        GROUP BY f.id
                        union 
                        SELECT f.id as id, 
                                concat(f.serie, " ", f.folio) as SerieFolio,
                                MAX(p.fecha_pago) as fecha_factura, 
                                cli.razon_social as razon_social,
                                (select ifnull(sum(df.subtotal),0) FROM detalle_facturacion as df WHERE df.factura_id=f.id) as monto_fac_si, 
                                (select ifnull(sum(df.subtotal),0)*:porcentaje2 FROM detalle_facturacion as df WHERE df.factura_id=f.id) as monto_comisionado,
                                1 as tipoDoc
                        FROM clientes cli 
                                INNER JOIN facturacion f ON cli.PKCliente=f.cliente_id 
                                inner join detalle_comision_factura dcf on dcf.id_factura = f.id
                                INNER JOIN movimientos_cuentas_bancarias_empresa mcbe on f.id=mcbe.id_factura and mcbe.estatus = 1 and mcbe.tipo_CuentaCobrar = 2
                                inner join pagos p on mcbe.id_pago=p.idpagos
                        WHERE dcf.id_comision = :comision AND f.estatus=3 and f.empleado_id=:vendedor2 and p.estatus = 1 and f.empresa_id=:empresa2
                        GROUP BY f.id
                        
                        union 

                        SELECT vd.PKVentaDirecta as id, 
                                vd.Referencia as SerieFolio, 
                                cli.razon_social as razon_social, 
                                MAX(p.fecha_pago) as fecha_factura,
                                vd.Subtotal as monto_fac_si, 
                                vd.Subtotal*:porcentaje3 as monto_comisionado,
                                2 as tipoDoc
                        FROM clientes cli 
                                INNER JOIN ventas_directas vd ON cli.PKCliente=vd.FKCliente 
                                INNER JOIN movimientos_cuentas_bancarias_empresa mcbe on vd.PKVentaDirecta=mcbe.id_factura and mcbe.tipo_CuentaCobrar = 1 and mcbe.estatus = 1
                                inner join pagos p on mcbe.id_pago=p.idpagos
                        WHERE vd.flag_comisionada = 0 AND vd.estatus_cuentaCobrar=3 and vd.empleado_id=:vendedor3 and vd.empresa_id=:empresa3 and p.estatus = 1 and p.fecha_pago between :fecha_desde2 and :fecha_hasta2
                        GROUP BY vd.PKVentaDirecta
                        
                        union
                        
                        SELECT vd.PKVentaDirecta as id, 
                                vd.Referencia as SerieFolio, 
                                cli.razon_social as razon_social, 
                                MAX(p.fecha_pago) as fecha_factura,
                                vd.Subtotal as monto_fac_si, 
                                vd.Subtotal*:porcentaje4 as monto_comisionado,
                                2 as tipoDoc
                        FROM clientes cli 
                                INNER JOIN ventas_directas vd ON cli.PKCliente=vd.FKCliente 
                                inner join detalle_comision_venta dcv on dcv.id_venta = vd.PKVentaDirecta
                                INNER JOIN movimientos_cuentas_bancarias_empresa mcbe on vd.PKVentaDirecta=mcbe.id_factura and mcbe.estatus = 1 and mcbe.tipo_CuentaCobrar = 1 
                                inner join pagos p on mcbe.id_pago=p.idpagos
                        WHERE dcv.id_comision = :comision2 AND vd.estatus_cuentaCobrar=3 and vd.empleado_id=:vendedor4 and p.estatus = 1 and vd.empresa_id=:empresa4
                        GROUP BY vd.PKVentaDirecta;');
  
$stmt->bindValue(":empresa",$empresa);
$stmt->bindValue(":empresa2",$empresa);
$stmt->bindValue(":empresa3",$empresa);
$stmt->bindValue(":empresa4",$empresa);
$stmt->bindValue(":fecha_desde",$fecha_desde);
$stmt->bindValue(":fecha_desde2",$fecha_desde);
$stmt->bindValue(":fecha_hasta",$fecha_hasta);
$stmt->bindValue(":fecha_hasta2",$fecha_hasta);
$stmt->bindValue(":vendedor",$vendedor);
$stmt->bindValue(":vendedor2",$vendedor);
$stmt->bindValue(":vendedor3",$vendedor);
$stmt->bindValue(":vendedor4",$vendedor);
$stmt->bindValue(":comision",$comision);
$stmt->bindValue(":comision2",$comision);
$stmt->bindValue(":porcentaje",$porcentaje);
$stmt->bindValue(":porcentaje2",$porcentaje);
$stmt->bindValue(":porcentaje3",$porcentaje);
$stmt->bindValue(":porcentaje4",$porcentaje);
$stmt->execute();
$verificarFilas=$stmt->rowCount();

if($verificarFilas>0){
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $data['id']=$row['id'];
                $data['Folio']=$row['SerieFolio'];
                $data['fecha_factura']=$row['fecha_factura'];
                $data['razon_social']=$row['razon_social'];
                $data['monto_facturado']='$'.number_format($row['monto_fac_si'], 2, '.', ' ');
                $data['monto_comisionado']='$'.number_format($row['monto_comisionado'], 2, '.', ' ');
                $data['Seleccionar']='<input class=contarFila type=checkbox name=invoiceSelected onclick=sumar(this) data-tipo='.$row['tipoDoc'].' value='.$row['id'].'-'.$row['monto_comisionado'].'-'.$row['monto_fac_si'].'>';
                array_push($array,$data);
        }
}

echo json_encode ($array);

?>