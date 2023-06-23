<?php
session_start();

if (isset($_SESSION["Usuario"])) {

    require_once '../../../include/db-conn.php';

    $json = new \stdClass();

    $html = '';
    date_default_timezone_set('America/Mexico_City');
    $id = $_POST['id'];
    $idactualizacion = $_POST['idactualizacion'];
    $idtarea = $_POST['idtarea'];
    $ruta = $_POST['ruta'];
    $titulo = "";
    try {
        if ($id != 0) {
            $stmt = $conn->prepare("SELECT c.*, IFNULL(CONCAT(e.Nombres,' ', e.PrimerApellido), u.Nombre) as nombre_usuario, cf.PKChatFavoritos, cl1.PKChat_likes as like1, cl2.PKChat_likes as like2, u.estado_web as Estado FROM chat as c LEFT JOIN usuarios as u ON u.id = c.FKUsuario LEFT JOIN empleados_usuarios as eu ON eu.FKUsuario = u.id LEFT JOIN empleados as e ON e.PKEmpleado = eu.FKEmpleado LEFT JOIN chat_favoritos as cf ON cf.FKChat = c.PKChat AND cf.FKUsuario = :fkusuario LEFT JOIN chat_likes as cl1 ON cl1.FKChat = c.PKChat AND cl1.FKUsuario = :usuario1 AND cl1.Tipo = 1 LEFT JOIN chat_likes as cl2 ON cl2.FKChat = c.PKChat AND cl2.FKUsuario = :usuario2 AND cl2.Tipo = 2 WHERE c.FKTarea = :id AND c.Tipo <> 2 ORDER BY c.Anclar Desc, c.PKChat Desc");
            $stmt->execute(array('usuario1' => $_SESSION['PKUsuario'], 'usuario2' => $_SESSION['PKUsuario'], 'fkusuario' => $_SESSION['PKUsuario'], ':id' => $id));
            $tipo = 1;
        }

        if ($idactualizacion != 0) {
            $stmt = $conn->prepare("SELECT c.*, IFNULL(CONCAT(e.Nombres,' ', e.PrimerApellido), u.Nombre) as nombre_usuario, cf.PKChatFavoritos, cl1.PKChat_likes as like1, cl2.PKChat_likes as like2, u.estado_web as Estado FROM chat as c LEFT JOIN usuarios as u ON u.id = c.FKUsuario LEFT JOIN empleados_usuarios as eu ON eu.FKUsuario = u.id LEFT JOIN empleados as e ON e.PKEmpleado = eu.FKEmpleado LEFT JOIN chat_favoritos as cf ON cf.FKChat = c.PKChat AND cf.FKUsuario = :fkusuario LEFT JOIN chat_likes as cl1 ON cl1.FKChat = c.PKChat AND cl1.FKUsuario = :usuario1 AND cl1.Tipo = 1 LEFT JOIN chat_likes as cl2 ON cl2.FKChat = c.PKChat AND cl2.FKUsuario = :usuario2 AND cl2.Tipo = 2 WHERE c.PKChat = :id ORDER BY c.PKChat Desc");
            $stmt->execute(array('usuario1' => $_SESSION['PKUsuario'], 'usuario2' => $_SESSION['PKUsuario'], 'fkusuario' => $_SESSION['PKUsuario'], ':id' => $idactualizacion));
            $tipo = 2;
        }

        if ($idtarea != 0) {
            $stmt = $conn->prepare("SELECT c.*, IFNULL(CONCAT(e.Nombres,' ', e.PrimerApellido), u.Nombre) as nombre_usuario, cf.PKChatFavoritos, cl1.PKChat_likes as like1, cl2.PKChat_likes as like2, u.estado_web as Estado FROM chat as c INNER JOIN chat_favoritos as cf ON cf.FKChat = c.PKChat AND cf.FKUsuario = :fkusuario LEFT JOIN usuarios as u ON u.id = c.FKUsuario LEFT JOIN empleados_usuarios as eu ON eu.FKUsuario = u.id LEFT JOIN empleados as e ON e.PKEmpleado = eu.FKEmpleado LEFT JOIN chat_likes as cl1 ON cl1.FKChat = c.PKChat AND cl1.FKUsuario = :usuario1 AND cl1.Tipo = 1 LEFT JOIN chat_likes as cl2 ON cl2.FKChat = c.PKChat AND cl2.FKUsuario = :usuario2 AND cl2.Tipo = 2 WHERE c.FKTarea = :idtarea AND c.Tipo <> 2");
            $stmt->execute(array('fkusuario' => $_SESSION['PKUsuario'], 'usuario1' => $_SESSION['PKUsuario'], 'usuario2' => $_SESSION['PKUsuario'], ':idtarea' => $idtarea));
            $tipo = 3;
        }
        $row = $stmt->fetchAll();

        if ($tipo == 1) {
            $stmt = $conn->prepare("SELECT Tarea FROM tareas WHERE PKTarea = :id");
            $stmt->execute(array(':id' => $id));
            $row_titulo = $stmt->fetch();
            $titulo = $row_titulo['Tarea'];
        }

        if ($tipo == 2) {
            $stmt = $conn->prepare("SELECT Tarea FROM tareas WHERE PKTarea = :id");
            $stmt->execute(array(':id' => $row[0]['FKTarea']));
            $row_titulo = $stmt->fetch();
            $titulo = $row_titulo['Tarea'];
            $json->FKTareaInd = $row[0]['FKTarea'];
        }

        $mes = array('enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');

        if (count($row) > 0) {
            foreach ($row as $chat) {

                //ingresar de visto
                $stmt = $conn->prepare("REPLACE INTO chat_vistos (FKChat,FKUsuario) VALUES (:fkchat,:fkusuario)");
                $stmt->bindParam(':fkchat', $chat['PKChat']);
                $stmt->bindParam(':fkusuario', $_SESSION['PKUsuario']);
                $stmt->execute();

                $stmt = $conn->prepare("SELECT FKChat FROM chat_vistos WHERE FKChat = :fkchat");
                $stmt->bindParam(':fkchat', $chat['PKChat']);
                $stmt->execute();
                $nvistos = $stmt->rowCount();

                $stmt = $conn->prepare("SELECT IFNULL(COUNT(cl.PKChat_likes),0) as cant
                                            FROM chat as c
                                              LEFT JOIN chat_likes as cl ON cl.FKChat = c.PKChat AND cl.Tipo = 1
                                              WHERE c.PKChat = :chat1
                                        UNION ALL
                                        SELECT IFNULL(COUNT(cl.PKChat_likes),0) as cant
                                            FROM chat as c
                                              LEFT JOIN chat_likes as cl ON cl.FKChat = c.PKChat AND cl.Tipo = 2
                                              WHERE c.PKChat = :chat2");
                $stmt->bindParam(':chat1', $chat['PKChat']);
                $stmt->bindParam(':chat2', $chat['PKChat']);
                $stmt->execute();
                $row_nl = $stmt->fetchAll();

                $likes_cantidad = $row_nl[0]['cant'];
                $dislikes_cantidad = $row_nl[1]['cant'];

                //Fecha formato
                $orgFecha = $chat['FechaAlta'];

                $division = explode(" ", $orgFecha);

                $divisionFecha = explode("-", $division[0]);
                $hora = date("h:i A", strtotime($division[1]));
                $mes_nombre_ini = $mes[$divisionFecha[1] - 1];
                $fecha = $divisionFecha[2] . " de " . $mes_nombre_ini . " " . $divisionFecha[0] . " " . $hora;

                //diferencia de los tiempos de la fecha
                $diferencia = strtotime(date("Y-m-d H:i:s")) - strtotime($orgFecha);

                if ($diferencia < 60) {
                    $tiempo = "Ahora";
                } elseif ($diferencia > 59 && $diferencia < 3600) {
                    $tiempo = round($diferencia / 60) . "m";
                } elseif ($diferencia > 3599 && $diferencia < 86400) {
                    $tiempo = round($diferencia / 3600) . "h";
                } elseif ($diferencia > 86399) {
                    $tiempo = round($diferencia / 86400) . "d";
                }

                if ($tipo == 2) {

                    $html .= '<div class="row">
                              <div class="col-md-12">
                                <a href="#" onclick="' . "loadChat('" . $chat['FKTarea'] . "',2)" . '">Ver todas las actualizaciones</a>
                              </div>
                            </div><br>';
                }

                $html .= '
                    <div class="actualizacionCard actualizacion_' . $chat['PKChat'] . '">
                          <div class="row">
                            <div class="col-md-10" style="position: relative;">
                              <div class="wrapper-actualizacion">
                                <div class="col-md-12 col-xs-12">
                                    <div style="float: left;">
                                      <span data-toggle="tooltip" title="' . $chat['nombre_usuario'] . '" class="tooltip_chat">
                                        <img src="' . $ruta . '../../img/chat/users.svg" class="user-img img-responsive" width="25px">
                                      </span>
                                      <span class="nombre-usuario">' . $chat['nombre_usuario'] . '</span>';

                if ($chat['Estado'] == 0) {
                    $estado_cir = 'estado-inactivo';
                    $texto_estado = 'Inactivo';
                } else {
                    $estado_cir = 'estado-activo';
                    $texto_estado = 'Activo';
                }

                $html .= '
                                      <span data-toggle="tooltip" title="' . $texto_estado . '" class="tooltip_chat ' . $estado_cir . ' estado-circulo"></span>
                                    </div>
                                    <div style="float: right;">
                                      <img src="' . $ruta . '../../img/chat/reloj.svg" class="img-responsive tooltip_chat" width="15px" data-toggle="tooltip" title="' . $fecha . '" /><span class="panel-header">' . $tiempo . '</span>
                                      <button type="button" id="recordatorioDesplegable" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <img src="' . $ruta . '../../img/chat/alertas.svg" class="img-responsive tooltip_chat" width="15px" data-toggle="tooltip" title="Recordatorio" />
                                      </button>
                                      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="recordatorioDesplegable">
                                        <li style="color:#fff;font-weight:800;">Alerta en:</li>
                                        <li><a class="dropdown-item" href="#" onclick="agregarAlerta(' . "'20m'" . ',' . $chat['PKChat'] . ');">20 minutos</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="agregarAlerta(' . "'1h'" . ',' . $chat['PKChat'] . ');">1 hora</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="agregarAlerta(' . "'3h'" . ',' . $chat['PKChat'] . ');">3 horas</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="agregarAlerta(' . "'T'" . ',' . $chat['PKChat'] . ');">Mañana</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="agregarAlerta(' . "'W'" . ',' . $chat['PKChat'] . ');">La próxima semana</a></li>
                                      </div>
                                      <span><button type="button" id="botonDesplegable" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <img src="' . $ruta . '../../img/chat/menu_desplegable.svg" class="img-responsive" width="15px">
                                      </button>
                                      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="botonDesplegable">';

                if ($tipo == 1) {
                    $html .= '<a class="dropdown-item" href="#" onclick="anclarChat(' . $chat['PKChat'] . ');"><img src="' . $ruta . '../../img/chat/anclar.svg" width="18px" class="img-responsive" style="position: relative; right: 4px;"/> Anclar a la parte superior</a>';
                }

                $html .= '<a class="dropdown-item" href="#" onclick="copiarLink(' . $chat['PKChat'] . ');"><img src="' . $ruta . '../../img/chat/copiar_enlace.svg" width="18px" class="img-responsive" style="position: relative; right: 4px;bottom: 1px;"/> Copiar enlace de actualización</a>
                                            <a class="dropdown-item" href="#" onclick="editarActualizacion(this, ' . $chat['PKChat'] . ',1);"><img src="' . $ruta . '../../img/chat/editar.svg" width="18px" class="img-responsive" style="position: relative; right: 4px;bottom: 2px;"/> Editar actualización</a>
                                            <a class="dropdown-item" href="#" onclick="eliminarActualizacion(' . $chat['PKChat'] . ');"><img src="' . $ruta . '../../img/chat/eliminar.svg" width="18px" class="img-responsive" style="position: relative; right: 4px;bottom: 2px;"/> Eliminar actualización</a>
                                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#centralCompartirActualizacion" onclick="cargarIDEnviar(' . $chat['PKChat'] . ');"><img src="' . $ruta . '../../img/chat/compartir.svg" width="18px" class="img-responsive" style="position: relative; right: 4px;"/> Compartir actualización</a>';

                if ($chat['PKChatFavoritos'] == '') {
                    $html .= '<span class="favorito_' . $chat['PKChat'] . '"><a class="dropdown-item" href="#" onclick="marcarFavorito(' . $chat['PKChat'] . ');"><img src="' . $ruta . '../../img/chat/favorito.svg" width="18px" class="img-responsive" style="position: relative; right: 4px;"/> Marcar como favorito</a></span>';
                } else {
                    $html .= '<span class="favorito_' . $chat['PKChat'] . '"><a class="dropdown-item" href="#" onclick="eliminarFavorito(' . $chat['PKChat'] . ');"><img src="' . $ruta . '../../img/chat/favorito.svg" width="18px" class="img-responsive" style="position: relative; right: 4px;"/> Eliminar de favoritos</a></span>';
                }

                $html .= '</div>
                                    </span>
                                    </div>
                                    <br><br>
                                    <div class="textoActualizacion_' . $chat['PKChat'] . ' estiloTextoMCE">
                                      ' . $chat['Contenido'] . '
                                    </div>
                                <br>
                                </div>
                                  <div class="wrapper-visto">
                                    <div class="visto">
                                        <a href="#" class="visto-sin" data-toggle="modal" data-target="#verUsuariosVisto" onclick="mostrarUsuariosVistos(' . $chat['PKChat'] . ')">
                                          <img src="' . $ruta . '../../img/chat/visto.svg" class="img-responsive ver" width="20px" /> Visto por ' . $nvistos . '
                                        </a>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            <div class="col-md-2">
                              <div class="media-ask">
                                <div class="media-top">
                                  <span data-toggle="tooltip" title="Responder" class="space_comment_up tooltip_chat">
                                    <a href="#" onclick="responder(this,' . $chat['PKChat'] . ')">
                                      <img src="' . $ruta . '../../img/chat/ask.svg" class="user-img img-responsive fill-white" width="16px" />
                                    </a>
                                  </span>
                                  <span data-toggle="tooltip" title="' . $likes_cantidad . ' me gusta" class="tooltip_chat likeTitle_' . $chat['PKChat'] . '">
                                    <a href="#" onclick="megusta(this,' . $chat['PKChat'] . ',1)">
                                      <img ';

                if ($chat['like1'] == '') {
                    $html .= '
                                      src="' . $ruta . '../../img/chat/like.svg" ';
                } else {
                    $html .= '
                                      src="' . $ruta . '../../img/chat/like_click.svg" ';
                }

                $html .= '
                                      class="user-img img-responsive likeimage_' . $chat['PKChat'] . '" width="16px" /><br>
                                    </a>
                                  </span>
                                  <span data-toggle="tooltip" title="' . $dislikes_cantidad . ' no me gusta" class="space_comment_bt tooltip_chat dislikeTitle_' . $chat['PKChat'] . '">
                                    <a href="#" onclick="nomegusta(this,' . $chat['PKChat'] . ',1)">
                                      <img ';

                if ($chat['like2'] == '') {
                    $html .= '
                                      src="' . $ruta . '../../img/chat/dislike.svg" ';
                } else {
                    $html .= '
                                      src="' . $ruta . '../../img/chat/dislike_click.svg" ';
                }

                $html .= '
                                        class="user-img img-responsive dislikeimage_' . $chat['PKChat'] . '" width="16px" /><br>
                                    </a>
                                  </span>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="responder agregarResponder_' . $chat['PKChat'] . '">';

                $stmt = $conn->prepare("SELECT c.*, IFNULL(CONCAT(e.Nombres,' ', e.PrimerApellido), u.Nombre) as nombre_usuario, cl1.PKChat_likes as like1, cl2.PKChat_likes as like2, u.estado_web as Estado FROM chat as c LEFT JOIN usuarios as u ON u.id = c.FKUsuario LEFT JOIN empleados_usuarios as eu ON eu.FKUsuario = u.id LEFT JOIN empleados as e ON e.PKEmpleado = eu.FKEmpleado LEFT JOIN chat_likes as cl1 ON cl1.FKChat = c.PKChat AND cl1.FKUsuario = :usuario1 AND cl1.Tipo = 1 LEFT JOIN chat_likes as cl2 ON cl2.FKChat = c.PKChat AND cl2.FKUsuario = :usuario2 AND cl2.Tipo = 2 WHERE c.ChatPadre = :idchat AND c.Tipo = 2 ORDER BY c.PKCHAT ASC");
                $stmt->execute(array('usuario1' => $_SESSION['PKUsuario'], 'usuario2' => $_SESSION['PKUsuario'], ':idchat' => $chat['PKChat']));
                $row_respuesta = $stmt->fetchAll();

                foreach ($row_respuesta as $rr) {

                    //ingresar de visto
                    $stmt = $conn->prepare("REPLACE INTO chat_vistos (FKChat,FKUsuario) VALUES (:fkchat,:fkusuario)");
                    $stmt->bindParam(':fkchat', $rr['PKChat']);
                    $stmt->bindParam(':fkusuario', $_SESSION['PKUsuario']);
                    $stmt->execute();

                    $stmt = $conn->prepare("SELECT FKChat FROM chat_vistos WHERE FKChat = :fkchat");
                    $stmt->bindParam(':fkchat', $rr['PKChat']);
                    $stmt->execute();
                    $nvistosr = $stmt->rowCount();

                    //Fecha formato
                    $orgFecha = $rr['FechaAlta'];

                    $division = explode(" ", $orgFecha);

                    $divisionFecha = explode("-", $division[0]);
                    $hora = date("h:i A", strtotime($division[1]));
                    $mes_nombre_ini = $mes[$divisionFecha[1] - 1];
                    $fecha = $divisionFecha[2] . " de " . $mes_nombre_ini . " " . $divisionFecha[0] . " " . $hora;

                    //diferencia de los tiempos de la fecha
                    $diferencia = strtotime(date("Y-m-d H:i:s")) - strtotime($orgFecha);

                    if ($diferencia < 60) {
                        $tiempo = "Ahora";
                    } elseif ($diferencia > 59 && $diferencia < 3600) {
                        $tiempo = round($diferencia / 60) . "m";
                    } elseif ($diferencia > 3599 && $diferencia < 86400) {
                        $tiempo = round($diferencia / 3600) . "h";
                    } elseif ($diferencia > 86399) {
                        $tiempo = round($diferencia / 86400) . "d";
                    }

                    $stmt = $conn->prepare("SELECT IFNULL(COUNT(cl.PKChat_likes),0) as cant
                                            FROM chat as c
                                              LEFT JOIN chat_likes as cl ON cl.FKChat = c.PKChat AND cl.Tipo = 1
                                              WHERE c.PKChat = :chat1
                                        UNION ALL
                                        SELECT IFNULL(COUNT(cl.PKChat_likes),0) as cant
                                            FROM chat as c
                                              LEFT JOIN chat_likes as cl ON cl.FKChat = c.PKChat AND cl.Tipo = 2
                                              WHERE c.PKChat = :chat2");
                    $stmt->bindParam(':chat1', $rr['PKChat']);
                    $stmt->bindParam(':chat2', $rr['PKChat']);
                    $stmt->execute();
                    $row_nl_r = $stmt->fetchAll();

                    $likes_cantidad_r = $row_nl_r[0]['cant'];
                    $dislikes_cantidad_r = $row_nl_r[1]['cant'];

                    $html .= '
                         <div class="actualizacionRespuestaCard actualizacion_' . $rr['PKChat'] . '">
                          <div class="row">
                            <div class="col-md-12" style="position: relative;">
                              <div class="wrapper-responder">
                                <div class="col-md-12 col-xs-12">
                                    <div style="float: left;">
                                      <span data-toggle="tooltip" title="" class="tooltip_chat" data-original-title="' . $rr['nombre_usuario'] . '">
                                        <img src="' . $ruta . '../../img/chat/users.svg" class="user-img img-responsive" width="25px">
                                      </span>
                                      <span class="nombre-usuario">' . $rr['nombre_usuario'] . '</span>';

                    if ($rr['Estado'] == 0) {
                        $estado_cir = 'estado-inactivo';
                        $texto_estado = 'Inactivo';
                    } else {
                        $estado_cir = 'estado-activo';
                        $texto_estado = 'Activo';
                    }

                    $html .= '
                                      <span data-toggle="tooltip" title="' . $texto_estado . '" class="tooltip_chat ' . $estado_cir . ' estado-circulo"></span>
                                    </div>
                                    <div style="float: right;">
                                      <span class="reloj-responder"><img src="' . $ruta . '../../img/chat/reloj.svg" class="img-responsive tooltip_chat" width="15px" data-toggle="tooltip" title="" data-original-title="' . $fecha . '"><span class="panel-header">' . $tiempo . '</span></span>
                                      <button type="button" id="editarRespuesta" onclick="editarActualizacion(this,' . $rr['PKChat'] . ',2)">
                                        <img src="' . $ruta . '../../img/timdesk/edit.svg" class="img-responsive tooltip_chat" width="15px" data-toggle="tooltip" title="" data-original-title="Editar respuesta">
                                      </button>
                                      <button type="button" id="eliminarEspecial" class="botonCancelar eliminarEspecial" onclick="eliminarActualizacion(' . $rr['PKChat'] . ');">
                                        <img src="' . $ruta . '../../img/timdesk/delete.svg" class="img-responsive tooltip_chat" width="15px" data-toggle="tooltip" title="" data-original-title="Eliminar respuesta">
                                      </button>
                                    </div>
                                    <br><br>
                                    <div class="textoActualizacion_' . $rr['PKChat'] . ' estiloTextoMCE">
                                      ' . $rr['Contenido'] . '
                                    </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="row responderEventos responderEventos_' . $rr['PKChat'] . '">
                            <div class="col-md-12" style="display: flex;">
                              <div class="col-md-4 text-center">
                                <span data-toggle="tooltip" title="" class="tooltip_chat likeTitle_' . $rr['PKChat'] . '" data-original-title="' . $likes_cantidad_r . ' me gusta">
                                  <a href="#" class="sin-enlace" onclick="megusta(this,' . $rr['PKChat'] . ',2)">
                                    <img ';
                    if ($rr['like1'] == '') {
                        $html .= '
                                        src="' . $ruta . '../../img/chat/like_blue.svg" ';
                    } else {
                        $html .= '
                                        src="' . $ruta . '../../img/chat/like_blue_click.svg" ';
                    }

                    $html .= '
                                       class="user-img img-responsive likeimage_' . $rr['PKChat'] . '" width="16px"><br>
                                  </a>
                                </span>
                              </div>
                              <div class="col-md-4 text-center">
                                <span data-toggle="tooltip" title="" class="tooltip_chat dislikeTitle_' . $rr['PKChat'] . '" data-original-title="' . $dislikes_cantidad_r . ' no me gusta">
                                  <a href="#" class="sin-enlace" onclick="nomegusta(this,' . $rr['PKChat'] . ',2)">
                                    <img ';

                    if ($rr['like2'] == '') {
                        $html .= '
                                        src="' . $ruta . '../../img/chat/dislike_blue.svg" ';
                    } else {
                        $html .= '
                                        src="' . $ruta . '../../img/chat/dislike_blue_click.svg" ';
                    }

                    $html .= '
                                    class="user-img img-responsive dislikeimage_' . $rr['PKChat'] . '" width="16px"><br>
                                  </a>
                                </span>
                              </div>
                              <div class="col-md-4">
                                <div class="visto">
                                    <a href="#" class="visto-sin" data-toggle="modal" data-target="#verUsuariosVisto" onclick="mostrarUsuariosVistos(' . $rr['PKChat'] . ')">
                                      <img src="' . $ruta . '../../img/chat/visto.svg" class="img-responsive ver" width="16px"> Visto por ' . $nvistosr . '
                                    </a>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>';

                }

                $html .= '
                        </div>
                        <div id="responder_' . $chat['PKChat'] . '" class="responderUnico">
                        </div>
                        <br class="espacio_' . $chat['PKChat'] . '">
                        ';
            }
        } else {
            $html = '<br><center><span id="noexistenactualizaciones">AÚN NO EXISTEN ACTUALIZACIONES</span></center>';
        }

        $json->titulo = $titulo;
        $json->tipo = $tipo;
        $json->html = $html;

        $json = json_encode($json);
        echo $json;
    } catch (\Throwable $th) {
        echo json_encode(array('error' => $th));
    }

}