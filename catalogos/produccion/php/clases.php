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
    
    public function getListaMaterialesTable($isPermissionsEdit,$isPermissionsDelete)
    {
        $con = new conectar();
        $db = $con->getDb();
        $table = "";

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $stmt = $db->prepare('call spc_Tabla_ListaMateriales_Consulta(?)');
        $stmt->execute(array($PKEmpresa));
        $array = $stmt->fetchAll();

        $rutaServer = $_ENV['RUTA_ARCHIVOS_READ'].$PKEmpresa.'/img'.'/';

        foreach ($array as $r) {
            $id = $r['id'];
            $claveInterna = $r['claveInterna'];
            $nombre = str_replace(["\r", "\n"], "", $r['nombre']);
            $descripcion = str_replace(["\r", "\n"], "", $r['descripcion']);
            $marcaProducto = $r['marcaProducto'];
            $categoriaProductos = $r['categoriaProductos'];
            $tipoProducto = $r['tipoProducto'];
            $codigoBarras = $r['codigoBarras'];
            $estatus = $r['estatus'];

            if ($r['imagen'] == 'agregar.svg') {
                $imagen = '<img src=\"../../../../imgProd/' . $r['imagen'] . '\" width=\"25px\">';
            } else {
                $imagen = '<img src=\"' . $rutaServer . $r['imagen'] . '\" width=\"50px\">';
            }

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>'; 

            if ($isPermissionsEdit == '1'){
              $acciones = '<i class=\"fas fa-edit pointer\" onclick=\"obtenerEditarListaMaterial('.$id.');\"></i>';
            }

            if ($isPermissionsDelete == '1'){
                $acciones = $acciones . '<i class=\"fas fa-trash-alt pointer\" data-toggle=\"modal\" data-target=\"#eliminar_Material\" onclick=\"obtenerDatosEliminarListaMaterial('.$id.');\"></i>';
            }

            $textId = '<span class=\"textTable\">' . $id . '</span>';
            $textDescription = '<span class=\"textTable\">' . $descripcion . '</span>';
            $textName = '<span class=\"textTable\">' . $nombre . '</span>';
            $textInternalKey = '<span class=\"textTable\">' . $claveInterna . '</span>';
            $textProductMark = '<span class=\"textTable\">' . $marcaProducto . '</span>';
            $textProductCategory = '<span class=\"textTable\">' . $categoriaProductos . '</span>';
            $textProductType = '<span class=\"textTable\">' . $tipoProducto . '</span>';
            $textBarcode = '<span class=\"textTable\">' . $codigoBarras . '</span>';
            $textStatus = '<span class=\"textTable\">' . $estatus . '</span>';
            $textImagen = '<span class=\"textTable\">' . $imagen . '</span>';
            $textActions = '<span class=\"textTable\">' . $acciones . '</span>';

            $table .= '{"Id":"' . $textId . '","ClaveInterna":"' . $textInternalKey . '","Nombre":"' . $textName . '","Descripcion":"' . $textDescription . '","MarcaProducto":"' . $textProductMark . '","CategoriaProductos":"' . $textProductCategory . '","TipoProducto":"' . $textProductType . '","CodigoBarras":"' . $textBarcode . '","Estatus":"' . $textStatus . '","Imagen":"' . $textImagen . '","Acciones":"' . $textActions . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';

    }

    public function getUnidadesSATTable($buscador)
    {
        $con = new conectar();
        $db = $con->getDb();
        $table = "";

        $stmt = $db->prepare('call spc_Tabla_UnidadSAT_Consulta(?)');
        $stmt->execute(array($buscador));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id = $r['id'];
            $clave = $r['clave'];
            $descripcion = $r['descripcion'];

            $etiquetaI = '<span class=\"textTable\" data-dismiss=\"modal\" onclick=\"obtenerIdUnidadSeleccionar(' . $id . ',\'' . $clave . '\',\'' . $descripcion . '\');\">';
            $etiquetaF = '</span>';
            $acciones = '<i><img class=\"btnEdit\" data-dismiss=\"modal\" onclick=\"obtenerIdUnidadSeleccionar(' . $id . ',\'' . $clave . '\',\'' . $descripcion . '\');\" src=\"../../../../img/timdesk/ICONO ESTADO-TERMINADO_Mesa de trabajo 1.svg\"></i>';

            $table .= '{"Id":"' . $etiquetaI . $id . $etiquetaF . '",
                "Clave":"' . $etiquetaI . $clave . $etiquetaF . '",
                "Descripcion":"' . $etiquetaI . $descripcion . $etiquetaF . $acciones . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';

    }
    
    public function getlistaMaterialesConsumirTable($sucursal_id)
    {
        $con = new conectar();
        $db = $con->getDb();
        $table = "";

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $stmt = $db->prepare('call spc_Tabla_ListaMaterialesConsumir(?,?)');
        $stmt->execute(array($sucursal_id, $PKEmpresa));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id = $r['id'];
            $sucursal = $r['sucursal'];
            $claveInterna = $r['ClaveInterna'];
            $nombre = str_replace(["\r", "\n"], "", $r['Nombre']);
            $existencia = $r['existencia'];
            $materiales_consumir = $r['materiales_consumir'];
            $materiales_consumir2 = $r['materiales_consumir2'];
            $unidad = $r['unidad'];

            $textId = '<span class=\"textTable\">' . $id . '</span>';
            $textSucursal = '<span class=\"textTable\">' . $sucursal . '</span>';
            $textName = '<span class=\"textTable\">' . $nombre . '</span>';
            $textInternalKey = '<span class=\"textTable\">' . $claveInterna . '</span>';
            $textStock = '<span class=\"textTable\">' . $existencia . '</span>';
            if($materiales_consumir2 == null){
                $textMaterialesConsumir = '<span class=\"textTable\">' . $materiales_consumir . '</span>';
            }else{
                $textMaterialesConsumir = '<span class=\"textTable\">' . $materiales_consumir2 . '</span>';
            }

            $table .= '{
                "Id":"' . $textId . '",
                "Sucursal":"' . $textSucursal . '",
                "ClaveInterna":"' . $textInternalKey . '",
                "Nombre":"' . $textName . '",
                "Existencia":"' . $textStock . '",
                "MaterialesConsumir":"' . $textMaterialesConsumir . '",
                "Unidad":"' . $unidad . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';

    }

    /////////////////////////COMBOS//////////////////////////////
    
    public function getCmbProductos($PKProducto)
    {
        $con = new conectar();
        $db = $con->getDb();
        $table = "";

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $stmt = $db->prepare('call spc_Combo_ProductosNoEmpaques(?,?)');
        $stmt->execute(array($PKEmpresa, $PKProducto));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id = $r['id'];
            $claveInterna = $r['claveInterna'];
            $nombre = $r['nombre'];
            $fkEstatusGeneral = $r['fkEstatusGeneral'];
            $unidadMedida = $r['unidadMedida'];
            $costoFabri = $r['costoFabri'];
            $moneda = $r['tipoMoneda'];
            $fkMoneda = $r['fkTipoMoneda'];

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';
            $acciones = '<i><img class=\"btnEdit\" data-dismiss=\"modal\" onclick=\"obtenerIdProductoSeleccionar(\'' . $id . '\',\'' . $claveInterna . '\',\'' . $nombre . '\',\'' . $unidadMedida . '\',\'' . $costoFabri . '\',\'' . $moneda . '\',\'' . $fkMoneda . '\');\" src=\"../../../../img/timdesk/ICONO ESTADO-TERMINADO_Mesa de trabajo 1.svg\"></i>';

            $table .= '{"Id":"' . $etiquetaI . $id . $etiquetaF . '",
                "ClaveInterna":"' . $etiquetaI . $claveInterna . $etiquetaF . '",
                "Nombre":"' . $etiquetaI . $nombre . $etiquetaF . $acciones . '",
                "Estatus":"' . $etiquetaI . $fkEstatusGeneral . $etiquetaF . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }
    
    public function getCmbEmpaques($PKProducto)
    {
        $con = new conectar();
        $db = $con->getDb();
        $table = "";

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $stmt = $db->prepare('call spc_Combo_Empaques(?,?)');
        $stmt->execute(array($PKEmpresa, $PKProducto));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id = $r['id'];
            $claveInterna = $r['claveInterna'];
            $nombre = $r['nombre'];
            $fkEstatusGeneral = $r['fkEstatusGeneral'];
            $unidadMedida = $r['unidadMedida'];
            $costoFabri = $r['costoFabri'];
            $moneda = $r['tipoMoneda'];
            $fkMoneda = $r['fkTipoMoneda'];

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';
            $acciones = '<i><img class=\"btnEdit\" data-dismiss=\"modal\" onclick=\"obtenerIdEmpaquesSeleccionar(\'' . $id . '\',\'' . $claveInterna . '\',\'' . $nombre . '\',\'' . $unidadMedida . '\',\'' . $costoFabri . '\',\'' . $moneda . '\',\'' . $fkMoneda . '\');\" src=\"../../../../img/timdesk/ICONO ESTADO-TERMINADO_Mesa de trabajo 1.svg\"></i>';

            $table .= '{"Id":"' . $etiquetaI . $id . $etiquetaF . '",
                "ClaveInterna":"' . $etiquetaI . $claveInterna . $etiquetaF . '",
                "Nombre":"' . $etiquetaI . $nombre . $etiquetaF . $acciones . '",
                "Estatus":"' . $etiquetaI . $fkEstatusGeneral . $etiquetaF . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getCmbProductoListaCompuestos()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Combo_Productos_ListaNoCompuestos(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getCmbMoneda()
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Combo_Moneda()');
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /////////////////////////DATOS PARA EDICIÓN//////////////////////////////

    public function getDataDatosProducto($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Datos_Prod_ConsultaGeneral(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getDataDatosProductoCompuesto($pkProducto)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        $query = sprintf('call spc_Datos_Prod_ConsultaGeneralCompuestos(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkProducto, $PKuser));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getDataDatosEmpaqueCompuesto($pkProducto)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Datos_Emp_ConsultaGeneralCompuestos(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkProducto));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
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

    public function validarProductoCompuestoTemp($pkProducto)
    {
        $con = new conectar();
        $db = $con->getDb();

        if (isset($_SESSION["Usuario"])) {
            $PKuser = $_SESSION["PKUsuario"];
        }

        $query = sprintf('call spc_ValidarUnicoProductoCompTemp(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkProducto, $PKuser));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    /////////////////////////INFO//////////////////////////////
    
    /////////////////////////COMBOS//////////////////////////////

    function getSucursales()
    {
      $con = new conectar();
      $db = $con->getDb();

      $query = sprintf("select id, sucursal texto from sucursales where empresa_id = :id and activar_inventario = 1");
      $stmt = $db->prepare($query);
      $stmt->bindValue(":id",$_SESSION["IDEmpresa"]);
      $stmt->execute();

      return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    //END JAVIER RAMIREZ
}

class save_data
{
    //JAVIER RAMIREZ

    public function saveDatosProductoCompTemp($pkProducto, $cantidad, $PKCompuestoTemp, $costo, $moneda, $colectivos)
    {
        $con = new conectar();
        $db = $con->getDb();

        if (isset($_SESSION["Usuario"])) {
            $PKuser = $_SESSION["PKUsuario"];
        }

        try {
            $query = sprintf('call spi_Prod_AgregarProdCompuestoTemp (?,?,?,?,?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKuser, $pkProducto, $cantidad, $PKCompuestoTemp, $costo, $moneda, $colectivos));

            $data[0] = ['status' => $status];

            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveDatosProductoCompTempAll($pkProducto)
    {
        $con = new conectar();
        $db = $con->getDb();

        if (isset($_SESSION["Usuario"])) {
            $PKuser = $_SESSION["PKUsuario"];
        }

        try {
            $query = sprintf('call spi_Prod_AgregarProdCompuestoTempAll (?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKuser, $pkProducto));

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
    
    public function editListaMaterialesProducto($value, $pkProducto, $estatus)
    {
        $con = new conectar();
        $db = $con->getDb();

        //$fkEstatusGeneral = $value['estatus'];
        if (isset($_SESSION["Usuario"])) {
            $PKuser = $_SESSION["PKUsuario"];
        }

        try {
            $query = sprintf('call spu_Prod_ActualizarLstMaterialesGeneral (?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($estatus, $pkProducto, $PKuser));

            $data[0] = ['status' => $status];

            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editDatosCantidadProductoCompTemp($pkProducto, $cantidad, $costo, $moneda)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_Prod_CantidadProdCompuestoTemp_Actualizar (?,?,?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKuser, $pkProducto, $cantidad, $costo, $moneda));

            $data[0] = ['status' => $status];

            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editDatosCostoProductoCompTemp($pkProducto, $costo, $moneda)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_Prod_CostoProdCompuestoTemp_Actualizar (?,?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKuser, $pkProducto, $costo, $moneda));

            $data[0] = ['status' => $status];

            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editDatosMonedaProductoCompTemp($pkProducto, $costo, $moneda)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_Prod_CostoProdCompuestoTemp_Actualizar (?,?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKuser, $pkProducto, $costo, $moneda));

            $data[0] = ['status' => $status];

            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editDatosUnidadMProductoCompTemp($pkProducto, $costo, $moneda, $unidadMedidaID)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_Prod_UnidadMedidaProdCompuestoTemp_Actualizar (?,?,?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKuser, $pkProducto, $costo, $moneda, $unidadMedidaID));

            $data[0] = ['status' => $status];

            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editColectivosEmpaqueCompTemp($pkProducto, $colectivos)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_ColectivosEmpCompuestoTemp_Actualizar (?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKuser, $pkProducto, $colectivos));

            $data[0] = ['status' => $status];

            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editDatosListaMaterialEstatus($pkProducto)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_Prod_Estatus_Actualizar (?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKuser, $pkProducto));

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

    public function deleteDatosProductoCompTemp($pkProducto)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spd_EliminarProdCompuestoTemp(?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKuser, $pkProducto));

            $data[0] = ['status' => $status];
            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }
    
    public function deleteDatosProductoCompTempAll()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spd_EliminarProdCompuestoTempAll(?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKuser));

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