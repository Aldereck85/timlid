<?php
session_start();
date_default_timezone_set('America/Mexico_City');

class conectar
{
  public function getDb()
  {
    include "../../../include/db-conn.php";
    return $conn;
  }
}

class enviroment
{
  function GetEvn()
  {
    include "../../../include/db-conn.php";
    $appUrlWrite = $_ENV['APP_URL_WRITE'];
    return ['appUrlWrite' => $appUrlWrite];
  }
}

class get_data
{
  public function getSecciones()
  {
    $con = new conectar();
    $db = $con->getDb();
    $data = array();
    try {
      $stmt = $db->prepare('SELECT id AS value, seccion AS text FROM secciones');
      if (!$stmt->execute()) {
        throw new \Exception('Fallo al traer las secciones');
      }
      $secciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

      $data['status'] = 'success';
      $data['data'] = $secciones;

      return $data;
    } catch (Exception $e) {
      $data['status'] = 'fail';
      $data['message'] = $e->getMessage();

      return $data;
    }

    return;
  }
  public function validateSeccion($valor, $tipo)
  {
    $con = new conectar();
    $db = $con->getDb();
    $data = array();
    try {
      switch ($tipo) {
        case 'pantalla':
          $query = 'SELECT id FROM pantallas WHERE pantalla = :valor';
          break;
        case 'seccion':
          $query = 'SELECT id FROM secciones WHERE seccion = :valor';
          break;
        case 'url':
          $query = 'SELECT id FROM pantallas WHERE url = :valor';
          break;

        default:
          throw new \Exception('Enviaste algo invalido.');
          break;
      }
      $stmt = $db->prepare($query);
      if (!$stmt->execute([':valor' => $valor])) {
        throw new \Exception('Enviaste algo invalido.');
      }
      $res = $stmt->fetch(PDO::FETCH_ASSOC);

      $data['status'] = 'success';
      $data['existe'] = $res;

      return $data;
    } catch (Exception $e) {
      $data['status'] = 'fail';
      $data['message'] = $e->getMessage();

      return $data;
    }

    return;
  }
}

class save_data
{
  public function saveSeccion($nombre, $siglas, $perfiles, $icono)
  {
    $con = new conectar();
    $db = $con->getDb();
    $env = new enviroment();
    $appUrlWrite = $env->GetEvn()['appUrlWrite'];
    $data = array();
    $perfilesArray = explode(',', $perfiles);
    $nombreIcono = $icono['name'];

    try {
      $query = sprintf('SELECT * FROM secciones');
      $stmt = $db->prepare($query);
      $stmt->execute();
      $numberSections = $stmt->rowCount();

      $query = sprintf('INSERT INTO secciones (seccion, siglas, icono, orden) VALUES (:seccion, :siglas, :icono, :orden);');
      $stmt = $db->prepare($query);
      if (!$stmt->execute([':seccion' => $nombre, ':siglas' => $siglas, ':icono' => $nombreIcono, ':orden' => $numberSections + 1])) {
        throw new \Exception('Fallo al añadir la sección');
      }
      $idSeccion = $db->lastInsertId();

      $location = $appUrlWrite . 'img/menu/' . $nombreIcono;
      $imageFileType = pathinfo($location, PATHINFO_EXTENSION);
      $imageFileType = strtolower($imageFileType);
      $valid_extensions = array('png', 'gif', 'svg');

      if (in_array(strtolower($imageFileType), $valid_extensions)) {
        if (!move_uploaded_file($icono['tmp_name'], $location)) {
          throw new \Exception('Fallo al añadir el icono');
        }
      }

      $query = sprintf('SELECT id FROM perfiles_permisos');
      $stmt = $db->prepare($query);
      $stmt->execute();
      $resPerfiles = $stmt->fetchAll(PDO::FETCH_ASSOC);

      foreach ($resPerfiles as $resPerfil) {
        if (in_array($resPerfil['id'], $perfilesArray) || ($resPerfil['id'] == 1) || ($resPerfil['id'] == 2) || ($resPerfil['id'] == 10)) {
          $query = sprintf('INSERT INTO permisos_secciones (seccion_id, perfil_permiso_id, permiso) VALUES (:seccion, :perfil, :permiso);');
          $stmt = $db->prepare($query);
          if (!$stmt->execute([':seccion' => $idSeccion, ':perfil' => $resPerfil['id'], ':permiso' => 1])) {
            throw new \Exception("Fallo al añadir los permisos");
          }
        } else {
          $query = sprintf('INSERT INTO permisos_secciones (seccion_id, perfil_permiso_id, permiso) VALUES (:seccion, :perfil, :permiso);');
          $stmt = $db->prepare($query);
          if (!$stmt->execute([':seccion' => $idSeccion, ':perfil' => $resPerfil['id'], ':permiso' => 0])) {
            throw new \Exception("Fallo al añadir los permisos");
          }
        }
      }

      $data['status'] = 'success';
      $data['message'] = 'Sección agregada correctamente';
      $data['data'] = ['idSeccion' => $idSeccion, 'seccion' => $nombre];

      return $data;
    } catch (Exception $e) {
      $data['status'] = 'fail';
      $data['message'] = $e->getMessage();

      return $data;
    }
  }

