<?php
session_start();

$jwt_ruta = "../../../";
require_once '../../jwt.php';

require_once '../../../include/db-conn.php';

$stmt = $conn->prepare("SELECT td.id, td.codigo, td.concepto, rtd.clave FROM tipo_deduccion as td LEFT JOIN relacion_tipo_deduccion as rtd ON rtd.tipo_deduccion_id = td.id  AND rtd.empresa_id = ".$_SESSION['IDEmpresa']);
$stmt->execute();
$percepciones = $stmt->fetchAll();
$table = "";
foreach ($percepciones as $p) {

    /*if ($number_of_rows > 0) {
        $funciones = '<a class=\"btn btn-success\" href=\"asistencia.php?id=' . $empleado['PKEmpleado'] . '&semana=' . $semana . '\"><i class=\"fas fa-search-dollar\"></i> Ver nomina</a>&nbsp;&nbsp;';
    } else {
        $funciones = '<div class=\"dropdown\"><button class=\"btn btn-secondary btnMargin dropdown-toggle\" type=\"button\" id=\"dropdownMenuButton\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\"><i class=\"fas fa-tools\"></i> Opciones</button><div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuButton\"><a class=\"dropdown-item\" href=\"nomina.php?id=' . $empleado['PKEmpleado'] . '&Dias=5\"><i class=\"fas fa-user-clock\"></i> Checador</a><a class=\"dropdown-item\" href=\"asistencia.php?id=' . $empleado['PKEmpleado'] . '&semana=' . $semana . '&turno=' . $empleado['FKTurno'] . '\"><i class=\"fas fa-file-invoice-dollar\"></i> Nomina</a> </div></div>';
    }*/
    $editar = '<span class=\"fas fa-clipboard-list pointer\"  data-toggle=\"modal\" data-target=\"#agregar_clave_tipo\" onclick=\"cargarDeduccion(' . $p['id'] . ');\" data-backdrop=\"static\" data-keyboard=\"false\"></span>';

    $table .= '{"concepto":"' . $p['codigo'] . ' - ' .$p['concepto'].'","clave":"' . $p['clave'] . '", "acciones":"' . $editar .'"},';

}
$table = substr($table, 0, strlen($table) - 1);
echo '{"data":[' . $table . ']}';