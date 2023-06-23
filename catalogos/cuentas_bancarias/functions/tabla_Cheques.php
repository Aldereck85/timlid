<?php
require_once('../../../include/db-conn.php');
    
      $json = new \stdClass();

      $idCuenta = $_POST['idDetalle'];

      $stmt = $conn->prepare('SELECT cbe.PKCuenta as id,
      cbe.Nombre as nombre,
      cbe.estatus as estado,
      ch.PKCuentasCheque as idCheque,
      ch.Numero_Cuenta as noCuenta,
      ch.FKMoneda as fkMoneda,
      ch.Saldo_Inicial as saldoI,
      ch.FKBanco,
      ch.CLABE,
      "CHEQUES" as tipoCuenta,
      b.Banco as nomBanco,
      mo.Clave as claveMoneda,
      mo.PKMoneda,
      mo.Descripcion as nomMoneda,
      mo.Clave as claveMon
      from cuentas_bancarias_empresa cbe
      inner join cuentas_cheques as ch on cbe.PKCuenta = ch.FKCuenta
      inner join  monedas as mo on ch.FKMoneda=mo.PKMoneda 
      inner join bancos b on b.PKBanco = ch.FKBanco WHERE cbe.PKCuenta =:id');


      $stmt->execute(array(':id'=>$idCuenta));
      $stmt->execute();
      $row = $stmt->fetch();

      $claveMon = $row['claveMon'];
      
      $tipoC = $row['tipoCuenta'];
      $nombre = $row['nombre'];
      $saldoE = $row['saldoI'];
      $noCuenta = $row['noCuenta'];
      $banco = $row['nomBanco'];
      $CLABE = $row['CLABE'];

      $saldo = number_format($row['saldoI'],2)." ".$claveMon;

      $json->saldoG = $saldo;
      $json->tipoCuenta = $tipoC;
      $json->nomCuenta = $nombre;
      $json->noCuenta = $noCuenta;
      $json->clabe = $CLABE;
      $json->banco = $banco;
    
      $json = json_encode($json);
      echo $json;
  
?>