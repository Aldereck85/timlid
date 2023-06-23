<?php
session_start();
if (isset($_SESSION["Usuario"])) {
    date_default_timezone_set('America/Mexico_City');
    require_once '../../../include/db-conn.php';
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $tipoPersona = $_POST['tipoPersona'];
    $isCreditoCheck = $_POST['isCreditoCheck'];
    $diascredito = $_POST['diascredito'] === "" ? 0 : $_POST['diascredito'];
    $limitepractico = $_POST['limitepractico'] === "" ? 0 : $_POST['limitepractico'];
    $idemp = $_SESSION["IDEmpresa"];
    $usuario = $_SESSION["PKUsuario"];
    $now = date("Y-m-d H:i:s");
//TODO: AÑADIR EL USUARIO Y LA FECHA
    try {
        $stmt = $conn->prepare('INSERT INTO proveedores (NombreComercial, Email, Dias_Credito, Monto_credito, tipo, empresa_id, estatus, usuario_creacion_id, usuario_edicion_id, created_at, updated_at, tipo_persona)
    VALUES(:nombre, :email, :dias_credito, :limite_credito, :tipo, :idempresa, :stat, :usuario_creat, :usuario_upt, :created, :updated, :tipo_persona)');
        $stmt->bindValue(':nombre', $nombre);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':dias_credito', $diascredito);
        $stmt->bindValue(':limite_credito', $limitepractico);
        $stmt->bindValue(':tipo', 1);
        $stmt->bindValue(':idempresa', $idemp);
        $stmt->bindValue(':stat', 1);
        $stmt->bindValue(':usuario_creat', $usuario);
        $stmt->bindValue(':usuario_upt', $usuario);
        $stmt->bindValue(':created', $now);
        $stmt->bindValue(':updated', $now);
        $stmt->bindValue(':tipo_persona', $tipoPersona);
        if ($stmt->execute()) {
            echo "exito";
        } else {
            echo "fallo";
        }
    } catch (\Throwable $th) {
        echo $th;
    }
}
