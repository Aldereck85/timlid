<?php
session_start();
date_default_timezone_set('America/Mexico_City');
$user = $_SESSION["Usuario"];

class conectar
{ //Llamado al archivo de la conexión.

    public function getDb()
    {
        include "../../../include/db-conn.php";
        return $conn;
    }
}

class get_data
{
    //JAVIER RAMIREZ
    /////////////////////////TABLAS//////////////////////////////
    
    public function getVehiculosTable($isPermissionsEdit,$isPermissionsDelete)
    {
        $con = new conectar();
        $db = $con->getDb();

        $table = "";
        $PKEmpresa = $_SESSION["IDEmpresa"];

        $stmt = $db->prepare('call spc_Tabla_Vehiculos_Consulta(?)');
        $stmt->execute(array($PKEmpresa));
        $array = $stmt->fetchAll();

        $acciones = '';

        foreach ($array as $r) {

            $Id = $r['id'];
            $linea = $r['linea'];
            $marca = $r['marca'];
            $serie = $r['serie'];
            $placas = $r['placas'];
            $color = $r['color'];
            $modelo = $r['modelo'];
            $puertas = $r['puertas'];
            $cilindros = $r['cilindros'];
            $odometro = $r['odometro'];
            $kilometraje = $r['kilometraje'];
            $motor = $r['motor'];
            $combustible = $r['combustible'];
            $transmision = $r['transmision'];
            $estatus = $r['estatus'];
                
            if ($isPermissionsEdit == '1'){
                $acciones = '<i class=\"fas fa-edit pointer\" onclick=\"obtenerEditarVehiculo(\''.$Id.'\');\"></i>';
            }

            if ($isPermissionsDelete == '1'){
                $acciones = $acciones . '<i class=\"fas fa-trash-alt pointer\" onclick=\"obtenerDatosEliminarVehiculo(\''.$Id.'\');\"></i>';
            }

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';

            $table .= '{"Id":"' . $etiquetaI . $Id . $etiquetaF . '",
                  "Estatus":"' . $etiquetaI . $estatus . $etiquetaF . '",
                  "Linea":"' . $etiquetaI . $linea . $etiquetaF . '",
                  "Marca":"' . $etiquetaI . $marca . $etiquetaF . '",
                  "Serie":"' . $etiquetaI . $serie . $etiquetaF . '",
                  "Placas":"' . $etiquetaI . $placas . $etiquetaF . '",
                  "Color":"' . $etiquetaI . $color . $etiquetaF . '",
                  "Modelo":"' . $etiquetaI . $modelo . $etiquetaF . '",
                  "Puertas":"' . $etiquetaI . $puertas . $etiquetaF . '",
                  "Cilindros":"' . $etiquetaI . $cilindros . $etiquetaF . '",
                  "Odometro":"' . $etiquetaI . $odometro . $etiquetaF . '",
                  "Kilometraje":"' . $etiquetaI . $kilometraje . $etiquetaF . '",
                  "Motor":"' . $etiquetaI . $motor . $etiquetaF . '",
                  "Combustible":"' . $etiquetaI . $combustible . $etiquetaF . '",
                  "Transmision":"' . $etiquetaI . $transmision . $etiquetaF . '",
                  "Acciones":"' . $etiquetaI . $acciones . $etiquetaF . '"},';

        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getCargasCombustibleVehiculoTable($data, $permissionEdit, $permissionDelete)
    {
        $con = new conectar();
        $db = $con->getDb();
        $table = "";

        $query = sprintf('call spc_Tabla_Vehiculo_CargasCombustible_Consulta(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($data));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id = $r['id'];
            $fechaCarga = $r['fechaCarga'];
            $cantidad = $r['cantidad'];
            $unidadMedidaLQ = $r['unidadMedida'];
            $costoUnitario = $r['costoUnitario'];
            $moneda = $r['moneda'];
            $odometro = $r['odometro'];
            $isTanqueLleno = $r['tanqueLleno'];
            $responsable = $r['responsable'];


            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';

            $acciones = '';

            if($permissionEdit == '1'){
            $acciones .= '<i class=\"fas fa-edit\" data-toggle=\"modal\" data-target=\"#editar_CargaCombustible\" onclick=\"modalDatosEditCargaCombustible(\''.$id.'\');\"></i>';
            }

            $table .= '{"Id":"' . $etiquetaI . $id . $etiquetaF . '",
                "FechaCarga":"' . $etiquetaI . $fechaCarga . $etiquetaF . '",
                "Cantidad":"' . $etiquetaI . $cantidad . $etiquetaF . '",
                "UnidadMedidaLq":"' . $etiquetaI . $unidadMedidaLQ . $etiquetaF . '",
                "CostoUnitario":"' . $etiquetaI . $costoUnitario . $etiquetaF . '",
                "Moneda":"' . $etiquetaI . $moneda . $etiquetaF . '",
                "Odometro":"' . $etiquetaI . $odometro . $etiquetaF . '",
                "TanqueLleno":"' . $etiquetaI . $isTanqueLleno . $etiquetaF . '",
                "Responsable":"' . $etiquetaI . $responsable . $etiquetaF . '",
                "Acciones":"' . $etiquetaI . $acciones . $etiquetaF . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getServiciosVehiculoTable($data, $permissionEdit, $permissionDelete)
    {
        $con = new conectar();
        $db = $con->getDb();
        $table = "";

        $query = sprintf('call spc_Tabla_Vehiculo_Servicios_Consulta(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($data));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id = $r['id'];
            $servicio = $r['servicio'];
            $descripcion = $r['descripcion'];
            $lugar = $r['lugar'];
            $tipoServicio = $r['tipoServicio'];
            $costo = $r['costo'];
            $moneda = $r['moneda'];
            $archivo=$r['archivo'];
            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';

            $acciones = '<i class=\"fas fa-file-pdf\" style=\"cursor:pointer\" width=\"30px\" height=\"30px\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"Descargar PDF\" onclick=\"descargarPDFServicio(\''.$archivo.'\')\"></i> ';
            if($permissionEdit == '1'){
            $acciones .= '<i class=\"fas fa-edit\" data-toggle=\"modal\" data-target=\"#editar_Servicios\" onclick=\"modalDatosEditServicios(\''.$id.'\');\"></i>';
            }

            $table .= '{"Id":"' . $etiquetaI . $id . $etiquetaF . '",
                "Servicio":"' . $etiquetaI . $servicio . $etiquetaF . '",
                "Descripcion":"' . $etiquetaI . $descripcion . $etiquetaF . '",
                "Lugar":"' . $etiquetaI . $lugar . $etiquetaF . '",
                "TipoServicio":"' . $etiquetaI . $tipoServicio . $etiquetaF . '",
                "Costo":"' . $etiquetaI . $costo . $etiquetaF . '",
                "Moneda":"' . $etiquetaI . $moneda . $etiquetaF . '",
                "Acciones":"' . $etiquetaI . $acciones . $etiquetaF . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getPrestamosVehiculoTable($data, $permissionEdit, $permissionDelete)
    {
        $con = new conectar();
        $db = $con->getDb();
        $table = "";

        $query = sprintf('call spc_Tabla_Vehiculo_Prestamos_Consulta(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($data));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id = $r['id'];
            $empleado = $r['empleado'];
            $motivoPrestamo = $r['motivoPrestamo'];
            $fechaPrestamo = $r['fechaPrestamo'];
            $estatus=$r['estatus'];

            //verifica el estatus
            if($estatus===1){
                $estatus='<span class=\"left-dot green-dot\">Abierto</span>';
            }else{
                $estatus='<span class=\"left-dot gray-dot\">Cerrado</span>';
            }

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';

            $acciones = '<i class=\"fas fa-file-pdf\" style=\"cursor:pointer\" width=\"30px\" height=\"30px\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Descargar PDF\" onclick=\"descargarPDFPrestamo(\''.$id.'\')\"></i> ';
            if($permissionEdit == '1'){
            $acciones .= '<i class=\"fas fa-edit\" data-toggle=\"modal\" onclick=\"modalDatosEditPrestamos(\''.$id.'\');\"></i>';
            }

            $table .= '{"Id":"' . $etiquetaI . $id . $etiquetaF . '",
                "Empleado":"' . $etiquetaI . $empleado . $etiquetaF . '",
                "Motivo":"' . $etiquetaI . $motivoPrestamo . $etiquetaF . '",
                "Estatus":"' . $etiquetaI . $estatus . $etiquetaF . '",
                "FechaPrestamo":"' . $etiquetaI . $fechaPrestamo . $etiquetaF . '",
                "Acciones":"' . $etiquetaI . $acciones . $etiquetaF . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getPrestamosVehiculoTableFiltered($pkVehiculo, $empleado_id, $fromDate, $toDate, $permissionEdit, $permissionDelete)
    {
        $con = new conectar();
        $db = $con->getDb();
        $table = "";

        if($empleado_id == "f" || $empleado_id==null){
            $empleado_id=0;
        }

        if($fromDate=="" || $fromDate==null){
            $fromDate='0000-00-00';
        }

        if($toDate=="" || $toDate==null){
            $toDate='0000-00-00';
        }

        $query = sprintf('call spc_Tabla_Vehiculo_Prestamos_Consulta_filtered(?,?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkVehiculo, $empleado_id, $fromDate, $toDate));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id = $r['id'];
            $empleado = $r['empleado'];
            $motivoPrestamo = $r['motivoPrestamo'];
            $fechaPrestamo = $r['fechaPrestamo'];
            $estatus=$r['estatus'];

            //verifica el estatus
            if($estatus===1){
                $estatus='<span class=\"left-dot green-dot\">Abierto</span>';
            }else{
                $estatus='<span class=\"left-dot gray-dot\">Cerrado</span>';
            }

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';

            $acciones = '';

            if($permissionEdit == '1'){
            $acciones .= '<i class=\"fas fa-edit\" data-toggle=\"modal\" data-target=\"#editar_Prestamos\" onclick=\"modalDatosEditPrestamos(\''.$id.'\');\"></i>';
            }

            $table .= '{"Id":"' . $etiquetaI . $id . $etiquetaF . '",
                "Empleado":"' . $etiquetaI . $empleado . $etiquetaF . '",
                "Motivo":"' . $etiquetaI . $motivoPrestamo . $etiquetaF . '",
                "Estatus":"' . $etiquetaI . $estatus . $etiquetaF . '",
                "FechaPrestamo":"' . $etiquetaI . $fechaPrestamo . $etiquetaF . '",
                "Acciones":"' . $etiquetaI . $acciones . $etiquetaF . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getGuiasTable($isPermissionsEdit,$isPermissionsDelete)
    {
        $con = new conectar();
        $db = $con->getDb();

        $table = "";
        $PKEmpresa = $_SESSION["IDEmpresa"];

        $stmt = $db->prepare('call spc_Tabla_Guias_Consulta(?)');
        $stmt->execute(array($PKEmpresa));
        $array = $stmt->fetchAll();

        $acciones = '';

        foreach ($array as $r) {

            $Id = $r['id'];
            $estatus = $r['estatus'];
            $numero = $r['numero'];
            $descripcion = $r['descripcion'];
            $tipoPago = $r['tipoPago'];
            $paqueteria = $r['paqueteria'];

            if ($isPermissionsEdit == '1'){
                $acciones = '<i class=\"fas fa-edit pointer\" data-toggle=\"modal\" data-target=\"#editar_Guia\" onclick=\"obtenerDatosEditarGuia(\''.$Id.'\');\"></i>';
            }

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';

            $table .= '{"Id":"' . $etiquetaI . $Id . $etiquetaF . '",
                  "Estatus":"' . $etiquetaI . $estatus . $etiquetaF . '",
                  "Numero":"' . $etiquetaI . $numero . $etiquetaF . '",
                  "Descripcion":"' . $etiquetaI . $descripcion . $etiquetaF . '",
                  "TipoPago":"' . $etiquetaI . $tipoPago . $etiquetaF . '",
                  "Paqueteria":"' . $etiquetaI . $paqueteria . $etiquetaF . '",
                  "Acciones":"' . $etiquetaI . $acciones . $etiquetaF . '"},';

        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getPaqueteriasTable($isPermissionsEdit,$isPermissionsDelete)
    {
        $con = new conectar();
        $db = $con->getDb();

        $table = "";
        $PKEmpresa = $_SESSION["IDEmpresa"];

        $stmt = $db->prepare('call spc_Tabla_Paqueterias_Consulta(?)');
        $stmt->execute(array($PKEmpresa));
        $array = $stmt->fetchAll();

        $acciones = '';

        foreach ($array as $r) {

            $Id = $r['id'];
            $estatus = $r['estatus'];
            $nombreComercial = $r['nombreComercial'];
            $telefono = $r['telefono'];
            $razonSocial = $r['razonSocial'];
            $email = $r['email'];
            $rfc = $r['rfc'];
            $calle = $r['calle'];
            $numeroExt = $r['numeroExt'];
            $numeroInt = $r['numeroInt'];
            $colonia = $r['colonia'];
            $cp = $r['cp'];
            $municipio = $r['municipio'];
            $estado = $r['estado'];
            $pais = $r['pais'];
                
            if ($isPermissionsEdit == '1'){
                $acciones = '<i class=\"fas fa-edit pointer\" onclick=\"obtenerEditarPaqueteria(\''.$Id.'\');\"></i>';
            }

            if ($isPermissionsDelete == '1'){
                $acciones = $acciones . '<i class=\"fas fa-trash-alt pointer\" data-toggle=\"modal\" data-target=\"#eliminar_Paqueteria\" onclick=\"obtenerDatosEliminarPaqueteria(\''.$Id.'\');\"></i>';
            }

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';

            $table .= '{"Id":"' . $etiquetaI . $Id . $etiquetaF . '",
                  "Estatus":"' . $etiquetaI . $estatus . $etiquetaF . '",
                  "NombreComercial":"' . $etiquetaI . $nombreComercial . $etiquetaF . '",
                  "Telefono":"' . $etiquetaI . $telefono . $etiquetaF . '",
                  "RazonSocial":"' . $etiquetaI . $razonSocial . $etiquetaF . '",
                  "Email":"' . $etiquetaI . $email . $etiquetaF . '",
                  "RFC":"' . $etiquetaI . $rfc . $etiquetaF . '",
                  "Calle":"' . $etiquetaI . $calle . $etiquetaF . '",
                  "NumeroExt":"' . $etiquetaI . $numeroExt . $etiquetaF . '",
                  "NumeroInt":"' . $etiquetaI . $numeroInt . $etiquetaF . '",
                  "Colonia":"' . $etiquetaI . $colonia . $etiquetaF . '",
                  "CP":"' . $etiquetaI . $cp . $etiquetaF . '",
                  "Municipio":"' . $etiquetaI . $municipio . $etiquetaF . '",
                  "Estado":"' . $etiquetaI . $estado . $etiquetaF . '",
                  "Pais":"' . $etiquetaI . $pais . $etiquetaF . '",
                  "Acciones":"' . $etiquetaI . $acciones . $etiquetaF . '"},';

        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getContactoPaqueteriaTable($data, $permissionEdit, $permissionDelete)
    {
        $con = new conectar();
        $db = $con->getDb();
        $table = "";

        $query = sprintf('call spc_Tabla_Contactos_Proveedores_Consulta(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($data));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id = $r['id'];
            $nombre = $r['nombre'];
            $apellido = $r['apellido'];
            $puesto = $r['puesto'];
            $telefono = $r['telefono'];
            $celular = $r['celular'];
            $email = $r['email'];

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';

            $acciones = '';

            if($permissionEdit == '1'){
            $acciones .= '<i class=\"fas fa-edit\" data-toggle=\"modal\" data-target=\"#editar_Contacto\" onclick=\"modalDatosEditContacto(\''.$id.'\');\"></i>';
            }

            $table .= '{"Id":"' . $etiquetaI . $id . $etiquetaF . '",
                "Nombre":"' . $etiquetaI . $nombre . $etiquetaF . '",
                "Apellido":"' . $etiquetaI . $apellido . $etiquetaF . '",
                "Puesto":"' . $etiquetaI . $puesto . $etiquetaF . '",
                "TelefonoFijo":"' . $etiquetaI . $telefono . $etiquetaF . '",
                "Celular":"' . $etiquetaI . $celular . $etiquetaF . '",
                "Email":"' . $etiquetaI . $email . $etiquetaF . '",
                "Acciones":"' . $etiquetaI . $acciones . $etiquetaF . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getCuentaBancariaPaqueteriaTable($pkPaqueteria, $permissionEdit, $permissionDelete)
    {
        $con = new conectar();
        $db = $con->getDb();
        $table = "";

        $query = sprintf('call spc_Tabla_Bancos_Proveedores_Consulta(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkPaqueteria));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id = $r['id'];
            $banco = $r['banco'];
            $noCuenta = $r['noCuenta'];
            $clabe = $r['clabe'];
            $moneda = $r['moneda'];

            $etiquetaI = '<label class=\"textTable\">';
            $etiquetaF = '</label>';

            if ($permissionEdit == '1'){
                $acciones = '<i><img class=\"btnEdit\" data-toggle=\"modal\" data-target=\"#editar_CuentaBancancaria\" onclick=\"modalDatosEditCuentaBancaria(\''.$id.'\');\" src=\"../../../../img/timdesk/edit.svg\"></i>';
            }else{
                $acciones = '';
            }
            
            $table .= '{"Id":"' . $etiquetaI . $id . $etiquetaF . '",
                "Banco":"' . $etiquetaI . $banco . $etiquetaF . '",
                "NoCuenta":"' . $etiquetaI . $noCuenta . $etiquetaF . '",
                "CLABE":"' . $etiquetaI . $clabe . $etiquetaF . '",
                "Moneda":"' . $etiquetaI . $moneda . $etiquetaF . '",
                "Acciones":"' . $etiquetaI . $acciones . $etiquetaF . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getSucursalesPaqueteriaTable($pkProveedor, $permissionEdit, $permissionDelete)
    {
        $con = new conectar();
        $db = $con->getDb();
        $table = "";

        $query = sprintf('call spc_Tabla_DirecionesEnvio_Proveedores_Consulta(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkProveedor));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id = $r['id'];
            $sucursal = $r['sucursal'];
            $email = $r['email'];
            $calle = $r['calle'];
            $numeroExt = $r['numeroExt'];
            $numeroInt = $r['numeroInt'];
            $colonia = $r['colonia'];
            $municipio = $r['municipio'];
            $estado = $r['estado'];
            $pais = $r['pais'];
            $cp = $r['cp'];

            $etiquetaI = '<label class=\"textTable\">';
            $etiquetaF = '</label>';

            if ($permissionEdit == '1'){
                $acciones = '<i><img class=\"btnEdit\" data-toggle=\"modal\" data-target=\"#editar_Sucursal\" onclick=\"modalDatosEditSucursal(\''.$id.'\');\" src=\"../../../../img/timdesk/edit.svg\"></i>';
            }else{
                $acciones = '';
            }

            $table .= '{"Id":"' . $etiquetaI . $id . $etiquetaF . '",
                "Sucursal":"' . $etiquetaI . $sucursal . $etiquetaF . '",
                "Email":"' . $etiquetaI . $email . $etiquetaF . '",
                "Calle":"' . $etiquetaI . $calle . $etiquetaF . '",
                "NumeroExt":"' . $etiquetaI . $numeroExt . $etiquetaF . '",
                "NumeroInt":"' . $etiquetaI . $numeroInt . $etiquetaF . '",
                "Colonia":"' . $etiquetaI . $colonia . $etiquetaF . '",
                "Municipio":"' . $etiquetaI . $municipio . $etiquetaF . '",
                "Estado":"' . $etiquetaI . $estado . $etiquetaF . '",
                "Pais":"' . $etiquetaI . $pais . $etiquetaF . '",
                "CP":"' . $etiquetaI . $cp . $etiquetaF . '",
                "Acciones":"' . $etiquetaI . $acciones . $etiquetaF . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    /////////////////////////COMBOS//////////////////////////////
    
    public function getCmbVehiculoResponsable()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Combo_Vehiculo_Responsable(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));
        return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getCmbVehiculoUnidadMedida()
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Combo_Vehiculo_UMLiquidos()');
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getCmbVehiculoMonedaCU()
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Combo_Moneda()');
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getCmbVehiculoTipoServicio()
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Combo_Vehiculo_TipoServicio()');
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getCmbVehiculoEmpleados()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Combo_Empleados(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));
        return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getCmbGuiaTipoPago()
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Combo_TipoPago_Guia()');
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getCmbGuiaPaqueteria()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Combo_Paqueterias(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));
        return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getCmbPaises()
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Combo_Pais()');
        $stmt = $db->prepare($query);
        $stmt->execute();
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getCmbEstados($pkPais)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Combo_Estado(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkPais));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getCmbBanco()
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Combo_Banco()');
        $stmt = $db->prepare($query);
        $stmt->execute();
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    /////////////////////////DATOS PARA EDICIÓN//////////////////////////////
    
    public function getDatosVehiculoCargaCombustible($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Datos_Vehiculo_CargaCombustible_Consulta(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getDatosVehiculoServicio($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Datos_Vehiculo_Servicio_Consulta(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getDatosVehiculoPrestamo($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Datos_Vehiculo_Prestamo_Consulta(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getDatosVehiculoGenerales($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Datos_Vehiculo_General_Consulta(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getDatosVehiculoPoliza($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Datos_Vehiculo_Poliza_Consulta(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getDatosGuiaGeneral($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Datos_Guia_General_Consulta(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getDatosPaqueteriaContacto($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Datos_Contacto_Proveedor(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getDatosPaqueteriaCuentaBancaria($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Datos_CuentaBancaria_Proveedor(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getDatosPaqueteriaSucursal($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Datos_DireccionEnvio_Proveedor(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getDatosPaqueteriaGenerales($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Datos_Paqueteria_ConsultaGeneral(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getPdfPrestamo($value)
    {
        $con = new conectar();
        $db = $con->getDb();
        $pkPrestamo=$value;
        require_once('../../../lib/TCPDF/tcpdf.php');


        //consulta los nuevos datos del prestamo
        $query2 = ('SELECT e.nombres, e.primerapellido, e.segundoapellido, bv.bitacora_vehiculos_id, bv.motivo, bv.fechaprestamo, bv.nivel_combustible_inicio, 
        bv.nivel_combustible_final, bv.kilometraje_inicio, bv.kilometraje_final, date_format(if(bv.estatus=0,bv.updated_at,""), "%Y-%m-%d")as fechaEntrega, 
        v.marca, v.linea, v.modelo, v.color, v.placas
        from empleados e 
        inner join bitacora_vehiculos as bv on bv.empleado_id = e.PKEmpleado
        inner join vehiculos v on v.PKVehiculo=bv.vehiculo_id
        where bv.bitacora_vehiculos_id='.$pkPrestamo.';');

        $stmt2 = $db->prepare($query2);
        $stmt2->execute();
        $row=$stmt2->fetch();

        //se actualiza el pdf
        $ci1=' ';
        $ci2=' ';
        $ci3=' ';
        $ci4=' ';
        $ci5=' ';
        $cf1=' ';
        $cf2=' ';
        $cf3=' ';
        $cf4=' ';
        $cf5=' ';
        switch($row['nivel_combustible_inicio']){
            case '1':
                $ci1='X';
                break;
            case '2':
                $ci2='X';
                break;
            case '3':
                $ci3='X';
                break;
            case '4':
                $ci4='X';
                break;
            case '5':
                $ci5='X';
                break;
        }
        switch($row['nivel_combustible_final']){
            case '1':
                $cf1='X';
                break;
            case '2':
                $cf2='X';
                break;
            case '3':
                $cf3='X';
                break;
            case '4':
                $cf4='X';
                break;
            case '5':
                $cf5='X';
                break;
        }
        //se crea el pdf            
        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Timlid');
        $pdf->SetTitle('Préstamo de vehículo');
        
        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        
        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        
        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        
        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        
        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf->setLanguageArray($l);
        }
        
        // ---------------------------------------------------------
        
        // set default font subsetting mode
        $pdf->setFontSubsetting(true);
        
        // Set font
        // dejavusans is a UTF-8 Unicode font, if you only need to
        // print standard ASCII chars, you can use core fonts like
        // helvetica or times to reduce file size.
        $pdf->SetFont('helvetica', '', 11, '', true);
        
        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage();
        
        // set text shadow effect
        $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));
        
        // Set some content to print
        $tbl = '
        <table cellpadding="5" cellspacing="0" style="border-collapse:collapse;">
            <tr>
                <th colspan="5" align="center"><h3 style="text-align:center;font-weight:bold;">CHECK LIST PRÉSTAMO DE VEHÍCULO</h3></th>
            </tr>
            <tr>
                <td colspan = "5" align="center" style="background-color:#808080; color:#fff; margin:10px 0px 10px 0px;"><h4> DATOS GENERALES DEL SOLICITANTE</h4></td>
            </tr>
            <tr>
                <td colspan="3">Nombre del solicitante: '.$row['nombres'].' '.$row['primerapellido'].' '.$row['segundoapellido'].'</td>
                <td colspan="2">Fecha de préstamo: '.$row['fechaprestamo'].'</td>
            </tr>
            <tr>
                <td colspan="3">Motivo de préstamo: '.$row['motivo'].'</td>
                <td colspan="2">Fecha de entrega: '.$row['fechaEntrega'].'</td>
            </tr>
        </table>
        <br><br><br>
        ';

        $pdf->writeHTML($tbl, true, false, false, false, '');

        $tbl2='
        <table colspan="5" cellpadding="5" cellspacing="0" style="border-collapse:collapse;">
            <tr>
                <td colspan = "5" align="center" style="background-color:#808080; color:#fff; margin:10px 0px 10px 0px;"><h4> DATOS DEL VEHÍCULO</h4></td>
            </tr>    
            <tr>
                <td>Marca:</td>
                <td>Submarca:</td>
                <td>Modelo:</td>
                <td>Color:</td>
                <td>Placas: </td>
            </tr>
            <tr>
                <td>'.$row['marca'].'</td>
                <td>'.$row['linea'].'</td>
                <td>'.$row['modelo'].'</td>
                <td>'.$row['color'].'</td>
                <td>'.$row['placas'].' </td>
            </tr>
        </table>
        <br><br><br>
        ';
        $pdf->writeHTML($tbl2, true, false, false, false, '');

        $tbl3='
        <table colspan="5" cellpadding="2" cellspacing="0" style="border-collapse:collapse;">
            <tr>
                <td colspan = "5" align="center" style="background-color:#808080; color:#fff; margin:10px 0px 10px 0px;"><h4> CONSUMO Y RENDIMIENTO</h4></td>
            </tr>    
            <tr>
                <td colspan="2">Kilometraje inicial: '.$row['kilometraje_inicio'].'</td>
                <td colspan="3">Combustible inicial: ['.$ci1.']Lleno ['.$ci2.']3/4 &nbsp; ['.$ci3.']1/2 &nbsp; ['.$ci4.']1/4 &nbsp; ['.$ci5.']Reserva</td>
            </tr>
            <tr>
                <td colspan="2">Kilometraje final: '.$row['kilometraje_final'].'</td>
                <td colspan="3">Combustible final: ['.$cf1.']Lleno ['.$cf2.']3/4 &nbsp; ['.$cf3.']1/2 &nbsp; ['.$cf4.']1/4 &nbsp; ['.$cf5.']Reserva</td>
            </tr>
        </table>
        <br><br><br>
        ';

        $pdf->writeHTML($tbl3, true, false, false, false, '');

        $tbl4='
        <table colspan="5" cellpadding="2" cellspacing="0" style="border-collapse:collapse;">
            <tr>
                <td colspan = "5" align="center" style="background-color:#808080; color:#fff; margin:10px 0px 10px 0px;"><h4>CONDICIONES GENERALES</h4></td>
            </tr>    
            <tr>
                <td>( )Gato hidráulico</td>
                <td>( )Llanta de refacción</td>
                <td colspan = "2">( )Tarjeta de circulación</td>
                <td>( )Herramienta</td>
            </tr>
            <tr>
                <td>( )Placas</td>
                <td>( )Engomado</td>

                <td>( )Espejos</td>
                <td>( )Antena</td>
                <td>( )Radio</td>
            </tr>
        </table>
        <br><br><br>
        ';

        $pdf->writeHTML($tbl4, true, false, false, false, '');

        $tbl5='
        <table colspan="5" cellpadding="2" cellspacing="0" style="border-collapse:collapse;">
            <tr>
                <td colspan = "5" align="center" style="background-color:#808080; color:#fff; margin:10px 0px 10px 0px;"><h4>OBSERVACIONES</h4></td>
            </tr>    
            <tr>
                <td border="1" colspan="5" height="150">:</td>
            </tr>
        </table>
        <br><br><br>
        <table colspan="6" cellpadding="2" cellspacing="0" style="border-collapse:collapse;">
            <tr>
                <td colspan="3" align="center" height="80">Solicitante</td>
                <td colspan="3" align="center" height="80">Autoriza</td>
            </tr>
            <tr>
                <td padding-top="5" align="center" colspan="3">______________________</td>
                <td padding-top="5" align="center" colspan="3">______________________</td>
            </tr>
        </table>
        ';

        $pdf->writeHTML($tbl5, true, false, false, false, '');
        
        // ---------------------------------------------------------
        
        // Close and output PDF document

        echo $pdf->Output("Prestamo-".$row['bitacora_vehiculos_id'], 'I');
                                   
        //============================================================+
        // END OF FILE
        //============================================================+

    }

    /////////////////////////VALIDACIONES//////////////////////////////
    
    public function validarPermisos($pkPantalla)
    {
        $con = new conectar();
        $db = $con->getDb();

        if (isset($_SESSION["Usuario"])) {
            $PKuser = $_SESSION["PKUsuario"];
        }

        $query = sprintf('call spc_Validar_Permisos(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKuser, $pkPantalla));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarEmpresaVehiculo($PKVehiculo)
    {
        $con = new conectar();
        $db = $con->getDb();

        if (isset($_SESSION["Usuario"])) {
            $PKEmpresa = $_SESSION["IDEmpresa"];

        }

        $query = sprintf('call spc_ValidarEmpresaVehiculo(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa, $PKVehiculo));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarPaqueteriaNombreComercial($data)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Validar_Paqueteria_NombreComercial(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($data, $PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarPaqueteriaRazonSocial($razonSocial, $pkProveedor)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_ValidarUnicoRazonSocialProveedor(?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($razonSocial, $pkProveedor, $PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarPaqueteriaRfc($rfc, $pkProveedor)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_ValidarUnicoRFCProveedor(?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($rfc, $pkProveedor, $PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarEstado($estado, $pais)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_ValidarUnicoEstadoPais(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($estado, $pais));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarEmpresaPaqueteria($PKPaqueteria)
    {
        $con = new conectar();
        $db = $con->getDb();

        if (isset($_SESSION["Usuario"])) {
            $PKEmpresa = $_SESSION["IDEmpresa"];

        }

        $query = sprintf('call spc_ValidarEmpresaPaqueteria(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa, $PKPaqueteria));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarPaqueteriaContacto($email, $PKPaqueteria)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_ValidarUnicoContactoProveedor(?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($email, $PKPaqueteria, $PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarPaqueteriaNoCuenta($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_ValidarUnicaNoCuenta_Proveedor(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value, $PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarPaqueteriaCLABE($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_ValidarUnicaCLABE_Proveedor(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value, $PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarPaqueteriaDatosBancarios($pkBanco, $noCuenta, $clabe, $pkPaqueteria)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_ValidarUnicoBancoProveedor(?,?,?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkBanco, $noCuenta, $clabe, $pkPaqueteria, $PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarPaqueteriaSucursal($sucursal, $pkPaqueteria)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_ValidarUnicoSucursalProveedor(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($sucursal, $pkPaqueteria));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    /////////////////////////INFO//////////////////////////////
    
    public function getPKEmpresa()
    {
        $empresa=$_SESSION['IDEmpresa'];
        return $empresa;
    }
    


    //END JAVIER RAMIREZ
}

class save_data
{
    //JAVIER RAMIREZ
    
    public function saveDatosVehiculo($array, $estaus, $linea, $marca, $serie, $placas, $modelo, $puertas, $cilindros, $odometro, $kilometros, $motor, $color, $combustible, $transmision, $responsable)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKVehiculo = "0";
        $PKEmpresa = $_SESSION["IDEmpresa"];

        try {
            $query = sprintf('call spi_Vehiculos_AgregarGeneral (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($estaus, $linea, $marca, $serie, $placas, $modelo, $puertas, $cilindros, $odometro, $kilometros, $motor, $color, $combustible, $transmision, $responsable, $PKEmpresa, $PKVehiculo));
            $PKProducto = $stmt->fetch()['0'];

            $data[0] = ['status' => $status, 'id' => $PKProducto];

            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveDatosVehiculoCargaCombustible($array, $fechaCarga, $cantidad, $unidadMedida, $costoUnitario, $moneda, $odometro, $tanqueLleno, $pkVehiculo, $isEdit)
    {
        $con = new conectar();
        $db = $con->getDb();
        
        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spi_Vehiculo_AgregarCargaCombustible(?,?,?,?,?,?,?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($fechaCarga, $cantidad, $unidadMedida, $costoUnitario, $moneda, $odometro, $tanqueLleno, $pkVehiculo, $PKuser, $isEdit));
            $data[0] = ['status' => $status];

            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }
        $stmt = null;
        $db = null;
    }

    public function saveDatosVehiculoPolizaSeguro($array, $noPoliza, $aseguradora, $fechaInicio, $fechaTermino, $inciso, $importePoliza, $monedaPoliza, $agenteSeguros, $telefonoAgente, $telefonoSiniestros, $archivo, $pkVehiculo, $isEdit)
    {
        $con = new conectar();
        $db = $con->getDb();
        
        $PKuser = $_SESSION["PKUsuario"];

        if ($archivo == 1) {
            $pdf = 'ruta';
        } else {
            $pdf = 'vacia';
        }

        try {
            $query = sprintf('call spi_Vehiculo_AgregarPolizaSeguro(?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($noPoliza, $aseguradora, $fechaInicio, $fechaTermino, $inciso, $importePoliza, $monedaPoliza, $agenteSeguros, $telefonoAgente, $telefonoSiniestros, $pdf, $pkVehiculo, $PKuser, $isEdit));
            $PKPolizaSeguro = $stmt->fetch()['0'];

            $data[0] = ['status' => $status, 'idPoliza' => $PKPolizaSeguro];

            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }
        $stmt = null;
        $db = null;
    }

    public function saveDatosVehiculoServicio($array, $servicio, $descripcion, $lugar, $tipoServicio, $costoServico, $moneda, $pkVehiculo, $isEdit, $archivo)
    {
        $con = new conectar();
        $db = $con->getDb();

        if ($archivo == 1) {
            $pdf = 'ruta';
        } else {
            $pdf = 'vacia';
        }
        
        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spi_Vehiculo_AgregarServicio(?,?,?,?,?,?,?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($servicio, $descripcion, $lugar, $tipoServicio, $costoServico, $moneda, $pkVehiculo, $PKuser, $isEdit,$pdf));
            $PKServicio = $stmt->fetch()['0'];
            $data[0] = ['status' => $status, 'idServicio' => $PKServicio];

            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }
        $stmt = null;
        $db = null;
    }

    public function saveDatosVehiculoPrestamo($array, $empleado, $motivo, $nivel_combustible_inicio, $id_autorizo, $kilometraje_inicio, $fecha, $pkVehiculo, $isEdit)
    {
        $con = new conectar();
        $db = $con->getDb();
        require_once('../../../lib/TCPDF/tcpdf.php');

        $PKuser = $_SESSION["PKUsuario"];

        if($id_autorizo==''|| $id_autorizo==null){
            $id_autorizo=null;
        }

        try {
            $query = sprintf('call spi_Vehiculo_AgregarPrestamo(?,?,?,?,?,?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($empleado, $motivo, $nivel_combustible_inicio, $id_autorizo, $kilometraje_inicio, $fecha, $pkVehiculo, $PKuser, $isEdit));
            $row= $stmt->fetch();
            $haveOpen = $row['HaveOpen'];
            $stmt->closeCursor();
            
            $data[0] = ['status' => $status, 'haveOpen'=>$haveOpen, 'idPrestamo'=>$row['idPrestamo']];
            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }
        $stmt = null;
        $db = null;
    }

    public function saveDatosGuiaGeneral($array, $estatus, $numero, $descripcion, $tipoPago, $paqueteria, $isEdit){
        $con = new conectar();
        $db = $con->getDb();
        
        $pkGuia = "0";
        $PKuser = $_SESSION["PKUsuario"];
        $PKEmpresa = $_SESSION["IDEmpresa"];

        try {
            $query = sprintf('call spi_Guia_AgregarGeneral(?,?,?,?,?,?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($estatus, $numero, $descripcion, $tipoPago, $paqueteria, $PKuser, $PKEmpresa ,$isEdit, $pkGuia));
            $PKGuia = $stmt->fetch()['0'];

            $data[0] = ['status' => $status, 'id' => $PKGuia];

            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }
        $stmt = null;
        $db = null;
    }

    public function saveEstadoPais($estado, $pais)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $query = sprintf('call spi_EstadoFederativo_Agregar (?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($estado, $pais));

            $data[0] = ['status' => $status];

            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveDatosPaqueteria($array, $estatus, $nombreComercial, $telefono, $email, $razonSocial, $rfc, $calle, $numeroExt, $numeroInt, $colonia, $municipio, $pais, $estado, $cp)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKPaqueteria = "0";
        $PKEmpresa = $_SESSION["IDEmpresa"];
        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spi_Paqueteria_AgregarGeneral (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($estatus, $nombreComercial, $telefono, $email, $razonSocial, $rfc, $calle, $numeroExt, $numeroInt, $colonia, $municipio, $pais, $estado, $cp, $PKEmpresa, $PKuser, $PKPaqueteria));
            $PKPaqueteria = $stmt->fetch()['0'];

            $data[0] = ['status' => $status, 'id' => $PKPaqueteria];

            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveDatosPaqueteriaContacto($array, $nombreContacto, $apellidoContacto, $puesto, $telefono, $celular, $email, $pkPaqueteria, $isEdit)
    {
        $con = new conectar();
        $db = $con->getDb();
        
        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spi_Proveedores_AgregarContacto(?,?,?,?,?,?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($nombreContacto, $apellidoContacto, $puesto, $telefono, $celular, $email, $pkPaqueteria, $isEdit, $PKuser));
            $data[0] = ['status' => $status];

            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }
        $stmt = null;
        $db = null;
    }

    public function saveDatosPaqueteriaCuentaBancaria($array, $banco, $noCuenta, $clabe, $moneda, $pkPaqueteria, $isEdit){
        $con = new conectar();
        $db = $con->getDb();
        
        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spi_Proveedores_AgregarBanco(?,?,?,?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($banco, $noCuenta, $clabe, $pkPaqueteria, $isEdit, $moneda, $PKuser));
            $data[0] = ['status' => $status];

            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }
        $stmt = null;
        $db = null;
    }

    public function saveDatosPaqueteriaSucursal($array, $sucursal, $contacto, $telefono, $email, $calle, $numeroExt, $numeroInt, $colonia, $municipio, $pais, $estado, $cp, $pkPaqueteria, $isEdit){
        $con = new conectar();
        $db = $con->getDb();
        
        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spi_Proveedores_AgregarDireccionesEnvio(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($sucursal, $email, $calle, $numeroExt, $numeroInt, $colonia, $municipio, $pais, $estado, $cp, $pkPaqueteria, $isEdit, $contacto, $telefono, $PKuser));
            $data[0] = ['status' => $status];

            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }
        $stmt = null;
        $db = null;
    }

    //END JAVIER RAMIREZ
}

class edit_data
{
    //JAVIER RAMIREZ
    
    public function editDatosVehiculo($array, $estaus, $linea, $marca, $serie, $placas, $modelo, $puertas, $cilindros, $odometro, $kilometros, $motor, $color, $combustible, $transmision, $responsable, $PKVehiculo)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        try {
            $query = sprintf('call spu_Vehiculos_EditarGeneral (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($estaus, $linea, $marca, $serie, $placas, $modelo, $puertas, $cilindros, $odometro, $kilometros, $motor, $color, $combustible, $transmision, $responsable, $PKEmpresa, $PKVehiculo));
            $data[0] = ['status' => $status];

            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editDatosVehiculoEstatus($PKVehiculo)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        try {
            $query = sprintf('call spu_Vehiculos_EditarEstatus(?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKVehiculo));
            $data[0] = ['status' => $status];

            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editDatosGuiaEstatus($PKGuia)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];
        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_Guia_EditarEstatus(?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKGuia, $PKuser));
            $data[0] = ['status' => $status];

            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editDatosPaqueteria($array, $estatus, $nombreComercial, $telefono, $email, $razonSocial, $rfc, $calle, $numeroExt, $numeroInt, $colonia, $municipio, $pais, $estado, $cp, $pkPaqueteria)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];
        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_Paqueteria_EditarGeneral (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($estatus, $nombreComercial, $telefono, $email, $razonSocial, $rfc, $calle, $numeroExt, $numeroInt, $colonia, $municipio, $pais, $estado, $cp, $PKEmpresa, $PKuser, $pkPaqueteria));
            $PKPaqueteria = $stmt->fetch()['0'];

            $data[0] = ['status' => $status, 'id' => $PKPaqueteria];

            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editDatosPaqueteriaEstatus($PKPaqueteria)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        try {
            $query = sprintf('call spu_Paqueteria_EditarEstatus(?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKPaqueteria));
            $data[0] = ['status' => $status];

            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    //END JAVIER RAMIREZ
}

class delete_data
{
    //JAVIER RAMIREZ
    
    public function deleteVehiculoCargaCombustible($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spd_EliminarVehiculoCargaCombustible(?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($value, $PKuser));

            $data[0] = ['status' => $status];
            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function deleteVehiculoServicio($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spd_EliminarVehiculoServicio(?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($value, $PKuser));
            $archivo=$stmt->fetch()['0'];
            $ruta_archivo_servicio='../catalogos/vehiculos/'.$archivo;
            
            //se elimina el archivo pdf del servidor
            if(file_exists($ruta_archivo_servicio)){
                unlink($ruta_archivo_servicio);
            }

            $data[0] = ['status' => $status];
            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function deleteVehiculoPrestamo($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spd_EliminarVehiculoPrestamo(?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($value, $PKuser));
            $result = $stmt->fetch()['0'];

            $data[0] = ['status' => $status, 'result'=> $result];
            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function closeVehiculoPrestamo($array, $combustible_final, $kilometraje_final, $pkPrestamo)
    {
        $con = new conectar();
        $db = $con->getDb();
        require_once('../../../lib/TCPDF/tcpdf.php');

        $PKuser = $_SESSION["PKUsuario"];
        $PKEmpresa = $_SESSION["IDEmpresa"];


        try {
            $query = sprintf('call spu_CerrarPrestamo_vehiculos(?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($combustible_final, $kilometraje_final, $pkPrestamo, $PKuser));

            $data[0] = ['status' => $status];
            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function deletePaqueteriaContacto($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spd_EliminarContactoProveedor(?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($value, $PKuser));

            $data[0] = ['status' => $status];
            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function deletePaqueteriaCuentaBancaria($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spd_EliminarBancoProveedor(?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($value, $PKuser));

            $data[0] = ['status' => $status];
            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function deletePaqueteriaSucursales($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spd_EliminarDireccionEnvioProveedor(?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($value, $PKuser));

            $data[0] = ['status' => $status];
            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }
    
    //END JAVIER RAMIREZ
}