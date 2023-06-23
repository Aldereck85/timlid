<?php
require_once('../../../include/db-conn.php');
    
      $json = new \stdClass();

      $idCuenta = $_POST['idDetalle'];

      $stmt = $conn->prepare('SELECT cbe.PKCuenta as id,
      cbe.Nombre as nombre, 
      cbe.estatus as estado, 
      o.Saldo_Inicial as saldoI, 
      "Cuentas Otras" as tipo, 
      cbe.tipo_cuenta as idTipo, 
      "Ninguno" as banco,
      "Caja chica" as nombreBanco, 
      mo.Clave as claveMoneda, 
      mo.PKMoneda from cuentas_bancarias_empresa cbe 
      inner join cuentas_otras o on cbe.PKCuenta = o.FKCuenta 
      inner join monedas mo on o.FKMoneda=mo.PKMoneda 
      WHERE cbe.PKCuenta =:id');
    $stmt->execute(array(':id'=>$idCuenta));
    $stmt->execute();
    $row = $stmt->fetch();

    $saldoEnviar = $row['saldoI'];
    $claveM = $row['claveMoneda'];
    $saldo = " $ ".number_format($row['saldoI'],2);
    $tipoC = $row['tipo'];
    $nombre = $row['nombre'];
    $banco = $row['banco'];
    
    $pkmoneda = $row['PKMoneda'];
      
      $json->saldoG = $saldo;
      $json->tipoCuenta = $tipoC;
      $json->nomCuenta = $nombre;
    
      $json = json_encode($json);
      echo $json;

    
?>