<?php
session_start();

include '../modelos/Contacto.php';
$user = $_SESSION["PKUsuario"];
$empresa_id = $_SESSION["IDEmpresa"];


if (!empty($_POST["accion"])) {
    $accion = $_POST["accion"];
    if ($accion === "verContactosActivos") {

        $estatus_id = 1;
        $index = Contacto::index($user, $estatus_id, $empresa_id);
        echo json_encode($index);

    } else if ($accion === "verContactosInactivos") {

        $estatus_id = 2;
        $index = Contacto::index($user, $estatus_id, $empresa_id);
        echo json_encode($index);


    } else if ($accion === "verContacto") {

        $id = $_POST['id'];


        $show = Contacto::show($empresa_id, $id);
        echo json_encode($show);


    } else if ($accion === "agregarContacto") {


        $contacto = new Contacto();

        $contacto->nombre = trim($_POST['nombre']);
        $contacto->empresa = $_POST['empresa'];
        $contacto->apellido = trim($_POST['apellido']);
        $contacto->email = $_POST['email'];
        $contacto->puesto = $_POST['puesto'];
        $contacto->telefono = $_POST['telefono'];
        $contacto->celular = $_POST['celular'];
        $contacto->empleado_id = $_POST['propietario'];
        $contacto->estatus_iniciativa_id = $_POST['estatus_iniciativa_id'] = 1;
        $contacto->medio_contacto_campania_id = $_POST['medio_contacto_campania_id'];
        $contacto->estado_id = $_POST['estado_id'];
        $contacto->usuario_creo_id = $user;

        $contacto->funcion = 'store';

        $validate_contact = ContactoRequests::rules($contacto, $empresa_id);
        if ($validate_contact == null) {
            $store = Contacto::store($contacto, $empresa_id);
            echo json_encode($store);
        } else {
            echo json_encode($validate_contact);
        }
    } else if ($accion === "editarContacto") {

        $contacto = new Contacto();

        $contacto->id = $_POST['id'];
        $contacto->contacto_empresa_id = $_POST['contacto_empresa_id'];
        $contacto->nombre = $_POST['nombre'];
        //$contacto->empresa = $_POST['empresa'];
        $contacto->apellido = $_POST['apellido'];
        $contacto->email = $_POST['email'];
        $contacto->puesto = $_POST['puesto'];
        $contacto->telefono = $_POST['telefono'];
        $contacto->celular = $_POST['celular'];
        $contacto->empleado_id = $_POST['propietario'];
        $contacto->estatus_iniciativa_id = $_POST['estatus_iniciativa_id'] = 1;
        $contacto->medio_contacto_campania_id = $_POST['medio_contacto_campania_id'];
        $contacto->estado_id = $_POST['estado_id'];
        $contacto->usuario_edito_id = $user;


        $update = Contacto::update($contacto, $empresa_id);
        if ($update == true) {
            echo 'Contacto actualizado correctamente';
        }

    } else if ($accion === "eliminarContacto") {

        $contacto = new Contacto();

        $contacto->id = $_POST['id'];
        $contacto->motivo_declinar = $_POST['motivo'];
        $contacto->estatus_iniciativa_id = $_POST['estatus_iniciativa_id'] = 2;
        $contacto->usuario_edito_id = $user;

        $contacto->funcion = 'destroy';

        $validate_destroy = ContactoRequests::rules($contacto, $empresa_id);

        if ($validate_destroy == null) {
            $destroy = Contacto::destroy($contacto);
            echo json_encode($destroy);
        } else {
            echo json_encode($validate_destroy);
        }
    } else if ($accion === "cargarPropietarios") {

        $empresa_id = $_SESSION["IDEmpresa"];

        $propietarios = Contacto::loadContactos($empresa_id);
        echo json_encode($propietarios);
    } else if ($accion === "CargarEstados") {

        $estados = Contacto::loadEstados();
        echo json_encode($estados);
    } else if ($accion === "cargarEmpleados") {

        $empleados = Contacto::loadEmpleados($empresa_id);
        echo json_encode($empleados);
    }
}


