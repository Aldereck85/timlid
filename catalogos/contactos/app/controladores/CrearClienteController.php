<?php

session_start();


include '../requests/CrearCliente.php';
include '../modelos/CrearCliente.php';
include '../modelos/Contacto.php';


$user_id = $_SESSION["PKUsuario"];
$empresa_id = $_SESSION["IDEmpresa"];


if ($_POST["accion"] == "CargarPropietarios") {
	$empresa_id = $_SESSION["IDEmpresa"];
	$select_seller = CrearCliente::loadSelectSeller($empresa_id);
	echo json_encode($select_seller);
} else if ($_POST["accion"] == "CargarEstados") {
	$select_state = CrearCliente::loadState();
	echo json_encode($select_state);
} else if ($_POST["accion"] === "VerificarContacto") {
	$id = $_POST['id'];
	$contacto_empresa_id = $_POST['contacto_empresa_id'];
	$crearCliente = Contacto::show($contacto_empresa_id, $id);
	if ($crearCliente) {
		echo json_encode($crearCliente);
	}
} else if ($_POST["accion"] === "CrearCliente") {
	$cliente = new CrearCliente();
	$cliente->NombreComercial = $_POST['nombreCliente'];
	$cliente->medio_contacto_id = $_POST['medioContacto'];
	$cliente->empleado_id = $_POST['vendedor'];
	$cliente->Telefono = $_POST['telefono'];
	$cliente->Email = $_POST['email'];
	if ($_POST['monto_credito'] == null) {
		$cliente->Monto_credito = 0;
	} else {
		$cliente->Monto_credito = $_POST['monto_credito'];
	}
	if ($_POST['dias_credito'] == null) {
		$cliente->Dias_credito = 0;
	} else {
		$cliente->Dias_credito = $_POST['dias_credito'];
	}
	$cliente->razon_social = $_POST['razon_social'];
	$cliente->rfc = $_POST['rfc'];
	$cliente->Municipio = $_POST['municipio'];
	$cliente->Colonia = $_POST['colonia'];
	$cliente->Calle = $_POST['calle'];
	$cliente->Numero_exterior = $_POST['numero_exterior'];
	$cliente->Numero_interior = $_POST['numero_interior'];
	$cliente->codigo_postal = $_POST['codigo_postal'];
	$cliente->pais_id = $_POST['pais'] = 146;
	$cliente->estado_id = $_POST['estado'];
	$cliente->empresa_id = $empresa_id;
	$cliente->usuario_creacion_id = $user_id;
	$cliente->funcion = 'store';
	$cliente->empresa_id = $empresa_id;
	//$validar_cliente = CrearClienteRequest::rules($cliente);
	//if ($validar_cliente == null) {
	$crear_cliente = CrearCliente::store($cliente);
	echo json_encode($crear_cliente);
	//} else {
	echo json_encode($validar_cliente);
	//}
} else if ($_POST["accion"] === "CrearContactosCliente") {
	$contacto_cliente = new CrearCliente();
	$contacto_cliente->PKCliente = $_SESSION['id_cliente'];
	$contacto_cliente->PKUsuario = $user_id;
	$contacto_cliente->empresa_id = $empresa_id;
	$contacto_cliente->Nombres =  $_POST['nombre'];
	$contacto_cliente->Apellidos = $_POST['apellido'];
	$contacto_cliente->Puesto = $_POST['puesto'];
	$contacto_cliente->Telefono = $_POST['telefono'];
	$contacto_cliente->Celular = $_POST['celular'];
	$contacto_cliente->Email = $_POST['email'];
	$contacto_cliente->PKContacto = $_POST['contacto_id'];
	$contacto_cliente->EmailFacturacion = $_POST['facturacion'];
	$contacto_cliente->EmailComplementoPago = $_POST['complemento'];
	$contacto_cliente->EmailAvisosEnvio = $_POST['avisos'];
	$contacto_cliente->EmailPagos = $_POST['pagos'];
	$contacto_cliente->funcion = 'storeContact';
	//$validar_contacto = CrearClienteRequest::rules($contacto_cliente);
	//if ($validar_contacto == null) {
	$crear_contacto_cliente = CrearCliente::storeContacto($contacto_cliente);
	echo json_encode($crear_contacto_cliente);
	//}else{
	//	echo json_encode($validar_contacto);
	//}
} else if ($_POST["accion"] == "CargarClientes") {
	$clientes = CrearCliente::loadClientes($empresa_id);
	echo json_encode($clientes);
} else if ($_POST["accion"] == "CargarMedios") {
	$empresa_id = $_SESSION["IDEmpresa"];
	$select_seller = Contacto::loadMedios($empresa_id);
	echo json_encode($select_seller);
} else if ($_POST["accion"] == "CargarRegimen") {
	$select_seller = Contacto::loadRegimen();
	echo json_encode($select_seller);
} else if ($_POST["accion"] == "CargarPaises") {
	$select_seller = Contacto::loadPaises();
	echo json_encode($select_seller);
}
