<?php
require_once '../../../include/db-conn.php';
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare('SELECT * FROM datos_contacto_cliente WHERE FKCliente = :id');
    $stmt->execute(array(':id' => $id));
    $table = "";
    $datosContacto = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($datosContacto as $datoContacto) {
        $edit = '<i><img class=\"btnEdit\" data-toggle=\"modal\" data-target=\"#modalContacto\" onclick=\"obtenerDatosContacto(' . $datoContacto['PKContactoCliente'] . ');\" src=\"../../../img/timdesk/edit.svg\"></i>';

        $table .= '{"Nombre":"' . $datoContacto['Nombres'] . '","Apellido":"' . $datoContacto['Apellidos'] . '","Puesto":"' . $datoContacto['Puesto'] . '","Telefono":"' . $datoContacto['Telefono'] . '","Celular":"' . $datoContacto['Celular'] . '","Email":"' . $datoContacto['Email'] . $edit . '"},';
    }
    $table = substr($table, 0, strlen($table) - 1);
    echo '{"data":[' . $table . ']}';
}