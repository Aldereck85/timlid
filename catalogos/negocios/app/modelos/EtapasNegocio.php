<?php

class ConnDB
{ //Llamado al archivo de la conexión.

    public function getDb()
    {
        include "../../../../include/db-conn.php";
        return $conn;
    }
}

class EtapasNegocio
{

    public $id;
    public $etapa;
    public $orden;
    public $usuario_id;
    public $estatus;

    public static function index()
    {
    }

    public static function etapas($empresa_id)
    {
        $data = array();

        $con = new ConnDB();
        $conn = $con->getDb();

        $stmt = $conn->prepare("SELECT en.id, en.etapa, en.estatus, ee.orden, ee.etapa_id, ee.active
                                FROM etapas_empresa ee
	                            INNER JOIN etapas_negocio en on ee.etapa_id = en.id
                                WHERE ee.empresa_id = :empresa_id ORDER BY ee.orden ASC");
        $stmt->bindParam(':empresa_id', $empresa_id);
        $stmt->execute();
        $resultado = $stmt->fetchAll();


        foreach ($resultado as $row) {
            $data[] = array(
                "id" => $row["id"],
                "etapa" => $row["etapa"],
                "estatus" => $row["estatus"],
                "orden" => $row["orden"],
                "etapa_id" => $row["etapa_id"],
                "active" => $row["active"]
            );
        }

        $stmt = null;
        return $data;
    }

    public static function show()
    {
    }

    public static function store(EtapasNegocio $data, $empresa_id)
    {
        $con = new ConnDB();
        $conn = $con->getDb();

        $sql = "CALL spi_insertar_etapas_negocio(?,?)";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute([$empresa_id, $data->etapa])) {
            $output['success'] = true;
            $output['message'] = 'La etapa se ha creado con éxito';
            $output['res'] = $stmt->fetch(PDO::FETCH_ASSOC);
            return $output;
        }
        $conn = null;
    }

    public static function update(EtapasNegocio $data)
    {
        $con = new ConnDB();
        $conn = $con->getDb();

        $sql = "UPDATE etapas_negocio SET estatus = ? WHERE id = ?";
        $conn->prepare($sql)
            ->execute(
                array(
                    $data->estatus,
                    $data->id
                )
            );
        if ($conn) {
            $output['success'] = true;
            $output['message'] = 'La etapa se ha actualizado con éxito';
            return $output;
        }

        $conn = null;
    }


