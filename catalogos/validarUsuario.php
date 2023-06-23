<?php
session_start();
require_once '../include/db-conn.php';
//require_once '../include/db-conn-2.php';
require_once '../lib/json_web_token.php';
$json = new \stdClass();

if (!isset($_SESSION['login_attempt'])) {
    $_SESSION['login_attempt'] = 1;
}
$user = $_POST["Usuario"];
$usuario = $_POST["Usuario"];
$contrasena = $_POST["Contrasena"];
$token = $_POST["csr_token_78L4"];


if ($_SESSION['login_attempt'] <= 3) {

    if (!empty($_SESSION['token_ld10d'])) {
        if (!hash_equals($_SESSION['token_ld10d'], $token)) {
            $json->estatus = "fail";
            $json->message = "error-general";
            $json = json_encode($json);
            echo $json;
        } else {
            try {
                /* VERIFICA QUE TENGA UNA CUENTA ACTIVA */
                $activeAcount = false;
                $trialAccount = false;
                $queryEmp = "SELECT empresa_id FROM usuarios WHERE usuario = :usuario";
                $stmEmp = $conn->prepare($queryEmp);
                $stmEmp->execute(array(':usuario' => $usuario));
                //echo $stmEmp->rowCount();
                if ($stmEmp->rowCount() > 0) {
                    $idEmp = $stmEmp->fetch(PDO::FETCH_ASSOC);
                    $idEmp = $idEmp['empresa_id'];
                    if ($idEmp !== "") {
                        $trialAccount = true;
                        // $queryPlan = "SELECT sub.stripe_status, sub.stripe_plan, usr.stripe_id, usr.trial_ends_at
                        // FROM subscriptions AS sub
                        // RIGHT JOIN users AS usr ON usr.id = sub.user_id
                        // INNER JOIN companies AS cmp ON cmp.user_id = usr.id
                        // WHERE cmp.id = :company;";
                        // $stmPlan = $conn2->prepare($queryPlan);
                        // $stmPlan->execute(array(':company' => $idEmp));
                        // $infoPlan = $stmPlan->fetch(PDO::FETCH_ASSOC);
                        // $countInfoPlan = $stmPlan->rowCount();
                        // //var_dump($infoPlan);
                        // if ($countInfoPlan > 0) {
                        //     //echo "plan 1";
                        //     $date = new Datetime("now");
                        //     $now = $date->format('Y-m-d H:i:s');
                        //     if($now <= $infoPlan['trial_ends_at']){
                        //         $trialAccount = true;
                        //     }
                        //     if ($infoPlan['stripe_status'] === 'active' || $infoPlan['stripe_status'] === 'trialing') {
                        //         $activeAcount = true;
                        //         //echo "plan 2";
                        //     }
                        // }

                    }
                    if($trialAccount === true){
                        $queryEmp = "SELECT active FROM empresas WHERE PKEmpresa = :IDEmpresa";
                        $stmtEmp = $conn->prepare($queryEmp);
                        $stmtEmp->execute(array(':IDEmpresa' => $idEmp));
                        $activeEmpresa = $stmtEmp->fetch(PDO::FETCH_ASSOC);
                        if ($activeEmpresa['active'] !== 1) {
                            //require('crearDatosDefault.php');
                            $rutaEmpresa = $_ENV['RUTA_ARCHIVOS_WRITE'] . "$idEmp";
                            $rutaEmpresaArch = $_ENV['RUTA_ARCHIVOS_WRITE'] . "$idEmp/archivos";
                            $rutaEmpresaImg = $_ENV['RUTA_ARCHIVOS_WRITE'] . "$idEmp/img";
                            $rutaEmpresaTemp = $_ENV['RUTA_ARCHIVOS_WRITE'] . "$idEmp/temp";
                            $rutaEmpresaFisc = $_ENV['RUTA_ARCHIVOS_WRITE'] . "$idEmp/fiscales";
                            mkdir($rutaEmpresa, 0777, true);
                            chmod($rutaEmpresa, 0777);
                            mkdir($rutaEmpresaArch, 0777, true);
                            chmod($rutaEmpresaArch, 0777);
                            mkdir($rutaEmpresaImg, 0777, true);
                            chmod($rutaEmpresaImg, 0777);
                            mkdir($rutaEmpresaTemp, 0777, true);
                            chmod($rutaEmpresaTemp, 0777);
                            mkdir($rutaEmpresaFisc, 0777, true);
                            chmod($rutaEmpresaFisc, 0777);
                            $queryEmpU = "UPDATE empresas SET active = 1 WHERE PKEmpresa = :IDEmpresa";
                            $statement = $conn->prepare($queryEmpU);
                            $statement->execute(array(':IDEmpresa' => $idEmp));
                        }
                        include_once("../functions/functions.php");
                        $query = "SELECT estatus FROM usuarios WHERE usuario = :Usuario";
                        $statement = $conn->prepare($query);
                        $statement->execute(array($usuario));
                        $count = $statement->rowCount();
                        $row = $statement->fetch();

                        if ($count > 0) {
                            if ($row["estatus"] == 0) {
                                $json->estatus = "no-activado";
                                $json = json_encode($json);
                                echo $json;
                            } else if ($row["estatus"] == 1) {
                                $query = "SELECT u.id, u.usuario, u.password, u.empresa_id, u.nuevo_password, u.imagen, e.nombre as nombreEmpresa, empl.Nombres, empl.PrimerApellido, empl.SegundoApellido,u.tim_impulsa FROM usuarios as u LEFT JOIN empresas as e ON e.PKEmpresa = u.empresa_id LEFT JOIN empleados as empl ON empl.PKEmpleado = u.id WHERE u.usuario = :Usuario";
                                $statement = $conn->prepare($query);
                                $statement->execute(array($usuario));
                                $rowUsu = $statement->fetch();

                                if (password_verify($contrasena, $rowUsu['password'])) {

                                    if ($rowUsu['nuevo_password'] == 0) {
                                        $json->estatus = "exito-nuevo";
                                        $json->usuario_id = $rowUsu['id'];
                                        $json = json_encode($json);
                                        echo $json;
                                    } else {

                                        $segundoApellido = "";
                                        if (trim($rowUsu["SegundoApellido"]) != "" || trim($rowUsu["SegundoApellido"]) != NULL) {
                                            $segundoApellido = " " . trim($rowUsu["SegundoApellido"]);
                                        }
                                        $nombreCompleto = trim($rowUsu["Nombres"]) . " " . trim($rowUsu["PrimerApellido"]) . $segundoApellido;

                                        if (trim($nombreCompleto) == "") {
                                            $nombreCompleto = "Sin nombre";
                                        }

                                        $_SESSION["UsuarioNombre"] = $nombreCompleto;
                                        $_SESSION["Usuario"] = $rowUsu["usuario"];
                                        $_SESSION["PKUsuario"] = $rowUsu['id'];
                                        $_SESSION["avatar"] = $rowUsu['imagen'];
                                        $_SESSION["IDEmpresa"] = $rowUsu['empresa_id'];
                                        $_SESSION["NombreEmpresa"] = $rowUsu['nombreEmpresa'];
                                        $_SESSION["tim_impulsa"] = $rowUsu['tim_impulsa'];
                                        $_SESSION["token"] = Auth::SignIn([
                                            'id' => $rowUsu["id"],
                                            'name' => $nombreCompleto
                                        ]);
                                        $_SESSION['token_ld10d'] = bin2hex(random_bytes(32));
                                        //$_SESSION['stripe_id'] = $infoPlan['stripe_id'] : 0;
                                        $_SESSION['stripe_id'] = null;

                                        //guardar datos de sesion
                                        guardarSession($conn, $_SESSION["PKUsuario"]);

                                        //Estatus activo al usuario
                                        $query = "UPDATE usuarios SET estado_web = 1 WHERE id = :idusuario";
                                        $statement = $conn->prepare($query);
                                        $statement->execute(array('idusuario' => $rowUsu['id']));

                                        $_SESSION['login_attempt'] = 1;
                                        $json->estatus = "exito";
                                        $json = json_encode($json);
                                        echo $json;
                                    }
                                } else {
                                    $_SESSION['login_attempt'] = $_SESSION['login_attempt'] + 1;
                                    $json->estatus = "fallo";
                                    $json->login_attempt = $_SESSION['login_attempt'];
                                    $json = json_encode($json);
                                    echo $json;
                                }
                            } else if ($row["estatus"] == 2) {
                                $json->estatus = "inactivo";
                                $json = json_encode($json);
                                echo $json;
                            }
                        } else {
                            $json->estatus = "fallonoexisteusuario";
                            $json = json_encode($json);
                            echo $json;
                        }
                    }else{
                        if ($activeAcount === true) {
                            $queryEmp = "SELECT active FROM empresas WHERE PKEmpresa = :IDEmpresa";
                            $stmtEmp = $conn->prepare($queryEmp);
                            $stmtEmp->execute(array(':IDEmpresa' => $idEmp));
                            $activeEmpresa = $stmtEmp->fetch(PDO::FETCH_ASSOC);
                            if ($activeEmpresa['active'] !== 1) {
                                require('crearDatosDefault.php');
                                $rutaEmpresa = $_ENV['RUTA_ARCHIVOS_WRITE'] . "$idEmp";
                                $rutaEmpresaArch = $_ENV['RUTA_ARCHIVOS_WRITE'] . "$idEmp/archivos";
                                $rutaEmpresaImg = $_ENV['RUTA_ARCHIVOS_WRITE'] . "$idEmp/img";
                                $rutaEmpresaTemp = $_ENV['RUTA_ARCHIVOS_WRITE'] . "$idEmp/temp";
                                $rutaEmpresaFisc = $_ENV['RUTA_ARCHIVOS_WRITE'] . "$idEmp/fiscales";
                                mkdir($rutaEmpresa, 0777, true);
                                chmod($rutaEmpresa, 0777);
                                mkdir($rutaEmpresaArch, 0777, true);
                                chmod($rutaEmpresaArch, 0777);
                                mkdir($rutaEmpresaImg, 0777, true);
                                chmod($rutaEmpresaImg, 0777);
                                mkdir($rutaEmpresaTemp, 0777, true);
                                chmod($rutaEmpresaTemp, 0777);
                                mkdir($rutaEmpresaFisc, 0777, true);
                                chmod($rutaEmpresaFisc, 0777);
                                $queryEmpU = "UPDATE empresas SET active = 1 WHERE PKEmpresa = :IDEmpresa";
                                $statement = $conn->prepare($queryEmpU);
                                $statement->execute(array(':IDEmpresa' => $idEmp));
                            }
                            include_once("../functions/functions.php");
                            $query = "SELECT estatus FROM usuarios WHERE usuario = :Usuario";
                            $statement = $conn->prepare($query);
                            $statement->execute(array($usuario));
                            $count = $statement->rowCount();
                            $row = $statement->fetch();

                            if ($count > 0) {
                                if ($row["estatus"] == 0) {
                                    $json->estatus = "no-activado";
                                    $json = json_encode($json);
                                    echo $json;
                                } else if ($row["estatus"] == 1) {
                                    $query = "SELECT u.id, u.usuario, u.password, u.empresa_id, u.nuevo_password, u.imagen, e.nombre as nombreEmpresa, empl.Nombres, empl.PrimerApellido, empl.SegundoApellido,u.tim_impulsa FROM usuarios as u LEFT JOIN empresas as e ON e.PKEmpresa = u.empresa_id LEFT JOIN empleados as empl ON empl.PKEmpleado = u.id WHERE u.usuario = :Usuario";
                                    $statement = $conn->prepare($query);
                                    $statement->execute(array($usuario));
                                    $rowUsu = $statement->fetch();

                                    if (password_verify($contrasena, $rowUsu['password'])) {

                                        if ($rowUsu['nuevo_password'] == 0) {
                                            $json->estatus = "exito-nuevo";
                                            $json->usuario_id = $rowUsu['id'];
                                            $json = json_encode($json);
                                            echo $json;
                                        } else {

                                            $segundoApellido = "";
                                            if (trim($rowUsu["SegundoApellido"]) != "" || trim($rowUsu["SegundoApellido"]) != NULL) {
                                                $segundoApellido = " " . trim($rowUsu["SegundoApellido"]);
                                            }
                                            $nombreCompleto = trim($rowUsu["Nombres"]) . " " . trim($rowUsu["PrimerApellido"]) . $segundoApellido;

                                            if (trim($nombreCompleto) == "") {
                                                $nombreCompleto = "Sin nombre";
                                            }

                                            $_SESSION["UsuarioNombre"] = $nombreCompleto;
                                            $_SESSION["Usuario"] = $rowUsu["usuario"];
                                            $_SESSION["PKUsuario"] = $rowUsu['id'];
                                            $_SESSION["avatar"] = $rowUsu['imagen'];
                                            $_SESSION["IDEmpresa"] = $rowUsu['empresa_id'];
                                            $_SESSION["NombreEmpresa"] = $rowUsu['nombreEmpresa'];
                                            $_SESSION["tim_impulsa"] = $rowUsu['tim_impulsa'];
                                            $_SESSION["token"] = Auth::SignIn([
                                                'id' => $rowUsu["id"],
                                                'name' => $nombreCompleto
                                            ]);
                                            $_SESSION['token_ld10d'] = bin2hex(random_bytes(32));
                                            $_SESSION['stripe_id'] = $infoPlan['stripe_id'];

                                            //guardar datos de sesion
                                            guardarSession($conn, $_SESSION["PKUsuario"]);

                                            //Estatus activo al usuario
                                            $query = "UPDATE usuarios SET estado_web = 1 WHERE id = :idusuario";
                                            $statement = $conn->prepare($query);
                                            $statement->execute(array('idusuario' => $rowUsu['id']));

                                            $_SESSION['login_attempt'] = 1;
                                            $json->estatus = "exito";
                                            $json = json_encode($json);
                                            echo $json;
                                        }
                                    } else {
                                        $_SESSION['login_attempt'] = $_SESSION['login_attempt'] + 1;
                                        $json->estatus = "fallo";
                                        $json->login_attempt = $_SESSION['login_attempt'];
                                        $json = json_encode($json);
                                        echo $json;
                                    }
                                } else if ($row["estatus"] == 2) {
                                    $json->estatus = "inactivo";
                                    $json = json_encode($json);
                                    echo $json;
                                }
                            } else {
                                $json->estatus = "fallonoexisteusuario";
                                $json = json_encode($json);
                                echo $json;
                            }
                        } else {
                            $json->estatus = "badPlan";
                            $json->message = "Verifica tengas un plan activo.";
                            $json = json_encode($json);
                            echo $json;
                        }                      
                    }
                } else {
                    $json->estatus = "badPlan";
                    $json->message = "Aun no cuentas con nuestro servicio.";
                    $json = json_encode($json);
                    echo $json;
                }
            } catch (PDOException $error) {
                $message = $error->getMessage();
                echo $message;
            }
        }
    } else {
        $json->estatus = "fail";
        $json->message = "error-general2";
        $json = json_encode($json);
        echo $json;
    }
} else {
    $json->estatus = "fail";
    $json->message = "error-general3";
    $json = json_encode($json);
    echo $json;
}
