<?php
//session_start();
session_start();
include '../modelos/EtapasNegocio.php';
$user = $_SESSION["PKUsuario"];
$empresa_id = $_SESSION["IDEmpresa"];
if ($_POST["accion"] === "guardarEtapa") {
    $data = new EtapasNegocio();
    $data->etapa = $_POST['etapa'];
    $nueva_etapa = EtapasNegocio::store($data, $empresa_id);
    if ($nueva_etapa) {
        echo json_encode($nueva_etapa);
    }
} else if ($_POST["accion"] === "cargarEtapas") {
    $etapas = EtapasNegocio::etapas($empresa_id);
    if ($etapas) {
        echo json_encode($etapas);
    }
} else if ($_POST["accion"] === "cargarColumnas") {
    $columnas = EtapasNegocio::getColumns($empresa_id);
    echo json_encode($columnas);
} else if ($_POST["accion"] === "cargarFilas") {
    $etapa_id = $_POST['id'];
    $filas = EtapasNegocio::getRows($user, $etapa_id, $empresa_id);
    echo json_encode($filas);
} else if ($_POST["accion"] === "updateSiguiente") {
    $data = new EtapasNegocio();
    $data->id = $_POST['id'];
    $data->etapa_empresa_usuario_id = $_POST['etapa_id'];
    $etapa = EtapasNegocio::updateSiguiente($data);
    echo json_encode($etapa);
} else if ($_POST["accion"] === "updateAnterior") {
    $data = new EtapasNegocio();
    $data->id = $_POST['id'];
    $data->etapa_empresa_usuario_id = $_POST['etapa_id'];
    $etapa = EtapasNegocio::updateAnterior($data);
    echo json_encode($etapa);
} else if ($_POST["accion"] === "cargarPropietarios") {
    $propietarios = EtapasNegocio::loadPropietarios($empresa_id);
    echo json_encode($propietarios);
} else if ($_POST["accion"] === "filtrarNegocioFechas") {
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $negocios_etapa = EtapasNegocio::getRowsDates($fecha_inicio, $fecha_fin);
    echo json_encode($negocios_etapa);
} else if ($_POST["accion"] === "filtrarNegociosByEmpleado") {
    $empleado_id = $_POST['empleado_id'];
    $usuario_id = $user;
    $negocioByEmpleado = EtapasNegocio::getRowsEmpleado($usuario_id, $empleado_id);
    echo json_encode($negocioByEmpleado);
} else if ($_POST['accion'] === "updateOrdenColumn") {
    //$id = $_POST['id'];
    $orden = $_POST['orden'];
    $ordenColumn = EtapasNegocio::updateColumn($orden, $empresa_id);
    echo json_encode($ordenColumn);
} else if ($_POST['accion'] === "cargarContactos") {
    $contactos_negocio = EtapasNegocio::cargarContactos($user, $empresa_id);
    echo json_encode($contactos_negocio);
} else if ($_POST['accion'] === "cargarContactosTipo") {
    $id = $_POST['id'];
    $tipo = $_POST['tipo'];
    $contactos_negocio = EtapasNegocio::cargarContactosTipo($tipo, $id);
    echo json_encode($contactos_negocio);
} else if ($_POST['accion'] === "cargarContactosTipoEditar") {
    $id = $_POST['id'];
    $tipo = $_POST['tipo'];
    $contacto = $_POST['contacto'];
    $contactos_negocio = EtapasNegocio::cargarContactosTipoEditar($tipo, $id, $contacto);
    echo json_encode($contactos_negocio);
} else if ($_POST['accion'] === "cierreGanado") {
    $negocio = $_POST['negocio'];
    $ganadoPerdido = $_POST['ganadoPerdido'];
    $motivo = $_POST['motivo'];
    echo json_encode(EtapasNegocio::cierreGanadoPerdido($negocio, $ganadoPerdido, $motivo));
} else if ($_POST['accion'] === 'activarDesactivarEtapa') {
    $etapa = $_POST['etapa_id'];
    $activarDesactivar = $_POST['activarDesactivar'];
    $empresa = $empresa_id;
    echo json_encode(EtapasNegocio::activarDesactivarEtapa($etapa, $activarDesactivar, $empresa));
}
?>