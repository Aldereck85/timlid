<?php
require_once '../../../include/db-conn.php';

date_default_timezone_set('America/Mexico_City');
session_start();
$timestamp = date('Y-m-d H:i:s');
$guardar = $_POST['Texto'];
$idusuario = $_POST['IDUsuario'];
$idalertas = $_POST['IDAlertas'];
$idtarea = $_POST['IDTarea'];

$json = new \stdClass();

try {
    $stmt = $conn->prepare('INSERT INTO chat (Contenido, FKUsuario, FechaAlta, FKTarea, Anclar, Tipo, ChatPadre) VALUES(:content, :idusuario, NOW(), :fktarea, :anclar, :tipo, :padre)');
    $stmt->bindValue(':content', $guardar);
    $stmt->bindValue(':idusuario', $idusuario);
    $stmt->bindValue(':fktarea', $idtarea);
    $stmt->bindValue(':anclar', 0);
    $stmt->bindValue(':tipo', 0);
    $stmt->bindValue(':padre', 0);
    if ($stmt->execute()) {
        $id = $conn->lastInsertId();

        //agrega id de notificaciones
        if ($idalertas[0] != -1) {
            foreach ($idalertas as $al) {
                $stmt = $conn->prepare('INSERT INTO chat_notificaciones (FKUsuario, FKUsuarioMencion, FKChat, Visto, FechaCreacion, FKTipoNotificacion) VALUES(:usuario, :usuariomencion, :chat, 0, NOW(), 2)');
                $stmt->bindValue(':usuario', $idusuario);
                $stmt->bindValue(':usuariomencion', $al);
                $stmt->bindValue(':chat', $id);
                $stmt->execute();
            }
        }

        /* NOTIFICACIONES */
        $query = sprintf("SELECT FKUsuario, FKProyecto FROM responsables_tarea WHERE FKTarea = ? AND EXISTS (SELECT 1 FROM usuarios)");
        $stmt = $conn->prepare($query);
        $stmt->execute(array($idtarea));
        $tareas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $count = $stmt->rowCount();
        if ($count > 0) {
            foreach ($tareas as $tarea) {
                if ($tarea['FKUsuario'] > 0) {
                    /* INSERTAMOS LA NOTIFICACION EN LA BD */
                    $stmt = $conn->prepare('INSERT INTO notificaciones (tipo_notificacion, detalle_tipo_notificacion, id_elemento, id_sub_elemento, created_at, usuario_creo, usuario_recibe) VALUES (:tipoNot, :detaleNot, :idElem, :idSubElem, :fecha, :usrCreo, :usrRecibe)');
                    $stmt->execute([':tipoNot' => 2, ':detaleNot' => 3, ':idElem' => $tarea['FKProyecto'], ':idSubElem' => $idtarea, ':fecha' => $timestamp, ':usrCreo' => $_SESSION['PKUsuario'], ':usrRecibe' => $tarea['FKUsuario']]);
                }
            }
        }

        //Ingresar para los visto
        $stmt = $conn->prepare('REPLACE INTO chat_vistos (FKUsuario, FKChat) VALUES(:idusuario, :fkchat)');
        $stmt->bindValue(':idusuario', $idusuario);
        $stmt->bindValue(':fkchat', $id);
        $stmt->execute();

        $mes = array('enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');
        $orgFecha = date("Y-m-d h:i A");
        $division = explode(" ", $orgFecha);

        $divisionFecha = explode("-", $division[0]);
        $hora = date("h:i A", strtotime($orgFecha));
        $mes_nombre_ini = $mes[$divisionFecha[1] - 1];
        $fecha = $divisionFecha[2] . " de " . $mes_nombre_ini . " " . $divisionFecha[0] . " " . $hora;
        $json->fecha = $fecha;

        $json->res = $id;
        $json = json_encode($json);
        echo $json;
    }
} catch (\Throwable $th) {
    echo $th;
}
