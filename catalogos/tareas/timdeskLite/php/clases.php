<?php
date_default_timezone_set('America/Mexico_City');

class conectar
{
  public function getDb()
  {
    include "../../../../include/db-conn.php";
    return $conn;
  }
}

class enviroment
{
  function GetEvn()
  {
    include "../../../../include/db-conn.php";
    $appUrlWrite = $_ENV['APP_URL_WRITE'];
    return ['appUrlWrite' => $appUrlWrite];
  }
}

class get_data
{
  public function getTareas($idUsuario, $terminoBusqueda = "", $filtros = [])
  {
    $con = new conectar();
    $db = $con->getDb();
    $terminoBusqueda = "%" . $terminoBusqueda . "%";
    $data = array();
    $query = "SELECT tsr.id, tsr.id, tsr.fecha_tarea, tsr.status, ts.descripcion, ts.titulo, ts.recurrencia, ts.frecuencia
    FROM tareas_simples_recurrentes AS tsr
    INNER JOIN tareas_simples AS ts ON tsr.id_tarea_simple = ts.id
    WHERE ts.id_usuario = ? AND (ts.titulo LIKE ? OR ts.descripcion LIKE ?) AND tsr.active = 1";
    try {
      if ($filtros) {
        if (array_key_exists('mostrar', $filtros)) {
          $mostrarArray = $filtros['mostrar'];
          $mostrar = "AND (_FILTERS_)";
          $statusMostrar = '';
          for ($i = 0; $i < count($mostrarArray); $i++) {
            if ($i === 0) {
              $statusMostrar = "tsr.status = '$mostrarArray[$i]'";
            } else {
              $statusMostrar .= " OR tsr.status = '$mostrarArray[$i]'";
            }
          }
          $mostrar = str_replace("_FILTERS_", $statusMostrar, $mostrar);
          $query = "SELECT tsr.id, tsr.id, tsr.fecha_tarea, tsr.status, ts.descripcion, ts.titulo, ts.recurrencia, ts.frecuencia
          FROM tareas_simples_recurrentes AS tsr
          INNER JOIN tareas_simples AS ts ON tsr.id_tarea_simple = ts.id
          WHERE ts.id_usuario = ? $mostrar AND (ts.titulo LIKE ? OR ts.descripcion LIKE ?) AND tsr.active = 1";
        }

        if (array_key_exists('rangos', $filtros)) {
          $desde = $filtros['rangos'][0] == 0 ? '' : $filtros['rangos'][0];
          $hasta = $filtros['rangos'][1] == 0 ? '' : $filtros['rangos'][1];
          $rangos = " AND (_RANGOS_)";
          if ($desde || $hasta) {
            if ($desde && $hasta) {
              $rangosMostrar = "tsr.fecha_tarea >= '$desde' AND tsr.fecha_tarea <= '$hasta'";
            } else if ($desde) {
              $rangosMostrar = "tsr.fecha_tarea >= '$desde'";
            } else if ($hasta) {
              $rangosMostrar = "tsr.fecha_tarea <= '$hasta'";
            }
            $rangos = str_replace("_RANGOS_", $rangosMostrar, $rangos);
            $query .= $rangos;
          }
        }

        if (array_key_exists('orden', $filtros)) {
          $orden = implode(", ", $filtros['orden']);
          $query .= ' ORDER BY ' . $orden . ' ASC';
        }
      }
      $stmt = $db->prepare($query);
      if (!$stmt->execute([$idUsuario, $terminoBusqueda, $terminoBusqueda])) {
        throw new \Exception('Fallo al traer las tareas');
      }
      $tareas = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $data['status'] = 'success';
      $data['data'] = $tareas;
      return $data;
    } catch (Exception $e) {
      $data['status'] = 'fail';
      $data['message'] = $e->getMessage();
      return $data;
    }
  }

