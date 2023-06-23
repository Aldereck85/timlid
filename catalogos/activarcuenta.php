<?php
session_start();
require_once('../include/db-conn.php');

$token = $_POST["csr_token_78L4"];

if (!empty($_SESSION['token_ld10d'])) {
    if (!hash_equals($_SESSION['token_ld10d'], $token)) {
        echo "error-general";
    } else {
        try {
            $statement = $conn->prepare("SELECT id, nombre, codigo, estatus, ifnull(email, usuario) as email FROM usuarios WHERE usuario = :Usuario");
            $statement->bindValue(':Usuario', $_POST["Email"]);
            $statement->execute();
            $row = $statement->fetch();
            $existeemail = $statement->rowCount();


            if ($existeemail > 0) {

                if ($row['estatus'] == 0) {

                    $idUsuario = $row['id'];
                    $nombreUsuario = $row['nombre'];
                    $codigoBD = $row['codigo'];

                    include_once("../functions/functions.php");

                    $idDes = encryptor("encrypt", $idUsuario);
                    $codigoDes = encryptor("encrypt", $codigoBD);

                    //enviar email
                    require_once('../lib/phpmailer_configuration.php');

                    $statement = $conn->prepare("SELECT valor FROM parametros_servidor WHERE parametro = 'url' OR parametro = 'email_contacto' ");
                    $statement->execute();
                    $url = $statement->fetchAll();
                    $appUrl = $_ENV['APP_URL'] ?? 'https://app.timlid.com/';

                    $origen = $_ENV['ORIGEN_MAIL'] ?? "no-reply@timlid.com";
                    $usuario_envia = "Timlid";
                    $mail->Sender = $origen;
                    $mail->setFrom($origen, $usuario_envia);
                    $mail->addReplyTo($origen, $usuario_envia);
                    $mail->addAddress($_POST["Email"]);     //Add a recipient  $user

                    $message = $nombreUsuario . "<br><br>" . "Se te ha generado un usuario en el sistema Timlid, para poder activarlo ingresa en el siguiente link:<br><br><a href='" . $appUrl . "index.php?id=" . $idDes . "&codigo=" . $codigoDes . "' target='_blank' title='TimLid - Activación de cuenta'>Timlid - Activar cuenta</a>";
                    $message .= "<br><br>" . "<b>Correo:</b> " . $row['email'] . "<br>";

                    /* $message = "
                <h2 align='center'>Activar cuenta</h2>
                <hr>
                <p align='left'>Saludos, " . $nombreUsuario . "</p>
                <p align='justify'>Bienvenido al sistema ERP de Timlid, para completar tu registro solo necesitas acceder al siguiente enlace:</p>
                <p align='center'><a href='" . $appUrl . "index.php?id=" . $idDes . "&codigo=" . $codigoDes . "' >Timlid - Activar cuenta</a></p>
                <hr>
                <center><img src='" . $appUrl . "img/Logo-transparente.png' width='15%' /></center>
                "; */

                    //Content
                    $mail->isHTML(true);                                  //Set email format to HTML
                    $mail->Subject = utf8_decode("Timlid - Activar cuenta");
                    $mail->Body    = utf8_decode($message);

                    $emailenviado = false;
                    $email_cuenta = 0;
                    while ($emailenviado == false && $email_cuenta < 3) {
                        $emailenviado = $mail->send();
                        $email_cuenta++;
                        if ($email_cuenta > 2) {
                            $emailenviado = true;
                        }
                    }

                    if ($emailenviado || $email_cuenta > 0) {
                        echo "exito";
                    } else {
                        echo "fallo";
                    }
                } else {
                    echo "activo";
                }
            } else {
                echo "fallo";
            }
        } catch (PDOException $error) {
            $message = $error->getMessage();
        }
    }
} else {
    echo "error-general";
}
