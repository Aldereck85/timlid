<?php


class ConnDB
{

    public function getDb()
    {
        include "../../../../include/db-conn.php";
        return $conn;
    }
}

class EnviarCorreoCrm
{
    public static function empleadoEnvioCorreoNuevo($integrantes, $actividad_reunion, $event, $fecha_reunion, $username, $updatedActividad = 0)
    {
        $con = new ConnDB();
        $conn = $con->getDb();
        $res = array();
        if ($updatedActividad === 1) {
            $stmt = $conn->prepare("DELETE FROM envios_correos_temp WHERE actividad_id = :actividad_id");
            $stmt->bindValue(':actividad_id', $actividad_reunion);
            $stmt->execute();
        }
        $email_body = "<b>Tiene una invitación de reuni&oacute;n:</b> &nbsp;" . $fecha_reunion . "<br>
                    <b>Nombre de la invitaci&oacute;n:</b> &nbsp;" . $event->title . "<br>
                    <b>Fecha:</b> &nbsp;" . $fecha_reunion . "<br>
                    <b>Hora:</b> &nbsp;" . $event->hora_inicio . "<br>
                    <b>Lugar de la reuni&oacute;n:</b> &nbsp;" . $event->lugar . "<br>
                    <b>Asistentes:</b> &nbsp;" . $event->participantes_reunion . "<br>
                    <b>Convocante:</b> &nbsp;" . $username;

        foreach ($integrantes as $integrante) {
            $query = "SELECT u.id, u.usuario, e.Nombres, e.PrimerApellido, e.SegundoApellido
            FROM usuarios AS u
            LEFT JOIN empleados AS e ON  u.id = e.PKEmpleado
            WHERE u.id = :id";
            $stmt = $conn->prepare($query);
            $stmt->execute([':id' => $integrante]);
            $results = $stmt->fetch(PDO::FETCH_ASSOC);

            $sql = "INSERT INTO envios_correos_temp(empleado_id, actividad_id) 
                    VALUES (:empleado_id,:actividad_id)";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':empleado_id', $results['id']);
            $stmt->bindValue(':actividad_id', $actividad_reunion);
            $stmt->execute();
            if ($results["usuario"] === null) {
                $res = ['status' => 'fail', 'message' => 'El empleado: ' . $results["Nombres"] . ' no cuenta con correo'];
                break;
            }
            $email = self::enviarEmail($results, $email_body);
            if ($email['status'] !== 'success') {
                $res = ['status' => 'fail', 'message' => $email['message']];
                break;
            }
            $res = ['status' => 'success', 'message' => 'Email enviado'];
        }
        return $res;
    }

    public static function enviarEmail($resulted, $email_body)
    {
        require('../../../../lib/phpmailer_configuration.php');

        $res = [];
        $mail->IsHTML(true);
        $mail->AddAddress($resulted['usuario'], "recipient-name");
        $mail->SetFrom("no-reply@timlid.com", $resulted['usuario']);
        $mail->Subject = "Estimado:" . $resulted['Nombres'] . ' ' . $resulted['PrimerApellido'] . ' ' . $resulted['SegundoApellido'];
        $content = $email_body;
        $mail->MsgHTML($content);
        if (!$mail->Send()) {
            $mensaje = "No se pudo enviar el correo a:" . $resulted['usuario'];
            $res['status'] =  'fail';
            $res['message'] = $mensaje;
            return $res;
        }
        $mail->ClearAllRecipients();
        $res['status'] = 'success';
        return $res;
    }

    public static function reenviarCorreos($integrantes, $evento)
    {
        $con = new ConnDB();
        $conn = $con->getDb();
        $actividad_id = $evento->id;
        $stmt = $conn->prepare("DELETE FROM envios_correos_temp WHERE actividad_id = :actividad_id");
        $stmt->bindValue(':actividad_id', $actividad_id);
        $stmt->execute();
        foreach ($integrantes as $integrante) {
            $stmt = $conn->prepare("INSERT INTO envios_correos_temp(empleado_id, actividad_id) VALUES (:empleado_id, :actividad_id)");
            $stmt->bindValue(':empleado_id', $integrante);
            $stmt->bindValue(':actividad_id', $actividad_id);
            $stmt->execute();
        }

        self::updateEmailIntegrantes($integrantes, $evento);
    }

