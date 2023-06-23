<?php
session_start();

if (isset($_POST['id'])) {
    $idEmpresa = $_SESSION['IDEmpresa'];
    require_once('../../../include/db-conn.php');

    $json = new \stdClass();

    $id = $_POST['id'];
    $stmt = $conn->prepare("SELECT * FROM proyectos WHERE PKProyecto = :id");
    $stmt->execute(array(':id' => $id));
    $row = $stmt->fetch();

    if (count($row) > 0) {
        $html = $row['Proyecto'];
    } else {
        $html = 'No tiene nombre de proyecto.';
    }
    $json->html = $html;

    $json->htmlDescripcion = $row['Descripcion'];

    //obtiene el encargado
    $stmt = $conn->prepare("SELECT u.id, u.nombre as nombre_usuario, CONCAT(e.Nombres,' ',e.PrimerApellido,' ',e.SegundoApellido) as nombre_empleado
    FROM usuarios as u
    LEFT JOIN empleados_usuarios as eu ON eu.FKUsuario = u.id
    LEFT JOIN empleados as e ON e.PKEmpleado = eu.FKEmpleado
    WHERE u.empresa_id = :empresa_id");
    $stmt->execute([':empresa_id'=> $idEmpresa]);
    $row = $stmt->fetchAll();

    ///Deprecate, Solo un responsable
/*     $stmt1 = $conn->prepare("SELECT p.FKResponsable FROM proyectos as p WHERE PKProyecto= :id");
    $stmt1->execute(array(':id' => $id));
    $row1 = $stmt1->fetch();
    $idUsuario = $row1['FKResponsable']; */

    ///NEW n responsables.
    $stmt1 = $conn->prepare("SELECT Proyecto, rp.usuarios_id from proyectos as p 
        inner join responsables_proyecto as rp on rp.proyectos_PKProyecto = p.PKProyecto where rp.proyectos_PKProyecto = :id");
    $stmt1->execute(array(':id' => $id));
    $row1 = $stmt1->fetchAll();
    $idUsuario = [];
    foreach($row1 as $us){
        /* print_r($us["usuarios_id"]); */
        array_push($idUsuario, $us["usuarios_id"]);
    }
/*     echo($id." <- ");
    print_r($row1); */
    $UsSession = $_SESSION['PKUsuario'];
    
    if(in_array(strval($UsSession), $idUsuario)){
        $json->permiso = 1;
    }else{
        $json->permiso = 0;
    }
    /* if ($idUsuario == $_SESSION['PKUsuario']) {
        $json->permiso = 1;
    } else {
        $json->permiso = 0;
    } */



    $html = '';
    if (count($row) > 0) {
        foreach ($row as $r) {

            if (trim($r['nombre_empleado']) != "") {
                $nombre_empleado = $r['nombre_empleado'];
            } else {
                $nombre_empleado = $r['nombre_usuario'];
            }

            $html .= '<option value="' . $r['id'] . '"';

            if(in_array($r['id'], $idUsuario)){
                $html .= 'selected>' . $nombre_empleado . '</option>';
            }else{
                $html .= '>' . $nombre_empleado . '</option>';
            }

            /* if ($r['id'] == $idUsuario) {
                $html .= 'selected>' . $nombre_empleado . '</option>';
            } else {
                $html .= '>' . $nombre_empleado . '</option>';
            } */
        }
    } else {
        $html .= '<option value="" disabled>No hay usuarios para mostrar.</option>';
    }
    $json->html2 = $html;

    //obtener equipos del proyecto
    $html = "";
    $bandera = false;
    $stack = array();
    $stmt2 = $conn->prepare("SELECT FKEquipo FROM equipos_por_proyecto  WHERE FKProyecto = :id");
    $stmt2->execute(array(':id' => $id));

    while (($row = $stmt2->fetch()) !== false) {
        array_push($stack, $row['FKEquipo']);
    }

    $stmt = $conn->prepare("SELECT PKEquipo, Nombre_Equipo FROM equipos");
    $stmt->execute();

    while (($row2 = $stmt->fetch()) !== false) {
        $bandera  = false;
        foreach ($stack as $value) {
            if ($value == $row2['PKEquipo']) {
                $bandera = true;
                break;
            }
        }
        if ($bandera == true) {
            $html .= "<option value='" . $row2['PKEquipo'] . "' selected>" . $row2['Nombre_Equipo'] . "</option>";
        } else {
            $html .= "<option value='" . $row2['PKEquipo'] . "'>" . $row2['Nombre_Equipo'] . "</option>";
        }
    }
    $json->html3 = $html;

    /* Integrantes del proyecto */
    $integrantes = $conn->prepare("SELECT ip.FKUsuario FROM integrantes_proyecto AS ip WHERE ip.FKProyecto = :id");
    $integrantes->execute(array(':id' => $id));
    $integrantes = $integrantes->fetchAll(PDO::FETCH_ASSOC);

    //obtener los usuarios del proyecto
    $html1 = "";
    $usuarios = $conn->prepare("SELECT u.id, CONCAT(e.Nombres,' ',e.PrimerApellido) AS nombre_usuario 
    FROM usuarios AS u 
    INNER JOIN empleados AS e ON e.PKEmpleado = u.id 
    WHERE u.empresa_id = :idEmpresa AND u.estatus = 1");
    $usuarios->execute(array(':idEmpresa' => $idEmpresa));
    $usuarios = $usuarios->fetchAll(PDO::FETCH_ASSOC);
    $usuarioIsSelected;

    //Recorremos los usuarios de la empresa
        //Buscamos si ese usuario esta en el arreglo de integrantes
    foreach ($usuarios as $usuario) {
        $seleccionado = 0;
        foreach ($integrantes as $integrante) {
            // Si esta en integrantes lo agregamos al html, si no está no hacemos nada.
            if ($usuario['id'] == $integrante['FKUsuario']) {
                $seleccionado = 1;
                /* echo($integrante['FKUsuario']." - "); */
                $html1 .= '<option value="' . $usuario['id'] . '" selected>' . $usuario['nombre_usuario'] . '</option>';
                break;
            } 
        }
        //Si termino de recorrerse y nunca estuvo el usuario en seleccionados lo pintamos deseleccionado.
        if($seleccionado!=1){
            $html1 .= '<option value="' . $usuario['id'] . '">' . $usuario['nombre_usuario'] . '</option>'; 
        }
    }
    $json->html4 = $html1;

    //obtener los empleados del proyecto
    $html2 = "";
    $empleados = $conn->prepare("SELECT e.PKEmpleado, CONCAT(e.Nombres,' ',e.PrimerApellido) as nombre_empleado FROM empleados AS e 
    WHERE e.empresa_id = :idEmpresa AND e.estatus = 1 AND NOT EXISTS (SELECT 1 FROM usuarios AS u WHERE u.id = e.PKEmpleado)");
    $empleados->execute(array(':idEmpresa' => $idEmpresa));
    $empleados = $empleados->fetchAll(PDO::FETCH_ASSOC);

    foreach ($empleados as $empleado) {
        $seleccionado = 0;
        foreach ($integrantes as $integrante) {
            if ($empleado['PKEmpleado'] == $integrante['FKUsuario']) {
                $seleccionado = 1;
                $html2 .= '<option value="' . $empleado['PKEmpleado'] . '" selected>' . $empleado['nombre_empleado'] . '</option>';

            }
        }
        if($seleccionado!=1){
            $html2 .= '<option value="' . $empleado['PKEmpleado'] . '">' . $empleado['nombre_empleado'] . '</option>';
        }
    }
    $json->html5 = $html2;


    //fin de obtener integrantes
    $json = json_encode($json);
    echo $json;
}
