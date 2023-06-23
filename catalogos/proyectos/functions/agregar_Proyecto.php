<?php
session_start();
date_default_timezone_set('America/Mexico_City');

if (isset($_SESSION["Usuario"])) {
    $timestamp = date('Y-m-d H:i:s');
    require_once '../../../include/db-conn.php';

    if (isset($_POST['txtProyecto'])) {
        if (isset($_POST['txtProyecto'])) {
            $Proyecto = $_POST['txtProyecto'];
            $Descripcion = $_POST['txtDescripcion'];
            $idUsuario = strval($_SESSION['PKUsuario']);
            $responsables = isset($_POST['cmbIdUsuario']) ? $_POST['cmbIdUsuario'] : [];
            $usuarios = isset($_POST['cmbUsuarios']) ? $_POST['cmbUsuarios'] : [];
            $empleados = isset($_POST['cmbEmpleados']) ? $_POST['cmbEmpleados'] : [];
            $integrantes = $resultado = array_merge($usuarios, $empleados, $responsables);
            $idEquipo = isset($_POST['cmbIdEquipo']) ? $_POST['cmbIdEquipo'] : [];

            if ($responsables !== $idUsuario) {
                if (!in_array($idUsuario, $integrantes)) {
                    $integrantes[] = $idUsuario;
                }
            }

            try {
                //insertar DB proyectos
                date_default_timezone_set('America/Mexico_City');
                $fechaIngreso = date('Y/m/d H:i:s');
                $stmt = $conn->prepare('INSERT INTO proyectos (Proyecto,FKResponsable, Descripcion, empresa_id, created_at, updated_at) VALUES (:proyecto,:fkresponsable, :descripcion, :empresa_id, :fechaingreso, :fechamodificacion)');
                $stmt->bindValue(':proyecto', $Proyecto);
                $stmt->bindValue(':fkresponsable', $idUsuario);
                $stmt->bindValue(':descripcion', $Descripcion);
                $stmt->bindValue(':empresa_id', $_SESSION['IDEmpresa']);
                $stmt->bindValue(':fechaingreso', $fechaIngreso);
                $stmt->bindValue(':fechamodificacion', $fechaIngreso);
                $stmt->execute();

                $lastInsertId = $conn->lastInsertId();

                foreach ($responsables as $responsable) {
                    $stmt = $conn->prepare('INSERT INTO responsables_proyecto (proyectos_PKProyecto,usuarios_id) VALUES (:idproyecto,:usuarios_id)');
                    $stmt->bindValue(':idproyecto', $lastInsertId);
                    $stmt->bindValue(':usuarios_id', $responsable);
                    $stmt->execute();
                }

                //insertar en DB integrantes_proyeto
                foreach ($integrantes as $idIntegrante) {
                    $stmt = $conn->prepare('INSERT INTO integrantes_proyecto (FKProyecto,FKUsuario) VALUES (:idproyecto,:fkusuario)');
                    $stmt->bindValue(':idproyecto', $lastInsertId);
                    $stmt->bindValue(':fkusuario', $idIntegrante);
                    $stmt->execute();
                }

                foreach ($usuarios as $usuario) {
                    /* INSERTAMOS LA NOTIFICACION EN LA BD */
                    $stmt = $conn->prepare('INSERT INTO notificaciones (tipo_notificacion, detalle_tipo_notificacion, id_elemento, created_at, usuario_recibe) VALUES (:tipoNot, :detaleNot, :idElem, :fecha, :usrRecibe)');
                    $stmt->execute([':tipoNot' => 1, ':detaleNot' => 14, ':idElem' => $lastInsertId, ':fecha' => $timestamp, ':usrRecibe' => $usuario]);
                }
                echo $lastInsertId;
            } catch (PDOException $ex) {
                echo $ex->getMessage();
            }
        }
    }
}
