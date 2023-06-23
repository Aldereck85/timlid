<?php
require_once '../../../../../include/db-conn.php';
$stmt = $conn->prepare('SELECT * FROM proveedores INNER JOIN estados_federativos AS E ON proveedores.FKEstado = E.PKEstado INNER JOIN paises AS P ON proveedores.FKPais = P.PKPais');
$stmt->execute();
$table = "";
//href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Proveedor\" class=\"btn btn-primary\" onclick=\"obtenerIdProveedorEditar('.$row['PKProveedor'].');\"><i class=\"fas fa-edit\"></i> Editar
//href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_Proveedor\" class=\"btn btn-danger\" onclick=\"obtenerIdProveedorEliminar('.$row['PKProveedor'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar
while (($row = $stmt->fetch()) !== false) {
    $edit = '<a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Proveedor\" class=\"btn btn-primary\" onclick=\"obtenerIdProveedorEditar(' . $row['PKProveedor'] . ');\"><i class=\"fas fa-edit\"></i> Editar</a>';
    $delete = '<a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_Proveedor\" class=\"btn btn-danger\" onclick=\"obtenerIdProveedorEliminar(' . $row['PKProveedor'] . ')\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a>';

    $funciones = '<div class=\"dropdown\"><button class=\"btn btn-secondary btnMargin dropdown-toggle\" type=\"button\" id=\"dropdownMenuButton\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\"><i class=\"fas fa-tools\"></i> Operaciones</button><div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuButton\"><a class=\"dropdown-item\" href=\"directorio/cuentas/index.php?id=' . $row['PKProveedor'] . '\"><i class=\"fas fa-money-check-alt\"></i> Cuentas bancarias</a><a class=\"dropdown-item\" href=\"directorio/datos_contacto/index.php?id=' . $row['PKProveedor'] . '\"><i class=\"fas fa-id-card-alt\"></i> Datos de contacto</a><a class=\"dropdown-item\" href=\"directorio/domicilios_envio/index.php?id=' . $row['PKProveedor'] . '\"><i class=\"fas fa-address-book\"></i> Direcciones de envio</a>' . $edit . $delete . '</div></div>';

    if (preg_match('/[A-Z_\-0-9]/i', $row['Numero_Interior'])) {
        $imp = $row['Numero_Interior'];
    } else {
        $imp = "";
    }

    if ($row['Dias_Credito'] == 0 && $row['Limite_Credito'] == 0 || !(isset($row['Dias_Credito']) && isset($row['Limite_Credito']))) {
        $dias = "No ofrece crédito";
        $fecha = "No ofrece crédito";
    } else {
        $dias = $row['Dias_Credito'];
        $fecha = "$" . number_format($row['Limite_Credito'], 2);
    }

    /*$numInt = "S/N";
    if(isset($row['Numero_Interior']) && !empty($row['Numero_Interior'])){
    $numInt = $row['Numero_Interior'];
    }*/

    $table .= '{"Id":"<label class=\"textTable\">' . $row['PKProveedor'] . '","Razon Social":"<label class=\"textTable\">' . $row['Razon_Social'] . '","Nombre comercial":"<label class=\"textTable\">' . $row['Nombre_comercial'] . '","RFC":"<label class=\"textTable\">' . $row['RFC'] . '","Direccion":"<label class=\"textTable\">' . $row['Calle'] . ' No. ' . $row['Numero_exterior'] . ' ' . $imp . '","Colonia":"<label class=\"textTable\">' . $row['Colonia'] . '","Municipio":"<label class=\"textTable\">' . $row['Municipio'] . '","Pais":"<label class=\"textTable\">' . $row['Pais'] . '","Estado":"<label class=\"textTable\">' . $row['Estado'] . '","Codigo Postal":"<label class=\"textTable\">' . $row['CP'] . '","Dias de credito":"<label class=\"textTable\">' . $dias . '","Limite de credito":"<label class=\"textTable\">' . $fecha . '</label><i><img class=\"btnEdit\" data-toggle=\"modal\" data-target=\"#modalEditar\" onclick=\"obtenerIdProveedorEditar(' . $row['PKProveedor'] . ');\" src=\"../../../../img/timdesk/edit.svg\"></i>"},';
}
$table = substr($table, 0, strlen($table) - 1);
echo '{"data":[' . $table . ']}';