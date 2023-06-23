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

function GetEvn()
{
    include "../../../include/db-conn.php";
    $appUrl = $_ENV['APP_URL'] ?? 'https://app.timlid.com/';
    return ['server' => $appUrl];
}

class get_data
{

    //JAVIER RAMIREZ

    /////////////////////////TABLAS//////////////////////////////
    public function getClientesTable()
    {
        $con = new conectar();
        $db = $con->getDb();
        $table = "";

        $stmt = $db->prepare('SELECT c.PKCliente, c.NombreComercial, mcc.MedioContactoCliente, c.created_at, c.Telefono, c.Email, c.Monto_credito, c.Dias_credito, c.estatus, e.Nombres, c.razon_social, c.rfc
        FROM clientes AS c
        LEFT JOIN medios_contacto_clientes AS mcc ON c.medio_contacto_id = mcc.PKMedioContactoCliente
        INNER JOIN empleados AS e ON c.empleado_id = e.PKEmpleado
        WHERE c.empresa_id = :empresa_id');
        $stmt->execute([':empresa_id' => $_SESSION['IDEmpresa']]);
        $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($clientes as $cliente) {
            $nombreComercial = str_replace('"', '\"', $cliente['NombreComercial']);
            $id = $cliente['PKCliente'];
            $medioCotacto = $cliente['MedioContactoCliente'];
            $fechaAlta = $cliente['created_at'];
            $telefono = $cliente['Telefono'];
            $email = $cliente['Email'];
            $montoCredito = $cliente['Monto_credito'];
            $diasCredito = $cliente['Dias_credito'];
            $estatus = $cliente['estatus'] === 1 ? 'Activo' : 'Inactivo';
            $vendedor = $cliente['Nombres'];
            $razonSocial = str_replace('"', '\"', $cliente['razon_social']);
            $rfc = $cliente['rfc'];

            $etiquetaI = '<div class=\"d-flex align-items-center\">';
            $etiquetaF = '</div>';
            $acciones = '<a href=\"detalles_cliente.php?c=' . $id . '\" class=\"mr-1\"><i class=\"fas fa-clipboard-list color-primary pointer mr-2\"></i></a> <i class=\"fas fa-edit color-primary pointer mr-2\" onclick=\"obtenerIdClienteEditar(' . $id . ');\"></i> <i class=\"fas fa-trash-alt color-primary pointer\" data-toggle=\"modal\" data-target=\"#eliminar_Cliente\" onclick=\"obtenerIdClienteEliminar(' . $id . ');\"></i>';
            $nombreComercialEnlace = '<a href=\"detalles_cliente.php?c=' . $id . '\">'.$nombreComercial.'</a>';
            $razonSocialEnlace = '<a href=\"detalles_cliente.php?c=' . $id . '\">'.$razonSocial.'</a>';
            $table .= '{"Id":"' . $id . '",
                "NombreComercial":"' . $nombreComercialEnlace . '",
                "RazonSocial":"' . $razonSocialEnlace . '",
                "Rfc":"' . $rfc . '",
                "Telefono":"' . $telefono . '",
                "Email":"' . $email . '",
                "Monto":"' . $montoCredito . '",
                "Dias":"' . $diasCredito . '",
                "Estatus":"' . $estatus . '",
                "Vendedor":"' . $vendedor . '",
                "MedioContacto":"' . $medioCotacto . '",
                "FechaAlta":"' . $fechaAlta . '",
                "Acciones":"' . $etiquetaI . $acciones . $etiquetaF . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getRazonSocialClientesTable($pkCliente)
    {
        $con = new conectar();
        $db = $con->getDb();
        $table = "";

        $query = sprintf('call spc_Tabla_RazonesSociales_Clientes_Consulta(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkCliente));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id = $r['id'];
            $razonSocial = $r['razonSocial'];
            $rfc = $r['rfc'];
            $calle = $r['calle'];
            $numeroExt = $r['numeroExt'];
            $numeroInt = $r['numeroInt'];
            $colonia = $r['colonia'];
            $municipio = $r['municipio'];
            $estado = $r['estado'];
            $pais = $r['pais'];
            $cp = $r['cp'];

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';
            $acciones = '<i><img class=\"btnEdit\"  onclick=\"obtenerIdRazonSocialClienteEliminar(' . $id . ');\" src=\"../../../../img/timdesk/delete.svg\"><img class=\"btnEdit\"  onclick=\"obtenerIdRazonSocialClienteEditar(' . $id . ');\" src=\"../../../../img/timdesk/edit.svg\"></i>';

            $table .= '{"Id":"' . $etiquetaI . $id . $etiquetaF . '",
                "RazonSocial":"' . $etiquetaI . $razonSocial . $etiquetaF . '",
                "RFC":"' . $etiquetaI . $rfc . $etiquetaF . '",
                "Calle":"' . $etiquetaI . $calle . $etiquetaF . '",
                "NumeroExt":"' . $etiquetaI . $numeroExt . $etiquetaF . '",
                "NumeroInt":"' . $etiquetaI . $numeroInt . $etiquetaF . '",
                "Colonia":"' . $etiquetaI . $colonia . $etiquetaF . '",
                "Municipio":"' . $etiquetaI . $municipio . $etiquetaF . '",
                "Estado":"' . $etiquetaI . $estado . $etiquetaF . '",
                "Pais":"' . $etiquetaI . $pais . $etiquetaF . '",
                "CP":"' . $etiquetaI . $cp . $etiquetaF . $acciones . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getContactoClientesTable($pkCliente, $permissionEdit)
    {
        $con = new conectar();
        $db = $con->getDb();
        $table = "";

        $query = sprintf('call spc_Tabla_Contactos_Clientes_Consulta(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkCliente));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id = $r['id'];
            $nombre = $r['nombre'];
            $apellido = $r['apellido'];
            $puesto = $r['puesto'];
            $telefono = $r['telefono'];
            $celular = $r['celular'];
            $email = $r['email'];

            $isCorreos = "";

            if ($r['isFacturacion'] == '1') {
                $isCorreos .= 'Facturación';
            }
            if ($r['isComplementoPago'] == '1') {
                if ($isCorreos == '') {
                    $isCorreos .= 'Complementos de pago';
                } else {
                    $isCorreos .= ', Complementos de pago';
                }
            }
            if ($r['isAvisosEnvio'] == '1') {
                if ($isCorreos == '') {
                    $isCorreos .= 'Avisos de envío';
                } else {
                    $isCorreos .= ', Avisos de envío';
                }
            }
            if ($r['isPagos'] == '1') {
                if ($isCorreos == '') {
                    $isCorreos .= 'Pagos';
                } else {
                    $isCorreos .= ', Pagos';
                }
            }

            if ($isCorreos == '') {
                $isCorreos = 'Ninguno';
            }

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';

            if ($permissionEdit == '1') {
                $acciones = '<i class=\"fas fa-edit pointer\" data-toggle=\"modal\" data-target=\"#editar_Contacto\" onclick=\"obtenerIdContactoClienteEditar(' . $id . ');\"></i>';

                $cliente = '<a class=\"pointer\" href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Contacto\" onclick=\"obtenerIdContactoClienteEditar(' . $id . ');\">'.$etiquetaI . $nombre . $etiquetaF.'</a>';
            } else {
                $acciones = '';
                $cliente = $etiquetaI . $nombre . $etiquetaF;
            }

            $table .= '{"Id":"' . $etiquetaI . $id . $etiquetaF . '",
                "Nombre":"' . $cliente . '",
                "Apellido":"' . $etiquetaI . $apellido . $etiquetaF . '",
                "Puesto":"' . $etiquetaI . $puesto . $etiquetaF . '",
                "TelefonoFijo":"' . $etiquetaI . $telefono . $etiquetaF . '",
                "Celular":"' . $etiquetaI . $celular . $etiquetaF . '",
                "RecibirCorreos":"' . $etiquetaI . $isCorreos . $etiquetaF . '",
                "Acciones":"",
                "Email":"' . $etiquetaI . $email . $etiquetaF . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getBancoClientesTable($pkCliente, $permissionEdit)
    {
        $con = new conectar();
        $db = $con->getDb();
        $table = "";

        $query = sprintf('call spc_Tabla_Bancos_Clientes_Consulta(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkCliente));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id = $r['id'];
            $banco = $r['banco'];
            $noCuenta = $r['noCuenta'];
            $clabe = $r['clabe'];
            $moneda = $r['moneda'];

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';
            if ($permissionEdit == '1') {
                $acciones = '<i class=\"fas fa-edit pointer\" data-toggle=\"modal\" data-target=\"#editar_CuentaBancancaria\" onclick=\"obtenerIdBancoClienteEditar(' . $id . ');\"></i>';

                $banco = '<a class=\"pointer\" href=\"#\" data-toggle=\"modal\" data-target=\"#editar_CuentaBancancaria\" onclick=\"obtenerIdBancoClienteEditar(' . $id . ');\">'.$etiquetaI . $banco . $etiquetaF.'</a>';
            } else {
                $acciones = '';
                $banco = $etiquetaI . $banco . $etiquetaF;
            }

            $table .= '{"Id":"' . $etiquetaI . $id . $etiquetaF . '",
                "Banco":"' . $banco . '",
                "NoCuenta":"' . $etiquetaI . $noCuenta . $etiquetaF . '",
                "CLABE":"' . $etiquetaI . $clabe . $etiquetaF . '",
                "Acciones":"",
                "Moneda":"' . $etiquetaI . $moneda . $etiquetaF . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getProductosTable($pkCliente, $permissionEdit)
    {
        $con = new conectar();
        $db = $con->getDb();
        $table = "";

        $query = sprintf('call spc_Tabla_Producto_Cliente_Consulta(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkCliente));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id = $r['id'];
            $cliente = $r['nombre'];
            $costoEspecial = $r['costo'];
            $moneda = $r['moneda'];

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';

            if ($permissionEdit == '1') {
                //$acciones = '<i class=\"fas fa-trash-alt pointer\" onclick=\"eliminarProducto(' . $id . ');\"></i>';
                $prod = '<a class=\"pointer\" data-toggle=\"modal\" data-target=\"#editar_Producto_cliente\" onclick=\"getProductoIdAndName(' . $id . ')\">' . $etiquetaI . $cliente . $etiquetaF . '</a>';
            } else {
                //$acciones = '';
                $prod = $etiquetaI . $cliente . $etiquetaF;
            }


            $table .= '{"Id":"' . $etiquetaI . $id . $etiquetaF . '",
                "NombreComercial":"' . $prod . '",
                "CostoEspecial":"' . $etiquetaI . $costoEspecial . ' ' . $moneda . $etiquetaF . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getDireccionEnvioClientesTable($pkCliente, $permissionEdit)
    {
        $con = new conectar();
        $db = $con->getDb();
        $table = "";

        $query = sprintf('call spc_Tabla_DirecionesEnvio_Cliente_Consulta(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkCliente));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id = $r['id'];
            $sucursal = $r['sucursal'];
            $email = $r['email'];
            $calle = $r['calle'];
            $calle = str_replace('"', '\"', $calle);
            $numeroExt = $r['numeroExt'];
            $numeroInt = $r['numeroInt'];
            $colonia = $r['colonia'];
            $colonia = str_replace('"', '\"', $colonia);
            $municipio = $r['municipio'];
            $estado = $r['estado'];
            $pais = $r['pais'];
            $cp = $r['cp'];

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';

            if ($permissionEdit == '1') {
                $acciones = '<i class=\"fas fa-edit pointer\" data-toggle=\"modal\" data-target=\"#editar_DireccionEnvio\" onclick=\"obtenerIdDireccionProveedorEditar(' . $id . ');\"></i>';
                $direccion = '<a class=\"pointer\" href=\"#\" data-toggle=\"modal\" data-target=\"#editar_DireccionEnvio\" onclick=\"obtenerIdDireccionProveedorEditar(' . $id . ');\">'. $etiquetaI . $sucursal . $etiquetaF .'</a>';
            } else {
                $acciones = '';
                $direccion =  $etiquetaI . $sucursal . $etiquetaF;
            }

            if ($r['predeterminado'] == '1') {
                $isCheck = 'checked';
            } else {
                $isCheck = '';
            }

            $predeterminado = '<input type=\"checkbox\" id=\"cbxPred-' . $id . '\" name=\"cbxPred-' . $id . '\" data-toggle=\"modal\" data-target=\"#editar_Predeterminado\" onclick=\"seleccionarPredeterminado(' . $id . ')\" ' . $isCheck . ' disabled>';

            $table .= '{"Id":"' . $etiquetaI . $id . $etiquetaF . '",
                "Predeterminar":"' . $etiquetaI . $predeterminado . $etiquetaF . '",
                "Sucursal":"' . $direccion . '",
                "Email":"' . $etiquetaI . $email . $etiquetaF . '",
                "Calle":"' . $etiquetaI . $calle . $etiquetaF . '",
                "NumeroExt":"' . $etiquetaI . $numeroExt . $etiquetaF . '",
                "NumeroInt":"' . $etiquetaI . $numeroInt . $etiquetaF . '",
                "Colonia":"' . $etiquetaI . $colonia . $etiquetaF . '",
                "Municipio":"' . $etiquetaI . $municipio . $etiquetaF . '",
                "Estado":"' . $etiquetaI . $estado . $etiquetaF . '",
                "Pais":"' . $etiquetaI . $pais . $etiquetaF . '",
                "Acciones":"",
                "CP":"' . $etiquetaI . $cp . $etiquetaF . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }
    /////////////////////////COMBOS//////////////////////////////

    public function getCmbEstatusGral()
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Combo_EstatusGeneral()');
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getCmbMediosContacto($empresa_id)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Combo_MediosContactoCliente(?)');
        $stmt = $db->prepare($query);
        $stmt->execute([$empresa_id]);
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getCmbVendedor()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Combo_Vendedor(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getCmbCategoriaCliente()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('SELECT PKCategoria_cliente as id,
                                    nombre as categoria
                          from categorias_clientes where empresa_id = ? and estatus = 1');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getCmbRegimen()
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Combo_Regimen()');
        $stmt = $db->prepare($query);
        $stmt->execute();
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
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

    public function getCmbProductoCliente()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Combo_ProductosCliente(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getCmbCostouniVenta()
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Combo_Moneda()');
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getCmbCostouniVentaEsp()
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Combo_Moneda()');
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    /////////////////////////VALIDACIONES//////////////////////////////
    public function validarMedioContactoCliente($data)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_ValidarUnicoMedioContacto(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($data));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarNombreComercial($data)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_ValidarUnicoNombreComercial(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($data, $PKEmpresa));
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

    public function validarRazonSocialCliente($razonSocial)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_ValidarUnicoRazonSocialCliente(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($razonSocial, $PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarRfcCliente($rfc)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_ValidarUnicoRFCCliente(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($rfc, $PKEmpresa));
        $array = $stmt->fetch(PDO::FETCH_ASSOC);

        return $array;
    }

    public function validarContactoCliente($nombreContacto, $apellidoContacto, $PKCliente)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_ValidarUnicoContactoCliente(?,?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($nombreContacto, $apellidoContacto, $PKCliente, $PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarNoCuenta($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_ValidarUnicaNoCuenta(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value, $PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarCLABE($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_ValidarUnicaCLABE(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value, $PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarDatosBanariosCliente($pkBanco, $noCuenta, $clabe, $pkCliente)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_ValidarUnicoBancoCliente(?,?,?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkBanco, $noCuenta, $clabe, $pkCliente, $PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarProductoCliente($pkProducto, $pkCliente)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_ValidarUnicoClienteProducto(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkCliente, $pkProducto));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarSucursalCliente($sucursal, $pkCliente)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_ValidarUnicoSucursalCliente(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($sucursal, $pkCliente));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarEmpresaCliente($pkCliente)
    {
        $con = new conectar();
        $db = $con->getDb();

        if (isset($_SESSION["Usuario"])) {
            $PKEmpresa = $_SESSION["IDEmpresa"];
        }

        $query = sprintf('call spc_ValidarEmpresaCliente(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa, $pkCliente));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

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

    public function validarCP($CP)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('SELECT id FROM codigos_postales WHERE codigo_postal=?');
        $stmt = $db->prepare($query);
        $stmt->execute(array($CP));
        $array = $stmt->fetch(PDO::FETCH_OBJ);

        return $array;
    }
    /////////////////////////DATOS PARA EDICIÓN//////////////////////////////
    public function getDatosFiscalCliente($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Datos_RazonSocial(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getDatosContactoCliente($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Datos_Contacto(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getDatosCuentaBancariaCliente($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Datos_CuentaBancaria(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getDatosCostoGralProducto($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Datos_Prod_ConsultaVenta(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getDatosGeneralesCliente($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Datos_Cliente_ConsultaGeneral(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getDatosDireccionEnvioCliente($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Datos_DireccionEnvio_Cliente(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getDatosVentaCliente($permisoRead, $clienteId)
    {
        $con = new conectar();
        $db = $con->getDb();
        $envVariables = GetEvn();
        $appUrl = $envVariables['server'];

        $table = "";
        $PKEmpresa = $_SESSION["IDEmpresa"];

        $stmt = $db->prepare('call spc_Tabla_VentasDirectas_Consulta_Cliente(?,?)');
        $stmt->execute(array($PKEmpresa, $clienteId));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {

            $Id = $r['PKVentaDirecta'];
            $Referencia = $r['Referencia'];
            $FechaCreacion = $r['FechaCreacion'];
            $FechaVencimiento = $r['FechaVencimiento'];
            $importe = number_format($r['Importe'], 2);

            $FKEstatusVenta = $r['FKEstatusVenta'];
            $EstatusVenta = $r['EstatusVenta'];
            $isInventario = $r['isInventario'];
            $estatusOrdenPedido = $r['estatusOrdenPedido'];

            $EstatusFactura = $r['estatusFactura'];

            $colorEstatus = '';
            $cierreEstatus = '</span>';
            //$acciones = '<a href=\"' . $appUrl . 'catalogos/ventas_directas/catalogos/ventas/ver_ventas.php?vd=' . $Id . '\"><i class=\"fas fa-clipboard-list pointer\"></i></a>';
            $Referencia = '<a href=\"' . $appUrl . 'catalogos/ventas_directas/catalogos/ventas/ver_ventas.php?vd=' . $Id . '\">' . $Referencia . '</a>';

            if ($estatusOrdenPedido == '1' || $estatusOrdenPedido == '2' || $estatusOrdenPedido == '0') {
                $colorEstatus = '<span class=\"left-dot turquoise-dot\">';

                if ($isInventario == '1') {
                    if ($estatusOrdenPedido == '1') {
                        $EstatusVenta = 'Nueva';
                    } else if ($estatusOrdenPedido == '2') {
                        $EstatusVenta = 'Nueva FD';
                    } else if ($estatusOrdenPedido == '0') {
                        if ($FKEstatusVenta == '6') {
                            $EstatusVenta = 'Factura pendiente';
                        } else if ($FKEstatusVenta == '2') {
                            $EstatusVenta = 'Facturada';
                        }
                    }
                }
            } else if ($estatusOrdenPedido == '3' || $estatusOrdenPedido == '4') {
                $colorEstatus = '<span class=\"left-dot yellow-dot\">';
                if ($isInventario == '1') {
                    if ($estatusOrdenPedido == '3') {
                        $EstatusVenta = 'Parcialmente surtida';
                    } else if ($estatusOrdenPedido == '4') {
                        $EstatusVenta = 'Parcialmente surtida FD';
                    }
                }
            } else if ($estatusOrdenPedido == '9') {
                $colorEstatus = '<span class=\"left-dot gray-dot\">';
                //$acciones = '<input type=\"hidden\" value=\"' . md5($Id) . '\" id=\"inp-' . $Id . '\"> <i onclick=\"obtenerVer(' . $Id . ');\" class=\"fas fa-clipboard-list pointer\"></i>';
            } else if ($EstatusVenta == 'Cerrada' || $estatusOrdenPedido == '7' || $estatusOrdenPedido == '8') {
                $colorEstatus = '<span class=\"left-dot red-dot\">';
                if ($estatusOrdenPedido == '8') {
                    $EstatusVenta = 'Cancelada';
                }
            } else if ($estatusOrdenPedido == '5' || $estatusOrdenPedido == '6') {
                $colorEstatus = '<span class=\"left-dot green-dot\">';
                if ($isInventario == '1') {
                    if ($estatusOrdenPedido == '5') {
                        $EstatusVenta = 'Surtida completa';
                    } else if ($estatusOrdenPedido == '6') {
                        $EstatusVenta = 'Surtida completa FD';
                    }
                }
            }
            //$acciones = '';
            $table .= '{"Id":"' . $Id . '",
                  "Referencia":"' . $Referencia . '",
                  "FechaEmision":"' . $FechaCreacion . '",
                  "FechaVencimiento":"' . $FechaVencimiento . '",
                  "Importe":"' . '$ ' . $importe . '",
                  "EstatusFactura":"' . $EstatusFactura . '",
                  "EstatusVenta":"' . $colorEstatus . $EstatusVenta . $cierreEstatus . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getDatosCotizacionCliente($permisoRead, $clienteId)
    {
        $con = new conectar();
        $db = $con->getDb();
        $envVariables = GetEvn();
        $appUrl = $envVariables['server'];
        $table = "";

        $stmt = $db->prepare('SELECT c.PKCotizacion, c.id_cotizacion_empresa, c.ImporteTotal, ec.tipo_estatus as Estatus ,c.FechaVencimiento, s.sucursal, s.activar_inventario, c.facturacion_directa, c.estatus_factura_id as estatus_factura 
        FROM cotizacion as c 
        LEFT JOIN estatus_cotizacion as ec ON ec.id = c.estatus_cotizacion_id 
        LEFT JOIN sucursales as s ON s.id = c.FKSucursal 
        WHERE c.empresa_id = :idEmpresa and c.FKCliente=:clienteID
        ORDER BY c.id_cotizacion_empresa Desc');
        $stmt->execute([':idEmpresa' => $_SESSION['IDEmpresa'], ':clienteID' => $clienteId]);
        $row = $stmt->fetchAll();
        $table = "";

        foreach ($row as $r) {

            //1 Aceptada
            //2 Facturada
            //3 Cancelada
            //4 Vencida
            //5 Pendiente
            $estatus = $r['Estatus'];
            $Id = $r['PKCotizacion'];

            if ($estatus == "Pendiente") {
                if (strtotime(date("Y-m-d")) > strtotime($r['FechaVencimiento'])) {
                    $stmt = $db->prepare('UPDATE cotizacion SET estatus_cotizacion_id = 4 WHERE PKCotizacion = :id AND empresa_id = ' . $_SESSION['IDEmpresa']);
                    $stmt->execute(array(':id' => $r['PKCotizacion']));
                    $estatus = "Vencida";
                }
            }

            switch ($estatus) {
                case 'Pendiente':
                    $estatus = "<span class='left-dot yellow-dot'>Pendiente</span>";
                    break;
                case 'Vencida':
                    $estatus = "<span class='left-dot red-dot'>Vencida</span>";
                    break;
                case 'Aceptada':
                    $estatus = "<span class='left-dot green-dot'>Aceptada</span>";
                    break;
                case 'Facturada':
                    $estatus = "<span class='left-dot turquoise-dot'>Facturada</span>";
                    break;
                case 'Cancelada':
                    $estatus = "<span class='left-dot red-dot'>Cancelada</span>";
                    break;
            }
            $estatus_factura = $r['estatus_factura'];
            $enlace = '<a href=\"' . $appUrl . 'catalogos/cotizaciones/detalleCotizacion.php?id=' . $Id . '\"><i class=\"fas fa-clipboard-list pointer\"></i></a>';

            switch ($estatus_factura) {
                case 1:
                    $estatus_factura = "<span class='left-dot turquoise-dot'>Facturado completo</span>";
                    break;
                case 2:
                    $estatus_factura = "<span class='left-dot turquoise-dot'>Facturado directo</span>";
                    break;
                case 3:
                    $estatus_factura = "<span class='left-dot yellow-dot'>Pendiente de <br>facturar</span>";
                    break;
                case 4:
                    $estatus_factura = "<span class='left-dot yellow-dot'>Pendiente de <br>facturar directo</span>";
                    break;
                case 5:
                    $estatus_factura = "<span class='left-dot green-dot'>Parcialmente<br> facturado almacén</span>";
                    break;
                case 6:
                    $estatus_factura = "<span class='left-dot red-dot'>Cancelada</span>";
                    break;
            }

            $id_cotizacion_empresa = sprintf("%011d", $r['id_cotizacion_empresa']);
            $id_cotizacion_empresa = '<a href=\"' . $appUrl . 'catalogos/cotizaciones/detalleCotizacion.php?id=' . $Id . '\">' . $id_cotizacion_empresa . '</a>';

            $table .= '{"Referencia":"' . $id_cotizacion_empresa . '",
                "Importe":"' . "$" . number_format($r['ImporteTotal'], 2) . '",
                "Sucursal":"' . $r['sucursal'] . '",
                "Acciones": "' . $enlace . '",
                "Estatus": "' . $estatus . '",
                "Estatus factura":"' . $estatus_factura . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getDatosPedidoCliente($permisoRead, $clienteId)
    {
        $con = new conectar();
        $db = $con->getDb();
        $envVariables = GetEvn();
        $appUrl = $envVariables['server'];
        $table = "";
        $values = [':idEmpresa' => $_SESSION['IDEmpresa'], 'clienteID' => $clienteId];
        $query = 'SELECT ops.id, ops.id_orden_pedido_empresa, so.sucursal as sucursal_origen, sd.sucursal as sucursal_destino, 
                  DATE_FORMAT(ops.fecha_captura, "%d/%m/%Y %H:%i:%s") as fecha_ingreso, ops.tipo_pedido, eop.estatus, ops.estatus_factura_id as estatus_factura  
                  FROM orden_pedido_por_sucursales as ops 
                  LEFT JOIN sucursales as so ON so.id = ops.sucursal_origen_id 
                  LEFT JOIN sucursales as sd ON sd.id = ops.sucursal_destino_id 
                  LEFT JOIN estatus_orden_pedido as eop ON eop.id = ops.estatus_orden_pedido_id 
                  WHERE ops.empresa_id = :idEmpresa and ops.cliente_id = :clienteID ORDER BY ops.id DESC';

        $stmt = $db->prepare($query);
        $stmt->execute($values);
        $row = $stmt->fetchAll();
        $table = "";

        foreach ($row as $r) {

            $id_orden_pedido_empresa = sprintf("%011d", $r['id_orden_pedido_empresa']);
            $id_orden_pedido_empresa = '<a href=\"' . $appUrl . 'catalogos/pedidos/detallePedido.php?id=' . $r['id'] . '\">' . $id_orden_pedido_empresa . '</a>';
            $enlace = '<a href=\"' . $appUrl . 'catalogos/pedidos/detallePedido.php?id=' . $r['id'] . '\"><i class=\"fas fa-clipboard-list pointer\"></i></a>';

            switch ($r['estatus']) {
                case 'Nuevo':
                    $estatus = '<span class=\"left-dot turquoise-dot\">Nuevo</span>';
                    break;
                case 'Nuevo FD':
                    $estatus = '<span class=\"left-dot turquoise-dot\">Nuevo-FD</span>';
                    break;
                case 'Parcialmente surtido':
                    $estatus = '<span class=\"left-dot yellow-dot\">Parcialmente surtido</span>';
                    break;
                case 'Parcialmente surtido FD':
                    $estatus = '<span class=\"left-dot yellow-dot\">Parcialmente surtido-FD</span>';
                    break;
                case 'Surtido completo':
                    $estatus = '<span class=\"left-dot green-dot\">Surtido completo</span>';
                    break;
                case 'Surtido completo FD':
                    $estatus = '<span class=\"left-dot green-dot\">Surtido completo-FD</span>';
                    break;
                case 'Cerrado':
                    $estatus = '<span class=\"left-dot red-dot\">Cerrado</span>';
                    break;
                case 'Cancelado':
                    $estatus = '<span class=\"left-dot red-dot\">Cancelado</span>';
                    break;
                case 'Facturado-directo':
                    $estatus = '<span class=\"left-dot turquoise-dot\">Facturado-directo</span>';
                    break;
                case 'Facturado-almacen':
                    $estatus = '<span class=\"left-dot turquoise-dot\">Facturado-almacen</span>';
                    break;
                case 'Remisionado parcial':
                    $estatus = '<span class=\"left-dot orange-dot\">Remisionado parcial</span>';
                    break;
                case 'Remisionado completo':
                    $estatus = '<span class=\"left-dot orange-dot\">Remisionado completo</span>';
                    break;
            }

            $estatus_factura = $r['estatus_factura'];
            switch ($estatus_factura) {
                case 1:
                    $estatus_factura = "<span class='left-dot turquoise-dot'>Facturado completo</span>";
                    break;
                case 2:
                    $estatus_factura = "<span class='left-dot turquoise-dot'>Facturado directo</span>";
                    break;
                case 3:
                    $estatus_factura = "<span class='left-dot yellow-dot'>Pendiente de <br>facturar</span>";
                    break;
                case 4:
                    $estatus_factura = "<span class='left-dot yellow-dot'>Pendiente de <br>facturar directo</span>";
                    break;
                case 5:
                    $estatus_factura = "<span class='left-dot green-dot'>Parcialmente<br> facturado almacén</span>";
                    break;
                case 6:
                    $estatus_factura = "<span class='left-dot red-dot'>Cancelada</span>";
                    break;
                case 7:
                    $estatus_factura = "<span class='left-dot orange-dot'>Remisionado parcial</span>";
                    break;
                case 8:
                    $estatus_factura = "<span class='left-dot orange-dot'>Remisionado completo</span>";
                    break;
                case 9:
                    $estatus_factura = "<span class='left-dot green-dot'>Facturado de remision parcial</span>";
                    break;
                case 10:
                    $estatus_factura = "<span class='left-dot dark-dot'>Facturado de remision completo</span>";
                    break;
            }

            if ($r['tipo_pedido'] == 1) {
                $tipo_pedido = "Traspaso";
            } elseif ($r['tipo_pedido'] == 2) {
                $tipo_pedido = "General";
            } elseif ($r['tipo_pedido'] == 3) {
                $tipo_pedido = "Cotización";
            } elseif ($r['tipo_pedido'] == 4) {
                $tipo_pedido = "Venta";
            }

            $table .= '{"No Pedido":"' . $id_orden_pedido_empresa . '","Sucursal origen":"' . $r['sucursal_origen'] . '","Sucursal destino":"' . $r['sucursal_destino'] . '","Fecha generacion":"' . $r['fecha_ingreso'] . '","Tipo pedido": "' . $tipo_pedido . '","Estatus": "' . $estatus . '","Estatus factura": "' . $estatus_factura . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getDatosPagosCliente($permisoRead, $clienteId, $isfiltered = 0, $fecha_desde = "no", $fecha_hasta = "no")
    {
        $con = new conectar();
        $db = $con->getDb();
        $envVariables = GetEvn();
        $appUrl = $envVariables['server'];
        $table = "";
        $respuesta['total'] = 0;
        $respuesta['saldo'] = 0;
        $total = 0;
        $totalPagado = 0;
        $totalCredito = 0;
        $totalVencidas = 0;
        $totalCorriente = 0;

        $creditoAsignado = 0;
        $creditoDisponible = 0;

        if ($isfiltered == 1) {
            if ($fecha_desde != "no") {
                if ($fecha_hasta == "no") {
                    $fecha_hasta = date("Y-m-d");
                }
                $between = 'and f.fecha_timbrado between "' . $fecha_desde . '" and "' . $fecha_hasta . ' 23:59:59"';
            } else {
                if ($fecha_hasta != "no") {
                    $between = 'and f.fecha_timbrado <= "' . $fecha_hasta . ' 23:59:59"';
                }
            }
            $query = "SELECT cliente, credito, id, folio, serie, fecha_de_facturacion, fecha_de_vencimiento, Estado, Monto, Monto_total, Monto_pagado, importeNC FROM
            (
                (SELECT  c.NombreComercial as cliente, c.Monto_credito as credito, f.id as 'id', f.folio as 'folio', f.serie as 'serie', f.fecha_timbrado as fecha_de_facturacion, 
                if(f.fecha_vencimiento is null, date_add(f.fecha_timbrado, interval c.Dias_credito day),f.fecha_vencimiento) as fecha_de_vencimiento, f.estatus as Estado, 
                f.saldo_insoluto as 'Monto', f.total_facturado as Monto_total, ifnull(m.Deposito,0) as Monto_pagado, notas.importeNC as importeNC FROM facturacion as f
                left join (select sum(mov.Deposito) as Deposito, mov.id_factura, max(mov.parcialidad) as parcialidad 
                from movimientos_cuentas_bancarias_empresa as mov where mov.tipo_movimiento_id = 5 and mov.estatus = 1 and mov.tipo_CuentaCobrar = 2 and mov.id_pago is not null group by mov.id_factura) as m on m.id_factura = f.id
                left join (select sum(nc.importe) as importeNC, ncf.facturacion_id, group_concat(nc.folion_nota SEPARATOR '/') as foliosNotas 
                from notas_cuentas_por_cobrar as nc 
                    inner join notas_cuentas_por_cobrar_has_facturacion as ncf on nc.id = ncf.notas_cuentas_por_cobrar_id 
                where nc.estatus = 1 and nc.empresa_id=:idEmpresa3 group by ncf.facturacion_id) as notas on notas.facturacion_id = f.id, clientes as c
                where f.cliente_id=c.PKCliente and f.empresa_id = :idEmpresa and f.estatus  not in (4) and c.PKCliente = :idCliente $between order by fecha_timbrado desc)
            UNION
                (SELECT  c.NombreComercial as cliente, c.Monto_credito as credito, vd.PKVentaDirecta as 'id', vd.Referencia as 'folio', '' as 'serie', vd.created_at as fecha_de_facturacion, 
                vd.FechaVencimiento as fecha_de_vencimiento, vd.estatus_cuentaCobrar as Estado, 
                IFNULL(vd.saldo_insoluto_venta,0) as 'Monto', vd.Importe as Monto_total, ifnull(m.Deposito,0) as Monto_pagado, 0 as importeNC FROM ventas_directas as vd 
                left join (select sum(mov.Deposito) as Deposito, mov.id_factura, max(mov.parcialidad) as parcialidad 
                from movimientos_cuentas_bancarias_empresa as mov where mov.tipo_movimiento_id = 5 and mov.estatus = 1 and mov.tipo_CuentaCobrar = 1 and mov.id_pago is not null group by mov.id_factura) as m on m.id_factura = vd.PKVentaDirecta, clientes as c
                where vd.FKCliente=c.PKCliente and vd.empresa_id = :idEmpresa2 and vd.FKEstatusVenta  not in (2 ,5) and (vd.estatus_factura_id = 3 OR vd.estatus_factura_id = 4 OR vd.estatus_factura_id = 7) and c.PKCliente = :idCliente2 $between order by vd.created_at desc)
            ) as tbl";
            $stmt = $db->prepare($query);
            $stmt->execute([':idEmpresa' => $_SESSION['IDEmpresa'], ':idCliente' => $clienteId, ':idEmpresa2' => $_SESSION['IDEmpresa'], ':idCliente2' => $clienteId, ':idEmpresa3' => $_SESSION['IDEmpresa']]);
        } else {
            $query = "SELECT cliente, credito, id, folio, serie, fecha_de_facturacion, fecha_de_vencimiento, Estado, Monto, Monto_total, Monto_pagado, importeNC FROM
            (
                (SELECT  c.NombreComercial as cliente, c.Monto_credito as credito, f.id as 'id', f.folio as 'folio', f.serie as 'serie', f.fecha_timbrado as fecha_de_facturacion, 
                if(f.fecha_vencimiento is null, date_add(f.fecha_timbrado, interval c.Dias_credito day),f.fecha_vencimiento) as fecha_de_vencimiento, f.estatus as Estado, 
                f.saldo_insoluto as 'Monto', f.total_facturado as Monto_total, ifnull(m.Deposito,0) as Monto_pagado, ifnull(notas.importeNC,0) as importeNC FROM facturacion as f
                left join (select sum(mov.Deposito) as Deposito, mov.id_factura, max(mov.parcialidad) as parcialidad 
                from movimientos_cuentas_bancarias_empresa as mov where mov.tipo_movimiento_id = 5 and mov.estatus = 1 and mov.tipo_CuentaCobrar = 2 and mov.id_pago is not null group by mov.id_factura) as m on m.id_factura = f.id
                left join (select sum(nc.importe) as importeNC, ncf.facturacion_id, group_concat(nc.folion_nota SEPARATOR '/') as foliosNotas 
                from notas_cuentas_por_cobrar as nc 
                    inner join notas_cuentas_por_cobrar_has_facturacion as ncf on nc.id = ncf.notas_cuentas_por_cobrar_id 
                where nc.estatus = 1 and nc.empresa_id=:idEmpresa group by ncf.facturacion_id) as notas on notas.facturacion_id = f.id, clientes as c
                where f.cliente_id=c.PKCliente and f.empresa_id = :idEmpresa2 and f.estatus  not in (4) and c.PKCliente = :idCliente order by fecha_timbrado desc)
            UNION
                (SELECT  c.NombreComercial as cliente, c.Monto_credito as credito, vd.PKVentaDirecta as 'id', vd.Referencia as 'folio', '' as 'serie', vd.created_at as fecha_de_facturacion, 
                vd.FechaVencimiento as fecha_de_vencimiento, vd.estatus_cuentaCobrar as Estado, 
                IFNULL(vd.saldo_insoluto_venta, 0) as 'Monto', vd.Importe as Monto_total, ifnull(m.Deposito,0) as Monto_pagado, 0 as importeNC  FROM ventas_directas as vd 
                left join (select sum(mov.Deposito) as Deposito, mov.id_factura, max(mov.parcialidad) as parcialidad 
                from movimientos_cuentas_bancarias_empresa as mov where mov.tipo_movimiento_id = 5 and mov.estatus = 1 and mov.tipo_CuentaCobrar = 1 and mov.id_pago is not null group by mov.id_factura) as m on m.id_factura = vd.PKVentaDirecta, clientes as c
                where vd.FKCliente=c.PKCliente and vd.empresa_id = :idEmpresa3 and vd.FKEstatusVenta  not in (2 ,5) and (vd.estatus_factura_id = 3 OR vd.estatus_factura_id = 4 OR vd.estatus_factura_id = 7) and c.PKCliente = :idCliente2 order by vd.created_at desc)
            ) as tbl";
            $stmt = $db->prepare($query);
            $stmt->execute([':idEmpresa' => $_SESSION['IDEmpresa'], ':idCliente' => $clienteId, ':idEmpresa2' => $_SESSION['IDEmpresa'], ':idCliente2' => $clienteId, ':idEmpresa3' => $_SESSION['IDEmpresa']]);
        }

        while (($row = $stmt->fetch()) !== false) {

            //solo suma el monto de las cuentas pendientes de pago
            if ($row['Estado'] == 1 || $row['Estado'] == 2) {
                $respuesta['total'] = $respuesta['total'] + $row['Monto'];
            }
            //solo resta el monto de las cuentas pagadas, parcialmente pagadas y las pendientes de pago, suma tambien el monto pagado y el monto de las notas credito cuando estan pagadas o parcialmente pagadas
            if ($row['Estado'] == 2 || $row['Estado'] == 3 || $row['Estado'] == 1) {
                $total += $row['Monto_total']; 
                $totalPagado += $row['Monto_pagado'];
                $totalCredito += $row['importeNC'];
            }
            $creditoAsignado = $row['credito'];
            //semaforo de fecha de vencimiento
            $row['fecha_de_facturacion'] = date("d-m-Y", strtotime($row['fecha_de_facturacion']));
            $row['fecha_de_vencimiento'] = date("d-m-Y", strtotime($row['fecha_de_vencimiento']));

            $fechaVencimiento = date("Y-m-d", strtotime($row['fecha_de_vencimiento']));
            $fechaActual = date("Y-m-d");

            if ($fechaActual > $fechaVencimiento && $row['Estado'] != 3) {
                $row['fecha_de_vencimiento'] = '<span class=\"badge badge-danger\" style=\"font-size:1rem;font-family: Montserrat, sans-serif\">' . $row['fecha_de_vencimiento'] . '</span>';
                $totalVencidas += $row['Monto'];
            } else if ($row['Estado'] != 3) {
                $row['fecha_de_vencimiento'] = '<span class=\"badge badge-success\" style=\"font-size:1rem;font-family: Montserrat, sans-serif\">' . $row['fecha_de_vencimiento'] . '</span>';
                $totalCorriente += $row['Monto'];
            }


            //añade una etiqueta segun el estado de la factura
            if ($row['Estado'] == 1) {
                $row['Estado'] = '<span class=\"left-dot blue-light-dot\">Pendiente de Pago</span>';
            } elseif ($row['Estado'] == 2) {
                $row['Estado'] = '<span class=\"left-dot orange-dot\">Parcialmente Pagada</span>';
            } elseif ($row['Estado'] == 3) {
                $row['Estado'] = '<span class=\"left-dot green-dot\">Pagada</span>';
            } elseif ($row['Estado'] == 4) {
                $row['Estado'] = '<span class=\"left-dot red-dot\">Cancelada</span>';
            } elseif ($row['Estado'] == 5) {
                $row['Estado'] = '<span class=\"left-dot gray-dot\">En proceso de cancelación</span>';
            }
            $row['Monto_total'] = number_format($row['Monto_total'], 2);
            $row['Monto_pagado'] = number_format($row['Monto_pagado'], 2);
            $row['importeNC'] = number_format($row['importeNC'], 2);
            $row['Monto'] = number_format($row['Monto'], 2);

            /* TODO: REVISAR EL ENLACE */
            $enlace = '<a href=\"' . $appUrl . 'catalogos/cuentas_cobrar/detalle_factura.php?idFactura=' . $row['id'] . '\">'.$row['folio'].'</a>';
            //llena la tabla en formato json
            $table .= '{"Serie":"' . $row['serie'] .
                '","Folio factura":"' . $enlace .
                '","F de expedicion":"' . $row['fecha_de_facturacion'] .
                '","F de vencimiento":"' . $row['fecha_de_vencimiento'] .
                '","Estado":"' . $row['Estado'] .
                '","Total":"$' . $row['Monto_total'] .
                '","Importe Pagado":"$' . $row['Monto_pagado'] .
                '","Importe Notas Credito":"$' . $row['importeNC'] .
                '","Monto":"$' . $row['Monto'] .'"},';
        }
        $table = substr($table, 0, strlen($table) - 1);
        $creditoDisponible = $creditoAsignado - floatval($respuesta['total']);
        $respuesta['total'] = number_format($respuesta['total'], 2);
        $respuesta['saldo'] = $totalPagado + $totalCredito - $total;
        return '{"data":[' . $table . '], "total":"' . $respuesta['total'] . '", "creditoA": "' . number_format($creditoAsignado, 2) . '", "creditoD": "' . number_format($creditoDisponible, 2) . '" , "cuentasV": "' . number_format($totalVencidas, 2) . '", "cuentasC": "' . number_format($totalCorriente, 2) . '", "saldo": "' . number_format($respuesta['saldo'], 2) . '"}';
    }
    /////////////////////////COLUMNAS AJUSTABLES//////////////////////////////
    public function listaColumnas()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spc_Columnas_Clientes(?)');
            $stmt = $db->prepare($query);
            $stmt->execute(array($PKuser));
            $array = $stmt->fetchAll(PDO::FETCH_OBJ);

            return $array;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function infoColumnas($array)
    {

        $data = [];
        $PKEmpresa = $_SESSION["IDEmpresa"];

        try {

            for ($i = 0; $i < count($array); $i++) {
                $con = new conectar();
                $db = $con->getDb();

                $query = sprintf("call spc_Tabla_Columnas_Clientes_Consulta(?,?)");
                $stmt = $db->prepare($query);
                $stmt->execute(array($array[$i][0], $PKEmpresa));
                $lista = $stmt->fetchAll(PDO::FETCH_OBJ);

                //print_r($lista);

                array_push($data, [$lista, $array[$i][2]]);
            }

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    //Lista el orden de las columnas del empleado
    public function ordenColumnas()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf("call spc_Columnas_Clientes_Ordenadas(?)");
            $stmt = $db->prepare($query);
            $stmt->execute(array($PKuser));
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function obtenerIds()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf("call spc_Columnas_Clientes_Ids(?)");
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));

        return $id = $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function ordenDatos($sort, $indice, $search)
    {

        $PKEmpresa = $_SESSION["IDEmpresa"];

        try {


            $con = new conectar();
            $db = $con->getDb();

            $query = sprintf('call spc_Tabla_Columnas_Clientes_SetOrden_Consulta(?,?,?,?,?)');
            //echo $query;
            $stmt = $db->prepare($query);
            $stmt->execute(array(1, $sort, $indice, $PKEmpresa, $search));
            $ordenClientes = $stmt->fetchAll(PDO::FETCH_OBJ);


            return $ordenClientes;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }
    }
    //END JAVIER RAMIREZ

}

class data_order
{
    //JAVIER RAMIREZ
    public function columnOrder($array)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            for ($i = 0; $i < count($array); $i++) {
                if ($array[$i] != 1) {
                    $update = sprintf("call spu_Columnas_Clientes_Orden(?,?,?)");
                    $stmt = $db->prepare($update);
                    $stmt->execute(array($i + 1, $array[$i], $PKuser));
                }
            }

            return "ok";
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }
    #END JAVIER RAMIREZ
}

class buscar_data
{
    //JAVIER RAMIREZ
    public function buscarCliente($inputValue, $array)
    {
        $data = [];

        $PKEmpresa = $_SESSION["IDEmpresa"];
        try {

            for ($i = 0; $i < count($array); $i++) {
                $con = new conectar();
                $db = $con->getDb();

                $query = sprintf("call spc_Tabla_Columnas_Clientes_Search_Consulta(?,?,?)");
                $stmt = $db->prepare($query);
                $stmt->execute(array($array[$i][0], $inputValue, $PKEmpresa));
                $lista = $stmt->fetchAll(PDO::FETCH_OBJ);

                //print_r($lista);

                array_push($data, [$lista, $array[$i][2]]);
            }

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }
    #END JAVIER RAMIREZ
    public function getCostoProducto_Cliente($pkRegistro)
    {
        try {

            $con = new conectar();
            $db = $con->getDb();

            $query = sprintf('SELECT ce.CostoEspecial, ce.FKTipoMoneda, concat(p.ClaveInterna," - ", p.Nombre) as producto 
                            from costo_especial_producto_cliente ce
                                inner join productos p on p.PKProducto = ce.FKProducto
                            where PKCostoEspecialProductoCliente = ?');
            $stmt = $db->prepare($query);
            $stmt->execute(array($pkRegistro));
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
            
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }
}

class save_data
{
    //JAVIER RAMIREZ
    public function saveMedioContactoCliente($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $query = sprintf('call spi_Medio_Contacto_Cliente_Agregar (?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($value));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveDatosCliente($array, $nombreComercial, $medioContactoCliente, $vendedor, $montoCredito, $diasCredito, $telefono, $email, $estatus, $contacto, $categoria)
    {
        $ruta_api = "../../../";
        include $ruta_api . "include/functions_api_facturation.php";
        $con = new conectar();
        $db = $con->getDb();
        $api = new API();

        $pkCliente = '0';
        $PKuser = $_SESSION["PKUsuario"];
        $PKEmpresa = $_SESSION["IDEmpresa"];

        try {
            $query = sprintf('call spi_Cliente_AgregarGeneral (?,?,?,?,?,?,?,?,?,?,?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($nombreComercial, $medioContactoCliente, $telefono, $email, $montoCredito, $diasCredito, $estatus, $vendedor, $pkCliente, $PKuser, $PKEmpresa, $contacto, $categoria));
            $PKCliente = $stmt->fetch()['0'];

            /*if($status){
              $query = sprintf("select key_company_api key_company from empresas where PKEmpresa = :id");
              $stmt = $db->prepare($query);
              $stmt->bindValue(":id",$_SESSION['IDEmpresa']);
              $stmt->execute();

              $key_company_api = $stmt->fetchAll();

              $cliente_api = [
                "email" => $email,
                "legal_name" => $razonSocial,
                "tax_id" => $rfc,
                "address" => array(
                  "country"=>"MEX")
              ];

              $api->createCustomer($key_company_api[0]['key_company'],$cliente_api);
            }*/

            $data[0] = ['status' => $status, 'id' => $PKCliente];

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

    public function saveContactoCliente($nombreContacto, $apellidoContacto, $puesto, $telefonoFijo, $celular, $email, $pkCliente, $pkContacto, $isFacturacion, $isComplementoPago, $isAvisosEnvio, $isPagos)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spi_Clientes_AgregarContacto(?,?,?,?,?,?,?,?,?,?,?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($nombreContacto, $apellidoContacto, $puesto, $telefonoFijo, $celular, $email, $pkCliente, $pkContacto, $isFacturacion, $isComplementoPago, $isAvisosEnvio, $isPagos, $PKuser));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    
    public function saveRazonSocialCliente($razonSocial, $rfc, $calle, $numExt, $numInt, $colonia, $municipio, $pais, $estado, $cp, $pkCliente, $pkRazonSocial, $regimenFiscal)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spi_Clientes_AgregarFiscales(?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($razonSocial, $rfc, $calle, $numExt, $numInt, $colonia, $municipio, $pais, $estado, $cp, $pkCliente, $pkRazonSocial, $PKuser, $regimenFiscal));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveBancoCliente($pkBanco, $noCuenta, $clabe, $pkCliente, $pkCuentaBancaria, $moneda)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spi_Clientes_AgregarBanco(?,?,?,?,?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($pkBanco, $noCuenta, $clabe, $pkCliente, $pkCuentaBancaria, $moneda, $PKuser));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveProductoCliente($pkProducto, $costoEsp, $moneda, $pkCliente)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spi_Prod_AgregarProductoCliente(?,?,?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($pkProducto, $costoEsp, $moneda, $pkCliente, $PKuser));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveDireccionEnvioCliente($sucursal, $email, $calle, $numExt, $numInt, $colonia, $municipio, $pais, $estado, $cp, $pkCliente, $pkRazonSocial, $contacto, $telefono)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spi_Clientes_AgregarDireccionesEnvio(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($sucursal, $email, $calle, $numExt, $numInt, $colonia, $municipio, $pais, $estado, $cp, $pkCliente, $pkRazonSocial, $contacto, $telefono, $PKuser));

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
    public function editRazonSocialCliente($razonSocial, $rfc, $calle, $numExt, $numInt, $colonia, $municipio, $pais, $estado, $cp, $pkCliente, $pkRazonSocial, $regimenFiscal)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_Clientes_AgregarFiscales(?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($razonSocial, $rfc, $calle, $numExt, $numInt, $colonia, $municipio, $pais, $estado, $cp, $pkCliente, $pkRazonSocial, $PKuser, $regimenFiscal));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editDatosCliente($nombreComercial, $medioContactoCliente, $vendedor, $montoCredito, $diasCredito, $telefono, $email, $estatus, $pkCliente, $categoria, $contacto)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_Cliente_ActualizarGeneral(?,?,?,?,?,?,?,?,?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($nombreComercial, $medioContactoCliente, $telefono, $email, $montoCredito, $diasCredito, $estatus, $vendedor, $pkCliente, $PKuser, $contacto, $categoria));

            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editDatosFiscalesCliente($razonSocial, $rfc, $calle, $numExt, $numInt, $colonia, $municipio, $pais, $estado, $cp, $pkRazon, $regimen)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_Cliente_ActualizarDatosFiscales(?,?,?,?,?,?,?,?,?,?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($razonSocial, $rfc, $calle, $numExt, $numInt, $colonia, $municipio, $pais, $estado, $cp, $pkRazon, $PKuser, $regimen));

            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function updateCostoCliente($pkRegistro, $Costo, $moneda)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_EditarProdCliente(?,?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($pkRegistro, $PKuser, $Costo, $moneda, 0));

            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editDatosClientePredeterminado($idContactoCliente)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_Cliente_ActualizarPredeterminado(?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKuser, $idContactoCliente));

            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    /////////////////////////COLUMNAS AJUSTABLES//////////////////////////////
    public function updateCheckColumn($pkColumnaCliente, $flag)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_Columnas_Clientes_Check(?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($pkColumnaCliente, $flag, $PKuser));

            if ($flag == 1) {
                $array = $stmt->fetchAll(PDO::FETCH_OBJ);
                $data[0] = ['status' => $status, 'array' => $array];
            } else {
                $data[0] = ['status' => $status];
            }

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
    public function deleteRazonSocialCliente($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spd_EliminarRazonSocialCliente(?,?)');
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

    public function deleteContactoCliente($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spd_EliminarContactoCliente(?,?)');
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

    public function deleteCuentaBancariaCliente($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spd_EliminarBancoCliente(?,?)');
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

    public function deleteClienteProducto($pkCliente)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spd_EliminarProdCliente(?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($pkCliente, $PKuser));

            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function deleteCliente($pkCliente)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spd_EliminarCliente(?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($pkCliente, $PKuser));

            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function deleteDireccionEnvioCliente($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spd_EliminarDireccionEnvioCliente(?,?)');
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

class upload_file
{
    public function uploadXmlEntries($data)
    {
        $con = new conectar();
        $db = $con->getDb();
        try {
            //$value = $data." ".$file;
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }
        $stmt = null;
        $db = null;
    }
}

class delete_file
{
    public function deleteXml($value)
    {
        $target_dir = "../catalogos/entradas_productos/documentos/";
        $target_file = $target_dir . basename($value);

        return unlink($target_file);
    }
}

//$prueba = new get_data();
//var_dump($prueba->getDatosCotizacionCliente(1, 5));