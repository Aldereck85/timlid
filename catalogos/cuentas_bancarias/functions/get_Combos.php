<?php
session_start();
//var_dump($_POST);
  if(isset($_POST['id'])){
    require_once('../../../include/db-conn.php');
    $json = new \stdClass();
    //print_r("SIII");
    $id = $_POST['id'];
    $stmt = $conn->prepare('SELECT * FROM cuenta_caja_chica WHERE FKCuenta = :id');
    $stmt->execute(array(':id'=>$id));
    $stmt->execute();
    $row = $stmt->fetch();
    $saldoInicialC = $row['SaldoInicialCaja']; 


    $stmt = $conn->prepare('SELECT cc.PKCuentaCajaChica,
    cc.FKCuenta,
    cbe.Nombre,
    mo.Descripcion,
    cc.SaldoInicialCaja
    FROM cuenta_caja_chica as cc INNER JOIN cuentas_bancarias_empresa as cbe  ON cc.FKCuenta=cbe.PKCuenta  INNER JOIN monedas as mo ON cc.FKMoneda=mo.PKMoneda WHERE cc.FKCuenta != :id');
    $stmt->execute(array(':id'=>$id));
    $stmt->execute();
    $row = $stmt->fetchAll();

    $lista = "";
      foreach($row as $d){
      
      $lista .= "<option value='".$d["FKCuenta"]."'";
        $lista .= " selected";
      $lista .= ">".$d['Nombre']."</option>";
      }

    $json->saldoInicialCaja = $saldoInicialC;
    $json->idCajaActual = $id;
    $json->listaO = $lista;

    $json = json_encode($json);
    echo $json;
  }
?>