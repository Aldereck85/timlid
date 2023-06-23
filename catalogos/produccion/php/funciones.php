<?php
include_once "clases.php";
$array = "";
if (isset($_REQUEST['clase']) && isset($_REQUEST['funcion'])) {

    switch ($_REQUEST['clase']) {
        case "get_data":
            $data = new get_data();
            switch ($_REQUEST['funcion']) {
                //JAVIER RAMIREZ
                /////////////////////////TABLAS//////////////////////////////
                
                case "get_listaMaterialesTable":
                    $isPermissionsEdit = $_REQUEST['data'];
                    $isPermissionsDelete = $_REQUEST['data2'];
                    $json = $data->getListaMaterialesTable($isPermissionsEdit,$isPermissionsDelete); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                break;

                case "get_unidadesSATTable":
                    $buscador = $_REQUEST['data'];
                    $json = $data->getUnidadesSATTable($buscador); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                break;

                case "get_listaMaterialesConsumirTable":
                    $sucursal = $_REQUEST['data'];
                    $json = $data->getlistaMaterialesConsumirTable($sucursal); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                break;
                
                /////////////////////////COMBOS//////////////////////////////

                case "get_cmb_productos":
                    $PKProducto = $_REQUEST['data'];
                    $json = $data->getCmbProductos($PKProducto); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                break;
                case "get_cmb_empaques":
                    $PKProducto = $_REQUEST['data'];
                    $json = $data->getCMBEmpaques($PKProducto); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                break;
                case "get_cmb_empaques":
                    $PKProducto = $_REQUEST['data'];
                    $json = $data->getCmbEmpaques($PKProducto); //Guardando el return de la función
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
                    $json = $data->getDataDatosProductoCompuesto($pkProducto); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "get_DataDatosEmpaqueCompuesto":
                    $pkProducto = $_REQUEST['data'];
                    $json = $data->getDataDatosEmpaqueCompuesto($pkProducto); //Guardando el return de la función
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
                
                /////////////////////////COMBOS//////////////////////////////

                case "get_sucursales":
                    $json = $data->getSucursales();//Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;

                /* END JAVIER RAMIREZ */
            }
            break;

        case "save_data":
            $data = new save_data();
            switch ($_REQUEST['funcion']) {
                //JAVIER RAMIREZ
                
                case "save_datosProductoCompTemp":
                    $pkProducto = $_REQUEST['datos'];
                    $cantidad = $_REQUEST['datos2'];
                    $PKCompuestoTemp = $_REQUEST['datos4'];
                    $costo = $_REQUEST['datos5'];
                    $moneda = $_REQUEST['datos6'];
                    $colectivos = $_REQUEST['datos7'];                    
                    $json = $data->saveDatosProductoCompTemp($pkProducto, $cantidad, $PKCompuestoTemp, $costo, $moneda, $colectivos); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "save_datosProductoCompTempAll":
                    $pkProducto = $_REQUEST['datos'];                   
                    $json = $data->saveDatosProductoCompTempAll($pkProducto); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                
                //END JAVIER RAMIREZ
            }
            break;

        case "edit_data":
            $data = new edit_data();
            switch ($_REQUEST['funcion']) {
                //JAVIER RAMIREZ
                
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
                case "edit_colectivosEmpaqueCompTemp":
                    $pkProducto = $_REQUEST['datos'];
                    $colectivos = $_REQUEST['datos2'];
                    $json = $data->editColectivosEmpaqueCompTemp($pkProducto, $colectivos); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "edit_colectivosEmpaqueCompTemp":
                    $pkProducto = $_REQUEST['datos2'];
                    $unidadMedidaID = $_REQUEST['datos3'];
                    $costo = $_REQUEST['datos4'];
                    $moneda = $_REQUEST['datos5'];
                    $json = $data->editColectivosEmpaqueCompTemp($pkProducto, $costo, $moneda, $unidadMedidaID); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "edit_datosListaMaterialEstatus":
                    $pkProducto = $_REQUEST['datos'];
                    $json = $data->editDatosListaMaterialEstatus($pkProducto); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;

                //END JAVIER RAMIREZ
            }
            break;

        case "delete_data":
            $data = new delete_data();
            switch ($_REQUEST['funcion']) {
                //JAVIER RAMIREZ
                
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
                
                //END JAVIER RAMIREZ
            }
            break;
    }

}