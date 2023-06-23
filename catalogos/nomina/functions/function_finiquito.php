<?php
session_start();

$jwt_ruta = "../../../";
require_once '../../jwt.php';

require_once '../../../include/db-conn.php';

$stmt = $conn->prepare("SELECT f.id, CONCAT(e.Nombres,' ',e.PrimerApellido,' ',e.SegundoApellido) as nombre_empleado, DATE_FORMAT(f.fecha_ingreso, '%d/%m/%Y') as fecha_ingreso, DATE_FORMAT(f.fecha_salida, '%d/%m/%Y') as fecha_salida, IF(ISNULL(l.id),1,2) as tipo, f.total_pagar,  DATE_FORMAT(f.fecha_alta, '%d/%m/%Y') as fecha_alta, f.estadoTimbrado FROM finiquito as f INNER JOIN empleados as e ON e.PKEmpleado = f.empleado_id LEFT JOIN liquidacion as l ON f.id = l.finiquito_id WHERE f.empresa_id = ".$_SESSION['IDEmpresa'].' ORDER BY f.id DESC, f.estadoTimbrado');
$stmt->execute();
$finiquito = $stmt->fetchAll();
$table = "";
foreach ($finiquito as $f) {

    if($f['tipo'] == "1"){
        $tipo = "Finiquito";
    }
    else{
        $tipo = "Liquidación";
    }

    switch ($f['estadoTimbrado']) {
        case '0':
            $estatus = "<span class='left-dot yellow-dot'>Pendiente</span>";
            break;
        case '2':
            $estatus = "<span class='left-dot red-dot'>Cancelada</span>";
            break;
        case '1':
            $estatus = "<span class='left-dot green-dot'>Timbrada</span>";
            break;
    }
     
    $editar = '<span class=\"fas fa-clipboard-list pointer\"  onclick=\"obtenerVer(' . $f['id'] .','.$f['tipo']. ');\"></span>';

    $table .= '{"empleado":"' . $f['nombre_empleado'] . '","fecha ing":"' . $f['fecha_ingreso'] . '","fecha sal":"' . $f['fecha_salida'] . '","tipo":"' . $tipo . '","total pag":"' . number_format($f['total_pagar'],2) . '","fecha gen":"' . $f['fecha_alta'] .'", "estatus":"' . $estatus .'","acciones":"' . $editar .'"},';

}
$table = substr($table, 0, strlen($table) - 1);
echo '{"data":[' . $table . ']}';