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
    public function getTraspasos($fromDate, $toDate)
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
                        DATE_FORMAT(ops.fecha_captura, '%d/%m/%Y %H:%i:%s') as fecha_ingreso,
                        u.nombre
                    FROM orden_pedido_por_sucursales as ops 
                        LEFT JOIN usuarios u ON u.id = ops.usuario_creo_id
                        LEFT JOIN sucursales as so ON so.id = ops.sucursal_origen_id
                        LEFT JOIN sucursales as sd ON sd.id = ops.sucursal_destino_id
                    WHERE ops.tipo_pedido = 1 and ops.empresa_id = :idEmpresa group by ops.id ORDER BY ops.id DESC";

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
            $enlace = '<a href=\"detalleTraspaso.php?id=' . $r['id'] . '\">' . $id_orden_pedido_empresa . '</a>';

            $table .= '{"No Traspaso":"' . $enlace . '","Sucursal origen":"' . $r['sucursal_origen'] . '","Sucursal destino":"' . $r['sucursal_destino'] . '","Fecha generacion":"' . $r['fecha_ingreso'] . '", "Usuario" : "' . $r['nombre'] . '"},';
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

    public function getLotesTraspaso($pkProducto, $pkSucursalOrigen)
    {
        $con = new conectar();
        $db = $con->getDb();
        $PKPUsuario = $_SESSION['PKUsuario'];
        
        $query = sprintf('call spc_Lotes_Traspaso(?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkProducto, $pkSucursalOrigen, $PKPUsuario));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }
}

//$ejemplo = new get_data();
//var_dump($ejemplo->getPedido("2021-07-15","2021-07-16"));