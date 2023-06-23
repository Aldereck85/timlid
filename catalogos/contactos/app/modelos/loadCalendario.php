<?php

class Conexion
{ //Llamado al archivo de la conexión.

    public function conectar()
    {
        include "../../../../include/db-conn.php";
        return $conn;
    }
}

class Calendar
{
    public static function cargarActividades($contacto_id, $empresa_id, $user)
    {
        $con = new Conexion();
        $conn = $con->conectar();
        $data = array();
        $query = "SELECT * FROM actividades WHERE usuario_id = :usuario_id AND empresa_id = :empresa_id AND contacto_id = :contacto_id";
        $statement = $conn->prepare($query);
        $statement->bindParam(':usuario_id', $user, PDO::PARAM_INT);
        $statement->bindParam(':empresa_id', $empresa_id, PDO::PARAM_INT);
        $statement->bindParam(':contacto_id', $contacto_id, PDO::PARAM_INT);
        $statement->execute();
        $eventos = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach ($eventos as $evento) {
            $data[] = array(
                "id" => $evento["id"],
                "title" => $evento["title"],
                "start" => $evento["start"],
                "color" => $evento["color"],
                "groupId" => $evento["tipo_actividad_id"],
                "extendedProps" => array(
                    "hora_inicio" => $evento["hora_inicio"],
                    "hora_final" => $evento["hora_final"],
                    "prioriodad" => $evento["prioridad_tarea"],
                    "descripcion" => $evento["descripcion"],
                    "lugar" => $evento["lugar"],
                    "es_todo_dia" => $evento["es_todo_dia"],
                    "tipo_actividad_id" => $evento["tipo_actividad_id"],
                    "contacto_id" => $evento["contacto_id"],
                    "participantes" => $evento["participantes_reunion"],
                    "resultado_llamada" => $evento["resultado_llamadas"],
                    "color" => $evento["color"],
                ),
            );
        }
        return $data;
    }

    public static function cargarActividadesCliente($cliente_id, $empresa_id, $user)
    {
        $con = new Conexion();
        $conn = $con->conectar();
        $data = array();
        $query = "SELECT * FROM actividades WHERE usuario_id = :usuario_id AND empresa_id = :empresa_id AND cliente_id = :cliente_id";
        $statement = $conn->prepare($query);
        $statement->bindParam(':usuario_id', $user, PDO::PARAM_INT);
        $statement->bindParam(':empresa_id', $empresa_id, PDO::PARAM_INT);
        $statement->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
        $statement->execute();
        $eventos = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach ($eventos as $evento) {
            $data[] = array(
                "id" => $evento["id"],
                "title" => $evento["title"],
                "start" => $evento["start"],
                "color" => $evento["color"],
                "groupId" => $evento["tipo_actividad_id"],
                "extendedProps" => array(
                    "hora_inicio" => $evento["hora_inicio"],
                    "hora_final" => $evento["hora_final"],
                    "prioriodad" => $evento["prioridad_tarea"],
                    "descripcion" => $evento["descripcion"],
                    "lugar" => $evento["lugar"],
                    "es_todo_dia" => $evento["es_todo_dia"],
                    "tipo_actividad_id" => $evento["tipo_actividad_id"],
                    "cliente_id" => $evento["cliente_id"],
                    "participantes" => $evento["participantes_reunion"],
                    "resultado_llamada" => $evento["resultado_llamadas"],
                    "color" => $evento["color"],
                ),
            );
        }
        return $data;
    }

    public static function cargarActividadesPorCliente($cliente_id, $empresa_id, $user)
    {
        $con = new Conexion();
        $conn = $con->conectar();
        $data = array();
        $query = "SELECT * FROM actividades WHERE usuario_id = :usuario_id AND empresa_id = :empresa_id AND cliente_id = :cliente_id";
        $statement = $conn->prepare($query);
        $statement->bindParam(':usuario_id', $user, PDO::PARAM_INT);
        $statement->bindParam(':empresa_id', $empresa_id, PDO::PARAM_INT);
        $statement->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
        $statement->execute();
        $eventos = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach ($eventos as $evento) {
            $data[] = array(
                "id" => $evento["id"],
                "title" => $evento["title"],
                "start" => $evento["start"],
                "color" => $evento["color"],
                "groupId" => $evento["tipo_actividad_id"],
                "extendedProps" => array(
                    "hora_inicio" => $evento["hora_inicio"],
                    "hora_final" => $evento["hora_final"],
                    "prioriodad" => $evento["prioridad_tarea"],
                    "descripcion" => $evento["descripcion"],
                    "lugar" => $evento["lugar"],
                    "es_todo_dia" => $evento["es_todo_dia"],
                    "tipo_actividad_id" => $evento["tipo_actividad_id"],
                    "cliente_id" => $evento["cliente_id"],
                    "participantes" => $evento["participantes_reunion"],
                    "resultado_llamada" => $evento["resultado_llamadas"],
                    "color" => $evento["color"],
                ),
            );
        }
        return $data;
    }

    public static function cargarActividadPorUsuario($user)
    {
        $con = new Conexion();
        $conn = $con->conectar();
        $data = array();

        $query = "SELECT * FROM  actividades WHERE usuario_id = :usuario_id";
        $statement = $conn->prepare($query);
        $statement->bindParam(':usuario_id', $user, PDO::PARAM_INT);
        $statement->execute();
        $eventos = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach ($eventos as $evento) {
            $data[] = array(
                "id" => $evento["id"],
                "title" => $evento["title"],
                "start" => $evento["start"],
                "color" => $evento["color"],
                "groupId" => $evento["tipo_actividad_id"],
                "extendedProps" => array(
                    "hora_inicio" => $evento["hora_inicio"],
                    "hora_final" => $evento["hora_final"],
                    "prioriodad" => $evento["prioridad_tarea"],
                    "descripcion" => $evento["descripcion"],
                    "lugar" => $evento["lugar"],
                    "es_todo_dia" => $evento["es_todo_dia"],
                    "tipo_actividad_id" => $evento["tipo_actividad_id"],
                    "contacto_id" => $evento["contacto_id"],
                    "participantes" => $evento["participantes_reunion"],
                    "resultado_llamada" => $evento["resultado_llamadas"],
                    "color" => $evento["color"],
                ),
            );
        }
        return $data;
    }
}
