<?php
session_start();

$jwt_ruta = "../../../";
require_once '../../jwt.php';

require_once '../../../include/db-conn.php';

$stmt = $conn->prepare("SELECT n.id, n.no_nomina, s.sucursal, pp.Periodo, tn.tipo, n.no_empleados, DATE_FORMAT(n.fecha_pago, '%d/%m/%Y') as fecha_pago, DATE_FORMAT(n.fecha_inicio, '%d/%m/%Y') as fecha_inicio, DATE_FORMAT(n.fecha_fin, '%d/%m/%Y') as fecha_fin, n.total, n.estatus FROM nomina as n INNER JOIN sucursales as s ON s.id = n.sucursal_id INNER JOIN periodo_pago as pp ON pp.PKPeriodo_pago = n.periodo_id INNER JOIN tipo_nomina as tn ON tn.id = n.tipo_id WHERE n.empresa_id = ".$_SESSION['IDEmpresa'].' ORDER BY n.id DESC');
$stmt->execute();
$nomina = $stmt->fetchAll();
$table = "";
foreach ($nomina as $n) {

    if($n['tipo'] == "Ordinaria"){
        $tipo = 1;
    }
    else{
        $tipo = 2;
    }
     
    $editar = '<span class=\"fas fa-trash-alt pointer\" data-toggle=\"modal\" data-target=\"#eliminar_VentaDirecta\" onclick=\"eliminarNomina(\'' . $n['id'] .'\');\"></span> <span class=\"fas fa-edit pointer\"  onclick=\"obtenerEditar(' . $n['id'] . ');\" data-toggle=\"modal\" data-target=\"#editar_nomina\"></span> <span class=\"fas fa-clipboard-list pointer\"  onclick=\"obtenerVer(' . $n['id'] .','.$tipo. ');\"></span>';

    if($n['estatus'] == 1){
        $estatus = "<span class='left-dot gray-dot'>Pendiente</span>";
    }
    if($n['estatus'] == 2){
        $estatus = "<span class='left-dot green-dot'>Timbrada</span>";
    }


    $table .= '{"no nomina":"' . $n['no_nomina'] . '","periodicidad":"' . $n['Periodo'] . '","tipo":"' . $n['tipo'] . '","fecha pago":"' . $n['fecha_pago'] . '","fecha inicio":"' . $n['fecha_inicio'] . '","fecha fin":"' . $n['fecha_fin'] .'","no empleados":"' . $n['no_empleados'] .'", "total":"' . number_format($n['total'],2) .'", "acciones":"' . $editar .'", "estatus":"' . $estatus . '"},';

}
$table = substr($table, 0, strlen($table) - 1);
echo '{"data":[' . $table . ']}';