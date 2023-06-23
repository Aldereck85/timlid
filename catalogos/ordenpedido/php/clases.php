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

class get_data
{
    public function getOrdenPedido()
    {
        $con = new conectar();
        $db = $con->getDb();
        $table = "";

        $stmt = $db->prepare('SELECT ops.id, ops.id_orden_pedido_empresa, so.sucursal as sucursal_origen, sd.sucursal as sucursal_destino, c.NombreComercial as cliente, DATE_FORMAT(ops.fecha_captura, "%d/%m/%Y %H:%i:%s") as fecha_ingreso, DATE_FORMAT(ops.fecha_entrega, "%d/%m/%Y") as fecha_entrega, eop.estatus  FROM orden_pedido_por_sucursales as ops INNER JOIN sucursales as so ON so.id = ops.sucursal_origen_id LEFT JOIN sucursales as sd ON sd.id = ops.sucursal_destino_id LEFT JOIN clientes as c ON c.PKCliente = ops.cliente_id LEFT JOIN estatus_orden_pedido as eop ON eop.id = ops.estatus_orden_pedido_id WHERE ops.empresa_id = '.$_SESSION['IDEmpresa']);
        $stmt->execute();
        $row = $stmt->fetchAll();
        $table = "";

        foreach ($row as $r) {

            $id_orden_pedido_empresa = sprintf("%011d", $r['id_orden_pedido_empresa']);
            $enlace = '<a href=\"detalleOrdenPedido.php?id=' . $r['id'] . '\">' . $id_orden_pedido_empresa . '</a>';

            switch ($r['estatus']) {
                case 'Nuevo':   
                    $estatus = "<div style='background:#8e8e8e;padding:5px;color:white; width:100%;'><center>Nuevo</center></div>";
                    break;
                case 'Nuevo-FD':
                    $estatus = "<div style='background:#6c757d;padding:5px;color:white; width:100%;'><center>Nuevo-FD</center></div>";
                    break;
                case 'Parcialmente surtido':
                    $estatus = "<div style='background:#ffd467;padding:5px;color:white; width:100%;'><center>Parcialmente surtido</center></div>";
                    break;
                case 'Parcialmente surtido-FD':
                    $estatus = "<div style='background:#f6c23e;padding:5px;color:white; width:100%;'><center>Parcialmente surtido-FD</center></div>";
                    break;
                case 'Surtido completo':
                    $estatus = "<div style='background:#54d0a3;padding:5px;color:white; width:100%;'><center>Surtido completo</center></div>";
                    break;
                case 'Surtido completo-FD':
                    $estatus = "<div style='background:#08c681;padding:5px;color:white; width:100%;'><center>Surtido completo-FD</center></div>";
                    break;
                case 'Cerrado':
                    $estatus = "<div style='background:#d8636e;padding:5px;color:white; width:100%;'><center>Cerrado</center></div>";
                    break;
                case 'Cancelado':
                    $estatus = "<div style='background:#dc3545;padding:5px;color:white; width:100%;'><center>Cancelado</center></div>";
                    break;
                case 'Facturado-directo':
                    $estatus = "<div style='background:#91deea;padding:5px;color:white; width:100%;'><center>Facturado-directo</center></div>";
                    break;
                case 'Facturado-almacen':
                    $estatus = "<div style='background:#67c3d2;padding:5px;color:white; width:100%;'><center>Facturado-almacen</center></div>";
                    break;
                
                
            }

            $table .= '{"No Pedido":"' . $enlace . '","Sucursal origen":"' . $r['sucursal_origen'] . '","Sucursal destino":"' . $r['sucursal_destino'] . '","Cliente":"' . $r['cliente'] . '","Fecha generacion":"' . $r['fecha_ingreso'] . '","Fecha entrega": "' . $r['fecha_entrega'] . '","Estatus": "' . $estatus . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';

    }

    
}