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

$empresa = $_SESSION["IDEmpresa"];
$seleccion=$_REQUEST['seleccion'];
$fecha_desde=$_REQUEST['fecha_desde'];
$fecha_hasta=$_REQUEST['fecha_hasta'];
$cliente="";
$between="";
$betweenb="";
$respuesta['message']="ok";
$respuesta['total']=0;


if($seleccion!="" || $seleccion!=null){
    $cliente='and (c.NombreComercial REGEXP "'.$seleccion.'" or c.razon_social REGEXP "'.$seleccion.'" or c.rfc REGEXP "'.$seleccion.'")';
}

if($fecha_desde!="no"){
    if($fecha_hasta=="no"){
        $fecha_hasta=date("Y-m-d");
    }
    $between='and f.fecha_timbrado between "'.$fecha_desde.'" and "'.$fecha_hasta.' 23:59:59"';
    $betweenb='and vd.created_at between "'.$fecha_desde.'" and "'.$fecha_hasta.' 23:59:59"';
}else{
    if($fecha_hasta!="no"){
        $between='and f.fecha_timbrado <= "'.$fecha_hasta.' 23:59:59"';
        $betweenb='and vd.created_at <= "'.$fecha_hasta.' 23:59:59"';
    }
}
try{
        $query = ('SELECT * FROM (SELECT  c.PKCliente, 
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
                    where f.empresa_id=:empresa3 and f.prefactura = 0 and f.estatus not in (4) '.$cliente.' '.$between.' 
                    
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
                            where nc.estatus = 1 and nc.empresa_id=:empresa5 group by ncf.venta_id) as notas on notas.venta_id = vd.PKVentaDirecta          
                        where vd.empresa_id=:empresa4 and vd.empresa_id !=6 and vd.estatus_cuentaCobrar != 4 and vd.estatus_factura_id not in (1,2) and vd.FKEstatusVenta != 5 '.$cliente.' '.$betweenb.') as tabla Order by `fecha de facturacion` desc;');
    $stmt = $conn->prepare($query);
    $stmt->bindValue(":empresa1",$empresa);
    $stmt->bindValue(":empresa2",$empresa);
    $stmt->bindValue(":empresa3",$empresa);
    $stmt->bindValue(":empresa4",$empresa);
    $stmt->bindValue(":empresa5",$empresa);
    $stmt->execute();

    $table="";
    //variable para identificar el primer cliente y usarlo como referencia, si existen mas de un cliente no calcula el total.
    $FirstCliente= $stmt->rowCount() > 0 ? null : 0;
    $isManyClientes=0;

    while (($row = $stmt->fetch()) !== false) {

        if($FirstCliente == null){
            $FirstCliente= $row['PKCliente'];
        }

        if($FirstCliente != $row['PKCliente'] && $isManyClientes==0){
            $isManyClientes=1;
            $respuesta['total']=0;
        }else{
            //solo suma el monto de las facturas pendientes de pago
            if(($row['Estado']==1 || $row['Estado']==2)){
                $respuesta['total']=$respuesta['total']+$row['Monto_insoluto'];
            }
        }

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
        $clienteRS = str_replace('"', '', $row['cliente']);
    
        //link para detalle del cliente
        $row['cliente'] = '<a style=\"cursor:pointer\" href=\"'.$appUrl.'catalogos/clientes/catalogos/clientes/detalles_cliente.php?c='.$row['PKCliente'].'\">'.$row['cliente'].'</a>';
    
        //llena la tabla en formato json
        $table.= '{"Cliente":"'.$clienteRS.
            '","Folio factura":"'.$row['folio'].
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
            '","Ver":"'.'<a href=\"../cuentas_cobrar/detalle_factura.php?idFactura='.$row['id'].'\"><i class=\"fas fa-clipboard-list pointer\" style=\"cursor:pointer\" width=\"30px\" height=\"30px\" data-toggle=\"tooltip\" data-placement=\"'.$posicion.'\" title=\"Ver detalle\"></i></a>'.
            '","Id":"'.$row['id'].'"},';
    }
}catch(Exception $e){
    $respuesta['message']=$e;
}

$respuesta['total'] = formatoCantidad($respuesta['total']);
$table = '{"data":['.substr($table,0,strlen($table)-1).'], "total":"'.$respuesta['total'].'", "isManyClientes":"'.$isManyClientes.'", "message":"'.$respuesta['message'].'"}';
echo ($table);
?>