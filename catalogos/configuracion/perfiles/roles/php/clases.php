<?php
session_start();
date_default_timezone_set('America/Mexico_City');
$user = $_SESSION["Usuario"];

class conectar
{ //Llamado al archivo de la conexión.
  function getDb()
  {
    include "../../../../../include/db-conn.php";
    return $conn;
  }
}

function GetEvn()
{
  include "../../../include/db-conn.php";
  $appUrl = $_ENV['APP_URL'] ?? 'https://app.timlid.com/';
  $origenMail = $_ENV['ORIGEN_MAIL'] ?? "no-reply@timlid.com";
  return ['server' => $appUrl, 'origenMail' => $origenMail];
}

class get_data
{
  function getScreens()
  {
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf('call spc_Datos_Pantalla');
    $stmt = $db->prepare($query);
    $stmt->execute();
    $array = $stmt->fetchAll(PDO::FETCH_OBJ);

    return $array;
  }

  function getScreensVal($value)
  {
    $con = new conectar();
    $db = $con->getDb();
    $array = [];

    try {
        if($_SESSION['tim_impulsa'] === 0){
            $query = sprintf('SELECT p.id,p.pantalla,p.url,p.seccion_id FROM pantallas p WHERE seccion_id = ?');
            $stmt = $db->prepare($query);
            $stmt->execute(array($value));
            $array = $stmt->fetchAll(PDO::FETCH_OBJ);
        } else {
            $query = sprintf('SELECT p.id,p.pantalla,p.url,p.seccion_id FROM pantallas p WHERE seccion_id = ? AND id <> 17 AND id <> 45 AND id <> 25 AND id <> 51 AND id <> 52 AND id <> 53 AND id <> 16 AND id <> 68 ORDER BY orden ASC');
            $stmt = $db->prepare($query);
            $stmt->execute(array($value));
            $array = $stmt->fetchAll(PDO::FETCH_OBJ);
        }

    } catch (PDOException $e) {
        return "Error en consulta: " . $e->getMessage();
    }
    return $array;
  }

  function getSections()
  {
    $con = new conectar();
    $db = $con->getDb();
    $array = [];
    if($_SESSION['tim_impulsa'] === 0){
        $query = sprintf('SELECT * FROM secciones');
        $stmt = $db->prepare($query);
        $stmt->execute();
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);
    } else {
        $query = sprintf('SELECT * FROM secciones WHERE id <> 1 AND id <> 11 AND id <> 15 ORDER BY orden ASC');
        $stmt = $db->prepare($query);
        $stmt->execute();
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    return $array;
  }

  function getFunctions()
  {
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf('call spc_Funciones()');
    $stmt = $db->prepare($query);
    $stmt->execute();
    $array = $stmt->fetchAll(PDO::FETCH_OBJ);

    return $array;
  }

