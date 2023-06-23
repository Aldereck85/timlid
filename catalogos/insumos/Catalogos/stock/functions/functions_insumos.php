<?php
require_once('../../../../../include/db-conn.php');

$stmt = $conn->prepare('call spc_Insumos()');
$stmt->execute();
$table="";


while (($row = $stmt->fetch()) !== false) {
    //$edit = '<a class=\"btn btn-primary\" href=\"#\" data-toggle=\"modal\" data-target=\"#editar_puesto\" class=\"btn btn-primary\" onclick=\"obtenerIdPuestoEditar('.$row['PKPuesto'].');\"><i class=\"fas fa-edit\"></i> Editar</a>';
    //$delete ='<a class=\"btn btn-danger\" href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_puesto\" class=\"btn btn-danger\" onclick=\"obtenerIdPuestoEliminar('.$row['PKPuesto'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a>';

    $table.='{"id":"<label class=\"textTable\">'.$row['PKInsumosStock'].'</label>",
        "Identificador":"<label class=\"textTable\">'.$row['Identificador'].'</label>",
        "Nombre":"<label class=\"textTable\">'.$row['Nombre'].'</label>",
        "Tipo_de_insumo":"<label class=\"textTable\">'.$row['Tipo'].'</label>",
        "Unidad_de_medida":"<label class=\"textTable\">'.$row['UnidadMedida'].'</label>",
        "Cantidad_en_Existencia":"<label class=\"textTable\">'.$row['CantidadExistencia'].'</label>",
        "Cantidad_Minima":"<label class=\"textTable\">'.$row['CantidadMinima'].'</label>",
        "Descripcion":"<label class=\"textTable\">'.$row['DescripcionBreve'].'</label>",
        "Fecha_de_actualizacion":"<label class=\"textTable\">'.$row['Fecha_Actualizacion'].'</label>",
        "Estatus":"<label class=\"textTable\">'.$row['EstatusInsumo'].'</label>",
        "Usuario":"<label class=\"textTable\">'.$row['Nombres'].' '.$row['PrimerApellido'].' '.$row['SegundoApellido'].'</label><i><img class=\"btnEdit\" data-toggle=\"modal\" data-target=\"#editar_Insumo\" onclick=\"obtenerIdInsumoEditar('.$row['PKInsumosStock'].');\" src=\"../../../../img/timdesk/edit.svg\"></i>"},';

    
    /*if ( ((int) $row['CantidadExistencia'] == 0) && ($row['EstatusInsumo'] != 'Inactivo')){
        $table.='{"id":"<label class=\"textTable\">'.$row['PKInsumosStock'].'</label>",
            "Identificador":"<label class=\"textTable\">'.$row['Identificador'].'</label>",
            "Nombre":"<label class=\"textTable\">'.$row['Nombre'].'</label>",
            "Tipo_de_insumo":"<label class=\"textTable\">'.$row['Tipo'].'</label>",
            "Unidad_de_medida":"<label class=\"textTable\">'.$row['UnidadMedida'].'</label>",
            "Cantidad_en_Existencia":"<label class=\"textTable\">'.$row['CantidadExistencia'].'</label>",
            "Cantidad_Minima":"<label class=\"textTable\">'.$row['CantidadMinima'].'</label>",
            "Descripcion":"<label class=\"textTable\">'.$row['DescripcionBreve'].'</label>",
            "Fecha_de_actualizacion":"<label class=\"textTable\">'.$row['Fecha_Actualizacion'].'</label>",
            "Estatus":"<label class=\"textTable\">'.$row['EstatusInsumo'].'</label>",
            "Usuario":"<label class=\"textTable\">'.$row['Nombres'].' '.$row['PrimerApellido'].' '.$row['SegundoApellido'].'</label><i><img class=\"btnEdit\" data-toggle=\"modal\" data-target=\"#editar_Insumo\" onclick=\"obtenerIdInsumoEditar('.$row['PKInsumosStock'].');\" src=\"../../../../img/timdesk/edit.svg\"></i>"},';
    }
    else if ( ((int ) $row['CantidadMinima'] >= (int) $row['CantidadExistencia']) && ($row['EstatusInsumo'] != 'Inactivo')){
        $table.='{"id":"<label class=\"textTable\">'.$row['PKInsumosStock'].'</label>",
            "Identificador":"<label class=\"textTable\">'.$row['Identificador'].'</label>",
            "Nombre":"<label class=\"textTable\">'.$row['Nombre'].'</label>",
            "Tipo_de_insumo":"<label class=\"textTable\">'.$row['Tipo'].'</label>",
            "Unidad_de_medida":"<label class=\"textTable\">'.$row['UnidadMedida'].'</label>",
            "Cantidad_en_Existencia":"<label class=\"textTable\">'.$row['CantidadExistencia'].'</label>",
            "Cantidad_Minima":"<label class=\"textTable\">'.$row['CantidadMinima'].'</label>",
            "Descripcion":"<label class=\"textTable\">'.$row['DescripcionBreve'].'</label>",
            "Fecha_de_actualizacion":"<label class=\"textTable\">'.$row['Fecha_Actualizacion'].'</label>",
            "Estatus":"<label class=\"textTable\">'.$row['EstatusInsumo'].'</label>",
            "Usuario":"<label class=\"textTable\">'.$row['Nombres'].' '.$row['PrimerApellido'].' '.$row['SegundoApellido'].'</label><i><img class=\"btnEdit\" data-toggle=\"modal\" data-target=\"#editar_Insumo\" onclick=\"obtenerIdInsumoEditar('.$row['PKInsumosStock'].');\" src=\"../../../../img/timdesk/edit.svg\"></i>"},';
    }
    else if ( $row['EstatusInsumo'] == 'Inactivo'){
        $table.='{"id":"<label class=\"textTable\">'.$row['PKInsumosStock'].'</label>",
            "Identificador":"<label class=\"textTable\">'.$row['Identificador'].'</label>",
            "Nombre":"<label class=\"textTable\">'.$row['Nombre'].'</label>",
            "Tipo_de_insumo":"<label class=\"textTable\">'.$row['Tipo'].'</label>",
            "Unidad_de_medida":"<label class=\"textTable\">'.$row['UnidadMedida'].'</label>",
            "Cantidad_en_Existencia":"<label class=\"textTable\">'.$row['CantidadExistencia'].'</label>",
            "Cantidad_Minima":"<label class=\"textTable\">'.$row['CantidadMinima'].'</label>",
            "Descripcion":"<label class=\"textTable\">'.$row['DescripcionBreve'].'</label>",
            "Fecha_de_actualizacion":"<label class=\"textTable\">'.$row['Fecha_Actualizacion'].'</label>",
            "Estatus":"<label class=\"textTable\">'.$row['EstatusInsumo'].'</label>",
            "Usuario":"<label class=\"textTable\">'.$row['Nombres'].' '.$row['PrimerApellido'].' '.$row['SegundoApellido'].'</label><i><img class=\"btnEdit\" data-toggle=\"modal\" data-target=\"#editar_Insumo\" onclick=\"obtenerIdInsumoEditar('.$row['PKInsumosStock'].');\" src=\"../../../../img/timdesk/edit.svg\"></i>"},';
    }
    else{
        $table.='{"id":"<label class=\"textTable\">'.$row['PKInsumosStock'].'</label>",
            "Identificador":"<label class=\"textTable\">'.$row['Identificador'].'</label>",
            "Nombre":"<label class=\"textTable\">'.$row['Nombre'].'</label>",
            "Tipo_de_insumo":"<label class=\"textTable\">'.$row['Tipo'].'</label>",
            "Unidad_de_medida":"<label class=\"textTable\">'.$row['UnidadMedida'].'</label>",
            "Cantidad_en_Existencia":"<label class=\"textTable\">'.$row['CantidadExistencia'].'</label>",
            "Cantidad_Minima":"<label class=\"textTable\">'.$row['CantidadMinima'].'</label>",
            "Descripcion":"<label class=\"textTable\">'.$row['DescripcionBreve'].'</label>",
            "Fecha_de_actualizacion":"<label class=\"textTable\">'.$row['Fecha_Actualizacion'].'</label>",
            "Estatus":"<label class=\"textTable\">'.$row['EstatusInsumo'].'</label>",
            "Usuario":"<label class=\"textTable\">'.$row['Nombres'].' '.$row['PrimerApellido'].' '.$row['SegundoApellido'].'</label><i><img class=\"btnEdit\" data-toggle=\"modal\" data-target=\"#editar_Insumo\" onclick=\"obtenerIdInsumoEditar('.$row['PKInsumosStock'].');\" src=\"../../../../img/timdesk/edit.svg\"></i>"},';
    }*/
   
}
$table = substr($table,0,strlen($table)-1);
echo '{"data":['.$table.']}';
