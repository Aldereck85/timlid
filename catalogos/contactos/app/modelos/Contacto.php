<?php

//include '../../../../include/db-conn.php';


class ConnDB
{
    public function getDb()
    {
        $conn = null;
        include "../../../../include/db-conn.php";
        return $conn;
    }
}

class Contacto
{
    public $id;
    public $empresa_id;
    public $empleado_id;
    public $medio_contacto_campania_id;
    public $nombre;
    public $apellido;
    public $puesto;
    public $email;
    public $telefono;
    public $celular;
    public $sitio_web;
    public $direccion;
    public $fecha_aniversario;
    public $pais_id;
    public $estado_id;
    public $estatus_iniciativa_id;
    public $motivo_declinar;
    public $usuario_creo_id;
    public $usuario_edito_id;
    public $funcion;
    public $contacto_empresa_id;

    public static function index($id, $estatus_id, $empresa_id)
    {
        $data = array();
        $con = new ConnDB();
        $conn = $con->getDb();

        $stmt = $conn->prepare("SELECT role_id FROM usuarios WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        $userRol = $res['role_id'];

        $query = "SELECT cs.id, cs.contacto_empresa_id, cs.empresa, cs.nombre, cs.apellido, cs.email, cs.estatus_iniciativa_id,
		cs.cliente_id, cs.empleado_id, IFNULL(cs.motivo_declinar, '') AS motivo, mcc.MedioContactoCliente as campania, em.Nombres, em.PrimerApellido, em.SegundoApellido
		FROM contactos cs 
		LEFT JOIN medios_contacto_clientes mcc ON cs.medio_contacto_campania_id = mcc.PKMedioContactoCliente 
		LEFT JOIN empleados em ON  cs.empleado_id = em.PKEmpleado
		WHERE (cs.usuario_creo_id = :id OR cs.empleado_id = :idEmpleado) AND cs.empresa_id = :empresa_id AND cs.cliente_id IS NULL
        ORDER BY cs.contacto_empresa_id ASC";
        $values = [':id' => $id, ':idEmpleado' => $id, ':empresa_id' => $empresa_id];

        if ($userRol == 2 || $userRol == 12) {
            $query = "SELECT cs.id, cs.contacto_empresa_id, cs.empresa, cs.nombre, cs.apellido, cs.email, cs.estatus_iniciativa_id,
            cs.cliente_id, cs.empleado_id, IFNULL(cs.motivo_declinar, '') AS motivo, mcc.MedioContactoCliente as campania, em.Nombres, em.PrimerApellido, em.SegundoApellido
            FROM contactos cs 
            LEFT JOIN medios_contacto_clientes mcc ON cs.medio_contacto_campania_id = mcc.PKMedioContactoCliente 
            LEFT JOIN empleados em ON  cs.empleado_id = em.PKEmpleado
            WHERE cs.empresa_id = :empresa_id AND cs.cliente_id IS NULL
            ORDER BY cs.contacto_empresa_id ASC";
            $values = [':empresa_id' => $empresa_id];
        }
        $stmt = $conn->prepare($query);
        $stmt->execute($values);
        $resultado = $stmt->fetchAll(PDO::FETCH_OBJ);


        foreach ($resultado as $row) {
            $data[] = array(
                "id" => $row->id,
                "contacto_id" => $row->contacto_empresa_id,
                "empresa" => $row->empresa,
                "nombre" => $row->nombre,
                "apellido" => $row->apellido,
                "email" => $row->email,
                "medio_contacto_campania" => $row->campania,
                "tipo" => $row->cliente_id,
                "estatus" => $row->estatus_iniciativa_id,
                "propietario" => $row->Nombres . ' ' . $row->PrimerApellido . ' ' . $row->SegundoApellido,
                "motivo" => $row->motivo,
            );
        }

        $stmt = null;
        return $data;
    }


    public static function show($contacto_empresa_id, $id)
    {
        $con = new ConnDB();
        $conn = $con->getDb();

        try {
            $stmt = $conn->prepare("SELECT * FROM contactos
			WHERE id = :id AND contacto_empresa_id = :contacto_empresa_id");

            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':contacto_empresa_id', $contacto_empresa_id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public static function show1($empresa_id, $id)
    {
        $con = new ConnDB();
        $conn = $con->getDb();

        try {
            $stmt = $conn->prepare("SELECT cs.id, cs.cliente_id, cs.empresa, cs.nombre, cs.apellido, cs.estado_id, cs.puesto, cs.email, cs.telefono, cs.celular, cs.empleado_id, cs.medio_contacto_campania_id, cs.motivo_declinar, ef.Estado, cs.pais_id, cs.sitio_web, cs.direccion, cs.aniversario_empresa
            FROM contactos cs
            LEFT JOIN estados_federativos ef ON cs.estado_id = ef.PKEstado
			WHERE cs.contacto_empresa_id = :contacto_empresa_id
            AND cs.empresa_id = :empresa_id");

            $stmt->bindParam(':contacto_empresa_id', $id);
            $stmt->bindParam(':empresa_id', $empresa_id);

            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public static function store(Contacto $contacto, $empresa_id)
    {
        $con = new ConnDB();
        $conn = $con->getDb();
        $idContactoPorEmpresa = self::getLastIdEmpresa($empresa_id);
        try {
            $sql = "INSERT INTO `contactos` (`contacto_empresa_id`, `empresa`, `nombre`, `apellido`, `puesto`, `email`, `telefono`, `celular`, `medio_contacto_campania_id`, `estatus_iniciativa_id`, `empleado_id`, `estado_id`, `created_at`, `usuario_creo_id`, `sitio_web`, `direccion`, `aniversario_empresa`, `pais_id`, `empresa_id`) 
        VALUES(:contacto_empresa_id, :empresa, :nombre, :apellido, :puesto, :email, :telefono, :celular, :medio_contacto_id, :estatus, :empleado_id, :estado_id, NOW(), :usuario_creo_id, :sitio_web, :direccion, :aniversario, :pais_id, :empresa_id)";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':contacto_empresa_id', $idContactoPorEmpresa);
            $stmt->bindParam(':empresa', $contacto->empresa);
            $stmt->bindParam(':nombre', $contacto->nombre);
            $stmt->bindParam(':apellido', $contacto->apellido);
            $stmt->bindParam(':puesto', $contacto->puesto);
            $stmt->bindParam(':email', $contacto->email);
            $stmt->bindParam(':telefono', $contacto->telefono);
            $stmt->bindParam(':celular', $contacto->celular);
            $stmt->bindParam(':medio_contacto_id', $contacto->medio_contacto_campania_id);
            $stmt->bindParam(':estatus', $contacto->estatus_iniciativa_id);
            $stmt->bindParam(':empleado_id', $contacto->empleado_id);
            $stmt->bindParam(':estado_id', $contacto->estado_id);
            $stmt->bindParam(':usuario_creo_id', $contacto->usuario_creo_id);
            $stmt->bindParam(':sitio_web', $contacto->sitio_web);
            $stmt->bindParam(':direccion', $contacto->direccion);
            $stmt->bindParam(':aniversario', $contacto->fecha_aniversario);
            $stmt->bindParam(':pais_id', $contacto->pais_id);
            $stmt->bindParam(':empresa_id', $contacto->empresa_id);

            if (!$stmt->execute()) {
                throw new Exception('Algo salio mal');
            }
            $output['status'] = 'success';
            $output['message'] = 'El contacto ' . $contacto->nombre . ' se ha registrado con éxito';
        } catch (\Throwable $th) {
            $output['status'] = 'fail';
            $output['message'] = $th->getMessage();
        }
        $conn = null;
        return $output;
    }

    public static function addContactoProspecto($data)
    {
        $con = new ConnDB();
        $conn = $con->getDb();
        try {
            $sql = "INSERT INTO `contactos_prospectos` (`nombre`, `email`, `celular`, `contacto_id`) VALUES(:nombre, :email, :celular, :contacto_id)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nombre', $data['nombre']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':celular', $data['celular']);
            $stmt->bindParam(':contacto_id', $data['id']);
            if (!$stmt->execute()) {
                throw new Exception('No se pudo guardar el contacto correctamente');
            }
            $output['status'] = 'success';
            $output['message'] = 'El contacto se ha registrado con éxito';
        } catch (\Throwable $th) {
            $output['status'] = 'fail';
            $output['message'] = $th->getMessage();
        }
        $conn = null;
        return $output;
    }

    public static function verContactosProspectos($id)
    {
        $con = new ConnDB();
        $conn = $con->getDb();
        $data = array();
        try {
            $stmt = $conn->prepare("SELECT * FROM contactos_prospectos WHERE contacto_id = :id ");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $contactos =  $stmt->fetchAll(PDO::FETCH_OBJ);
            foreach ($contactos as $contacto) {
                $data[] = array(
                    "id" => $contacto->id,
                    "nombre" => $contacto->nombre,
                    "email" => $contacto->email,
                    "celular" => $contacto->celular,
                );
            }
            $stmt = null;
            return $data;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public static function deleteContactoProspecto($id)
    {
        $con = new ConnDB();
        $conn = $con->getDb();
        $sql = "DELETE FROM contactos_prospectos WHERE id = :id";
        try {
            $stmt = $conn->prepare($sql);
            if (!$stmt->execute([':id' => $id])) {
                throw new Exception('Algo salio mal.');
            }
            $res = ['status' => 'success'];
        } catch (\Throwable $th) {
            $res = ['status' => 'fail'];
        }
        return $res;
    }

    public static function getLastIdEmpresa($empresa_id)
    {
        $con = new ConnDB();
        $conn = $con->getDb();
        $sql = "SELECT COALESCE(MAX(contacto_empresa_id),0) AS contacto_empresa_id FROM contactos WHERE empresa_id = :empresa_id";
        try {
            $stmt = $conn->prepare($sql);
            if (!$stmt->execute([':empresa_id' => $empresa_id])) {
                throw new Exception('Algo salio mal');
            }
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado['contacto_empresa_id'] + 1;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public static function update(Contacto $data)
    {
        $con = new ConnDB();
        $conn = $con->getDb();
        try {
            $sql = "UPDATE contactos
            SET empresa = ?, nombre = ?, apellido = ?, puesto = ?, email = ?, telefono = ?, celular = ?, empleado_id = ?, estado_id = ?, medio_contacto_campania_id = ?, usuario_edito_id = ?, updated_at = NOW(), sitio_web = ?, direccion = ?, aniversario_empresa = ?, pais_id = ?
            WHERE id = ? AND contacto_empresa_id = ?";
            $stmt = $conn->prepare($sql);
            if (!$stmt->execute(
                [
                    $data->empresa,
                    $data->nombre,
                    $data->apellido,
                    $data->puesto,
                    $data->email,
                    $data->telefono,
                    $data->celular,
                    $data->empleado_id,
                    $data->estado_id,
                    $data->medio_contacto_campania_id,
                    $data->usuario_edito_id,
                    $data->sitio_web,
                    $data->direccion,
                    $data->fecha_aniversario,
                    $data->pais_id,
                    $data->id,
                    $data->contacto_empresa_id,
                ]
            )) {
                throw new Exception('El contacto no se actualizo correctamente');
            }
            $output['status'] = 'success';
            $output['message'] = 'El contacto ' . $data->nombre . ' se ha actualizado con éxito';
        } catch (\Throwable $th) {
            $output['status'] = 'fail';
            $output['message'] = 'Algo salio mal: ' . $th->getMessage();
        }
        return $output;
    }

    public static function destroy(Contacto $data)
    {
        $con = new ConnDB();
        $conn = $con->getDb();
        try {
            $stmt = $conn->prepare("SELECT cliente_id FROM contactos WHERE id = :id");
            if (!$stmt->execute([':id' => $data->id])) {
                throw new Exception('Fallo al buscar cliente');
            }
            $cliente_id = $stmt->fetch(PDO::FETCH_ASSOC);
            $cliente_id_delete = $cliente_id['cliente_id'];
            $query = "UPDATE contactos SET motivo_declinar = ?, estatus_iniciativa_id = ?, usuario_edito_id = ?, updated_at = NOW() WHERE id = ?";
            $stmt = $conn->prepare($query);
            if (!$stmt->execute([$data->motivo_declinar, $data->estatus_iniciativa_id, $data->usuario_edito_id, $data->id])) {
                throw new Exception('Fallo al actualizar cliente');
            }
            if ($cliente_id_delete != null) {
                $status = 0;
                $resDestroyCliente = self::destroyClient($status, $cliente_id_delete);
                if ($resDestroyCliente['status'] === 'fail') {
                    throw new Exception('Fallo al eliminar cliente');
                }
            }
            $res = ['status' => 'success', 'message' => 'El contacto se ha eliminado con éxito'];
        } catch (\Throwable $th) {
            $res = ['status' => 'fail', 'message' => $th->getMessage()];
        }
        return $res;
    }

    public static function destroyClient($status, $client_id_delete)
    {
        $con = new ConnDB();
        $conn = $con->getDb();
        $sql = "UPDATE clientes SET estatus = :estatus WHERE PKCliente = :id";
        try {
            $stmt = $conn->prepare($sql);
            if (!$stmt->execute([':estatus' => $status, ':id' => $client_id_delete])) {
                throw new Exception('Algo salio mal.');
            }
            $res = ['status' => 'success'];
        } catch (\Throwable $th) {
            $res = ['status' => 'fail'];
        }
        return $res;
    }

    public static function loadContactos($empresa_id)
    {
        $data = [['placeholder' => true, 'text' => 'Selecciona un vendedor']];
        $con = new ConnDB();
        $conn = $con->getDb();

        $query = "SELECT em.PKEmpleado, concat(em.Nombres,' ',em.PrimerApellido,' ',em.SegundoApellido) AS nombre
        FROM usuarios us
        INNER JOIN empleados em ON us.id = em.PKEmpleado
        LEFT JOIN relacion_tipo_empleado re ON us.id = re.empleado_id
        WHERE (re.tipo_empleado_id = 1 OR us.role_id = 2 OR us.role_id = 12) AND us.empresa_id = :id
        GROUP BY PKEmpleado
        ORDER BY nombre ASC;";
        $rst = $conn->prepare($query);
        $rst->bindParam(':id', $empresa_id);
        $rst->execute();
        $resultado = $rst->fetchAll(PDO::FETCH_ASSOC);
        foreach ($resultado as $row) {
            $data[] = [
                "value" => $row['PKEmpleado'],
                "text" => $row['nombre'],

            ];
        }
        $rst = null;
        return $data;
    }

    public static function loadEstados()
    {
        $data = [];
        $con = new ConnDB();
        $conn = $con->getDb();

        $query = 'SELECT * FROM estados_federativos where FKPais = 146;';
        $rst = $conn->prepare($query);
        $rst->execute();
        $resultado = $rst->fetchAll();
        foreach ($resultado as $row) {
            $data[] = array(
                "id" => $row['PKEstado'],
                "estado" => $row['Estado'],
            );
        }

        return $data;
    }

    public static function loadEmpleados($empresa_id)
    {
        $data = [];
        $con = new ConnDB();
        $conn = $con->getDb();

        $query = "SELECT u.id, u.usuario, e.Nombres, e.PrimerApellido, e.SegundoApellido
        FROM usuarios AS u
        LEFT JOIN empleados AS e ON  u.id = e.PKEmpleado
        WHERE u.empresa_id = :empresa_id AND u.estatus = 1";
        $rst = $conn->prepare($query);
        $rst->execute([':empresa_id' => $empresa_id]);
        $resultado = $rst->fetchAll(PDO::FETCH_ASSOC);
        foreach ($resultado as $row) {
            $fullName = $row['SegundoApellido'] ? $row['Nombres'] . ' ' . $row['PrimerApellido'] . ' ' . $row['SegundoApellido'] : $row['Nombres'] . ' ' . $row['PrimerApellido'];
            $data[] = array(
                "id" => $row['id'],
                "nombre_completo" => $fullName,
                "correo" => $row['usuario'],
            );
        }
        return $data;
    }

    public static function verClientes($empresa_id)
    {
        $data = array();

        $con = new ConnDB();
        $conn = $con->getDb();
        $stmt = $conn->prepare("SELECT PKCliente, NombreComercial, Email, razon_social, rfc FROM clientes WHERE empresa_id = :empresa_id");
        $stmt->bindParam(':empresa_id', $empresa_id);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_OBJ);


        foreach ($resultado as $row) {
            $data[] = array(
                'id' => $row->PKCliente,
                'nombre' => $row->NombreComercial,
                'email' => $row->Email,
                'razon_social' => $row->razon_social,
                'rfc' => $row->rfc,
                'acciones' => ''
            );
        }
        $stmt = null;
        return $data;
    }

    public static function loadMedios($empresa_id)
    {
        $data = [['placeholder' => true, 'text' => 'Selecciona un vendedor']];
        $con = new ConnDB();
        $conn = $con->getDb();
        $query = "SELECT PKMedioContactoCliente, MedioContactoCliente FROM medios_contacto_clientes WHERE empresa_id = 1 OR empresa_id = :empresa_id";
        $rst = $conn->prepare($query);
        $rst->bindParam(':empresa_id', $empresa_id);
        $rst->execute();
        $resultado = $rst->fetchAll(PDO::FETCH_ASSOC);
        foreach ($resultado as $row) {
            $data[] = [
                "value" => $row['PKMedioContactoCliente'],
                "text" => $row['MedioContactoCliente'],

            ];
        }
        $rst = null;
        return $data;
    }

    public static function validateMedio($medio, $empresa_id)
    {
        $con = new ConnDB();
        $conn = $con->getDb();
        $existe = 0;
        $sql = "SELECT PKMedioContactoCliente AS existe FROM medios_contacto_clientes WHERE MedioContactoCliente = :medio AND (empresa_id = 1 OR empresa_id = :empresa_id)";
        try {
            $stmt = $conn->prepare($sql);
            if (!$stmt->execute([':medio' => $medio, ':empresa_id' => $empresa_id])) {
                throw new Exception("Algo salio mal");
            }
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($resultado) {
                $existe = 1;
            }
            return ['status' => 'success', 'existe' => $existe];
        } catch (\Throwable $th) {
            return ['status' => 'fail', 'message' => $th->getMessage()];
        }
    }

    public static function addMedio($medio, $empresa_id)
    {
        $con = new ConnDB();
        $conn = $con->getDb();
        $sql = "INSERT INTO medios_contacto_clientes (MedioContactoCliente, empresa_id) VALUES (:nombre, :empresa_id)";
        try {
            $stmt = $conn->prepare($sql);
            if (!$stmt->execute([':nombre' => $medio, ':empresa_id' => $empresa_id])) {
                throw new Exception("Algo salio mal");
            }
            $id = $conn->lastInsertId();
            return ['status' => 'success', 'id' => $id];
        } catch (\Throwable $th) {
            return ['status' => 'fail', 'message' => $th->getMessage()];
        }
    }

    public static function cliente($empresa_id, $id)
    {
        $con = new ConnDB();
        $conn = $con->getDb();

        try {
            $stmt = $conn->prepare("SELECT PKCliente, NombreComercial, rfc FROM clientes WHERE PKCliente = :cliente_id AND empresa_id = :empresa_id");
            $stmt->bindParam(':cliente_id', $id);
            $stmt->bindParam(':empresa_id', $empresa_id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public static function addCliente($datosCliente, $datosContacto, $empresa_id, $user_id)
    {
        try {
            $con = new ConnDB();
            $conn = $con->getDb();
            if (!$datosContacto['nombreContacto'] || !$datosContacto['emailContacto'] || !$datosContacto['celularContacto'] || (!$datosContacto['medioContacto'] || $datosContacto['medioContacto'] === 'undefined')) {
                throw new Exception('Por favor llena todos los datos del contacto.');
            }

            if (!$datosCliente['nombreCliente'] || !$datosCliente['vendedorCliente'] || !$datosCliente['razonSocCliente'] || !$datosCliente['rfcCliente'] || (!$datosCliente['regimenCliente'] || $datosCliente['regimenCliente'] === 'undefined') || !$datosCliente['codigoPostalCliente'] || (!$datosCliente['paisCliente'] || $datosCliente['paisCliente'] === 'undefined') || (!$datosCliente['estadoCliente'] || $datosCliente['estadoCliente'] === 'undefined')) {
                throw new Exception('Por favor llena todos los datos del cliente.');
            }

            $stmt = $conn->prepare("SELECT rfc FROM clientes WHERE rfc = :rfc AND empresa_id = :empresa_id");
            if (!$stmt->execute([':rfc' => $datosCliente['rfcCliente'], ':empresa_id' => $empresa_id])) {
                throw new Exception('Fallo al validar RFC.');
            }

            $rfcExiste = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($rfcExiste) {
                throw new Exception('El RFC ya esta registrado.');
            }

            $stmt = $conn->prepare("INSERT INTO clientes (NombreComercial, Telefono, Email, razon_social, rfc, Calle, Numero_exterior, Numero_Interior, Municipio, Colonia, codigo_postal, estatus_prospecto_id, pais_id, estado_id, empresa_id, medio_contacto_id, usuario_creacion_id, created_at, estatus, empleado_id, regimen_fiscal_id)
            VALUES (:NombreComercial, :Telefono, :Email, :razon_social, :rfc, :Calle, :Numero_exterior, :Numero_Interior, :Municipio, :Colonia, :codigo_postal, 1, :pais_id, :estado_id, :empresa_id, :medio_contacto_id, :usuario_creacion_id, NOW(), 1, :empleado_id, :regimen_fiscal_id)");
            if (!$stmt->execute([':NombreComercial' => $datosCliente['nombreCliente'], ':Telefono' => $datosContacto['celularContacto'], ':Email' => $datosContacto['emailContacto'], ':razon_social' => $datosCliente['razonSocCliente'], ':rfc' => $datosCliente['rfcCliente'], ':Calle' => $datosCliente['calleCliente'], ':Numero_exterior' => $datosCliente['noExteriorCliente'], ':Numero_Interior' => $datosCliente['noInteriorCliente'], ':Municipio' => $datosCliente['municipioCliente'], ':Colonia' => $datosCliente['coloniaCliente'], ':codigo_postal' => $datosCliente['codigoPostalCliente'], ':pais_id' => $datosCliente['paisCliente'], ':estado_id' => $datosCliente['estadoCliente'], ':empresa_id' => $empresa_id, ':medio_contacto_id' => $datosContacto['medioContacto'], ':usuario_creacion_id' => $user_id, ':empleado_id' => $datosCliente['vendedorCliente'], ':regimen_fiscal_id' => $datosCliente['regimenCliente']])) {
                throw new Exception('Fallo al insertar nuevo clientes');
            }
            $cliente_id = $conn->lastInsertId();

            $stmt = $conn->prepare("INSERT INTO dato_contacto_cliente
            (Nombres, Apellidos, Puesto, Telefono, Celular, Email, EmailFacturacion, EmailComplementoPago, EmailAvisosEnvio, EmailPagos, FKCliente)
            VALUES (:nombres, :apellidos, :puesto, :telefono, :celular, :email, 1, 1, 1, 1, :cliente_id)");
            if (!$stmt->execute([':nombres' => $datosContacto['nombreContacto'], ':apellidos' => $datosContacto['apellidoContacto'], ':puesto' => $datosContacto['puestoContacto'], ':telefono' => $datosContacto['telefonoContacto'], ':celular' => $datosContacto['celularContacto'], ':email' => $datosContacto['emailContacto'], ':cliente_id' => $cliente_id])) {
                throw new Exception('Fallo al insertar nuevo contacto en clientes');
            }
            $idContactoCliente = $conn->lastInsertId();

            $stmt = $conn->prepare("UPDATE contactos SET contacto_cliente_id = :relacion_contacto_id, cliente_id = :cliente_id  WHERE id = :contacto_id");
            if (!$stmt->execute([':relacion_contacto_id' => $idContactoCliente, ':cliente_id' => $cliente_id, ':contacto_id' => $datosContacto['contacto_id']])) {
                throw new Exception('Fallo al actualizar el contacto');
            }

            $updatedActividades = self::updateActividadesCRM($datosContacto['contacto_id'], $cliente_id);
            if ($updatedActividades['status'] !== 'success') {
                throw new Exception($updatedActividades['message']);
            }

            $otrosContactos = self::addOtrosContactos($datosContacto['contacto_id'], $cliente_id);
            if ($otrosContactos['status'] !== 'success') {
                throw new Exception($otrosContactos['message']);
            }

            return ['status' => 'success', 'message' => 'Se agrego el cliente.'];
        } catch (\Throwable $th) {
            return ['status' => 'fail', 'message' => $th->getMessage(), 'medio' => $datosContacto['medioContacto']];
        }
    }

    public static function addClienteNoFacturacion($datosCliente, $datosContacto, $empresa_id, $user_id)
    {
        try {
            $con = new ConnDB();
            $conn = $con->getDb();
            if (!$datosContacto['nombreContacto'] || !$datosContacto['emailContacto'] || !$datosContacto['celularContacto'] || (!$datosContacto['medioContacto'] || $datosContacto['medioContacto'] === 'undefined')) {
                throw new Exception('Por favor llena todos los datos del contacto.');
            }

            if (!$datosCliente['razonSocCliente']) {
                throw new Exception('Por favor llena todos los datos del cliente.');
            }

            $stmt = $conn->prepare("SELECT PKEmpleado FROM empleados WHERE empresa_id = :empresa AND estatus = 1 ORDER BY PKEmpleado ASC LIMIT 1");
            if (!$stmt->execute([':empresa' => $empresa_id])) {
                throw new Exception('Fallo al buscar el empleado');
            }
            $empleado = $stmt->fetch(PDO::FETCH_ASSOC);

            $stmt = $conn->prepare("INSERT INTO clientes (NombreComercial, Email, razon_social, rfc, codigo_postal, estatus_prospecto_id, pais_id, estado_id, empresa_id, medio_contacto_id, usuario_creacion_id, created_at, estatus, empleado_id)
            VALUES (:NombreComercial, :Email, :razon_social, :rfc, :codigo_postal, 1, :pais_id, :estado_id, :empresa_id, :medio_contacto_id, :usuario_creacion_id, NOW(), 1, :empleado_id)");
            if (!$stmt->execute([':NombreComercial' => $datosCliente['razonSocCliente'],':Email' => 'N/A', ':razon_social' => $datosCliente['razonSocCliente'], ':rfc' => 'N/A', ':codigo_postal' => 'N/A', ':pais_id' => 146, ':estado_id' => 14, ':empresa_id' => $empresa_id, ':medio_contacto_id' => 1, ':usuario_creacion_id' => $user_id, ':empleado_id' => $empleado['PKEmpleado']])) {
                throw new Exception('Fallo al insertar nuevo clientes');
            }
            $cliente_id = $conn->lastInsertId();

            $stmt = $conn->prepare("INSERT INTO dato_contacto_cliente
            (Nombres, Apellidos, Puesto, Telefono, Celular, Email, EmailFacturacion, EmailComplementoPago, EmailAvisosEnvio, EmailPagos, FKCliente)
            VALUES (:nombres, :apellidos, :puesto, :telefono, :celular, :email, 1, 1, 1, 1, :cliente_id)");
            if (!$stmt->execute([':nombres' => $datosContacto['nombreContacto'], ':apellidos' => $datosContacto['apellidoContacto'], ':puesto' => $datosContacto['puestoContacto'], ':telefono' => $datosContacto['telefonoContacto'], ':celular' => $datosContacto['celularContacto'], ':email' => $datosContacto['emailContacto'], ':cliente_id' => $cliente_id])) {
                throw new Exception('Fallo al insertar nuevo contacto en clientes');
            }
            $idContactoCliente = $conn->lastInsertId();

            $stmt = $conn->prepare("UPDATE contactos SET contacto_cliente_id = :relacion_contacto_id, cliente_id = :cliente_id  WHERE id = :contacto_id");
            if (!$stmt->execute([':relacion_contacto_id' => $idContactoCliente, ':cliente_id' => $cliente_id, ':contacto_id' => $datosContacto['contacto_id']])) {
                throw new Exception('Fallo al actualizar el contacto');
            }

            $updatedActividades = self::updateActividadesCRM($datosContacto['contacto_id'], $cliente_id);
            if ($updatedActividades['status'] !== 'success') {
                throw new Exception($updatedActividades['message']);
            }

            $otrosContactos = self::addOtrosContactos($datosContacto['contacto_id'], $cliente_id);
            if ($otrosContactos['status'] !== 'success') {
                throw new Exception($otrosContactos['message']);
            }

            return ['status' => 'success', 'message' => 'Se agrego el cliente.'];
        } catch (\Throwable $th) {
            return ['status' => 'fail', 'message' => $th->getMessage(), 'medio' => $datosContacto['medioContacto']];
        }
    }

    public static function addContactoClienteExistente($contacto, $empresa_id)
    {
        $con = new ConnDB();
        $conn = $con->getDb();
        try {
            $contacto_id = $contacto['contacto_id'];

            $stmt = $conn->prepare("SELECT telefono, apellido, puesto FROM contactos WHERE id = :contacto_id AND empresa_id = :empresa_id");
            if (!$stmt->execute([':contacto_id' => $contacto_id, ':empresa_id' => $empresa_id])) {
                throw new Exception('Fallo al buscar el contacto');
            }
            $restoDatosContacto = $stmt->fetch(PDO::FETCH_ASSOC);

            $cliente_id = $contacto['cliente_id'];
            $nombres = $contacto['nombre'];
            $celular = $contacto['celular'];
            $email = $contacto['email'];
            $telefono = $restoDatosContacto['telefono'] ? $restoDatosContacto['telefono'] : NULL;
            $apellidos = $restoDatosContacto['apellido'] ? $restoDatosContacto['apellido'] : NULL;
            $puesto = $restoDatosContacto['puesto'] ? $restoDatosContacto['puesto'] : NULL;

            $stmt = $conn->prepare("INSERT INTO dato_contacto_cliente
            (Nombres, Apellidos, Puesto, Telefono, Celular, Email, EmailFacturacion, EmailComplementoPago, EmailAvisosEnvio, EmailPagos, FKCliente)
            VALUES (:nombres, :apellidos, :puesto, :telefono, :celular, :email, 0, 0, 0, 0, :cliente_id)");
            if (!$stmt->execute([':nombres' => $nombres, ':apellidos' => $apellidos, ':puesto' => $puesto, ':telefono' => $telefono, ':celular' => $celular, ':email' => $email, ':cliente_id' => $cliente_id])) {
                throw new Exception('Fallo al insertar nuevo contacto en clientes');
            }
            $idContactoCliente = $conn->lastInsertId();

            $stmt = $conn->prepare("UPDATE contactos SET contacto_cliente_id = :relacion_contacto_id, cliente_id = :cliente_id  WHERE id = :contacto_id");
            if (!$stmt->execute([':relacion_contacto_id' => $idContactoCliente, ':cliente_id' => $cliente_id, ':contacto_id' => $contacto_id])) {
                throw new Exception('Fallo al actualizar el contacto');
            }

            $updatedActividades = self::updateActividadesCRM($contacto_id, $cliente_id);
            if ($updatedActividades['status'] !== 'success') {
                throw new Exception($updatedActividades['message']);
            }

            $otrosContactos = self::addOtrosContactos($contacto_id, $cliente_id);
            if ($otrosContactos['status'] !== 'success') {
                throw new Exception($otrosContactos['message']);
            }

            return ['status' => 'success', 'message' => 'El contacto fue ascendido a cliente'];
        } catch (\Throwable $th) {
            return ['status' => 'fail', 'message' => $th->getMessage()];
        }
    }

    public static function addOtrosContactos($contacto_id, $cliente_id)
    {
        $con = new ConnDB();
        $conn = $con->getDb();
        try {
            $stmt = $conn->prepare("SELECT nombre, email, celular FROM contactos_prospectos WHERE contacto_id = :contacto_id");
            $stmt->execute([':contacto_id' => $contacto_id]);
            $contactos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($contactos) {
                foreach ($contactos as $contacto) {
                    $stmt = $conn->prepare("INSERT INTO dato_contacto_cliente
                    (Nombres, Celular, Email, EmailFacturacion, EmailComplementoPago, EmailAvisosEnvio, EmailPagos, FKCliente)
                    VALUES (:nombres, :celular, :email, 0, 0, 0, 0, :cliente_id)");
                    if (!$stmt->execute([':nombres' => $contacto['nombre'], ':celular' => $contacto['celular'], ':email' => $contacto['email'], ':cliente_id' => $cliente_id])) {
                        throw new Exception('Fallo al insertar contacto otros');
                    }
                }
            }
            return ['status' => 'success'];
        } catch (\Throwable $th) {
            return ['status' => 'fail', 'message' => $th->getMessage()];
        }
    }

    public static function updateActividadesCRM($contacto_id, $cliente_id)
    {
        $con = new ConnDB();
        $conn = $con->getDb();

        try {
            $stmt = $conn->prepare("SELECT id FROM actividades WHERE contacto_id = :contacto_id");
            if (!$stmt->execute([':contacto_id' => $contacto_id])) {
                throw new Exception('Fallo al buscar actividades');
            }
            $actividades = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($actividades as $actividad) {
                $stmt = $conn->prepare("UPDATE actividades SET contacto_id = NULL, cliente_id = :cliente_id WHERE id = :idActividad");
                if (!$stmt->execute([':cliente_id' => $cliente_id, ':idActividad' => $actividad['id']])) {
                    throw new Exception('Fallo al actualizar la actividad');
                }
            }
            return ['status' => 'success'];
        } catch (\Throwable $th) {
            return ['status' => 'fail', 'message' => $th->getMessage()];
        }
    }

    public static function loadRegimen()
    {
        $data = [['placeholder' => true, 'text' => 'Selecciona un régimen']];
        $con = new ConnDB();
        $conn = $con->getDb();
        $query = "SELECT id, CONCAT(clave, ' - ', descripcion) AS regimen FROM claves_regimen_fiscal";
        $rst = $conn->prepare($query);
        $rst->execute();
        $resultado = $rst->fetchAll(PDO::FETCH_ASSOC);
        foreach ($resultado as $row) {
            $data[] = [
                "value" => $row['id'],
                "text" => $row['regimen'],

            ];
        }
        $rst = null;
        return $data;
    }

    public static function loadPaises()
    {
        $data = [['placeholder' => true, 'text' => 'Selecciona un pais']];
        $con = new ConnDB();
        $conn = $con->getDb();
        $query = "SELECT PKPais, pais FROM paises";
        $rst = $conn->prepare($query);
        $rst->execute();
        $resultado = $rst->fetchAll(PDO::FETCH_ASSOC);
        foreach ($resultado as $row) {
            $data[] = [
                "value" => $row['PKPais'],
                "text" => $row['pais'],

            ];
        }
        $rst = null;
        return $data;
    }

    public static function activarContacto(Contacto $data)
    {
        $con = new ConnDB();
        $conn = $con->getDb();
        try {
            $query = "UPDATE contactos SET motivo_declinar = ?, estatus_iniciativa_id = ?, usuario_edito_id = ?, updated_at = NOW() WHERE id = ?";
            $stmt = $conn->prepare($query);
            if (!$stmt->execute([$data->motivo_declinar, $data->estatus_iniciativa_id, $data->usuario_edito_id, $data->id])) {
                throw new Exception('Fallo al activar contacto');
            }
            $res = ['status' => 'success', 'message' => 'El contacto se activo con éxito'];
        } catch (\Throwable $th) {
            $res = ['status' => 'fail', 'message' => $th->getMessage()];
        }
        return $res;
    }
}
