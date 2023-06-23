<?php

class GetConectar
{ //Llamado al archivo de la conexión.

    public function getConectar()
    {
        include "../../../../include/db-conn.php";
        return $conn;
    }
}

class EventosCrm
{
    public $id;
    public $title;
    public $start;
    public $hora_inicio;
    public $end;
    public $hora_final;
    public $color;
    public $contacto_id;
    public $tipo_actividad_id;
    public $descripcion;
    public $lugar;
    public $es_todo_dia;
    public $calendario_id;
    public $funcion;
    public $telefono;
    public $participantes_reunion;
    public $prioridad_tarea;
    public $usuario_actividad_creo_id;
    public $usuario_actividad_edito_id;
    public $resultado_llamadas;
    public $groupId;



}

?>