<?php
include_once "addclass.php";


if (isset($_REQUEST['clase']) && isset($_REQUEST['funcion'])) {
    
    switch ($_REQUEST['clase']) {
        case "get_data":
            $data = new Get_datas;
            switch ($_REQUEST['funcion']) {
                case "get_proveedorCombo":
                    $json = $data->loadCmbProviders(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "get_cuenta":
                    $json = $data->loadCmbCuentas(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "get_cuenta_cheque":
                    $json = $data->loadCmbCuentasCheques(); //Guardando el return de la función
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
                case "get_cuenta_otras":
                    $json = $data->loadCmbCuentasOtras(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "validateTptal":
                    $id = $_REQUEST['id'];
                    $importe = $_REQUEST['importe'];
                    $json = $data->validateimportes($id,$importe); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "Update_validateimportes":
                    $id = $_REQUEST['id'];
                    $importe = $_REQUEST['importe'];
                    $id_pago = $_REQUEST['id_pago'];
                    $json = $data->Update_validateimportes($id,$id_pago,$importe); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "Add_Validate_importe":
                    $ids = $_REQUEST['ids'];
                    $_origenCE = $_REQUEST['origen'];
                    $_cadena_CP = $_REQUEST['_cadena_CP'];
                    $array_cadena_CP = explode(",", $_cadena_CP);
                            $count_cadena = count($array_cadena_CP);
                    $json = $data->Add_Validate_importe($ids,$_origenCE,$_cadena_CP,$count_cadena); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "Add_Validate_importeAnticipos":
                    $_origenCE = $_REQUEST['origen'];
                    $_cadena_CP = $_REQUEST['_cadena_CP'];
                    $_cadena_CP_insolutos = $_REQUEST['_cadena_CP_insolutos'];
                    $_idpagos = $_REQUEST['_idpagos'];
                    $array_cadena_CP = explode(",", $_cadena_CP);
                            $count_cadena = count($array_cadena_CP);
                    $json = $data->Add_Validate_importeAnticipos($_origenCE,$_cadena_CP,$_cadena_CP_insolutos,$count_cadena,$_idpagos); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "Validate_importeAnticipos":
                    $_origenCE = $_REQUEST['origen'];
                    $_cadena_CP = $_REQUEST['_cadena_CP'];
                    $_cadena_CP_insolutos = $_REQUEST['_cadena_CP_insolutos'];
                    $array_cadena_CP = explode(",", $_cadena_CP);
                            $count_cadena = count($array_cadena_CP);
                    $json = $data->Validate_importeAnticipos($_origenCE,$_cadena_CP,$_cadena_CP_insolutos,$count_cadena); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "Validate_importePagoLibre":
                    $_origenCE = $_REQUEST['origen'];
                    $total = $_REQUEST['total'];
                    $json = $data->Validate_importePagoLibre($_origenCE,$total); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "get_expenseCategory":
                    $cuenta_id = $_REQUEST['cuenta_id'];
                    $json = $data->getExpenseCategory($cuenta_id);
                    echo json_encode($json);
                break;
            }
        case "save_datas":
            $data = new save_datas;
            switch ($_REQUEST['funcion']) {
                case "insert":
                    $tipoPago = $_REQUEST['tipoPago'];
                    $Comentarios = $_REQUEST['Comentarios'];
                    $total = $_REQUEST['total'];
                    $tipoMovimiento = 1;
                    $ramdon_relacion = $_REQUEST['ramdon_str'];
                    $json = $data->insertPago($tipoPago, $Comentarios, $total, $tipoMovimiento,$ramdon_relacion); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "insert_mov":
                    $_cuenta_pagar_id = $_REQUEST['_destino'];
                    $_tipoMovimiento = $_REQUEST['_tipoMovimiento'];
                    $_Retiro = $_REQUEST['_importe'];
                    $_Descripcion = $_REQUEST['_descripcion'];
                    $_Referencia = $_REQUEST['_referencia'];
                    $_cuenta_origen_id = $_REQUEST['_origen'];
                    $_ramdonstring = $_REQUEST['_ramdon_str'];
                    $json = $data->insertmovi( $_Descripcion, $_Retiro, $_Referencia,$_cuenta_origen_id,$_cuenta_pagar_id,$_ramdonstring); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "insert_all":
                    $_PKuser = $_SESSION["PKUsuario"];
                    $_proveedor = $_REQUEST['_proveedor'];
                    $_referencia = $_REQUEST['_referencia'];
                    $_cuentaCobrar = "";
                    $_cadena_CP = $_REQUEST['_cadena_CP']; 
                        $array_cadena_CP = explode(",", $_cadena_CP);
                            $count_cadena = count($array_cadena_CP);
                    $_tipoPago = $_REQUEST['tipoPago'];
                    $_comentarios = $_REQUEST['Comentarios'];
                    $_total = $_REQUEST['total'];
                    $tipo_movi = $_REQUEST['tipo_movi'];
                    $_origenCE = $_REQUEST['_origenCE'];
                    $_cuentaDest = $_REQUEST['_cuentaDest'];
                    $_fecha_pago = $_REQUEST['_fecha_pago'];
                    $_categoria = $_REQUEST['_categoria'];
                    $_subcategoria = $_REQUEST['_subcategoria'];
                    //echo($_fecha_pago);

                    $json = $data->insertPagoMovimi($_PKuser,$_proveedor,$_referencia,$_cuentaCobrar, $_cadena_CP, $count_cadena,$_tipoPago,$_comentarios,$_total, $tipo_movi, $_origenCE, $_cuentaDest,$_fecha_pago,$_categoria,$_subcategoria); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                break;
                case "insert_pago_libre":
                    $_PKuser = $_SESSION["PKUsuario"];
                    $_proveedor = $_REQUEST['_proveedor'];
                    $_referencia = $_REQUEST['_referencia'];
                    $_cuentaCobrar = "";
                    $_cadena_CP = $_REQUEST['_cadena_CP'];
                    $_tipoPago = $_REQUEST['tipoPago'];
                    $_comentarios = $_REQUEST['Comentarios'];
                    $_total = $_REQUEST['total'];
                    $tipo_movi = $_REQUEST['tipo_movi'];
                    $_origenCE = $_REQUEST['_origenCE'];
                    $_cuentaDest = $_REQUEST['_cuentaDest'];
                    $_fecha_pago = $_REQUEST['_fecha_pago'];
                    $_categoria = $_REQUEST['_categoria'];
                    $_subcategoria = $_REQUEST['_subcategoria'];
                    //echo($_fecha_pago);

                    $json = $data->insertPagoLibre($_PKuser,$_proveedor,$_referencia,$_cuentaCobrar, $_cadena_CP,$_tipoPago,$_comentarios,$_total, $tipo_movi, $_origenCE, $_cuentaDest,$_fecha_pago,$_categoria,$_subcategoria); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                break;
                case "test":
                        $checks = json_decode($_REQUEST['checks'],true);
                        $ArrayAssoc = $_REQUEST['checks'];
                        print_r($checks);
                        foreach($ArrayAssoc as $posicion=>$jugador)
                        {
                        echo "El " . $posicion . " es " . $jugador;
                        echo "<br>";
                        }
                    break;



            }
        case "update_data":
            $data = new Update_datas;
            switch ($_REQUEST['funcion']) {
                case "update_detail":
                    $_PKREsponsable = $_SESSION["PKUsuario"];
                    $_idpagos = $_REQUEST['idpagos'];
                    $_cobroOpago = $_REQUEST['cobroOpago'];
                    $_tipopagoid = $_REQUEST['tipopagoid']; 
                    $_txtTotal = $_REQUEST['txtTotal'];
                    $_txtfecha = $_REQUEST['txtfecha']; 
                   
                    $_proveedorid = $_REQUEST['proveedorid'];
                    $_txtreferencia = $_REQUEST['txtreferencia'];
                    $_stringToInsert = $_REQUEST['stringToInsert'];
                        $array_stringToInsert = explode(",", $_stringToInsert);
                            $_count_cadena_insert = count($array_stringToInsert);
                            if($_stringToInsert==null){
                                $_count_cadena_insert = 0;
                            }
                    $_stringToDelete = $_REQUEST['stringToDelete'];
                        $array_stringToDelete = explode(",", $_stringToDelete);
                            $_count_cadena_delete = count($array_stringToDelete);
                            if($_stringToDelete==null){
                                $_count_cadena_delete = 0;
                            }
                    $_textareaCoemtarios = $_REQUEST['textareaCoemtarios'];
                    $_cuentaid = $_REQUEST['cuentaid'];
                    $_cuentaDest = 300;
                    $_tipo_movimiento = 1;
                   //echo($_idpagos." , ".$_cobroOpago." , ".$_tipopagoid." , ".$_txtTotal." , ".$_txtfecha." , ".$_proveedorid." , ".$_txtreferencia." , ".$_PKREsponsable." , ".$_stringToInsert." , ".$_count_cadena_insert." , ". $_stringToDelete." , ". $_count_cadena_delete." , ".$_textareaCoemtarios." , ".$_cuentaid." , ". $_cuentaDest." , ".$_tipo_movimiento);

                    $json = $data->updateDetails($_idpagos,$_cobroOpago,$_tipopagoid,$_txtTotal, $_txtfecha, $_proveedorid,$_txtreferencia,$_PKREsponsable,$_stringToInsert, $_count_cadena_insert, $_stringToDelete, $_count_cadena_delete,$_textareaCoemtarios,$_cuentaid, $_cuentaDest,$_tipo_movimiento); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                break;
                case "anticipo_update_detail":
                    $_PKREsponsable = $_SESSION["PKUsuario"];
                    $_idpagos = $_REQUEST['idpagos'];
                    $_cobroOpago = $_REQUEST['cobroOpago'];
                    $_tipopagoid = $_REQUEST['tipopagoid']; 
                    $_txtTotal = $_REQUEST['txtTotal'];
                    $_txtfecha = $_REQUEST['txtfecha']; 
                   
                    $_txtreferencia = $_REQUEST['txtreferencia'];
                    $_stringToInsert = $_REQUEST['stringToInsert'];
                        $array_stringToInsert = explode(",", $_stringToInsert);
                            $_count_cadena_insert = count($array_stringToInsert);
                            if($_stringToInsert==null){
                                $_count_cadena_insert = 0;
                            }
                    $_stringToDelete = $_REQUEST['stringToDelete'];
                        $array_stringToDelete = explode(",", $_stringToDelete);
                            $_count_cadena_delete = count($array_stringToDelete);
                            if($_stringToDelete==null){
                                $_count_cadena_delete = 0;
                            }
                    $_stringToUpdate = $_REQUEST['stringToUpdate'];
                    $array__stringToUpdate = explode(",", $_stringToUpdate);
                        $_count_cadena_update = count($array__stringToUpdate);
                        if($_stringToUpdate==null){
                            $_count_cadena_update = 0;
                        }
                    $_textareaCoemtarios = $_REQUEST['textareaCoemtarios'];
                    $_cuentaid = $_REQUEST['cuentaid'];
                    $_cuentaDest = 300;
                    $_tipo_movimiento = 1;
                    $json = $data->Anticipo_updateDetails($_idpagos,$_cobroOpago,$_tipopagoid,$_txtTotal, $_txtfecha,$_txtreferencia,$_PKREsponsable,$_stringToInsert, $_count_cadena_insert, $_stringToDelete, $_count_cadena_delete,$_stringToUpdate,$_count_cadena_update,$_textareaCoemtarios,$_cuentaid, $_cuentaDest,$_tipo_movimiento); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                break;
                case "validateUpdate":
                break;

            }
            case "delete_data": 
                    $data = new Delete_data;
                    switch ($_REQUEST['funcion']){
                            case "delete_pago":

                                $_idpagos = $_REQUEST['idpagos'];
                                $_origen = $_REQUEST['_origen'];
                                $json = $data->deletePago($_idpagos,$_origen); //Guardando el return de la función
                                echo json_encode($json); //Retornando el resultado al ajax
                                break;          
                    }
                break;

    }
}
?>