<?php
session_start();
include_once "clases.php";
$idempresa = $_SESSION["IDEmpresa"];

$array = "";
if (isset($_REQUEST['clase']) && isset($_REQUEST['funcion'])) {
    switch ($_REQUEST['clase']) {

        case "get_data":
            $data = new get_data();
            switch ($_REQUEST['funcion']) {

                case 'get_cajaTableMovimientos':
                    $id = $_REQUEST['data'];
                    $json = $data->getCajaTableMovimeintos($id);
                    echo $json;
                    return;
                    break;
                //OTHERS
                case 'get_otrerTableMovements':
                    $id = $_REQUEST['data'];
                    $json = $data->getOtherTableMovements($id);
                    echo $json;
                    return;
                    break;
                //CREDIT
                case 'get_creditTableMovements':
                    $id = $_REQUEST['data'];
                    $json = $data->getCreditTableMovements($id);
                    echo $json;
                    return;
                    break;
                //CHECKS
                case 'get_checksTableMovements':
                    $id = $_REQUEST['data'];
                    $json = $data->getChecksTableMovements($id);
                    echo $json;
                    return;
                    break;

                case "validar_clabe":
                    $clabe = $_REQUEST['valor'];
                    $fkempresa = $_REQUEST['fkempresa'];
                    //echo json_encode(['clave' => $clabe, 'empresa' => $fkempresa]);
                    $json = $data->validarClabe($clabe, $fkempresa);
                    echo json_encode($json);
                    break;
                case "validar_clabeU":
                    $clabe = $_REQUEST['clave'];
                    $idcuenta = $_REQUEST['idcuenta'];
                    $fkempresa = $_REQUEST['fkempresa'];
                    $json = $data->validarClabeU($clabe, $idcuenta, $fkempresa);
                    echo json_encode($json);
                    break;
                case "validar_no_cuenta":
                    $nocuenta = $_REQUEST['valor'];
                    $fkempresa = $_REQUEST['fkempresa'];
                    $json = $data->validarNoCuenta($nocuenta, $fkempresa);
                    echo json_encode($json);
                    break;
                case "validar_no_cuentaU":
                    $nocuenta = $_REQUEST['nocuenta'];
                    $idcuenta = $_REQUEST['idcuenta'];
                    $fkempresa = $_REQUEST['fkempresa'];
                    $json = $data->validarNoCuentaU($nocuenta, $idcuenta, $fkempresa);
                    echo json_encode($json);
                    break;
                case "validar_no_credito":
                    $nocredito = $_REQUEST['valor'];
                    $fkempresa = $_REQUEST['fkempresa'];
                    $json = $data->validarNoCredito($nocredito, $fkempresa);
                    echo json_encode($json);
                    break;
                case "validar_no_creditoU":
                    $credito = $_REQUEST['credito'];
                    $idcuenta = $_REQUEST['idcuenta'];
                    $fkempresa = $_REQUEST['fkempresa'];
                    $json = $data->validarNoCreditoU($credito, $idcuenta, $fkempresa);
                    echo json_encode($json);
                    break;
                case "validar_identificacor":
                    $identificador = $_REQUEST['valor'];
                    $fkempresa = $_REQUEST['fkempresa'];
                    $json = $data->validarIdentificador($identificador, $fkempresa);
                    echo json_encode($json);
                    break;
                case "validar_identificacorU":
                    $identificador = $_REQUEST['valor'];
                    $idcuenta = $_REQUEST['idcuenta'];
                    $fkempresa = $_REQUEST['fkempresa'];
                    $json = $data->validarIdentificadorU($identificador, $idcuenta, $fkempresa);
                    echo json_encode($json);
                    break;
                case "get_cmb_categorias_gasto":
                    $idemp = $_REQUEST['data'];
                    $json = $data->getCmbCategoriaG($idemp);
                    echo json_encode($json);
                    return;
                    break;
            }
            break;
        //// SAVE DATOS
        case "save_data":
            $data = new save_data();
            switch ($_REQUEST['funcion']) {

                case "agregar_cuenta_cheques":
                    $nombrecuenta = $_REQUEST['data'];
                    $tipocuenta = $_REQUEST['data2'];
                    $empresa = $_REQUEST['data3'];
                    $estado = $_REQUEST['data4'];
                    $banco = $_REQUEST['data5'];
                    $nocuenta = $_REQUEST['data6'];
                    $clabe = $_REQUEST['data7'];
                    $saldo = $_REQUEST['data8'];
                    $moneda = 100;

                    $json = $data->saveChekingAccount($nombrecuenta, $tipocuenta, $empresa, $estado, $banco, $nocuenta, $clabe, $saldo, $moneda); //Guardando el return de la función
                    echo json_encode($json);
                    break;

                case "agregar_cuenta_credito":
                    $nombrecuenta = $_REQUEST['data'];
                    $tipocuenta = $_REQUEST['data2'];
                    $empresa = $_REQUEST['data3'];
                    $estado = $_REQUEST['data4'];
                    $banco = $_REQUEST['data5'];
                    $nocredito = $_REQUEST['data6'];
                    $referencia = $_REQUEST['data7'];
                    $limitecredito = $_REQUEST['data8'];
                    $moneda = 100;

                    $json = $data->saveCreditAccount($nombrecuenta, $tipocuenta, $empresa, $estado, $banco, $nocredito, $referencia, $limitecredito, $moneda); //Guardando el return de la función
                    echo json_encode($json);
                    break;

                case "agregar_cuenta_otras":
                    $nombrecuenta = $_REQUEST['data'];
                    $tipocuenta = $_REQUEST['data2'];
                    $empresa = $_REQUEST['data3'];
                    $estatus = $_REQUEST['data4'];

                    $idcuenta = $_REQUEST['data5'];
                    $descripcion = $_REQUEST['data6'];
                    $saldoinicial = $_REQUEST['data7'];
                    $moneda = 100;

                    $json = $data->saveOtherAccount($nombrecuenta, $tipocuenta, $empresa, $estatus, $idcuenta, $descripcion, $saldoinicial, $moneda); //Guardando el return de la función
                    echo json_encode($json);
                    break;

                case "agregar_cuenta_caja_chica":
                    $nombrecuenta = $_REQUEST['data'];
                    $tipocuenta = $_REQUEST['data2'];
                    $empresa = $_REQUEST['data3'];
                    $estatus = $_REQUEST['data4'];

                    $responsable = $_REQUEST['data5'];
                    $descripcion = $_REQUEST['data6'];
                    $sucursal = $_REQUEST['data7'];
                    $saldoi = $_REQUEST['data8'];
                    $moneda = 100;
                    $idu = $_REQUEST['data10'];

                    $json = $data->saveBoxAccount($nombrecuenta, $tipocuenta, $empresa, $estatus, $responsable, $descripcion, $sucursal, $saldoi, $moneda, $idu);
                    echo json_encode($json);
                    break;

            }
            break;

        case "edit_data":
            $data = new edit_data();
            switch ($_REQUEST['funcion']) {

                case 'editar_cuenta_cheques':
                    $id = $_REQUEST['datos'];
                    $nom = $_REQUEST['datos2'];
                    $noCuenta = $_REQUEST['datos3'];
                    $clabe = $_REQUEST['datos4'];
                    $json = $data->editChekingAccount($id, $nom, $noCuenta, $clabe);
                    echo json_encode($json);
                    return;
                    break;
            }
            break;
    }

}