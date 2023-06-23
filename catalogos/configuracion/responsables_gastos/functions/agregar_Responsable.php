<?php
session_start();
if (isset($_SESSION["Usuario"])) {
    require_once '../../../../include/db-conn.php';
    $fkempleado = $_POST['fkempleado'];
    try {
        $stmt = $conn->prepare('INSERT INTO relacion_tipo_empleado (empleado_id, tipo_empleado_id) VALUES (:fkempleado, 2)');
        $stmt->bindValue(':fkempleado', $fkempleado);
        if ($stmt->execute()) {
            echo "exito";
        } else {
            echo "fallo";
        }
    } catch (PDOException $ex) {
        echo $ex->getMessage();
    }
    $con = null;
    $db = null;
    $stmt = null;
}
