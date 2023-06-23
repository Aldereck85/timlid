<?php
session_start();
//var_dump($_POST);
if (isset($_POST['id']) && isset($_SESSION["IDEmpresa"])) {
    require_once '../../../include/db-conn.php';
    $json = new \stdClass();
    $idCuentaOrigen = $_POST['id'];
    $idempresa = $_SESSION["IDEmpresa"];
    //$idCuentaDestino = $_POST['idDestinoTr'];
    $monedaDestino = "";
    //selecciona la moneda de la cuenta actual
    $stmt = $conn->prepare('SELECT cc.PKCuentaCajaChica,
          cc.FKCuenta,
          cc.FKResponsable,
          cc.FKMoneda,
          mo.Descripcion as DescripcionMo,
          cc.SaldoInicialCaja as saldoI,
          cbe.Nombre as nomCuenta
         FROM cuenta_caja_chica as cc
         INNER JOIN monedas as mo ON cc.FKMoneda=mo.PKMoneda
         INNER JOIN cuentas_bancarias_empresa as cbe ON cc.FKCuenta=cbe.PKCuenta WHERE FKCuenta = :id');
    $stmt->bindValue(':id', $idCuentaOrigen);
    $stmt->execute();
    $rowss = $stmt->fetch();
    //print_r($id);
    $monedaCuentaActual = $rowss['FKMoneda'];

    $json->monedaActual = $monedaCuentaActual;
    $monedaAct = $rowss['DescripcionMo'];
    $saldoI = $rowss['saldoI'];

    $nomCuentaT = $rowss['nomCuenta'];

    $json->saldoI = $saldoI;
    $json->nomCuentaT = $nomCuentaT;
    $json->monedaActualText = $monedaAct;
    //LISTA DE CUENTAS DESTINO
    $stmt = $conn->prepare('SELECT id, nombre, monedaDestino, empresa from(
      select cbe.PKCuenta as id, cbe.Nombre as nombre, c.FKMoneda as monedaDestino, cbe.empresa_id as empresa
      from cuentas_bancarias_empresa cbe
      inner join cuenta_caja_chica c on cbe.PKCuenta = c.FKCuenta
    )as tabla where id != :id and empresa = :empresa order by id asc');
    $stmt->execute(array(':id' => $idCuentaOrigen, ':empresa' => $idempresa));
    $stmt->execute();
    $cuentaR = $stmt->rowCount();

    $row = $stmt->fetchAll();

    if ($cuentaR == 0) {
        $listaCuentasDestinatarios = "<option value='0'>No hay cuentas para transferir</option>";
    } else {
        $listaCuentasDestinatarios = "<option value='0'>Elija la cuenta...</option>";
        foreach ($row as $b) {
            $monedaDestino = "";
            $listaCuentasDestinatarios .= "<option value='" . $b["id"] . "'";
            if ($idCuentaOrigen == $b["id"]) {
                $listaCuentasDestinatarios .= " selected";
            }
            $listaCuentasDestinatarios .= ">" . $b['nombre'] . "</option>";
        }
    }

    $json->cuentasDestinatarios = $listaCuentasDestinatarios;
    $json->idCuentaActualTransfer = $idCuentaOrigen;
    $json->monedaDestin = $monedaDestino;

    $json = json_encode($json);
    echo $json;
}
