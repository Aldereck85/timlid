<?php
session_start();
if(isset($_SESSION["Usuario"])){
	require_once('../../../include/db-conn.php');

	$json = new \stdClass();

	$idEquipo = $_POST['idEquipo'];
	$idProyecto = $_POST['idProyecto'];
	$ruta = $_POST['ruta'];
	$idEncargado = -1;
	$claveEncargado = -1;

	$stmt = $conn->prepare("SELECT u.PKUsuario, CONCAT(e.Primer_Nombre,' ',e.Apellido_Paterno) as nombreempleado, ep.FKProyecto as Activo FROM usuarios as u INNER JOIN empleados as e ON e.PKEmpleado = u.FKEmpleado LEFT JOIN integrantes_equipo as ie ON ie.FKUsuario = u.PKUsuario LEFT JOIN equipos_por_proyecto as ep ON ep.FKEquipo = ie.FKEquipo AND ep.FKProyecto = :idproyecto WHERE u.PKUsuario NOT IN (SELECT ie.FKUsuario FROM equipos_por_proyecto as ep INNER JOIN integrantes_equipo as ie ON ie.FKEquipo = ep.FKEquipo WHERE ep.FKProyecto = :idproyecto2 AND ie.FKEquipo != :idequipo) GROUP BY u.PKUsuario");
	$stmt->bindValue(':idproyecto', $idProyecto);
	$stmt->bindValue(':idproyecto2', $idProyecto);
	$stmt->bindValue(':idequipo', $idEquipo);
	$stmt->execute();
	$row_integrantes = $stmt->fetchAll();

	$html1 = '
		<br>
		<div class="row">
			<div class="col-md-10">
				<select placeholder="Ingresa el nombre" id="agregarIntegrantesUn_'.$idEquipo.'" class="search_test SumoUnder" tabindex="-1">';

					$index = 0;
					foreach ($row_integrantes as $ri) {

						$arrayIndex[$index] = $ri['PKUsuario'];

						$html1 .=
					          '<option value="'.$idEquipo.'_'.$ri['PKUsuario'].'_'.$index.'" ';

					          if(trim($ri['Activo']) != ""){
					          	$html1 .= "disabled";
					          }


					    $html1 .=  '>'.$ri['nombreempleado'].'</option>';
					    $index++;

					}
				    
				    $html1 .= '</select>
				    </div>
			<div class="col-md-2">
				<center><button class="btn tooltip_chat btnSinSonmbra" id="agregarIntegranteBtn" data-toggle="tooltip" title="Agregar integrante"><img src="'.$ruta.'../../img/chat/agregarintegrante.svg" class="img-responsive" width="25px" style="margin-bottom: 2px;margin-right: 5px;" /></button></center>
			</div>
		</div><br>';



	$stmt = $conn->prepare("SELECT e.PKEmpleado, CONCAT(e.Primer_Nombre,' ',e.Apellido_Paterno) as nombreempleado, p.PKProyecto,u.PKUsuario FROM integrantes_equipo as ie INNER JOIN usuarios as u ON ie.FKUsuario = u.PKUsuario INNER JOIN empleados as e ON e.PKEmpleado = u.FKEmpleado LEFT JOIN proyectos as p ON p.PKProyecto = :idproyecto AND p.FKResponsable = u.PKUsuario WHERE ie.FKEquipo = :idequipo");
	$stmt->bindValue(':idproyecto', $idProyecto);
	$stmt->bindValue(':idequipo', $idEquipo);
	$stmt->execute();
	$row_integrantes = $stmt->fetchAll();


	$html1 .= '<div class="equipoIndividual" id="equipo_'.$idEquipo.'">';

	foreach ($row_integrantes as $ri) {

		$clave = array_search($ri['PKUsuario'], $arrayIndex);
	
		$html1 .= '
			 
			  <div class="row elemento-equipo" id="idusuario_'.$ri['PKUsuario'].'">
	            <span class="col-md-8">
	              <span class="tooltip_chat" data-toggle="tooltip" title="'.$ri['nombreempleado'].'"><img src="'.$ruta.'../../img/chat/users.svg" class="user-img img-responsive" width="25px"></span>
	              <a href="#" class="color-blue">'.$ri['nombreempleado'].'</a>
	            </span>
	            <span class="col-md-4" align="right">';
	              

	              				
	              if(trim($ri['PKProyecto']) != ""){

	              	$idEncargado = $ri['PKUsuario'];
	              	$claveEncargado = array_search($ri['PKUsuario'], $arrayIndex);
	                
	                $html1 .= '	<a href="#" class="sin-enlace tooltip_chat" data-toggle="tooltip" title="'.$ri['nombreempleado'].' encargado del proyecto" id="0" onclick="asignarEncargado(this,\''.$clave.'_'.$idProyecto.'_'.$ri['PKUsuario'].'_'.$idEquipo.'\');">
	                		<img src="'.$ruta.'../../img/chat/estrella_activa.png" id="estrella_'.$ri['PKUsuario'].'" class="img-responsive quitarestrella">';

	              }
	              else{

	              	$html1 .= '	<a href="#" class="sin-enlace tooltip_chat" data-toggle="tooltip" title="'.$ri['nombreempleado'].' miembro del equipo" id="0" onclick="asignarEncargado(this,\''.$clave.'_'.$idProyecto.'_'.$ri['PKUsuario'].'_'.$idEquipo.'\');">
	              			<img src="'.$ruta.'../../img/chat/estrella_inactiva.png" id="estrella_'.$ri['PKUsuario'].'" class="img-responsive quitarestrella">';

	              }


	    $html1 .= '
	              </a>	              
	                	<a href="#" id="eliminarAct_'.$ri['PKUsuario'].'" class="sin-enlace tooltip_chat" data-toggle="tooltip" title="Eliminar miembro" onclick="eliminarIntegranteEquipo(\''.$clave.'_'.$ri['PKUsuario'].'_'.$idEquipo.'\');">
	                		<img src="'.$ruta.'../../img/chat/eliminar_usuario.png" class="img-responsive">
	                	</a>
	            </span>
	          </div>';
	}

	$html1 .= '</div>';

	$json->idEncargado = $idEncargado;
	$json->claveEncargado = $claveEncargado;
	$json->html1 = $html1;
	$json = json_encode($json);
	echo $json;

}

?>