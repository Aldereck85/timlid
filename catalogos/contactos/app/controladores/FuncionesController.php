<?php


session_start();
include '../modelos/EventosCalendario.php';
include '../requests/Evento.php';
include 'EnviarCorreo.php';

$user = $_SESSION["PKUsuario"];
$empresa_id = $_SESSION["IDEmpresa"];
$username = $_SESSION["UsuarioNombre"];

if ($_POST['accion'] == 'cargarContactos') {


    $ver_contactos = Eventos::loadContactos($user);
    if ($ver_contactos) {
        echo json_encode($ver_contactos);
    }
} else if ($_POST['accion'] == 'cargarTipoActividades') {
    $ver_actividades = Eventos::loadActividades();
    if ($ver_actividades) {
        echo json_encode($ver_actividades);
    }
}else{
    echo 'no se cargo nada';
}