  public function getTarea($id)
  {
    $con = new conectar();
    $db = $con->getDb();
    $data = array();
    try {
      $stmt = $db->prepare('SELECT tsr.id, tsr.fecha_tarea, tsr.status, ts.descripcion, ts.titulo, ts.recurrencia, ts.frecuencia
      FROM tareas_simples_recurrentes AS tsr
      INNER JOIN tareas_simples AS ts ON tsr.id_tarea_simple = ts.id
      WHERE tsr.id = :id');
      if (!$stmt->execute([':id' => $id])) {
        throw new \Exception('Fallo al traer la tarea');
      }
      $tarea = $stmt->fetch(PDO::FETCH_ASSOC);
      $data['status'] = 'success';
      $data['data'] = $tarea;
      return $data;
    } catch (Exception $e) {
      $data['status'] = 'fail';
      $data['message'] = $e->getMessage();
      return $data;
    }
  }

  public static function checkRecurrencia($id)
  {
    $con = new conectar();
    $db = $con->getDb();
    $data = array();
    try {
      $stmt = $db->prepare('SELECT id_tarea_simple FROM tareas_simples_recurrentes WHERE id = :id');
      if (!$stmt->execute([':id' => $id])) {
        throw new \Exception('Fallo al traer la tarea');
      }
      $idTarea = $stmt->fetch(PDO::FETCH_OBJ);
      $idTarea = $idTarea->id_tarea_simple;

      $stmt = $db->prepare('SELECT id FROM tareas_simples_recurrentes WHERE id_tarea_simple = :id AND fecha_tarea > NOW() AND active = 0');
      if (!$stmt->execute([':id' => $idTarea])) {
        throw new \Exception('Fallo al traer la tarea');
      }

      $rowCount = $stmt->rowCount();
      $data['status'] = 'success';
      $data['rowCount'] = $rowCount;
      $data['idTarea'] = $idTarea;
      return $data;
    } catch (Exception $e) {
      $data['status'] = 'fail';
      $data['message'] = $e->getMessage();
      return $data;
    }
  }

  public function checkRecurrencias($idUsuario)
  {
    $con = new conectar();
    $db = $con->getDb();
    $data = array();
    try {
      $stmt = $db->prepare('SELECT tsr.id_tarea_simple, ts.frecuencia
      FROM tareas_simples AS ts
      INNER JOIN tareas_simples_recurrentes AS tsr ON ts.id = tsr.id_tarea_simple
      WHERE ts.recurrencia = 1 AND ts.id_usuario = :idUsuario GROUP BY id_tarea_simple');
      if (!$stmt->execute([':idUsuario' => $idUsuario])) {
        throw new \Exception('Fallo al traer las tarea');
      }
      $tareasRec = $stmt->fetchAll(PDO::FETCH_OBJ);

      foreach ($tareasRec as $tareaRec) {
        $stmt = $db->prepare('SELECT fecha_tarea FROM tareas_simples_recurrentes WHERE id_tarea_simple = :id ORDER BY fecha_tarea DESC LIMIT 1');
        if (!$stmt->execute([':id' => $tareaRec->id_tarea_simple])) {
          throw new \Exception('Fallo al traer la tarea');
        }
        $fechaRec = $stmt->fetch(PDO::FETCH_OBJ);
        $fechaRec = $fechaRec->fecha_tarea;
        if ($fechaRec >= date('Y-m-d')) {
          save_data::saveRecurrencias($tareaRec->frecuencia, $fechaRec, $tareaRec->id_tarea_simple);
        }
      }

      $data['status'] = 'success';
      return $data;
    } catch (Exception $e) {
      $data['status'] = 'fail';
      $data['message'] = $e->getMessage();
      return $data;
    }
  }
}

class save_data
{
  public function saveTarea($idUsuario, $tarea)
  {
    $con = new conectar();
    $db = $con->getDb();
    $data = array();

    $fecha = $tarea['fecha'] ? $tarea['fecha'] : NULL;
    $descripcion = $tarea['descripcion'];
    $titulo = $tarea['titulo'];
    $status = $tarea['status'];
    $recurrencia = $tarea['recurrencia'];
    $frecuencia = $tarea['frecuencia'] ? $tarea['frecuencia'] : NULL;

    try {
      $query = 'INSERT INTO tareas_simples (id_usuario, descripcion, titulo, recurrencia, frecuencia) VALUES (:idUsuario, :descripcion, :titulo, :recurrencia, :frecuencia)';
      $values = [':idUsuario' => $idUsuario, ':descripcion' => $descripcion, ':titulo' => $titulo, ':recurrencia' => $recurrencia, ':frecuencia' => $frecuencia];
      $query = sprintf($query);
      $stmt = $db->prepare($query);

      if (!$stmt->execute($values)) {
        throw new \Exception('Fallo al añadir la tarea');
      }
      $idTareaSimple = $db->lastInsertId();


      $resTareaRel = $this::saveRelacionTarea($idTareaSimple, $fecha, $status, 1);
      if ($resTareaRel['status'] != 'success') {
        throw new Exception($resTareaRel['message']);
      }

      if ($recurrencia == 1) {
        $resTareaRelRec = $this::saveRecurrencias($frecuencia, $fecha, $idTareaSimple);
        if ($resTareaRelRec['status'] != 'success') {
          throw new Exception($resTareaRelRec['message']);
        }
      }

      $data['status'] = 'success';
      $data['message'] = 'Tarea agregada correctamente';
      return $data;
    } catch (Exception $e) {
      $data['status'] = 'fail';
      $data['message'] = $e->getMessage();
      return $data;
    }
  }

  public static function saveRelacionTarea($id_tarea, $fecha, $status, $active)
  {
    $con = new conectar();
    $db = $con->getDb();
    $data = array();

    try {
      $query = 'INSERT INTO tareas_simples_recurrentes (id_tarea_simple, fecha_tarea, status, active) VALUES (:id_tarea_simple, :fecha_tarea, :status_tarea, :active)';
      $query = sprintf($query);
      $stmt = $db->prepare($query);
      $stmt->bindParam(":id_tarea_simple", $id_tarea);
      $stmt->bindParam(":fecha_tarea", $fecha);
      $stmt->bindParam(":status_tarea", $status);
      $stmt->bindParam(":active", $active);

      if (!$stmt->execute()) {
        throw new \Exception('Fallo al añadir la tarea2');
      }

      $data['status'] = 'success';
      $data['message'] = 'Tarea agregada correctamente';
      return $data;
    } catch (Exception $e) {
      $data['status'] = 'fail';
      $data['message'] = $e->getMessage();
      return $data;
    }
  }

  public static function saveRecurrencias($frecuencia, $fecha, $idTareaSimple)
  {
    $con = new conectar();
    $db = $con->getDb();
    $output = [];
    $newDate = $fecha;
    $query = 'INSERT INTO tareas_simples_recurrentes (id_tarea_simple, fecha_tarea, status, active) VALUES';
    try {
      switch ($frecuencia) {
        case 'semanal':
          for ($i = 0; $i < 50; $i++) {
            $newDate = date('Y-m-d', strtotime($newDate . ' +7 days'));
            /* SI ES LA ULTIMA ITERACION CAMBIA (,) POR (;) AL FINAL */
            $query .= $i === 49 ? " ($idTareaSimple, '$newDate', 'todo', 0); " : " ($idTareaSimple, '$newDate', 'todo', 0), ";
          }
          $stmt = $db->prepare($query);
          if (!$stmt->execute()) {
            throw new \Exception('Fallo al añadir la tarea2');
          }
          return ['status' => 'success', 'message' => 'Tarea agregada correctamente'];
        case 'mensual':
          for ($i = 0; $i < 12; $i++) {
            $newDate = date('Y-m-d', strtotime($newDate . ' +1 months'));
            /* SI ES LA ULTIMA ITERACION CAMBIA (,) POR (;) AL FINAL */
            $query .= $i === 11 ? " ($idTareaSimple, '$newDate', 'todo', 0); " : " ($idTareaSimple, '$newDate', 'todo', 0), ";
          }
          $stmt = $db->prepare($query);
          if (!$stmt->execute()) {
            throw new \Exception('Fallo al añadir la tarea2');
          }
          return ['status' => 'success', 'message' => 'Tarea juanito'];
        case 'trimestral':
          for ($i = 0; $i < 4; $i++) {
            $newDate = date('Y-m-d', strtotime($newDate . ' +3 months'));
            /* SI ES LA ULTIMA ITERACION CAMBIA (,) POR (;) AL FINAL */
            $query .= $i === 3 ? " ($idTareaSimple, '$newDate', 'todo', 0); " : " ($idTareaSimple, '$newDate', 'todo', 0), ";
          }
          $stmt = $db->prepare($query);
          if (!$stmt->execute()) {
            throw new \Exception('Fallo al añadir la tarea2');
          }
          return ['status' => 'success', 'message' => 'Tarea agregada correctamente'];
        case 'semestral':
          for ($i = 0; $i < 2; $i++) {
            $newDate = date('Y-m-d', strtotime($newDate . ' +6 months'));
            /* SI ES LA ULTIMA ITERACION CAMBIA (,) POR (;) AL FINAL */
            $query .= $i === 1 ? " ($idTareaSimple, '$newDate', 'todo', 0); " : " ($idTareaSimple, '$newDate', 'todo', 0), ";
          }
          $stmt = $db->prepare($query);
          if (!$stmt->execute()) {
            throw new \Exception('Fallo al añadir la tarea2');
          }
          return ['status' => 'success', 'message' => 'Tarea agregada correctamente'];
        case 'anual':
          for ($i = 0; $i < 2; $i++) {
            $newDate = date('Y-m-d', strtotime($newDate . ' +12 months'));
            /* SI ES LA ULTIMA ITERACION CAMBIA (,) POR (;) AL FINAL */
            $query .= $i === 1 ? " ($idTareaSimple, '$newDate', 'todo', 0); " : " ($idTareaSimple, '$newDate', 'todo', 0), ";
          }
          $stmt = $db->prepare($query);
          if (!$stmt->execute()) {
            throw new \Exception('Fallo al añadir la tarea2');
          }
          return ['status' => 'success', 'message' => 'Tarea agregada correctamente'];
        default:
          return ['status' => 'fail', 'message' => 'La recurrencia no es valida'];
      }
    } catch (\Throwable $th) {
      return ['status' => 'fail', 'message' => $th->getMessage()];
    }
  }
}

class edit_data
{
  public function editTarea($tarea)
  {
    $con = new conectar();
    $db = $con->getDb();
    $data = array();

    $id = $tarea['id'];
    $fecha = $tarea['fecha'] ? $tarea['fecha'] : NULL;
    $descripcion = $tarea['descripcion'];
    $titulo = $tarea['titulo'];
    $status = $tarea['status'];
    $recurrencia = $tarea['recurrencia'];
    $frecuencia = $tarea['frecuencia'] ? $tarea['frecuencia'] : NULL;

    try {
      $query = 'UPDATE tareas_simples_recurrentes AS tsr
      INNER JOIN tareas_simples AS ts ON tsr.id_tarea_simple = ts.id
      SET tsr.fecha_tarea = :fecha, ts.descripcion = :descripcion, ts.titulo = :titulo, tsr.status = :status, ts.recurrencia = :recurrencia, ts.frecuencia = :frecuencia WHERE tsr.id = :id';
      $values = [':fecha' => $fecha, ':descripcion' => $descripcion, ':titulo' => $titulo, ':status' => $status, ':recurrencia' => $recurrencia, ':frecuencia' => $frecuencia, ':id' => $id];

      $query = sprintf($query);
      $stmt = $db->prepare($query);
      if (!$stmt->execute($values)) {
        throw new \Exception('Fallo al editar la tarea');
      }

      if ($recurrencia == 0) {
        $resTareaRelDelete = delete_data::detele_recurrentes($id);
        if ($resTareaRelDelete['status'] != 'success') {
          throw new Exception($resTareaRelDelete['message']);
        }
      }

      if ($recurrencia == 1) {
        $resCheckTarea = get_data::checkRecurrencia($id);
        if ($resCheckTarea['status'] != 'success') {
          throw new Exception($resCheckTarea['message']);
        }
        if ($resCheckTarea['rowCount'] == 0) {
          save_data::saveRecurrencias($frecuencia, $fecha, $resCheckTarea['idTarea'],);
        }
      }

      $data['status'] = 'success';
      $data['message'] = 'Tarea editada correctamente';

      return $data;
    } catch (Exception $e) {
      $data['status'] = 'fail';
      $data['message'] = $e->getMessage();

      return $data;
    }
  }

  public function finalizarTarea($id)
  {
    $con = new conectar();
    $db = $con->getDb();
    $data = array();

    try {

      $query = 'UPDATE tareas_simples_recurrentes SET status = :status WHERE id = :id';
      $values = [':status' => 'done', ':id' => $id];

      $query = sprintf($query);
      $stmt = $db->prepare($query);
      if (!$stmt->execute($values)) {
        throw new \Exception('Fallo al finalizar la tarea');
      }

      $data['status'] = 'success';
      $data['message'] = 'Tarea finalizada correctamente';

      return $data;
    } catch (Exception $e) {
      $data['status'] = 'fail';
      $data['message'] = $e->getMessage();
      return $data;
    }
  }
}

class delete_data
{
  public function delete_tarea($id)
  {
    $con = new conectar();
    $db = $con->getDb();
    $data = array();

    try {
      $query = 'DELETE ts
      FROM tareas_simples as ts
      INNER JOIN tareas_simples_recurrentes AS tsr ON ts.id = tsr.id_tarea_simple
      WHERE tsr.id = :id';
      $stmt = $db->prepare($query);
      if (!$stmt->execute([':id' => $id])) {
        throw new \Exception('Algo salio mal');
      }

      $data['status'] = 'success';
      $data['message'] = "Se elimino la tarea correctamente";
      return $data;
    } catch (Exception $e) {
      $data['status'] = 'fail';
      $data['message'] = $e->getMessage();
      return $data;
    }
  }

  public static function detele_recurrentes($id)
  {
    $con = new conectar();
    $db = $con->getDb();
    $data = array();

    try {
      $query = 'SELECT id_tarea_simple FROM tareas_simples_recurrentes WHERE id = :id';
      $stmt = $db->prepare($query);
      if (!$stmt->execute([':id' => $id])) {
        throw new \Exception('Algo salio mal');
      }
      $idTarea = $stmt->fetch(PDO::FETCH_ASSOC);

      $query = 'DELETE FROM tareas_simples_recurrentes
      WHERE (id_tarea_simple = :id_tarea_simple AND id != :id_tarea_recurrente) AND fecha_tarea > NOW()';
      $stmt = $db->prepare($query);
      if (!$stmt->execute([':id_tarea_simple' => $idTarea['id_tarea_simple'], ':id_tarea_recurrente' => $id])) {
        throw new \Exception('Algo salio mal');
      }

      $data['status'] = 'success';
      $data['message'] = "Se elimino la tarea correctamente";
      return $data;
    } catch (Exception $e) {
      $data['status'] = 'fail';
      $data['message'] = $e->getMessage();
      return $data;
    }
  }
}

class notificaciones
{
  public function setnotification($idelemento)
  {
    $con = new conectar();
    $db = $con->getDb();

    /* NOTIFICACIONES */
    $timestamp = date('Y-m-d H:i:s');
    /* SELECCIONAMOS LOS USUARIOS DE TIPO ALMACEN */
    $stmt = $db->prepare('SELECT id FROM usuarios WHERE empresa_id = :empresaId AND role_id = :roleId');
    $stmt->execute([':empresaId' => $_SESSION['IDEmpresa'], ':roleId' => 6]);
    $empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($empleados as $empleado) {
      /* INSERTAMOS LA NOTIFICACION EN LA BD */
      $stmt = $db->prepare('INSERT INTO notificaciones (tipo_notificacion, detalle_tipo_notificacion, id_elemento, created_at) VALUES (:tipoNot, :detaleNot, :idElem, :fecha)');
      $insertedNot = $stmt->execute([':tipoNot' => 6, ':detaleNot' => 13, ':idElem' => $idelemento, ':fecha' => $timestamp]);
      $idNotification = $db->lastInsertId();
      /* RELACIONAMOS LA NITIFICACION CON EL USUARIO */
      $stmt = $db->prepare('INSERT INTO notificaciones_usuarios (id_notificacion, id_usuario) VALUES (:idNot, :idUsu)');
      $insertedUsu = $stmt->execute([':idNot' => $idNotification, ':idUsu' => $empleado['id']]);
    }
  }
}
