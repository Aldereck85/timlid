<?php
session_start();
require_once('../../../../include/db-conn.php');

$table = "";
$PKEmpresa = $_SESSION["IDEmpresa"];
$proveedor_id = $_POST['proveedor_id'];

$stmt = $conn->prepare('call spc_Tabla_OrdenesCompra_ConsultaDetalleProveedor(?,?)');
$stmt->execute(array($PKEmpresa, $proveedor_id));
$array = $stmt->fetchAll();

foreach ($array as $r) {

    $Id = $r['PKOrdenCompra'];
    $Referencia = $r['Referencia'];
    $FechaCreacion = $r['FechaCreacion'];
    $FechaEstimadaEntrega = $r['FechaEstimada'];

    if ($r['FechaEntrega'] !== "" && $r['FechaEntrega'] !== null) {
        $fechaEntrega = $r['FechaEntrega'];
    } else {
        $fechaEntrega = "No se ha entregado el pedido";
    }

    $importe = number_format($r['Importe'], 2, '.', ',');

    $EstatusOrden = $r['EstatusOrden'];
    $colorEstatus = '';
    $cierreEstatus = '</span>';
    //$acciones = '';

    if ($EstatusOrden == 'En espera') {
        $colorEstatus = '<span class=\"left-dot gray-dot\">';
        //$acciones = '<i><input type=\"hidden\" value=\"' . md5($Id) . '\" id=\"inp-' . $Id . '\"><i class=\"fas fa-clipboard-list pointer\"  onclick=\"obtenerVer(\'' . $Id . '\',\'1\');\"</i></i>';
        $Referencia = '<a style=\"cursor:pointer; color: #4e73df\" class=\"btn-table-custom btn-table-custom--blue\" onclick=\"obtenerVer(\'' . $Id . '\',\'1\');\">'.$Referencia.'</a>';
    } else if ($EstatusOrden == 'Vencida') {
        $colorEstatus = '<span class=\"left-dot red-dot\">';
        //$acciones = '<i><input type=\"hidden\" value=\"' . md5($Id) . '\" id=\"inp-' . $Id . '\"><i class=\"fas fa-clipboard-list pointer\"  onclick=\"obtenerVer(\'' . $Id . '\',\'2\');\"</i></i>';
        $Referencia = '<a style=\"cursor:pointer; color: #4e73df\" class=\"btn-table-custom btn-table-custom--blue\" onclick=\"obtenerVer(\'' . $Id . '\',\'2\');\">'.$Referencia.'</a>';
    } else if ($EstatusOrden == 'Aceptada') {
        $colorEstatus = '<span class=\"left-dot turquoise-dot\">';
        //$acciones = '<i><input type=\"hidden\" value=\"' . md5($Id) . '\" id=\"inp-' . $Id . '\"><i class=\"fas fa-clipboard-list pointer\"  onclick=\"obtenerVer(\'' . $Id . '\',\'3\');\"</i></i>';
        $Referencia = '<a style=\"cursor:pointer; color: #4e73df\" class=\"btn-table-custom btn-table-custom--blue\" onclick=\"obtenerVer(\'' . $Id . '\',\'3\');\">'.$Referencia.'</a>';
    } else if ($EstatusOrden == 'Cancelada') {
        $colorEstatus = '<span class=\"left-dot red-dot\">';
        //$acciones = '<i><input type=\"hidden\" value=\"' . md5($Id) . '\" id=\"inp-' . $Id . '\"><i class=\"fas fa-clipboard-list pointer\"  onclick=\"obtenerVer(\'' . $Id . '\',\'4\');\"</i></i>';
        $Referencia = '<a style=\"cursor:pointer; color: #4e73df\" class=\"btn-table-custom btn-table-custom--blue\" onclick=\"obtenerVer(\'' . $Id . '\',\'4\');\">'.$Referencia.'</a>';
    } else if ($EstatusOrden == 'Rechazada') {
        $colorEstatus = '<span class=\"left-dot red-dot\">';
        //$acciones = '<i><input type=\"hidden\" value=\"' . md5($Id) . '\" id=\"inp-' . $Id . '\"><i class=\"fas fa-clipboard-list pointer\"  onclick=\"obtenerVer(\'' . $Id . '\',\'5\');\"</i></i>';
        $Referencia = '<a style=\"cursor:pointer; color: #4e73df\" class=\"btn-table-custom btn-table-custom--blue\" onclick=\"obtenerVer(\'' . $Id . '\',\'5\');\">'.$Referencia.'</a>';
    } else if ($EstatusOrden == 'Aceptada-Demorada') {
        $colorEstatus = '<span class=\"left-dot yellow-dot\">';
        //$acciones = '<i><input type=\"hidden\" value=\"' . md5($Id) . '\" id=\"inp-' . $Id . '\"><i class=\"fas fa-clipboard-list pointer\"  onclick=\"obtenerVer(\'' . $Id . '\',\'6\');\"</i></i>';
        $Referencia = '<a style=\"cursor:pointer; color: #4e73df\" class=\"btn-table-custom btn-table-custom--blue\" onclick=\"obtenerVer(\'' . $Id . '\',\'6\');\">'.$Referencia.'</a>';
    } else if ($EstatusOrden == 'Cerrada') {
        $colorEstatus = '<span class=\"left-dot red-dot\">';
        //$acciones = '<i><input type=\"hidden\" value=\"' . md5($Id) . '\" id=\"inp-' . $Id . '\"><i class=\"fas fa-clipboard-list pointer\"  onclick=\"obtenerVer(\'' . $Id . '\',\'7\');\"</i></i>';
        $Referencia = '<a style=\"cursor:pointer; color: #4e73df\" class=\"btn-table-custom btn-table-custom--blue\" onclick=\"obtenerVer(\'' . $Id . '\',\'7\');\">'.$Referencia.'</a>';
    } else if ($EstatusOrden == 'Completa') {
        $colorEstatus = '<span class=\"left-dot green-dot\">';
        //$acciones = '<i><input type=\"hidden\" value=\"' . md5($Id) . '\" id=\"inp-' . $Id . '\"><i class=\"fas fa-clipboard-list pointer\"  onclick=\"obtenerVer(\'' . $Id . '\',\'8\');\"</i></i>';
        $Referencia = '<a style=\"cursor:pointer; color: #4e73df\" class=\"btn-table-custom btn-table-custom--blue\" onclick=\"obtenerVer(\'' . $Id . '\',\'8\');\">'.$Referencia.'</a>';
    }

    //$temp = "No hay fecha limite de pago"; "Fecha Limite de Pago":"'.$temp.'",

    $etiquetaI = '<span class=\"textTable\">';
    $etiquetaF = '</span>';

    $table .= '{"Id":"' . $etiquetaI . $Id . $etiquetaF . '",
            "Referencia":"' . $etiquetaI . $Referencia . $etiquetaF . '",
            "FechaEmision":"' . $etiquetaI . $FechaCreacion . $etiquetaF . '",
            "FechaEstimadaEntrega":"' . $etiquetaI . $FechaEstimadaEntrega . $etiquetaF . '",
            "Importe":"' . $etiquetaI . '$' . $importe . $etiquetaF . '",
            "EstatusOrden":"' . $colorEstatus . $EstatusOrden . $cierreEstatus . '"},';

}
$table = substr($table, 0, strlen($table) - 1);

echo '{"data":[' . $table . ']}';

?>