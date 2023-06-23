<?php
session_start();

if (isset($_POST['idEmpleado']) && isset($_POST['nodoPadre'])) {
    
    require_once '../../../include/db-conn.php';

    $idEmpleado = $_POST['idEmpleado'];
    $nodoPadre = $_POST['nodoPadre'];
    $idempresa = $_SESSION['IDEmpresa'];
    $imagen = "";

    if(trim($nodoPadre) == ""){
        $nodoPadre = NULL;
    }

    if (isset($_POST['imagenSubir'])) {
        $imagen = $_POST['imagenSubir'];
    }

    /* QUERY PARA EDITAR EL ORGANIGRAMA */
    $stmt = $conn->prepare('INSERT INTO organigrama (FKEmpleado , ParentNode , Imagen_Perfil, empresa_id) VALUES (:idempleado , :nodoPadre, :imagen, :idempresa)');
    $stmt->bindValue(':idempleado', $idEmpleado);
    $stmt->bindValue(':nodoPadre', $nodoPadre);
    $stmt->bindValue(':imagen', $imagen);
    $stmt->bindValue(':idempresa', $idempresa);
    if ($stmt->execute()){
        
        if (isset($_POST['imagenSubir'])) {
            
            $org_image =  $_ENV['RUTA_ARCHIVOS_WRITE'].$idempresa.'/img'.'/' . $imagen;
            $destination = "perfil";
            $img_name = basename($org_image);
        }

        echo json_encode(array('status' => 'success', 'message' => 'Empleado agregado correctamente', 'imagen' => $img_name));
    } else {
        echo json_encode(array('status' => 'fail', 'message' => 'No se encontro el registro requerido'));
    }
} else {
    echo json_encode(array('status' => 'fail', 'message' => 'No se enviaron los datos correctamente'));
}