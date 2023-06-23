<?php

class conection
{
  function getDb()
  {
    require_once '../include/db-conn.php';
    return $conn;
  }
}

class get_data
{
  function getNotiTask($value)
  {
    $con = new conection();
    $db = $con->getDb();

    try {
      $query = sprintf('SELECT taskNoti.PKTareaNotificacion,taskNoti.FechaCreacion,task.PKTarea,task.Tarea,project.PKProyecto, taskNoti.FKTipoNotificacion
                                FROM tarea_notificaciones AS taskNoti
                                LEFT JOIN tareas AS task ON taskNoti.FKTarea = task.PKTarea
                                LEFT JOIN proyectos AS project ON task.FKProyecto = project.PKProyecto
                                WHERE taskNoti.FKResponsableTarea = ? AND taskNoti.Visto = 0');
      $stmt = $db->prepare($query);
      $stmt->execute(array($value));
      $array = $stmt->fetchAll(PDO::FETCH_OBJ);
    } catch (PDOException $e) {
      return "Error en Consulta: " . $e->getMessage();
    }
    return $array;

    $con = "";
    $db = "";
  }

  function getNotiChat($value)
  {
    $con = new conection();
    $db = $con->getDb();

    try {
      $query = sprintf('SELECT chatNoti.PKChat_Notificaciones,chatNoti.FechaCreacion,task.Tarea,task.PKTarea,project.PKProyecto, chatNoti.FKTipoNotificacion
                                FROM chat_notificaciones AS chatNoti
                                LEFT JOIN chat AS ch ON chatNoti.FKChat = ch.PKChat
                                LEFT JOIN tareas AS task ON ch.FKTarea = task.PKTarea
                                LEFT JOIN proyectos AS project ON task.FKProyecto = project.PKProyecto
                                WHERE chatNoti.FKUsuario = ? AND chatNoti.Visto = 0');

      $stmt = $db->prepare($query);
      $stmt->execute(array($value));
      $array = $stmt->fetchAll(PDO::FETCH_OBJ);
    } catch (PDOException $e) {
      return "Error en Consulta: " . $e->getMessage();
    }
    return $array;

    $con = "";
    $db = "";
  }

  function getNotiSubTask($value)
  {
    $con = new conection();
    $db = $con->getDb(PDO::FETCH_OBJ);

    try {
      $query = sprintf('SELECT subTaskNoti.PKSubTareaNotificacion,subTaskNoti.FechaCreacion,subtask.SubTarea,task.Tarea,task.PKTarea,project.PKProyecto, subTaskNoti.FKTipoNotificacion
                                FROM subtarea_notificaciones AS subTaskNoti
                                LEFT JOIN subtareas AS subtask ON subTaskNoti.FKSubTarea = subtask.PKSubTarea
                                LEFT JOIN tareas AS task ON subtask.FKTarea = task.PKTarea
                                LEFT JOIN proyectos AS project ON task.FKProyecto = project.PKProyecto
                                WHERE subTaskNoti.FKResponsableSubTarea = :id AND subTaskNoti.Visto = 0');
      $stmt = $db->prepare($query);
      $stmt->execute(array($value));
      $array = $stmt->fetchAll(PDO::FETCH_OBJ);
    } catch (PDOException $e) {
      return "Error en Consulta: " . $e->getMessage();
    }

    return $array;

    $con = "";
    $db = "";
  }

  function getNotiVerification($value)
  {
    $con = new conection();
    $db = $con->getDb();

    try {
      $query = sprintf('SELECT checkNoti.PKVerificacionNotificacion,checkNoti.FechaCreacion,task.Tarea,task.PKTarea,checkNoti.FKTarea,project.PKProyecto, checkNoti.FKTipoNotificacion
                                FROM verificacion_notificaciones AS checkNoti
                                LEFT JOIN tareas AS task ON checkNoti.FKTarea = task.PKTarea
                                LEFT JOIN proyectos AS project ON task.FKProyecto = project.PKProyecto
                                WHERE checkNoti.FKResponsableTarea = ? AND checkNoti.Visto = 0');
      $stmt = $db->prepare($query);
      $stmt->execute(array($value));

      $array = $stmt->fetchAll(PDO::FETCH_OBJ);
    } catch (PDOException $e) {
      return "Error en Consulta: " . $e->getMessage();
    }

    return $array;

    $con = "";
    $db = "";
  }

  function getCountNotiTask($value)
  {
    return count($this->getNotiTask($value));
  }

  function getCountNotiChat($value)
  {
    return count($this->getNotiChat($value));
  }

  function getCountNoti($value, $ruta)
  {
    $con = new conection();
    $db = $con->getDb($ruta);

    try {
      $query = sprintf('SELECT taskNoti.PKTareaNotificacion,taskNoti.FechaCreacion,task.PKTarea,task.Tarea,project.PKProyecto, taskNoti.FKTipoNotificacion
                                FROM tarea_notificaciones AS taskNoti
                                LEFT JOIN tareas AS task ON taskNoti.FKTarea = task.PKTarea
                                LEFT JOIN proyectos AS project ON task.FKProyecto = project.PKProyecto
                                WHERE taskNoti.FKResponsableTarea = ? AND taskNoti.Visto = 0');
      $stmt = $db->prepare($query);
      $stmt->execute(array($value));
      $countTask = $stmt->rowCount();

      $query = sprintf('SELECT chatNoti.PKChat_Notificaciones,chatNoti.FechaCreacion,task.Tarea,task.PKTarea,project.PKProyecto, chatNoti.FKTipoNotificacion
                                FROM chat_notificaciones AS chatNoti
                                LEFT JOIN chat AS ch ON chatNoti.FKChat = ch.PKChat
                                LEFT JOIN tareas AS task ON ch.FKTarea = task.PKTarea
                                LEFT JOIN proyectos AS project ON task.FKProyecto = project.PKProyecto
                                WHERE chatNoti.FKUsuario = ? AND chatNoti.Visto = 0');

      $stmt = $db->prepare($query);
      $stmt->execute(array($value));
      $countChat = $stmt->rowCount();

      $query = sprintf('SELECT subTaskNoti.PKSubTareaNotificacion,subTaskNoti.FechaCreacion,subtask.SubTarea,task.Tarea,task.PKTarea,project.PKProyecto, subTaskNoti.FKTipoNotificacion
                                FROM subtarea_notificaciones AS subTaskNoti
                                LEFT JOIN subtareas AS subtask ON subTaskNoti.FKSubTarea = subtask.PKSubTarea
                                LEFT JOIN tareas AS task ON subtask.FKTarea = task.PKTarea
                                LEFT JOIN proyectos AS project ON task.FKProyecto = project.PKProyecto
                                WHERE subTaskNoti.FKResponsableSubTarea = :id AND subTaskNoti.Visto = 0');
      $stmt = $db->prepare($query);
      $stmt->execute(array($value));
      $countSubTask = $stmt->rowCount();

      $query = sprintf('SELECT checkNoti.PKVerificacionNotificacion,checkNoti.FechaCreacion,task.Tarea,task.PKTarea,checkNoti.FKTarea,project.PKProyecto, checkNoti.FKTipoNotificacion
                                FROM verificacion_notificaciones AS checkNoti
                                LEFT JOIN tareas AS task ON checkNoti.FKTarea = task.PKTarea
                                LEFT JOIN proyectos AS project ON task.FKProyecto = project.PKProyecto
                                WHERE checkNoti.FKResponsableTarea = ? AND checkNoti.Visto = 0');
      $stmt = $db->prepare($query);
      $stmt->execute(array($value));
      $countVerf = $stmt->rowCount();
    } catch (PDOException $e) {
      return "Error en Consulta: " . $e->getMessage();
    }

    $totalNoti = $countTask + $countChat + $countSubTask + $countVerf;

    $con = "";
    $db = "";

    return $totalNoti;
  }

  function cmp($a, $b)
  {
    return strcmp($b["FechaCreacion"], $a["FechaCreacion"]);
  }

  function getNotiTotal($value)
  {
    $con = new conection();
    $db = $con->getDb();
    $arrayGeneral = [];
    $aux = [];

    try {
      $query = sprintf('SELECT taskNoti.PKTareaNotificacion,taskNoti.FechaCreacion,task.PKTarea,task.Tarea,project.PKProyecto,project.Proyecto, taskNoti.FKTipoNotificacion
                                FROM tarea_notificaciones AS taskNoti
                                LEFT JOIN tareas AS task ON taskNoti.FKTarea = task.PKTarea
                                LEFT JOIN proyectos AS project ON task.FKProyecto = project.PKProyecto
                                WHERE taskNoti.FKResponsableTarea = ? AND taskNoti.Visto = 0');
      $stmt = $db->prepare($query);
      $stmt->execute(array($value));
      $arrayTask = $stmt->fetchAll();

      $query = sprintf('SELECT chatNoti.PKChat_Notificaciones,chatNoti.FechaCreacion,task.Tarea,task.PKTarea,project.PKProyecto,project.Proyecto, chatNoti.FKTipoNotificacion
                                FROM chat_notificaciones AS chatNoti
                                LEFT JOIN chat AS ch ON chatNoti.FKChat = ch.PKChat
                                LEFT JOIN tareas AS task ON ch.FKTarea = task.PKTarea
                                LEFT JOIN proyectos AS project ON task.FKProyecto = project.PKProyecto
                                WHERE chatNoti.FKUsuario = ? AND chatNoti.Visto = 0');

      $stmt = $db->prepare($query);
      $stmt->execute(array($value));
      $arrayChat = $stmt->fetchAll();

      $query = sprintf('SELECT subTaskNoti.PKSubTareaNotificacion,subTaskNoti.FechaCreacion,subtask.SubTarea,task.Tarea,task.PKTarea,project.PKProyecto,project.Proyecto, subTaskNoti.FKTipoNotificacion
                                FROM subtarea_notificaciones AS subTaskNoti
                                LEFT JOIN subtareas AS subtask ON subTaskNoti.FKSubTarea = subtask.PKSubTarea
                                LEFT JOIN tareas AS task ON subtask.FKTarea = task.PKTarea
                                LEFT JOIN proyectos AS project ON task.FKProyecto = project.PKProyecto
                                WHERE subTaskNoti.FKResponsableSubTarea = :id AND subTaskNoti.Visto = 0');
      $stmt = $db->prepare($query);
      $stmt->execute(array($value));
      $arraySubTask = $stmt->fetchAll();

      $query = sprintf('SELECT checkNoti.PKVerificacionNotificacion,checkNoti.FechaCreacion,task.Tarea,task.PKTarea,checkNoti.FKTarea,project.PKProyecto,project.Proyecto, checkNoti.FKTipoNotificacion
                                FROM verificacion_notificaciones AS checkNoti
                                LEFT JOIN tareas AS task ON checkNoti.FKTarea = task.PKTarea
                                LEFT JOIN proyectos AS project ON task.FKProyecto = project.PKProyecto
                                WHERE checkNoti.FKUsuarioMencion = ? AND checkNoti.Visto = 0');
      $stmt = $db->prepare($query);
      $stmt->execute(array($value));
      $arrayVerf = $stmt->fetchAll();

      $arrayGeneral = array_merge($arrayTask, $arrayChat, $arraySubTask, $arrayVerf);
    } catch (PDOException $e) {
      return "Error en Consulta: " . $e->getMessage();
    }

    $con = "";
    $db = "";

    usort($arrayGeneral, array($this, 'cmp'));

    return $arrayGeneral;
  }
}
