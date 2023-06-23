<?php
session_start();
if (isset($_SESSION["Usuario"])) {
    if (isset($_POST['id'])) {
        require_once '../../../include/db-conn.php';
        $id = $_POST['id'];
        try {
            $getNota = $conn->prepare('SELECT
        bitacora_notas_clientes.PKBitacoraNotas,
        bitacora_notas_clientes.Nota
        FROM bitacora_notas_clientes
        WHERE bitacora_notas_clientes.PKBitacoraNotas = :id');
            $getNota->execute(array(':id' => $id));
            $nota = $getNota->fetch(PDO::FETCH_ASSOC);
            if (!$nota) {
                echo json_encode(array('status' => 'fail', 'message' => 'No hay registro con los datos enviados.'));
            } else {
                echo json_encode(array('status' => 'success', 'data' => $nota));
            }
        } catch (PDOException $ex) {
            var_dump($ex);
            echo json_encode(array('status' => 'fail', 'message' => 'Algo salio mal. Intentalo de nuevo más tarde.'));
        }
    } else {
        echo json_encode(array('status' => 'fail', 'message' => 'No se enviaron los datos correctamente'));
    }
} else {
    echo json_encode(array('status' => 'fail', 'message' => 'No tienes acceso a este recurso'));
}