<?php

use get_data as GlobalGet_data;
use save_data as GlobalSave_data;

session_start();
date_default_timezone_set('America/Mexico_City');

class conectar
{ //Llamado al archivo de la conexión.


    public function getDb()
    {
        include "../../../include/db-conn.php";
        return $conn;
    }
}

class cleaner
{
    //elimina acentos de cadena
    public function eliminar_acentos($cadena)
    {
        //Reemplazamos la A y a
        $cadena = str_replace(
            array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'),
            array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
            $cadena
        );

        //Reemplazamos la E y e
        $cadena = str_replace(
            array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),
            array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
            $cadena
        );

        //Reemplazamos la I y i
        $cadena = str_replace(
            array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
            array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
            $cadena
        );

        //Reemplazamos la O y o
        $cadena = str_replace(
            array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
            array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
            $cadena
        );

        //Reemplazamos la U y u
        $cadena = str_replace(
            array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
            array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
            $cadena
        );

        //Reemplazamos la N, n, C y c
        $cadena = str_replace(
            array('Ñ', 'ñ', 'Ç', 'ç'),
            array('N', 'n', 'C', 'c'),
            $cadena
        );

        return $cadena;
    }
}

class get_data
{

    /////////////////////////TABLAS//////////////////////////////

    /*public function getEntriesTable()
    {
        $con = new conectar();
        $db = $con->getDb();
        $prefijo = "";
        $table = "";
        $no = 1;
        $stmt = $db->prepare('SELECT * FROM entradas_inventarios
                              LEFT JOIN tipos_entradas_inventarios ON entradas_inventarios.FKTipoEntrada = tipos_entradas_inventarios.PKTipoEntrada
                              LEFT JOIN almacenes ON entradas_inventarios.FKAlmacen = almacenes.PKAlmacen
                              LEFT JOIN prueba_rh.usuarios AS usuarios ON entradas_inventarios.FKUsuario = usuarios.PKUsuario
                              LEFT JOIN prueba_rh.empleados AS empleados ON usuarios.FKEmpleado = empleados.PKEmpleado');
        $stmt->execute();
        $array = $stmt->fetchAll();
        foreach ($array as $r) {
            $usuario = $r['Nombres'] . " " . $r['PrimerApellido'];
            $fechaHora = date("d/m/Y H:i:s", strtotime($r['Fecha']));
            $number = str_pad($r['PKEntradaInventario'], 5, "0", STR_PAD_LEFT);
            switch ($r['FKTipoEntrada']) {
                case '1':
                    $prefijo = "C";
                    break;
                case '2':
                    $prefijo = "D";
                    break;
                case '3':
                    $prefijo = "F";
                    break;
                case '4':
                    $prefijo = "A";
                    break;
                case '5':
                    $prefijo = "T";
                    break;
            }
            $folio = '<a href=\"../tareas/timDesk/index.php?id=' . $r['PKEntradaInventario'] . '\"  class=\"linkTable link\">' . $prefijo . $number . '</a>';
            $fecha = '<span class=\"textTable\">' . $fechaHora . '</span>';
            $tipo = '<span class=\"textTable\">' . $r['TipoEntrada'] . '</span>';
            $mensaje = 'hola';
            $usuario = '<span class=\"textTable\">' . $usuario . '</span><i><img class=\"btnEdit\" onclick=\"getIdEdit(' . $r['PKEntradaInventario'] . ');\" src=\"../../../../img/timdesk/edit.svg\"></i>';
            $table .= '{"Id":"' . $r['PKEntradaInventario'] . '","Folio":"' . $folio . '","Fecha":"' . $fecha . '","Tipo de entrada":"' . $tipo . '","Usuario":"' . $usuario . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);
        return '{"data":[' . $table . ']}';
    }*/

    public function getBrands()
    {
        $con = new conectar();
        $db = $con->getDb();

        $table = "";

        $stmt = $db->prepare('SELECT * FROM marcas_productos');
        $stmt->execute();
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $table .= '{"Id":"' . $r['PKMarcaProducto'] . '","Marcas":"' . $r['MarcaProducto'] . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    //JAVIER RAMIREZ
    public function getExitsTable()
    {
        $con = new conectar();
        $db = $con->getDb();

        $table = "";
        $PKEmpresa = $_SESSION["IDEmpresa"];

        $stmt = $db->prepare('call spc_Tabla_Salidas_Consulta(?)');
        $stmt->execute(array($PKEmpresa));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {

            $Id = $r['id'];
            $Folio = $r['folio'];
            $Origen = $r['origen'];
            $Fecha = $r['fecha'];
            $Tipo = $r['tipo'];
            $isMovimiento = $r['is_movimiento'];
            $isFacturado = $r['is_facturado'];
            $isFacturadoV = $r['is_facturadoV'];
            $Destino = $r['destino'];

            if ($isMovimiento == 1) {
                $acciones = '<i class=\"fas fa-clipboard-list pointer\" onclick=\"obtenerVer(\'' . $Folio . '\');\"><input type=\"hidden\" id=\"inp-' . $Id . '\"></i>';
            } elseif ($isFacturado == 1) {
                $acciones = '<i class=\"fas fa-clipboard-list pointer\" onclick=\"obtenerVer(\'' . $Folio . '\');\"><input type=\"hidden\" id=\"inp-' . $Id . '\"></i>';
            } elseif ($isFacturadoV == 1) {
                $acciones = '<i class=\"fas fa-clipboard-list pointer\" onclick=\"obtenerVer(\'' . $Folio . '\');\"><input type=\"hidden\" id=\"inp-' . $Id . '\"></i>';
            } else {
                $acciones = '<i class=\"fas fa-edit pointer\" onclick=\"obtenerEditar(\'' . $Folio . '\');\"><input type=\"hidden\" id=\"inp-' . $Id . '\"></i>';
            }

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';

            $table .= '{"Id":"' . $etiquetaI . $Id . $etiquetaF . '",
                  "Folio":"' . $etiquetaI . $Folio . $etiquetaF . '",
                  "Origen":"' . $etiquetaI . $Origen . $etiquetaF . '",
                  "Destino":"' . $etiquetaI . $Destino . $etiquetaF . '",
                  "Fecha":"' . $etiquetaI . $Fecha . $etiquetaF . '",
                  "Acciones":"' . $etiquetaI . $acciones . $etiquetaF . '",
                  "Tipo_Salida":"' . $etiquetaI . $Tipo . $etiquetaF . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getEntriesTable()
    {
        $con = new conectar();
        $db = $con->getDb();

        $table = "";
        $PKEmpresa = $_SESSION["IDEmpresa"];

        $stmt = $db->prepare('call spc_Tabla_Entradas_Consulta(?)');
        $stmt->execute(array($PKEmpresa));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {

            $Id = $r['id'];
            $Folio = $r['folio'];
            $Origen = $r['origen'];
            $Fecha = $r['fecha'];
            $Tipo = $r['tipo'];
            $TipoID = $r['tipoId'];
            $Referencia = $r['referencia'];
            $isMovimiento = $r['is_movimiento'];

            if ($TipoID == '1' || $TipoID == '2') {
                if ($isMovimiento == '0') {
                    $acciones = '<input type=\"hidden\" value=\"' . $Id . '\" id=\"inp-' . $Id . '\"><i class=\"fas fa-trash-alt pointer\" onclick=\"obtenerEliminar(\'' . $Folio . '\');\"</i><i class=\"fas fa-edit pointer\" onclick=\"obtenerEditar(\'' . $Folio . '\',\'' . $TipoID . '\');\"></i>';
                } else {
                    $acciones = '<i class=\"fas fa-clipboard-list pointer\" onclick=\"obtenerVer(\'' . $Folio . '\',\'' . $TipoID . '\');\"><input type=\"hidden\" value=\"' . $Id . '\" id=\"inp-' . $Id . '\"></i>';
                }
            } else {
                $acciones = '';
            }

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';

            $table .= '{"Id":"' . $etiquetaI . $Id . $etiquetaF . '",
                  "Folio":"' . $etiquetaI . $Folio . $etiquetaF . '",
                  "Origen":"' . $etiquetaI . $Origen . $etiquetaF . '",
                  "Fecha":"' . $etiquetaI . $Fecha . $etiquetaF . '",
                  "Referencia":"' . $etiquetaI . $Referencia . $etiquetaF . '",
                  "Acciones":"' . $etiquetaI . $acciones . $etiquetaF . '",
                  "Tipo_Entrada":"' . $etiquetaI . $Tipo . $etiquetaF . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getEntriesTableFilter($branchID, $typeEntryID, $fromDate, $toDate)
    {
        $con = new conectar();
        $db = $con->getDb();

        $table = "";
        $PKEmpresa = $_SESSION["IDEmpresa"];

        $stmt = $db->prepare('call spc_Tabla_Entradas_ConsultaFilter(?,?,?,?,?)');
        $stmt->execute(array($PKEmpresa, $branchID, $typeEntryID, $fromDate, $toDate));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {

            $Id = $r['id'];
            $IdAjuste = $r['idAjuste'];
            $FechaAjuste = $r['fechaAjuste'];
            $UsuarioAjuste = $r['nombre'];
            $Folio = $r['folio'];
            $Origen = $r['origen'];
            $Fecha = $r['fecha'];
            $Tipo = $r['tipo'];
            $TipoID = $r['tipoId'];
            $Referencia = $r['referencia'];
            $isMovimiento = $r['is_movimiento'];

            $cliente = $r['cliente'];
            $proveedor = $r['proveedor'];
            $sucOrigen = $r['sucOrigen'];

            /* if($TipoID == '1' ){
                if ($isMovimiento == '0'){
                    $acciones = '<input type=\"hidden\" value=\"' . $Id . '\" id=\"inp-' . $Id . '\"><i class=\"fas fa-trash-alt pointer\" onclick=\"obtenerEliminar(\'' . $Folio . '\',\'' . $TipoID . '\',\'' . $cliente . '\',\'' . $proveedor . '\',\'' . $sucOrigen . '\')\"></i><i class=\"fas fa-edit pointer\" onclick=\"obtenerEditar(\'' . $Folio . '\',\'' . $TipoID . '\')\"></i>';  
                }else{
                    $acciones = '<input type=\"hidden\" value=\"' . $Id . '\" id=\"inp-' . $Id . '\"><i <i class=\"fas fa-clipboard-list pointer\" onclick=\"obtenerVer(\'' . $Folio . '\')\"></i>';   
                }   
            }else if($TipoID == '2' ){
                if ($isMovimiento == '0'){
                    $acciones = '<input type=\"hidden\" value=\"' . $Id . '\" id=\"inp-' . $Id . '\"><i class=\"fas fa-trash-alt pointer\" onclick=\"obtenerEliminar(\'' . $Folio . '\',\'' . $TipoID . '\',\'' . $cliente . '\',\'' . $proveedor . '\',\'' . $sucOrigen . '\')\"></i><i class=\"fas fa-edit pointer\" onclick=\"obtenerEditar(\'' . $Folio . '\',\'' . $TipoID . '\')\"></i>';  
                }else{
                    $acciones = '<input type=\"hidden\" value=\"' . $Id . '\" id=\"inp-' . $Id . '\"><i <i class=\"fas fa-clipboard-list pointer\" onclick=\"obtenerVer(\'' . $Folio . '\')\"></i>';   
                }  
            }else if($TipoID == '3' ){
                if ($isMovimiento == '0'){
                    $acciones = '<input type=\"hidden\" value=\"' . $Id . '\" id=\"inp-' . $Id . '\"><i class=\"fas fa-trash-alt pointer\" onclick=\"obtenerEliminar(\'' . $Folio . '\',\'' . $TipoID . '\',\'' . $cliente . '\',\'' . $proveedor . '\',\'' . $sucOrigen . '\')\"></i><i class=\"fas fa-edit pointer\" onclick=\"obtenerEditar(\'' . $Folio . '\',\'' . $TipoID . '\')\"></i>';  
                }else{
                    $acciones = '<input type=\"hidden\" value=\"' . $Id . '\" id=\"inp-' . $Id . '\"><i <i class=\"fas fa-clipboard-list pointer\" onclick=\"obtenerVer(\'' . $Folio . '\')\"></i>';   
                }  
            }else if($TipoID == '4 ' ){
                if ($isMovimiento == '0'){
                    $acciones = '<input type=\"hidden\" value=\"' . $Id . '\" id=\"inp-' . $Id . '\"><i class=\"fas fa-trash-alt pointer\" onclick=\"obtenerEliminar(\'' . $Folio . '\',\'' . $TipoID . '\',\'' . $cliente . '\',\'' . $proveedor . '\',\'' . $sucOrigen . '\')\"></i><i class=\"fas fa-edit pointer\" onclick=\"obtenerEditar(\'' . $Folio . '\',\'' . $TipoID . '\')\"></i>';  
                }else{
                    $acciones = '<input type=\"hidden\" value=\"' . $Id . '\" id=\"inp-' . $Id . '\"><i <i class=\"fas fa-clipboard-list pointer\" onclick=\"obtenerVer(\'' . $Folio . '\')\"></i>';   
                }  
            }else{
                $acciones = '';
            } */
            $Folio = '<span style=\"cursor: pointer;\" onclick=\"obtenerEditar(\'' . $Folio . '\',\'' . $TipoID . '\',\'' . $IdAjuste . '\',\'' . $Origen . '\',\'' . $FechaAjuste . '\',\'' . $UsuarioAjuste . '\')\">' . $Folio . '</span>';

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';

            $table .= '{"Id":"' . $etiquetaI . $Id . $etiquetaF . '",
                  "Folio":"' . $etiquetaI . $Folio . $etiquetaF . '",
                  "Origen":"' . $etiquetaI . $Origen . $etiquetaF . '",
                  "Fecha":"' . $etiquetaI . $Fecha . $etiquetaF . '",
                  "Referencia":"' . $etiquetaI . $Referencia . $etiquetaF . '",
                  "Tipo_Entrada":"' . $etiquetaI . $Tipo . $etiquetaF . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getExitsTableFilter($branchID, $typeExitID, $fromDate, $toDate)
    {
        $con = new conectar();
        $db = $con->getDb();

        $table = "";
        $PKEmpresa = $_SESSION["IDEmpresa"];

        $stmt = $db->prepare('call spc_Tabla_Salidas_ConsultaFilter(?,?,?,?,?)');
        $stmt->execute(array($PKEmpresa, $branchID, $typeExitID, $fromDate, $toDate));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {

            $Id = $r['id'];
            $Folio = $r['folio'];
            $Origen = $r['origen'];
            $Fecha = $r['fecha'];
            $Tipo = $r['tipo'];
            $isMovimiento = $r['is_movimiento'];
            $isFacturado = $r['is_facturado'];
            $isFacturadoV = $r['is_facturadoV'];
            $Destino = str_replace('"', '\"', $r['destino']);
            $acciones = '<input type=\"hidden\" id=\"inp-' . $Id . '\">';
            $acciones = '';
            /* if ($isMovimiento == 1 || $isFacturado == 1 || $isFacturadoV == 1) {
                $acciones .= '<i class=\"fas fa-clipboard-list pointer\" onclick=\"obtenerVer(\'' . $Folio . '\');\"></i>';
            } else{
                $acciones .= '<i class=\"fas fa-edit pointer\" onclick=\"obtenerEditar(\'' . $Folio . '\');\"></i>';
            } */
            $Folio = '<span class=\"pointer\" onclick=\"obtenerEditar(\'' . $Folio . '\');\">' . $Folio . '</span>';

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';

            $table .= '{"Id":"' . $etiquetaI . $Id . $etiquetaF . '",
                  "Folio":"' . $etiquetaI . $Folio . $etiquetaF . '",
                  "Origen":"' . $etiquetaI . $Origen . $etiquetaF . '",
                  "Destino":"' . $etiquetaI . $Destino . $etiquetaF . '",
                  "Fecha":"' . $etiquetaI . $Fecha . $etiquetaF . '",
                  "Tipo_Salida":"' . $etiquetaI . $Tipo . $etiquetaF . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getPurchaseOrders($permissionEdit)
    {
        $con = new conectar();
        $db = $con->getDb();

        $table = "";
        $PKEmpresa = $_SESSION["IDEmpresa"];

        $stmt = $db->prepare('call spc_Tabla_OrdenesCompra_Consulta(?)');
        $stmt->execute(array($PKEmpresa));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {

            $Id = $r['PKOrdenCompra'];
            $Referencia = $r['Referencia'];
            $FechaCreacion = $r['FechaCreacion'];
            $FechaEstimadaEntrega = $r['FechaEstimada'];

            if ($r['FechaEntrega'] !== "" && $r['FechaEntrega'] !== null) {
                $fechaEntrega = $r['FechaEntrega'];
            } else {
                $fechaEntrega = "No se ha entregado el pedido";
            }

            $nombreComercial = $r['NombreComercial'];
            $importe = number_format($r['Importe'], 2, '.', ',');

            $EstatusOrden = $r['EstatusOrden'];
            $colorEstatus = '';
            $cierreEstatus = '</span>';
            //$acciones = '';

            if ($EstatusOrden == 'En espera') {
                $colorEstatus = '<span class=\"left-dot gray-dot\">';
                //$acciones = '<i><input type=\"hidden\" value=\"' . md5($Id) . '\" id=\"inp-' . $Id . '\"><i class=\"fas fa-clipboard-list pointer\"  onclick=\"obtenerVer(\'' . $Id . '\',\'1\');\"</i></i>';
                $Referencia = '<a style=\"cursor:pointer; color: #4e73df\" class=\"btn-table-custom btn-table-custom--blue\" onclick=\"obtenerVer(\'' . $Id . '\',\'1\');\">' . $Referencia . '</a>';
            } else if ($EstatusOrden == 'Vencida') {
                $colorEstatus = '<span class=\"left-dot red-dot\">';
                //$acciones = '<i><input type=\"hidden\" value=\"' . md5($Id) . '\" id=\"inp-' . $Id . '\"><i class=\"fas fa-clipboard-list pointer\"  onclick=\"obtenerVer(\'' . $Id . '\',\'2\');\"</i></i>';
                $Referencia = '<a style=\"cursor:pointer; color: #4e73df\" class=\"btn-table-custom btn-table-custom--blue\" onclick=\"obtenerVer(\'' . $Id . '\',\'2\');\">' . $Referencia . '</a>';
            } else if ($EstatusOrden == 'Aceptada') {
                $colorEstatus = '<span class=\"left-dot turquoise-dot\">';
                //$acciones = '<i><input type=\"hidden\" value=\"' . md5($Id) . '\" id=\"inp-' . $Id . '\"><i class=\"fas fa-clipboard-list pointer\"  onclick=\"obtenerVer(\'' . $Id . '\',\'3\');\"</i></i>';
                $Referencia = '<a style=\"cursor:pointer; color: #4e73df\" class=\"btn-table-custom btn-table-custom--blue\" onclick=\"obtenerVer(\'' . $Id . '\',\'3\');\">' . $Referencia . '</a>';
            } else if ($EstatusOrden == 'Cancelada') {
                $colorEstatus = '<span class=\"left-dot red-dot\">';
                //$acciones = '<i><input type=\"hidden\" value=\"' . md5($Id) . '\" id=\"inp-' . $Id . '\"><i class=\"fas fa-clipboard-list pointer\"  onclick=\"obtenerVer(\'' . $Id . '\',\'4\');\"</i></i>';
                $Referencia = '<a style=\"cursor:pointer; color: #4e73df\" class=\"btn-table-custom btn-table-custom--blue\" onclick=\"obtenerVer(\'' . $Id . '\',\'4\');\">' . $Referencia . '</a>';
            } else if ($EstatusOrden == 'Rechazada') {
                $colorEstatus = '<span class=\"left-dot red-dot\">';
                //$acciones = '<i><input type=\"hidden\" value=\"' . md5($Id) . '\" id=\"inp-' . $Id . '\"><i class=\"fas fa-clipboard-list pointer\"  onclick=\"obtenerVer(\'' . $Id . '\',\'5\');\"</i></i>';
                $Referencia = '<a style=\"cursor:pointer; color: #4e73df\" class=\"btn-table-custom btn-table-custom--blue\" onclick=\"obtenerVer(\'' . $Id . '\',\'5\');\">' . $Referencia . '</a>';
            } else if ($EstatusOrden == 'Aceptada-Demorada') {
                $colorEstatus = '<span class=\"left-dot yellow-dot\">';
                //$acciones = '<i><input type=\"hidden\" value=\"' . md5($Id) . '\" id=\"inp-' . $Id . '\"><i class=\"fas fa-clipboard-list pointer\"  onclick=\"obtenerVer(\'' . $Id . '\',\'6\');\"</i></i>';
                $Referencia = '<a style=\"cursor:pointer; color: #4e73df\" class=\"btn-table-custom btn-table-custom--blue\" onclick=\"obtenerVer(\'' . $Id . '\',\'6\');\">' . $Referencia . '</a>';
            } else if ($EstatusOrden == 'Cerrada') {
                $colorEstatus = '<span class=\"left-dot red-dot\">';
                //$acciones = '<i><input type=\"hidden\" value=\"' . md5($Id) . '\" id=\"inp-' . $Id . '\"><i class=\"fas fa-clipboard-list pointer\"  onclick=\"obtenerVer(\'' . $Id . '\',\'7\');\"</i></i>';
                $Referencia = '<a style=\"cursor:pointer; color: #4e73df\" class=\"btn-table-custom btn-table-custom--blue\" onclick=\"obtenerVer(\'' . $Id . '\',\'7\');\">' . $Referencia . '</a>';
            } else if ($EstatusOrden == 'Completa') {
                $colorEstatus = '<span class=\"left-dot green-dot\">';
                //$acciones = '<i><input type=\"hidden\" value=\"' . md5($Id) . '\" id=\"inp-' . $Id . '\"><i class=\"fas fa-clipboard-list pointer\"  onclick=\"obtenerVer(\'' . $Id . '\',\'8\');\"</i></i>';
                $Referencia = '<a style=\"cursor:pointer; color: #4e73df\" class=\"btn-table-custom btn-table-custom--blue\" onclick=\"obtenerVer(\'' . $Id . '\',\'8\');\">' . $Referencia . '</a>';
            }

            //$temp = "No hay fecha limite de pago"; "Fecha Limite de Pago":"'.$temp.'",

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';

            $table .= '{"Id":"' . $etiquetaI . $Id . $etiquetaF . '",
                  "Referencia":"' . $etiquetaI . $Referencia . $etiquetaF . '",
                  "FechaEmision":"' . $etiquetaI . $FechaCreacion . $etiquetaF . '",
                  "FechaEstimadaEntrega":"' . $etiquetaI . $FechaEstimadaEntrega . $etiquetaF . '",
                  "FechaEntrega":"' . $etiquetaI . $fechaEntrega . $etiquetaF . '",
                  "Proveedor":"' . $etiquetaI . $nombreComercial . $etiquetaF . '",
                  "Importe":"' . $etiquetaI . '$' . $importe . $etiquetaF . '",
                  "Acciones":"",
                  "EstatusOrden":"' . $colorEstatus . $EstatusOrden . $cierreEstatus . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getProductosEmpresa($PKSucursal)
    {
        $con = new conectar();
        $db = $con->getDb();
        $PKUsuario = $_SESSION["PKUsuario"];

        $table = "";

        $query = sprintf('call spc_Tabla_Productos_Empresa(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKSucursal));
        $array = $stmt->fetchAll();

        $date = date('Y-m-d');

        foreach ($array as $r) {
            $id_detalle = trim($r['id']);
            $id = trim($r['producto_id']);
            $clave = trim($r['clave']);
            $nombre = trim($r['Nombre']);
            $descripcion = trim($r['Descripcion']);
            $cantidad = trim($r['cantidad_toma']);
            //$serie = trim($r['serie']);
            $lote = trim($r['lote']);
            $fecha_caducidad = trim($r['caducidad']);
            $numero = trim($r['claves']);

            if ($cantidad == 0) {
                $cantidad = '';
            }
            $cantidadFinal = '<input class=\"form-control\" type=\"number\" min=\"0\" oninput=\"limitarLongitud(this)\" id=\"cant_' . $id_detalle . '\" value=\"' . $cantidad . '\" onkeyup=\"validarCantidad(this)\" onchange=\"inserPrevio(this)\"> <div class=\"d-none\">' . $cantidad . '</div>';

            $inputNombre = '<input type=\"hidden\" id=\"idNombre_' . $id_detalle . '\" value=\"' . $nombre . '\">';
            $inputDescripcion = '<input type=\"hidden\" id=\"idDescripcion_' . $id_detalle . '\" value=\"' . $descripcion . '\">';
            $inputIdDetalle = '<input type=\"hidden\" id=\"idDetalle_' . $id_detalle . '\" value=\"' . $id_detalle . '\">';
            $inputId = '<input type=\"hidden\" id=\"id_' . $id_detalle . '\" value=\"' . $id . '\">';
            $inputClave = '<input type=\"hidden\" id=\"clave_' . $id_detalle . '\" value=\"' . $clave . '\">';

            /*if ($serie == '0') {
                $serieFinal = '<input class=\"form-control\" type=\"text\" id=\"ser_' . $id_detalle . '\" disabled> <div class=\"d-none\">No aplica</div>';
            }else{
                $serieFinal = '<input class=\"form-control\" type=\"text\" maxlength=\"45\" id=\"ser_' . $id_detalle . '\" value=\"' . $serie . '\" onkeyup=\"validarCampo(this)\" onchange=\"inserPrevio(this)\"> <div class=\"d-none\">' . $serie . '</div>';
            }*/

            if ($lote == '0') {
                $loteFinal = '<input class=\"form-control\" type=\"text\" id=\"lot_' . $id_detalle . '\" disabled> <div class=\"d-none\">No aplica</div>';
            } else {
                $loteFinal = '<input class=\"form-control\" type=\"text\" maxlength=\"45\" id=\"lot_' . $id_detalle . '\" value=\"' . $lote . '\" onkeyup=\"validarCampo(this)\" onchange=\"inserPrevio(this)\"> <div class=\"d-none\">' . $lote . '</div>';
            }

            if ($fecha_caducidad == '0') {
                $fecha_caducidadFinal = '<input class=\"form-control\" type=\"date\"  id=\"fech_' . $id_detalle . '\" disabled> <div class=\"d-none\">No aplica</div>';
            } else {
                $fecha_caducidadFinal = '<input class=\"form-control\" type=\"date\" min=\"' . date('Y-m-d', strtotime($date . ' + 1 day')) . '\"  id=\"fech_' . $id_detalle . '\" value=\"' . $fecha_caducidad . '\" onchange=\"campoCaducidad(this)\"> <div class=\"d-none\">' . $fecha_caducidad . '</div>';
            }

            if ($numero > 1) {
                //if($serie != '0' || $lote != '0' || $fecha_caducidad != '0')
                if ($lote != '0' || $fecha_caducidad != '0') {
                    $accionAgregar = '<img class=\"ml-4 mt-2\" id=\"btnAgregar_' . $id_detalle . '\" width=\"15px\" height=\"15px\" src=\"../../../../img/timdesk/ICONO AGREGAR3.svg\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Agregar\">';
                    $accionEliminar = '<img class=\"ml-4 mt-1 mb-auto\" id=\"btnEliminar_' . $id_detalle . '\" width=\"15px\" height=\"15px\" src=\"../../../../img/chat/eliminar_equipo.svg\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Eliminar\">';
                } else {
                    $accionAgregar = '<img class=\"ml-4 d-none\" id=\"btnAgregar_' . $id_detalle . '\" width=\"15px\" height=\"15px\" src=\"../../../../img/timdesk/ICONO AGREGAR3.svg\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Agregar\">';
                    $accionEliminar = '<img class=\"ml-4 mt-3 d-none\" id=\"btnEliminar_' . $id_detalle . '\" width=\"15px\" height=\"15px\" src=\"../../../../img/chat/eliminar_equipo.svg\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Eliminar\">';
                }
            } else {
                //if($serie != '0' || $lote != '0' || $fecha_caducidad != '0')
                if ($lote != '0' || $fecha_caducidad != '0') {
                    $accionAgregar = '<img class=\"ml-4 mt-2\" id=\"btnAgregar_' . $id_detalle . '\" width=\"15px\" height=\"15px\" src=\"../../../../img/timdesk/ICONO AGREGAR3.svg\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Agregar\">';
                    $accionEliminar = '<img class=\"ml-4 mt-1 mb-auto d-none\" id=\"btnEliminar_' . $id_detalle . '\" width=\"15px\" height=\"15px\" src=\"../../../../img/chat/eliminar_equipo.svg\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Eliminar\">';
                } else {
                    $accionAgregar = '<img class=\"ml-4 d-none\" id=\"btnAgregar_' . $id_detalle . '\" width=\"15px\" height=\"15px\" src=\"../../../../img/timdesk/ICONO AGREGAR3.svg\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Agregar\">';
                    $accionEliminar = '<img class=\"ml-4 mt-3 d-none\" id=\"btnEliminar_' . $id_detalle . '\" width=\"15px\" height=\"15px\" src=\"../../../../img/chat/eliminar_equipo.svg\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Eliminar\">';
                }
            }

            $etiquetaI = '<span class=\"textTable flex-column\">';
            $etiquetaF = '</span>';

            $etiquetaINombre = '<span class=\"textTable flex-column\" width=\"100px\">';
            $etiquetaFNombre = '</span>';

            $etiquetaIDescripcion = '<p class=\"textTable flex-column\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"' . $descripcion . '\">';
            $etiquetaFDescripcion = '</p>';

            //"Serie":"' . $etiquetaI . $serieFinal . $etiquetaF . '", between cantidad-lote
            $table .= '{"IdDetalle":"' . $etiquetaI . $id_detalle . $etiquetaF . '",
                        "Id":"' . $etiquetaI . $id . $etiquetaF . '",
                        "Clave":"' . $etiquetaI . $inputClave . $clave . $etiquetaF . '",
                        "Nombre":"' . $etiquetaINombre . $inputNombre . $nombre . $etiquetaFNombre . '",
                        "Descripcion":"' . $etiquetaIDescripcion . $inputId . $inputDescripcion . $descripcion . $etiquetaFDescripcion . '",
                        "Cantidad":"' . $etiquetaI . $inputIdDetalle . $cantidadFinal . $etiquetaF . '",
                        "Lote":"' . $etiquetaI . $loteFinal . $etiquetaF . '",
                        "FechaCaducidad":"' . $etiquetaI . $fecha_caducidadFinal . $etiquetaF . '",
                        "Acciones":"' . $etiquetaI . $accionAgregar . $accionEliminar . $etiquetaF . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getNoProductosEmpresa()
    {
        $con = new conectar();
        $db = $con->getDb();

        $table = "";

        $query = sprintf('call spc_Tabla_No_Productos_Empresa()');
        $stmt = $db->prepare($query);
        $stmt->execute();
        $array = $stmt->fetchAll();

        $date = date('Y-m-d');

        foreach ($array as $r) {
            $id_detalle = $r['id'];
            $id = $r['PKProducto'];
            $clave = $r['ClaveInterna'];
            $nombre = $r['Nombre'];
            $descripcion = $r['Descripcion'];
            $cantidad = $r['cantidad_toma'];
            $serie = $r['serie'];
            $lote = $r['lote'];
            $fecha_caducidad = $r['caducidad'];
            $numero = $r['claves'];

            $cantidadFinal = '<input class=\"form-control\" type=\"number\" min=\"0\" id=\"cant_' . $id_detalle . '\" value=\"' . $cantidad . '\" onchange=\"validarCantidad(this)\" onchange=\"inserPrevio(this)\"> <div class=\"d-none\">' . $cantidad . '</div>';

            $inputNombre = '<input type=\"hidden\" id=\"idNombre_' . $id_detalle . '\" value=\"' . $nombre . '\">';
            $inputDescripcion = '<input type=\"hidden\" id=\"idDescripcion_' . $id_detalle . '\" value=\"' . $descripcion . '\">';
            $inputIdDetalle = '<input type=\"hidden\" id=\"idDetalle_' . $id_detalle . '\" value=\"' . $id_detalle . '\">';
            $inputId = '<input type=\"hidden\" id=\"id_' . $id_detalle . '\" value=\"' . $id . '\">';
            $inputClave = '<input type=\"hidden\" id=\"clave_' . $id_detalle . '\" value=\"' . $clave . '\">';

            if ($serie == '0') {
                $serieFinal = '<input class=\"form-control\" type=\"text\" id=\"ser_' . $id_detalle . '\" disabled> <div class=\"d-none\">No aplica</div>';
            } else {
                $serieFinal = '<input class=\"form-control\" type=\"text\" id=\"ser_' . $id_detalle . '\" value=\"' . $serie . '\" onkeyup=\"validarCampo(this)\" onchange=\"inserPrevio(this)\"> <div class=\"d-none\">' . $serie . '</div>';
            }

            if ($lote == '0') {
                $loteFinal = '<input class=\"form-control\" type=\"text\" id=\"lot_' . $id_detalle . '\" disabled> <div class=\"d-none\">No aplica</div>';
            } else {
                $loteFinal = '<input class=\"form-control\" type=\"text\" id=\"lot_' . $id_detalle . '\" value=\"' . $lote . '\" onkeyup=\"validarCampo(this)\" onchange=\"inserPrevio(this)\"> <div class=\"d-none\">' . $lote . '</div>';
            }

            if ($fecha_caducidad == '0') {
                $fecha_caducidadFinal = '<input class=\"form-control\" type=\"date\"  id=\"fech_' . $id_detalle . '\" disabled> <div class=\"d-none\">No aplica</div>';
            } else {
                $fecha_caducidadFinal = '<input class=\"form-control\" type=\"date\" min=\"' . date('Y-m-d', strtotime($date . ' + 1 day')) . '\"  id=\"fech_' . $id_detalle . '\" value=\"' . $fecha_caducidad . '\" onchange=\"campoCaducidad(this)\"> <div class=\"d-none\">' . $fecha_caducidad . '</div>';
            }

            if ($numero > 1) {
                if ($serie != '0' || $lote != '0' || $fecha_caducidad != '0') {
                    $accionAgregar = '<img class=\"ml-4 mt-2\" id=\"btnAgregar_' . $id_detalle . '\" width=\"15px\" height=\"15px\" src=\"../../../../img/timdesk/ICONO AGREGAR3.svg\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Agregar\">';
                    $accionEliminar = '<img class=\"ml-4 mt-1 mb-auto\" id=\"btnEliminar_' . $id_detalle . '\" width=\"15px\" height=\"15px\" src=\"../../../../img/chat/eliminar_equipo.svg\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Eliminar\">';
                } else {
                    $accionAgregar = '<img class=\"ml-4 d-none\" id=\"btnAgregar_' . $id_detalle . '\" width=\"15px\" height=\"15px\" src=\"../../../../img/timdesk/ICONO AGREGAR3.svg\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Agregar\">';
                    $accionEliminar = '<img class=\"ml-4 mt-3 d-none\" id=\"btnEliminar_' . $id_detalle . '\" width=\"15px\" height=\"15px\" src=\"../../../../img/chat/eliminar_equipo.svg\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Eliminar\">';
                }
            } else {
                if ($serie != '0' || $lote != '0' || $fecha_caducidad != '0') {
                    $accionAgregar = '<img class=\"ml-4 mt-2\" id=\"btnAgregar_' . $id_detalle . '\" width=\"15px\" height=\"15px\" src=\"../../../../img/timdesk/ICONO AGREGAR3.svg\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Agregar\">';
                    $accionEliminar = '<img class=\"ml-4 mt-1 mb-auto d-none\" id=\"btnEliminar_' . $id_detalle . '\" width=\"15px\" height=\"15px\" src=\"../../../../img/chat/eliminar_equipo.svg\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Eliminar\">';
                } else {
                    $accionAgregar = '<img class=\"ml-4 d-none\" id=\"btnAgregar_' . $id_detalle . '\" width=\"15px\" height=\"15px\" src=\"../../../../img/timdesk/ICONO AGREGAR3.svg\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Agregar\">';
                    $accionEliminar = '<img class=\"ml-4 mt-3 d-none\" id=\"btnEliminar_' . $id_detalle . '\" width=\"15px\" height=\"15px\" src=\"../../../../img/chat/eliminar_equipo.svg\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Eliminar\">';
                }
            }

            $etiquetaI = '<span class=\"textTable flex-column\">';
            $etiquetaF = '</span>';

            $etiquetaINombre = '<span class=\"textTable flex-column\" width=\"100px\">';
            $etiquetaFNombre = '</span>';

            $etiquetaIDescripcion = '<p class=\"textTable flex-column\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"' . $descripcion . '\">';
            $etiquetaFDescripcion = '</p>';

            $table .= '{"IdDetalle":"' . $etiquetaI . $id_detalle . $etiquetaF . '",
                        "Id":"' . $etiquetaI . $id . $etiquetaF . '",
                        "Clave":"' . $etiquetaI . $inputClave . $clave . $etiquetaF . '",
                        "Nombre":"' . $etiquetaINombre . $inputNombre . $nombre . $etiquetaFNombre . '",
                        "Descripcion":"' . $etiquetaIDescripcion . $inputId . $inputDescripcion . $descripcion . $etiquetaFDescripcion . '",
                        "Cantidad":"' . $etiquetaI . $inputIdDetalle . $cantidadFinal . $etiquetaF . '",
                        "Serie":"' . $etiquetaI . $serieFinal . $etiquetaF . '",
                        "Lote":"' . $etiquetaI . $loteFinal . $etiquetaF . '",
                        "FechaCaducidad":"' . $etiquetaI . $fecha_caducidadFinal . $etiquetaF . '",
                        "Acciones":"' . $etiquetaI . $accionAgregar . $accionEliminar . $etiquetaF . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getProductosPorCategoria($idCategoria, $activo, $PKSucursal)
    {
        $con = new conectar();
        $db = $con->getDb();
        $PKUsuario = $_SESSION["PKUsuario"];

        $table = "";

        $query = sprintf('call spc_Tabla_Productos_PorCategoria(?,?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKUsuario, $idCategoria, $activo, $PKSucursal));
        $array = $stmt->fetchAll();

        $date = date('Y-m-d');

        foreach ($array as $r) {
            $id_detalle = trim($r['id']);
            $id = trim($r['producto_id']);
            $clave = trim($r['clave']);
            $nombre = trim($r['Nombre']);
            $descripcion = trim($r['Descripcion']);
            $cantidad = trim($r['cantidad_toma']);
            //$serie = trim($r['serie']);
            $lote = trim($r['lote']);
            $fecha_caducidad = trim($r['caducidad']);
            $numero = trim($r['claves']);

            $cantidadFinal = '<input class=\"form-control\" type=\"number\" min=\"0\" oninput=\"limitarLongitud(this)\" id=\"cant_' . $id_detalle . '\" value=\"' . $cantidad . '\" onkeyup=\"validarCantidad(this)\" onchange=\"inserPrevio(this)\"> <div class=\"d-none\">' . $cantidad . '</div>';

            $inputNombre = '<input type=\"hidden\" id=\"idNombre_' . $id_detalle . '\" value=\"' . $nombre . '\">';
            $inputDescripcion = '<input type=\"hidden\" id=\"idDescripcion_' . $id_detalle . '\" value=\"' . $descripcion . '\">';
            $inputIdDetalle = '<input type=\"hidden\" id=\"idDetalle_' . $id_detalle . '\" value=\"' . $id_detalle . '\">';
            $inputId = '<input type=\"hidden\" id=\"id_' . $id_detalle . '\" value=\"' . $id . '\">';
            $inputClave = '<input type=\"hidden\" id=\"clave_' . $id_detalle . '\" value=\"' . $clave . '\">';

            /*if ($serie == '0') {
                $serieFinal = '<input class=\"form-control\" type=\"text\" id=\"ser_' . $id_detalle . '\" disabled> <div class=\"d-none\">No aplica</div>';
            }else{
                $serieFinal = '<input class=\"form-control\" type=\"text\" maxlength=\"45\" id=\"ser_' . $id_detalle . '\" value=\"' . $serie . '\" onkeyup=\"validarCampo(this)\" onchange=\"inserPrevio(this)\"> <div class=\"d-none\">' . $serie . '</div>';
            }*/

            if ($lote == '0') {
                $loteFinal = '<input class=\"form-control\" type=\"text\" id=\"lot_' . $id_detalle . '\" disabled> <div class=\"d-none\">No aplica</div>';
            } else {
                $loteFinal = '<input class=\"form-control\" type=\"text\" maxlength=\"45\" id=\"lot_' . $id_detalle . '\" value=\"' . $lote . '\" onkeyup=\"validarCampo(this)\" onchange=\"inserPrevio(this)\"> <div class=\"d-none\">' . $lote . '</div>';
            }

            if ($fecha_caducidad == '0') {
                $fecha_caducidadFinal = '<input class=\"form-control\" type=\"date\"  id=\"fech_' . $id_detalle . '\" disabled> <div class=\"d-none\">No aplica</div>';
            } else {
                $fecha_caducidadFinal = '<input class=\"form-control\" type=\"date\" min=\"' . date('Y-m-d', strtotime($date . ' + 1 day')) . '\"  id=\"fech_' . $id_detalle . '\" value=\"' . $fecha_caducidad . '\" onchange=\"campoCaducidad(this)\"> <div class=\"d-none\">' . $fecha_caducidad . '</div>';
            }

            if ($numero > 1) {
                //if($serie != '0' || $lote != '0' || $fecha_caducidad != '0')
                if ($lote != '0' || $fecha_caducidad != '0') {
                    $accionAgregar = '<img class=\"ml-4 mt-2\" id=\"btnAgregar_' . $id_detalle . '\" width=\"15px\" height=\"15px\" src=\"../../../../img/timdesk/ICONO AGREGAR3.svg\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Agregar\">';
                    $accionEliminar = '<img class=\"ml-4 mt-1 mb-auto\" id=\"btnEliminar_' . $id_detalle . '\" width=\"15px\" height=\"15px\" src=\"../../../../img/chat/eliminar_equipo.svg\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Eliminar\">';
                } else {
                    $accionAgregar = '<img class=\"ml-4 d-none\" id=\"btnAgregar_' . $id_detalle . '\" width=\"15px\" height=\"15px\" src=\"../../../../img/timdesk/ICONO AGREGAR3.svg\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Agregar\">';
                    $accionEliminar = '<img class=\"ml-4 mt-3 d-none\" id=\"btnEliminar_' . $id_detalle . '\" width=\"15px\" height=\"15px\" src=\"../../../../img/chat/eliminar_equipo.svg\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Eliminar\">';
                }
            } else {
                //if($serie != '0' || $lote != '0' || $fecha_caducidad != '0')
                if ($lote != '0' || $fecha_caducidad != '0') {
                    $accionAgregar = '<img class=\"ml-4 mt-2\" id=\"btnAgregar_' . $id_detalle . '\" width=\"15px\" height=\"15px\" src=\"../../../../img/timdesk/ICONO AGREGAR3.svg\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Agregar\">';
                    $accionEliminar = '<img class=\"ml-4 mt-1 mb-auto d-none\" id=\"btnEliminar_' . $id_detalle . '\" width=\"15px\" height=\"15px\" src=\"../../../../img/chat/eliminar_equipo.svg\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Eliminar\">';
                } else {
                    $accionAgregar = '<img class=\"ml-4 d-none\" id=\"btnAgregar_' . $id_detalle . '\" width=\"15px\" height=\"15px\" src=\"../../../../img/timdesk/ICONO AGREGAR3.svg\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Agregar\">';
                    $accionEliminar = '<img class=\"ml-4 mt-3 d-none\" id=\"btnEliminar_' . $id_detalle . '\" width=\"15px\" height=\"15px\" src=\"../../../../img/chat/eliminar_equipo.svg\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Eliminar\">';
                }
            }

            $etiquetaI = '<span class=\"textTable flex-column\">';
            $etiquetaF = '</span>';

            $etiquetaINombre = '<span class=\"textTable flex-column\" width=\"100px\">';
            $etiquetaFNombre = '</span>';

            $etiquetaIDescripcion = '<p class=\"textTable flex-column\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"' . $descripcion . '\">';
            $etiquetaFDescripcion = '</p>';

            //"Serie":"' . $etiquetaI . $serieFinal . $etiquetaF . '", between cantidad y lote
            $table .= '{"IdDetalle":"' . $etiquetaI . $id_detalle . $etiquetaF . '",
                        "Id":"' . $etiquetaI . $id . $etiquetaF . '",
                        "Clave":"' . $etiquetaI . $inputClave . $clave . $etiquetaF . '",
                        "Nombre":"' . $etiquetaINombre . $inputNombre . $nombre . $etiquetaFNombre . '",
                        "Descripcion":"' . $etiquetaIDescripcion . $inputId . $inputDescripcion . $descripcion . $etiquetaFDescripcion . '",
                        "Cantidad":"' . $etiquetaI . $inputIdDetalle . $cantidadFinal . $etiquetaF . '",
                        "Lote":"' . $etiquetaI . $loteFinal . $etiquetaF . '",
                        "FechaCaducidad":"' . $etiquetaI . $fecha_caducidadFinal . $etiquetaF . '",
                        "Acciones":"' . $etiquetaI . $accionAgregar . $accionEliminar . $etiquetaF . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getExistenciaProductosEmpresa($PKSucursal)
    {
        $con = new conectar();
        $db = $con->getDb();

        $table = "";

        $query = sprintf('call spc_Tabla_Existencia_Productos_Empresa(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKSucursal));
        $array = $stmt->fetchAll();

        $date = date('Y-m-d');

        foreach ($array as $r) {
            $id_existencia = trim($r['id']);
            $id = trim($r['PKProducto']);
            $clave = trim($r['ClaveInterna']);
            $nombre = trim($r['Nombre']);
            $descripcion = trim($r['Descripcion']);
            $existencia = trim($r['existencia']);
            //$serie = trim($r['numero_serie']);
            $lote = trim($r['numero_lote']);
            $fecha_caducidad = trim($r['caducidad']);

            if ($fecha_caducidad == "0000-00-00") {
                $fecha_caducidad = "";
            }

            $check = '<input class=\"form-check-input m-auto\" type=\"checkbox\" id=\"check_' . $id_existencia . '\">';
            $cantidad = '<input class=\"form-control\" type=\"number\" min=\"0\" id=\"cant_' . $id_existencia . '\">';

            $etiquetaI = '<span class=\"textTable flex-column\">';
            $etiquetaF = '</span>';

            $etiquetaINombre = '<span class=\"textTable flex-column\" style=\"width: 100px;\">';
            $etiquetaFNombre = '</span>';

            $etiquetaIDescripcion = '<p class=\"textTable flex-column\">';
            $etiquetaFDescripcion = '</p>';

            //"Serie":"' . $etiquetaI . $serie . $etiquetaF . '", between cantidad y lote
            $table .= '{"IdExistencia":"' . $etiquetaI . $id_existencia . $etiquetaF . '",
                        "Id":"' . $etiquetaI . $id . $etiquetaF . '",
                        "Check":"' . $etiquetaI . $check . $etiquetaF . '",
                        "Clave":"' . $etiquetaI . $clave . $etiquetaF . '",
                        "Nombre":"' . $etiquetaINombre . $nombre . $etiquetaFNombre . '",
                        "Descripcion":"' . $etiquetaIDescripcion . $descripcion . $etiquetaFDescripcion . '",
                        "Existencia":"' . $etiquetaI . $existencia . $etiquetaF . '",
                        "Cantidad":"' . $etiquetaI . $cantidad . $etiquetaF . '",
                        "Lote":"' . $etiquetaI . $lote . $etiquetaF . '",
                        "FechaCaducidad":"' . $etiquetaI . $fecha_caducidad . $etiquetaF . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getNoExistenciaProductosEmpresa()
    {
        $con = new conectar();
        $db = $con->getDb();

        $table = "";

        $query = sprintf('call spc_Tabla_No_Productos_Empresa()');
        $stmt = $db->prepare($query);
        $stmt->execute();
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id_existencia = $r['id'];
            $id = $r['PKProducto'];
            $clave = $r['ClaveInterna'];
            $nombre = $r['Nombre'];
            $descripcion = $r['Descripcion'];
            $existencia = $r['existencia'];
            //$serie = $r['numero_serie'];
            $lote = $r['numero_lote'];
            $fecha_caducidad = $r['caducidad'];

            if ($fecha_caducidad == "0000-00-00") {
                $fecha_caducidad = "";
            }

            $check = '<input class=\"form-check-input m-auto\" type=\"checkbox\" id=\"check_' . $id_existencia . '\">';
            $cantidad = '<input class=\"form-control\" type=\"number\" min=\"0\" id=\"cant_' . $id_existencia . '\">';

            $etiquetaI = '<span class=\"textTable flex-column\">';
            $etiquetaF = '</span>';

            $etiquetaINombre = '<span class=\"textTable flex-column\" style=\"width: 100px;\">';
            $etiquetaFNombre = '</span>';

            $etiquetaIDescripcion = '<p class=\"textTable flex-column\">';
            $etiquetaFDescripcion = '</p>';

            //"Serie":"' . $etiquetaI . $serie . $etiquetaF . '", between cantidad y lote
            $table .= '{"IdExistencia":"' . $etiquetaI . $id_existencia . $etiquetaF . '",
                        "Id":"' . $etiquetaI . $id . $etiquetaF . '",
                        "Check":"' . $etiquetaI . $check . $etiquetaF . '",
                        "Clave":"' . $etiquetaI . $clave . $etiquetaF . '",
                        "Nombre":"' . $etiquetaINombre . $nombre . $etiquetaFNombre . '",
                        "Descripcion":"' . $etiquetaIDescripcion . $descripcion . $etiquetaFDescripcion . '",
                        "Existencia":"' . $etiquetaI . $existencia . $etiquetaF . '",
                        "Cantidad":"' . $etiquetaI . $cantidad . $etiquetaF . '",
                        
                        "Lote":"' . $etiquetaI . $lote . $etiquetaF . '",
                        "FechaCaducidad":"' . $etiquetaI . $fecha_caducidad . $etiquetaF . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getExistenciaProductosPorCategoria($idCategoria, $activo, $PKSucursal)
    {
        $con = new conectar();
        $db = $con->getDb();
        $PKUsuario = $_SESSION["PKUsuario"];

        $table = "";

        $query = sprintf('call spc_Tabla_Existencia_Productos_PorCategoria(?,?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKUsuario, $idCategoria, $activo, $PKSucursal));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id_existencia = trim($r['id']);
            $id = trim($r['PKProducto']);
            $clave = trim($r['ClaveInterna']);
            $nombre = trim($r['Nombre']);
            $descripcion = trim($r['Descripcion']);
            $existencia = trim($r['existencia']);
            //$serie = trim($r['numero_serie']);
            $lote = trim($r['numero_lote']);
            $fecha_caducidad = trim($r['caducidad']);

            if ($fecha_caducidad == "0000-00-00") {
                $fecha_caducidad = "";
            }

            $check = '<input class=\"form-check-input m-auto\" type=\"checkbox\" id=\"check_' . $id_existencia . '\">';
            $cantidad = '<input class=\"form-control\" type=\"number\" min=\"0\" id=\"cant_' . $id_existencia . '\">';

            $etiquetaI = '<span class=\"textTable flex-column\">';
            $etiquetaF = '</span>';

            $etiquetaINombre = '<span class=\"textTable flex-column\" style=\"width: 100px;\">';
            $etiquetaFNombre = '</span>';

            $etiquetaIDescripcion = '<p class=\"textTable flex-column\">';
            $etiquetaFDescripcion = '</p>';

            //"Serie":"' . $etiquetaI . $serie . $etiquetaF . '",
            $table .= '{"IdExistencia":"' . $etiquetaI . $id_existencia . $etiquetaF . '",
                        "Id":"' . $etiquetaI . $id . $etiquetaF . '",
                        "Check":"' . $etiquetaI . $check . $etiquetaF . '",
                        "Clave":"' . $etiquetaI . $clave . $etiquetaF . '",
                        "Nombre":"' . $etiquetaINombre . $nombre . $etiquetaFNombre . '",
                        "Descripcion":"' . $etiquetaIDescripcion . $descripcion . $etiquetaFDescripcion . '",
                        "Existencia":"' . $etiquetaI . $existencia . $etiquetaF . '",
                        "Cantidad":"' . $etiquetaI . $cantidad . $etiquetaF . '",
                       
                        "Lote":"' . $etiquetaI . $lote . $etiquetaF . '",
                        "FechaCaducidad":"' . $etiquetaI . $fecha_caducidad . $etiquetaF . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getConteosInventariosPorSucursales()
    {
        $con = new conectar();
        $db = $con->getDb();
        $PKEmpresa = $_SESSION["IDEmpresa"];

        $table = "";

        $query = sprintf('call spc_Tabla_Conteo_Inventario_Por_Sucursal(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id_inventario = trim($r['id']);
            $conteo = trim($r['conteo']);
            $estatus = trim($r['estatus']);
            $usuario = trim($r['nombre']);
            $numero_productos = trim($r['numero']);

            $etiquetaI = '<span class=\"textTable flex-column\">';
            $etiquetaF = '</span>';

            $table .= '{"IdInventario":"' . $etiquetaI . $id_inventario . $etiquetaF . '",
                        "Conteo":"' . $etiquetaI . $conteo . $etiquetaF . '",
                        "Estatus":"' . $etiquetaI . $estatus . $etiquetaF . '",
                        "Usuario":"' . $etiquetaI . $usuario . $etiquetaF . '",
                        "NumeroProductos":"' . $etiquetaI . $numero_productos . $etiquetaF . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getDetalleInventarioPeriodico($PKSucursal)
    {
        $con = new conectar();
        $db = $con->getDb();

        $table = "";

        $query = sprintf('call spc_Tabla_Detalle_Inventario_Periodico(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKSucursal));
        $array = $stmt->fetchAll();

        $date = date('Y-m-d');

        foreach ($array as $r) {
            $id_detalle = trim($r['id']);
            $producto_id = trim($r['producto_id']);
            $clave = trim($r['clave']);
            $nombre = trim($r['Nombre']);
            $existencia = trim($r['cantidad_sistema']);
            $cantidad = trim($r['Cantidad']);
            $lote = trim($r['numero_lote']);
            $caducidad = trim($r['caducidad']);
            $numero = trim($r['claves']);
            $agregado_inventario = trim($r['agregado_inventario']);
            $tiene_lote = trim($r['lote']);
            $tiene_caducidad = trim($r['fecha_caducidad']);

            $etiquetaI = '<span class=\"textTable flex-column\">';
            $etiquetaF = '</span>';

            $inputId = '<input type=\"hidden\" id=\"id_' . $id_detalle . '\" value=\"' . $producto_id . '\">';
            $inputClave = '<input type=\"hidden\" id=\"clave_' . $id_detalle . '\" value=\"' . $clave . '\">';

            $cantidadFinal = '<input class=\"form-control\" type=\"number\" min=\"0\" oninput=\"limitarLongitud(this)\" id=\"cant_' . $id_detalle . '\" value=\"' . $cantidad . '\" onchange=\"inserPrevio(this)\"> <div class=\"d-none\">' . $cantidad . '</div>';

            if ($numero > 1) {
                if ($lote != '') {
                    $accionAgregar = '<img class=\"ml-4 mt-2\" id=\"btnAgregar_' . $id_detalle . '\" width=\"15px\" height=\"15px\" src=\"../../../../img/timdesk/ICONO AGREGAR3.svg\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Agregar\">';
                    $accionEliminar = '<img class=\"ml-4 mt-1 mb-auto\" id=\"btnEliminar_' . $id_detalle . '\" width=\"15px\" height=\"15px\" src=\"../../../../img/chat/eliminar_equipo.svg\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Eliminar\">';
                } else {
                    $accionAgregar = '<img class=\"ml-4 d-none\" id=\"btnAgregar_' . $id_detalle . '\" width=\"15px\" height=\"15px\" src=\"../../../../img/timdesk/ICONO AGREGAR3.svg\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Agregar\">';
                    $accionEliminar = '<img class=\"ml-4 mt-3 d-none\" id=\"btnEliminar_' . $id_detalle . '\" width=\"15px\" height=\"15px\" src=\"../../../../img/chat/eliminar_equipo.svg\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Eliminar\">';
                }
            } else {
                if ($lote != '') {
                    $accionAgregar = '<img class=\"ml-4 mt-2\" id=\"btnAgregar_' . $id_detalle . '\" width=\"15px\" height=\"15px\" src=\"../../../../img/timdesk/ICONO AGREGAR3.svg\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Agregar\">';
                    $accionEliminar = '<img class=\"ml-4 mt-1 mb-auto d-none\" id=\"btnEliminar_' . $id_detalle . '\" width=\"15px\" height=\"15px\" src=\"../../../../img/chat/eliminar_equipo.svg\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Eliminar\">';
                } else {
                    $accionAgregar = '<img class=\"ml-4 d-none\" id=\"btnAgregar_' . $id_detalle . '\" width=\"15px\" height=\"15px\" src=\"../../../../img/timdesk/ICONO AGREGAR3.svg\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Agregar\">';
                    $accionEliminar = '<img class=\"ml-4 mt-3 d-none\" id=\"btnEliminar_' . $id_detalle . '\" width=\"15px\" height=\"15px\" src=\"../../../../img/chat/eliminar_equipo.svg\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Eliminar\">';
                }
            }

            if($agregado_inventario == 1){
                $accionAgregar = '<img class=\"ml-4 mt-2\" id=\"btnAgregar_' . $id_detalle . '\" width=\"15px\" height=\"15px\" src=\"../../../../img/timdesk/ICONO AGREGAR3.svg\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Agregar\">';
                $accionEliminar = '<img class=\"ml-4 mt-1 mb-auto\" id=\"btnEliminar_' . $id_detalle . '\" width=\"15px\" height=\"15px\" src=\"../../../../img/chat/eliminar_equipo.svg\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Eliminar\">';
            }

            if($agregado_inventario == 1 && $tiene_lote == 1){
                $lote = '<input class=\"form-control mx-auto\" style=\"width: 150px;\" type=\"text\" id=\"lot_' . $id_detalle . '\" value=\"' . $lote . '\" onchange=\"inserPrevio(this)\">';
            }

            if($agregado_inventario == 1 && $tiene_caducidad == 1){
                $caducidad = '<input class=\"form-control\" type=\"date\" min=\"' . date('Y-m-d', strtotime($date . ' + 1 day')) . '\"  id=\"fech_' . $id_detalle . '\" value=\"' . $caducidad . '\" onchange=\"campoCaducidad(this)\">';
            }

            $table .= '{"IdDetalle":"' . $etiquetaI . $id_detalle . $etiquetaF . '",
                        "IdProducto":"' . $etiquetaI . $producto_id . $etiquetaF . '",
                        "Clave":"' . $etiquetaI . $clave. $inputId . $inputClave . $etiquetaF . '",
                        "Nombre":"' . $etiquetaI . $nombre . $etiquetaF . '",
                        "Existencia":"' . $etiquetaI . $existencia . $etiquetaF . '",
                        "Cantidad":"' . $etiquetaI . $cantidadFinal . $etiquetaF . '",
                        "Lote":"' . $etiquetaI . $lote . $etiquetaF . '",
                        "Caducidad":"' . $etiquetaI . $caducidad . $etiquetaF . '",
                        "Acciones":"' . $etiquetaI . $accionAgregar . $accionEliminar . $etiquetaF . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getAjustes($PKSucursal, $PKTipo, $PKFolio)
    {
        $con = new conectar();
        $db = $con->getDb();
        $PKEmpresa = $_SESSION["IDEmpresa"];

        $table = "";

        $query = sprintf('call spc_Tabla_Ajustes(?,?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa, $PKSucursal, $PKTipo, $PKFolio));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id_ajuste = trim($r['id']);
            $sucursal = trim($r['sucursal']);
            $fecha_captura = trim($r['fecha_captura']);
            $nombre = trim($r['Nombre']);
            $folio = trim($r['folio']);
            $tipo_ajuste = trim($r['tipo_ajuste']);


            $input_id_ajuste = '<input type=\"hidden\" value=\"' . $id_ajuste . '\">';

            $etiquetaI = '';
            $etiquetaF = '';
            $etiquetaIHead = '<a href=\"#\" class=\"pointer\">';
            $etiquetaFHead = '</a>';
            $table .= '{"IdAjuste":"' . $etiquetaI . $id_ajuste . $etiquetaF . '",
                        "Sucursal":"' . $etiquetaIHead . $sucursal . $etiquetaFHead . '",
                        "FechaCaptura":"' . $etiquetaI . $fecha_captura . $etiquetaF . '",
                        "Usuario":"' . $etiquetaI . $nombre . $etiquetaF . '",
                        "Folio":"' . $etiquetaI . $input_id_ajuste . $folio . $etiquetaF . '",
                        "TipoAjuste":"' . $etiquetaI . $tipo_ajuste . $etiquetaF . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getAjustesTodos()
    {
        $con = new conectar();
        $db = $con->getDb();
        $PKEmpresa = $_SESSION["IDEmpresa"];

        $table = "";

        $query = sprintf('call spc_Tabla_Ajustes_Todos(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id_ajuste = trim($r['id']);
            $sucursal = trim($r['sucursal']);
            $fecha_captura = trim($r['fecha_captura']);
            $nombre = trim($r['Nombre']);
            $folio = trim($r['folio']);
            $tipo_ajuste = trim($r['tipo_ajuste']);


            $input_id_ajuste = '<input type=\"hidden\" value=\"' . $id_ajuste . '\">';

            $etiquetaI = '';
            $etiquetaF = '';
            $etiquetaIHead = '<a href=\"#\" class=\"pointer\">';
            $etiquetaFHead = '</a>';

            $table .= '{"IdAjuste":"' . $etiquetaI . $id_ajuste . $etiquetaF . '",
                        "Sucursal":"' . $etiquetaIHead . $sucursal . $etiquetaFHead . '",
                        "FechaCaptura":"' . $etiquetaI . $fecha_captura . $etiquetaF . '",
                        "Usuario":"' . $etiquetaI . $nombre . $etiquetaF . '",
                        "Folio":"' . $etiquetaI . $input_id_ajuste . $folio . $etiquetaF . '",
                        "TipoAjuste":"' . $etiquetaI . $tipo_ajuste . $etiquetaF . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getBusquedaAjusteNegativo($PKSucursal, $Valor)
    {

        $con = new conectar();
        $db = $con->getDb();

        $PKUsuario = $_SESSION["PKUsuario"];

        $table = "";

        $query = sprintf('call spc_Tabla_Busqueda_Ajuste_Negativo(?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKUsuario, $PKSucursal, $Valor));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id_existencia = trim($r['id']);
            $id_producto = trim($r['PKProducto']);
            $clave = trim($r['ClaveInterna']);
            $nombre = trim($r['Nombre']);
            $descripcion = trim($r['Descripcion']);
            //$serie_producto = trim($r['serie']);
            $lote_producto = trim($r['lote']);
            $caducidad_producto = trim($r['fecha_caducidad']);
            //$serie = trim($r['numero_serie']);
            $lote = trim($r['numero_lote']);
            $caducidad = trim($r['caducidad']);
            $existencia = trim($r['existencia']);

            $inputIdExistencia = '<input type=\"hidden\" id=\"idEx_' . $id_existencia . '\" value=\"' . $id_existencia . '\">';
            $inputIdProdcuto = '<input type=\"hidden\" id=\"id_' . $id_existencia . '\" value=\"' . $id_producto . '\">';

            $etiquetaIDescripcion = '<p class=\"textTable flex-column\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"' . $descripcion . '\" onmouseout=\"desaparecerTooltip(this)\" onclick=\"desaparecerTooltip(this)\">';
            $etiquetaFDescripcion = '</p>';

            //"SerieProducto":"' . $serie_producto . '", 1048
            //"Serie":"' . $serie . '", 1055
            $table .= '{"IdExistencia":"' . $id_existencia . '",
                        "IdProducto":"' . $id_producto . '",
                        
                        "LoteProducto":"' . $lote_producto . '",
                        "CaducidadProducto":"' . $caducidad_producto . '",
                        "Clave":"' . $inputIdExistencia . $clave . '",
                        "Nombre":"' . $inputIdProdcuto . $nombre . '",
                        "Descripcion":"' . $etiquetaIDescripcion . $descripcion . $etiquetaFDescripcion . '",
                        
                        "Lote":"' . $lote . '",
                        "Caducidad":"' . $caducidad . '",
                        "Existencia":"' . $existencia . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getBusquedaAjustePositivo($Valor)
    {

        $con = new conectar();
        $db = $con->getDb();

        $PKUsuario = $_SESSION["PKUsuario"];

        $table = "";

        $query = sprintf('call spc_Tabla_Busqueda_Ajuste_Positivo(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKUsuario, $Valor));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id_producto = trim($r['PKProducto']);
            $clave = trim($r['ClaveInterna']);
            $nombre = trim($r['Nombre']);
            $descripcion = trim($r['Descripcion']);
            //$serie_producto = trim($r['serie']);
            $lote_producto = trim($r['lote']);
            $caducidad_producto = trim($r['fecha_caducidad']);

            $inputIdProdcuto = '<input type=\"hidden\" id=\"id_' . $id_producto . '\" value=\"' . $id_producto . '\">';

            $etiquetaIDescripcion = '<p class=\"textTable flex-column\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"' . $descripcion . '\" onmouseout=\"desaparecerTooltip(this)\" onclick=\"desaparecerTooltip(this)\">';
            $etiquetaFDescripcion = '</p>';

            //"SerieProducto":"' . $serie_producto . '",
            $table .= '{"IdProducto":"' . $id_producto . '",
                        
                        "LoteProducto":"' . $lote_producto . '",
                        "CaducidadProducto":"' . $caducidad_producto . '",
                        "Clave":"' . $clave . '",
                        "Nombre":"' . $inputIdProdcuto . $nombre . '",
                        "Descripcion":"' . $etiquetaIDescripcion . $descripcion . $etiquetaFDescripcion . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getMovimientosAjuste($PKAjuste)
    {

        $con = new conectar();
        $db = $con->getDb();

        $table = "";

        $query = sprintf('call spc_Tabla_Movimientos_Ajuste(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKAjuste, $_SESSION['IDEmpresa']));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id_ajuste_detalle = trim($r['id']);
            $clave = trim($r['clave']);
            $nombre = trim($r['nombre']);
            $cantidad_ajustada = trim($r['cantidad']);
            $lote = trim($r['numero_lote']);
            //$serie = trim($r['numero_serie']);
            $caducidad = trim($r['caducidad']);
            $motivo = trim($r['motivo']);
            $observaciones = trim($r['observaciones']);

            //"Serie":"' . $serie . '", 1136
            $table .= '{"IdAjusteDetalle":"' . $id_ajuste_detalle . '",
                        "Clave":"' . $clave . '",
                        "Nombre":"' . $nombre . '",
                        
                        "Lote":"' . $lote . '",
                        "Caducidad":"' . $caducidad . '",
                        "CantidadAjustada":"' . $cantidad_ajustada . '",
                        "Motivo":"' . $motivo . '",
                        "Comentarios":"' . $observaciones . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getIdAjuste()
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Ultimo_Ajuste_Inventario_Por_Sucursal()');
        $stmt = $db->prepare($query);
        $stmt->execute();
        $array = $stmt->fetch();


        return $array;
    }

    public function getValidacionProductosIncompletos($PKSucursal)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = 'SELECT p.serie, p.lote, p.fecha_caducidad, dips.numero_serie, dips.numero_lote, dips.caducidad, dips.id, dips.cantidad_toma  FROM detalle_inventario_por_sucursales dips INNER JOIN inventario_por_sucursales ips ON dips.inventario_id = ips.id INNER JOIN productos p ON dips.producto_id = p.PKProducto WHERE ips.sucursal_id = ?';
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKSucursal));
        $array = $stmt->fetchAll();

        $incompleto = 0;
        $len = COUNT($array);

        for ($c = 0; $c < $len; $c++) {
            if (trim($array[$c][0]) == 1 && trim($array[$c][3]) == '' && trim($array[$c][7]) != 0) {
                $incompleto = 1;
            }
            if (trim($array[$c][1]) == 1 && trim($array[$c][4]) == '' && trim($array[$c][7]) != 0) {
                $incompleto = 1;
            }
            if (trim($array[$c][2]) == 1 && trim($array[$c][5]) == '0000-00-00' && trim($array[$c][7]) != 0) {
                $incompleto = 1;
            }
            if (trim($array[$c][2]) == 1 && trim($array[$c][5]) == null && trim($array[$c][7]) != 0) {
                $incompleto = 1;
            }
        }

        return $incompleto;
    }

    public function getValidacionRepeInvIni($PKSucursal, $PKDetalle, $Clave, $Valor, $Tipo)
    {
        $con = new conectar();
        $db = $con->getDb();
        $PKUsuario = $_SESSION["PKUsuario"];

        $query = sprintf('call spc_Tabla_Productos_Empresa(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKUsuario, $PKSucursal));
        $array = $stmt->fetchAll();
        $salida = array();

        $mensaje = null;

        foreach ($array as $r) {
            if ($PKDetalle != trim($r['id'])) {
                if ($Tipo == "lote") {
                    if (trim($r['lote']) != '0' && trim($r['lote']) != null) {
                        if ($Clave == trim($r['ClaveInterna']) && $Valor == trim($r['lote'])) {
                            $mensaje = 'Este lote ya existe';
                        }
                    }
                } else {
                    if (trim($r['serie']) != '0' && trim($r['serie']) != null) {
                        if ($Clave == trim($r['ClaveInterna']) && $Valor == trim($r['serie'])) {
                            $mensaje = 'Esta seria ya existe';
                        }
                    }
                }
            }
        }

        $data[0] = ['mensaje' => $mensaje];

        return $data;
    }

    public function getValidacionRepeInvPerio($PKSucursal, $PKDetalle, $Clave, $Lote)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Tabla_Detalle_Inventario_Periodico(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKSucursal));
        $array = $stmt->fetchAll();

        $mensaje = null;

        foreach ($array as $r) {
            if ($PKDetalle != trim($r['id'])) {
                if (trim($r['numero_lote']) != '0' && trim($r['numero_lote']) != null) {
                    if ($Clave == trim($r['clave']) && $Lote == trim($r['numero_lote'])) {
                        $mensaje = 'Este lote ya existe';
                    }
                }
            }
        }

        $data[0] = ['mensaje' => $mensaje];

        return $data;
    }

    public function getValidProductNuevoInvPeriodico($PKSucursal, $PKProducto)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_ValidarProductoNuevo_InvPeriodico(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKSucursal, $PKProducto));
        $array = $stmt->fetch();

        $mensaje = null;

        $data[0] = ['mensaje' => $mensaje];

        return $array;
    }

    public function getValidacionCantOrdenCompra($Cantidad, $idEntrada, $OrdenCompra, $cuentaPagarId)
    {
        $con = new conectar();
        $db = $con->getDb();
        $PKUsuario = $_SESSION["PKUsuario"];
        $PKEmpresa = $_SESSION["IDEmpresa"];

        try {

            $db->beginTransaction();

            $query = sprintf('SELECT producto_id from entrada_directa_temp where entrada_directa_id = ? and usuario_id = ?');
            $stmt = $db->prepare($query);
            $stmt->execute(array($idEntrada, $PKUsuario));
            $res = $stmt->fetchAll();
            $PKProducto = $res[0]['producto_id'];

            // valida si es producto de la orden de compra
            $query = sprintf('SELECT 1 as existe from detalle_orden_compra as doc inner join ordenes_compra as oc on doc.FKOrdenCompra = oc.PKOrdenCompra where FKProducto = ? and FKOrdenCompra = ? and oc.empresa_id = ?');
            $stmt = $db->prepare($query);
            $stmt->execute(array($PKProducto, $OrdenCompra, $PKEmpresa));
            $exist = $stmt->rowCount();

            if ($exist == 0) {
                $data[0] = ['status' => "ok"];

                return $data;
            }

            $query = sprintf('SELECT sum(cantidad) as total from entrada_directa_temp where producto_id = ? and usuario_id = ? and entrada_directa_id != ?');
            $stmt = $db->prepare($query);
            $stmt->execute(array($PKProducto, $PKUsuario, $idEntrada));
            $res = $stmt->fetchAll();
            $CantProducto = $res[0]['total'];

            if ($cuentaPagarId != 0) {
                $query = sprintf('SELECT sum(tabla.restante) as restante from
                                    (SELECT (Cantidad - ifnull(Cantidad_Recibida,0)) as restante from detalle_orden_compra where FKProducto = ? and FKOrdenCompra = ?
                                    union
                                    select sum(ieps.cantidad) as restante 
                                    from inventario_entrada_por_sucursales ieps
                                        inner join cuentas_por_pagar cpp on  concat(cpp.folio_factura," / ", cpp.num_serie_factura) = ieps.numero_documento
                                        inner join productos p on ieps.clave = p.ClaveInterna and p.empresa_id = ?
                                    where cpp.id = ? and p.PKProducto = ?) as tabla;');
                $stmt = $db->prepare($query);
                $stmt->execute(array($PKProducto, $OrdenCompra, $PKEmpresa, $cuentaPagarId, $PKProducto));
                $res = $stmt->fetchAll();
            } else {
                $query = sprintf('SELECT (Cantidad - ifnull(Cantidad_Recibida,0)) as restante from detalle_orden_compra where FKProducto = ? and FKOrdenCompra = ?');
                $stmt = $db->prepare($query);
                $stmt->execute(array($PKProducto, $OrdenCompra));
                $res = $stmt->fetchAll();
            }

            $restante = $res[0]['restante'];

            if (($CantProducto + $Cantidad) > $restante) {
                $data[0] = ['status' => "no", 'limite' => $restante];
            } else {
                $data[0] = ['status' => "ok"];
            }

            return $data;
        } catch (PDOException $ex) {
            $db->rollBack();
            $data[0] = ['status' => 'err'];
            $data[0] = ['mensaje' => $ex->getMessage()];
            return $data;
        }
    }

    public function getValidacionAjusteExistencia($PKSucursal, $Clave,  $Lote, $Cantidad) //$Serie,
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Validar_Ajuste_Existencia(?,?,?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKSucursal, $Clave,  $Lote, $Cantidad)); //$Serie,
        $array = $stmt->fetch();

        return trim($array['menor']);
    }

    public function getLSC($PKProducto)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Lote_Serie_Caducidad(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKProducto));
        $array = $stmt->fetchAll();

        return $array;
    }

    public function getValidarEmpresaAjusteInventario()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Validar_Empresa_Ajuste_Inventario(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));
        $array = $stmt->fetchAll();

        return $array;
    }

    public function getTablaReporteGeneralKardex($Sucursal)
    {
        $con = new conectar();
        $db = $con->getDb();

        $table = "";

        $PKUsuario = $_SESSION["PKUsuario"];

        $query = sprintf('call spc_Tabla_Reporte_General_Kardex(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKUsuario, $Sucursal));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id_producto = $r['id'];
            $clave = $r['clave'];
            $descripcion = $r['descripcion'];
            $lote = $r['lote'];
            $serie = $r['serie'];
            $caducidad = $r['caducidad'];
            $inventario_inicial = $r['inventario_inicial'];
            $entradas = $r['entradas'];
            $salidas = $r['salidas'];
            $cantidad_sistema = $r['cantidad_sistema'];

            $etiquetaIDescripcion = '<p class=\"textTable flex-column\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"' . $descripcion . '\">';
            $etiquetaFDescripcion = '</p>';

            $table .= '{"Id":"' . $id_producto . '",
                        "Clave":"' . $clave . '",
                        "Descripcion":"' . $descripcion . '",
                        "Lote":"' . $lote . '",
                        "Serie":"' . $serie . '",
                        "Caducidad":"' . $caducidad . '",
                        "InventarioInicial":"' . $inventario_inicial . '",
                        "Entradas":"' . $entradas . '",
                        "Salidas":"' . $salidas . '",
                        "CantidadSistema":"' . $cantidad_sistema . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getTablaReporteDetalladoKardex($Sucursal, $TipoMovimiento, $FechaDe, $FechaHasta)
    {
        $con = new conectar();
        $db = $con->getDb();

        $table = "";

        $PKUsuario = $_SESSION["PKUsuario"];

        $query = sprintf('call spc_Tabla_Reporte_Detallado_Kardex(?,?,?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKUsuario, $Sucursal, $TipoMovimiento, $FechaDe, $FechaHasta));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id_producto = trim($r['id_producto']);
            $clave = trim($r['clave']);
            $descripcion = trim($r['descripcion']);
            $lote = trim($r['lote']);
            $serie = trim($r['serie']);
            $caducidad = trim($r['caducidad']);
            $inventario_inicial = trim($r['inventario_inicial']);
            $entradas = trim($r['entradas']);
            $salidas = trim($r['salidas']);
            $cantidad_sistema = trim($r['cantidad_sistema']);
            $referencia = trim($r['referencia']);
            $tipo_movimiento = trim($r['tipo_movimiento']);
            $usuario = trim($r['usuario']);
            $observaciones = trim($r['observaciones']);
            $fecha = trim($r['fecha']);
            $folio = trim($r['folioVentaCotizacion']);
            $motivo = trim($r['motivo']);

            $etiquetaIDescripcion = '<p class=\"textTable flex-column\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"' . $descripcion . '\">';
            $etiquetaFDescripcion = '</p>';

            $table .= '{"Id":"' . $id_producto . '",
                        "Clave":"' . $clave . '",
                        "Descripcion":"' . $etiquetaIDescripcion . $descripcion . $etiquetaFDescripcion . '",
                        "Lote":"' . $lote . '",
                        "Serie":"' . $serie . '",
                        "Caducidad":"' . $caducidad . '",
                        "InventarioInicial":"' . $inventario_inicial . '",
                        "Entradas":"' . $entradas . '",
                        "Salidas":"' . $salidas . '",
                        "CantidadSistema":"' . $cantidad_sistema . '",
                        "Referencia":"' . $referencia . '",
                        "TipoMovimiento":"' . $tipo_movimiento . '",
                        "Usuario":"' . $usuario . '",
                        "Observaciones":"' . $observaciones . '",
                        "Fecha":"' . $fecha . '",
                        "Folio":"' . $folio . '",
                        "Motivo":"' . $motivo . '"},';
            
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getCambios($PKSucursal, $PKTipo, $PKFolio)
    {
        $con = new conectar();
        $db = $con->getDb();
        $PKEmpresa = $_SESSION["IDEmpresa"];

        $table = "";

        $query = sprintf('call spc_Tabla_Cambios_Lote_Serie(?,?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa, $PKSucursal, $PKTipo, $PKFolio));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id_cambio = trim($r['id']);
            $sucursal = trim($r['sucursal']);
            $fecha_captura = trim($r['fecha_captura']);
            $nombre = trim($r['Nombre']);
            $folio = trim($r['folio']);
            $tipo_cambio = trim($r['tipo_cambio']);


            $input_id_cambio = '<input type=\"hidden\" value=\"' . $id_cambio . '\">';

            $etiquetaI = '';
            $etiquetaF = '';
            $etiquetaIHead = '<a href=\"#\" class=\"pointer\">';
            $etiquetaFHead = '</a>';
            $table .= '{"IdCambio":"' . $etiquetaI . $id_cambio . $etiquetaF . '",
                        "Sucursal":"' . $etiquetaIHead . $sucursal . $etiquetaFHead . '",
                        "FechaCaptura":"' . $etiquetaI . $fecha_captura . $etiquetaF . '",
                        "Usuario":"' . $etiquetaI . $nombre . $etiquetaF . '",
                        "Folio":"' . $etiquetaI . $input_id_cambio . $folio . $etiquetaF . '",
                        "TipoCambio":"' . $etiquetaI . $tipo_cambio . $etiquetaF . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getCambiosTodos()
    {
        $con = new conectar();
        $db = $con->getDb();
        $PKEmpresa = $_SESSION["IDEmpresa"];
        $PKUsuario = $_SESSION["PKUsuario"];
        $table = "";

        $query = sprintf('call spc_Tabla_Cambios_Lote_Serie_Todos(?, ?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa, $PKUsuario));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id_cambio = trim($r['id']);
            $sucursal = trim($r['sucursal']);
            $fecha_captura = trim($r['fecha_captura']);
            $nombre = trim($r['Nombre']);
            $folio = trim($r['folio']);
            $tipo_cambio = trim($r['tipo_cambio']);


            $input_id_cambio = '<input type=\"hidden\" value=\"' . $id_cambio . '\">';

            $etiquetaI = '';
            $etiquetaF = '';
            $etiquetaIHead = '<a href=\"#\" class=\"pointer\">';
            $etiquetaFHead = '</a>';
            $table .= '{"IdCambio":"' . $etiquetaI . $id_cambio . $etiquetaF . '",
                        "Sucursal":"' . $etiquetaIHead . $sucursal . $etiquetaFHead . '",
                        "FechaCaptura":"' . $etiquetaI . $fecha_captura . $etiquetaF . '",
                        "Usuario":"' . $etiquetaI . $nombre . $etiquetaF . '",
                        "Folio":"' . $etiquetaI . $input_id_cambio . $folio . $etiquetaF . '",
                        "TipoCambio":"' . $etiquetaI . $tipo_cambio . $etiquetaF . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getBusquedaCambioLote($PKSucursal, $Valor)
    {

        $con = new conectar();
        $db = $con->getDb();

        $PKUsuario = $_SESSION["PKUsuario"];

        $table = "";

        $query = sprintf('call spc_Tabla_Busqueda_Cambio_Lote(?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKUsuario, $PKSucursal, $Valor));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id_existencia = trim($r['id']);
            $id_producto = trim($r['PKProducto']);
            $clave = trim($r['ClaveInterna']);
            $nombre = trim($r['Nombre']);
            $serie_producto = trim($r['serie']);
            $lote_producto = trim($r['lote']);
            $caducidad_producto = trim($r['fecha_caducidad']);
            $lote = trim($r['numero_lote']);
            $caducidad = trim($r['caducidad']);
            $existencia = trim($r['existencia']);

            $inputIdExistencia = '<input type=\"hidden\" id=\"idEx_' . $id_existencia . '\" value=\"' . $id_existencia . '\">';
            $inputIdProdcuto = '<input type=\"hidden\" id=\"id_' . $id_existencia . '\" value=\"' . $id_producto . '\">';

            //"SerieProducto":"' . $serie_producto . '", 1489
            $table .= '{"IdExistencia":"' . $id_existencia . '",
                        "IdProducto":"' . $id_producto . '",
                        
                        "LoteProducto":"' . $lote_producto . '",
                        "CaducidadProducto":"' . $caducidad_producto . '",
                        "Clave":"' . $inputIdExistencia . $clave . '",
                        "Nombre":"' . $inputIdProdcuto . $nombre . '",
                        "Lote":"' . $lote . '",
                        "Caducidad":"' . $caducidad . '",
                        "Existencia":"' . $existencia . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getBusquedaCambioSerie($PKSucursal, $Valor)
    {

        $con = new conectar();
        $db = $con->getDb();

        $PKUsuario = $_SESSION["PKUsuario"];

        $table = "";

        $query = sprintf('call spc_Tabla_Busqueda_Cambio_Serie(?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKUsuario, $PKSucursal, $Valor));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id_existencia = trim($r['id']);
            $id_producto = trim($r['PKProducto']);
            $clave = trim($r['ClaveInterna']);
            $nombre = trim($r['Nombre']);
            $serie_producto = trim($r['serie']);
            $lote_producto = trim($r['lote']);
            $caducidad_producto = trim($r['fecha_caducidad']);
            $serie = trim($r['numero_serie']);
            $caducidad = trim($r['caducidad']);
            $existencia = trim($r['existencia']);

            $inputIdExistencia = '<input type=\"hidden\" id=\"idEx_' . $id_existencia . '\" value=\"' . $id_existencia . '\">';
            $inputIdProdcuto = '<input type=\"hidden\" id=\"id_' . $id_existencia . '\" value=\"' . $id_producto . '\">';

            $table .= '{"IdExistencia":"' . $id_existencia . '",
                        "IdProducto":"' . $id_producto . '",
                        
                        "LoteProducto":"' . $lote_producto . '",
                        "CaducidadProducto":"' . $caducidad_producto . '",
                        "Clave":"' . $inputIdExistencia . $clave . '",
                        "Nombre":"' . $inputIdProdcuto . $nombre . '",
                        "Serie":"' . $serie . '",
                        "Caducidad":"' . $caducidad . '",
                        "Existencia":"' . $existencia . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getMovimientosCambiosLoteSerie($PKCambio)
    {

        $con = new conectar();
        $db = $con->getDb();

        $table = "";

        $query = sprintf('call spc_Tabla_Movimientos_Cambios_Lote_Serie(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKCambio, $_SESSION['IDEmpresa']));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id_detalle_cambio = trim($r['id']);
            $clave = trim($r['clave']);
            $nombre = trim($r['nombre']);
            $cantidad = trim($r['cantidad']);
            $lote_antiguo = trim($r['lote_antiguo']);
            $serie_antigua = trim($r['serie_antigua']);
            $lote_nuevo = trim($r['lote_nuevo']);
            $serie_nueva = trim($r['serie_nueva']);
            $caducidad = trim($r['caducidad']);
            $observaciones = trim($r['observaciones']);

            //"SerieAntigua":"' . $serie_antigua . '", 1581
            //"SerieNueva":"' . $serie_nueva . '", 1584
            $table .= '{"IdDetalleCambio":"' . $id_detalle_cambio . '",
                        "Clave":"' . $clave . '",
                        "Nombre":"' . $nombre . '",
                        "Cantidad":"' . $cantidad . '",
                       
                        "LoteAntiguo":"' . $lote_antiguo . '",
                        
                        "LoteNuevo":"' . $lote_nuevo . '",
                        "Caducidad":"' . $caducidad . '",
                        "Comentarios":"' . $observaciones . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getIdCambio()
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('SELECT id, tipo_cambio, folio FROM cambio_lote_serie WHERE id = (SELECT MAX(id) AS id FROM cambio_lote_serie)');
        $stmt = $db->prepare($query);
        $stmt->execute();
        $array = $stmt->fetch();


        return $array;
    }

    public function getValidacionExistenciaCambioLoteSerie($PKSucursal, $Clave, $Serie, $Lote, $Cantidad)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Validar_Existencia_Cambio_Lote_Serie(?,?,?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKSucursal, $Clave, $Serie, $Lote, $Cantidad));
        $array = $stmt->fetch();

        return trim($array['menor']);
    }

    public function getValidarEmpresaCambioLoteSerie()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Validar_Empresa_Cambio_Lote_Serie(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));
        $array = $stmt->fetchAll();

        return $array;
    }

    public function getValidarUnicoLote($Clave, $Sucursal, $Tipo, $LoteSerie)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_ValidarUnicoLote(?,?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($Clave, $Sucursal, $Tipo, $LoteSerie));
        $array = $stmt->fetchAll();

        return $array;
    }

    public function getOrdenesCompraTable($pkUsuario)
    {
        $con = new conectar();
        $db = $con->getDb();

        $table = "";

        $query = sprintf('call spc_Tabla_OrdenesCompraTemp_Consulta(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkUsuario));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id = $r['id'];
            $nombre = $r['nombre'];
            $clave = $r['clave'];

            if ($r['clave'] == '') {
                $producto = $r['nombre'];
            } else {
                $producto = $r['clave'] . ' - ' . $r['nombre'];
            }

            $cantidad = $r['cantidad'];

            if ($r['unidadMedida'] == '') {
                $unidadMedida = '(Sin unidad de medida)';
            } else {
                $unidadMedida = $r['unidadMedida'];
            }

            $precio = $r['precio'];
            $importe = $r['importe'];
            $impuestos = $r['impuestos'];
            $minima = $r['minima'];

            $precio = number_format($precio, 2, ".", ",");
            $importe = number_format($importe, 2, ".", ",");

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';
            $acciones = '<i><img class=\"btnEdit\"  onclick=\"obtenerIdOrdenCompraTempEliminar(' . $id . ');\" src=\"../../../../img/timdesk/delete.svg\"></i>';

            $cantidadEditable = ' <div class=\"input-group\"><input class=\"form-control textTable border-0\" type=\"number\" value=\"' . $cantidad . '\" onchange=\"validarCantidad(' . $id . ');\" id=\"cantidad-' . $id . '\"> <div class=\"invalid-feedback\" id=\"invalid-cantidad-' . $id . '\">La cantidad es inválida.</div></div>';

            $cantidadHIs = '<input type=\"hidden\" value=\"' . $minima . '\" id=\"cantidadHis-' . $id . '\">';

            $table .= '{"Id":"' . $etiquetaI . $id . $etiquetaF . '",
                "Producto":"' . $etiquetaI . $producto . $cantidadHIs . $etiquetaF . '",
                "Cantidad":"' . $cantidadEditable . '",
                "UnidadMedida":"' . $etiquetaI . $unidadMedida . $etiquetaF . '",
                "PrecioUnitario":"' . $etiquetaI . '$ ' . $precio . $etiquetaF . '",
                "Impuestos":"' . $etiquetaI . $impuestos . $etiquetaF . '",
                "Importe":"' . $etiquetaI . '$ ' . $importe . $etiquetaF . $acciones . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getOrdenesCompraTableEdit($pkOrden)
    {
        $con = new conectar();
        $db = $con->getDb();

        $table = "";

        $query = sprintf('call spc_Tabla_OrdenesCompra_ConsultaEdit(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkOrden));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id = $r['id'];
            $idDetalle = $r['idDetalle'];
            $nombre = $r['nombre'];
            $clave = $r['clave'];

            if ($r['clave'] == '') {
                $producto = $r['nombre'];
            } else {
                $producto = $r['clave'] . ' - ' . $r['nombre'];
            }

            $cantidad = $r['cantidad'];

            if ($r['unidadMedida'] == '') {
                $unidadMedida = '(Sin unidad de medida)';
            } else {
                $unidadMedida = $r['unidadMedida'];
            }

            $precio = $r['precio'];
            $importe = $r['importe'];
            $impuestos = $r['impuestos'];
            $minima = $r['minima'];

            $precio = number_format($precio, 2, ".", ",");
            $importe = number_format($importe, 2, ".", ",");

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';
            $acciones = '<i><img class=\"btnEdit\"  onclick=\"obtenerIdOrdenCompraTempEliminar(' . $idDetalle . ');\" src=\"../../../../img/timdesk/delete.svg\"></i>';

            $cantidadEditable = ' <div class=\"input-group\"><input class=\"form-control textTable border-0\" type=\"number\" value=\"' . $cantidad . '\" onchange=\"validarCantidad(' . $idDetalle . ');\" id=\"cantidad-' . $idDetalle . '\"> <input type=\"hidden\" value=\"' . $minima . '\" id=\"cantidadHis-' . $idDetalle . '\"> <div class=\"invalid-feedback\" id=\"invalid-cantidad-' . $idDetalle . '\">La cantidad es inválida.</div></div>';

            $table .= '{"Id":"' . $etiquetaI . $idDetalle . $etiquetaF . '",
                "Producto":"' . $etiquetaI . $producto . $etiquetaF . '",
                "Cantidad":"' . $cantidadEditable . '",
                "UnidadMedida":"' . $etiquetaI . $unidadMedida . $etiquetaF . '",
                "PrecioUnitario":"' . $etiquetaI . '$ ' . $precio . $etiquetaF . '",
                "Impuestos":"' . $etiquetaI . $impuestos . $etiquetaF . '",
                "Importe":"' . $etiquetaI . '$ ' . $importe . $etiquetaF . $acciones . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getOrdenesCompraTableVer($pkOrden)
    {
        $con = new conectar();
        $db = $con->getDb();

        $table = "";

        $query = sprintf('call spc_Tabla_OrdenesCompra_ConsultaEditVer(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkOrden));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id = $r['id'];
            $idDetalle = $r['idDetalle'];
            $nombre = $r['nombre'];
            $clave = $r['clave'];

            if ($r['clave'] == '') {
                $producto = str_replace('"', '\"', $r['nombre']);
            } else {
                $producto = str_replace('"', '\"', $r['clave']) . ' - ' . str_replace('"', '\"', $r['nombre']);
            }

            $cantidad = $r['cantidad'];

            if ($r['unidadMedida'] == '') {
                $unidadMedida = '(Sin unidad de medida)';
            } else {
                $unidadMedida = $r['unidadMedida'];
            }

            $precio = $r['precio'];
            $importe = $r['importe'];
            $impuestos = $r['impuestos'];
            $minima = $r['minima'];

            $precio = number_format($precio, 2, ".", ",");
            $importe = number_format($importe, 2, ".", ",");

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';
            $acciones = '';

            $cantidadEditable = ' <div class=\"input-group\"><span>' . $cantidad . '</span>';

            $table .= '{"Id":"' . $etiquetaI . $idDetalle . $etiquetaF . '",
                "Producto":"' . $etiquetaI . $producto . $etiquetaF . '",
                "Cantidad":"' . $cantidadEditable . '",
                "UnidadMedida":"' . $etiquetaI . $unidadMedida . $etiquetaF . '",
                "PrecioUnitario":"' . $etiquetaI . '$ ' . $precio . $etiquetaF . '",
                "Impuestos":"' . $etiquetaI . $impuestos . $etiquetaF . '",
                "Importe":"' . $etiquetaI . '$ ' . $importe . $etiquetaF . $acciones . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getProductosOrdenesCompraTable($pkOrden)
    {
        $con = new conectar();
        $db = $con->getDb();

        $table = "";

        $query = sprintf('call spc_Tabla_OrdenesCompra_ConsultaEdit(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkOrden));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id = $r['id'];
            $idDetalle = $r['idDetalle'];
            $nombre = $r['nombre'];
            $clave = $r['clave'];

            if ($r['clave'] == '') {
                $producto = $r['nombre'];
            } else {
                $producto = $r['clave'] . ' - ' . $r['nombre'];
            }

            $cantidad = $r['cantidad'];

            if ($r['unidadMedida'] == '') {
                $unidadMedida = '(Sin unidad de medida)';
            } else {
                $unidadMedida = $r['unidadMedida'];
            }

            $precio = $r['precio'];
            $importe = $r['importe'];
            $impuestos = $r['impuestos'];
            $minima = $r['minima'];

            $precio = number_format($precio, 2, ".", ",");
            $importe = number_format($importe, 2, ".", ",");

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';

            $table .= '{"Id":"' . $etiquetaI . $idDetalle . $etiquetaF . '",
                "Producto":"' . $etiquetaI . $producto . $etiquetaF . '",
                "Cantidad":"' . $etiquetaI . $cantidad . $etiquetaF . '",
                "UnidadMedida":"' . $etiquetaI . $unidadMedida . $etiquetaF . '",
                "PrecioUnitario":"' . $etiquetaI . '$ ' . $precio . $etiquetaF . '",
                "Impuestos":"' . $etiquetaI . $impuestos . $etiquetaF . '",
                "Importe":"' . $etiquetaI . '$ ' . $importe . $etiquetaF . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getProductosTraspasoTempTable($folioSalida)
    {
        $con = new conectar();
        $db = $con->getDb();

        $table = "";
        $PKuser = $_SESSION["PKUsuario"];
        $PKEmpresa = $_SESSION['IDEmpresa'];

        $query = sprintf('call spc_Tabla_Entradas_TraspasosTemp_ConsultaEdit(?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($folioSalida, $PKuser, $PKEmpresa));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $idEntradaTemp = $r['idEntradaTraspaso'];
            $nombre = $r['producto'];
            $clave = $r['clave'];
            $lote = $r['lote'];
            $serie = $r['serie'];
            $fechaCaducidad = $r['caducidad'];

            if ($clave == '') {
                $producto = $nombre;
            } else {
                $producto = $clave . ' - ' . $nombre;
            }

            $cantidadTotal = $r['cantidadTotal'];
            $cantidadARestante = $r['cantidadFaltante'];
            $cantidadARecibir = $r['cantidadRecibir'];

            if ($r['unidadMedida'] == '' || $r['unidadMedida'] == 'Sin Clave') {
                $unidadMedida = '(Sin unidad de medida)';
            } else {
                $unidadMedida = $r['unidadMedida'];
            }

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';

            $acciones = '<i><img class=\"btnEdit\" id=\"btnDelete-' . $idEntradaTemp . '\" width=\"20px\" height=\"20px\" src=\"../../../../img/chat/eliminar_equipo.svg\" alt=\"Eliminar lote/serie\" onclick=\"openModalDeleteProdTraspasoTemp(' . $idEntradaTemp . ')\"></i>';

            $cantidadEditable = ' <div class=\"input-group\"><input class=\"form-control invalid-emptyCount textTable border-0\" type=\"number\" value=\"' . $cantidadARecibir . '\" onchange=\"validEmptyInput2(\'txtCantidadTras-' . $idEntradaTemp . '\', \'invalid-cantidadTras-' . $idEntradaTemp . '\', \'Se requiere la cantidad a recibir.\');\" id=\"txtCantidadTras-' . $idEntradaTemp . '\" required><div class=\"invalid-feedback\" id=\"invalid-cantidadTras-' . $idEntradaTemp . '\">Se requiere la cantidad a recibir.</div></div>';


            $table .= '{"Id":"' . $etiquetaI . $idEntradaTemp . $etiquetaF . '",
                "Producto":"' . $etiquetaI . $producto . $etiquetaF . '",
                "Cantidad":"' . $cantidadEditable . '",
                "UnidadMedida":"' . $etiquetaI . $unidadMedida . $etiquetaF . '",
                "Lote":"' . $etiquetaI . $lote . $etiquetaF . '",
                "Serie":"' . $etiquetaI . $serie . $etiquetaF . '",
                "FechaCaducidad":"' . $etiquetaI . $fechaCaducidad . $etiquetaF . $acciones . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        $stmt = null;
        $db = null;

        return '{"data":[' . $table . ']}';
    }

    public function getProductosEntradaDirectaTempTable($sucOrigen)
    {
        $con = new conectar();
        $db = $con->getDb();

        $table = "";
        $PKuser = $_SESSION["PKUsuario"];

        $query = sprintf('call spc_Tabla_Entradas_DirectasTemp_ConsultaEdit(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKuser, $sucOrigen));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $idEntradaTemp = $r['idEntradaDirecta'];
            $idProducto = $r['producto_id'];
            $nombre = $r['producto'];
            $clave = $r['clave'];
            $lote = $r['lote'];
            $serie = $r['serie'];
            $fechaCaducidad = $r['caducidad'];

            if ($clave == '') {
                $producto = $nombre;
            } else {
                $producto = $clave . ' - ' . $nombre;
            }

            $cantidadARecibir = $r['cantidadRecibir'];

            if ($r['unidadMedida'] == '' || $r['unidadMedida'] == 'Sin Clave') {
                $unidadMedida = '(Sin unidad de medida)';
            } else {
                $unidadMedida = $r['unidadMedida'];
            }

            $IsLote = $r['isLote'];
            $IsSerie = $r['isSerie'];
            $IsFechaCaducidad = $r['isCaducidad'];

            $hoy = $r['hoy'];
            $repeticiones = $r['repeticiones'];

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';

            if ($IsLote == 0 && $IsSerie == 0 && $IsFechaCaducidad == 0) {
                $acciones = '<i><img class=\"btnEdit\" id=\"btnDelete-' . $idEntradaTemp . '\" width=\"20px\" height=\"20px\" src=\"../../../../img/chat/eliminar_equipo.svg\" alt=\"Eliminar lote/serie\" onclick=\"openModalDeleteED(' . $idEntradaTemp . ')\"></i>';
            } else {
                /*if ($repeticiones == 1){
                    $acciones = '<i><img class=\"btnEdit\" id=\"btnAdd-' . $idEntradaTemp . '\" width=\"20px\" height=\"20px\" src=\"../../../../img/timdesk/ICONO AGREGAR3.svg\" alt=\"Agregar lote/serie\" onclick=\"seleccionarProductoED(' .$idProducto. ')\" data-producto=\"'.$idProducto.'\"></i>';
                }else{
                    $acciones = '<i><img class=\"btnEdit\" id=\"btnDelete-' . $idEntradaTemp . '\" width=\"20px\" height=\"20px\" src=\"../../../../img/chat/eliminar_equipo.svg\" alt=\"Eliminar lote/serie\" onclick=\"openModalDeleteED(' . $idEntradaTemp . ')\"><img class=\"btnEdit\" id=\"btnAdd-' . $idEntradaTemp . '\" width=\"20px\" height=\"20px\" src=\"../../../../img/timdesk/ICONO AGREGAR3.svg\" alt=\"Agregar lote/serie\" onclick=\"seleccionarProductoED(' . $idProducto . ')\" data-producto=\"'.$idProducto.'\"></i>';
                }*/
                $acciones = '<i><img class=\"btnEdit\" id=\"btnDelete-' . $idEntradaTemp . '\" width=\"20px\" height=\"20px\" src=\"../../../../img/chat/eliminar_equipo.svg\" alt=\"Eliminar lote/serie\" onclick=\"openModalDeleteED(' . $idEntradaTemp . ')\"><img class=\"btnEdit\" id=\"btnAdd-' . $idEntradaTemp . '\" width=\"20px\" height=\"20px\" src=\"../../../../img/timdesk/ICONO AGREGAR3.svg\" alt=\"Agregar lote/serie\" onclick=\"seleccionarProductoED(' . $idProducto . ')\" data-producto=\"' . $idProducto . '\"></i>';
            }

            if ($IsLote == 1) {
                $InpLote = '<div class=\"input-group\"><input class=\"form-control invalid-empty textTable border-0\" type=\"text\" value=\"' . $lote . '\" onchange=\"validEmptyInputSL(\'txtLote-' . $idEntradaTemp . '\', \'invalid-lote-' . $idEntradaTemp . '\', \'Se requiere el lote del producto.\');\" id=\"txtLote-' . $idEntradaTemp . '\" required><div class=\"invalid-feedback\" id=\"invalid-lote-' . $idEntradaTemp . '\">Se requiere el lote del producto.</div></div>';
            } else {
                $InpLote = '<div class=\"input-group\"><input class=\"form-control textTable border-0\" type=\"text\" value=\"' . $lote . '\" id=\"txtLote-' . $idEntradaTemp . '\" disabled></div>';
            }

            if ($IsSerie == 1) {
                $InpSerie = '<div class=\"input-group\"><input class=\"form-control invalid-empty textTable border-0\" type=\"text\" value=\"' . $serie . '\" onchange=\"validEmptyInputSL(\'txtSerie-' . $idEntradaTemp . '\', \'invalid-serie-' . $idEntradaTemp . '\', \'Se requiere la serie del producto.\');\" id=\"txtSerie-' . $idEntradaTemp . '\" required><div class=\"invalid-feedback\" id=\"invalid-serie-' . $idEntradaTemp . '\">Se requiere la serie del producto.</div></div>';
            } else {
                $InpSerie = '<div class=\"input-group\"><input class=\"form-control textTable border-0\" type=\"text\" value=\"' . $serie . '\" id=\"txtSerie-' . $idEntradaTemp . '\" disabled></div>';
            }

            if ($IsFechaCaducidad == 1) {
                $InpCaducidad = '<div class=\"input-group\"><input class=\"form-control invalid-empty textTable border-0\" type=\"date\" value=\"' . $fechaCaducidad . '\" onchange=\"validEmptyInputSL(\'txtCaducidad-' . $idEntradaTemp . '\', \'invalid-caducidad-' . $idEntradaTemp . '\', \'Se requiere la caducidad del producto.\');\" id=\"txtCaducidad-' . $idEntradaTemp . '\" required min=\"' . $hoy . '\"><div class=\"invalid-feedback\" id=\"invalid-caducidad-' . $idEntradaTemp . '\">Se requiere la caducidad del producto.</div></div>';
            } else {
                $InpCaducidad = '<div class=\"input-group\"><input class=\"form-control textTable border-0\" type=\"date\" value=\"' . $fechaCaducidad . '\" id=\"txtCaducidad-' . $idEntradaTemp . '\" disabled></div>';
            }

            $cantidadEditable = ' <div class=\"input-group\"><input class=\"form-control invalid-emptyCount textTable border-0\" type=\"number\" value=\"' . $cantidadARecibir . '\" onchange=\"validEmptyInputSL(\'txtCantidadED-' . $idEntradaTemp . '\', \'invalid-cantidadED-' . $idEntradaTemp . '\', \'Se requiere la cantidad a recibir.\');\" id=\"txtCantidadED-' . $idEntradaTemp . '\" required><div class=\"invalid-feedback\" id=\"invalid-cantidadED-' . $idEntradaTemp . '\">Se requiere la cantidad a recibir.</div></div>';


            $table .= '{"Id":"' . $etiquetaI . $idEntradaTemp . $etiquetaF . '",
                "Producto":"' . $etiquetaI . $producto . $etiquetaF . '",
                "Cantidad":"' . $cantidadEditable . '",
                "UnidadMedida":"' . $etiquetaI . $unidadMedida . $etiquetaF . '",
                "Lote":"' . $etiquetaI . $InpLote . $etiquetaF . '",
                "Serie":"' . $etiquetaI . $InpSerie . $etiquetaF . '",
                "FechaCaducidad":"' . $etiquetaI . $InpCaducidad . $etiquetaF . '",
                "Acciones":"' . $acciones . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getProductosEntradaDirectaTempTableNoEdit($sucOrigen)
    {
        $con = new conectar();
        $db = $con->getDb();

        $table = "";
        $PKuser = $_SESSION["PKUsuario"];

        $query = sprintf('call spc_Tabla_Entradas_DirectasTemp_ConsultaEdit(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKuser, $sucOrigen));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $idEntradaTemp = $r['idEntradaDirecta'];
            $idProducto = $r['producto_id'];
            $nombre = $r['producto'];
            $clave = $r['clave'];
            $lote = $r['lote'];
            $serie = $r['serie'];
            $fechaCaducidad = $r['caducidad'];

            if ($clave == '') {
                $producto = $nombre;
            } else {
                $producto = $clave . ' - ' . $nombre;
            }

            $cantidadARecibir = $r['cantidadRecibir'];

            if ($r['unidadMedida'] == '' || $r['unidadMedida'] == 'Sin Clave') {
                $unidadMedida = '(Sin unidad de medida)';
            } else {
                $unidadMedida = $r['unidadMedida'];
            }

            $IsLote = $r['isLote'];
            $IsSerie = $r['isSerie'];
            $IsFechaCaducidad = $r['isCaducidad'];

            $hoy = $r['hoy'];
            $repeticiones = $r['repeticiones'];

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';

            $acciones = '';

            if ($IsLote == 1) {
                $InpLote = '<div class=\"input-group\"><input disabled class=\"form-control invalid-empty textTable border-0\" type=\"text\" value=\"' . $lote . '\" onchange=\"validEmptyInputSL(\'txtLote-' . $idEntradaTemp . '\', \'invalid-lote-' . $idEntradaTemp . '\', \'Se requiere el lote del producto.\');\" id=\"txtLote-' . $idEntradaTemp . '\" required><div class=\"invalid-feedback\" id=\"invalid-lote-' . $idEntradaTemp . '\">Se requiere el lote del producto.</div></div>';
            } else {
                $InpLote = '<div class=\"input-group\"><input class=\"form-control textTable border-0\" type=\"text\" value=\"' . $lote . '\" id=\"txtLote-' . $idEntradaTemp . '\" disabled></div>';
            }

            if ($IsSerie == 1) {
                $InpSerie = '<div class=\"input-group\"><input disabled class=\"form-control invalid-empty textTable border-0\" type=\"text\" value=\"' . $serie . '\" onchange=\"validEmptyInputSL(\'txtSerie-' . $idEntradaTemp . '\', \'invalid-serie-' . $idEntradaTemp . '\', \'Se requiere la serie del producto.\');\" id=\"txtSerie-' . $idEntradaTemp . '\" required><div class=\"invalid-feedback\" id=\"invalid-serie-' . $idEntradaTemp . '\">Se requiere la serie del producto.</div></div>';
            } else {
                $InpSerie = '<div class=\"input-group\"><input class=\"form-control textTable border-0\" type=\"text\" value=\"' . $serie . '\" id=\"txtSerie-' . $idEntradaTemp . '\" disabled></div>';
            }

            if ($IsFechaCaducidad == 1) {
                $InpCaducidad = '<div class=\"input-group\"><input disabled class=\"form-control invalid-empty textTable border-0\" type=\"date\" value=\"' . $fechaCaducidad . '\" onchange=\"validEmptyInputSL(\'txtCaducidad-' . $idEntradaTemp . '\', \'invalid-caducidad-' . $idEntradaTemp . '\', \'Se requiere la caducidad del producto.\');\" id=\"txtCaducidad-' . $idEntradaTemp . '\" required min=\"' . $hoy . '\"><div class=\"invalid-feedback\" id=\"invalid-caducidad-' . $idEntradaTemp . '\">Se requiere la caducidad del producto.</div></div>';
            } else {
                $InpCaducidad = '<div class=\"input-group\"><input class=\"form-control textTable border-0\" type=\"date\" value=\"' . $fechaCaducidad . '\" id=\"txtCaducidad-' . $idEntradaTemp . '\" disabled></div>';
            }

            $cantidadEditable = ' <div class=\"input-group\"><input disabled class=\"form-control invalid-emptyCount textTable border-0\" type=\"number\" value=\"' . $cantidadARecibir . '\" onchange=\"validEmptyInputSL(\'txtCantidadED-' . $idEntradaTemp . '\', \'invalid-cantidadED-' . $idEntradaTemp . '\', \'Se requiere la cantidad a recibir.\');\" id=\"txtCantidadED-' . $idEntradaTemp . '\" required><div class=\"invalid-feedback\" id=\"invalid-cantidadED-' . $idEntradaTemp . '\">Se requiere la cantidad a recibir.</div></div>';


            $table .= '{"Id":"' . $etiquetaI . $idEntradaTemp . $etiquetaF . '",
                "Producto":"' . $etiquetaI . $producto . $etiquetaF . '",
                "Cantidad":"' . $cantidadEditable . '",
                "UnidadMedida":"' . $etiquetaI . $unidadMedida . $etiquetaF . '",
                "Lote":"' . $etiquetaI . $InpLote . $etiquetaF . '",
                "Serie":"' . $etiquetaI . $InpSerie . $etiquetaF . '",
                "FechaCaducidad":"' . $etiquetaI . $InpCaducidad . $etiquetaF . '",
                "Acciones":"' . $acciones . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getProductosEntradaDirectaTempTableProvider($proveedor)
    {
        $con = new conectar();
        $db = $con->getDb();

        $table = "";
        $PKuser = $_SESSION["PKUsuario"];

        $query = sprintf('call spc_Tabla_Entradas_DirectasTemp_Proveedor_ConsultaEdit(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKuser, $proveedor));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $idEntradaTemp = $r['idEntradaDirecta'];
            $idProducto = $r['producto_id'];
            $nombre = $r['producto'];
            $clave = $r['clave'];
            $lote = $r['lote'];
            $serie = $r['serie'];
            $fechaCaducidad = $r['caducidad'];

            if ($clave == '') {
                $producto = $nombre;
            } else {
                $producto = $clave . ' - ' . $nombre;
            }

            $cantidadARecibir = $r['cantidadRecibir'];
            $precio = number_format($r['precio'], 2, ".", ",");
            $importe = number_format($r['importe'], 2, ".", ",");

            if ($r['unidadMedida'] == '' || $r['unidadMedida'] == 'Sin Clave') {
                $unidadMedida = '(Sin unidad de medida)';
            } else {
                $unidadMedida = $r['unidadMedida'];
            }

            $IsLote = $r['isLote'];
            $IsSerie = $r['isSerie'];
            $IsFechaCaducidad = $r['isCaducidad'];

            $hoy = $r['hoy'];

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';

            if ($IsLote == 0 && $IsSerie == 0 && $IsFechaCaducidad == 0) {
                $acciones = '<i><img class=\"btnEdit\" id=\"btnDelete-' . $idEntradaTemp . '\" width=\"20px\" height=\"20px\" src=\"../../../../img/chat/eliminar_equipo.svg\" alt=\"Eliminar lote/serie\" onclick=\"openModalDeleteEDProvider(' . $idEntradaTemp . ')\"></i>';
            } else {
                $acciones = '<i><img class=\"btnEdit\" id=\"btnDelete-' . $idEntradaTemp . '\" width=\"20px\" height=\"20px\" src=\"../../../../img/chat/eliminar_equipo.svg\" alt=\"Eliminar lote/serie\" onclick=\"openModalDeleteEDProvider(' . $idEntradaTemp . ')\"><img class=\"btnEdit\" id=\"btnAdd-' . $idEntradaTemp . '\" width=\"20px\" height=\"20px\" src=\"../../../../img/timdesk/ICONO AGREGAR3.svg\" alt=\"Agregar lote/serie\" onclick=\"seleccionarProductoEDProvider(' . $idProducto . ')\" data-producto=\"' . $idProducto . '\"></i>';
            }

            if ($IsLote == 1) {
                $InpLote = '<div class=\"input-group\"><input class=\"form-control invalid-empty textTable border-0\" type=\"text\" value=\"' . $lote . '\" onchange=\"validEmptyInputSLProvider(\'txtLote-' . $idEntradaTemp . '\', \'invalid-lote-' . $idEntradaTemp . '\', \'Se requiere el lote del producto.\');\" id=\"txtLote-' . $idEntradaTemp . '\" required><div class=\"invalid-feedback\" id=\"invalid-lote-' . $idEntradaTemp . '\">Se requiere el lote del producto.</div></div>';
            } else {
                $InpLote = '<div class=\"input-group\"><input class=\"form-control textTable border-0\" type=\"text\" value=\"' . $lote . '\" id=\"txtLote-' . $idEntradaTemp . '\" disabled></div>';
            }

            if ($IsSerie == 1) {
                $InpSerie = '<div class=\"input-group\"><input class=\"form-control invalid-empty textTable border-0\" type=\"text\" value=\"' . $serie . '\" onchange=\"validEmptyInputSLProvider(\'txtSerie-' . $idEntradaTemp . '\', \'invalid-serie-' . $idEntradaTemp . '\', \'Se requiere la serie del producto.\');\" id=\"txtSerie-' . $idEntradaTemp . '\" required><div class=\"invalid-feedback\" id=\"invalid-serie-' . $idEntradaTemp . '\">Se requiere la serie del producto.</div></div>';
            } else {
                $InpSerie = '<div class=\"input-group\"><input class=\"form-control textTable border-0\" type=\"text\" value=\"' . $serie . '\" id=\"txtSerie-' . $idEntradaTemp . '\" disabled></div>';
            }

            if ($IsFechaCaducidad == 1) {
                $InpCaducidad = '<div class=\"input-group\"><input class=\"form-control invalid-empty textTable border-0\" type=\"date\" value=\"' . $fechaCaducidad . '\" onchange=\"validEmptyInputSLProvider(\'txtCaducidad-' . $idEntradaTemp . '\', \'invalid-caducidad-' . $idEntradaTemp . '\', \'Se requiere la caducidad del producto.\');\" id=\"txtCaducidad-' . $idEntradaTemp . '\" required min=\"' . $hoy . '\"><div class=\"invalid-feedback\" id=\"invalid-caducidad-' . $idEntradaTemp . '\">Se requiere la caducidad del producto.</div></div>';
            } else {
                $InpCaducidad = '<div class=\"input-group\"><input class=\"form-control textTable border-0\" type=\"date\" value=\"' . $fechaCaducidad . '\" id=\"txtCaducidad-' . $idEntradaTemp . '\" disabled></div>';
            }

            $cantidadEditable = ' <div class=\"input-group\"><input class=\"form-control invalid-emptyCount textTable border-0\" type=\"number\" value=\"' . $cantidadARecibir . '\" onchange=\"validEmptyInputSLProvider(\'txtCantidadED-' . $idEntradaTemp . '\', \'invalid-cantidadED-' . $idEntradaTemp . '\', \'Se requiere la cantidad a recibir.\');\" id=\"txtCantidadED-' . $idEntradaTemp . '\" required><div class=\"invalid-feedback\" id=\"invalid-cantidadED-' . $idEntradaTemp . '\">Se requiere la cantidad a recibir.</div></div>';

            $precioEditable = ' <div class=\"input-group\"><input class=\"form-control invalid-emptyCount textTable border-0\" type=\"number\" value=\"' . $precio . '\" onchange=\"validEmptyInputSLProvider(\'txtPrecioED-' . $idEntradaTemp . '\', \'invalid-precioED-' . $idEntradaTemp . '\', \'Se requiere el precio del producto.\');\" id=\"txtPrecioED-' . $idEntradaTemp . '\" required><div class=\"invalid-feedback\" id=\"invalid-precioED-' . $idEntradaTemp . '\">Se requiere el precio del producto.</div></div>';

            $etiquetaImpIn = '<span class=\"textTableInactivo\" id=\"lblImpuesto-' . $idEntradaTemp . '\" name=\"lblImpuesto-' . $idEntradaTemp . '\">';

            $impuestoIEPS = number_format($r['ieps_monto_fijo'], 2, ".", ",");

            if ($r['iva'] == 0) {
                $option0 = '<option id=\"0\" name=\"opIva-' . $idEntradaTemp . '\" selected>0</option>';
            } else {
                $option0 = '<option id=\"0\" name=\"opIva-' . $idEntradaTemp . '\">0</option>';
            }

            if ($r['iva'] == 8) {
                $option8 = '<option id=\"0\" name=\"opIva-' . $idEntradaTemp . '\" selected>8</option>';
            } else {
                $option8 = '<option id=\"0\" name=\"opIva-' . $idEntradaTemp . '\">8</option>';
            }

            if ($r['iva'] == 16) {
                $option16 = '<option id=\"16\" name=\"opIva-' . $idEntradaTemp . '\" selected>16</option>';
            } else {
                $option16 = '<option id=\"16\" name=\"opIva-' . $idEntradaTemp . '\">16</option>';
            }

            $InFormGroup = '<div class=\"form-group\"><div class=\"row\">';
            $InGroup = '<div class=\"col-xl-6 col-lg-6 col-md-4 col-sm-6 col-xs-6\">';
            $FnGroup = '</div>';
            $FnFormGroup = '</div></div>';

            $impuestosIS = '';
            $impuestosIS .= $etiquetaImpIn . '<div class=\"input-group\">' . $InFormGroup . $InGroup . '<label>IEPS (MF)</label>' . $FnGroup . $InGroup . '<input class=\"form-control textTable\" type=\"number\" value=\"' . $impuestoIEPS . '\" onchange=\"validEmptyInputSLProvider(\'txtImpED-' . $idEntradaTemp . '\', \'invalid-impED-' . $idEntradaTemp . '\', \'Se requiere el monto del impuesto.\');\" id=\"txtImpED-' . $idEntradaTemp . '\" required><div class=\"invalid-feedback\" id=\"invalid-impED-' . $idEntradaTemp . '\">Se requiere el monto del impuesto.</div></div>'  . $etiquetaF . $FnGroup . $FnFormGroup;
            $impuestosIS .= $etiquetaImpIn . '<div class=\"input-group\">' . $InFormGroup . $InGroup . '<label>IVA </label>' . $FnGroup . $InGroup . '<select class=\"form-control textTable\" type=\"number\" onchange=\"validEmptyInputSLProvider(\'txtImpIVAED-' . $idEntradaTemp . '\', \'invalid-impIVAED-' . $idEntradaTemp . '\', \'Se requiere la tasa del impuesto.\');\" id=\"txtImpIVAED-' . $idEntradaTemp . '\" required style=\"width: 100px!Important;\">' . $option0 . $option8 . $option16 . '</select><div class=\"invalid-feedback\" id=\"invalid-impIVAED-' . $idEntradaTemp . '\">Se requiere la tasa del impuesto.</div></div>'  . $etiquetaF . $FnGroup . $FnFormGroup;

            /* $table .= '{"Id":"' . $etiquetaI . $idEntradaTemp . $etiquetaF . '",
                "Producto":"' . $etiquetaI . $producto . $etiquetaF . '",
                "Cantidad":"' . $cantidadEditable . '",
                "UnidadMedida":"' . $etiquetaI . $unidadMedida . $etiquetaF . '",
                "Precio":"' . $etiquetaI . $precioEditable . $etiquetaF . '",
                "Lote":"' . $etiquetaI . $InpLote . $etiquetaF . '",
                "Serie":"' . $etiquetaI . $InpSerie . $etiquetaF . '",
                "FechaCaducidad":"' . $etiquetaI . $InpCaducidad . $etiquetaF . '",
                "Impuestos":"' . $etiquetaI . $impuestosIS . $etiquetaF . '",
                "Importe":"' . '$' .$etiquetaI . $importe . $etiquetaF . '",
                "Acciones":"' . $acciones . '"},'; */

            $table .= '{"Id":"' . $etiquetaI . $idEntradaTemp . $etiquetaF . '",
                "Producto":"' . $etiquetaI . $producto . $etiquetaF . '",
                "Cantidad":"' . $cantidadEditable . '",
                "UnidadMedida":"' . $etiquetaI . $unidadMedida . $etiquetaF . '",
                "Lote":"' . $etiquetaI . $InpLote . $etiquetaF . '",
                "FechaCaducidad":"' . $etiquetaI . $InpCaducidad . $etiquetaF . '",
                "Acciones":"' . $acciones . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getProductosEntradaDirectaTempTableProviderNoEdit($proveedor)
    {
        $con = new conectar();
        $db = $con->getDb();

        $table = "";
        $PKuser = $_SESSION["PKUsuario"];

        $query = sprintf('call spc_Tabla_Entradas_DirectasTemp_Proveedor_ConsultaEdit(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKuser, $proveedor));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $idEntradaTemp = $r['idEntradaDirecta'];
            $idProducto = $r['producto_id'];
            $nombre = $r['producto'];
            $clave = $r['clave'];
            $lote = $r['lote'];
            $serie = $r['serie'];
            $fechaCaducidad = $r['caducidad'];

            if ($clave == '') {
                $producto = $nombre;
            } else {
                $producto = $clave . ' - ' . $nombre;
            }

            $cantidadARecibir = $r['cantidadRecibir'];
            $precio = number_format($r['precio'], 2, ".", ",");
            $importe = number_format($r['importe'], 2, ".", ",");

            if ($r['unidadMedida'] == '' || $r['unidadMedida'] == 'Sin Clave') {
                $unidadMedida = '(Sin unidad de medida)';
            } else {
                $unidadMedida = $r['unidadMedida'];
            }

            $IsLote = $r['isLote'];
            $IsSerie = $r['isSerie'];
            $IsFechaCaducidad = $r['isCaducidad'];

            $hoy = $r['hoy'];

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';

            $acciones = '';

            if ($IsLote == 1) {
                $InpLote = '<div class=\"input-group\"><input disabled class=\"form-control invalid-empty textTable border-0\" type=\"text\" value=\"' . $lote . '\" onchange=\"validEmptyInputSLProvider(\'txtLote-' . $idEntradaTemp . '\', \'invalid-lote-' . $idEntradaTemp . '\', \'Se requiere el lote del producto.\');\" id=\"txtLote-' . $idEntradaTemp . '\" required><div class=\"invalid-feedback\" id=\"invalid-lote-' . $idEntradaTemp . '\">Se requiere el lote del producto.</div></div>';
            } else {
                $InpLote = '<div class=\"input-group\"><input class=\"form-control textTable border-0\" type=\"text\" value=\"' . $lote . '\" id=\"txtLote-' . $idEntradaTemp . '\" disabled></div>';
            }

            if ($IsSerie == 1) {
                $InpSerie = '<div class=\"input-group\"><input disabled class=\"form-control invalid-empty textTable border-0\" type=\"text\" value=\"' . $serie . '\" onchange=\"validEmptyInputSLProvider(\'txtSerie-' . $idEntradaTemp . '\', \'invalid-serie-' . $idEntradaTemp . '\', \'Se requiere la serie del producto.\');\" id=\"txtSerie-' . $idEntradaTemp . '\" required><div class=\"invalid-feedback\" id=\"invalid-serie-' . $idEntradaTemp . '\">Se requiere la serie del producto.</div></div>';
            } else {
                $InpSerie = '<div class=\"input-group\"><input class=\"form-control textTable border-0\" type=\"text\" value=\"' . $serie . '\" id=\"txtSerie-' . $idEntradaTemp . '\" disabled></div>';
            }

            if ($IsFechaCaducidad == 1) {
                $InpCaducidad = '<div class=\"input-group\"><input disabled class=\"form-control invalid-empty textTable border-0\" type=\"date\" value=\"' . $fechaCaducidad . '\" onchange=\"validEmptyInputSLProvider(\'txtCaducidad-' . $idEntradaTemp . '\', \'invalid-caducidad-' . $idEntradaTemp . '\', \'Se requiere la caducidad del producto.\');\" id=\"txtCaducidad-' . $idEntradaTemp . '\" required min=\"' . $hoy . '\"><div class=\"invalid-feedback\" id=\"invalid-caducidad-' . $idEntradaTemp . '\">Se requiere la caducidad del producto.</div></div>';
            } else {
                $InpCaducidad = '<div class=\"input-group\"><input class=\"form-control textTable border-0\" type=\"date\" value=\"' . $fechaCaducidad . '\" id=\"txtCaducidad-' . $idEntradaTemp . '\" disabled></div>';
            }

            $cantidadEditable = ' <div class=\"input-group\"><input disabled class=\"form-control invalid-emptyCount textTable border-0\" type=\"number\" value=\"' . $cantidadARecibir . '\" onchange=\"validEmptyInputSLProvider(\'txtCantidadED-' . $idEntradaTemp . '\', \'invalid-cantidadED-' . $idEntradaTemp . '\', \'Se requiere la cantidad a recibir.\');\" id=\"txtCantidadED-' . $idEntradaTemp . '\" required><div class=\"invalid-feedback\" id=\"invalid-cantidadED-' . $idEntradaTemp . '\">Se requiere la cantidad a recibir.</div></div>';

            $precioEditable = ' <div class=\"input-group\"><input disabled class=\"form-control invalid-emptyCount textTable border-0\" type=\"number\" value=\"' . $precio . '\" onchange=\"validEmptyInputSLProvider(\'txtPrecioED-' . $idEntradaTemp . '\', \'invalid-precioED-' . $idEntradaTemp . '\', \'Se requiere el precio del producto.\');\" id=\"txtPrecioED-' . $idEntradaTemp . '\" required><div class=\"invalid-feedback\" id=\"invalid-precioED-' . $idEntradaTemp . '\">Se requiere el precio del producto.</div></div>';

            $etiquetaImpIn = '<span class=\"textTableInactivo\" id=\"lblImpuesto-' . $idEntradaTemp . '\" name=\"lblImpuesto-' . $idEntradaTemp . '\">';

            $impuestoIEPS = number_format($r['ieps_monto_fijo'], 2, ".", ",");

            if ($r['iva'] == 0) {
                $option0 = '<option id=\"0\" name=\"opIva-' . $idEntradaTemp . '\" selected>0</option>';
            } else {
                $option0 = '<option id=\"0\" name=\"opIva-' . $idEntradaTemp . '\">0</option>';
            }

            if ($r['iva'] == 8) {
                $option8 = '<option id=\"0\" name=\"opIva-' . $idEntradaTemp . '\" selected>8</option>';
            } else {
                $option8 = '<option id=\"0\" name=\"opIva-' . $idEntradaTemp . '\">8</option>';
            }

            if ($r['iva'] == 16) {
                $option16 = '<option id=\"16\" name=\"opIva-' . $idEntradaTemp . '\" selected>16</option>';
            } else {
                $option16 = '<option id=\"16\" name=\"opIva-' . $idEntradaTemp . '\">16</option>';
            }

            $InFormGroup = '<div class=\"form-group\"><div class=\"row\">';
            $InGroup = '<div class=\"col-xl-6 col-lg-6 col-md-4 col-sm-6 col-xs-6\">';
            $FnGroup = '</div>';
            $FnFormGroup = '</div></div>';

            $impuestosIS = '';
            $impuestosIS .= $etiquetaImpIn . '<div class=\"input-group\">' . $InFormGroup . $InGroup . '<label>IEPS (MF)</label>' . $FnGroup . $InGroup . '<input disabled class=\"form-control textTable\" type=\"number\" value=\"' . $impuestoIEPS . '\" onchange=\"validEmptyInputSLProvider(\'txtImpED-' . $idEntradaTemp . '\', \'invalid-impED-' . $idEntradaTemp . '\', \'Se requiere el monto del impuesto.\');\" id=\"txtImpED-' . $idEntradaTemp . '\" required><div class=\"invalid-feedback\" id=\"invalid-impED-' . $idEntradaTemp . '\">Se requiere el monto del impuesto.</div></div>'  . $etiquetaF . $FnGroup . $FnFormGroup;
            $impuestosIS .= $etiquetaImpIn . '<div class=\"input-group\">' . $InFormGroup . $InGroup . '<label>IVA </label>' . $FnGroup . $InGroup . '<select disabled class=\"form-control textTable\" type=\"number\" onchange=\"validEmptyInputSLProvider(\'txtImpIVAED-' . $idEntradaTemp . '\', \'invalid-impIVAED-' . $idEntradaTemp . '\', \'Se requiere la tasa del impuesto.\');\" id=\"txtImpIVAED-' . $idEntradaTemp . '\" required style=\"width: 100px!Important;\">' . $option0 . $option8 . $option16 . '</select><div class=\"invalid-feedback\" id=\"invalid-impIVAED-' . $idEntradaTemp . '\">Se requiere la tasa del impuesto.</div></div>'  . $etiquetaF . $FnGroup . $FnFormGroup;

            $table .= '{"Id":"' . $etiquetaI . $idEntradaTemp . $etiquetaF . '",
                "Producto":"' . $etiquetaI . $producto . $etiquetaF . '",
                "Cantidad":"' . $cantidadEditable . '",
                "UnidadMedida":"' . $etiquetaI . $unidadMedida . $etiquetaF . '",
                "Precio":"' . $etiquetaI . $precioEditable . $etiquetaF . '",
                "Lote":"' . $etiquetaI . $InpLote . $etiquetaF . '",
                "Serie":"' . $etiquetaI . $InpSerie . $etiquetaF . '",
                "FechaCaducidad":"' . $etiquetaI . $InpCaducidad . $etiquetaF . '",
                "Impuestos":"' . $etiquetaI . $impuestosIS . $etiquetaF . '",
                "Importe":"' . '$' . $etiquetaI . $importe . $etiquetaF . '",
                "Acciones":"' . $acciones . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getProductosEntradaDirectaTempTableCustomer($cliente)
    {
        $con = new conectar();
        $db = $con->getDb();

        $table = "";
        $PKuser = $_SESSION["PKUsuario"];

        $query = sprintf('call spc_Tabla_Entradas_DirectasTemp_ConsultaEdit(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKuser, $cliente));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $idEntradaTemp = $r['idEntradaDirecta'];
            $idProducto = $r['producto_id'];
            $nombre = $r['producto'];
            $clave = $r['clave'];
            $lote = $r['lote'];
            $serie = $r['serie'];
            $fechaCaducidad = $r['caducidad'];

            if ($clave == '') {
                $producto = $nombre;
            } else {
                $producto = $clave . ' - ' . $nombre;
            }

            $cantidadARecibir = $r['cantidadRecibir'];

            if ($r['unidadMedida'] == '' || $r['unidadMedida'] == 'Sin Clave') {
                $unidadMedida = '(Sin unidad de medida)';
            } else {
                $unidadMedida = $r['unidadMedida'];
            }

            $IsLote = $r['isLote'];
            $IsSerie = $r['isSerie'];
            $IsFechaCaducidad = $r['isCaducidad'];

            $hoy = $r['hoy'];
            $repeticiones = $r['repeticiones'];

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';

            if ($IsLote == 0 && $IsSerie == 0 && $IsFechaCaducidad == 0) {
                $acciones = '<i><img class=\"btnEdit\" id=\"btnDelete-' . $idEntradaTemp . '\" width=\"20px\" height=\"20px\" src=\"../../../../img/chat/eliminar_equipo.svg\" alt=\"Eliminar lote/serie\" onclick=\"openModalDeleteEDCustomer(' . $idEntradaTemp . ')\"></i>';
            } else {
                $acciones = '<i><img class=\"btnEdit\" id=\"btnDelete-' . $idEntradaTemp . '\" width=\"20px\" height=\"20px\" src=\"../../../../img/chat/eliminar_equipo.svg\" alt=\"Eliminar lote/serie\" onclick=\"openModalDeleteEDCustomer(' . $idEntradaTemp . ')\"><img class=\"btnEdit\" id=\"btnAdd-' . $idEntradaTemp . '\" width=\"20px\" height=\"20px\" src=\"../../../../img/timdesk/ICONO AGREGAR3.svg\" alt=\"Agregar lote/serie\" onclick=\"seleccionarProductoEDCustomer(' . $idProducto . ')\" data-producto=\"' . $idProducto . '\"></i>';
            }

            if ($IsLote == 1) {
                $InpLote = '<div class=\"input-group\"><input class=\"form-control invalid-empty textTable border-0\" type=\"text\" value=\"' . $lote . '\" onchange=\"validEmptyInputSLCustomer(\'txtLote-' . $idEntradaTemp . '\', \'invalid-lote-' . $idEntradaTemp . '\', \'Se requiere el lote del producto.\');\" id=\"txtLote-' . $idEntradaTemp . '\" required><div class=\"invalid-feedback\" id=\"invalid-lote-' . $idEntradaTemp . '\">Se requiere el lote del producto.</div></div>';
            } else {
                $InpLote = '<div class=\"input-group\"><input class=\"form-control textTable border-0\" type=\"text\" value=\"' . $lote . '\" id=\"txtLote-' . $idEntradaTemp . '\" disabled></div>';
            }

            if ($IsSerie == 1) {
                $InpSerie = '<div class=\"input-group\"><input class=\"form-control invalid-empty textTable border-0\" type=\"text\" value=\"' . $serie . '\" onchange=\"validEmptyInputSLCustomer(\'txtSerie-' . $idEntradaTemp . '\', \'invalid-serie-' . $idEntradaTemp . '\', \'Se requiere la serie del producto.\');\" id=\"txtSerie-' . $idEntradaTemp . '\" required><div class=\"invalid-feedback\" id=\"invalid-serie-' . $idEntradaTemp . '\">Se requiere la serie del producto.</div></div>';
            } else {
                $InpSerie = '<div class=\"input-group\"><input class=\"form-control textTable border-0\" type=\"text\" value=\"' . $serie . '\" id=\"txtSerie-' . $idEntradaTemp . '\" disabled></div>';
            }

            if ($IsFechaCaducidad == 1) {
                $InpCaducidad = '<div class=\"input-group\"><input class=\"form-control invalid-empty textTable border-0\" type=\"date\" value=\"' . $fechaCaducidad . '\" onchange=\"validEmptyInputSLCustomer(\'txtCaducidad-' . $idEntradaTemp . '\', \'invalid-caducidad-' . $idEntradaTemp . '\', \'Se requiere la caducidad del producto.\');\" id=\"txtCaducidad-' . $idEntradaTemp . '\" required min=\"' . $hoy . '\"><div class=\"invalid-feedback\" id=\"invalid-caducidad-' . $idEntradaTemp . '\">Se requiere la caducidad del producto.</div></div>';
            } else {
                $InpCaducidad = '<div class=\"input-group\"><input class=\"form-control textTable border-0\" type=\"date\" value=\"' . $fechaCaducidad . '\" id=\"txtCaducidad-' . $idEntradaTemp . '\" disabled></div>';
            }

            $cantidadEditable = ' <div class=\"input-group\"><input class=\"form-control invalid-emptyCount textTable border-0\" type=\"number\" value=\"' . $cantidadARecibir . '\" onchange=\"validEmptyInputSLCustomer(\'txtCantidadEDCustomer-' . $idEntradaTemp . '\', \'invalid-cantidadEDCustomer-' . $idEntradaTemp . '\', \'Se requiere la cantidad a recibir.\');\" id=\"txtCantidadEDCustomer-' . $idEntradaTemp . '\" required><div class=\"invalid-feedback\" id=\"invalid-cantidadEDCustomer-' . $idEntradaTemp . '\">Se requiere la cantidad a recibir.</div></div>';


            $table .= '{"Id":"' . $etiquetaI . $idEntradaTemp . $etiquetaF . '",
                "Producto":"' . $etiquetaI . $producto . $etiquetaF . '",
                "Cantidad":"' . $cantidadEditable . '",
                "UnidadMedida":"' . $etiquetaI . $unidadMedida . $etiquetaF . '",
                "Lote":"' . $etiquetaI . $InpLote . $etiquetaF . '",
                "FechaCaducidad":"' . $etiquetaI . $InpCaducidad . $etiquetaF . '",
                "Acciones":"' . $acciones . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getProductosEntradaDirectaTempTableCustomerNoEdit($cliente)
    {
        $con = new conectar();
        $db = $con->getDb();

        $table = "";
        $PKuser = $_SESSION["PKUsuario"];

        $query = sprintf('call spc_Tabla_Entradas_DirectasTemp_ConsultaEdit(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKuser, $cliente));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $idEntradaTemp = $r['idEntradaDirecta'];
            $idProducto = $r['producto_id'];
            $nombre = $r['producto'];
            $clave = $r['clave'];
            $lote = $r['lote'];
            $serie = $r['serie'];
            $fechaCaducidad = $r['caducidad'];

            if ($clave == '') {
                $producto = $nombre;
            } else {
                $producto = $clave . ' - ' . $nombre;
            }

            $cantidadARecibir = $r['cantidadRecibir'];

            if ($r['unidadMedida'] == '' || $r['unidadMedida'] == 'Sin Clave') {
                $unidadMedida = '(Sin unidad de medida)';
            } else {
                $unidadMedida = $r['unidadMedida'];
            }

            $IsLote = $r['isLote'];
            $IsSerie = $r['isSerie'];
            $IsFechaCaducidad = $r['isCaducidad'];

            $hoy = $r['hoy'];
            $repeticiones = $r['repeticiones'];

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';

            $acciones = '';

            if ($IsLote == 1) {
                $InpLote = '<div class=\"input-group\"><input disabled class=\"form-control invalid-empty textTable border-0\" type=\"text\" value=\"' . $lote . '\" onchange=\"validEmptyInputSLCustomer(\'txtLote-' . $idEntradaTemp . '\', \'invalid-lote-' . $idEntradaTemp . '\', \'Se requiere el lote del producto.\');\" id=\"txtLote-' . $idEntradaTemp . '\" required><div class=\"invalid-feedback\" id=\"invalid-lote-' . $idEntradaTemp . '\">Se requiere el lote del producto.</div></div>';
            } else {
                $InpLote = '<div class=\"input-group\"><input class=\"form-control textTable border-0\" type=\"text\" value=\"' . $lote . '\" id=\"txtLote-' . $idEntradaTemp . '\" disabled></div>';
            }

            if ($IsSerie == 1) {
                $InpSerie = '<div class=\"input-group\"><input disabled class=\"form-control invalid-empty textTable border-0\" type=\"text\" value=\"' . $serie . '\" onchange=\"validEmptyInputSLCustomer(\'txtSerie-' . $idEntradaTemp . '\', \'invalid-serie-' . $idEntradaTemp . '\', \'Se requiere la serie del producto.\');\" id=\"txtSerie-' . $idEntradaTemp . '\" required><div class=\"invalid-feedback\" id=\"invalid-serie-' . $idEntradaTemp . '\">Se requiere la serie del producto.</div></div>';
            } else {
                $InpSerie = '<div class=\"input-group\"><input class=\"form-control textTable border-0\" type=\"text\" value=\"' . $serie . '\" id=\"txtSerie-' . $idEntradaTemp . '\" disabled></div>';
            }

            if ($IsFechaCaducidad == 1) {
                $InpCaducidad = '<div class=\"input-group\"><input disabled class=\"form-control invalid-empty textTable border-0\" type=\"date\" value=\"' . $fechaCaducidad . '\" onchange=\"validEmptyInputSLCustomer(\'txtCaducidad-' . $idEntradaTemp . '\', \'invalid-caducidad-' . $idEntradaTemp . '\', \'Se requiere la caducidad del producto.\');\" id=\"txtCaducidad-' . $idEntradaTemp . '\" required min=\"' . $hoy . '\"><div class=\"invalid-feedback\" id=\"invalid-caducidad-' . $idEntradaTemp . '\">Se requiere la caducidad del producto.</div></div>';
            } else {
                $InpCaducidad = '<div class=\"input-group\"><input class=\"form-control textTable border-0\" type=\"date\" value=\"' . $fechaCaducidad . '\" id=\"txtCaducidad-' . $idEntradaTemp . '\" disabled></div>';
            }

            $cantidadEditable = ' <div class=\"input-group\"><input disabled class=\"form-control invalid-emptyCount textTable border-0\" type=\"number\" value=\"' . $cantidadARecibir . '\" onchange=\"validEmptyInputSLCustomer(\'txtCantidadEDCustomer-' . $idEntradaTemp . '\', \'invalid-cantidadEDCustomer-' . $idEntradaTemp . '\', \'Se requiere la cantidad a recibir.\');\" id=\"txtCantidadEDCustomer-' . $idEntradaTemp . '\" required><div class=\"invalid-feedback\" id=\"invalid-cantidadEDCustomer-' . $idEntradaTemp . '\">Se requiere la cantidad a recibir.</div></div>';


            $table .= '{"Id":"' . $etiquetaI . $idEntradaTemp . $etiquetaF . '",
                "Producto":"' . $etiquetaI . $producto . $etiquetaF . '",
                "Cantidad":"' . $cantidadEditable . '",
                "UnidadMedida":"' . $etiquetaI . $unidadMedida . $etiquetaF . '",
                "Lote":"' . $etiquetaI . $InpLote . $etiquetaF . '",
                "Serie":"' . $etiquetaI . $InpSerie . $etiquetaF . '",
                "FechaCaducidad":"' . $etiquetaI . $InpCaducidad . $etiquetaF . '",
                "Acciones":"' . $acciones . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getProductosSucursalEDTable($sucOrigen)
    {
        $con = new conectar();
        $db = $con->getDb();

        $table = "";
        $PKEmpresa = $_SESSION["IDEmpresa"];

        $stmt = $db->prepare('call spc_Tabla_EDSucursal_Productos_Consulta(?,?)');
        $stmt->execute(array($sucOrigen, $PKEmpresa));
        $array = $stmt->fetchAll();

        $acciones = '';

        foreach ($array as $r) {

            $Id = $r['id'];
            $clave = $r['clave'];
            $producto = $r['producto'];

            $acciones = '<i><img class=\"btnEdit\" src=\"../../../../img/timdesk/ICONO ESTADO-TERMINADO_Mesa de trabajo 1.svg\" onclick=\"seleccionarProductoED(\'' . $Id . '\');\"></i>';

            $etiquetaI = '<label class=\"textTable\">';
            $etiquetaF = '</label>';

            $table .= '{"Id":"' . $etiquetaI . $Id . $etiquetaF . '",
                  "Clave":"' . $etiquetaI . $clave . $etiquetaF . '",
                  "Producto":"' . $etiquetaI . $producto . $etiquetaF . " " . $acciones . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getProductosProveedorEDTable($proveedor)
    {
        $con = new conectar();
        $db = $con->getDb();

        $table = "";
        $PKEmpresa = $_SESSION["IDEmpresa"];

        $stmt = $db->prepare('call spc_Tabla_EDSucursal_Productos_Consulta(?,?)');
        $stmt->execute(array($proveedor, $PKEmpresa));
        $array = $stmt->fetchAll();

        $acciones = '';

        foreach ($array as $r) {

            $Id = $r['id'];
            $clave = $r['clave'];
            $producto = str_replace(["\r", "\n"], "", str_replace('"', '\"', $r['producto']));

            $acciones = '<i><img class=\"btnEdit\" src=\"../../../../img/timdesk/ICONO ESTADO-TERMINADO_Mesa de trabajo 1.svg\" onclick=\"seleccionarProductoEDProvider(\'' . $Id . '\');\"></i>';

            $etiquetaI = '<label class=\"textTable btnEdit\" onclick=\"seleccionarProductoEDProvider(\'' . $Id . '\');\">';
            $etiquetaF = '</label>';

            $table .= '{"Id":"' . $etiquetaI . $Id . $etiquetaF . '",
                  "Clave":"' . $etiquetaI . $clave . $etiquetaF . '",
                  "Producto":"' . $etiquetaI . $producto . $etiquetaF . " " . $acciones . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getProductosCustomerEDTable($cliente)
    {
        $con = new conectar();
        $db = $con->getDb();

        $table = "";
        $PKEmpresa = $_SESSION["IDEmpresa"];

        $stmt = $db->prepare('call spc_Tabla_EDSucursal_Productos_Consulta(?,?)');
        $stmt->execute(array($cliente, $PKEmpresa));
        $array = $stmt->fetchAll();

        $acciones = '';

        foreach ($array as $r) {

            $Id = $r['id'];
            $clave = $r['clave'];
            $producto = $r['producto'];

            $acciones = '<i><img class=\"btnEdit\" src=\"../../../../img/timdesk/ICONO ESTADO-TERMINADO_Mesa de trabajo 1.svg\" onclick=\"seleccionarProductoEDCustomer(\'' . $Id . '\');\"></i>';

            $etiquetaI = '<label class=\"textTable btnEdit\" onclick=\"seleccionarProductoEDCustomer(\'' . $Id . '\');\">';
            $etiquetaF = '</label>';

            $table .= '{"Id":"' . $etiquetaI . $Id . $etiquetaF . '",
                  "Clave":"' . $etiquetaI . $clave . $etiquetaF . '",
                  "Producto":"' . $etiquetaI . $producto . $etiquetaF . " " . $acciones . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getProductosEntradaOCTempTable($pkOrden)
    {
        $con = new conectar();
        $db = $con->getDb();

        $table = "";

        if (isset($_SESSION["PKUsuario"])) {
            $PKuser = $_SESSION["PKUsuario"];
        } else {
            $PKuser = '0';
        }

        $query = sprintf('call spc_Tabla_EntradasOCTemp_ConsultaEdit(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkOrden, $PKuser));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id = $r['id'];
            $idDetalle = $r['idDetalle'];
            $idEntradaTemp = $r['idEntradaOCTemp'];
            $nombre = $r['nombre'];
            $clave = $r['clave'];
            $lote = $r['lote'];
            $serie = $r['serie'];
            $fechaCaducidad = $r['fechaCaducidad'];
            $IsLote = $r['isLote'];
            $IsSerie = $r['isSerie'];
            $IsFechaCaducidad = $r['isCaducidad'];

            if ($r['clave'] == '') {
                $producto = $r['nombre'];
            } else {
                $producto = $r['clave'] . ' - ' . $r['nombre'];
            }

            $cantidadRecibida = $r['cantidadRecibida'];
            $cantidad = $r['cantidadARecibir'];
            $cantidadARestante = $r['cantidadARestante'];

            if ($r['unidadMedida'] == '') {
                $unidadMedida = '(Sin unidad de medida)';
            } else {
                $unidadMedida = $r['unidadMedida'];
            }

            $hoy = $r['hoy'];
            $repeticiones = $r['repeticiones'];

            $precio = $r['precio'];
            $importe = $r['importe'];
            $impuestos = explode(' / ', $r['impuestos']);
            $minima = $r['minima'];
            $isImpuestos = explode(' / ', $r['isImpuestos']);
            $idImpuestoProd = explode(' / ', $r['idImpuestoProd']);

            $precio = number_format($precio, 2, ".", ",");
            $importe = number_format($importe, 2, ".", ",");

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';

            if ($IsLote == 0 && $IsSerie == 0 && $IsFechaCaducidad == 0) {
                $acciones = '';
            } else {
                if ($repeticiones == 1) {
                    $acciones = '<i><img class=\"btnEdit\" id=\"btnAdd-' . $idEntradaTemp . '\" width=\"20px\" height=\"20px\" src=\"../../../../img/timdesk/ICONO AGREGAR3.svg\" alt=\"Agregar lote/serie\" onclick=\"addLoteSerie(' . $idEntradaTemp . ')\"></i>';
                } else {
                    $acciones = '<i><img class=\"btnEdit\" id=\"btnDelete-' . $idEntradaTemp . '\" width=\"20px\" height=\"20px\" src=\"../../../../img/chat/eliminar_equipo.svg\" alt=\"Eliminar lote/serie\" onclick=\"openModalDelete(' . $idEntradaTemp . ')\"><img class=\"btnEdit\" id=\"btnAdd-' . $idEntradaTemp . '\" width=\"20px\" height=\"20px\" src=\"../../../../img/timdesk/ICONO AGREGAR3.svg\" alt=\"Agregar lote/serie\" onclick=\"addLoteSerie(' . $idEntradaTemp . ')\"></i>';
                }
            }

            $impuestosIS = '';

            $cantidadEditable = ' <div class=\"input-group\"><input class=\"form-control invalid-emptyCount textTable border-0\" type=\"number\" value=\"' . $cantidad . '\" onchange=\"validEmptyInput(\'txtCantidad-' . $idEntradaTemp . '\', \'invalid-cantidad-' . $idEntradaTemp . '\', \'Se requiere la cantidad a recibir.\');\" id=\"txtCantidad-' . $idEntradaTemp . '\" required><div class=\"invalid-feedback\" id=\"invalid-cantidad-' . $idEntradaTemp . '\">Se requiere la cantidad a recibir.</div></div>';

            if ($IsLote == 1) {
                $InpLote = '<div class=\"input-group\"><input class=\"form-control invalid-empty textTable border-0\" type=\"text\" value=\"' . $lote . '\" onchange=\"validEmptyInput(\'txtLote-' . $idEntradaTemp . '\', \'invalid-lote-' . $idEntradaTemp . '\', \'Se requiere el lote del producto.\');\" id=\"txtLote-' . $idEntradaTemp . '\" required><div class=\"invalid-feedback\" id=\"invalid-lote-' . $idEntradaTemp . '\">Se requiere el lote del producto.</div></div>';
            } else {
                $InpLote = '<div class=\"input-group\"><input class=\"form-control textTable border-0\" type=\"text\" value=\"' . $lote . '\" id=\"txtLote-' . $idEntradaTemp . '\" disabled></div>';
            }

            if ($IsSerie == 1) {
                $InpSerie = '<div class=\"input-group\"><input class=\"form-control invalid-empty textTable border-0\" type=\"text\" value=\"' . $serie . '\" onchange=\"validEmptyInput(\'txtSerie-' . $idEntradaTemp . '\', \'invalid-serie-' . $idEntradaTemp . '\', \'Se requiere la serie del producto.\');\" id=\"txtSerie-' . $idEntradaTemp . '\" required><div class=\"invalid-feedback\" id=\"invalid-serie-' . $idEntradaTemp . '\">Se requiere la serie del producto.</div></div>';
            } else {
                $InpSerie = '<div class=\"input-group\"><input class=\"form-control textTable border-0\" type=\"text\" value=\"' . $serie . '\" id=\"txtSerie-' . $idEntradaTemp . '\" disabled></div>';
            }

            if ($IsFechaCaducidad == 1) {
                $InpCaducidad = '<div class=\"input-group\"><input class=\"form-control invalid-empty textTable border-0\" type=\"date\" value=\"' . $fechaCaducidad . '\" onchange=\"validEmptyInput(\'txtCaducidad-' . $idEntradaTemp . '\', \'invalid-caducidad-' . $idEntradaTemp . '\', \'Se requiere la caducidad del producto.\');\" id=\"txtCaducidad-' . $idEntradaTemp . '\" required min=\"' . $hoy . '\"><div class=\"invalid-feedback\" id=\"invalid-caducidad-' . $idEntradaTemp . '\">Se requiere la caducidad del producto.</div></div>';
            } else {
                $InpCaducidad = '<div class=\"input-group\"><input class=\"form-control textTable border-0\" type=\"date\" value=\"' . $fechaCaducidad . '\" id=\"txtCaducidad-' . $idEntradaTemp . '\" disabled></div>';
            }

            for ($i = 0; $i < count($isImpuestos); $i++) {
                if (isset($idImpuestoProd[$i])) {
                    $checkImpuestos = ' <input class=\"form-check-input\" type=\"checkbox\" data-imp=\"' . $idImpuestoProd[$i] . '\" id=\"cbxImpuestos-' . $idEntradaTemp . '-' . $idImpuestoProd[$i] . '\"  name=\"cbxImpuestos-' . $idEntradaTemp . '-' . $idImpuestoProd[$i] . '\" onclick=\"activarImpuestos(\'' . $idEntradaTemp . '-' . $idImpuestoProd[$i] . '\')\" checked> ';
                    $checkImpuestosIn = ' <input class=\"form-check-input\" type=\"checkbox\" data-imp=\"' . $idImpuestoProd[$i] . '\" id=\"cbxImpuestos-' . $idEntradaTemp . '-' . $idImpuestoProd[$i] . '\" name=\"cbxImpuestos-' . $idEntradaTemp . '-' . $idImpuestoProd[$i] . '\" onclick=\"activarImpuestos(\'' . $idEntradaTemp . '-' . $idImpuestoProd[$i] . '\')\"> ';
                    $etiquetaImpI = '<span class=\"textTable\" id=\"lblImpuesto-' . $idImpuestoProd[$i] . '\" name=\"lblImpuesto-' . $idImpuestoProd[$i] . '\"><br>';
                    $etiquetaImpIn = '<span class=\"textTableInactivo\" id=\"lblImpuesto-' . $idImpuestoProd[$i] . '\" name=\"lblImpuesto-' . $idImpuestoProd[$i] . '\"><br>';

                    if ($isImpuestos[$i] == '1') {
                        if (isset($impuestos[$i])) {
                            if ($impuestos[$i] != null) {
                                $impuestosIS .= $etiquetaImpI . $checkImpuestos . $impuestos[$i] . $etiquetaF;
                            }
                        }
                    } else {
                        if (isset($impuestos[$i])) {
                            if ($impuestos[$i] != null) {
                                $impuestosIS .= $etiquetaImpIn . $checkImpuestosIn . $impuestos[$i] . $etiquetaF;
                            }
                        }
                    }
                }
            }

            $table .= '{"Id":"' . $etiquetaI . $idDetalle . $etiquetaF . '",
                "Producto":"' . $etiquetaI . $producto . $etiquetaF . '",
                "CantidadRecibida":"' . $etiquetaI . $cantidadRecibida . $etiquetaF . '",
                "Cantidad":"' . $cantidadEditable . '",
                "UnidadMedida":"' . $etiquetaI . $unidadMedida . $etiquetaF . '",
                "Lote":"' . $etiquetaI . $InpLote . $etiquetaF . '",
                "Serie":"' . $etiquetaI . $InpSerie . $etiquetaF . '",
                "FechaCaducidad":"' . $etiquetaI . $InpCaducidad . $etiquetaF . '",
                "PrecioUnitario":"' . $etiquetaI . '$ ' . $precio . $etiquetaF . '",
                "Impuestos":"' . $impuestosIS . '",
                "Importe":"' . $etiquetaI . '$ ' . $importe . $etiquetaF . $acciones . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getProductosEntradaOCTempTableEdit($folioEntrada)
    {
        $con = new conectar();
        $db = $con->getDb();

        $table = "";

        if (isset($_SESSION["IDEmpresa"])) {
            $PKEmpresa = $_SESSION["IDEmpresa"];
        } else {
            $PKEmpresa = '0';
        }

        if (isset($_SESSION["PKUsuario"])) {
            $PKuser = $_SESSION["PKUsuario"];
        } else {
            $PKuser = '0';
        }

        $query = sprintf('call spc_Tabla_EntradasOCTemp_Edicion(?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($folioEntrada, $PKEmpresa, $PKuser));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id = $r['id'];
            $idDetalle = $r['idDetalle'];
            $idEntradaTemp = $r['idEntradaOCTemp'];
            $nombre = $r['nombre'];
            $clave = $r['clave'];
            $lote = $r['lote'];
            $serie = $r['serie'];
            $fechaCaducidad = $r['fechaCaducidad'];
            $IsLote = $r['isLote'];
            $IsSerie = $r['isSerie'];
            $IsFechaCaducidad = $r['isCaducidad'];

            if ($r['clave'] == '') {
                $producto = $r['nombre'];
            } else {
                $producto = $r['clave'] . ' - ' . $r['nombre'];
            }

            $cantidadRecibida = $r['cantidadRecibida'];
            $cantidad = $r['cantidadARecibir'];
            $cantidadARestante = $r['cantidadARestante'];

            if ($r['unidadMedida'] == '') {
                $unidadMedida = '(Sin unidad de medida)';
            } else {
                $unidadMedida = $r['unidadMedida'];
            }

            $hoy = $r['hoy'];
            $repeticiones = $r['repeticiones'];

            $precio = $r['precio'];
            $importe = $r['importe'];
            $impuestos = explode(' / ', $r['impuestos']);
            $minima = $r['minima'];
            $isImpuestos = explode(' / ', $r['isImpuestos']);
            $idImpuestoProd = explode(' / ', $r['idImpuestoProd']);

            $precio = number_format($precio, 2, ".", ",");
            $importe = number_format($importe, 2, ".", ",");

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';

            if ($IsLote == 0 && $IsSerie == 0 && $IsFechaCaducidad == 0) {
                $acciones = '';
            } else {
                if ($repeticiones == 1) {
                    $acciones = '<i><img class=\"btnEdit\" id=\"btnAdd-' . $idEntradaTemp . '\" width=\"20px\" height=\"20px\" src=\"../../../../img/timdesk/ICONO AGREGAR3.svg\" alt=\"Agregar lote/serie\" onclick=\"addLoteSerie(' . $idEntradaTemp . ')\"></i>';
                } else {
                    $acciones = '<i><img class=\"btnEdit\" id=\"btnDelete-' . $idEntradaTemp . '\" width=\"20px\" height=\"20px\" src=\"../../../../img/chat/eliminar_equipo.svg\" alt=\"Eliminar lote/serie\" onclick=\"openModalDelete(' . $idEntradaTemp . ')\"><img class=\"btnEdit\" id=\"btnAdd-' . $idEntradaTemp . '\" width=\"20px\" height=\"20px\" src=\"../../../../img/timdesk/ICONO AGREGAR3.svg\" alt=\"Agregar lote/serie\" onclick=\"addLoteSerie(' . $idEntradaTemp . ')\"></i>';
                }
            }

            $impuestosIS = '';

            $cantidadEditable = ' <div class=\"input-group\"><input class=\"form-control invalid-emptyCount textTable border-0\" type=\"number\" value=\"' . $cantidad . '\" onchange=\"validEmptyInput(\'txtCantidad-' . $idEntradaTemp . '\', \'invalid-cantidad-' . $idEntradaTemp . '\', \'Se requiere la cantidad a recibir.\');\" id=\"txtCantidad-' . $idEntradaTemp . '\" required><div class=\"invalid-feedback\" id=\"invalid-cantidad-' . $idEntradaTemp . '\">Se requiere la cantidad a recibir.</div></div>';

            if ($IsLote == 1) {
                $InpLote = '<div class=\"input-group\"><input class=\"form-control invalid-empty textTable border-0\" type=\"text\" value=\"' . $lote . '\" onchange=\"validEmptyInput(\'txtLote-' . $idEntradaTemp . '\', \'invalid-lote-' . $idEntradaTemp . '\', \'Se requiere el lote del producto.\');\" id=\"txtLote-' . $idEntradaTemp . '\" required><div class=\"invalid-feedback\" id=\"invalid-lote-' . $idEntradaTemp . '\">Se requiere el lote del producto.</div></div>';
            } else {
                $InpLote = '<div class=\"input-group\"><input class=\"form-control textTable border-0\" type=\"text\" value=\"' . $lote . '\" id=\"txtLote-' . $idEntradaTemp . '\" disabled></div>';
            }

            if ($IsSerie == 1) {
                $InpSerie = '<div class=\"input-group\"><input class=\"form-control invalid-empty textTable border-0\" type=\"text\" value=\"' . $serie . '\" onchange=\"validEmptyInput(\'txtSerie-' . $idEntradaTemp . '\', \'invalid-serie-' . $idEntradaTemp . '\', \'Se requiere la serie del producto.\');\" id=\"txtSerie-' . $idEntradaTemp . '\" required><div class=\"invalid-feedback\" id=\"invalid-serie-' . $idEntradaTemp . '\">Se requiere la serie del producto.</div></div>';
            } else {
                $InpSerie = '<div class=\"input-group\"><input class=\"form-control textTable border-0\" type=\"text\" value=\"' . $serie . '\" id=\"txtSerie-' . $idEntradaTemp . '\" disabled></div>';
            }

            if ($IsFechaCaducidad == 1) {
                $InpCaducidad = '<div class=\"input-group\"><input class=\"form-control invalid-empty textTable border-0\" type=\"date\" value=\"' . $fechaCaducidad . '\" onchange=\"validEmptyInput(\'txtCaducidad-' . $idEntradaTemp . '\', \'invalid-caducidad-' . $idEntradaTemp . '\', \'Se requiere la caducidad del producto.\');\" id=\"txtCaducidad-' . $idEntradaTemp . '\" required min=\"' . $hoy . '\"><div class=\"invalid-feedback\" id=\"invalid-caducidad-' . $idEntradaTemp . '\">Se requiere la caducidad del producto.</div></div>';
            } else {
                $InpCaducidad = '<div class=\"input-group\"><input class=\"form-control textTable border-0\" type=\"date\" value=\"' . $fechaCaducidad . '\" id=\"txtCaducidad-' . $idEntradaTemp . '\" disabled></div>';
            }

            for ($i = 0; $i < count($isImpuestos); $i++) {
                if (isset($idImpuestoProd[$i])) {
                    $checkImpuestos = ' <input class=\"form-check-input\" type=\"checkbox\" data-imp=\"' . $idImpuestoProd[$i] . '\" id=\"cbxImpuestos-' . $idEntradaTemp . '-' . $idImpuestoProd[$i] . '\"  name=\"cbxImpuestos-' . $idEntradaTemp . '-' . $idImpuestoProd[$i] . '\" onclick=\"activarImpuestos(\'' . $idEntradaTemp . '-' . $idImpuestoProd[$i] . '\')\" checked> ';
                    $checkImpuestosIn = ' <input class=\"form-check-input\" type=\"checkbox\" data-imp=\"' . $idImpuestoProd[$i] . '\" id=\"cbxImpuestos-' . $idEntradaTemp . '-' . $idImpuestoProd[$i] . '\" name=\"cbxImpuestos-' . $idEntradaTemp . '-' . $idImpuestoProd[$i] . '\" onclick=\"activarImpuestos(\'' . $idEntradaTemp . '-' . $idImpuestoProd[$i] . '\')\"> ';
                    $etiquetaImpI = '<span class=\"textTable\" id=\"lblImpuesto-' . $idImpuestoProd[$i] . '\" name=\"lblImpuesto-' . $idImpuestoProd[$i] . '\"><br>';
                    $etiquetaImpIn = '<span class=\"textTableInactivo\" id=\"lblImpuesto-' . $idImpuestoProd[$i] . '\" name=\"lblImpuesto-' . $idImpuestoProd[$i] . '\"><br>';

                    if ($isImpuestos[$i] == '1') {
                        if (isset($impuestos[$i])) {
                            if ($impuestos[$i] != null) {
                                $impuestosIS .= $etiquetaImpI . $checkImpuestos . $impuestos[$i] . $etiquetaF;
                            }
                        }
                    } else {
                        if (isset($impuestos[$i])) {
                            if ($impuestos[$i] != null) {
                                $impuestosIS .= $etiquetaImpIn . $checkImpuestosIn . $impuestos[$i] . $etiquetaF;
                            }
                        }
                    }
                }
            }

            $table .= '{"Id":"' . $etiquetaI . $idDetalle . $etiquetaF . '",
                "Producto":"' . $etiquetaI . $producto . $etiquetaF . '",
                "CantidadRecibida":"' . $etiquetaI . $cantidadRecibida . $etiquetaF . '",
                "Cantidad":"' . $cantidadEditable . '",
                "UnidadMedida":"' . $etiquetaI . $unidadMedida . $etiquetaF . '",
                "Lote":"' . $etiquetaI . $InpLote . $etiquetaF . '",
                "Serie":"' . $etiquetaI . $InpSerie . $etiquetaF . '",
                "FechaCaducidad":"' . $etiquetaI . $InpCaducidad . $etiquetaF . '",
                "PrecioUnitario":"' . $etiquetaI . '$ ' . $precio . $etiquetaF . '",
                "Impuestos":"' . $impuestosIS . '",
                "Importe":"' . $etiquetaI . '$ ' . $importe . $etiquetaF . $acciones . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getProductosEntradaTransferTempTableEdit($folioEntrada)
    {
        $con = new conectar();
        $db = $con->getDb();

        $table = "";
        if (isset($_SESSION["IDEmpresa"])) {
            $PKEmpresa = $_SESSION["IDEmpresa"];
        } else {
            $PKEmpresa = '0';
        }

        if (isset($_SESSION["PKUsuario"])) {
            $PKuser = $_SESSION["PKUsuario"];
        } else {
            $PKuser = '0';
        }

        $query = sprintf('call spc_Tabla_EntradasTraspasoTemp_Edicion(?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($folioEntrada, $PKEmpresa, $PKuser));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $idEntradaTemp = $r['idEntradaTraspaso'];
            $nombre = $r['producto'];
            $clave = $r['clave'];
            $lote = $r['lote'];
            $serie = $r['serie'];
            $fechaCaducidad = $r['caducidad'];

            if ($clave == '') {
                $producto = $nombre;
            } else {
                $producto = $clave . ' - ' . $nombre;
            }

            $cantidadTotal = $r['cantidadTotal'];
            $cantidadARestante = $r['cantidadFaltante'];
            $cantidadARecibir = $r['cantidadRecibir'];

            if ($r['unidadMedida'] == '' || $r['unidadMedida'] == 'Sin Clave') {
                $unidadMedida = '(Sin unidad de medida)';
            } else {
                $unidadMedida = $r['unidadMedida'];
            }

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';

            $acciones = '<i><img class=\"btnEdit\" id=\"btnDelete-' . $idEntradaTemp . '\" width=\"20px\" height=\"20px\" src=\"../../../../img/chat/eliminar_equipo.svg\" alt=\"Eliminar lote/serie\" onclick=\"openModalDeleteProdTraspasoTemp(' . $idEntradaTemp . ')\"></i>';

            $cantidadEditable = ' <div class=\"input-group\"><input class=\"form-control invalid-emptyCount textTable border-0\" type=\"number\" value=\"' . $cantidadARecibir . '\" onchange=\"validEmptyInput2(\'txtCantidadTras-' . $idEntradaTemp . '\', \'invalid-cantidadTras-' . $idEntradaTemp . '\', \'Se requiere la cantidad a recibir.\');\" id=\"txtCantidadTras-' . $idEntradaTemp . '\" required><div class=\"invalid-feedback\" id=\"invalid-cantidadTras-' . $idEntradaTemp . '\">Se requiere la cantidad a recibir.</div></div>';


            $table .= '{"Id":"' . $etiquetaI . $idEntradaTemp . $etiquetaF . '",
                "Producto":"' . $etiquetaI . $producto . $etiquetaF . '",
                "Cantidad":"' . $cantidadEditable . '",
                "UnidadMedida":"' . $etiquetaI . $unidadMedida . $etiquetaF . '",
                "Lote":"' . $etiquetaI . $lote . $etiquetaF . '",
                "Serie":"' . $etiquetaI . $serie . $etiquetaF . '",
                "FechaCaducidad":"' . $etiquetaI . $fechaCaducidad . $etiquetaF . $acciones . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getProductosEntradaOCTable($folioEntrada)
    {
        $con = new conectar();
        $db = $con->getDb();

        $table = "";
        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Tabla_EntradasOC_Consulta(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($folioEntrada, $PKEmpresa));
        $array = $stmt->fetchAll();

        $etiquetaI = '<span class=\"textTable\">';
        $etiquetaF = '</span>';

        foreach ($array as $r) {

            $id = $r['id'];
            $nombre = $r['nombre'];
            $clave = $r['clave'];

            if ($clave == '') {
                $producto = $nombre;
            } else {
                $producto = $clave . ' - ' . $nombre;
            }

            $cantidadRecibida = $r['cantidad'];
            $precio = $r['precio'];
            $unidadMedida = $r['unidadMedida'];
            $InpLote = $r['lote'];
            $InpSerie = $r['noSerie'];
            $InpCaducidad = $r['caducidad'];
            $impuestosIS = $r['impuestos'];
            $importe = $r['importe'];

            $table .= '{"Id":"' . $etiquetaI . $id . $etiquetaF . '",
                "Producto":"' . $etiquetaI . $producto . $etiquetaF . '",
                "CantidadRecibida":"' . $etiquetaI . $cantidadRecibida . $etiquetaF . '",
                "UnidadMedida":"' . $etiquetaI . $unidadMedida . $etiquetaF . '",
                "Lote":"' . $etiquetaI . $InpLote . $etiquetaF . '",
                "Serie":"' . $etiquetaI . $InpSerie . $etiquetaF . '",
                "FechaCaducidad":"' . $etiquetaI . $InpCaducidad . $etiquetaF . '",
                "PrecioUnitario":"' . $etiquetaI . '$ ' . $precio . $etiquetaF . '",
                "Impuestos":"' . $impuestosIS . '",
                "Importe":"' . $etiquetaI . '$ ' . $importe . $etiquetaF . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getProductosEntradaTranferTable($folioEntrada)
    {
        $con = new conectar();
        $db = $con->getDb();

        $table = "";
        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Tabla_EntradasTransfer_Consulta(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($folioEntrada, $PKEmpresa));
        $array = $stmt->fetchAll();

        $etiquetaI = '<span class=\"textTable\">';
        $etiquetaF = '</span>';

        foreach ($array as $r) {
            $idEntrada = $r['id'];
            $nombre = $r['producto'];
            $clave = $r['clave'];
            $lote = $r['lote'];
            $serie = $r['serie'];
            $fechaCaducidad = $r['caducidad'];

            if ($clave == '') {
                $producto = $nombre;
            } else {
                $producto = $clave . ' - ' . $nombre;
            }

            $cantidadRecibida = $r['cantidad'];

            if ($r['unidadMedida'] == '' || $r['unidadMedida'] == 'Sin Clave') {
                $unidadMedida = '(Sin unidad de medida)';
            } else {
                $unidadMedida = $r['unidadMedida'];
            }

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';

            $table .= '{"Id":"' . $etiquetaI . $idEntrada . $etiquetaF . '",
                "Producto":"' . $etiquetaI . $producto . $etiquetaF . '",
                "CantidadRecibida":"' . $etiquetaI . $cantidadRecibida . $etiquetaF . '",
                "UnidadMedida":"' . $etiquetaI . $unidadMedida . $etiquetaF . '",
                "Lote":"' . $etiquetaI . $lote . $etiquetaF . '",
                "Serie":"' . $etiquetaI . $serie . $etiquetaF . '",
                "FechaCaducidad":"' . $etiquetaI . $fechaCaducidad . $etiquetaF . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function editSalidaCantidadTemp($folioSerie, $cantidad, $id, $idProducto)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_Salida_OP_Cantidad_Modal_Temp(?,?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($folioSerie, $cantidad, $id, $idProducto, $PKuser));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function getProductoSalidaOPTempTable($pkOrdenPedido)
    {
        $con = new conectar();
        $db = $con->getDb();

        $table = "";
        $PKuser = $_SESSION["PKUsuario"];

        $query = sprintf('call spc_Tabla_Salidas_OP_Temp(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKuser, $pkOrdenPedido));
        $array = $stmt->fetchAll();

        $rSumCantidad = 0;
        $countCant = 0;
        $pkProductoRep = 0;
        $countProdRep = 0;
        $countProdRepSinLoteSerie = 0;
        $acciones = '';
        $filas =  0;

        foreach ($array as $r) {
            $id = $r['pKOrdenPedido'];
            $idProducto = $r['pkProducto'];
            $nombre = $r['producto'];
            $clave = $r['clave'];
            $codigoBarras = '<div id=\"lbl_bc\" data-id-lbl=\"' . $idProducto . '\" name=\"lblCodigoBarras\" style=\"display:none;\">' . $r['cb'] . '</div>';

            if ($r['clave'] == '') {
                $producto = $r['producto'];
            } else {
                $producto = $r['clave'] . ' - ' . $r['producto'];
            }

            $descripcion = $r['descripcion'];
            $cantidadPedida = $r['cantidadPedida'];
            $cantidadSurtida = $r['cantidadSurtida'];
            $cantidadFaltante = $r['cantidadFaltante'];
            $cantidadExistencia = $r['existencia'];

            $lote = $r['lote'];
            $serie = $r['serie'];
            $caducidad = $r['caducidad'];
            $unidadMedida = $r['um'];

            $identificador = '';

            if ($lote == '') {
                $identificador = $serie;
            } else {
                $identificador = $lote;
            }

            $buscarLoteSerie = '<i class=\"fas fa-search-plus color-primary-dark pointer\" onclick=\"configurarProducto(' . $idProducto . ',\'' . $producto . '\',\'' . $cantidadPedida . '\',\'' . $descripcion . '\',\'' . $cantidadFaltante . '\')\" data-toggle=\"tooltip\" data-placement=\"right\" title=\"Añadir lote/serie\"></i>';

            // --> Si el registro posee un sólo registro en existencias para la sucursal<--- //
            if ($r['repeticiones'] == '1') {
                // --> Si la cantidad en existencia es 0 <--- //
                if ($cantidadExistencia == '0') {
                    $cantidadSalida = '<div class=\"input-group\"><input class=\"form-control textTable border-0\" type=\"text\" value=\"0\" id=\"cantidad-' . $identificador . $idProducto . '\" disabled></div>';
                } else { // --> Si la cantidad en existencia es mayor a 0 <--- //

                    // --> Si la canidad pedida es mayor a la de existencia y la cantidad es diferente de N/A<--- //
                    if ($cantidadFaltante > $cantidadExistencia && $cantidadExistencia != 'N/A') {
                        $cantidadSalida = '<div class=\"input-group\"><input class=\"form-control textTable border-0 input-table-salida\" type=\"text\" value=\"' . $cantidadExistencia . '\" onchange=\"validarCantidad(\'' . $identificador . '\', \'' . $idProducto . '\');\" onkeyup=\"disableButtonsAdd()\" id=\"cantidad-' . $identificador . $idProducto . '\" data-id=\"' . $idProducto . '\" name=\"inptCantidad\"> <div class=\"invalid-feedback\" id=\"invalid-cantidad-' . $identificador . $idProducto . '\">La cantidad es inválida.</div></div>';
                        $this->editSalidaCantidadTemp($identificador, $cantidadExistencia, $id, $idProducto);
                    } else { // --> Si la canidad pedida es menor a la de existencia <--- //
                        $cantidadSalida = '<div class=\"input-group\"><input class=\"form-control textTable border-0 input-table-salida\" type=\"text\" value=\"' . $cantidadFaltante . '\" onchange=\"validarCantidad(\'' . $identificador . '\', \'' . $idProducto . '\');\" onkeyup=\"disableButtonsAdd()\" id=\"cantidad-' . $identificador . $idProducto . '\" data-id=\"' . $idProducto . '\" name=\"inptCantidad\"> <div class=\"invalid-feedback\" id=\"invalid-cantidad-' . $identificador . $idProducto . '\">La cantidad es inválida.</div></div>';
                        $this->editSalidaCantidadTemp($identificador, $cantidadFaltante, $id, $idProducto);
                    }
                }
            } else { // --> Si el registro posee más de un registro en existencias para la sucursal<--- //
                if ($cantidadExistencia == 'N/A') {
                    $cantidadExistencia = $cantidadPedida;
                }
                // --> Si el producto anterior es igual al actual <--- //
                if ($pkProductoRep == $idProducto) {

                    // --> Sumar la cantidad de las repeteciones del producto <--- //
                    $rSumCantidad = $rSumCantidad + $cantidadExistencia;

                    // --> Cantidad pedida menor a cantidad  <---//
                    if ($cantidadFaltante <= $rSumCantidad) {

                        $diferencia = $cantidadFaltante - ($rSumCantidad - $cantidadExistencia);

                        if ($countCant == 0) {
                            if ($countProdRep == 0) {
                                $cantidadSalida = '<div class=\"input-group\"><input class=\"form-control textTable border-0 input-table-salida\" type=\"text\" value=\"' . $diferencia . '\" onchange=\"validarCantidad(\'' . $identificador . '\', \'' . $idProducto . '\');\" onkeyup=\"disableButtonsAdd()\" id=\"cantidad-' . $identificador . $idProducto . '\" data-id=\"' . $idProducto . '\" name=\"inptCantidad\"> <div class=\"invalid-feedback\" id=\"invalid-cantidad-' . $identificador . $idProducto . '\">La cantidad es inválida.</div></div>';
                                $this->editSalidaCantidadTemp($identificador, $diferencia, $id, $idProducto);
                                $countProdRep = 1;
                            } else {
                                $cantidadSalida = '';
                            }
                        } else {
                            $cantidadSalida = '';
                        }

                        $countCant = $countCant + 1;
                    } else {
                        if ($cantidadFaltante > $rSumCantidad) {
                            if ($countProdRep == 0) {
                                $cantidadSalida = '<div class=\"input-group\"><input class=\"form-control textTable border-0 input-table-salida\" type=\"text\" value=\"' . $cantidadExistencia . '\" onchange=\"validarCantidad(\'' . $identificador . '\', \'' . $idProducto . '\');\" onkeyup=\"disableButtonsAdd()\" id=\"cantidad-' . $identificador . $idProducto . '\" data-id=\"' . $idProducto . '\" name=\"inptCantidad\"> <div class=\"invalid-feedback\" id=\"invalid-cantidad-' . $identificador . $idProducto . '\">La cantidad es inválida.</div></div>';
                                $this->editSalidaCantidadTemp($identificador, $cantidadExistencia, $id, $idProducto);
                                $countProdRep = 1;
                            } else {
                                $cantidadSalida = '';
                            }
                        } else {
                            $cantidadSalida = '';
                        }
                    }
                } else { // --> Si el producto anterior es distinto al actual <--- //

                    // --> Resetear valores de sumatoria <--- //
                    $rSumCantidad = 0;
                    $countCant = 0;
                    $countProdRep = 0;

                    // --> Sumar la cantidad de las repeteciones del producto <--- //
                    $rSumCantidad = $rSumCantidad + $cantidadExistencia;

                    if ($cantidadFaltante <= $rSumCantidad) {

                        $diferencia = $cantidadFaltante - ($rSumCantidad - $cantidadExistencia);

                        if ($countCant == 0) {
                            if ($countProdRep == 0) {
                                $cantidadSalida = '<div class=\"input-group\"><input class=\"form-control textTable border-0 input-table-salida\" type=\"text\" value=\"' . $diferencia . '\" onchange=\"validarCantidad(\'' . $identificador . '\', \'' . $idProducto . '\');\" onkeyup=\"disableButtonsAdd()\" id=\"cantidad-' . $identificador . $idProducto . '\" data-id=\"' . $idProducto . '\" name=\"inptCantidad\"> <div class=\"invalid-feedback\" id=\"invalid-cantidad-' . $identificador . $idProducto . '\">La cantidad es inválida.</div></div>';
                                $this->editSalidaCantidadTemp($identificador, $diferencia, $id, $idProducto);
                                $countProdRep = 1;
                            } else {
                                $cantidadSalida = '';
                            }
                        } else {
                            $cantidadSalida = '';
                        }

                        $countCant = $countCant + 1;
                    } else {
                        if ($cantidadFaltante > $rSumCantidad) {
                            if ($countProdRep == 0) {
                                $cantidadSalida = '<div class=\"input-group\"><input class=\"form-control textTable border-0 input-table-salida\" type=\"text\" value=\"' . $cantidadExistencia . '\" onchange=\"validarCantidad(\'' . $identificador . '\', \'' . $idProducto . '\');\" onkeyup=\"disableButtonsAdd()\" id=\"cantidad-' . $identificador . $idProducto . '\" data-id=\"' . $idProducto . '\" name=\"inptCantidad\"> <div class=\"invalid-feedback\" id=\"invalid-cantidad-' . $identificador . $idProducto . '\">La cantidad es inválida.</div></div>';
                                $this->editSalidaCantidadTemp($identificador, $cantidadExistencia, $id, $idProducto);
                                $countProdRep = 1;
                            } else {
                                $cantidadSalida = '';
                            }
                        } else {
                            $cantidadSalida = '';
                        }
                    }
                }

                $pkProductoRep = $r['pkProducto'];

                if ($cantidadSalida != '') {
                    $cantidadSalida .= $buscarLoteSerie;
                }
            }

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';
            $etiquetaInputI = '<span class=\"inputTable\">';
            $etiquetaInputF = '</span>';

            $cantPedida = '<input class=\"textTable\" value=\"' . $cantidadPedida . '\" disabled style=\"background: transparent; border:none\" id=\"cantMaxPedida-' . $identificador . $idProducto . '\">';

            $existenciasTotales = $r['existencia'];

            if ($cantidadSalida != '') {
                /* $filas++;
                if($filas == 1) {
                    $acciones = '';
                } else {
                    $acciones = '<i class=\"fas fa-minus-circle color-primary-dark pointer\" id=\"btnDelete-' . $identificador . '\" onclick=\"openModalDelete(\'' . $identificador . '\', \''.$idProducto.'\')\" data-toggle=\"tooltip\" data-placement=\"right\" title=\"Borrar producto\"></i>';
                } */
                //"Serie":"' . $etiquetaI . $serie . $etiquetaF . '",
                $acciones = '<i class=\"fas fa-minus-circle color-primary-dark pointer\" id=\"btnDelete-' . $identificador . '\" onclick=\"openModalDelete(\'' . $identificador . '\', \'' . $idProducto . '\')\" data-toggle=\"tooltip\" data-placement=\"right\" title=\"Borrar producto\"></i>';
                $table .= '{"Id":"' . $etiquetaI . $identificador . $etiquetaF . '",
                    "Clave":"' . $etiquetaI . $clave . $etiquetaF . '",
                    "Producto":"' . $etiquetaI . $nombre . $etiquetaF . '",
                    "CantidadPedida":"' . $etiquetaI . $cantPedida . $etiquetaF . '",
                    "CantidadSurtida":"' . $etiquetaI . $cantidadSurtida . $etiquetaF . '",
                    "CantidadRestante":"' . $etiquetaI . $cantidadFaltante . $etiquetaF . '",
                    "Existencias":"' . $etiquetaI . $cantidadExistencia . $etiquetaF . '",
                    "CantidadSalida":"' . $etiquetaInputI . $cantidadSalida . $etiquetaInputF . '",
                    "Lote":"' . $etiquetaI . $lote . $etiquetaF . '",
                    
                    "um":"' . $etiquetaI . $unidadMedida . $etiquetaF . '",
                    "cb":"' . $etiquetaI . $codigoBarras . $etiquetaF . '",
                    "Caducidad":"' . $etiquetaI . $caducidad . $etiquetaF . '",
                    "Acciones":"' . $etiquetaI . $acciones . $etiquetaF . '"},';
            }
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getProductoSalidaOPTempTableEditModal($pkOrdenPedido)
    {
        $con = new conectar();
        $db = $con->getDb();

        $table = "";
        $PKuser = $_SESSION["PKUsuario"];

        $query = sprintf('call spc_Tabla_Salidas_OP_Temp_EditModal(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKuser, $pkOrdenPedido));
        $array = $stmt->fetchAll();

        $rSumCantidad = 0;
        $countCant = 0;
        $pkProductoRep = 0;
        $countProdRep = 0;
        $countProdRepSinLoteSerie = 0;
        $acciones = '';
        $filas = 0;

        foreach ($array as $r) {
            $id = $r['pKOrdenPedido'];
            $idProducto = $r['pkProducto'];
            $nombre = $r['producto'];
            $clave = $r['clave'];
            $codigoBarras = '<div id=\"lbl_bc\" data-id-lbl=\"' . $idProducto . '\" name=\"lblCodigoBarras\" style=\"display:none;\">' . $r['cb'] . '</div>';

            if ($r['clave'] == '') {
                $producto = $r['producto'];
            } else {
                $producto = $r['clave'] . ' - ' . $r['producto'];
            }

            $descripcion = str_replace(["\r", "\n"], "", $r['descripcion']);
            $cantidadPedida = str_replace(["\r", "\n"], "", $r['cantidadPedida']);
            $cantidadSurtida = $r['cantidadSurtida'];
            $cantidadFaltante = $r['cantidadFaltante'];
            $cantidadExistencia = $r['existencia'];

            $lote = $r['lote'];
            $serie = $r['serie'];
            $caducidad = $r['caducidad'];
            $unidadMedida = $r['um'];

            $salida = str_replace(["\r", "\n"], "", $r['salida']);

            $identificador = '';

            if ($lote == '') {
                $identificador = $serie;
            } else {
                $identificador = $lote;
            }

            $repeCant = $r['repeticionesCant'];

            $buscarLoteSerie = '<i class=\"fas fa-search-plus color-primary-dark pointer\" onclick=\"configurarProducto(' . $idProducto . ',\'' . $producto . '\',\'' . $cantidadPedida . '\',\'' . $descripcion . '\',\'' . $cantidadFaltante . '\')\" data-toggle=\"tooltip\" data-placement=\"right\" title=\"Añadir lote/serie\"></i>';

            // --> Si el registro posee un sólo registro en existencias para la sucursal<--- //
            if ($r['repeticiones'] == '1' || $r['repeticionesCant'] == '0') {
                // --> Si la cantidad en existencia es 0 <--- //
                if ($cantidadExistencia == '0') {
                    $cantidadSalida = '<div class=\"input-group\"><input class=\"form-control textTable border-0\" type=\"text\" value=\"0\" id=\"cantidad-' . $identificador . $idProducto . '\" disabled></div>';
                } else { // --> Si la cantidad en existencia es mayor a 0 <--- //
                    // --> Si la canidad pedida es mayor a la de existencia <--- //
                    $cantidadSalida = '<div class=\"input-group\"><input class=\"form-control textTable border-0\" type=\"text\" value=\"' . $salida . '\" onchange=\"validarCantidad(\'' . $identificador . '\', \'' . $idProducto . '\');\" onkeyup=\"disableButtonsAdd()\" id=\"cantidad-' . $identificador . $idProducto . '\" data-id=\"' . $idProducto . '\" name=\"inptCantidad\"> <div class=\"invalid-feedback\" id=\"invalid-cantidad-' . $identificador . $idProducto . '\">La cantidad es invalida.</div></div>';
                }

                $acciones = '';
            } else {
                $cantidadSalida = '<div class=\"input-group\"><input class=\"form-control textTable border-0\" type=\"text\" value=\"' . $salida . '\" onchange=\"validarCantidad(\'' . $identificador . '\', \'' . $idProducto . '\');\" onkeyup=\"disableButtonsAdd()\" id=\"cantidad-' . $identificador . $idProducto . '\" data-id=\"' . $idProducto . '\" name=\"inptCantidad\"> <div class=\"invalid-feedback\" id=\"invalid-cantidad-' . $identificador . $idProducto . '\">La cantidad es invalida.</div></div>';

                if ($repeCant > 1) {
                    $acciones = '<i class=\"fas fa-minus-circle color-primary-dark pointer\" id=\"btnDelete-' . $identificador . '\" onclick=\"openModalDelete(\'' . $identificador . '\', \'' . $idProducto . '\')\" data-toggle=\"tooltip\" data-placement=\"right\" title=\"Borrar producto\"></i>';
                } else {
                    $acciones = '';
                }

                if ($cantidadSalida != '') {
                    $cantidadSalida .= $buscarLoteSerie;
                }
            }

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';
            $etiquetaInputI = '<span class=\"inputTable\">';
            $etiquetaInputF = '</span>';

            $cantPedida = '<input class=\"textTable\" value=\"' . $cantidadPedida . '\" disabled style=\"background: transparent; border:none\" id=\"cantMaxPedida-' . $identificador . $idProducto . '\">';

            if ($cantidadSalida != '') {
                /* $filas++;
                if($filas == 1) {
                    $acciones = '';
                } else {
                    $acciones = '<i class=\"fas fa-minus-circle color-primary-dark pointer\" id=\"btnDelete-' . $identificador . '\" onclick=\"openModalDelete(\'' . $identificador . '\', \''.$idProducto.'\')\" data-toggle=\"tooltip\" data-placement=\"right\" title=\"Borrar producto\"></i>';
                } */
                $acciones = '<i class=\"fas fa-minus-circle color-primary-dark pointer\" id=\"btnDelete-' . $identificador . '\" onclick=\"openModalDelete(\'' . $identificador . '\', \'' . $idProducto . '\')\" data-toggle=\"tooltip\" data-placement=\"right\" title=\"Borrar producto\"></i>';
                $table .= '{"Id":"' . $etiquetaI . $identificador . $etiquetaF . '",
                    "Clave":"' . $etiquetaI . $clave . $etiquetaF . '",
                    "Producto":"' . $etiquetaI . $nombre . $etiquetaF . '",
                    "CantidadPedida":"' . $etiquetaI . $cantPedida . $etiquetaF . '",
                    "CantidadSurtida":"' . $etiquetaI . $cantidadSurtida . $etiquetaF . '",
                    "CantidadRestante":"' . $etiquetaI . $cantidadFaltante . $etiquetaF . '",
                    "Existencias":"' . $etiquetaI . $cantidadExistencia . $etiquetaF . '",
                    "CantidadSalida":"' . $etiquetaInputI . $cantidadSalida . $etiquetaInputF . '",
                    "Lote":"' . $etiquetaI . $lote . $etiquetaF . '",
                    "Serie":"' . $etiquetaI . $serie . $etiquetaF . '",
                    "um":"' . $etiquetaI . $unidadMedida . $etiquetaF . '",
                    "cb":"' . $etiquetaI . $codigoBarras . $etiquetaF . '",
                    "Caducidad":"' . $etiquetaI . $caducidad . $etiquetaF . '",
                    "Acciones":"' . $etiquetaI . $acciones . $etiquetaF . '"},';
            }
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getProductoSalidaOPTempTableEdicion($folioSalida)
    {
        $con = new conectar();
        $db = $con->getDb();

        $table = "";
        $PKuser = $_SESSION["PKUsuario"];

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Tabla_Salidas_OP_TempEdicion(?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKuser, $PKEmpresa, $folioSalida));
        $array = $stmt->fetchAll();

        $rSumCantidad = 0;
        $countCant = 0;
        $pkProductoRep = 0;
        $countProdRep = 0;
        $countProdRepSinLoteSerie = 0;
        $acciones = '';
        $filas = 0;

        foreach ($array as $r) {
            $id = $r['pKOrdenPedido'];
            $idProducto = $r['pkProducto'];
            $nombre = $r['producto'];
            $clave = $r['clave'];
            $codigoBarras = $r['cb'];
            $unidadMedida = $r['um'];

            if ($r['clave'] == '') {
                $producto = $r['producto'];
            } else {
                $producto = $r['clave'] . ' - ' . $r['producto'];
            }

            $descripcion = str_replace(["\r", "\n"], "", $r['descripcion']);
            $cantidadPedida = $r['cantidadPedida'];
            $cantidadSurtida = $r['cantidadSurtida'];
            $cantidadFaltante = $r['cantidadFaltante'];
            $cantidadExistencia = $r['existencia'];

            $lote = $r['lote'];
            $serie = $r['serie'];
            $caducidad = $r['caducidad'];

            $salida = str_replace(["\r", "\n"], "", $r['salida']);

            $identificador = '';

            if ($lote == '') {
                $identificador = $serie;
            } else {
                $identificador = $lote;
            }

            $repeCant = $r['repeticionesCant'];

            $buscarLoteSerie = '<i class=\"fas fa-search-plus color-primary-dark pointer\" onclick=\"configurarProducto(' . $idProducto . ',\'' . $producto . '\',\'' . $cantidadPedida . '\',\'' . $descripcion . '\',\'' . $cantidadFaltante . '\')\" data-toggle=\"tooltip\" data-placement=\"right\" title=\"Añadir lote/serie\"></i>';

            // --> Si el registro posee un sólo registro en existencias para la sucursal<--- //
            if ($r['repeticiones'] == '1' || $r['repeticionesCant'] == '0') {
                // --> Si la cantidad en existencia es 0 <--- //
                if ($cantidadExistencia == '0') {
                    $cantidadSalida = '<div class=\"input-group\"><input class=\"form-control textTable border-0\" type=\"text\" value=\"0\" id=\"cantidad-' . $identificador . '\" disabled></div>';
                } else { // --> Si la cantidad en existencia es mayor a 0 <--- //
                    // --> Si la canidad pedida es mayor a la de existencia <--- //
                    $cantidadSalida = '<div class=\"input-group\"><input class=\"form-control textTable border-0\" type=\"text\" value=\"' . $salida . '\" onchange=\"validarCantidad(\'' . $identificador . '\', \'' . $idProducto . '\');\" onkeyup=\"disableButtonsAdd()\" id=\"cantidad-' . $identificador . $idProducto . '\"> <div class=\"invalid-feedback\" id=\"invalid-cantidad-' . $identificador . $idProducto . '\">La cantidad es invalida.</div></div>';
                }

                $acciones = '<i class=\"fas fa-minus-circle color-primary-dark pointer\" id=\"btnDelete-' . $identificador . '\" onclick=\"openModalDelete(\'' . $identificador . '\', \'' . $idProducto . '\')\" data-toggle=\"tooltip\" data-placement=\"right\" title=\"Borrar producto\"></i>';
            } else {
                $cantidadSalida = '<div class=\"input-group\"><input class=\"form-control textTable border-0\" type=\"text\" value=\"' . $salida . '\" onchange=\"validarCantidad(\'' . $identificador . '\', \'' . $idProducto . '\');\" onkeyup=\"disableButtonsAdd()\" id=\"cantidad-' . $identificador . $idProducto . '\" required> <div class=\"invalid-feedback\" id=\"invalid-cantidad-' . $identificador . $idProducto . '\">La cantidad es invalida.</div></div>';

                if ($repeCant > 1) {
                    $acciones = '<i class=\"fas fa-minus-circle color-primary-dark pointer\" id=\"btnDelete-' . $identificador . '\" onclick=\"openModalDelete(\'' . $identificador . '\', \'' . $idProducto . '\')\" data-toggle=\"tooltip\" data-placement=\"right\" title=\"Borrar producto\"></i>';
                } else {
                    $acciones = '<i class=\"fas fa-minus-circle color-primary-dark pointer\" id=\"btnDelete-' . $identificador . '\" onclick=\"openModalDelete(\'' . $identificador . '\', \'' . $idProducto . '\')\" data-toggle=\"tooltip\" data-placement=\"right\" title=\"Borrar producto\"></i>';
                }

                if ($cantidadSalida != '') {
                    $cantidadSalida .= $buscarLoteSerie;
                }
            }

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';
            $etiquetaInputI = '<span class=\"inputTable\">';
            $etiquetaInputF = '</span>';

            $cantPedida = '<input class=\"textTable\" value=\"' . $cantidadPedida . '\" disabled style=\"background: transparent; border:none\" id=\"cantMaxPedida-' . $identificador . $idProducto . '\">';

            if ($cantidadSalida != '') {
                /* $filas++;
                if($filas == 1) {
                    $acciones = '';
                } else {
                    $acciones = '<i class=\"fas fa-minus-circle color-primary-dark pointer\" id=\"btnDelete-' . $identificador . '\" onclick=\"openModalDelete(\'' . $identificador . '\', \''.$idProducto.'\')\" data-toggle=\"tooltip\" data-placement=\"right\" title=\"Borrar producto\"></i>';
                } */

                //"Serie":"' . $etiquetaI . $serie . $etiquetaF . '",
                $acciones = '<i class=\"fas fa-minus-circle color-primary-dark pointer\" id=\"btnDelete-' . $identificador . '\" onclick=\"openModalDelete(\'' . $identificador . '\', \'' . $idProducto . '\')\" data-toggle=\"tooltip\" data-placement=\"right\" title=\"Borrar producto\"></i>';
                $table .= '{"Id":"' . $etiquetaI . $identificador . $etiquetaF . '",
                    "Producto":"' . $etiquetaI . $producto . $etiquetaF . '",
                    "Descripcion":"' . $etiquetaI . $descripcion . $etiquetaF . '",
                    "CantidadPedida":"' . $etiquetaI . $cantPedida . $etiquetaF . '",
                    "CantidadSurtida":"' . $etiquetaI . $cantidadSurtida . $etiquetaF . '",
                    "CantidadRestante":"' . $etiquetaI . $cantidadFaltante . $etiquetaF . '",
                    "Existencias":"' . $etiquetaI . $cantidadExistencia . $etiquetaF . '",
                    "CantidadSalida":"' . $etiquetaInputI . $cantidadSalida . $etiquetaInputF . '",
                    "Lote":"' . $etiquetaI . $lote . $etiquetaF . '",
                    
                    "um":"' . $etiquetaI . $unidadMedida . $etiquetaF . '",
                    "cb":"' . $etiquetaI . $codigoBarras . $etiquetaF . '",
                    "Acciones":"' . $etiquetaI . $acciones . $etiquetaF . '",
                    "Caducidad":"' . $etiquetaI . $caducidad . $etiquetaF . '"},';
            }
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getProductoSalidaDevolucionTempTableEdicion($folioSalida)
    {
        $con = new conectar();
        $db = $con->getDb();

        $table = "";
        $PKuser = $_SESSION["PKUsuario"];
        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Tabla_Salidas_Devolucion_TempEdicion(?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKuser, $PKEmpresa, $folioSalida));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id = $r['id'];
            $idProducto = $r['producto_id'];
            $idCuentaPorPagar = $r['cuenta_id'];
            $nombre = $r['nombre'];
            $clave = $r['clave'];

            if ($clave == '') {
                $producto = $nombre;
            } else {
                $producto = $clave . ' - ' . $nombre;
            }

            $descripcion = $r['descripcion'];
            $cantidadEntrada = $r['cantidadEntrada'];
            $cantidadExistencia = $r['existencia'];

            $lote = $r['lote'];
            //$serie = $r['serie'];
            $caducidad = $r['caducidad'];

            $isDevolucion = $r['isDevolution'];

            $cantidad = $r['cantidadSalida'];

            if ($isDevolucion == '1') {
                $cantidadSalida = '<div class=\"input-group\"><input class=\"form-control textTable border-0\" type=\"number\" value=\"' . $cantidad . '\" onchange=\"validarCantidadDevolucion(\'' . $id . '\', \'' . $idProducto . '\', \'' . $idCuentaPorPagar . '\');\" id=\"cantidad-' . $id . '\" style=\"background:#efefa8\" title=\"Ya se registró una devolución para este producto\"> <div class=\"invalid-feedback\" id=\"invalid-cantidad-' . $id . '\">La cantidad es inválida.</div></div>';
            } else {
                $cantidadSalida = '<div class=\"input-group\"><input class=\"form-control textTable border-0\" type=\"number\" value=\"' . $cantidad . '\" onchange=\"validarCantidadDevolucion(\'' . $id . '\', \'' . $idProducto . '\', \'' . $idCuentaPorPagar . '\');\" id=\"cantidad-' . $id . '\"> <div class=\"invalid-feedback\" id=\"invalid-cantidad-' . $id . '\">La cantidad es inválida.</div></div>';
            }

            $cantidadDevuelta = $r['cantidadDevuelta'];

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';

            //"Serie":"' . $etiquetaI . $serie . $etiquetaF . '",
            $table .= '{"Id":"' . $etiquetaI . $id . $etiquetaF . '",
                "Producto":"' . $etiquetaI . $producto . $etiquetaF . '",
                "Descripcion":"' . $etiquetaI . $descripcion . $etiquetaF . '",
                "CantidadEntrada":"' . $etiquetaI . $cantidadEntrada . $etiquetaF . '",
                "CantidadDevuelta":"' . $etiquetaI . $cantidadDevuelta . $etiquetaF . '",
                "Existencias":"' . $etiquetaI . $cantidadExistencia . $etiquetaF . '",
                "CantidadSalida":"' . $etiquetaI . $cantidadSalida . $etiquetaF . '",
                "Lote":"' . $etiquetaI . $lote . $etiquetaF . '",
                
                "Acciones":"' . $etiquetaI . '' . $etiquetaF . '",
                "Caducidad":"' . $etiquetaI . $caducidad . $etiquetaF . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getProductoSalidaDevolucionTempTable($id_cuenta_pagar)
    {
        $con = new conectar();
        $db = $con->getDb();

        $table = "";
        $PKuser = $_SESSION["PKUsuario"];
        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Tabla_Salidas_Devolucion(?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKuser, $PKEmpresa, $id_cuenta_pagar));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id = $r['id'];
            $idProducto = $r['producto_id'];
            $idCuentaPorPagar = $r['cuenta_id'];
            $nombre = $r['nombre'];
            $clave = $r['clave'];

            if ($clave == '') {
                $producto = $nombre;
            } else {
                $producto = $clave . ' - ' . $nombre;
            }

            $descripcion = $r['descripcion'];
            $cantidadEntrada = $r['cantidadEntrada'];
            $cantidadExistencia = $r['existencia'];

            $cantidad = $r['cantidadSalida'];

            $cantidadSalida = '<div class=\"input-group\"><input class=\"form-control textTable border-0\" type=\"text\" value=\"' . $cantidad . '\" onchange=\"validarCantidadDevolucion(\'' . $id . '\', \'' . $idProducto . '\', \'' . $idCuentaPorPagar . '\');\" id=\"cantidad-' . $id . '\"> <div class=\"invalid-feedback\" id=\"invalid-cantidad-' . $id . '\">La cantidad es inválida.</div></div>';

            $lote = $r['lote'];
            $serie = $r['serie'];
            $caducidad = $r['caducidad'];

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';

            //"Serie":"' . $etiquetaI . $serie . $etiquetaF . '",
            $table .= '{"Id":"' . $etiquetaI . $id . $etiquetaF . '",
                "Clave":"' . $etiquetaI . $clave . $etiquetaF . '",
                "Producto":"' . $etiquetaI . $nombre . $etiquetaF . '",
                "Descripcion":"' . $etiquetaI . $descripcion . $etiquetaF . '",
                "CantidadEntrada":"' . $etiquetaI . $cantidadEntrada . $etiquetaF . '",
                "Existencias":"' . $etiquetaI . $cantidadExistencia . $etiquetaF . '",
                "CantidadSalida":"' . $etiquetaI . $cantidadSalida . $etiquetaF . '",
                "Lote":"' . $etiquetaI . $lote . $etiquetaF . '",
                
                "Acciones":"' . $etiquetaI . '' . $etiquetaF . '",
                "Caducidad":"' . $etiquetaI . $caducidad . $etiquetaF . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getProductoSalidaOPTableEdit($folioSalida)
    {
        $con = new conectar();
        $db = $con->getDb();

        $table = "";
        $PKuser = $_SESSION["PKUsuario"];
        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Tabla_Salidas_OP_Edit(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa, $folioSalida));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id = $r['id'];
            $idProducto = $r['idProducto'];
            $nombre = $r['nombre'];
            $clave = $r['clave'];
            $codigoBarras = $r['cb'];
            $unidadMedida = $r['um'];

            if ($clave == '') {
                $producto = $nombre;
            } else {
                $producto = $clave . ' - ' . $nombre;
            }

            $descripcion = str_replace(["\r", "\n"], "", $r['descripcion']);
            $cantidadPedida = $r['cantidad'];
            $cantidadSurtida = $r['cantidadSurtida'];
            $cantidadFaltante = $r['cantidadFaltante'];
            $cantidadExistencia = $r['existencia'];

            $lote = $r['lote'];
            $serie = $r['serie'];
            $caducidad = $r['fechaCaducidad'];

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';

            $table .= '{"Id":"' . $etiquetaI . $id . $etiquetaF . '",
                "Producto":"' . $etiquetaI . $producto . $etiquetaF . '",
                "Descripcion":"' . $etiquetaI . $descripcion . $etiquetaF . '",
                "CantidadPedida":"' . $etiquetaI . $cantidadPedida . $etiquetaF . '",
                "CantidadSurtida":"' . $etiquetaI . $cantidadSurtida . $etiquetaF . '",
                "CantidadRestante":"' . $etiquetaI . $cantidadFaltante . $etiquetaF . '",
                "Existencias":"' . $etiquetaI . $cantidadExistencia . $etiquetaF . '",
                "Lote":"' . $etiquetaI . $lote . $etiquetaF . '",
                "Serie":"' . $etiquetaI . $serie . $etiquetaF . '",
                "um":"' . $etiquetaI . $unidadMedida . $etiquetaF . '",
                "cb":"' . $etiquetaI . $codigoBarras . $etiquetaF . '",
                "Caducidad":"' . $etiquetaI . $caducidad . $etiquetaF . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getInventory($sucu, $cate, $exist)
    {
        $con = new conectar();
        $db = $con->getDb();
        $idEmpresa = $_SESSION['IDEmpresa'];
        $conditions = "";
        if ($sucu != "todas") {
            $conditions .= 'AND suc.id = :sucursal ';
        }

        if ($cate != "todas") {
            $conditions .= 'AND p.FKCategoriaProducto = :categoria ';
        }

        if ($exist == 'noExistencia') {
            $conditions .= 'AND expp.existencia = 0';
        } elseif ($exist == 'existencia') {
            $conditions .= 'AND expp.existencia > 0';
        } else {
            $conditions .= 'AND expp.existencia >= 0';
        }
        //serie, 3770
        //expp.numero_serie AS serie, 3799
        //AND expp.numero_serie = isps.numero_serie 3864
        //AND isps.numero_serie = ieps.numero_serie 3873
        $query = "SELECT
        existencia,
        existenciaMinima,
        existenciaMaxima,
        lote,
        serie,
        idExistencia,
        caducidad,
        clave,
        unidadMedida,
        descripcion,
        nombre,
        sucursal,
        sucursal_id,
        sucursalOri,
        sucursalDest,
        tipo,
        folio_salida,
        cantidadSalida,
        cantidadEntrada,
        categoria,
        IF (
          tipo = 'Pedido (Traspaso)',(cantidadSalida - cantidadEntrada),
          0
        ) cantidadFaltante,
        estatus_orden_pedido_id
      FROM
        (
          SELECT
            expp.existencia AS existencia,
            expp.existencia_minima AS existenciaMinima,
            expp.existencia_maxima AS existenciaMaxima,
            expp.numero_lote AS lote,
            expp.numero_serie AS serie,
            expp.id AS idExistencia,
            expp.caducidad AS caducidad,
            expp.clave_producto AS clave,
            csu.Descripcion AS unidadMedida,
            p.Descripcion AS descripcion,
            p.Nombre AS nombre,
            p.FKCategoriaProducto AS categoria,
            suc.sucursal AS sucursal,
            suc.id AS sucursal_id,
            IFNULL (opps.sucursal_origen_id, 0) AS sucursalOri,
            IFNULL (opps.sucursal_destino_id, 0) AS sucursalDest,
            IFNULL (
              concat(
                tsi.TipoSalida,
                IF (
                  opps.numero_cotizacion IS NOT NULL
                  AND opps.numero_cotizacion != ''
                  AND opps.numero_cotizacion != 0
                  AND opps.tipo_pedido = 3,
                  ' (Cotización)',
                  IF (
                    opps.numero_venta_directa IS NOT NULL
                    AND opps.numero_venta_directa != ''
                    AND opps.numero_venta_directa != 0
                    AND opps.tipo_pedido = 4,
                    ' (Venta)',
                    IF (
                      opps.sucursal_destino_id IS NOT NULL
                      AND opps.sucursal_destino_id != ''
                      AND opps.sucursal_destino_id != 0
                      AND opps.tipo_pedido = 1,
                      ' (Traspaso)',
                      IF (
                        (
                          opps.sucursal_destino_id IS NOT NULL
                          AND opps.sucursal_destino_id != ''
                          AND opps.sucursal_destino_id != 0
                        )
                        or (
                          opps.cliente_id IS NOT NULL
                          AND opps.cliente_id != ''
                          AND opps.cliente_id != 0
                        )
                        AND opps.tipo_pedido = 2,
                        ' (General)',
                        ''
                      )
                    )
                  )
                )
              ),
              ''
            ) AS tipo,
            IFNULL (isps.folio_salida, '') AS folio_salida,
            IFNULL (isps.cantidad, 0) AS cantidadSalida,
            IFNULL (ieps.cantidad, 0) AS cantidadEntrada,
            opps.estatus_orden_pedido_id
          FROM
            existencia_por_productos AS expp
            INNER JOIN productos AS p ON expp.producto_id = p.PKProducto
            LEFT JOIN info_fiscal_productos AS ifp ON p.PKProducto = ifp.FKProducto
            LEFT JOIN claves_sat_unidades AS csu ON ifp.FKClaveSATUnidad = csu.PKClaveSATUnidad
            INNER JOIN sucursales AS suc ON expp.sucursal_id = suc.id
            LEFT JOIN inventario_salida_por_sucursales AS isps ON expp.numero_lote = isps.numero_lote
            AND expp.numero_serie = isps.numero_serie
            AND expp.clave_producto = isps.clave
            AND expp.sucursal_id = isps.sucursal_id
            LEFT JOIN orden_pedido_por_sucursales AS opps ON isps.orden_pedido_id = opps.id
            LEFT JOIN tipos_salidas_inventarios tsi ON isps.tipo_salida = tsi.PKTipoSalida
            LEFT JOIN inventario_entrada_por_sucursales AS ieps ON isps.id = ieps.inventario_salida_id
            AND isps.numero_lote = ieps.numero_lote
            AND isps.numero_serie = ieps.numero_serie
            AND isps.clave = ieps.clave
          WHERE
            p.empresa_id = :empresa $conditions
            AND p.FKTipoProducto != 8
        ) AS sqlCantidad
        GROUP BY clave, lote, categoria, sucursal_id ORDER BY sucursal_id"; //serie,

        $table = "";

        $stmt = $db->prepare($query);
        if ($sucu != "todas" && $cate != "todas") {
            $stmt->execute([':empresa' => $idEmpresa, ':sucursal' => $sucu, ':categoria' => $cate]);
        } else if ($sucu != "todas" && $cate == "todas") {
            $stmt->execute([':empresa' => $idEmpresa, ':sucursal' => $sucu,]);
        } else if ($sucu == "todas" && $cate != "todas") {
            $stmt->execute([':empresa' => $idEmpresa, ':categoria' => $cate]);
        } else {
            $stmt->execute([':empresa' => $idEmpresa]);
        }

        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $idExistencia = $r['idExistencia'];
            $clave = str_replace('"', '\"', $r['clave']);
            $sucursal = str_replace('"', '\"', $r['sucursal']);
            $Descripcion = str_replace('"', '\"', $r['descripcion']);
            $Nombre = str_replace('"', '\"', $r['nombre']);
            $Traspasos = $r['cantidadFaltante'];
            $Existencia = $r['existencia'];
            $Lote = str_replace('"', '\"', $r['lote']);
            $Serie = str_replace('"', '\"', $r['serie']);
            $Caducidad = $r['caducidad'];
            $Unidad = $r['unidadMedida'];
            $Minimo = $r['existenciaMinima'];
            $Maximo = $r['existenciaMaxima'];

            $loteSerie = $Lote != "" ? $Lote : $Serie;
            if ($Existencia == "0") {
                $Existencia = '<span class=\"textTable status-table status-table--red\">' . $Existencia . '</span>';
            } else {
                $Existencia = '<span class=\"textTable\">' . $Existencia . '</span>';
            }

            $inputMinimo = '<input type=\"hidden\" id=\"id-min-' . $idExistencia . '\" value=\"' . $Minimo . '\">';
            $inputMaximo = '<input type=\"hidden\" id=\"id-max-' . $idExistencia . '\" value=\"' . $Maximo . '\">';
            $inputClave = '<input type=\"hidden\" id=\"id-clave-' . $idExistencia . '\" value=\"' . $clave . '\">';
            $inputSerieLote = '<input type=\"hidden\" id=\"id-serieLote-' . $idExistencia . '\" value=\"' . $loteSerie . '\">';
            $clave = '<a data-toggle=\"modal\" class=\"btnEdit\" data-target=\"#editStockModal\" onclick=\"getInfoStock(' . $idExistencia . ')\">' . $clave . '</a>';

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';
            //"Serie":"' . $etiquetaI . $Serie . $etiquetaF . '", 3933
            $table .= '{"Clave":"' . $etiquetaI . $clave . $inputClave . $etiquetaF . '",
                "Sucursal":"' . $etiquetaI . $sucursal . $etiquetaF . '",
                "Nombre":"' . $etiquetaI . $Nombre . $etiquetaF . '",
                "Traspasos":"' . $etiquetaI . $Traspasos . $etiquetaF . '",
                "Existencia":"' . $Existencia . '",
                "Lote":"' . $etiquetaI . $Lote . $inputSerieLote . $etiquetaF . '",
                
                "Caducidad":"' . $etiquetaI . $Caducidad . $etiquetaF . '",
                "Unidad":"' . $etiquetaI . $Unidad . $etiquetaF . '",
                "Minimo":"' . $etiquetaI . $Minimo . $inputMinimo . $etiquetaF . '",
                "Maximo":"' . $etiquetaI . $Maximo . $inputMaximo .  $etiquetaF . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getInventory2($sucu, $exist)
    {
        $con = new conectar();
        $db = $con->getDb();
        $idEmpresa = $_SESSION['IDEmpresa'];
        $conditions = "";
        if ($sucu != "todas") {
            $conditions .= 'AND s.id = :sucursal ';
        }

        if ($exist == 'noExistencia') {
            $conditions .= 'AND existencia >= 0';
        } elseif ($exist == 'existencia') {
            $conditions .= 'AND existencia > 0';
        }
        $query = "SELECT
            suc.sucursal AS sucursal,
            p.ClaveInterna AS clave,
            IFNULL(existenciasQuery.existencia, 0) AS existencias,
            SUM(IFNULL(dopps.cantidad_pedida, 0)) AS cantidadPedida,
            SUM(IFNULL(dopps.cantidad_surtida, 0)) AS cantidadSurtida,
            (SUM(IFNULL(dopps.cantidad_pedida, 0)) - SUM(IFNULL(dopps.cantidad_surtida, 0))) AS pedidos
        FROM
            orden_pedido_por_sucursales AS opps
            INNER JOIN detalle_orden_pedido_por_sucursales AS dopps ON opps.id = dopps.orden_pedido_id
            INNER JOIN productos AS p ON dopps.producto_id = p.PKProducto
            INNER JOIN sucursales AS suc ON opps.sucursal_origen_id = suc.id
            LEFT JOIN (
                SELECT
                    SUM(expp.existencia) AS existencia,
                    expp.sucursal_id AS sucursal,
                    p.ClaveInterna AS clave
                FROM
                    existencia_por_productos AS expp
                    INNER JOIN productos AS p ON expp.producto_id = p.PKProducto
                WHERE
                    p.empresa_id = :empresa1
                    AND p.FKTipoProducto != 8
                GROUP BY
                    clave,
                    p.PKProducto,
                    sucursal) AS existenciasQuery ON p.ClaveInterna = existenciasQuery.clave AND suc.id = existenciasQuery.sucursal
        WHERE
            p.empresa_id = :empresa2
            AND p.FKTipoProducto != 8
            AND(opps.estatus_orden_pedido_id BETWEEN 1 AND 4)
            $conditions
        GROUP BY
            p.ClaveInterna,
            suc.id;";

        $table = "";

        $stmt = $db->prepare($query);
        if ($sucu != "todas") {
            $stmt->execute([':empresa1' => $idEmpresa, ':empresa2' => $idEmpresa, ':sucursal' => $sucu]);
        } else {
            $stmt->execute([':empresa1' => $idEmpresa, ':empresa2' => $idEmpresa,]);
        }

        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $sucursal = $r['sucursal'];
            $clave = $r['clave'];
            $Pedidos = $r['pedidos'];
            $Existencia = $r['existencias'];
            $acciones = '';

            if ($Existencia <= "0") {
                $Existencia = '<span class=\"textTable status-table status-table--red\">' . $Existencia . '</span>';
            } else {
                $Existencia = '<span class=\"textTable\">' . $Existencia . '</span>';
            }

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';
            $table .= '{"Clave":"' . $etiquetaI . $clave . $etiquetaF . '",
                "Sucursal":"' . $etiquetaI . $sucursal . $etiquetaF . '",
                "Pedidos":"' . $etiquetaI . $Pedidos . $etiquetaF . '",
                "Existencia":"' . $Existencia . '",
                "Acciones":"' . $acciones . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getProductsTable()
    {
        $con = new conectar();
        $db = $con->getDb();
        $table = "";

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $stmt = $db->prepare("SELECT p.PKProducto, p.Nombre, p.ClaveInterna, p.CodigoBarras, cp.CategoriaProductos, mp.MarcaProducto, IFNULL(p.Descripcion, '') AS Descripcion, tp.TipoProducto, p.Imagen, p.estatus, i.StockExistencia
        FROM productos AS p
        INNER JOIN categorias_productos AS cp ON p.FKCategoriaProducto = cp.PKCategoriaProducto
        INNER JOIN marcas_productos AS mp ON p.FKMarcaProducto = mp.PKMarcaProducto
        INNER JOIN tipos_productos AS tp ON p.FKTipoProducto = tp.PKTipoProducto
        LEFT JOIN inventarios i on p.PKProducto = i.FKProducto
        WHERE p.empresa_id = :idEmpresa order by p.PKProducto desc");
        $stmt->execute([":idEmpresa" => $PKEmpresa]);
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //$rutaServer = $_ENV['RUTA_ARCHIVOS_READ'] . $PKEmpresa . '/img' . '/';


        foreach ($productos as $producto) {
            $id = $producto['PKProducto'];
            $nombre = str_replace('"', '\"', str_replace(["\r", "\n"], "", $producto['Nombre']));
            $claveInterna = str_replace('"', '\"', str_replace(["\r", "\n"], "", $producto['ClaveInterna']));
            $codigoBarras = str_replace('"', '\"', str_replace(["\r", "\n"], "", $producto['CodigoBarras']));
            $categoriaProductos = str_replace('"', '\"', str_replace(["\r", "\n"], "", $producto['CategoriaProductos']));
            $marcaProducto = str_replace('"', '\"', str_replace(["\r", "\n"], "", $producto['MarcaProducto']));
            $descripcion = str_replace('"', '\"', str_replace(["\r", "\n"], "", $producto['Descripcion']));
            $tipoProducto = $producto['TipoProducto'];
            $imagen = $producto['Imagen'] ? $producto['Imagen'] : '';
            $estatus = $producto['estatus'] === 1 ? 'Activo' : 'Inactivo';
            $existencia = $producto['StockExistencia'];

            $nombre = '<a href=\"editar_producto.php?p=' . $id . '\">' . $nombre . '</a>';

            $table .= '{"Nombre":"' . trim($nombre) . '",
                "ClaveInterna":"' . trim($claveInterna) . '",
                "CodigoBarras":"' . trim($codigoBarras) . '",
                "CategoriaProductos":"' . trim($categoriaProductos) . '",
                "MarcaProducto":"' . trim($marcaProducto) . '",
                "Descripcion":"' . trim($descripcion) . '",
                "TipoProducto":"' . $tipoProducto . '",
                "Imagen":"' . $existencia . '",
                "Estatus":"' . $estatus . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getClavesSATTable($buscador)
    {
        $con = new conectar();
        $db = $con->getDb();
        $table = "";

        $stmt = $db->prepare('call spc_Tabla_ClaveSAT_Consulta(?)');
        $stmt->execute(array($buscador));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id = $r['id'];
            $clave = $r['clave'];
            $descripcion = $r['descripcion'];

            $concatenacion = "" . $clave . " - " . $descripcion;

            $etiquetaI = '<span class=\"textTable\" data-dismiss=\"modal\" onclick=\"obtenerIdClaveSeleccionar(' . $id . ',\'' . $clave . '\',\'' . $descripcion . '\');\">';
            $etiquetaF = '</span>';
            $acciones = '<i><img class=\"btnEdit\" data-dismiss=\"modal\" onclick=\"obtenerIdClaveSeleccionar(' . $id . ',\'' . $clave . '\',\'' . $descripcion . '\');\" src=\"../../../../img/timdesk/ICONO ESTADO-TERMINADO_Mesa de trabajo 1.svg\"></i>';

            $table .= '{"Id":"' . $etiquetaI . $id . $etiquetaF . '",
                "Clave":"' . $etiquetaI . $clave . $etiquetaF . '",
                "Descripcion":"' . $etiquetaI . $descripcion . $etiquetaF . $acciones . '"},';
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

    public function getImpuestoProductoTable($data, $permissionEdit)
    {
        $con = new conectar();
        $db = $con->getDb();
        $table = "";

        $query = sprintf('call spc_Tabla_Impuestos_Producto_Consulta(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($data));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id = $r['id'];
            $nombre = $r['nombre'];
            $tipoImpuesto = $r['tipoImpuesto'];
            $tasa = $r['tasa'];

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';

            if ($permissionEdit == '1') {
                $acciones = '<i class=\"fas fa-trash-alt pointer\" onclick=\"eliminarImpuesto(' . $id . ');\"></i>';
                $nombre_impuesto = '<a class=\"pointer\" href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_ImpuestoProducto\" onclick=\"get_impuestoProduct(' . $id . ');\">' . $nombre . '</a>';
            } else {
                $acciones = '';
                $nombre_impuesto = $nombre;
            }


            $table .= '{"Id":"' . $etiquetaI . $id . $etiquetaF . '",
                "Nombre":"' . $etiquetaI . $nombre_impuesto . $etiquetaF . '",
                "TipoImpuesto":"' . $etiquetaI . $tipoImpuesto . $etiquetaF . '",
                "Acciones":"",
                "Tasa":"' . $etiquetaI . $tasa . $etiquetaF . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getAccionProductoTable($data)
    {
        $con = new conectar();
        $db = $con->getDb();
        $table = "";

        $query = sprintf('call spc_Tabla_Acciones_Producto_Consulta(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($data));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id = $r['id'];
            $accion = $r['accion'];

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';
            $acciones = '<i><img class=\"btnEdit\" onclick=\"eliminarTipoProducto(' . $id . ');\" src=\"../../../../img/timdesk/delete.svg\"></i>';

            $table .= '{"Id":"' . $etiquetaI . $id . $etiquetaF . '",
                "TipoProducto":"' . $etiquetaI . $accion . $etiquetaF . $acciones . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getAccionProductoTableTemp($data)
    {
        $con = new conectar();
        $db = $con->getDb();
        $table = "";

        $query = sprintf('call spc_Tabla_Acciones_Producto_Consulta_Temp(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($data));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id = $r['id'];
            $accion = $r['accion'];

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';
            $acciones = '<i><img class=\"btnEdit\" onclick=\"eliminarAccionProductoTemp(' . $id . ');\" src=\"../../../../img/timdesk/delete.svg\"></i>';

            $table .= '{"Id":"' . $etiquetaI . $id . $etiquetaF . '",
                "TipoProducto":"' . $etiquetaI . $accion . $etiquetaF . $acciones . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getCategoriasTable()
    {
        $con = new conectar();
        $db = $con->getDb();
        $table = "";
        $idemp = $_SESSION['IDEmpresa'];
        $stmt = $db->prepare('call spc_Tabla_Categorias_Consulta(?)');
        $stmt->execute(array($idemp));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id = $r['id'];
            $categoria = $r['categoria'];
            $estatus = $r['estatus'];
            if ($estatus == 1) {
                $estatusR = "<span class='left-dot green-dot'>Activo</span>";
            } else {
                $estatusR = "<span class='left-dot red-dot'>Inactivo</span>";
            }

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';
            $acciones = '<i class=\"fas fa-edit pointer\" data-toggle=\"modal\" data-target=\"#editar_Categoria\" onclick=\"obtenerIdCategoriaEditar(' . $id . ');\"></i>';

            $table .= '{"Id":"' . $etiquetaI . $id . $etiquetaF . '",
                "CategoriaProducto":"' . $etiquetaI . $categoria . $etiquetaF . '",
                "Acciones":"' . $etiquetaI . $acciones . $etiquetaF . '",
                "Estatus":"' . $etiquetaI . $estatusR . $etiquetaF . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getMarcasTable()
    {
        $con = new conectar();
        $db = $con->getDb();
        $table = "";
        $idemp = $_SESSION['IDEmpresa'];
        $stmt = $db->prepare('call spc_Tabla_Marcas_Consulta(?)');
        $stmt->execute(array($idemp));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id = $r['id'];
            $marca = $r['marca'];
            $estatus = $r['estatus'];
            if ($estatus == 1) {
                $estatusR = "<span class='left-dot green-dot'>Activo</span>";
            } else {
                $estatusR = "<span class='left-dot red-dot'>Inactivo</span>";
            }

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';
            $acciones = '<i class=\"fas fa-edit pointer\" data-toggle=\"modal\" data-target=\"#editar_Marca\" onclick=\"obtenerIdMarcaEditar(' . $id . ');\"></i>';

            $table .= '{"Id":"' . $etiquetaI . $id . $etiquetaF . '",
                "MarcaProducto":"' . $etiquetaI . $marca . $etiquetaF . '",
                "Acciones":"' . $etiquetaI . $acciones . $etiquetaF . '",
                "Estatus":"' . $estatusR . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getTipoProductoTable()
    {
        $con = new conectar();
        $db = $con->getDb();
        $table = "";

        $stmt = $db->prepare('call spc_Tabla_TipoProducto_Consulta()');
        $stmt->execute();
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id = $r['id'];
            $tipoProducto = $r['tipoProducto'];
            $estatus = $r['estatus'];

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';
            $acciones = '<i><img class=\"btnEdit\" data-toggle=\"modal\" data-target=\"#editar_TipoProducto\" onclick=\"obtenerIdTipoProductoEditar(' . $id . ');\" src=\"../../../../img/timdesk/edit.svg\"></i>';

            $table .= '{"Id":"' . $etiquetaI . $id . $etiquetaF . '",
                "TipoProducto":"' . $etiquetaI . $tipoProducto . $etiquetaF . '",
                "Estatus":"' . $etiquetaI . $estatus . $etiquetaF . $acciones . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getTipoOrdenInventarioTable($value)
    {
        $con = new conectar();
        $db = $con->getDb();
        $table = "";

        $stmt = $db->prepare('call spc_Tabla_TipoOrdenInventario_Consulta(:emp_id)');
        $stmt->bindValue(":emp_id", $value, PDO::PARAM_INT);
        $stmt->execute();
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id = $r['id'];
            $tipoOdenInventario = $r['tipoOrdenInventario'];
            $estatus = $r['estatus'];
            if ($estatus == 1) {
                $estatusR = "Activo";
            } else {
                $estatusR = "Inactivo";
            }

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';
            $acciones = '<i class=\"fas fa-edit pointer\" data-toggle=\"modal\" data-target=\"#editar_Tipoordeninventario_51\" onclick=\"obtenerIdTipoOrdenInventarioEditar(' . $id . ');\"></i>';

            $table .= '{"Id":"' . $etiquetaI . $id . $etiquetaF . '",
                "TipoOrdenInventario":"' . $etiquetaI . $tipoOdenInventario . $etiquetaF . '",
                "Acciones":"' . $etiquetaI . $acciones . $etiquetaF . '",
                "Estatus":"' . $etiquetaI . $estatusR . $etiquetaF . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getProveedorTable($pkProducto, $permissionEdit)
    {
        $con = new conectar();
        $db = $con->getDb();
        $table = "";

        $query = sprintf('call spc_Tabla_Proveedor_Producto_Consulta(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkProducto));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id = $r['id'];
            $proveedor = $r['proveedor'];
            $producto = $r['producto'];
            $clave = $r['clave'];
            $precio = $r['precio'];
            $dias = $r['dias'];

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';

            if ($permissionEdit == '1') {
                $acciones = '<i class=\"fas fa-edit pointer\" data-toggle=\"modal\" data-target=\"#editar_Proveedor\" onclick=\"datosEditProveedor(' . $id . ');\"></i>';
                $link_proveedor = '<a class=\"pointer\" href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Proveedor\" onclick=\"datosEditProveedor(' . $id . ');\">' . $proveedor . '</a>';
            } else {
                $acciones = '';
            }

            $table .= '{"Id":"' . $etiquetaI . $id . $etiquetaF . '",
                "Proveedor":"' . $etiquetaI . $link_proveedor . $etiquetaF . '",
                "Producto":"' . $etiquetaI . $producto . $etiquetaF . '",
                "Clave":"' . $etiquetaI . $clave . $etiquetaF . '",
                "Precio":"' . $etiquetaI . $precio . $etiquetaF . '",
                "Acciones":"",
                "DiasEntrega":"' . $etiquetaI . $dias . $etiquetaF . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getProductoProveTable($pkProveedor, $permissionEdit)
    {
        $con = new conectar();
        $db = $con->getDb();
        $table = "";

        $query = sprintf('call spc_Tabla_ProductoProve_Consulta(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkProveedor));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id = $r['id'];
            $proveedor = addslashes($r['proveedor']);
            $producto = addslashes($r['producto']);
            $clave = $r['clave'];
            $precio = $r['precio'];
            $dias = $r['dias'];

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';
            $etiquetaInicionAccion = '<span class=\"textTable pointer\" data-toggle=\"modal\" data-target=\"#editar_Producto\" onclick=\"datosEditProveedor(' . $id . ');\">';
            $acciones = '';

            $table .= '{"Id":"' . $etiquetaI . $id . $etiquetaF . '",
                "Proveedor":"' . $etiquetaI . $proveedor . $etiquetaF . '",
                "Producto":"' . $etiquetaInicionAccion . $producto . $etiquetaF . '",
                "Clave":"' . $etiquetaI . $clave . $etiquetaF . '",
                "Precio":"' . $etiquetaI . $precio . $etiquetaF . '",
                "Acciones":"' . $etiquetaI . $acciones . $etiquetaF . '",
                "DiasEntrega":"' . $etiquetaI . $dias . $etiquetaF . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getClientesTable($pkProducto, $permissionEdit)
    {
        $con = new conectar();
        $db = $con->getDb();
        $table = "";
        $idemp = $_SESSION['IDEmpresa'];

        $query = sprintf('call spc_Tabla_Cliente_Producto_Consulta(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($idemp, $pkProducto));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id = $r['id'];
            $cliente = $r['nombre'];
            $costoEspecial = $r['costo'];
            $moneda = $r['moneda'];

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';

            if ($permissionEdit == '1') {

                $cliente = '<a class=\"pointer\" data-toggle=\"modal\" data-target=\"#editar_Producto_costo\" onclick=\"getProductoIdAndName(' . $id . ')\"><span class=\"textTable\">' . $cliente . '</span></a>';

                //$acciones = '<i class=\"fas fa-trash-alt pointer\" onclick=\"eliminarCliente(' . $id . ');\"></i>';
            }

            $table .= '{"Id":"' . $etiquetaI . $id . $etiquetaF . '",
                "NombreComercial":"' . $etiquetaI . $cliente . $etiquetaF . '",
                "CostoEspecial":"' . $etiquetaI . $costoEspecial . ' ' . $moneda . $etiquetaF . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    /* PROVEEDORES */
    public function getProveedoresTable()
    {
        $con = new conectar();
        $db = $con->getDb();
        $table = "";

        $stmt = $db->prepare('SELECT p.PKProveedor, p.NombreComercial, p.Movil, p.Telefono, p.Email, p.SegundoEmail, p.Giro, dfp.Razon_Social, p.Dias_credito, p.estatus, p.Vendedor, dfp.RFC, dfp.Calle, dfp.Numero_exterior, dfp.Numero_Interior, dfp.Municipio, dfp.Colonia, dfp.CP, ps.Pais, ef.Estado, dfp.Localidad, dfp.Referencia
        FROM proveedores AS p
        LEFT JOIN domicilio_fiscal_proveedor AS dfp ON p.PKProveedor = dfp.FKProveedor
        LEFT JOIN paises AS ps ON dfp.Pais = ps.PKPais
        LEFT JOIN estados_federativos AS ef ON dfp.Estado = ef.PKEstado
        WHERE p.empresa_id = :empresa_id');
        $stmt->execute([':empresa_id' => $_SESSION["IDEmpresa"]]);
        $proveedores = $stmt->fetchAll();

        foreach ($proveedores as $proveedor) {
            $id = $proveedor['PKProveedor'];
            $nombreComercial = $proveedor['NombreComercial'];
            $movil = $proveedor['Movil'];
            $telefono = $proveedor['Telefono'];
            $email = $proveedor['Email'];
            $emailSecundario = $proveedor['SegundoEmail'];
            $giro = $proveedor['Giro'];
            $razonSocial = $proveedor['Razon_Social'];
            $diasCredito = $proveedor['Dias_credito'];
            $estatus = $proveedor['estatus'] === 1 ? 'Activo' : 'Inactivo';
            $vendedor = $proveedor['Vendedor'];
            $rfc = $proveedor['RFC'];
            $calle = $proveedor['Calle'];
            $numeroExt = $proveedor['Numero_exterior'];
            $numeroInt = $proveedor['Numero_Interior'];
            $municipio = $proveedor['Municipio'];
            $colonia = $proveedor['Colonia'];
            $cp = $proveedor['CP'];
            $pais = $proveedor['Pais'];
            $estado = $proveedor['Estado'];
            $localidad = $proveedor['Localidad'];
            $referencia = $proveedor['Referencia'];

            $etiquetaI = '<div class=\"d-flex\">';
            $etiquetaF = '</div>';
            //$acciones = '<i class=\"fas fa-edit color-primary pointer mr-2\" onclick=\"obtenerIdProveedorEditar(' . $id . ');\"></i> <i class=\"fas fa-trash-alt color-primary pointer\" data-toggle=\"modal\" data-target=\"#eliminar_Proveedor\" onclick=\"obtenerIdProveedorEliminar(' . $id . ');\"></i>';
            $nombreComercial = '<a style=\"cursor:pointer\" href=\"detalle_proveedor.php?proveedor_id=' . $id . '\">' . $nombreComercial . '</a>';

            $table .= '{"NombreComercial":"' . $nombreComercial . '",
                "Movil":"' . $movil . '",
                "Telefono":"' . $telefono . '",
                "Email":"' . $email . '",
                "EmailSecundario":"' . $emailSecundario . '",
                "Giro":"' . $giro . '",
                "RazonSocial":"' . $razonSocial . '",
                "DiasDeCredito":"' . $diasCredito . '",
                "EstatusDelProveedor":"' . $estatus . '",
                "Vendedor":"' . $vendedor . '",
                "RFC":"' . $rfc . '",
                "Calle":"' . $calle . '",
                "NumeroExterior":"' . $numeroExt . '",
                "NumeroInterior":"' . $numeroInt . '",
                "Municipio":"' . $municipio . '",
                "Colonia":"' . $colonia . '",
                "CodigoPostal":"' . $cp . '",
                "Pais":"' . $pais . '",
                "Estado":"' . $estado . '",
                "Localidad":"' . $localidad . '",
                "Referencia":"' . $referencia . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getRazonSocialProveedoresTable($pkProveedor, $permissionEdit)
    {
        $con = new conectar();
        $db = $con->getDb();
        $table = "";

        $query = sprintf('call spc_Tabla_RazonesSociales_Proveedores_Consulta(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkProveedor));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id = trim($r['id']);
            $razonSocial = trim($r['razonSocial']);
            $rfc = trim($r['rfc']);
            $calle = trim($r['calle']);
            $numeroExt = trim($r['numeroExt']);
            $numeroInt = trim($r['numeroInt']);
            $colonia = trim($r['colonia']);
            $municipio = trim($r['municipio']);
            $estado = trim($r['estado']);
            $pais = trim($r['pais']);
            $cp = trim($r['cp']);
            $localidad = trim($r['Localidad']);
            $referencia = trim($r['Referencia']);

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';
            $etiquetaInicionAccion = '<span class=\"textTable pointer\" data-toggle=\"modal\" data-target=\"#editar_InfoFiscal\" onclick=\"obtenerIdRazonSocialProveedorEditar(' . $id . ');\">';

            $acciones = '';

            $table .= '{"Id":"' . $etiquetaI . $id . $etiquetaF . '",
                "RazonSocial":"' . $etiquetaInicionAccion . $razonSocial . $etiquetaF . '",
                "RFC":"' . $etiquetaI . $rfc . $etiquetaF . '",
                "Calle":"' . $etiquetaI . $calle . $etiquetaF . '",
                "NumeroExt":"' . $etiquetaI . $numeroExt . $etiquetaF . '",
                "NumeroInt":"' . $etiquetaI . $numeroInt . $etiquetaF . '",
                "Colonia":"' . $etiquetaI . $colonia . $etiquetaF . '",
                "Municipio":"' . $etiquetaI . $municipio . $etiquetaF . '",
                "Estado":"' . $etiquetaI . $estado . $etiquetaF . '",
                "Pais":"' . $etiquetaI . $pais . $etiquetaF . '",
                "CP":"' . $etiquetaI . $cp . $etiquetaF . '",
                "Localidad":"' . $etiquetaI . $localidad . $etiquetaF . '",
                "Referencia":"' . $etiquetaI . $referencia . $etiquetaF . '",
                "Acciones":"' . $etiquetaI . $acciones . $etiquetaF . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getDireccionEnvioProveedoresTable($pkProveedor)
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

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';
            $acciones = '<i class=\"fas fa-trash-alt pointer\" onclick=\"obtenerIdDireccionProveedorEliminar(' . $id . ');\"</i><i class=\"fas fa-edit pointer\" onclick=\"obtenerIdDireccionProveedorEditar(' . $id . ');\"></i>';

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
                "CP":"' . $etiquetaI . $cp . $etiquetaF . $acciones . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getContactoProveedoresTable($pkProveedor, $permissionEdit)
    {
        $con = new conectar();
        $db = $con->getDb();
        $table = "";

        $query = sprintf('call spc_Tabla_Contactos_Proveedores_Consulta(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkProveedor));
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
            $etiquetaInicionAccion = '<span class=\"textTable pointer\" data-toggle=\"modal\" data-target=\"#editar_Contacto\"  onclick=\"obtenerIdContactoProveedorEditar(' . $id . ');\">';
            $acciones = '';

            $table .= '{"Id":"' . $etiquetaI . $id . $etiquetaF . '",
                "Nombre":"' . $etiquetaInicionAccion . $nombre . $etiquetaF . '",
                "Apellido":"' . $etiquetaI . $apellido . $etiquetaF . '",
                "Puesto":"' . $etiquetaI . $puesto . $etiquetaF . '",
                "TelefonoFijo":"' . $etiquetaI . $telefono . $etiquetaF . '",
                "Celular":"' . $etiquetaI . $celular . $etiquetaF . '",
                "Acciones":"' . $etiquetaI . $acciones . $etiquetaF . '",
                "Email":"' . $etiquetaI . $email . $etiquetaF . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getBancoProveedoresTable($pkProveedor, $permissionEdit)
    {
        $con = new conectar();
        $db = $con->getDb();
        $table = "";

        $query = sprintf('call spc_Tabla_Bancos_Proveedores_Consulta(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkProveedor));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id = $r['id'];
            $banco = $r['banco'];
            $noCuenta = $r['noCuenta'];
            $clabe = $r['clabe'];
            $moneda = $r['moneda'];

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';
            $etiquetaInicionAccion = '<span class=\"textTable pointer\" data-toggle=\"modal\" data-target=\"#editar_CuentaBancancaria\" onclick=\"obtenerIdBancoProveedorEditar(' . $id . ');\">';
            $acciones = '';

            $table .= '{"Id":"' . $etiquetaI . $id . $etiquetaF . '",
                "Banco":"' . $etiquetaInicionAccion . $banco . $etiquetaF . '",
                "NoCuenta":"' . $etiquetaI . $noCuenta . $etiquetaF . '",
                "CLABE":"' . $etiquetaI . $clabe . $etiquetaF . '",
                "Acciones":"' . $etiquetaI . $acciones . $etiquetaF . '",
                "Moneda":"' . $etiquetaI . $moneda . $etiquetaF . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }
    /* PROVEEDORES */

    /////////////////////////COMBOS//////////////////////////////
    public function getCmbProductos($PKProducto)
    {
        $con = new conectar();
        $db = $con->getDb();
        $table = "";

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $stmt = $db->prepare('call spc_Combo_Productos(?,?)');
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

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>';
            $acciones = '<i><img class=\"btnEdit\" data-dismiss=\"modal\" onclick=\"obtenerIdProductoSeleccionar(\'' . $id . '\',\'' . $claveInterna . '\',\'' . $nombre . '\',\'' . $unidadMedida . '\',\'' . $costoFabri . '\',\'' . $moneda . '\');\" src=\"../../../../img/timdesk/ICONO ESTADO-TERMINADO_Mesa de trabajo 1.svg\"></i>';

            $table .= '{"Id":"' . $etiquetaI . $id . $etiquetaF . '",
                "ClaveInterna":"' . $etiquetaI . $claveInterna . $etiquetaF . '",
                "Nombre":"' . $etiquetaI . $nombre . $etiquetaF . $acciones . '",
                "Estatus":"' . $etiquetaI . $fkEstatusGeneral . $etiquetaF . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getCmbEstatusGral()
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Combo_EstatusGeneral()');
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getCmbCategoria()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Combo_Categoria(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));
        return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function loadCmbCategorias()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf("SELECT * from (select 
                            PKCategoria, 
                            Nombre 
                        from 
                            categoria_gastos 
                        where empresa_id = :idEmpresa and estatus = 1
                        
                        union 
                        
                        select
                            PKCategoria, 
                            Nombre 
                        from 
                            categoria_gastos 
                        where PKCategoria = 1) as cat ORDER BY cat.PKCategoria");
        $stmt = $db->prepare($query);
        $stmt->bindValue(':idEmpresa', $PKEmpresa);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public function loadCmbSubcategorias($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf("select PKSubcategoria, Nombre from subcategorias_gastos where FKCategoria = :categoria");
        $stmt = $db->prepare($query);
        $stmt->bindValue(':categoria', $value);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getCmbMarca()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Combo_Marca(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));
        return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getCmbTipo()
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Combo_TipoProducto()');
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getCmbTipoOrden()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Combo_TipoOrdenInventario(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));
        return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getCmbCostouniCompra()
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Combo_Moneda()');
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getCmbCostouniCompraEdit()
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Combo_Moneda()');
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
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

    public function getCmbImpuestos()
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Combo_Impuestos()');
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getCmbTasaImpuestos($data)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Combo_TasasImpuestos(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($data));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getCmbAccionesProducto()
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Combo_AccionesProducto()');
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getCmbProveedor()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Combo_Proveedor(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));
        return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getCmbProveedorEdit()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Combo_Proveedor(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));
        return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getCmbUnidadMProveedor($data)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Combo_UnidadM_Proveedor(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($data));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getCmbClientesProducto()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Combo_Cliente(?)');
        $stmt = $db->prepare($query);
        $status = $stmt->execute(array($PKEmpresa));
        return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getCmbProductoCliente()
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Combo_ProductosCliente()');
        $stmt = $db->prepare($query);
        $stmt->execute();
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getCmbProductoProveedor()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Combo_ProductosProveedor(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    /* PROVEEDORES */
    public function getCmbMediosContacto()
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Combo_MediosContactoCliente()');
        $stmt = $db->prepare($query);
        $stmt->execute();
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getCmbVendedor()
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Combo_Vendedor()');
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

    public function getCmbCostouniVentaEsp()
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Combo_Moneda()');
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    /* END PROVEEDORES */

    public function getCmbPurchaseOrderEntry($pkProveedor)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Combo_OrdenesCompraEntrada(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa, $pkProveedor));
        return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getCmbNoDocsExit($pkProveedor)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Combo_NoDocumentosSalida(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa, $pkProveedor));
        return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getCmbProviderEntry()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Combo_Proveedor_Entrada(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getCmbBranchEntry($pkOrdenCompra)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Combo_Sucursal_Entrada(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkOrdenCompra));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getCmbBranchEntryFilter()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Combo_Sucursal_EntradaFiltro(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getCmbBranchExit($idCuentaPorPagar)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Combo_Sucursal_Salida(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($idCuentaPorPagar));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getCmbTypeEntryFilter()
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Combo_TipoEntrada()');
        $stmt = $db->prepare($query);
        $stmt->execute();
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getCmbTypeExitFilter()
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Combo_TipoSalida()');
        $stmt = $db->prepare($query);
        $stmt->execute();
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getCmbProductsEntry($pkOrdenCompra)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Combo_Productos_Entrada(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkOrdenCompra));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getCmbSucursales()
    {
        $con = new conectar();
        $db = $con->getDb();
        $PKUsuario = $_SESSION["PKUsuario"];

        $query = sprintf('call spc_Combo_Sucursales_InventarioInicial(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKUsuario));

        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }


    public function getCmbSucursalesInvPeriodico()
    {
        $con = new conectar();
        $db = $con->getDb();
        $PKUsuario = $_SESSION["PKUsuario"];

        $query = sprintf('call spc_Combo_Sucursales_InventarioPeriodico(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKUsuario));

        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }


    public function getCmbProductosInvPeriodico()
    {
        $con = new conectar();
        $db = $con->getDb();
        $PKUsuario = $_SESSION["IDEmpresa"];

        $query = sprintf('SELECT PKProducto, ClaveInterna, Nombre FROM productos WHERE empresa_id = ?');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKUsuario));

        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }


    public function getDataSucursales($PKSucursal)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('SELECT created_at, conteo, estatus FROM inventario_por_sucursales WHERE sucursal_id = ? AND tipo=1 ORDER BY created_at ASC LIMIT 1');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKSucursal));

        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getCmbSucursalesAjuste()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION['IDEmpresa'];
        $query = sprintf('call spc_Combo_Sucursales_Ajuste_Inventario(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));

        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getCmbFoliosAjuste($PKSucursal, $PKTipo)
    {
        $con = new conectar();
        $db = $con->getDb();
        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Combo_Folios_Ajuste_Inventario(?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa, $PKSucursal, $PKTipo));

        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getcmbClaves()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('SELECT PKProducto, ClaveInterna FROM productos WHERE empresa_id = ?');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));
        $array = $stmt->fetchAll();

        return $array;
    }

    public function getcmbSucursalesKardex()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKUsuario = $_SESSION["PKUsuario"];

        $query = sprintf('SELECT s.id, s.sucursal 
        FROM sucursales AS s 
        INNER JOIN usuarios AS u ON s.empresa_id = u.empresa_id 
	    WHERE u.id = ? 
        AND s.activar_inventario = 1');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKUsuario));
        $array = $stmt->fetchAll();

        return $array;
    }

    public function getCmbFoliosCambiosLote($PKSucursal, $PKTipo)
    {
        $con = new conectar();
        $db = $con->getDb();
        $PKEmpresa = $_SESSION["IDEmpresa"];
        $PKUsuario = $_SESSION["PKUsuario"];
        $query = sprintf('call spc_Combo_Folios_Cambio_Lote_Serie(?,?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa, $PKSucursal, $PKTipo, $PKUsuario));

        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getCmbBranchOrigin()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Combo_Sucursales_Origen(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getCmbBranchDirectEntry()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Combo_Sucursales_Origen(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getCmbBranchDirectEntryOriginED($PKSucursal)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Combo_Sucursales_Origen_ED(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa, $PKSucursal));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getCmbProviderDirectEntryED($PKSucursal)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Combo_Proveedor_Entrada(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getOCprovedoor($PKProveedor, $sucursalDestino)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('SELECT 
                                oc.PKOrdenCompra,
                                oc.Referencia
                            FROM ordenes_compra oc
                                where oc.empresa_id = ? and oc.FKProveedor = ? and oc.FKEstatusOrden in (2,6) and oc.FKSucursal = ?
                            order by oc.Referencia desc
                            ;');

        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa, $PKProveedor, $sucursalDestino));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getCmbCustomerDirectEntryED($PKSucursal)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Combo_Cliente_Entrada(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getCmbTipoDirectEntryProviderED()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Combo_Tipo_EntradaDireta_Proveedor(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getCmbBranchTypeOriginDirectEntry()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Combo_TipoOrigen_EntradaDirecta(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getCmbBranchDestination($pkOrdenPedido)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Combo_Sucursales_Destino(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkOrdenPedido));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getCmbBranchOrCustomer($pkOrdenPedido)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Combo_SucursalDestino_O_Cliente(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkOrdenPedido));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getCmbBranchOriginExit($folioSalida)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION['IDEmpresa'];

        $query = sprintf('call spc_Combo_Sucursal_Origen_Salida(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($folioSalida, $PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $array;
    }

    public function getCmbOrderPedido($pkSucursalOrigen)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Combo_OrdenPedido_SucOri(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkSucursalOrigen, $PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getCmbOrderPedidoGral($pkSucursalOrigen)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Combo_OrdenPedidoGral_SucOri(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkSucursalOrigen, $PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getCmbQuotes($pkSucursalOrigen)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Combo_Cotizaciones_Salidas(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkSucursalOrigen, $PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getCmbSales($pkSucursalOrigen)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Combo_Ventas_Salidas(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkSucursalOrigen, $PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getCmbTraspasoEntrada($pkSucursalDestino)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Combo_TraspasoEntrada_SucDest(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkSucursalDestino, $PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getCmbDispenserExit()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Combo_Surtidores_Salidas(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getCmbComprador()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Combo_Comprador(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getCmbCondicionPago()
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Combo_CondicionPago()');
        $stmt = $db->prepare($query);
        $stmt->execute();
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
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getCmbSucursalesProductos()
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('SELECT s.id, s.sucursal FROM sucursales AS s INNER JOIN usuarios AS u ON s.empresa_id = u.empresa_id WHERE u.id = ? AND s.activar_inventario = 1 and s.estatus = 1');
        $stmt = $db->prepare($query);
        $stmt->execute(array($_SESSION["PKUsuario"]));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }
    /////////////////////////DATOS PARA EDICIÓN//////////////////////////////
    public function getCategoriasProductosEmpresa($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Checkbox_Categorias_ProductosEmpresa(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getDatosCategoria($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Datos_Categoria(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getDatosMarca($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Datos_Marca(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getDatosTipoProducto($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Datos_TipoProducto(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getDatosTipoOrdenInventario($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Datos_TipoOrdenInventario(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

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

    public function getDataDatosProductoCompuesto($pkProducto, $pkUsuario)
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

    public function getDataEntryOC($folioEntrada)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION['IDEmpresa'];

        $query = sprintf('call spc_Datos_Entrada_ConsultaOC(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($folioEntrada, $PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getDataExitOP($folioSalida)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Datos_Salida_ConsultaOP(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($folioSalida, $PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getDataExitCoti($folioSalida)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Datos_Salida_ConsultaCoti(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($folioSalida, $PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getDataExitVenta($folioSalida)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Datos_Salida_ConsultaVenta(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($folioSalida, $PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getDataExitDevolucion($folioSalida)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Datos_Salida_ConsultaDevolucion(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($folioSalida));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getDataEntryTransfer($folioEntrada)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION['IDEmpresa'];

        $query = sprintf('call spc_Datos_Entrada_ConsultaTranfer(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($folioEntrada, $PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $array;
    }

    public function getDataEntryTransferEdit($folioEntrada)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION['IDEmpresa'];

        $query = sprintf('call spc_Datos_Entrada_ConsultaTranfer(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($folioEntrada, $PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $array;
    }

    public function getDataEntryDirectEdit($folioEntrada)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION['IDEmpresa'];

        $query = sprintf('call spc_Datos_Entrada_ConsultaDirecta(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($folioEntrada, $PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $array;
    }

    public function getDataEntryDirectProviderEdit($folioEntrada)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION['IDEmpresa'];

        $query = sprintf('call spc_Datos_EntradaProveedor_ConsultaDirecta(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($folioEntrada, $PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getDataEntryDirectCustomerEdit($folioEntrada)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION['IDEmpresa'];

        $query = sprintf('call spc_Datos_EntradaCliente_ConsultaDirecta(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($folioEntrada, $PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $array;
    }

    public function getDataEntryOCEdit($folioEntrada)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION['IDEmpresa'];

        $query = sprintf('call spc_Datos_Entrada_ConsultaOCEdit(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($folioEntrada, $PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $array;
    }

    public function getDataFiscalProducto($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Datos_Prod_ConsultaFiscal(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getDatosProveedorProducto($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Datos_ProveedorProd_Consulta(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getDatosProductoProveedor($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Datos_ProductoProve_Consulta(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getDataPestanaInventario($id)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('SELECT 
                                p.PKProducto,
                                p.ClaveInterna,
                                p.Nombre,
                                p.FKTipoProducto,
                                p.serie,
                                p.lote,
                                p.fecha_caducidad,
                                epp.id,
                                epp.numero_lote,
                                epp.numero_serie,
                                epp.caducidad,
                                epp.existencia,
                                epp.sucursal_id
                            FROM
                                productos p
                                    left JOIN
                                existencia_por_productos epp ON p.PKProducto = epp.producto_id
                            WHERE
                                p.PKProducto = ?');
        $stmt = $db->prepare($query);
        $stmt->execute(array($id));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getDataInventarioProducto($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Datos_Prod_ConsultaInventario(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getDataVentaProducto($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Datos_Prod_ConsultaVenta(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    /* PROVEEDORES */
    public function getDatosFiscalProveedor($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Datos_RazonSocial_Proveedor(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getDatosDireccionEnvioProveedor($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Datos_DireccionEnvio_Proveedor(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getDatosContactoProveedor($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Datos_Contacto_Proveedor(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getDatosCuentaBancariaProveedor($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Datos_CuentaBancaria_Proveedor(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getDatosGeneralesProveedor($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Datos_Proveedor_ConsultaGeneral(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }
    /* END PROVEEDORES */
    public function getSubTotalOrdenCompraTemp($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Info_OrdenCompra_Subtotal(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getSubTotalOrdenCompra($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Info_OrdenCompra_SubtotalEdit(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getSubTotalEntradaOCTemp($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        $query = sprintf('call spc_Info_EntradaOC_Subtotal(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value, $PKuser));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getSubTotalEntradaOCTempEdit($folioEntrada)
    {
        $con = new conectar();
        $db = $con->getDb();

        if (isset($_SESSION["IDEmpresa"])) {
            $PKEmpresa = $_SESSION["IDEmpresa"];
        } else {
            $PKEmpresa = '0';
        }

        if (isset($_SESSION["PKUsuario"])) {
            $PKuser = $_SESSION["PKUsuario"];
        } else {
            $PKuser = '0';
        }

        $query = sprintf('call spc_Info_EntradaOC_SubtotalEdit(?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($folioEntrada, $PKEmpresa, $PKuser));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getSubTotalEntradaEDProviderTemp()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];
        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Info_EntradaEDProvider_Subtotal(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa, $PKuser));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getImpuestoOrdenCompraTemp($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Info_OrdenCompra_Impuestos(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getImpuestoOrdenCompra($value, $isEdit)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Info_OrdenCompra_ImpuestosEdit(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value, $isEdit));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getTotalOrdenCompraTemp($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Info_OrdenCompra_Total(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getTotalOrdenCompra($value, $isEdit)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Info_OrdenCompra_TotalEdit(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value, $isEdit));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getDatosComentario($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Datos_OrdenCompra_Chat(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getDatosOrdenCompraPDF($PKOrdenCompraEncrypted, $PKUsuario)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Datos_OrdenesCompra_PDF(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKOrdenCompraEncrypted, $PKUsuario));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getDatosOrdenCompraPDFOffLine($PKOrdenCompraEncrypted)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Datos_OrdenesCompra_PDFOFF(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKOrdenCompraEncrypted));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getDatosProdOrdenCompraPDF($PKOrdenCompraEncrypted)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Info_OrdenCompra_ProductosPDF(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKOrdenCompraEncrypted));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getDatosImpuOrdenCompraPDF($PKOrdenCompraEncrypted)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Info_OrdenCompra_ImpuestosPDF(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKOrdenCompraEncrypted));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getDatosOrdenCompra($PKOrdenCompra)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Datos_OrdenCompra_General(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKOrdenCompra));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getInfoCantidadProductoOC($idProducto, $idOrdenC)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Info_CantidadProductoOC(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($idProducto, $idOrdenC));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getSubTotalEntradaOC($value, $impuestosActive)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Info_Entrada_OC_SubtotalEdit(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value, $impuestosActive));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getVerSubTotalEntradaOC($folioEntrada)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Info_EntradaOC_SubtotalVer(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($folioEntrada, $PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getImpuestoEntradaOC($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        $query = sprintf('call spc_Info_Entrada_OC_Impuestos(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value, $PKuser));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getImpuestoEntradaOCEdit($folioEntrada)
    {
        $con = new conectar();
        $db = $con->getDb();

        if (isset($_SESSION["IDEmpresa"])) {
            $PKEmpresa = $_SESSION["IDEmpresa"];
        } else {
            $PKEmpresa = '0';
        }

        if (isset($_SESSION["PKUsuario"])) {
            $PKuser = $_SESSION["PKUsuario"];
        } else {
            $PKuser = '0';
        }

        $query = sprintf('call spc_Info_Entrada_OC_ImpuestosEdit(?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($folioEntrada, $PKEmpresa, $PKuser));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getImpuestoEntradaEDProviderTemp()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];
        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Info_EntradaEDProvider_Impuestos(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa, $PKuser));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getVerImpuestoEntradaOC($folio)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Info_EntradaOC_ImpuestosVer(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($folio, $PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getTotalEntradaOC($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        $query = sprintf('call spc_Info_Entrada_OC_Total(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value, $PKuser));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getTotalEntradaOCVer($folio)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Info_EntradaOC_TotalVer(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($folio, $PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getTotalEntradaEDProviderTemp()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];
        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Info_EntradaEDProvider_Total(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa, $PKuser));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getTotalEntradaTransferVer($folio)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Info_EntradaTransfer_TotalVer(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($folio, $PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getTotalEntradaOCEdit($folioEntrada)
    {
        $con = new conectar();
        $db = $con->getDb();

        if (isset($_SESSION["IDEmpresa"])) {
            $PKEmpresa = $_SESSION["IDEmpresa"];
        } else {
            $PKEmpresa = '0';
        }

        if (isset($_SESSION["PKUsuario"])) {
            $PKuser = $_SESSION["PKUsuario"];
        } else {
            $PKuser = '0';
        }

        $query = sprintf('call spc_Info_Entrada_OC_TotalEdit(?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($folioEntrada, $PKEmpresa, $PKuser));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getTotalEntradaTransferEdit($folioEntrada)
    {
        $con = new conectar();
        $db = $con->getDb();

        if (isset($_SESSION["IDEmpresa"])) {
            $PKEmpresa = $_SESSION["IDEmpresa"];
        } else {
            $PKEmpresa = '0';
        }

        if (isset($_SESSION["PKUsuario"])) {
            $PKuser = $_SESSION["PKUsuario"];
        } else {
            $PKuser = '0';
        }

        $query = sprintf('call spc_Info_Entrada_Traspaso_TotalEdit(?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($folioEntrada, $PKEmpresa, $PKuser));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getTotalEntradaTraspasoTemp($idEntrada, $folioSalida)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];
        $PKEmpresa = $_SESSION['IDEmpresa'];

        $query = sprintf('call spc_Info_Entrada_Traspaso_Total(?,?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($idEntrada, $PKuser, $folioSalida, $PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = null;
        $db = null;

        return $array;
    }

    public function getDataDatosSalidaCantTemp($pkProducto, $ordenPedido)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        $query = sprintf('call spc_Datos_SalidaOP_Cant_Temp(?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkProducto, $ordenPedido, $PKuser));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getDataDatosSalidaCantTempEdicion($pkProducto, $folioSalida)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        $query = sprintf('call spc_Datos_SalidaOP_Cant_TempEdicion(?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkProducto, $folioSalida, $PKuser));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getTotalSalidaOP($pkOrden)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        $query = sprintf('call spc_Info_Salida_OP_Total(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKuser, $pkOrden));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getTotalSalidaOPVer($folioSalida)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];
        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Info_Salida_OP_TotalVer(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa, $folioSalida));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getTotalSalidaOPEdicion($folioSalida)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];
        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Info_Salida_OP_TotalEdicion(?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKuser, $folioSalida, $PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getDataQuoteExit($PKOrdenPedido)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];
        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Info_Salida_Cotizacion(?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKuser, $PKEmpresa, $PKOrdenPedido));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getDataSaleExit($PKOrdenPedido)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];
        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Info_Salida_VentaDirecta(?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKuser, $PKEmpresa, $PKOrdenPedido));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getDataNoDocExit($id_cuenta_pagar)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];
        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Info_Salida_NoDocumento(?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKuser, $PKEmpresa, $id_cuenta_pagar));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getDataTipoSalidaPedido($idOrdenPedido)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];
        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Info_Salida_Tipo(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($idOrdenPedido));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getBarcodeSalidas()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('SELECT acceso_codigoBarras_salidas as access from empresas where PKEmpresa = :id');
        $stmt = $db->prepare($query);
        $stmt->bindValue(':id', $PKEmpresa);
        $stmt->execute();
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function get_idProdsSalidas($idOrdenPedido)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        $query = sprintf('call spc_Tabla_Salidas_OP_Temp(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKuser, $idOrdenPedido));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function get_valida_codigo_ProdOrden($codigo, $idOrdenPedido)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];
        $PKEmpresa = $_SESSION["IDEmpresa"];

        // recupera clave interna y PK del producto
        $query = sprintf('SELECT p.PKProducto, p.ClaveInterna from productos p where p.empresa_id = :empresa and p.CodigoBarras = :codigo');
        $stmt = $db->prepare($query);
        $stmt->bindValue(":empresa", $PKEmpresa);
        $stmt->bindValue(":codigo", trim($codigo));
        $stmt->execute();
        $countProd = $stmt->rowCount();
        $prodFromCod = $stmt->fetchAll();

        $data['existeProd'] = 0;
        $data['producto'] = '';

        if ($countProd == 1) {
            //recupera los productos de la orden de compra para verificar que los datos coincidan.
            $query = sprintf('call spc_Tabla_Salidas_OP_Temp(?,?)');
            $stmt = $db->prepare($query);
            $stmt->execute(array($PKuser, $idOrdenPedido));
            $prodsOfOrden = $stmt->fetchAll();

            foreach ($prodsOfOrden as $r) {
                if ($r['pkProducto'] == $prodFromCod[0]['PKProducto'] && $r['clave'] == $prodFromCod[0]['ClaveInterna']) {
                    $data['existeProd'] = 1;
                    $data['producto'] = $r['pkProducto'];
                    break;
                }
            }
        }

        return $data;
    }
    /////////////////////////VALIDACIONES//////////////////////////////
    public function validarClaveInterna($data)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_ValidarUnicaClaveInterna(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($data, $PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarNombre($data)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_ValidarUnicoNombre(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($data, $PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarCodigoBarras($data)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_ValidarUnicoCodigoBarras(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($data, $PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarImpuestoProducto($data, $data2)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_ValidarUnicoImpuestoProducto(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($data, $data2));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarAccionProducto($data, $data2)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_ValidarUnicoAccionProducto(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($data, $data2));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarAccionProductoTemp($data, $data2)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_ValidarUnicoAccionProductoTemp(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($data, $data2));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarCategoriaProducto($data)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_ValidarUnicaCategoria(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($data, $PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarMarcaProducto($data)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_ValidarUnicaMarca(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($data, $PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarTipoProducto($data)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_ValidarUnicoTipoProducto(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($data));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarTipoOrdenInventario($data)
    {
        $idempresa = $_SESSION["IDEmpresa"];
        $con = new conectar();
        $db = $con->getDb();
        $query = sprintf('call spc_ValidarUnicoTipoOrdenInventario(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($data, $idempresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarProductoCompuestoTemp($pkUsuario, $pkProducto)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];


        $query = sprintf('call spc_ValidarUnicoProductoCompTemp(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkProducto, $PKuser));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarProveedorProducto($pkProveedor, $pkProducto)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_ValidarUnicoProveedorProducto(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkProducto, $pkProveedor));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarClaveProveedorProducto($pkProveedor, $clave)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_ValidarUnicoClaveProveedorProducto(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkProveedor, $clave));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarClienteProducto($pkCliente, $pkProducto)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_ValidarUnicoClienteProducto(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkCliente, $pkProducto));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    /* PROVEEDORES */
    public function validarMedioContactoProveedor($data)
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

        $query = sprintf('call spc_ValidarUnicoNombreComercial_Proveedor(?,?)');
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

    public function validarRazonSocialProveedor($razonSocial, $pkProveedor)
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

    public function validarRfcProveedor($rfc, $pkProveedor)
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

    public function validarContactoProveedor($email, $PKProveedor)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_ValidarUnicoContactoProveedor(?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($email, $PKProveedor, $PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarNoCuenta($value)
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

    public function validarCLABE($value)
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

    public function validarDatosBanariosProveedor($pkBanco, $noCuenta, $clabe, $pkProveedor)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_ValidarUnicoBancoProveedor(?,?,?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkBanco, $noCuenta, $clabe, $pkProveedor, $PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarSucursalProveedor($sucursal, $pkProveedor)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_ValidarUnicoSucursalProveedor(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($sucursal, $pkProveedor));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }
    /* END PROVEEDORES */
    public function validarProductoOrdenCompra($pkProducto, $pkUsuario, $pkProveedor)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_ValidarUnicoProductoOrdenCompra(?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkProducto, $pkUsuario, $pkProveedor));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarProductoOrdenCompraEdit($pkProducto, $pkOrden, $pkProveedor)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_ValidarUnicoProductoOrdenCompraEdit(?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkProducto, $pkOrden, $pkProveedor));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarEstadoOrdenCompra($pkOrden)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_ValidarEstadoOrdenCompra(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkOrden));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarProductoEntradaOC($idDetalle)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        $query = sprintf('call spc_ValidarUnicoProductoEntradaOCTemp(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($idDetalle, $PKuser));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarEmpresaProducto($pkProducto)
    {
        $con = new conectar();
        $db = $con->getDb();

        if (isset($_SESSION["Usuario"])) {
            $PKEmpresa = $_SESSION["IDEmpresa"];
        }

        $query = sprintf('call spc_ValidarEmpresaProducto(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa, $pkProducto));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarEmpresaProveedor($pkProveedor)
    {
        $con = new conectar();
        $db = $con->getDb();

        if (isset($_SESSION["Usuario"])) {
            $PKEmpresa = $_SESSION["IDEmpresa"];
        }

        $query = sprintf('call spc_ValidarEmpresaProveedor(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa, $pkProveedor));
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

    public function validarPermisosCat($pkPantalla)
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

    public function validarPermisosMar($pkPantalla)
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

    public function validarFolioEntradaOC($folio, $ordenCompra)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_ValidarFolio_EntradaOC(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($folio, $ordenCompra));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarFolioEntradaEDProvider($folio, $serie, $proveedor, $sucursal)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_ValidarFolio_EntradaDirecta_Provider(?,?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($folio, $serie, $proveedor, $sucursal));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarFolioEntradaEDProviderEdit($folio, $serie, $referencia)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('SELECT ieps.sucursal_id, ieps.proveedor_id, ieps.numero_documento 
                            from inventario_entrada_por_sucursales ieps 
                                inner join sucursales s on ieps.sucursal_id = s.id
                        where ieps.folio_entrada = ? and s.empresa_id = ?');
        $stmt = $db->prepare($query);
        $stmt->execute(array($referencia, $PKEmpresa));
        $array = $stmt->fetchAll();

        $proveedor = $array[0]['proveedor_id'];
        $sucursal = $array[0]['sucursal_id'];
        $numDoc = $array[0]['numero_documento'];

        if ($numDoc === ($folio . ' / ' . $serie)) {
            return array(array('Folio' => $folio, 'existe' => "0"));
        }

        $query = sprintf('call spc_ValidarFolio_EntradaDirecta_Provider(?,?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($folio, $serie, $proveedor, $sucursal));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        $db = '';
        $stmt = '';

        return $array;
    }

    public function validarSerieEntradaOC($serie, $ordenCompra)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_ValidarSerie_EntradaOC(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($serie, $ordenCompra));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarSerieEntradaEDProvider($serie, $proveedor, $sucursal)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_ValidarSerie_EntradaDirecta_Provider(?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($serie, $proveedor, $sucursal));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarSalidaCantidadModalTemp($serieLote, $cantidad, $ordenPedido, $pkProducto)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_ValidarSalida_CantidadModalTemp(?,?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($serieLote, $cantidad, $ordenPedido, $pkProducto));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarSalidaCantidadModalTempEdicion($serieLote, $cantidad, $folioSalida, $pkProducto)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_ValidarSalida_CantidadModalTempEdicion(?,?,?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($serieLote, $cantidad, $folioSalida, $pkProducto, $PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarSalidaCantidadDevolucionTemp($idSalidaTemp, $cantidad, $idCuentaPorPagar, $pkProducto)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_ValidarSalida_CantidadDevolucionTemp(?,?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($idSalidaTemp, $cantidad, $idCuentaPorPagar, $pkProducto));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarSalidaCantidadDevolucionTempEdicion($idSalidaTemp, $cantidad, $idCuentaPorPagar, $pkProducto)
    {
        $con = new conectar();
        $db = $con->getDb();

        if (isset($_SESSION["IDEmpresa"])) {
            $PKEmpresa = $_SESSION["IDEmpresa"];
        } else {
            $PKEmpresa = '0';
        }

        if (isset($_SESSION["PKUsuario"])) {
            $PKuser = $_SESSION["PKUsuario"];
        } else {
            $PKuser = '0';
        }

        $query = sprintf('call spc_ValidarSalida_CantidadDevolucionTempEdicion(?,?,?,?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($idSalidaTemp, $cantidad, $idCuentaPorPagar, $pkProducto, $PKEmpresa, $PKuser));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarCantidadProdEntradaOC($idEntradaTemp, $cantidad)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_ValidarCantidad_EntradaTemp(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($idEntradaTemp, $cantidad));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarCantidadProdEntradaOCEdit($idEntradaTemp, $cantidad, $folioEntrada)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_ValidarCantidad_EntradaTempEdit(?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($idEntradaTemp, $cantidad, $folioEntrada));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarCantidadProdEntradaTraspaso($idEntradaTemp, $cantidad)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_ValidarCantidad_Entrada_Traspaso_Temp(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($idEntradaTemp, $cantidad));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarCantidadProdEntradaTraspasoEdit($idEntradaTemp, $cantidad, $folioEntrada)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_ValidarCantidad_Entrada_Traspaso_TempEdit(?,?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($idEntradaTemp, $cantidad, $folioEntrada, $PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarLoteProdEntradaOC($idEntradaTemp, $lote)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        $query = sprintf('call spc_ValidarLote_EntradaTemp(?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($idEntradaTemp, $lote, $PKuser));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarLoteProdEntradaED($idEntradaTemp, $lote)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        $query = sprintf('call spc_ValidarLote_EntradaEDTemp(?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($idEntradaTemp, $lote, $PKuser));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarSerieProdEntradaOC($idEntradaTemp, $serie)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        $query = sprintf('call spc_ValidarSerie_EntradaTemp(?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($idEntradaTemp, $serie, $PKuser));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarSerieProdEntradaED($idEntradaTemp, $serie)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        $query = sprintf('call spc_ValidarSerie_EntradaEDTemp(?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($idEntradaTemp, $serie, $PKuser));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarCaducidadProdEntradaOC($idEntradaTemp, $caducidad, $loteSerie)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        $query = sprintf('call spc_ValidarCaducidad_EntradaTemp(?,?,?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($idEntradaTemp, $caducidad, $PKuser, $loteSerie));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarNoSalidas($folioSalida)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        $query = sprintf('call spc_ValidarNOCantidadSalida(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($folioSalida, $PKuser));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    //END JAVIER RAMIREZ
    //JAVIER RAMIREZ / OMAR GARCÍA
    public function getTypeEntries()
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $query = sprintf('call spc_Combo_TipoEntrada()');
            $stmt = $db->prepare($query);
            $stmt->execute();
            $array = $stmt->fetchAll(PDO::FETCH_OBJ);

            return $array;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }
        $stmt = null;
        $db = null;
    }

    public function getTypeExits()
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $query = sprintf('call spc_Combo_TipoSalida()');
            $stmt = $db->prepare($query);
            $stmt->execute();
            $array = $stmt->fetchAll(PDO::FETCH_OBJ);

            return $array;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }
        $stmt = null;
        $db = null;
    }
    //END JAVIER RAMIREZ / OMAR GARCÍA
    public function getIdEntries()
    {
        $con = new conectar();
        $db = $con->getDb();

        $stmt = $db->prepare("SELECT PKEntradaInventario FROM entradas_inventarios ORDER BY PKEntradaInventario LIMIT 1");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getUsers()
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $query = sprintf('SELECT usuarios.PKUsuario, empleados.Nombres, empleados.PrimerApellido, empleados.SegundoApellido FROM prueba_rh.usuarios AS usuarios
                          LEFT JOIN prueba_rh.empleados AS empleados ON usuarios.FKEmpleado = empleados.PKEmpleado');
            $stmt = $db->prepare($query);
            $stmt->execute();
            $array = $stmt->fetchAll(PDO::FETCH_OBJ);

            return $array;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }
        $stmt = null;
        $db = null;
    }

    public function getProductsEntries()
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $query = sprintf('SELECT PKProducto,Codigo FROM productos');
            $stmt = $db->prepare($query);
            $stmt->execute();
            $array = $stmt->fetchAll(PDO::FETCH_OBJ);

            return $array;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }
        $stmt = null;
        $db = null;
    }

    public function getEntriesSelect()
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $query = sprintf('SELECT PKEntradaInventario FROM entradas_inventarios');
            $stmt = $db->prepare($query);
            $stmt->execute();
            $array = $stmt->fetchAll(PDO::FETCH_OBJ);

            return $array;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }
        $stmt = null;
        $db = null;
    }

    public function getWarehouses()
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $query = sprintf('SELECT PKAlmacen,Almacen FROM almacenes');
            $stmt = $db->prepare($query);
            $stmt->execute();
            $array = $stmt->fetchAll(PDO::FETCH_OBJ);

            return $array;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }
        $stmt = null;
        $db = null;
    }

    public function getOutputsSelect()
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $query = sprintf('SELECT PKSalidaInventario FROM salidas_inventarios');
            $stmt = $db->prepare($query);
            $stmt->execute();
            $array = $stmt->fetchAll(PDO::FETCH_OBJ);

            return $array;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }
        $stmt = null;
        $db = null;
    }

    public function getProducts()
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $query = sprintf('SELECT PKProducto,Producto FROM productos');
            $stmt = $db->prepare($query);
            $stmt->execute();
            $array = $stmt->fetchAll(PDO::FETCH_OBJ);

            return $array;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }
        $stmt = null;
        $db = null;
    }

    public function getProductsOther($value, $value1)
    {
        $con = new conectar();
        $db = $con->getDb();

        if ($value1 === "2") {
            try {
                $query = sprintf('SELECT PKProducto,Producto FROM productos
                            INNER JOIN lista_salida_inventarios ON productos.PKProducto = lista_salida_inventarios.FKProducto
                            WHERE lista_salida_inventarios.FKSalidaInventario = ?');
                $stmt = $db->prepare($query);
                $stmt->execute(array($value));
                $array = $stmt->fetchAll(PDO::FETCH_OBJ);

                return $array;
            } catch (PDOException $e) {
                return "Error en Consulta: " . $e->getMessage();
            }
        } else if ($value1 === "3") {
            try {
                $query = sprintf('SELECT PKProducto,Producto FROM productos
                            INNER JOIN lista_orden_fabricacion ON productos.PKProducto = lista_orden_fabricacion.FKProducto
                            WHERE lista_orden_fabricacion.FKOrdenFabricacion = ?');
                $stmt = $db->prepare($query);
                $stmt->execute(array($value));
                $array = $stmt->fetchAll(PDO::FETCH_OBJ);

                return $array;
            } catch (PDOException $e) {
                return "Error en Consulta: " . $e->getMessage();
            }
        }
        $stmt = null;
        $db = null;
    }

    public function getOutputsTable()
    {
        $con = new conectar();
        $db = $con->getDb();

        $table = "";
        $no = 1;
        $stmt = $db->prepare('SELECT * FROM salidas_inventarios
                              LEFT JOIN tipos_salidas_inventarios ON salidas_inventarios.FKTipoSalida = tipos_salidas_inventarios.PKTipoSalida
                              LEFT JOIN almacenes ON salidas_inventarios.FKAlmacen = almacenes.PKAlmacen
                              LEFT JOIN prueba_rh.usuarios AS usuarios ON salidas_inventarios.FKUsuario = usuarios.PKUsuario
                              LEFT JOIN prueba_rh.empleados AS empleados ON usuarios.FKEmpleado = empleados.PKEmpleado');
        $stmt->execute();
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $usuario = $r['Nombres'] . " " . $r['PrimerApellido'];
            $fechaHora = date("d/m/Y H:i:s", strtotime($r['Fecha']));

            $table .= '{"Folio":"' . $r['PKSalidaInventario'] . '","Fecha":"' . $fechaHora . '","Tipo de salida":"' . $r['TipoSalida'] . '","Usuario":"' . $usuario . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getProvider()
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $query = sprintf('SELECT PKProveedor,NombreComercial FROM proveedores');
            $stmt = $db->prepare($query);
            $stmt->execute();
            $array = $stmt->fetchAll(PDO::FETCH_OBJ);

            return $array;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }
        $stmt = null;
        $db = null;
    }

    public function getClientsSelect()
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $query = sprintf('SELECT PKCliente,NombreComercial FROM clientes');
            $stmt = $db->prepare($query);
            $stmt->execute();
            $array = $stmt->fetchAll(PDO::FETCH_OBJ);

            return $array;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }
        $stmt = null;
        $db = null;
    }

    public function getOutputClient($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $query = sprintf('SELECT PKSalidaInventario FROM salidas_inventarios
                          INNER JOIN ventas_salidas_clientes ON salidas_inventarios.PKSalidaInventario = ventas_salidas_clientes.FKSalidaInventario
                          WHERE ventas_salidas_clientes.FKCliente = ?');
            $stmt = $db->prepare($query);
            $stmt->execute(array($value));
            $array = $stmt->fetchAll(PDO::FETCH_OBJ);

            return $array;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }
        $stmt = null;
        $db = null;
    }

    public function getManufacturingInput()
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $query = sprintf('SELECT PKOrdenFabricacion FROM ordenes_fabricacion');
            $stmt = $db->prepare($query);
            $stmt->execute();
            $array = $stmt->fetchAll(PDO::FETCH_OBJ);

            return $array;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }
        $stmt = null;
        $db = null;
    }

    public function getTypeEntry($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {

            $query = sprintf('SELECT FKTipoEntrada FROM entradas_inventarios
                          WHERE PKEntradaInventario = ?');
            $stmt = $db->prepare($query);
            $stmt->execute(array($value));
            $tipoEntrada = $stmt->fetch()['FKTipoEntrada'];
            //return $tipoEntrada;
            if ($tipoEntrada === 1) {

                $query = sprintf('SELECT entradas_inventarios.FKTipoEntrada,compra_entrada_proveedor.FKProveedor,entradas_inventarios.FKAlmacen,entradas_documentos_notas.Notas,entradas_documentos_notas.Documento FROM entradas_inventarios
                            INNER JOIN compra_entrada_proveedor ON entradas_inventarios.PKEntradaInventario = compra_entrada_proveedor.FKEntradaInventario
                            LEFT JOIN entradas_documentos_notas ON entradas_inventarios.PKEntradaInventario = entradas_documentos_notas.FKEntradaInventario
                            WHERE entradas_inventarios.PKEntradaInventario = ?');

                $stmt = $db->prepare($query);
                $stmt->execute(array($value));
                $array = $stmt->fetchAll(PDO::FETCH_OBJ);
            }
            if ($tipoEntrada === 2) {
                $query = sprintf('SELECT entradas_inventarios.FKTipoEntrada,devolucion_entrada_cliente.FKCliente,entradas_inventarios.FKAlmacen,entradas_tickets_notas.Tickets,entradas_tickets_notas.Documento,entradas_tickets_notas.Notas FROM entradas_inventarios
                            INNER JOIN devolucion_entrada_cliente ON entradas_inventarios.PKEntradaInventario = devolucion_entrada_cliente.FKEntradaInventario
                            LEFT JOIN entradas_tickets_notas ON entradas_inventarios.PKEntradaInventario = entradas_tickets_notas.FKEntradaInventario
                            WHERE entradas_inventarios.PKEntradaInventario = ?');

                $stmt = $db->prepare($query);
                $stmt->execute(array($value));
                $array = $stmt->fetchAll(PDO::FETCH_OBJ);
            }

            if ($tipoEntrada === 3) {
                $query = sprintf('SELECT entradas_inventarios.FKTipoEntrada,fabricacion_entrada_orden.FKOrdenFabricacion,entradas_inventarios.FKAlmacen FROM entradas_inventarios
                            INNER JOIN fabricacion_entrada_orden ON entradas_inventarios.PKEntradaInventario = fabricacion_entrada_orden.FKEntradaInventario
                            WHERE entradas_inventarios.PKEntradaInventario = ?');

                $stmt = $db->prepare($query);
                $stmt->execute(array($value));
                $array = $stmt->fetchAll(PDO::FETCH_OBJ);
            }

            if ($tipoEntrada === 5) {
                $query = sprintf('SELECT entradas_inventarios.FKTipoEntrada,traspaso_entrada_salida.FKSalidaInventario,entradas_inventarios.FKAlmacen FROM entradas_inventarios
                            INNER JOIN traspaso_entrada_salida ON entradas_inventarios.PKEntradaInventario = traspaso_entrada_salida.FKEntradaInventario
                            WHERE entradas_inventarios.PKEntradaInventario = ?');

                $stmt = $db->prepare($query);
                $stmt->execute(array($value));
                $array = $stmt->fetchAll(PDO::FETCH_OBJ);
            }

            return $array;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }
        $stmt = null;
        $db = null;
    }

    public function getProductsEntriesTable($value)
    {
        $con = new conectar();
        $db = $con->getDb();
        $table = "";
        $no = 1;
        $noSerie = "";
        $lote = "";
        $caducidad = "";
        try {

            $query = sprintf('SELECT Codigo,Producto,Lote,NumeroSerie AS Noserie,Caducidad,Cantidad FROM lista_entrada_inventarios
                          INNER JOIN productos ON lista_entrada_inventarios.FKProducto = productos.PKProducto
                          INNER JOIN entradas_inventarios ON lista_entrada_inventarios.FKEntradaInventario = entradas_inventarios.PKEntradaInventario
                          WHERE FKEntradaInventario = ?');
            $stmt = $db->prepare($query);
            $stmt->execute(array($value));
            $array = $stmt->fetchAll();

            foreach ($array as $r) {
                if ($r['Noserie'] !== "null") {
                    $noSerie = $r['Noserie'];
                } else {
                    $noSerie = "";
                }

                if ($r['Lote'] !== "null") {
                    $lote = $r['Lote'];
                } else {
                    $lote = "";
                }

                $caducidad = date("d/m/Y", strtotime($r['Caducidad']));

                //<span class=\"textTable\">'+$('#cmbProducto').val()+'</span>
                $table .= '{"No":"<span class=\"textTable\">' . $no . '</span>","Codigo":"<span class=\"textTable\">' . $r['Codigo'] . '</span>","Producto":"<span class=\"textTable\">' . $r['Producto'] . '</span>","Lote":"<span class=\"textTable\">' . $lote . '</span>","Noserie":"<span class=\"textTable\">' . $noSerie . '</span>","Caducidad":"<span class=\"textTable\">' . $caducidad . '</span>","Cantidad":"<span class=\"textTable\">' . $r['Cantidad'] . '</span>"},';
                $no++;
            }

            $table = substr($table, 0, strlen($table) - 1);
            return '{"data":[' . $table . ']}';
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }
        $stmt = null;
        $db = null;
    }

    /*  OMAR GARCIA / JAVIER RAMIREZ */
    public function loadCmbProduct($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf("call spc_Combo_Productos_OrdenCompra(?,?)");
        $stmt = $db->prepare($query);
        $stmt->execute(array($value, $PKEmpresa));

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function loadCmbAllProduct($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf("call spc_Combo_Productos_OrdenCompraAll(?,?)");

        $stmt = $db->prepare($query);
        $stmt->execute(array($value, $PKEmpresa));

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function loadCmbProvider()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Combo_Proveedores_OrdenCompra(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function loadCmbLocation()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Combo_Sucursales_OrdenCompra(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function loadPriceProvider($value, $value1)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf("call spc_Info_OrdenCompra_PrecioProductoProveedor(?,?)");
        $stmt = $db->prepare($query);
        $stmt->execute(array($value, $value1));

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /* END OMAR GARCIA / JAVIER RAMIREZ */
    /* JAVIER RAMIREZ */
    public function getClaveReferencia()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Info_Producto_ReferenciaClaveInterna(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));
        $ProductoReferencia = $stmt->fetch();
        $cantidadRegistros = $stmt->rowCount();
        if ($cantidadRegistros > 0) {
            $numReferencia = $ProductoReferencia['PKProducto'];
            $Referencia = "-" . str_pad($numReferencia, 6, "0", STR_PAD_LEFT);
        } else {
            $Referencia = "-000001";
        }
        return $Referencia;
    }

    public function getClaveReferenciaEdit($pkProducto)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Info_Producto_ReferenciaClaveInternaEdit(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa, $pkProducto));
        $ProductoReferencia = $stmt->fetch();
        $cantidadRegistros = $stmt->rowCount();
        if ($cantidadRegistros > 0) {
            $numReferencia = $ProductoReferencia['PKProducto'];
            $Referencia = "-" . str_pad($numReferencia, 6, "0", STR_PAD_LEFT);
        } else {
            $Referencia = "-000001";
        }
        return $Referencia;
    }

    public function getReferencia()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Info_OrdenCompra_Referencia(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));
        $OCReferencia = $stmt->fetch();

        return $OCReferencia['PKOrdenCompra'];
    }

    public function getFolioEntradaED()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Info_EntradaED_Folio(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));
        $FolioEntrada = $stmt->fetch();

        return $FolioEntrada['FolioEntrada'];
    }

    public function getFechaEmision()
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Info_OrdenCompra_FechaEmision()');
        $stmt = $db->prepare($query);
        $stmt->execute();
        $FechaEmision = $stmt->fetch();
        $cantidadRegistros = $stmt->rowCount();
        if ($cantidadRegistros > 0) {
            $Fecha = $FechaEmision['FechaEmision'];
        } else {
            $Fecha = "Error al obtener la fecha";
        }
        return $Fecha;
    }

    public function getFechaEntegraMin()
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Info_OrdenCompra_FechaEntregaMin()');
        $stmt = $db->prepare($query);
        $stmt->execute();
        $FechaEntregaMin = $stmt->fetch();
        $cantidadRegistros = $stmt->rowCount();
        if ($cantidadRegistros > 0) {
            $Fecha = $FechaEntregaMin['FechaEntregaMin'];
        } else {
            $Fecha = "Error al obtener la fecha";
        }
        return $Fecha;
    }

    public function getImpuestos($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf("call spc_Datos_OrdenesCompra_Impuestos(?)");
        $stmt = $db->prepare($query);

        $stmt->execute(array($value));

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    /* JAVIER RAMIREZ */

    public function getDatosProducto($PKProducto)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf("SELECT * FROM productos WHERE PKProducto=?");
        $stmt = $db->prepare($query);

        $stmt->execute(array($PKProducto));

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /////////////////////////COLUMNAS AJUSTABLES PROVEEDORES//////////////////////////////
    public function listaColumnas()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spc_Columnas_Proveedores(?)');
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

                $query = sprintf("call spc_Tabla_Columnas_Proveedores_Consulta(?,?)");
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
            $query = sprintf("call spc_Columnas_Proveedores_Ordenadas(?)");
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

        $query = sprintf("call spc_Columnas_Proveedores_Ids(?)");
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

            $query = sprintf('call spc_Tabla_Columnas_Proveedores_SetOrden_Consulta(?,?,?,?,?)');
            //echo $query;
            $stmt = $db->prepare($query);
            $stmt->execute(array(1, $sort, $indice, $PKEmpresa, $search));
            $ordenProveedores = $stmt->fetchAll(PDO::FETCH_OBJ);

            return $ordenProveedores;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }
    }

    /////////////////////////COLUMNAS AJUSTABLES PRODUCTOS//////////////////////////////
    public function listaColumnasProd()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spc_Columnas_Productos(?)');
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

    public function infoColumnasProd()
    {
        $PKEmpresa = $_SESSION["IDEmpresa"];

        try {

            $con = new conectar();
            $db = $con->getDb();

            $query = sprintf("call spc_Tabla_Columnas_Productos_Consulta(?,?)");
            $stmt = $db->prepare($query);
            $stmt->execute(array(1, $PKEmpresa));
            $lista = $stmt->fetchAll(PDO::FETCH_OBJ);

            return $lista;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    //Lista el orden de las columnas del empleado
    public function ordenColumnasProd()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf("call spc_Columnas_Productos_Ordenadas(?)");
            $stmt = $db->prepare($query);
            $stmt->execute(array($PKuser));
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function obtenerIdsProd()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf("call spc_Columnas_Productos_Ids(?)");
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));

        return $id = $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function ordenDatosProd($sort, $indice, $search)
    {

        $PKEmpresa = $_SESSION["IDEmpresa"];

        try {
            $con = new conectar();
            $db = $con->getDb();

            $query = sprintf('call spc_Tabla_Columnas_Productos_SetOrden_Consulta(?,?,?,?,?)');
            //echo $query;
            $stmt = $db->prepare($query);
            $stmt->execute(array(1, $sort, $indice, $PKEmpresa, $search));
            $ordenProveedores = $stmt->fetchAll(PDO::FETCH_OBJ);

            return $ordenProveedores;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }
    }

    function getExpenseCategory($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf("
                select 
                    cat.PKCategoria categoria_id,
                    subcat.PKSubcategoria subcategoria_id 
                from ordenes_compra oc
                inner join categoria_gastos cat on oc.categoria_id = cat.PKCategoria
                inner join subcategorias_gastos subcat on oc.subcategoria_id = subcat.PKSubcategoria
                where PKOrdenCompra = :id
                ");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":id",$value);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}

class data_order
{

    //JAVIER RAMIREZ
    /////////////////////////COLUMNAS AJUSTABLES PROVEEDORES//////////////////////////////
    public function columnOrder($array)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            for ($i = 0; $i < count($array); $i++) {
                if ($array[$i] != 1) {
                    $update = sprintf("call spu_Columnas_Proveedores_Orden(?,?,?)");
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
    /////////////////////////COLUMNAS AJUSTABLES PRODUCTOS//////////////////////////////
    public function columnOrderProd($array)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            for ($i = 0; $i < count($array); $i++) {
                if ($array[$i] != 1) {
                    $update = sprintf("call spu_Columnas_Productos_Orden(?,?,?)");
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
    /////////////////////////COLUMNAS AJUSTABLES PROVEEDORES//////////////////////////////
    public function buscarProveedor($inputValue, $array)
    {
        $data = [];
        $PKEmpresa = $_SESSION["IDEmpresa"];

        try {

            for ($i = 0; $i < count($array); $i++) {
                $con = new conectar();
                $db = $con->getDb();

                $query = sprintf("call spc_Tabla_Columnas_Proveedores_Search_Consulta(?,?,?)");
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
    /////////////////////////COLUMNAS AJUSTABLES PRODUCTO//////////////////////////////
    public function buscarProducto($inputValue)
    {
        $PKEmpresa = $_SESSION["IDEmpresa"];

        try {

            $con = new conectar();
            $db = $con->getDb();

            $query = sprintf("call spc_Tabla_Columnas_Productos_Search_Consulta(?,?,?)");
            $stmt = $db->prepare($query);
            $stmt->execute(array(0, $inputValue, $PKEmpresa));
            $lista = $stmt->fetchAll(PDO::FETCH_OBJ);

            return $lista;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }
    #END JAVIER RAMIREZ

    public function getCostoproducto($pkRegistro)
    {
        try {

            $con = new conectar();
            $db = $con->getDb();

            $query = sprintf('SELECT ce.CostoEspecial, ce.FKTipoMoneda, concat(p.ClaveInterna," - ", p.Nombre) as producto, ce.FKCliente, ce.FKProducto
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
    public function saveEmptyProductStock($PKDetalle, $PKSucursal, $PKProducto, $Clave, $Cantidad)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $query = sprintf('call spi_Detalle_Inventarios_Iniciales_PorSucursal_ProductoRepetido(?,?,?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKDetalle, $PKSucursal, $PKProducto, $Clave, $Cantidad));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveDuplicarEliminarProductoInventarioPeriodico($PKDetalle, $PKSucursal, $PKProducto, $Cantidad)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $query = sprintf('call spi_DuplicarEliminarProducto_InventarioPeriodico(?,?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $stmt->execute(array($PKDetalle, $PKSucursal, $PKProducto, $Cantidad));
            $respuesta = $stmt->fetch();

            return $respuesta;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveHeaderInitialStock($PKSucursal)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKUsuario = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spi_Inventarios_Iniciales_PorSucursal(?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKSucursal, $PKUsuario));

            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveDetailInitialStock($PKSucursal, $PKDetalle, $PKProducto, $Clave, $Lote, $Serie, $Caducidad, $Cantidad)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $query = sprintf('call spi_Detalle_Inventarios_Iniciales_PorSucursal(?,?,?,?,?,?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKDetalle, $PKSucursal, $PKProducto, $Clave, $Lote, $Serie, $Caducidad, $Cantidad));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveDetailInitialStock2($PKSucursal, $PKDetalle, $PKProducto, $Clave, $Entrada, $Tipo)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $query = sprintf('call spi_Detalle_Inventarios_Iniciales_PorSucursal2(?,?,?,?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKSucursal, $PKDetalle, $PKProducto, $Clave, $Entrada, $Tipo));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveInitialStock($PKSucursal)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $query = sprintf('call spd_Detalle_Inventario_Por_Sucursales(?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKSucursal));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveTempInitialStock($PKSucursal)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKUsuario = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spi_Temp_Detalle_Inventario_Por_Sucursales(?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKSucursal, $PKUsuario));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveAjustes($PKSucursal, $PKTipo)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKUsuario = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spi_Ajuste_Inventario_Por_Sucursal(?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKUsuario, $PKSucursal, $PKTipo));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveAjustar($PKAjuste, $PKProducto, $Existencia, $Cantidad, $Clave, $Lote, $Serie, $Caducidad, $Motivo, $Observaciones)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $query = sprintf('call spi_Ajuste_Detalle_Inventario_Por_Sucursal(?,?,?,?,?,?,?,?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKAjuste, $PKProducto, $Existencia, $Cantidad, $Clave, $Lote, $Serie, $Caducidad, $Motivo, $Observaciones));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveClavesKardexTemp($Clave)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKUsuario = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spi_Claves_Kardex_Temp(?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKUsuario, $Clave));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveCambios($PKSucursal, $PKTipo)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKUsuario = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spi_Cambio_Lote_Serie(?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKUsuario, $PKSucursal, $PKTipo));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveCambiar($PKCambio, $PKProducto, $Existencia, $Cantidad, $Clave, $LoteAntiguo, $SerieAntigua, $LoteNuevo, $SerieNueva, $Caducidad, $Observaciones)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $query = sprintf('call spi_Detalle_Cambio_Lote_Serie(?,?,?,?,?,?,?,?,?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKCambio, $PKProducto, $Existencia, $Cantidad, $Clave, $LoteAntiguo, $SerieAntigua, $LoteNuevo, $SerieNueva, $Caducidad, $Observaciones));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveDetailInvPerio($PKSucursal, $PKDetalle, $PKProducto, $Clave, $Entrada, $Tipo)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $query = sprintf('call spi_Detalle_Inventarios_Periodico(?,?,?,?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKSucursal, $PKDetalle, $PKProducto, $Clave, $Entrada, $Tipo));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveTypeEntries($data)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {

            $query = sprintf('INSERT INTO tipos_entradas_inventarios (TipoEntrada) VALUES (?)');
            $stmt = $db->prepare($query);
            return $stmt->execute(array($data));
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }
        $stmt = null;
        $db = null;
    }

    public function saveEntry($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $usuario = $value['usuario'][0];
        $fecha = $value['fecha'][0];
        $tipoEntrada = $value['tipoEntrada'][0];
        $almacen = $value['almacen'][0];

        try {
            $query = sprintf('SELECT PKUsuario FROM prueba_rh.usuarios WHERE Usuario = ?');
            $stmt = $stmt = $db->prepare($query);
            $stmt->execute(array($usuario));
            $PKUsuario = $stmt->fetch()['PKUsuario'];

            $query = sprintf('INSERT INTO entradas_inventarios (FKUsuario,Fecha,FKAlmacen,FKTipoEntrada) VALUES (?,?,?,?)');
            $stmt = $db->prepare($query);
            $stmt->execute(array($PKUsuario, $fecha, $almacen, $tipoEntrada));
            $idLast = $db->lastInsertId();

            if ($value['tipoEntrada'][0] === "1") {
                $proveedor = $value['proveedor'][0];
                $referencia = $value['referencia'][0];
                $notas = $value['notas'][0];

                $query = sprintf('INSERT INTO compra_entrada_proveedor (FKEntradaInventario,FKProveedor) VALUES (?,?)');
                $stmt = $db->prepare($query);
                $stmt->execute(array($idLast, $proveedor));

                $query = sprintf('INSERT INTO entradas_documentos_notas (FKEntradaInventario,Documento,Notas) VALUES (?,?,?)');
                $stmt = $db->prepare($query);
                $stmt->execute(array($idLast, $referencia, $notas));
            } else if ($value['tipoEntrada'][0] === "2") {
                $cliente = $value['cliente'][0];
                $documento = $value['documento'][0];
                $ticket = $value['ticket'][0];
                $notas = $value['notas'][0];

                $query = sprintf('INSERT INTO devolucion_entrada_cliente (FKEntradaInventario,FKCliente) VALUES (?,?)');
                $stmt = $db->prepare($query);
                $stmt->execute(array($idLast, $cliente));

                $query = sprintf('INSERT INTO entradas_tickets_notas (FKEntradaInventario,Documento,Tickets,Notas) VALUES (?,?,?,?)');
                $stmt = $db->prepare($query);
                $stmt->execute(array($idLast, $documento, $ticket, $notas));
            } else if ($value['tipoEntrada'][0] === "3") {
                $ordenFabricacion = $value['ordenFabricacion'][0];

                $query = sprintf('INSERT INTO fabricacion_entrada_orden (FKEntradaInventario,FKOrdenFabricacion) VALUES (?,?)');
                $stmt = $db->prepare($query);
                $stmt->execute(array($idLast, $ordenFabricacion));
            } else if ($value['tipoEntrada'][0] === "5") {
                $salida = $value['salida'][0];

                $query = sprintf('INSERT INTO traspaso_entrada_salida (FKEntradaInventario,FKSalidaInventario) VALUES (?,?)');
                $stmt = $db->prepare($query);
                $stmt->execute(array($idLast, $salida));
            }

            $length = count($value['productos'][0]['Codigo'][0]);
            for ($i = 0; $i < $length; $i++) {
                $date = $value['productos'][0]['Caducidad'][0][$i];
                $aux = explode("/", $date);
                $caducidad = date("Y-m-d", strtotime($aux[2] . "-" . $aux[1] . "-" . $aux[0]));
                $query = sprintf('INSERT INTO lista_entrada_inventarios (FKEntradaInventario,FKProducto,Cantidad,Lote,NumeroSerie,Caducidad) VALUES (?,?,?,?,?,?)');
                $stmt = $db->prepare($query);
                $stmt->execute(array($idLast, $value['productos'][0]['Codigo'][0][$i], $value['productos'][0]['Cantidad'][0][$i], $value['productos'][0]['Lote'][0][$i], $value['productos'][0]['Noserie'][0][$i], $caducidad));
            }
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    //JAVIER RAMIREZ
    public function saveDatosProducto($value, $foto, $pkUsuario, $isCompra, $isVenta, $isFabricacion, $isGastoFijo, $costoCompra, $monedaCompra, $costoVenta, $monedaVenta, $costoFabricacion, $monedaFabricacion, $costoGastoFijo, $monedaGastoFijo, $isSerie, $isLote, $isCaducidad, $unidadMedida)
    {
        $con = new conectar();
        $db = $con->getDb();

        $fkEstatusGeneral = $value['estatus'];
        $nombre = $value['nombre'];
        $claveInterna = $value['claveInterna'];
        $codigoBarras = $value['codigoBarra'];
        $fkCategoriaProducto = $value['categoria'];
        $fkMarcaProducto = $value['marca'];
        $fkTipoProducto = $value['tipo'];
        $descripcion = $value['descripcion'];

        $producto_api[] = [
            'description' => $nombre,
            'sku' => $claveInterna,
            'price' => $costoVenta,
            'tax_included' => false
        ];

        if ($foto == 1) {
            $imagen = 'ruta';
        } else {
            $imagen = 'vacia';
        }

        $PKProducto = "0";
        $PKEmpresa = $_SESSION["IDEmpresa"];

        if ($costoVenta == '' || $costoVenta == null) {
            $costoVenta = 0;
        }

        if ($costoCompra == '' || $costoCompra == null) {
            $costoCompra = 0;
        }

        if ($costoFabricacion == '' || $costoFabricacion == null) {
            $costoFabricacion = 0;
        }


        try {
            $query = sprintf('call spi_Prod_AgregarGeneral (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($nombre, strtoupper($claveInterna), $codigoBarras, $fkCategoriaProducto, $fkMarcaProducto, $descripcion, $fkTipoProducto, $fkEstatusGeneral, $imagen, $pkUsuario, $isCompra, $isVenta, $isFabricacion, $isGastoFijo, $PKProducto, $costoVenta, $monedaVenta, $costoCompra, $monedaCompra, $costoFabricacion, $monedaFabricacion, $costoGastoFijo, $monedaGastoFijo, $PKEmpresa, $isSerie, $isLote, $isCaducidad, $unidadMedida));
            $PKProducto = $stmt->fetch()['0'];


            $data[0] = ['status' => $status, 'id' => $PKProducto];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveDatosProductoCompTemp($pkProducto, $cantidad, $pkUsuario, $PKCompuestoTemp, $costo, $moneda)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $query = sprintf('call spi_Prod_AgregarProdCompuestoTemp (?,?,?,?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($pkUsuario, $pkProducto, $cantidad, $PKCompuestoTemp, $costo, $moneda));

            $data[0] = ['status' => $status];



            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveDatosImpuesto($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $impuestos_api = [];

        $PKProducto = $value[0]['datos'];
        $fkClaveSAT = $value[1]['datos'];
        //$fkClaveSATUnidad = $value[3]['datos'];
        $impuesto = $value[3]['datos'];
        $tipoTasa = $value[5]['datos'];
        $tasa = $value[6]['datos'];
        if ($tasa == null || $tasa == '') {
            $tasa = 0;
        }
        $PKuser = $_SESSION["PKUsuario"];



        $query = sprintf("SELECT Clave FROM claves_sat WHERE PKClaveSAT = :id");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":id", $fkClaveSAT);
        $stmt->execute();
        $claveSAT_api = $stmt->fetch()['Clave'];

        $query = sprintf("SELECT csu.Clave, csu.Descripcion, csu.PKClaveSATUnidad FROM claves_sat_unidades as csu inner join info_fiscal_productos as f on csu.PKClaveSATUnidad = f.FKClaveSATUnidad where f.FKProducto = :id");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":id", $PKProducto);
        $stmt->execute();
        $claveSATUnidad_api = $stmt->fetchAll(PDO::FETCH_OBJ);

        $producto_api[] = [
            "product_key" => $claveSAT_api,
            "unit_name" => $claveSATUnidad_api[0]->Descripcion,
            "unit_key" => $claveSATUnidad_api[0]->Clave
        ];

        try {
            $query = sprintf('call spi_Prod_AgregarImpuesto (?,?,?,?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKProducto, $impuesto, $tipoTasa, $tasa, $fkClaveSAT, $claveSATUnidad_api[0]->PKClaveSATUnidad, $PKuser));

            $data[0] = ['status' => $status];



            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveDatosFiscales($fkClave, /* $fkUnidad, */ $pkProducto)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];





        try {
            $query = sprintf('call spi_Prod_AgregarImpuestoGral (?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($pkProducto, $fkClave, 0,/* $fkUnidad, */ $PKuser));
            $idInsert = $db->lastInsertId();

            $data[0] = ['status' => $status];

            $query = sprintf("SELECT i.Nombre,tii.TipoImporteImpuesto,ti.TipoImpuesto,ip.Tasa FROM impuestos_productos ip 
                      INNER JOIN impuesto i ON ip.FKImpuesto = i.PKImpuesto
                      INNER JOIN tipos_impuestos ti ON i.FKTipoImpuesto = ti.PKTipoImpuesto
                      INNER JOIN tipos_importe_impuestos tii ON i.FKTipoImporte = tii.PKTipoImporte
                      INNER JOIN info_fiscal_productos ifp ON ip.FKInfoFiscalProducto = ifp.PKInfoFiscalProducto
                      WHERE ifp.PKInfoFiscalProducto = :id");
            $stmt = $db->prepare($query);
            $stmt->bindValue(":id", $idInsert);

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveDatosInventarioProducto($PKProducto, $arrayData)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $db->beginTransaction();

            foreach ($arrayData as $existencia) {
                $query = sprintf('INSERT INTO existencia_por_productos (existencia_minima, existencia_maxima, punto_reorden, numero_lote, numero_serie, caducidad, existencia, sucursal_id, producto_id, clave_producto) SELECT 0, 0, 0, ?, ?, ?, ?, ?, PKProducto, ClaveInterna FROM productos WHERE PKProducto = ?');
                $stmt = $stmt = $db->prepare($query);
                $status = $stmt->execute(array($existencia['lote'], '', $existencia['caducidad'], $existencia['cantidad'], $existencia['sucursal'], $PKProducto));
            }

            $data[0] = ['status' => $status];
            $db->commit();
            return $data;
        } catch (PDOException $ex) {
            $db->rollBack();
            echo "fallo";
            echo $ex->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveDatosInventario($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $fkProducto = $value[0]['datos'];
        $fkTipoOrdenInventario = $value[1]['datos'];
        $stockExistencia = $value[2]['datos'];
        $stockMinimo = $value[3]['datos'];
        $stockMaximo = $value[4]['datos'];
        $puntoReorden = $value[5]['datos'];
        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spi_Prod_AgregarInventario (?,?,?,?,?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($fkProducto, $fkTipoOrdenInventario, $stockExistencia, $stockMinimo, $stockMaximo, $puntoReorden, $PKuser));

            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveDatosTipoProducto($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKProducto = $value[0]['datos'];
        $accion = $value[1]['datos'];
        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spi_Prod_AgregarAcciones(?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($accion, $PKProducto, $PKuser));

            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveDatosTipoProductoTemp($pkAccion, $pkUsuario)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $query = sprintf('call spi_Prod_AgregarAcciones_Temp(?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($pkAccion, $pkUsuario));

            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveCategoria($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        try {
            $query = sprintf('call spi_Categoria_Agregar(?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($value, $PKEmpresa));

            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveMarca($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        try {
            $query = sprintf('call spi_Marca_Agregar(?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($value, $PKEmpresa));

            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveTipoProducto($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $query = sprintf('call spi_TipoProducto_Agregar(?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($value));

            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }
        $stmt = null;
        $db = null;
    }
    public function saveTipoOrdenInventario($value, $value2)
    {
        $con = new conectar();
        $db = $con->getDb();
        try {
            $query = sprintf('call spi_TipoOrdenInventario_Agregar(?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($value, $value2));

            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveDatosProveedor($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKProducto = $value[0]['datos'];
        $pkProveedor = $value[2]['datos'];
        $nombreProd = $value[3]['datos'];
        $clave = $value[4]['datos'];

        if ($value[5]['datos'] == '' || $value[5]['datos'] == null) {
            $precio = 0;
        } else {
            $precio = $value[5]['datos'];
        }

        $moneda = $value[6]['datos'];

        if ($value[7]['datos'] == '' || $value[7]['datos'] == null) {
            $cantidadMin = 0;
        } else {
            $cantidadMin = $value[7]['datos'];
        }

        if ($value[8]['datos'] == '' || $value[8]['datos'] == null) {
            $diasEntrega = 0;
        } else {
            $diasEntrega = $value[8]['datos'];
        }

        $unidadMedida = $value[9]['datos'];
        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spi_Prod_AgregarProveedor(?,?,?,?,?,?,?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKProducto, $pkProveedor, $nombreProd, $clave, $precio, $moneda, $cantidadMin, $diasEntrega, $unidadMedida, $PKuser));

            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveDatosProveedor2($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $pkProveedor = $value[0]['datos'];
        $PKProducto = $value[1]['datos'];
        $nombreProd = $value[2]['datos'];
        $clave = $value[3]['datos'];

        if ($value[4]['datos'] == '' || $value[4]['datos'] == null) {
            $precio = 0;
        } else {
            $precio = $value[4]['datos'];
        }


        $moneda = $value[5]['datos'];

        if ($value[6]['datos'] == '' || $value[6]['datos'] == null) {
            $cantidadMin = 0;
        } else {
            $cantidadMin = $value[6]['datos'];
        }


        if ($value[7]['datos'] == '' || $value[7]['datos'] == null) {
            $diasEntrega = 0;
        } else {
            $diasEntrega = $value[7]['datos'];
        }

        $unidadMedida = $value[8]['datos'];
        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spi_Prod_AgregarProveedor(?,?,?,?,?,?,?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKProducto, $pkProveedor, $nombreProd, $clave, $precio, $moneda, $cantidadMin, $diasEntrega, $unidadMedida, $PKuser));

            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveClienteProducto($cliente, $costo, $moneda, $producto, $costoGral, $monedaGral)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spi_Prod_AgregarCliente(?,?,?,?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($cliente, $costo, $moneda, $producto, $costoGral, $monedaGral, $PKuser));

            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveDatosVenta($costoGral, $monedaGral, $producto)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spi_Prod_AgregarVenta(?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($costoGral, $monedaGral, $producto, $PKuser));

            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    /* PROVEEDORES */
    public function saveMedioContactoProveedor($value)
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

    public function saveDatosProveedorTable($array, $nombreComercial, $vendedor, $montoCredito, $diasCredito, $telefono, $email, $estatus, $tipoPersona, $email2, $movil, $giro)
    {
        $con = new conectar();
        $db = $con->getDb();

        $pkProveedor = '0';
        $PKuser = $_SESSION["PKUsuario"];
        $PKEmpresa = $_SESSION["IDEmpresa"];

        try {
            $query = sprintf('call spi_Proveedor_AgregarGeneral (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($nombreComercial, '0', $telefono, $email, $montoCredito, $diasCredito, $estatus, $vendedor, $pkProveedor, $PKuser, $PKEmpresa, $tipoPersona, $email2, $movil, $giro));
            $PKProveedor = $stmt->fetch()['0'];

            $data[0] = ['status' => $status, 'id' => $PKProveedor];

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

    public function saveRazonSocialProveedor($razonSocial, $rfc, $calle, $numExt, $numInt, $colonia, $municipio, $pais, $estado, $cp, $pkProveedor, $pkRazonSocial, $localidad, $referencia)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spi_Proveedores_AgregarFiscales(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($razonSocial, $rfc, $calle, $numExt, $numInt, $colonia, $municipio, $pais, $estado, $cp, $pkProveedor, $pkRazonSocial, $PKuser, $localidad, $referencia));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveDireccionEnvioProveedor($sucursal, $email, $calle, $numExt, $numInt, $colonia, $municipio, $pais, $estado, $cp, $pkProveedor, $pkRazonSocial)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spi_Proveedores_AgregarDireccionesEnvio(?,?,?,?,?,?,?,?,?,?,?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($sucursal, $email, $calle, $numExt, $numInt, $colonia, $municipio, $pais, $estado, $cp, $pkProveedor, $pkRazonSocial, $PKuser));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveContactoProveedor($nombreContacto, $apellidoContacto, $puesto, $telefonoFijo, $celular, $email, $pkProveedor, $pkContacto)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spi_Proveedores_AgregarContacto(?,?,?,?,?,?,?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($nombreContacto, $apellidoContacto, $puesto, $telefonoFijo, $celular, $email, $pkProveedor, $pkContacto, $PKuser));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveBancoProveedor($pkBanco, $noCuenta, $clabe, $pkProveedor, $pkCuentaBancaria, $moneda)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spi_Proveedores_AgregarBanco(?,?,?,?,?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($pkBanco, $noCuenta, $clabe, $pkProveedor, $pkCuentaBancaria, $moneda, $PKuser));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }
    /* END PROVEEDORES */
    public function saveOrdenCompraTemp($idproducto, $cantidad, $pkUsuario, $PKProveedor, $precio, $nombre, $clave)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $query = sprintf('call spi_OrdenCompra_Temp_Agregar(?,?,?,?,?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($idproducto, $cantidad, $pkUsuario, $PKProveedor, $precio, $nombre, $clave));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveOrdenCompra($idproducto, $cantidad, $pkOrden, $PKProveedor, $precio, $nombre, $clave)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spi_OrdenCompra_AgregarEdit(?,?,?,?,?,?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($idproducto, $cantidad, $pkOrden, $PKProveedor, $precio, $nombre, $clave, $PKuser));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveOrderPurchase($referencia, $fechaEmision, $fechaEntrega, $proveedor, $direccionEntrega, $importe, $pkUsuario, $notasInternas, $notasProveedor, $comprador, $condicionPago, $moneda, $categoria, $subcategoria)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $pkOrden = 0;
        try {
            $query = sprintf('call spi_OrdenCompra_Agregar(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($referencia, $proveedor, $importe, $fechaEmision, $fechaEntrega, $direccionEntrega, $pkUsuario, $pkOrden, $notasInternas, $notasProveedor, $PKEmpresa, $comprador, $condicionPago, $moneda, $categoria, $subcategoria));
            $PKOrdenCompra = $stmt->fetch()['0'];

            $data[0] = ['status' => $status, 'id' => $PKOrdenCompra];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveProveedor($nombreCom, $contacto, $telefono, $email, $tipo)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKProveedor = 0;
        $PKuser = 0;

        if (isset($_SESSION["PKUsuario"])) {
            $PKuser = $_SESSION["PKUsuario"];
        }

        try {
            $query = 'INSERT INTO proveedores (NombreComercial, Telefono, Email, tipo_persona, tipo, empresa_id, estatus, usuario_creacion_id, usuario_edicion_id, created_at, updated_at) VALUES (:nombreCom, :telefono, :email, :tipo_persona, 1, :id_empresa, 1, :id_usuario_c, :id_usuario_e, (SELECT NOW()), (SELECT NOW()))';
            $stmt = $db->prepare($query);
            if ($stmt->execute(array(':nombreCom' => $nombreCom, ':telefono' => $telefono, ':email' => $email, ':tipo_persona' => $tipo, ':id_empresa' => $_SESSION["IDEmpresa"], ':id_usuario_c' => $PKuser, ':id_usuario_e' => $PKuser))) {
                $PKProveedor = $db->lastInsertId();
            }
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        try {
            $queryC = 'INSERT INTO datos_contacto_proveedor (Nombres, Apellidos, Puesto, Telefono, Celular, Email, FKProveedor) VALUES (:nombre, "", "", "", "", "", :id_proveedor)';
            $stmtC = $db->prepare($queryC);
            if ($stmtC->execute(array(':nombre' => $contacto, ':id_proveedor' => $PKProveedor))) {
                return "exito";
            }
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveOrdenCompraMensaje($mensaje, $tipo, $fKOrdenEncripted)
    {
        $con = new conectar();
        $db = $con->getDb();

        $fecha = '0000-00-00';
        $PKuser = '0';

        if (isset($_SESSION["PKUsuario"])) {
            $PKuser = $_SESSION["PKUsuario"];
        } else {
            $PKuser = '0';
        }

        try {
            $query = sprintf('call spi_OrdenCompra_AgregarMensajeChat(?,?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($mensaje, $tipo, $fKOrdenEncripted, $fecha, $PKuser));
            $horafecha = $stmt->fetch()['0'];

            $data[0] = ['status' => $status, 'fecha' => $horafecha];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveEntryPartialOc($array, $idProducto, $cantidad, $idDetalle)
    {
        $con = new conectar();
        $db = $con->getDb();

        if (isset($_SESSION["PKUsuario"])) {
            $PKuser = $_SESSION["PKUsuario"];
        } else {
            $PKuser = '0';
        }

        try {
            $query = sprintf('call spi_Entrada_OrdenCompraTemp_Agregar(?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($cantidad, $idDetalle, $PKuser));
            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveEntryPartialOcTempTable($pkOrden)
    {
        $con = new conectar();
        $db = $con->getDb();

        if (isset($_SESSION["PKUsuario"])) {
            $PKuser = $_SESSION["PKUsuario"];
        } else {
            $PKuser = '0';
        }

        try {
            $query = sprintf('call spi_Entrada_OCTable_Temp(?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($pkOrden, $PKuser));
            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveEntryPartialOcTempTableEntradaDirecta($pkOrden)
    {
        $con = new conectar();
        $db = $con->getDb();

        if (isset($_SESSION["PKUsuario"])) {
            $PKuser = $_SESSION["PKUsuario"];
        } else {
            $PKuser = '0';
        }

        try {
            $query = sprintf('INSERT into entrada_directa_temp (numero_lote, caducidad, cantidad, producto_id, usuario_id) 
                                select "",
                                    "0000-00-00",
                                    (doc.Cantidad - ifnull(doc.Cantidad_Recibida,0)) as cantidadRestante,
                                    doc.FKProducto,
                                    ?
                                from detalle_orden_compra doc
                                    inner join ordenes_compra oc on oc.PKOrdenCompra = doc.FKOrdenCompra  
                                    left join datos_producto_proveedores dpp on doc.FKProducto = dpp.FKProducto and oc.FKProveedor = dpp.FKProveedor
                                where oc.PKOrdenCompra = ? and doc.Cantidad - ifnull(doc.Cantidad_Recibida,0) >0;');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKuser, $pkOrden));
            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveEntryPartialOcTempTableEdit($folioEntrada)
    {
        $con = new conectar();
        $db = $con->getDb();

        if (isset($_SESSION["IDEmpresa"])) {
            $PKEmpresa = $_SESSION["IDEmpresa"];
        } else {
            $PKEmpresa = '0';
        }

        if (isset($_SESSION["PKUsuario"])) {
            $PKuser = $_SESSION["PKUsuario"];
        } else {
            $PKuser = '0';
        }

        try {
            $query = sprintf('call spi_Entrada_OCTable_TempEdit(?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($folioEntrada, $PKEmpresa, $PKuser));
            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveEntryPartialTransferTempTableEdit($folioEntrada)
    {
        $con = new conectar();
        $db = $con->getDb();

        if (isset($_SESSION["IDEmpresa"])) {
            $PKEmpresa = $_SESSION["IDEmpresa"];
        } else {
            $PKEmpresa = '0';
        }

        if (isset($_SESSION["PKUsuario"])) {
            $PKuser = $_SESSION["PKUsuario"];
        } else {
            $PKuser = '0';
        }

        try {
            $query = sprintf('call spi_Entrada_TransferTable_TempEdit(?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($folioEntrada, $PKEmpresa, $PKuser));
            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveEntryDirectTempTableEdit($folioEntrada)
    {
        $con = new conectar();
        $db = $con->getDb();

        if (isset($_SESSION["IDEmpresa"])) {
            $PKEmpresa = $_SESSION["IDEmpresa"];
        } else {
            $PKEmpresa = '0';
        }

        if (isset($_SESSION["PKUsuario"])) {
            $PKuser = $_SESSION["PKUsuario"];
        } else {
            $PKuser = '0';
        }

        try {
            $query = sprintf('call spi_Entrada_DirectaTable_TempEdit(?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($folioEntrada, $PKEmpresa, $PKuser));
            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveEntryDirectProviderTempTableEdit($folioEntrada)
    {
        $con = new conectar();
        $db = $con->getDb();

        if (isset($_SESSION["IDEmpresa"])) {
            $PKEmpresa = $_SESSION["IDEmpresa"];
        } else {
            $PKEmpresa = '0';
        }

        if (isset($_SESSION["PKUsuario"])) {
            $PKuser = $_SESSION["PKUsuario"];
        } else {
            $PKuser = '0';
        }

        try {
            $query = sprintf('call spi_Entrada_DirectaProveedorTable_TempEdit(?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($folioEntrada, $PKEmpresa, $PKuser));
            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveEntryPartialAddOcTempTable($pkEntradaTemp)
    {
        $con = new conectar();
        $db = $con->getDb();

        if (isset($_SESSION["PKUsuario"])) {
            $PKuser = $_SESSION["PKUsuario"];
        } else {
            $PKuser = '0';
        }

        try {
            $query = sprintf('call spi_EntradaAdd_OCTable_Temp(?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($pkEntradaTemp, $PKuser));
            $PKOrden = $stmt->fetch()['0'];

            $data[0] = ['status' => $status, 'pkOrden' => $PKOrden];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveEntryPartialOcTable($pkOrden, $proveedor, $sucursal, $noDocumento, $serie, $subtotal, $iva, $ieps, $importe, $descuento, $fechaFactura, $remision, $notas)
    {
        $con = new conectar();
        $db = $con->getDb();

        if (isset($_SESSION["PKUsuario"])) {
            $PKuser = $_SESSION["PKUsuario"];
        } else {
            $PKuser = '0';
        }

        $_Folio_Entrada = 0;
        $_IDCuentaPagar = 0;

        $folioOut = '';
        $id_cuentaOut = '';

        try {
            $query = sprintf('call spi_Entrada_OrdenCompra_AgregarEntry(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($pkOrden, $PKuser, $proveedor, $sucursal, $noDocumento, $serie, $subtotal, $iva, $ieps, $importe, $descuento, $fechaFactura, $remision, $notas, $_Folio_Entrada, $_IDCuentaPagar));
            $row = $stmt->fetch();

            $folioCuenta = explode(' / ', $row['0']);

            for ($i = 0; $i < count($folioCuenta); $i++) {
                if ($i == 0) {
                    $folioOut = $folioCuenta[$i];
                } else if ($i == 1) {
                    $id_cuentaOut = (int) $folioCuenta[$i];
                }
            }

            $data[0] = ['status' => $status, 'folio_entrada' => $folioOut, 'id_cuenta_pagar' => $id_cuentaOut];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveExitOPTempTable($pkOrdenPedido)
    {
        $con = new conectar();
        $db = $con->getDb();

        if (isset($_SESSION["PKUsuario"])) {
            $PKuser = $_SESSION["PKUsuario"];
        } else {
            $PKuser = '0';
        }

        try {
            $query = sprintf('call spi_Salida_OP_Agregar_Temp(?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKuser, $pkOrdenPedido));
            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveExitDevolucionTempTable($id_cuenta_pagar)
    {
        $con = new conectar();
        $db = $con->getDb();

        if (isset($_SESSION["PKUsuario"])) {
            $PKuser = $_SESSION["PKUsuario"];
        } else {
            $PKuser = '0';
        }

        if (isset($_SESSION["IDEmpresa"])) {
            $PKEmpresa = $_SESSION["IDEmpresa"];
        } else {
            $PKEmpresa = '0';
        }

        try {
            $query = sprintf('call spi_Salida_Devolucion_Agregar_Temp(?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKuser, $PKEmpresa, $id_cuenta_pagar));
            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveExitOPTableEdit($folioSalida)
    {
        $con = new conectar();
        $db = $con->getDb();

        if (isset($_SESSION["PKUsuario"])) {
            $PKuser = $_SESSION["PKUsuario"];
        } else {
            $PKuser = '0';
        }

        if (isset($_SESSION["IDEmpresa"])) {
            $PKEmpresa = $_SESSION["IDEmpresa"];
        } else {
            $PKEmpresa = '0';
        }


        try {
            $query = sprintf('call spi_Salida_OP_Agregar_TempEdit(?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKuser, $PKEmpresa, $folioSalida));
            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveExitDevolucionTableEdit($folioSalida)
    {
        $con = new conectar();
        $db = $con->getDb();

        if (isset($_SESSION["PKUsuario"])) {
            $PKuser = $_SESSION["PKUsuario"];
        } else {
            $PKuser = '0';
        }

        if (isset($_SESSION["IDEmpresa"])) {
            $PKEmpresa = $_SESSION["IDEmpresa"];
        } else {
            $PKEmpresa = '0';
        }


        try {
            $query = sprintf('call spi_Salida_Devolucion_Agregar_TempEdit(?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKuser, $PKEmpresa, $folioSalida));
            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveDatosSalidaOP($pkOrdenPedido, $observaciones, $surtidor)
    {
        $con = new conectar();
        $db = $con->getDb();

        if (isset($_SESSION["PKUsuario"])) {
            $PKuser = $_SESSION["PKUsuario"];
        } else {
            $PKuser = '0';
        }

        try {
            $query = sprintf('call spi_Salida_OP_Agregar(?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($pkOrdenPedido, $observaciones, $surtidor, $PKuser));
            $FolioSalida = $stmt->fetch()['0'];

            $data[0] = ['status' => $status, 'folio' => $FolioSalida];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function valida_UnidadMedida($ordenPedido)
    {
        $con = new conectar();
        $db = $con->getDb();

        if (isset($_SESSION["PKUsuario"])) {
            $PKuser = $_SESSION["PKUsuario"];
        } else {
            $PKuser = '0';
        }

        try {
            $query = sprintf('SELECT f.FKClaveSATUnidad, p.ClaveInterna from salida_orden_pedido_temp as sopt
                                    inner join productos as p on p.PKProducto = sopt.producto_id
                                    left join info_fiscal_productos as f on f.FKProducto = sopt.producto_id
                                where sopt.usuario_id = ? and sopt.orden_pedido_id = ? group by sopt.producto_id;');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKuser, $ordenPedido));
            $res = $stmt->fetchAll(PDO::FETCH_OBJ);

            $isContinue = 1;
            $msj = '';

            foreach ($res as $r) {
                if ($r->FKClaveSATUnidad == null || $r->FKClaveSATUnidad == 1 || $r->FKClaveSATUnidad == '') {
                    $isContinue = 0;
                    $msj .= $r->ClaveInterna . " ";
                }
            }

            $data = ['status' => $isContinue, 'msj' => $msj];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveDatosSalidaOPGral($pkOrdenPedido, $observaciones, $surtidor)
    {
        $con = new conectar();
        $db = $con->getDb();

        if (isset($_SESSION["PKUsuario"])) {
            $PKuser = $_SESSION["PKUsuario"];
        } else {
            $PKuser = '0';
        }

        try {
            $query = sprintf('call spi_Salida_OPGral_Agregar(?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($pkOrdenPedido, $observaciones, $surtidor, $PKuser));
            $FolioSalida = $stmt->fetch()['0'];

            $data[0] = ['status' => $status, 'folio' => $FolioSalida];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveDatosSalidaCoti($pkOrdenPedido, $observaciones, $surtidor)
    {
        $con = new conectar();
        $db = $con->getDb();

        if (isset($_SESSION["PKUsuario"])) {
            $PKuser = $_SESSION["PKUsuario"];
        } else {
            $PKuser = '0';
        }

        try {
            $query = sprintf('call spi_Salida_Coti_Agregar(?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($pkOrdenPedido, $observaciones, $PKuser, $surtidor));
            $FolioSalida = $stmt->fetch()['0'];

            $data[0] = ['status' => $status, 'folio' => $FolioSalida];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveDatosSalidaVenta($pkOrdenPedido, $observaciones, $surtidor)
    {
        $con = new conectar();
        $db = $con->getDb();

        if (isset($_SESSION["PKUsuario"])) {
            $PKuser = $_SESSION["PKUsuario"];
        } else {
            $PKuser = '0';
        }

        try {
            $query = sprintf('call spi_Salida_Venta_Agregar(?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($pkOrdenPedido, $observaciones, $PKuser, $surtidor));
            $FolioSalida = $stmt->fetch()['0'];

            $data[0] = ['status' => $status, 'folio' => $FolioSalida];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveDatosSalidaDevolucion($idCuentaPorPagar, $observaciones, $surtidor)
    {
        $con = new conectar();
        $db = $con->getDb();

        if (isset($_SESSION["PKUsuario"])) {
            $PKuser = $_SESSION["PKUsuario"];
        } else {
            $PKuser = '0';
        }

        try {
            $query = sprintf('call spi_Salida_Devolucion_Agregar(?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($idCuentaPorPagar, $observaciones, $PKuser, $surtidor));
            $FolioSalida = $stmt->fetch()['0'];

            $data[0] = ['status' => $status, 'folio' => $FolioSalida];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveEntryTransferTempTable($folioSalida)
    {
        $con = new conectar();
        $db = $con->getDb();

        if (isset($_SESSION["PKUsuario"])) {
            $PKuser = $_SESSION["PKUsuario"];
            $PKEmpresa = $_SESSION['IDEmpresa'];
        } else {
            $PKuser = '0';
        }

        try {
            $query = sprintf('call spi_Entrada_Traspaso_AgregarEntryTemp(?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($folioSalida, $PKuser, $PKEmpresa));

            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveEntryTransferTable($folioSalida)
    {
        $con = new conectar();
        $db = $con->getDb();

        if (isset($_SESSION["PKUsuario"])) {
            $PKuser = $_SESSION["PKUsuario"];
        } else {
            $PKuser = '0';
        }

        try {
            $query = sprintf('call spi_Entrada_Traspaso_AgregarEntry(?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($folioSalida, $PKuser));
            $folioEntrada = $stmt->fetch()['0'];

            $data[0] = ['status' => $status, 'folioEntrada' => $folioEntrada];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveDatosProductoED($PKProducto, $sucDestino)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];
        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spi_EntradaED_AgregarProducto(?,?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKProducto, $sucDestino, $PKEmpresa, $PKuser));
            $insertado = $stmt->fetch()['0'];

            $data[0] = ['status' => $status, 'insertado' => $insertado];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveEntryPartialEdTable($referencia, $notas, $sucursalEntrada, $sucursalOrigen)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];
        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spi_Entrada_Directa_AgregarEntry(?,?,?,?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($referencia, $notas, $sucursalEntrada, $sucursalOrigen, $PKEmpresa, $PKuser));
            $insertado = $stmt->fetch()['0'];

            $data[0] = ['status' => $status, 'insertado' => $insertado];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveEntryPartialEdProviderTable($sucEntrada, $proveedor, /* $serie, */ $folio, $tipoEntradas, $subtotal, $importe, $fecha, $fechaVenci, $notas, $addCuentaPagar, $ordenCompra, $categoria,$subcategoria)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];
        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spi_Entrada_DirectaProveedor_AgregarEntry(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($sucEntrada, $proveedor, 'N/A', $folio, $tipoEntradas, $subtotal, $importe, $fecha, $fechaVenci, $notas, $PKEmpresa, $PKuser, $addCuentaPagar, $ordenCompra, $categoria,$subcategoria));
            $insertado = $stmt->fetchAll();

            $data[0] = ['status' => $status, 'insertado' => $insertado[0]['folioEntrada'], 'cuentaPagar' => $insertado[0]['cuentaPagar']];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveEntryPartialEdCustomerTable($referencia, $notas, $sucursalEntrada, $cliente)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];
        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spi_Entrada_DirectaCliente_AgregarEntry(?,?,?,?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($referencia, $notas, $sucursalEntrada, $cliente, $PKEmpresa, $PKuser));
            $insertado = $stmt->fetch()['0'];

            $data[0] = ['status' => $status, 'insertado' => $insertado];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }
    //END JAVIER RAMIREZ
    function saveCategoriaGasto($value,$value1,$check)
    {
        $con = new conectar();
        $db = $con->getDb();
        $save_data = new save_data();

        $empresa_id = $_SESSION['IDEmpresa'];
        $user = $_SESSION['PKUsuario'];

        $query = sprintf("insert into categoria_gastos (Nombre,estatus,created_at,updated_at,empresa_id,usuario_creacion_id,usuario_edicion_id) values (:name,:status,:created,:updated,:empresa_id,:user_created,:user_updated)");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":name", $value);
        $stmt->bindValue(":status", 1);
        $stmt->bindValue(":created", date('Y-m-d H:i:s'));
        $stmt->bindValue(":updated", date('Y-m-d H:i:s'));
        $stmt->bindValue(":empresa_id", $empresa_id);
        $stmt->bindValue(":user_created", $user);
        $stmt->bindValue(":user_updated", $user);
        $res = $stmt->execute();
        $id = $db->lastInsertId();
        
        if((!is_null($value1) && !empty($value1)) && $check === 'true' ){
            $res1 = $save_data->saveSubcategoriaGasto($value1, $id);
        }

        return ['estatus' => $res, 'id' => $id,'id_sub' =>$res1];
    }

    function saveSubcategoriaGasto($value, $value1)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf("insert into subcategorias_gastos (Nombre,FKCategoria) values (:name,:cat)");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":name", $value);
        $stmt->bindValue(":cat", $value1);
        $res = $stmt->execute();
        $id = $db->lastInsertId();

        return ['estatus' => $res, 'id' => $id];
    }

    function saveIniciarPeriodicInv($PKSucursal)
    {
        $con = new conectar();
        $db = $con->getDb();
        
        $user = $_SESSION['PKUsuario'];

        $query = sprintf("call spi_IniciarInventarioPeriodico(:sucursal_id, :usuario_id)");
        $stmt = $db->prepare($query);
        $stmt->bindValue(":sucursal_id", $PKSucursal);
        $stmt->bindValue(":usuario_id", $user);
        $res = $stmt->execute();

        return ['estatus' => $res];
    }
}

class edit_data
{
    public function editInventario($minimo, $maximo, $idExistencia)
    {
        $con = new conectar();
        $db = $con->getDb();
        try {
            $stmt = $db->prepare("SELECT clave_producto, sucursal_id FROM existencia_por_productos WHERE id = :id");
            $stmt->execute([':id' => $idExistencia]);
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            $clave = $res['clave_producto'];
            $sucursal = $res['sucursal_id'];
            $stmt = $db->prepare('UPDATE existencia_por_productos SET existencia_minima = :minimo, existencia_maxima = :maximo WHERE clave_producto = :claveProducto AND sucursal_id = :sucursalId');
            $stmt->execute([':minimo' => $minimo, ':maximo' => $maximo, ':claveProducto' => $clave, ':sucursalId' => $sucursal]);
            if ($stmt->rowCount() > 0) {
                return $data[0] = ['status' => 'success'];
            }
            return $data[0] = ['status' => 'fail'];
        } catch (\Throwable $th) {
            /* Error development */
            return $data[0] = ['status' => 'fail', 'message' => $th];
            //return $data[0] = ['status' => 'fail'];

        }
    }

    public function editEntry($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $usuario = $value['usuario'][0];
        $fecha = $value['fecha'][0];
        $tipoEntrada = $value['tipoEntrada'][0];
        $almacen = $value['almacen'][0];
        $idEntrada = $value['idEntrada'][0];

        try {

            $query = sprintf('SELECT PKUsuario FROM prueba_rh.usuarios WHERE Usuario = ?');
            $stmt = $stmt = $db->prepare($query);
            $stmt->execute(array($usuario));
            $PKUsuario = $stmt->fetch()['PKUsuario'];

            $query = sprintf('UPDATE entradas_inventarios SET FKUsuario=?,Fecha=?,FKAlmacen=?,FKTipoEntrada=? WHERE PKEntradaInventario = ?');
            $stmt = $db->prepare($query);
            $stmt->execute(array($PKUsuario, $fecha, $almacen, $tipoEntrada, $idEntrada));
            $idLast = $db->lastInsertId();

            if ($tipoEntrada === "1") {
                $proveedor = $value['proveedor'][0];
                $referencia = $value['referencia'][0];
                $notas = $value['notas'][0];

                $query = sprintf('SELECT * FROM compra_entrada_proveedor
                            INNER JOIN entradas_documentos_notas ON compra_entrada_proveedor.FKEntradaInventario = entradas_documentos_notas.FKEntradaInventario
                            WHERE compra_entrada_proveedor.FKEntradaInventario = ? AND entradas_documentos_notas.FKEntradaInventario = ?');
                $stmt = $db->prepare($query);
                $stmt->execute(array($idEntrada, $idEntrada));
                $rowCount = $stmt->rowCount();

                if ($rowCount > 0) {
                    $query = sprintf('UPDATE compra_entrada_proveedor SET FKProveedor=? WHERE FKEntradaInventario = ?');
                    $stmt = $db->prepare($query);
                    $stmt->execute(array($proveedor, $idEntrada));

                    $query = sprintf('UPDATE entradas_documentos_notas SET Documento=?,Notas=? WHERE FKEntradaInventario = ?');
                    $stmt = $db->prepare($query);
                    $stmt->execute(array($referencia, $notas, $idEntrada));
                } else {
                    $query = sprintf('INSERT INTO compra_entrada_proveedor (FKEntradaInventario,FKProveedor) VALUES (?,?)');
                    $stmt = $db->prepare($query);
                    $stmt->execute(array($idLast, $proveedor));

                    $query = sprintf('INSERT INTO entradas_documentos_notas (FKEntradaInventario,Documento,Notas) VALUES (?,?,?)');
                    $stmt = $db->prepare($query);
                    $stmt->execute(array($idLast, $referencia, $notas));
                }
            } else if ($tipoEntrada === "2") {
                $cliente = $value['cliente'][0];
                $documento = $value['documento'][0];
                $ticket = $value['ticket'][0];
                $notas = $value['notas'][0];

                $query = sprintf('SELECT * FROM devolucion_entrada_cliente
                            INNER JOIN entradas_tickets_notas ON devolucion_entrada_cliente.FKEntradaInventario = entradas_tickets_notas.FKEntradaInventario
                            WHERE devolucion_entrada_cliente.FKEntradaInventario = ? AND entradas_tickets_notas.FKEntradaInventario = ?');
                $stmt = $db->prepare($query);
                $stmt->execute(array($idEntrada, $idEntrada));
                $rowCount = $stmt->rowCount();

                if ($rowCount > 0) {
                    $query = sprintf('UPDATE devolucion_entrada_cliente SET FKCliente=? WHERE FKEntradaInventario=?');
                    $stmt = $db->prepare($query);
                    $stmt->execute(array($cliente, $idEntrada));

                    $query = sprintf('UPDATE entradas_tickets_notas SET Documento=?,Tickets=?,Notas=? WHERE FKEntradaInventario=?');
                    $stmt = $db->prepare($query);
                    $stmt->execute(array($documento, $ticket, $notas, $idEntrada));
                } else {
                    $query = sprintf('INSERT INTO devolucion_entrada_cliente (FKEntradaInventario,FKCliente) VALUES (?,?)');
                    $stmt = $db->prepare($query);
                    $stmt->execute(array($idEntrada, $cliente));

                    $query = sprintf('INSERT INTO entradas_tickets_notas (FKEntradaInventario,Documento,Tickets,Notas) VALUES (?,?,?,?)');
                    $stmt = $db->prepare($query);
                    $stmt->execute(array($idEntrada, $documento, $ticket, $notas));
                }
            } else if ($tipoEntrada === "3") {
                $ordenFabricacion = $value['ordenFabricacion'][0];

                $query = sprintf('UPDATE fabricacion_entrada_orden SET FKOrdenFabricacion=? WHERE FKEntradaInventario=?');
                $stmt = $db->prepare($query);
                $stmt->execute(array($ordenFabricacion, $idEntrada));
            } else if ($tipoEntrada === "5") {
                $salida = $value['salida'][0];

                $query = sprintf('UPDATE traspaso_entrada_salida SET FKSalidaInventario=? WHERE FKEntradaInventario=?');
                $stmt = $db->prepare($query);
                $stmt->execute(array($salida, $idEntrada));
            }

            //return $idEntrada;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    //JAVIER RAMIREZ
    public function editCategoria($estatus, $categoria, $id)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $query = sprintf('call spu_Categoria_Datos_Actualizar(?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($estatus, $categoria, $id));

            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editMarca($estatus, $marca, $id)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $query = sprintf('call spu_Marca_Datos_Actualizar(?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($estatus, $marca, $id));

            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editTipoProducto($estatus, $tipoProducto, $id)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $query = sprintf('call spu_TipoProducto_Datos_Actualizar(?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($estatus, $tipoProducto, $id));

            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editTipoOrdenInventario($estatus, $tipoOrdenInventario, $id)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $query = sprintf('call spu_TipoOrdenInventario_Datos_Actualizar(?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($estatus, $tipoOrdenInventario, $id));

            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editDatosCantidadProductoCompTemp($pkProducto, $cantidad, $pkUsuario, $costo, $moneda)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $query = sprintf('call spu_Prod_CantidadProdCompuestoTemp_Actualizar (?,?,?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($pkUsuario, $pkProducto, $cantidad, $costo, $moneda));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editDatosProducto($value, $foto, $usuario, $isCompra, $isVenta, $isFabricacion, $isGastoFijo, $pkProducto, $costoCompra, $monedaCompra, $costoVenta, $monedaVenta, $costoFabri, $monedaFabri, $costoGastoFijo, $monedaGastoFijo,/*,$isSerie, $serie*/ $isLote/*, $lote*/, $isCaducidad/*, $caducidad*/, $unidadMedida)
    {
        $con = new conectar();
        $db = $con->getDb();

        $editData = new edit_data();

        $fkEstatusGeneral = $value['estatus'];
        $nombre = $value['nombre'];
        $claveInterna = $value['claveInterna'];
        $codigoBarras = $value['codigoBarra'];
        $fkCategoriaProducto = $value['categoria'];
        $fkMarcaProducto = $value['marca'];
        $descripcion = $value['descripcion'];

        if ($foto == 1) {
            $imagen = 'ruta';
        } else {
            $imagen = 'vacia';
        }

        try {
            if ($isLote == 1) {
                $editData->loteDefault($pkProducto);
            }

            $query = sprintf('call spu_Prod_ActualizarGeneral (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($nombre, $claveInterna, $codigoBarras, $fkCategoriaProducto, $fkMarcaProducto, $descripcion, $fkEstatusGeneral, $imagen, $isCompra, $isVenta, $isFabricacion, $isGastoFijo, $pkProducto, $usuario, $costoVenta, $monedaVenta, $costoCompra, $monedaCompra, $costoFabri, $monedaFabri, $costoGastoFijo, $monedaGastoFijo, /*$isSerie, $serie,*/ $isLote/*, $lote*/, $isCaducidad/*, $caducidad*/, $unidadMedida));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editDatosProveedor($PKProducto, $pkProveedor, $nombreProd, $clave, $precio, $moneda, $cantidadMin, $diasEntrega, $unidadMedida, $idDetalleProdProv)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_Prod_ActualizarProveedor(?,?,?,?,?,?,?,?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKProducto, $pkProveedor, $nombreProd, $clave, $precio, $moneda, $cantidadMin, $diasEntrega, $unidadMedida, $idDetalleProdProv, $PKuser));

            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editDatosInventarioProducto($id, $sucursal, $cantidad, $serie, $lote, $caducidad)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $query = sprintf('SELECT id FROM existencia_por_productos WHERE producto_id = ?');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($id));
            $row = $stmt->fetch(PDO::FETCH_OBJ);

            if ($row == null) {
                try {
                    $query = sprintf('INSERT INTO existencia_por_productos (existencia_minima, existencia_maxima, punto_reorden, numero_lote, numero_serie, caducidad, existencia, sucursal_id, producto_id, clave_producto) SELECT 0, 0, 0, ?, ?, ?, ?, ?, PKProducto, ClaveInterna FROM productos WHERE PKProducto = ?');
                    $stmt = $stmt = $db->prepare($query);
                    $status = $stmt->execute(array($lote, $serie, $caducidad, $cantidad, $sucursal, $id));

                    $data[0] = ['status' => $status];
                    return $data;
                } catch (PDOException $e) {
                    return "Error en Consulta: " . $e->getMessage();
                }
            } else {
                try {
                    $query = sprintf('UPDATE existencia_por_productos SET sucursal_id = ?, existencia = ?, numero_serie = ?, numero_lote = ?, caducidad = ? WHERE producto_id = ?');
                    $stmt = $stmt = $db->prepare($query);
                    $status = $stmt->execute(array($sucursal, $cantidad, $serie, $lote, $caducidad, $id));

                    $data[0] = ['status' => $status];
                    return $data;
                } catch (PDOException $e) {
                    return "Error en Consulta: " . $e->getMessage();
                }
            }
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    //función para registrar un lote en caso de no tener ninguno registrado
    public function loteDefault($id)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $query = sprintf('SELECT id FROM existencia_por_productos WHERE producto_id = ?');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($id));
            $row = $stmt->fetch(PDO::FETCH_OBJ);

            if ($row == null) {
                try {

                    $query = sprintf('SELECT id
                                    FROM sucursales 
                                    where empresa_id = :id_empresa and estatus = 1 and activar_inventario = 1
                                    order by id limit 1');
                    $stmt = $stmt = $db->prepare($query);
                    $stmt->bindValue(":id_empresa", $_SESSION['IDEmpresa']);
                    $status = $stmt->execute();
                    $sucursal = $stmt->fetchAll();

                    $query = sprintf('INSERT INTO existencia_por_productos (existencia_minima, existencia_maxima, punto_reorden, numero_lote, existencia, sucursal_id, producto_id, clave_producto) SELECT 0, 0, 0, "TIMLID", 0, ?, PKProducto, ClaveInterna FROM productos WHERE PKProducto = ?');
                    $stmt = $stmt = $db->prepare($query);
                    $status = $stmt->execute(array($sucursal[0]['id'], $id));

                    $data[0] = ['status' => $status];
                    return $data;
                } catch (PDOException $e) {
                    return "Error en Consulta: " . $e->getMessage();
                }
            } else {
                $query = sprintf('UPDATE existencia_por_productos set numero_lote = "TIMLID" WHERE producto_id = ? and (numero_lote = "" or numero_lote is null)');
                $stmt = $stmt = $db->prepare($query);
                $status = $stmt->execute(array($id));
            }
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editDatosInventario($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $fkProducto = $value[0]['datos'];
        $fkTipoOrdenInventario = $value[1]['datos'];
        $stockExistencia = $value[2]['datos'];
        $stockMinimo = $value[3]['datos'];
        $stockMaximo = $value[4]['datos'];
        $puntoReorden = $value[5]['datos'];
        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_Prod_ActualizarInventario (?,?,?,?,?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($fkProducto, $fkTipoOrdenInventario, $stockExistencia, $stockMinimo, $stockMaximo, $puntoReorden, $PKuser));

            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    /* PROVEEDORES */
    public function editDatosProveedorTable($array, $nombreComercial, $vendedor, $montoCredito, $diasCredito, $telefono, $email, $estatus, $pkProveedor, $tipoPersona, $email2, $movil, $giro)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_Proveedor_ActualizarGeneral(?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($nombreComercial, '0', $telefono, $email, $montoCredito, $diasCredito, $estatus, $vendedor, $pkProveedor, $PKuser, $tipoPersona, $email2, $movil, $giro));

            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }
    /* END PROVEEDORES */

    /////////////////////////COLUMNAS AJUSTABLES PROVEEDORES//////////////////////////////
    public function updateCheckColumn($pkColumnaProveedor, $flag)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_Columnas_Proveedores_Check(?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($pkColumnaProveedor, $flag, $PKuser));

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

    /////////////////////////COLUMNAS AJUSTABLES PRODUCTOS//////////////////////////////
    public function updateCheckColumnProd($pkColumnaProductos, $flag)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_Columnas_Productos_Check(?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($pkColumnaProductos, $flag, $PKuser));

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

    //Orden compra
    public function editOrdenCompraTemp($idproducto, $cantidad, $pkUsuario, $pkProveedor)
    {
        $con = new conectar();
        $db = $con->getDb();
        try {
            $query = sprintf('call spu_OrdenCompra_Temp_Actualizar(?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($idproducto, $cantidad, $pkUsuario, $pkProveedor));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editOrdenCompra($idproducto, $cantidad, $pkOrden, $pkProveedor)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_OrdenCompra_ActualizarEdit(?,?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($idproducto, $cantidad, $pkOrden, $pkProveedor, $PKuser));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editOrdenCompraCantidad($idOrdenTemp, $cantidad)
    {
        $con = new conectar();
        $db = $con->getDb();
        try {
            $query = sprintf('call spu_OrdenCompra_Temp_ActualizarCantidad(?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($idOrdenTemp, $cantidad));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editOrdenCompraCantidadEdit($idDetalleOrden, $cantidad)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_OrdenCompra_ActualizarCantidadEdit(?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($idDetalleOrden, $cantidad, $PKuser));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editOrdenCompraDescuento($descuento)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_OrdenCompra_Temp_ActualizarDescuento(?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($descuento, $PKuser));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editAceptarOrdenCompra($PKOrdenCompraEncrypted, $Estado)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = '0';

        if (isset($_SESSION["PKUsuario"])) {
            $PKuser = $_SESSION["PKUsuario"];
        } else {
            $PKuser = '0';
        }

        try {
            $query = sprintf('call spu_OrdenCompra_ActualizarEstado(?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKOrdenCompraEncrypted, $Estado, $PKuser));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editOrderPurchase($fechaEntrega, $direccionEntrega, $importe, $pkUsuario, $notasInternas, $notasProveedor, $PKOrden, $comprador, $condicion_Pago, $moneda, $categoria, $subcategoria)
    {
        $con = new conectar();
        $db = $con->getDb();
        try {
            $query = sprintf('call spu_OrdenCompra_ActualizarGeneral(?,?,?,?,?,?,?,?,?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($fechaEntrega, $direccionEntrega, $importe, $pkUsuario, $notasInternas, $notasProveedor, $PKOrden, $comprador, $condicion_Pago, $moneda, $categoria, $subcategoria));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editEntradaOCTemp($idDetalle, $cantidad)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_EntradaOC_Temp_Actualizar(?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($idDetalle, $cantidad, $PKuser));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editAmountEntryPartialTemp($cantidad, $idEntradaTemp)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_Cantidad_EntradaOC_Temp(?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($idEntradaTemp, $cantidad, $PKuser));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editAmountEntryTranferTemp($cantidad, $idEntradaTemp)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_Cantidad_Entrada_Traspaso_Temp(?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($idEntradaTemp, $cantidad, $PKuser));
            $FolioEntrada = $stmt->fetch()['0'];

            $data[0] = ['status' => $status, 'folio' => $FolioEntrada];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editLotEntryPartialTemp($lote, $idEntradaTemp)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_Lote_EntradaOC_Temp(?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($idEntradaTemp, $lote, $PKuser));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editLotEntryDirectTemp($lote, $idEntradaDirectTemp)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_Lote_EntradaED_Temp(?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($idEntradaDirectTemp, $lote, $PKuser));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editSerieEntryPartialTemp($serie, $idEntradaTemp)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_Serie_EntradaOC_Temp(?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($idEntradaTemp, $serie, $PKuser));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editSerieEntryDirectTemp($serie, $idEntradaTemp)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_Serie_EntradaED_Temp(?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($idEntradaTemp, $serie, $PKuser));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editCaducidadEntryPartialTemp($caducidad, $idEntradaTemp)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_Caducidad_EntradaOC_Temp(?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($idEntradaTemp, $caducidad, $PKuser));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editCantidadEntryDirectTemp($cantidad, $idEntradaTemp)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_Cantidad_EntradaED_Temp(?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($idEntradaTemp, $cantidad, $PKuser));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editCaducidadEntryDirect_temp($caducidad, $idEntradaTemp)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_Caducidad_EntradaED_Temp(?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($idEntradaTemp, $caducidad, $PKuser));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editImpuestoEntryDirectTemp($impuesto, $idEntradaDirectaTemp)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_Impuesto_EntradaED_Temp(?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($idEntradaDirectaTemp, $impuesto, $PKuser));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editImpuestoIVAEntryDirectTemp($impuesto, $idEntradaDirectaTemp)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_ImpuestoIVA_EntradaED_Temp(?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($idEntradaDirectaTemp, $impuesto, $PKuser));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editPrecioEntryDirectTemp($precio, $idEntradaDirectaTemp)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_Precio_EntradaED_Temp(?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($idEntradaDirectaTemp, $precio, $PKuser));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editIsImpuestoEntryPartialTemp($idImpuestoOC, $isOrNot)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $query = sprintf('call spu_IsImpuesto_EntradaOC_Temp(?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($idImpuestoOC, $isOrNot));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editEntradaOCEstatusFactura($folio, $serie, $ordenCompra, $id_cuenta_pagar)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $query = sprintf('call spu_Entrada_OC_EstatusFactura(?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($folio, $serie, $ordenCompra, $id_cuenta_pagar));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editEntradaOCEstatusFacturaEdit($folio, $serie, $folioEntrada, $id_cuenta_pagar)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION['IDEmpresa'];

        try {
            $query = sprintf('call spu_Entrada_OC_EstatusFacturaEdit(?,?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($folio, $serie, $folioEntrada, $PKEmpresa, $id_cuenta_pagar));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editSalidaCantidadModalTemp($serieLote, $cantidad, $ordenPedido, $pkProducto)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_Salida_OP_Cantidad_Modal_Temp(?,?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($serieLote, $cantidad, $ordenPedido, $pkProducto, $PKuser));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editSalidaCantidadModalTempEdicion($serieLote, $cantidad, $folioSalida, $pkProducto)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_Salida_OP_Cantidad_Modal_TempEdicion(?,?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($serieLote, $cantidad, $folioSalida, $pkProducto, $PKuser));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editSalidaCantidadDevolucionTemp($idSalidaTemp, $cantidad, $idCuentaPorPagar, $pkProducto)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_Salida_Devolucion_Cantidad_Temp(?,?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($idSalidaTemp, $cantidad, $idCuentaPorPagar, $pkProducto, $PKuser));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editEstatusOC($pkOrdenCompra)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_OrdenCompra_Estatus(?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($pkOrdenCompra, $PKuser));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editEntryPartialOcTable($folioEntrada, $noDocumento, $serie, $subtotal, $iva, $ieps, $importe, $descuento, $fechaFactura, $remision, $notas)
    {
        $con = new conectar();
        $db = $con->getDb();

        if (isset($_SESSION["PKUsuario"])) {
            $PKuser = $_SESSION["PKUsuario"];
        } else {
            $PKuser = '0';
        }

        if (isset($_SESSION["IDEmpresa"])) {
            $PKEmpresa = $_SESSION["IDEmpresa"];
        } else {
            $PKEmpresa = '0';
        }

        $folioOut = '';
        $id_cuentaOut = '';

        try {
            $query = sprintf('call spu_Entrada_OrdenCompra_EditarEntry(?,?,?,?,?,?,?,?,?,?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($folioEntrada, $PKEmpresa, $PKuser, $noDocumento, $serie, $subtotal, $iva, $ieps, $importe, $descuento, $fechaFactura, $remision, $notas));
            $row = $stmt->fetch();

            $folioCuenta = explode(' / ', $row['0']);

            for ($i = 0; $i < count($folioCuenta); $i++) {
                if ($i == 0) {
                    $folioOut = $folioCuenta[$i];
                } else if ($i == 1) {
                    $id_cuentaOut = (int) $folioCuenta[$i];
                }
            }

            $data[0] = ['status' => $status, 'folio_entrada' => $folioOut, 'id_cuenta_pagar' => $id_cuentaOut];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editEntryTransferTable($folioEntrada)
    {
        $con = new conectar();
        $db = $con->getDb();

        if (isset($_SESSION["PKUsuario"])) {
            $PKuser = $_SESSION["PKUsuario"];
        } else {
            $PKuser = '0';
        }

        if (isset($_SESSION["IDEmpresa"])) {
            $PKEmpresa = $_SESSION["IDEmpresa"];
        } else {
            $PKEmpresa = '0';
        }

        try {
            $query = sprintf('call spu_Entrada_Traspaso_EditarEntry(?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($folioEntrada, $PKEmpresa, $PKuser));
            $folioEntrada = $stmt->fetch()['0'];

            $data[0] = ['status' => $status, 'folioEntrada' => $folioEntrada];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function validaCantidadEntradaPedido($folio)
    {
        $con = new conectar();
        $db = $con->getDb();

        if (isset($_SESSION["IDEmpresa"])) {
            $PKEmpresa = $_SESSION["IDEmpresa"];
        } else {
            $PKEmpresa = '0';
        }

        $query = sprintf('SELECT sum(i.cantidad_entrada) as cantidad_entrada 
                        from inventario_salida_por_sucursales as i 
                            left join orden_pedido_por_sucursales opps on i.orden_pedido_id = opps.id
                         where i.folio_salida = ? and opps.empresa_id = ?');
        $stmt = $db->prepare($query);
        $stmt->execute(array($folio, $PKEmpresa));
        $res = $stmt->fetch(PDO::FETCH_OBJ);
        $cantEntrada = $res->cantidad_entrada;

        if ($cantEntrada > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function editDatosSalidaOPEdicion($folio, $observaciones)
    {
        $con = new conectar();
        $db = $con->getDb();

        $get = new edit_data();

        if ($get->validaCantidadEntradaPedido($folio)) {
            $data[0] = ['status' => 0];
            return $data;
        }

        if (isset($_SESSION["PKUsuario"])) {
            $PKuser = $_SESSION["PKUsuario"];
        } else {
            $PKuser = '0';
        }

        if (isset($_SESSION["IDEmpresa"])) {
            $PKEmpresa = $_SESSION["IDEmpresa"];
        } else {
            $PKEmpresa = '0';
        }

        try {
            $query = sprintf('call spu_Salida_OP_Editar(?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($folio, $observaciones, $PKuser, $PKEmpresa));
            $row = $stmt->fetch();

            $folioSalida = explode(' / ', $row['0']);

            for ($i = 0; $i < count($folioSalida); $i++) {
                if ($i == 0) {
                    $folioExit = $folioSalida[$i];
                } else if ($i == 1) {
                    $idOrder = (int) $folioSalida[$i];
                }
            }

            $data[0] = ['status' => $status, 'folio' => $folioExit, 'idOrder' => $idOrder];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editDatosSalidaDevolucionEdicion($folio, $observaciones)
    {
        $con = new conectar();
        $db = $con->getDb();

        if (isset($_SESSION["PKUsuario"])) {
            $PKuser = $_SESSION["PKUsuario"];
        } else {
            $PKuser = '0';
        }

        if (isset($_SESSION["IDEmpresa"])) {
            $PKEmpresa = $_SESSION["IDEmpresa"];
        } else {
            $PKEmpresa = '0';
        }

        try {
            $query = sprintf('call spu_Salida_Devolucion_Editar(?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($folio, $observaciones, $PKuser, $PKEmpresa));
            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editEntryPartialEdTable($notas, $referencia)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];
        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_Entrada_Directa_ActualizarEntry(?,?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($notas, $referencia, $PKEmpresa, $PKuser));
            $insertado = $stmt->fetch()['0'];

            $data[0] = ['status' => $status, 'insertado' => $insertado];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editEntryPartialEdProviderTable($serie, $folio, $tipoEntradas, $subtotal, $importe, $fecha, $notas, $referencia, $addCuentaPagar,$categoria,$subcategoria)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];
        $PKuser = $_SESSION["PKUsuario"];

        try {

            $query = sprintf("SELECT ifnull(cpp.estatus_factura,0) as estatus_factura, 
                                    if(ieps.is_movimiento is null or ieps.is_movimiento = null,0, ieps.is_movimiento) as is_movimiento 
                            from cuentas_por_pagar cpp
                                inner join inventario_entrada_por_sucursales ieps on concat(cpp.folio_factura,' / ',num_serie_factura) = ieps.numero_documento
                                inner join sucursales s on ieps.sucursal_id = s.id
                            where s.empresa_id = ? and ieps.folio_entrada = ? limit 1");
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKEmpresa, $referencia));
            $res = $stmt->fetchAll();
            $rowCount = $stmt->rowCount();
            if ($rowCount == 1 && ($res[0]['estatus_factura'] == 4 || $res[0]['estatus_factura'] == 5 || $res[0]['is_movimiento'] == 1)) {
                $data[0] = ['status' => 'no'];

                return $data;
            }

            $query = sprintf('call spu_Entrada_DirectaProveedor_ActualizarEntry(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($serie, $folio, $tipoEntradas, $subtotal, 0, 0, $importe, $fecha, $notas, $PKEmpresa, $PKuser, $referencia, $addCuentaPagar,$categoria,$subcategoria));
            $insertado = $stmt->fetchAll();

            $data[0] = ['status' => $status, 'insertado' => $insertado[0]['folioEntrada'], 'cuentaPagar' => $insertado[0]['cuentaPagar']];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editEntryPartialEdCustomerTable($notas, $referencia)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];
        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_Entrada_DirectaCliente_ActualizarEntry(?,?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($notas, $referencia, $PKEmpresa, $PKuser));
            $insertado = $stmt->fetch()['0'];

            $data[0] = ['status' => $status, 'insertado' => $insertado];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }
    //END JAVIER RAMIREZ
    public function editClavesKardexTemp()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKUsuario = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_Claves_Kardex_Temp(?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKUsuario));
            $data = $stmt->fetch();

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function updateCostoCliente($pkRegistro, $Costo, $moneda, $cliente)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_EditarProdCliente(?,?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($pkRegistro, $PKuser, $Costo, $moneda, $cliente));

            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editCancelInv($PKSucursal)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('UPDATE inventario_por_sucursales SET estatus=2, usuario_edito_id=? WHERE sucursal_id=? AND tipo=1 AND estatus=0');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKSucursal,$PKuser));

            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }
}

class delete_data
{
    //JOSÍAS PONCE
    public function deleteEmptyProductStock($PKDetalle, $PKSucursal, $PKProducto, $Clave, $Cantidad)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $query = sprintf('call spi_Detalle_Inventarios_Iniciales_PorSucursal_ProductoRepetido(?,?,?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKDetalle, $PKSucursal, $PKProducto, $Clave, $Cantidad));

            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    //JAVIER RAMIREZ
    public function deleteImpuestoProducto($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spd_EliminarImpuestoProducto(?,?)');
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

    public function deleteAccionProducto($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spd_EliminarAccionProducto(?,?)');
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

    public function deleteAccionProductoTemp($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $query = sprintf('call spd_EliminarAccionProductoTemp(?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($value));

            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function deleteCategoria($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $query = sprintf('call spd_EliminarCategoria(?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($value));

            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function deleteMarca($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $query = sprintf('call spd_EliminarMarca(?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($value));

            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function deleteTipoProducto($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $query = sprintf('call spd_EliminarTipoProducto(?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($value));

            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function deleteTipoOrdenInventario($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $query = sprintf('call spd_EliminarTipoOrdenInventario(?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($value));

            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function deleteDatosProductoCompTempAll($value)
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

    public function deleteDatosProductoCompTemp($pkUsuario, $pkProducto)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $query = sprintf('call spd_EliminarProdCompuestoTemp(?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($pkUsuario, $pkProducto));

            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function deleteProveedorProducto($datoProductoProveedor)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spd_EliminarProdProveedor(?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($datoProductoProveedor, $PKuser));

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

    public function deleteProducto($pkProducto)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spd_EliminarProducto(?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($pkProducto, $PKuser));

            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    /* PROVEEDORES */
    public function deleteRazonSocialProveedor($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spd_EliminarRazonSocialProveedor(?,?)');
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

    public function deleteDireccionEnvioProveedor($value)
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

    public function deleteContactoProveedor($value)
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

    public function deleteCuentaBancariaProveedor($value)
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

    public function deleteProveedorTable($pkProveedor)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spd_EliminarProveedor(?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($pkProveedor, $PKuser));

            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }
    /* END PROVEEDORES */
    public function deleteOrdenCompraTemp($PKOrdenCompraTemp)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $query = sprintf('call spd_EliminarOrdenCompraTemp(?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKOrdenCompraTemp));

            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function deleteOrdenCompraTempAll($PKUsuario)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $query = sprintf('call spd_EliminarOrdenCompraTempAll(?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKUsuario));

            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function deleteOrdenCompra($PKDetalleOrdenCompra)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spd_EliminarOrdenCompraEdit(?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKDetalleOrdenCompra, $PKuser));

            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function deleteDatosEntradaOCTemp()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spd_Eliminar_Entrada_OCTempTable(?)');
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

    public function deleteDatosEntradaTransferTemp()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spd_Eliminar_Entrada_TransferTempTable(?)');
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

    public function deleteDatosEntradaDirectaTemp()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spd_Eliminar_Entrada_DirectaTempTable(?)');
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

    public function deleteDatosSalidaOPTemp()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spd_Eliminar_Salida_OPTempTable(?)');
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

    public function deleteDatosSalidaInvoiceTemp()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spd_Eliminar_Salida_InvoiceTempTable(?)');
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

    public function deleteDatosSalidaDevolucionTemp()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spd_Eliminar_Salida_DevolucionTempTable(?)');
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

    public function deleteEntryPartialRemoveOcTempTable($idEntradaTemp)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spd_Eliminar_EntradaRemove_OCTempTable(?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($idEntradaTemp, $PKuser));

            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function deleteExitRemoveTempTable($idSalidaTemp)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spd_Eliminar_SalidaRemove_TempTable(?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($idSalidaTemp, $PKuser));

            $data[0] = ['status' => $status];
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function deleteEntryTrasladeTempTable()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spd_EliminarEntrada_TraspasoTemp(?)');
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

    public function deleteEntryTransferRemoveTempTable($idEntradaTemp)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spd_Eliminar_Entrada_TraspasoRemove_TempTable(?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($idEntradaTemp, $PKuser));
            $FolioEntrada = $stmt->fetch()['0'];

            $data[0] = ['status' => $status, 'folio' => $FolioEntrada];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function deleteEntryTable($folioEntrada)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];
        $PKEmpresa = $_SESSION["IDEmpresa"];

        try {
            $query = sprintf('call spd_Eliminar_Entrada_Table(?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($folioEntrada, $PKEmpresa, $PKuser));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function deleteEntryTranferTable($folioEntrada)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];
        $PKEmpresa = $_SESSION["IDEmpresa"];

        try {
            $query = sprintf('call spd_Eliminar_EntradaTraspaso_Table(?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($folioEntrada, $PKEmpresa, $PKuser));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function deleteEntryDirectBranchTable($folioEntrada)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];
        $PKEmpresa = $_SESSION["IDEmpresa"];

        try {
            $query = sprintf('call spd_Eliminar_EntradaDirectaSucursal_Table(?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($folioEntrada, $PKEmpresa, $PKuser));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function deleteEntryDirectProviderTable($folioEntrada)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];
        $PKEmpresa = $_SESSION["IDEmpresa"];

        try {
            $query = sprintf('call spd_Eliminar_EntradaDirectaProveedor_Table(?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($folioEntrada, $PKEmpresa, $PKuser));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function deleteEntryDirectCustomerTable($folioEntrada)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];
        $PKEmpresa = $_SESSION["IDEmpresa"];

        try {
            $query = sprintf('call spd_Eliminar_EntradaDirectaCliente_Table(?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($folioEntrada, $PKEmpresa, $PKuser));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function deleteEntryAdjustTable($folioEntrada)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];
        $PKEmpresa = $_SESSION["IDEmpresa"];

        try {
            $query = sprintf('call spd_Eliminar_EntradaAjusteSucursal_Table(?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($folioEntrada, $PKEmpresa, $PKuser));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function deleteDatosSalidaAll($folioSalida)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];
        $PKEmpresa = $_SESSION["IDEmpresa"];

        try {
            $query = sprintf('call spd_Eliminar_SalidaCompleta_Table(?,?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($folioSalida, $PKEmpresa, $PKuser));

            $data[0] = ['status' => $status];

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function deleteEntryRemoveEDTempTable($idEntradaDirecta)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spd_Eliminar_Entrada_DirectaRemove_TempTable(?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($idEntradaDirecta, $PKuser));

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

//var_dump($prueba->getInventory2("todas", ""));
//echo ($prueba);