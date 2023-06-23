<?php

class Conexion
{ //Llamado al archivo de la conexión.

    public function conectar()
    {
        $conn = null;
        include "../../../../include/db-conn.php";
        return $conn;
    }
}

class Eventos
{
    public $id;
    public $title;
    public $color;
    public $start;
    public $hora_inicio;
    public $hora_final;
    public $prioridad_tarea;
    public $descripcion;
    public $lugar;
    public $es_todo_dia;
    public $created_at;
    public $updated_at;
    public $tipo_actividad_id;
    public $contacto_id;
    public $cliente_id;
    public $participantes_reunion;
    public $resultado_llamadas;
    public $usuario_id;
    public $empresa_id;

    public static function store(Eventos $evento)
    {
        $con = new Conexion();
        $conn = $con->conectar();
        $output = [];
        try {
            switch ($evento->tipo_actividad_id) {
                case 1:
                    $query = "INSERT INTO actividades
                    (title, tipo_actividad_id, color, start, descripcion, es_todo_dia, hora_inicio, contacto_id, cliente_id, prioridad_tarea, usuario_id, empresa_id)
                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
                    $statement = $conn->prepare($query);
                    if (!$statement->execute(
                        [
                            $evento->title,
                            $evento->tipo_actividad_id,
                            $evento->color,
                            $evento->start,
                            $evento->descripcion,
                            $evento->es_todo_dia,
                            $evento->hora_inicio,
                            $evento->contacto_id,
                            $evento->cliente_id,
                            $evento->prioridad_tarea,
                            $evento->usuario_id,
                            $evento->empresa_id
                        ]
                    )) {
                        throw new Exception('No se registro la tarea correctamente.');
                    }
                    $output['status'] = 'success';
                    $output['message'] = 'La tarea se ha agregado correctamente.';
                    break;
                case 2:
                    $query = "INSERT INTO actividades
                    (title, tipo_actividad_id, color, start, descripcion, es_todo_dia, hora_inicio, hora_final, lugar, participantes_reunion, contacto_id, cliente_id, usuario_id, empresa_id)
                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                    $statement = $conn->prepare($query);
                    if (!$statement->execute(
                        [
                            $evento->title,
                            $evento->tipo_actividad_id,
                            $evento->color,
                            $evento->start,
                            $evento->descripcion,
                            $evento->es_todo_dia,
                            $evento->hora_inicio,
                            $evento->hora_final,
                            $evento->lugar,
                            $evento->participantes_reunion,
                            $evento->contacto_id,
                            $evento->cliente_id,
                            $evento->usuario_id,
                            $evento->empresa_id
                        ]
                    )) {
                        throw new Exception('No se registro la reunion correctamente');
                    }
                    $output['status'] = 'success';
                    $output['message'] = 'La reunión se ha agregado correctamente.';
                    $output['actividad_id'] = $conn->lastInsertId();
                    break;

                case 3:
                    $query = "INSERT INTO actividades
                    (title, tipo_actividad_id, color, start, descripcion, hora_inicio, contacto_id, cliente_id, resultado_llamadas, usuario_id, empresa_id)
                    VALUES (?,?,?,?,?,?,?,?,?,?,?)";
                    $statement = $conn->prepare($query);
                    if (!$statement->execute(
                        array(
                            $evento->title,
                            $evento->tipo_actividad_id,
                            $evento->color,
                            $evento->start,
                            $evento->descripcion,
                            $evento->hora_inicio,
                            $evento->contacto_id,
                            $evento->cliente_id,
                            $evento->resultado_llamadas,
                            $evento->usuario_id,
                            $evento->empresa_id
                        )
                    )) {
                        throw new Exception('No se registro la llamada correctamente.');
                    }
                    $output['status'] = 'success';
                    $output['message'] = 'La llamada se ha agregado correctamente.';
                    break;
                case 4:
                    $query = "INSERT INTO actividades
                    (title, tipo_actividad_id, color, start, descripcion, hora_inicio, contacto_id, cliente_id, usuario_id, empresa_id)
                    VALUES (?,?,?,?,?,?,?,?,?,?)";
                    $statement = $conn->prepare($query);
                    if (!$statement->execute(
                        [
                            $evento->title,
                            $evento->tipo_actividad_id,
                            $evento->color,
                            $evento->start,
                            $evento->descripcion,
                            $evento->hora_inicio,
                            $evento->contacto_id,
                            $evento->cliente_id,
                            $evento->usuario_id,
                            $evento->empresa_id
                        ]
                    )) {
                        throw new Exception('No se registro el correo correctamente.');
                    }
                    $output['status'] = 'successs';
                    $output['message'] = 'El correo se ha agregado correctamente.';
                    break;
                default:
                    $output['status'] = 'fail';
                    $output['message'] = 'No es una actividad valida.';
                    break;
            }
        } catch (\Throwable $th) {
            $output['status'] = 'fail';
            $output['message'] = $th->getMessage();
        }
        return $output;
    }

