<?php
session_start();
if (isset($_SESSION["Usuario"])) {
    require_once '../../../include/db-conn.php';
    if ((isset($_POST['idProspecto']) && !empty($_POST['idProspecto'])) && (isset($_POST['nombreComercial']) && !empty($_POST['nombreComercial'])) && (isset($_POST['medioContacto']) && !empty($_POST['medioContacto']))) {
        $id = $_POST['idProspecto'];
        $nombre = $_POST['nombreComercial'];
        $medio = $_POST['medioContacto'];
        try {
            $stmt = $conn->prepare('UPDATE clientes set  NombreComercial = :nombre, FKMedioContactoCliente = :medio WHERE PKCliente = :id');
            $stmt->bindValue(':nombre', $nombre);
            $stmt->bindValue(':medio', $medio);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->rowCount() === 0) {
                echo json_encode(array('status' => 'fail', 'message' => 'No se encontro registro con los datos enviados'));
            } else {
                echo json_encode(array('status' => 'success', 'message' => 'Registro actualizado'));
            }
        } catch (PDOException $ex) {
            echo $ex->getMessage();
            echo json_encode(array('status' => 'fail', 'message' => 'Algo salio mal por favor intentalo más tarde'));
        }
    } else {
        echo json_encode(array('status' => 'fail', 'message' => 'No se enviaron los datos necesarios'));
    }
} else {
    echo json_encode(array('status' => 'fail', 'message' => 'No tienes acceso a este recurso'));
}