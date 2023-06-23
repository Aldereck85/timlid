<?php
session_start();
if (isset($_SESSION["Usuario"])) {
    if (isset($_POST['id']) && isset($_POST['nombres']) && isset($_POST['apellidos']) && isset($_POST['puesto']) && isset($_POST['telefono']) && isset($_POST['celular']) && isset($_POST['email'])) {
        $id = $_POST['id'];
        $nombres = $_POST['nombres'];
        $apellidos = $_POST['apellidos'];
        $puesto = $_POST['puesto'];
        $telefono = $_POST['telefono'];
        $celular = $_POST['celular'];
        $email = $_POST['email'];
        require_once '../../../include/db-conn.php';
        /* QUERY PARA EDITAR EL CONTACTO */
        $stmt = $conn->prepare('UPDATE datos_contacto_cliente SET
    Nombres = :nombres,
    Apellidos = :apellidos,
    Puesto = :puesto,
    Telefono = :telefono,
    Celular = :celular,
    Email = :email
    WHERE PKContactoCliente = :id');
        $stmt->execute(array(':nombres' => $nombres, ':apellidos' => $apellidos, ':puesto' => $puesto, ':telefono' => $telefono, ':celular' => $celular, ':email' => $email, ':id' => $id));
        if ($stmt->rowCount() <= 0) {
            echo json_encode(array('status' => 'fail', 'message' => 'No se encontro el registro requerido'));
        } else {
            echo json_encode(array('status' => 'success', 'message' => 'Datos actualizados correctamente'));
        }
    } else {
        echo json_encode(array('status' => 'fail', 'message' => 'No se enviaron los datos correctamente'));
    }
} else {
    echo json_encode(array('status' => 'fail', 'message' => 'No tienes acceso a este recurso'));
}