  public function savePantalla($nombre, $url, $seccion, $perfiles)
  {
    $con = new conectar();
    $db = $con->getDb();
    $data = array();

    try {
      $query = sprintf('SELECT orden FROM pantallas WHERE seccion_id = :seccion ORDER BY orden DESC LIMIT 1');
      $stmt = $db->prepare($query);
      $stmt->execute([':seccion' => $seccion]);
      if (!$stmt->execute([':seccion' => $seccion])) {
        throw new \PDOException("Fallo al buscar la sección");
      }
      $lastItemOrden = $stmt->fetch(PDO::FETCH_ASSOC);

      $query = sprintf('INSERT INTO pantallas (pantalla, url, seccion_id, orden) VALUES (:pantalla, :url, :seccion, :orden)');
      $stmt = $db->prepare($query);
      if (!$stmt->execute([':pantalla' => $nombre, ':url' => $url, ':seccion' => $seccion, ':orden' => $lastItemOrden['orden'] + 1])) {
        throw new \Exception('Fallo al añadir la pantalla');
      }
      $idPantalla = $db->lastInsertId();

      $query = sprintf("INSERT INTO funciones (funcion, pantalla_id) VALUES ('Ver', $idPantalla), ('Agregar', $idPantalla), ('Editar', $idPantalla), ('Eliminar', $idPantalla),  ('Exportar excel', $idPantalla)");
      $stmt = $db->prepare($query);
      if (!$stmt->execute()) {
        throw new \Exception('Fallo al añadir las funciones');
      }

      $query = sprintf('SELECT id, roles_id FROM perfiles_permisos');
      $stmt = $db->prepare($query);
      $stmt->execute();
      $resPerfiles = $stmt->fetchAll(PDO::FETCH_ASSOC);

      foreach ($resPerfiles as $resPerfil) {
        $permiso = 0;
        if (in_array($resPerfil['roles_id'], $perfiles) || ($resPerfil['id'] == 1) || ($resPerfil['id'] == 2)) {
          $permiso = 1;
          $query = sprintf('INSERT INTO permisos_pantallas (pantalla_id, perfil_permiso_id, permiso)
          VALUES (:pantalla, :perfil, :permiso)');
          $stmt = $db->prepare($query);
          if (!$stmt->execute([':pantalla' => $idPantalla, ':perfil' => $resPerfil['id'], ':permiso' => $permiso])) {
            throw new \Exception("Fallo al añadir los permisos");
          }
          $query = sprintf('INSERT INTO funciones_permisos (funcion_ver, funcion_agregar, funcion_editar, funcion_eliminar, funcion_exportar, pantalla_id, perfil_id)
          VALUES (:permisoVer, :permisoAgr, :permisoEdt, :permisoElm, :permisoExp, :pantalla, :perfil)');
          $stmt = $db->prepare($query);
          if (!$stmt->execute([':permisoVer' => $permiso, ':permisoAgr' => $permiso, ':permisoEdt' => $permiso, ':permisoElm' => $permiso, ':permisoExp' => $permiso, ':pantalla' => $idPantalla, ':perfil' => $resPerfil['id'],])) {
            throw new \Exception("Fallo al añadir las funciones permisos");
          }
        } else if ($resPerfil['roles_id'] == 10) {
          /* AUDITORIA */
          $query = sprintf('INSERT INTO permisos_pantallas (pantalla_id, perfil_permiso_id, permiso) VALUES (:pantalla, :perfil, :permiso)');
          $stmt = $db->prepare($query);
          if (!$stmt->execute([':pantalla' => $idPantalla, ':perfil' => 10, ':permiso' => 1])) {
            throw new \Exception("Fallo al añadir permiso auditoria");
          }
          $query = sprintf('INSERT INTO funciones_permisos (funcion_ver, funcion_agregar, funcion_editar, funcion_eliminar, funcion_exportar, pantalla_id, perfil_id)
          VALUES (:permisoVer, :permisoAgr, :permisoEdt, :permisoElm, :permisoExp, :pantalla, :perfil)');
          $stmt = $db->prepare($query);
          if (!$stmt->execute([':permisoVer' => 1, ':permisoAgr' => 0, ':permisoEdt' => 0, ':permisoElm' => 0, ':permisoExp' => 0, ':pantalla' => $idPantalla, ':perfil' => 10])) {
            throw new \Exception("Fallo al añadir las funciones permiso auditoria");
          }
        } else {
          $query = sprintf('INSERT INTO permisos_pantallas (pantalla_id, perfil_permiso_id, permiso) VALUES (:pantalla, :perfil, :permiso)');
          $stmt = $db->prepare($query);
          if (!$stmt->execute([':pantalla' => $idPantalla, ':perfil' => $resPerfil['id'], ':permiso' => $permiso])) {
            throw new \Exception("Fallo al añadir los permisos");
          }
          $query = sprintf('INSERT INTO funciones_permisos (funcion_ver, funcion_agregar, funcion_editar, funcion_eliminar, funcion_exportar, pantalla_id, perfil_id)
          VALUES (:permisoVer, :permisoAgr, :permisoEdt, :permisoElm, :permisoExp, :pantalla, :perfil)');
          $stmt = $db->prepare($query);
          if (!$stmt->execute([':permisoVer' => $permiso, ':permisoAgr' => $permiso, ':permisoEdt' => $permiso, ':permisoElm' => $permiso, ':permisoExp' => $permiso, ':pantalla' => $idPantalla, ':perfil' => $resPerfil['id'],])) {
            throw new \Exception("Fallo al añadir las funciones permisos");
          }
        }
      }

      $data['status'] = 'success';
      $data['message'] = 'Pantalla agregada correctamente';
      $data['data'] = ['idPantalla' => $idPantalla, 'pantalla' => $nombre, 'idSeccion' => $seccion];

      return $data;
    } catch (Exception $e) {
      $data['status'] = 'fail';
      $data['message'] = $e->getMessage();

      return $data;
    }
  }
}

class edit_data
{
  public function editOrdenSecciones($nuevoOrden)
  {
    $con = new conectar();
    $db = $con->getDb();
    try {
      foreach ($nuevoOrden as $item) {
        $query = sprintf('UPDATE secciones SET orden = :orden WHERE seccion = :seccion');
        $stmt = $db->prepare($query);
        if (!$stmt->execute(array(':orden' => $item['orden'], ':seccion' => $item['seccion']))) {
          throw new \Exception('Fallo al reordenar las secciones');
        }
      }

      $data['status'] = 'success';
      $data['message'] = 'Secciones reordenadas correctamente';

      return $data;
    } catch (Exception $e) {
      $data['status'] = 'fail';
      $data['message'] = $e->getMessage();

      return $data;
    }
  }

  public function editOrdenPantallas($seccion, $nuevoOrden)
  {
    $con = new conectar();
    $db = $con->getDb();
    try {
      foreach ($nuevoOrden as $item) {
        $query = sprintf('UPDATE pantallas SET orden = :orden WHERE seccion_id = :seccion AND id = :pantalla');
        $stmt = $db->prepare($query);
        if (!$stmt->execute(array(':orden' => $item['orden'], ':seccion' => $seccion, ':pantalla' => $item['pantalla']))) {
          throw new \Exception('Fallo al reordenar las pantalla');
        }
      }

      $data['status'] = 'success';
      $data['message'] = 'Pantallas reordenadas correctamente';

      return $data;
    } catch (Exception $e) {
      $data['status'] = 'fail';
      $data['message'] = $e->getMessage();

      return $data;
    }
  }

  public function validarEditar($id, $valor, $tipo)
  {
    $con = new conectar();
    $db = $con->getDb();
    $values = [];
    $data = array();
    try {
      switch ($tipo) {
        case 'pantalla':
          $queryValidate = 'SELECT id FROM pantallas WHERE pantalla = :pantalla AND id != :id';
          $queryUpdate = 'UPDATE pantallas SET pantalla = :pantalla WHERE id = :id';
          $values = [':pantalla' => $valor, ':id' => $id];
          break;
        case 'seccion':
          $queryValidate = 'SELECT id FROM secciones WHERE seccion = :seccion AND id != :id';
          $queryUpdate = 'UPDATE secciones SET seccion = :seccion WHERE id = :id';
          $values = [':seccion' => $valor, ':id' => $id];
          break;

        default:
          throw new \Exception('Enviaste algo invalido.');
          break;
      }
      $stmt = $db->prepare($queryValidate);
      if (!$stmt->execute($values)) {
        throw new \Exception('Enviaste algo invalido.');
      }
      $res = $stmt->fetch(PDO::FETCH_ASSOC);
      if ($res) {
        throw new \Exception("La $tipo debe ser unica.");
      }

      $stmt = $db->prepare($queryUpdate);
      if (!$stmt->execute($values)) {
        throw new \Exception("Fallo al actualizar la $tipo");
      }

      $data['status'] = 'success';
      $data['message'] = "Se actualizaron los datos correctamente.";

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
  public function eliminarPantallaSeccion($id, $tipo)
  {
    $con = new conectar();
    $db = $con->getDb();
    $data = array();

    try {
      switch ($tipo) {
        case 'pantalla':
          $query = 'DELETE FROM pantallas WHERE id = :id';
          break;
        case 'seccion':
          $query = 'DELETE FROM secciones WHERE id = :id';
          break;
        default:
          throw new \Exception('Enviaste algo invalido.');
          break;
      }
      $stmt = $db->prepare($query);
      if (!$stmt->execute([':id' => $id])) {
        throw new \Exception('Algo salio mal');
      }

      $data['status'] = 'success';
      $data['message'] = "Se elimino la $tipo correctamente";

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
