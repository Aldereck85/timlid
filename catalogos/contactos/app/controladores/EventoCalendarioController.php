<?php
session_start();

include '../modelos/EventosCalendario.php';
include '../requests/Evento.php';
include 'EnviarCorreo.php';

$user = $_SESSION["PKUsuario"];
$empresa_id = $_SESSION["IDEmpresa"];
$username = $_SESSION["UsuarioNombre"];

if (!empty($_POST["accion"])) {
    if ($_POST['accion'] == 'insertarEvento') {
        $evento = new Eventos();
        $evento->usuario_id = $user;
        $evento->empresa_id = $empresa_id;
        $evento->title = $_POST['title'];
        $evento->start = $_POST['start'];
        $evento->color = $_POST['color'];
        $evento->tipo_actividad_id = $_POST['actividad_id'];
        $evento->descripcion = $_POST['descripcion'] ? $_POST['descripcion'] : NULL;
        $evento->hora_inicio = $_POST['hora_inicio'] ? $_POST['hora_inicio'] : NULL;
        $evento->es_todo_dia = isset($_POST['tarea_todo_dia']) ? $_POST['tarea_todo_dia'] : NULL;
        if ($evento->es_todo_dia == 1) {
            $evento->hora_inicio = '00:00:00';
        }
        if ($_POST['contactoCliente']) {
            $tipeContactoCliente = explode("-", $_POST['contactoCliente'])[0];
            $idContactoCliente = explode("-", $_POST['contactoCliente'])[1];
            if ($tipeContactoCliente === 'cnt') {
                $evento->contacto_id = $idContactoCliente;
            } elseif ($tipeContactoCliente === 'cli') {
                $evento->cliente_id = $idContactoCliente;
            }
        }
        switch ($_POST['actividad_id']) {
            case 1:
                $evento->prioridad_tarea = $_POST['prioridad'] ? $_POST['prioridad'] : NULL;
                $nuevo_evento = Eventos::store($evento);
                break;
            case 2:
                $evento->hora_final = $_POST['hora_final'] ? $_POST['hora_final'] : NULL;
                if ($evento->es_todo_dia == 1) {
                    $evento->hora_final = '23:59:59';
                }
                $evento->lugar = $_POST['lugar'] ? $_POST['lugar'] : NULL;
                $evento->participantes_reunion = $_POST['invitados'] ? $_POST['invitados'] : NULL;
                $nuevo_evento = Eventos::store($evento);
                if ($nuevo_evento['status'] !== 'success') {
                    break;
                }
                if (!(empty($_POST['integrantes']))) {
                    $integrantes = $_POST['integrantes'];
                    $fecha_con_formato = $_POST['fecha_reunion'];
                    $actividad_reunion = $nuevo_evento['actividad_id'];
                    $nuevo_evento = EnviarCorreoContactos::empleadoEnvioCorreoNuevo($integrantes, $actividad_reunion, $evento, $fecha_con_formato, $username);
                }
                break;
            case 3:
                $evento->resultado_llamadas = $_POST['resultado_llamada'];
                $nuevo_evento = Eventos::store($evento);
                break;
            case 4:
                $nuevo_evento = Eventos::store($evento);
                break;
            default:
                $nuevo_evento = ['status' => 'fail', 'message' => 'No es una actividad valida.'];
                break;
        }
        echo json_encode($nuevo_evento);
    } else if ($_POST['accion'] == 'updateEvento') {
        $evento = new Eventos();
        $evento->id = $_POST['id'];
        $evento->usuario_id = $user;
        $evento->empresa_id = $empresa_id;
        $evento->title = $_POST['title'];
        $evento->start = $_POST['start'];
        $evento->color = $_POST['color'];
        $evento->tipo_actividad_id = $_POST['actividad_id'];
        $evento->descripcion = $_POST['descripcion'] ? $_POST['descripcion'] : NULL;
        $evento->hora_inicio = $_POST['hora_inicio'] ? $_POST['hora_inicio'] : NULL;
        $evento->es_todo_dia = isset($_POST['tarea_todo_dia']) ? $_POST['tarea_todo_dia'] : NULL;
        if ($evento->es_todo_dia == 1) {
            $evento->hora_inicio = '00:00:00';
        }
        if ($_POST['contactoCliente']) {
            $tipeContactoCliente = explode("-", $_POST['contactoCliente'])[0];
            $idContactoCliente = explode("-", $_POST['contactoCliente'])[1];
            if ($tipeContactoCliente === 'cnt') {
                $evento->contacto_id = $idContactoCliente;
            } elseif ($tipeContactoCliente === 'cli') {
                $evento->cliente_id = $idContactoCliente;
            }
        }
        switch ($_POST['actividad_id']) {
            case 1:
                $evento->prioridad_tarea = $_POST['prioridad'] ? $_POST['prioridad'] : NULL;
                $evento_actualizado = Eventos::update($evento);
                break;

            case 2:
                $evento->hora_final = $_POST['hora_final'] ? $_POST['hora_final'] : NULL;
                if ($evento->es_todo_dia == 1) {
                    $evento->hora_final = '23:59:59';
                }
                $evento->lugar = $_POST['lugar'] ? $_POST['lugar'] : NULL;
                $evento->participantes_reunion = $_POST['invitados'] ? $_POST['invitados'] : NULL;
                $evento_actualizado = Eventos::update($evento);
                if ($evento_actualizado['status'] !== 'success') {
                    break;
                }
                if (!empty($_POST['integrantes'])) {
                    $integrantes = $_POST['integrantes'];
                    $fecha_con_formato = $_POST['fecha_reunion'];
                    $actividad_reunion = $evento_actualizado['actividad_id'];
                    $evento_actualizado = EnviarCorreoContactos::empleadoEnvioCorreoNuevo($integrantes, $actividad_reunion, $evento, $fecha_con_formato, $username);
                }
                break;
            case 3:
                $evento->resultado_llamadas = $_POST['resultado_llamada'];
                $evento_actualizado = Eventos::update($evento);
                break;
            case 4:
                $evento_actualizado = Eventos::update($evento);
                break;
            default:
                $evento_actualizado = ['status' => 'fail', 'message' => 'No es una actividad valida.'];
                break;
        }
        echo json_encode($evento_actualizado);
    } else if ($_POST['accion'] == 'updateEventoDropRisize') {
        $evento = new Eventos();
        $evento->start = $_POST['start'];
        $evento->end = $_POST['end'];
        $evento->title = $_POST['title'];
        $evento->id = $_POST['id'];
        $actualizar_evento = Eventos::updateEventDate($evento);
        echo json_encode($actualizar_evento);
    } else if ($_POST['accion'] == 'updateInicioFin') {
        $evento = new Eventos();
        $evento->hora_inicio = $_POST['start'];
        $evento->hora_final = $_POST['end'];
        $evento->id = $_POST['id'];
        $actualizar_evento = Eventos::updateInicioFin($evento);
        echo json_encode($actualizar_evento);
    } else if ($_POST['accion'] == 'updateEventoFecha') {
        $evento = new Eventos();
        $evento->start = $_POST['start'];
        $evento->end = $_POST['end'];
        $evento->title = $_POST['title'];
        $evento->id = $_POST['id'];
        $actualizar_evento = Eventos::updateEventDate($evento);
        echo json_encode($actualizar_evento);
    } else if ($_POST['accion'] == 'eliminarEvento') {
        $id = $_POST['id'];
        $eliminar = Eventos::destroy($id);
        echo json_encode($eliminar);
    } else if ($_POST['accion'] == 'eliminarEventoReunion') {
        $id = $_POST['id'];
        $eliminar = Eventos::destroyReunion($id, $username);
        echo json_encode($eliminar);
    } else if ($_POST['accion'] == 'cargarContactos') {
        $ver_contactos = Eventos::loadContactos($user, $empresa_id);
        if ($ver_contactos) {
            echo json_encode($ver_contactos);
        }
    } else if ($_POST['accion'] == 'cargarTipoActividades') {
        $ver_actividades = Eventos::loadActividades();
        if ($ver_actividades) {
            echo json_encode($ver_actividades);
        }
    } else if ($_POST['accion'] == 'buscarEmpleados') {
        $id = $_POST['contacto_id'];
        $actividad_id = $_POST['actividad_id'];
        $cargar_empleados = EnviarCorreoContactos::buscarEmpleados($actividad_id);
        echo json_encode($cargar_empleados);
    } else if ($_POST['accion'] == 'actualizarActividad') {
        $data = [];
        $data = $_POST['data'];
        $validar_actividad = EnviarCorreoContactos::verActividadEnvioCorreoEditar($data);
        if ($validar_actividad) {
            echo json_encode($validar_actividad);
        }
    } else if ($_POST['accion'] === 'enviarCorreos') {
        $data = $_POST['data'];
        $data['username'] = $username;
        $data['idUser'] = $user;
        //$opc = $data[5];
        //array_push($data, $username, $user);
        //echo json_encode($data);
        echo json_encode(EnviarCorreoContactos::reenviarCorreos($data));

        /* switch ($opc) {
            case 1:
                $agregar_actualizar = EnviarCorreoContactos::agregarActualizar($data);
                break;
            case 2:
                $actualizar_eliminar = EnviarCorreoContactos::actualizarEliminar($data);
                break;
            case 3:
                $agregar_empleado = EnviarCorreoContactos::agregarEmpleado($data);
                break;
            case 4:
                $actualizar_actividad = EnviarCorreoContactos::actualizarActividad($data);
                break;
            case 5:
                $eliminar_empleado = EnviarCorreoContactos::eliminarEmpleado($data);
                break;
        } */
    }
}
