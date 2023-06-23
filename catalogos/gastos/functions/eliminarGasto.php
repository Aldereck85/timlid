<?php
if(isset($_POST['idGasto'])){
  require_once('../../../include/db-conn.php');
  $stmt = $conn->prepare('SELECT Retiro, cuenta_origen_id FROM movimientos_cuentas_bancarias_empresa WHERE PKMovimiento='.$_POST['idGasto']);
  $stmt->execute();
  $row = $stmt->fetch();
  $retiro = $row['Retiro'];
  $cuenta = $row['cuenta_origen_id'];

  $stmtTC = $conn->prepare('SELECT tipo_cuenta FROM cuentas_bancarias_empresa WHERE PKCuenta ='.$cuenta);
  $stmtTC->execute();
  $rowTC = $stmtTC->fetch();

  switch($rowTC['tipo_cuenta']){
    case 1:
      $stmtC = $conn->prepare('UPDATE cuentas_cheques SET Saldo_Inicial = Saldo_Inicial + :retiro WHERE FKCuenta = :cuenta');
      $stmtC->execute(array($retiro, $cuenta));
    break;
    case 3:
      $stmtC = $conn->prepare('UPDATE cuentas_otras SET Saldo_Inicial = Saldo_Inicial + :retiro WHERE FKCuenta = :cuenta');
      $stmtC->execute(array($retiro, $cuenta));
    break;
    case 4:
      $stmtC = $conn->prepare('UPDATE cuenta_caja_chica SET SaldoInicialCaja = SaldoInicialCaja + :retiro WHERE FKCuenta = :cuenta');
      $stmtC->execute(array($retiro, $cuenta));
    break;
  }
  $stmtE = $conn->prepare('DELETE FROM movimientos_cuentas_bancarias_empresa WHERE PKMovimiento='.$_POST['idGasto']);
  $stmtE->execute();
}
?>