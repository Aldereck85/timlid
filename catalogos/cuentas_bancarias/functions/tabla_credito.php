<?php
require_once '../../../include/db-conn.php';

$json = new \stdClass();

$idDet = $_POST['idDetalle'];

$stmt = $conn->prepare('SELECT cbe.PKCuenta as id,
            cbe.Nombre as nombre,
            cbe.estatus as estado,
            cr.PKCuentaCredito as idCredito,
            cr.Numero_Credito as noCredito,
            cr.FKMoneda as fkMoneda,
            cr.Limite_Credito as limCredito,
            cr.Credito_Utilizado as credUtiliz,
            cr.FKBanco,
            cr.Numero_Credito,
            "CRÉDITO" as tipoCuenta,
            cbe.tipo_cuenta as idTipo,
            b.Banco as nomBanco,
            mo.Clave as claveMoneda,
            mo.PKMoneda,
            mo.Descripcion as nomMoneda,
            mo.Clave as claveMon
        from cuentas_bancarias_empresa cbe
        inner join cuentas_credito cr on cbe.PKCuenta = cr.FKCuenta
        inner join  monedas mo on cr.FKMoneda=mo.PKMoneda
        inner join bancos b on b.PKBanco= cr.FKBanco WHERE cbe.PKCuenta =:id');
$stmt->execute(array(':id' => $idDet));
$row = $stmt->fetch();
$claveMon = $row['claveMon'];
$credUtilizadoE = $row['credUtiliz'];

$credUtilizado = number_format($row['credUtiliz'], 2);
$limCreditoE = $row['limCredito'];
$limCredito = number_format($row['limCredito'], 2);
$tipoCuenta = $row['tipoCuenta'];
$nombre = $row['nombre'];
$noCredito = $row['noCredito'];
$banco = $row['nomBanco'];
$resta = $limCreditoE - $credUtilizadoE; // es una resta del utilizado y el limite
$credDisponible = number_format($resta, 2);

$json->limiteCred = $limCredito;
$json->credUtilizado = $credUtilizado;
$json->credDisponible = $credDisponible;
$json->tipoCuenta = $tipoCuenta;
$json->nombre = $nombre;
$json->noCredito = $noCredito;
$json->banco = $banco;
$json->claveMon = $claveMon;

$json = json_encode($json);
echo $json;