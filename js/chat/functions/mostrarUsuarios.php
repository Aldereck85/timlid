<?php
session_start();
if (isset($_SESSION["Usuario"])) {
    require_once '../../../include/db-conn.php';

    $idActualizacion = $_POST['idActualizacion'];
    $ruta = $_POST['ruta'];

    try {
        $stmt = $conn->prepare("SELECT u.nombre as nombreempleado FROM chat_vistos as cv
				INNER JOIN usuarios as u ON cv.FKUsuario = u.id
				WHERE cv.FKChat = :idActualizacion");
        $stmt->bindValue(':idActualizacion', $idActualizacion);
        $stmt->execute();
        $row_usuarios = $stmt->fetchAll();

        $html1 = '<div>';

        foreach ($row_usuarios as $ru) {

            $html1 .= '

			  <div class="row elemento-equipo">
	            <span class="col-md-12">
	              <span class="tooltip_chat" data-toggle="tooltip" title="' . $ru['nombreempleado'] . '"><img src="' . $ruta . '../../img/chat/users.svg" class="user-img img-responsive" width="25px"></span>
	              <a href="#" class="color-blue">' . $ru['nombreempleado'] . '</a>
	            </span>
	          </div>';
        }

        $html1 .= '</div>';

        echo $html1;
    } catch (\Throwable $th) {
        echo $th;
    }

}