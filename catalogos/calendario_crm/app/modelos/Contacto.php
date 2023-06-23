<?php

//include '../../../../include/db-conn.php';


class conectar
{ //Llamado al archivo de la conexión.

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
    public $nombre;
    public $empresa;
    public $apellido;
    public $puesto;
    public $email;
    public $telefono;
    public $celular;
    public $estatus_iniciativa_id;
    public $empleado_id;
    public $estado_id;
    public $motivo_declinar;
    public $medio_contacto_campania_id;
    public $usuario_creo_id;
    public $usuario_edito_id;
    public $funcion;
    public $contacto_empresa_id;

    public static function index($id, $estatus_id, $empresa_id)
    {

        $data = array();

        $con = new conectar();
        $conn = $con->getDb();

        $stmt = $conn->prepare("SELECT cs.id,cs.contacto_empresa_id,cs.empresa,cs.nombre,cs.apellido,cs.email,cs.estatus_iniciativa_id,
			cs.cliente_id,cs.empleado_id,mcc.nombre as campania,em.Nombres,em.PrimerApellido,em.SegundoApellido
			FROM contactos cs 
			INNER JOIN medio_contacto_campania mcc ON cs.medio_contacto_campania_id = mcc.id 
			INNER JOIN empleados em ON  cs.empleado_id = em.PKEmpleado
            INNER JOIN usuarios us ON cs.usuario_creo_id = us.id
            INNER JOIN empresas es ON us.empresa_id = es.PKEmpresa
			WHERE cs.usuario_creo_id = :id AND cs.estatus_iniciativa_id = :estatus_id AND es.PKEmpresa = :empresa_id
            ORDER BY cs.empresa ASC");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam('estatus_id', $estatus_id);
        $stmt->bindParam(':empresa_id', $empresa_id);
        $stmt->execute();
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

            );
        }

