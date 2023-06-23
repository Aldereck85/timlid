<?php
session_start();
if (isset($_SESSION["Usuario"])) {
    if (isset($_POST['id'])) {
        require_once '../../../include/db-conn.php';
        $id = $_POST['id'];
        try {
            $stmt = $conn->prepare("DELETE FROM bitacora_notas_clientes WHERE PKBitacoraNotas = :id");
            $stmt->execute(array(':id' => $id));
            if ($stmt->rowCount() === 0) {
                echo json_encode(array('status' => 'fail', 'message' => 'No tienes acceso a este recurso'));
            } else {
                echo json_encode(array('status' => 'success', 'message' => 'Se elimino el registro correctamente'));
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