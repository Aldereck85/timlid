<?php
session_start();
if (isset($_SESSION["Usuario"])) {
    if (isset($_POST['id']) && isset($_POST['nota']) && isset($_POST['nota'])) {
        $id = $_POST['id'];
        $nota = $_POST['nota'];
        date_default_timezone_set("America/Mexico_City");
        $fecha = date('y/m/d H:i:s');
        require_once '../../../include/db-conn.php';
        try {
            /* QUERY PARA EDITAR EL CONTACTO */
            $stmt = $conn->prepare('UPDATE bitacora_notas_clientes SET Nota = :nota, FechaModificacion = :fecha WHERE PKBitacoraNotas  = :id');
            $stmt->execute(array(':nota' => $nota, ':fecha' => $fecha, ':id' => $id));
            if ($stmt->rowCount() <= 0) {echo json_encode(array('status' => 'fail', 'message' => 'No se encontro el registro requerido'));
            } else {
                echo json_encode(array('status' => 'success', 'message' => 'Datos actualizados correctamente'));
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