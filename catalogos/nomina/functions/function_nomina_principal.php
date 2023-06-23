<?php
session_start();

$jwt_ruta = "../../../";
require_once '../../jwt.php';

require_once '../../../include/db-conn.php';

$stmt = $conn->prepare("SELECT n.id, n.no_nomina, s.sucursal, pp.Periodo, tn.tipo, DATE_FORMAT(n.fecha_ini, '%d/%m/%Y') as fecha_inicio, n.confidencial FROM nomina_principal as n INNER JOIN sucursales as s ON s.id = n.sucursal_id INNER JOIN periodo_pago as pp ON pp.PKPeriodo_pago = n.periodo_id INNER JOIN tipo_nomina as tn ON tn.id = n.tipo_id WHERE n.empresa_id = ".$_SESSION['IDEmpresa'].' ORDER BY n.id DESC');
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

    if($n['confidencial'] == 1 ){
        $confidencial =   '<div class=\"container_esp\"><label class=\"container_esp\"><input type=\"checkbox\" checked=\"checked\" disabled><span class=\"checkmark\"></span></label></div>';
    }
    else{
        $confidencial =   '<div class=\"container_esp\"><label class=\"container_esp\"><input type=\"checkbox\" disabled><span class=\"checkmark\"></span></label></div>';
    }
     
    $editar = '<span class=\"fas fa-trash-alt pointer\" data-toggle=\"modal\" data-target=\"#eliminar_VentaDirecta\" onclick=\"eliminarNomina(\'' . $n['id'] .'\');\"></span> <span class=\"fas fa-clipboard-list pointer\"  onclick=\"verPeriodos(' . $n['id'] .','.$tipo. ');\"></span>';


    $table .= '{"no nomina":"' . $n['no_nomina'] . '","sucursal":"' . $n['sucursal'] . '","periodicidad":"' . $n['Periodo'] . '","tipo":"' . $n['tipo'] . '","fecha inicio":"' . $n['fecha_inicio'] . '","confidencial":"' . $confidencial . '", "acciones":"' . $editar .'"},';

}
$table = substr($table, 0, strlen($table) - 1);
echo '{"data":[' . $table . ']}';