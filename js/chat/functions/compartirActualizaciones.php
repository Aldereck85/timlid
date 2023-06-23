<?php
session_start();
//error_reporting(0);

if(isset($_SESSION["Usuario"])){
	require_once('../../../include/db-conn.php');
	require_once('../../../lib/phpmailer_configuration.php');
	include_once('lib/simple_html_dom.php');

	$idusuario = $_POST['IDUsuario'];
	$idchat = $_POST['ChatId'];
	$emails = json_decode($_POST['Emails']);
	$urlGlobal = "http://timdesk.erpghmedic.com.mx/catalogos/proyectos/";

	$stmt = $conn->prepare('SELECT Contenido FROM chat WHERE PKChat = :idchat');
	$stmt->bindValue(':idchat',$idchat);
	$stmt->execute();
	$row = $stmt->fetch();

	$nombreusuario = "Timlid";

	$stmt = $conn->prepare('SELECT IFNULL(CONCAT(e.Nombres," ", e.PrimerApellido), u.Nombre)  as nombreusuario FROM usuarios as u LEFT JOIN empleados_usuarios as eu ON eu.FKUsuario = u.id LEFT JOIN empleados as e ON e.PKEmpleado = eu.FKEmpleado WHERE u.id = :idusuario');
	$stmt->bindValue(':idusuario',$idusuario);
	$stmt->execute();
	$rown = $stmt->fetch();

	if(count($rown) > 0){
		$nombreusuario = $rown['nombreusuario'];
	}

	$stmt = $conn->prepare("SELECT valor FROM parametros_servidor WHERE parametro = 'email_contacto'");
	$stmt->execute();
	$url = $stmt->fetch();
	$email_origen = $_ENV['ORIGEN_MAIL'] ?? "no-reply@timlid.com";

	$mail->Sender = $email_origen;
    $mail->setFrom($email_origen, $nombreusuario);
    $mail->addReplyTo($email_origen, $nombreusuario);
	
	$asunto = utf8_decode("Timlid.com Actualización");
	$mensaje = utf8_decode($row['Contenido']);
	//Agregar destinatario
	$mail->IsHTML(true);
	$mail->Subject = $asunto;
	//$mail->Body = "como etsas";//$mensaje;

$str=<<<HTML
.$mensaje.
HTML;
	$html = str_get_html($str);

	//echo $html."<br><br><br>";
	// Find all images
	$dom = new DOMDocument();
	$dom->loadHTML($html);
	$iFrame = $dom->getElementsByTagName('iframe')->item(0);

	$anexos = "";
	foreach($dom->getElementsByTagName('iframe') as $element){
	  //print_r($element);
	  $src = $element->getAttribute('src');
	  if(strpos($src, ".docx") !== false || strpos($src, ".doc") !== false || strpos($src, ".xls") !== false || strpos($src, ".pptx") !== false  || strpos($src, ".ppt") !== false  || strpos($src, ".pdf") !== false ){
		    $adjunto = substr($src, 35, -14);
		    $info = new SplFileInfo($adjunto);
		    $adjunto = str_replace(" ", "%20", $adjunto);
		    
		    $mail->addStringAttachment(file_get_contents(utf8_decode($adjunto)), utf8_decode($info->getFilename()));
	  }

	  if(strpos($src, "www.youtube.com") !== false){
		    $adjunto = substr($src, 2);
		    $anexos = $anexos."<br>".$adjunto;
	  }
	}

	$mail->Body = $mensaje."<br>".$anexos;

	$numeroemails = count($emails);

	$cuentaemail = 0;
	foreach ($emails as $e) {
		$exito = 0;
		$cont = 0;
		$mail->ClearAllRecipients();

		$mail->AddAddress($e);
		do{
			if ($mail->Send()) {
				$exito = 1;
				$cuentaemail++;
			}
			$cont++;
		}while ($cont < 3 && $exito == 0);
	}

	if($cuentaemail == $numeroemails){
		echo "1";
	}
	else{
		echo "0";
	}


}

?>