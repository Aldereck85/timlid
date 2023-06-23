<?php
	include_once("conexion.php");//Incluyendo el archivo de la clase conexión

	class admin_data{

		function getProject(){
			$con = new conectar();
			$db = $con->getDb();
			/*
			SELECT * FROM proyectos LEFT JOIN etapas ON proyectos.PKProyecto = etapas.FKProyecto LEFT JOIN tareas ON PKetapa = FKEtapa LEFT JOIN columnas_estado ON proyectos.PKProyecto = columnas_estado.FKProyecto LEFT JOIN estado_tarea ON PKColumnaEstado = FKColumnaEstado AND PKTarea = FKTarea WHERE PKProyecto = 1 GROUP BY Tarea
			*/
			try{
				$query=sprintf("SELECT * FROM proyectos WHERE PKProyecto = 1");	
				$stmt = $db->prepare($query);
				$stmt->execute();
				return $stmt->fetchAll(PDO::FETCH_OBJ);
			} 
			catch(PDOException $e){
				return "Error en Consulta: ".$e->getMessage();
			}

			$stmt= NULL;
			$db = NULL;
		}

		function getLevels($id){ //Etapas y sus columnas
			$con = new conectar();
			$db = $con->getDb();

			try{

				$query = sprintf("SELECT PKColumnaProyecto, nombre, tipo, orden FROM columnas_proyecto WHERE FKProyecto = ? ORDER BY orden ASC");
				$stmt = $db->prepare($query);
				$stmt->execute(array($id));
				$columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
				//var_dump($columns);

				$query1=sprintf("SELECT * FROM etapas WHERE FKProyecto = 1");	
				$stmt1 = $db->prepare($query1);
				$stmt1->execute();
				$etapas = $stmt1->fetchAll(PDO::FETCH_ASSOC);
				//var_dump($etapas);
				$data_col = [];
					
				for ($i=0; $i < count($etapas); $i++) { 
					array_push($etapas[$i], $columns);
				}
				
				return $etapas;

			} 
			catch(PDOException $e){
				return "Error en Consulta: ".$e->getMessage();
			}

			$stmt= NULL;
			$db = NULL;
		}

		function getTask($id){ //Tareas
			$con = new conectar();
			$db = $con->getDb();

			try{

				$query = sprintf("SELECT * FROM tareas WHERE FKProyecto = ?");
				$stmt = $db->prepare($query);
				$stmt->execute(array($id));
				return $stmt->fetchAll(PDO::FETCH_OBJ);

			} 
			catch(PDOException $e){
				return "Error en Consulta: ".$e->getMessage();
			}

			$stmt= NULL;
			$db = NULL;
		}

		
		function getInfo($array, $id){ //Información de las tareas
			$con = new conectar();
			$db = $con->getDb();
			$data = [];
			//En el array vienen los tipos de columnas 
			//2:estado_tarea.
			$tareas_estados=[];
			//1:responsables_tarea.
			$responsables=[];
			try{
				for ($i=0; $i < count($array); $i++) {
					if ($array[$i] == 1) {
						$query = sprintf("SELECT columnas_proyecto.FKProyecto, FKTarea,PKColumnaProyecto,PKResponsable as id,PKUsuario,Usuario as Texto, Orden, Tipo FROM responsables_tarea LEFT JOIN columnas_proyecto ON FKColumnaProyecto = PKColumnaProyecto LEFT JOIN usuarios ON FKUsuario = PKUsuario WHERE responsables_tarea.FKProyecto = ? ORDER BY orden ASC");
						$stmt = $db->prepare($query);
						$stmt->execute(array($id));
						$responsables = $stmt->fetchAll(PDO::FETCH_ASSOC);
					}
					if ($array[$i] == 2) {
						$query = sprintf("SELECT PKEstadoTarea as id, Color, Estado as Texto, estado_tarea.FKProyecto, FKTarea, PKColumnaProyecto, PKEstadoTarea, Orden, Tipo FROM estado_tarea LEFT JOIN columnas_proyecto ON FKColumnaProyecto=PKColumnaProyecto WHERE estado_tarea.FKProyecto = ? ORDER BY orden ASC");
						$stmt = $db->prepare($query);
						$stmt->execute(array($id));
						$tareas_estados = $stmt->fetchAll(PDO::FETCH_ASSOC);
					}
				}
				$resultado = array_merge($responsables,$tareas_estados);

				$ordenamiento = array_column($resultado, 'Orden');
				array_multisort($ordenamiento, SORT_ASC, $resultado);
				//var_dump($resultado);
				array_push($data, $resultado);
				return $data;
				//$query = sprintf("SELECT * FROM estado_tarea WHERE FKProyecto = ? ORDER BY FKColumnaProyecto ASC");
				//$stmt = $db->prepare($query);
				//$stmt->execute(array($id));
				//return $stmt->fetchAll(PDO::FETCH_OBJ);
			} 
			catch(PDOException $e){
				return "Error en Consulta: ".$e->getMessage();
			}

			$stmt= NULL;
			$db = NULL;
		}
		/*
		function getInfo($array, $id){ //Información de las tareas
			$con = new conectar();
			$db = $con->getDb();
			$data = [];

			try{
				$query = sprintf("SELECT * FROM estado_tarea WHERE FKProyecto = ? ORDER BY FKColumnaProyecto ASC");
				$stmt = $db->prepare($query);
				$stmt->execute(array($id));
				return $stmt->fetchAll(PDO::FETCH_OBJ);
			} 
			catch(PDOException $e){
				return "Error en Consulta: ".$e->getMessage();
			}

			$stmt= NULL;
			$db = NULL;
		}
		*/
	}//fin admin_data

	class add_data{

		function addColumn($id, $tipo, $tabla){
			$con = new conectar();
			$db = $con->getDb();
			$nombre_nuevo="";
			$identificador_tareas = [];
			$respuesta = [];
			try{
				//Columna tipo estado
				if ($tipo == 2) {
					$query=sprintf("SELECT * FROM columnas_proyecto WHERE tipo=? AND nombre='Estado' AND FKProyecto=?");	
					$stmt = $db->prepare($query);
					$stmt->execute(array($tipo, $id));
					$cuenta = $stmt->rowCount();
					$data = $stmt->fetch(PDO::FETCH_ASSOC);					
					//Si el nombre ya existe va probando con un consecutivo hasta encontrar uno inexistente del 1 al 100
					if ($cuenta>0){
						for ($a=1;$a<100;$a++){
							$pn=$data["nombre"]." ".$a;
							$stmt1 = $db->prepare("SELECT * FROM columnas_proyecto WHERE tipo=? AND nombre=? AND FKProyecto=?");
							$stmt1->execute(array($tipo,$pn,$id));
							$cuenta1 = $stmt1->rowCount();
							if ($cuenta1==0){
								$nombre_nuevo=$data["nombre"]." ".$a;
								$a=100;
							}
						}
					}else $nombre_nuevo="Estado";

					//Orden que le corresponderá a la columna
					$query1=sprintf("SELECT * FROM columnas_proyecto WHERE FKProyecto=? ORDER BY orden");	
					$stmt2 = $db->prepare($query1);
					$stmt2->execute(array($id));
					$data1 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
					$orden = count($data1)+1;

					//Insertando la nueva columna en la tabla columnas_proyecto
					$insert = sprintf("INSERT INTO columnas_proyecto(nombre, tipo, orden, FKProyecto) VALUES(?,?,?,?)");
					$stmt3 = $db->prepare($insert);
					$stmt3->execute(array($nombre_nuevo,$tipo,$orden,$id));
					$id_columna = $db->lastInsertId();
					//Consultando el total de tareas que existen en el proyecto
					$tareas = sprintf("SELECT PKTarea FROM tareas WHERE FKProyecto = ?");
					$stmt3 = $db->prepare($tareas);
					$stmt3->execute(array($id));
					$tareasRows = $stmt3->fetchAll(PDO::FETCH_ASSOC);
					//var_dump($tareasRows);
					//Por cada tarea se le asignará un estado default
					
					for ($i=0; $i < count($tareasRows); $i++) { 
						$stmt4 = $db->prepare("INSERT INTO estado_tarea(Estado,Color,FKTarea,FKColumnaProyecto,FKProyecto) VALUES(?,?,?,?,?)");
						$stmt4->execute(array("","#f5f6f8",$tareasRows[$i]['PKTarea'], $id_columna,$id));
						$id_tarea = $db->lastInsertId();
						array_push($identificador_tareas, $id_tarea);
					}


					$respuesta = [
						"Nombre"=>$nombre_nuevo,
						"Orden"=>$orden,
						"id"=>$id_columna,
						$identificador_tareas
					];
					
					return $respuesta;
				}
				
			} 
			catch(PDOException $e){
				return "Error en Consulta: ".$e->getMessage();
			}

			$stmt= NULL;
			$db = NULL;
		}
	}//fin add_data

	class data_order{
		function columnOrder($id, $orden){
			$con = new conectar();
			$db = $con->getDb();

			try{
				for ($i=0; $i < count($orden); $i++) { 
					$update = sprintf("UPDATE columnas_proyecto SET orden = ? WHERE PKColumnaProyecto = ?");
					$stmt = $db->prepare($update);
					$stmt->execute(array($i+1, $orden[$i]));
				}

				return "ok";
			}catch(PDOException $e){
				return "Error en Consulta: ".$e->getMessage();
			}

			$stmt= NULL;
			$db = NULL;
		}
	}//fin data_order