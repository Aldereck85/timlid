<?php
session_start();
if (isset($_SESSION["Usuario"])) {
    if (isset($_POST['id'])) {
        require_once '../../../include/db-conn.php';
        $id = $_POST['id'];
        try {
            $getContacto = $conn->prepare('SELECT
        datos_contacto_cliente.PKContactoCliente,
        datos_contacto_cliente.Nombres,
        datos_contacto_cliente.Apellidos,
        datos_contacto_cliente.Puesto,
        datos_contacto_cliente.Telefono,
        datos_contacto_cliente.Celular,
        datos_contacto_cliente.Email
        FROM datos_contacto_cliente
        WHERE datos_contacto_cliente.PKContactoCliente = :id');
            $getContacto->execute(array(':id' => $id));
            $contacto = $getContacto->fetch(PDO::FETCH_ASSOC);
            if (!$contacto) {
                echo json_encode(array('status' => 'fail', 'message' => 'No hay registro con los datos enviados.'));
            } else {
                echo json_encode(array('status' => 'success', 'data' => $contacto));
            }
        } catch (PDOException $ex) {
            echo json_encode(array('status' => 'fail', 'message' => 'Algo salio mal. Intentalo de nuevo más tarde.'));
        }
    } else {
        echo json_encode(array('status' => 'fail', 'message' => 'No se enviaron los datos correctamente'));
    }
} else {
    echo json_encode(array('status' => 'fail', 'message' => 'No tienes acceso a este recurso'));
}