<?php
session_start();
if (isset($_SESSION["Usuario"])) {
  require_once('../../../include/db-conn.php');
  if (isset($_POST['btnEditar'])) {
    $idProyecto = (int) $_POST['idProyectoU'];
    $Proyecto = $_POST['txtProyectoU'];
    $Descripcion = $_POST['txtDescripcionU'];
    $idUsuario = $_SESSION['PKUsuario'];
    $responsables = isset($_POST['cmbIdUsuarioU']) ? $_POST['cmbIdUsuarioU'] : [];
    $usuarios = isset($_POST['cmbUsuarios']) ? $_POST['cmbUsuarios'] : [];
    $empleados = isset($_POST['cmbEmpleados']) ? $_POST['cmbEmpleados'] : [];
    $integrantes = $resultado = array_merge($usuarios, $empleados, $responsables);
    $idEquipos = isset($_POST['cmbIdEquipoU']) ? $_POST['cmbIdEquipoU'] : [];

    if ($responsables !== $idUsuario) {
      if (!in_array($idUsuario, $integrantes)) {
        $integrantes[] = $idUsuario;
      }
    }

    try {
      date_default_timezone_set('America/Mexico_City');
      $fechaModificacion = date('Y/m/d H:i:s');
      //actualizar Nombre y Responsable en DB Tabla proyectos
      $stmt = $conn->prepare('UPDATE proyectos set Proyecto = :proyecto, Descripcion = :descripcion, updated_at = :fechamodificacion WHERE PKProyecto = :idproyecto');
      $stmt->bindValue(':proyecto', $Proyecto);
      $stmt->bindValue(':descripcion', $Descripcion);
      $stmt->bindValue(':fechamodificacion', $fechaModificacion);
      $stmt->bindValue(':idproyecto', $idProyecto, PDO::PARAM_INT);
      $stmt->execute();

      //Eliminamos en DB responsables
      $stmt = $conn->prepare("DELETE FROM responsables_proyecto WHERE proyectos_PKProyecto = :idproyecto");
      $stmt->bindValue(':idproyecto', $idProyecto);
      $stmt->execute();

      //Eliminamos en DB integrantes
      $stmt = $conn->prepare("DELETE FROM integrantes_proyecto WHERE FKProyecto = :idproyecto");
      $stmt->bindValue(':idproyecto', $idProyecto);
      $stmt->execute();

      //insertar en DB responsables
      foreach ($responsables as $responsable) {
        $stmt = $conn->prepare('INSERT INTO responsables_proyecto (proyectos_PKProyecto,usuarios_id) VALUES (:idproyecto,:usuarios_id)');
        $stmt->bindValue(':idproyecto', $idProyecto);
        $stmt->bindValue(':usuarios_id', $responsable);
        $stmt->execute();
      }

      //insertar en DB integrantes_proyeto
      foreach ($integrantes as $idIntegrante) {
        $stmt = $conn->prepare('INSERT INTO integrantes_proyecto (FKProyecto,FKUsuario) VALUES (:idproyecto,:fkusuario)');
        $stmt->bindValue(':idproyecto', $idProyecto);
        $stmt->bindValue(':fkusuario', $idIntegrante);
        $stmt->execute();
      }
      header('Location:../../tareas/timDesk/index.php?id='.$idProyecto.'#task-name-null');
    } catch (PDOException $ex) {
      echo $ex->getMessage();
    }
  }
} else {
  header("location:../../dashboard.php");
}
