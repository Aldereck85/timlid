<?php
session_start();
//var_dump($_POST);
    if(isset($_POST['idCuentaOrigen'])){
    require_once('../../../include/db-conn.php');
        $json = new \stdClass();
        $id = $_POST['idCuentaOrigen'];
    //ELEGIMOS LA MONEDA DEL ORIGEN DE LA INYECCION
    $stmt = $conn->prepare('SELECT  mo.PKMoneda,
		    mo.Descripcion, 
            cc.FKMoneda,
            cc.PKCuentaCajaChica,
            cc.FKCuenta,
            mo.Clave,
            cc.SaldoInicialCaja
        FROM monedas as mo INNER JOIN cuenta_caja_chica as cc ON mo.PKMoneda=cc.FKMoneda WHERE cc.FKCuenta = :id');
    $stmt->execute(array(':id'=>$id));
    $stmt->execute();
    $rowC = $stmt->fetch();
    $fkMonedaOrigen = $rowC['FKMoneda'];

        $json->monOrgigen = $fkMonedaOrigen;
        $json->idCuentaOrigen = $id;
        
        $json = json_encode($json);
        echo $json;
    }
?>

    