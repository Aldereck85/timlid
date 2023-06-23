<?php
date_default_timezone_set('America/Mexico_City');

class conectar
{
    public function getDb()
    {
        include "../../../include/db-conn.php";
        return $conn;
    }
}

/* ---------------------------------------------------------------- */
/* Consulta los permisos de la pantalla actual */

        $con = new conectar();
        $db = $con->getDb(); 
        //Traer la url actual
        $url = explode("catalogos/", $_SERVER["REQUEST_URI"]);
        $url = $url[1];

        ///Consultar el id de la pantalla 
        $stmt = $db->prepare("SELECT id
        FROM pantallas as p
        WHERE p.url LIKE '$url';");
        $stmt->execute();
        $row = $stmt->fetch();

        //$pantallaid = $row['id'];
        $pantallaid = 71;
        $pkuser = $_SESSION["PKUsuario"];
        $stmt2 = $db->prepare("Select funcion_ver, funcion_agregar, funcion_editar, funcion_eliminar, funcion_exportar, 
             pantalla_id, fp.perfil_id from funciones_permisos as fp inner join usuarios as us 
             on fp.perfil_id = us.perfil_id where us.id = $pkuser and pantalla_id = $pantallaid");
      $stmt2->execute();
      $row2 = $stmt2->fetch();
      //Ponemos en el DOM el permiso ver
      echo ('<input id="ver" type="hidden" value="' . $row2['funcion_ver'] . '">');
      //echo ('<input id="add" type="hidden" value="' . $row2['funcion_agregar'] . '">');
      echo ('<input id="exportar" type="hidden" value="' . $row2['funcion_exportar'] . '">');


?>