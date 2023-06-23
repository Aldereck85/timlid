<?php
session_start();
date_default_timezone_set('America/Mexico_City');
$user_id = $_SESSION["PKUsuario"];


class conectar
{
  function getDb()
  {
    include "../../../include/db-empresas.php";
    return $conn;
  }
  function getDbUnica()
  {
    include "../../../include/db-conn.php";
    return $conn;
  }
  function getDbUnica2()
  {
    include "../../../include/db-conn-2.php";
    return $conn2;
  }

}

function GetEvn()
{
  include "../../../include/db-conn.php";
  $appUrl = $_ENV['APP_URL'] ?? 'https://app.timlid.com/';
  $origenMail = $_ENV['ORIGEN_MAIL'] ?? "no-reply@timlid.com";
  return ['server' => $appUrl, 'origenMail' => $origenMail];
}

class StripeClass
{
  function getStripeConn()
  {
    include "../../../include/db-conn.php";
    require_once('../../../vendor/autoload.php');
    $stripe = new \Stripe\StripeClient('sk_test_51IugPYDgclpya5MU5T0jdN7eCJMXEZqZBv6USblPX0UrHQ6bztRFTAc9HA55Ay5OPSLtkfJo4pQ4m0dLTNGziDfQ00GSzvE5dJ');
    return $stripe;
  }
}

