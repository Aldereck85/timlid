<?php


class Conectar
{

    public function getConectar()
    {
        include "../../../../include/db-conn.php";
        return $conn;
    }
}


class Notas
{
    public $id;
    public $contacto_id;
    public $descripcion;
    public $color;
    public $funcion;
    public $user_id;

    public static function index($user, $id)
    {

        $con = new Conectar();
        $conn = $con->getConectar();
        $data = array();

        $query = "SELECT n.id, n.descripcion, n.color, n.created_at, n.updated_at 
        FROM notas n 
        WHERE n.usuario_id = :usuario_id AND n.contacto_id = :id";
        $stmt = $conn->prepare($query);

        $stmt->bindParam(':usuario_id', $user);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_OBJ);

        foreach ($resultado as  $row) {
            $data[] = array(
                "id" => $row->id,
                "nota" => $row->descripcion,
                "fecha_creacion" => $row->created_at,
                "fecha_edicion" => $row->updated_at,
                "color" => $row->color,
            );
        }

        $stmt = null;
        return $data;
    }

    public static function show($data)
    {
        $con = new Conectar();
        $conn = $con->getConectar();
        $output = [];
        $stmt = $conn->prepare("SELECT n.id, n.descripcion, n.color FROM notas n WHERE n.usuario_id = :usuario_id AND n.id = :id");
        $stmt->execute([':usuario_id' => $data->user_id, ':id' => $data->id]);
        $output = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt = null;
        return $output;
    }


    public static function store(Notas $data)
    {

        $con = new Conectar();
        $conn = $con->getConectar();

        $sql = "INSERT INTO notas(contacto_id, descripcion, color, created_at, usuario_id) VALUES (?, ?, ?, NOW(), ?)";
        $conn->prepare($sql)->execute([$data->contacto_id, $data->descripcion, $data->color, $data->user_id]);
        if ($conn) {
            $output['success'] = true;
            $output['message'] = 'La nota se ha registrado con éxito';
        } else {
            $output['error'] = true;
            $output['message'] = 'Ocurrio un error al agregar la nota';
        }
        $conn = null;
        return $output;
    }

    public static function update(Notas $data)
    {
        $con = new Conectar();
        $conn = $con->getConectar();
        $sql = "UPDATE notas SET descripcion = ?, color = ?, updated_at = NOW() WHERE id = ? AND contacto_id = ?";
        $conn->prepare($sql)->execute([$data->descripcion, $data->color, $data->id, $data->contacto_id]);
        if ($conn) {
            $output['success'] = true;
            $output['message'] = 'La nota se ha actualizado con éxito';
        } else {
            $output['error'] = true;
            $output['message'] = 'Ocurrio un error al actualizar la nota';
        }
        $conn = null;
        return $output;
    }

    public static function destroy($data)
    {
        $con = new Conectar();
        $conn = $con->getConectar();
        $sql = "DELETE FROM notas WHERE id = :id AND contacto_id = :contacto_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $data->id, ':contacto_id' => $data->contacto_id]);
        if ($conn) {
            $output['success'] = true;
            $output['message'] = 'La nota se ha eliminado con éxito';
        } else {
            $output['error'] = true;
            $output['message'] = 'Ocurrio un error al eliminar la nota';
        }
        $stmt = null;
        return $output;
    }
}
