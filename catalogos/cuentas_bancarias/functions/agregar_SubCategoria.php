<?php
session_start();
date_default_timezone_set('America/Mexico_City');
if (isset($_SESSION["Usuario"])) {
    require_once '../../../include/db-conn.php';
    $categoria = $_POST['categoriaId'];
    $nombre = $_POST['nombreSubCat'];
    $idemp = $_SESSION["IDEmpresa"];
    $usuario = $_SESSION["PKUsuario"];
    $now = date("Y-m-d H:i:s");

    try {
        $stmt = $conn->prepare('INSERT INTO subcategorias_gastos (Nombre, FKCategoria)
        VALUES (:nombre, :categoria)');
        $stmt->bindValue(':nombre', $nombre);
        $stmt->bindValue(':categoria', $categoria);
        if ($stmt->execute()) {
            echo "exito";
        } else {
            echo "fallo";
        }
    } catch (PDOException $ex) {
        echo $ex->getMessage();
    }
}