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
                
                case "get_notaCreditoTable":
                    $isPermissionsEdit = $_REQUEST['data'];
                    $isPermissionsDelete = $_REQUEST['data2'];
                    $json = $data->getNotaCreditoTable($isPermissionsEdit,$isPermissionsDelete); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                break;
                case "get_productosDevolucionTable":
                    $pkDevolucion = $_REQUEST['data'];
                    $json = $data->getProductosDevolucionTable($pkDevolucion); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                break;
                case "get_notaCredito_Productos_Cantidad_Table":
                    $pkDevolucion = $_REQUEST['data'];
                    $json = $data->getNotaCreditoProductosCantidadTable($pkDevolucion); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                break;

                /////////////////////////COMBOS//////////////////////////////

                case "get_cmb_notaCredito_cuentaPagar":
                    $json = $data->getCmbNotaCreditoCuentaPagar(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "get_cmb_notaCreditoCant_cuentaPagar":
                    $pkDevolucion = $_REQUEST['data'];
                    $json = $data->getCmbNotaCreditoCantCuentaPagar($pkDevolucion); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "get_cmb_notaCredito_proveedor":
                    $json = $data->getCmbNotaCreditoProveedor(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "get_cmb_notaCredito_proveedorDevolucion":
                    $json = $data->getCmbNotaCreditoProveedorDevolucion(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "get_cmb_notaCredito_devolucion":
                    $pkProveedor = $_REQUEST['data'];
                    $json = $data->getCmbNotaCreditoDevolucion($pkProveedor); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "get_cmb_notaCredito_devolucionCant":
                    $pkProveedor = $_REQUEST['data'];
                    $json = $data->getCmbNotaCreditoDevolucionCant($pkProveedor); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;

                /////////////////////////DATOS PARA EDICIÓN//////////////////////////////
                
                case "get_dataNCMonto":
                    $pkNotaCredito = $_REQUEST['data'];
                    $json = $data->getDataNCMonto($pkNotaCredito); //Guardando el return de la función
                    echo json_encode($json);
                break;
                case "get_dataNCCantidad":
                    $pkNotaCredito = $_REQUEST['data'];
                    $json = $data->getDataNCCantidad($pkNotaCredito); //Guardando el return de la función
                    echo json_encode($json);
                break;

                /////////////////////////VALIDACIONES//////////////////////////////
                
                case "validar_Permisos":
                    $pkPantalla = $_REQUEST['data'];
                    $json = $data->validarPermisos($pkPantalla); //Guardando el return de la función
                    echo json_encode($json);
                break;
                case "validar_notaCredito_cantidadProd_devolucion":
                    $idCantidadTemp = $_REQUEST['data'];
                    $cantidad = $_REQUEST['data2'];
                    $json = $data->validarNotaCreditoCantidadProd_devolucion($idCantidadTemp, $cantidad); //Guardando el return de la función
                    echo json_encode($json);
                break;

                /////////////////////////INFO//////////////////////////////
                
                case "get_subTotalNotaCreditoCantTemp":
                    $json = $data->getSubTotalNotaCreditoCantTemp(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "get_impuestoNotaCreditoCantTemp":
                    $json = $data->getImpuestoNotaCreditoCantTemp(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "get_totalNotaCreditoCantTemp":
                    $json = $data->getTotalNotaCreditoCantTemp(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;

                //END JAVIER RAMIREZ
            }
            break;

        case "save_data":
            $data = new save_data();
            switch ($_REQUEST['funcion']) {
                //JAVIER RAMIREZ
                
                case "save_datosNotaCreditoMonto":
                    $array = $_REQUEST['datos'];
                    $tipoGral = $_REQUEST['datos']['tipoGral'];
                    $serie = $_REQUEST['datos']['serie'];
                    $folio = $_REQUEST['datos']['folio'];
                    $importe  = $_REQUEST['datos']['importe'];
                    $subtotal  = $_REQUEST['datos']['subtotal'];
                    $iva  = $_REQUEST['datos']['iva'];
                    $ieps  = $_REQUEST['datos']['ieps'];
                    $fechaNota  = $_REQUEST['datos']['fechaNota'];
                    $tipoNota  = $_REQUEST['datos']['tipoNota'];
                    $archivoPDF  = $_REQUEST['datos']['archivoPDF'];
                    $archivoXML  = $_REQUEST['datos']['archivoXML'];
                    $proveedor  = $_REQUEST['datos']['proveedor'];
                    $folioFiscal  = $_REQUEST['datos']['folioFiscal'];
                    $cuentaPagar  = $_REQUEST['datos']['cuentaPagar'];
                    $pkNotaCredito  = $_REQUEST['datos']['pkNotaCredito'];
                    $json = $data->saveDatosNotaCreditoMonto(
                        $array, 
                        $tipoGral,
                        $serie, 
                        $folio, 
                        $importe, 
                        $subtotal, 
                        $iva, 
                        $ieps, 
                        $fechaNota, 
                        $tipoNota, 
                        $archivoPDF,
                        $archivoXML,
                        $proveedor, 
                        $folioFiscal, 
                        $cuentaPagar, 
                        $pkNotaCredito
                    ); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "save_datosNotaCreditoCantidad":
                    $array = $_REQUEST['datos'];
                    $tipoGral = $_REQUEST['datos']['tipoGral'];
                    $serie = $_REQUEST['datos']['serie'];
                    $folio = $_REQUEST['datos']['folio'];
                    $importe  = $_REQUEST['datos']['importe'];
                    $subtotal  = $_REQUEST['datos']['subtotal'];
                    $iva  = $_REQUEST['datos']['iva'];
                    $ieps  = $_REQUEST['datos']['ieps'];
                    $fechaNota  = $_REQUEST['datos']['fechaNota'];
                    $tipoNota  = $_REQUEST['datos']['tipoNota'];
                    $archivoPDF  = $_REQUEST['datos']['archivoPDF'];
                    $archivoXML  = $_REQUEST['datos']['archivoXML'];
                    $proveedor  = $_REQUEST['datos']['proveedor'];
                    $devolucion  = $_REQUEST['datos']['devolucion'];
                    $folioFiscal  = $_REQUEST['datos']['folioFiscal'];
                    $cuentaPagar  = $_REQUEST['datos']['cuentaPagar'];
                    $pkNotaCredito  = $_REQUEST['datos']['pkNotaCredito'];
                    $json = $data->saveDatosNotaCreditoCantidad(
                        $array, 
                        $tipoGral,
                        $serie, 
                        $folio, 
                        $importe, 
                        $subtotal, 
                        $iva, 
                        $ieps, 
                        $fechaNota, 
                        $tipoNota, 
                        $archivoPDF,
                        $archivoXML,
                        $proveedor, 
                        $devolucion,
                        $folioFiscal, 
                        $cuentaPagar, 
                        $pkNotaCredito
                    ); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "save_datosNotaCreditoProductoCant":
                    $idDetalleDev = $_REQUEST['data'];
                    $pkDevolucion = $_REQUEST['data2'];
                    $json = $data->saveDatosNotaCreditoProductoCant($idDetalleDev, $pkDevolucion); //Guardando el return de la función
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
                
                case "edit_cantidadNotaCreditoProductoTemp":
                    $idDetalleNCTemp = $_REQUEST['data'];
                    $cantidad = $_REQUEST['data2'];
                    $json = $data->editCantidadNotaCreditoProductoTemp($idDetalleNCTemp, $cantidad); //Guardando el return de la función
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
                
                case "delete_datosNotaCreditoCantidadAllTemp":
                    $json = $data->deleteDatosNotaCreditoCantidadAllTemp();//Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "delete_datosNotaCreditoProductoTemp":
                    $idDetalleNCTemp = $_REQUEST['datos'];
                    $json = $data->deleteDatosNotaCreditoProductoTemp($idDetalleNCTemp);//Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "delete_InsertdatosNCCantidadAllTemp":
                    $pkDevolucion = $_REQUEST['data'];
                    $pkNotaCredito = $_REQUEST['data2'];
                    $json = $data->deleteInsertdatosNCCantidadAllTemp($pkDevolucion, $pkNotaCredito);//Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "delete_datosNotaCredito":
                    $pkNotaCredito = $_REQUEST['datos'];
                    $json = $data->deleteDatosNotaCredito($pkNotaCredito);//Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;

                //END JAVIER RAMIREZ
            }
            break;
    }

}