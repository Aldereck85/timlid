<?php
session_start();
//var_dump($_POST);
if (isset($_POST['id']) && isset($_SESSION["IDEmpresa"])) {
    require_once '../../../include/db-conn.php';
    $idempresa = $_SESSION["IDEmpresa"];
    $json = new \stdClass();
    $idCuentaOrigen = $_POST['id'];
    $monedaDestino = "";
    //selecciona la moneda de la cuenta actual
    $stmt = $conn->prepare('SELECT cr.PKCuentaCredito,
          cr.FKCuenta,
          cr.FKMoneda,
          cr.Limite_Credito,
          cr.Credito_Utilizado,
          mo.Descripcion as DescripcionMo,
          cbe.Nombre as nomCuenta
      FROM cuentas_credito  cr INNER JOIN monedas  mo ON cr.FKMoneda=mo.PKMoneda INNER JOIN cuentas_bancarias_empresa cbe ON cbe.PKCuenta=cr.FKCuenta WHERE cr.FKCuenta = :id');

    $stmt->bindValue(':id', $idCuentaOrigen);
    $stmt->execute();
    $rowss = $stmt->fetch();
    $monedaCuentaActual = $rowss['FKMoneda'];
    $json->moActual = $monedaCuentaActual;
    $monedaAct = $rowss['DescripcionMo'];
    $creditoUtilizado = $rowss['Credito_Utilizado'];
    $limiteCredito = $rowss['Limite_Credito'];
    $nomCuenta = $rowss['nomCuenta'];
    $saldoDisponible = $limiteCredito - $creditoUtilizado;

    $json->monedaDescripcion = $monedaAct;
    $json->nomCuenta = $nomCuenta;

    // LISTA DE TODAS LAS CUENTAS
    $stmt = $conn->prepare('SELECT id, nombre, monedaDestino, empresa from(
          select cbe.PKCuenta as id, cbe.Nombre as nombre, cc.FKMoneda as monedaDestino, cbe.empresa_id as empresa
          from cuentas_bancarias_empresa cbe
          inner join cuentas_cheques cc on cbe.PKCuenta = cc.FKCuenta
          inner join monedas mo
          union distinct
          select cbe.PKCuenta as id, cbe.Nombre as nombre, cr.FKMoneda as monedaDestino, cbe.empresa_id as empresa
          from cuentas_bancarias_empresa cbe
          inner join cuentas_credito cr on cbe.PKCuenta = cr.FKCuenta
          union distinct
          select cbe.PKCuenta as id, cbe.Nombre as nombre, co.FKMoneda as monedaDestino, cbe.empresa_id as empresa
          from cuentas_bancarias_empresa cbe
          inner join cuentas_otras co on cbe.PKCuenta = co.FKCuenta
          union distinct
          select cbe.PKCuenta as id, cbe.Nombre as nombre, c.FKMoneda as monedaDestino, cbe.empresa_id as empresa
          from cuentas_bancarias_empresa cbe
          inner join cuenta_caja_chica c on cbe.PKCuenta = c.FKCuenta
        )as tabla where id != :id and empresa = :empresa order by id asc');
    $stmt->execute(array(':id' => $idCuentaOrigen, ':empresa' => $idempresa));
    $stmt->execute();
    $cuentaR = $stmt->rowCount();

    $row = $stmt->fetchAll();

    if ($cuentaR == 0) {
        $listaCuentasDisponer = "<option value='0'>No hay cuentas disponibles</option>";
    } else {
        $listaCuentasDisponer = "<option value='0'>Elija la cuenta...</option>";
        foreach ($row as $b) {
            $monedaDestino = $b['monedaDestino'];
            $listaCuentasDisponer .= "<option value='" . $b["id"] . "'";
            if ($idCuentaOrigen == $b["id"]) {
                $listaCuentasDisponer .= " selected";
            }
            $listaCuentasDisponer .= ">" . $b['nombre'] . "</option>";
        }

    }

    $json->listaCuentasDisponer = $listaCuentasDisponer;
    $json->idCuentaAc = $idCuentaOrigen;
    $json->saldoDisponible = $saldoDisponible;

    $json = json_encode($json);
    echo $json;
}
