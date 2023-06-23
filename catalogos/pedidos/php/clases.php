<?php
session_start();
date_default_timezone_set('America/Mexico_City');
$user = $_SESSION["Usuario"];
class conectar
{

    public function getDb()
    {
        include "../../../include/db-conn.php";
        return $conn;
    }
}

function GetEvn()
{
    include "../../../include/db-conn.php";
    $appUrl = $_ENV['APP_URL'] ?? 'https://app.timlid.com/';
    return ['server' => $appUrl];
}

class get_data
{
    public function getPedido($fromDate, $toDate)
    {
        $con = new conectar();
        $db = $con->getDb();
        $envVariables = GetEvn();
        $appUrl = $envVariables['server'];

        $table = "";
        $filters = '';
        $values = [':idEmpresa' => $_SESSION['IDEmpresa']];
        $query =    "SELECT 
                        ops.id,
                        ops.id_orden_pedido_empresa,
                        so.sucursal as sucursal_origen,
                        sd.sucursal as sucursal_destino,
                        c.PKCliente,
                        c.razon_social as cliente,
                        DATE_FORMAT(ops.fecha_captura, '%d/%m/%Y %H:%i:%s') as fecha_ingreso,
                        ops.tipo_pedido,
                        eop.estatus,
                        ops.estatus_factura_id as estatus_factura,
                        if(ops.estatus_factura_id = 1 || ops.estatus_factura_id = 2 || ops.estatus_factura_id = 5 || ops.estatus_factura_id = 9 || ops.estatus_factura_id = 10,ifnull(fac.folio, ifnull(fa.folio, ifnull(f.folio,fact.folio))), null) as folio_facturacion,
                        if(ops.estatus_factura_id = 1 || ops.estatus_factura_id = 2 || ops.estatus_factura_id = 5 || ops.estatus_factura_id = 9 || ops.estatus_factura_id = 10,ifnull(fac.serie, ifnull(fa.serie, ifnull(f.serie,fact.serie))), null) as serie_facturacion
                    FROM orden_pedido_por_sucursales as ops 
                        LEFT JOIN sucursales as so ON so.id = ops.sucursal_origen_id
                        LEFT JOIN sucursales as sd ON sd.id = ops.sucursal_destino_id
                        LEFT JOIN clientes as c ON c.PKCliente = ops.cliente_id
                        LEFT JOIN estatus_orden_pedido as eop ON eop.id = ops.estatus_orden_pedido_id
                        LEFT JOIN inventario_salida_por_sucursales AS isps on isps.orden_pedido_id = ops.id
                        left join facturacion as f on f.referencia like concat('%', isps.folio_salida, '%') and f.estatus != 4
                        left join facturacion as fa on fa.referencia like concat('%', ops.numero_venta_directa, '%') and ops.numero_venta_directa != '' and fa.tipo = 2 and fa.estatus != 4
                        left join facturacion as fac on fac.referencia like concat('%', ops.numero_cotizacion, '%') and ops.numero_cotizacion != '' and fac.tipo = 1 and fac.estatus != 4
                        left join facturacion as fact on fact.id like concat('%', ops.factura_id, '%') and ops.factura_id != '' and fact.tipo = 1 and fact.estatus != 4
                    WHERE ops.empresa_id = :idEmpresa group by ops.id ORDER BY ops.id DESC";

        if($fromDate && $toDate) {
            $filters = " AND ops.fecha_captura >= :fromDate AND ops.fecha_captura <= :toDate";
            $values = [':idEmpresa' => $_SESSION['IDEmpresa'], ':fromDate' => $fromDate, ':toDate' => $toDate];
        } else if($fromDate) {
            $filters = " AND ops.fecha_captura >= :fromDate";
            $values = [':idEmpresa' => $_SESSION['IDEmpresa'], ':fromDate' => $fromDate];
        } else if($toDate) {
            $filters = " AND ops.fecha_captura <= :toDate";
            $values = [':idEmpresa' => $_SESSION['IDEmpresa'], ':toDate' => $toDate];
        }
        
        $stmt = $db->prepare($query.$filters);
        $stmt->execute($values);
        $row = $stmt->fetchAll();
        $table = "";

        foreach ($row as $r) {

            $id_orden_pedido_empresa = sprintf("%011d", $r['id_orden_pedido_empresa']);
            $enlace = '<a href=\"detallePedido.php?id=' . $r['id'] . '\">' . $id_orden_pedido_empresa . '</a>';

            switch ($r['estatus']) {
                case 'Nuevo':   
                    $estatus = '<span class=\"left-dot turquoise-dot\">Nuevo</span>';
                    break;
                case 'Nuevo FD':
                    $estatus = '<span class=\"left-dot turquoise-dot\">Nuevo-FD</span>';
                    break;
                case 'Parcialmente surtido':
                    $estatus = '<span class=\"left-dot yellow-dot\">Parcialmente surtido</span>';
                    break;
                case 'Parcialmente surtido FD':
                    $estatus = '<span class=\"left-dot yellow-dot\">Parcialmente surtido-FD</span>';
                    break;
                case 'Surtido completo':
                    $estatus = '<span class=\"left-dot green-dot\">Surtido completo</span>';
                    break;
                case 'Surtido completo FD':
                    $estatus = '<span class=\"left-dot green-dot\">Surtido completo-FD</span>';
                    break;
                case 'Cerrado':
                    $estatus = '<span class=\"left-dot red-dot\">Cerrado</span>';
                    break;
                case 'Cancelado':
                    $estatus = '<span class=\"left-dot red-dot\">Cancelado</span>';
                    break;
                case 'Facturado directo':
                    $estatus = '<span class=\"left-dot turquoise-dot\">Facturado-directo</span>';
                    break;
                case 'Facturado almacen':
                    $estatus = '<span class=\"left-dot turquoise-dot\">Facturado-almacen</span>';
                    break;
                case 'Remisionado parcial':
                    $estatus = '<span class=\"left-dot orange-dot\">Remisionado parcial</span>';
                    break;
                case 'Remisionado completo':
                    $estatus = '<span class=\"left-dot orange-dot\">Remisionado completo</span>';
                    break;
                
            }

            $estatus_factura = $r['estatus_factura'];
            switch ($estatus_factura) {
                case 1:
                    $estatus_factura = '<span class=\"left-dot turquoise-dot\">Facturado completo</span>';
                    break;
                case 2:
                    $estatus_factura = '<span class=\"left-dot turquoise-dot\">Facturado directo</span>';
                    break;
                case 3:
                    $estatus_factura = '<span class=\"left-dot yellow-dot\">Pendiente de facturar</span>';
                    break;
                case 4:
                    $estatus_factura = '<span class=\"left-dot yellow-dot\">Pendiente de facturar directo</span>';
                    break;
                case 5:
                    $estatus_factura = '<span class=\"left-dot green-dot\">Parcialmente facturado almacén</span>';
                    break;
                case 6:
                    $estatus_factura = '<span class=\"left-dot red-dot\">Cancelada</span>';
                    break;
                case 7:
                    $estatus_factura = '<span class=\"left-dot orange-dot\">Remisionado parcial</span>';
                    break;
                case 8:
                    $estatus_factura = '<span class=\"left-dot orange-dot\">Remisionado completo</span>';
                    break;
                case 9:
                    $estatus_factura = '<span class=\"left-dot gray-dot\">Facturado de remision parcial</span>';
                    break;
                case 10:
                    $estatus_factura = '<span class=\"left-dot dark-dot\">Facturado de remision completo</span>';
                    break;
            }

            if($r['tipo_pedido'] == 1){
                $tipo_pedido = "Traspaso";
            }
            elseif($r['tipo_pedido'] == 2){
                $tipo_pedido = "General";
            }
            elseif($r['tipo_pedido'] == 3){
                $tipo_pedido = "Cotización";
            }
            elseif($r['tipo_pedido'] == 4){
                $tipo_pedido = "Venta";
            }
            elseif($r['tipo_pedido'] == 5){
                $tipo_pedido = "Factura";
            }

            if($r['folio_facturacion'] == null && $r['serie_facturacion'] == null){
                $guion = '';
            }else{
                $guion = '-';
            }

            $r['cliente'] = str_replace('"', '\"', $r['cliente']);

            //link para detalle del cliente
            $r['cliente'] = '<a style=\"cursor:pointer\" href=\"'.$appUrl.'catalogos/clientes/catalogos/clientes/detalles_cliente.php?c='.$r['PKCliente'].'\">'.$r['cliente'].'</a>';

            $table .= '{"No Pedido":"' . $enlace . '","Sucursal origen":"' . $r['sucursal_origen'] . '","Sucursal destino":"' . $r['sucursal_destino'] . '","Cliente":"' . $r['cliente'] . '","Fecha generacion":"' . $r['fecha_ingreso'] . '","Tipo pedido": "' . $tipo_pedido . '","Acciones": "'.$r['folio_facturacion'].$guion.$r['serie_facturacion'].'","Estatus": "' . $estatus . '","Estatus factura": "' . $estatus_factura . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';

    }

    public function getCmbProductosSucOrigen($pkSucursalOrigen)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Combo_Productos_SucOrigen(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa,$pkSucursalOrigen));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getEstatusSalidaTraspasoPedido($OrdenPedido)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Datos_Estatus_OrdenPedidoTraspaso(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($OrdenPedido));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getParcialidadesPedido($PKPedido)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_ParcialidadesPedido(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKPedido));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getTipoParcialidadesPedido($Folio)
    {
        $con = new conectar();
        $db = $con->getDb();
        $PKEmpresa= $_SESSION['IDEmpresa'];
        
        $query = sprintf('call spc_TipoParcialidadPedido(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($Folio, $PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }
}

//$ejemplo = new get_data();
//var_dump($ejemplo->getPedido("2021-07-15","2021-07-16"));