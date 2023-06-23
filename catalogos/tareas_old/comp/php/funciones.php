<?php
include_once("conexion.php");//Incluyendo el archivo de la clase conexión
include_once("clases.php");
if(isset($_REQUEST['clase']) && isset($_REQUEST['funcion'])){//Requiriendo de ajax la clase y la función

	switch($_REQUEST['clase']){//clase

		case "admin_data"://clase "admin_data"
			$data = new admin_data();//nuevo objeto de admin_data
			switch ($_REQUEST['funcion']){//función

				case "getProject"://dato del proyecto que se eligió
					$json = $data->getProject();//Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
				break;

				case "getLevels"://Etapas
					$id = $_REQUEST['id'];
					$json = $data->getLevels($id);//Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
				break;

				case "getTask"://Etapas
					$id = $_REQUEST['id'];
					$json = $data->getTask($id);//Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
				break;

				case "getInfo"://Etapas
					$id = $_REQUEST['id'];
					$array = $_REQUEST['array'];
					$json = $data->getInfo($array,$id);//Guardando el return de la función
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
			}
		break;
	}
}