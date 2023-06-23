<?php
require_once('../../../include/db-conn.php');
session_start();
//función para dar formato a las cantidades
require_once('function_formatoCantidad.php');

if(isset($_REQUEST['idPago'])){
    $empresa = $_SESSION["IDEmpresa"];
    $idPago = $_REQUEST['idPago'];
    $query='SELECT if(m.tipo_CuentaCobrar = 1, vd.Referencia, concat(f.serie, f.folio)) as "Folio", 
                          if(m.tipo_CuentaCobrar = 1, cl.razon_social, c.razon_social) AS "Nombre Comercial", 
                          if(m.tipo_CuentaCobrar = 1, vd.created_at, f.fecha_timbrado) as "Fecha de facturacion", 
                          date_add(if(m.tipo_CuentaCobrar = 1, vd.created_at, f.fecha_timbrado), interval if(m.tipo_CuentaCobrar = 1,cl.Dias_credito, c.Dias_credito) day)  as "Fecha de vencimiento",  
                          if(m.tipo_CuentaCobrar = 1, vd.importe, f.total_facturado) as "Monto factura", 
                          m.saldo_anterior, 
                          m.saldo_insoluto, 
                          m.parcialidad, 
                          if(m.tipo_CuentaCobrar = 1, vd.PKVentaDirecta, f.id) as "id",
                          if(m.tipo_CuentaCobrar = 1, 0, f.metodo_pago) as metodo_pago, 
                          m.Deposito 
                      FROM pagos as p
                          inner join movimientos_cuentas_bancarias_empresa as m on m.id_pago=p.idpagos 
                          left join facturacion as f on m.id_factura=f.id and f.estatus not in (4,5) and f.prefactura = 0
                          left join ventas_directas as vd on m.id_factura=vd.PKVentaDirecta and vd.empresa_id !=6
                          left join clientes as c on f.cliente_id=c.PKCliente 
                          left join clientes as cl on vd.FKCliente=cl.PKCliente 
                      where p.empresa_id=:empresa and p.identificador_pago=:idPago and p.tipo_movimiento=0 and p.estatus=1 group by m.id_factura;';
    $stmt = $conn->prepare($query);
    $stmt->bindValue(":idPago",$idPago);
    $stmt->bindValue(":empresa",$empresa);
    $stmt->execute();
    $table="";

    while (($row = $stmt->fetch()) !== false) { 
  
        if($row['parcialidad']==""){
          $row['parcialidad']="0";
        }

        //cambiamos formato a las fechas
        $row['Fecha de facturacion']=date("Y-m-d", strtotime($row['Fecha de facturacion']));
        $row['Fecha de vencimiento']=date("Y-m-d", strtotime($row['Fecha de vencimiento']));

          /* Guardamos en un JSON los datos de la consulta  */
          $row['saldo_anterior']=formatoCantidad($row['saldo_anterior']);
          $row['saldo_insoluto']=formatoCantidad($row['saldo_insoluto']);
          $row['Monto factura']=formatoCantidad($row['Monto factura']);
          $row['Deposito']=formatoCantidad($row['Deposito']);
          $row['Nombre Comercial'] = str_replace('"', '\"', $row['Nombre Comercial']);

          $table.='{"Folio":"'.$row['Folio'].'",
            "Cliente":"'.$row['Nombre Comercial'].'",
            "Monto factura":"$'.$row['Monto factura'].'",
            "F Expedicion":"'.date("d-m-Y", strtotime($row['Fecha de facturacion'])).'",
            "F Vencimiento":"'.date("d-m-Y", strtotime($row['Fecha de vencimiento'])).'",
            "Saldo anterior":"$'.$row['saldo_anterior'].'",
            "Importe pago":"$'.$row['Deposito'].'",
            "Saldo insoluto":"$'.$row['saldo_insoluto'].'",
            "No Parcialidad":"'.$row['parcialidad'].'"},'; 
        }

       $table = substr($table,0,strlen($table)-1);
        echo '{"data":['.$table.']}';      
}

?>