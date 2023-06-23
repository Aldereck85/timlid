<?php
include_once "clases.php";
$array = "";
if (isset($_REQUEST['clase']) && isset($_REQUEST['funcion'])) {

    switch ($_REQUEST['clase']) {
        case "get_data":
            $data = new get_data();
            switch ($_REQUEST['funcion']) {
                //JULIO
                /////////////////////////TABLAS//////////////////////////////
                
                case "get_listaCostosTable":
                    $isPermissionsEdit = $_REQUEST['data'];
                    $isPermissionsDelete = $_REQUEST['data2'];
                    $json = $data->get_listaCostos($isPermissionsEdit,$isPermissionsDelete); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                break;

                case "get_unidadesSATTable":
                    $buscador = $_REQUEST['data'];
                    $modo = $_REQUEST['modo'];
                    $json = $data->getUnidadesSATTable($buscador,$modo); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                break;
                
                /////////////////////////COMBOS//////////////////////////////

                case "get_Productos":
                    $json = $data->getProductos(); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                break;

                case "get_Costos":
                    $idCosto = $_REQUEST['datos'];
                    $json = $data->getCostos($idCosto); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                break;

                case "get_cmb_proveedores":
                    $modo = $_REQUEST['modo']; 
                    $json = $data->getCmbProveedores($modo); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                break;

                case "get_cmb_tipo":
                    $tipo = $_REQUEST['datos'];
                    $json = $data->getCmbTipo($tipo); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                    break;

                case "get_cmb_productos":
                    $PKProducto = $_REQUEST['data'];
                    $modo = $_REQUEST['modo'];
                    $json = $data->getCmbProductos($PKProducto, $modo); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                break;

                case "get_costos_historicos":
                    $PKProducto = $_REQUEST['data'];
                    $modo = $_REQUEST['modo'];
                    $proveedor = $_REQUEST["proveedor"];
                    $json = $data->getCostosHistoricos($PKProducto, $modo, $proveedor); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                break;
                case "get_cmb_producto_listaCompuestos":
                    $json = $data->getCmbProductoListaCompuestos(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "get_cmb_moneda":
                    $json = $data->getCmbMoneda(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                
                /////////////////////////DATOS PARA EDICIÓN//////////////////////////////
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

                /////////////////////////VALIDACIONES//////////////////////////////
                
                case "validar_Permisos":
                    $pkPantalla = $_REQUEST['data'];
                    $json = $data->validarPermisos($pkPantalla); //Guardando el return de la función
                    echo json_encode($json);
                break;
                case "validar_producto_compuesto_temp":
                    $pkProducto = $_REQUEST['data'];
                    $json = $data->validarProductoCompuestoTemp($pkProducto); //Guardando el return de la función
                    echo json_encode($json);
                break;

                /////////////////////////INFO//////////////////////////////
                

            }
            break;

        case "save_data":
            $data = new save_data();
            switch ($_REQUEST['funcion']) {


                case "guardar_Costos":
                    $datos = $_REQUEST['datos'];
                    $json = $data->guardarCostos($datos); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;

                case "save_datosProducto":
                    $datos = $_REQUEST['datos'];
                    $json = $data->guardarProducto($datos); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;

                
                case "save_datosProductoCompTemp":
                    $pkProducto = $_REQUEST['datos'];
                    $cantidad = $_REQUEST['datos2'];
                    $PKCompuestoTemp = $_REQUEST['datos4'];
                    $costo = $_REQUEST['datos5'];
                    $moneda = $_REQUEST['datos6'];
                    $json = $data->saveDatosProductoCompTemp($pkProducto, $cantidad, $PKCompuestoTemp, $costo, $moneda); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                
            }
            break;

        case "edit_data":
            $data = new edit_data();
            switch ($_REQUEST['funcion']) {
                
                case "editar_Costos":
                    $datos = $_REQUEST['datos'];
                    $json = $data->editarCostos($datos); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;

                case "modificar_unidad_sat":
                    $idProducto = $_REQUEST['idProducto'];
                    $idUnidadSat = $_REQUEST['idUnidadSat'];
                    $json = $data->modificarUnidadSAT($idProducto,$idUnidadSat); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;

                case "edit_ListaMaterialesProducto":
                    $array = $_REQUEST['datos'];
                    $pkProducto = $_REQUEST['datos']['pkProducto'];
                    $estatus = $_REQUEST['datos']['estatus'];
                    $json = $data->editListaMaterialesProducto($array, $pkProducto, $estatus); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "edit_datosCantidadProductoCompTemp":
                    $pkProducto = $_REQUEST['datos2'];
                    $cantidad = $_REQUEST['datos3'];
                    $costo = $_REQUEST['datos4'];
                    $moneda = $_REQUEST['datos5'];
                    $json = $data->editDatosCantidadProductoCompTemp($pkProducto, $cantidad, $costo, $moneda); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "edit_datosCostoProductoCompTemp":
                    $pkProducto = $_REQUEST['datos2'];
                    $costo = $_REQUEST['datos4'];
                    $moneda = $_REQUEST['datos5'];
                    $json = $data->editDatosCostoProductoCompTemp($pkProducto, $costo, $moneda); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "edit_datosMonedaProductoCompTemp":
                    $pkProducto = $_REQUEST['datos2'];
                    $costo = $_REQUEST['datos4'];
                    $moneda = $_REQUEST['datos5'];
                    $json = $data->editDatosMonedaProductoCompTemp($pkProducto, $costo, $moneda); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "edit_datosUnidadMProductoCompTemp":
                    $pkProducto = $_REQUEST['datos2'];
                    $unidadMedidaID = $_REQUEST['datos3'];
                    $costo = $_REQUEST['datos4'];
                    $moneda = $_REQUEST['datos5'];
                    $json = $data->editDatosUnidadMProductoCompTemp($pkProducto, $costo, $moneda, $unidadMedidaID); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "edit_datosListaMaterialEstatus":
                    $pkProducto = $_REQUEST['datos'];
                    $json = $data->editDatosListaMaterialEstatus($pkProducto); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;


            }
            break;

        case "delete_data":
            $data = new delete_data();
            switch ($_REQUEST['funcion']) {

                case "delete_Costos":
                    $pkCosto = $_REQUEST['datos'];
                    $json = $data->deleteCostos($pkCosto); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;

                case "delete_datosProductoCompTemp":
                    $pkProducto = $_REQUEST['datos'];
                    $json = $data->deleteDatosProductoCompTemp($pkProducto); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;

                case "delete_datosProductoCompTempAll":
                    $json = $data->deleteDatosProductoCompTempAll(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;

            }
            break;
    }

}