<?php
session_start(); 
require_once('../../../include/db-conn.php');
/*Recupera las facturas de un cálculo*/

if(isset($_REQUEST['idComision']) && !empty($_REQUEST['idComision'])) {
  $empresa = $_SESSION["IDEmpresa"];
  $idU = $_SESSION["PKUsuario"];
  $idComision = $_REQUEST['idComision'];
  
  $stmt = $conn->prepare('SELECT 
                            f.id as id, 
                            concat(f.serie, " ", f.folio) as SerieFolio, 
                            MAX(p.fecha_pago) as fecha_factura, 
                            cli.razon_social as razon_social,
                            (select ifnull(sum(df.subtotal),0) FROM detalle_facturacion as df WHERE df.factura_id=f.id) as monto_fac_si, 
                            dcf.monto_comisionado as monto_comisionado,
                            1 as tipoDoc
                          FROM clientes cli 
                            INNER JOIN facturacion f ON cli.PKCliente=f.cliente_id 
                            INNER JOIN detalle_comision_factura dcf ON f.id=dcf.id_factura 
                            INNER JOIN comisiones c ON c.id=dcf.id_comision 
                            INNER JOIN movimientos_cuentas_bancarias_empresa mcbe on f.id=mcbe.id_factura and mcbe.estatus = 1 and mcbe.tipo_CuentaCobrar = 2
                            inner join pagos p on mcbe.id_pago=p.idpagos and p.estatus = 1
                          WHERE dcf.id_comision=:idComision and f.empresa_id=:empresa GROUP BY f.id
                          
                          union 

                          SELECT 
                            vd.PKVentaDirecta as id, 
                            vd.Referencia as SerieFolio, 
                            MAX(p.fecha_pago) as fecha_factura, 
                            cli.razon_social as razon_social,
                            vd.Subtotal as monto_fac_si, 
                            dcv.monto_comisionado as monto_comisionado,
                            2 as tipoDoc
                          FROM clientes cli 
                            INNER JOIN ventas_directas vd ON cli.PKCliente=vd.FKCliente 
                            INNER JOIN detalle_comision_venta dcv ON vd.PKVentaDirecta=dcv.id_venta 
                            INNER JOIN comisiones c ON c.id=dcv.id_comision 
                            INNER JOIN movimientos_cuentas_bancarias_empresa mcbe on vd.PKVentaDirecta=mcbe.id_factura and mcbe.estatus = 1 and mcbe.tipo_CuentaCobrar = 1
                            inner join pagos p on mcbe.id_pago=p.idpagos and p.estatus = 1
                          WHERE dcv.id_comision=:idComision2 and vd.empresa_id=:empresa2 GROUP BY vd.PKVentaDirecta
                          ');
  
  $stmt->bindValue(":empresa",$empresa);
  $stmt->bindValue(":empresa2",$empresa);
  $stmt->bindValue(":idComision",$idComision);
  $stmt->bindValue(":idComision2",$idComision);
  $stmt->execute();
  
  $table="";
  
  while (($row = $stmt->fetch()) !== false) {

    $html = '<input class=\"contarFila\" type=\"checkbox\" name=\"invoiceSelected\" id=\" \" onclick=\"sumar(this)\" data-tipo=\"'.$row['tipoDoc'].'\" value=\"'.$row['id'].'-'.$row['monto_comisionado'].'-'.$row['monto_fac_si'].'\" checked>';

    $row['razon_social'] = str_replace('"', '\"', $row['razon_social']);

    $table.='{"Folio":"'.$row['SerieFolio'].'",
            "fecha_factura":"'.date("Y-m-d", strtotime($row['fecha_factura'])).'",
            "razon_social":"'.$row['razon_social'].'",
            "monto_facturado":"$'.number_format($row['monto_fac_si'], 2, '.', ' ').'",
            "monto_comisionado":"$'.number_format($row['monto_comisionado'], 2, '.', ' ').'",
            "Seleccionar":"'.$html.'"},'; 
  }
  
  $table = substr($table,0,strlen($table)-1);
  echo '{"data":['.$table.']}'; 
}
?>