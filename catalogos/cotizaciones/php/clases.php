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
class save_data
{
    
public function saveDatosClienteCotizacion($nombreComercial, $medioContactoCliente, $vendedor, $telefono, $email, $razonSocial, $rfc, $pais, $estado, $cp, $regimen)
{
    $con = new conectar();
    $db = $con->getDb();

    $PKuser = $_SESSION["PKUsuario"];
    $PKEmpresa = $_SESSION["IDEmpresa"];


    $query = 'SELECT PKEmpleado FROM empleados WHERE empresa_id= :id_empresa LIMIT 1';
    $stmt = $db->prepare($query);
    $stmt->execute(array(':id_empresa' => $PKEmpresa));
    if($vendedor == '' || !$vendedor){
        $vendedor = $stmt->fetch(PDO::FETCH_ASSOC)['PKEmpleado'];
    }
    echo $vendedor;
    

    try {
        $query = 'INSERT INTO clientes(NombreComercial, Telefono, Email, razon_social, rfc, codigo_postal, estatus_prospecto_id, pais_id, estado_id, empresa_id, medio_contacto_id, usuario_creacion_id, usuario_edicion_id, created_at, updated_at, estatus, empleado_id, regimen_fiscal_id) VALUES (:nombreComercial,:telefono,:email,:razonSocial,:rfc,:cp,4,:pais,:estado,:PKEmpresa,:medioContactoCliente,:PKuser,:PKuser2,(SELECT NOW()),(SELECT NOW()),1,:vendedor,:regimen)';
        $stmt = $db->prepare($query);
        if($stmt->execute(array(':nombreComercial' => $nombreComercial, ':telefono' => $telefono, ':email' => $email, ':razonSocial' => $razonSocial, ':rfc' => $rfc, ':cp' => $cp, ':pais' => $pais, ':estado' => $estado, ':PKEmpresa' => $PKEmpresa, ':medioContactoCliente' => $medioContactoCliente, ':PKuser' => $PKuser, ':PKuser2' => $PKuser, ':vendedor' => $vendedor, ':regimen' => $regimen))){
            $idCliente = $db->lastInsertId();
        }

    } catch (PDOException $e) {
        return "Error en Consulta: " . $e->getMessage();
    }

    try {
        $query = 'INSERT INTO direcciones_envio_cliente(Sucursal, Calle, Numero_exterior, Numero_Interior, Municipio, Colonia, CP, Email, Contacto, Telefono, Pais, Estado, FKCliente, predeterminado) VALUES ("Dirección Fiscal","N/A","N/A","N/A","N/A","N/A",45037,"N/A",:nombreComercial,"N/A",146,1,:id_cliente,1)';
        $stmt = $db->prepare($query);
        if($stmt->execute(array(':nombreComercial' => $nombreComercial, ':id_cliente' => $idCliente))){
            return 'exito';
        }

    } catch (PDOException $e) {
        return "Error en Consulta: " . $e->getMessage();
    }

    

    $stmt = null;
    $db = null;
}

}
class get_data
{
    public function getCotizaciones()
    {
        $con = new conectar();
        $db = $con->getDb();
        $envVariables = GetEvn();
        $appUrl = $envVariables['server'];
        $table = "";

        $stmt = $db->prepare('SELECT c.PKCotizacion, c.id_cotizacion_empresa, c.ImporteTotal, cl.rfc as RFC, cl.NombreComercial, cl.PKCliente, ec.tipo_estatus as Estatus ,c.FechaVencimiento, s.sucursal, s.activar_inventario, c.facturacion_directa, c.modificar, c.estatus_factura_id as estatus_factura, vd.Referencia as referencia_venta, vd.PKVentaDirecta 
        FROM cotizacion as c LEFT JOIN estatus_cotizacion as ec ON ec.id = c.estatus_cotizacion_id 
        LEFT JOIN clientes as cl ON cl.PKCliente = c.FKCliente 
        LEFT JOIN sucursales as s ON s.id = c.FKSucursal 
        left join orden_pedido_por_sucursales as ops on ops.numero_cotizacion=c.PKCotizacion
        LEFT JOIN ventas_directas vd ON c.id_cotizacion_empresa = vd.referencia_cotizacion and vd.empresa_id = c.empresa_id
        WHERE c.empresa_id = '.$_SESSION['IDEmpresa'].' ORDER BY c.id_cotizacion_empresa Desc');
        
        $stmt->execute();
        $row = $stmt->fetchAll();
        $table = "";

        foreach ($row as $r) {

            //1 Aceptada
            //2 Facturada
            //3 Cancelada
            //4 Vencida
            //5 Pendiente
            //6 Pagada
            //7 Vendida
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
                case 'Pagada':
                    $estatus = "<span class='left-dot blue-dark-dot'>Pagada</span>";
                    break;
            }

            if($estatus == 'Vendida'){
                $referenciaVenta = "<a href='../ventas_directas/catalogos/ventas/ver_ventas.php?vd=". $r['PKVentaDirecta'] . "' target='_blank'>". $r['referencia_venta'] . "</span>";
            }else{
                $referenciaVenta = "";
            }

            /* switch ($estatus_orden) {
                case '1':
                    $estatus_orden = "<span class='left-dot turquoise-dot'>Nueva</span>";
                    break;
                case '2':
                    $estatus_orden = "<span class='left-dot turquoise-dot'>Nueva FD</span>";
                    break;
                case '3':
                    $estatus_orden = "<span class='left-dot yellow-dot'>Parcialmente Surtida</span>";
                    break;
                case '4':
                    $estatus_orden = "<span class='left-dot yellow-dot'>Parcialmente Surtida FD</span>";
                    break;
                case '5':
                    $estatus_orden = "<span class='left-dot green-dot'>Surtida completa</span>";
                    break;
                case '6':
                    $estatus_orden = "<span class='left-dot green-dot'>Surtida completa FD</span>";
                    break;
                case '7':
                    $estatus_orden = "<span class='left-dot red-dot'>Cerrada</span>";
                    break;
                case '8':
                    $estatus_orden = "<span class='left-dot red-dot'>Cancelada</span>";
                    break;
                case '9':
                    $estatus_orden = "<span class='left-dot turquoise-dot'>Facturado directo</span>";
                    break;
                case '10':
                    $estatus_orden = "<span class='left-dot turquoise-dot'>Facturado almacén</span>";
                    break;
                case '11':
                    $estatus_orden = "<span class='left-dot gray-dot'>Remisionado parcial</span>";
                    break;
                case '12':
                    $estatus_orden = "<span class='left-dot gray-dot'>Remisionado completo</span>";
                    break;
                default:
                    $estatus_orden = "<span class='left-dot yellow-dot'>Pendiente</span>";

            } */

            $enlace = '<a id=\"detalle_cotizacion_icono\" href=\"#\" data-id=\"' . $r['PKCotizacion'] . '\"><i class=\"fas fa-clipboard-list pointer\"></i></a>';
            $estatus_factura = $r['estatus_factura'];

            switch ($estatus_factura) {
                case 1:
                    $estatus_factura = '<span class=\"left-dot turquoise-dot\">Facturado completo</span>';
                    $enlace .= '<br><i data-toggle=\"modal\" data-target=\"#copyVenta\" onclick=\"idCopear(' . $Id . ');\" class=\"fas fa-copy pointer\"></i>';
                    break;
                case 2:
                    $estatus_factura = '<span class=\"left-dot turquoise-dot\">Facturado directo</span>';
                    $enlace .= '<br><i data-toggle=\"modal\" data-target=\"#copyVenta\" onclick=\"idCopear(' . $Id . ');\" class=\"fas fa-copy pointer\"></i>';
                    break;
                case 3:
                    $estatus_factura = '<span class=\"left-dot yellow-dot\">Pendiente de <br>facturar</span>';
                    break;
                case 4:
                    $estatus_factura = '<span class=\"left-dot yellow-dot\">Pendiente de <br>facturar directo</span>';
                    break;
                case 5:
                    $estatus_factura = '<span class=\"left-dot green-dot\">Parcialmente<br> facturado almacén</span>';
                    break;
                case 6:
                    $estatus_factura = '<span class=\"left-dot red-dot\">Cancelada</span>';
                    break;
                case 9:
                    $enlace .= '<br><i data-toggle=\"modal\" data-target=\"#copyVenta\" onclick=\"idCopear(' . $Id . ');\" class=\"fas fa-copy pointer\"></i>';
                    break;
                case 10:
                    $enlace .= '<br><i data-toggle=\"modal\" data-target=\"#copyVenta\" onclick=\"idCopear(' . $Id . ');\" class=\"fas fa-copy pointer\"></i>';
                    break;
            }

            $factura = '<a class=\"btn btn-info\" href=\"functions/facturar.php?id=' . $r['PKCotizacion'] . '\"><i class=\"fas fa-file-invoice\"></i> Facturar</a>&nbsp;&nbsp;';

            //validar si se permite marcar como vendida
            $marcarVendida = '';
            if ($r['Estatus'] == 'Aceptada' && $estatus_factura != 1 && $estatus_factura != 2 && $estatus_factura != 5 && $estatus_factura != 6 && $estatus_factura != 9 && $estatus_factura != 10) {
                $marcarVendida = '<input class=\"form-check-input\" type=\"checkbox\" id=\"cbxMarcarVenta-' . $r['PKCotizacion'] . '\" name=\"cbxMarcarVenta-' . $r['PKCotizacion'] . '\" onchange=\"venderCotizacion(' . $r['PKCotizacion'] . ', this)\">';
            }

            $nombreComercial = str_replace('"', '\"', $r['NombreComercial']);

            //link para detalle del cliente
            //$r['NombreComercial'] = '<a style=\"cursor:pointer\" href=\"'.$appUrl.'catalogos/clientes/catalogos/clientes/detalles_cliente.php?c='.$r['PKCliente'].'\">'.$nombreComercial.'</a>';

            $id_cotizacion_empresa = sprintf("%011d", $r['id_cotizacion_empresa']);
            $html = '<a id=\"detalle_cotizacion\" href=\"detalleCotizacion.php?id=' . $r['PKCotizacion'] . '\" data-id=\"' . $r['PKCotizacion'] . '\">' . $id_cotizacion_empresa . '</a>';
            $table .= '{"id":"' . $r['PKCotizacion'] . '",
                "Referencia":"' . $html . '",
                "Nombre":"' . $nombreComercial . '",
                "RFC":"' . $r['RFC'] . '",
                "Importe":"' . "$" . number_format($r['ImporteTotal'], 2) . '",
                "Sucursal":"' . $r['sucursal'] . '",
                "Facturacion directa": "' . $marcarVendida . '",
                "Acciones": "' . $enlace . '",
                "Estatus": "' . $estatus . '",
                "Estatus orden": "' . $referenciaVenta . '",
                "Estatus factura": "' . $estatus_factura . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getCmbDireccionesEnvio($pkCliente)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf("call spc_Combo_Clientes_DireccionesEnvio(?)");
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkCliente));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);
        $array[]=$_SESSION['IDEmpresa'];
        return $array;
    } 

    public function getDireccionesEnviosCliente($pkCliente)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION['IDEmpresa'];

        $query = sprintf("call spc_Datos_Clientes_DireccionesEnvioPred(?,?)");
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkCliente, $PKEmpresa));

        return $stmt->fetchAll(PDO::FETCH_OBJ);
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

    public function getInventarioSucursal($pkSucursal, $pkProducto)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf("call spc_Info_VentaDirecta_StockInvenSuc(?,?)");
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkSucursal, $pkProducto));

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getVendedor()
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = "SELECT PKEmpleado FROM empleados WHERE empresa_id = :id_empresa LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->execute(array(':id_empresa' => $_SESSION["IDEmpresa"]));

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getDatosCotizacion($PKCotizacion){
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf("call spc_Info_Cotizacion_Datos(?)");
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKCotizacion));

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getDatosListadoProductosCotizacionAdd($PKCotizacion){
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf("SELECT dc.FKProducto, 
                                dc.Cantidad, 
                                dc.Precio, 
                                p.ClaveInterna, 
                                p.Nombre, 
                                csu.Descripcion, 
                                p.FKTipoProducto,
                                co.FKSucursal,
                                (select ifnull(sum(existencia),0) as StockExistencia
                                    from existencia_por_productos
                                    where producto_id = dc.FKProducto
                                    and sucursal_id = co.FKSucursal
                                ) as existencia
                        FROM detalle_cotizacion as dc 
                            INNER JOIN productos as p ON p.PKProducto = dc.FKProducto 
                            INNER JOIN cotizacion as co ON dc.FKCotizacion = co.PKCotizacion
                            LEFT JOIN info_fiscal_productos as ifp ON ifp.FKProducto = p.PKProducto 
                            LEFT JOIN claves_sat_unidades as csu ON csu.PKClaveSATUnidad = ifp.FKClaveSATUnidad 
                        WHERE dc.FKCotizacion = ?");
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKCotizacion));

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getdatosListadoImpuestosProductos($PKProducto, $PKCotizacion){
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf("SELECT i.PKImpuesto, 
                                 i.Nombre, 
                                 i.FKTipoImpuesto as TipoImpuesto, 
                                 i.FKTipoImporte as TipoImporte, 
                                 i.Operacion, 
                                 di.FKProducto, 
                                 di.Tasa 
                          FROM detalleimpuesto  as di 
                                INNER JOIN impuesto as i ON i.PKImpuesto = di.FKImpuesto 
                          WHERE di.FKCotizacion= ? AND FKProducto = ?");
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKCotizacion, $PKProducto));

        return $stmt->fetchAll(PDO::FETCH_OBJ);
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

    public function getSucursalCombo()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Combo_Sucursales_VentaDirecta(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getCmbVendedor()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Combo_Vendedor_Funcion(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getProductoCombo($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        $query = sprintf("call spc_Combo_Productos_VentaDirecta(?,?)");
        $stmt = $db->prepare($query);
        $stmt->execute(array($value, $PKuser));

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getClienteCombo()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Combo_Clientes_VentaDirecta(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getCmbMedioContacto()
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Combo_MediosContactoCliente(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($_SESSION['IDEmpresa']));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }
}
