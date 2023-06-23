<?php
session_start();
//var_dump($_POST);
if(isset($_POST['idCuentaDestino'])){
  require_once('../../../include/db-conn.php');
    $json = new \stdClass();
    $id = $_POST['idCuentaDestino'];
    $idCuentaActual = $_POST['idCuentaActual'];
    $moOrigen = $_POST['moOrigen'];
    //selecciona la moneda de la cuenta actual
    $stmt = $conn->prepare('SELECT * FROM cuenta_caja_chica WHERE FKCuenta = :id');
    $stmt->bindValue(':id',$idCuentaActual);
      $stmt->execute();
      $rowss = $stmt->fetch();
      $monedaCuentaActual = $rowss['FKMoneda'];
      $saldoIn = $rowss['SaldoInicialCaja'];
     

    $json->monedaActual = $monedaCuentaActual;
    $json->saldoIn = $saldoIn;
    
    //SELECCION DE LA MONEDA DESTINO
    $stmt = $conn->prepare('SELECT  mo.PKMoneda,
		    mo.Descripcion, 
        cc.FKMoneda,
        cc.PKCuentaCajaChica,
        cc.FKCuenta,
        mo.Clave,
        cc.SaldoInicialCaja
        FROM monedas as mo INNER JOIN cuenta_caja_chica as cc ON mo.PKMoneda=cc.FKMoneda WHERE cc.FKCuenta = :id');
    $stmt->execute(array(':id'=>$id));
    $stmt->execute();
    $rowC = $stmt->fetch();
    $fkMonedaD = $rowC['FKMoneda'];
    $fkMonedaDes = $rowC['Descripcion'];
    $claveMoneda = $rowC['Clave'];
    $saldoDeDestino = $rowC['SaldoInicialCaja'];
    
   
    //VALOR DEL TIPO DE CAMBIO
    if(($moOrigen == 100) && ($fkMonedaD == 149) ){
      $pkTipoCam = 1;
    }else{
      if(($moOrigen == 149) && ($fkMonedaD == 100)){
        $pkTipoCam = 2;
      }else{
        if(($moOrigen == 100) && ($fkMonedaD == 49)){
          $pkTipoCam = 3;
        }else{
          if(($moOrigen == 49) && ($fkMonedaD == 100)){
            $pkTipoCam = 4;
          }else{
            if(($moOrigen == 149) && ($fkMonedaD == 49)){
              $pkTipoCam = 5;
            }else{
              if(($moOrigen == 49) && ($fkMonedaD == 149)){
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

    //$json->moACuentaDescrip = $moDescripcion;
    $json->valorTipoCambio = $valorTC;
    }else{

    }

    $json->idDestinoTr = $id; //ID DESTINO
    $json->idActualTr = $idCuentaActual; //ID ACTUAL
    $json->saldoDest = $saldoDeDestino;
    $json->claveMoneda = $claveMoneda;
    $json->monedaD = $fkMonedaD;
    $json->monedaDes = $fkMonedaDes;
    //$json->monedasDestino = $monedasDestino;
    $json = json_encode($json);
    echo $json;
}
?>
