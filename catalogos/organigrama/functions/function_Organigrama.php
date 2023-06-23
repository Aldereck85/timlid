<?php
session_start();
require_once '../../../include/db-conn.php';

$stmt = $conn->prepare('SELECT o.PKOrganigrama, e.Nombres, e.PrimerApellido, e.SegundoApellido, e2.Nombres as PN, e2.PrimerApellido as AP, e2.SegundoApellido as AM, p.puesto
FROM organigrama as o
LEFT JOIN organigrama as o2  ON o.ParentNode = o2.PKOrganigrama
LEFT JOIN empleados as e  ON e.PKEmpleado = o.FKEmpleado
LEFT JOIN empleados as e2  ON e2.PKEmpleado = o2.FKEmpleado
LEFT JOIN datos_laborales_empleado as de ON de.FKEmpleado = e.PKEmpleado
LEFT JOIN puestos as p ON p.id = de.FKPuesto
WHERE o.empresa_id = :idempresa');
$stmt->bindValue(':idempresa', $_SESSION['IDEmpresa']);
$stmt->execute();
$organigramas = $stmt->fetchAll();
$table = "";

foreach ($organigramas as $organigrama) {

    $nombreEmpleado = $organigrama['Nombres'] . " " . $organigrama['PrimerApellido'] . " " . $organigrama['SegundoApellido'];

    $nombreJefeInmediato = $organigrama['PN'] . " " . $organigrama['AP'] . " " . $organigrama['AM'];

    $edit = '<i><img class=\"btnEdit\" data-toggle=\"modal\" data-target=\"#editar_Organigrama\" onclick=\"obtenerDatosOrganigramaEditar(' . $organigrama['PKOrganigrama'] . ');\" src=\"../../img/timdesk/edit.svg\"></i>';

    $delete = '<a class=\"btn btn-danger\" href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_Organigrama\" class=\"btn btn-danger\" onclick=\"obtenerIdOrganigramaEliminar(' . $organigrama['PKOrganigrama'] . ')\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a>';

    $table .= '{"PKOrganigrama":"' . $organigrama['PKOrganigrama'] . '","Empleado":"' . $nombreEmpleado . '","Jefe inmediato":"' . $nombreJefeInmediato . '","Acciones":"' . $edit . '","Puesto":"' . $organigrama['puesto'] . '"},';
}
$table = substr($table, 0, strlen($table) - 1);
echo '{"data":[' . $table . ']}';