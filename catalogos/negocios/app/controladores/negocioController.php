<?php
session_start();

include '../modelos/Negocio.php';

$user = $_SESSION["PKUsuario"];
$empresa_id = $_SESSION["IDEmpresa"];

if ($_POST["accion"] === "guardarNegocio") {

    $negocio = new Negocio();
    $negocio->nombre = $_POST['nombre'];
    $negocio->valor = $_POST['valor'];
    $negocio->client_id = $_POST['empresaCliente'] === "" ? NULL : $_POST['empresaCliente'];
    $negocio->empleado_id = $_POST['empleado_id'];
    $negocio->etapa_usuario_id = $_POST['etapa_usuario_id'];
    $negocio->prioridad_id = $_POST['prioridad_id'];
    $negocio->usuario_id = $user;
    $negocio->contacto_id = $_POST['contacto'] === "" ? NULL : $_POST['contacto'];
    $negocio->prospecto_id = $_POST['empresaPros'] === "" ? NULL : $_POST['empresaPros'];
    $negocio->descripcion = $_POST['descripcion'] === "" ? NULL : $_POST['descripcion'];

    $nuevo_negocio = Negocio::storeNegocio($negocio);
    if ($nuevo_negocio) {
        echo json_encode($nuevo_negocio);
    }
} else if ($_POST["accion"] === "editarNegocio") {

    $negocio = new Negocio();
    $negocio->id = $_POST['id'];
    $negocio->nombre = $_POST['nombre'];
    $negocio->valor = $_POST['valor'];
    $negocio->client_id = $_POST['empresaCliente'] === "" ? NULL : $_POST['empresaCliente'];
    $negocio->empleado_id = $_POST['empleado_id'];
    $negocio->etapa_usuario_id = $_POST['etapa_usuario_id'];
    $negocio->prioridad_id = $_POST['prioridad_id'];
    $negocio->contacto_id = $_POST['contacto'] === "" ? NULL : $_POST['contacto'];
    $negocio->prospecto_id = $_POST['empresaPros'] === "" ? NULL : $_POST['empresaPros'];
    $negocio->descripcion = $_POST['descripcion'] === "" ? NULL : $_POST['descripcion'];

    $editar_negocio = Negocio::update($negocio);
    if($editar_negocio){
        echo json_encode($editar_negocio);
    }
} else if ($_POST["accion"] === "eliminarNegocio") {

    $eliminar_negocio = Negocio::destroy($_POST['id']);
    if($eliminar_negocio){
        echo json_encode($eliminar_negocio);
    }
}