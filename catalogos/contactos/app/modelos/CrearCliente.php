<?php


class Conexion1
{
    public function getDb()
    {
        include "../../../../include/db-conn.php";
        return $conn;
    }
}


class CrearCliente
{
    public $contacto_id;
    public $PKCliente;
    public $NombreComercial;
    public $Telefono;
    public $Email;
    public $Monto_credito;
    public $Dias_credito;
    public $razon_social;
    public $rfc;
    public $Calle;
    public $Numero_exterior;
    public $Numero_interior;
    public $Municipio;
    public $Colonia;
    public $codigo_postal;
    public $estatus_prospecto_id;
    public $pais_id;
    public $estado_id;
    public $empresa_id;
    public $usuario_creacion_id;
    public $usuario_edicion_id;
    public $created_at;
    public $updated_at;
    public $estatus;
    public $empleado_id;
    public $medio_contacto_id;
    public $funcion;
    public $EmailFacturacion;
    public $EmailComplementoPago;
    public $EmailAvisosEnvio;
    public $EmailPagos;
    public $PKContacto;
    public $Nombres;
    public $Apellidos;
    public $Puesto;
    public $Celular;
    public $PKUsuario;

    public static function store(CrearCliente $data)
    {
        $row = array();
        $con = new Conexion1();
        $conn = $con->getDb();
        $sql = "CALL spi_Cliente_AgregarGeneral(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $stmt = $conn->prepare($sql);
        $status = $stmt->execute(

            array(
                $data->NombreComercial,
                $data->medio_contacto_id,
                $data->Telefono,
                $data->Email,
                $data->Monto_credito,
                $data->Dias_credito,
                $data->estatus = 1,
                $data->empleado_id,
                $data->razon_social,
                $data->rfc,
                $data->Calle,
                $data->Numero_exterior,
                $data->Numero_interior,
                $data->Colonia,
                $data->Municipio,
                $data->pais_id,
                $data->estado_id,
                $data->codigo_postal,
                $data->PKRazonSocial = 0,
                $data->PKCliente = 0,
                $data->usuario_creacion_id,
                $data->empresa_id,
            )
        );

        $cliente_id = $stmt->fetch()['0'];

        if ($stmt && $cliente_id) {
            $output = self::updateContacto($data, $cliente_id);
        }
        $stmt = null;
        return $output;
    }

    public static function storeContacto(CrearCliente $data)
    {

        $con = new Conexion1();
        $conn = $con->getDb();

        $sql = "CALL spi_Clientes_AgregarContacto(?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $conn->prepare($sql)
            ->execute(
                array(
                    $data->Nombres,
                    $data->Apellidos,
                    $data->Puesto,
                    $data->Telefono,
                    $data->Celular,
                    $data->Email,
                    $data->PKCliente,
                    $data->PKContacto = 0,
                    $data->EmailFacturacion,
                    $data->EmailComplementoPago,
                    $data->EmailAvisosEnvio,
                    $data->EmailPagos,
                    $data->PKUsuario,
                )
            );
        if ($conn) {
            $output['success'] = true;
            $output['message'] = 'El contacto ' . $data->Nombres . ' ' . $data->Apellidos . ' se ha registrado con éxito';
        } else {
            $output['error'] = true;
            $output['message'] = 'Ha ocurrido un error al registrar el contacto: ' . $data->Nombres . ' ' . $data->Apellidos;
        }
        $conn = null;
        return $output;
    }

    public static function updateContacto($data, $cliente_id)
    {
        $con = new Conexion1();
        $conn = $con->getDb();
        $output = array();

        $sql = "UPDATE contactos SET cliente_id = ? WHERE id = ?";

        $conn->prepare($sql)->execute([$cliente_id, $data->contacto_id]);

        $output['success'] = true;
        $output['message'] = 'El cliente ' . $data->NombreComercial . ' se ha registrado con éxito';
        $output['cliente_id'] = $cliente_id;
        $output['nombre'] = $data->NombreComercial;
        return $output;
    }

    public static function loadSelectSeller($empresa_id)
    {

        $con = new Conexion1();
        $conn = $con->getDb();

        $query = "SELECT em.PKEmpleado AS value, concat(em.Nombres,' ',em.PrimerApellido,' ',em.SegundoApellido) AS text
        FROM usuarios us
        INNER JOIN empleados em ON us.id = em.PKEmpleado
        LEFT JOIN relacion_tipo_empleado re ON us.id = re.empleado_id
        WHERE (re.tipo_empleado_id = 1 OR us.role_id = 2 OR us.role_id = 12) AND us.empresa_id = :id
        GROUP BY PKEmpleado
        ORDER BY nombre ASC";

        $rst = $conn->prepare($query);
        $rst->bindParam(':id', $empresa_id);
        $rst->execute();

        $propietarios = $rst->fetchAll(PDO::FETCH_OBJ);
        return $propietarios;
    }

    public static function loadState()
    {
        $data = [['placeholder' => true, 'text' => 'Selecciona un régimen']];
        $con = new Conexion1();
        $conn = $con->getDb();
        $data = array();

        $query = 'SELECT e.PKEstado as estado_id, e.Estado as estado
		from estados_federativos e
        WHERE FKPais = 146
		order by e.Estado asc ';
        $rst = $conn->prepare($query);
        $rst->execute();
        $estados = $rst->fetchAll(PDO::FETCH_OBJ);

        foreach ($estados as $row) {
            $data[] = array(
                "value" => $row->estado_id,
                "text" => $row->estado
            );
        }
        return $data;
    }

    public static function loadClientes($empresa_id)
    {
        $con = new Conexion1();
        $conn = $con->getDb();

        $query = "SELECT PKCliente AS value, CONCAT(NombreComercial, ' - ', rfc) AS text FROM clientes WHERE empresa_id = :empresa_id AND estatus = 1";

        $rst = $conn->prepare($query);
        $rst->bindParam(':empresa_id', $empresa_id);
        $rst->execute();

        $clientes = $rst->fetchAll(PDO::FETCH_OBJ);
        return $clientes;
    }
}
