<?php
session_start();
//var_dump($_POST);
if(isset($_POST['idCuentaDestino'])){
  require_once('../../../include/db-conn.php');
    $json = new \stdClass();
    $id = $_POST['idCuentaDestino'];
    $moOrigen = $_POST['moOrigen'];
    
    $idCuentaActual = $_POST['idCuentaActual'];
    $pkTipoCam=0;
    //SELECCION DE LA MONEDA DESTINO de todo tipo de cuentas 
    
    $stmt = $conn->prepare('SELECT id, nombre, monedaDestino, tipoC, monedaDescrip, saldoI from(
      select cbe.PKCuenta as id,
           cbe.Nombre as nombre,
            cc.FKMoneda as monedaDestino,
            cc.Saldo_Inicial as saldoI,
            cbe.tipo_cuenta as tipoC,
            mo.Descripcion as monedaDescrip
      from cuentas_bancarias_empresa cbe
        inner join cuentas_cheques cc on cbe.PKCuenta = cc.FKCuenta
  inner join monedas mo on cc.FKMoneda=mo.PKMoneda
        
      union distinct 
    
      select cbe.PKCuenta as id,
          cbe.Nombre as nombre,
          cr.FKMoneda as monedaDestino,
           cr.Limite_Credito as saldoI,
            cbe.tipo_cuenta as tipoC,
            mo.Descripcion as monedaDescrip
      from cuentas_bancarias_empresa cbe
        inner join cuentas_credito cr on cbe.PKCuenta = cr.FKCuenta
        inner join monedas mo on cr.FKMoneda=mo.PKMoneda
        
      union distinct 
        select cbe.PKCuenta as id,
      cbe.Nombre as nombre,
      co.FKMoneda as monedaDestino,
       co.Saldo_Inicial as saldoI,
      cbe.tipo_cuenta as tipoC,
                mo.Descripcion as monedaDescrip
        from cuentas_bancarias_empresa cbe
          inner join cuentas_otras co on cbe.PKCuenta = co.FKCuenta
          inner join monedas mo on co.FKMoneda=mo.PKMoneda
      union distinct 
        select cbe.PKCuenta as id,
            cbe.Nombre as nombre,
    c.FKMoneda as monedaDestino,
     c.SaldoInicialCaja as saldoI,
    cbe.tipo_cuenta as tipoC,
            mo.Descripcion as monedaDescrip
        from cuentas_bancarias_empresa cbe
          inner join cuenta_caja_chica c on cbe.PKCuenta = c.FKCuenta
          inner join monedas mo on c.FKMoneda=mo.PKMoneda
    )as tabla where id = :id');
    $stmt->execute(array(':id'=>$id));
    $stmt->execute();
    $rowC = $stmt->fetch();
    $monedaACuenta = $rowC['monedaDestino']; //NO BORRAR
    $moDescripcion = $rowC['monedaDescrip'];// NO BORRAR 

    
   
    $tipoC = $rowC['tipoC'];
    $saldoACuenta = $rowC['saldoI'];
    
    //VALOR DEL TIPO DE CAMBIO
    if(($moOrigen == 100) && ($monedaACuenta == 149) ){
      $pkTipoCam = 1;
    }else{
      if(($moOrigen == 149) && ($monedaACuenta == 100)){
        $pkTipoCam = 2;
      }else{
        if(($moOrigen == 100) && ($monedaACuenta == 49)){
          $pkTipoCam = 3;
        }else{
          if(($moOrigen == 49) && ($monedaACuenta == 100)){
            $pkTipoCam = 4;
          }else{
            if(($moOrigen == 149) && ($monedaACuenta == 49)){
              $pkTipoCam = 5;
            }else{
              if(($moOrigen == 49) && ($monedaACuenta == 149)){
                $pkTipoCam = 6;
              }else{
                $pkTipoCam = 0;
              }
            }
          }
        }
      } 
    }
    if($pkTipoCam != 0){
      $stmtC = $conn->prepare('SELECT * FROM tipo_cambio WHERE PKTipoCambio = :pktipocam');
      $stmtC->bindValue(':pktipocam', $pkTipoCam);
      $stmtC->execute();
      $rowss = $stmtC->fetch();
      $valorTC = $rowss['Valor'];

    $json->moACuentaDescrip = $moDescripcion;
    $json->valorTipoCambio = $valorTC;
    }else{

    }
    
    $json->moACuentaDescrip = $moDescripcion;
    $json->saldoACuenta = $saldoACuenta;

    $json->idACuenta = $id; //ID DESTINO
    
    $json->idActualDisposicion = $idCuentaActual; //ID ACTUAL
    $json->moACuenta = $monedaACuenta;

    
    $json->tipoC = $tipoC;
    
    
    $json = json_encode($json);
    echo $json;
    //echo $monedaACuenta;
}
?>
