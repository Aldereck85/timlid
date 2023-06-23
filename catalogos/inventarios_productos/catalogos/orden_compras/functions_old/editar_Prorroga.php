<?php
    require_once('../../../include/db-conn.php');
    $id = $_POST['id'];
    $fecha = $_POST['fecha'];
    $stmt = $conn->prepare('UPDATE orden_compra SET Fecha_Deseada_Entrega= :fecha WHERE PKOrdenCompra = :id');
    $stmt->bindValue(':fecha',$fecha);
    $stmt->bindValue(':id',$id);
    $stmt->execute();

?>