    public static function update(Eventos $evento)
    {
        $con = new Conexion();
        $conn = $con->conectar();
        $output = [];
        try {
            switch ($evento->tipo_actividad_id) {
                case 1:
                    $query = "UPDATE actividades SET title = ?, color = ?, start = ?, descripcion = ?, es_todo_dia = ?, hora_inicio = ?, contacto_id = ?, cliente_id = ?, prioridad_tarea = ? WHERE id = ? AND usuario_id = ? AND empresa_id = ?";
                    $statement = $conn->prepare($query);
                    if (!$statement->execute(
                        [
                            $evento->title,
                            $evento->color,
                            $evento->start,
                            $evento->descripcion,
                            $evento->es_todo_dia,
                            $evento->hora_inicio,
                            $evento->contacto_id,
                            $evento->cliente_id,
                            $evento->prioridad_tarea,
                            $evento->id,
                            $evento->usuario_id,
                            $evento->empresa_id
                        ]
                    )) {
                        throw new Exception('No se actualizo la tarea correctamente');
                    }
                    $output['status'] = 'success';
                    $output['message'] = $evento;
                    break;
                case 2:
                    $query = "UPDATE actividades
                    SET title =  ?, color = ?, start = ?, descripcion = ?, es_todo_dia = ?, hora_inicio = ?, hora_final = ?, lugar = ?, participantes_reunion = ?, contacto_id = ?, cliente_id = ?
                    WHERE id = ?";
                    $statement = $conn->prepare($query);
                    if (!$statement->execute(
                        [
                            $evento->title,
                            $evento->color,
                            $evento->start,
                            $evento->descripcion,
                            $evento->es_todo_dia,
                            $evento->hora_inicio,
                            $evento->hora_final,
                            $evento->lugar,
                            $evento->participantes_reunion,
                            $evento->contacto_id,
                            $evento->cliente_id,
                            $evento->id
                        ]
                    )) {
                        throw new Exception('No se actualizo la reunion correctamente');
                    }
                    $output['status'] = 'success';
                    $output['message'] = 'La reunión se ha actualizado correctamente';
                    $output['actividad_id'] = $evento->id;
                    return $output;
                    break;
                case 3:
                    $query = "UPDATE actividades
                    SET title =  ?, color = ?, start = ?, descripcion = ?, hora_inicio = ?, hora_final = ?, contacto_id = ?, cliente_id = ?, resultado_llamadas = ?
                    WHERE id = ? ";
                    $statement = $conn->prepare($query);
                    if (!$statement->execute(
                        [
                            $evento->title,
                            $evento->color,
                            $evento->start,
                            $evento->descripcion,
                            $evento->hora_inicio,
                            $evento->hora_final,
                            $evento->contacto_id,
                            $evento->cliente_id,
                            $evento->resultado_llamadas,
                            $evento->id,
                        ]
                    )) {
                        throw new Exception('Nose actualizo la llamada correctamente');
                    }
                    $output['status'] = 'success';
                    $output['message'] = 'La llamada se ha actualizado correctamente';
                    return $output;
                    break;
                case 4:
                    $query = "UPDATE actividades
                    SET title =  ?, color = ?, start = ?, descripcion = ?, hora_inicio = ?, contacto_id = ?, cliente_id = ?
                    WHERE id = ?";
                    $statement = $conn->prepare($query);
                    if (!$statement->execute(
                        [
                            $evento->title,
                            $evento->color,
                            $evento->start,
                            $evento->descripcion,
                            $evento->hora_inicio,
                            $evento->contacto_id,
                            $evento->cliente_id,
                            $evento->id,
                        ]
                    )) {
                        throw new Exception('El correo no se actualizo correctamente');
                    }
                    $output['status'] = 'success';
                    $output['message'] = 'El correo se ha actualizado correctamente';
                    return $output;
                    break;
                default:
                    $output['status'] = 'fail';
                    $output['message'] = 'No es una actividad valida.';
                    break;
            }
        } catch (\Throwable $th) {
            $output['status'] = 'fail';
            $output['message'] = $th->getMessage();
        }
        return $output;
    }

