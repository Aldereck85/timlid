<?php
include_once "class.php";

if (isset($_REQUEST['clase']) && isset($_REQUEST['funcion'])) {
    
    switch ($_REQUEST['clase']) {
            case "get_data":
                $data = new Get_datasNC;
                switch ($_REQUEST['funcion']) {
                    case "getFacturas_Cliente":
                        $id_cliente = $_REQUEST['id_cliente'];
                        $json = $data->getFacturas_Cliente($id_cliente); //Guardando el return de la función
                        echo json_encode($json); //Retornando el resultado al ajax
                        return;
                    break;
                    case "get_Cliente":
                        $json = $data->loadCmbClientes(); //Guardando el return de la función
                        echo json_encode($json); //Retornando el resultado al ajax
                        return;
                    break;
                    case "get_cabecera":
                        $idNC = $_REQUEST['idnc'];
                        $json = $data->loadCabecera($idNC); //Guardando el return de la función
                        echo json_encode($json); //Retornando el resultado al ajax
                        return;
                    break;
                    case "get_tblConceptos":
                        $idNC = $_REQUEST['idnc'];
                        $json = $data->loadtblConceptos($idNC); //Guardando el return de la función
                        echo json_encode($json); //Retornando el resultado al ajax
                        return;
                    break;
                    case "get_ImpGen":
                        $id_factura = $_REQUEST['id_factura'];
                        $json = $data->loadCMBImpGen($id_factura); //Guardando el return de la función
                        echo json_encode($json); //Retornando el resultado al ajax
                        return;
                    break;
                    case "get_ImpuestosPrd":
                        $id_producto = $_REQUEST['id_producto'];
                        $json = $data->loadCMBImpuestosPrd($id_producto); //Guardando el return de la función
                        echo json_encode($json); //Retornando el resultado al ajax
                        return;
                    break;
                    case "get_detalle_factura":
                        $id_factura = $_REQUEST['id_factura'];
                        $json = $data->loadCMBdetallesFact($id_factura); //Guardando el return de la función
                        echo json_encode($json); //Retornando el resultado al ajax
                        return;
                    break;
                    case "get_fd_pago":
                        $json = $data->loadCmbFdPago(); //Guardando el return de la función
                        echo json_encode($json); //Retornando el resultado al ajax
                        return;
                    break;
                    case "get_fd_relacion_fact":
                        $json = $data->loadCmbRelacion_fact(); //Guardando el return de la función
                        echo json_encode($json); //Retornando el resultado al ajax
                        return;
                    break;
                    case "get_Concept":
                        $word = $_REQUEST['palabra'];
                        $json = $data->loadlistConcepto($word); //Guardando el return de la función
                        echo json_encode($json); //Retornando el resultado al ajax
                        return;
                    break;
                    case "get_claveSat":
                        $word = $_REQUEST['palabra'];
                        $json = $data->loadlistClavesSat($word); //Guardando el return de la función
                        echo json_encode($json); //Retornando el resultado al ajax
                        return;
                    break;
                    //Pide los datos para la lista del select
                    case "get_claveSat_unit":
                        $word = $_REQUEST['palabra'];
                        $json = $data->loadlistClavesSat_unit($word); //Guardando el return de la función
                        echo json_encode($json); //Retornando el resultado al ajax
                        return;
                    break;
                    //Pide las notas de credito del cliente que se va a cancelar
                    case "get_Docs":
                        $cliente = $_REQUEST['client'];
                        $json = $data->load_DocsR($cliente); //Guardando el return de la función
                        echo json_encode($json); //Retornando el resultado al ajax
                        break;
                }
                break;

            case "insert_data":
                $data = new Insert_datas;
                switch ($_REQUEST['funcion']) {
                    
                }
                break;
            case "update_data":
                $data = new Update_datasNC;
                switch ($_REQUEST['funcion']) {
                    
                }
                break;
            case "delete_data":
                $data = new Delete_datas;
                switch ($_REQUEST['funcion']) {
                    case "cancel_NC":
                        $idNC = $_REQUEST['idnc'];
                        $motive = $_REQUEST['motive'];
                        $idsnc = $_REQUEST['idsnc'];
                        $json = $data->delete_NC($idNC,$motive,$idsnc); //Guardando el return de la función
                        echo ($json); //Retornando el resultado al ajax
                        return;
                    break;
                    case "cancel_NCV":
                        $idNCV = $_REQUEST['idnc'];
                        $json = $data->delete_NCV($idNCV); //Guardando el return de la función
                        echo ($json); //Retornando el resultado al ajax
                        return;
                    break;
                }
                break;
            case "send_data":
                $data = new send_data();
                switch($_REQUEST['funcion']){
                    case "send_email":
                    $value = $_REQUEST['value'];
                    $arr = $_REQUEST['destinos'];
                    $json = $data->sendEmail($value,$arr);
                    echo json_encode($json);
                    break;
                }
                break;
    }
}

?>