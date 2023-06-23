<?php
require_once('../../../include/db-conn.php');
session_start();

//función para dar formato a las cantidades
require_once('function_formatoCantidad.php');


if(isset($_REQUEST['ids'])){
    $cliente = $_REQUEST['cliente'];
    $empresa = $_SESSION["IDEmpresa"];
    $ids = $_REQUEST['ids'];
    $ids2 = $_REQUEST['ids2'];
    $query='SELECT * from (
      SELECT concat(f.serie, f.folio) as "Folio", 
                c.razon_social AS "Nombre Comercial", 
                f.fecha_timbrado as "Fecha de facturacion",
                if(f.fecha_vencimiento is null, date_add(f.fecha_timbrado, interval c.Dias_credito day),f.fecha_vencimiento)  as "Fecha de vencimiento",  
                f.total_facturado as "Monto factura", 
                m.saldo_anterior, 
                f.saldo_insoluto, 
                m.parcialidad, 
                f.id as "id",
                f.metodo_pago,
                2 as tipoDoc 
      FROM facturacion as f
        inner join clientes as c on f.cliente_id=c.PKCliente 
        left join (select m.id_factura, m.saldo_anterior, m.parcialidad from movimientos_cuentas_bancarias_empresa as m 
              where m.parcialidad is not null and m.estatus=1 and m.tipo_CuentaCobrar = 2 and m.parcialidad = (select max(mm.parcialidad) from movimientos_cuentas_bancarias_empresa as mm  where m.id_factura=mm.id_factura and mm.estatus=1 and m.tipo_CuentaCobrar = 2) group by m.id_factura) as m on m.id_factura=f.id 
      where f.empresa_id=:empresa and c.PKCliente = :cliente and f.estatus not in (4,3,5) and f.id in('.$ids.') and f.prefactura = 0 group by f.id
        
        union
        
        SELECT vd.referencia as "Folio",  
                c.razon_social AS "Nombre Comercial", 
                vd.created_at as "Fecha de facturacion",
                if(vd.FechaVencimiento is null, date_add(vd.created_at, interval c.Dias_credito day),vd.FechaVencimiento)  as "Fecha de vencimiento",  
                vd.importe as "Monto factura", 
                m.saldo_anterior, 
                vd.saldo_insoluto_venta, 
                m.parcialidad, 
                vd.PKVentaDirecta as "id",
                0 as metodo_pago,
                1 as tipoDoc  
      FROM ventas_directas as vd
        inner join clientes as c on vd.FKCliente=c.PKCliente 
        left join (select m.id_factura, m.saldo_anterior, m.parcialidad from movimientos_cuentas_bancarias_empresa as m 
              where m.parcialidad is not null and m.estatus=1 and m.tipo_CuentaCobrar = 1 and m.parcialidad = (select max(mm.parcialidad) from movimientos_cuentas_bancarias_empresa as mm  where m.id_factura=mm.id_factura and mm.estatus=1 and m.tipo_CuentaCobrar = 1) group by m.id_factura) as m on m.id_factura=vd.PKVentaDirecta 
      where vd.empresa_id=:empresa2 and vd.empresa_id !=6 and c.PKCliente = :cliente2 and vd.estatus_cuentaCobrar in (1,2) and vd.FKEstatusVenta != 5 and vd.estatus_factura_id not in (1,2) and vd.PKVentaDirecta in('.$ids2.') group by vd.PKVentaDirecta
      
    ) as tabla ;';

    $stmt = $conn->prepare($query);
    $stmt->bindValue(":empresa",$empresa);
    $stmt->bindValue(":empresa2",$empresa);
    $stmt->bindValue(":cliente",$cliente);
    $stmt->bindValue(":cliente2",$cliente);
    $stmt->execute();
    $table="";

    while (($row = $stmt->fetch()) !== false) { 

        if($row['saldo_anterior']==""){
          $row['saldo_anterior']=$row['saldo_insoluto'];
        }

        if($row['parcialidad']==""){
          $row['parcialidad']="0";
        }

        //cambiamos formato a las fechas
        $row['Fecha de facturacion']=date("d-m-Y", strtotime($row['Fecha de facturacion']));
        $row['Fecha de vencimiento']=date("d-m-Y", strtotime($row['Fecha de vencimiento']));

        $fechaVencimiento=date("Y-m-d", strtotime($row['Fecha de vencimiento']));
        $fechaActual=date("Y-m-d");
  
        if( $fechaActual > $fechaVencimiento){
          $row['Fecha de vencimiento']='<span class=\"badge badge-danger\" style=\"font-size:1rem;font-family: Montserrat, sans-serif\">'.$row['Fecha de vencimiento'].'</span>';
        }else{
          $row['Fecha de vencimiento']='<span class=\"badge badge-success\" style=\"font-size:1rem;font-family: Montserrat, sans-serif\">'.$row['Fecha de vencimiento'].'</span>';
        }

        //elimina los 0 que no se utilizan
        $row['saldo_anterior'] = formatoCantidad($row['saldo_anterior']);
        $row['saldo_insoluto'] = formatoCantidad($row['saldo_insoluto']);
        $row['Monto factura'] = formatoCantidad($row['Monto factura']);

        $MF= $row['saldo_insoluto'];

        //segun el metodo de pago, activa o desactiva el input para insertar el importe
        if($row['metodo_pago']==1){
          $row['metodo_pago']='<input disabled class=\"form-control numericDecimal-only\" type=\"text\" name=\"inputs_facturas\" value=\"'.$MF.'\" onchange=\"sumarInputs(this)\" id=\"'.$row['id'].'-'.$MF.'\" data-id=\"'.$row['tipoDoc'].'\" min=\"1\" maxlength=\"18\"> <div class=\"invalid-feedback\" id=\"invalid-input\">gg</div>';
        }else{
          $row['metodo_pago']='<input class=\"form-control numericDecimal-only\" type=\"text\" name=\"inputs_facturas\" placeholder=\"0\" value=\"'.$MF.'\" onchange=\"sumarInputs(this)\" id=\"'.$row['id'].'\" data-id=\"'.$row['tipoDoc'].'\" min=\"1\"  maxlength=\"18\"> <div class=\"invalid-feedback\" id=\"invalid-input\">gg</div>';
        }

        //condicion: que para definir la posición del tooltip en "ver"
        if($stmt->rowCount()<2){
          $posicion="left";
        }else{
          $posicion="auto";
        }
        $row['Nombre Comercial'] = str_replace('"', '', $row['Nombre Comercial']);

        $table.='{"Folio":"'.$row['Folio'].'",
          "Cliente":"'.$row['Nombre Comercial'].'",
          "Monto total":"$'.$row['Monto factura'].'",
          "F Facturacion":"'.$row['Fecha de facturacion'].'",
          "F Vencimiento":"'.$row['Fecha de vencimiento'].'",
          "Saldo anterior":"$'.$row['saldo_anterior'].'",
          "Importe pago":"'.$row['metodo_pago'].'",
          "Saldo insoluto":"$'.$row['saldo_insoluto'].'",
          "Acciones":"<input type=\"image\" src=\"../../img/timdesk/delete.svg\" style=\"cursor:pointer\" width=\"20px\" heigth=\"20px\" data-toggle=\"tooltip\" data-placement=\"'.$posicion.'\" title=\"Eliminar\" onclick=\"eliminaFactura('.$row['id'].')\"/>",
          "No Parcialidad":"'.$row['parcialidad'].'"},'; 
    }

       $table = substr($table,0,strlen($table)-1);
        echo '{"data":['.$table.']}';      
}

?>