    public static function destroy($id)
    {
        $con = new Conexion;
        $conn = $con->conectar();
        try {
            $query = "DELETE FROM actividades WHERE id = ?";
            $stmt = $conn->prepare($query);
            if (!$stmt->execute(array($id))) {
                throw new Exception("Error Processing Request", 1);
            }
            return ['status' => 'success'];
        } catch (\Throwable $e) {
            return ['status' => 'fail', 'message' => 'Ha ocurrido un error al eliminar el evento:' . $e->getMessage()];
        }
    }

    public static function destroyReunion($id, $username)
    {
        $con = new Conexion;
        $conn = $con->conectar();
        try {
            $query = "SELECT title, start, hora_inicio, lugar FROM actividades WHERE id = :actividad_id";
            $stmt = $conn->prepare($query);
            $stmt->execute([':actividad_id' => $id]);
            $actividad = $stmt->fetch(PDO::FETCH_ASSOC);

            $query = "SELECT empleado_id FROM envios_correos_temp WHERE actividad_id = :actividad_id";
            $stmt = $conn->prepare($query);
            $stmt->execute([':actividad_id' => $id]);
            $empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($empleados as $empleado_id) {
                $query = "SELECT u.id, u.usuario, e.Nombres, e.PrimerApellido, e.SegundoApellido FROM usuarios AS u LEFT JOIN empleados AS e ON  u.id = e.PKEmpleado WHERE u.id = :id";
                $stmt = $conn->prepare($query);
                $stmt->execute([':id' => $empleado_id['empleado_id']]);
                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
                $email = self::enviarCorreo($usuario, $actividad, $username);
                if ($email['status'] !== 'success') {
                    throw new Exception($email['message']);
                }
            }
            $query = "DELETE FROM actividades WHERE id = ?";
            $stmt = $conn->prepare($query);
            if (!$stmt->execute([$id])) {
                throw new Exception('No se elimino la reunion correctamenmte');
            }
            $output['status'] = 'success';
            $output['message'] = 'La reunion se elimino correctamente';
            return $output;
        } catch (Exception $e) {
            $output['status'] = 'fail';
            $output['message'] = 'Ha ocurrido un error al eliminar el evento:' . $e->getMessage();
            return $output;
        }
    }

    public static function updateInicioFin(Eventos $data)
    {

        $con = new Conexion();
        $conn = $con->conectar();
        $output = [];

        $query = "UPDATE actividades SET hora_inicio = ?, hora_final = ?  WHERE id = ? ";

        $statement = $conn->prepare($query);
        $statement->execute(
            array(

                $data->hora_inicio,
                $data->hora_final,
                $data->id,
            )
        );
        if ($statement) {
            $output['success'] = true;
            $output['message'] = 'Evento se ha actualizado correctamente';
            return $output;
        }
    }


    public static function updateEventDate(Eventos $data)
    {

        $con = new Conexion();
        $conn = $con->conectar();
        $output = [];

        $query = "UPDATE actividades SET title = ?, start = ?, end = ?  WHERE id = ?";

        $statement = $conn->prepare($query);
        $statement->execute(
            array(

                $data->title,
                $data->start,
                $data->end,
                $data->id,
            )
        );
        if ($statement) {
            $output['success'] = true;
            $output['message'] = 'Evento se ha actualizado correctamente';
            return $output;
        }
    }


