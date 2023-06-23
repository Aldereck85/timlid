<?php
session_start();

require_once('../../../include/db-conn.php');

try {
    $query = "CALL spi_Cotizacion_Vendida(?,?)";
    $stmt = $conn->prepare($query);
    if($stmt->execute(array($_REQUEST['idCotizacion'], $_SESSION['PKUsuario']))){
        $referencia = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($referencia);
    }else{
        return 'fallo';
    }

} catch (PDOException $e) {
    return "Error en Consulta: " . $e->getMessage();
}

?>