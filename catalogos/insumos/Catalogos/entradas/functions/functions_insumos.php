<?php
require_once('../../../../../include/db-conn.php');

$stmt = $conn->prepare('call spc_Entradas()');
$stmt->execute();
$table="";


while (($row = $stmt->fetch()) !== false) {
    //$edit = '<a class=\"btn btn-primary\" href=\"#\" data-toggle=\"modal\" data-target=\"#editar_puesto\" class=\"btn btn-primary\" onclick=\"obtenerIdPuestoEditar('.$row['PKPuesto'].');\"><i class=\"fas fa-edit\"></i> Editar</a>';
    //$delete ='<a class=\"btn btn-danger\" href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_puesto\" class=\"btn btn-danger\" onclick=\"obtenerIdPuestoEliminar('.$row['PKPuesto'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a>';

    

    $table.='{"id":"<label class=\"textTable\">'.$row['PKHistorialInsumos'].'</label>",
        "Identificador":"<label class=\"textTable\">'.$row['Identificador'].'</label>",
        "Nombre":"<label class=\"textTable\">'.$row['Nombre'].'</label>",
        "Costo":"<label class=\"textTable\">'.$row['Costo'].'</label>",
        "Cantidad entrante":"<label class=\"textTable\">'.$row['CantidadMovimiento'].'</label>",
        "Descripcion":"<label class=\"textTable\">'.$row['Descripcion'].'</label>",
        "Fecha de entrada":"<label class=\"textTable\">'.$row['FechaEdicion'].'</label>",
        "Usuario":"<label class=\"textTable\">'.$row['Nombres'].' '.$row['PrimerApellido'].' '.$row['SegundoApellido'].'</label><i><img class=\"btnEdit\" data-toggle=\"modal\" data-target=\"#editar_Insumo\" onclick=\"obtenerIdInsumoEditar('.$row['PKHistorialInsumos'].');\" src=\"../../../../img/timdesk/edit.svg\"></i>"},';

}
$table = substr($table,0,strlen($table)-1);
echo '{"data":['.$table.']}';
