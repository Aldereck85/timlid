<?php
session_start();

$jwt_ruta = "../../../";
require_once '../../jwt.php';

require_once '../../../include/db-conn.php';

$idNomina = $_POST['idNomina'];

$stmt = $conn->prepare("SELECT n.id, n.no_nomina, n.no_empleados, DATE_FORMAT(n.fecha_pago, '%d/%m/%Y') as fecha_pago, DATE_FORMAT(n.fecha_inicio, '%d/%m/%Y') as fecha_inicio,
    DATE_FORMAT(n.fecha_fin, '%d/%m/%Y') as fecha_fin, n.total, n.estatus, n.ultima_nomina, n.autorizada
        FROM nomina as n
            WHERE n.empresa_id = ".$_SESSION['IDEmpresa'].' AND n.fk_nomina_principal = :fk_nomina_principal ORDER BY n.no_nomina ASC');
$stmt->bindValue(":fk_nomina_principal", $idNomina);
$stmt->execute();
$nomina = $stmt->fetchAll();
$table = "";
foreach ($nomina as $n) {

    $tipo = 1; //siempre se maneja ordinaria en esta pantalla
    
    if($n['autorizada'] == 1 ){
        $autorizada =   '<div class=\"container_esp\"><label class=\"container_esp\"><input type=\"checkbox\" checked=\"checked\" disabled><span class=\"checkmark\"></span></label></div>';
        $autorizarNomina = '<span class=\"fas fa-user-check click\"  onclick=\"NominaAutorizada();\" title=\"Autorizar nómina\"></span>';
    }
    else{
        $autorizada =   '<div class=\"container_esp\"><label class=\"container_esp\"><input type=\"checkbox\" disabled><span class=\"checkmark\"></span></label></div>';
        $autorizarNomina = '<span class=\"fas fa-user-check click\"  onclick=\"autorizarNomina(' . $n['id'] . ');\" title=\"Autorizar nómina\"></span>';
    }

    
    $editar = '<span class=\"fas fa-edit pointer\"  onclick=\"obtenerEditar(' . $n['id'] . ');\" data-toggle=\"modal\" data-target=\"#editar_nomina\"></span> '.$autorizarNomina.' <span class=\"fas fa-clipboard-list pointer\"  onclick=\"obtenerVer(' . $n['id'] .','.$tipo. ');\"></span>';

    if($n['estatus'] == 1){
        $estatus = "<span class='left-dot gray-dot'>Pendiente</span>";
    }
    if($n['estatus'] == 2){
        $estatus = "<span class='left-dot green-dot'>Timbrada</span>";
    }

    if($n['ultima_nomina'] == 1 ){
        $ultima_nomina =   '<div class=\"container_esp\"><label class=\"container_esp\"><input type=\"checkbox\" checked=\"checked\" disabled><span class=\"checkmark\"></span></label></div>';
    }
    else{
        $ultima_nomina =   '<div class=\"container_esp\"><label class=\"container_esp\"><input type=\"checkbox\" disabled><span class=\"checkmark\"></span></label></div>';
    }

    


    $table .= '{"no nomina":"' . $n['no_nomina'] . '","fecha inicio":"' . $n['fecha_inicio'] . '","fecha fin":"' . $n['fecha_fin'] .'","fecha pago":"' . $n['fecha_pago'] . '","no empleados":"' . $n['no_empleados'] .'", "total":"' . number_format($n['total'],2) .'","ultima nomina":"' . $ultima_nomina . '","autorizada":"' . $autorizada . '","estatus":"' . $estatus . '","acciones":"' . $editar .'"},';

}
$table = substr($table, 0, strlen($table) - 1);
echo '{"data":[' . $table . ']}';