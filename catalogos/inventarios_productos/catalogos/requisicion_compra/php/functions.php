<?php
include_once("clases.php");
$array = "";
if (isset($_REQUEST['clase']) && isset($_REQUEST['funcion'])) {
    switch ($_REQUEST['clase']) {
        case "get_data" :
            $data = new get_data();
            switch ($_REQUEST['funcion']) {
                case 'get_requisicionesTable' :
                    $isPermissionsEdit = $_REQUEST['data'];
                    $isPermissionsDelete = $_REQUEST['data2'];
                    $json = $data->getRequisicionesTable($isPermissionsEdit,$isPermissionsDelete); //Guardando el return de la función
                    echo ($json); //Retornando el resultado al ajax
                    return;
                    break;
                case 'get_productos' :
                    $json = $data->get_cmbProductos(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_ProductosTable" :
                    $IDprods = $_REQUEST['prods'];
                    $provee = $_REQUEST['provee'];
                    $json = $data->getProductosTable($IDprods, $provee); //Guardando el return de la función
                    echo ($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_cmb_Empleado":
                    $json = $data->getCmbEmpleado();//Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_cmb_Area":
                    $json = $data->getCmbArea();//Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "get_data_CabeceraRequisicion":
                    $idRequisicion = $_REQUEST['data'];
                    $json = $data->getCabeceraRequisicion($idRequisicion);//Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case 'get_ProductosTableDetalle' :
                    $idRequisicion = $_REQUEST['data'];
                    $json = $data->get_ProductosDetalle($idRequisicion); //Guardando el return de la función
                    echo ($json); //Retornando el resultado al ajax
                    return;
                    break;
                case 'get_dataRequisicionEdit' :
                    $idRequisicion = $_REQUEST['data'];
                    $json = $data->getDataRequisicionEdit($idRequisicion); //Guardando el return de la función
                    echo ($json); //Retornando el resultado al ajax
                    return;
                    break;   
                case "get_data_CabeceraSeguimientoRequisicion":
                    $idRequisicion = $_REQUEST['data'];
                    $json = $data->getCabeceraSeguimientoRequisicion($idRequisicion);//Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;  
                case 'get_productosRequisicion' :
                    $idRequisicion = $_REQUEST['idRequisicion'];
                    $idProveedor = isset($_REQUEST['idProveedor']) ? $_REQUEST['idProveedor'] : 0;
                    $json = $data->get_cmbProductosRequisicion($idRequisicion, $idProveedor); //Guardando el return de la función
                    echo ($json); //Retornando el resultado al ajax
                    return;
                    break;
                case 'get_data_ProductosRequisicion' :
                    $idRequisicion = $_REQUEST['idRequisicion'];
                    $json = $data->get_dataProductosRequisicion($idRequisicion); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case 'get_Taxes_ProductosRequisicion' :
                    $idRequisicion = $_REQUEST['idRequisicion'];
                    $json = $data->get_taxesProductosRequisicion($idRequisicion); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;  
                case 'get_ordenes_generadas' :
                    $idRequisicion = $_REQUEST['data'];
                    $json = $data->get_OrdenesGeneradas($idRequisicion); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;  
            }
            break;
        case "save_data" :
            $data = new save_data();
            switch ($_REQUEST['funcion']) {
                case "save_requisicion" :
                    $isPermissionsAdd = $_REQUEST['data2'];
                    $datos = $_REQUEST['data3'];
                    $_FechaEstimada = $_REQUEST['data4'];
                    $_SucursalEntrega = $_REQUEST['data5'];
                    $_Area = $_REQUEST['data6'];
                    $_Empleado = $_REQUEST['data7'];
                    $_Proveedor = $_REQUEST['data8'];
                    $_Comprador = $_REQUEST['data9'];
                    $_NotasComprador = $_REQUEST['data12'];
                    $_NotasInternas = $_REQUEST['data13'];
                    $json = $data->saveRequisicion($isPermissionsAdd,$datos,$_FechaEstimada,$_SucursalEntrega,$_Area,$_Empleado,$_Proveedor,$_Comprador,$_NotasComprador,$_NotasInternas); //Guardando el return de la función
                    echo ($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "update_requisicion" :
                    $isPermissionsEdit = $_REQUEST['data2'];
                    $idRequisicion = $_REQUEST['data3'];
                    $insert = isset($_REQUEST['insert']) ? $_REQUEST['insert'] : 0;
                    $update = isset($_REQUEST['update']) ? $_REQUEST['update'] : 0;
                    $delete = isset($_REQUEST['delete']) ? $_REQUEST['delete'] : 0;
                    $_FechaEstimada = $_REQUEST['data4'];
                    $_SucursalEntrega = $_REQUEST['data5'];
                    $_Area = $_REQUEST['data6'];
                    $_Empleado = $_REQUEST['data7'];
                    $_Proveedor = $_REQUEST['data8'];
                    $_Comprador = $_REQUEST['data9'];
                    $_NotasComprador = $_REQUEST['data12'];
                    $_NotasInternas = $_REQUEST['data13'];
                    $json = $data->updateRequisicion($isPermissionsEdit, $idRequisicion, $insert,$update,$delete,$_FechaEstimada,$_SucursalEntrega,$_Area,$_Empleado,$_Proveedor,$_Comprador,$_NotasComprador,$_NotasInternas); //Guardando el return de la función
                    echo ($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "save_seguimiento" :
                    $isPermissionsAdd = $_REQUEST['data2'];
                    $datos = $_REQUEST['data3'];
                    $_SucursalEntrega = $_REQUEST['data4'];
                    $_Proveedor = $_REQUEST['data5'];
                    $_Comprador = $_REQUEST['data6'];
                    $_NotasProveedor = $_REQUEST['data7'];
                    $_CondicionPago = $_REQUEST['data8'];
                    $_Moneda = $_REQUEST['data9'];
                    $_FechaEstimada = $_REQUEST['data10'];
                    $idRequisicion = $_REQUEST['data11'];
                    $json = $data->saveSeguimiento($isPermissionsAdd, $datos, $_FechaEstimada, $_SucursalEntrega,$_Proveedor,$_Comprador,$_NotasProveedor,$_CondicionPago,$_Moneda,$idRequisicion); //Guardando el return de la función
                    echo ($json); //Retornando el resultado al ajax
                    return;
                    break;
            }
            break;
        case "delete_data" :
            $data = new delete_data();
            switch ($_REQUEST['funcion']) {
                case "cancela_Requisicion" :
                    $idRequisicion = $_REQUEST['data2'];
                    $json = $data->cancelaRequisicion($idRequisicion); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break; 
                case "cerrar_Requisicion" :
                    $idRequisicion = $_REQUEST['data2'];
                    $json = $data->cerrarRequisicion($idRequisicion); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break; 
            }
            break;
        case "edit_data" :
            $data = new edit_data();
            switch ($_REQUEST['funcion']) {

            }
            break;
        case "send_data" :
            $data = new send_data();
            switch ($_REQUEST['funcion']) {

            }
            break;
        case "validate_data" :
            $data = new validate_data();
            switch ($_REQUEST['funcion']) {
                case "validate_permisos" :
                    $json = $data->validaPermisosPantalla(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "validate_isComprador_Requisicion":
                    $json = $data->validateIsComprador(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
                case "validar_estadoRequisicionCompra":
                    $idRequisicion = $_REQUEST['data'];
                    $json = $data->validateEstadoRequisicion($idRequisicion); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;
            }
            break;
    }
}
?>