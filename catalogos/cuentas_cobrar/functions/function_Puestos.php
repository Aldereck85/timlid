<?php
require_once('../../../include/db-conn.php');
session_start();

function GetEvn()
{
    include "../../../include/db-conn.php";
    $appUrl = $_ENV['APP_URL'] ?? 'https://app.timlid.com/';
    return ['server' => $appUrl];
}

//función para dar formato a las cantidades
require_once('function_formatoCantidad.php');

  $envVariables = GetEvn();
  $appUrl = $envVariables['server'];

  $idEmpresa=$_SESSION['IDEmpresa'];
  $cliente = ($_GET['cliente_id']);
  $periodo = ($_GET['periodo']);
  $seleccion=($_GET['seleccion']);
  $_periodo = 0;

if($periodo=="00"){
  if($seleccion==1){
    $stmt = $conn->prepare('SELECT * from(
      SELECT c.PKCliente, 
              c.razon_social as "Nombre Comercial",  
              f.id as id, 
              concat(f.serie, f.folio) as folio, 
              f.fecha_timbrado as "Fecha de facturacion", 
              if(f.fecha_vencimiento is not null,f.fecha_vencimiento, date_add(f.fecha_timbrado, interval c.Dias_credito day))  as "Fecha de vencimiento", 
              f.estatus as Estado, 
              f.total_facturado as Monto,
              f.saldo_insoluto as Monto_insoluto,
              ifnull(m.Deposito,0) as Monto_pagado,
              ifnull(m.parcialidad,0)as parcialidad,
              ifnull(comp.foliosFacturas,"") as foliosFacturas,
              ifnull(notas.importeNC,0) as importeNC,
              ifnull(notas.foliosNotas,"") as foliosNotas,
              2 as tipoDoc
            FROM facturacion as f
            inner join clientes as c on f.cliente_id=c.PKCliente
            left join (select sum(mov.Deposito) as Deposito, mov.id_factura, max(mov.parcialidad) as parcialidad 
                from movimientos_cuentas_bancarias_empresa as mov 
                where mov.tipo_movimiento_id = 5 and mov.estatus = 1 and mov.tipo_CuentaCobrar = 2 and mov.id_pago is not null group by mov.id_factura) as m on m.id_factura = f.id
            left join (select mv.id_factura, group_concat(fp.folio_complemento SEPARATOR "/") as foliosFacturas
                from pagos p 
                inner join movimientos_cuentas_bancarias_empresa mv on p.idpagos = mv.id_pago 
                left join facturas_pagos fp on p.identificador_pago = fp.folio_pago and p.empresa_id = fp.empresa_id
                where p.empresa_id = :empresa1 and p.estatus = 1 and p.tipo_movimiento = 0 and mv.estatus = 1 and fp.estatus in (1,2) group by mv.id_factura) as comp on comp.id_factura = f.id
            left join (select sum(nc.importe) as importeNC, ncf.facturacion_id, group_concat(concat(nc.num_serie_nota," ", nc.folion_nota) SEPARATOR "/") as foliosNotas 
                from notas_cuentas_por_cobrar as nc 
                inner join notas_cuentas_por_cobrar_has_facturacion as ncf on nc.id = ncf.notas_cuentas_por_cobrar_id 
                where nc.estatus = 1 and nc.empresa_id=:empresa2 group by ncf.facturacion_id) as notas on notas.facturacion_id = f.id
            where f.cliente_id=c.PKCliente and c.PKCliente=:cliente and datediff(if(f.fecha_vencimiento is not null,f.fecha_vencimiento, date_add(f.fecha_timbrado, interval c.Dias_credito day)), SYSDATE()) > 90 and f.empresa_id=:idEmpresa and f.estatus  in (1,2) and f.prefactura = 0
                
                union
                
                SELECT c.PKCliente, 
              c.razon_social as "Nombre Comercial",  
              vd.PKVentaDirecta as id, 
              vd.Referencia as folio, 
              vd.created_at as "Fecha de facturacion", 
              if(vd.FechaVencimiento is not null,vd.FechaVencimiento, date_add(vd.created_at, interval c.Dias_credito day))  as "Fecha de vencimiento", 
              vd.estatus_cuentaCobrar as Estado, 
              vd.Importe as Monto,
              vd.saldo_insoluto_venta as Monto_insoluto,
              ifnull(m.Deposito,0) as Monto_pagado,
              ifnull(m.parcialidad,0)as parcialidad,
              "" as foliosFacturas,
              notas.importeNC as importeNC,
              notas.foliosNotas as foliosNotas,
              1 as tipoDoc
            FROM ventas_directas as vd
            inner join clientes as c on vd.FKCliente=c.PKCliente
            left join (select sum(mov.Deposito) as Deposito, mov.id_factura, max(mov.parcialidad) as parcialidad 
                from movimientos_cuentas_bancarias_empresa as mov 
                where mov.tipo_movimiento_id = 5 and mov.estatus = 1 and mov.tipo_CuentaCobrar = 1 and mov.id_pago is not null group by mov.id_factura) as m on m.id_factura = vd.PKVentaDirecta 
            left join (select sum(nc.importe) as importeNC, ncf.venta_id, group_concat(concat(nc.num_serie_nota," ", nc.folion_nota) SEPARATOR "/") as foliosNotas 
                        from notas_cuentas_por_cobrar as nc 
                            inner join notas_cuentas_por_cobrar_has_ventas as ncf on nc.id = ncf.notaCredito_id 
                        where nc.estatus = 1 and nc.empresa_id=:idEmpresa3 group by ncf.venta_id) as notas on notas.venta_id = vd.PKVentaDirecta          
            where vd.FKCliente=c.PKCliente and c.PKCliente=:cliente2 and datediff(if(vd.FechaVencimiento is not null,vd.FechaVencimiento, date_add(vd.created_at, interval c.Dias_credito day)), SYSDATE()) > 90 and vd.empresa_id=:idEmpresa2 and vd.empresa_id !=6 and vd.estatus_factura_id not in (1,2) and vd.FKEstatusVenta != 5 and vd.estatus_cuentaCobrar in (1,2)
                ) as tabla Order by "Fecha de facturacion" desc;'); 

  }elseif($seleccion==2){
    $stmt = $conn->prepare('SELECT * from(
      SELECT c.PKCliente, 
              c.razon_social as "Nombre Comercial",  
              f.id as id, 
              concat(f.serie, f.folio) as folio, 
              f.fecha_timbrado as "Fecha de facturacion", 
              if(f.fecha_vencimiento is not null,f.fecha_vencimiento, date_add(f.fecha_timbrado, interval c.Dias_credito day))  as "Fecha de vencimiento", 
              f.estatus as Estado, 
              f.total_facturado as Monto,
              f.saldo_insoluto as Monto_insoluto,
              ifnull(m.Deposito,0) as Monto_pagado,
              ifnull(m.parcialidad,0)as parcialidad,
              ifnull(comp.foliosFacturas,"") as foliosFacturas,
              ifnull(notas.importeNC,0) as importeNC,
              ifnull(notas.foliosNotas,"") as foliosNotas,
              2 as tipoDoc
            FROM facturacion as f
            inner join clientes as c on f.cliente_id=c.PKCliente
            left join (select sum(mov.Deposito) as Deposito, mov.id_factura, max(mov.parcialidad) as parcialidad 
                from movimientos_cuentas_bancarias_empresa as mov 
                where mov.tipo_movimiento_id = 5 and mov.estatus = 1 and mov.tipo_CuentaCobrar = 2 and mov.id_pago is not null group by mov.id_factura) as m on m.id_factura = f.id
            left join (select mv.id_factura, group_concat(fp.folio_complemento SEPARATOR "/") as foliosFacturas
                from pagos p 
                inner join movimientos_cuentas_bancarias_empresa mv on p.idpagos = mv.id_pago 
                left join facturas_pagos fp on p.identificador_pago = fp.folio_pago and p.empresa_id = fp.empresa_id
                where p.empresa_id = :empresa1 and p.estatus = 1 and p.tipo_movimiento = 0 and mv.estatus = 1 and fp.estatus in (1,2) group by mv.id_factura) as comp on comp.id_factura = f.id
            left join (select sum(nc.importe) as importeNC, ncf.facturacion_id, group_concat(concat(nc.num_serie_nota," ", nc.folion_nota) SEPARATOR "/") as foliosNotas 
                from notas_cuentas_por_cobrar as nc 
                inner join notas_cuentas_por_cobrar_has_facturacion as ncf on nc.id = ncf.notas_cuentas_por_cobrar_id 
                where nc.estatus = 1 and nc.empresa_id=:empresa2 group by ncf.facturacion_id) as notas on notas.facturacion_id = f.id
            where f.cliente_id=c.PKCliente and c.PKCliente=:cliente and datediff(SYSDATE(), if(f.fecha_vencimiento is not null,f.fecha_vencimiento, date_add(f.fecha_timbrado, interval c.Dias_credito day))) > 90 and f.empresa_id=:idEmpresa and f.estatus  in (1,2) and f.prefactura = 0
                
                union
                
                SELECT c.PKCliente, 
              c.razon_social as "Nombre Comercial",  
              vd.PKVentaDirecta as id, 
              vd.Referencia as folio, 
              vd.created_at as "Fecha de facturacion", 
              if(vd.FechaVencimiento is not null,vd.FechaVencimiento, date_add(vd.created_at, interval c.Dias_credito day))  as "Fecha de vencimiento", 
              vd.estatus_cuentaCobrar as Estado, 
              vd.Importe as Monto,
              vd.saldo_insoluto_venta as Monto_insoluto,
              ifnull(m.Deposito,0) as Monto_pagado,
              ifnull(m.parcialidad,0)as parcialidad,
              "" as foliosFacturas,
              notas.importeNC as importeNC,
              notas.foliosNotas as foliosNotas,
              1 as tipoDoc
            FROM ventas_directas as vd
            inner join clientes as c on vd.FKCliente=c.PKCliente
            left join (select sum(mov.Deposito) as Deposito, mov.id_factura, max(mov.parcialidad) as parcialidad 
                from movimientos_cuentas_bancarias_empresa as mov 
                where mov.tipo_movimiento_id = 5 and mov.estatus = 1 and mov.tipo_CuentaCobrar = 1 and mov.id_pago is not null group by mov.id_factura) as m on m.id_factura = vd.PKVentaDirecta 
            left join (select sum(nc.importe) as importeNC, ncf.venta_id, group_concat(concat(nc.num_serie_nota," ", nc.folion_nota) SEPARATOR "/") as foliosNotas 
                    from notas_cuentas_por_cobrar as nc 
                        inner join notas_cuentas_por_cobrar_has_ventas as ncf on nc.id = ncf.notaCredito_id 
                    where nc.estatus = 1 and nc.empresa_id=:idEmpresa3 group by ncf.venta_id) as notas on notas.venta_id = vd.PKVentaDirecta          
            where vd.FKCliente=c.PKCliente and c.PKCliente=:cliente2 and datediff(SYSDATE(), if(vd.FechaVencimiento is not null,vd.FechaVencimiento, date_add(vd.created_at, interval c.Dias_credito day))) > 90 and vd.empresa_id=:idEmpresa2 and vd.empresa_id !=6 and vd.estatus_factura_id not in (1,2) and vd.FKEstatusVenta != 5 and vd.estatus_cuentaCobrar in (1,2)
                ) as tabla Order by "Fecha de facturacion" desc;');    
  }
  $stmt->bindValue(":cliente",$cliente);
  $stmt->bindValue(":cliente2",$cliente);
  $stmt->bindValue(":idEmpresa",$idEmpresa);
  $stmt->bindValue(":idEmpresa2",$idEmpresa);
  $stmt->bindValue(":idEmpresa3",$idEmpresa);
  $stmt->bindValue(":empresa1",$idEmpresa);
  $stmt->bindValue(":empresa2",$idEmpresa);
}else{
    switch($periodo){
      case "30":
        //si se seleccionaron las vencidas, se inicializa en 1, para no tomar las cuentas con 0 dias de vencidas. 
        if($seleccion==2){
          $_periodo=1;
        }else{
          $_periodo=0;
        }
        break;
      case "60":
        $_periodo=31;
        break;
      case "90":
        $_periodo=61;
        break;
    }
    if($seleccion==1){
      $stmt = $conn->prepare('SELECT * from (
        SELECT c.PKCliente, 
            c.razon_social as "Nombre Comercial",  
            f.id as id, 
            concat(f.serie, f.folio) as folio, 
            f.fecha_timbrado as "Fecha de facturacion", 
            if(f.fecha_vencimiento is not null,f.fecha_vencimiento, date_add(f.fecha_timbrado, interval c.Dias_credito day))  as "Fecha de vencimiento", 
            f.estatus as Estado, 
            f.total_facturado as Monto,
            f.saldo_insoluto as Monto_insoluto,
            ifnull(m.Deposito,0) as Monto_pagado,
            ifnull(m.parcialidad,0)as parcialidad,
            ifnull(comp.foliosFacturas,"") as foliosFacturas,
            ifnull(notas.importeNC,0) as importeNC,
            ifnull(notas.foliosNotas,"") as foliosNotas,
            2 as tipoDoc
          FROM facturacion as f
          inner join clientes as c on f.cliente_id=c.PKCliente
          left join (select sum(mov.Deposito) as Deposito, mov.id_factura, max(mov.parcialidad) as parcialidad 
              from movimientos_cuentas_bancarias_empresa as mov 
              where mov.tipo_movimiento_id = 5 and mov.estatus = 1 and mov.tipo_CuentaCobrar = 2 and mov.id_pago is not null group by mov.id_factura) as m on m.id_factura = f.id
          left join (select mv.id_factura, group_concat(fp.folio_complemento SEPARATOR "/") as foliosFacturas
              from pagos p 
              inner join movimientos_cuentas_bancarias_empresa mv on p.idpagos = mv.id_pago 
              left join facturas_pagos fp on p.identificador_pago = fp.folio_pago and p.empresa_id = fp.empresa_id
              where p.empresa_id = :empresa1 and p.estatus = 1 and p.tipo_movimiento = 0 and mv.estatus = 1 and fp.estatus in (1,2) group by mv.id_factura) as comp on comp.id_factura = f.id
          left join (select sum(nc.importe) as importeNC, ncf.facturacion_id, group_concat(concat(nc.num_serie_nota," ", nc.folion_nota) SEPARATOR "/") as foliosNotas 
              from notas_cuentas_por_cobrar as nc 
              inner join notas_cuentas_por_cobrar_has_facturacion as ncf on nc.id = ncf.notas_cuentas_por_cobrar_id 
              where nc.estatus = 1 and nc.empresa_id=:empresa2 group by ncf.facturacion_id) as notas on notas.facturacion_id = f.id
          where f.cliente_id=c.PKCliente and c.PKCliente=:cliente and datediff(if(f.fecha_vencimiento is not null,f.fecha_vencimiento, date_add(f.fecha_timbrado, interval c.Dias_credito day)), SYSDATE()) between :_periodo and :periodo and f.empresa_id=:idEmpresa and f.estatus  in (1,2) and f.prefactura = 0
          
                union
                
                SELECT c.PKCliente, 
              c.razon_social as "Nombre Comercial",  
              vd.PKVentaDirecta as id, 
              vd.Referencia as folio, 
              vd.created_at as "Fecha de facturacion", 
              if(vd.FechaVencimiento is not null,vd.FechaVencimiento, date_add(vd.created_at, interval c.Dias_credito day))  as "Fecha de vencimiento", 
              vd.estatus_cuentaCobrar as Estado, 
              vd.Importe as Monto,
              vd.saldo_insoluto_venta as Monto_insoluto,
              ifnull(m.Deposito,0) as Monto_pagado,
              ifnull(m.parcialidad,0)as parcialidad,
              "" as foliosFacturas,
              notas.importeNC as importeNC,
              notas.foliosNotas as foliosNotas,
              1 as tipoDoc
            FROM ventas_directas as vd
            inner join clientes as c on vd.FKCliente=c.PKCliente
            left join (select sum(mov.Deposito) as Deposito, mov.id_factura, max(mov.parcialidad) as parcialidad 
                from movimientos_cuentas_bancarias_empresa as mov 
                where mov.tipo_movimiento_id = 5 and mov.estatus = 1 and mov.tipo_CuentaCobrar = 1 and mov.id_pago is not null group by mov.id_factura) as m on m.id_factura = vd.PKVentaDirecta
            left join (select sum(nc.importe) as importeNC, ncf.venta_id, group_concat(concat(nc.num_serie_nota," ", nc.folion_nota) SEPARATOR "/") as foliosNotas 
                    from notas_cuentas_por_cobrar as nc 
                        inner join notas_cuentas_por_cobrar_has_ventas as ncf on nc.id = ncf.notaCredito_id 
                    where nc.estatus = 1 and nc.empresa_id=:idEmpresa3 group by ncf.venta_id) as notas on notas.venta_id = vd.PKVentaDirecta          
            where vd.FKCliente=c.PKCliente and c.PKCliente=:cliente2 and datediff(if(vd.fechaVencimiento is not null,vd.fechaVencimiento, date_add(vd.created_at, interval c.Dias_credito day)), SYSDATE()) between :_periodo2 and :periodo2 and vd.empresa_id=:idEmpresa2 and vd.empresa_id !=6 and vd.estatus_factura_id not in (1,2) and vd.FKEstatusVenta != 5 and vd.estatus_cuentaCobrar in (1,2)
                                    
    ) as tabla Order by "Fecha de facturacion" desc');    
    }elseif($seleccion==2){
      $stmt = $conn->prepare('SELECT * from (
        SELECT c.PKCliente, 
            c.razon_social as "Nombre Comercial",  
            f.id as id, 
            concat(f.serie, f.folio) as folio, 
            f.fecha_timbrado as "Fecha de facturacion", 
            if(f.fecha_vencimiento is not null,f.fecha_vencimiento, date_add(f.fecha_timbrado, interval c.Dias_credito day))  as "Fecha de vencimiento", 
            f.estatus as Estado, 
            f.total_facturado as Monto,
            f.saldo_insoluto as Monto_insoluto,
            ifnull(m.Deposito,0) as Monto_pagado,
            ifnull(m.parcialidad,0)as parcialidad,
            ifnull(comp.foliosFacturas,"") as foliosFacturas,
            ifnull(notas.importeNC,0) as importeNC,
            ifnull(notas.foliosNotas,"") as foliosNotas,
            2 as tipoDoc
          FROM facturacion as f
          inner join clientes as c on f.cliente_id=c.PKCliente
          left join (select sum(mov.Deposito) as Deposito, mov.id_factura, max(mov.parcialidad) as parcialidad 
              from movimientos_cuentas_bancarias_empresa as mov 
              where mov.tipo_movimiento_id = 5 and mov.estatus = 1 and mov.tipo_CuentaCobrar = 2 and mov.id_pago is not null group by mov.id_factura) as m on m.id_factura = f.id
          left join (select mv.id_factura, group_concat(fp.folio_complemento SEPARATOR "/") as foliosFacturas
              from pagos p 
              inner join movimientos_cuentas_bancarias_empresa mv on p.idpagos = mv.id_pago 
              left join facturas_pagos fp on p.identificador_pago = fp.folio_pago and p.empresa_id = fp.empresa_id
              where p.empresa_id = :empresa1 and p.estatus = 1 and p.tipo_movimiento = 0 and mv.estatus = 1 and fp.estatus in (1,2) group by mv.id_factura) as comp on comp.id_factura = f.id
          left join (select sum(nc.importe) as importeNC, ncf.facturacion_id, group_concat(concat(nc.num_serie_nota," ", nc.folion_nota) SEPARATOR "/") as foliosNotas 
              from notas_cuentas_por_cobrar as nc 
              inner join notas_cuentas_por_cobrar_has_facturacion as ncf on nc.id = ncf.notas_cuentas_por_cobrar_id 
              where nc.estatus = 1 and nc.empresa_id=:empresa2 group by ncf.facturacion_id) as notas on notas.facturacion_id = f.id
          where f.cliente_id=c.PKCliente and c.PKCliente=:cliente and datediff(SYSDATE(), if(f.fecha_vencimiento is not null,f.fecha_vencimiento, date_add(f.fecha_timbrado, interval c.Dias_credito day))) between :_periodo and :periodo and f.empresa_id=:idEmpresa and f.estatus  in (1,2) and f.prefactura = 0
          
                union
                
                SELECT c.PKCliente, 
              c.razon_social as "Nombre Comercial",  
              vd.PKVentaDirecta as id, 
              vd.Referencia as folio, 
              vd.created_at as "Fecha de facturacion", 
              if(vd.FechaVencimiento is not null,vd.FechaVencimiento, date_add(vd.created_at, interval c.Dias_credito day))  as "Fecha de vencimiento", 
              vd.estatus_cuentaCobrar as Estado, 
              vd.Importe as Monto,
              vd.saldo_insoluto_venta as Monto_insoluto,
              ifnull(m.Deposito,0) as Monto_pagado,
              ifnull(m.parcialidad,0)as parcialidad,
              "" as foliosFacturas,
              notas.importeNC as importeNC,
              notas.foliosNotas as foliosNotas,
              1 as tipoDoc
            FROM ventas_directas as vd
            inner join clientes as c on vd.FKCliente=c.PKCliente
            left join (select sum(mov.Deposito) as Deposito, mov.id_factura, max(mov.parcialidad) as parcialidad 
                from movimientos_cuentas_bancarias_empresa as mov 
                where mov.tipo_movimiento_id = 5 and mov.estatus = 1 and mov.tipo_CuentaCobrar = 1 and mov.id_pago is not null group by mov.id_factura) as m on m.id_factura = vd.PKVentaDirecta
            left join (select sum(nc.importe) as importeNC, ncf.venta_id, group_concat(concat(nc.num_serie_nota," ", nc.folion_nota) SEPARATOR "/") as foliosNotas 
                    from notas_cuentas_por_cobrar as nc 
                        inner join notas_cuentas_por_cobrar_has_ventas as ncf on nc.id = ncf.notaCredito_id 
                    where nc.estatus = 1 and nc.empresa_id=:idEmpresa3 group by ncf.venta_id) as notas on notas.venta_id = vd.PKVentaDirecta          
            where vd.FKCliente=c.PKCliente and c.PKCliente=:cliente2 and datediff(SYSDATE(), if(vd.FechaVencimiento is not null,vd.FechaVencimiento, date_add(vd.created_at, interval c.Dias_credito day))) between :_periodo2 and :periodo2 and vd.empresa_id=:idEmpresa2 and vd.empresa_id !=6 and vd.estatus_factura_id not in (1,2) and vd.FKEstatusVenta != 5 and vd.estatus_cuentaCobrar in (1,2)
                                    
    ) as tabla Order by "Fecha de facturacion" desc;');    
    }
    $stmt->bindValue(":cliente",$cliente);
    $stmt->bindValue(":cliente2",$cliente);
    $stmt->bindValue(":_periodo",$_periodo);
    $stmt->bindValue(":_periodo2",$_periodo);
    $stmt->bindValue(":periodo",$periodo);
    $stmt->bindValue(":periodo2",$periodo);
    $stmt->bindValue(":idEmpresa",$idEmpresa);    
    $stmt->bindValue(":idEmpresa2",$idEmpresa);    
    $stmt->bindValue(":idEmpresa3",$idEmpresa);    
    $stmt->bindValue(":empresa1",$idEmpresa);
    $stmt->bindValue(":empresa2",$idEmpresa);
}

  $stmt->execute();
  $table="";
  while (($row = $stmt->fetch()) !== false) {       

    if($row['Estado']==1){
      $row['Estado']= '<span class=\"left-dot blue-light-dot\">Pendiente de Pago</span>';
    }elseif($row['Estado']==2){
        $row['Estado']= '<span class=\"left-dot orange-dot\">Parcialmente Pagada</span>';
    }
  
      /* Guardamos en un JSON los datos de la consulta  */
      $row['Monto']=formatoCantidad($row['Monto']);
      $row['Monto_insoluto']=formatoCantidad($row['Monto_insoluto']);
      $row['Monto_pagado']=formatoCantidad($row['Monto_pagado']);
      $row['importeNC']=formatoCantidad($row['importeNC']);

      //condicion: que para definir la posición del tooltip en "ver"
      if($stmt->rowCount()<2){
        $posicion="left";
      }else{
        $posicion="auto";
      }
      
      $row['Fecha de facturacion']=date("d-m-Y", strtotime($row['Fecha de facturacion']));
      $row['Fecha de vencimiento']=date("d-m-Y", strtotime($row['Fecha de vencimiento']));

      $fechaVencimiento=date("Y-m-d", strtotime($row['Fecha de vencimiento']));
      $fechaActual=date("Y-m-d");

      if( $fechaActual > $fechaVencimiento){
        $row['Fecha de vencimiento']='<span class=\"badge badge-danger\" style=\"font-size:1rem;font-family: Montserrat, sans-serif\">'.$row['Fecha de vencimiento'].'</span>';
      }else{
        $row['Fecha de vencimiento']='<span class=\"badge badge-success\" style=\"font-size:1rem;font-family: Montserrat, sans-serif\">'.$row['Fecha de vencimiento'].'</span>';
      }

      //si es factua manda la variable factura, si no manda la variable venta
      if($row['tipoDoc'] == 1){
        $var_get='idVenta';
      }else{
        $var_get='idFactura';
      }

      //Escapar las dobles comillas
      $row['Nombre Comercial'] = str_replace('"', '', $row['Nombre Comercial']);

      //link para detalle del cliente
      $row['Nombre Comercial'] = '<a style=\"cursor:pointer\" href=\"'.$appUrl.'catalogos/clientes/catalogos/clientes/detalles_cliente.php?c='.$row['PKCliente'].'\">'.$row['Nombre Comercial'].'</a>';

      $table.='{"Cliente":"'.$row['Nombre Comercial'].'",
        "Folio de Factura":"'.$row['folio'].'",
        "Fecha de Facturacion":"'.$row['Fecha de facturacion'].'",
        "Fecha de Vencimiento":"'.$row['Fecha de vencimiento'].'",
        "Estado":"'.$row['Estado'].
        '","Monto":"$'.$row['Monto'].
        '","Monto pagado":"$'.$row['Monto_pagado'].
        '","Parcialidades":"'.$row['parcialidad'].
        '","Monto notas credito":"$'.$row['importeNC'].
        '","Monto insoluto":"$'.$row['Monto_insoluto'].
        '","Complementos":"'.$row['foliosFacturas'].
        '","Notas credito":"'.$row['foliosNotas'].'",
        "Acciones":"'.'<a id=\"editarcp\" href=\"detalle_factura.php?'.$var_get.'='.$row['id'].'\"><i class=\"fas fa-clipboard-list\" data-toggle=\"tooltip\" data-placement=\"'.$posicion.'\" title=\"Ver detalle\"></i></a>"},'; 
     
    }
    $table = substr($table,0,strlen($table)-1);
    echo '{"data":['.$table.']}';
  
 ?>
