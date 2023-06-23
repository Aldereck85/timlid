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
    
    public function getNotaCreditoTable($isPermissionsEdit,$isPermissionsDelete)
    {
        $con = new conectar();
        $db = $con->getDb();

        $table = "";
        $PKEmpresa = $_SESSION["IDEmpresa"];

        $stmt = $db->prepare('call spc_Tabla_NotasCredito_Consulta(?)');
        $stmt->execute(array($PKEmpresa));
        $array = $stmt->fetchAll();

        //$acciones = '';

        foreach ($array as $r) {

            $Id = $r['id'];
            $estatus = $r['estatus'];
            $folioSerie = $r['folioSerie'];
            $proveedor = $r['proveedor'];
            $importe = $r['importe'];
            $fecha = $r['fecha'];
            $factura = $r['factura'];
            $tipo = $r['tipo'];

            //$acciones = '';
                
            if ($isPermissionsEdit == '1'){
                //$acciones = '<i class=\"fas fa-edit pointer\" onclick=\"obtenerEditarNotaCredito(\''.$Id.'\');\"></i>';
            }

            if ($isPermissionsDelete == '1'){
                //$acciones = $acciones . '<i class=\"fas fa-trash-alt pointer\" data-toggle=\"modal\" data-target=\"#eliminar_NotaCredito\" onclick=\"obtenerDatosEliminarNotaCredito(\''.$Id.'\');\"></i>';
            }

            $folioSerie = '<a href=\"#\" onclick=\"obtenerEditarNotaCredito(\''.$Id.'\');\">'.$folioSerie.'</a>';

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';

            $table .= '{"Id":"' . $etiquetaI . $Id . $etiquetaF . '",
                  "Estatus":"' . $etiquetaI . $estatus . $etiquetaF . '",
                  "FolioSerie":"' . $etiquetaI . $folioSerie . $etiquetaF . '",
                  "Proveedor":"' . $etiquetaI . $proveedor . $etiquetaF . '",
                  "Importe":"' . $etiquetaI . $importe . $etiquetaF . '",
                  "Fecha":"' . $etiquetaI . $fecha . $etiquetaF . '",
                  "Factura":"' . $etiquetaI . $factura . $etiquetaF . '",
                  "Tipo":"' . $etiquetaI . $tipo . $etiquetaF . '",
                  "Acciones":""},';

        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getProductosDevolucionTable($pkDevolucion)
    {
        $con = new conectar();
        $db = $con->getDb();

        $table = "";
        $PKEmpresa = $_SESSION["IDEmpresa"];

        $stmt = $db->prepare('call spc_Tabla_Devolucion_Productos_Consulta(?,?)');
        $stmt->execute(array($pkDevolucion, $PKEmpresa));
        $array = $stmt->fetchAll();

        $acciones = '';

        foreach ($array as $r) {

            $Id = $r['id'];
            $clave = $r['clave'];
            $producto = $r['producto'];
            $cantidad = $r['cantidad'];

            $acciones = '<i><img class=\"btnEdit\" src=\"../../../../img/timdesk/ICONO ESTADO-TERMINADO_Mesa de trabajo 1.svg\" onclick=\"seleccionarProducto(\''.$Id.'\');\"></i>';


            $etiquetaI = '<label class=\"textTable\">';
            $etiquetaF = '</label>';

            $table .= '{"Id":"' . $etiquetaI . $Id . $etiquetaF . '",
                  "Clave":"' . $etiquetaI . $clave . $etiquetaF . '",
                  "Producto":"' . $etiquetaI . $producto . $etiquetaF . '",
                  "Cantidad":"' . $etiquetaI . $cantidad . $etiquetaF . " ". $acciones .'"},';

        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getNotaCreditoProductosCantidadTable($pkDevolucion)
    {
        $con = new conectar();
        $db = $con->getDb();

        $table = "";
        $PKEmpresa = $_SESSION["IDEmpresa"];
        $PKuser = $_SESSION["PKUsuario"];

        $stmt = $db->prepare('call spc_Tabla_NotaCredito_Cantidad_productos(?,?,?)');
        $stmt->execute(array($PKuser, $PKEmpresa,$pkDevolucion));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {

            $Id = $r['id'];
            $clave = $r['clave'];
            $producto = $r['producto'];
            $cantidad = $r['cantidad'];
            $costo = $r['costo'];
            $total = $r['total'];
            $devolucionID = $r['devolucion_id'];

            $acciones = '<i><img class=\"btnEdit\" data-toggle=\"modal\" data-target=\"#eliminar_NotaCreditoProductoTemp\" onclick=\"obtenerDatosEliminarNotaCreditoTemp(\''.$Id.'\');\" src=\"../../../../img/timdesk/delete.svg\"></i>';

            $cantidadEditable = '<div class=\"col-lg-12 input-group\">' .
                                    '<input class=\"form-control numeric-only\" type=\"number\" name=\"txtCant-'.$Id.'\" id=\"txtCant-'.$Id.'\" value=\"'.$cantidad.'\" required maxlength=\"5\" onchange=\"validEmptyInput(\'txtCant-'.$Id.'\', \'invalid-cant-'.$Id.'\', \'La cantidad debe de ser mayor a 0.\')\">' .
                                    '<div class=\"invalid-feedback\" id=\"invalid-cant-'.$Id.'\">La cantidad debe de ser mayor a 0.</div>'.
                                '</div>';

            $etiquetaI = '<label class=\"textTable\">';
            $etiquetaF = '</label>';

            $table .= '{"Id":"' . $etiquetaI . $Id . $etiquetaF . '",
                  "Clave":"' . $etiquetaI . $clave . $etiquetaF . '",
                  "Producto":"' . $etiquetaI . $producto . $etiquetaF . '",
                  "Cantidad":"' . $etiquetaI . $cantidadEditable . $etiquetaF . '",
                  "Costo":"' . $etiquetaI .'$ '. $costo . $etiquetaF . '",
                  "Total":"' . $etiquetaI .'$ '. $total . $etiquetaF . '",
                  "Acciones":"' . $etiquetaI . $acciones . $etiquetaF . '"},';

        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    /////////////////////////COMBOS//////////////////////////////
    
    public function getCmbNotaCreditoCuentaPagar()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Combo_NotaCredito_CuentaPagar(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa,0));
        return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getCmbNotaCreditoCantCuentaPagar($pkDevolucion)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Combo_NotaCredito_CuentaPagar(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa, $pkDevolucion));
        return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getCmbNotaCreditoProveedor()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Combo_NotaCredito_Proveedor(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));
        return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getCmbNotaCreditoProveedorDevolucion()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Combo_NotaCredito_ProveedorDevolucion(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));
        return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getCmbNotaCreditoDevolucion($pkProveedor)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Combo_NotaCredito_Devolucion(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa, $pkProveedor));
        return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getCmbNotaCreditoDevolucionCant($pkProveedor)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Combo_NotaCredito_DevolucionCant(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa, $pkProveedor));
        return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /////////////////////////DATOS PARA EDICIÓN//////////////////////////////
    
    public function getDataNCMonto($pkNotaCredito){
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];
        $PKuser = $_SESSION["PKUsuario"];

        $query = sprintf('call spc_Datos_NotaCredito_Monto(?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa, $PKuser, $pkNotaCredito));
        return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getDataNCCantidad($pkNotaCredito){
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];
        $PKuser = $_SESSION["PKUsuario"];

        $query = sprintf('call spc_Datos_NotaCredito_Cantidad(?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa, $PKuser, $pkNotaCredito));
        return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
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

    public function validarNotaCreditoCantidadProd_devolucion($idCantidadTemp, $cantidad){
        $con = new conectar();
        $db = $con->getDb();

        if (isset($_SESSION["Usuario"])) {
            $PKuser = $_SESSION["PKUsuario"];
        }

        $query = sprintf('call spc_Validar_NotaCredito_CantProd_Temp(?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKuser, $idCantidadTemp, $cantidad));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    /////////////////////////INFO//////////////////////////////
    
    public function getSubTotalNotaCreditoCantTemp()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        $query = sprintf('call spc_Info_NotaCreditoCant_Subtotal(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKuser));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getImpuestoNotaCreditoCantTemp()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        $query = sprintf('call spc_Info_NotaCreditoCant_Impuestos(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKuser));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getTotalNotaCreditoCantTemp()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        $query = sprintf('call spc_Info_NotaCreditoCant_Total(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKuser));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    //END JAVIER RAMIREZ
}

class save_data
{
    //JAVIER RAMIREZ
    
    public function saveDatosNotaCreditoMonto($array, $tipoGral, $serie, $folio, $importe, $subtotal, $iva, $ieps, $fechaNota, $tipoNota, $archivoPDF, $archivoXML, $proveedor, $folioFiscal, $cuentaPagar, $pkNotaCredito)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];
        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spi_NotasCredito_AgregarGeneral(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($tipoGral, $serie, $folio, $importe, $subtotal, $iva, $ieps, $fechaNota, $tipoNota, $archivoPDF, $archivoXML, $proveedor, $folioFiscal, $cuentaPagar, $PKEmpresa, $PKuser, $pkNotaCredito));
            $PKNotaCredito = $stmt->fetch()['0'];

            $data[0] = ['status' => $status, 'id' => $PKNotaCredito];

            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveDatosNotaCreditoCantidad($array, $tipoGral, $serie, $folio, $importe, $subtotal, $iva, $ieps, $fechaNota, $tipoNota, $archivoPDF, $archivoXML, $proveedor, $devolucion, $folioFiscal, $cuentaPagar, $pkNotaCredito)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];
        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spi_NotasCreditoCantidad_AgregarGeneral(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($tipoGral, $serie, $folio, $importe, $subtotal, $iva, $ieps, $fechaNota, $tipoNota, $archivoPDF, $archivoXML, $proveedor, $devolucion, $folioFiscal, $cuentaPagar, $PKEmpresa, $PKuser, $pkNotaCredito));
            $PKNotaCredito = $stmt->fetch()['0'];

            $data[0] = ['status' => $status, 'id' => $PKNotaCredito];

            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveDatosNotaCreditoProductoCant($idDetalleDev, $pkDevolucion){
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];
        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spi_NotasCreditoCant_AgregarProducto(?,?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($idDetalleDev, $pkDevolucion, $PKEmpresa, $PKuser));

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
    
    public function editCantidadNotaCreditoProductoTemp($idDetalleNCTemp, $cantidad){
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_NotasCreditoCant_ActualizarCantidad(?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($idDetalleNCTemp, $cantidad, $PKuser));

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
    
    public function deleteDatosNotaCreditoCantidadAllTemp()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spd_EliminarNotaCreditoCantidadTemp(?)');
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
    
    public function deleteDatosNotaCreditoProductoTemp($idDetalleNCTemp)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spd_EliminarNotaCreditoProductoTemp(?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKuser, $idDetalleNCTemp));

            $data[0] = ['status' => $status];
            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function deleteInsertdatosNCCantidadAllTemp($pkDevolucion, $pkNotaCredito)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];
        $PKEmpresa = $_SESSION["IDEmpresa"];

        try {
            $query = sprintf('call spd_EliminarInsertNCCantidadTemp(?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKEmpresa, $PKuser, $pkDevolucion, $pkNotaCredito));

            $data[0] = ['status' => $status];
            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function deleteDatosNotaCredito($pkNotaCredito)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];
        $PKEmpresa = $_SESSION["IDEmpresa"];

        try {
            $query = sprintf('call spd_EliminarNotaCredito(?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKEmpresa, $PKuser, $pkNotaCredito));

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