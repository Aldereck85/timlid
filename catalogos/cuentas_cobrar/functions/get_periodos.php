<?php
require_once('../../../include/db-conn.php');
session_start();
$empresa = $_SESSION["IDEmpresa"];
//variable que definira que consulta se hará
$seleccion = ($_GET['combo']);

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

switch($seleccion){
    case 1: //corrientes
        $stmt = $conn->prepare("SELECT * from(
            SELECT razon_social, PKCliente,
                SUM(CASE WHEN (DATEDIFF( if(f.fecha_vencimiento is not null,f.fecha_vencimiento, date_add(fecha_timbrado, interval c.Dias_credito day)), SYSDATE())) between 0 and 30 THEN saldo_insoluto END) AS '30',
                SUM(CASE WHEN (DATEDIFF(if(f.fecha_vencimiento is not null,f.fecha_vencimiento, date_add(fecha_timbrado, interval c.Dias_credito day)), SYSDATE())) between 31 and 60 THEN saldo_insoluto END) AS '60',
                SUM(CASE WHEN (DATEDIFF(if(f.fecha_vencimiento is not null,f.fecha_vencimiento, date_add(fecha_timbrado, interval c.Dias_credito day)), SYSDATE())) between 61 and 90 THEN saldo_insoluto END) AS '90',
                SUM(CASE WHEN (DATEDIFF(if(f.fecha_vencimiento is not null,f.fecha_vencimiento, date_add(fecha_timbrado, interval c.Dias_credito day)), SYSDATE())) > 90 THEN saldo_insoluto END) AS '99'
            from facturacion as f 
            inner join clientes as c on f.cliente_id = c.PKCliente 
            where (DATEDIFF(if(f.fecha_vencimiento is not null,f.fecha_vencimiento, date_add(fecha_timbrado, interval c.Dias_credito day)), SYSDATE())>=0) and f.empresa_id=:empresa and f.estatus in (1,2) and f.prefactura = 0 Group By PKCliente
            
            union
            
            SELECT razon_social, PKCliente,
                SUM(CASE WHEN (DATEDIFF( if(vd.FechaVencimiento is not null,vd.FechaVencimiento, date_add(vd.created_at, interval c.Dias_credito day)), SYSDATE())) between 0 and 30 THEN vd.saldo_insoluto_venta END) AS '30',
                SUM(CASE WHEN (DATEDIFF(if(vd.FechaVencimiento is not null,vd.FechaVencimiento, date_add(vd.created_at, interval c.Dias_credito day)), SYSDATE())) between 31 and 60 THEN vd.saldo_insoluto_venta END) AS '60',
                SUM(CASE WHEN (DATEDIFF(if(vd.FechaVencimiento is not null,vd.FechaVencimiento, date_add(vd.created_at, interval c.Dias_credito day)), SYSDATE())) between 61 and 90 THEN vd.saldo_insoluto_venta END) AS '90',
                SUM(CASE WHEN (DATEDIFF(if(vd.FechaVencimiento is not null,vd.FechaVencimiento, date_add(vd.created_at, interval c.Dias_credito day)), SYSDATE())) > 90 THEN vd.saldo_insoluto_venta END) AS '99'
            from ventas_directas as vd 
            inner join clientes as c on vd.FKCliente = c.PKCliente 
            where (DATEDIFF(if(vd.FechaVencimiento is not null, vd.FechaVencimiento, date_add(vd.created_at, interval c.Dias_credito day)), SYSDATE())>=0) and vd.empresa_id=:empresa2 and vd.empresa_id !=6 and vd.estatus_factura_id not in (1,2) and vd.FKEstatusVenta != 5 and vd.estatus_cuentaCobrar in (1,2) Group By PKCliente
                ) as tabla Group By PKCliente;");
    break;           
    case 2: //vencidas
        $stmt = $conn->prepare("SELECT * from(
            SELECT razon_social, PKCliente,
                SUM(CASE WHEN (DATEDIFF(SYSDATE(), if(f.fecha_vencimiento is not null,f.fecha_vencimiento, date_add(fecha_timbrado, interval c.Dias_credito day)))) between 1 and 30 THEN saldo_insoluto END) AS '30',
                SUM(CASE WHEN (DATEDIFF(SYSDATE(), if(f.fecha_vencimiento is not null,f.fecha_vencimiento, date_add(fecha_timbrado, interval c.Dias_credito day)))) between 31 and 60 THEN saldo_insoluto END) AS '60',
                SUM(CASE WHEN (DATEDIFF(SYSDATE(), if(f.fecha_vencimiento is not null,f.fecha_vencimiento, date_add(fecha_timbrado, interval c.Dias_credito day)))) between 61 and 90 THEN saldo_insoluto END) AS '90',
                SUM(CASE WHEN (DATEDIFF(SYSDATE(), if(f.fecha_vencimiento is not null,f.fecha_vencimiento, date_add(fecha_timbrado, interval c.Dias_credito day)))) > 90 THEN saldo_insoluto END) AS '99'
            from facturacion as f 
            inner join clientes as c on f.cliente_id = c.PKCliente 
            where (DATEDIFF(SYSDATE(), if(f.fecha_vencimiento is not null,f.fecha_vencimiento, date_add(fecha_timbrado, interval c.Dias_credito day)))>0) and f.empresa_id=:empresa and f.estatus  in (1,2) and f.prefactura = 0 Group By PKCliente
            
            union
            
            SELECT razon_social, PKCliente,
                 SUM(CASE WHEN (DATEDIFF(SYSDATE(), if(vd.FechaVencimiento is not null,vd.FechaVencimiento, date_add(vd.created_at, interval c.Dias_credito day)))) between 1 and 30 THEN vd.saldo_insoluto_venta END) AS '30',
                SUM(CASE WHEN (DATEDIFF(SYSDATE(), if(vd.FechaVencimiento is not null,vd.FechaVencimiento, date_add(vd.created_at, interval c.Dias_credito day)))) between 31 and 60 THEN vd.saldo_insoluto_venta END) AS '60',
                SUM(CASE WHEN (DATEDIFF(SYSDATE(), if(vd.FechaVencimiento is not null,vd.FechaVencimiento, date_add(vd.created_at, interval c.Dias_credito day)))) between 61 and 90 THEN vd.saldo_insoluto_venta END) AS '90',
                SUM(CASE WHEN (DATEDIFF(SYSDATE(), if(vd.FechaVencimiento is not null,vd.FechaVencimiento, date_add(vd.created_at, interval c.Dias_credito day)))) > 90 THEN vd.saldo_insoluto_venta END) AS '99'
            from ventas_directas as vd 
            inner join clientes as c on vd.FKCliente = c.PKCliente 
            where (DATEDIFF(SYSDATE(), if(vd.FechaVencimiento is not null,vd.FechaVencimiento, date_add(vd.created_at, interval c.Dias_credito day)))>0) and vd.empresa_id=:empresa2 and vd.empresa_id !=6 and vd.estatus_factura_id not in (1,2) and vd.FKEstatusVenta != 5 and vd.estatus_cuentaCobrar in (1,2) Group By PKCliente
                ) as tabla Group By PKCliente;");
    break;
    case 3: //historico
        $stmt = $conn->prepare("SELECT * from (
                                    SELECT  c.PKCliente, 
                                            c.razon_social as `cliente`, 
                                            f.id,
                                            concat(f.serie, f.folio) as folio,
                                            f.fecha_timbrado as `fecha de facturacion`, 
                                            if(f.fecha_vencimiento is null, date_add(f.fecha_timbrado, interval c.Dias_credito day),f.fecha_vencimiento) as `fecha de vencimiento`, 
                                            f.estatus as `Estado`,
                                            f.total_facturado as Monto_total,
                                            f.saldo_insoluto as Monto_insoluto,
                                            ifnull(m.Deposito,0) as Monto_pagado,
                                            ifnull(m.parcialidad,0)as parcialidad,
                                            ifnull(comp.foliosFacturas,'') as foliosFacturas,
                                            ifnull(notas.importeNC,0) as importeNC,
                                            ifnull(notas.foliosNotas,'') as foliosNotas,
                                            2 as tipoDoc
                                    FROM facturacion as f
                                        inner join clientes as c on f.cliente_id=c.PKCliente
                                        left join (select sum(mov.Deposito) as Deposito, mov.id_factura, max(mov.parcialidad) as parcialidad 
                                                    from movimientos_cuentas_bancarias_empresa as mov 
                                                    where mov.tipo_movimiento_id = 5 and mov.estatus = 1 and mov.tipo_CuentaCobrar = 2 and mov.id_pago is not null group by mov.id_factura) as m on m.id_factura = f.id
                                        left join (select mv.id_factura, group_concat(fp.folio_complemento SEPARATOR '/') as foliosFacturas
                                                    from pagos p 
                                                        inner join movimientos_cuentas_bancarias_empresa mv on p.idpagos = mv.id_pago 
                                                        left join facturas_pagos fp on p.identificador_pago = fp.folio_pago and p.empresa_id = fp.empresa_id
                                                    where p.empresa_id = :empresa and p.estatus = 1 and p.tipo_movimiento = 0 and mv.estatus = 1 and fp.estatus in (1,2) group by mv.id_factura) as comp on comp.id_factura = f.id
                                        left join (select sum(nc.importe) as importeNC, ncf.facturacion_id, group_concat(concat(nc.num_serie_nota,' ', nc.folion_nota) SEPARATOR '/') as foliosNotas 
                                                    from notas_cuentas_por_cobrar as nc 
                                                        inner join notas_cuentas_por_cobrar_has_facturacion as ncf on nc.id = ncf.notas_cuentas_por_cobrar_id 
                                                    where nc.estatus = 1 and nc.empresa_id=:empresa2 group by ncf.facturacion_id) as notas on notas.facturacion_id = f.id
                                    where f.empresa_id=:empresa3 and f.estatus  not in (4) and f.prefactura = 0 
                                    
                                    union
                                    
                                    SELECT  c.PKCliente, 
                                            c.razon_social as `cliente`, 
                                            vd.PKVentaDirecta as id,
                                            vd.Referencia as folio, 
                                            vd.created_at as `fecha de facturacion`, 
                                            if(vd.FechaVencimiento is null, date_add(vd.created_at, interval c.Dias_credito day),vd.FechaVencimiento) as `fecha de vencimiento`, 
                                            vd.estatus_cuentaCobrar as `Estado`,
                                            vd.Importe as Monto_total,
                                            vd.saldo_insoluto_venta as Monto_insoluto,
                                            ifnull(m.Deposito,0) as Monto_pagado,
                                            ifnull(m.parcialidad,0)as parcialidad,
                                            '' as foliosFacturas,
                                            notas.importeNC as importeNC,
                                            notas.foliosNotas as foliosNotas,
                                            1 as tipoDoc
                                    FROM ventas_directas as vd
                                        inner join clientes as c on vd.FKCliente=c.PKCliente
                                        left join (select sum(mov.Deposito) as Deposito, mov.id_factura, max(mov.parcialidad) as parcialidad 
                                                    from movimientos_cuentas_bancarias_empresa as mov 
                                                    where mov.tipo_movimiento_id = 5 and mov.estatus = 1 and mov.tipo_CuentaCobrar = 1 and mov.id_pago is not null group by mov.id_factura) as m on m.id_factura = vd.PKVentaDirecta
                                        left join (select sum(nc.importe) as importeNC, ncf.venta_id, group_concat(concat(nc.num_serie_nota,' ', nc.folion_nota) SEPARATOR '/') as foliosNotas 
                                                    from notas_cuentas_por_cobrar as nc 
                                                        inner join notas_cuentas_por_cobrar_has_ventas as ncf on nc.id = ncf.notaCredito_id 
                                                    where nc.estatus = 1 and nc.empresa_id=:empresa5 group by ncf.venta_id) as notas on notas.venta_id = vd.PKVentaDirecta
                                    where vd.empresa_id=:empresa4 and vd.empresa_id !=6 and vd.estatus_cuentaCobrar != 4 and vd.estatus_factura_id not in (1,2) and vd.FKEstatusVenta != 5) as tabla Order by `fecha de facturacion` desc;");
$stmt->bindValue(":empresa3",$empresa);
$stmt->bindValue(":empresa4",$empresa);
$stmt->bindValue(":empresa5",$empresa);
    break;
}
$stmt->bindValue(":empresa",$empresa);
$stmt->bindValue(":empresa2",$empresa);

