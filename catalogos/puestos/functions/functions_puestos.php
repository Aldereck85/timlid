<?php
require_once('../../../include/db-conn.php');

$stmt = $conn->prepare('SELECT * FROM puestos INNER JOIN tipo_pago_nomina ON PKPagoNomina = FKTipoPagoNomina');
$stmt->execute();
$table="";


while (($row = $stmt->fetch()) !== false) {
    //$edit = '<a class=\"btn btn-primary\" href=\"#\" data-toggle=\"modal\" data-target=\"#editar_puesto\" class=\"btn btn-primary\" onclick=\"obtenerIdPuestoEditar('.$row['PKPuesto'].');\"><i class=\"fas fa-edit\"></i> Editar</a>';
    //$delete ='<a class=\"btn btn-danger\" href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_puesto\" class=\"btn btn-danger\" onclick=\"obtenerIdPuestoEliminar('.$row['PKPuesto'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a>';

    $puesto = $row['Puesto'];

    $table.='{"id":"<label class=\"textTable\">'.$row['PKPuesto'].'","Puesto":"<label class=\"textTable\">'.$puesto.'","Tipo de pago":"<label class=\"textTable\">'.$row['TipoPago'].'</label><i><img class=\"btnEdit\" data-toggle=\"modal\" data-target=\"#editar_Puesto\" onclick=\"obtenerIdPuestoEditar('.$row['PKPuesto'].');\" src=\"../../img/timdesk/edit.svg\"></i>"},';
}
$table = substr($table,0,strlen($table)-1);
echo '{"data":['.$table.']}';