        $stmt = null;
        return $data;
    }


    public static function show($empresa_id, $id)
    {
        $output = [];
        $con = new conectar();
        $conn = $con->getDb();

        $stmt = $conn->prepare("SELECT cs.*,ef.Estado
			FROM contactos cs
            INNER JOIN usuarios us ON cs.usuario_creo_id = us.id
            INNER JOIN empresas es ON us.empresa_id = es.PKEmpresa
            INNER JOIN estados_federativos ef ON cs.estado_id = ef.PKEstado
			WHERE cs.contacto_empresa_id = :id
            AND es.PKEmpresa = :empresa_id ");

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':empresa_id', $empresa_id);
        $stmt->execute();
        return $stmt->fetch();
    }


    public static function store(Contacto $data, $empresa_id)
    {
        $con = new conectar();
        $conn = $con->getDb();

        $validate_id_contact = self::validateIdContact($empresa_id);
        $contacto_empresa_id = $validate_id_contact[0]->contacto_empresa_id;
        $contacto_empresa = $contacto_empresa_id + 1;


        $sql = "INSERT INTO contactos(empresa,nombre,apellido,puesto,email,telefono,celular,
		estatus_iniciativa_id,empleado_id,estado_id,medio_contacto_campania_id,usuario_creo_id,created_at,contacto_empresa_id) 
		VALUES(:empresa,:nombre,:apellido,:puesto,:email,:telefono,:celular,:estatus,
		       :empleado_id,:estado_id,:medio_contacto_id,:usuario_creo_id,NOW(),:contacto_empresa_id)";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':empresa', $data->empresa);
        $stmt->bindParam(':nombre', $data->nombre);
        $stmt->bindParam(':apellido', $data->apellido);
        $stmt->bindParam(':puesto', $data->puesto);
        $stmt->bindParam(':email', $data->email);
        $stmt->bindParam(':telefono', $data->telefono);
        $stmt->bindParam(':celular', $data->celular);
        $stmt->bindParam(':estatus', $data->estatus_iniciativa_id);
        $stmt->bindParam(':empleado_id', $data->empleado_id);
        $stmt->bindParam(':estado_id', $data->estado_id);
        $stmt->bindParam(':medio_contacto_id', $data->medio_contacto_campania_id);
        $stmt->bindParam(':usuario_creo_id', $data->usuario_creo_id);
        $stmt->bindParam(':contacto_empresa_id', $contacto_empresa);
        $stmt->execute();

        if ($conn) {

            $output['success'] = true;
            $output['message'] = 'El contacto ' . $data->nombre . ' se ha registrado con éxito';
        }
        $conn = null;
        return $output;
    }

    public static function validateIdContact($empresa_id)
    {
        $con = new conectar();
        $conn = $con->getDb();

        $sql = "SELECT MAX(cs.contacto_empresa_id) as contacto_empresa_id, es.PKEmpresa
                FROM  contactos cs 
                inner join usuarios us on cs.usuario_creo_id = us.id
                inner join empresas es on us.empresa_id = es.PKEmpresa
                WHERE us.empresa_id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $empresa_id);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $resultado;
    }

    public static function update(Contacto $data)
    {
        $con = new conectar();
        $conn = $con->getDb();


        $sql = "UPDATE contactos SET 
		/*empresa = ?,*/ nombre = ?,
		apellido = ?, puesto = ?,
		email = ?, telefono = ?,
		celular = ?, estatus_iniciativa_id = ?,
		empleado_id = ?, estado_id = ?,
		medio_contacto_campania_id = ?, usuario_edito_id = ?,
		updated_at = NOW()
		WHERE id = ? AND contacto_empresa_id = ?";
        $conn->prepare($sql)->execute(
            array(

                //$data->empresa,
                $data->nombre,
                $data->apellido,
                $data->puesto,
                $data->email,
                $data->telefono,
                $data->celular,
                $data->estatus_iniciativa_id,
                $data->empleado_id,
                $data->estado_id,
                $data->medio_contacto_campania_id,
                $data->usuario_edito_id,
                $data->id,
                $data->contacto_empresa_id,

            )
        );

        if ($conn) {

            $output['success'] = true;
            $output['message'] = 'El contacto ' . $data->nombre . ' se ha actualizado con éxito';
        }
        $conn = null;
        return $output;
    }

    public static function destroy(Contacto $data)
    {


        $con = new conectar();
        $conn = $con->getDb();
        $row = [];

        $stmt = $conn->prepare("SELECT cliente_id FROM contactos WHERE id = :id");
        $stmt->bindParam(':id', $data->id);
        $stmt->execute();
        $row = $stmt->fetch();

        $client_id_delete = $row['cliente_id'];

        $sql = "UPDATE contactos SET 
		motivo_declinar = ?, 
		estatus_iniciativa_id = ?,
		usuario_edito_id = ?,
		updated_at = NOW()
		WHERE id = ?";

        $stmt = $conn->prepare($sql)->execute(
            array(
                $data->motivo_declinar,
                $data->estatus_iniciativa_id,
                $data->usuario_edito_id,
                $data->id
            )
        );

        if ($client_id_delete != null) {
            $status = 0;
            self::destroyClient($status, $client_id_delete);
        }

        $sql = "UPDATE contactos SET usuario_edito_id = :usuario_id WHERE  id = :id";
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':usuario_id', $data->usuario_edito_id, PDO::PARAM_INT);
        $stmt->bindParam(':id', $data->id, PDO::PARAM_INT);


        if ($stmt->execute()) {
            $output['success'] = true;
            $output['message'] = 'EL contacto se ha eliminado con éxito';
        }

        $stmt = null;
        return $output;
    }


    public static function destroyClient($status, $client_id_delete)
    {

        $con = new conectar();
        $conn = $con->getDb();

        $sql = "UPDATE clientes SET estatus = :estatus WHERE PKCliente = :id";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':estatus', $status, PDO::PARAM_INT);
        $stmt->bindParam(':id', $client_id_delete, PDO::PARAM_INT);
        $stmt->execute();
        $stmt = null;
    }

    public static function loadContactos($empresa_id)
    {

        $data = [];
        $con = new conectar();
        $conn = $con->getDb();

        $query = "select em.PKEmpleado,concat(em.Nombres,' ',em.PrimerApellido,' ',em.SegundoApellido) as nombre
               from relacion_tipo_empleado re 
               inner join empleados em on re.empleado_id = em.PKEmpleado
               where tipo_empleado_id = 1
               and em.empresa_id = :id
               order by nombre";
        $rst = $conn->prepare($query);
        $rst->bindParam(':id', $empresa_id);
        $rst->execute();
        $resultado = $rst->fetchAll();
        foreach ($resultado as $row) {
            $data[] = array(
                "id" => $row['PKEmpleado'],
                "nombre" => $row['nombre'],

            );
        }
        $rst = null;
        return $data;
    }

    public static function loadEstados()
    {

        $data = [];
        $con = new conectar();
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
        $con = new conectar();
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
}
