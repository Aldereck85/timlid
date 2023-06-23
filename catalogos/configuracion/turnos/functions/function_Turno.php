<?php
session_start();
require_once '../../../../include/db-conn.php';

$stmt = $conn->prepare('SELECT PKTurno, Turno, DATE_FORMAT(Entrada,"%H:%i") Entrada, DATE_FORMAT(Salida,"%H:%i") Salida, Dias_de_trabajo, DATE_FORMAT(TiempoComida,"%H:%i") TComida, DATE_FORMAT(TIMEDIFF(Salida, Entrada), "%H:%i") as jornada, HorasTrabajo as hrsTrabajadas
from turnos WHERE estatus = 1 AND empresa_id = :empresa');
$stmt->bindValue(":empresa", $_SESSION['IDEmpresa']);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$table = "";
foreach ($result as $item) {
    $diasLaborales = json_decode($item["Dias_de_trabajo"], true);
    $diasLaboralesString = '';
    $diasTrabajados = 0;
    if ($diasLaborales['lunes']) {
        $diasLaboralesString .= 'Lunes ';
        $diasTrabajados++;
    }
    if ($diasLaborales['martes']) {
        $diasLaboralesString .= 'Martes ';
        $diasTrabajados++;
    }
    if ($diasLaborales['miercoles']) {
        $diasLaboralesString .= 'Miercoles ';
        $diasTrabajados++;
    }
    if ($diasLaborales['jueves']) {
        $diasLaboralesString .= 'Jueves ';
        $diasTrabajados++;
    }
    if ($diasLaborales['viernes']) {
        $diasLaboralesString .= 'Viernes ';
        $diasTrabajados++;
    }
    if ($diasLaborales['sabado']) {
        $diasLaboralesString .= 'Sabado ';
        $diasTrabajados++;
    }
    if ($diasLaborales['domingo']) {
        $diasLaboralesString .= 'Domingo';
        $diasTrabajados++;
    }
    /* //echo 'dias trabajados: ' . $diasTrabajados;
    //echo '<br>';
    $horas = intval(explode(":",$item['jornada'])[0]);
    $minutos = intval(explode(":",$item['jornada'])[1]);
    //echo 'horas: ' . $horas;
    //echo '<br>';
    //echo 'minutos: ' . $minutos;
    //echo '<br>';
    if($horas > 0 && $minutos > 0) {
        $minutos += $horas * 60;
    } else if($horas > 0 && !$minutos > 0) {
        $minutos = $horas * 60;
    }
    $horas_trabajo = (intval($diasTrabajados) * $minutos) / 60;
    //echo 'Horas trabajo: ' . $horas_trabajo;
    //echo '<br>'; */

    $horas_trabajo = intval($diasTrabajados) * intval($item['hrsTrabajadas']);

    $table .= '{"id":"<span class=\"textTable\">' . $item['PKTurno'] . '</span>",
      "Turno":"<span class=\"textTable\">' . $item['Turno'] . '</span>",
      "Entrada":"<span class=\"textTable\">' . $item['Entrada'] . '</span>",
      "Salida":"<span class=\"textTable\">' . $item['Salida'] . '</span>",
      "Dias":"<span class=\"textTable\">' . $diasLaboralesString . '</span>",
      "Horas/Semana":"<span class=\"textTableNumber\">' . $horas_trabajo . '</span>",
      "Acciones":"<i class=\"fas fa-edit pointer\" data-toggle=\"modal\" data-target=\"#editar_Turnos_46\" onclick=\"obtenerIdTurnoEditar(' . $item['PKTurno'] . ');\"></i>",
      "TiempoComida":"<span class=\"textTableNumber\">' . $item['TComida'] . '</span>"},';
}
$table = substr($table, 0, strlen($table) - 1);
echo '{"data":[' . $table . ']}';
?>
