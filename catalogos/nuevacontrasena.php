<?php
session_start();
require_once '../include/db-conn.php';
require_once '../lib/json_web_token.php';

$valido    = preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{10,}$/', $_POST["contrasena"]);
if(!$valido){
  echo "error-general";
  return;
}

$token = $_POST["csr_token_78L4"];
$contrasena_sp = htmlspecialchars($_POST["contrasena"], ENT_QUOTES);

if(!empty($_SESSION['token_ld10d'])) {
    if (!hash_equals($_SESSION['token_ld10d'], $token)) {
        echo "error-general";
    }
    else{
        try {
                    include_once("../functions/functions.php");
                    
                    $query = "SELECT u.id, u.usuario, u.password, u.empresa_id, u.nuevo_password,u.imagen, e.RazonSocial, empl.Nombres, empl.PrimerApellido, empl.SegundoApellido,u.tim_impulsa FROM usuarios as u LEFT JOIN empresas as e ON e.PKEmpresa = u.empresa_id LEFT JOIN empleados as empl ON empl.PKEmpleado = u.id WHERE u.id = :idusuario AND u.usuario = :Usuario";
                    $statement = $conn->prepare($query);
                    $statement->execute(
                        array(
                            'idusuario' => $_POST["IDUsuario"],
                            'Usuario' => $_POST["Usuario"],
                        )
                    );
                    $rowUsu = $statement->fetch();
                    $cuenta = $statement->rowCount();

                    if($cuenta > 0){
                        $contrasena = password_hash($contrasena_sp, PASSWORD_DEFAULT);
                        $query = "UPDATE usuarios SET password = :contrasena, nuevo_password = 1 WHERE id = :idusuario AND usuario = :Usuario";
                        $statement = $conn->prepare($query);
                        if($statement->execute(
                            array(
                                'contrasena' => $contrasena,
                                'idusuario' => $_POST["IDUsuario"],
                                'Usuario' => $_POST["Usuario"],
                            )
                        )){
                            

                            $segundoApellido = "";
                            if(trim($rowUsu["SegundoApellido"]) != "" || trim($rowUsu["SegundoApellido"]) != NULL){
                                $segundoApellido = " ".trim($rowUsu["SegundoApellido"]);
                            }
                            $nombreCompleto = trim($rowUsu["Nombres"])." ".trim($rowUsu["PrimerApellido"]).$segundoApellido;

                            if(trim($nombreCompleto) == ""){
                                $nombreCompleto = "Sin nombre";
                            }

                            $_SESSION["UsuarioNombre"] = $nombreCompleto;
                            $_SESSION["Usuario"] = $rowUsu["usuario"];
                            $_SESSION["PKUsuario"] = $rowUsu['id'];
                            $_SESSION["IDEmpresa"] = $rowUsu['empresa_id'];
                            $_SESSION["NombreEmpresa"] = $rowUsu['RazonSocial'];
                            $_SESSION["avatar"] = $rowUsu['imagen'];
                            $_SESSION["tim_impulsa"] = $rowUsu['tim_impulsa'];
                            $_SESSION["token"] = Auth::SignIn([
                                'id' => $rowUsu["id"],
                                'name' => $nombreCompleto
                            ]);
                            $_SESSION['token_ld10d'] = bin2hex(random_bytes(32));

                            //guardar datos de sesion
                            guardarSession($conn, $_SESSION["PKUsuario"]);

                            //Estatus activo al usuario
                            $query = "UPDATE usuarios SET estado_web = 1 WHERE id = :idusuario";
                            $statement = $conn->prepare($query);
                            $statement->execute(array('idusuario' => $rowUsu['id']));

                            echo "exito";
                        }
                        else{
                            echo "erroragregar";
                        }

                        
                    }
                    else{
                        echo "noexisteusuario";
                    }

        } catch (PDOException $error) {
            $message = $error->getMessage();
        }
    }
}
else{
    echo "error-general2";
}