class get_data
{
  function getNoUsuarios()
  {
    try {
      $con = new conectar();
      $db = $con->getDbUnica();

      $con1 = new conectar();
      $db1 = $con1->getDbUnica2();

      $stmt = $db->prepare('SELECT u.id FROM usuarios u WHERE empresa_id = :emp_id AND (u.estatus = 1 OR u.estatus = 0)');
      $stmt->bindValue(":emp_id", $_SESSION["IDEmpresa"], PDO::PARAM_INT);
      $stmt->execute();
      $usrCount = $stmt->rowCount();

      $stmt1 = $db1->prepare('SELECT stripe_status, quantity FROM subscriptions s 
                                inner join companies c on s.user_id = c.user_id
                              WHERE c.id = :emp_id');
      $stmt1->bindValue(":emp_id", $_SESSION["IDEmpresa"], PDO::PARAM_INT);
      $stmt1->execute();
      $arr = $stmt1->fetchAll(PDO::FETCH_OBJ);
      if((int)$_SESSION["IDEmpresa"] === 7 || (int)$_SESSION["IDEmpresa"] === 6){
        $maxUsers = 10000;
      } else {
        $maxUsers = $arr[0]->quantity;
      }
    //   $stripeConn = new StripeClass();
    //   $stripe = $stripeConn->getStripeConn();
    //   $customer = $stripe->customers->retrieve(
    //     $_SESSION['stripe_id'],
    //     ['expand' => ['subscriptions']]
    //   );
    //   $planName = $customer->subscriptions->data[0]->plan->metadata->name;
    //   $maxUsers = $planName === 'TimDev' ? 10000 : $customer->subscriptions->data[0]->quantity;

      return json_encode(['status' => 'success', 'ursCount' => ($maxUsers - $usrCount), 'stripe_status' => $arr[0]->stripe_status]);
    } catch (\Throwable $th) {
      //return json_encode(['status'=> 'fail', 'message' => $th]);
      return json_encode(['status' => 'fail', 'message' => 'Algo salio mal']);
    }
  }

  function getUserTable($user, $session_user, $screen)
  {
    $con = new conectar();
    $db = $con->getDbUnica();
    $table = "";


    $stmt = $db->prepare('SELECT u.id, u.usuario, u.estatus, u.nuevo_password, e.Nombres, e.PrimerApellido, u.founder
    FROM usuarios AS u 
    INNER JOIN empleados AS e ON u.id = e.PKEmpleado
    WHERE u.empresa_id = :emp_id ORDER BY u.id DESC');
    $stmt->bindValue(":emp_id", $user, PDO::PARAM_INT);
    $stmt->execute();
    $array = $stmt->fetchAll();

    $query1 = sprintf('SELECT fp.funcion_editar,fp.funcion_eliminar FROM funciones_permisos fp 
                        INNER JOIN pantallas p ON fp.pantalla_id = p.id
                        INNER JOIN perfiles_permisos pf ON fp.perfil_id = pf.id
                        INNER JOIN usuarios u ON pf.id = u.perfil_id
                        WHERE u.id = :sessionUser AND p.id = :screen');

    $stmt1 = $db->prepare($query1);
    $stmt1->bindValue(":sessionUser", $session_user, PDO::PARAM_INT);
    $stmt1->bindValue(":screen", $screen, PDO::PARAM_INT);
    $stmt1->execute();
    $permisos = $stmt1->fetchAll(PDO::FETCH_ASSOC);

    $x = count($array);
    foreach ($array as $r) {
      $resend = '';
      $id = $r['id'];
      $nombres = $r['Nombres'] . ' ' . $r['PrimerApellido'];
      $usuario = $r['usuario'];
      $estatus = $r['estatus'];
      $usuario_nuevo = $r['nuevo_password'];

      if ($estatus === 1) {
        $mensaje_estatus = "Activo";
      } else if ($estatus === 0) {
        $mensaje_estatus = "Sin activar";
        $resend = '<i class=\"fas fa-mail-bulk pointer permission-view-edit ml-1\" onclick=\"resendEmailConfirmation(' . $id . ')\">';
      } else if ($estatus === 2) {
        $mensaje_estatus = "Inactivo";
      }
      $acciones = '';
      if ($r['founder'] !== 1) {
        if ($permisos[0]['funcion_editar'] === 1 || $permisos[0]['funcion_eliminar'] === 1) {
          $acciones = $resend . '</i> <i class=\"fas fa-edit pointer permission-view-edit\" data-toggle=\"modal\" data-target=\"#editar_Usuarios_43\" onclick=\"obtenerIdUsuarioEditar(' . $id . ');\"></i>';
        }
      }
      $table .= '
          {"id":"' . $id . '",
            "No":"' . $x . '",
            "Nombre completo":"' . $nombres . '",
            "Usuario":"' . $usuario . '",
            "Acciones":"' . $acciones . '",
            "Estatus":"' . $mensaje_estatus . '"
          },';
      $x--;
    }

    $table = substr($table, 0, strlen($table) - 1);

    return '{"data":[' . $table . ']}';

    $con = "";
    $stmt = "";
    $db = "";
  }

  function getUser($value)
  {
    $con = new conectar();
    $db = $con->getDbUnica();

    $query = sprintf('SELECT u.usuario, u.nombre, u.estatus, r.id AS rol, pfp.id AS perfil 
                      FROM usuarios as u
                      INNER JOIN roles AS r ON u.role_id = r.id
                      INNER JOIN perfiles_permisos AS pfp ON u.perfil_id = pfp.id
                      WHERE u.id = :usuario_id');
    $stmt = $db->prepare($query);
    $stmt->bindValue(":usuario_id", $value, PDO::PARAM_INT);
    $stmt->execute(array($value));

    return $stmt->fetchAll(PDO::FETCH_OBJ);

    $con = "";
    $stmt = "";
    $db = "";
  }

  public function getUsersByWidget($empresaID)
  {
    $con = new conectar();
    $db = $con->getDbUnica();

    $stmt = $db->prepare('SELECT u.id AS usuarioID, CONCAT(e.Nombres, " ", e.PrimerApellido) AS usuario, wg.name AS widgetName, pwg.permiso
    FROM permisos_widgets AS pwg
    INNER JOIN usuarios AS u ON pwg.FKUsuario = u.id
    INNER JOIN empleados AS e ON u.id = e.PKEmpleado
    INNER JOIN widgets AS wg ON pwg.FKWidget = wg.id
    WHERE u.empresa_id = :empresaID
    ORDER BY wg.id ASC, u.id ASC');
    if ($stmt->execute([':empresaID' => $empresaID])) {
      return json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
    return json_encode(['Algo salio mal.']);
  }

  public function validarCategoriaProducto($data)
    {
      $con = new conectar();
      $db = $con->getDbUnica();

        $PKEmpresa = $_SESSION['IDEmpresa'];

        $query = sprintf('call spc_ValidarUnicaCategoria(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($data, $PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
        $con = "";
        $stmt = "";
        $db = "";
    }
  public function validarCategoriaCliente($data)
  {
    $con = new conectar();
    $db = $con->getDbUnica();

      $PKEmpresa = $_SESSION["IDEmpresa"];

      $query = sprintf('SELECT EXISTS(SELECT PKCategoria_cliente FROM categorias_clientes WHERE nombre = ? and empresa_id = ? and estatus != 3) as existe ');
      $stmt = $db->prepare($query);
      $stmt->execute(array($data, $PKEmpresa));
      $array = $stmt->fetchAll(PDO::FETCH_OBJ);

      return $array;
      $con = "";
      $stmt = "";
      $db = "";
  }
  public function getCmbEstatusGral()
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Combo_EstatusGeneral()');
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public function getDatosCategoria($value)
    {
      $con = new conectar();
      $db = $con->getDbUnica();

        $query = sprintf('call spc_Datos_Categoria(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
        $con = "";
        $stmt = "";
        $db = "";
    }

    public function getDatosCategoriaClientes($value)
    {
      $con = new conectar();
      $db = $con->getDbUnica();

        $query = sprintf('SELECT cc.PKCategoria_cliente as id,
                                  cc.nombre as categoria,
                                  cc.estatus as estatus,
                                  (SELECT EXISTS(select c.PKCliente from clientes c where c.FKCategoriaCliente = ?)) as existe 
                            FROM categorias_clientes cc
                            where cc.PKCategoria_cliente = ? and cc.empresa_id=?
                            ;');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value, $value, $_SESSION['IDEmpresa']));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
        $con = "";
        $stmt = "";
        $db = "";
    }

  public function getMarcasTable()
  {
      $con = new conectar();
      $db = $con->getDbUnica();
        $table = "";
        $idemp = $_SESSION['IDEmpresa'];
        $stmt = $db->prepare('call spc_Tabla_Marcas_Consulta(?)');
        $stmt->execute(array($idemp));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id = $r['id'];
            $marca = $r['marca'];
            $estatus = $r['estatus'];
            if ($estatus == 1) {
                $estatusR = "<span class='left-dot green-dot'>Activo</span>";
            } else {
                $estatusR = "<span class='left-dot red-dot'>Inactivo</span>";
            }

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';
            $acciones = '<i class=\"fas fa-edit pointer\" data-toggle=\"modal\" data-target=\"#editar_MarcadeProductos_9\" onclick=\"obtenerIdMarcaEditar(' . $id . ');\"></i>';

            $table .= '{"Id":"' . $etiquetaI . $id . $etiquetaF . '",
                "MarcaProducto":"' . $etiquetaI . $marca . $etiquetaF . '",
                "Acciones":"' . $etiquetaI . $acciones . $etiquetaF . '",
                "Estatus":"' . $estatusR . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';

        $con = "";
        $stmt = "";
        $db = "";
  }
  function get_marcasP($value)
  {
    $con = new conectar();
    $db = $con->getDbUnica();

    $table = "";
    $idemp = $_SESSION['IDEmpresa'];
    $stmt = $db->prepare('call spc_Tabla_Categorias_Consulta(?)');
    $stmt->execute(array($idemp));
    $array = $stmt->fetchAll();

    foreach ($array as $r) {
        $id = $r['id'];
        $categoria = $r['categoria'];
        $estatus = $r['estatus'];
        if ($estatus == 1) {
            $estatusR = '<span class=\"left-dot green-dot\">Activo</span>';
        } else {
            $estatusR = '<span class=\"left-dot red-dot\">Inactivo</span>';
        }

        $etiquetaI = '<span class=\"textTable\">';
        $etiquetaF = '</span>';
        $acciones = '<i class=\"fas fa-edit pointer\" data-toggle=\"modal\" data-target=\"#editar_CategoriadeProductos_8\" onclick=\"obtenerIdCategoriaEditar(' . $id . ');\"></i>';

        $table .= '{"Id":"' . $etiquetaI . $id . $etiquetaF . '",
            "CategoriaProducto":"' . $etiquetaI . $categoria . $etiquetaF . '",
            "Acciones":"' . $etiquetaI . $acciones . $etiquetaF . '",
            "Estatus":"' . $etiquetaI . $estatusR . $etiquetaF . '"},';
    }
    $table = substr($table,0,strlen($table)-1);
    return '{"data":[' . $table . ']}';

    $con = "";
    $stmt = "";
    $db = "";
  }
  function get_categoriasClientes()
  {
    $con = new conectar();
    $db = $con->getDbUnica();

    $table = "";
    $idemp = $_SESSION['IDEmpresa'];
    $stmt = $db->prepare('SELECT cc.PKCategoria_cliente as id,
                              cc.nombre as categoria,
                              cc.estatus as estatus
                          FROM categorias_clientes cc
                          where cc.estatus != 3 and cc.empresa_id = ?;');
    $stmt->execute(array($idemp));
    $array = $stmt->fetchAll();

    $c=0;
    foreach ($array as $r) {
        $c++;
        $id = $r['id'];
        $categoria = $r['categoria'];
        $estatus = $r['estatus'];
        if ($estatus == 1) {
            $estatusR = '<span class=\"left-dot green-dot\">Activo</span>';
        } else {
            $estatusR = '<span class=\"left-dot red-dot\">Inactivo</span>';
        }

        $etiquetaI = '<span class=\"textTable\">';
        $etiquetaF = '</span>';
        $acciones = '<i class=\"fas fa-edit pointer\" data-toggle=\"modal\" data-target=\"#editar_CategoriadeClientes_87\" onclick=\"obtenerIdCategoriaClienteEditar(' . $id . ');\"></i>';

        $table .= '{"Id":"' . $etiquetaI . $c . $etiquetaF . '",
            "CategoriaCliente":"' . $etiquetaI . $categoria . $etiquetaF . '",
            "Acciones":"' . $etiquetaI . $acciones . $etiquetaF . '",
            "Estatus":"' . $etiquetaI . $estatusR . $etiquetaF . '"},';
    }
    $table = substr($table,0,strlen($table)-1);
    return '{"data":[' . $table . ']}';

    $con = "";
    $stmt = "";
    $db = "";
  }
  function getDatosMarca($value)
    {
      $con = new conectar();
      $db = $con->getDbUnica();
        $query = sprintf('call spc_Datos_Marca(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
        
    $con = "";
    $stmt = "";
    $db = "";
    }
  public function validarMarcaProducto($data)
  {
    $con = new conectar();
    $db = $con->getDbUnica();

      $PKEmpresa = $_SESSION["IDEmpresa"];

      $query = sprintf('call spc_ValidarUnicaMarca(?,?)');
      $stmt = $db->prepare($query);
      $stmt->execute(array($data, $PKEmpresa));
      $array = $stmt->fetchAll(PDO::FETCH_OBJ);

      return $array;
      $con = "";
      $stmt = "";
      $db = "";
  }

  function getRols()
  {
    $con = new conectar();
    $db = $con->getDbUnica();
    $array = [];
    $user_id = $_SESSION["PKUsuario"];
      $query = sprintf("SELECT role_id FROM usuarios WHERE (id = $user_id)");
      $stmt = $db->prepare($query);
      $stmt->execute();
      $role = $stmt->fetchAll(PDO::FETCH_OBJ);
      $role = $role[0]->role_id;

    if($role == 12)
    {
      $query = sprintf('SELECT id,rol FROM roles WHERE id = 12');
      $stmt = $db->prepare($query);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    else
    {
      $query = sprintf('SELECT id,rol FROM roles WHERE id <> 1');
      $stmt = $db->prepare($query);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    $con = "";
    $stmt = "";
    $db = "";
  }

  function getEmployer()
  {
    $con = new conectar();
    $db = $con->getDbUnica();


    $query = sprintf('SELECT e.PKEmpleado, CONCAT(e.Nombres," ",e.PrimerApellido," ",e.SegundoApellido) Nombre
                        FROM empleados e WHERE e.PKEmpleado
                        NOT IN (SELECT u.id FROM usuarios u) AND e.empresa_id = :idempresa AND e.is_generic = 0');
    $stmt = $db->prepare($query);
    $stmt->bindValue(':idempresa', $_SESSION['IDEmpresa']);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);

    $con = "";
    $stmt = "";
    $db = "";
  }

  function getProfiles()
  {
    $con = new conectar();
    $db = $con->getDbUnica();
    if((int) $_SESSION['tim_impulsa'] === 0){
      $query = sprintf("
                        SELECT 
                          id, 
                          nombre 
                        FROM 
                          perfiles_permisos 
                        WHERE 
                          tim_impulsa = :ban
                        AND 
                          empresa_id = :idempresa
                        
                        UNION
                        
                        SELECT 
                          id, 
                          nombre 
                        FROM 
                          perfiles_permisos 
                        WHERE 
                          id = 2
                        ORDER BY id asc
      ");
    } else  if((int) $_SESSION['tim_impulsa'] === 1){
      $query = sprintf("
                        SELECT 
                          id, 
                          nombre 
                        FROM 
                          perfiles_permisos 
                        WHERE 
                          tim_impulsa = :ban
                        AND 
                          empresa_id = :idempresa
                        
                        UNION
                        
                        SELECT 
                          id, 
                          nombre 
                        FROM 
                          perfiles_permisos 
                        WHERE 
                          id = 37
                        ORDER BY id asc
      ");
    }
    // $query = sprintf('SELECT id, nombre FROM perfiles_permisos WHERE empresa_id = :idempresa AND id <> 1 AND id <> 2 AND tim_impulsa = :ban
    // UNION
    //     SELECT id, nombre FROM perfiles_permisos WHERE tim_impulsa = :ban1 ORDER BY id asc');
    $stmt = $db->prepare($query);
    $stmt->bindValue(':idempresa', $_SESSION['IDEmpresa']);
    $stmt->bindValue(':ban', $_SESSION['tim_impulsa']);
    //$stmt->bindValue(':ban1', $_SESSION['tim_impulsa']);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);

    $con = "";
    $stmt = "";
    $db = "";
  }

  function getScreen($value)
  {
    $con = new conectar();
    $db = $con->getDbUnica();

    $queryRol = 'SELECT role_id FROM usuarios WHERE id = :id';
    $stmtRol = $db->prepare($queryRol);
    $stmtRol->execute([':id' => $value]);
    $rol = $stmtRol->fetch(PDO::FETCH_OBJ);

    $query = 'SELECT ps.id, ps.pantalla, ps.url FROM permisos_pantallas pp
    INNER JOIN pantallas ps ON pp.pantalla_id =  ps.id
    INNER JOIN perfiles_permisos pfp ON pp.perfil_permiso_id = pfp.id
    INNER JOIN usuarios u ON pfp.id = u.perfil_id
    WHERE u.id = :id AND ps.seccion_id = 8 AND pp.permiso = 1 ORDER BY ps.orden';

    /* if ($rol->role_id === 12) {
      $query = 'SELECT ps.id, ps.pantalla, ps.url FROM permisos_pantallas pp
      INNER JOIN pantallas ps ON pp.pantalla_id =  ps.id
      INNER JOIN perfiles_permisos pfp ON pp.perfil_permiso_id = pfp.id
      INNER JOIN usuarios u ON pfp.id = u.perfil_id
      WHERE u.id = :id AND ps.seccion_id = 8 AND pp.permiso = 1 ORDER BY ps.orden';
    } */

    $stmt = $db->prepare($query);
    $stmt->bindValue(':id', $value, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_OBJ);

    $con = "";
    $stmt = "";
    $db = "";
  }

  function getPermissionScreen($usuario, $pantalla)
  {
    $con = new conectar();
    $db = $con->getDbUnica();

    $query = sprintf('SELECT p.pantalla, pf.id,pf.funcion_ver,pf.funcion_agregar,pf.funcion_editar,pf.funcion_eliminar,pf.funcion_exportar from funciones_permisos pf
                              INNER JOIN perfiles_permisos pfp ON pf.perfil_id = pfp.id
                              INNER JOIN usuarios u ON pfp.id = u.perfil_id
                              INNER JOIN pantallas p ON pf.pantalla_id = p.id
                              WHERE u.id = :user AND pf.pantalla_id = :screen');
    $stmt = $db->prepare($query);
    $stmt->bindValue(':user', $usuario, PDO::PARAM_INT);
    $stmt->bindValue(':screen', $pantalla, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_OBJ);

    $con = "";
    $stmt = "";
    $db = "";
  }

  //function getProfilesTable($value){
  function getProfilesTable()
  {
    $con = new conectar();
    $db = $con->getDbUnica();

    $query = sprintf('SELECT * FROM perfiles_permisos WHERE empresa_id = :idempresa AND tim_impulsa = :ban');
    $stmt = $db->prepare($query);
    $stmt->bindValue(':idempresa', $_SESSION['IDEmpresa']);
    $stmt->bindValue(':ban', $_SESSION['tim_impulsa']);
    $stmt->execute();
    $table = "";
    $array = $stmt->fetchAll();

    //$acciones = '<i class=\"permission-view-edit\"><img class=\"btnEdit\" data-toggle=\"modal\" data-target=\"#editarUsuario\" onclick=\"obtenerIdUsuarioEditar('.$r[$i].');\" src=\"../../img/timdesk/edit.svg\"></i>';
    //$acciones = "";
    $i = count($array);
    foreach ($array as $r) {

      if ($r['estatus'] === 1) {
        $estatus = "Activo";
      } else {
        $estatus = "Inactivo";
      }
      $acciones = '<i class=\"fas fa-edit pointer fas fa-editpermission-view-edit\" id=\"editProfile\" onclick=\"editarPerfilId(' . $r['id'] . ')\" data-id=\"' . $r['id'] . '\"></i>';
      $table .= '{
          "Id":"' . $r["id"] . '",
          "No":"' . $i . '",
          "Nombre":"' . $r["nombre"] . '",
          "Acciones":"' . $acciones . '",
          "Estatus":"' . $estatus . '"
          },';
      $i--;
    }

    $table = substr($table, 0, strlen($table) - 1);

    return '{"data":[' . $table . ']}';

    $con = "";
    $stmt = "";
    $db = "";
  }

  function getCompanyDataTable()
  {
    $con = new conectar();
    $db = $con->getDbUnica();
    $table = "";

    $query = sprintf("SELECT e.*, ef.Estado,crf.clave FROM empresas e LEFT JOIN estados_federativos ef ON e.estado_id = ef.PKEstado LEFT JOIN claves_regimen_fiscal crf ON e.regimen_fiscal_id = crf.id WHERE PKEmpresa = :id");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $_SESSION['IDEmpresa']);
    $stmt->execute();
    $array = $stmt->fetchAll();

    foreach ($array as $r) {

      if ($r["inicio_vencimiento_sello_cfdi"] !== "" && $r["inicio_vencimiento_sello_cfdi"] !== "0000-00-00 00:00:00" && $r["inicio_vencimiento_sello_cfdi"] !== null) {
        $date_start = new DateTime($r["inicio_vencimiento_sello_cfdi"]);
        $inicio_ven = $date_start->format("d-m-Y");
      } else {
        $inicio_ven = "";
      }

      if ($r["termino_vencimiento_sello_cfdi"] !== "" && $r["termino_vencimiento_sello_cfdi"] !== "0000-00-00 00:00:00" && $r["termino_vencimiento_sello_cfdi"] !== null) {
        $date_end = new DateTime($r["termino_vencimiento_sello_cfdi"]);
        $fin_ven = $date_end->format("d-m-Y");
      } else {
        $fin_ven = "";
      }

      if ($inicio_ven !== "" && $fin_ven !== "") {
        $vencimiento = "desde " . $inicio_ven . " hasta " . $fin_ven;
      } else {
        $vencimiento = "";
      }

      if ($r["logo"] !== "" && $r["logo"] !== null) {
        $logo = '<img id=\"logo-company\" src=\"../empresas/archivos/' . $_SESSION['IDEmpresa'] . '/fiscales/' . $r["logo"] . '\" alt=\"' . $r["logo"] . '\" width=\"30\" height=\"30\">';
      } else {
        $logo = "";
      }

      if ($r["numero_interior"] !== "") {
        $int = " Int." . $r["numero_interior"];
      } else {
        $int = "";
      }

      if ($r["telefono"] !== "") {
        $phone =  $r["telefono"];
      } else {
        $phone = "";
      }

      if ($r["calle"] !== null && $r["numero_exterior"] !== null && $r["colonia"] !== null && $r["codigo_postal"] !== null && $r["ciudad"] !== null && $r["Estado"] !== null) {
        $direccion = $r["calle"] . " No. " . $r["numero_exterior"] . $int . " Col. " .  $r["colonia"] . " C.P. " .  $r["codigo_postal"] . " " .  $r["ciudad"] . ", " .  $r["Estado"] . $phone;
      } else {
        $direccion = "";
      }
      $acciones = "";
      if (
        $r["giro_comercial"] !== null ||
        $r["domicilio_fiscal"] !== null ||
        $r["registro_patronal"] !== null ||
        $r["propietario_certificado"] !== null ||
        $r["sello_cfdi"] !== null ||
        $r["inicio_vencimiento_sello_cfdi"] !== null ||
        $r["termino_vencimiento_sello_cfdi"] !== null ||
        $r["logo"] !== null ||
        $r["certificado_archivo"] !== null ||
        $r["llave_certificado_archivo"] !== null ||
        $r["password_certificado"] !== null ||
        $r["key_user_company_api"] !== null ||
        $r["key_company_api"] !== null ||
        $r["calle"] !== null ||
        $r["numero_exterior"] !== null ||
        $r["numero_interior"] !== null ||
        $r["colonia"] !== null ||
        $r["ciudad"] !== null ||
        $r["estado_id"] !== null ||
        $r["codigo_postal"] !== null ||
        $r["regimen_fiscal_id"] !== null ||
        $r["telefono"] !== null ||
        $r["nombre"] !== null ||
        $r["serie_inicial"] !== null ||
        $r["folio_inicial"] !== null
      ) {
        $acciones = '<i class=\"fas fa-edit pointer permission-view-edit\" id=\"editCompanyData\" data-id=\"' . $r['PKEmpresa'] . '\"></i>';
      }


      $table .= '{
          "Giro comercial":"' . $r["giro_comercial"] . '",
          "Razon social":"' . $r["RazonSocial"] . '",
          "RFC":"' . $r["RFC"] . '",
          "Domicilio fiscal":"' . $direccion . '",
          "Regimen fiscal":"' . $r["clave"] . '",
          "IMSS":"' . $r["registro_patronal"] . '",
          "Propietario sello cfdi":"' . $r["propietario_certificado"] . '",
          "Sello cfdi":"' . $r["sello_cfdi"] . '",
          "Vencimiento sello cfdi":"' . $vencimiento . '",
          "Acciones":"' . $acciones . '",
          "Logo":"' . $logo . '"
        },';
    }

    $table = substr($table, 0, strlen($table) - 1);

    return '{"data":[' . $table . ']}';

    $con = "";
    $stmt = "";
    $db = "";
  }

  function getPersonalDataTable()
  {
    $con = new conectar();
    $db = $con->getDbUnica();
    $table = "";

    $query = 
    "SELECT e.PKEmpleado, e.Nombres, e.PrimerApellido, if(e.Genero <> null and e.Genero <> '',e.Genero,'N/A') Genero, ef.Estado,
    (SELECT GROUP_CONCAT(tee.tipo) FROM tipo_empleado tee 
    LEFT JOIN relacion_tipo_empleado rtee ON tee.id = rtee.tipo_empleado_id 
    INNER JOIN empleados ee ON rtee.empleado_id = e.PKEmpleado 
    WHERE ee.empresa_id=e.empresa_id 
    GROUP BY ee.PKEmpleado 
    ORDER BY ee.PKEmpleado ASC LIMIT 1) AS tipo 
    FROM empleados e 
    LEFT JOIN relacion_tipo_empleado rte ON e.PKEmpleado=rte.empleado_id 
    LEFT JOIN tipo_empleado te ON rte.tipo_empleado_id=te.id 
    LEFT JOIN estados_federativos ef ON e.PKEmpleado = ef.PKEstado
    WHERE e.empresa_id=:id 
    GROUP BY e.PKEmpleado 
    ORDER BY e.PKEmpleado
    DESC";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $_SESSION['IDEmpresa'], PDO::PARAM_INT);
    $stmt->execute();
    $array = $stmt->fetchAll();
    foreach ($array as $r) {
      $table .= '{
          "id":"' . $r["PKEmpleado"] . '",
          "Nombre":"' . $r["Nombres"] . ' ' . $r["PrimerApellido"] . '",
          "Genero":"' . $r["Genero"] . '",
          "Estado":"' . $r["Estado"] . '",
          "Roles":"' . $r['tipo'] . '",
          "Acciones":"<i class=\"fas fa-edit pointer permission-view-edit\" data-toggle=\"modal\" data-target=\"#editar_Personal_72\" onclick=\"obtenerIdPersonalEditar(' . $r["PKEmpleado"] . ');\"></i>"
        },';
    }

    $table = substr($table, 0, strlen($table) - 1);

    return '{"data":[' . $table . ']}';

    $con = "";
    $stmt = "";
    $db = "";
  }

  function getCompanyData($value)
  {
    $con = new conectar();
    $db = $con->getDbUnica();

    $query = sprintf("SELECT 
                      RazonSocial,
                      RFC,
                      giro_comercial,
                      calle,
                      numero_exterior,
                      numero_interior,
                      colonia,
                      codigo_postal,
                      ciudad,
                      estado_id,
                      registro_patronal,
                      regimen_fiscal_id,
                      certificado_archivo,
                      logo,
                      llave_certificado_archivo,
                      password_certificado,
                      telefono,
                      serie_inicial,
                      folio_inicial
                    FROM empresas e
                    WHERE PKEmpresa = :id");
    $stmt = $db->prepare($query);
    $stmt->bindValue(":id", $value);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);

    $con = "";
    $stmt = "";
    $db = "";
  }

  function getRegimenFiscal()
  {
    $con = new conectar();
    $db = $con->getDbUnica();

    $query = sprintf("SELECT id, CONCAT(clave,' - ',descripcion) texto FROM claves_regimen_fiscal");
    $stmt = $db->prepare($query);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }
  

  function getStateCompany()
  {
    $con = new conectar();
    $db = $con->getDbUnica();

    $query = sprintf("SELECT PKEstado id, Estado texto FROM estados_federativos where FKPais = 146");
    $stmt = $db->prepare($query);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  function getPersonalU($idEmpleado)
  {
    $con = new conectar();
    $db = $con->getDbUnica();

    $query = 
    "SELECT e.Nombres, e.PrimerApellido, e.Genero, e.FKEstado,
    (SELECT GROUP_CONCAT(teee.id) FROM tipo_empleado teee 
    LEFT JOIN relacion_tipo_empleado rteee ON teee.id = rteee.tipo_empleado_id 
    INNER JOIN empleados eee ON rteee.empleado_id = e.PKEmpleado 
    WHERE eee.empresa_id=e.empresa_id 
    GROUP BY eee.PKEmpleado 
    ORDER BY eee.PKEmpleado ASC LIMIT 1) AS tipo_id
    FROM empleados e 
    LEFT JOIN relacion_tipo_empleado rte ON e.PKEmpleado=rte.empleado_id 
    LEFT JOIN tipo_empleado te ON rte.tipo_empleado_id=te.id
    WHERE e.PKEmpleado=:id
    GROUP BY e.PKEmpleado";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $idEmpleado, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }
}

class save_data
{
  function saveUser($user, $name, $primerApp, $segundoApp, $employer, $role, $profile, $empresa)
  {
    error_reporting(E_ALL ^ E_NOTICE); 
    
    $get = new get_data();
    $noUser = json_decode($get->getNoUsuarios());
    
    if ($noUser->ursCount <= 0) {
      return -777;
    }
    $role = 12;
    /* if($_SESSION['tim_impulsa'] === 1){
        $role = 2;
    } else {
        $role = 12;
    } */
    $fullName = $segundoApp !== "" ? $name . " " .  $primerApp . " " . $segundoApp : $name . " " .  $primerApp;
    date_default_timezone_set('America/Mexico_City');
    $now = date("Y-m-d H:i:s");
    $con = new conectar();
    //$db = $con->getDb();
    $dbU = $con->getDbUnica();
    $cod = new save_data();
    $nombre = "";

    $pass = htmlspecialchars($cod->generateRandomString(12, 1), ENT_QUOTES);
    $password = password_hash($pass, PASSWORD_DEFAULT);
    $codigo = $cod->generateRandomString(12, 2);


    if ($employer !== "") {
      $query1 = sprintf('SELECT CONCAT(Nombres," ",PrimerApellido) Nombre FROM empleados WHERE PKEmpleado = ?');
      $stmt1 = $dbU->prepare($query1);
      $stmt1->execute(array($employer));
      $nombre = $stmt1->fetch()['Nombre'];
    } else {
      $nombre = $fullName;
    }

    if ($profile !== "") {
      $query = sprintf('SELECT roles_id FROM perfiles_permisos WHERE id = ? AND estatus = 1');
      $stmt = $dbU->prepare($query);
      $stmt->execute(array($profile));
      $rol = $stmt->fetch()['roles_id'];
      $perfil = $profile;
    } else {
      $query = sprintf("SELECT pp.id FROM roles r INNER JOIN perfiles_permisos pp ON r.id = pp.roles_id WHERE r.id = ?");
      $stmt = $dbU->prepare($query);
      $stmt->execute(array($role));
      $rol = $role;
      $perfil = $stmt->fetch()['id'];
    }
    
    try {
      
      $queryind = sprintf("SELECT valor FROM parametros_servidor WHERE parametro = 'url' OR parametro = 'email_contacto' ");
      $stmtind = $dbU->prepare($queryind);
      $stmtind->execute();
      $url = $stmtind->fetchAll();

      $query3 = sprintf('SELECT * FROM usuarios WHERE usuario = ?');
      $stmt3 = $dbU->prepare($query3);
      $stmt3->execute(array($user));
      $count = $stmt3->rowCount();
      
      if ($count > 0) {
        $id = 0;
      } else {
        if ($employer === "") {
          
          if($noUser->stripe_status === 'trialing' && $noUser->ursCount === 1){
            $query = sprintf('INSERT INTO empleados (Nombres, PrimerApellido, SegundoApellido, Genero, CURP, RFC, Telefono, Direccion, NumeroExterior, Interior, Ciudad, Colonia, CP, FKEstadoCivil, FKEstado, empresa_id, estatus, id_empleado, is_generic) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
            $stmt = $dbU->prepare($query);
            $stmt->execute(array($name, $primerApp, $segundoApp, "XXXXXX", "XXXXXX", "XXXXXX", "XXXXXX", "XXXXXX", 0, "XXXXXX", "XXXXXX", "XXXXXX", 00000, 1, 1, $empresa, 1, 0, 1));
            $id_user = $dbU->lastInsertId();

            $query = sprintf('SELECT * FROM widgets');
            $stmt = $dbU->prepare($query);
            $stmt->execute();
            $widgets = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($widgets as $widget) {
              $query = sprintf('INSERT INTO permisos_widgets (FKUsuario, FKWidget, Permiso) VALUES (?,?,?)');
              $stmt = $dbU->prepare($query);
              $stmt->execute([$id_user, $widget['id'], 1]);
            }

            $query = sprintf('INSERT INTO usuarios (id,usuario,email,password,nombre,codigo,estatus,created_at,updated_at,role_id,perfil_id,empresa_id,tim_impulsa) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)');
            $stmt = $dbU->prepare($query);
            $emailenviado = false;
            $email_cuenta = 0;

            if ($stmt->execute(array($id_user, $user, $user, $password, $nombre, $codigo, 0, $now, $now, $role, $profile, $empresa,$_SESSION['tim_impulsa']))) {
              $id = $id_user;
              //$this->saveColums($id);
              while ($emailenviado == false && $email_cuenta < 3) {
                $emailenviado = $cod->sendEmail($id, $user, $nombre, $pass, $codigo);
                $email_cuenta++;
                if ($email_cuenta > 2) {
                  $emailenviado = true;
                }
              }
            }
          } else if($noUser->stripe_status === 'active' && $noUser->ursCount > 0) {
            $query = sprintf('INSERT INTO empleados (Nombres, PrimerApellido, SegundoApellido, Genero, CURP, RFC, Telefono, Direccion, NumeroExterior, Interior, Ciudad, Colonia, CP, FKEstadoCivil, FKEstado, empresa_id, estatus, id_empleado, is_generic) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
            $stmt = $dbU->prepare($query);
            $stmt->execute(array($name, $primerApp, $segundoApp, "XXXXXX", "XXXXXX", "XXXXXX", "XXXXXX", "XXXXXX", 0, "XXXXXX", "XXXXXX", "XXXXXX", 00000, 1, 1, $empresa, 1, 0, 1));
            $id_user = $dbU->lastInsertId();

            $query = sprintf('SELECT * FROM widgets');
            $stmt = $dbU->prepare($query);
            $stmt->execute();
            $widgets = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($widgets as $widget) {
              $query = sprintf('INSERT INTO permisos_widgets (FKUsuario, FKWidget, Permiso) VALUES (?,?,?)');
              $stmt = $dbU->prepare($query);
              $stmt->execute([$id_user, $widget['id'], 1]);
            }

            $query = sprintf('INSERT INTO usuarios (id,usuario,email,password,nombre,codigo,estatus,created_at,updated_at,role_id,perfil_id,empresa_id,tim_impulsa) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)');
            $stmt = $dbU->prepare($query);
            $emailenviado = false;
            $email_cuenta = 0;

            if ($stmt->execute(array($id_user, $user, $user, $password, $nombre, $codigo, 0, $now, $now, $role, $profile, $empresa,$_SESSION['tim_impulsa']))) {
             $id = $id_user;
              //$this->saveColums($id);
              while ($emailenviado == false && $email_cuenta < 3) {
                $emailenviado = $cod->sendEmail($id, $user, $nombre, $pass, $codigo);
                $email_cuenta++;
                if ($email_cuenta > 2) {
                  $emailenviado = true;
                }
              }
            }
          }
        } else {
          if($noUser->stripe_status === 'trialing' && $noUser->ursCount === 1){
            $query = sprintf('SELECT * FROM widgets');
            $stmt = $dbU->prepare($query);
            $stmt->execute();
            $widgets = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($widgets as $widget) {
              $query = sprintf('INSERT INTO permisos_widgets (FKUsuario, FKWidget, Permiso) VALUES (?,?,?)');
              $stmt = $dbU->prepare($query);
              $stmt->execute([$employer, $widget['id'], 1]);
            }

            $query = sprintf('INSERT INTO usuarios (id,usuario,email,password,nombre,codigo,estatus,created_at,updated_at,role_id,perfil_id,empresa_id,tim_impulsa) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)');
            $stmt = $dbU->prepare($query);
            $emailenviado = false;
            $email_cuenta = 0;

            if ($stmt->execute(array($employer, $user, $user, $password, $nombre, $codigo, 0, $now, $now, $role, $profile, $empresa,$_SESSION['tim_impulsa']))) {
              $id = $employer;
              //$this->saveColums($id);
              while ($emailenviado == false && $email_cuenta < 3) {
                $emailenviado = $cod->sendEmail($id, $user, $nombre, $pass, $codigo);
                $email_cuenta++;
                if ($email_cuenta > 2) {
                  $emailenviado = true;
                }
              }
            }
          } else if($noUser->stripe_status === 'active' && $noUser->ursCount > 0){
            $query = sprintf('SELECT * FROM widgets');
            $stmt = $dbU->prepare($query);
            $stmt->execute();
            $widgets = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($widgets as $widget) {
              $query = sprintf('INSERT INTO permisos_widgets (FKUsuario, FKWidget, Permiso) VALUES (?,?,?)');
              $stmt = $dbU->prepare($query);
              $stmt->execute([$employer, $widget['id'], 1]);
            }
            
            $query = sprintf('INSERT INTO usuarios (id,usuario,email,password,nombre,codigo,estatus,created_at,updated_at,role_id,perfil_id,empresa_id,tim_impulsa) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)');
            $stmt = $dbU->prepare($query);
            $emailenviado = false;
            $email_cuenta = 0;

            if ($stmt->execute(array($employer, $user, $user, $password, $nombre, $codigo, 0, $now, $now, $role, $profile, $empresa,$_SESSION['tim_impulsa']))) {
              $id = $employer;
              //$this->saveColums($id);
              while ($emailenviado == false && $email_cuenta < 3) {
                $emailenviado = $cod->sendEmail($id, $user, $nombre, $pass, $codigo);
                $email_cuenta++;
                if ($email_cuenta > 2) {
                  $emailenviado = true;
                }
              }
            }
          }
        }
        /* if ($employer !== "") {
          $query2 = sprintf("INSERT INTO empleados_usuarios (FKEmpleado,FKUsuario) VALUES (?,?)");
          $stmt2 = $dbU->prepare($query2);
          $stmt2->execute(array($employer, $id));
        } */
      }
      return $id;
    } catch (PDOException $e) {
      return "Error en Consulta: " . $e->getMessage();
    }
    $con = "";
    $stmt = "";
    $dbU = "";
  }

  public function saveMarca($value)
  {
      $con = new conectar();
      $db = $con->getDbUnica();

      $PKEmpresa = $_SESSION["IDEmpresa"];

      try {
          
          $query = sprintf('call spi_Marca_Agregar(?,?)');
          $stmt = $db->prepare($query);
          $status = $stmt->execute(array($value, $PKEmpresa));
          
          $data[0] = ['status' => $status];
          return $data;
      } catch (PDOException $e) {
          
          return "Error en Consulta: " . $e->getMessage();
      }
  }

  public function saveCategoria($value)
  {
      $con = new conectar();
      $db = $con->getDbUnica();
        
        $PKEmpresa = $_SESSION["IDEmpresa"];

        try {
            $query = sprintf('call spi_Categoria_Agregar(?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($value, $PKEmpresa));

            $data[0] = ['status' => $status];
            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $con = "";
        $stmt = "";
        $db = "";
  }

  public function saveCategoriaClientes($value)
  {
    $con = new conectar();
    $db = $con->getDbUnica();
      
      $PKEmpresa = $_SESSION["IDEmpresa"];

      try {
          $query = sprintf('INSERT into categorias_clientes (nombre, estatus, empresa_id) values
                            (?, 1, ?);');
          $stmt = $db->prepare($query);
          $status = $stmt->execute(array($value, $PKEmpresa));

          $data[0] = ['status' => $status];
          return $data;

      } catch (PDOException $e) {
          return "Error en Consulta: " . $e->getMessage();
      }

      $con = "";
      $stmt = "";
      $db = "";
  }

  function savePersonal($nombre, $apellido, $genero, $roles, $estado)
  {
    $con = new conectar();
    $db = $con->getDbUnica();
      
      $PKEmpresa = $_SESSION["IDEmpresa"];
      $idEmpleado=0;

    try {
      $selectIdEmpleado = "SELECT MAX(id_empleado) + 1 AS id_empleado FROM empleados WHERE empresa_id=:empresa_id";
      $stmSelectIdEmpleado = $db->prepare($selectIdEmpleado);
      $stmSelectIdEmpleado->execute(array(':empresa_id' => $PKEmpresa));
      $id_empleado = $stmSelectIdEmpleado->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      return "Error en Consulta: " . $e->getMessage();
    }

    try {
        $query = "INSERT INTO empleados(id_empleado, Nombres, PrimerApellido, Genero, FKEstado, empresa_id, estatus)
                  VALUES (:id_empleado, :nombre, :apellido, :genero, :estado_id, :empresa_id, 1)";
        $stmt = $db->prepare($query);
        $stmt->execute(array(':id_empleado' => $id_empleado['id_empleado'],':nombre' => $nombre,':apellido' => $apellido,':genero' => $genero,':estado_id' => $estado, ':empresa_id' => $PKEmpresa));
        $idEmpleado = $db->lastInsertId();

    } catch (PDOException $e) {
        return "Error en Consulta: " . $e->getMessage();
    }
    
    try {
      foreach($roles as $rol){
        $query = "INSERT INTO relacion_tipo_empleado (empleado_id, tipo_empleado_id)
                  VALUES (:empleado_id, :rol_id)";
        $stmt = $db->prepare($query);
        $stmt->execute(array(':empleado_id' => $idEmpleado,':rol_id' => $rol));
      }
    } catch (PDOException $e) {
      return "Error en Consulta: " . $e->getMessage();
    }
      return 'exito';
  }

  function generateRandomString($length = 12, $passwordModo = 1)
  {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $charactersEsp = '@$!%*?&';
    $charactersEspLength = strlen($charactersEsp);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {

      if ($passwordModo == 1) {
        if ($i != 5) {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
        } else {
          $randomString .= $charactersEsp[rand(0, $charactersEspLength - 1)];
        }
      } else {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
    }
    return $randomString;
  }

  function sendEmail($id, $user, $name, $password, $code)
  {
    $envVariables = GetEvn();
    $appUrl = $envVariables['server'];
    $origen = $envVariables['origenMail'];

    require('../../../lib/phpmailer_configuration.php');

    try {
      $usuario_envia = "Timlid";
      $mail->Sender = $origen;
      $mail->setFrom($origen, $usuario_envia);
      $mail->addReplyTo($origen, $usuario_envia);
      $mail->addAddress($user);     //Add a recipient  $user

      $cod = new save_data();
      $idEnc = $cod->encryptor("encrypt", $id);
      $codeEnc = $cod->encryptor("encrypt", $code);

      $mensaje = $name . "<br><br>" . "Se te ha generado un usuario en el sistema Timlid, para poder activarlo ingresa en el siguiente link:<br><br><a href='" . $appUrl . "index.php?id=" . $idEnc . "&codigo=" . $codeEnc . "' target='_blank' title='TimLid - Activación de cuenta'>Timlid - Activar cuenta</a>";
      $mensaje .= "<br><br>" . "<b>Correo:</b> " . $user . "<br>";
      $mensaje .= "<b>Contraseña:</b> " . $password . "<br>";
      //Content
      $mail->isHTML(true);                                  //Set email format to HTML
      $mail->Subject = utf8_decode("Timlid - Activar cuenta");
      $mail->Body    = utf8_decode($mensaje);

      if (!$mail->send()) {
        return false;
      }
      return true;
    } catch (Exception $e) {
      //header('Location: ver_Cotizacion.php?id='.$id.'&estatus=2');
      return $e->getMessage();
      //return false;
    }
  }

  function encryptor($action, $string)
  {
    $output = false;

    $encrypt_method = "AES-256-CBC";

    $secret_key = 'f(>FZA18Nx$m5$)8jT*wG2_-u8V#C0aI[jalB?k5z439itWgp;p<RIIASB[OeoC';
    $secret_iv = 'Eix{6C]T.xdc;Z!rZxgYsw[~IpR@/*X=3O5QKpGNv[2b)qt&ETt_6<R=@e}]qu8';

    // hash
    $key = hash('sha256', $secret_key);

    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    if ($action == 'encrypt') {
      $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
      $output = base64_encode($output);
    } else if ($action == 'decrypt') {
      $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }

    return $output;
  }

  function saveColums($id)
  {
    $con = new conectar();
    $dbU = $con->getDbUnica();
    $query = sprintf("INSERT INTO columnas_productos (FKTipoColumnaProductos, Orden, Seleccionada, usuario_id) VALUES 
              (1, 1, 1, :usuario_id1),
              (2, 2, 1, :usuario_id2),
              (3, 3, 1, :usuario_id3),
              (4, 4, 1, :usuario_id4),
              (5, 5, 1, :usuario_id5),
              (6, 6, 1, :usuario_id6),
              (7, 7, 1, :usuario_id7),
              (8, 8, 1, :usuario_id8),
              (9, 9, 1, :usuario_id9),
              (10, 10, 1, :usuario_id10)");
    $stmt = $dbU->prepare($query);
    $stmt->bindValue(":usuario_id1", $id);
    $stmt->bindValue(":usuario_id2", $id);
    $stmt->bindValue(":usuario_id3", $id);
    $stmt->bindValue(":usuario_id4", $id);
    $stmt->bindValue(":usuario_id5", $id);
    $stmt->bindValue(":usuario_id6", $id);
    $stmt->bindValue(":usuario_id7", $id);
    $stmt->bindValue(":usuario_id8", $id);
    $stmt->bindValue(":usuario_id9", $id);
    $stmt->bindValue(":usuario_id10", $id);
    $stmt->execute();

    $query = sprintf("INSERT INTO columnas_clientes (FKTipoColumnaClientes, Orden, Seleccionada, usuario_id) VALUES 
              (1, 1, 1, :usuario_id1),
              (2, 2, 1, :usuario_id2),
              (3, 3, 1, :usuario_id3),
              (4, 4, 1, :usuario_id4),
              (5, 5, 1, :usuario_id5),
              (6, 6, 1, :usuario_id6),
              (7, 7, 1, :usuario_id7),
              (8, 8, 1, :usuario_id8),
              (9, 9, 1, :usuario_id9),
              (10, 10, 1, :usuario_id10),
              (11, 11, 1, :usuario_id11),
              (11, 12, 1, :usuario_id12)");
    $stmt = $dbU->prepare($query);
    $stmt->bindValue(":usuario_id1", $id);
    $stmt->bindValue(":usuario_id2", $id);
    $stmt->bindValue(":usuario_id3", $id);
    $stmt->bindValue(":usuario_id4", $id);
    $stmt->bindValue(":usuario_id5", $id);
    $stmt->bindValue(":usuario_id6", $id);
    $stmt->bindValue(":usuario_id7", $id);
    $stmt->bindValue(":usuario_id8", $id);
    $stmt->bindValue(":usuario_id9", $id);
    $stmt->bindValue(":usuario_id10", $id);
    $stmt->bindValue(":usuario_id11", $id);
    $stmt->bindValue(":usuario_id12", $id);
    $stmt->execute();

    $query = sprintf("INSERT INTO columna_proveedores (FKTipoColumnaProveedores, Orden, Seleccionada, usuario_id) VALUES 
              (1, 1, 1, :usuario_id1),
              (2, 2, 1, :usuario_id2),
              (4, 3, 1, :usuario_id3),
              (5, 4, 1, :usuario_id4),
              (6, 5, 1, :usuario_id5),
              (7, 6, 1, :usuario_id6),
              (8, 7, 1, :usuario_id7),
              (9, 8, 1, :usuario_id8),
              (10, 9, 1, :usuario_id9),
              (11, 10, 1, :usuario_id10),
              (11, 11, 1, :usuario_id11)");
    $stmt = $dbU->prepare($query);
    $stmt->bindValue(":usuario_id1", $id);
    $stmt->bindValue(":usuario_id2", $id);
    $stmt->bindValue(":usuario_id3", $id);
    $stmt->bindValue(":usuario_id4", $id);
    $stmt->bindValue(":usuario_id5", $id);
    $stmt->bindValue(":usuario_id6", $id);
    $stmt->bindValue(":usuario_id7", $id);
    $stmt->bindValue(":usuario_id8", $id);
    $stmt->bindValue(":usuario_id9", $id);
    $stmt->bindValue(":usuario_id10", $id);
    $stmt->bindValue(":usuario_id11", $id);
    $stmt->execute();
  }

  function saveWidgets($widgets, $empresaID)
  {
    if (
      $this->updateWidgetPermissions($widgets['wgCalendario'], 10, $empresaID) &&
      $this->updateWidgetPermissions($widgets['wgCuentasCobrar'], 5, $empresaID) &&
      $this->updateWidgetPermissions($widgets['wgCuentasPagar'], 4, $empresaID) &&
      $this->updateWidgetPermissions($widgets['wgCumpleanios'], 8, $empresaID) &&
      $this->updateWidgetPermissions($widgets['wgMiFacturacion'], 1, $empresaID) &&
      $this->updateWidgetPermissions($widgets['wgNotas'], 9, $empresaID) &&
      $this->updateWidgetPermissions($widgets['wgProyectos'], 7, $empresaID) &&
      $this->updateWidgetPermissions($widgets['wgVentas'], 2, $empresaID) &&
      $this->updateWidgetPermissions($widgets['wgVentasAnio'], 6, $empresaID) &&
      $this->updateWidgetPermissions($widgets['wgVentasAnioGrafica1'], 11, $empresaID) &&
      $this->updateWidgetPermissions($widgets['wgVentasAnioGrafica2'], 12, $empresaID) &&
      $this->updateWidgetPermissions($widgets['wgVentasEjecutivo'], 3, $empresaID) &&
      $this->updateWidgetPermissions($widgets['wgVentasMes'], 13, $empresaID)
    ) {
      return json_encode(['status' => 'success', 'message' => 'Permisos actualizados correctamente.']);
    }
    return json_encode(['status' => 'fail', 'message' => 'Algo salio mal.']);
  }

  function updateWidgetPermissions($wgPermissionsUserID, $widgetID, $empresaID)
  {
    try {
      $con = new conectar();
      $dbU = $con->getDbUnica();
      $query = "UPDATE permisos_widgets AS pwg
      INNER JOIN usuarios AS u ON pwg.FKUsuario = u.id
      SET pwg.permiso = 0
      WHERE u.empresa_id = :empresa_id
      AND pwg.FKWidget = :widget_id";
      $stmt = $dbU->prepare($query);
      $stmt->bindValue(":empresa_id", $empresaID);
      $stmt->bindValue(":widget_id", $widgetID);
      $stmt->execute();

      foreach ($wgPermissionsUserID as $wgPermissionUserID) {
        $query = "UPDATE permisos_widgets
        SET permiso = 1
        WHERE FKUsuario = :usuario_id
        AND FKWidget = :widget_id";
        $stmt = $dbU->prepare($query);
        $stmt->bindValue(":usuario_id", $wgPermissionUserID);
        $stmt->bindValue(":widget_id", $widgetID);
        $stmt->execute();
      }
      return true;
    } catch (\Throwable $th) {
      //return ['status' => 'fail', 'message' => $th->getMessage()];
      return false;
    }
  }
}

class update_data
{
  function updateUser($idUser, $user, $name, $role, $profile)
  {
    error_reporting(E_ALL ^ E_NOTICE); 

    $con = new conectar();
    $db = $con->getDbUnica();
    $role = $_SESSION['tim_impulsa'] === 1 ? 12 : 2;
    if ($profile !== "") {
      $query = sprintf('SELECT roles_id FROM perfiles_permisos WHERE id = ? AND estatus = 1');
      $stmt = $db->prepare($query);
      $stmt->execute(array($profile));
      $rol = $stmt->fetch()['roles_id'];
      $perfil = $profile;
    } else {
      $query = sprintf("SELECT pp.id FROM roles r INNER JOIN 
                                 perfiles_permisos pp ON r.id = pp.roles_id
                                 WHERE r.id = ?");
      $stmt = $db->prepare($query);
      $stmt->execute(array($role));
      $rol = $role;
      $perfil = $stmt->fetch()['id'];
    }
    
    try {
      $query3 = sprintf('SELECT * FROM usuarios WHERE usuario = ? AND id != ?');
      $stmt3 = $db->prepare($query3);
      $stmt3->execute(array($user, $idUser));
      $count = $stmt3->rowCount();
      if ($count > 0) {
        return -777;
      } else {
        $query = sprintf('UPDATE usuarios SET usuario = :usuario, nombre = :nombre, role_id = :rol, perfil_id = :perfil WHERE id = :usuario_id');
        $stmt = $db->prepare($query);
        $stmt->bindValue(":usuario_id", $idUser, PDO::PARAM_INT);
        $stmt->bindValue(":usuario", $user, PDO::PARAM_STR);
        $stmt->bindValue(":nombre", $name, PDO::PARAM_STR);
        $stmt->bindValue(":rol", $role, PDO::PARAM_INT);
        $stmt->bindValue(":perfil", $perfil, PDO::PARAM_INT);
        return $stmt->execute();
      }
    } catch (PDOException $e) {
      return "Error en Consulta: " . $e->getMessage();
    }

    $con = "";
    $stmt = "";
    $db = "";
  }
  public function editCategoria($estatus, $categoria, $id)
    {
      $con = new conectar();
      $db = $con->getDbUnica();

        try {
            $query = sprintf('call spu_Categoria_Datos_Actualizar(?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($estatus, $categoria, $id));

            $data[0] = ['status' => $status];
            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $con = "";
        $stmt = "";
        $db = "";
    }

    public function editCategoriaClientes($estatus, $categoria, $id)
    {
      $con = new conectar();
      $db = $con->getDbUnica();

        try {
          //comprueba que sea posible eliminar la categoría
          $query = sprintf('SELECT EXISTS(SELECT PKCliente from clientes c where c.FKCategoriaCliente = ?) as existe ');
          $stmt = $db->prepare($query);
          $stmt->execute(array($categoria));
          $res = $stmt->fetchAll();

          if($res[0]['existe'] == 0){
            $query = sprintf('UPDATE categorias_clientes cc set 
                                      cc.nombre = ?, 
                                      cc.estatus = ?
                              where cc.PKCategoria_cliente = ? and cc.empresa_id = ?');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($categoria, $estatus, $id, $_SESSION['IDEmpresa']));

            $data[0] = ['status' => $status];
          }else{
            $data[0] = ['msj' => 'No es posible deshabilitar una categoria asignada a clientes'];
          }
            
            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $con = "";
        $stmt = "";
        $db = "";
    }

  public function editMarca($estatus, $marca, $id)
    {
      $con = new conectar();
      $db = $con->getDbUnica();

        try {
            $query = sprintf('call spu_Marca_Datos_Actualizar(?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($estatus, $marca, $id));

            $data[0] = ['status' => $status];
            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $con = "";
        $stmt = "";
        $db = "";
    }

  function activateUser($idUser)
  {
    $get = new get_data();
    $noUser = json_decode($get->getNoUsuarios());
    if ($noUser->ursCount <= 0) {
      return 3;
    }
    date_default_timezone_set('America/Mexico_City');
    $con = new conectar();
    $db = $con->getDbUnica();
    $now = date("Y-m-d H:i:s");
    try {
      //$query = sprintf('DELETE FROM usuarios WHERE id = :id_usuario');
      $query = sprintf('UPDATE usuarios SET estatus = 1, updated_at = :update_at WHERE id = :id_usuario');
      $stmt = $db->prepare($query);
      $stmt->bindValue("update_at", $now);
      $stmt->bindValue(":id_usuario", $idUser, PDO::PARAM_INT);
      if ($stmt->execute()) {
        return 1;
      }
      return 2;
    } catch (PDOException $e) {
      if ($stmt->errorInfo()[1]) {
        return 2;
      }
    }
    $con = "";
    $stmt = "";
    $db = "";
  }

  function resendEmail($idUser)
  {
    $con = new conectar();
    $db = $con->getDbUnica();
    $saveData = new save_data();
    $now = date("Y-m-d H:i:s");
    $data = [];
    try {
      $query1 = sprintf('SELECT CONCAT(e.Nombres, " ", e.PrimerApellido) AS nombre, u.usuario AS email
    FROM empleados AS e
    INNER JOIN usuarios AS u ON e.PKEmpleado = u.id
    WHERE e.PKEmpleado = :idEmpleado AND e.empresa_id = :idEmpresa');
      $stmt = $db->prepare($query1);
      if (!$stmt->execute([':idEmpleado' => $idUser, ':idEmpresa' => $_SESSION["IDEmpresa"]])) {
        throw new \Exception('No existe el usuario.');
      }
      $res = $stmt->fetch(PDO::FETCH_ASSOC);
      $nombre = $res['nombre'];
      $email = $res['email'];
      $password = $saveData->generateRandomString(12, 1);
      $passwordhash = password_hash($password, PASSWORD_DEFAULT);
      $codigo = $saveData->generateRandomString(12, 2);

      $query = sprintf('UPDATE usuarios SET password = :newPassword, updated_at = :updatedAt WHERE id = :idUsuario');
      $stmt = $db->prepare($query);
      if (!$stmt->execute([':newPassword' => $passwordhash, ':updatedAt' => $now, ':idUsuario' => $idUser])) {
        throw new \Exception('Fallo al actualizar usuario.');
      }
      if(!$saveData->sendEmail($idUser, $email, $nombre, $password, $codigo)) {
        throw new \Exception('Fallo al enviar el email.');
      }
      /* $emailenviado = false;
      $email_cuenta = 0;
      while ($emailenviado == false && $email_cuenta < 3) {
        $emailenviado = $saveData->sendEmail($idUser, $email, $nombre, $password, $codigo);
        $email_cuenta++;
        if ($email_cuenta > 2) {
          $emailenviado = true;
        }
      } */
      
      $data['status'] = 'success';
      $data['message'] = 'Email enviado';
      return $data;
    } catch (Exception $e) {
      $data['status'] = 'fail';
      $data['message'] = $e->getMessage();
      return $data;
    }
  }

  function updatePersonal($nombre, $apellido, $genero, $roles, $estado, $idEmpleado)
  {
    $con = new conectar();
    $db = $con->getDbUnica();
    try {
      $query = 'UPDATE empleados SET Nombres = :nombre, PrimerApellido = :apellido, Genero = :genero, FKEstado = :estado WHERE PKEmpleado = :empleado_id';
      $stmt = $db->prepare($query);
      $stmt->execute(array(':nombre' => $nombre, ':apellido' => $apellido, ':genero' => $genero, ':estado' => $estado, ':empleado_id' => $idEmpleado));
    } catch (PDOException $e) {
      return "Error en Consulta: " . $e->getMessage();
    }

    try {
      $query = 'DELETE FROM relacion_tipo_empleado WHERE empleado_id = :empleado_id';
      $stmt = $db->prepare($query);
      $stmt->execute(array(':empleado_id' => $idEmpleado));
    } catch (PDOException $e) {
      return "Error en Consulta: " . $e->getMessage();
    }

    try {
      foreach($roles as $rol){
        $query = "INSERT INTO relacion_tipo_empleado (empleado_id, tipo_empleado_id)
                  VALUES (:empleado_id, :rol_id)";
        $stmt = $db->prepare($query);
        $stmt->execute(array(':empleado_id' => $idEmpleado,':rol_id' => $rol));
      }
    } catch (PDOException $e) {
      return "Error en Consulta: " . $e->getMessage();
    }
    return 'exito';
    $con = "";
    $stmt = "";
    $db = "";
  }
}

class delete_data
{
  function deleteUser($value)
  {
    date_default_timezone_set('America/Mexico_City');
    $con = new conectar();
    $db = $con->getDbUnica();

    $now = date("Y-m-d H:i:s");

    try {
      //$query = sprintf('DELETE FROM usuarios WHERE id = :id_usuario');
      $query = sprintf('UPDATE usuarios SET estatus = 2, updated_at = :update_at WHERE id = :id_usuario');
      $stmt = $db->prepare($query);
      $stmt->bindValue("update_at", $now);
      $stmt->bindValue(":id_usuario", $value, PDO::PARAM_INT);
      return $stmt->execute();
    } catch (PDOException $e) {
      if ($stmt->errorInfo()[1]) {
        return "No se puede eliminar el registro. Se está utilizando en alguna parte.";
      }
    }
    $con = "";
    $stmt = "";
    $db = "";
  }
  public function deleteCategoria($value)
  {
    $con = new conectar();
    $db = $con->getDbUnica();

      try {
          $query = sprintf('call spd_EliminarCategoria(?)');
          $stmt = $db->prepare($query);
          $status = $stmt->execute(array($value));

          $data[0] = ['status' => $status];
          return $data;

      } catch (PDOException $e) {
          return "Error en Consulta: " . $e->getMessage();
      }

      $con = "";
      $stmt = "";
      $db = "";
  }

  public function deleteCategoriaCliente($value)
  {
    $con = new conectar();
    $db = $con->getDbUnica();

      try {
        $db->beginTransaction();

          //comprueba que sea posible eliminar la categoría
          $query = sprintf('SELECT EXISTS(SELECT PKCliente from clientes c where c.FKCategoriaCliente = ?) as existe ');
          $stmt = $db->prepare($query);
          $stmt->execute(array($value));
          $res = $stmt->fetchAll();

          if($res[0]['existe'] == 0){
            $query = sprintf('UPDATE categorias_clientes cc set  
                        cc.estatus = 3
                  where cc.PKCategoria_cliente = ? and cc.empresa_id = ?;');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($value, $_SESSION['IDEmpresa']));

            $data[0] = ['status' => $status];
          }else{
            $data[0] = ['msj' => 'No es posible eliminar una categoria asignada a clientes'];
          }
          $db->commit();
          return $data;
      } catch (PDOException $e) {
        $db->rollBack();
        return "Error en Consulta: " . $e->getMessage();
      }

      $con = "";
      $stmt = "";
      $db = "";
  }

  public function deleteMarca($value)
  {
    $con = new conectar();
    $db = $con->getDbUnica();

      try {
          $query = sprintf('call spd_EliminarMarca(?)');
          $stmt = $db->prepare($query);
          $status = $stmt->execute(array($value));

          $data[0] = ['status' => $status];
          return $data;

      } catch (PDOException $e) {
          return "Error en Consulta: " . $e->getMessage();
      }

      $con = "";
      $stmt = "";
      $db = "";
  }

  function deleteCompanyData($value)
  {
    $con = new conectar();
    $db = $con->getDbUnica();

    try {
      $query = sprintf("UPDATE empresas SET 
                            giro_comercial = '',
                            domicilio_fiscal='',
                            registro_patronal = '',
                            regimen_fiscal = '', 
                            propietario_certificado = '', 
                            sello_cfdi = '', 
                            inicio_vencimiento_sello_cfdi='',
                            termino_vencimiento_sello_cfdi='',
                            logo='',
                            certificado_archivo='',
                            llave_certificado_archivo=''
                    WHERE PKEmpresa = :id");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id", $value);
      echo $stmt->execute();
    } catch (PDOException $e) {
      return "Error en Consulta: " . $e->getMessage();
    }
  }

  function deletePersonal($idEmpleado)
  {
    $con = new conectar();
    $db = $con->getDbUnica();

    try {
      $queryDeleteEmp = "DELETE FROM empleados WHERE PKEmpleado = :id";
      $stmtDeleteEmp = $db->prepare($queryDeleteEmp);
      $stmtDeleteEmp->bindValue(":id", $idEmpleado, PDO::PARAM_INT);
      $stmtDeleteEmp->execute();
    } catch (PDOException $e) {
      return "Error en Consulta: " . $e->getMessage();
    }

    try {
      $queryDeleteRelacionEmp = 'DELETE FROM relacion_tipo_empleado WHERE empleado_id = :id';
      $stmtDeleteRelacionEmp = $db->prepare($queryDeleteRelacionEmp);
      $stmtDeleteEmp->bindValue(":id", $idEmpleado, PDO::PARAM_INT);
      $stmtDeleteRelacionEmp->execute();
    } catch (PDOException $e) {
      return "Error en Consulta: " . $e->getMessage();
    }
    return 'exito';
  }
}
