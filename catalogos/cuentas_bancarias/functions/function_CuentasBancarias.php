<?php
session_start();
require_once '../../../include/db-conn.php';
$idempresa = $_SESSION["IDEmpresa"];

$table = "";
$no = 1;
$stmt = $conn->prepare("SELECT id, nombre, saldoI, tipo, idTipo, estado from
  (
    select cbe.PKCuenta as id,
        cbe.Nombre as nombre,
        cbe.estatus as estado,
        cc.Saldo_Inicial as saldoI,
        'Cheques(Bancaria)' as tipo,
            cbe.tipo_cuenta as idTipo
    from cuentas_bancarias_empresa cbe
      inner join cuentas_cheques cc on cbe.PKCuenta = cc.FKCuenta
      where cbe.empresa_id = $idempresa

    union distinct

    select cbe.PKCuenta as id,
        cbe.Nombre as nombre,
        cbe.estatus as estado,
        cc.Limite_Credito as saldoI,
        'Crédito' as tipo,
            cbe.tipo_cuenta as idTipo
    from cuentas_bancarias_empresa cbe
      inner join cuentas_credito cc on cbe.PKCuenta = cc.FKCuenta
      where cbe.empresa_id = $idempresa

    union distinct
      select cbe.PKCuenta as id,
                  cbe.Nombre as nombre,
                  cbe.estatus as estado,
                  co.Saldo_Inicial as saldoI,
          'Otros' as tipo,
            cbe.tipo_cuenta as idTipo
      from cuentas_bancarias_empresa cbe
        inner join cuentas_otras co on cbe.PKCuenta = co.FKCuenta
        where cbe.empresa_id = $idempresa


     union distinct
      select cbe.PKCuenta as id,
                  cbe.Nombre as nombre,
                  cbe.estatus as estado,
                  ch.SaldoInicialCaja as saldoI,
          'CajaChica' as tipo,
            cbe.tipo_cuenta as idTipo
      from cuentas_bancarias_empresa cbe
        inner join cuenta_caja_chica ch on cbe.PKCuenta = ch.FKCuenta
        where cbe.empresa_id = $idempresa


  )as tabla ORDER BY id desc");
//$stmt = $conn->prepare('SELECT * FROM cuentas_bancarias_empresa AS cbe  ON cbe.PKCuenta=cheques.FKCuenta');
$stmt->execute();
/* $res = $stmt->fetch();
echo $res; */
$est = "";
while ($row = $stmt->fetch()) {
    if ($row['estado'] == 1) {
        $est = "Activa";
    } else {
        $est = "Inctiva";
    }
    $saldo = "$" . number_format($row['saldoI'], 2);

    $nombre = '<span class=\"pointer\" onclick=\"irDetalleCuenta(' . $row['id'] . ',' . $row['idTipo'] . ')\">' . $row['nombre'] . '</span>';

    $table .= '{"Estado":"' . $est . '",
      "Tipo":"' . $row['tipo'] . '",
      "Nombre":"' . $nombre . '",
      "Acciones":"<input type=\"hidden\" id=\"hddIdCuenta-' . $row['id'] . '\">",
      "Saldo":"' . $saldo . '"},';
    $no++;
}

$table = substr($table, 0, strlen($table) - 1);
echo '{"data":[' . $table . ']}';