    public static function getColumns($empresa_id)
    {
        $con = new ConnDB();
        $conn = $con->getDb();
        $data = array();

        $stmt = $conn->prepare("SELECT en.id,en.etapa,ee.orden,ee.etapa_id
                               FROM etapas_negocio en
                               LEFT JOIN etapas_empresa ee ON en.id = ee.etapa_id
                               WHERE ee.empresa_id = :empresa_id or en.id between 1 and 7
                               ORDER BY id");
        $stmt->bindParam(':empresa_id', $empresa_id);
        $stmt->execute();
        $resultado = $stmt->fetchAll();


        foreach ($resultado as $row) {
            $data[] = array(
                "id" => $row["id"],
                "etapa" => $row["etapa"],
                "estatus" => $row["estatus"],
                "etapa_id" => $row["etapa_id"]
            );
        }

        $stmt = null;
        return $data;
    }

    public static function getRows($user_id, $etapa_id, $empresa_id)
    {
        $con = new ConnDB();
        $conn = $con->getDb();
        $data = array();

        $stmt = $conn->prepare("SELECT role_id FROM usuarios WHERE id = :id");
        $stmt->execute([':id' => $user_id]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        $userRol = $res['role_id'];

        $query = "SELECT ns.*, ps.prioridad, CONCAT(e.Nombres, ' ', e.PrimerApellido) AS nombreEmpleado,
        IF(ns.cliente_id, dcc.Nombres, NULL) AS nombreContClient,
        IF(ns.prospecto_id, cp.nombre, NULL) AS nombreContProps,
        c.NombreComercial,
        co.empresa,
        ns.motivo
        FROM negocios ns
        INNER JOIN prioridades ps ON ns.prioridad_id = ps.id
        LEFT JOIN empleados e ON ns.empleado_id = e.PKEmpleado
        LEFT JOIN contactos_prospectos cp ON ns.contacto_id = cp.id
        LEFT JOIN dato_contacto_cliente dcc ON ns.contacto_id = dcc.PKContactoCliente
        LEFT JOIN clientes c ON ns.cliente_id = c.PKCliente
        LEFT JOIN contactos co ON ns.prospecto_id = co.id
        WHERE ns.etapa_empresa_usuario_id = :etapa_id AND ns.usuario_id = :id";
        $values = [':etapa_id' => $etapa_id, ':id' => $user_id];

        if ($userRol === 2 || $userRol === 12) {
            $query = "SELECT ns.*, ps.prioridad, CONCAT(e.Nombres, ' ', e.PrimerApellido) AS nombreEmpleado,
            IF(ns.cliente_id, dcc.Nombres, NULL) AS nombreContClient,
            IF(ns.prospecto_id, cp.nombre, NULL) AS nombreContProps,
            c.NombreComercial,
            co.empresa,
            ns.motivo
            FROM negocios ns
            INNER JOIN prioridades ps ON ns.prioridad_id = ps.id
            LEFT JOIN empleados e ON ns.empleado_id = e.PKEmpleado
            LEFT JOIN contactos_prospectos cp ON ns.contacto_id = cp.id
            LEFT JOIN dato_contacto_cliente dcc ON ns.contacto_id = dcc.PKContactoCliente
            LEFT JOIN clientes c ON ns.cliente_id = c.PKCliente
            LEFT JOIN contactos co ON ns.prospecto_id = co.id
            WHERE ns.etapa_empresa_usuario_id = :etapa_id AND e.empresa_id = :empresa_id";
            $values = [':etapa_id' => $etapa_id, ':empresa_id' => $empresa_id];
        }
        $stmt = $conn->prepare($query);
        $stmt->execute($values);
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($resultado as $row) {
            $data[] = array(
                "id" => $row["id"],
                "nombre" => $row["nombre"],
                "valor" => $row["valor"],
                "prioridad" => $row["prioridad"],
                "etapa_id" => $row["etapa_empresa_usuario_id"],
                "nombre_empleado" => $row["nombreEmpleado"],
                "nombreContClient" => $row["nombreContClient"],
                "nombreContProps" => $row["nombreContProps"],
                "descripcion" => $row["descripcion"],
                "nombreComercial" => $row["NombreComercial"],
                "empresa" => $row["empresa"],
                "motivo" => $row["motivo"],
            );
        }

        $stmt = null;
        return $data;
    }

    public static function getRowsDates($fecha_inicio, $fecha_fin)
    {
        $con = new ConnDB();
        $conn = $con->getDb();
        $data = array();

        $stmt = $conn->prepare("SELECT * FROM negocios WHERE created_at >= :inicio AND created_at <= :fin");
        $stmt->bindParam(':inicio', $fecha_inicio);
        $stmt->bindParam(':fin', $fecha_fin);
        $stmt->execute();
        $resultado = $stmt->fetchAll();

        foreach ($resultado as $row) {
            $data[] = array(
                "id" => $row["id"],
                "empresa" => $row["empresa"],
                "nombre" => $row["nombre"],
                "valor" => $row["valor"],
                "cliente_id" => $row["cliente_id"],
                "empleado_id" => $row["empleado_id"],
                "etapa_usuario_id" => $row["empleado_id"],
                "prioridad_id" => $row["prioridad_id"],
                "usuario_id" => $row["usuario_id"],
                "etapa_id" => $row["etapa_empresa_usuario_id"],
            );
        }

        $stmt = null;
        return $data;
    }

    public static function getRowsEmpleado($usuario_id, $empleado_id)
    {
        $con = new ConnDB();
        $conn = $con->getDb();
        $data = array();
        $sql = $empleado_id === 'todos' ? 'SELECT ns.*, ps.prioridad, CONCAT(e.Nombres, " ", e.PrimerApellido) AS nombreEmpleado
        FROM negocios ns 
        INNER JOIN prioridades ps ON ns.prioridad_id = ps.id 
        LEFT JOIN empleados e ON ns.empleado_id = e.PKEmpleado
        WHERE usuario_id = :usuario_id' : 'SELECT ns.*, ps.prioridad 
        FROM negocios ns 
        INNER JOIN prioridades ps ON ns.prioridad_id = ps.id 
        WHERE usuario_id = :usuario_id AND empleado_id = :empleado_id';
        $values = $empleado_id === 'todos' ? [':usuario_id' => $usuario_id] : [':usuario_id' => $usuario_id, ':empleado_id' => $empleado_id];
        $stmt = $conn->prepare($sql);
        $stmt->execute($values);
        $resultado = $stmt->fetchAll();

        foreach ($resultado as $row) {
            $data[] = array(
                "id" => $row["id"],
                "empresa" => $row["empresa"],
                "nombre" => $row["nombre"],
                "valor" => $row["valor"],
                "cliente_id" => $row["cliente_id"],
                "empleado_id" => $row["empleado_id"],
                "etapa_usuario_id" => $row["empleado_id"],
                "prioridad_id" => $row["prioridad_id"],
                "usuario_id" => $row["usuario_id"],
                "etapa_id" => $row["etapa_empresa_usuario_id"],
            );
        }

        $stmt = null;
        return $data;
    }

    public static function updateSiguiente(EtapasNegocio $data)
    {
        $con = new ConnDB();
        $conn = $con->getDb();

        $sql = "UPDATE negocios SET etapa_empresa_usuario_id = ? WHERE id = ?";
        $conn->prepare($sql)
            ->execute(
                array(
                    $data->etapa_empresa_usuario_id,
                    $data->id
                )
            );
        if ($conn) {
            $output['success'] = true;
            $output['message'] = 'La etapa se ha actualizado con éxito';
            return $output;
        }
        $conn = null;
    }

    public static function updateAnterior(EtapasNegocio $data)
    {
        $con = new ConnDB();
        $conn = $con->getDb();

        try {
            $sql = "UPDATE negocios SET etapa_empresa_usuario_id = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            if (!$stmt->execute([$data->etapa_empresa_usuario_id, $data->id])) {
                throw new Exception('Algo salio mal');
            }
            $output['success'] = true;
            $output['message'] = 'La etapa se ha actualizado con éxito';
            return $output;
        } catch (\Throwable $th) {
            $output['success'] = false;
            $output['message'] = $th->getMessage();
            return $output;
        }
        $conn = null;
    }

    public static function loadPropietarios($empresa_id)
    {
        $data = [];
        $con = new ConnDB();
        $conn = $con->getDb();

        $query = "SELECT em.PKEmpleado, concat(em.Nombres,' ',em.PrimerApellido,' ',em.SegundoApellido) AS nombre
               FROM relacion_tipo_empleado re 
               INNER JOIN empleados em ON re.empleado_id = em.PKEmpleado
               WHERE re.tipo_empleado_id = 1
               AND em.estatus = 1
               AND em.empresa_id = :id
               ORDER BY nombre";
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

    public static function updateColumn($orden, $empresa_id)
    {

        $con = new ConnDB();
        $conn = $con->getDb();
        try {
            for ($i = 0; $i < count($orden); $i++) {
                $sql = "UPDATE etapas_empresa SET orden = :orden WHERE etapa_id = :id AND empresa_id = :empresa";
                $stmt = $conn->prepare($sql);
                if (!$stmt->execute([':orden' => $i + 1, ':id' => $orden[$i], ':empresa' => $empresa_id])) {
                    throw new Exception("No se pudo actualizar $orden[$i]");
                }
            }
            return $orden;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public static function cargarContactos($user, $empresa_id)
    {

        $data = [];
        $con = new ConnDB();
        $conn = $con->getDb();

        $query = "SELECT cs.id,cs.nombre,cs.apellido,cs.empresa
        FROM contactos cs
        INNER JOIN usuarios us ON cs.usuario_creo_id = us.id
        INNER JOIN empresas es ON us.empresa_id = es.PKEmpresa
        WHERE us.id = :usuario_id AND es.PKEmpresa = :empresa_id";
        $rst = $conn->prepare($query);
        $rst->bindParam(':usuario_id', $user);
        $rst->bindParam(':empresa_id', $empresa_id);
        $rst->execute();
        $resultado = $rst->fetchAll();
        foreach ($resultado as $row) {
            $data[] = array(
                "id" => $row['id'],
                "nombre" => $row['nombre'] . ' ' . $row['apellido'] . ' - ' . $row['empresa']

            );
        }
        $rst = null;
        return $data;
    }

    public static function cargarContactosTipo($tipo, $id)
    {
        $placeholder = [['placeholder' => true, 'text' => 'Selecciona un contacto']];
        $con = new ConnDB();
        $conn = $con->getDb();

        $query = '';
        $values = [':ID' => $id];
        if ($tipo === 'cliente') {
            $query = "SELECT PKContactoCliente AS value, Nombres AS text FROM dato_contacto_cliente WHERE FKCliente = :ID";
            $rst = $conn->prepare($query);
            $rst->execute($values);
        } else if ($tipo === 'prospecto') {
            $query = "SELECT value, text FROM (SELECT cp.id AS value, cp.nombre AS text FROM contactos_prospectos cp WHERE cp.contacto_id = :ID UNION SELECT c.id AS value, c.empresa as text FROM contactos c WHERE c.id = :ID2) AS tabla";
            $rst = $conn->prepare($query);
            $rst->execute(array(':ID' => $id, ':ID2' => $id));
        }
        $resultado = $rst->fetchAll(PDO::FETCH_ASSOC);
        $data = array_merge($placeholder, $resultado);
        return $data;
    }

    public static function cargarContactosTipoEditar($tipo, $id, $contacto)
    {
        $placeholder = [['placeholder' => true, 'text' => 'Selecciona un contacto']];
        $con = new ConnDB();
        $conn = $con->getDb();

        $query = '';
        $values = [':ID' => $id];
        if ($tipo === 'cliente') {
            $query = "SELECT PKContactoCliente AS value, Nombres AS text FROM dato_contacto_cliente WHERE FKCliente = :ID";
            $rst = $conn->prepare($query);
            $rst->execute($values);
        } else if ($tipo === 'prospecto') {
            $query = "SELECT value, text FROM (SELECT cp.id AS value, cp.nombre AS text FROM contactos_prospectos cp WHERE cp.contacto_id = :ID UNION SELECT c.id AS value, c.empresa as text FROM contactos c WHERE c.id = :ID2) AS tabla";
            $rst = $conn->prepare($query);
            $rst->execute(array(':ID' => $id, ':ID2' => $id));
        }
        $resultado = $rst->fetchAll(PDO::FETCH_ASSOC);
        foreach($resultado as $res){
            if($res['value'] == $contacto){
                $data = array_merge($placeholder, [['value' => $res['value'], 'text' => $res['text'], 'selected' => true]]);
            }else{
                $data = array_merge($placeholder, [['value' => $res['value'], 'text' => $res['text']]]);
            }
        }
        return $data;
    }

    public static function formatMoney($number, $cents = 2)
    { // cents: 0=never, 1=if needed, 2=always
        if (is_numeric($number)) { // a number
            if (!$number) { // zero
                $money = ($cents == 2 ? '0.00' : '0'); // output zero
            } else { // value
                if (floor($number) == $number) { // whole number
                    $money = number_format($number, ($cents == 2 ? 2 : 0)); // format
                } else { // cents
                    $money = number_format(round($number, 2), ($cents == 0 ? 0 : 2)); // format
                } // integer or decimal
            } // value
            return '$' . $money;
        } // numeric
    }

    public static function cierreGanadoPerdido($negocio, $ganadoPerdido, $motivo)
    {
        $con = new ConnDB();
        $conn = $con->getDb();

        try {
            $sql = "UPDATE negocios SET etapa_empresa_usuario_id = :ganadoPerdido, motivo = :motivo WHERE id = :id";
            $stmt = $conn->prepare($sql);
            if (!$stmt->execute([':ganadoPerdido' => $ganadoPerdido, ':motivo' => $motivo, ':id' => $negocio])) {
                throw new Exception('Algo salio mal');
            }
            $output['success'] = true;
            $output['message'] = 'La etapa se ha actualizado con éxito';
            return $output;
        } catch (\Throwable $th) {
            $output['success'] = false;
            $output['message'] = $th->getMessage();
            return $output;
        }
        $conn = null;
    }

    public static function activarDesactivarEtapa($etapa, $activarDesactivar, $empresa)
    {
        $con = new ConnDB();
        $conn = $con->getDb();

        try {
            $sql = "UPDATE etapas_empresa SET active = :active WHERE etapa_id = :etapa AND empresa_id = :empresa";
            $stmt = $conn->prepare($sql);
            if (!$stmt->execute([':active' => $activarDesactivar, ':etapa' => $etapa, ':empresa' => $empresa])) {
                throw new Exception('Algo salio mal');
            }
            $output['success'] = true;
            $output['message'] = 'La etapa se ha actualizado con éxito';
            return $output;
        } catch (\Throwable $th) {
            $output['success'] = false;
            $output['message'] = $th->getMessage();
            return $output;
        }
        $conn = null;
    }
}
