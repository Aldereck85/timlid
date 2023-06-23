<?php

include_once("clases.php");
$array = "";
$empresa_id = $_SESSION["IDEmpresa"];
if (isset($_REQUEST['clase']) && isset($_REQUEST['funcion'])) {

	switch ($_REQUEST['clase']) {

		case "get_data":
			$data = new get_data();
			switch ($_REQUEST['funcion']) {
				case "get_empleados": //dato del proyecto que se eligió
					$json = $data->getEmpleados($empresa_id); //Guardando el return de la función
					echo $json; //Retornando el resultado al ajax
					return;
					break;
				case "lista_columnas": //dato del proyecto que se eligió
					$json = $data->lista_columnas(); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
					break;
				case "orden_columnas": //dato del proyecto que se eligió
					$json = $data->orden_columnas(); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
					break;
				case "orden_datos":
					$sort = $_REQUEST["sort"];
					$indice = $_REQUEST["indice"];
					$array = $_REQUEST["array"];
					$json = $data->orden_datos($sort, $indice, $array); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
					break;
				case "info_columnas": //dato del proyecto que se eligió
					if (isset($_REQUEST["array"]))
						$array = $_REQUEST["array"];
					$json = $data->info_columnas($array); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
					break;
				case "obtener_estadoCivilSeleccionable":
					$json = $data->selectCivilState(); //Guardando el return de la función
					echo json_encode($json);
					break;
				case "obtener_statusSeleccionable":
					$json = $data->selectStatus(); //Guardando el return de la función
					echo json_encode($json);
					break;

				case "obtener_empleados":
					$json = $data->getEmployer(); //Guardando el return de la función
					echo json_encode($json);
					break;

				case "obtener_empleado":
					$id = $_REQUEST['data'];
					$json = $data->getEmployer($id); //Guardando el return de la función
					echo json_encode($json);
					break;

				case "obtener_ids":
					$json = $data->getId(); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					break;

				case "obtener_nombre":
					$id = $_REQUEST["id"];
					$json = $data->getName($id); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					break;

				case "obtener_apellido_paterno":
					$id = $_REQUEST["id"];
					$json = $data->getFirstLastName($id); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					break;

				case "obtener_apellido_materno":
					$id = $_REQUEST["id"];
					$json = $data->getSecondLastName($id); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					break;

				case "obtener_nombreCompleto":
					$id = $_REQUEST["datos"];
					$json = $data->getNameComplete($id); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					break;

				case "obtener_estado_civil":
					$id = $_REQUEST["id"];
					$json = $data->getMaritalStatus($id); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					break;

				case "obtener_genero":
					$id = $_REQUEST["id"];
					$json = $data->getGender($id); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					break;

				case "obtener_direccion":
					$id = $_REQUEST["id"];
					$json = $data->getAddress($id); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					break;

				case "obtener_estado":
					$id = $_REQUEST["id"];
					$json = $data->getState($id); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					break;

				case "obtener_ciudad":
					$id = $_REQUEST["id"];
					$json = $data->getCity($id); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					break;

				case "obtener_colonia":
					$id = $_REQUEST["id"];
					$json = $data->getColony($id); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					break;

				case "obtener_cp":
					$id = $_REQUEST["id"];
					$json = $data->getCP($id); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					break;

				case "obtener_curp":
					$id = $_REQUEST["id"];
					$json = $data->getCurp($id); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					break;

				case "obtener_rfc":
					$id = $_REQUEST["id"];
					$json = $data->getRfc($id); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					break;

				case "obtener_nacimiento":
					$id = $_REQUEST["id"];
					$json = $data->getBirth($id); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					break;

				case "obtener_telefono":
					$id = $_REQUEST["id"];
					$json = $data->getTelephone($id); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					break;

				case "obtener_estatus":
					$id = $_REQUEST["id"];
					$json = $data->getStatus($id); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					break;

				case "obtener_datosLaborales":
					$id = $_REQUEST["data"];
					$json = $data->getDataLaboral($id); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					break;

				case "obtener_turnos":
					$json = $data->getTurns(); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					break;

				case "obtener_locaciones":
					$json = $data->getLocation(); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					break;

				case "obtener_puestos":
					$json = $data->getJobPositions(); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					break;

				case "obtener_tipo_contrato":
					$json = $data->getContractType(); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					break;

				case "obtener_area_departamento":
					$json = $data->getAreaDepartamento(); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					break;

				case "obtener_riesgo_puesto":
					$json = $data->getRiskPosition(); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					break;

				case "obtener_tipo_regimen":
					$json = $data->getRegimeType(); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					break;

				case "obtener_periodos":
					$json = $data->getPeriods(); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					break;

				case "obtener_empresas":
					$json = $data->getCompany(); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					break;

				case "obtener_estadosFederativos":
					$json = $data->getFedStates(); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					break;

				case "obtener_RegimenFiscal":
					$json = $data->getTaxRegime(); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					break;

				case "obtener_bancos":
					$json = $data->getBanks(); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					break;

				case "obtener_roles_empleados":
					$json = $data->getRolesEmpleados(); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					break;

				case "obtener_datosMedicos":
					$id = $_REQUEST["data"];
					$json = $data->getDataMedical($id); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					break;
				case "obtener_datosBancarios":
					$id = $_REQUEST["data"];
					$json = $data->getDataBank($id); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					break;
				case "obtener_Roles":
					$id = $_REQUEST["data"];
					$json = $data->getDatosRolesEmpleados($id); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					break;
				case "obtener_DatatablesRoles":
					$id = $_REQUEST["data"];
					$json = $data->getDatatablesRolesEmpleados($id); //Guardando el return de la función
					echo $json; //Retornando el resultado al ajax
					break;
				case "empleadosBaja":
					$json = $data->getEmpleadosBaja(); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					break;
				case "obtener_forma_pago":
					$json = $data->getFormasPago(); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					break;
			}
			break;

		case "data_order":
			$order = new data_order();
			switch ($_REQUEST['funcion']) {
				case "columnOrder": //dato del proyecto que se eligió
					if (isset($_REQUEST["ordenArray"]))
						$array = $_REQUEST["ordenArray"];
					$json = $order->columnOrder($array); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
					break;
			}
			break;

		case "update_data":
			$update = new update_data();
			switch ($_REQUEST['funcion']) {
				case "update_check_column": //dato del proyecto que se eligió
					$id_column = $_REQUEST["id_column"];
					$flag = $_REQUEST["flag"];
					$json = $update->update_check_column($id_column, $flag); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
					break;

				case "editar_elemento": //dato del proyecto que se eligió
					$id_elemento = $_REQUEST["id_elemento"];
					$tipo = $_REQUEST["tipo"];
					$json = $update->editar_elemento($id_elemento, $tipo); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
					break;

				case "actualizar_datosPersonales":
					$array = $_REQUEST['datos'];
					$json = $update->update_personalData($array);
					echo json_encode($json);
					return;
					break;

				case "actualizar_datosLaborales":
					$datos = $_REQUEST['datos'];
					$json = $update->update_laboralData($datos);
					echo $json;
					return;
					break;

				case "actualizar_datosMedicos":
					$array = $_REQUEST['datos'];
					$json = $update->update_medicalData($array);
					echo $json;
					return;
					break;

				case "actualizar_datosBancarios":
					$array = $_REQUEST['datos'];
					$json = $update->update_bancariosData($array);
					echo $json;
					return;
					break;
			}
			break;

		case "buscar_data": //Buscar info en ddbb
			$buscar = new buscar_data(); //creando un nuevo objeto que referencia a la clase buscar data
			switch ($_REQUEST['funcion']) {
				case "buscar_empleado":
					$inputValue = $_REQUEST['usuarioInput'];
					$array = $_REQUEST['Array'];
					$json = $buscar->searchEmployer($inputValue, $array);
					echo json_encode($json);
					return;
			}
			break;

		case "delete_data": //Buscar info en ddbb
			$delete = new delete_data(); //creando un nuevo objeto que referencia a la clase buscar data
			switch ($_REQUEST['funcion']) {
				case "delete_rol":
					$array = array();
					$array['idRol'] = $_REQUEST['idRol'];
					$json = $delete->delete_RolEmpleado($array);
					echo $json;
					return;
			}
			break;

		case "save_data":

			$save = new save_data();
			switch ($_REQUEST['funcion']) {

				case "nueva_area_departamento":
					$nombre = $_REQUEST["name"];
					$json = $save->saveAreaDepartamento($nombre); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					break;

				case "guardar_datosPersonales":
					$array = $_REQUEST['datos'];
					$json = $save->save_personalData($array);
					echo json_encode($json);
					return;
					break;

				case "guardar_datosLaborales":
					$datos = $_REQUEST['datos'];
					$json = $save->save_laboralData($datos);
					echo json_encode($json);
					return;
					break;

				case "guardar_datosMedicos":
					$array = $_REQUEST['datos'];
					$json = $save->save_medicalData($array);
					echo json_encode($json);
					return;
					break;

				case "guardar_datosBancarios":
					$array = $_REQUEST['datos'];
					$json = $save->save_bankData($array);
					echo json_encode($json);
					return;
					break;

				case "guardar_rolEmpleado":
					$array = array();
					$array['idRol'] = $_REQUEST['idRol'];
					$array['idEmpleado'] = $_REQUEST['idEmpleado'];
					$json = $save->save_rolesEmpleado($array);
					echo json_encode($json);
					return;
					break;
			}
			break;
	}
}
