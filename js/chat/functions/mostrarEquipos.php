<?php
session_start();
if(isset($_SESSION["Usuario"])){
	require_once('../../../include/db-conn.php');
	
  $json = new \stdClass();

	$idProyecto = $_POST['idProyecto'];
  $IDUsuario = $_POST['IDUsuario'];
	$ruta = $_POST['ruta'];

  $stmt = $conn->prepare("SELECT FKResponsable FROM proyectos WHERE PKProyecto = :idproyecto AND FKResponsable = :IDUsuario");
  $stmt->bindValue(':idproyecto', $idProyecto);
  $stmt->bindValue(':IDUsuario', $IDUsuario);
  $stmt->execute();
  $encargado = $stmt->rowCount();

	$stmt = $conn->prepare('SELECT e.PKEquipo, e.Nombre_Equipo FROM proyectos as p INNER JOIN equipos_por_proyecto as ep ON p.PKProyecto = ep.FKProyecto INNER JOIN equipos as e ON e.PKEquipo = ep.FKEquipo WHERE p.PKProyecto = :idproyecto');
  $stmt->bindValue(':idproyecto', $idProyecto);
  $stmt->execute();
  $row_equipos = $stmt->fetchAll();

  $stmt = $conn->prepare("SELECT e.PKEquipo, e.Nombre_Equipo, ep.FKProyecto as PKEquipoProyecto FROM equipos as e LEFT JOIN equipos_por_proyecto as ep ON ep.FKEquipo = e.PKEquipo AND ep.FKProyecto = :idproyecto");
  $stmt->bindValue(':idproyecto', $idProyecto);
  $stmt->execute();
  $row_mostrar_equipos = $stmt->fetchAll();

  $html = '<h4 class="color-blue">Equipos</h4>
  <br>';


  $html .= '
    <div class="row">
      <div class="col-md-10">
        <select placeholder="Ingresa el equipo" id="equiposAgregar" class="search_equipos SumoUnder" tabindex="-1">';

          $index = 0;
          foreach ($row_mostrar_equipos as $rme) {

            $arrayIndex[$index] = $rme['PKEquipo'];

            $html .=
                    '<option value="'.$rme['PKEquipo'].'_'.$index.'" ';

                    if(trim($rme['PKEquipoProyecto']) != ""){
                      $html .= "disabled";
                    }


              $html .=  '>'.$rme['Nombre_Equipo'].'</option>';
              $index++;

          }
            
            $html .= '</select>
            </div>
      <div class="col-md-2">
        <center><button class="btn tooltip_chat btnSinSonmbra" id="agregarEquipoBtn" data-toggle="tooltip" title="Agregar equipo"><img src="'.$ruta.'../../img/chat/agregarintegrante.svg" class="img-responsive" width="25px" style="margin-bottom: 2px;margin-right: 5px;" /></button></center>
      </div>
    </div><br>';

          foreach ($row_equipos as $re){

            $clave = array_search($re['PKEquipo'], $arrayIndex);

            $html .= '
              <div id="equipoId_'.$re['PKEquipo'].'" class="row" style="margin-bottom: 5px;">
                <div class="col-md-12">
                  <a href="#" onclick="eliminarEquipoProy('.$re['PKEquipo'].','.$clave.')" ><span class="plus-team"><img src="'.$ruta.'../../img/chat/eliminar_equipo.svg" class="img-responsive tooltip_chat" width="20px" style="margin-bottom: 2px;margin-right: 5px;" data-toggle="tooltip" title="Eliminar equipo"/></span> </a>
                  <a href="#" class="color-blue enlaceIntegrantes inactivo" onclick="desplegarEquipo(this,'.$re['PKEquipo'].','.$idProyecto.')" id="0"><img src="'.$ruta.'../../img/chat/equipo.svg" class="img-responsive" width="20px" style="margin-bottom: 2px;margin-right: 5px;"/> '.$re['Nombre_Equipo'].'<span class="plus-team"> <img src="'.$ruta.'../../img/chat/menu_desplegable.svg" class="contraerEquipo img-responsive tooltip_chat" width="20px" style="margin-bottom: 2px;margin-right: 5px;" id="imgEquipo_'.$re['PKEquipo'].'" data-toggle="tooltip" title="Desplegar" /> </span></a>
                </div>
                <div class="col-md-12 mostrarIntegrantesClass" id="mostrarIntegrantes_'.$re['PKEquipo'].'">

                </div>
              </div>';

          }

	$html .= '<div id="nuevosEquipos"></div>
        </div>';

  $json->encargado = $encargado;
  $json->html = $html;
  $json = json_encode($json);
  echo $json;

}

?>