$stmt->execute();

$table="";

    //se llena la tabla según la consulta realizada
    if($seleccion==3){
        while (($row = $stmt->fetch()) !== false) {

            //semaforo de fecha de vencimiento
            $row['fecha de facturacion']=date("d-m-Y", strtotime($row['fecha de facturacion']));
            $row['fecha de vencimiento']=date("d-m-Y", strtotime($row['fecha de vencimiento']));

            $fechaVencimiento=date("Y-m-d", strtotime($row['fecha de vencimiento']));
            $fechaActual=date("Y-m-d");

            if( $fechaActual > $fechaVencimiento && $row['Estado']!=3){
              $row['fecha de vencimiento']='<span class=\"badge badge-danger\" style=\"font-size:1rem;font-family: Montserrat, sans-serif\">'.$row['fecha de vencimiento'].'</span>';
            }else if($row['Estado']!=3){
              $row['fecha de vencimiento']='<span class=\"badge badge-success\" style=\"font-size:1rem;font-family: Montserrat, sans-serif\">'.$row['fecha de vencimiento'].'</span>';
            }

            //añade una etiqueta segun el estado de la factura
            if($row['Estado']==1){
                $row['Estado']= '<span class=\"left-dot blue-light-dot\">Pendiente de Pago</span>';
              }elseif($row['Estado']==2){
                  $row['Estado']= '<span class=\"left-dot orange-dot\">Parcialmente Pagada</span>';
              }elseif($row['Estado']==3){
                $row['Estado']= '<span class=\"left-dot green-dot\">Pagada</span>';
            }elseif($row['Estado']==4){
                $row['Estado']= '<span class=\"left-dot red-dot\">Cancelada</span>';
            }elseif($row['Estado']==5){
                $row['Estado']= '<span class=\"left-dot gray-dot\">En proceso de cancelación</span>';
            }

            //formatea la cantidad
            
            $row['Monto_total']=formatoCantidad($row['Monto_total']);
            $row['Monto_insoluto']=formatoCantidad($row['Monto_insoluto']);
            $row['Monto_pagado']=formatoCantidad($row['Monto_pagado']);
            $row['importeNC']=formatoCantidad($row['importeNC']);

            //condicion: que para definir la posición del tooltip en "ver"
            if($stmt->rowCount()<2){
                $posicion="left";
              }else{
                $posicion="auto";
              }

            //Escapar las dobles comillas
           $cliente = str_replace('"', '', $row['cliente']);

             //si es factua manda la variable factura, si no manda la variable venta
            if($row['tipoDoc'] == 1){
                $var_get='idVenta';
            }else{
                $var_get='idFactura';
            }
            $folio = '<a style=\"cursor:pointer\" href=\"'.$appUrl.'catalogos/cuentas_cobrar/detalle_factura.php?'.$var_get.'='.$row['id'].'\">'.$row['folio'].'</a>';
            
            //link para detalle del cliente
            // $row['cliente'] = '<a style=\"cursor:pointer\" href=\"'.$appUrl.'catalogos/clientes/catalogos/clientes/detalles_cliente.php?c='.$row['PKCliente'].'\">'.$row['cliente'].'</a>';

            //llena la tabla en formato json
            $table.= '{"Cliente":"'.$cliente.
                '","Folio factura":"'.$folio.
                '","F de expedicion":"'.$row['fecha de facturacion'].
                '","F de vencimiento":"'.$row['fecha de vencimiento'].
                '","Estado":"'.$row['Estado'].
                '","Monto total":"$'.$row['Monto_total'].
                '","Monto pagado":"$'.$row['Monto_pagado'].
                '","Parcialidades":"'.$row['parcialidad'].
                '","Monto notas credito":"$'.$row['importeNC'].
                '","Monto insoluto":"$'.$row['Monto_insoluto'].
                '","Complementos":"'.$row['foliosFacturas'].
                '","Notas credito":"'.$row['foliosNotas'].
                '","Ver":"'.'<input type=\"hidden\" id=\"hddTipo-'.$var_get.'-Id-'.$row['id'].'\">'.
                '","Id":"'.$row['id'].'"},';
        } 
    }else{
        while (($row = $stmt->fetch()) !== false) {
            //los campos sin datos los sustituye y cambia el estilo para que esten desactivados
            if($row['60'] == "" || $row['60'] == null){
                $row['60']="$0.00";
                $enlace60 = '<a id=\"edit_btn_30\" class=\"disabled\"> '.$row['60'].' </a>';
        
            }else{
                $row['60']="$".formatoCantidad($row['60']);
                $enlace60 = '<a id=\"edit_btn_30\" class=\"\" href=\"../cuentas_cobrar/cuentas_Cliente.php?periodo=60&id='.$row['PKCliente'].'&seleccion='.$seleccion.'\" > '.$row['60'].' </a>';
        
            }
        
            if($row['30'] == "" || $row['30'] == null){
                $row['30']="$0.00";
                $enlace30 = '<a id=\"edit_btn_30\" class=\"disabled\"> '.$row['30'].' </a>';
            }else{
                $row['30']="$".formatoCantidad($row['30']);
                $enlace30 = '<a id=\"edit_btn_30\" class=\"\" href=\"../cuentas_cobrar/cuentas_Cliente.php?periodo=30&id='.$row['PKCliente'].'&seleccion='.$seleccion.'\" > '.$row['30'].' </a>';
            }
        
            if($row['90'] == "" || $row['90'] == null){
                $row['90']="$0.00";
                $enlace90 = '<a id=\"edit_btn_30\" class=\"disabled\"> '.$row['90'].' </a>';
                 
            }else{
                $row['90']="$".formatoCantidad($row['90']);
                $enlace90 = '<a id=\"edit_btn_30\" class=\"\" href=\"../cuentas_cobrar/cuentas_Cliente.php?periodo=90&id='.$row['PKCliente'].'&seleccion='.$seleccion.'\" > '.$row['90'].' </a>';
        
            }
        
            if($row['99'] == "" || $row['99'] == null){
                $row['99']="$0.00";
                $enlace00 = '<a id=\"edit_btn_30\" class=\"disabled\"> '.$row['99'].' </a>';
    
            }else{
                $row['99']="$".formatoCantidad($row['99']);
                $enlace00 = '<a id=\"edit_btn_30\" class=\"\" href=\"../cuentas_cobrar/cuentas_Cliente.php?periodo=00&id='.$row['PKCliente'].'&seleccion='.$seleccion.'\" > '.$row['99'].' </a>';

            }

            $row['razon_social'] = str_replace('"', '', $row['razon_social']);

            //link para detalle del cliente
            $row['razon_social'] = '<a style=\"cursor:pointer\" href=\"'.$appUrl.'catalogos/clientes/catalogos/clientes/detalles_cliente.php?c='.$row['PKCliente'].'\">'.$row['razon_social'].'</a>';
        
            /* Guardamos en un JSON los datos de la consulta  */
            $table.='{"Cliente":"'.$row['razon_social'].
                '","Id":"'.$row['PKCliente'].
                '","De 0-30 Dias":"'.$enlace30.
                '","De 31-60 Dias":"'.$enlace60.
                '","De 61-60 Dias":"'.$enlace90.
                '","Mas de 90 Dias":"'.$enlace00.' </a>'.'"},';   
        }
    }

  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';

 ?>