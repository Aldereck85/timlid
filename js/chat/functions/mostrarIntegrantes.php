<?php
session_start();
if(isset($_SESSION["Usuario"])){
	require_once('../../../include/db-conn.php');

  $json = new \stdClass();
	
	$idProyecto = $_POST['idProyecto'];
	$ruta = $_POST['ruta'];
  $idEncargado = -1;
  $claveEncargado = -1;

  $stmt = $conn->prepare("SELECT u.PKUsuario, IFNULL(CONCAT(e.Nombres,' ', e.PrimerApellido), u.Nombre) as nombreempleado, ip.FKProyecto as Activo FROM usuarios as u LEFT JOIN empleados_usuarios as eu ON eu.FKUsuario = u.PKUsuario LEFT JOIN empleados as e ON e.PKEmpleado = eu.FKEmpleado LEFT JOIN integrantes_proyecto as ip ON ip.FKUsuario = u.PKUsuario AND ip.FKProyecto = :idproyecto");
  $stmt->bindValue(':idproyecto', $idProyecto);
  $stmt->execute();
  $row_integrantes = $stmt->fetchAll();

  $html = '<h4 class="color-blue">Integrantes</h4>
  <br>';


  $html .= '
    <div class="row">
      <div class="col-md-10">
        <select placeholder="Ingresa el usuario" id="integranteAgregar" class="search_integrantes SumoUnder" tabindex="-1">';

          $index = 0;
          foreach ($row_integrantes as $ri) {

            $arrayIndex[$index] = $ri['PKUsuario'];

            $html .=
                    '<option value="'.$ri['PKUsuario'].'_'.$index.'" ';

                    if(trim($ri['Activo']) != ""){
                      $html .= "disabled";
                    }


              $html .=  '>'.$ri['nombreempleado'].'</option>';

              $index++;

          }
            
            $html .= '</select>
            </div>
      <div class="col-md-2">
        <center><button class="btn tooltip_chat btnSinSonmbra" id="agregarIntegranteInd" data-toggle="tooltip" title="Agregar integrante"><img src="'.$ruta.'../../img/chat/agregarintegrante.svg" class="img-responsive" width="25px" style="margin-bottom: 2px;margin-right: 5px;" /></button></center>
      </div>
    </div><br>';

  $stmt = $conn->prepare("SELECT e.PKEmpleado, IFNULL(CONCAT(e.Nombres,' ', e.PrimerApellido), u.Nombre) as nombreempleado, p.PKProyecto,u.PKUsuario FROM integrantes_proyecto as ip INNER JOIN usuarios as u ON ip.FKUsuario = u.PKUsuario LEFT JOIN empleados_usuarios as eu ON eu.FKUsuario = u.PKUsuario LEFT JOIN empleados as e ON e.PKEmpleado = eu.FKEmpleado LEFT JOIN proyectos as p ON p.PKProyecto = :idproyecto AND p.FKResponsable = u.PKUsuario  WHERE ip.FKProyecto = :idproyecto2");
  $stmt->bindValue(':idproyecto', $idProyecto);
  $stmt->bindValue(':idproyecto2', $idProyecto);
  $stmt->execute();
  $row_integrantes_ind = $stmt->fetchAll();

  $html .= '<div id="integrantesIndividuales">';

  foreach ($row_integrantes_ind as $rii) {

    $clave = array_search($rii['PKUsuario'], $arrayIndex);

    $html .= '
       
        <div class="row elemento-equipo" id="idusuario_'.$rii['PKUsuario'].'">
              <span class="col-md-8">
                <span class="tooltip_chat" data-toggle="tooltip" title="'.$rii['nombreempleado'].'"><img src="'.$ruta.'../../img/chat/users.svg" class="user-img img-responsive" width="25px"></span>
                <a href="#" class="color-blue">'.$rii['nombreempleado'].'</a>
              </span>
              <span class="col-md-4" align="right">';
                        
                if(trim($rii['PKProyecto']) != ""){

                  $idEncargado = $rii['PKUsuario'];
                  $claveEncargado = array_search($rii['PKUsuario'], $arrayIndex);
                  
                  $html .= ' <a href="#" class="sin-enlace tooltip_chat" data-toggle="tooltip" title="'.$rii['nombreempleado'].' encargado del proyecto" id="1" onclick="asignarEncargado(this,\''.$clave.'_'.$idProyecto.'_'.$rii['PKUsuario'].'\');">
                      <img src="'.$ruta.'../../img/chat/estrella_activa.png" id="estrella_'.$rii['PKUsuario'].'" class="img-responsive quitarestrella">';

                }
                else{

                  $html .= ' <a href="#" class="sin-enlace tooltip_chat" data-toggle="tooltip" title="'.$ri['nombreempleado'].' miembro del proyecto" id="1" onclick="asignarEncargado(this,\''.$clave.'_'.$idProyecto.'_'.$rii['PKUsuario'].'\');">
                      <img src="'.$ruta.'../../img/chat/estrella_inactiva.png" id="estrella_'.$rii['PKUsuario'].'" class="img-responsive quitarestrella">';

                }


      $html .= '
                </a>                
                    <a href="#" id="eliminarAct_'.$rii['PKUsuario'].'" class="sin-enlace tooltip_chat" data-toggle="tooltip" title="Eliminar miembro" onclick="eliminarIntegranteProyecto(\''.$clave.'_'.$rii['PKUsuario'].'_'.$idProyecto.'\');">
                        <img src="'.$ruta.'../../img/chat/eliminar_usuario.png" class="img-responsive"></a>
              </span>
            </div>';
  }


	$html .= '
          <div id="nuevoIntegranteProyecto"></div>
        </div>';

  $json->idEncargado = $idEncargado;
  $json->claveEncargado = $claveEncargado;
  $json->html = $html;
  $json = json_encode($json);
  echo $json;

}

?>