<?php
session_start();

$jwt_ruta = "../../../";
require_once '../../jwt.php';

require_once '../../../include/db-conn.php';

$stmt = $conn->prepare("SELECT rcp.id, tp.codigo, rtp.clave, rcp.concepto_nomina as concepto, rbp.id as idrbp, GROUP_CONCAT(s.sucursal SEPARATOR '<br>') as sucursales 
                        FROM relacion_concepto_percepcion as rcp
                        LEFT JOIN tipo_percepcion as tp ON rcp.tipo_percepcion_id = tp.id
                        LEFT JOIN relacion_tipo_percepcion as rtp ON rtp.tipo_percepcion_id = tp.id  AND rtp.empresa_id = ".$_SESSION['IDEmpresa']." 
                        LEFT JOIN relacion_base_percepcion as rbp ON rbp.relacion_concepto_percepcion_id = rcp.id AND rbp.empresa_id = ".$_SESSION['IDEmpresa']."
                        LEFT JOIN relacion_sucursal_percepcion as rsp ON rsp.relacion_concepto_percepcion_id = rcp.id AND rsp.empresa_id = ".$_SESSION['IDEmpresa']." 
                        LEFT JOIN sucursales as s ON s.id = rsp.sucursal_id WHERE rcp.empresa_id = ".$_SESSION['IDEmpresa']."  GROUP BY rcp.id");

$stmt->execute();
$percepciones = $stmt->fetchAll();
$table = "";
foreach ($percepciones as $p) {

    $editar = '<span class=\"fas fa-clipboard-list pointer\"  title=\"Cargar datos por sucursal\" data-toggle=\"modal\" data-target=\"#agregar_clave_tipo\" onclick=\"cargarBaseSucursal(' . $p['id'] . ');\" data-backdrop=\"static\" data-keyboard=\"false\"></span> <span class=\"far fa-clipboard pointer\"  title=\"Cargar datos por empresa\" data-toggle=\"modal\" data-target=\"#agregar_clave_tipo\" onclick=\"cargarBaseEmpresa(' . $p['id'] . ');\" data-backdrop=\"static\" data-keyboard=\"false\"></span> <span class=\"fas fa-clipboard pointer\"  title=\"Cargar datos del concepto\" data-toggle=\"modal\" data-target=\"#agregar_clave_tipo\" onclick=\"cargarDatosConcepto(' . $p['id'] . ');\" data-backdrop=\"static\" data-keyboard=\"false\"></span>';


    if(trim($p['idrbp']) != "" || trim($p['idrbp']) != NULL){
        $global =   '<div class=\"container_esp\"><label class=\"container_esp\"><input type=\"checkbox\" checked=\"checked\" disabled><span class=\"checkmark\"></span></label></div>';
    }
    else{
        $global =   '<div class=\"container_esp\"><label class=\"container_esp\"><input type=\"checkbox\" disabled><span class=\"checkmark\"></span></label></div>';
    }


    $table .= '{"concepto":"' . $p['codigo'] . ' - ' .$p['concepto'].'","clave":"' . $p['clave'] . '","global":"' . $global . '","sucursal":"' . $p['sucursales'] .'", "acciones":"' . $editar .'"},';

}
$table = substr($table, 0, strlen($table) - 1);
echo '{"data":[' . $table . ']}';