<?php

  require_once('../../../include/db-conn.php');
  $idMov = $_POST['idMovimiento'];
  $idCuenta = $_POST['idCuenta'];
  //select tipo de cuenta
    $stmtC = $conn->prepare('SELECT * FROM  cuentas_bancarias_empresa WHERE PKCuenta = :idCuenta');
    $stmtC->bindValue(':idCuenta',$idCuenta,PDO::PARAM_INT);
    $stmtC->execute();
    $row = $stmtC->fetch();
    $tipoCuenta = $row['tipo_cuenta'];
  //select datos del movimiento actual 
    $stmtM = $conn->prepare('SELECT * FROM  movimientos_cuentas_bancarias_empresa WHERE PKMovimiento = :id');
    $stmtM->bindValue(':id',$idMov,PDO::PARAM_INT);
    $stmtM->execute();
    $row = $stmtM->fetch();
    $idCuentaMovActual = $row['cuenta_origen_id'];
    $archivo = $row['Referencia'];
    $idDestino = $row['cuenta_destino_id'];
    $tipo = $row['tipo_movimiento_id'];
    $retiro = $row['Retiro'];
    $deposito = $row['Deposito'];
    
    $fichero= 'Documentos/'.$archivo;
    
    if($tipo == 7){
     
 
      $stmtR = $conn->prepare('SELECT MAX(PKMovimiento) as anterior from movimientos_cuentas_bancarias_empresa where PKMovimiento <:id' );
      $stmtR->bindValue(':id',$idMov,PDO::PARAM_INT);
      $stmtR->execute();
      $row = $stmtR->fetch();
      $movimientoAnterior = $row['anterior'];
      //selct datos del mov anterior
      $stmtM = $conn->prepare('SELECT 
          Referencia as referencia,
          cuenta_destino_id as cuentaDest,
          cuenta_origen_id as cuentaOrigen,
          Tipo as tipo,
          Fecha as fecha  
      FROM movimientos_cuentas_bancarias_empresa WHERE PKMovimiento = :id');
      $stmtM->bindValue(':id',$movimientoAnterior,PDO::PARAM_INT);
      $stmtM->execute();
      $row = $stmtM->fetch();

      $referenciaA = $row['referencia'];
      $cuentaDestAnterior = $row['cuentaDest'];
      $tipoAnterior = $row['tipo'];  
      
     

      if($idCuentaMovActual == $cuentaDestAnterior && $tipoAnterior == 3){ //el movimiento anterior es una transferencia al mov actual buscado
        $ficheroAct = 'Documentos/'.$archivo;  //eliminar el archivo actual si existe
        
        if($archivo != "" || $archivo != "-"){
          if (file_exists($ficheroAct)) {
            unlink($ficheroAct);
          }
        }
        $ficheroAnt= 'Documentos/'.$referenciaA;  //eliminar el archivo anterior si existe
        if($referenciaA != "" || $referenciaA != "-"){
          if (file_exists($ficheroAnt)) {
            unlink($ficheroAnt);
          }
        }
          
          //selecciona el saldo de la cuenta_caja_chica ACTUAL
          $stmt1 = $conn->prepare('SELECT * FROM  cuentas_otras WHERE FKCuenta = :fkcuenta');
          $stmt1->bindValue(':fkcuenta',$idCuenta,PDO::PARAM_INT);
          $stmt1->execute();
          $rowc = $stmt1->fetch();
          $saldoI = $rowc['Saldo_Inicial'];

          //actualiza saldo de la cuenta actual
          $saldoFinal=$saldoI-$deposito;
          $stmtU = $conn->prepare('UPDATE cuentas_otras SET Saldo_Inicial =:saldoFinal WHERE FKCuenta = :fkcuenta');
          $stmtU->bindValue(':fkcuenta',$idCuenta, PDO::PARAM_INT);
          $stmtU->bindValue(':saldoFinal',$saldoFinal);
          if($stmtU->execute() == true){
            $uCajaActual = true;
          }else{ 
            $uCajaActual = false;
          }
          
          //selciona el saldo de la cuenta destino anterior
          $stmtD = $conn->prepare('SELECT * FROM  cuentas_otras WHERE FKCuenta = :fkcuenta');
          $stmtD->bindValue(':fkcuenta',$cuentaDestAnterior, PDO::PARAM_INT);
          $stmtD->execute();
          $rowDes = $stmtD->fetch();
          $saldoFAnt = $rowDes['Saldo_Inicial'];
          //actualiza el saldo de la cunata anterior
          
          $saldoFinalAnt=$saldoFAnt+$deposito;
          $stmt = $conn->prepare('UPDATE cuentas_otras SET Saldo_Inicial =:saldoFinal WHERE FKCuenta =:fkcuenta');
          $stmt->bindValue(':fkcuenta',$cuentaDestAnterior, PDO::PARAM_INT);
          $stmt->bindValue(':saldoFinal',$saldoFinalAnt);
          if($stmt->execute() == true){
            $uCajaAnterior = true;
          }else{ 
            $uCajaAnterior = false;
          }
        // borrar el movimiento Actual
        $stmt = $conn->prepare('DELETE FROM  movimientos_cuentas_bancarias_empresa WHERE PKMovimiento = :id');
        $stmt->bindValue(':id',$idMov);
        if($stmt->execute()==true){
          $exitoMovAc=true;
        }else{
          $exitoMovAc=false;
        }
        // borrar el movimiento Anterior
        $stmt = $conn->prepare('DELETE FROM  movimientos_cuentas_bancarias_empresa WHERE PKMovimiento = :id');
        $stmt->bindValue(':id',$movimientoAnterior);
        if($stmt->execute()==true){
          $exitoMovAnt=true;
        }else{
          $exitoMovAnt=false;
        }
      }
      if($uCajaActual == true && $uCajaAnterior == true && $exitoMovAc == true && $exitoMovAnt == true){
        echo "1";
      }else{
        echo "0";
      }
    }else{
      if($tipo == 3){ //Busca el siguiente movimiento

        

        $stmtR = $conn->prepare('SELECT MIN(PKMovimiento) as siguiente from movimientos_cuentas_bancarias_empresa where PKMovimiento > :id' );
        $stmtR->bindValue(':id',$idMov,PDO::PARAM_INT);
        $stmtR->execute();
        $row = $stmtR->fetch();
        $movimientoSiguiente = $row['siguiente'];
       
        //selct de los datos del siguiente mov
        $stmtM = $conn->prepare('SELECT
            cuenta_origen_id as idCuentaSig, 
            Referencia as referencia,
            cuenta_destino_id as cuentaDest,
            cuenta_origen_id as cuentaOrigen,
            tipo_movimiento_id as tipo,
            Fecha as fecha  
        FROM movimientos_cuentas_bancarias_empresa WHERE PKMovimiento = :id');
        $stmtM->bindValue(':id',$movimientoSiguiente,PDO::PARAM_INT);
        $stmtM->execute();
        $row = $stmtM->fetch();
        $cuentaSiguiente = $row['idCuentaSig'];
        echo "CuentaSig ". $cuentaSiguiente;
        $referenciaS = $row['referencia'];
        $tipoSiguiente = $row['tipo'];

        if($idDestino == $cuentaSiguiente && $tipoSiguiente == 7){ //el movimiento anterior es una transferencia al mov actual buscado
         
          $ficheroAct = 'Documentos/'.$archivo;  //eliminar el archivo actual si existe
          if($archivo != "" || $archivo != "-"){
            if (file_exists($ficheroAct)) {
              unlink($ficheroAct);
            }
          }
          $ficheroSig= 'Documentos/'.$referenciaS;  //eliminar el archivo siguiente si existe
          if($referenciaS != "" || $referenciaS != "-"){
            if (file_exists($ficheroSig)) {
              unlink($ficheroSig);
            }
          }
          //selecciona el saldo de la cuenta_caja_chica ACTUAL
          $stmt1 = $conn->prepare('SELECT * FROM  cuentas_otras WHERE FKCuenta = :fkcuenta');
          $stmt1->bindValue(':fkcuenta',$idCuenta,PDO::PARAM_INT);
          $stmt1->execute();
          $rowc = $stmt1->fetch();
          $saldoIc = $rowc['Saldo_Inicial'];
          
          //actualiza saldo de la cuenta actual
          $saldoFinal=$saldoIc+$retiro;
          $stmtU = $conn->prepare('UPDATE cuentas_otras SET Saldo_Inicial =:saldoFinal WHERE FKCuenta = :fkcuenta');
          $stmtU->bindValue(':fkcuenta',$idCuenta, PDO::PARAM_INT);
          $stmtU->bindValue(':saldoFinal',$saldoFinal);
          if($stmtU->execute() == true){
            $uCajaActual = true;
          }else{ 
            $uCajaActual = false;
          }
          //selciona el saldo de la cuenta destino anterior
          $stmtD = $conn->prepare('SELECT * FROM  cuentas_otras WHERE FKCuenta = :fkcuenta');
          $stmtD->bindValue(':fkcuenta',$cuentaSiguiente, PDO::PARAM_INT);
          $stmtD->execute();
          $rowDes = $stmtD->fetch();
          $saldoFSig = $rowDes['Saldo_Inicial'];
          //actualiza el saldo de la cunata anterior
          
          $saldoFinalSig=$saldoFSig-$retiro;
          $stmt = $conn->prepare('UPDATE cuentas_otras SET Saldo_Inicial =:saldoFinal WHERE FKCuenta =:fkcuenta');
          $stmt->bindValue(':fkcuenta',$cuentaSiguiente, PDO::PARAM_INT);
          $stmt->bindValue(':saldoFinal',$saldoFinalSig);
          if($stmt->execute() == true){
            $uCajaSiguiente = true;
          }else{ 
            $uCajaSiguiente = false;
          }
          // borrar el movimiento Actual
          $stmt = $conn->prepare('DELETE FROM  movimientos_cuentas_bancarias_empresa WHERE PKMovimiento = :id');
          $stmt->bindValue(':id',$idMov);
          if($stmt->execute()==true){
            $exitoMovAc=true;
          }else{
            $exitoMovAc=false;
          }
          // borrar el movimiento Siguiente
          $stmt = $conn->prepare('DELETE FROM  movimientos_cuentas_bancarias_empresa WHERE PKMovimiento = :id');
          $stmt->bindValue(':id',$movimientoSiguiente);
          if($stmt->execute()==true){
            $exitoMovAnt=true;
          }else{
            $exitoMovAnt=false;
          }

          if($uCajaActual == true && $uCajaSiguiente == true && $exitoMovAc == true && $exitoMovAnt == true){
            echo "1";
          }else{
            echo "0";
          }
        }

      }else{ // no es transferencia ni recepcion de dinero 
        if (file_exists($fichero)) {
          unlink($fichero);
        } else {
         
        }
        //si es un ajuste
        if($tipo == 6 || $tipo == 2){
          
          $stmtD = $conn->prepare('SELECT * FROM  cuentas_otras WHERE FKCuenta = :fkcuenta');
          $stmtD->bindValue(':fkcuenta',$idCuenta, PDO::PARAM_INT);
          $stmtD->execute();
          $rowDes = $stmtD->fetch();
          $saldoAc = $rowDes['Saldo_Inicial'];
          //actualiza el saldo de la cunata anterior
         
          
          $saldoFin=$saldoAc+$retiro;
          $stmt = $conn->prepare('UPDATE cuentas_otras SET Saldo_Inicial =:saldoFinal WHERE FKCuenta =:fkcuenta');
          $stmt->bindValue(':fkcuenta',$idCuenta, PDO::PARAM_INT);
          $stmt->bindValue(':saldoFinal',$saldoFin);
          if($stmt->execute() == true){
            $uCajaA = true;
          }else{ 
            $uCajaA = false;
          }
          $stmt = $conn->prepare('DELETE FROM  movimientos_cuentas_bancarias_empresa WHERE PKMovimiento = :id');
          $stmt->bindValue(':id',$idMov);
          if($stmt->execute()==true){
            $exitoM=true;
          }else{
            $exitoM=false;
          }
          if($exitoM == true && $uCajaA == true){
            echo "1";
          }else{
            echo "0";
          }
        }else{
          if($tipo == 1){
            $stmtD = $conn->prepare('SELECT * FROM  cuentas_otras WHERE FKCuenta = :fkcuenta');
            $stmtD->bindValue(':fkcuenta',$idCuenta, PDO::PARAM_INT);
            $stmtD->execute();
            $rowDes = $stmtD->fetch();
            $saldoAc = $rowDes['Saldo_Inicial'];
            
            //actualiza el saldo de la cunata anterior
           
            $saldoFin=$saldoAc-$deposito;
            $stmt = $conn->prepare('UPDATE cuentas_otras SET Saldo_Inicial =:saldoFinal WHERE FKCuenta =:fkcuenta');
            $stmt->bindValue(':fkcuenta',$idCuenta, PDO::PARAM_INT);
            $stmt->bindValue(':saldoFinal',$saldoFin);
            if($stmt->execute() == true){
              $uCajaA = true;
            }else{ 
              $uCajaA = false;
            }
            $stmt = $conn->prepare('DELETE FROM  movimientos_cuentas_bancarias_empresa WHERE PKMovimiento = :id');
            $stmt->bindValue(':id',$idMov);
            if($stmt->execute()==true){
              $exitoM=true;
            }else{
              $exitoM=false;
            }
            if($exitoM == true && $uCajaA == true){
              echo "1";
            }else{
              echo "0";
            }
  
          }
        }
      }
       
    }

?>