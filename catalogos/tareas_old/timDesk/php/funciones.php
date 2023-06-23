<?php
//include_once("conexion.php");//Incluyendo el archivo de la clase conexión
include_once("clases.php");
if(isset($_REQUEST['clase']) && isset($_REQUEST['funcion'])){//Requiriendo de ajax la clase y la función

	switch($_REQUEST['clase']){//clase

		case "admin_data"://clase "admin_data" //Esta es la primer clase del archivo.
			$data = new admin_data();//nuevo objeto de admin_data (para que use sus funciones)
			switch ($_REQUEST['funcion']){//función

				case "getProject"://dato del proyecto que se eligió
					$id = $_REQUEST['id'];
					$json = $data->getProject($id);//Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
				break;

				case "getLevels"://Etapas
					$id = $_REQUEST['id'];
					$json = $data->getLevels($id);//Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
				break;

				case "getTask"://tareas
					$id = $_REQUEST['id'];
					$json = $data->getTask($id);//Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
				break;

				case "getInfo"://información de las tareas
					$id = $_REQUEST['id'];
					$array = $_REQUEST['array'];
					$json = $data->getInfo($array,$id);//Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
				break;

				case "getColor"://Color de Etapas
					$id = $_REQUEST['id'];
					$color = $_REQUEST['color'];
					$json = $data->getColor($id,$color);//Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
				break;

				case "getFecha"://Color de Etapas
					$id = $_REQUEST['id'];
					$fecha = $_REQUEST['fecha'];
					$json = $data->getFecha($id,$fecha);//Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
				break;

				case "getColorColumna"://Color de Etapas
					$id_estado = $_REQUEST['id_estado'];
					$id_columna = $_REQUEST['id_columna'];
					$json = $data->getColorColumna($id_estado,$id_columna);//Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
				break;

				case "setColorTarea"://Color de Etapas
					$id_estado = $_REQUEST['id_estado'];
					$id_color = $_REQUEST['id_color'];
					$json = $data->setColorTarea($id_estado,$id_color);//Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
				break;
			}
		break;

		case "add_data"://Agregar columnas / tareas / etapas
			$add = new add_data();//nuevo objeto de add_data
			switch ($_REQUEST['funcion']){//función

				case "addColumn"://agregar columna al proyecto
					$id = $_REQUEST['id'];
					$tipo = $_REQUEST['tipo'];
					$tabla = $_REQUEST['tabla'];
					$json = $add->addColumn($id, $tipo, $tabla);//Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
				break;

				case "addTask"://agregar tarea al proyecto
					$id_etapa = $_REQUEST['id_etapa'];
					$id_proyecto = $_REQUEST['id_proyecto'];
					$json = $add->addTask($id_etapa,$id_proyecto);//Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
				break;

				case "addGroup":
					$id_proyecto = $_REQUEST['id_proyecto'];
					$json = $add->addGroup($id_proyecto);//Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
				break;
			}
		break;

		case "edit_data":
			$edit = new edit_data();//nuevo objeto de add_data
			switch ($_REQUEST['funcion']){//función
				case "editGroup"://agregar columna al proyecto
					$id = $_REQUEST['id_etapa'];
					$nombre = $_REQUEST['nombre'];
					$json = $edit->editGroup($id,$nombre);//Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
				break;

				case "editTask"://agregar columna al proyecto
					$id = $_REQUEST['id_tarea'];
					$nombre = $_REQUEST['nombre'];
					$json = $edit->editTask($id,$nombre);
					echo json_encode($json); 
					return;
				break;

				case "editColumn"://agregar columna al proyecto
					$id = $_REQUEST['id_columna'];
					$nombre = $_REQUEST['nombre'];
					$json = $edit->editColumn($id,$nombre);
					echo json_encode($json); 
					return;
				break;

				case "setLead"://agregar responsable a la tarea
					$id = $_REQUEST['id'];
					$pkR = $_REQUEST['pkR'];
					$json = $edit->setLead($id,$pkR);
					echo json_encode($json); 
					return;
				break;

				case "noLead"://dejar sin responsable la tarea
					$pkR = $_REQUEST['pkR'];
					$json = $edit->noLead($pkR);
					echo json_encode($json); 
					return;
				break;

			}
		break;

		case "elim_data"://Eliminar columnas / tareas / etapas
			$elim = new elim_data();//nuevo objeto de add_data
			switch ($_REQUEST['funcion']){//función
				case "elimColumn"://agregar columna al proyecto
					$id = $_REQUEST['id'];
					$tipo = $_REQUEST['tipo'];
					$json = $elim->elimColumn($id, $tipo);//Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
				break;

				case "elimTask"://agregar columna al proyecto
					$id_tarea = $_REQUEST['id_tarea'];
					$id_proyecto = $_REQUEST['id_proyecto'];
					$json = $elim->elimTask($id_tarea,$id_proyecto);//Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
				break;

				case "elimGroup"://agregar columna al proyecto
					$id_etapa = $_REQUEST['id_etapa'];
					$id_proyecto = $_REQUEST['id_proyecto'];
					$json = $elim->elimGroup($id_etapa,$id_proyecto);//Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
				break;
			}
		break;

		case "data_order"://Ordenar columnas y tareas.
			$order = new data_order();//nuevo objeto de data_order
			switch ($_REQUEST['funcion']){//función
				
				case "columnOrder"://ordenar columnas del proyecto
					$id = $_REQUEST['id']; //id del proyecto
					$orden = $_REQUEST['ordenArray']; //array con el orden
					$json = $order->columnOrder($id, $orden);//Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
				break;

				case "etapaOrder"://ordenar columnas del proyecto
					$id = $_REQUEST['id']; //id del proyecto
					$orden = $_REQUEST['ordenArray']; //array con el orden
					$json = $order->etapaOrder($id, $orden);//Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
				break;

				case "tablaOrder"://ordenar columnas del proyecto
					$id = $_REQUEST['id']; //id del proyecto
					$orden = $_REQUEST['ordenArray']; //array con el orden
					$tarea = $_REQUEST['tarea'];//id de la tarea que cambio
					$etapa = $_REQUEST['etapa'];//id de la etapa a la que pertenece la terea
					$json = $order->tablaOrder($id, $orden, $tarea, $etapa);//Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
				break;

				case "groupOrder"://ordenar columnas del proyecto
					$id = $_REQUEST['id']; //id del proyecto
					$orden = $_REQUEST['ordenArray']; //array con el orden
					$json = $order->groupOrder($id, $orden);//Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
				break;
			}
		break;

		case "get_data"://Obtener información varia de la BBDD
			$get = new get_data();//nuevo objeto de data_order
			switch ($_REQUEST['funcion']){//función
				case "getUsers"://ordenar columnas del proyecto
					$json = $get->getUsers();//Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
				break;

				case "getAllProjects"://dato del proyecto que se eligió
					$json = $get->getAllProjects();//Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
				break;
			}
		break;

		case "buscar_data"://BUscar info en ddbb
			$buscar = new buscar_data();//creando un nuevo objeto que referencia a la clase buscar data
			switch ($_REQUEST['funcion']){
				case "buscar_tarea":
					$inputValue = $_REQUEST['usuarioInput'];
					$id_proyecto = $_REQUEST['id'];
					$json = $buscar->buscarTarea($inputValue,$id_proyecto);
					echo json_encode($json);
					return;
				break;
			}
		break;
	}
}