    public static function updateEmailIntegrantes($integrantes, $evento)
    {
        $con = new ConnDB();
        $conn = $con->getDb();
        $query = "SELECT title, participantes_reunion FROM actividades WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $actividad_id);
        $stmt->execute();
        $result = $stmt->fetch();
        foreach ($integrantes as $integrante) {

            $email_body = "<b>Tiene una invitación de reunión:</b>" . $evento->fecha . "<br>
                      <b>Nombre de la invitacion:</b>" . $integrante["title"] . "<br>
                      <b>Fecha:</b>" . $evento->fecha . "<br>
                      <b>Hora:</b>" . $evento->hora_inicio . "<br>
                      <b>Lugar de la reunión:</b>" . $evento->lugar . "<br>
                      <b>Asistentes:</b>" . $integrante["participantes_reunion"] . "<br>
                      <b>Convocante:</b>" . $evento->username;


            $stmt = $conn->prepare("SELECT e.PKEmpleado, e.Nombres, e.PrimerApellido, e.SegundoApellido, u.usuario
            FROM empleados AS e
            INNER JOIN usuarios AS u ON e.PKEmpleado = u.id
            WHERE PKEmpleado = :id");
            $stmt->bindParam(':id', $integrante);
            $stmt->execute();
            $results = $stmt->fetch();

            /* if ($results["email"] === null) {
                $data[] = 'El empleado: ' . $results["Nombres"] . ' no cuenta con correo';
            } */

            self::enviarCorreo1($results, $email_body);
        }
    }

    public static function buscarEmpleados($actividad_id)
    {
        $con = new ConnDB();
        $conn = $con->getDb();

        $data = [];
        $stmt = $conn->prepare("SELECT id, empleado_id FROM envios_correos_temp WHERE actividad_id = :actividad_id");
        $stmt->bindParam(':actividad_id', $actividad_id);
        $stmt->execute();
        $resultado = $stmt->fetchAll();
        foreach ($resultado as $row) {
            $data[] = array(
                "id" => $row['id'],
                "empleado_id" => $row['empleado_id'],
            );
        }
        return $data;
    }

    public static function verActividadEnvioCorreoEditar($data)
    {


        $con = new ConnDB();
        $conn = $con->getDb();

        $result = array();

        $event1 = 0;
        $event2 = 0;
        $event3 = 0;

        $stmt = $conn->prepare("SELECT empleado_id
                                    FROM envios_correos_temp 
                                    WHERE actividad_id = :actividad_id");
        $stmt->bindValue(':actividad_id', $data[4]);
        $stmt->execute();
        $employes = $stmt->fetchAll(PDO::FETCH_NUM);


        $empleado_id = array_column($employes, 0);

        $borrar_empleados = array_diff($empleado_id, $data[0]);
        $agregar_empleados = array_diff($data[0], $empleado_id);


        if (!(empty($borrar_empleados))) {
            $action = 'borrar';
            array_push($result, $action);
        }
        if (!(empty($agregar_empleados))) {
            $action = 'agregar';
            array_push($result, $action);
        }

        $stmt = $conn->prepare("SELECT id,lugar,hora_inicio,start
                                    FROM actividades 
                                    WHERE id = :actividad_id");
        $stmt->bindValue(':actividad_id', $data[4]);
        $stmt->execute();
        $results = $stmt->fetch();

        $fecha = date('Y-m-d', strtotime($results['start']));

        if (!($results['lugar'] === $data[3])) {
            $event1 = 1;
        }
        if (!($results['hora_inicio'] === $data[2])) {
            $event2 = 2;
        }
        if (!($fecha === $data[1])) {
            $event3 = 3;
        }
        if ($event1 !== 0 || $event2 !== 0 || $event3 !== 0) {
            $action = 'actualizar';
            array_push($result, $action);
        }
        return $result;
    }


    public static function agregarActualizar($data)
    {

        $con = new ConnDB();
        $conn = $con->getDb();
        $events = [];

        $event1 = 0;
        $event2 = 0;
        $event3 = 0;

        $actividad_id = $data[4];

        $stmt = $conn->prepare("SELECT empleado_id
                                    FROM envios_correos_temp 
                                    WHERE actividad_id = :actividad_id");
        $stmt->bindValue(':actividad_id', $data[4]);
        $stmt->execute();
        $employes = $stmt->fetchAll(PDO::FETCH_NUM);
        $empleado_id = array_column($employes, 0);

        $agregar_empleados = array_diff($data[0], $empleado_id);

        if ($agregar_empleados) {
            self::store($agregar_empleados, $actividad_id, $data);
        }

        $stmt = $conn->prepare("SELECT id,lugar,hora_inicio,start,title,participantes_reunion
                                    FROM actividades 
                                    WHERE id = :actividad_id");
        $stmt->bindValue(':actividad_id', $data[4]);
        $stmt->execute();
        $result = $stmt->fetch();

        $fecha = date('Y-m-d', strtotime($result['start']));

        if (!($result['lugar'] === $data[3])) {
            $event1 = 1;
        }
        if (!($result['hora_inicio'] === $data[2])) {
            $event2 = 2;
        }
        if (!($fecha === $data[1])) {
            $event3 = 3;
        }

        if ($event1 !== 0 || $event2 !== 0 || $event3 !== 0) {
            array_push($events, $event1, $event2, $event3);

            $empleados_enviar = array_diff($data[0], $agregar_empleados);

            if (!empty($empleados_enviar)) {
                $data[0] = $empleados_enviar;
            }
            array_push($data, $result["title"], $result["participantes_reunion"]);

            self::enviarCorreoModificacion($events, $data);
        }
    }

    public static function actualizarEliminar($data)
    {

        $con = new ConnDB();
        $conn = $con->getDb();
        $events = [];

        $event1 = 0;
        $event2 = 0;
        $event3 = 0;

        $actividad_id = $data[4];

        $stmt = $conn->prepare("SELECT empleado_id
                                    FROM envios_correos_temp 
                                    WHERE actividad_id = :actividad_id");
        $stmt->bindValue(':actividad_id', $data[4]);
        $stmt->execute();
        $employes = $stmt->fetchAll(PDO::FETCH_NUM);
        $empleado_id = array_column($employes, 0);

        $borrar_empleados = array_diff($empleado_id, $data[0]);
        if ($borrar_empleados) {
            self::delete($borrar_empleados, $data);
        }
        $agregar_empleados = array_diff($data[0], $empleado_id);

        $stmt = $conn->prepare("SELECT id,lugar,hora_inicio,start,title,participantes_reunion
                                    FROM actividades 
                                    WHERE id = :actividad_id");
        $stmt->bindValue(':actividad_id', $data[4]);
        $stmt->execute();
        $result = $stmt->fetch();

        $fecha = date('Y-m-d', strtotime($result['start']));

        if (!($result['lugar'] === $data[3])) {
            $event1 = 1;
        }
        if (!($result['hora_inicio'] === $data[2])) {
            $event2 = 2;
        }
        if (!($fecha === $data[1])) {
            $event3 = 3;
        }

        if ($event1 !== 0 || $event2 !== 0 || $event3 !== 0) {
            array_push($events, $event1, $event2, $event3);

            $empleados_enviar = array_diff($data[0], $agregar_empleados);

            if (!empty($empleados_enviar)) {
                $data[0] = $empleados_enviar;
            }
            array_push($data, $result["title"], $result["participantes_reunion"]);

            self::enviarCorreoModificacion($events, $data);
        }
    }

    public static function agregarEmpleado($data)
    {
        $con = new ConnDB();
        $conn = $con->getDb();

        $actividad_id = $data[4];

        $stmt = $conn->prepare("SELECT empleado_id
                                    FROM envios_correos_temp 
                                    WHERE actividad_id = :actividad_id");
        $stmt->bindValue(':actividad_id', $data[4]);
        $stmt->execute();
        $employes = $stmt->fetchAll(PDO::FETCH_NUM);
        $empleado_id = array_column($employes, 0);

        $agregar_empleados = array_diff($data[0], $empleado_id);

        if ($agregar_empleados) {
            self::store($agregar_empleados, $actividad_id, $data);
        }
    }

    public static function actualizarActividad($data)
    {

        $con = new ConnDB();
        $conn = $con->getDb();

        $events = [];

        $event1 = 0;
        $event2 = 0;
        $event3 = 0;

        $stmt = $conn->prepare("SELECT id,lugar,hora_inicio,start,title,participantes_reunion
                                    FROM actividades 
                                    WHERE id = :actividad_id");
        $stmt->bindValue(':actividad_id', $data[4]);
        $stmt->execute();
        $result = $stmt->fetch();

        $fecha = date('Y-m-d', strtotime($result['start']));

        if (!($result['lugar'] === $data[3])) {
            $event1 = 1;
        }
        if (!($result['hora_inicio'] === $data[2])) {
            $event2 = 2;
        }
        if (!($fecha === $data[1])) {
            $event3 = 3;
        }

        if ($event1 !== 0 || $event2 !== 0 || $event3 !== 0) {
            array_push($events, $event1, $event2, $event3);

            array_push($data, $result["title"], $result["participantes_reunion"]);

            self::enviarCorreoModificacion($events, $data);
        }
    }

    public static function eliminarEmpleado($data)
    {
        $con = new ConnDB();
        $conn = $con->getDb();

        $stmt = $conn->prepare("SELECT empleado_id
                                    FROM envios_correos_temp 
                                    WHERE actividad_id = :actividad_id");
        $stmt->bindValue(':actividad_id', $data[4]);
        $stmt->execute();
        $employes = $stmt->fetchAll(PDO::FETCH_NUM);
        $empleado_id = array_column($employes, 0);

        $borrar_empleados = array_diff($empleado_id, $data[0]);
        if ($borrar_empleados) {
            self::delete($borrar_empleados, $data);
        }
    }


    public static function store($agregar_empleados, $actividad_id, $data)
    {

        $con = new ConnDB();
        $conn = $con->getDb();

        $stmt = $conn->prepare("SELECT title,participantes_reunion,contacto_id FROM actividades WHERE id = :id");
        $stmt->bindParam(':id', $actividad_id);
        $stmt->execute();
        $result = $stmt->fetch();

        foreach ($agregar_empleados as $empleado_agregar) {

            $email_body = "<b>Tiene una invitación de reunión:</b>" . $data[1] . "<br>
                      <b>Nombre de la invitacion:</b>" . $result["title"] . "<br>
                      <b>Fecha:</b>" . $data[1] . "<br>
                      <b>Hora:</b>" . $data[2] . "<br>
                      <b>Lugar de la reunión:</b>" . $data[3] . "<br>
                      <b>Asistentes:</b>" . $result["participantes_reunion"] . "<br>
                      <b>Convocante:</b>" . $data[6];


            $stmt = $conn->prepare("SELECT PKEmpleado,Nombres,PrimerApellido,SegundoApellido,email FROM empleados WHERE PKEmpleado = :id");
            $stmt->bindParam(':id', $empleado_agregar);
            $stmt->execute();
            $results = $stmt->fetch();

            if ($results["email"] === null) {
                $data[] = 'El empleado: ' . $results["Nombres"] . ' no cuenta con correo';
            }

            $sql = "INSERT INTO envios_correos_temp(empleado_id,actividad_id)
                      VALUES (:empleado_id,:actividad_id)";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':empleado_id', $results['PKEmpleado']);
            $stmt->bindValue(':actividad_id', $actividad_id);
            if ($stmt->execute()) {
                self::enviarEmail($results, $email_body);
            }
        }
        return $data;
    }

    public static function delete($borrar_empleados, $data)
    {
        $con = new ConnDB();
        $conn = $con->getDb();

        foreach ($borrar_empleados as $empleado) {
            $sql = "DELETE FROM envios_correos_temp WHERE empleado_id = :empleado_id AND actividad_id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':empleado_id', $empleado);
            $stmt->bindValue(':id', $data[4]);
            $stmt->execute();
        }
    }

    public static function enviarCorreoModificacion($events, $data)
    {

        if ($events[0] == 1 && $events[1] == 2 && $events[2] == 3) {

            $opc = 1;
            self::update($data, $opc);
        } else if ($events[1] == 2 && $events[2] == 3) {

            $opc = 2;
            self::update($data, $opc);
        } else if ($events[1] == 2 && $events[0] == 1) {

            $opc = 3;
            self::update($data, $opc);
        } else if ($events[2] == 3 && $events[0] == 1) {

            $opc = 4;
            self::update($data, $opc);
        } else if ($events[1] == 2) {

            $opc = 5;
            self::update($data, $opc);
        } else if ($events[2] == 3) {

            $opc = 6;
            self::update($data, $opc);
        } else if ($events[0] == 1) {

            $opc = 7;
            self::update($data, $opc);
        }
    }

    public static function update($data, $opc)
    {
        $con = new ConnDB();
        $conn = $con->getDb();

        $query = "";

        $email_body = "<b>Invitación actualizada:</b>" . $data[8] . ' ' . $data[1] . ' ' . $data[2] . "<br>
                    <b>Nombre de la invitacion:</b>" . $data[8] . "<br>
                    <b>Fecha:</b>" . $data[1] . "<br>
                    <b>Hora:</b>" . $data[2] . "<br>
                    <b>Lugar de la reunión:</b>" . $data[3] . "<br>
                    <b>Asistentes:</b>" . $data[9] . "<br>
                    <b>Convocante:</b>" . $data[6];

        switch ($opc) {
            case 1:
                // lugar,hora_inicio,fecha
                $query = "lugar = '" . $data[3] . "',hora_inicio ='" . $data[2] . "',start ='" . $data[1] . "'";
                break;
            case 2:
                // hora_inicio,fecha
                $query = "hora_inicio ='" . $data[2] . "',start ='" . $data[1] . "'";
                break;
            case 3:
                // hora_inicio,lugar
                $query = "hora_inicio = '" . $data[2] . "', lugar ='" . $data[3] . "'";
                break;
            case 4:
                //  fecha,lugar
                $query = "start ='" . $data[1] . "',lugar ='" . $data[3] . "'";
                break;
            case 5:
                //  hora_inicio
                $query = "hora_inicio ='" . $data[2] . "'";
                break;
            case 6:
                // fecha
                $query = "start = '" . $data[1] . "'";
                break;
            case 7:
                // lugar
                $query = "lugar = '" . $data[3] . "'";
                break;
        }

        $sql = "UPDATE actividades SET " . $query . " WHERE id = :actividad_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':actividad_id', $data[4]);
        if ($stmt->execute()) {
            foreach ($data[0] as $empleado) {
                $stmt = $conn->prepare("SELECT PKEmpleado,Nombres,PrimerApellido,SegundoApellido,email FROM empleados WHERE PKEmpleado = :id");
                $stmt->bindParam(':id', $empleado);
                $stmt->execute();
                $resulted = $stmt->fetch();
                self::enviarEmail($resulted, $email_body);
            }
        }
    }

    public static function enviarCorreo1($resulted, $email_body)
    {
        require('../../../../lib/phpmailer_configuration.php');

        $mail->IsHTML(true);
        $mail->AddAddress($resulted['usuario'], "recipient-name");
        $mail->SetFrom("no-reply@timlid.com", $resulted['usuario']);
        //$mail->AddReplyTo("reply-to-email@domain", "reply-to-name");
        // $mail->AddCC("cc-recipient-email@domain", "cc-recipient-name");
        $mail->Subject = "Estimado:" . $resulted['Nombres'] . ' ' . $resulted['PrimerApellido'] . ' ' . $resulted['SegundoApellido'];
        $content = $email_body;
        $mail->MsgHTML($content);
        if (!$mail->Send()) {
            $error = "No se pudo enviar el correo a:" . $resulted['usuario'];
            echo json_encode($error);
        }
        $mail->ClearAllRecipients();
    }
}
