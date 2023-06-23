<?php

session_start();

include '../modelos/loadCalendario.php';

$user = $_SESSION["PKUsuario"];
$empresa_id = $_SESSION["IDEmpresa"];

if (!empty($_POST["accion"])) {
    $accion = $_POST["accion"];
    if ($accion == "cargar_eventos_contacto") {
        $contacto_id = intval($_POST["id"]);
        $actividades_calendar = Calendar::cargarActividades($contacto_id, $empresa_id, $user);
        echo json_encode($actividades_calendar);
    } else if ($accion == "cargar_eventos_cliente") {
        $cliente_id = intval($_POST["id"]);
        $actividades_usuario = Calendar::cargarActividadesCliente($cliente_id, $empresa_id, $user);
        echo json_encode($actividades_usuario);
    } else if ($accion == "cargar_eventos_por_usuario") {
        $actividades_usuario = Calendar::cargarActividadPorUsuario($user);
        echo json_encode($actividades_usuario);
    }
}
?>