<?php
class conectar
{ //Llamado al archivo de la conexión.

    public function getDb()
    {
        include "../../../../include/db-conn.php";
        return $conn;
    }
}

class Negocio
{
    public $id;
    public $nombre;
    public $valor;
    public $client_id;
    public $empleado_id;
    public $etapa_usuario_id;
    public $prioridad_id;
    public $usuario_id;
    public $contacto_id;
    public $prospecto_id;
    public $tipo;

    public static function index()
    {
    }

    public static function show()
    {
    }

    public static function store(Negocio $data, $user)
    {
        if ($data->contacto_id) {
            $validate = self::validarContactoNegocio($data, $user);
            if (!empty($validate)) {
                return $validate;
            } else {
                return self::storeNegocio($data);
            }
        } else {
            $validate_valor = self::validarValorNegocio($data, $user);
            if (!empty($validate_valor)) {
                return $validate_valor;
            } else {
                return self::storeNegocio($data);
            }
        }
    }

    public static function validarValorNegocio($data, $user)
    {
        $con = new conectar();
        $conn = $con->getDb();
        $output = [];

        $nombre = trim($data->nombre);

        $sql = "SELECT nombre,valor FROM negocios WHERE usuario_id = :user";
        $rst = $conn->prepare($sql);
        $rst->bindParam(':user', $user);
        $rst->execute();
        $rst = $rst->fetchAll();
        foreach ($rst as $rs) {
            if ($rs['nombre'] == $nombre && $rs['valor'] == $data->valor) {
                $output['error'] = true;
                $output['message'] = 'El negocio: ' . $data->nombre . ' ya se encuentra registrado con el mismo valor';
            }
        }
        return $output;
    }


    public static function validarContactoNegocio($data, $user)
    {
        $con = new conectar();
        $conn = $con->getDb();
        $output = [];

        $sql = "SELECT nombre,contacto_id FROM negocios WHERE usuario_id = :user";
        $rst = $conn->prepare($sql);
        $rst->bindParam(':user', $user);
        $rst->execute();
        $rst = $rst->fetchAll();
        foreach ($rst as $rs) {
            if ($rs['nombre'] == $data->nombre && $rs['contacto_id'] == $data->contacto_id) {
                $output['error'] = true;
                $output['message'] = 'El negocio: ' . $data->nombre . ' ya tiene registrado el mismo contacto';
            }
        }
        return $output;
    }

    public static function storeNegocio(Negocio $negocio)
    {

        $con = new conectar();
        $conn = $con->getDb();
        $output = [];

        if($negocio->contacto_id == 0){
            $contacto = null;
        }else{
            $contacto = $negocio->contacto_id;
        }

        try {
            $sql = "INSERT INTO negocios (nombre, valor, cliente_id, empleado_id, etapa_empresa_usuario_id, prioridad_id, usuario_id, created_at, contacto_id, prospecto_id, descripcion)
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if (!$stmt->execute(
                [
                    $negocio->nombre,
                    $negocio->valor,
                    $negocio->client_id,
                    $negocio->empleado_id,
                    $negocio->etapa_usuario_id,
                    $negocio->prioridad_id,
                    $negocio->usuario_id,
                    $contacto,
                    $negocio->prospecto_id,
                    $negocio->descripcion
                ]
            )) {
                throw new Exception('No se guardo el negocio correctamente');
            }
            $output['status'] = 'success';
            $output['message'] = 'El negocio: ' . $negocio->nombre . 'se ha registrado con éxito';
            $output['contacto_id'] = $negocio->contacto_id;
        } catch (\Throwable $th) {
            $output['status'] = 'fail';
            $output['message'] = $th->getMessage();
            $output['contacto_id'] = $negocio->contacto_id;
        }
        return $output;
    }

    public static function update(Negocio $data)
    {
        $con = new conectar();
        $conn = $con->getDb();

        $sql = "UPDATE negocios SET prospecto_id = ?, nombre = ?,valor = ?, cliente_id = ?, empleado_id = ?, etapa_empresa_usuario_id = ?, prioridad_id = ?, contacto_id = ?, descripcion = ? 
             WHERE id = ? ";
        $conn->prepare($sql)
            ->execute(
                array(
                    $data->prospecto_id,
                    $data->nombre,
                    $data->valor,
                    $data->client_id,
                    $data->empleado_id,
                    $data->etapa_usuario_id,
                    $data->prioridad_id,
                    $data->contacto_id,
                    $data->descripcion,
                    $data->id,
                )
            );
        if ($conn) {
            $output['success'] = true;
            $output['message'] = 'EL negocio se ha editado con éxito';
        }

        $conn = null;
        return $output;
    }

    public static function destroy($id)
    {
        $con = new conectar();
        $conn = $con->getDb();

        $sql = "DELETE FROM negocios WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        if ($stmt->execute()) {
            $output['success'] = true;
            $output['message'] = 'EL negocio se ha eliminado con éxito';
        }

        $stmt = null;
        return $output;
    }
}
