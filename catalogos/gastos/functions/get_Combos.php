<?php
session_start();
//var_dump($_POST);
  if(isset($_POST['id'])){
    require_once('../../../include/db-conn.php');
    $json = new \stdClass();
    //print_r("SIII");
    $id = $_POST['id'];
    $saldoInicialC = '';
    $stmt = $conn->prepare('SELECT tipo_cuenta FROM cuentas_bancarias_empresa WHERE PKCuenta = :id');
    $stmt->execute(array(':id'=>$id));
    $stmt->execute();
    $row = $stmt->fetch();
    switch($row['tipo_cuenta']){
      case 1:
        $stmt = $conn->prepare('SELECT Saldo_Inicial FROM cuentas_cheques WHERE FKCuenta = :id');
        $stmt->execute(array(':id'=>$id));
        $stmt->execute();
        $row = $stmt->fetch();
        $saldoInicialC = $row['Saldo_Inicial'];
      break;
      case 3:
        $stmt = $conn->prepare('SELECT Saldo_Inicial FROM cuentas_otras WHERE FKCuenta = :id');
        $stmt->execute(array(':id'=>$id));
        $stmt->execute();
        $row = $stmt->fetch();
        $saldoInicialC = $row['Saldo_Inicial'];
      break;
      case 4:
        $stmt = $conn->prepare('SELECT SaldoInicialCaja FROM cuenta_caja_chica WHERE FKCuenta = :id');
        $stmt->execute(array(':id'=>$id));
        $stmt->execute();
        $row = $stmt->fetch();
        $saldoInicialC = $row['SaldoInicialCaja'];
      break;
    } 

    $json->saldoInicialCaja = $saldoInicialC;

    $json = json_encode($json);
    echo $json;
  }
?>