<?php
session_start();
if(isset($_SESSION["Usuario"])){
	require_once('../../../include/db-conn.php');

	$json = new \stdClass();

	$idEquipo = $_POST['idEquipo'];
	$idProyecto = $_POST['idProyecto'];

	$stmt = $conn->prepare("SELECT u.PKUsuario, CONCAT(e.Primer_Nombre,' ',e.Apellido_Paterno) as nombreempleado, ep.PKEquipoProyecto as Activo FROM usuarios as u INNER JOIN empleados as e ON e.PKEmpleado = u.FKEmpleado LEFT JOIN integrantes_equipo as ie ON ie.FKUsuario = u.PKUsuario LEFT JOIN equipos_por_proyecto as ep ON ep.FKEquipo = ie.FKEquipo AND ep.FKProyecto = :idproyecto WHERE u.PKUsuario NOT IN (SELECT ie.FKUsuario FROM equipos_por_proyecto as ep INNER JOIN integrantes_equipo as ie ON ie.FKEquipo = ep.FKEquipo WHERE ep.FKProyecto = :idproyecto2 AND ie.FKEquipo != :idequipo) GROUP BY u.PKUsuario");
	$stmt->bindValue(':idproyecto', $idProyecto);
	$stmt->bindValue(':idproyecto2', $idProyecto);
	$stmt->bindValue(':idequipo', $idEquipo);
	$stmt->execute();
	$row_integrantes = $stmt->fetchAll();

	$html1 = '<br>
		<select name="cmbIdUsuario[]" id="multiple_'.$idEquipo.'" multiple="" tabindex="-1" data-ssid="ss-35566" style="display: block;">';

	foreach ($row_integrantes as $ri) {

		$html1 .=
	          '<option value="'.$idEquipo.'_'.$ri['PKUsuario'].'" ';

	          if(trim($ri['Activo']) != ""){
	          	$html1 .= "selected";
	          }


	    $html1 .=  '>'.$ri['nombreempleado'].'</option>';

	}

	$html1 .= '</select><br>';

	$stmt = $conn->prepare("SELECT e.PKEmpleado, CONCAT(e.Primer_Nombre,' ',e.Apellido_Paterno) as nombreempleado, p.PKProyecto FROM integrantes_equipo as ie INNER JOIN usuarios as u ON ie.FKUsuario = u.PKUsuario INNER JOIN empleados as e ON e.PKEmpleado = u.FKEmpleado LEFT JOIN proyectos as p ON p.PKProyecto = :idproyecto AND p.FKResponsable = u.PKUsuario WHERE ie.FKEquipo = :idequipo");
	$stmt->bindValue(':idproyecto', $idProyecto);
	$stmt->bindValue(':idequipo', $idEquipo);
	$stmt->execute();
	$row_integrantes = $stmt->fetchAll();

/*
	echo '<div id="equipo_'.$idEquipo.'">';
	
	foreach ($row_integrantes as $ri) {
	
		echo '
			 
			  <div class="row elemento-equipo">
	            <span class="col-md-6">
	              <span data-toggle="tooltip" title="'.$ri['nombreempleado'].'"><img src="../../img/chat/users.svg" class="user-img img-responsive" width="25px"></span>
	              <a href="#" class="color-blue">'.$ri['nombreempleado'].'</a>
	            </span>
	            <span class="col-md-6" align="right">';
	              

	              if(trim($ri['PKProyecto']) != ""){
	                
	                echo '	<a href="#" data-toggle="tooltip" title="'.$ri['nombreempleado'].' encargado del proyecto">
	                		<img src="../../img/chat/estrella_activa.png" class="img-responsive">';

	              }
	              else{

	              	echo '	<a href="#" data-toggle="tooltip" title="'.$ri['nombreempleado'].' miembro del equipo">
	              			<img src="../../img/chat/estrella_inactiva.png" class="img-responsive">';

	              }


	    echo '
	              </a>	              
	                	<a href="#" onclick="removeFromSelected(11);"><img src="../../img/chat/eliminar_usuario.png" class="img-responsive"></a>
	            </span>
	          </div>';
	}

	echo '</div>';*/

/*
	$html2 = '<div class="ss-83909 ss-main" style="display: block;"><div class="ss-multi-selected"><div class="ss-values">';
	
	foreach ($row_integrantes as $ri) {
		
		$html2 .= '
			 
			  <div class="ss-value" data-id="45194798">
			  	<span class="ss-value-text">'.$ri['nombreempleado'].'</span>
			  	<span class="ss-value-delete">x</span>
			  </div>

			  ';
	}

	$html2 .= '</div></div></div>';*/

	$json->html1 = $html1;
	//$json->html2 = $html2;
	$json = json_encode($json);
	echo $json;

}

?>