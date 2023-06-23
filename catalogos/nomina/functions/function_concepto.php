<?php
session_start();

$jwt_ruta = "../../../";
require_once '../../jwt.php';

require_once '../../../include/db-conn.php';

$stmt = $conn->prepare("SELECT rtp.id, rtp.clave, tp.codigo, tp.concepto, 'Percepción' as tipo_mov, 1 as tipo FROM relacion_tipo_percepcion as rtp 
                            LEFT JOIN tipo_percepcion as tp ON tp.id = rtp.tipo_percepcion_id 
                            WHERE rtp.empresa_id = ".$_SESSION['IDEmpresa']." 
                            UNION ALL
                       SELECT rtd.id, rtd.clave, td.codigo, td.concepto, 'Deducción' as tipo_mov, 2 as tipo FROM relacion_tipo_deduccion as rtd
                            LEFT JOIN tipo_deduccion as td ON td.id = rtd.tipo_deduccion_id
                            WHERE rtd.empresa_id = ".$_SESSION['IDEmpresa']."
                            ORDER BY tipo");
$stmt->execute();
$concepto = $stmt->fetchAll();
$table = "";
foreach ($concepto as $c) {

    $editar = '<span style=\"float:right;\"><img class=\"btnEdit\" data-toggle=\"modal\" data-target=\"#eliminar_Concepto\" onclick=\"eliminarClave(' . $c['id'] .',' . $c['tipo'] .');\" src=\"../../img/timdesk/delete.svg\"> <img class=\"btnEdit\"  onclick=\"obtenerEditar(' . $c['id'] .',' . $c['tipo'] .');\" src=\"../../img/icons/editar.svg\" title=\"Concepto\" alt=\"Concepto\" data-toggle=\"modal\" data-target=\"#editar_clave\"></span>';

    if($c['tipo'] == 1){
        $clave = $c['codigo']. " - ". $c['concepto'];
    }
    if($c['tipo'] == 2){
        $clave = $c['codigo']. " - ". $c['concepto'];
    }

    $table .= '{"concepto":"' . $clave . '","tipo":"' . $c['tipo_mov'] . '","clave":"' . $c['clave'] .$editar. '"},';

}
$table = substr($table, 0, strlen($table) - 1);
echo '{"data":[' . $table . ']}';