<?php
session_start();

$jwt_ruta = "../../../";
require_once '../../jwt.php';

require_once '../../../include/db-conn.php';

$stmt = $conn->prepare("SELECT rcd.id, td.codigo, rtd.clave, rcd.concepto_nomina as concepto, rbd.id as idrbp, GROUP_CONCAT(s.sucursal SEPARATOR '<br>') as sucursales 
                        FROM relacion_concepto_deduccion as rcd
                        LEFT JOIN tipo_deduccion as td ON rcd.tipo_deduccion_id = td.id
                        LEFT JOIN relacion_tipo_deduccion as rtd ON rtd.tipo_deduccion_id = td.id  AND rtd.empresa_id = ".$_SESSION['IDEmpresa']." 
                        LEFT JOIN relacion_base_deduccion as rbd ON rbd.relacion_concepto_deduccion_id = rcd.id AND rbd.empresa_id = ".$_SESSION['IDEmpresa']."
                        LEFT JOIN relacion_sucursal_deduccion as rsd ON rsd.relacion_concepto_deduccion_id = rcd.id AND rsd.empresa_id = ".$_SESSION['IDEmpresa']." 
                        LEFT JOIN sucursales as s ON s.id = rsd.sucursal_id WHERE rcd.empresa_id = ".$_SESSION['IDEmpresa']." GROUP BY rcd.id");

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