  function getFunctionsVal($value)
  {
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf('SELECT f.id,f.funcion,f.pantalla_id FROM funciones f 
                        WHERE pantalla_id = ?');
    $stmt = $db->prepare($query);
    $stmt->execute(array($value));
    $array = $stmt->fetchAll(PDO::FETCH_OBJ);

    return $array;
  }

  function getFunctionsValues($value, $value1)
  {
    $con = new conectar();
    $db = $con->getDb();

    if ($value !== "") {
      $query = sprintf('SELECT 
                                fp.funcion_ver Ver,
                                fp.funcion_agregar Agregar,
                                fp.funcion_editar Editar,
                                fp.funcion_eliminar Eliminar,
                                fp.funcion_exportar Exportar,
                                p.id pantalla_id
                          FROM funciones_permisos fp
                                INNER JOIN pantallas p ON fp.pantalla_id = p.id
                                INNER JOIN perfiles_permisos pp ON fp.perfil_id = pp.id
                          WHERE perfil_id = :perfil AND fp.pantalla_id = :pantalla_id');
      $stmt = $db->prepare($query);
      $stmt->bindValue(":perfil", $value);
      $stmt->bindValue(":pantalla_id", $value1);
    } else {
      $query = sprintf('select funcion_ver,funcion_agregar,funcion_editar,funcion_eliminar,funcion_exportar from funciones_permisos');
      $stmt = $db->prepare($query);
    }


    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  function getRoles()
  {
    $con = new conectar();
    $db = $con->getDb();
    $arr = [];

    $query = sprintf('SELECT id as id, rol as name FROM roles WHERE estatus = 1 AND id <> 1 AND tim_impulsa = :ban');
    $stmt = $db->prepare($query);
    $stmt->bindValue(":ban",$_SESSION['tim_impulsa']);
    $stmt->execute();

    $arr = $stmt->fetchAll(PDO::FETCH_OBJ);

    return $arr;
  }

  function getProfile($value)
  {
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf('SELECT * FROM perfiles_permisos WHERE id = :id');
    $stmt = $db->prepare($query);
    $stmt->bindValue(':id', $value);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }
}

class save_data
{
  function savePermission($value, $perfil, $rol, $id_perfil, $user, $name, $role)
  {
    $cod = new save_data();
    $con = new conectar();
    $db = $con->getDb();
    date_default_timezone_set('America/Mexico_City');
    $now = date("Y-m-d H:i:s");
   // echo $value;
    $data = json_decode($value, true);

    for ($i = 0; $i < count($data); $i++) {
      if (
        $data[$i]['funcion_ver'] === 0 and
        $data[$i]['funcion_agregar'] === 0 and
        $data[$i]['funcion_editar'] === 0 and
        $data[$i]['funcion_eliminar'] === 0 and
        $data[$i]['funcion_exportarExcel'] === 0
      ) {
        $pantallas[] = array(
          'pantalla' => $data[$i]['pantalla'],
          'seccion' => $data[$i]['seccion'],
          'permiso' => 0
        );
      } else {
        $pantallas[] = array(
          'pantalla' => $data[$i]['pantalla'],
          'seccion' => $data[$i]['seccion'],
          'permiso' => 1
        );
      }
      $aux[] = array($data[$i]['seccion']);
    }
    $secciones_aux = array_values(array_unique($aux, SORT_REGULAR));

    $aux = null;

    for ($i = 0; $i < count($secciones_aux); $i++) {
      $aux = false;
      for ($j = 0; $j < count($pantallas); $j++) {
        if ($secciones_aux[$i][0] === $pantallas[$j]['seccion']) {
          if ($pantallas[$j]['permiso'] === 1) {
            $aux = true;
          }
        }
      }
      if ($aux) {
        $secciones[] = array(
          'seccion' => $secciones_aux[$i][0],
          'permiso' => 1
        );
      } else {
        $secciones[] = array(
          'seccion' => $secciones_aux[$i][0],
          'permiso' => 0
        );
      }
    }

    /* Gestor de tareas */

    /* $data[] = array(
      'funcion_ver' => 1,
      'funcion_agregar' => 1,
      'funcion_editar' => 1,
      'funcion_eliminar' => 1,
      'funcion_exportarExcel' => 1,
      'pantalla' => 40,
      'seccion' => 7
    );

    $data[] = array(
      'funcion_ver' => 1,
      'funcion_agregar' => 1,
      'funcion_editar' => 1,
      'funcion_eliminar' => 1,
      'funcion_exportarExcel' => 1,
      'pantalla' => 41,
      'seccion' => 7
    );

    $data[] = array(
      'funcion_ver' => 1,
      'funcion_agregar' => 1,
      'funcion_editar' => 1,
      'funcion_eliminar' => 1,
      'funcion_exportarExcel' => 1,
      'pantalla' => 42,
      'seccion' => 7
    );

    $data[] = array(
      'funcion_ver' => 1,
      'funcion_agregar' => 1,
      'funcion_editar' => 1,
      'funcion_eliminar' => 1,
      'funcion_exportarExcel' => 1,
      'pantalla' => 59,
      'seccion' => 7
    );

    $secciones[] = array(
      'seccion' => 7,
      'permiso' => 1
    ); */

    /* $pantallas[] = array(
      'pantalla' => 40,
      'seccion' => 7,
      'permiso' => 1
    );

    $pantallas[] = array(
      'pantalla' => 41,
      'seccion' => 7,
      'permiso' => 1
    );

    $pantallas[] = array(
      'pantalla' => 42,
      'seccion' => 7,
      'permiso' => 1
    );

    $pantallas[] = array(
      'pantalla' => 59,
      'seccion' => 7,
      'permiso' => 1
    ); */

    /* Fin gestor de tareas */

    $queryind = sprintf("SELECT valor FROM parametros_servidor WHERE parametro = 'url' OR parametro = 'email_contacto' ");
    $stmtind = $db->prepare($queryind);
    $stmtind->execute();
    $url = $stmtind->fetchAll();

    try {

      $db->beginTransaction();

      if ($id_perfil === "" || $id_perfil === null) {
        if((int)$_SESSION['tim_impulsa'] === 1){
            $rol_id = 12;
        }else {
            $rol_id = 2;
        }
        $query = sprintf('INSERT INTO perfiles_permisos (nombre, estatus, roles_id, empresa_id,tim_impulsa) VALUES (:nombre, :estatus, :roles_id, :empresa_id,:tim_impulsa)');
        $stmt = $db->prepare($query);
        $stmt->bindValue(':nombre', $perfil);
        $stmt->bindValue(':estatus', 1);
        $stmt->bindValue(':roles_id', $rol_id);
        $stmt->bindValue(':empresa_id', $_SESSION['IDEmpresa']);
        $stmt->bindValue(':tim_impulsa', $_SESSION['tim_impulsa']);
        $stmt->execute();
        $idlast = $db->lastInsertId();

        for ($i = 0; $i < count($secciones); $i++) {
          $query1 = sprintf('INSERT INTO permisos_secciones (seccion_id, perfil_permiso_id, permiso) VALUES (:seccion, :perfil, :permiso)');
          $stmt1 = $db->prepare($query1);

          $stmt1->bindValue(':seccion', $secciones[$i]['seccion']);
          $stmt1->bindValue(':perfil', $idlast);
          $stmt1->bindValue(':permiso', $secciones[$i]['permiso']);
          $stmt1->execute();
        }

        for ($i = 0; $i < count($pantallas); $i++) {
          $query = sprintf('INSERT INTO permisos_pantallas (pantalla_id, perfil_permiso_id, permiso) VALUES (:pantalla, :perfil, :permiso)');
          $stmt = $db->prepare($query);
          $stmt->bindValue(':pantalla', $pantallas[$i]['pantalla']);
          $stmt->bindValue(':perfil', $idlast);
          $stmt->bindValue(':permiso', $pantallas[$i]['permiso']);
          $stmt->execute();
        }

        for ($i = 0; $i < count($data); $i++) {
          $query = sprintf('INSERT INTO funciones_permisos (funcion_ver, funcion_agregar ,funcion_editar, funcion_eliminar, funcion_exportar, pantalla_id, perfil_id) 
                                                      VALUES (:funcion_ver,:funcion_agregar,:funcion_editar,:funcion_eliminar,:funcion_exportar,:pantalla_id,:perfil_id)');
          $stmt = $db->prepare($query);
          $stmt->bindValue(':funcion_ver', $data[$i]['funcion_ver']);
          $stmt->bindValue(':funcion_agregar', $data[$i]['funcion_agregar']);
          $stmt->bindValue(':funcion_editar', $data[$i]['funcion_editar']);
          $stmt->bindValue(':funcion_eliminar', $data[$i]['funcion_eliminar']);
          $stmt->bindValue(':funcion_exportar', $data[$i]['funcion_exportarExcel']);
          $stmt->bindValue(':pantalla_id', $data[$i]['pantalla']);
          $stmt->bindValue(':perfil_id', $idlast);
          $stmt->execute();
        }

        if ($user !== "" && $name !== "" && $role !== "") {
          $pass = $cod->generateRandomString();
          $password = password_hash($pass, PASSWORD_DEFAULT);
          $codigo = $cod->generateRandomString();

          $query = sprintf('INSERT INTO usuarios (usuario, email, password, nombre, codigo, estatus, created_at, updated_at, role_id, perfil_id, empresa_id) VALUES (:usuario, :email, :password, :nombre, :codigo, :estatus, :created_at, :updated_at, :role_id, :perfil_id, :empresa_id)');
          $stmt = $db->prepare($query);

          $stmt->bindValue(':usuario', $user);
          $stmt->bindValue(':email', $user);
          $stmt->bindValue(':password', $password);
          $stmt->bindValue(':nombre', $name);
          $stmt->bindValue(':codigo', $codigo);
          $stmt->bindValue(':estatus', 1);
          $stmt->bindValue(':created_at', $now);
          $stmt->bindValue(':updated_at', $now);
          $stmt->bindValue(':role_id', $role);
          $stmt->bindValue(':perfil_id', $idlast);
          $stmt->bindValue(':empresa_id', $_SESSION['IDEmpresa']);

          $emailenviado = false;
          $email_cuenta = 0;
          if ($stmt->execute()) {

            $id = $db->lastInsertId();

            while ($emailenviado == false && $email_cuenta < 3) {
              $emailenviado = $cod->sendEmail($id, $user, $name, $pass, $codigo);
              $email_cuenta++;
              if ($email_cuenta > 2) {
                $emailenviado = true;
              }
            }
          }
        }
      } else {
        if((int)$_SESSION['tim_impulsa'] === 1){
            $rol = 12;
        }else {
            $rol = 2;
        }
        $query = sprintf('UPDATE perfiles_permisos SET nombre = :nombre, roles_id = :roles_id WHERE id = :id');
        $stmt = $db->prepare($query);
        $stmt->bindValue(':nombre', $perfil);
        $stmt->bindValue(':roles_id', $rol);
        $stmt->bindValue(':id', $id_perfil);
        $stmt->execute();

        $query = sprintf('SELECT ifnull(id,0) as id FROM permisos_secciones WHERE perfil_permiso_id = :id');
        $stmt = $db->prepare($query);
        $stmt->bindValue(':id', $id_perfil);
        $stmt->execute();
        $arr_sec = $stmt->fetchAll();

        if ($arr_sec[0]['id'] != 0) {
          for ($i = 0; $i < count($secciones); $i++) {
            $query = sprintf('UPDATE permisos_secciones SET permiso = :permiso WHERE seccion_id = :id and (perfil_permiso_id = :perfil)');
            $stmt = $db->prepare($query);
            $stmt->bindValue(':permiso', $secciones[$i]['permiso']);
            $stmt->bindValue(':perfil', $id_perfil);
            $stmt->bindValue(':id', $secciones[$i]['seccion']);
            $stmt->execute();
          }
        } else {
          for ($i = 0; $i < count($secciones); $i++) {
            $query1 = sprintf('INSERT INTO permisos_secciones (seccion_id, perfil_permiso_id, permiso) VALUES (:seccion, :perfil, :permiso)');
            $stmt1 = $db->prepare($query1);

            $stmt1->bindValue(':seccion', $secciones[$i]['seccion']);
            $stmt1->bindValue(':perfil', $id_perfil);
            $stmt1->bindValue(':permiso', $secciones[$i]['permiso']);
            $stmt1->execute();
          }
        }


        $query = sprintf('SELECT ifnull(id,0) as id FROM permisos_pantallas WHERE perfil_permiso_id = :id');
        $stmt = $db->prepare($query);
        $stmt->bindValue(':id', $id_perfil);
        $stmt->execute();
        $arr_pan = $stmt->fetchAll();

        if ($arr_pan[0]['id'] != 0) {
          for ($i = 0; $i < count($pantallas); $i++) {
            $query = sprintf('UPDATE permisos_pantallas SET permiso = :permiso WHERE (pantalla_id = :id) and (perfil_permiso_id = :perfil)');
            $stmt = $db->prepare($query);
            $stmt->bindValue(':permiso', $pantallas[$i]['permiso']);
            $stmt->bindValue(':id', $pantallas[$i]['pantalla']);
            $stmt->bindValue(':perfil', $id_perfil);
            $stmt->execute();
          }
        } else {
          for ($i = 0; $i < count($pantallas); $i++) {
            $query = sprintf('INSERT INTO permisos_pantallas (pantalla_id, perfil_permiso_id, permiso) VALUES (:pantalla, :perfil, :permiso)');
            $stmt = $db->prepare($query);
            $stmt->bindValue(':pantalla', $pantallas[$i]['pantalla']);
            $stmt->bindValue(':perfil', $id_perfil);
            $stmt->bindValue(':permiso', $pantallas[$i]['permiso']);
            $stmt->execute();
          }
        }

        $query = sprintf('SELECT ifnull(id,0) as id FROM funciones_permisos WHERE perfil_id = :id');
        $stmt = $db->prepare($query);
        $stmt->bindValue(':id', $id_perfil);
        $stmt->execute();
        $arr_fun = $stmt->fetchAll();

        if ($arr_fun[0]['id'] != 0) {
          for ($i = 0; $i < count($data); $i++) {
            $query = sprintf('UPDATE funciones_permisos SET funcion_ver = :funcion_ver, funcion_agregar = :funcion_agregar, funcion_editar = :funcion_editar, funcion_eliminar = :funcion_eliminar, funcion_exportar = :funcion_exportar WHERE (pantalla_id= :id) and (perfil_id = :perfil)');
            $stmt = $db->prepare($query);
            $stmt->bindValue(':funcion_ver', $data[$i]['funcion_ver']);
            $stmt->bindValue(':funcion_agregar', $data[$i]['funcion_agregar']);
            $stmt->bindValue(':funcion_editar', $data[$i]['funcion_editar']);
            $stmt->bindValue(':funcion_eliminar', $data[$i]['funcion_eliminar']);
            $stmt->bindValue(':funcion_exportar', $data[$i]['funcion_exportarExcel']);
            $stmt->bindValue(':id', $data[$i]['pantalla']);
            $stmt->bindValue(':perfil', $id_perfil);
            $stmt->execute();
          }
        } else {
          for ($i = 0; $i < count($data); $i++) {
            $query = sprintf('INSERT INTO funciones_permisos (funcion_ver, funcion_agregar ,funcion_editar, funcion_eliminar, funcion_exportar, pantalla_id, perfil_id) 
              VALUES (:funcion_ver,:funcion_agregar,:funcion_editar,:funcion_eliminar,:funcion_exportar,:pantalla_id,:perfil_id)');
            $stmt = $db->prepare($query);
            $stmt->bindValue(':funcion_ver', $data[$i]['funcion_ver']);
            $stmt->bindValue(':funcion_agregar', $data[$i]['funcion_agregar']);
            $stmt->bindValue(':funcion_editar', $data[$i]['funcion_editar']);
            $stmt->bindValue(':funcion_eliminar', $data[$i]['funcion_eliminar']);
            $stmt->bindValue(':funcion_exportar', $data[$i]['funcion_exportarExcel']);
            $stmt->bindValue(':pantalla_id', $data[$i]['pantalla']);
            $stmt->bindValue(':perfil_id', $id_perfil);
            $stmt->execute();
          }
        }
      }
      return $db->commit();
    } catch (PDOException $e) {
      $msj = "Error en consulta: " . $e->getMessage() . "<br>Error sql: " .
        "<br>1.-" . $stmt->errorInfo()[0] .
        "<br>2.-" . $stmt->errorInfo()[1] .
        "<br>3.-" . $stmt->errorInfo()[2];

      $db->rollBack();
      return $msj;
    }
  }

  function generateRandomString($length = 12)
  {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }

  function sendEmail($id, $user, $name, $password, $code)
  {
    $envVariables = GetEvn();
    $appUrl = $envVariables['server'];
    $origen = $envVariables['origenMail'];

    require('../../../../../lib/phpmailer_configuration.php');

    try {
      $origen = $email_origen;
      $usuario_envia = "Timlid";
      $mail->Sender = $origen;
      $mail->setFrom($origen, $usuario_envia);
      $mail->addReplyTo($origen, $usuario_envia);
      $mail->addAddress($user);     //Add a recipient  $user

      $mensaje = $name . "<br><br>" . "Se te ha generado un usuario en el sistema Timlid, para poder activarlo ingresa en el siguiente link:<br><br><a href='" . $appUrl . "index.php?id=" . $id . "&codigo=" . $code . "' target='_blank' title='TimLid - Activación de cuenta'>Timlid - Activar cuenta</a>";
      $mensaje .= "<br><br>" . "<b>Correo:</b> " . $user . "<br>";
      $mensaje .= "<b>Contraseña:</b> " . $password . "<br>";
      //Content
      $mail->isHTML(true);                                  //Set email format to HTML
      $mail->Subject = utf8_decode("Timlid - Contraseña de usuario");
      $mail->Body    = utf8_decode($mensaje);

      if ($mail->send()) {
        return true;
      } else {
        return false;
      }
    } catch (Exception $e) {
      //header('Location: ver_Cotizacion.php?id='.$id.'&estatus=2');
      return false;
    }
  }
}

class delete_data
{
  function deleteProfile($value)
  {
    $con = new conectar();
    $db = $con->getDb();

    $query = sprintf("SELECT * FROM usuarios WHERE perfil_id = :perfil_id");
    $stmt = $db->prepare($query);
    $stmt->bindValue(':perfil_id', $value);
    $stmt->execute();
    $count = $stmt->rowCount();

    if ($count === 0) {
      $db->beginTransaction();

      try {

        $query = sprintf('delete from funciones_permisos WHERE perfil_id = :id');
        $stmt = $db->prepare($query);
        $stmt->bindValue(':id', $value);
        $stmt->execute();

        $query = sprintf('delete from permisos_pantallas WHERE perfil_permiso_id = :id');
        $stmt = $db->prepare($query);
        $stmt->bindValue(':id', $value);
        $stmt->execute();

        $query = sprintf('delete from permisos_secciones WHERE perfil_permiso_id = :id');
        $stmt = $db->prepare($query);
        $stmt->bindValue(':id', $value);
        $stmt->execute();

        $query = sprintf('delete from perfiles_permisos WHERE id = :id');
        $stmt = $db->prepare($query);
        $stmt->bindValue(':id', $value);
        $stmt->execute();

        return $db->commit();
      } catch (PDOException $e) {
        $msj = "Error en consulta: " . $e->getMessage();
        $db->rollBack();
        return $msj;
      }
    }
  }
}
