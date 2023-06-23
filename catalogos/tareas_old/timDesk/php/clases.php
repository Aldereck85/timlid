<?php

	class conectar{//Llamado al archivo de la conexión.

		function getDB(){
			include "../../../../include/db-conn.php";
			return $conn;
		}
	}
	
	class admin_data{

		function getProject($id){

			$con = new conectar();
			$db = $con->getDb();
			try{
				$query=sprintf("SELECT * FROM proyectos WHERE PKProyecto = ?");	
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

		function getLevels($id){ //Etapas y sus columnas
			$con = new conectar();
			$db = $con->getDb();

			try{
				//Guardando la consulta en la variable $query
				$query = sprintf("SELECT PKColumnaProyecto, nombre, tipo, Orden FROM columnas_proyecto WHERE FKProyecto = ? ORDER BY Orden ASC");
				$stmt = $db->prepare($query);//Preparando la consulta
				$stmt->execute(array($id));//
				$columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
				//var_dump($columns);

				$query1=sprintf("SELECT * FROM etapas WHERE FKProyecto = ? ORDER BY Orden");	
				$stmt1 = $db->prepare($query1);
				$stmt1->execute(array($id));
				$etapas = $stmt1->fetchAll(PDO::FETCH_ASSOC);//Guardalo como array
					
				for ($i=0; $i < count($etapas); $i++) { 
					//array_push(array al que le quieres añadir la inforamción , variable que vas a añadir)
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

				$query = sprintf("SELECT FKEtapa,tareas.FKProyecto,tareas.Orden,PKTarea,Tarea,Terminada,etapas.color FROM tareas LEFT JOIN etapas ON PKEtapa = FKetapa WHERE tareas.FKProyecto = ? ORDER BY Orden");
				$stmt = $db->prepare($query);
				$stmt->execute(array($id));
				return $stmt->fetchAll(PDO::FETCH_OBJ);//devuelvelo como objeto.

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
			//3:fecha_tarea
			$fechas=[];
			//4:Hipervinculo
			$hipervinculo=[];
			try{
				for ($i=0; $i < count($array); $i++) {
					if ($array[$i] == 1) {//Si el array[i] es igual a 1, quiere decir que el tipo de columna es responsable. Por lo que va a buscar en la tabla responsables_tarea.
						$query = sprintf("SELECT columnas_proyecto.FKProyecto, FKTarea,PKColumnaProyecto,PKResponsable as id,PKUsuario,Usuario, columnas_proyecto.Orden as cOrden, Tipo, tareas.Orden as tOrden, CONCAT(empleados.Primer_Nombre,' ',empleados.Apellido_Paterno) as Texto FROM responsables_tarea LEFT JOIN columnas_proyecto ON FKColumnaProyecto = PKColumnaProyecto LEFT JOIN usuarios ON FKUsuario = PKUsuario LEFT JOIN empleados ON PKEmpleado = usuarios.FKEmpleado LEFT JOIN tareas ON FKTarea = PKTarea WHERE responsables_tarea.FKProyecto = ? ORDER BY tareas.Orden ASC");
						$stmt = $db->prepare($query);
						$stmt->execute(array($id));//array()
						$responsables = $stmt->fetchAll(PDO::FETCH_ASSOC);
						//var_dump($responsables);
					}
					if ($array[$i] == 2) {//Estado
						$query = sprintf("SELECT PKEstadoTarea as id, colores_columna.color, colores_columna.nombre as Texto, colores_columna.FKProyecto, FKTarea, PKColumnaProyecto, columnas_proyecto.Orden as cOrden, Tipo, tareas.Orden as tOrden FROM estado_tarea LEFT JOIN colores_columna ON FKColorColumna = PKColorColumna LEFT JOIN columnas_proyecto ON FKColumnaProyecto=PKColumnaProyecto LEFT JOIN tareas ON FKTarea = PKTarea WHERE colores_columna.FKProyecto = ? ORDER BY tareas.Orden ASC");
						$stmt = $db->prepare($query);
						$stmt->execute(array($id));
						$tareas_estados = $stmt->fetchAll(PDO::FETCH_ASSOC);
					}

					if ($array[$i] == 3) {//Fecha
						$query = sprintf("SELECT columnas_proyecto.FKProyecto, FKTarea,PKColumnaProyecto,PKFecha as id,Fecha as Texto, columnas_proyecto.Orden as cOrden, Tipo, tareas.Orden as tOrden FROM fecha_tarea LEFT JOIN columnas_proyecto ON FKColumnaProyecto = PKColumnaProyecto LEFT JOIN tareas ON FKTarea = PKTarea WHERE fecha_tarea.FKProyecto = ? ORDER BY tareas.Orden ASC");
						$stmt = $db->prepare($query);
						$stmt->execute(array($id));
						$fechas = $stmt->fetchAll(PDO::FETCH_ASSOC);
					}

					if ($array[$i] == 4) {
						$query = sprintf("SELECT columnas_proyecto.FKProyecto, FKTarea,PKColumnaProyecto,PKHipervinculo as id,Texto,Direccion,columnas_proyecto.Orden as cOrden, Tipo, tareas.Orden as tOrden FROM hipervinculo_tarea LEFT JOIN columnas_proyecto ON FKColumnaProyecto = PKColumnaProyecto LEFT JOIN tareas ON FKTarea = PKTarea WHERE hipervinculo_tarea.FKProyecto = ? ORDER BY tareas.Orden ASC");
						$stmt = $db->prepare($query);
						$stmt->execute(array($id));
						$hipervinculo = $stmt->fetchAll(PDO::FETCH_ASSOC);
					}
				}
				$resultado = array_merge($responsables,$tareas_estados,$fechas,$hipervinculo);
				//var_dump($resultado);

				$ordenamiento = array_column($resultado, 'tOrden');
				//var_dump($ordenamiento);
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

		function getColor($id, $color){//Actualiza el color de la etapa
			$con = new conectar();
			$db = $con->getDb();

			try{
				$picker = sprintf("UPDATE etapas SET color=? WHERE PKEtapa=?");
				$stmt = $db->prepare($picker);
				$stmt->execute(array($color,$id));

				return "ok";	
			}catch(PDOException $e){
				return "Error en Consulta: ".$e->getMessage();
			}

			$stmt= NULL;
			$db = NULL;
		}

		function getFecha($id,$fecha){
			$con = new conectar();
			$db = $con->getDb();

			try {
				$date = sprintf("UPDATE fecha_tarea SET Fecha=? WHERE PKFecha=?");
				$stmt = $db->prepare($date);
				$stmt->execute(array($fecha,$id));

				return "ok";
			}catch(PDOException $e){
				return "Error en Consulta: ".$e->getMessage();
			}

			$stmt= NULL;
			$db = NULL;
		}

		function getColorColumna($id_estado,$id_columna){//consulta los colores de una columna estado
			$con = new conectar();
			$db = $con->getDb();

			try {
				$consulta = sprintf("SELECT * FROM colores_columna WHERE FKColumnaProyecto = ?");
				$stmt = $db->prepare($consulta);
				$stmt->execute(array($id_columna));
				return $stmt->fetchAll(PDO::FETCH_OBJ);
			}catch(PDOException $e){
				return "Error en Consulta: ".$e->getMessage();
			}

			$stmt= NULL;
			$db = NULL;
		}

		function setColorTarea($id_estado,$id_color){
			$con = new conectar();
			$db = $con->getDb();

			try {
				$actualiza = sprintf("UPDATE estado_tarea SET FKColorColumna = ? WHERE PKEstadoTarea = ?");
				$stmt = $db->prepare($actualiza);
				$stmt->execute(array($id_color, $id_estado));
				return "ok";
			}catch(PDOException $e){
				return "Error en Consulta: ".$e->getMessage();
			}

			$stmt= NULL;
			$db = NULL;
		}

	}//fin admin_data

	class add_data{

		function addColumn($id, $tipo, $tabla){
			$con = new conectar();
			$db = $con->getDb();
			$nombre_nuevo="";
			$identificador_tareas = [];
			$respuesta = [];
			try{
				//Comprobando que existe al menos una etapa para agregar la columna:
				$stmt = $db->prepare("SELECT * FROM etapas WHERE FKProyecto = ?");
				$stmt->execute(array($id));
				$countGroups = $stmt->rowCount();
				if ($countGroups == 0) {
					return "noGroups";
				}else{
					if ($tipo == 1) {//Columna tipo responsables
						$query=sprintf("SELECT * FROM columnas_proyecto WHERE tipo=? AND nombre='Responsable' AND FKProyecto=?");	
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
						}else $nombre_nuevo="Responsable";
						
						$comun = new add_data();
						$resp = $comun->columnComun($nombre_nuevo,$id,$tipo,$tabla);
						
						if (count($resp[0])!=0) {//Si hay tareas en el proyecto:
							//Por cada tarea se le asignará null como responsable
							for ($i=0; $i < count($resp[0]); $i++) { 
								$stmt4 = $db->prepare("INSERT INTO responsables_tarea(FKTarea,FKColumnaProyecto,FKProyecto) VALUES(?,?,?)");
								$stmt4->execute(array($resp[0][$i]['PKTarea'], $resp['id_columna'],$id));
								$id_tarea = $db->lastInsertId();
								array_push($identificador_tareas, $id_tarea);
							}
						}
						

						$respuesta = [
							"Nombre"=>$nombre_nuevo,
							"Orden"=>$resp['Orden'],
							"PKColumnaProyecto"=>$resp['id_columna'],
							"id"=>$id_tarea,
							"Tipo"=>$tipo,
							"Texto"=>null,
							$identificador_tareas
						];
						
						return $respuesta;
					}

					if ($tipo == 2) {//Columna tipo estado
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

						$comun = new add_data();
						$resp = $comun->columnComun($nombre_nuevo,$id,$tipo,$tabla);

						$colores = array(
							["nombre" => "Hecho",
							"color" => "#28a745",
							"bandera" => 0
							],
							["nombre" => "Pendiente",
							"color" => "#ffc107",
							"bandera" => 0
							],
							["nombre" => "Atrasado",
							"color" => "#dc3545",
							"bandera" => 0
							],
							["nombre" => " ",
							"color" => "#9a9a9a",
							"bandera" => 1
							]
						);
						$id_color = "";
						//Por cada tarea se le asignará un estado default
						for ($i=0; $i < count($colores); $i++) { 
							$stmt4 = $db->prepare("INSERT INTO colores_columna(nombre,color,FKColumnaProyecto,FKProyecto,bandera) VALUES(?,?,?,?,?)");
							$stmt4->execute(array($colores[$i]["nombre"],$colores[$i]["color"], $resp['id_columna'],$id,$colores[$i]["bandera"]));
						}
						$id_color = $db->lastInsertId();
						
						for ($i=0; $i < count($resp[0]); $i++) { 
							$stmt4 = $db->prepare("INSERT INTO estado_tarea(FKColorColumna,FKTarea) VALUES(?,?)");
							$stmt4->execute(array($id_color,$resp[0][$i]['PKTarea']));
							$id_tarea = $db->lastInsertId();
							array_push($identificador_tareas, $id_tarea);
						}

						$respuesta = [
							"Nombre"=>$nombre_nuevo,
							"Orden"=>$resp['Orden'],
							"PKColumnaProyecto"=>$resp['id_columna'],
							"Tipo"=>$tipo,
							"id"=>$id_tarea,
							"Texto"=> " ",
							"color"=>"#9a9a9a",
							$identificador_tareas
						];
						
						return $respuesta;
					}

					if ($tipo == 3) {//Columna tipo fecha
						$query=sprintf("SELECT * FROM columnas_proyecto WHERE tipo=? AND nombre='Fecha' AND FKProyecto=?");	
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
						}else $nombre_nuevo="Fecha";
						
						$comun = new add_data();
						$resp = $comun->columnComun($nombre_nuevo,$id,$tipo,$tabla);

						//Por cada tarea se le asignará un estado default
						for ($i=0; $i < count($resp[0]); $i++) { 
							$stmt4 = $db->prepare("INSERT INTO fecha_tarea(Fecha,FKTarea,FKColumnaProyecto,FKProyecto) VALUES(?,?,?,?)");
							$stmt4->execute(array("0000-00-00",$resp[0][$i]['PKTarea'], $resp['id_columna'],$id));
							$id_tarea = $db->lastInsertId();
							array_push($identificador_tareas, $id_tarea);
						}

						$respuesta = [
							"Nombre"=>$nombre_nuevo,
							"Orden"=>$resp['Orden'],
							"PKColumnaProyecto"=>$resp['id_columna'],
							"Tipo"=>$tipo,
							"id"=>$id_tarea,
							"Texto"=>"0000-00-00",
							$identificador_tareas
						];
						
						return $respuesta;
					}
				}
				
				
			}catch(PDOException $e){
				return "Error en Consulta: ".$e->getMessage();
			}

			$stmt= NULL;
			$db = NULL;
		}

		function columnComun($nombre_nuevo, $id, $tipo, $tabla){
			$con = new conectar();
			$db = $con->getDb();

			try{
				//Orden que le corresponderá a la columna
				$query1=sprintf("SELECT Orden FROM columnas_proyecto WHERE FKProyecto=? ORDER BY Orden DESC");	
				$stmt2 = $db->prepare($query1);
				$stmt2->execute(array($id));
				$data1 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
				if ( (count($data1) ) == 0) {//Si no hay columnas agregadas:
					//El orden será el uno
					$ordenY = 1;
				}else{//Si hay columnas agregadas:
					//El orden será el mayor número en la columna "orden" + 1
					$mayor = $data1[0]['Orden'];
					$ordenY = $mayor+1;
				}
				//Insertando la nueva columna en la tabla columnas_proyecto
				$insert = sprintf("INSERT INTO columnas_proyecto(nombre, tipo, Orden, FKProyecto) VALUES(?,?,?,?)");
				$stmt3 = $db->prepare($insert);
				$stmt3->execute(array($nombre_nuevo,$tipo,$ordenY,$id));
				$id_columna = $db->lastInsertId();

				//Consultando el total de tareas que existen en el proyecto
				$tareas = sprintf("SELECT PKTarea FROM tareas WHERE FKProyecto = ? ORDER BY Orden");
				$stmt3 = $db->prepare($tareas);
				$stmt3->execute(array($id));
				$tareasRows = $stmt3->fetchAll(PDO::FETCH_ASSOC);
				//var_dump($tareasRows);
				return $resp= [
						"id_columna"=>$id_columna,
						"Orden"=>$ordenY,
						$tareasRows
					];
			}catch(PDOException $e){
				return "Error en Consulta: ".$e->getMessage();
			}

			$stmt= NULL;
			$db = NULL;
		}//fin de la función columnComun

		function addTask($id_etapa,$id_proyecto){
			$con = new conectar();
			$db = $con->getDb();	
			$nombre_nuevo="";
			$up1 = new acciones();
			$aritmetica="sumar";
			$newOrderTareas = [];
			$upnow=[];
			$id_tarea;

			try{
				$verificar = sprintf("SELECT * FROM tareas WHERE Tarea='Tarea 1' AND FKProyecto=?");
				$stmt = $db->prepare($verificar);
				$stmt->execute(array($id_proyecto));
				$cuenta = $stmt->rowCount();				
				//Si el nombre ya existe va probando con un consecutivo hasta encontrar uno inexistente del 1 al 100
				if ($cuenta>0){
					for ($a=2;$a<100;$a++){
						$pn="Tarea ".$a;
						$stmt1 = $db->prepare("SELECT * FROM tareas WHERE Tarea=? AND FKProyecto=?");
						$stmt1->execute(array($pn,$id_proyecto));
						$cuenta1 = $stmt1->rowCount();
						if ($cuenta1==0){
							$nombre_nuevo="Tarea ".$a;
							$a=100;
						}
					}
				}else $nombre_nuevo="Tarea 1";

				//Verificar que existan tareas:
				$query = sprintf("SELECT PKTarea FROM tareas WHERE FKProyecto=?");
				$statment = $db->prepare($query);
				$statment->execute(array($id_proyecto));
				$count = $statment->rowCount();

				if ($count!=0) { //Si existen tareas dentro del proyecto
					//Orden de la etapa
					$ordenE = sprintf("SELECT Orden,color FROM etapas WHERE PKEtapa=? ORDER BY Orden DESC");
					$stEt = $db->prepare($ordenE);
					$stEt->execute(array($id_etapa));
					$etapasO = $stEt->fetch(PDO::FETCH_ASSOC);
					$pos = $etapasO['Orden'];
					//Orden de las tareas dentro de la etapa:
					$orden = sprintf("SELECT Orden FROM tareas WHERE FKEtapa=? ORDER BY Orden DESC");
					$stmt2 = $db->prepare($orden);
					$stmt2->execute(array($id_etapa));
					$tareas = $stmt2->fetchAll(PDO::FETCH_ASSOC);

					if ((count($tareas)) == 0) {//Si no hay tareas agregadas en la etapa:
						//Verificar si la etapa está en la posición 1:
						if ($pos==1) {//Que esté en la posición 1 del proyecto
							//El orden de la tarea será el uno, mientras que el orden de las demás aumentara en consecutivo
							$ordenY = 1;
							//$upnow = $up1->sumaOResta($ordenY,$id_proyecto,$aritmetica);
						}else{//No está en la posición 1, Verificar el total de etapas y el último número en orden de las tareas:

							$orden2 = sprintf("SELECT Orden FROM tareas WHERE FKProyecto=? ORDER BY Orden DESC");
							$stmt21 = $db->prepare($orden2);
							$stmt21->execute(array($id_proyecto));
							$tareas2 = $stmt21->fetchAll(PDO::FETCH_ASSOC);

							$etapas = sprintf("SELECT Orden FROM etapas WHERE FKProyecto=? ORDER BY Orden DESC");
							$stmtE = $db->prepare($etapas);
							$stmtE->execute(array($id_proyecto));
							$total = $stmtE->fetchAll(PDO::FETCH_ASSOC);
							//La mayor posición de las etapas (última en orden) es igual a la posición de la etapa.
							if ($total[0]['Orden'] == $pos) {
								$mayor = $tareas2[0]['Orden'];
								$ordenY = $mayor+1;
							}else{//Si la posición de la etapa no es la primera ni la ultima.
								$ord = $pos-1;
								$tareas3 = [];
								for ($i=$ord; $i > 0; $i--) {  //ejmeplo: es la posición 3 de 4 etapas y la etapa 2 no tiene tareas
									//Obtener el id de la etapa que en orden es la inmediata superior y tiene tareas
									$plusFromUp = sprintf("SELECT PKEtapa FROM etapas WHERE Orden = ? AND FKProyecto=?");
									$st = $db->prepare($plusFromUp);
									$st->execute(array($i, $id_proyecto));
									$etapa = $st->fetch(PDO::FETCH_ASSOC);
									//var_dump($etapa);
									//Obtener el número más alto en orden de esa etapa inmediata superior
									$ordenT = sprintf("SELECT Orden FROM tareas WHERE FKEtapa=? ORDER BY Orden DESC");
									$st2 = $db->prepare($ordenT);
									$st2->execute(array($etapa['PKEtapa']));
									$tareas3 = $st2->fetchAll(PDO::FETCH_ASSOC);
									//Si la etapa inmediata superior tiene elementos:
									if ((count($tareas3)) != 0) {
										$i=0;
									}
								}
								//Si ninguna etapa superior tiene elementos:
								if ((count($tareas3)) == 0) {
									$ordenY = 1;
								}else{//tarea de orden mayor de la etapa con elementos
									$mayor = $tareas3[0]['Orden'];
									$ordenY = $mayor+1;
								}
								
							}
						}

					}else{//Si hay tareas agregadas dentro de la etapa:
						//El orden de la tarea será el mayor número dentro de las tareas en la etapa 
						$mayor = $tareas[0]['Orden'];
						$ordenY = $mayor+1;
						//$upnow = $up1->sumaOResta($ordenY,$id_proyecto,$aritmetica);
					}

					$insertar = sprintf("INSERT INTO tareas(Tarea,Orden,FKProyecto,Terminada,FKEtapa) VALUES(?,?,?,?,?)");
					$stmt3 = $db->prepare($insertar);
					$stmt3->execute(array($nombre_nuevo,$ordenY,$id_proyecto,0,$id_etapa));
					$id_tarea = $db->lastInsertId();

					$upnow = $up1->sumaOResta($ordenY,$id_proyecto,$aritmetica,$id_tarea);
				}else{//No existen tareas en el proyecto:
					$ordenY = 1;

					$insertar = sprintf("INSERT INTO tareas(Tarea,Orden,FKProyecto,Terminada,FKEtapa) VALUES(?,?,?,?,?)");
					$stmt3 = $db->prepare($insertar);
					$stmt3->execute(array($nombre_nuevo,$ordenY,$id_proyecto,0,$id_etapa));
					$id_tarea = $db->lastInsertId();
				}
				
				//Columnas:
				$columnas = sprintf("SELECT PKColumnaProyecto,tipo,Orden FROM columnas_proyecto WHERE FKProyecto=? ORDER BY tipo");
				$stC = $db->prepare($columnas);
				$stC->execute(array($id_proyecto));
				$all_columns = $stC->fetchAll(PDO::FETCH_ASSOC);

				if ((count($all_columns)) == 0) {//No existen columnas en el proyecto
					return $resp = [
						"id_etapa" => $id_etapa,
						"id_tarea" => $id_tarea,
						"orden" => $ordenY,
						"nombre" => $nombre_nuevo,
						$upnow //Lista de las tareas con el orden actualizado
					];
				}else{//Si existen columnas dentro del proyecto
					$elementos = new add_data();
					$resp = $elementos->addElementsFromTask($all_columns,$id_tarea,$id_proyecto);
					/*
						$id_etapa (id de la etapa donde se agrego la tarea)
						$id_tarea (id de la tarea que se creó)
						$nombre_nuevo (Nombre que se le asignó a la tarea)
						$all_columns (Array del que se obtendrá el orden de las columnas)
								(Id de cada elemento creado para la tarea)
					*/
					if ($resp == "ok") {
						$dataElements=[];
						//2:estado_tarea.
						$tareas_estados=[];
						//1:responsables_tarea.
						$responsables=[];
						//3:fecha_tarea
						$fechas=[];
						for ($i=0; $i < count($all_columns); $i++) { 
							if ($all_columns[$i]['tipo'] == 1) {
								$query = sprintf("SELECT PKResponsable as id, FKUsuario as Texto,FKTarea,PKColumnaProyecto, Tipo, columnas_proyecto.Orden as cOrden, tareas.FKEtapa, tareas.Tarea, tareas.Orden as tOrden FROM responsables_tarea LEFT JOIN columnas_proyecto ON FKColumnaProyecto=PKColumnaProyecto LEFT JOIN tareas ON FKTarea=PKTarea WHERE FKTarea=?");
								$stmt = $db->prepare($query);
								$stmt->execute(array($id_tarea));
								$responsables = $stmt->fetchAll(PDO::FETCH_ASSOC);
								//var_dump($responsables);
							}
							if ($all_columns[$i]['tipo'] == 2) {
								/*"SELECT PKEstadoTarea as id,Estado as Texto, Color,FKTarea,FKColumnaProyecto,tipo, columnas_proyecto.orden as cOrden, tareas.FKEtapa, tareas.Tarea, tareas.Orden as tOrden FROM estado_tarea LEFT JOIN columnas_proyecto ON FKColumnaProyecto=PKColumnaProyecto LEFT JOIN tareas ON FKTarea=PKTarea WHERE FKTarea=?"*/
								$query = sprintf("SELECT PKEstadoTarea as id, FKColorColumna, colores_columna.color, colores_columna.nombre as Texto, colores_columna.FKProyecto, FKTarea, columnas_proyecto.Orden as cOrden, Tipo,tareas.FKEtapa,tareas.Tarea, tareas.Orden as tOrden, PKColumnaProyecto FROM estado_tarea LEFT JOIN colores_columna ON FKColorColumna = PKColorColumna LEFT JOIN columnas_proyecto ON FKColumnaProyecto=PKColumnaProyecto LEFT JOIN tareas ON FKTarea = PKTarea WHERE FKTarea=?");
								$stmt = $db->prepare($query);
								$stmt->execute(array($id_tarea));
								$tareas_estados = $stmt->fetchAll(PDO::FETCH_ASSOC);
							}
							if ($all_columns[$i]['tipo'] == 3) {
								$query = sprintf("SELECT PKFecha as id, Fecha as Texto,FKTarea, PKColumnaProyecto,Tipo,columnas_proyecto.Orden as cOrden, tareas.FKEtapa,tareas.Tarea,tareas.Orden as tOrden FROM fecha_tarea LEFT JOIN columnas_proyecto ON FKColumnaProyecto=PKColumnaProyecto LEFT JOIN tareas ON FKTarea=PKTarea WHERE FKTarea = ?");
								$stmt = $db->prepare($query);
								$stmt->execute(array($id_tarea));
								$fechas = $stmt->fetchAll(PDO::FETCH_ASSOC);
							}
						}
						$resultado = array_merge($responsables,$tareas_estados,$fechas);

						$ordenamiento = array_column($resultado, 'cOrden');
						array_multisort($ordenamiento, SORT_ASC, $resultado);
						//var_dump($resultado);
						array_push($dataElements, $resultado);

						return $array = [
							$dataElements,
							$upnow //Lista de las tareas con el orden actualizado
						];
					}else{
						return $resp;
					}
				}

				
				
			}catch(PDOException $e){
				return "Error en Consulta: ".$e->getMessage();
			}

			$stmt= NULL;
			$db = NULL;
		} //Termina función addTask
		
		function addElementsFromTask($array,$id_tarea,$id_proyecto){
			$con = new conectar();
			$db = $con->getDb();
			
			for ($i=0; $i < count($array); $i++) { 
				if ($array[$i]['tipo']==1) {//Responsable
					$stmt = $db->prepare("INSERT INTO responsables_tarea(FKTarea,FKColumnaProyecto,FKProyecto) VALUES(?,?,?)");
					$stmt->execute(array($id_tarea,$array[$i]['PKColumnaProyecto'],$id_proyecto));
				}
				if ($array[$i]['tipo']==2) {//Estado
					
					$stmt = $db->prepare("SELECT PKColorColumna FROM colores_columna WHERE FKColumnaProyecto = ? AND bandera = ?");
					$stmt->execute(array($array[$i]['PKColumnaProyecto'], 1));
					$data = $stmt->fetch(PDO::FETCH_ASSOC);
					$id_color = $data['PKColorColumna'];
					
					$stmt4 = $db->prepare("INSERT INTO estado_tarea(FKColorColumna,FKTarea) VALUES(?,?)");
					$stmt4->execute(array($id_color,$id_tarea));
					
					/*
					$stmt = $db->prepare("INSERT INTO estado_tarea(Estado,Color,FKTarea,FKColumnaProyecto,FKProyecto) VALUES(?,?,?,?,?)");
					$stmt->execute(array("","#f5f6f8",$id_tarea,$array[$i]['PKColumnaProyecto'],$id_proyecto));
					*/
				}
				if ($array[$i]['tipo']==3) {//Fecha
					$stmt = $db->prepare("INSERT INTO fecha_tarea(Fecha,FKTarea,FKColumnaProyecto,FKProyecto) VALUES(?,?,?,?)");
					$stmt->execute(array("0000-00-00",$id_tarea,$array[$i]['PKColumnaProyecto'],$id_proyecto));
				}
			}

			return "ok";

			$stmt= NULL;
			$db = NULL;
		}

		function addGroup($id_proyecto){
			$con = new conectar();
			$db = $con->getDb();
			$orden = 1;
			$columns = [];
			$up = new acciones();
			$allGroups = [];

			$st=$db->prepare("SELECT * FROM etapas WHERE FKProyecto = ?");
			$st->execute(array($id_proyecto));
			$change = $st->fetchAll(PDO::FETCH_ASSOC);

			if ( (count($change))!=0 ) {//Si hay etapas en el proyecto
				for ($i=0; $i < count($change); $i++) { 
					$update = sprintf("UPDATE etapas SET Orden=? WHERE PKEtapa = ?");
					$dec = $db->prepare($update);
					$num = $change[$i]['Orden']+1;
					$dec->execute(array($num,$change[$i]['PKEtapa']));
				}

				$allGroups = $up->ordenEtapas($id_proyecto);
			}

			$query = sprintf("SELECT PKColumnaProyecto, nombre, tipo, Orden FROM columnas_proyecto WHERE FKProyecto = ? ORDER BY Orden ASC");
			$stmt = $db->prepare($query);
			$stmt->execute(array($id_proyecto));
			$proof = $stmt->rowCount();
			if ($proof!=0) { //Si existen columnas en el proyecto
				$columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
			}
			
			$newGroup=sprintf("INSERT INTO etapas(Etapa,Orden,FKProyecto,color) VALUES(?,?,?,?)");
			$stmt2 = $db->prepare($newGroup);
			$stmt2->execute(array("Nueva Etapa",$orden,$id_proyecto,"#1c4587"));
			$id_etapa = $db->lastInsertId();

			$etapa = [
				"PKEtapa"=>$id_etapa,
				"Orden" =>$orden,
				"Etapa" => "Nueva etapa"
			];

			array_push($etapa,$columns,$allGroups);

			return $etapa;

			$stmt= NULL;
			$db = NULL;
		}

	}//fin add_data

	class edit_data{

		function editGroup($id,$nombre){
			$con = new conectar();
			$db = $con->getDb();

			try {

				$edita = sprintf("UPDATE etapas SET Etapa=? WHERE PKEtapa=?");
				$stmt = $db->prepare($edita);
				$stmt->execute(array($nombre,$id));

				return $nombre;
				
			}catch(PDOException $e){
				return "Error en Consulta: ".$e->getMessage();
			}

			$stmt= NULL;
			$db = NULL;
		}

		function editTask($id,$nombre){
			$con = new conectar();
			$db = $con->getDb();

			try {

				$edita = sprintf("UPDATE tareas SET Tarea=? WHERE PKTarea=?");
				$stmt = $db->prepare($edita);
				$stmt->execute(array($nombre,$id));

				return $nombre;
				
			}catch(PDOException $e){
				return "Error en Consulta: ".$e->getMessage();
			}

			$stmt= NULL;
			$db = NULL;
		}

		function editColumn($id,$nombre){
			$con = new conectar();
			$db = $con->getDb();

			try {

				$edita = sprintf("UPDATE columnas_proyecto SET nombre=? WHERE PKColumnaProyecto=?");
				$stmt = $db->prepare($edita);
				$stmt->execute(array($nombre,$id));

				return $nombre;
				
			}catch(PDOException $e){
				return "Error en Consulta: ".$e->getMessage();
			}

			$stmt= NULL;
			$db = NULL;
		}

		function setLead($id,$pkR){
			$con = new conectar();
			$db = $con->getDb();

			try {

				$lider = sprintf("UPDATE responsables_tarea SET FKUsuario=? WHERE PKResponsable=?");
				$stmt = $db->prepare($lider);
				$stmt->execute(array($id,$pkR));

				return "ok";
				
			}catch(PDOException $e){
				return "Error en Consulta: ".$e->getMessage();
			}

			$stmt= NULL;
			$db = NULL;
		}

		function noLead($pkR){
			$con = new conectar();
			$db = $con->getDb();

			try {

				$lider = sprintf("UPDATE responsables_tarea SET FKUsuario=? WHERE PKResponsable=?");
				$stmt = $db->prepare($lider);
				$stmt->execute(array(null,$pkR));

				return "ok";
				
			}catch(PDOException $e){
				return "Error en Consulta: ".$e->getMessage();
			}

			$stmt= NULL;
			$db = NULL;
		}
	}//fin edit_data

	class elim_data{

		function elimColumn($id, $tipo){
			$con = new conectar();
			$db = $con->getDb();
			$tabla = "";
			try{

				$elimina = sprintf("DELETE FROM columnas_proyecto WHERE PKColumnaProyecto = ?");
				$stmt = $db->prepare($elimina);
				$stmt->execute(array($id));

				if ($tipo == 2) {
					$tabla = "estado_tarea";
				}

				if ($tipo == 1) {
					$tabla = "responsables_tarea";
				}

				if ($tipo == 3) {
					$tabla = "fecha_tarea";
				}

				/*
				$elimElementos = sprintf("DELETE FROM ".$tabla." WHERE FKColumnaProyecto= ?");
				$stmt2 = $db->prepare($elimElementos);
				$stmt2->execute(array($id));
				*/
				return "ok";
			}catch(PDOException $e){
				return "Error en Consulta: ".$e->getMessage();
			}

			$stmt= NULL;
			$db = NULL;
		}

		function elimTask($id_tarea,$id_proyecto){
			$con = new conectar();
			$db = $con->getDb();
			$aritmetica = "restar";
			$up1 = new acciones();

			try{
				$orden = sprintf("SELECT Orden FROM tareas WHERE PKTarea=?");
				$stm = $db->prepare($orden);
				$stm->execute(array($id_tarea));
				$ordTask = $stm->fetch(PDO::FETCH_ASSOC);
				$ord = $ordTask['Orden'];

				$orden2 = sprintf("SELECT Orden FROM tareas WHERE FKProyecto=? ORDER BY Orden DESC");
				$stmt21 = $db->prepare($orden2);
				$stmt21->execute(array($id_proyecto));
				$tareas2 = $stmt21->fetchAll(PDO::FETCH_ASSOC);

				$elimina = sprintf("DELETE FROM tareas WHERE PKTarea=?");
				$stmt = $db->prepare($elimina);
				$stmt->execute(array($id_tarea));

				//Si no quedan tareas:
				if ( ( count($tareas2) ) == 0 ) {
					return "ok";
				}else{
					if ($tareas2[0]['Orden']==$ord) {//Si la tarea era la última en orden del total de tareas:
						return "ok";
					}else{//Si la posición de la tarea no es la última, actualiza el orden de las tareas
						$upnow = $up1->sumaOResta($ord,$id_proyecto,$aritmetica,$id_tarea);
						return $upnow;
					}
				}
				
			}catch(PDOException $e){
				return "Error en Consulta: ".$e->getMessage();
			}

			$stmt= NULL;
			$db = NULL;
		}

		function elimGroup($id_etapa,$id_proyecto){
			$con = new conectar();
			$db = $con->getDb();
			$up = new acciones();
			try {
				//Consulta el total de etapas para determinar la acción según la posición de la etapa eliminada.
				$consulta = sprintf("SELECT * FROM etapas WHERE FKProyecto=? ORDER BY Orden DESC");
				$st1 = $db->prepare($consulta);
				$st1->execute(array($id_proyecto));
				$etapas = $st1->fetchAll(PDO::FETCH_ASSOC);
				//var_dump($etapas);

				//Comprobar si la etapa tiene tareas o está vacía:
				$comprobar = sprintf("SELECT * FROM tareas WHERE FKEtapa=? ORDER BY Orden DESC");
				$stmt = $db->prepare($comprobar);
				$stmt->execute(array($id_etapa));
				$tareasEtapa = $stmt->fetchAll(PDO::FETCH_ASSOC);
				$count = $stmt->rowCount();
				//Verificar la posición de la etapa
				$posicion = sprintf("SELECT * FROM etapas WHERE PKEtapa=?");
				$stmt = $db->prepare($posicion);
				$stmt->execute(array($id_etapa));
				$data = $stmt->fetch(PDO::FETCH_ASSOC);
				$pos = $data['Orden'];
				//Se elimina la etapa
				$delete = sprintf("DELETE FROM etapas WHERE PKEtapa=?");//Elimina la etapa
				$st = $db->prepare($delete);
				$st->execute(array($id_etapa));

				if ($count != 0) { //Qué sí hay tareas en la etapa.
					//Eliminar las tareas de la etapa eliminada
					$deleteT = sprintf("DELETE FROM tareas WHERE FKEtapa=?");//Elimina la etapa
					$stT = $db->prepare($deleteT);
					$stT->execute(array($id_etapa));
					//Si la posición de la etapa era la primera:
					if ($pos == 1) {
						//Consulta el orden actual de las tareas del proyecto por el campo Orden
						$tabla = "tareas";
						$orderByOrden = $up->orderByOrden($id_proyecto,$tabla);
						//Actualiza el orden de las tareas:
						for ($i=0; $i < count($orderByOrden); $i++) { 
							$act = $db->prepare("UPDATE tareas SET Orden=? WHERE PKTarea=?");
							$act->execute(array($i+1, $orderByOrden[$i]['PKTarea']));
						}

						//Consulta nuevo orden de las tareas:
						$newOrderTareas = $up->ordenTareas($id_proyecto);
						//Resta uno el campo Orden de la tabla "etapas".
						$orden = $up->restaEtapas($id_proyecto, $pos);

						if ($orden == "updated") {//Si existen más etapas
							$update = $up->ordenEtapas($id_proyecto);//Consulta el orden nuevo de las etapas.
							$accion = [//Identifica la acción para el JavaScript.
								"accion" => "actualizarTareas",
								"numTareas" => $count
							];
							array_push($accion,$update,$newOrderTareas);
							return $accion;
						}else{//No existen más etapas:
							$accion = [
								"accion" => "eliminar"
							];
							return $accion;
						}
					}else if ($pos == $etapas[0]['Orden']){//Verificar que la etapa sea la última
						$accion = [
							"accion" => "eEtapaATareas",
							"numTareas" => $count
						];
						return $accion;
					}else{//La etapa no es la primera ni la última:
						//Obteniendo la etapa inmediata superior a la etapa eliminada:
						/*
						$value = $pos-1;
						$PKEtapa;
						for ($i=0; $i < count($etapas); $i++) { 
							if ($etapas[$i]['Orden'] == $value) {
								$PKEtapa = $etapas[$i]['PKEtapa'];
							}
						}
						*/
						$val = $pos-1;
						$tareas3=[];
						for ($i=$val; $i > 0; $i--){  //ejmeplo: es la posición 3 de 4 etapas y la etapa 2 no tiene tareas
							//Obtener el id de la etapa que en orden es la inmediata superior y tiene tareas
							$plusFromUp = sprintf("SELECT PKEtapa FROM etapas WHERE Orden = ? AND FKProyecto=?");
							$st = $db->prepare($plusFromUp);
							$st->execute(array($i,$id_proyecto));
							$etapa = $st->fetch(PDO::FETCH_ASSOC);
							//Obtener el número más alto en orden de la tarea de esa etapa inmediata superior
							$ordenT = sprintf("SELECT Orden FROM tareas WHERE FKEtapa=? ORDER BY Orden DESC");
							$st2 = $db->prepare($ordenT);
							$st2->execute(array($etapa['PKEtapa']));
							$tareas3 = $st2->fetchAll(PDO::FETCH_ASSOC);
							//var_dump($tareas3);
							//Si la etapa inmediata superior tiene elementos:
							if ((count($tareas3)) != 0) {
								$i=0;
							}
						}

						//Si ninguna etapa superior tiene tareas:
						if ((count($tareas3)) == 0) {
							//Consulta el orden actual de las tareas del proyecto por el campo Orden
							$tabla = "tareas";
							$orderByOrden = $up->orderByOrden($id_proyecto,$tabla);
							//Actualiza el orden de las tareas:
							for ($i=0; $i < count($orderByOrden); $i++) { 
								$act = $db->prepare("UPDATE tareas SET Orden=? WHERE PKTarea=?");
								$act->execute(array($i+1, $orderByOrden[$i]['PKTarea']));
							}
							//Consulta nuevo orden de las tareas:
							$newOrderTareas = $up->ordenTareas($id_proyecto);
							//Resta uno el campo Orden de la tabla "etapas".
							$orden = $up->restaEtapas($id_proyecto, $pos);

							$update = $up->ordenEtapas($id_proyecto);//Consulta el orden nuevo de las etapas.

							$accion = [//Identifica la acción para el JavaScript.
								"accion" => "actualizarTareas",
								"numTareas" => $count
							];
							array_push($accion,$update,$newOrderTareas);
							return $accion;
							
						}else{//tarea de orden mayor de la etapa con elementos
							$mayor = $tareas3[0]['Orden'];//2
							$ordenT = $mayor+1;

							//Seleccionando las tareas mayores al último orden de la etapa con elementos
							$query = sprintf("SELECT * FROM tareas WHERE FKProyecto=? AND Orden > ".$mayor."");
							$stmt = $db->prepare($query);
							$stmt->execute(array($id_proyecto));
							$response = $stmt->fetchAll(PDO::FETCH_ASSOC);
							
							if ((count($response))!=0) {//SI hay tareas inferiores
								
								for ($i=0; $i < count($response); $i++) { 
									$update = sprintf("UPDATE tareas SET Orden = ? WHERE PKTarea = ?");
									$stmt = $db->prepare($update);
									$stmt->execute(array($ordenT, $response[$i]['PKTarea']));
									$ordenT++;
								}

								//Consulta nuevo orden de las tareas:
								$newOrderTareas = $up->ordenTareas($id_proyecto);
								//Resta uno el campo Orden de la tabla "etapas".
								$orden = $up->restaEtapas($id_proyecto, $pos);

								$update = $up->ordenEtapas($id_proyecto);//Consulta el orden nuevo de las etapas.
								$accion = [//Identifica la acción para el JavaScript.
									"accion" => "actualizarTareas",
									"numTareas" => $count
								];
								array_push($accion,$update,$newOrderTareas);
								return $accion;
							}else{//No hay tareas inferiores.
								$update = $up->ordenEtapas($id_proyecto);//Consulta el orden nuevo de las etapas.
								$accion = [//Identifica la acción para el JavaScript.
									"accion" => "actualizarArray",
									"numTareas" => $count
								];
								array_push($accion,$update);
								return $accion;
							}
							
							
						}

					}
				}else{ //No hay tareas en la etapa.
					//Si la posición de la etapa eliminada es menor a la posición de la última etapa en orden
					if ($pos < $etapas[0]['Orden']) {
						$orden = $up->restaEtapas($id_proyecto, $pos);//Resta uno el campo Orden de la tabla "etapas".
						if ($orden == "updated") {//Si se actualizaron las etapas:
							$update = $up->ordenEtapas($id_proyecto);//Consulta el orden nuevo de las etapas.
							$accion = [
								"accion" => "actualizar"
							];
							array_push($accion,$update);
							return $accion;
						}else{//No existen etapas que actualizar
							$accion = [
								"accion" => "eliminar"
							];
							return $accion;
						}
					}else{//La etapa eliminada es la última en posición.
						$accion = [
							"accion" => "eliminar"
						];
						return $accion;
					}
				}
			}catch(PDOException $e){
				return "Error en Consulta: ".$e->getMessage();
			}

			$stmt= NULL;
			$db = NULL;
		}

	}//fin elim_data

	/*****************************************/
	/*### ORDEN DE TAREAS COLUMNAS ETAPAS ###*/
	/*****************************************/
	class data_order{

		function columnOrder($id, $orden){
			$con = new conectar();
			$db = $con->getDb();

			try{
				for ($i=0; $i < count($orden); $i++) { 
					$update = sprintf("UPDATE columnas_proyecto SET Orden = ? WHERE PKColumnaProyecto = ?");
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

		function etapaOrder($id, $orden){//Cuando la terea cambia de orden dentro de la misma etapa.
			$con = new conectar();
			$db = $con->getDb();
			$up1 = new acciones();

			try{
				for ($i=0; $i < count($orden); $i++) { 
					$update = sprintf("UPDATE tareas SET Orden = ? WHERE PKTarea = ?");
					$stmt = $db->prepare($update);
					$stmt->execute(array($i+1, $orden[$i]));
				}

				$order = $up1->ordenTareas($id);

				return $order;
			}catch(PDOException $e){
				return "Error en Consulta: ".$e->getMessage();
			}

			$stmt= NULL;
			$db = NULL;
		}

		function tablaOrder($id,$orden,$tarea,$etapa){ //Cuando se reordena de una etapa a otra una tarea.
			$con = new conectar();
			$db = $con->getDb();
			$up1 = new acciones();

			try{

				for ($i=0; $i < count($orden); $i++) { 
					$update = sprintf("UPDATE tareas SET Orden = ? WHERE PKTarea = ?");
					$stmt = $db->prepare($update);
					$stmt->execute(array($i+1, $orden[$i]));
				}

				$cambio = sprintf("UPDATE tareas SET FKEtapa=? WHERE PKTarea=?");
				$stmt = $db->prepare($cambio);
				$stmt->execute(array($etapa, $tarea));

				$order = $up1->ordenTareas($id);

				return $order;
			}catch(PDOException $e){
				return "Error en Consulta: ".$e->getMessage();
			}

			$stmt= NULL;
			$db = NULL;
		}

		function groupOrder($id,$orden){//Cuando se cambia el orden de una etapa
			$con = new conectar();
			$db = $con->getDb();
			$up1 = new acciones();

			try{
				//Actualizando el orden de las etapas del proyecto.
				for ($i=0; $i < count($orden); $i++) { 
					$update = sprintf("UPDATE etapas SET Orden = ? WHERE PKEtapa = ?");
					$stmt = $db->prepare($update);
					$stmt->execute(array($i+1, $orden[$i]));
				}

				//Obteniendo las tareas dentro de esa etapa.
				$dec = $db->prepare('SELECT * FROM tareas where FKEtapa = ? ORDER BY Orden');
				$dec->execute(array($orden[0]));
				$tarea1 = $dec->fetchAll(PDO::FETCH_ASSOC);

				//Si la etapa tiene tareas:
				if ( (count($tarea1))!=0) {
					//Actualiza el orden de las tareas de esa etapa:
					$st = $db->prepare('SELECT * FROM etapas WHERE FKProyecto=? ORDER BY Orden');
					$st->execute(array($id));
					$groups = $st->fetchAll(PDO::FETCH_ASSOC);

					$cont = 1;
					for ($i=0; $i < count($groups); $i++) { //Por cada etapa

						$st = $db->prepare('SELECT * FROM tareas WHERE FKEtapa=? ORDER BY Orden');
						$st->execute(array($groups[$i]['PKEtapa']));
						$tasks = $st->fetchAll(PDO::FETCH_ASSOC);

						if ( (count($tasks))!=0) {//Si la etapa tiene tareas
							for ($j=0; $j < count($tasks); $j++) {//Por cada tarea
								$update = sprintf("UPDATE tareas SET Orden = ? WHERE PKTarea = ?");
								$stmt = $db->prepare($update);
								$stmt->execute(array($cont, $tasks[$j]['PKTarea']));
								$cont++;
							}
						}
					}

					$data=[
						"info" => "tareas" //Para indicarle al js que se van a actualizar todas las tareas.
					];

					$order = $up1->ordenTareas($id);
					$allGroups = $up1->ordenEtapas($id);
					array_push($data,$order,$allGroups);
					return $data;
				}

				$data=[
					"info" => "no" //Para indicarle al js que NO se van a actualizar las tareas.
				];

				$allGroups = $up1->ordenEtapas($id);
				array_push($data,$allGroups);
				return $data;
				
			}catch(PDOException $e){
				return "Error en Consulta: ".$e->getMessage();
			}

			$stmt= NULL;
			$db = NULL;
		}

	}//fin data_order

	class get_data{

		function getUsers(){
			$con = new conectar();
			$db = $con->getDb();

			try {
				$stmt = $db->prepare("SELECT u.PKUsuario, CONCAT(e.Primer_Nombre,' ',e.Apellido_Paterno) as nombre_empleado, u.FKEmpleado as id_empleado FROM usuarios as u INNER JOIN empleados as e ON u.FKEmpleado = e.PKEmpleado");
                    $stmt->execute();
                    $row = $stmt->fetchAll(PDO::FETCH_OBJ);

                    return $row;
			}catch(PDOException $e){
				return "Error en Consulta: ".$e->getMessage();
			}

			$stmt= NULL;
			$db = NULL;
		}

		function getAllProjects(){

			$con = new conectar();
			$db = $con->getDb();
			try{									
				$query=sprintf("SELECT * FROM proyectos"); //Where usuario id sea= $_SESSION["id"];
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
	}//fin get_data

	class acciones{

		function sumaOResta($ordenY,$id_proyecto,$aritmetica,$id_tarea){
			$con = new conectar();
			$db = $con->getDb();
			//Orden de las tareas mayores
			$orden2 = sprintf("SELECT * FROM tareas WHERE FKProyecto=? AND PKTarea!=? AND Orden >= ".$ordenY."");
			$stmtO = $db->prepare($orden2);
			$stmtO->execute(array($id_proyecto, $id_tarea));
			$tareasO = $stmtO->fetchAll(PDO::FETCH_ASSOC);

			if ($aritmetica == "sumar") {
				/*
					Se le asignará el orden mayor (a la nueva tarea) de acuerdo a la etapa que pertenece, por lo que
					sólo las tareas que ya existen con un orden mayor al asignado a la nueva tarea deberán cambiar aumentando
					+1 en cada caso
				*/
				for ($i=0; $i<count($tareasO); $i++) { 
					$stmtU = $db->prepare("UPDATE tareas SET Orden=? WHERE PKTarea=?");
					$stmtU->execute(array(($tareasO[$i]['Orden']+1),$tareasO[$i]['PKTarea']));
				}
			}else{

				for ($i=0; $i<count($tareasO); $i++) { 
					$stmtU = $db->prepare("UPDATE tareas SET Orden=? WHERE PKTarea=?");
					$stmtU->execute(array(($tareasO[$i]['Orden']-1),$tareasO[$i]['PKTarea']));
				}
			}

			//Consulta con el nuevo orden de las tareas:
			$newOrder = sprintf("SELECT PKTarea,Orden FROM tareas WHERE FKProyecto=?");
			$stmtF = $db->prepare($newOrder);
			$stmtF->execute(array($id_proyecto));
			$newOrderTareas = $stmtF->fetchAll(PDO::FETCH_OBJ);
			
			return $newOrderTareas;

			$stmt= NULL;
			$db = NULL;
		}

		function restaEtapas($id_proyecto,$pos){
			$con = new conectar();
			$db = $con->getDb();

			try {

				$resta = sprintf("SELECT * FROM etapas WHERE FKProyecto=?");
				$stmt = $db->prepare($resta);
				$stmt->execute(array($id_proyecto));
				$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

				//Si existen etapas:
				if ((count($data))!=0) {
					for ($i=0; $i < count($data); $i++) { 
						$stmt = $db->prepare("UPDATE etapas SET Orden = ? WHERE PKEtapa = ? AND Orden > ?");
						$num = $data[$i]['Orden']-1;
						$stmt->execute(array($num,$data[$i]['PKEtapa'],$pos));
					}

					return "updated";
				}else{//Si no existen etapas
					return "noGroups";
				}
				
			}catch(PDOException $e){
				return "Error en Consulta: ".$e->getMessage();
			}

			$stmt= NULL;
			$db = NULL;
		}

		function ordenTareas($id){
			$con = new conectar();
			$db = $con->getDb();

			try{
				//Consulta con el nuevo orden de las tareas:
				$newOrder = sprintf("SELECT PKTarea,Orden FROM tareas WHERE FKProyecto=?");
				$stmtF = $db->prepare($newOrder);
				$stmtF->execute(array($id));
				$newOrderTareas = $stmtF->fetchAll(PDO::FETCH_OBJ);

				return $newOrderTareas;
			}catch(PDOException $e){
				return "Error en Consulta: ".$e->getMessage();
			}

			$stmt= NULL;
			$db = NULL;
		}

		function orderByOrden($id, $tabla){
			$con = new conectar();
			$db = $con->getDb();

			try {

				$query = sprintf("SELECT * FROM ".$tabla." WHERE FKProyecto=? ORDER BY Orden");
				$stmt = $db->prepare($query);
				$stmt->execute(array($id));
				$response = $stmt->fetchAll(PDO::FETCH_ASSOC);
				return $response;
				
			}catch(PDOException $e){
				return "Error en Consulta: ".$e->getMessage();
			}

			$stmt= NULL;
			$db = NULL;
		}

		function ordenEtapas($id){
			$con = new conectar();
			$db = $con->getDb();

			try{
				//Consulta con el nuevo orden de las tareas:
				$newOrder = sprintf("SELECT PKEtapa,Orden FROM etapas WHERE FKProyecto=?");
				$stmtF = $db->prepare($newOrder);
				$stmtF->execute(array($id));
				$newOrderTareas = $stmtF->fetchAll(PDO::FETCH_OBJ);

				return $newOrderTareas;
			}catch(PDOException $e){
				return "Error en Consulta: ".$e->getMessage();
			}

			$stmt= NULL;
			$db = NULL;
		}

	}

	class buscar_data{

		function buscarTarea($inputValue,$id_proyecto){
			$con = new conectar();
			$db = $con->getDb();
			try{
				
					//consulta a solo tareas de las etapas
					//$busqueda = sprintf("SELECT * FROM tareas WHERE Tarea LIKE ? and FKProyecto = ?");
					//consulta a las etapas con sus tareas
					$busqueda = sprintf("SELECT tareas.PKTarea, tareas.Tarea,etapas.PKEtapa, etapas.Etapa FROM tareas, etapas WHERE (etapas.PKEtapa = tareas.FKEtapa AND tareas.Tarea LIKE ? AND tareas.FKProyecto = ?) OR (etapas.Etapa LIKE ? AND tareas.FKProyecto = ?)");
					$stmt = $db->prepare($busqueda);
					$stmt->execute(array("%".$inputValue."%",$id_proyecto,"%".$inputValue."%",$id_proyecto));
					$respuesta = $stmt->fetchAll(PDO::FETCH_OBJ);

					return $respuesta;

			}catch(PDOException $e){
				return "Error en Consulta: ".$e->getMessage();
			}

			$stmt= NULL;
			$db = NULL;
		}
	}