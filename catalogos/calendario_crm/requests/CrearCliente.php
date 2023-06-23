<?php

class Conx
{

    public function conx()
    {
        include "../../../../include/db-conn.php";
        return $conn;
    }
}


class CrearClienteRequest
//extends Contacto
{

    public static function rules(CrearCliente $data)
    {
        switch ($data->funcion) {
            case 'index':

                break;

            case 'show':

                break;

            case 'store':
                $add_client = self::validarCampos($data);

                if ($add_client == null) {

                    $validate_name = self::validateNameComercial($data);
                    if ($validate_name == 0) {

                        $validate_rfc = self::validateRfcClient($data, $value = 0);
                        return $validate_rfc;
                    }
                    return $validate_name;
                }
                return $add_client;
                break;

            case 'storeContact':
                return $add_contact = self::validateContactCliente($data);
                break;

            case 'update':

                break;

            case 'destroy':

                break;

        }

    }

    public static function validarCampos($data)
    {

        if ($data->NombreComercial == null || $data->NombreComercial == '') {
            return $output = array(
                'error' => true,
                'tipo' => 'empresa',
                'message' => 'El campo Nombre comercial es requerido'
            );
        }

        if ($data->empleado_id == null || $data->empleado_id == '') {
            return $output = array(
                'error' => true,
                'tipo' => 'vendedor',
                'message' => 'El campo Vendedor es requerido');
        }

        if ($data->Email == null || $data->Email == '') {
            return $output = array('error' => true,
                'tipo' => 'email',
                'message' => 'El campo Email es requerido');
        }

        if ($data->razon_social == null || $data->razon_social == '') {
            return $output = array('error' => true,
                'tipo' => 'razon_social',
                'message' => 'El campo Razon social es requerido');
        }

        if ($data->rfc == null || $data->rfc == '') {
            return $output = array(
                'error' => true,
                'tipo' => 'rfc',
                'message' => 'El campo RFC es requerido');
        }

        if ($data->codigo_postal == null || $data->codigo_postal == '') {
            return $output = array(
                'error' => true,
                'tipo' => 'codigo_postal',
                'message' => 'El campo Codigo Postal es requerido');
        }

    }


    public static function validateNameComercial($data)
    {

        $con = new Conx();
        $conn = $con->conx();

        $sql = "CALL spc_ValidarUnicoNombreComercial(:empresa,:empresa_id)";
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':empresa', $data->NombreComercial);
        $stmt->bindParam(':empresa_id', $data->empresa_id);
        $stmt->execute();

        $cliente = $stmt->fetch(PDO::FETCH_OBJ);
        $client_id = $cliente->PKCliente;

        if ($client_id == null) {
            $_SESSION['id_cliente'] = 0;
        }
        $_SESSION['id_cliente'] = $client_id;

        if ($cliente->existe == 1) {
            $output['tipo'] = 'contact_client';
            $output['error'] = true;
            $output['message'] = 'El nombre comercial: ' . $data->NombreComercial . ' ya se encuentra registrado';
        } else {
            $output = 0;
        }

        return $output;
    }


    public static function validateRfcClient($data, $value)
    {
        $con = new Conx();
        $conn = $con->conx();

        $cliente_id = null;

        if ($value == 0) {
            $cliente = $data->PKCliente;
        }

        $sql = "CALL spc_ValidarUnicoRFCCliente(:rfc,:empresa_id)";
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':rfc', $data->rfc);
        $stmt->bindParam(':empresa_id', $data->empresa_id);
        $stmt->execute();

        $rfc = $stmt->fetch(PDO::FETCH_OBJ);
        $rfc_existe = $rfc->existe;

        if ($rfc_existe == 1) {
            $output['tipo'] = 'rfc';
            $output['error'] = true;
            $output['message'] = 'El rfc: ' . $data->rfc . ' ya se encuentra registrado';
        } else {
            $output = 0;
        }

        return $output;
    }


    public static function validateContactCliente($data)
    {


        $con = new Conx();
        $conn = $con->conx();


        $sql = "SELECT email FROM contactos WHERE id = :contac_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':contac_id', $data->PKContacto);
        $stmt->execute();
        $contact_client = $stmt->fetch(PDO::FETCH_OBJ);
        $email_contact = $contact_client->email;

        $sql = "CALL spc_ValidarUnicoContactoCliente(:email,:cliente_id,:empresa_id)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email_contact);
        $stmt->bindParam(':cliente_id', $data->PKCliente);
        $stmt->bindParam(':empresa_id', $data->empresa_id);
        $stmt->execute();

        $contacto = $stmt->fetch(PDO::FETCH_OBJ);

        if ($contacto->existe == 1) {
            $output['tipo'] = 'contact_client';
            $output['error'] = true;
            $output['message'] = 'El contacto:' . $data->Nombres . ' ya esta agregado al cliente';
        } else {
            $output = 0;
        }

        return $output;
    }


}

?>