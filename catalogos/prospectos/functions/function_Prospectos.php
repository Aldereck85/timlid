<?php
session_start();
require_once '../../../include/db-conn.php';

if (isset($_GET['toque'])) {
    $estatus = $_GET['toque'];
}

$stmt = $conn->prepare('SELECT
usuarios.Nombre,
clientes.PKCliente,
clientes.FKEstatus,
clientes.FechaAlta,
clientes.NombreComercial,
estatus_cliente.Estatus,
medios_contacto_clientes.MedioContactoCliente
FROM clientes
LEFT JOIN vendedores on vendedores.PKVendedor = clientes.FKVendedor
LEFT JOIN usuarios on usuarios.PKUsuario = vendedores.FKUsuario
LEFT JOIN estatus_cliente ON clientes.FKEstatus = estatus_cliente.PKEstatusCliente
LEFT JOIN medios_contacto_clientes on medios_contacto_clientes.PKMedioContactoCliente = clientes.FKMedioContactoCliente
WHERE clientes.FKEstatus = :estatus');
$stmt->bindValue(':estatus', $estatus);
$stmt->execute();
$prospectos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$toque1 = 1;
$toque2 = 2;
$toque3 = 3;
$toque4 = 4;
$prospectoInactivo = 5;

//href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Prospecto\" class=\"btn btn-primary\" onclick=\"obtenerIdProspectoEditar('.$prospecto['PKCliente'].');\"><i class=\"fas fa-edit\"></i> Editar
//href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_Prospecto\" class=\"btn btn-danger\" onclick=\"obtenerIdProspectoEliminar('.$prospecto['PKCliente'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar
$table = "";
foreach ($prospectos as $prospecto) {
    $estatusActual = $prospecto['FKEstatus'];
    $estatusTabla;

    switch ($estatusActual) {
        case 1:
            $estatusTabla = "<span class='badge badge-pill badge-success' style='font-size:15px;'>Toque 1</span>";
            break;
        case 2:
            $estatusTabla = "<span class='badge badge-pill badge-warning' style='font-size:15px;'>Toque 2</span>";
            break;
        case 3:
            $estatusTabla = "<span class='badge badge-pill badge-danger' style='font-size:15px;'>Toque 3</span>";
            break;
        case 4:
            $estatusTabla = "<span class='badge badge-pill badge-primary' style='font-size:15px;'>Cliente</span>";
            break;
        case 5:
            $estatusTabla = "<span class='badge badge-pill badge-dark' style='font-size:15px;'>Prospecto Inactivo</span>";
            break;
    }
    $fecha = new DateTime($prospecto['FechaAlta']);
    $fechaAlt = date_format($fecha, 'd/m/Y');
    $vendedor = $prospecto['Nombre'];
    $edit = '<i><img class=\"btnEdit\" data-toggle=\"modal\" data-target=\"#editar_prospecto\" onclick=\"obtenerDatosProspecto(' . $prospecto['PKCliente'] . ');\" src=\"../../img/timdesk/edit.svg\"></i>';
    /*$estatusBot= '<div class=\"dropdown\"><button class=\"btn btn-primary btnMargin dropdown-toggle\" type=\"button\" id=\"dropdownMenuButton\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\"><i class=\"fas fa-exclamation-circle\"></i> Estatus</button><div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuButton\"><a class=\"dropdown-item\" href=\"functions/cambiar_estatus.php?id='.$prospecto['PKCliente']."&estatus=".$toque1.'\">Toque 1</a><a class=\"dropdown-item\" href=\"functions/cambiar_estatus.php?id='.$prospecto['PKCliente']."&estatus=".$toque2.'\">Toque 2</a><a class=\"dropdown-item\" href=\"functions/cambiar_estatus.php?id='.$prospecto['PKCliente']."&estatus=".$toque3.'\">Toque 3</a><a class=\"dropdown-item\" href=\"functions/cambiar_estatus.php?id='.$prospecto['PKCliente']."&estatus=".$toque4.'\">Toque 4 (Cliente)</a><a class=\"dropdown-item\" href=\"functions/cambiar_estatus.php?id='.$prospecto['PKCliente']
    ."&estatus=".$prospectoInactivo.'\">Prospecto inactivo</a> </div></div>';*/
    $funciones = '<form action=\"functions/mostrarDatosProspectos.php\" method=\"POST\"><input type=\"hidden\" name=\"idProspecto\" value=\"' . $prospecto['PKCliente'] . '\"><div class=\"menu-tabla\"><div class=\"menu-tabla__item\"><img class=\"btnEdit\" src=\"../../img/timdesk/edit.svg\"><div class=\"menu-tabla__sub\"><button class=\"btn\"><i class=\"fas fa-edit\"></i> Editar</button><a href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_Prospecto\" onclick=\"obtenerIdProspectoEliminar(' . $prospecto['PKCliente'] . ')\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a></div></div></div></form>';

    $table .= '{"Id prospecto":"' . $prospecto['PKCliente'] . '","Nombre comercial":"' . $prospecto['NombreComercial'] . '","Medio de contacto":"' . $prospecto['MedioContactoCliente'] . '","Fecha de alta":"' . $fechaAlt . '","Vendedor":"' . $vendedor . $funciones . '"},';
}
$table = substr($table, 0, strlen($table) - 1);
echo '{"data":[' . $table . ']}';