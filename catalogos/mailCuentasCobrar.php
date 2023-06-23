<?php
ini_set("max_execution_time",1000);
include "../include/db-conn.php";
require_once('../lib/phpmailer_configuration.php');
$orign = $_ENV['ORIGEN_MAIL'] ?? "no-reply@timlid.com";
$usuario_envia = "Timlid";


    //consulta todas las facturas/ventas vencidas de todas las empresas y envia correos de notificación
    $stmt = $conn->prepare('SELECT concat(f.serie, " - ", f.folio) as folio, 
                                c.razon_social,
                                c.PKCliente,
                                c.Email,
                                f.saldo_insoluto, 
                                date_format(f.fecha_vencimiento, "%d-%m-%Y") as fecha_vencimiento
                            from facturacion f inner join
                            clientes c on c.PKCliente = f.cliente_id  
                            where (f.estatus = 1 or f.estatus = 2) and f.fecha_vencimiento < date_format(now(), "%Y-%m-%d") and f.flag_sendMail_vencida = 0;');
    $stmt->execute();
    if($stmt->rowCount()>0){
        $array = $stmt->fetchAll();

        $clienteAnterior=0;
        $arrayAnterior;
        foreach ($array as $r) {
            $arrEmails = array($r['Email']);

            if($clienteAnterior != $r['PKCliente']){
                $stmt = $conn->prepare('SELECT Email from dato_contacto_cliente where (EmailFacturacion = 1 or EmailPagos = 1) and FKCliente = :fkc');
                $stmt->execute(array($r['PKCliente']));
                if($stmt->rowCount()>0){
                    $array2 = $stmt->fetchAll();
                    foreach ($array2 as $r2) {
                        array_push($arrEmails, $r2['Email']);
                    }
                }
                $arrayAnterior = $arrEmails;
                $clienteAnterior = $r['PKCliente'];

            }else{
                $arrEmails = $arrayAnterior;
            }

            $mensaje = '<p>Aviso de Vencimiento cuenta por pagar:</p>'. 
                    '<p>Serie y folio: '. $r['folio'].'<br>'.
                    'A nombre de: '. $r['razon_social'].'<br>'.
                    'Saldo Vencido: $'. $r['saldo_insoluto'].'<br>'.
                    'Fecha de vencimiento: '. $r['fecha_vencimiento'].'</p>'.
                    '<p>Este correo se genera de manera automática, no responder.</p>'.
                    '<p><a href="https://timlid.com/">Timlid.com</a></p>';

            $mail->Sender = $orign;
            $mail->setFrom($orign, $usuario_envia);
            $mail->addReplyTo($orign, $usuario_envia);
            $mail->isHTML(true);
            $mail->CharSet='UTF-8';
            $mail->Subject = sprintf("Timlid - Aviso de Vencimiento");
            $mail->Body = sprintf($mensaje);
            foreach ($arrEmails as $email) {
                try {
                    $mail->AddBCC($email);
                } catch (Exception $e) {
                    echo "Skipping invalid address: {$email}\n";
                    continue;
                }
            }

            try {
                $mail->Send();
            } catch (Exception $e) {
                echo $mail->ErrorInfo . '<br>';
                //Reset the connection to abort sending this message
                //The loop will continue trying to send to the rest of the list
                $mail->getSMTPInstance()->reset();
            }
            $mail->ClearAllRecipients();            
        }

        $stmt = $conn->prepare('UPDATE facturacion SET flag_sendMail_vencida = 1 where (estatus = 1 or estatus = 2) and fecha_vencimiento < date_format(now(), "%Y-%m-%d") and flag_sendMail_vencida = 0;');
        $stmt->execute();

        $stmt = null;
        
    }

?>
