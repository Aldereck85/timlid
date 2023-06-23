<?php
include_once "clases.php";
$array = "";
if (isset($_REQUEST['clase']) && isset($_REQUEST['funcion'])) {

    switch ($_REQUEST['clase']) {
        case "get_data":
            $data = new get_data();
            switch ($_REQUEST['funcion']) {

                    /////////////////////////TABLAS//////////////////////////////
                case 'get_productosEmpresa':
                    $PKSucursal = $_REQUEST['data'];
                    $json = $data->getProductosEmpresa($PKSucursal); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case 'get_noProductosEmpresa':
                    $json = $data->getNoProductosEmpresa(); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case 'get_productosPorCategoria':
                    $PKSucursal = $_REQUEST['data'];
                    $dataIdCategoria = $_REQUEST['dataIdCategoria'];
                    $dataActivo = $_REQUEST['dataActivo'];
                    $json = $data->getProductosPorCategoria($dataIdCategoria, $dataActivo, $PKSucursal); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case 'get_existenciaProductosEmpresa':
                    $PKSucursal = $_REQUEST['data'];
                    $json = $data->getExistenciaProductosEmpresa($PKSucursal); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case 'get_noExistenciaProductosEmpresa':
                    $json = $data->getNoExistenciaProductosEmpresa(); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case 'get_existenciaProductosPorCategoria':
                    $PKSucursal = $_REQUEST['data'];
                    $dataIdCategoria = $_REQUEST['dataIdCategoria'];
                    $dataActivo = $_REQUEST['dataActivo'];
                    $json = $data->getExistenciaProductosPorCategoria($dataIdCategoria, $dataActivo, $PKSucursal); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case 'get_conteosInventariosPorSucursales':
                    $json = $data->getConteosInventariosPorSucursales(); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case 'get_detalleInventarioPeriodico':
                    $PKSucursal = $_REQUEST['sucursal'];
                    $json = $data->getDetalleInventarioPeriodico($PKSucursal); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case 'get_validation':
                    $PKSucursal = $_REQUEST['data'];
                    $PKDetalle = $_REQUEST['data2'];
                    $Clave = $_REQUEST['data3'];
                    $Valor = $_REQUEST['data4'];
                    $Tipo = $_REQUEST['data5'];
                    $json = $data->getValidacionRepeInvIni($PKSucursal, $PKDetalle, $Clave, $Valor, $Tipo); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case 'get_validationInvPerio':
                    $PKSucursal = $_REQUEST['data'];
                    $PKDetalle = $_REQUEST['data2'];
                    $Clave = $_REQUEST['data3'];
                    $Valor = $_REQUEST['data4'];
                    $json = $data->getValidacionRepeInvPerio($PKSucursal, $PKDetalle, $Clave, $Valor); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case 'get_ValidProductNuevoInvPeriodico':
                    $PKSucursal = $_REQUEST['data'];
                    $PKProducto = $_REQUEST['data2'];
                    $json = $data->getValidProductNuevoInvPeriodico($PKSucursal, $PKProducto); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case 'get_validation_cantidadOC':
                    $Cantidad = $_REQUEST['data'];
                    $idEntrada = $_REQUEST['data2'];
                    $OrdenCompra = $_REQUEST['data3'];
                    $cuentaPagarId = isset($_REQUEST['data4']) ? $_REQUEST['data4'] : 0;
                    
                    $json = $data->getValidacionCantOrdenCompra($Cantidad, $idEntrada, $OrdenCompra, $cuentaPagarId); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case 'get_Ajustes':
                    $PKSucursal = $_REQUEST['data1'];
                    $PKTipo = $_REQUEST['data2'];
                    $PKFolio = $_REQUEST['data3'];
                    $json = $data->getAjustes($PKSucursal, $PKTipo, $PKFolio); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case 'get_AjustesTodos':
                    $json = $data->getAjustesTodos(); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case 'get_BusquedaAjusteNegativo':
                    $PKSucursal = $_REQUEST['data1'];
                    $Valor = $_REQUEST['data2'];
                    $json = $data->getBusquedaAjusteNegativo($PKSucursal, $Valor); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case 'get_BusquedaAjustePositivo':
                    $Valor = $_REQUEST['data1'];
                    $json = $data->getBusquedaAjustePositivo($Valor); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case 'get_MovimientosAjuste':
                    $PKAjuste = $_REQUEST['data'];
                    $json = $data->getMovimientosAjuste($PKAjuste); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case "get_IdAjuste":
                    $json = $data->getIdAjuste(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_ValidacionProductosIncompletos":
                    $PKSucursal = $_REQUEST['data'];
                    $json = $data->getValidacionProductosIncompletos($PKSucursal); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_ValidacionAjusteExistencia":
                    $PKSucursal = $_REQUEST['data1'];
                    $Clave = $_REQUEST['data2'];
                    $Serie = $_REQUEST['data3'];
                    $Lote = $_REQUEST['data4'];
                    $Cantidad = $_REQUEST['data5'];
                    $json = $data->getValidacionAjusteExistencia($PKSucursal, $Clave, $Serie, $Lote, $Cantidad); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case "get_LSC":
                    $PKProducto = $_REQUEST['data'];
                    $json = $data->getLSC($PKProducto); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_ValidarEmpresaAjusteInventario":
                    $json = $data->getValidarEmpresaAjusteInventario(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_TablaReporteGeneralKardex":
                    $Sucursal = $_REQUEST['data'];
                    $json = $data->getTablaReporteGeneralKardex($Sucursal); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case "get_TablaReporteDetalladoKardex":
                    $Sucursal = $_REQUEST['data1'];
                    $TipoMovimiento = $_REQUEST['data2'];
                    $FechaDe = $_REQUEST['data3'];
                    $FechaHasta = $_REQUEST['data4'];
                    $json = $data->getTablaReporteDetalladoKardex($Sucursal, $TipoMovimiento, $FechaDe, $FechaHasta); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case 'get_Cambios':
                    $PKSucursal = $_REQUEST['data1'];
                    $PKTipo = $_REQUEST['data2'];
                    $PKFolio = $_REQUEST['data3'];
                    $json = $data->getCambios($PKSucursal, $PKTipo, $PKFolio); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case 'get_CambiosTodos':
                    $json = $data->getCambiosTodos(); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case 'get_BusquedaCambioSerie':
                    $PKSucursal = $_REQUEST['data1'];
                    $Valor = $_REQUEST['data2'];
                    $json = $data->getBusquedaCambioSerie($PKSucursal, $Valor); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case 'get_BusquedaCambioLote':
                    $PKSucursal = $_REQUEST['data1'];
                    $Valor = $_REQUEST['data2'];
                    $json = $data->getBusquedaCambioLote($PKSucursal, $Valor); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case 'get_MovimientosCambioLoteSerie':
                    $PKCambio = $_REQUEST['data1'];
                    $json = $data->getMovimientosCambiosLoteSerie($PKCambio); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case "get_IdCambio":
                    $json = $data->getIdCambio(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_ValidacionExistenciaCambioLoteSerie":
                    $PKSucursal = $_REQUEST['data1'];
                    $Clave = $_REQUEST['data2'];
                    $Serie = $_REQUEST['data3'];
                    $Lote = $_REQUEST['data4'];
                    $Cantidad = $_REQUEST['data5'];
                    $json = $data->getValidacionExistenciaCambioLoteSerie($PKSucursal, $Clave, $Serie, $Lote, $Cantidad); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case "get_ValidarEmpresaAjusteInventario":
                    $json = $data->getValidarEmpresaAjusteInventario(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_ValidarUnicoLote":
                    $Clave = $_REQUEST['data1'];
                    $Sucursal = $_REQUEST['data2'];
                    $Tipo = $_REQUEST['data3'];
                    $LoteSerie = $_REQUEST['data4'];
                    $json = $data->getValidarUnicoLote($Clave, $Sucursal, $Tipo, $LoteSerie); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                /*case 'get_entriesTable':
                    $json = $data->getEntriesTable(); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;*/
                case 'get_entriesSelect':
                    $json = $data->getEntriesSelect(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_brands":
                    $json = $data->getBrands(); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                    //JAVIER RAMIREZ
                case 'get_entriesTable':
                    $json = $data->getEntriesTable(); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case 'get_entriesTableFilter':
                    $branchID = $_REQUEST['data'];
                    $typeEntryID = $_REQUEST['data2'];
                    $fromDate = $_REQUEST['data3'];
                    $toDate = $_REQUEST['data4'];
                    $json = $data->getEntriesTableFilter($branchID, $typeEntryID, $fromDate, $toDate); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case 'get_exitsTable':
                    $json = $data->getExitsTable(); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case 'get_extisTableFilter':
                    $branchID = $_REQUEST['data'];
                    $typeExitID = $_REQUEST['data2'];
                    $fromDate = $_REQUEST['data3'];
                    $toDate = $_REQUEST['data4'];
                    $json = $data->getExitsTableFilter($branchID, $typeExitID, $fromDate, $toDate); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case "get_purchaseOrders":
                    $permissionEdit = $_REQUEST['data'];
                    $json = $data->getPurchaseOrders($permissionEdit); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case "get_ordenesCompraTable":
                    $pkUsuario = $_REQUEST['data'];
                    $json = $data->getOrdenesCompraTable($pkUsuario); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case "get_ordenesCompraTableEdit":
                    $pkOrden = $_REQUEST['data'];
                    $json = $data->getOrdenesCompraTableEdit($pkOrden); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case "get_ordenesCompraTableVer":
                    $pkOrden = $_REQUEST['data'];
                    $json = $data->getOrdenesCompraTableVer($pkOrden); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case "get_productosOrdenesCompraTable":
                    $pkOrden = $_REQUEST['data'];
                    $json = $data->getProductosOrdenesCompraTable($pkOrden); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case "get_productosTraspasoTempTable":
                    $folioSalida = $_REQUEST['data'];
                    $json = $data->getProductosTraspasoTempTable($folioSalida); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case "get_productosEntradaDirectaTempTable":
                    $sucOrigen = $_REQUEST['data'];
                    $json = $data->getProductosEntradaDirectaTempTable($sucOrigen); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case "get_productosEntradaDirectaTempTableNoEdit":
                    $sucOrigen = $_REQUEST['data'];
                    $json = $data->getProductosEntradaDirectaTempTableNoEdit($sucOrigen); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case "get_productosEntradaDirectaTempTableProvider":
                    $proveedor = $_REQUEST['data'];
                    $json = $data->getProductosEntradaDirectaTempTableProvider($proveedor); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case "get_productosEntradaDirectaTempTableProviderNoEdit":
                    $proveedor = $_REQUEST['data'];
                    $json = $data->getProductosEntradaDirectaTempTableProviderNoEdit($proveedor); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case "get_productosEntradaDirectaTempTableCustomer":
                    $cliente = $_REQUEST['data'];
                    $json = $data->getProductosEntradaDirectaTempTableCustomer($cliente); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case "get_productosEntradaDirectaTempTableCustomerNoEdit":
                    $cliente = $_REQUEST['data'];
                    $json = $data->getProductosEntradaDirectaTempTableCustomerNoEdit($cliente); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case "get_productosSucursalEDTable":
                    $sucOrigen = $_REQUEST['data'];
                    $json = $data->getProductosSucursalEDTable($sucOrigen); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case "get_productosProveedorEDTable":
                    $proveedor = $_REQUEST['data'];
                    $json = $data->getProductosProveedorEDTable($proveedor); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case "get_productosCustomerEDTable":
                    $cliente = $_REQUEST['data'];
                    $json = $data->getProductosCustomerEDTable($cliente); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case "get_productosEntradaOCTempTable":
                    $pkOrden = $_REQUEST['data'];
                    $json = $data->getProductosEntradaOCTempTable($pkOrden); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case "get_productosEntradaOCTempTableEdit":
                    $folioEntrada = $_REQUEST['data'];
                    $json = $data->getProductosEntradaOCTempTableEdit($folioEntrada); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case "get_productosEntradaTransferTempTableEdit":
                    $folioEntrada = $_REQUEST['data'];
                    $json = $data->getProductosEntradaTransferTempTableEdit($folioEntrada); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case "get_productosEntradaOCTable":
                    $folioEntrada = $_REQUEST['data'];
                    $json = $data->getProductosEntradaOCTable($folioEntrada); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case "get_productosEntradaTransferTable":
                    $folioEntrada = $_REQUEST['data'];
                    $json = $data->getProductosEntradaTranferTable($folioEntrada); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case "get_productoSalidaOPTempTable":
                    $pkOrdenPedido = $_REQUEST['data'];
                    $json = $data->getProductoSalidaOPTempTable($pkOrdenPedido); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case "get_productoSalidaOPTempTableEdicion":
                    $folioSalida = $_REQUEST['data'];
                    $json = $data->getProductoSalidaOPTempTableEdicion($folioSalida); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case "get_productoSalidaDevolucionTempTableEdicion":
                    $folioSalida = $_REQUEST['data'];
                    $json = $data->getProductoSalidaDevolucionTempTableEdicion($folioSalida); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case "get_productoSalidaOPTempTableEditModal":
                    $pkOrdenPedido = $_REQUEST['data'];
                    $json = $data->getProductoSalidaOPTempTableEditModal($pkOrdenPedido); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case "get_productoSalidaDevolucionTempTable":
                    $id_cuenta_pagar = $_REQUEST['data'];
                    $json = $data->getProductoSalidaDevolucionTempTable($id_cuenta_pagar); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case "get_productoSalidaOPTableEdit":
                    $folioSalida = $_REQUEST['data'];
                    $json = $data->getProductoSalidaOPTableEdit($folioSalida); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case "get_inventory":
                    $json = $data->getInventory($_REQUEST['sucu'], $_REQUEST['cate'], $_REQUEST['exist']); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                case "get_inventory2":
                    $json = $data->getInventory2($_REQUEST['sucu'], $_REQUEST['exist']); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case "get_productsTable":
                    $json = $data->getProductsTable(); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case "get_clavesSATTable":
                    $buscador = $_REQUEST['data'];
                    $json = $data->getClavesSATTable($buscador); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case "get_unidadesSATTable":
                    $buscador = $_REQUEST['data'];
                    $json = $data->getUnidadesSATTable($buscador); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case "get_impuestoProductoTable":
                    $pkProducto = $_REQUEST['data'];
                    $permissionEdit = $_REQUEST['data2'];
                    $json = $data->getImpuestoProductoTable($pkProducto, $permissionEdit); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case "get_accionProductoTable":
                    $pkProducto = $_REQUEST['data'];
                    $json = $data->getAccionProductoTable($pkProducto); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case "get_accionProductoTableTemp":
                    $pkUsuario = $_REQUEST['data'];
                    $json = $data->getAccionProductoTableTemp($pkUsuario); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case "get_categoriasTable":
                    $json = $data->getCategoriasTable(); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case "get_marcasTable":
                    $json = $data->getMarcasTable(); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case "get_tipoProductoTable":
                    $json = $data->getTipoProductoTable(); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case "get_tipoOrdenInventarioTable":
                    $value = $_REQUEST['value'];
                    $json = $data->getTipoOrdenInventarioTable($value); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case "get_proveedorTable":
                    $pkProducto = $_REQUEST['data'];
                    $permissionEdit = $_REQUEST['data2'];
                    $json = $data->getProveedorTable($pkProducto, $permissionEdit); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case "get_productoProveTable":
                    $pkProveedor = $_REQUEST['data'];
                    $permissionEdit = $_REQUEST['data2'];
                    $json = $data->getProductoProveTable($pkProveedor, $permissionEdit); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case "get_clientesTable":
                    $pkProducto = $_REQUEST['data'];
                    $permissionEdit = $_REQUEST['data2'];
                    $json = $data->getClientesTable($pkProducto, $permissionEdit); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;

                    /* PROVEEDORES */
                case "get_proveedoresTable":
                    $json = $data->getProveedoresTable(); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case "get_razonSocial_proveedoresTable":
                    $pkProveedor = $_REQUEST['data'];
                    $permissionEdit = $_REQUEST['data2'];
                    $json = $data->getRazonSocialProveedoresTable($pkProveedor, $permissionEdit); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case "get_direccionEnvio_proveedoresTable":
                    $pkProveedor = $_REQUEST['data'];
                    $json = $data->getDireccionEnvioProveedoresTable($pkProveedor); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case "get_contacto_proveedoresTable":
                    $pkProveedor = $_REQUEST['data'];
                    $permissionEdit = $_REQUEST['data2'];
                    $json = $data->getContactoProveedoresTable($pkProveedor, $permissionEdit); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case "get_banco_proveedoresTable":
                    $pkProveedor = $_REQUEST['data'];
                    $permissionEdit = $_REQUEST['data2'];
                    $json = $data->getBancoProveedoresTable($pkProveedor, $permissionEdit); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                    /* END PROVEEDORES */

                    /////////////////////////COMBOS//////////////////////////////
                case "get_cmb_estatusGral":
                    $json = $data->getCmbEstatusGral(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_cmb_categoria":
                    $json = $data->getCmbCategoria(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_cmb_marca":
                    $json = $data->getCmbMarca(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_cmb_tipo":
                    $json = $data->getCmbTipo(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_cmb_tipo_orden":
                    $json = $data->getCmbTipoOrden(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_cmb_costouni_compra":
                    $json = $data->getCmbCostouniCompra(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_cmb_costouni_compraEdit":
                    $json = $data->getCmbCostouniCompraEdit(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_cmb_costouni_venta":
                    $json = $data->getCmbCostouniVenta(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_cmb_impuestos":
                    $json = $data->getCmbImpuestos(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_cmb_tasa_impuestos":
                    $pkImpuesto = $_REQUEST['data'];
                    $json = $data->getCmbTasaImpuestos($pkImpuesto); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_cmb_acciones_producto":
                    $json = $data->getCmbAccionesProducto(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_cmb_productos":
                    $PKProducto = $_REQUEST['data'];
                    $json = $data->getCmbProductos($PKProducto); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case "get_cmb_proveedor":
                    $json = $data->getCmbProveedor(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_cmb_proveedorEdit":
                    $json = $data->getCmbProveedorEdit(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_cmb_unidadM_proveedor":
                    $pkProveedor = $_REQUEST['data'];
                    $json = $data->getCmbUnidadMProveedor($pkProveedor); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_cmb_producto_cliente":
                    $json = $data->getCmbProductoCliente(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_cmb_producto_proveedor":
                    $json = $data->getCmbProductoProveedor(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;

                    /* PROVEEDORES */
                case "get_cmb_mediosContacto":
                    $json = $data->getCmbMediosContacto(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_cmb_vendedor":
                    $json = $data->getCmbVendedor(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_cmb_paises":
                    $json = $data->getCmbPaises(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_cmb_estados":
                    $pkPais = $_REQUEST['data'];
                    $json = $data->getCmbEstados($pkPais); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_cmb_banco":
                    $json = $data->getCmbBanco(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_cmb_costouni_ventaEsp":
                    $json = $data->getCmbCostouniVentaEsp(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                    /* END PROVEEDORES */
                case "get_cmb_purchaseOrderEntry":
                    $pkProveedor = $_REQUEST['data'];
                    $json = $data->getCmbPurchaseOrderEntry($pkProveedor); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_cmb_noDocsExit":
                    $pkProveedor = $_REQUEST['data'];
                    $json = $data->getCmbNoDocsExit($pkProveedor); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_cmb_providerEntry":
                    //$pkOrdenCompra = $_REQUEST['data'];
                    $json = $data->getCmbProviderEntry(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_cmb_branchEntry":
                    $pkOrdenCompra = $_REQUEST['data'];
                    $json = $data->getCmbBranchEntry($pkOrdenCompra); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_cmb_branchEntryFilter":
                    $json = $data->getCmbBranchEntryFilter(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_cmb_branchExit":
                    $idCuentaPorPagar = $_REQUEST['data'];
                    $json = $data->getCmbBranchExit($idCuentaPorPagar); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_cmb_typeEntryFilter":
                    $json = $data->getCmbTypeEntryFilter(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_cmb_typeExitFilter":
                    $json = $data->getCmbTypeExitFilter(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_cmb_productsEntry":
                    $pkOrdenCompra = $_REQUEST['data'];
                    $json = $data->getCmbProductsEntry($pkOrdenCompra); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case 'get_cmb_sucursales':
                    $json = $data->getCmbSucursales(); //Guardando el return de la función
                    echo json_encode($json);
                    return;
                    break;
                case 'get_cmb_sucursalesInvPeriodico':
                    $json = $data->getCmbSucursalesInvPeriodico(); //Guardando el return de la función
                    echo json_encode($json);
                    return;
                    break;
                case 'get_cmb_productosInvPeriodico':
                    $json = $data->getCmbProductosInvPeriodico(); //Guardando el return de la función
                    echo json_encode($json);
                    return;
                    break;
                case 'get_dataSucursal':
                    $PKSucursal = $_REQUEST['data'];
                    $json = $data->getDataSucursales($PKSucursal); //Guardando el return de la función
                    echo json_encode($json);
                    return;
                    break;
                case 'get_cmb_sucursalesAjuste':
                    $json = $data->getCmbSucursalesAjuste(); //Guardando el return de la función
                    echo json_encode($json);
                    return;
                    break;
                case 'get_cmb_foliosAjuste':
                    $PKSucursal = $_REQUEST['data1'];
                    $PKTipo = $_REQUEST['data2'];
                    $json = $data->getCmbFoliosAjuste($PKSucursal, $PKTipo); //Guardando el return de la función
                    echo json_encode($json);
                    return;
                    break;
                case "get_cmbClaves":
                    $json = $data->getcmbClaves(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "get_cmbSucursalesKardex":
                    $json = $data->getcmbSucursalesKardex(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case 'get_cmb_foliosCambiosLote':
                    $PKSucursal = $_REQUEST['data1'];
                    $PKTipo = $_REQUEST['data2'];
                    $json = $data->getCmbFoliosCambiosLote($PKSucursal, $PKTipo); //Guardando el return de la función
                    echo json_encode($json);
                    return;
                    break;
                case "get_cmb_branch_origin":
                    $json = $data->getCmbBranchOrigin(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_cmb_branch_directEntry":
                    $json = $data->getCmbBranchDirectEntry(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_cmb_branch_directEntry_OriginED":
                    $PKSucursal = $_REQUEST['data'];
                    $json = $data->getCmbBranchDirectEntryOriginED($PKSucursal); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_cmb_provider_directEntry_ED":
                    $PKSucursal = $_REQUEST['data'];
                    $json = $data->getCmbProviderDirectEntryED($PKSucursal); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_oc_provedoor":
                    $PKProveedor = $_REQUEST['data'];
                    $sucursalDestino = $_REQUEST['data2'];
                    $json = $data->getOCprovedoor($PKProveedor, $sucursalDestino); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_cmb_customer_directEntry_ED":
                    $PKSucursal = $_REQUEST['data'];
                    $json = $data->getCmbCustomerDirectEntryED($PKSucursal); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_cmb_tipo_directEntry_Provider_ED":
                    $json = $data->getCmbTipoDirectEntryProviderED(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_cmb_branch_typeOrigin_directEntry":
                    $json = $data->getCmbBranchTypeOriginDirectEntry(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_cmb_branch_destination":
                    $pkOrdenPedido = $_REQUEST['data'];
                    $json = $data->getCmbBranchDestination($pkOrdenPedido); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_cmb_branch_or_customer":
                    $pkOrdenPedido = $_REQUEST['data'];
                    $json = $data->getCmbBranchOrCustomer($pkOrdenPedido); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_cmb_branch_origin_exit":
                    $folioSalida = $_REQUEST['data'];
                    $json = $data->getCmbBranchOriginExit($folioSalida); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_cmb_order_pedido":
                    $pkSucursalOrigen = $_REQUEST['data'];
                    $json = $data->getCmbOrderPedido($pkSucursalOrigen); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_cmb_order_pedidoGral":
                    $pkSucursalOrigen = $_REQUEST['data'];
                    $json = $data->getCmbOrderPedidoGral($pkSucursalOrigen); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_cmb_quotes":
                    $pkSucursalOrigen = $_REQUEST['data'];
                    $json = $data->getCmbQuotes($pkSucursalOrigen); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_cmb_sales":
                    $pkSucursalOrigen = $_REQUEST['data'];
                    $json = $data->getCmbSales($pkSucursalOrigen); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_cmb_traspaso_entrada":
                    $pkSucursalDestino = $_REQUEST['data'];
                    $json = $data->getCmbTraspasoEntrada($pkSucursalDestino); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_cmb_dispenser_exit":
                    $json = $data->getCmbDispenserExit(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_cmb_comprador":
                    $json = $data->getCmbComprador(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break; 
                case "get_cmb_condicionPago":
                    $json = $data->getCmbCondicionPago();//Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "get_cmb_Moneda":
                    $json = $data->getCmbMoneda();//Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "get_cmb_sucursales_productos":
                    $json = $data->getCmbSucursalesProductos();//Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;   
                    /////////////////////////DATOS PARA EDICIÓN//////////////////////////////
                case "get_categorias_productos_empresa":
                    $pkSucursal = $_REQUEST['data'];
                    $json = $data->getCategoriasProductosEmpresa($pkSucursal); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_datos_categoria":
                    $pkCategoria = $_REQUEST['datos'];
                    $json = $data->getDatosCategoria($pkCategoria); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_cmb_clientes_producto":
                    $json = $data->getCmbClientesProducto(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_datos_marca":
                    $pkMarca = $_REQUEST['datos'];
                    $json = $data->getDatosMarca($pkMarca); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_datos_tipoProducto":
                    $pkTipoProducto = $_REQUEST['datos'];
                    $json = $data->getDatosTipoProducto($pkTipoProducto); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_datos_tipoOrdenInventario":
                    $pkTipoOrdenInventario = $_REQUEST['datos'];
                    $json = $data->getDatosTipoOrdenInventario($pkTipoOrdenInventario); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_DataDatosProducto":
                    $pkProducto = $_REQUEST['data'];
                    $json = $data->getDataDatosProducto($pkProducto); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_DataDatosProductoCompuesto":
                    $pkProducto = $_REQUEST['data'];
                    $pkUsuario = $_REQUEST['data2'];
                    $json = $data->getDataDatosProductoCompuesto($pkProducto, $pkUsuario); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_DataEntryOC":
                    $folioEntrada = $_REQUEST['data'];
                    $json = $data->getDataEntryOC($folioEntrada); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_DataExitOP":
                    $folioSalida = $_REQUEST['data'];
                    $json = $data->getDataExitOP($folioSalida); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_DataExitCoti":
                    $folioSalida = $_REQUEST['data'];
                    $json = $data->getDataExitCoti($folioSalida); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_DataExitVenta":
                    $folioSalida = $_REQUEST['data'];
                    $json = $data->getDataExitVenta($folioSalida); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_DataExitDevolucion":
                    $folioSalida = $_REQUEST['data'];
                    $json = $data->getDataExitDevolucion($folioSalida); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_DataEntryTransfer":
                    $folioEntrada = $_REQUEST['data'];
                    $json = $data->getDataEntryTransfer($folioEntrada); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_DataEntryTransferEdit":
                    $folioEntrada = $_REQUEST['data'];
                    $json = $data->getDataEntryTransferEdit($folioEntrada); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_DataEntryDirectEdit":
                    $folioEntrada = $_REQUEST['data'];
                    $json = $data->getDataEntryDirectEdit($folioEntrada); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_DataEntryDirectProviderEdit":
                    $folioEntrada = $_REQUEST['data'];
                    $json = $data->getDataEntryDirectProviderEdit($folioEntrada); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_DataEntryDirectCustomerEdit":
                    $folioEntrada = $_REQUEST['data'];
                    $json = $data->getDataEntryDirectCustomerEdit($folioEntrada); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_DataEntryOCEdit":
                    $folioEntrada = $_REQUEST['data'];
                    $json = $data->getDataEntryOCEdit($folioEntrada); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_DataFiscalProducto":
                    $pkProducto = $_REQUEST['data'];
                    $json = $data->getDataFiscalProducto($pkProducto); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_datos_proveedor_producto":
                    $PKdatosProveedor = $_REQUEST['datos'];
                    $json = $data->getDatosProveedorProducto($PKdatosProveedor); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_datos_producto_proveedor":
                    $PKdatosProveedor = $_REQUEST['datos'];
                    $json = $data->getDatosProductoProveedor($PKdatosProveedor); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_DataPestanaInventario":
                    $id = $_REQUEST['data'];
                    $json = $data->getDataPestanaInventario($id); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_DataInventarioProducto":
                    $pkProducto = $_REQUEST['data'];
                    $json = $data->getDataInventarioProducto($pkProducto); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_DataVentaProducto":
                    $pkProducto = $_REQUEST['data'];
                    $json = $data->getDataVentaProducto($pkProducto); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                    /* PROVEEDORES */
                case "get_datos_fiscal_proveedor":
                    $pkRazonSocial = $_REQUEST['datos'];
                    $json = $data->getDatosFiscalProveedor($pkRazonSocial); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_datos_direccionEnvio_proveedor":
                    $pkDireccionEnvio = $_REQUEST['datos'];
                    $json = $data->getDatosDireccionEnvioProveedor($pkDireccionEnvio); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_datos_contacto_proveedor":
                    $pkContacto = $_REQUEST['datos'];
                    $json = $data->getDatosContactoProveedor($pkContacto); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_datos_cuentaBancaria_proveedor":
                    $pkCuentaBancaria = $_REQUEST['datos'];
                    $json = $data->getDatosCuentaBancariaProveedor($pkCuentaBancaria); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_datos_generales_proveedor":
                    $pkProveedor = $_REQUEST['datos'];
                    $json = $data->getDatosGeneralesProveedor($pkProveedor); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                    /* END PROVEEDORES */
                case "get_subTotalOrdenCompraTemp":
                    $pkUsuario = $_REQUEST['datos'];
                    $json = $data->getSubTotalOrdenCompraTemp($pkUsuario); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_subTotalOrdenCompra":
                    $pkOrden = $_REQUEST['datos'];
                    $json = $data->getSubTotalOrdenCompra($pkOrden); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_subTotalEntradaOCTemp":
                    $pkOrden = $_REQUEST['datos'];
                    $json = $data->getSubTotalEntradaOCTemp($pkOrden); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_subTotalEntradaOCTempEdit":
                    $folioEntrada = $_REQUEST['datos'];
                    $json = $data->getSubTotalEntradaOCTempEdit($folioEntrada); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_subTotalEntradaEDProviderTemp":
                    $json = $data->getSubTotalEntradaEDProviderTemp(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_impuestoOrdenCompraTemp":
                    $pkUsuario = $_REQUEST['datos'];
                    $json = $data->getImpuestoOrdenCompraTemp($pkUsuario); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_impuestoOrdenCompra":
                    $pkOrden = $_REQUEST['datos'];
                    $isEdit = $_REQUEST['datos2'];
                    $json = $data->getImpuestoOrdenCompra($pkOrden, $isEdit); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_totalOrdenCompraTemp":
                    $pkUsuario = $_REQUEST['datos'];
                    $json = $data->getTotalOrdenCompraTemp($pkUsuario); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_totalOrdenCompra":
                    $pkOrden = $_REQUEST['datos'];
                    $isEdit = $_REQUEST['datos2'];
                    $json = $data->getTotalOrdenCompra($pkOrden, $isEdit); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_datos_Comentario":
                    $PKOrdenCompraEncrypted = $_REQUEST['data'];
                    $json = $data->getDatosComentario($PKOrdenCompraEncrypted); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_datos_OrdenCompraPDF":
                    $PKOrdenCompraEncrypted = $_REQUEST['data'];
                    $PKUsuario = $_REQUEST['data2'];
                    $json = $data->getDatosOrdenCompraPDF($PKOrdenCompraEncrypted, $PKUsuario); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_datos_OrdenCompraPDFOffLine":
                    $PKOrdenCompraEncrypted = $_REQUEST['data'];
                    $json = $data->getDatosOrdenCompraPDFOffLine($PKOrdenCompraEncrypted); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_datosProd_OrdenCompraPDF":
                    $PKOrdenCompraEncrypted = $_REQUEST['data'];
                    $json = $data->getDatosProdOrdenCompraPDF($PKOrdenCompraEncrypted); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_datosImpu_OrdenCompraPDF":
                    $PKOrdenCompraEncrypted = $_REQUEST['data'];
                    $json = $data->getDatosImpuOrdenCompraPDF($PKOrdenCompraEncrypted); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_datos_OrdenCompra":
                    $PKOrdenCompra = $_REQUEST['data'];
                    $json = $data->getDatosOrdenCompra($PKOrdenCompra); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_info_CantidadProductoOC":
                    $idProducto = $_REQUEST['data'];
                    $idOrdenC = $_REQUEST['data2'];
                    $json = $data->getInfoCantidadProductoOC($idProducto, $idOrdenC); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_subTotalEntradaOC":
                    $pkOrden = $_REQUEST['datos'];
                    $impuestosActive = $_REQUEST['datos2'];
                    $json = $data->getSubTotalEntradaOC($pkOrden, $impuestosActive); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_VersubTotalEntradaOC":
                    $folioEntrada = $_REQUEST['datos'];
                    $json = $data->getVerSubTotalEntradaOC($folioEntrada); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_impuestoEntradaOC":
                    $pkOrden = $_REQUEST['datos'];
                    $json = $data->getImpuestoEntradaOC($pkOrden); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_impuestoEntradaOCEdit":
                    $folioEntrada = $_REQUEST['datos'];
                    $json = $data->getImpuestoEntradaOCEdit($folioEntrada); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_impuestoEntradaEDProviderTemp":
                    $json = $data->getImpuestoEntradaEDProviderTemp(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_VerimpuestoEntradaOC":
                    $folioEntrada = $_REQUEST['datos'];
                    $json = $data->getVerImpuestoEntradaOC($folioEntrada); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_totalEntradaOC":
                    $pkOrden = $_REQUEST['datos'];
                    $json = $data->getTotalEntradaOC($pkOrden); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_totalEntradaOCVer":
                    $folioEntrada = $_REQUEST['datos'];
                    $json = $data->getTotalEntradaOCVer($folioEntrada); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_totalEntradaEDProviderTemp":
                    $json = $data->getTotalEntradaEDProviderTemp(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_totalEntradaTranferVer":
                    $folioEntrada = $_REQUEST['datos'];
                    $json = $data->getTotalEntradaTransferVer($folioEntrada); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_totalEntradaOCEdit":
                    $folioEntrada = $_REQUEST['datos'];
                    $json = $data->getTotalEntradaOCEdit($folioEntrada); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_totalEntradaTransferEdit":
                    $folioEntrada = $_REQUEST['datos'];
                    $json = $data->getTotalEntradaTransferEdit($folioEntrada); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_total_entrada_traspasoTemp":
                    $idEntrada = $_REQUEST['data'];
                    $folioSalida = $_REQUEST['data2'];
                    $json = $data->getTotalEntradaTraspasoTemp($idEntrada, $folioSalida); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_DataDatosSalidaCantTemp":
                    $pkProducto = $_REQUEST['data'];
                    $ordenPedido = $_REQUEST['data2'];
                    $json = $data->getDataDatosSalidaCantTemp($pkProducto, $ordenPedido); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_DataDatosSalidaCantTempEdicion":
                    $pkProducto = $_REQUEST['data'];
                    $folioSalida = $_REQUEST['data2'];
                    $json = $data->getDataDatosSalidaCantTempEdicion($pkProducto, $folioSalida); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_totalSalidaOP":
                    $pkOrden = $_REQUEST['datos'];
                    $json = $data->getTotalSalidaOP($pkOrden); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_totalSalidaOPVer":
                    $folioSalida = $_REQUEST['datos'];
                    $json = $data->getTotalSalidaOPVer($folioSalida); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_totalSalidaOPEdicion":
                    $folioSalida = $_REQUEST['datos'];
                    $json = $data->getTotalSalidaOPEdicion($folioSalida); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_DataQuoteExit":
                    $pkOrdenPedido = $_REQUEST['data'];
                    $json = $data->getDataQuoteExit($pkOrdenPedido); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_DataSaleExit":
                    $pkOrdenPedido = $_REQUEST['data'];
                    $json = $data->getDataSaleExit($pkOrdenPedido); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_DataNoDocExit":
                    $id_cuenta_pagar = $_REQUEST['data'];
                    $json = $data->getDataNoDocExit($id_cuenta_pagar); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_Data_Tipo_SalidaPedido":
                    $idOrdenPedido = $_REQUEST['data'];
                    $json = $data->getDataTipoSalidaPedido($idOrdenPedido); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                    /////////////////////////VALIDACIONES//////////////////////////////
                case "validar_claveInterna":
                    $clave = $_REQUEST['data'];
                    $json = $data->validarClaveInterna($clave); //Guardando el return de la función
                    echo json_encode($json);
                    break;
                case "validar_nombre":
                    $clave = $_REQUEST['data'];
                    $json = $data->validarNombre($clave); //Guardando el return de la función
                    echo json_encode($json);
                    break;
                case "validar_codigoBarras":
                    $codigo = $_REQUEST['data'];
                    $json = $data->validarCodigoBarras($codigo); //Guardando el return de la función
                    echo json_encode($json);
                    break;
                case "validar_impuestoProducto":
                    $pkProducto = $_REQUEST['data'];
                    $pkImpuesto = $_REQUEST['data2'];
                    $json = $data->validarImpuestoProducto($pkProducto, $pkImpuesto); //Guardando el return de la función
                    echo json_encode($json);
                    break;
                case "validar_accionProducto":
                    $pkAccion = $_REQUEST['data'];
                    $pkProducto = $_REQUEST['data2'];
                    $json = $data->validarAccionProducto($pkAccion, $pkProducto); //Guardando el return de la función
                    echo json_encode($json);
                    break;
                case "validar_accionProducto_temp":
                    $pkAccion = $_REQUEST['data'];
                    $pkUsuario = $_REQUEST['data2'];
                    $json = $data->validarAccionProductoTemp($pkAccion, $pkUsuario); //Guardando el return de la función
                    echo json_encode($json);
                    break;
                case "validar_categoriaProducto":
                    $categoria = $_REQUEST['data'];
                    $json = $data->validarCategoriaProducto($categoria); //Guardando el return de la función
                    echo json_encode($json);
                    break;
                case "validar_marcaProducto":
                    $marca = $_REQUEST['data'];
                    $json = $data->validarMarcaProducto($marca); //Guardando el return de la función
                    echo json_encode($json);
                    break;
                case "validar_tipoProducto":
                    $tipo = $_REQUEST['data'];
                    $json = $data->validarTipoProducto($tipo); //Guardando el return de la función
                    echo json_encode($json);
                    break;
                case "validar_tipoOrdenInventario":
                    $tipo = $_REQUEST['data'];
                    $json = $data->validarTipoOrdenInventario($tipo); //Guardando el return de la función
                    echo json_encode($json);
                    break;
                case "validar_producto_compuesto_temp":
                    $pkUsuario = $_REQUEST['data'];
                    $pkProducto = $_REQUEST['data2'];
                    $json = $data->validarProductoCompuestoTemp($pkUsuario, $pkProducto); //Guardando el return de la función
                    echo json_encode($json);
                    break;
                case "validar_proveedorProducto":
                    $pkProducto = $_REQUEST['data'];
                    $pkProveedor = $_REQUEST['data2'];
                    $json = $data->validarProveedorProducto($pkProveedor, $pkProducto); //Guardando el return de la función
                    echo json_encode($json);
                    break;
                case "validar_claveProveedorProducto":
                    $pkProveedor = $_REQUEST['data'];
                    $clave = $_REQUEST['data2'];
                    $json = $data->validarClaveProveedorProducto($pkProveedor, $clave); //Guardando el return de la función
                    echo json_encode($json);
                    break;
                case "validar_clienteProducto":
                    $pkCliente = $_REQUEST['data'];
                    $pkProducto = $_REQUEST['data2'];
                    $json = $data->validarClienteProducto($pkCliente, $pkProducto); //Guardando el return de la función
                    echo json_encode($json);
                    break;

                    /* PROVEEDORES */
                case "validar_medioContactoProveedor":
                    $medioContacto = $_REQUEST['data'];
                    $json = $data->validarMedioContactoProveedor($medioContacto); //Guardando el return de la función
                    echo json_encode($json);
                    break;
                case "validar_nombreComercial":
                    $nombreComercial = $_REQUEST['data'];
                    $json = $data->validarNombreComercial($nombreComercial); //Guardando el return de la función
                    echo json_encode($json);
                    break;
                case "validar_estado":
                    $estado = $_REQUEST['data'];
                    $PKPais = $_REQUEST['data2'];
                    $json = $data->validarEstado($estado, $PKPais); //Guardando el return de la función
                    echo json_encode($json);
                    break;
                case "validar_razonSocial_Proveedor":
                    $razonSocial = $_REQUEST['data'];
                    $PKProveedor = $_REQUEST['data2'];
                    $json = $data->validarRazonSocialProveedor($razonSocial, $PKProveedor); //Guardando el return de la función
                    echo json_encode($json);
                    break;
                case "validar_rfc_Proveedor":
                    $rfc = $_REQUEST['data'];
                    $PKProveedor = $_REQUEST['data2'];
                    $json = $data->validarRfcProveedor($rfc, $PKProveedor); //Guardando el return de la función
                    echo json_encode($json);
                    break;
                case "validar_contacto_proveedor":
                    /*$nombreContacto = $_REQUEST['data'];
                    $apellidoContacto = $_REQUEST['data2'];
                    $puesto = $_REQUEST['data3'];*/
                    $email = $_REQUEST['data4'];
                    $PKProveedor = $_REQUEST['data5'];
                    $json = $data->validarContactoProveedor($email, $PKProveedor); //Guardando el return de la función
                    echo json_encode($json);
                    break;
                case "validar_noCuenta":
                    $noCuenta = $_REQUEST['data'];
                    $json = $data->validarNoCuenta($noCuenta); //Guardando el return de la función
                    echo json_encode($json);
                    break;
                case "validar_CLABE":
                    $clabe = $_REQUEST['data'];
                    $json = $data->validarCLABE($clabe); //Guardando el return de la función
                    echo json_encode($json);
                    break;
                case "validar_datosBanarios_proveedor":
                    $pkBanco = $_REQUEST['data'];
                    $noCuenta = $_REQUEST['data2'];
                    $clabe = $_REQUEST['data3'];
                    $pkProveedor = $_REQUEST['data4'];
                    $json = $data->validarDatosBanariosProveedor($pkBanco, $noCuenta, $clabe, $pkProveedor); //Guardando el return de la función
                    echo json_encode($json);
                    break;
                    /*case "validar_producto_Proveedor":
                    $pkProducto = $_REQUEST['data'];
                    $pkProveedor = $_REQUEST['data2'];
                    $json = $data->validarProductoProveedor($pkProducto, $pkProveedor); //Guardando el return de la función
                    echo json_encode($json);
                break;*/
                case "validar_sucursal_Proveedor":
                    $sucursal = $_REQUEST['data'];
                    $pkProveedor = $_REQUEST['data2'];
                    $json = $data->validarSucursalProveedor($sucursal, $pkProveedor); //Guardando el return de la función
                    echo json_encode($json);
                    break;
                case "validar_productoOrdenCompra":
                    $pkProducto = $_REQUEST['data'];
                    $pkUsuario = $_REQUEST['data2'];
                    $pkProveedor = $_REQUEST['data3'];
                    $json = $data->validarProductoOrdenCompra($pkProducto, $pkUsuario, $pkProveedor); //Guardando el return de la función
                    echo json_encode($json);
                    break;
                case "validar_productoOrdenCompraEdit":
                    $pkProducto = $_REQUEST['data'];
                    $pkOrden = $_REQUEST['data2'];
                    $pkProveedor = $_REQUEST['data3'];
                    $json = $data->validarProductoOrdenCompraEdit($pkProducto, $pkOrden, $pkProveedor); //Guardando el return de la función
                    echo json_encode($json);
                    break;
                case "validar_estadoOrdenCompra":
                    $pkOrden = $_REQUEST['data'];
                    $json = $data->validarEstadoOrdenCompra($pkOrden); //Guardando el return de la función
                    echo json_encode($json);
                    break;
                case "validar_productoEntradaOC":
                    $idDetalle = $_REQUEST['data'];
                    $json = $data->validarProductoEntradaOC($idDetalle); //Guardando el return de la función
                    echo json_encode($json);
                    break;
                case "validar_EmpresaProducto":
                    $pkProducto = $_REQUEST['data'];
                    $json = $data->validarEmpresaProducto($pkProducto); //Guardando el return de la función
                    echo json_encode($json);
                    break;
                case "validar_EmpresaProveedor":
                    $pkProveedor = $_REQUEST['data'];
                    $json = $data->validarEmpresaProveedor($pkProveedor); //Guardando el return de la función
                    echo json_encode($json);
                    break;
                case "validar_Permisos":
                    $pkPantalla = $_REQUEST['data'];
                    $json = $data->validarPermisos($pkPantalla); //Guardando el return de la función
                    echo json_encode($json);
                    break;
                case "validar_Permisos_Cat":
                    $pkPantalla = $_REQUEST['data'];
                    $json = $data->validarPermisosCat($pkPantalla); //Guardando el return de la función
                    echo json_encode($json);
                    break;
                case "validar_Permisos_Mar":
                    $pkPantalla = $_REQUEST['data'];
                    $json = $data->validarPermisosMar($pkPantalla); //Guardando el return de la función
                    echo json_encode($json);
                    break;
                case "validar_folio_entradaOC":
                    $folio = $_REQUEST['data'];
                    $ordenCompra = $_REQUEST['data2'];
                    $json = $data->validarFolioEntradaOC($folio, $ordenCompra); //Guardando el return de la función
                    echo json_encode($json);
                    break;
                case "validar_folio_entradaEDProvider":
                    $folio = $_REQUEST['data'];
                    $proveedor = $_REQUEST['data2'];
                    $sucursal = $_REQUEST['data3'];
                    $serie = $_REQUEST['data4'];
                    $json = $data->validarFolioEntradaEDProvider($folio, $serie, $proveedor, $sucursal); //Guardando el return de la función
                    echo json_encode($json);
                    break;
                case "validar_folio_entradaEDProviderEdit":
                    $folio = $_REQUEST['data'];
                    $serie = $_REQUEST['data1'];
                    $referencia = $_REQUEST['data2'];
                    $json = $data->validarFolioEntradaEDProviderEdit($folio, $serie, $referencia); //Guardando el return de la función
                    echo json_encode($json);
                    break;
                case "validar_serie_entradaOC":
                    $serie = $_REQUEST['data'];
                    $ordenCompra = $_REQUEST['data2'];
                    $json = $data->validarSerieEntradaOC($serie, $ordenCompra); //Guardando el return de la función
                    echo json_encode($json);
                    break;
                case "validar_serie_entradaEDProvider":
                    $serie = $_REQUEST['data'];
                    $proveedor = $_REQUEST['data2'];
                    $sucursal = $_REQUEST['data3'];
                    $json = $data->validarSerieEntradaEDProvider($serie, $proveedor, $sucursal); //Guardando el return de la función
                    echo json_encode($json);
                    break;
                case "validar_salida_cantidadModalTemp":
                    $serieLote = $_REQUEST['data'];
                    $cantidad = $_REQUEST['data2'];
                    $ordenPedido = $_REQUEST['data3'];
                    $pkProducto = $_REQUEST['data4'];
                    $json = $data->validarSalidaCantidadModalTemp($serieLote, $cantidad, $ordenPedido, $pkProducto); //Guardando el return de la función
                    echo json_encode($json);
                    break;
                case "validar_salida_cantidadModalTempEdicion":
                    $serieLote = $_REQUEST['data'];
                    $cantidad = $_REQUEST['data2'];
                    $folioSalida = $_REQUEST['data3'];
                    $pkProducto = $_REQUEST['data4'];
                    $json = $data->validarSalidaCantidadModalTempEdicion($serieLote, $cantidad, $folioSalida, $pkProducto); //Guardando el return de la función
                    echo json_encode($json);
                    break;
                case "validar_salida_cantidadDevolucionTemp":
                    $idSalidaTemp = $_REQUEST['data'];
                    $cantidad = $_REQUEST['data2'];
                    $idCuentaPorPagar = $_REQUEST['data3'];
                    $pkProducto = $_REQUEST['data4'];
                    $json = $data->validarSalidaCantidadDevolucionTemp($idSalidaTemp, $cantidad, $idCuentaPorPagar, $pkProducto); //Guardando el return de la función
                    echo json_encode($json);
                    break;
                case "validar_salida_cantidadDevolucionTempEdicion":
                    $idSalidaTemp = $_REQUEST['data'];
                    $cantidad = $_REQUEST['data2'];
                    $idCuentaPorPagar = $_REQUEST['data3'];
                    $pkProducto = $_REQUEST['data4'];
                    $json = $data->validarSalidaCantidadDevolucionTempEdicion($idSalidaTemp, $cantidad, $idCuentaPorPagar, $pkProducto); //Guardando el return de la función
                    echo json_encode($json);
                    break;
                case "validar_cantidadProd_entradaOC":
                    $idEntradaTemp = $_REQUEST['data'];
                    $cantidad = $_REQUEST['data2'];
                    $json = $data->validarCantidadProdEntradaOC($idEntradaTemp, $cantidad); //Guardando el return de la función
                    echo json_encode($json);
                    break;
                case "validar_cantidadProd_entradaOCEdit":
                    $idEntradaTemp = $_REQUEST['data'];
                    $cantidad = $_REQUEST['data2'];
                    $folioEntrada = $_REQUEST['data3'];
                    $json = $data->validarCantidadProdEntradaOCEdit($idEntradaTemp, $cantidad, $folioEntrada); //Guardando el return de la función
                    echo json_encode($json);
                    break;
                case "validar_cantidadProd_entrada_traspaso":
                    $idEntradaTemp = $_REQUEST['data'];
                    $cantidad = $_REQUEST['data2'];
                    $json = $data->validarCantidadProdEntradaTraspaso($idEntradaTemp, $cantidad); //Guardando el return de la función
                    echo json_encode($json);
                    break;
                case "validar_cantidadProd_entrada_traspasoEdit":
                    $idEntradaTemp = $_REQUEST['data'];
                    $cantidad = $_REQUEST['data2'];
                    $folioEntrada = $_REQUEST['data3'];
                    $json = $data->validarCantidadProdEntradaTraspasoEdit($idEntradaTemp, $cantidad, $folioEntrada); //Guardando el return de la función
                    echo json_encode($json);
                    break;
                case "validar_loteProd_entradaOC":
                    $idEntradaTemp = $_REQUEST['data'];
                    $lote = $_REQUEST['data2'];
                    $json = $data->validarLoteProdEntradaOC($idEntradaTemp, $lote); //Guardando el return de la función
                    echo json_encode($json);
                    break;
                case "validar_loteProd_entradaED":
                    $idEntradaTemp = $_REQUEST['data'];
                    $lote = $_REQUEST['data2'];
                    $json = $data->validarLoteProdEntradaED($idEntradaTemp, $lote); //Guardando el return de la función
                    echo json_encode($json);
                    break;
                case "validar_serieProd_entradaOC":
                    $idEntradaTemp = $_REQUEST['data'];
                    $serie = $_REQUEST['data2'];
                    $json = $data->validarSerieProdEntradaOC($idEntradaTemp, $serie); //Guardando el return de la función
                    echo json_encode($json);
                    break;
                case "validar_serieProd_entradaED":
                    $idEntradaTemp = $_REQUEST['data'];
                    $serie = $_REQUEST['data2'];
                    $json = $data->validarSerieProdEntradaED($idEntradaTemp, $serie); //Guardando el return de la función
                    echo json_encode($json);
                    break;
                case "validar_caducidadProd_entradaOC":
                    $idEntradaTemp = $_REQUEST['data'];
                    $caducidad = $_REQUEST['data2'];
                    $loteSerie = $_REQUEST['data3'];
                    $json = $data->validarCaducidadProdEntradaOC($idEntradaTemp, $caducidad, $loteSerie); //Guardando el return de la función
                    echo json_encode($json);
                    break;
                case "validar_noSalidas":
                    $folioSalida = $_REQUEST['data'];
                    $json = $data->validarNoSalidas($folioSalida); //Guardando el return de la función
                    echo json_encode($json);
                    break;
                    /* END PROVEEDORES */
                    /////////////////////////COLUMNAS AJUSTABLES PROVEEDORES//////////////////////////////
                case "lista_columnas": //dato del proyecto que se eligió
                    $json = $data->listaColumnas(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "info_columnas": //dato del proyecto que se eligió
                    if (isset($_REQUEST["array"])) {
                        $array = $_REQUEST["array"];
                    }

                    $json = $data->infoColumnas($array); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "orden_columnas": //dato del proyecto que se eligió
                    $json = $data->ordenColumnas(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "obtener_ids":
                    $json = $data->obtenerIds(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "orden_datos":
                    $sort = $_REQUEST["sort"];
                    $indice = $_REQUEST["indice"];
                    $search = $_REQUEST["search"];
                    $json = $data->ordenDatos($sort, $indice, $search); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;

                    /////////////////////////COLUMNAS AJUSTABLES PRODUCTOS//////////////////////////////
                case "lista_columnasProd": //dato del proyecto que se eligió
                    $json = $data->listaColumnasProd(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "info_columnasProd": //dato del proyecto que se eligió
                    $json = $data->infoColumnasProd(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "orden_columnasProd": //dato del proyecto que se eligió
                    $json = $data->ordenColumnasProd(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "obtener_idsProd":
                    $json = $data->obtenerIdsProd(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "orden_datosProd":
                    $sort = $_REQUEST["sort"];
                    $indice = $_REQUEST["indice"];
                    $search = $_REQUEST["search"];
                    $json = $data->ordenDatosProd($sort, $indice, $search); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                    //END JAVIER RAMIREZ
                    //JAVIER RAMIREZ / OMAR GARCÍA
                case "get_typeEntries":
                    $json = $data->getTypeEntries(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_typeExits":
                    $json = $data->getTypeExits(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                    //END JAVIER RAMIREZ / OMAR GARCÍA
                case "get_users":
                    $json = $data->getUsers(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_productsEntries":
                    $json = $data->getProductsEntries(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_products":
                    $json = $data->getProducts(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_productsOther":
                    $value = $_REQUEST['id'];
                    $value1 = $_REQUEST['tipo'];
                    $json = $data->getProductsOther($value, $value1); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    break;
                case "get_idEntries":
                    $json = $data->getIdEntries(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_warehouses":
                    $json = $data->getWarehouses(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_outputsSelect":
                    $json = $data->getOutputsSelect(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_outputsTable":
                    $json = $data->getOutputsTable(); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                case "get_provider":
                    $json = $data->getProvider(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_clientsSelect":
                    $json = $data->getClientsSelect(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_outputsClient":
                    $value = $_REQUEST['data'];
                    $json = $data->getOutputClient($value); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_manufacturingInput":
                    $json = $data->getManufacturingInput(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_typeEntry":
                    $value = $_REQUEST['data'];
                    $json = $data->getTypeEntry($value); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_productsEntriesTable":
                    $value = $_REQUEST['id'];
                    $json = $data->getProductsEntriesTable($value); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                    break;
                    /*case "get_data_Productos":
                    $json = $data->getListadoProductosTable(); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                break;*/
                    /*  OMAR GARCIA / JAVIER RAMIREZ */
                case "get_productoCombo":
                    $value = $_REQUEST['value'];
                    $json = $data->loadCmbProduct($value); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;

                case "get_todoProductoCombo":
                    $value = $_REQUEST['value'];
                    $json = $data->loadCmbAllProduct($value); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;

                case "get_proveedorCombo":
                    $json = $data->loadCmbProvider(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;

                case "get_sucursalCombo":
                    $json = $data->loadCmbLocation(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_precioProveedor":
                    $value = $_REQUEST['value'];
                    $value1 = $_REQUEST['value1'];
                    $json = $data->loadPriceProvider($value, $value1); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                    /* END OMAR GARCIA / JAVIER RAMIREZ */
                    /* JAVIER RAMIREZ */
                case "get_claveReferencia":
                    $json = $data->getClaveReferencia(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_claveReferenciaEdit":
                    $pkProducto = $_REQUEST['datos'];
                    $json = $data->getClaveReferenciaEdit($pkProducto); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_referencia":
                    $json = $data->getReferencia(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_folioEntradaED":
                    $json = $data->getFolioEntradaED(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_fechaEmision":
                    $json = $data->getFechaEmision(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_fechaEntegraMin":
                    $json = $data->getFechaEntegraMin(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case 'get_impuestos':
                    $value = $_REQUEST['value'];
                    $json = $data->getImpuestos($value); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                    /* END JAVIER RAMIREZ */
                case 'get_datosProducto':
                    $PKProducto = $_REQUEST['datos'];
                    $json = $data->getDatosProducto($PKProducto); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case 'get_Barcode_salidas':
                    $json = $data->getBarcodeSalidas(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case 'get_idProds_salidas':
                    $idOrdenPedido = $_REQUEST['data'];
                    $json = $data->get_idProdsSalidas($idOrdenPedido); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case 'get_valida_codigo_ProdOrden';
                    $codigo = $_REQUEST['data'];
                    $idOrden = $_REQUEST['data2'];
                    $json = $data->get_valida_codigo_ProdOrden($codigo, $idOrden); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_categorias":
                    $json = $data->loadCmbCategorias(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "get_subcategorias":
                    $subCat = $_REQUEST['subCat'];
                    $json = $data->loadCmbSubcategorias($subCat); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "get_expenseCategory":
                    $compra_id = $_REQUEST['compra_id'];
                    $json = $data->getExpenseCategory($compra_id);
                    echo json_encode($json);
                break;
                case "get_estatus_sucursalInvPeriodico":
                    $compra_id = $_REQUEST['compra_id'];
                    $json = $data->getExpenseCategory($compra_id);
                    echo json_encode($json);
                break;
            }
            break;

        case "data_order":
            $order = new data_order();
            switch ($_REQUEST['funcion']) {
                    #JAVIER RAMIREZ
                    /////////////////////////COLUMNAS AJUSTABLES PROVEEDORES//////////////////////////////
                case "column_order": //dato del proyecto que se eligió
                    if (isset($_REQUEST["ordenArray"])) {
                        $array = $_REQUEST["ordenArray"];
                    }

                    $json = $order->columnOrder($array); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                    /////////////////////////COLUMNAS AJUSTABLES PRODUCTOS//////////////////////////////
                case "column_orderProd": //dato del proyecto que se eligió
                    if (isset($_REQUEST["ordenArray"])) {
                        $array = $_REQUEST["ordenArray"];
                    }

                    $json = $order->columnOrderProd($array); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                    #END JAVIER RAMIREZ
               
            }
            break;

        case "buscar_data": //Buscar info en ddbb
            $buscar = new buscar_data(); //creando un nuevo objeto que referencia a la clase buscar data
            switch ($_REQUEST['funcion']) {
                    #JAVIER RAMIREZ
                    /////////////////////////COLUMNAS AJUSTABLES PROVEEDORES//////////////////////////////
                case "buscar_proveedor":
                    $inputValue = $_REQUEST['data'];
                    $array = $_REQUEST['array'];
                    $json = $buscar->buscarProveedor($inputValue, $array);
                    echo json_encode($json);
                    return;
                    break;
                    /////////////////////////COLUMNAS AJUSTABLES PRODUCTOS//////////////////////////////
                case "buscar_producto":
                    $inputValue = $_REQUEST['data'];
                    $json = $buscar->buscarProducto($inputValue);
                    echo json_encode($json);
                    return;
                    break;
                    #END JAVIER RAMIREZ
                case "get_Costoproducto":
					$pkRegistro = $_REQUEST['data'];
                    $json = $buscar->getCostoproducto($pkRegistro);
                    echo json_encode($json);
                return;
                break;
            }
            break;

        case "save_data":
            $data = new save_data();
            switch ($_REQUEST['funcion']) {
                case "save_emptyProductStock":
                    $PKDetalle = $_REQUEST['data'];
                    $PKSucursal = $_REQUEST['data2'];
                    $PKProducto = $_REQUEST['data3'];
                    $Clave = $_REQUEST['data4'];
                    $Cantidad = $_REQUEST['data5'];
                    $json = $data->saveEmptyProductStock($PKDetalle, $PKSucursal, $PKProducto, $Clave, $Cantidad); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "save_DuplicarEliminarProductoInventarioPeriodico":
                    $PKDetalle = $_REQUEST['data'];
                    $PKSucursal = $_REQUEST['data2'];
                    $PKProducto = $_REQUEST['data3'];
                    $Cantidad = $_REQUEST['data4'];
                    $json = $data->saveDuplicarEliminarProductoInventarioPeriodico($PKDetalle, $PKSucursal, $PKProducto, $Cantidad); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "save_headerInitialStock":
                    $PKSucursal = $_REQUEST["data"];
                    $json = $data->saveHeaderInitialStock($PKSucursal); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "save_detailInitialStock":
                    $PKSucursal = $_REQUEST['data'];
                    $PKDetalle = $_REQUEST['data2'];
                    $PKProducto = $_REQUEST['data3'];
                    $Clave = $_REQUEST['data4'];
                    $Cantidad = $_REQUEST['data5'];
                    $Lote = $_REQUEST['data6'];
                    $Serie = $_REQUEST['data7'];
                    $Caducidad = $_REQUEST['data8'];
                    $json = $data->saveDetailInitialStock($PKSucursal, $PKDetalle, $PKProducto, $Clave, $Lote, $Serie, $Caducidad, $Cantidad); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "save_detailInitialStock2":
                    $PKSucursal = $_REQUEST['data'];
                    $PKDetalle = $_REQUEST['data2'];
                    $PKProducto = $_REQUEST['data3'];
                    $Clave = $_REQUEST['data4'];
                    $Entrada = $_REQUEST['data5'];
                    $Tipo = $_REQUEST['data6'];
                    $json = $data->saveDetailInitialStock2($PKSucursal, $PKDetalle, $PKProducto, $Clave, $Entrada, $Tipo); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "save_InitialStock":
                    $PKSucursal = $_REQUEST['data'];
                    $json = $data->saveInitialStock($PKSucursal); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "save_TempInitialStock":
                    $PKSucursal = $_REQUEST['data'];
                    $json = $data->saveTempInitialStock($PKSucursal); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "save_Ajustes":
                    $PKSucursal = $_REQUEST['data1'];
                    $PKTipo = $_REQUEST['data2'];
                    $json = $data->saveAjustes($PKSucursal, $PKTipo); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "save_Ajustar":
                    $PKAjuste = $_REQUEST['data1'];
                    $PKProducto = $_REQUEST['data2'];
                    $Existencia = $_REQUEST['data3'];
                    $Cantidad = $_REQUEST['data4'];
                    $Clave = $_REQUEST['data5'];
                    $Lote = $_REQUEST['data6'];
                    $Serie = $_REQUEST['data7'];
                    $Caducidad = $_REQUEST['data8'];
                    $Motivo = $_REQUEST['data9'];
                    $Observaciones = trim($_REQUEST['data10']);
                    $json = $data->saveAjustar($PKAjuste, $PKProducto, $Existencia, $Cantidad, $Clave, $Lote, $Serie, $Caducidad, $Motivo, $Observaciones); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case 'save_clavesKardexTemp':
                    $Clave = $_REQUEST['clave'];
                    $json = $data->saveClavesKardexTemp($Clave); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "save_Cambios":
                    $PKSucursal = $_REQUEST['data1'];
                    $PKTipo = $_REQUEST['data2'];
                    $json = $data->saveCambios($PKSucursal, $PKTipo); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "save_Cambiar":
                    $PKCambio = $_REQUEST['data1'];
                    $PKProducto = $_REQUEST['data2'];
                    $Existencia = $_REQUEST['data3'];
                    $Cantidad = $_REQUEST['data4'];
                    $Clave = $_REQUEST['data5'];
                    $LoteAntiguo = $_REQUEST['data6'];
                    $SerieAntigua = $_REQUEST['data7'];
                    $LoteNuevo = $_REQUEST['data8'];
                    $SerieNueva = $_REQUEST['data9'];
                    $Caducidad = $_REQUEST['data10'];
                    $Observaciones = trim($_REQUEST['data11']);
                    $json = $data->saveCambiar($PKCambio, $PKProducto, $Existencia, $Cantidad, $Clave, $LoteAntiguo, $SerieAntigua, $LoteNuevo, $SerieNueva, $Caducidad, $Observaciones); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "save_detailInvPerio":
                    $PKSucursal = $_REQUEST['data'];
                    $PKDetalle = $_REQUEST['data2'];
                    $PKProducto = $_REQUEST['data3'];
                    $Clave = $_REQUEST['data4'];
                    $Entrada = $_REQUEST['data5'];
                    $Tipo = $_REQUEST['data6'];
                    $json = $data->saveDetailInvPerio($PKSucursal, $PKDetalle, $PKProducto, $Clave, $Entrada, $Tipo); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                    /*case "save_AjusteTemporal":
                    $PKAjuste = $_REQUEST['data'];
                    $json = $data->saveAjustes($PKAjuste); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                break;*/
                case "save_typeEntries":
                    $value = $_REQUEST['data'];
                    $json = $data->saveTypeEntries($value); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "save_entry":
                    $value = $_REQUEST['data'];
                    $json = $data->saveEntry($value); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                    /*case "save_product":
                    $value = $_REQUEST['data'];
                    $json = $data->saveProduct($value); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;*/
                    //JAVIER RAMIREZ
                case "save_datosProducto":
                    $array = $_REQUEST['datos'];
                    $foto = $_REQUEST['datos']['fotografia'];
                    $pkUsuario = $_REQUEST['datos']['pkUsuario'];
                    $isCompra = $_REQUEST['datos']['compra']['active'];
                    $costoCompra = $_REQUEST['datos']['compra']['costo'];
                    $monedaCompra = $_REQUEST['datos']['compra']['moneda'];
                    $isVenta = $_REQUEST['datos']['venta']['active'];
                    $costoVenta = $_REQUEST['datos']['venta']['costo'];
                    $monedaVenta = $_REQUEST['datos']['venta']['moneda'];
                    $isFabricacion = $_REQUEST['datos']['fabricacion']['active'];
                    $costoFabricacion = $_REQUEST['datos']['fabricacion']['costo'];
                    $monedaFabricacion = $_REQUEST['datos']['fabricacion']['moneda'];
                    $isGastoFijo = $_REQUEST['datos']['gastoFijo']['active'];
                    $costoGastoFijo = $_REQUEST['datos']['gastoFijo']['costo'];
                    $monedaGastoFijo = $_REQUEST['datos']['gastoFijo']['moneda'];
                    $isSerie = $_REQUEST['datos']['serie']['active'];
                    /*$serie = $_REQUEST['datos']['serie']['serie'];*/
                    $isLote = $_REQUEST['datos']['lote']['active'];
                    /*$lote = $_REQUEST['datos']['lote']['lote'];*/
                    $isCaducidad = $_REQUEST['datos']['caducidad']['active'];
                    /*$caducidad = $_REQUEST['datos']['caducidad']['caducidad'];*/
                    $unidadMedida = $_REQUEST['datos']['unidadMedida'];
                    $json = $data->saveDatosProducto($array, $foto, $pkUsuario, $isCompra, $isVenta, $isFabricacion, $isGastoFijo, $costoCompra, $monedaCompra, $costoVenta, $monedaVenta, $costoFabricacion, $monedaFabricacion, $costoGastoFijo, $monedaGastoFijo, $isSerie/*, $serie*/, $isLote/*, $lote*/, $isCaducidad/*, $caducidad*/, $unidadMedida); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "save_datosProductoCompTemp":
                    $pkProducto = $_REQUEST['datos'];
                    $cantidad = $_REQUEST['datos2'];
                    $pkUsuario = $_REQUEST['datos3'];
                    $PKCompuestoTemp = $_REQUEST['datos4'];
                    $costo = $_REQUEST['datos5'];
                    $moneda = $_REQUEST['datos6'];
                    $json = $data->saveDatosProductoCompTemp($pkProducto, $cantidad, $pkUsuario, $PKCompuestoTemp, $costo, $moneda); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "save_datosImpuesto":
                    $array = $_REQUEST['datos'];
                    $json = $data->saveDatosImpuesto($array); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "save_datosFiscales":
                    $fkClave = $_REQUEST['datos'];
                    //$fkUnidad = $_REQUEST['datos2'];
                    $fkProducto = $_REQUEST['datos3'];
                    $json = $data->saveDatosFiscales($fkClave, /* $fkUnidad, */ $fkProducto); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "save_datosInventarioProducto":
                    $PKProducto = $_REQUEST['data'];
                    $arrayData = $_REQUEST['data1'];
                    $json = $data->saveDatosInventarioProducto($PKProducto, $arrayData); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "save_datosInventario":
                    $array = $_REQUEST['datos'];
                    $json = $data->saveDatosInventario($array); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "save_datosTipoProducto":
                    $array = $_REQUEST['datos'];
                    $json = $data->saveDatosTipoProducto($array); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "save_datosTipoProducto_Temp":
                    $pkAccion = $_REQUEST['data'];
                    $pkUsuario = $_REQUEST['data2'];
                    $json = $data->saveDatosTipoProductoTemp($pkAccion, $pkUsuario); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "save_categoria":
                    $value = $_REQUEST['datos'];
                    $json = $data->saveCategoria($value); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "save_marca":
                    $value = $_REQUEST['datos'];
                    $json = $data->saveMarca($value); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "save_tipoProducto":
                    $value = $_REQUEST['datos'];
                    $json = $data->saveTipoProducto($value); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "save_tipoOrdenInventario":
                    $value = $_REQUEST['datos'];
                    $value2 = $_REQUEST['datos2'];
                    $json = $data->saveTipoOrdenInventario($value, $value2); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "save_datosProveedor":
                    $value = $_REQUEST['datos'];
                    $json = $data->saveDatosProveedor($value); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "save_datosProveedor2":
                    $value = $_REQUEST['datos'];
                    $json = $data->saveDatosProveedor2($value); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "save_clienteProducto":
                    $cliente = $_REQUEST['datos'];
                    $costoEsp = $_REQUEST['datos2'];
                    $moneda = $_REQUEST['datos3'];
                    $pkProducto = $_REQUEST['datos4'];
                    $costoGral = $_REQUEST['datos5'];
                    $monedaGral = $_REQUEST['datos6'];
                    $json = $data->saveClienteProducto($cliente, $costoEsp, $moneda, $pkProducto, $costoGral, $monedaGral); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "save_datosVenta":
                    $costoGral = $_REQUEST['datos'];
                    $monedaGral = $_REQUEST['datos2'];
                    $pkProducto = $_REQUEST['datos3'];
                    $json = $data->saveDatosVenta($costoGral, $monedaGral, $pkProducto); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;

                    /* PROVEEDORES */
                case "save_medioContactoProveedor":
                    $medioContacto = $_REQUEST['datos'];
                    $json = $data->saveMedioContactoProveedor($medioContacto); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "save_datosProveedorTable":
                    $array = $_REQUEST['datos'];
                    $nombreComercial = $_REQUEST['datos2'];
                    /*$medioContactoProveedor = $_REQUEST['datos3'];*/
                    $vendedor = $_REQUEST['datos4'];
                    $montoCredito = $_REQUEST['datos5'];
                    $diasCredito = $_REQUEST['datos6'];
                    $telefono = $_REQUEST['datos7'];
                    $email = $_REQUEST['datos8'];
                    $estatus = $_REQUEST['datos9'];
                    $tipoPersona = $_REQUEST['datos10'];
                    $email2 = $_REQUEST['datos11'];
                    $movil = $_REQUEST['datos12'];
                    $giro = $_REQUEST['datos13'];
                    $json = $data->saveDatosProveedorTable($array, $nombreComercial, $vendedor, $montoCredito, $diasCredito, $telefono, $email, $estatus, $tipoPersona, $email2, $movil, $giro); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "save_estado_pais":
                    $estado = $_REQUEST['data'];
                    $pais = $_REQUEST['data2'];
                    $json = $data->saveEstadoPais($estado, $pais); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "save_razonSocial_Proveedor":
                    $razonSocial = $_REQUEST['datos'];
                    $rfc = $_REQUEST['datos2'];
                    $calle = $_REQUEST['datos4'];
                    $numExt = $_REQUEST['datos5'];
                    $numInt = $_REQUEST['datos6'];
                    $colonia = $_REQUEST['datos7'];
                    $municipio = $_REQUEST['datos8'];
                    $pais = $_REQUEST['datos9'];
                    $estado = $_REQUEST['datos10'];
                    $cp = $_REQUEST['datos11'];
                    $pkProveedor = $_REQUEST['datos12'];
                    $pkRazonSocial = $_REQUEST['datos13'];
                    $localidad = $_REQUEST['datos14'];
                    $referencia = $_REQUEST['datos15'];
                    $json = $data->saveRazonSocialProveedor($razonSocial, $rfc, $calle, $numExt, $numInt, $colonia, $municipio, $pais, $estado, $cp, $pkProveedor, $pkRazonSocial, $localidad, $referencia); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "save_direccionEnvio_Proveedor":
                    $sucursal = $_REQUEST['datos'];
                    $email = $_REQUEST['datos3'];
                    $calle = $_REQUEST['datos4'];
                    $numExt = $_REQUEST['datos5'];
                    $numInt = $_REQUEST['datos6'];
                    $colonia = $_REQUEST['datos7'];
                    $municipio = $_REQUEST['datos8'];
                    $pais = $_REQUEST['datos9'];
                    $estado = $_REQUEST['datos10'];
                    $cp = $_REQUEST['datos11'];
                    $pkProveedor = $_REQUEST['datos12'];
                    $pkDireccion = $_REQUEST['datos13'];
                    $json = $data->saveDireccionEnvioProveedor($sucursal, $email, $calle, $numExt, $numInt, $colonia, $municipio, $pais, $estado, $cp, $pkProveedor, $pkDireccion); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "save_contactoProveedor":
                    $nombreContacto = $_REQUEST['datos'];
                    $apellidoContacto = $_REQUEST['datos2'];
                    $puesto = $_REQUEST['datos3'];
                    $telefonoFijo = $_REQUEST['datos4'];
                    $celular = $_REQUEST['datos5'];
                    $email = $_REQUEST['datos6'];
                    $pkProveedor = $_REQUEST['datos7'];
                    $pkContacto = $_REQUEST['datos8'];

                    $json = $data->saveContactoProveedor($nombreContacto, $apellidoContacto, $puesto, $telefonoFijo, $celular, $email, $pkProveedor, $pkContacto); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "save_bancoProveedor":
                    $pkBanco = $_REQUEST['datos'];
                    $noCuenta = $_REQUEST['datos2'];
                    $clabe = $_REQUEST['datos3'];
                    $pkProveedor = $_REQUEST['datos4'];
                    $pkCuentaBancaria = $_REQUEST['datos5'];
                    $moneda = $_REQUEST['datos6'];
                    $json = $data->saveBancoProveedor($pkBanco, $noCuenta, $clabe, $pkProveedor, $pkCuentaBancaria, $moneda); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                    /* END PROVEEDORES */
                case "save_orden_compraTemp":
                    $idproducto = $_REQUEST['datos'];
                    $cantidad = $_REQUEST['datos2'];
                    $pkUsuario = $_REQUEST['datos3'];
                    $pkProveedor = $_REQUEST['datos4'];
                    $precio = $_REQUEST['datos5'];
                    $nombre = $_REQUEST['datos6'];
                    $clave = $_REQUEST['datos7'];
                    $json = $data->saveOrdenCompraTemp($idproducto, $cantidad, $pkUsuario, $pkProveedor, $precio, $nombre, $clave); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "save_orden_compra":
                    $idproducto = $_REQUEST['datos'];
                    $cantidad = $_REQUEST['datos2'];
                    $pkOrden = $_REQUEST['datos3'];
                    $pkProveedor = $_REQUEST['datos4'];
                    $precio = $_REQUEST['datos5'];
                    $nombre = $_REQUEST['datos6'];
                    $clave = $_REQUEST['datos7'];
                    $json = $data->saveOrdenCompra($idproducto, $cantidad, $pkOrden, $pkProveedor, $precio, $nombre, $clave); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case 'save_OrderPurchase':
                    $referencia = $_REQUEST['datos'];
                    $fechaEmision = $_REQUEST['datos2'];
                    $fechaEntrega = $_REQUEST['datos3'];
                    $proveedor = $_REQUEST['datos4'];
                    $direccionEntrega = $_REQUEST['datos5'];
                    $importe = $_REQUEST['datos6'];
                    $pkUsuario = $_REQUEST['datos7'];
                    $notasInternas = $_REQUEST['datos8'];
                    $notasProveedor = $_REQUEST['datos9'];
                    $comprador = $_REQUEST['datos10'];
                    $condicionPago = $_REQUEST['datos11'];
                    $moneda = $_REQUEST['datos13'];
                    $categoria = $_REQUEST['datos14'];
                    $subcategoria = $_REQUEST['datos15'];
                    $json = $data->saveOrderPurchase(
                        $referencia,
                        $fechaEmision,
                        $fechaEntrega,
                        $proveedor,
                        $direccionEntrega,
                        $importe,
                        $pkUsuario,
                        $notasInternas,
                        $notasProveedor,
                        $comprador,
                        $condicionPago,
                        $moneda,
                        $categoria,
                        $subcategoria
                    ); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                    /*case 'save_EventOrder':
                $PKOrdenCompra = $_REQUEST['datos'];
                $json = $data->saveEventOrder($PKOrdenCompra);//Guardando el return de la función
                echo json_encode($json); //Retornando el resultado al ajax
                return;
                break;*/
                case 'save_proveedor':
                    $nombreCom = $_REQUEST['datos'];
                    $contacto = $_REQUEST['datos2'];
                    $telefono = $_REQUEST['datos3'];
                    $email = $_REQUEST['datos4'];
                    $tipo = $_REQUEST['datos5'];
                    $json = $data->saveProveedor($nombreCom, $contacto, $telefono, $email, $tipo); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case 'save_ordenCompra_Mensaje':
                    $mensaje = $_REQUEST['datos'];
                    $tipo = $_REQUEST['datos2'];
                    $fKOrdenEncripted = $_REQUEST['datos3'];
                    $json = $data->saveOrdenCompraMensaje($mensaje, $tipo, $fKOrdenEncripted); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case 'save_entry_partial_oc':
                    $array = $_REQUEST['datos'];
                    $idProducto = $_REQUEST['datos']['idProducto'];
                    $cantidad = $_REQUEST['datos']['cantidad'];
                    $idDetalle = $_REQUEST['datos']['idDetalle'];
                    $json = $data->saveEntryPartialOc($array, $idProducto, $cantidad, $idDetalle); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case 'save_entry_partial_ocTempTable':
                    $pkOrden = $_REQUEST['data'];
                    $json = $data->saveEntryPartialOcTempTable($pkOrden); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case 'save_entry_partial_ocTempTable_EntradaDirecta':
                    $pkOrden = $_REQUEST['data'];
                    $json = $data->saveEntryPartialOcTempTableEntradaDirecta($pkOrden); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case 'save_entry_partial_ocTempTableEdit':
                    $folioEntrada = $_REQUEST['data'];
                    $json = $data->saveEntryPartialOcTempTableEdit($folioEntrada); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case 'save_entry_partial_transferTempTableEdit':
                    $folioEntrada = $_REQUEST['data'];
                    $json = $data->saveEntryPartialTransferTempTableEdit($folioEntrada); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case 'save_entry_directTempTableEdit':
                    $folioEntrada = $_REQUEST['data'];
                    $json = $data->saveEntryDirectTempTableEdit($folioEntrada); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case 'save_entry_directProviderTempTableEdit':
                    $folioEntrada = $_REQUEST['data'];
                    $json = $data->saveEntryDirectProviderTempTableEdit($folioEntrada); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case 'save_entry_partialAdd_ocTempTable':
                    $pkEntradaTemp = $_REQUEST['data'];
                    $json = $data->saveEntryPartialAddOcTempTable($pkEntradaTemp); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case 'save_entry_partial_ocTable':
                    $pkOrden = $_REQUEST['datos']['ordenCompra'];
                    $proveedor = $_REQUEST['datos']['proveedor'];
                    $sucursal = $_REQUEST['datos']['sucursal'];
                    $noDocumento = $_REQUEST['datos']['noDocumento'];
                    $serie = $_REQUEST['datos']['serie'];
                    $subtotal = $_REQUEST['datos']['subtotal'];
                    $iva = $_REQUEST['datos']['iva'];
                    $ieps = $_REQUEST['datos']['ieps'];
                    $importe = $_REQUEST['datos']['importe'];
                    $descuento = $_REQUEST['datos']['descuento'];
                    $fechaFactura = $_REQUEST['datos']['fechaFactura'];
                    $remision = $_REQUEST['datos']['remision']['active'];
                    $notas = $_REQUEST['datos']['notas'];
                    $json = $data->saveEntryPartialOcTable($pkOrden, $proveedor, $sucursal, $noDocumento, $serie, $subtotal, $iva, $ieps, $importe, $descuento, $fechaFactura, $remision, $notas); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case 'save_exitOP_TempTable':
                    $pkOrdenPedido = $_REQUEST['data'];
                    $json = $data->saveExitOPTempTable($pkOrdenPedido); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case 'save_exitDevolucion_TempTable':
                    $id_cuenta_pagar = $_REQUEST['data'];
                    $json = $data->saveExitDevolucionTempTable($id_cuenta_pagar); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case 'save_exitOP_TableEdit':
                    $folioSalida = $_REQUEST['data'];
                    $json = $data->saveExitOPTableEdit($folioSalida); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case 'save_exitDevolucion_TableEdit':
                    $folioSalida = $_REQUEST['data'];
                    $json = $data->saveExitDevolucionTableEdit($folioSalida); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case 'save_datosSalida_OP':
                    $pkOrdenPedido = $_REQUEST['data'];
                    $observaciones = $_REQUEST['data2'];
                    $surtidor = $_REQUEST['data3'];
                    $json = $data->saveDatosSalidaOP($pkOrdenPedido, $observaciones, $surtidor); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case 'save_datosSalida_OPGral':
                    $pkOrdenPedido = $_REQUEST['data'];
                    $observaciones = $_REQUEST['data2'];
                    $surtidor = $_REQUEST['data3'];
                    $json = $data->saveDatosSalidaOPGral($pkOrdenPedido, $observaciones, $surtidor); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case 'save_datosSalida_Coti':
                    $pkOrdenPedido = $_REQUEST['data'];
                    $observaciones = $_REQUEST['data2'];
                    $surtidor = $_REQUEST['data3'];
                    $json = $data->saveDatosSalidaCoti($pkOrdenPedido, $observaciones, $surtidor); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case 'save_datosSalida_Venta':
                    $pkOrdenPedido = $_REQUEST['data'];
                    $observaciones = $_REQUEST['data2'];
                    $surtidor = $_REQUEST['data3'];
                    $json = $data->saveDatosSalidaVenta($pkOrdenPedido, $observaciones, $surtidor); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case 'save_datosSalida_Devolucion':
                    $idCuentaPorPagar = $_REQUEST['data'];
                    $observaciones = $_REQUEST['data2'];
                    $surtidor = $_REQUEST['data3'];
                    $json = $data->saveDatosSalidaDevolucion($idCuentaPorPagar, $observaciones, $surtidor); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case 'save_entry_transfer_TempTable':
                    $folioSalida = $_REQUEST['data'];
                    $json = $data->saveEntryTransferTempTable($folioSalida); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case 'save_entry_tranfer_Table':
                    $folioSalida = $_REQUEST['datos']['ordenPedido'];
                    $json = $data->saveEntryTransferTable($folioSalida); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "save_datosProductoED":
                    $PKProducto = $_REQUEST['data'];
                    $sucDestino = $_REQUEST['data2'];
                    $json = $data->saveDatosProductoED($PKProducto, $sucDestino); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "save_entry_partial_edTable":
                    $referencia = $_REQUEST['datos']['referencia'];
                    $sucursalEntrada = $_REQUEST['datos']['sucursalEntrada'];
                    $sucursalOrigen = $_REQUEST['datos']['sucursalOrigen'];
                    $notas = $_REQUEST['datos']['notas'];
                    $json = $data->saveEntryPartialEdTable($referencia, $notas, $sucursalEntrada, $sucursalOrigen); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "save_entry_partial_edProviderTable":
                    if($_REQUEST['datos']['addCuentaPagar'] == 1){
                        //$serie = $_REQUEST['datos']['serie'];
                        $folio = $_REQUEST['datos']['folio'];
                        $subtotal = $_REQUEST['datos']['subtotal'];
                        $importe = $_REQUEST['datos']['importe'];
                        $fecha = $_REQUEST['datos']['fecha'];
                        $fechaVenci = $_REQUEST['datos']['fechaVenci'];
                        $tipoEntradas = $_REQUEST['datos']['tipoEntradas'];
                        $categoria = $_REQUEST['datos']['categoria'];
                        $subcategoria = $_REQUEST['datos']['subcategoria'];
                    }else{
                        //$serie = 0;
                        $folio = 0;
                        $subtotal = 0;
                        $importe = 0;
                        $fecha = '0000-00-00';
                        $fechaVenci = '0000-00-00';
                        $tipoEntradas = 0;
                        $categoria = null;
                        $subcategoria = null;
                    }
                    $sucEntrada = $_REQUEST['datos']['sucEntrada'];
                    $proveedor = $_REQUEST['datos']['proveedor'];
                    $notas = $_REQUEST['datos']['notas'];
                    $json = $data->saveEntryPartialEdProviderTable($sucEntrada, $proveedor, /* $serie, */ $folio, $tipoEntradas, $subtotal, $importe, $fecha, $fechaVenci, $notas, $_REQUEST['datos']['addCuentaPagar'], $_REQUEST['datos']['ordenCompra'],$categoria,$subcategoria); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "save_entry_partial_edCustomerTable":
                    $referencia = $_REQUEST['datos']['referencia'];
                    $sucursalEntrada = $_REQUEST['datos']['sucursalEntrada'];
                    $cliente = $_REQUEST['datos']['cliente'];
                    $notas = $_REQUEST['datos']['notas'];
                    $json = $data->saveEntryPartialEdCustomerTable($referencia, $notas, $sucursalEntrada, $cliente); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                    //END JAVIER RAMIREZ
                case "get_validUnidadMedida":
                    $ordenPedido = $_REQUEST['data'];
                    $json = $data->valida_UnidadMedida($ordenPedido); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case 'save_categoriaGastos':
                    $cat = $_REQUEST['value'];
                    $subcat = $_REQUEST['value1'];
                    $check_box =  $_REQUEST['checked'];
                    $json = $data->saveCategoriaGasto($cat,$subcat,$check_box);
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case 'save_subcategoriaGastos':
                    $subcat = $_REQUEST['value'];
                    $cat = $_REQUEST['value1'];
                    $json = $data->saveSubcategoriaGasto($subcat,$cat);
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case 'save_IniciarPeriodicInv':
                    $PKSucursal = $_REQUEST['data'];
                    $json = $data->saveIniciarPeriodicInv($PKSucursal);
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
            }
            break;

        case "edit_data":
            $data = new edit_data();
            switch ($_REQUEST['funcion']) {
                case "edit_inventario":
                    $json = $data->editInventario($_REQUEST['minimo'], $_REQUEST['maximo'], $_REQUEST['idExistencia']); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "edit_entry":
                    $value = $_REQUEST['data'];
                    $json = $data->editEntry($value); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                    //JAVIER RAMIREZ
                case "edit_categoria":
                    $estatus = $_REQUEST['datos'];
                    $categoria = $_REQUEST['datos2'];
                    $id = $_REQUEST['datos3'];
                    $json = $data->editCategoria($estatus, $categoria, $id); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "edit_marca":
                    $estatus = $_REQUEST['datos'];
                    $marca = $_REQUEST['datos2'];
                    $id = $_REQUEST['datos3'];
                    $json = $data->editMarca($estatus, $marca, $id); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "edit_tipoProducto":
                    $estatus = $_REQUEST['datos'];
                    $tipoProducto = $_REQUEST['datos2'];
                    $id = $_REQUEST['datos3'];
                    $json = $data->editTipoProducto($estatus, $tipoProducto, $id); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "edit_tipoOrdenInventario":
                    $estatus = $_REQUEST['datos'];
                    $tipoOrdenInventario = $_REQUEST['datos2'];
                    $id = $_REQUEST['datos3'];
                    $json = $data->editTipoOrdenInventario($estatus, $tipoOrdenInventario, $id); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "edit_datosCantidadProductoCompTemp":
                    $pkUsuario = $_REQUEST['datos'];
                    $pkProducto = $_REQUEST['datos2'];
                    $cantidad = $_REQUEST['datos3'];
                    $costo = $_REQUEST['datos4'];
                    $moneda = $_REQUEST['datos5'];
                    $json = $data->editDatosCantidadProductoCompTemp($pkProducto, $cantidad, $pkUsuario, $costo, $moneda); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "edit_datosProducto":
                    $array = $_REQUEST['datos'];
                    $foto = $_REQUEST['datos']['fotografia'];
                    $pkUsuario = $_REQUEST['datos']['pkUsuario'];
                    $isCompra = $_REQUEST['datos']['compra']['active'];
                    $costoCompra = $_REQUEST['datos']['compra']['costo'];
                    $monedaCompra = $_REQUEST['datos']['compra']['moneda'];
                    $isVenta = $_REQUEST['datos']['venta']['active'];
                    $costoVenta = $_REQUEST['datos']['venta']['costo'];
                    $monedaVenta = $_REQUEST['datos']['venta']['moneda'];
                    $isFabricacion = $_REQUEST['datos']['fabricacion']['active'];
                    $costoFabri = $_REQUEST['datos']['fabricacion']['costo'];
                    $monedaFabri = $_REQUEST['datos']['fabricacion']['moneda'];
                    $isGastoFijo = $_REQUEST['datos']['gastoFijo']['active'];
                    $costoGastoFijo = $_REQUEST['datos']['gastoFijo']['costo'];
                    $monedaGastoFijo = $_REQUEST['datos']['gastoFijo']['moneda'];
                    $pkProducto = $_REQUEST['datos']['pkProducto'];
                    /*$isSerie = $_REQUEST['datos']['serie']['active'];*/
                    /*$serie = $_REQUEST['datos']['serie']['serie'];*/
                    $isLote = $_REQUEST['datos']['lote']['active'];
                    /*$lote = $_REQUEST['datos']['lote']['lote'];*/
                    $isCaducidad = $_REQUEST['datos']['caducidad']['active'];
                    /*$caducidad = $_REQUEST['datos']['caducidad']['caducidad'];*/
                    $unidadMedida = $_REQUEST['datos']['unidadMedida'];
                    $json = $data->editDatosProducto($array, $foto, $pkUsuario, $isCompra, $isVenta, $isFabricacion, $isGastoFijo, $pkProducto, $costoCompra, $monedaCompra, $costoVenta, $monedaVenta, $costoFabri, $monedaFabri, $costoGastoFijo, $monedaGastoFijo, /* $isSerie, $serie*/ $isLote/*, $lote*/, $isCaducidad/*, $caducidad*/,$unidadMedida); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "edit_datosProveedor":
                    $PKProducto = $_REQUEST['datos'];
                    $pkProveedor = $_REQUEST['datos2'];
                    $nombreProd = $_REQUEST['datos3'];
                    $clave = $_REQUEST['datos4'];
                    $precio = $_REQUEST['datos5'];
                    $moneda = $_REQUEST['datos6'];
                    $cantidadMin = $_REQUEST['datos7'];
                    $diasEntrega = $_REQUEST['datos8'];
                    $unidadMedida = $_REQUEST['datos9'];
                    $idDetalleProdProv = $_REQUEST['datos10'];
                    $json = $data->editDatosProveedor($PKProducto, $pkProveedor, $nombreProd, $clave, $precio, $moneda, $cantidadMin, $diasEntrega, $unidadMedida, $idDetalleProdProv); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "update_datosInventarioProducto":
                    $id = $_REQUEST['data'];
                    $sucursal = $_REQUEST['data1'];
                    $cantidad = $_REQUEST['data2'];
                    $serie = $_REQUEST['data3'];
                    $lote = $_REQUEST['data4'];
                    $caducidad = $_REQUEST['data5'];
                    $json = $data->editDatosInventarioProducto($id, $sucursal, $cantidad, $serie, $lote, $caducidad); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "edit_datosInventario":
                    $array = $_REQUEST['datos'];
                    $json = $data->editDatosInventario($array); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;

                    /* PROVEEDORES */
                case "edit_datosProveedorTable":
                    $array = $_REQUEST['datos'];
                    $nombreComercial = $_REQUEST['datos2'];
                    //$medioContactoCliente = $_REQUEST['datos3'];
                    $vendedor = $_REQUEST['datos4'];
                    $montoCredito = $_REQUEST['datos5'];
                    $diasCredito = $_REQUEST['datos6'];
                    $telefono = $_REQUEST['datos7'];
                    $email = $_REQUEST['datos8'];
                    $estatus = $_REQUEST['datos9'];
                    $pkProveedor = $_REQUEST['datos10'];
                    $tipoPersona = $_REQUEST['datos11'];
                    $email2 = $_REQUEST['datos12'];
                    $movil = $_REQUEST['datos13'];
                    $giro = $_REQUEST['datos14'];
                    $json = $data->editDatosProveedorTable($array, $nombreComercial, $vendedor, $montoCredito, $diasCredito, $telefono, $email, $estatus, $pkProveedor, $tipoPersona, $email2, $movil, $giro); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                    /* END PREVEEDORES */
                    /////////////////////////COLUMNAS AJUSTABLES PROVEEDORES//////////////////////////////
                case "update_check_column": //dato del proyecto que se eligió
                    $pkColumnaProveedor = $_REQUEST["data"];
                    $flag = $_REQUEST["flag"];
                    $json = $data->updateCheckColumn($pkColumnaProveedor, $flag); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                    /////////////////////////COLUMNAS AJUSTABLES PRODUCTOS//////////////////////////////
                case "update_check_columnProd": //dato del proyecto que se eligió
                    $pkColumnaProducto = $_REQUEST["data"];
                    $flag = $_REQUEST["flag"];
                    $json = $data->updateCheckColumnProd($pkColumnaProducto, $flag); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "edit_orden_compraTemp": //dato del proyecto que se eligió
                    $idproducto = $_REQUEST["datos"];
                    $cantidad = $_REQUEST["datos2"];
                    $pkUsuario = $_REQUEST["datos3"];
                    $pkProveedor = $_REQUEST["datos4"];
                    $json = $data->editOrdenCompraTemp($idproducto, $cantidad, $pkUsuario, $pkProveedor); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "edit_orden_compra": //dato del proyecto que se eligió
                    $idproducto = $_REQUEST["datos"];
                    $cantidad = $_REQUEST["datos2"];
                    $pkOrden = $_REQUEST["datos3"];
                    $pkProveedor = $_REQUEST["datos4"];
                    $json = $data->editOrdenCompra($idproducto, $cantidad, $pkOrden, $pkProveedor); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "edit_OrdenCompra_Cantidad": //dato del proyecto que se eligió
                    $idOrdenTemp = $_REQUEST["datos"];
                    $cantidad = $_REQUEST["datos2"];
                    $json = $data->editOrdenCompraCantidad($idOrdenTemp, $cantidad); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "edit_OrdenCompra_CantidadEdit": //dato del proyecto que se eligió
                    $idDetalleOrden = $_REQUEST["datos"];
                    $cantidad = $_REQUEST["datos2"];
                    $json = $data->editOrdenCompraCantidadEdit($idDetalleOrden, $cantidad); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "edit_OrdenCompra_Descuento": //dato del proyecto que se eligió
                    $descuento = $_REQUEST["datos"];
                    $json = $data->editOrdenCompraDescuento($descuento); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "edit_AceptarOrdenCompra": //dato del proyecto que se eligió
                    $PKOrdenCompraEncrypted = $_REQUEST["datos"];
                    $Estado = $_REQUEST["datos2"];
                    $json = $data->editAceptarOrdenCompra($PKOrdenCompraEncrypted, $Estado); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "edit_OrderPurchase": //dato del proyecto que se eligió
                    $fechaEntrega = $_REQUEST["datos"];
                    $direccionEntrega = $_REQUEST["datos2"];
                    $importe = $_REQUEST["datos3"];
                    $pkUsuario = $_REQUEST["datos4"];
                    $notasInternas = $_REQUEST["datos5"];
                    $notasProveedor = $_REQUEST["datos6"];
                    $PKOrden = $_REQUEST["datos7"];
                    $comprador = $_REQUEST["datos8"];
                    $condicion_Pago = $_REQUEST["datos9"];
                    $moneda = $_REQUEST["datos10"];
                    $categoria = $_REQUEST['datos11'];
                    $subcategoria = $_REQUEST['datos12'];
                    $json = $data->editOrderPurchase($fechaEntrega, $direccionEntrega, $importe, $pkUsuario, $notasInternas, $notasProveedor, $PKOrden, $comprador, $condicion_Pago, $moneda,$categoria,$subcategoria); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "edit_entrada_OCTemp": //dato del proyecto que se eligió
                    $idDetalle = $_REQUEST["data"];
                    $cantidad = $_REQUEST["data2"];
                    $json = $data->editEntradaOCTemp($idDetalle, $cantidad); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "edit_amount_entry_partial_temp": //dato del proyecto que se eligió
                    $cantidad = $_REQUEST["data"];
                    $idEntradaTemp = $_REQUEST["data2"];
                    $json = $data->editAmountEntryPartialTemp($cantidad, $idEntradaTemp); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "edit_amount_entry_tranfer_temp": //dato del proyecto que se eligió
                    $cantidad = $_REQUEST["data"];
                    $idEntradaTemp = $_REQUEST["data2"];
                    $json = $data->editAmountEntryTranferTemp($cantidad, $idEntradaTemp); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "edit_lot_entry_partial_temp": //dato del proyecto que se eligió
                    $lote = $_REQUEST["data"];
                    $idEntradaTemp = $_REQUEST["data2"];
                    $json = $data->editLotEntryPartialTemp($lote, $idEntradaTemp); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "edit_lot_entryDirect_temp": //dato del proyecto que se eligió
                    $lote = $_REQUEST["data"];
                    $idEntradaDirectaTemp = $_REQUEST["data2"];
                    $json = $data->editLotEntryDirectTemp($lote, $idEntradaDirectaTemp); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "edit_serie_entry_partial_temp": //dato del proyecto que se eligió
                    $serie = $_REQUEST["data"];
                    $idEntradaTemp = $_REQUEST["data2"];
                    $json = $data->editSerieEntryPartialTemp($serie, $idEntradaTemp); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "edit_serie_entryDirect_temp": //dato del proyecto que se eligió
                    $serie = $_REQUEST["data"];
                    $idEntradaDirectaTemp = $_REQUEST["data2"];
                    $json = $data->editSerieEntryDirectTemp($serie, $idEntradaDirectaTemp); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "edit_caducidad_entry_partial_temp": //dato del proyecto que se eligió
                    $caducidad = $_REQUEST["data"];
                    $idEntradaTemp = $_REQUEST["data2"];
                    $json = $data->editCaducidadEntryPartialTemp($caducidad, $idEntradaTemp); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "edit_cantidad_entryDirect_temp": //dato del proyecto que se eligió
                    $cantidad = $_REQUEST["data"];
                    $idEntradaDirectaTemp = $_REQUEST["data2"];
                    $json = $data->editCantidadEntryDirectTemp($cantidad, $idEntradaDirectaTemp); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "edit_caducidad_entryDirect_temp": //dato del proyecto que se eligió
                    $caducidad = $_REQUEST["data"];
                    $idEntradaDirectaTemp = $_REQUEST["data2"];
                    $json = $data->editCaducidadEntryDirect_temp($caducidad, $idEntradaDirectaTemp); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "edit_impuesto_entryDirect_temp": //dato del proyecto que se eligió
                    $impuesto = $_REQUEST["data"];
                    $idEntradaDirectaTemp = $_REQUEST["data2"];
                    $json = $data->editImpuestoEntryDirectTemp($impuesto, $idEntradaDirectaTemp); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "edit_impuestoIVA_entryDirect_temp": //dato del proyecto que se eligió
                    $impuesto = $_REQUEST["data"];
                    $idEntradaDirectaTemp = $_REQUEST["data2"];
                    $json = $data->editImpuestoIVAEntryDirectTemp($impuesto, $idEntradaDirectaTemp); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "edit_precio_entryDirect_temp": //dato del proyecto que se eligió
                    $precio = $_REQUEST["data"];
                    $idEntradaDirectaTemp = $_REQUEST["data2"];
                    $json = $data->editPrecioEntryDirectTemp($precio, $idEntradaDirectaTemp); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "edit_isImpuesto_entry_partial_temp": //dato del proyecto que se eligió
                    $idImpuestoOC = $_REQUEST["data"];
                    $isOrNot = $_REQUEST["data2"];
                    $json = $data->editIsImpuestoEntryPartialTemp($idImpuestoOC, $isOrNot); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "edit_entradaOC_estatusFactura": //dato del proyecto que se eligió
                    $folio = $_REQUEST["data"];
                    $serie = $_REQUEST["data2"];
                    $ordenCompra = $_REQUEST["data3"];
                    $id_cuenta_pagar = $_REQUEST["data4"];
                    $json = $data->editEntradaOCEstatusFactura($folio, $serie, $ordenCompra, $id_cuenta_pagar); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "edit_entradaOC_estatusFacturaEdit": //dato del proyecto que se eligió
                    $folio = $_REQUEST["data"];
                    $serie = $_REQUEST["data2"];
                    $folioEntrada = $_REQUEST["data3"];
                    $id_cuenta_pagar = $_REQUEST["data4"];
                    $json = $data->editEntradaOCEstatusFacturaEdit($folio, $serie, $folioEntrada, $id_cuenta_pagar); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "edit_salida_cantidad_modal_temp": //dato del proyecto que se eligió
                    $serieLote = $_REQUEST['data'];
                    $cantidad = $_REQUEST['data2'];
                    $ordenPedido = $_REQUEST['data3'];
                    $pkProducto = $_REQUEST['data4'];
                    $json = $data->editSalidaCantidadModalTemp($serieLote, $cantidad, $ordenPedido, $pkProducto); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "edit_salida_cantidad_modal_tempEdicion": //dato del proyecto que se eligió
                    $serieLote = $_REQUEST['data'];
                    $cantidad = $_REQUEST['data2'];
                    $folioSalida = $_REQUEST['data3'];
                    $pkProducto = $_REQUEST['data4'];
                    $json = $data->editSalidaCantidadModalTempEdicion($serieLote, $cantidad, $folioSalida, $pkProducto); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "edit_salida_cantidad_devolucion_temp": //dato del proyecto que se eligió
                    $idSalidaTemp = $_REQUEST['data'];
                    $cantidad = $_REQUEST['data2'];
                    $idCuentaPorPagar = $_REQUEST['data3'];
                    $pkProducto = $_REQUEST['data4'];
                    $json = $data->editSalidaCantidadDevolucionTemp($idSalidaTemp, $cantidad, $idCuentaPorPagar, $pkProducto); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "edit_estatusOC": //dato del proyecto que se eligió
                    $pkOrdenCompra = $_REQUEST['data'];
                    $json = $data->editEstatusOC($pkOrdenCompra); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case 'edit_entry_partial_ocTable':
                    $noDocumento = $_REQUEST['datos']['noDocumento'];
                    $serie = $_REQUEST['datos']['serie'];
                    $subtotal = $_REQUEST['datos']['subtotal'];
                    $iva = $_REQUEST['datos']['iva'];
                    $ieps = $_REQUEST['datos']['ieps'];
                    $importe = $_REQUEST['datos']['importe'];
                    $descuento = $_REQUEST['datos']['descuento'];
                    $fechaFactura = $_REQUEST['datos']['fechaFactura'];
                    $remision = $_REQUEST['datos']['remision']['active'];
                    $notas = $_REQUEST['datos']['notas'];
                    $folioEntrada = $_REQUEST['datos']['folioEntrada'];
                    $json = $data->editEntryPartialOcTable($folioEntrada, $noDocumento, $serie, $subtotal, $iva, $ieps, $importe, $descuento, $fechaFactura, $remision, $notas); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case 'edit_entry_tranfer_Table':
                    $folioEntrada = $_REQUEST['datos']['folioEntrada'];
                    $json = $data->editEntryTransferTable($folioEntrada); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case 'edit_datosSalida_OPEdicion':
                    $folio = $_REQUEST['data'];
                    $observaciones = $_REQUEST['data2'];
                    $json = $data->editDatosSalidaOPEdicion($folio, $observaciones); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case 'edit_datosSalida_DevolucionEdicion':
                    $folio = $_REQUEST['data'];
                    $observaciones = $_REQUEST['data2'];
                    $json = $data->editDatosSalidaDevolucionEdicion($folio, $observaciones); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "edit_entry_partial_edTable":
                    $notas = $_REQUEST['datos']['notas'];
                    $referencia = $_REQUEST['datos']['referencia'];
                    $json = $data->editEntryPartialEdTable($notas, $referencia); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "edit_entry_partial_edProviderTable":
                    if($_REQUEST['datos']['addCuentaPagar'] == 1){
                        //$serie = $_REQUEST['datos']['serie'];
                        $folio = $_REQUEST['datos']['folio'];
                        $subtotal = $_REQUEST['datos']['subtotal'];
                        $importe = $_REQUEST['datos']['importe'];
                        $fecha = $_REQUEST['datos']['fecha'];
                        $categoria = $_REQUEST['datos']['categoria'];
                        $subcategoria = $_REQUEST['datos']['subcategoria'];
                    }else{
                        //$serie = 0;
                        $folio = 0;
                        $subtotal = 0;
                        $importe = 0;
                        $fecha = '0000-00-00';
                        $categoria = null;
                        $subcategoria = null;
                    }
                    $tipoEntradas = $_REQUEST['datos']['tipoEntradas'];
                    $notas = $_REQUEST['datos']['notas'];
                    $referencia = $_REQUEST['datos']['referencia'];
                    $json = $data->editEntryPartialEdProviderTable('N/A', $folio, $tipoEntradas, $subtotal, $importe, $fecha, $notas, $referencia, $_REQUEST['datos']['addCuentaPagar'],$categoria,$subcategoria); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "edit_entry_partial_edCustomerTable":
                    $notas = $_REQUEST['datos']['notas'];
                    $referencia = $_REQUEST['datos']['referencia'];
                    $json = $data->editEntryPartialEdCustomerTable($notas, $referencia); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                    //END JAVIER RAMIREZ
                case "update_clavesKardexTemp":
                    $json = $data->editClavesKardexTemp(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "update_costo_cliente":
                    $pkRegistro = $_REQUEST['datos'];
                    $Costo = $_REQUEST['datos2'];
                    $moneda = $_REQUEST['datos3'];
                    $cliente = $_REQUEST['datos4'];
                    $json = $data->updateCostoCliente($pkRegistro, $Costo, $moneda, $cliente);//Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                  break;  
                case "edit_cancelInv":
                    $PKSucursal = $_REQUEST['data'];
                    $json = $data->editCancelInv($PKSucursal);//Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
            }
            break;

        case "delete_data":
            $data = new delete_data();
            switch ($_REQUEST['funcion']) {
                    //JOSÍAS PONCE
                case "delete_emptyProductStock":
                    $PKDetalle = $_REQUEST['data'];
                    $PKSucursal = $_REQUEST['data2'];
                    $PKProducto = $_REQUEST['data3'];
                    $Clave = $_REQUEST['data4'];
                    $Cantidad = $_REQUEST['data5'];
                    $json = $data->deleteEmptyProductStock($PKDetalle, $PKSucursal, $PKProducto, $Clave, $Cantidad); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                    //JAVIER RAMIREZ
                case "delete_impuesto_producto":
                    $value = $_REQUEST['datos'];
                    $json = $data->deleteImpuestoProducto($value); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "delete_accion_producto":
                    $value = $_REQUEST['datos'];
                    $json = $data->deleteAccionProducto($value); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "delete_accion_producto_temp":
                    $value = $_REQUEST['datos'];
                    $json = $data->deleteAccionProductoTemp($value); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "delete_categoria":
                    $value = $_REQUEST['datos'];
                    $json = $data->deleteCategoria($value); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "delete_marca":
                    $value = $_REQUEST['datos'];
                    $json = $data->deleteMarca($value); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "delete_tipoProducto":
                    $value = $_REQUEST['datos'];
                    $json = $data->deleteTipoProducto($value); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "delete_tipoOrdenInventario":
                    $value = $_REQUEST['datos'];
                    $json = $data->deleteTipoOrdenInventario($value); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "delete_datosProductoCompTempAll":
                    $value = $_REQUEST['datos'];
                    $json = $data->deleteDatosProductoCompTempAll($value); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "delete_datosProductoCompTemp":
                    $pkUsuario = $_REQUEST['datos'];
                    $pkProducto = $_REQUEST['datos2'];
                    $json = $data->deleteDatosProductoCompTemp($pkUsuario, $pkProducto); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "delete_proveedor_producto":
                    $value = $_REQUEST['datos'];
                    $json = $data->deleteProveedorProducto($value); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "delete_cliente_producto":
                    $value = $_REQUEST['datos'];
                    $json = $data->deleteClienteProducto($value); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "delete_Producto":
                    $value = $_REQUEST['data'];
                    $json = $data->deleteProducto($value); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;

                    /* PROVEEDORES */
                case "delete_razonSocial_Proveedor":
                    $value = $_REQUEST['datos'];
                    $json = $data->deleteRazonSocialProveedor($value); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "delete_direccionEnvio_Proveedor":
                    $value = $_REQUEST['datos'];
                    $json = $data->deleteDireccionEnvioProveedor($value); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "delete_contacto_Proveedor":
                    $value = $_REQUEST['datos'];
                    $json = $data->deleteContactoProveedor($value); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "delete_cuentaBancaria_Proveedor":
                    $value = $_REQUEST['datos'];
                    $json = $data->deleteCuentaBancariaProveedor($value); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "delete_ProveedorTable":
                    $value = $_REQUEST['data'];
                    $json = $data->deleteProveedorTable($value); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "delete_OrdenCompraTemp":
                    $value = $_REQUEST['data'];
                    $json = $data->deleteOrdenCompraTemp($value); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "delete_OrdenCompraTempAll":
                    $value = $_REQUEST['data'];
                    $json = $data->deleteOrdenCompraTempAll($value); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "delete_OrdenCompra":
                    $value = $_REQUEST['data'];
                    $json = $data->deleteOrdenCompra($value); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "delete_datosEntradaOCTemp":
                    $json = $data->deleteDatosEntradaOCTemp(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "delete_datosEntradaTransferTemp":
                    $json = $data->deleteDatosEntradaTransferTemp(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "delete_datosEntradaDirectaTemp":
                    $json = $data->deleteDatosEntradaDirectaTemp(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "delete_datosSalidaOPTemp":
                    $json = $data->deleteDatosSalidaOPTemp(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "delete_datosSalidaDevolucionTemp":
                    $json = $data->deleteDatosSalidaDevolucionTemp(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "delete_entry_partialRemove_ocTempTable":
                    $idEntradaTemp = $_REQUEST['data'];
                    $json = $data->deleteEntryPartialRemoveOcTempTable($idEntradaTemp); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "delete_exit_Remove_TempTable":
                    $idSalidaTemp = $_REQUEST['data'];
                    $json = $data->deleteExitRemoveTempTable($idSalidaTemp); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "delete_entry_traslade_tempTable":
                    $json = $data->deleteEntryTrasladeTempTable(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "delete_entry_transferRemove_TempTable":
                    $idEntradaTemp = $_REQUEST['data'];
                    $json = $data->deleteEntryTransferRemoveTempTable($idEntradaTemp); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "delete_entry_Table":
                    $folioEntrada = $_REQUEST['data'];
                    $json = $data->deleteEntryTable($folioEntrada); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "delete_entryTranfer_Table":
                    $folioEntrada = $_REQUEST['data'];
                    $json = $data->deleteEntryTranferTable($folioEntrada); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "delete_entryDirectBranch_Table":
                    $folioEntrada = $_REQUEST['data'];
                    $json = $data->deleteEntryDirectBranchTable($folioEntrada); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "delete_entryDirectProvider_Table":
                    $folioEntrada = $_REQUEST['data'];
                    $json = $data->deleteEntryDirectProviderTable($folioEntrada); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "delete_entryDirectCustomer_Table":
                    $folioEntrada = $_REQUEST['data'];
                    $json = $data->deleteEntryDirectCustomerTable($folioEntrada); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "delete_entryAdjust_Table":
                    $folioEntrada = $_REQUEST['data'];
                    $json = $data->deleteEntryAdjustTable($folioEntrada); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "delete_datosSalida_All":
                    $folioSalida = $_REQUEST['data'];
                    $json = $data->deleteDatosSalidaAll($folioSalida); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "delete_entry_Remove_EDTempTable":
                    $idEntradaDirecta = $_REQUEST['data'];
                    $json = $data->deleteEntryRemoveEDTempTable($idEntradaDirecta); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                    //END JAVIER RAMIREZ
            }
            break;

            /*
        case "upload_file":
        $data = new upload_file();
        switch ($_REQUEST['funcion']) {
        case 'upload_xml_entry':
        $value = $_REQUEST['data'];
        //$file = $_FILES['data'];
        print_r($_REQUEST['data']);
        $json = $data->uploadXmlEntries($value);//Guardando el return de la función
        echo $json; //Retornando el resultado al ajax
        return;
        break;
        }
        break;
         */

        case "delete_file":
            $data = new delete_file();
            switch ($_REQUEST['funcion']) {
                case 'delete_xml':
                    $value = $_REQUEST['data'];
                    $json = $data->deleteXml($value); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
            }
            break;
    }
}