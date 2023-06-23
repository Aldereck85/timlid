<?php
session_start();
require_once('../../../include/db-conn.php');
/*Recupera las parcialidades de un cálculo*/ 

function GetEvn()
{
    include "../../../include/db-conn.php";
    $appUrl = $_ENV['APP_URL'] ?? 'https://app.timlid.com/';
    return ['server' => $appUrl];
}

$envVariables = GetEvn();
$appUrl = $envVariables['server'];
$comision = $_REQUEST["idComision"];


$stmt = $conn->prepare('SELECT a.id AS id_abono, 
                                a.fecha_abono AS fecha_abono, 
                                a.monto_abono AS monto_abono, 
                                u.nombre AS nombre_usuario
                        FROM comision_abonos a 
                            INNER JOIN comisiones c ON a.id_comision=c.id 
                            INNER JOIN empleados e ON c.id_empleado=e.PKEmpleado
                            INNER JOIN usuarios u ON a.id_usuario_registro=u.id
                        WHERE a.id_comision=:comision order by a.fecha_abono ASC');
$stmt->bindValue(":comision",$comision);
$stmt->execute();

$table="";
$html = "";
$i=0;

while (($row = $stmt->fetch()) !== false) {
    $i=$i+1;
    $html = '<a class=\"edit-tabs-371\" id=\"btnEliminarAbono\"><img src=\"../../img/timdesk/delete.svg\" style=\"cursor:pointer\" width=\"20px\" heigth=\"20px\" float=\"center\" data-toggle=\"tooltip\" title=\"Eliminar\" onclick=\"eliminarAbono('.$row['id_abono'].', '.$row['monto_abono'].', '.$i.')\"/></a>';


    $row['monto_abono'] = number_format($row['monto_abono'], 2, '.', ' ');

    //llena la tabla en formato json
    $table.= '{"Fecha":"'.date("d-m-Y", strtotime($row['fecha_abono'])).'",
        "Monto_abono":"$'.$row['monto_abono'].'",
        "Nombre_usuario":"'.$row['nombre_usuario'].'",
        "Eliminar":"'.$html.'"},'; 
   
}

$table = substr($table,0,strlen($table)-1);
echo '{"data":['.$table.']}';  
?>