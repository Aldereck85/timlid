<?php
//include_once("conexion.php");//Incluyendo el archivo de la clase conexión
include_once("clases.php");
if (isset($_REQUEST['clase']) && isset($_REQUEST['funcion'])) { //Requiriendo de ajax la clase y la función

	switch ($_REQUEST['clase']) { //clase

		case "admin_data": //clase "admin_data" //Esta es la primer clase del archivo.
			$data = new admin_data(); //nuevo objeto de admin_data (para que use sus funciones)
			switch ($_REQUEST['funcion']) { //función

				case "getProject": //dato del proyecto que se eligió
					$id = $_REQUEST['id'];
					$json = $data->getProject($id); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
					break;

				case "getProjectLite": //dato del proyecto que se eligió
					$lite = $_REQUEST['lite'];
					$json = $data->getProjectLite($lite); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
					break;

				case "getLevels": //Etapas
					$id = $_REQUEST['id'];
					$json = $data->getLevels($id); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
					break;

				case "getTask": //tareas
					$id = $_REQUEST['id'];
					$json = $data->getTask($id); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
					break;

				case "getInfo": //información de las tareas
					$id = $_REQUEST['id'];
					$array = $_REQUEST['array'];
					$json = $data->getInfo($array, $id); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
					break;

				case "getColor": //Color de Etapas
					$id = $_REQUEST['id'];
					$color = $_REQUEST['color'];
					$json = $data->getColor($id, $color); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
					break;

				case "getFecha": //Color de Etapas
					$id = $_REQUEST['id'];
					$fecha = $_REQUEST['fecha'];
					$json = $data->getFecha($id, $fecha); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
					break;

				case "getColorColumna": //Color de Etapas
					$id_estado = $_REQUEST['id_estado'];
					$id_columna = $_REQUEST['id_columna'];
					$json = $data->getColorColumna($id_estado, $id_columna); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
					break;

				case "setColorTarea": //Color de Etapas
					$id_estado = $_REQUEST['id_estado'];
					$id_color = $_REQUEST['id_color'];
					$flag = $_REQUEST['flag'];
					$json = $data->setColorTarea($id_estado, $id_color, $flag); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
					break;

				case "setHipervinculo": //Editar hipervinculo
					$valor1 = $_REQUEST['valor1'];
					$valor2 = $_REQUEST['valor2'];
					$id = $_REQUEST['id'];
					$json = $data->setHipervinculo($valor1, $valor2, $id); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
					break;

				case "setPhoneNumber": //Editar hipervinculo
					$number = $_REQUEST['number'];
					$id = $_REQUEST['id'];
					$json = $data->setPhoneNumber($number, $id); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
					break;
			}
			break;

		case "add_data": //Agregar columnas / tareas / etapas
			$add = new add_data(); //nuevo objeto de add_data
			switch ($_REQUEST['funcion']) { //función

				case "addColumn": //agregar columna al proyecto
					$id = $_REQUEST['id'];
					$tipo = $_REQUEST['tipo'];
					$tabla = $_REQUEST['tabla'];
					$json = $add->addColumn($id, $tipo, $tabla); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
					break;

				case "addTask": //agregar tarea al proyecto
					$id_etapa = $_REQUEST['id_etapa'];
					$id_proyecto = $_REQUEST['id_proyecto'];
					$json = $add->addTask($id_etapa, $id_proyecto); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
					break;

				case "addGroup":
					$id_proyecto = $_REQUEST['id_proyecto'];
					$json = $add->addGroup($id_proyecto); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
					break;

				case "add_label":
					$PKColumnaProyecto = $_REQUEST['PKColumnaProyecto'];
					$id_proyecto = $_REQUEST['id_proyecto'];
					$color = $_REQUEST['color'];
					$json = $add->add_label($id_proyecto, $PKColumnaProyecto, $color); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
					break;

				case "addMenud": //funcion para agregar nuevas etiquetas en el menu desplegable en DDBB
					$nombre = $_REQUEST['valor'];
					$id = $_REQUEST['id'];
					$id_col = $_REQUEST['idcol'];
					$json = $add->addETiqueta($nombre, $id, $id_col); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
					break;

				case "addMenudSelected": //funcion para guardar en DDBB las etiquetas seleccionadas
					$id_menu = $_REQUEST['id'];
					$array = $_REQUEST['array'];
					$json = $add->addETiquetaSelected($id_menu, $array); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
					break;

				case "add_sub": //funcion para guardar en DDBB las sub tareas
					$id_tarea = $_REQUEST['id_tarea'];
					$json = $add->add_sub($id_tarea); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
					break;
			}
			break;

		case "edit_data":
			$edit = new edit_data(); //nuevo objeto de add_data
			switch ($_REQUEST['funcion']) { //función
				case "editGroup": //agregar columna al proyecto
					$id = $_REQUEST['id_etapa'];
					$nombre = $_REQUEST['nombre'];
					$json = $edit->editGroup($id, $nombre); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
					break;

				case "editTask": //agregar columna al proyecto
					$id = $_REQUEST['id_tarea'];
					$nombre = $_REQUEST['nombre'];
					$json = $edit->editTask($id, $nombre);
					echo json_encode($json);
					return;
					break;

				case "editColumn": //agregar columna al proyecto
					$id = $_REQUEST['id_columna'];
					$nombre = $_REQUEST['nombre'];
					$json = $edit->editColumn($id, $nombre);
					echo json_encode($json);
					return;
					break;

				case "setLead": //agregar responsable a la tarea
					$id = $_REQUEST['id'];
					$pkR = $_REQUEST['pkR'];
					$json = $edit->setLead($id, $pkR);
					echo json_encode($json);
					return;
					break;

				case "noLead": //dejar sin responsable la tarea
					$pkR = $_REQUEST['pkR'];
					$json = $edit->noLead($pkR);
					echo json_encode($json);
					return;
					break;

				case "change_text_elements": //Cambiar el texto de un estado
					$PKColorColumna = $_REQUEST['PKColorColumna'];
					$texto = $_REQUEST['texto'];
					$json = $edit->change_text_elements($PKColorColumna, $texto);
					echo json_encode($json);
					return;
					break;

				case "change_color_elements": //Cambiar el color de un estado
					$PKColorColumna = $_REQUEST['PKColorColumna'];
					$color = $_REQUEST['color'];
					$json = $edit->change_color_elements($PKColorColumna, $color);
					echo json_encode($json);
					return;
					break;

				case "define_symbol_side": //Cambiar el color de un estado
					$id = $_REQUEST['id'];
					$side = $_REQUEST['side'];
					$json = $edit->define_symbol_side($id, $side);
					echo json_encode($json);
					return;
					break;

				case "set_numeric_value": //Cambiar el color de un estado
					$id = $_REQUEST['id'];
					$num = $_REQUEST['num'];
					$json = $edit->set_numeric_value($id, $num);
					echo json_encode($json);
					return;
					break;

				case "set_symbol_column": //Cambiar el color de un estado
					$id = $_REQUEST['id'];
					$symbol = $_REQUEST['symbol'];
					$json = $edit->set_symbol_column($id, $symbol);
					echo json_encode($json);
					return;
					break;

				case "set_symbol_column": //Cambiar el color de un estado
					$id = $_REQUEST['id'];
					$symbol = $_REQUEST['symbol'];
					$json = $edit->set_symbol_column($id, $symbol);
					echo json_encode($json);
					return;
					break;

				case "set_task_done": //Cambiar el color de un estado
					$id_tarea = $_REQUEST['id_tarea'];
					$id_element = $_REQUEST['id_element'];
					$json = $edit->set_task_done($id_tarea, $id_element);
					echo json_encode($json);
					return;
					break;

				case "set_subtask_done": //Cambiar el color de un estado
					$id_tarea = $_REQUEST['id_tarea'];
					$id_element = $_REQUEST['id_element'];
					$json = $edit->set_subtask_done($id_tarea, $id_element);
					echo json_encode($json);
					return;
					break;

				case "set_task_undone":
					$id_tarea = $_REQUEST['id_tarea'];
					$id_element = $_REQUEST['id_element'];
					$json = $edit->set_task_undone($id_tarea, $id_element);
					echo json_encode($json);
					return;
					break;

				case "set_subtask_undone": //Cambiar el check de las subtareas a gris
					$id_tarea = $_REQUEST['id_tarea'];
					$id_element = $_REQUEST['id_element'];
					$json = $edit->set_subtask_undone($id_tarea, $id_element);
					echo json_encode($json);
					return;
					break;

				case "edit_rank": //Cambiar el color de un estado
					$id_element = $_REQUEST['id_element'];
					$rango = $_REQUEST['rango'];
					$json = $edit->edit_rank($id_element, $rango);
					echo json_encode($json);
					return;
					break;

				case "edit_text_element": //Cambiar el color de un estado
					$id_element = $_REQUEST['id_element'];
					$new_text = $_REQUEST['new_text'];
					$json = $edit->edit_text_element($id_element, $new_text);
					echo json_encode($json);
					return;
					break;

				case "simple_number": //Cambiar el color de un estado
					$id_element = $_REQUEST['id_element'];
					$number = $_REQUEST['number'];
					$json = $edit->simple_number($id_element, $number);
					echo json_encode($json);
					return;
					break;

				case "simple_text": //Cambiar el color de un estado
					$id_element = $_REQUEST['id_element'];
					$text = $_REQUEST['text'];
					$json = $edit->simple_text($id_element, $text);
					echo json_encode($json);
					return;
					break;

				case "edit_sub": //Editar nombre de subtarea
					$id_sub = $_REQUEST['id_sub'];
					$nombre = $_REQUEST['nombre'];
					$json = $edit->edit_sub($id_sub, $nombre);
					echo json_encode($json);
					return;
					break;

				case "set_sub_done": //Marcar sub tarea como terminada
					$id_sub = $_REQUEST['id_sub'];
					$json = $edit->set_sub_done($id_sub);
					echo json_encode($json);
					return;
					break;

				case "set_sub_undone": //Marcar sub tarea como terminada
					$id_sub = $_REQUEST['id_sub'];
					$json = $edit->set_sub_undone($id_sub);
					echo json_encode($json);
					return;
					break;

				case "new_project_name": //Marcar sub tarea como terminada
					$id = $_REQUEST['id'];
					$nombre = $_REQUEST['nombre'];
					$json = $edit->new_project_name($id, $nombre);
					echo json_encode($json);
					return;
					break;

				case "edit_menu_element": //Marcar sub tarea como terminada
					$id = $_REQUEST['id'];
					$texto = $_REQUEST['texto'];
					$json = $edit->edit_menu_element($id, $texto);
					echo json_encode($json);
					return;
					break;
			}
			break;

		case "elim_data": //Eliminar columnas / tareas / etapas
			$elim = new elim_data(); //nuevo objeto de add_data
			switch ($_REQUEST['funcion']) { //función
				case "elimColumn": //agregar columna al proyecto
					$id = $_REQUEST['id'];
					$tipo = $_REQUEST['tipo'];
					$json = $elim->elimColumn($id, $tipo); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
					break;

				case "elimTask": //agregar columna al proyecto
					$id_tarea = $_REQUEST['id_tarea'];
					$id_proyecto = $_REQUEST['id_proyecto'];
					$json = $elim->elimTask($id_tarea, $id_proyecto); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
					break;

				case "elimGroup": //agregar columna al proyecto
					$id_etapa = $_REQUEST['id_etapa'];
					$id_proyecto = $_REQUEST['id_proyecto'];
					$json = $elim->elimGroup($id_etapa, $id_proyecto); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
					break;

				case "elimColorElement": //agregar columna al proyecto
					$PKColorColumna = $_REQUEST['PKColorColumna'];
					$json = $elim->elimColorElement($PKColorColumna); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
					break;

				case "elim_sub": //agregar columna al proyecto
					$id_sub = $_REQUEST['id_sub'];
					$id_tarea = $_REQUEST['id_tarea'];
					$json = $elim->elim_sub($id_sub, $id_tarea); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
					break;

				case "delete_menu_element": //Eliminar un elemento de menú despegable
					$id = $_REQUEST['id'];
					$json = $elim->delete_menu_element($id); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					break;

				case "delete_proyect":
					$idProyecto = $_REQUEST['idProyecto'];
					$json = $elim->deleteProyect($idProyecto); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
					break;
			}
			break;

		case "data_order": //Ordenar columnas y tareas.
			$order = new data_order(); //nuevo objeto de data_order
			switch ($_REQUEST['funcion']) { //función

				case "columnOrder": //ordenar columnas del proyecto
					$id = $_REQUEST['id']; //id del proyecto
					$orden = $_REQUEST['ordenArray']; //array con el orden
					$json = $order->columnOrder($id, $orden); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
					break;

				case "etapaOrder": //ordenar columnas del proyecto
					$id = $_REQUEST['id']; //id del proyecto
					$orden = $_REQUEST['ordenArray']; //array con el orden
					$json = $order->etapaOrder($id, $orden); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
					break;

				case "tablaOrder": //ordenar columnas del proyecto
					$id = $_REQUEST['id']; //id del proyecto
					$orden = $_REQUEST['ordenArray']; //array con el orden
					$tarea = $_REQUEST['tarea']; //id de la tarea que cambio
					$etapa = $_REQUEST['etapa']; //id de la etapa a la que pertenece la terea
					$json = $order->tablaOrder($id, $orden, $tarea, $etapa); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
					break;

				case "groupOrder": //ordenar columnas del proyecto
					$id = $_REQUEST['id']; //id del proyecto
					$orden = $_REQUEST['ordenArray']; //array con el orden
					$json = $order->groupOrder($id, $orden); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
					break;
			}
			break;

		case "get_data": //Obtener información varia de la BBDD
			$get = new get_data(); //nuevo objeto de data_order
			switch ($_REQUEST['funcion']) { //función
				case "getUsers": //ordenar columnas del proyecto
					$idProyecto = $_REQUEST['idProyecto']; //id del proyecto
					$json = $get->getUsers($idProyecto); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
					break;

				case "getAllProjects": //dato del proyecto que se eligió
					$json = $get->getAllProjects(); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
					break;

				case "get_columns_type": //dato del proyecto que se eligió
					$json = $get->get_columns_type(); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
					break;

				case "comprobar_columna_estado":
					$id_project = $_REQUEST['id_project']; //id del proyecto
					$json = $get->comprobar_columna_estado($id_project);
					echo json_encode($json);
					break;

				case "getAllTagsMenuDes": //dato del menu desplegable
					$id = $_REQUEST['id'];
					$array = $_REQUEST['array'];
					$json = $get->consultaEtiquetasMenu($id, $array); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
					break;

				case "getAllTagsSelected": //dato del menu desplegable
					$id = $_REQUEST['id'];
					$json = $get->consultaEtiquetasSelected($id); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
					break;

				case "getAllElementsSelected": //dato del menu desplegable
					$id = $_REQUEST['id'];
					$json = $get->consultaElementsSelected($id); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
					break;

				case "getAllTagsMenuToEdit": //dato del menu desplegable
					$id = $_REQUEST['id'];
					$json = $get->getAllTagsMenuToEdit($id); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
					break;

				case "get_sub": //dato del menu desplegable
					$id_tarea = $_REQUEST['id_tarea'];
					$json = $get->get_sub($id_tarea); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
					break;

				case "get_subtask": //dato del total de subtareas por tarea
					$array = $_REQUEST['array'];
					$json = $get->get_subtask($array); //Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
					break;

				case "verify_progress_columns": //verificar si ya están en uso las columnas verificar y progreso
					$id_project = $_REQUEST["id_project"];
					$json = $get->verify_progress_columns($id_project);
					echo json_encode($json); //Retornando el resultado al ajax
					return;
					break;

				case "comprobar_subtareas": //Comprobar si la tarea tiene subtareas
					$id_tarea = $_REQUEST["id_tarea"];
					$json = $get->comprobar_subtareas($id_tarea);
					echo json_encode($json); //Retornando el resultado al ajax
					return;
					break;

				case "checkValueSelected": //Comprobar si la etiqueta está en uso.
					$id = $_REQUEST["id"];
					$json = $get->checkValueSelected($id);
					echo json_encode($json); //Retornando el resultado al ajax
					return;
					break;
			}
			break;

		case "buscar_data": //Buscar info en ddbb
			$buscar = new buscar_data(); //creando un nuevo objeto que referencia a la clase buscar data
			switch ($_REQUEST['funcion']) {
				case "buscar_tarea":
					$inputValue = $_REQUEST['usuarioInput'];
					$id_proyecto = $_REQUEST['id'];
					$json = $buscar->buscarTarea($inputValue, $id_proyecto);
					echo json_encode($json);
					return;
					break;
			}
			break;

		case "mis_tareas": //BUscar info en ddbb mis tareas asignadas como responsable
			$misTareas = new mis_tareas(); //creando un nuevo objeto que referencia a la clase buscar data
			switch ($_REQUEST['funcion']) {
				case "mis_tareas":
					$id_user = $_REQUEST['id'];
					$id_proyecto = $_REQUEST['idProyecto'];
					$json = $misTareas->buscarMisTareas($id_user, $id_proyecto);
					echo json_encode($json);
					return;
					break;
			}
			break;
	}
}
