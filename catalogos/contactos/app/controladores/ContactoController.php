<?php
session_start();

include '../modelos/Contacto.php';
include '../requests/Contacto.php';

$user = $_SESSION["PKUsuario"];
$empresa_id = $_SESSION["IDEmpresa"];
$user_id = $_SESSION["PKUsuario"];


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
    } else if ($accion === "verClientes") {
        $estatus_id = 2;
        $index = Contacto::verClientes($empresa_id);
        echo json_encode($index);
    } else if ($accion === "verContacto") {
        $id = $_POST['id'];
        $show = Contacto::show1($empresa_id, $id);
        echo json_encode($show);
    } else if ($accion === "verCliente") {
        $id = $_POST['id'];
        $show = Contacto::cliente($empresa_id, $id);
        echo json_encode($show);
    } else if ($accion === "agregarContacto") {
        $contacto = new Contacto();
        $contacto->funcion = 'store';
        $contacto->usuario_creo_id = $user;
        $contacto->empresa_id = $empresa_id;
        $contacto->empresa = $_POST['empresa'];
        $contacto->empleado_id = $_POST['propietario'] ? $_POST['propietario'] : NULL;
        $contacto->medio_contacto_campania_id = $_POST['medio_contacto_campania_id'] ? $_POST['medio_contacto_campania_id'] : NULL;
        $contacto->nombre = trim($_POST['nombre']);
        $contacto->apellido = trim($_POST['apellido']);
        $contacto->puesto = $_POST['puesto'];
        $contacto->email = $_POST['email'];
        $contacto->telefono = $_POST['telefono'];
        $contacto->celular = $_POST['celular'];
        $contacto->sitio_web = $_POST['sitio_web'];
        $contacto->direccion = $_POST['direccion'];
        $contacto->fecha_aniversario = $_POST['aniversario'] ? $_POST['aniversario'] : NULL;
        $contacto->pais_id = $_POST['pais_id'] ? $_POST['pais_id'] : NULL;
        $contacto->estado_id = $_POST['estado_id'] ? $_POST['estado_id'] : NULL;
        $contacto->estatus_iniciativa_id = 1;

        //INFO: PREGUNTAR SOBRE ESTAS VALIDACIONES
        /* $validate_contact = ContactoRequests::rules($contacto, $empresa_id);
        if ($validate_contact == null) {
            $store = Contacto::store($contacto, $empresa_id);
            echo json_encode($store);
        } else {
            echo json_encode($validate_contact);
        } */
        $store = Contacto::store($contacto, $empresa_id);
        echo json_encode($store);
    } else if ($accion === "editarContacto") {
        $contacto = new Contacto();
        $contacto->id = $_POST['id'];
        $contacto->contacto_empresa_id = $_POST['contacto_empresa_id'];
        $contacto->empresa = $_POST['empresa'];
        $contacto->empleado_id = $_POST['propietario'] ? $_POST['propietario'] : NULL;
        $contacto->medio_contacto_campania_id = $_POST['medio_contacto_campania_id'] ? $_POST['medio_contacto_campania_id'] : NULL;
        $contacto->nombre = $_POST['nombre'];
        $contacto->apellido = $_POST['apellido'];
        $contacto->puesto = $_POST['puesto'];
        $contacto->email = $_POST['email'];
        $contacto->telefono = $_POST['telefono'];
        $contacto->celular = $_POST['celular'];
        $contacto->sitio_web = $_POST['sitioWeb'];
        $contacto->direccion = $_POST['direccion'];
        $contacto->fecha_aniversario = $_POST['aniversario'] ? $_POST['aniversario'] : NULL;
        $contacto->pais_id = $_POST['pais_id'] ? $_POST['pais_id'] : NULL;
        $contacto->estado_id = $_POST['estado_id'] ? $_POST['estado_id'] : NULL;
        $contacto->usuario_edito_id = $user;
        $update = Contacto::update($contacto, $empresa_id);
        echo json_encode($update);
    } else if ($accion === "eliminarContacto") {
        $contacto = new Contacto();
        $contacto->id = $_POST['id'];
        $contacto->motivo_declinar = $_POST['motivo'];
        $contacto->estatus_iniciativa_id = 2;
        $contacto->usuario_edito_id = $user;
        $contacto->funcion = 'destroy';
        $validate_destroy = ContactoRequests::rules($contacto, $empresa_id);

        if ($validate_destroy == null) {
            $destroy = Contacto::destroy($contacto);
            echo json_encode($destroy);
        } else {
            echo json_encode($validate_destroy);
        }
    } else if ($accion === "activarContacto") {
        $contacto = new Contacto();
        $contacto->id = $_POST['id'];
        $contacto->motivo_declinar = "";
        $contacto->estatus_iniciativa_id = 1;
        $contacto->usuario_edito_id = $user;
        $destroy = Contacto::activarContacto($contacto);
        echo json_encode($destroy);
    } else if ($accion === "agregarContactoProspecto") {
        $data['id'] = $_POST['id'];
        $data['nombre'] = $_POST['nombre'];
        $data['email'] = $_POST['email'];
        $data['celular'] = $_POST['celular'];
        $store = Contacto::addContactoProspecto($data);
        echo json_encode($store);
    } else if ($accion === "verContactosProspectos") {
        $id = $_POST["id"];
        $index = Contacto::verContactosProspectos($id);
        echo json_encode($index);
    } else if ($accion === "deleteContactoProspecto") {
        $id = $_POST["id"];
        $index = Contacto::deleteContactoProspecto($id);
        echo json_encode($index);
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
    } else if ($accion === "cargarMedios") {
        $medios = Contacto::loadMedios($empresa_id);
        echo json_encode($medios);
    } else if ($accion === "validateMedio") {
        $medio = $_POST['medio'];
        $respuesta = Contacto::validateMedio($medio, $empresa_id);
        echo json_encode($respuesta);
    } else if ($accion === "addMedio") {
        $medio = $_POST['medio'];
        $respuesta = Contacto::addMedio($medio, $empresa_id);
        echo json_encode($respuesta);
    } else if ($accion === "AgregarContactoClienteExistente") {
        $datosContacto = ['contacto_id' => $_POST['contacto_id'], 'cliente_id' => $_POST['cliente_id'], 'nombre' => $_POST['nombreExistente'], 'celular' => $_POST['celularExistente'], 'email' => $_POST['emailExistente']];
        $cliente = Contacto::addContactoClienteExistente($datosContacto, $empresa_id);
        echo json_encode($cliente);
    } else if ($accion === 'CrearCliente') {
        $datosCliente = ['nombreCliente' => $_POST['nombreCliente'], 'vendedorCliente' => $_POST['vendedorCliente'], 'razonSocCliente' => $_POST['razonSocCliente'], 'rfcCliente' => $_POST['rfcCliente'], 'regimenCliente' => $_POST['regimenCliente'], 'codigoPostalCliente' => $_POST['codigoPostalCliente'], 'paisCliente' => $_POST['paisCliente'], 'estadoCliente' => $_POST['estadoCliente'], 'webCliente' => $_POST['webCliente'], 'municipioCliente' => $_POST['municipioCliente'], 'coloniaCliente' => $_POST['coloniaCliente'], 'calleCliente' => $_POST['calleCliente'], 'noExteriorCliente' => $_POST['noExteriorCliente'], 'noInteriorCliente' => $_POST['noInteriorCliente']];
        $datosContacto = ['contacto_id' => $_POST['contacto_id'], 'nombreContacto' => $_POST['nombreContacto'], 'emailContacto' => $_POST['emailContacto'], 'celularContacto' => $_POST['celularContacto'], 'medioContacto' => $_POST['medioContacto'], 'apellidoContacto' => $_POST['apellidoContacto'], 'puestoContacto' => $_POST['puestoContacto'], 'telefonoContacto' => $_POST['telefonoContacto']];
        $cliente = Contacto::addCliente($datosCliente, $datosContacto, $empresa_id, $user_id);
        echo json_encode($cliente);
    } else if ($accion === 'CrearClienteNoFacturacion') {
        $datosCliente = ['razonSocCliente' => $_POST['razonSocCliente']];
        $datosContacto = ['contacto_id' => $_POST['contacto_id'], 'nombreContacto' => $_POST['nombreContacto'], 'emailContacto' => $_POST['emailContacto'], 'celularContacto' => $_POST['celularContacto'], 'medioContacto' => $_POST['medioContacto'], 'apellidoContacto' => $_POST['apellidoContacto'], 'puestoContacto' => $_POST['puestoContacto'], 'telefonoContacto' => $_POST['telefonoContacto']];
        $cliente = Contacto::addClienteNoFacturacion($datosCliente, $datosContacto, $empresa_id, $user_id);
        echo json_encode($cliente);
    }
}
