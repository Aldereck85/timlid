<?php

class Conn
{

    public function conn()
    {
        include "../../../../include/db-conn.php";
        return $conn;
    }
}

class ContactoRequests
{
    public static function rules(Contacto $data, $empresa_id)
    {
        switch ($data->funcion) {
            case 'index':
                break;
            case 'show':
                break;
            case 'store':
                self::validateContact($data, $empresa_id);
                break;
            case 'update':
                break;
            case 'destroy':
                self::validateDestroy($data);
                break;
        }
    }


    public static function validarStore($data)
    {

        if ($data->empresa == null || $data->empresa == '') {
            return $output = array(
                'error' => true,
                'tipo' => 'campo',
                'message' => 'El campo empresa es requerido');
        }

        if ($data->empleado_id == null || $data->empleado_id == '') {
            return $output = array('error' => true,
                'tipo' => 'campo',
                'message' => 'El campo vendedor es requerido');
        }

        if ($data->nombre == null || $data->nombre == '') {
            return $output = array('error' => true,
                'tipo' => 'campo',
                'message' => 'El campo nombre es requerido');
        }

        if ($data->apellido == null || $data->apellido == '') {
            return $output = array('error' => true,
                'tipo' => 'campo',
                'message' => 'El campo apellido es requerido');
        }

        if ($data->email == null || $data->email == '') {
            return $output = array('error' => true,
                'tipo' => 'campo',
                'message' => 'El campo email es requerido');
        }

    }


    public static function validateContact($data, $empresa_id)
    {

        $output = array();
        $con = new Conn();
        $conn = $con->conn();

        $stmt = $conn->prepare("SELECT cs.empresa,cs.nombre,cs.apellido,cs.email,es.PKEmpresa
                                FROM contactos cs
                                INNER JOIN usuarios us ON cs.usuario_creo_id = us.id
                                INNER JOIN empresas es ON us.empresa_id = es.PKEmpresa 
                                WHERE usuario_creo_id = ? AND es.PKEmpresa = ? ");
        $stmt->execute(array($data->usuario_creo_id, $empresa_id));
        $respuesta = $stmt->fetchAll();

        foreach ($respuesta as $value) {
            if ($value['nombre'] === $data->nombre) {
                $output['tipo'] = 'nombre';
                $output['error'] = true;
                $output['message'] = 'El contacto: ' . $data->nombre . ' ya se encuentra registrado';
                return $output;
            }
            if ($value['email'] === $data->email) {
                $output['tipo'] = 'email';
                $output['error'] = true;
                $output['message'] = 'El correo: ' . $data->email . ' ya se encuentra registrado';
                return $output;
            }

        }

    }


    public static function validateDestroy($data)
    {
        if ($data->motivo_declinar == null || $data->motivo_declinar == '') {
            $output['error'] = true;
            $output['tipo'] = 'delete_prospecto';
            $output['message'] = 'El campo motivo es requerido';
            return $output;
        }
    }
}

?>