    public static function loadContactos($id, $empresa_id)
    {
        $con = new Conexion;
        $conn = $con->conectar();

        $stmt = $conn->prepare("SELECT role_id FROM usuarios WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        $userRol = $res['role_id'];

        /* CONTACTOS */
        $dataContactos = [];
        $contactos = ['label' => 'Contactos'];
        $queryContactos = "SELECT id, nombre, apellido, empresa FROM contactos WHERE (usuario_creo_id = :id OR empleado_id = :idEmpleado) AND estatus_iniciativa_id = 1 AND cliente_id IS NULL ORDER BY id ASC";
        $valuesContactos = [':id' => $id, ':idEmpleado' => $id];
        if ($userRol == 2 || $userRol == 12) {
            $queryContactos = "SELECT id, nombre, apellido, empresa FROM contactos WHERE empresa_id = :empresa_id AND estatus_iniciativa_id = 1 AND cliente_id IS NULL ORDER BY id ASC";
            $valuesContactos = [':empresa_id' => $empresa_id];
        }
        $stmt = $conn->prepare($queryContactos);
        $stmt->execute($valuesContactos);
        $contactosRes = $stmt->fetchAll(PDO::FETCH_OBJ);
        foreach ($contactosRes as $contactoRes) {
            $dataContactos[] = ['value' => 'cnt-' . $contactoRes->id, 'text' => $contactoRes->nombre . ' - ' . $contactoRes->empresa];
        }
        $contactos['options'] = $dataContactos;

        /* CLIENTES */
        $dataClientes = [];
        $clientes = ['label' => 'Clientes'];
        $stmt = $conn->prepare("SELECT PKCliente, NombreComercial FROM clientes WHERE empresa_id = :empresa_id ORDER BY PKCliente ASC");
        $stmt->execute([':empresa_id' => $empresa_id]);
        $clientesRes = $stmt->fetchAll(PDO::FETCH_OBJ);
        foreach ($clientesRes as $clienteRes) {
            $dataClientes[] = ['value' => 'cli-' . $clienteRes->PKCliente, 'text' => $clienteRes->NombreComercial];
        }
        $clientes['options'] = $dataClientes;

        $stmt = null;
        return [$contactos, $clientes];
    }


    public static function loadActividades()
    {

        $data = [];

        $con = new Conexion();
        $conn = $con->conectar();

        $stmt = $conn->prepare("SELECT * FROM tipo_actividades ORDER BY actividad");
        $stmt->execute();
        $resultado = $stmt->fetchAll();

        foreach ($resultado as $row) {
            $data[] = array(
                "id" => $row['id'],
                "actividad" => $row['actividad']

            );
        }

        $stmt = null;
        return $data;
    }


    public static function enviarCorreo($usuario, $actividad, $username)
    {
        require('../../../../lib/phpmailer_configuration.php');
        try {
            $mail->IsHTML(true);
            $mail->AddAddress($usuario['usuario'], "recipient-name");
            $mail->SetFrom("no-reply@timlid.com", $usuario['usuario']);

            $mail->Subject = "Estimado:" . $usuario['Nombres'] . ' ' . $usuario['PrimerApellido'] . ' ' . $usuario['SegundoApellido'];
            $content = "<b>Evento cancelado:</b>" . $actividad['title'] . "<br>
            <b>Lugar de la reuni&oacute;n:</b>" . $actividad['lugar'] . "<br>
            <b>Fecha:</b>" . $actividad['start'] . "<br>
            <b>Hora:</b>" . $actividad['hora_inicio'] . "<br>
            <b>Convocante:</b>" . $username . "<br>";
            $mail->MsgHTML($content);
            if (!$mail->Send()) {
                throw new Exception("No se pudo enviar el correo: " . $usuario['Nombres']);
            }
            $mail->ClearAllRecipients();
            return ['status' => 'success', 'message' => 'Todo Bien'];
        } catch (\Throwable $th) {
            return ['status' => 'fail', 'message' => $th->getMessage()];
        }
    }
}
