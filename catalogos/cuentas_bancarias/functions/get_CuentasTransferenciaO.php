<?php
session_start();

if (isset($_POST['id'])) {
    require_once '../../../include/db-conn.php';
    $json = new \stdClass();
    $idCuentaOrigen = $_POST['id'];
    $idempresa = $_SESSION["IDEmpresa"];
    //$idCuentaDestino = $_POST['idDestinoTr'];
    $monedaDestino = "";
    //selecciona la moneda de la cuenta actual
    $stmt = $conn->prepare('SELECT o.PKCuentaOtra,
        o.FKCuenta,
        o.FKMoneda,
        mo.Descripcion as DescripcionMo,
        o.Saldo_Inicial as saldoI,
        cbe.Nombre as nomCuenta
       FROM cuentas_otras as o
       INNER JOIN monedas as mo ON o.FKMoneda=mo.PKMoneda
       INNER JOIN cuentas_bancarias_empresa as cbe ON o.FKCuenta=cbe.PKCuenta WHERE FKCuenta = :id');
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
    $stmt = $conn->prepare('SELECT o.PKCuentaOtra,
              o.FKCuenta,
              o.Saldo_Inicial,
              o.FKMoneda as monedaDestino,
              cbe.Nombre as nombreCuenta
              FROM cuentas_otras as o INNER JOIN cuentas_bancarias_empresa as cbe ON cbe.PKCuenta=o.FKCuenta WHERE o.FKCuenta != :id and cbe.empresa_id = :empresa');
    $stmt->execute(array(':id' => $idCuentaOrigen, ':empresa' => $idempresa));
    $stmt->execute();
    $cuentaR = $stmt->rowCount();

    $row = $stmt->fetchAll();

    $idCuentaDestino = "";

    if ($cuentaR == 0) {
        $listaCuentasDestinatarios = "<option value='0'>No hay cuentas para transferir</option>";
    } else {
        $listaCuentasDestinatarios = "<option value='0'>Elija la cuenta destino...</option>";
        foreach ($row as $b) {
            $monedaDestino = "";
            $listaCuentasDestinatarios .= "<option value='" . $b["FKCuenta"] . "'";
            if ($idCuentaOrigen == $b["PKCuentaOtra"]) {
                $listaCuentasDestinatarios .= " selected";
            }
            $listaCuentasDestinatarios .= ">" . $b['nombreCuenta'] . "</option>";
        }
    }

    $json->cuentasDestinatarios = $listaCuentasDestinatarios;
    $json->idCuentaActualTransfer = $idCuentaOrigen;
    $json->monedaDestin = $monedaDestino;

    $json = json_encode($json);
    echo $json;
}
