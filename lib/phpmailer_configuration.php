<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

//Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);

// $mail->From = "contacto@ghmedic.com.mx"; //remitente
//$mail->FromName = "GH Medic";

//Server settings
$mail->SMTPDebug = false;//SMTP::DEBUG_SERVER;                      //Enable verbose debug output
$mail->isSMTP();                                            //Send using SMTP
$mail->Host       = 'mail.timlid.com';                     //Set the SMTP server to send through
$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
$mail->Username   = 'no-reply@timlid.com';                     //SMTP username
$mail->Password   = 'R3&2w89t';                               //SMTP password
$mail->SMTPSecure = 'ssl';//PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
$mail->Port       = 465;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
/*
// Check for empty fields
if(empty($_POST['name'])      ||
   empty($_POST['email'])     ||
   empty($_POST['phone'])     ||
   empty($_POST['message'])   ||
   !filter_var($_POST['email'],FILTER_VALIDATE_EMAIL))
   {
   echo "¡Argumentos no provistos!";
   return false;
   }

require_once('PHPMailer/PHPMailerAutoload.php');
   
$name = strip_tags(htmlspecialchars($_POST['name']));
$email_address = strip_tags(htmlspecialchars($_POST['email']));
$phone = strip_tags(htmlspecialchars($_POST['phone']));
$message = strip_tags(htmlspecialchars($_POST['message']));


$asunto = "Formulario de contacto de ghmedic.com.mx:  $name";
$mensaje = "Haz recibido un mensaje de tu formulario de contacto.\n\n"."Aqui estan los detalles:\n\nNombre: $name\n\nEmail: $email_address\n\nTelefono: $phone\n\nMensaje:\n$message";
//Agregar destinatario
$mail->IsHTML(true);
$mail->Subject = $asunto;
$mail->Body = $mensaje;
$mail->AddAddress('jcdg10@gmail.com');
//$mail->AddAddress('contacto@ghasistencia.com');

$exito = 0;
$cont = 0;

do{
   if ($mail->Send()) {
      $exito = 1;
   }
   $cont++;
}while ($cont < 3 && $exito == 0);

return true;
*/

?>