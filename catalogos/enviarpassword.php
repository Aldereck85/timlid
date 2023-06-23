<?php
session_start();

require('../lib/phpmailer_configuration.php');
require_once('../include/db-conn.php');

function generateRandomString($length = 12) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

$token = $_POST["csr_token_78L4"];

if($_SESSION['pass_attempt'] < 6){
    if(!empty($_SESSION['token_ld10d'])) {
        if (!hash_equals($_SESSION['token_ld10d'], $token)) {
            echo "error-general";
        }
        else{

            include_once("../functions/functions.php");

            $statement = $conn->prepare("SELECT valor FROM parametros_servidor WHERE parametro = 'url' OR parametro = 'email_contacto' ");
            $statement->execute();
            $url = $statement->fetchAll();
            $appUrl = $_ENV['APP_URL'] ?? 'https://app.timlid.com/';
            try{

              $codigo = generateRandomString();
              $statement = $conn->prepare("SELECT estatus FROM usuarios WHERE usuario = :Usuario");
              $statement->bindValue(':Usuario',trim($_POST["Email"]));
              $statement->execute();
              $existe = $statement->rowCount();
              
              if($existe > 0){

                $statement = $conn->prepare("UPDATE usuarios SET codigo = :Codigo WHERE usuario = :Usuario");
                $statement->bindValue(':Codigo',$codigo);
                $statement->bindValue(':Usuario',$_POST["Email"]);
                
                if($statement->execute()){

                    $statement = $conn->prepare("SELECT id, nombre, codigo FROM usuarios WHERE usuario = :Usuario");
                    $statement->bindValue(':Usuario',$_POST["Email"]);
                    $statement->execute();
                    $row = $statement->fetch();

                    $idUsuario = $row['id'];
                    $nombreUsuario = $row['nombre'];
                    $codigoBD = $row['codigo'];

                    $idUsuarioEnc = encryptor("encrypt", $idUsuario);
                    $codigoBDEnc = encryptor("encrypt", $codigoBD);
                    //enviar email
                    try {
                        $origen = $_ENV['ORIGEN_MAIL'] ?? "no-reply@timlid.com";
                        $usuario_envia = "Timlid";
                        $mail->Sender = $origen;
                        $mail->setFrom($origen, $usuario_envia);
                        $mail->addReplyTo($origen, $usuario_envia);
                        $mail->addAddress($_POST["Email"]);     //Add a recipient  $user

                        $mensaje = "
                        <h2 align='center'>Generar nueva contrase&ntilde;a</h2>
                        <hr>
                        <p align='left'>Saludos, ".$nombreUsuario."</p>
                        <p align='justify'>Hemos recibido la solicitud de tu parte para restablecer tu constraseña, para hacerlo haz click en el 
                        siguiente vinculo:</p>
                        <p align='center'><a href='".$appUrl."recover-password.php?id=".$idUsuarioEnc."&codigo=".$codigoBDEnc."' >Timlid - Restablecer contraseña</a></p>
                        <hr>
                        <center><img src='".$appUrl."img/tim.png' width='15%'' /></center>
                        ";
                        //Content
                        $mail->isHTML(true);                                  //Set email format to HTML
                        $mail->Subject = utf8_decode("Restablecer Password | Timlid");
                        $mail->Body    = utf8_decode($mensaje);

                        $emailenviado = false; $email_cuenta = 0;
                          while($emailenviado == false && $email_cuenta < 3 ){
                              $emailenviado = $mail->send();
                              $email_cuenta++;
                              if($email_cuenta > 2){
                                  $emailenviado = true;
                              }
                          }

                          if($emailenviado || $email_cuenta > 0)
                          {
                              echo "exito";
                          }
                          else{
                              echo "fallo-email";
                          }

                    } catch (Exception $e) {
                        echo "fallo-email";
                    }
                }else{
                  echo "fallo-email";
                }
                

              }
              else{
                $_SESSION['pass_attempt']++; 

                if($_SESSION['pass_attempt'] < 6){
                  echo "fallo";
                }
                else{
                  date_default_timezone_set('America/Mexico_City');
                  $currentDate = strtotime(date("Y-m-d H:i:s"));
                  $futureDate = $currentDate+(60*5);
                  $formatDate = date("Y-m-d H:i:s", $futureDate);
                  $_SESSION['pass_attempt_time'] = $formatDate;
                  echo "tiempo-out";
                }
                

              }

              
            }
            catch(PDOException $error)
            {
                  $message = $error->getMessage();
            }
        }
    }
    else{
        echo "error-general2";
    }
}
else{
  echo "tiempo-out";
}

?>