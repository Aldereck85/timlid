<?php
session_start();

include '../modelos/Notas.php';

$user = $_SESSION["PKUsuario"];
$empresa_id = $_SESSION["IDEmpresa"];

if ($_POST["accion"] === "index") {
    $id = $_POST["id"];

    $ver_notas = Notas::index($user, $id);
    if ($ver_notas == '') {
        $data[] = array();
        echo json_encode($data);
    } else {
        echo json_encode($ver_notas);
    }

} elseif ($_POST["accion"] === "verNota") {

    $nota = new Notas();

    $nota->id = $_POST['id'];
    $nota->user_id = $user;

    $show_nota = Notas::show($nota);
    if ($show_nota) {
        echo json_encode($show_nota);
    }

} else if ($_POST["accion"] === "agregarNota") {

    $nota = new Notas();


    $nota->contacto_id = $_POST['contacto_id'];
    $nota->descripcion = $_POST['nota'];
    $nota->color = $_POST['color'];
    $nota->user_id = $user;

    $add_nota = Notas::store($nota);
    if ($add_nota) {
        echo json_encode($add_nota);
    }

} else if ($_POST["accion"] === "actualizarNota") {

    $nota = new Notas();

    $nota->contacto_id = $_POST['contacto_id'];
    $nota->id = $_POST['nota_id'];
    $nota->descripcion = $_POST['nota'];
    $nota->color = $_POST['color'];
    $nota->user_id = $user;


    $update_nota = Notas::update($nota);
    if ($update_nota) {
        echo json_encode($update_nota);
    }

} else if ($_POST["accion"] === "eliminarNota") {
    $nota = new Notas();

    $nota->id = $_POST['id'];
    $nota->contacto_id = $_POST['contacto_id'];

    $delete_nota = Notas::destroy($nota);
    if ($delete_nota) {
        echo json_encode($delete_nota);
    }
}

?>