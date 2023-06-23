<?php
session_start();

/* Validamos la sesión */
if (isset($_SESSION["Usuario"])) {
    /* Validamos los datos enviados */
    if ((isset($_POST['idUsuario']) && !empty($_POST['idUsuario']))
        && (isset($_POST['currentPassInput']) && !empty($_POST['currentPassInput']))
        && (isset($_POST['newPassInput']) && !empty($_POST['newPassInput']))
    ) {

        require_once '../../../include/db-conn.php';
        $id = $_SESSION["PKUsuario"];
        $currentPass = htmlspecialchars($_POST['currentPassInput'], ENT_QUOTES);
        $newPass = htmlspecialchars($_POST['newPassInput'], ENT_QUOTES);

        try {
            /* Validamos que exista el usuario */
            $getUsuario = $conn->prepare("SELECT password FROM usuarios WHERE id = :id");
            $getUsuario->execute(array(':id' => $id));
            $usuario = $getUsuario->fetch(PDO::FETCH_ASSOC);
            if (!$usuario) {
                echo json_encode(array('status' => 'fail', 'message' => 'No hay un registro con los datos enviados'));
            } else {
                /* VALIDAMOS LA CONTRASENIA */
                if (password_verify($currentPass, $usuario['password'])) {
                    /* Actualizamos la contraseña */
                    $newPass = password_hash($newPass, PASSWORD_DEFAULT);
                    $updateUsuario = $conn->prepare('UPDATE usuarios SET password = :newPas WHERE id = :id');
                    $updateUsuario->execute(array(':newPas' => $newPass, ':id' => $id));
                    if ($updateUsuario->rowCount() === 0) {
                        echo json_encode(array('status' => 'fail', 'message' => 'No hay un registro con los datos enviados'));
                    } else {
                        echo json_encode(array('status' => 'success', 'message' => 'Contraseña actualizada correctamente'));
                    }
                } else {
                    echo json_encode(array('status' => 'fail', 'message' => 'No es la contraseña correcta'));
                }
            }
        } catch (PDOException $ex) {
            echo json_encode(array('status' => 'fail', 'message' => $ex->getMessage()));
        }
    } else {
        echo json_encode(array('status' => 'fail', 'message' => 'No se enviaron los datos correctamente'));
    }
} else {
    session_destroy();
    header("location:../../../index.php");
}