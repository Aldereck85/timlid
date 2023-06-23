<?php

session_start();

include '../modelos/loadCalendario.php';
include '../requests/Evento.php';
include 'EnviarCorreoCrm.php';

$user = $_SESSION["PKUsuario"];
$empresa_id = $_SESSION["IDEmpresa"];
$username = $_SESSION["UsuarioNombre"];

if (!empty($_POST["accion"])) {
    if ($_POST["accion"] === "cargar_eventos_por_usuario") {
        $actividades_usuario = Calendar::cargarActividades($user, $empresa_id);
        echo json_encode($actividades_usuario);
    } else if ($_POST["accion"] === "cargarContactos") {
        $ver_contactos = Calendar::loadContactos($user, $empresa_id);
        if ($ver_contactos) {
            echo json_encode($ver_contactos);
        }
    } else if ($_POST['accion'] == 'cargarTipoActividades') {
        $ver_actividades = Calendar::loadActividades();
        if ($ver_actividades) {
            echo json_encode($ver_actividades);
        }
    } else if ($_POST['accion'] == 'insertarEvento') {
        $evento = new Calendar();
        $evento->contacto_id = NULL;
        $evento->cliente_id = NULL;
        $evento->usuario_id = $user;
        $evento->empresa_id = $empresa_id;
        $evento->title = $_POST['title'];
        $evento->start = $_POST['start'];
        $evento->color = $_POST['color'];
        $evento->tipo_actividad_id = $_POST['actividad_id'];
        $evento->descripcion = $_POST['descripcion'] ? $_POST['descripcion'] : NULL;
        $evento->hora_inicio = $_POST['hora_inicio'] ? $_POST['hora_inicio'] : NULL;
        $evento->es_todo_dia = isset($_POST['tarea_todo_dia']) ? $_POST['tarea_todo_dia'] : NULL;
        $evento->hora_inicio = $evento->es_todo_dia == 1 ? '00:00:00' : $evento->hora_inicio;
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
                $nuevo_evento = Calendar::store($evento);
                break;
            case 2:
                $evento->hora_final = $_POST['hora_final'] ? $_POST['hora_final'] : NULL;
                if ($evento->es_todo_dia == 1) {
                    $evento->hora_final = '23:59:59';
                }
                $evento->lugar = $_POST['lugar'] ? $_POST['lugar'] : NULL;
                $evento->participantes_reunion = $_POST['invitados'] ? $_POST['invitados'] : NULL;
                $nuevo_evento = Calendar::store($evento);
                if ($nuevo_evento['status'] !== 'success') {
                    break;
                }
                if (!empty($_POST['integrantes'])) {
                    $integrantes = $_POST['integrantes'];
                    $fecha_con_formato = $_POST['fecha_reunion'];
                    $actividad_reunion = $nuevo_evento['actividad_id'];
                    $nuevo_evento = EnviarCorreoCrm::empleadoEnvioCorreoNuevo($integrantes, $actividad_reunion, $evento, $fecha_con_formato, $username);
                }
                break;
            case 3:
                $evento->resultado_llamadas = $_POST['resultado_llamada'];
                $nuevo_evento = Calendar::store($evento);
                break;
            case 4:
                $nuevo_evento = Calendar::store($evento);
                break;
            default:
                $nuevo_evento = ['status' => 'fail', 'message' => 'No es una actividad valida.'];
                break;
        }
        echo json_encode($nuevo_evento);
    } else if ($_POST['accion'] == 'updateEvento') {
        $evento = new Calendar();
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
                $evento_actualizado = Calendar::update($evento);
                break;
            case 2:
                $evento->hora_final = $_POST['hora_final'] ? $_POST['hora_final'] : NULL;
                if ($evento->es_todo_dia == 1) {
                    $evento->hora_final = '23:59:59';
                }
                $evento->lugar = $_POST['lugar'] ? $_POST['lugar'] : NULL;
                $evento->participantes_reunion = $_POST['invitados'] ? $_POST['invitados'] : NULL;
                $evento_actualizado = Calendar::update($evento);
                if ($evento_actualizado['status'] !== 'success') {
                    break;
                }
                if (!empty($_POST['integrantes'])) {
                    $integrantes = $_POST['integrantes'];
                    $fecha_con_formato = $_POST['fecha_reunion'];
                    $actividad_reunion = $evento_actualizado['actividad_id'];
                    $evento_actualizado = EnviarCorreoCrm::empleadoEnvioCorreoNuevo($integrantes, $actividad_reunion, $evento, $fecha_con_formato, $username);
                }
                break;
            case 3:
                $evento->resultado_llamadas = $_POST['resultado_llamada'];
                $evento_actualizado = Calendar::update($evento);
                break;
            case 4:
                $evento_actualizado = Calendar::update($evento);
                break;
            default:
                $evento_actualizado = ['status' => 'fail', 'message' => 'No es una actividad valida.'];
                break;
        }
        echo json_encode($evento_actualizado);
    } else if ($_POST['accion'] == 'eliminarEvento') {
        $id = $_POST['id'];
        $eliminar = Calendar::destroy($id);
        echo json_encode($eliminar);
    } else if ($_POST['accion'] == 'eliminarEventoReunion') {
        $id = $_POST['id'];
        $eliminar = Calendar::destroyReunion($id, $username);
        echo json_encode($eliminar);
    } else if ($_POST['accion'] == 'updateEventoDropRisize') {
        $evento = new Calendar();
        $evento->start = $_POST['start'];
        $evento->id = $_POST['id'];
        $actualizar_evento = Calendar::updateEventDate($evento);
        echo json_encode($actualizar_evento);
    } else if ($_POST['accion'] == 'updateInicioFin') {
        $evento = new Calendar();
        $evento->hora_inicio = $_POST['start'];
        $evento->hora_final = $_POST['end'];
        $evento->id = $_POST['id'];
        $actualizar_evento = Calendar::updateInicioFin($evento);
        echo json_encode($actualizar_evento);
    } else if ($_POST['accion'] == 'updateEventoFecha') {
        $evento = new Calendar();
        $evento->start = $_POST['start'];
        $evento->end = $_POST['end'];
        $evento->title = $_POST['title'];
        $evento->id = $_POST['id'];
        $actualizar_evento = Calendar::updateEventDate($evento);
        echo json_encode($actualizar_evento);
    } else if ($_POST['accion'] == 'buscarEmpleados') {
        $actividad_id = $_POST['actividad_id'];
        $cargar_empleados = EnviarCorreoCrm::buscarEmpleados($actividad_id);
        echo json_encode($cargar_empleados);
    } else if ($_POST['accion'] == 'actualizarActividad') {
        $data = [];
        $data = $_POST['data'];
        $validar_actividad = EnviarCorreoCrm::verActividadEnvioCorreoEditar($data);
        if ($validar_actividad) {
            echo json_encode($validar_actividad);
        }
    } else if ($_POST['accion'] === 'enviarCorreos') {
        $data = $_POST['data'];
        $data['username'] = $username;
        $data['idUser'] = $user;
        //echo json_encode(EnviarCorreoCrm::reenviarCorreos($data));
    }
}
