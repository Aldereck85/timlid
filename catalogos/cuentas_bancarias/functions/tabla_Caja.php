<?php
require_once('../../../include/db-conn.php');
    
      $json = new \stdClass();

      $idCuenta = $_POST['idDetalle'];

      $stmt = $conn->prepare('SELECT cbe.PKCuenta as id,
      cbe.Nombre as nombre,
      cbe.estatus as estado,
      ch.SaldoInicialCaja as saldoI,
      "Caja Chica" as tipo,
      cbe.tipo_cuenta as idTipo,
      "Ninguno" as banco,
      "Caja chica" as nombreBanco,
      mo.Clave as claveMoneda,
      mo.PKMoneda
      from cuentas_bancarias_empresa cbe
      inner join cuenta_caja_chica ch on cbe.PKCuenta = ch.FKCuenta inner join  monedas mo on ch.FKMoneda=mo.PKMoneda WHERE cbe.PKCuenta =:id');
      $stmt->execute(array(':id'=>$idCuenta));
      $stmt->execute();
      $row = $stmt->fetch();
      $claveM = $row['claveMoneda'];
      $saldoEnviar = $row['saldoI'];
      $saldo = " $ ".number_format($row['saldoI'],2)."  ".$claveM;
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